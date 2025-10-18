<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Display the users management page with search and pagination
     */
    public function users(Request $request)
    {
        $query = User::with('collector');
        
        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }
        
        // Handle role filter
        if ($request->filled('role') && $request->get('role') !== 'all') {
            $query->where('role', $request->get('role'));
        }
        
        // Handle status filter
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->get('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Order and paginate results
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Preserve query parameters in pagination links
        $users->appends($request->query());
        
        return view('back.pages.users', compact('users'));
    }

    /**
     * Store a newly created user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:user,admin,moderator,collector',
            'is_active' => 'boolean',
            'faceid_enabled' => 'boolean',
            'send_welcome_email' => 'boolean',
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
            'name' => $request->name,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'profile_picture' => $profilePicturePath,
            'is_active' => $request->has('is_active'),
            'faceid_enabled' => $request->has('faceid_enabled'),
            'email_verified_at' => now(), // Auto-verify admin created users
        ]);

        // Send welcome email if requested
        if ($request->has('send_welcome_email')) {
            try {
                // You can implement a welcome email here
                // Mail::to($user->email)->send(new WelcomeEmail($user, $request->password));
            } catch (\Exception $e) {
                // Log email error but don't fail the user creation
                \Log::warning('Failed to send welcome email to ' . $user->email . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Check if email is available
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = User::where('email', $request->email)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email is already taken' : 'Email is available'
        ]);
    }

    /**
     * Get user data for editing
     */
    public function getUserData($id)
    {
        $user = User::findOrFail($id);
        $userData = $user->toArray();
        $userData['profile_picture_url'] = $user->profile_picture_url;
        return response()->json($userData);
    }

    /**
     * Update the specified user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:user,admin,moderator,collector',
            'is_active' => 'boolean',
            'faceid_enabled' => 'boolean',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile picture upload
        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'faceid_enabled' => $request->has('faceid_enabled'),
        ];

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }
            
            $file = $request->file('profile_picture');
            $filename = 'profile_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $updateData['profile_picture'] = 'uploads/profiles/' . $filename;
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Delete the specified user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('delete_success', 'User deleted successfully!');
    }

    /**
     * Upload profile picture for logged-in admin
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Delete old profile picture if exists
        if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
            unlink(public_path($user->profile_picture));
        }

        // Upload new profile picture
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);

            $user->profile_picture = 'uploads/profiles/' . $filename;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully!',
            'picture_url' => $user->profile_picture_url
        ]);
    }
}
