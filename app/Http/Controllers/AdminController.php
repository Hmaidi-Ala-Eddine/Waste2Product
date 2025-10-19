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
     * Display admin dashboard with comprehensive statistics
     */
    public function dashboard()
    {
        // ============ CURRENT PERIOD STATS ============
        $totalUsers = \App\Models\User::count();
        $totalWasteRequests = \App\Models\WasteRequest::count();
        $totalProducts = \App\Models\Product::count();
        $totalCollectors = \App\Models\Collector::where('verification_status', 'verified')->count();
        $totalOrders = \App\Models\Order::count();
        $totalPosts = \App\Models\Post::count();
        
        // ============ GROWTH PERCENTAGES (vs last month) ============
        $lastMonthUsers = \App\Models\User::where('created_at', '<', now()->subMonth())->count();
        $userGrowth = $lastMonthUsers > 0 ? round((($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : 0;
        
        $lastMonthRequests = \App\Models\WasteRequest::where('created_at', '<', now()->subMonth())->count();
        $requestGrowth = $lastMonthRequests > 0 ? round((($totalWasteRequests - $lastMonthRequests) / $lastMonthRequests) * 100, 1) : 0;
        
        $lastMonthProducts = \App\Models\Product::where('created_at', '<', now()->subMonth())->count();
        $productGrowth = $lastMonthProducts > 0 ? round((($totalProducts - $lastMonthProducts) / $lastMonthProducts) * 100, 1) : 0;
        
        $lastMonthOrders = \App\Models\Order::where('created_at', '<', now()->subMonth())->count();
        $orderGrowth = $lastMonthOrders > 0 ? round((($totalOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1) : 0;
        
        // ============ WASTE COLLECTION IMPACT ============
        $totalWasteCollected = \App\Models\WasteRequest::where('status', 'collected')->sum('quantity'); // kg
        $thisMonthCollected = \App\Models\WasteRequest::where('status', 'collected')
                                                      ->whereMonth('collected_at', now()->month)
                                                      ->sum('quantity');
        
        // ============ REVENUE FROM PRODUCTS & ORDERS ============
        $totalRevenue = \App\Models\Product::where('status', 'sold')->sum('price');
        $thisMonthRevenue = \App\Models\Product::where('status', 'sold')
                                               ->whereMonth('updated_at', now()->month)
                                               ->sum('price');
        
        // Order revenue
        $orderRevenue = \App\Models\Order::whereIn('status', ['completed', 'delivered'])->sum('total_price');
        $thisMonthOrderRevenue = \App\Models\Order::whereIn('status', ['completed', 'delivered'])
                                                   ->whereMonth('created_at', now()->month)
                                                   ->sum('total_price');
        
        $combinedRevenue = $totalRevenue + $orderRevenue;
        $thisMonthCombinedRevenue = $thisMonthRevenue + $thisMonthOrderRevenue;
        
        // ============ STATUS BREAKDOWN ============
        $pendingRequests = \App\Models\WasteRequest::where('status', 'pending')->count();
        $acceptedRequests = \App\Models\WasteRequest::where('status', 'accepted')->count();
        $collectedRequests = \App\Models\WasteRequest::where('status', 'collected')->count();
        $cancelledRequests = \App\Models\WasteRequest::where('status', 'cancelled')->count();
        
        // ============ PRODUCT STATUS ============
        $availableProducts = \App\Models\Product::where('status', 'available')->count();
        $soldProducts = \App\Models\Product::where('status', 'sold')->count();
        $donatedProducts = 0; // No longer used
        $reservedProducts = \App\Models\Product::where('status', 'reserved')->count();
        
        // ============ TOP PERFORMERS ============
        // Top 5 collectors by total collections
        $topCollectors = \App\Models\Collector::with('user')
                                              ->where('verification_status', 'verified')
                                              ->orderBy('total_collections', 'desc')
                                              ->take(5)
                                              ->get();
        
        // Top 5 governorates by waste volume
        $topStates = \App\Models\WasteRequest::selectRaw('state, COUNT(*) as total, SUM(quantity) as total_weight')
                                             ->whereNotNull('state')
                                             ->groupBy('state')
                                             ->orderBy('total', 'desc')
                                             ->take(5)
                                             ->get();
        
        // Waste type breakdown
        $wasteTypeBreakdown = \App\Models\WasteRequest::selectRaw('waste_type, COUNT(*) as count')
                                                      ->groupBy('waste_type')
                                                      ->orderBy('count', 'desc')
                                                      ->get();
        
        // ============ TOP PRODUCTS ============
        // Top 5 most sold products
        $topProducts = \App\Models\Product::with('user')
                                          ->where('status', 'sold')
                                          ->orderBy('price', 'desc')
                                          ->take(5)
                                          ->get();
        
        // Products by category
        $productsByCategory = \App\Models\Product::selectRaw('category, COUNT(*) as count')
                                                 ->groupBy('category')
                                                 ->orderBy('count', 'desc')
                                                 ->get();
        
        // ============ ORDER STATISTICS ============
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $processingOrders = \App\Models\Order::where('status', 'processing')->count();
        $completedOrders = \App\Models\Order::where('status', 'completed')->count();
        
        // Recent orders
        $recentOrders = \App\Models\Order::with('buyer')
                                         ->latest()
                                         ->take(5)
                                         ->get();
        
        // ============ USER INSIGHTS ============
        $activeUsers = \App\Models\User::where('is_active', true)->count();
        $newUsersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)->count();
        $usersByRole = \App\Models\User::selectRaw('role, COUNT(*) as count')
                                       ->groupBy('role')
                                       ->get();
        
        // ============ RECENT DATA ============
        $recentRequests = \App\Models\WasteRequest::with(['customer', 'collector'])
                                                   ->latest()
                                                   ->take(5)
                                                   ->get();
        
        $recentActivities = \App\Models\WasteRequest::with('customer')
                                                     ->latest()
                                                     ->take(6)
                                                     ->get();
        
        // ============ CHARTS DATA ============
        // Last 7 days requests
        $requestsPerDay = [];
        $daysLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $daysLabels[] = $date->format('D');
            $count = \App\Models\WasteRequest::whereDate('created_at', $date->format('Y-m-d'))->count();
            $requestsPerDay[] = $count;
        }
        
        // Monthly trend (last 6 months)
        $monthlyTrend = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabels[] = $month->format('M');
            $count = \App\Models\WasteRequest::whereYear('created_at', $month->year)
                                             ->whereMonth('created_at', $month->month)
                                             ->count();
            $monthlyTrend[] = $count;
        }
        
        // ============ CONVERSION RATES ============
        $collectionRate = $totalWasteRequests > 0 ? round(($collectedRequests / $totalWasteRequests) * 100, 1) : 0;
        $productSaleRate = $totalProducts > 0 ? round(($soldProducts / $totalProducts) * 100, 1) : 0;
        
        return view('back.dashboard', compact(
            // Core Stats
            'totalUsers', 'totalWasteRequests', 'totalProducts', 'totalCollectors', 'totalOrders', 'totalPosts',
            // Growth
            'userGrowth', 'requestGrowth', 'productGrowth', 'orderGrowth',
            // Impact
            'totalWasteCollected', 'thisMonthCollected',
            // Revenue
            'totalRevenue', 'thisMonthRevenue', 'orderRevenue', 'thisMonthOrderRevenue', 'combinedRevenue', 'thisMonthCombinedRevenue',
            // Request Status
            'pendingRequests', 'acceptedRequests', 'collectedRequests', 'cancelledRequests',
            // Product Status
            'availableProducts', 'soldProducts', 'donatedProducts', 'reservedProducts',
            // Order Status
            'pendingOrders', 'processingOrders', 'completedOrders',
            // Top Performers
            'topCollectors', 'topStates', 'topProducts',
            // Breakdowns
            'wasteTypeBreakdown', 'productsByCategory', 'usersByRole',
            // Recent Data
            'recentRequests', 'recentActivities', 'recentOrders',
            // Charts
            'requestsPerDay', 'daysLabels', 'monthlyTrend', 'monthLabels',
            // Rates & Insights
            'collectionRate', 'productSaleRate', 'activeUsers', 'newUsersThisMonth'
        ));
    }
    
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
