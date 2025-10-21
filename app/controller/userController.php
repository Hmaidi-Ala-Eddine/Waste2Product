<?php

namespace App\controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class UserController extends BaseController
{
    /**
     * Verify Google reCAPTCHA response token (v2 Invisible).
     */
    private function verifyRecaptcha(Request $request): bool
    {
        $token = $request->input('g-recaptcha-response');
        $secret = config('services.recaptcha.secret_key');
        if (!$secret || !$token) {
            return false;
        }

        try {
            $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);
            return $resp->ok() && ($resp->json('success') === true);
        } catch (\Throwable $e) {
            logger()->warning('reCAPTCHA verify failed: '.$e->getMessage());
            return false;
        }
    }
    /**
     * Display a paginated list of users.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);

        $users = User::select(
            'id', 'name', 'username', 'first_name', 'last_name', 'email', 'phone', 'role',
            'is_active', 'faceid_enabled', 'created_at', 'updated_at'
        )->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Display a single user by id.
     */
    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Create a new user.
     */
    public function store(Request $request)
    {
        // reCAPTCHA check first
        if (!$this->verifyRecaptcha($request)) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'faceid_enabled' => 'nullable|boolean',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $profilePicturePath = 'uploads/profiles/' . $filename;
        }

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => $data['role'] ?? 'user',
            'address' => $data['address'] ?? null,
            'profile_picture' => $profilePicturePath,
            'faceid_enabled' => $data['faceid_enabled'] ?? false,
            'is_active' => false, // mark new accounts inactive until email verification
        ]);

        // Generate email verification token and send email
        $token = Str::random(60);
        $user->email_verification_token = hash('sha256', $token);
        $user->email_verification_sent_at = Carbon::now();
        $user->save();

        // Build verification URL
        $verifyUrl = url('/verify-email?token=' . $token . '&email=' . urlencode($user->email));

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user, $verifyUrl));
        } catch (\Throwable $e) {
            // Mail failure should not crash account creation in this dev flow; log and continue
            logger()->error('Failed to send verification email: ' . $e->getMessage());

            // Also log the verification URL so developers can complete verification when SMTP fails
            $msg = sprintf("[ %s ] Verification URL for %s: %s", now()->toDateTimeString(), $user->email, $verifyUrl);
            logger()->info($msg);

            try {
                // Append URL to a dedicated file inside storage/logs for easy access
                $file = storage_path('logs/verification_urls.log');
                file_put_contents($file, $msg . PHP_EOL, FILE_APPEND | LOCK_EX);
            } catch (\Throwable $_) {
                // ignore file write failures
            }
        }

        if ($request->wantsJson()) {
            return response()->json($user, 201);
        }

        // If the request came from the front login/signup page, redirect back there
        $fromFront = $request->input('from') === 'front' || str_contains((string) $request->headers->get('referer'), '/login');
        if ($fromFront) {
            return redirect()->route('front.login')->with(['success' => 'Account created. Please sign in.', 'show' => 'signin']);
        }

        return redirect()->route('admin.sign-in')->with('success', 'Account created. Please sign in.');
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|nullable|string|max:50|unique:users,username,' . $id,
            'first_name' => 'sometimes|nullable|string|max:100',
            'last_name' => 'sometimes|nullable|string|max:100',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $id,
            'phone' => 'sometimes|nullable|string|max:30',
            'password' => 'sometimes|nullable|string|min:6',
            'role' => 'sometimes|nullable|string|max:50',
            'address' => 'sometimes|nullable|string',
            'is_active' => 'sometimes|boolean',
            'faceid_enabled' => 'sometimes|boolean',
        ]);

        if (isset($data['password']) && $data['password']) {
            $user->password = $data['password'];
        }

        foreach (['name','username','first_name','last_name','email','phone','role','address','is_active','faceid_enabled'] as $field) {
            if (array_key_exists($field, $data)) {
                $user->{$field} = $data[$field];
            }
        }

        $user->save();

        return response()->json($user);
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    /**
     * Login user and generate a simple token stored in jwt_token field.
     * This is NOT a full JWT implementation; it's a quick token stored in DB.
     */
    public function login(Request $request)
    {
        // reCAPTCHA check first
        if (!$this->verifyRecaptcha($request)) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $remember = (bool) $request->input('remember', false);

        // More reliable: find user and validate password explicitly, then log in.
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! \Illuminate\Support\Facades\Hash::check($data['password'], $user->password)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        // If account is not active, reject login for both front and back
        if (! isset($user->is_active) || ! $user->is_active) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Account not active. Please verify your email.'], 403);
            }

            return redirect()->back()->withErrors(['email' => 'Account is not active. Please verify your email.'])->withInput();
        }

        // Log the user in and regenerate session to prevent fixation
        Auth::login($user, $remember);
        $request->session()->regenerate();

    // generate a random token and set an expiration
        $token = hash('sha256', Str::random(80));
        $user->jwt_token = $token;
        $user->jwt_expires_at = Carbon::now()->addHours(8);
        $user->last_login_at = Carbon::now();
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'token' => $token,
                'expires_at' => $user->jwt_expires_at,
                'user' => $user->only(['id','name','email','username','role','is_active','faceid_enabled']),
            ]);
        }

    // If this is an admin sign-in attempt, ensure the user has admin role
    $route = $request->route();
    $routeName = $route ? $route->getName() : null;
    $isAdminAttempt = ($routeName === 'admin.sign-in.post' || $routeName === 'admin.sign-in' || str_starts_with($request->path(), 'admin'));
        if ($isAdminAttempt && (! isset($user->role) || $user->role !== 'admin')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->wantsJson()) {
                return response()->json(['message' => 'User is not authorized to access admin area'], 403);
            }

            return redirect()->back()->withErrors(['email' => 'You are not authorized to access the admin area'])->withInput();
        }

        // If login came from front login page, redirect to home
        $fromFront = $request->input('from') === 'front' || str_contains((string) $request->headers->get('referer'), '/login');
        if ($fromFront) {
            return redirect('/')->with('success', 'Signed in successfully');
        }

        return redirect()->route('admin.dashboard')->with('success', 'Signed in successfully');
    }

    /**
     * Verify email token and activate account.
     */
    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (! $token || ! $email) {
            return redirect()->route('front.login')->withErrors(['email' => 'Invalid verification link']);
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->route('front.login')->withErrors(['email' => 'User not found']);
        }

        if (! $user->email_verification_token) {
            return redirect()->route('front.login')->with('success', 'Account already verified');
        }

        if (hash('sha256', $token) !== $user->email_verification_token) {
            return redirect()->route('front.login')->withErrors(['email' => 'Invalid verification token']);
        }

        $user->is_active = true;
        $user->email_verification_token = null;
        $user->email_verification_sent_at = Carbon::now();
        $user->save();

        return redirect()->route('front.login')->with('success', 'Email verified. You can now sign in.');
    }

    /**
     * Logout the current user and redirect to home page.
     */
    public function logout(Request $request)
    {
        // Clear the JWT token from the user record
        $user = Auth::user();
        if ($user) {
            $user->jwt_token = null;
            $user->jwt_expires_at = null;
            $user->save();
        }

        // Logout the user from Laravel's session
        Auth::logout();

        // Invalidate the session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to home page with success message
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
