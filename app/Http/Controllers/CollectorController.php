<?php

namespace App\Http\Controllers;

use App\Models\Collector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Validators\CollectorValidator;

class CollectorController extends Controller
{
    /**
     * Display a listing of collectors with search and filtering (Admin)
     */
    public function index(Request $request)
    {
        $query = Collector::with(['user']);
        
        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('vehicle_type', 'LIKE', "%{$search}%")
                  ->orWhere('bio', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Handle verification status filter
        if ($request->filled('verification_status') && $request->get('verification_status') !== 'all') {
            $query->where('verification_status', $request->get('verification_status'));
        }
        
        // Handle vehicle type filter
        if ($request->filled('vehicle_type') && $request->get('vehicle_type') !== 'all') {
            $query->where('vehicle_type', $request->get('vehicle_type'));
        }
        
        // Order and paginate results (fixed 10 per page)
        $collectors = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Preserve query parameters in pagination links
        $collectors->appends($request->query());
        
        return view('back.pages.collectors', compact('collectors'));
    }

    /**
     * Store a newly created collector (Admin)
     */
    public function store(Request $request)
    {
        // Sanitize and validate via centralized validator
        $data = CollectorValidator::sanitize($request->all());
        $validator = CollectorValidator::validateStore($data);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Check if user already has a collector profile (one per user)
        if (Collector::where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'You already have a collector profile.');
        }

        // Admin creates collector profile for themselves - never on behalf of others
        $collector = Collector::create([
            'user_id' => auth()->id(),
            'company_name' => $data['company_name'] ?? null,
            'vehicle_type' => $data['vehicle_type'],
            'service_areas' => $data['service_areas'] ?? [],
            'capacity_kg' => $data['capacity_kg'],
            'availability_schedule' => $data['availability_schedule'] ?? [],
            'verification_status' => 'pending',
            'bio' => $data['bio'] ?? null,
        ]);

        return redirect()->route('admin.collectors')->with('success', 'Collector created successfully!');
    }

    /**
     * Get collector data for editing (Admin)
     */
    public function getData($id)
    {
        $collector = Collector::with(['user'])->findOrFail($id);
        return response()->json($collector);
    }

    /**
     * Update the specified collector (Admin)
     */
    public function update(Request $request, $id)
    {
        $collector = Collector::findOrFail($id);
        
        // Sanitize and validate via centralized validator
        $data = CollectorValidator::sanitize($request->all(), true);
        $validator = CollectorValidator::validateUpdate($data, $collector);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Ownership cannot be transferred - user_id stays with original creator
        // Admin can update profile details and verification status
        $collector->update([
            'company_name' => $data['company_name'] ?? null,
            'vehicle_type' => $data['vehicle_type'],
            'service_areas' => $data['service_areas'] ?? [],
            'capacity_kg' => $data['capacity_kg'],
            'availability_schedule' => $data['availability_schedule'] ?? [],
            'verification_status' => $data['verification_status'],
            'bio' => $data['bio'] ?? null,
        ]);

        return redirect()->route('admin.collectors')->with('success', 'Collector updated successfully!');
    }

    /**
     * Delete the specified collector (Admin)
     */
    public function destroy($id)
    {
        $collector = Collector::findOrFail($id);
        $collector->delete();

        return redirect()->route('admin.collectors')->with('delete_success', 'Collector deleted successfully!');
    }

    /**
     * Update collector verification status (Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $collector = Collector::findOrFail($id);
        
        // Validate through central validator
        $validator = CollectorValidator::validateStatus($request->all());
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $collector->update([
            'verification_status' => $request->verification_status,
        ]);

        return response()->json(['success' => 'Verification status updated successfully!']);
    }

    /**
     * Get users for dropdown (Admin)
     */
    public function getUsers()
    {
        $users = User::where('role', '!=', 'admin')
                    ->where('is_active', true)
                    ->whereDoesntHave('collector') // Users without collector profile
                    ->select('id', 'name', 'email')
                    ->get();
        
        return response()->json($users);
    }

    /**
     * Frontend: Display collector application form and user's collector profile
     */
    public function frontendIndex(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('front.login')->with('error', 'Please login to access collector application.');
        }

        $user = auth()->user();
        
        // Check if user already has a collector profile
        $collector = Collector::where('user_id', $user->id)->with('user')->first();

        return view('front.pages.collector-application', compact('collector'));
    }

    /**
     * Frontend: Submit collector application from logged-in user
     */
    public function frontendStore(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('front.login')->with('error', 'Please login to apply as a collector.');
        }

        $user = auth()->user();

        // Check if user already has a collector profile
        if (Collector::where('user_id', $user->id)->exists()) {
            return redirect()->route('front.collector-application')
                            ->with('error', 'You already have a collector application.');
        }

        // Validate the request
        $validator = CollectorValidator::validateFrontendApplication($request->all(), $user->id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Parse service areas (could be comma-separated string or array)
        $serviceAreas = $request->service_areas;
        if (is_string($serviceAreas)) {
            $serviceAreas = array_map('trim', explode(',', $serviceAreas));
            $serviceAreas = array_filter($serviceAreas);
        }

        // Create the collector profile
        Collector::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'vehicle_type' => $request->vehicle_type,
            'service_areas' => $serviceAreas,
            'capacity_kg' => $request->capacity_kg,
            'availability_schedule' => $request->availability_schedule ?? [],
            'verification_status' => 'pending',
            'bio' => $request->bio,
        ]);

        return redirect()->route('front.collector-application')
                        ->with('success', 'Your collector application has been submitted successfully! We will review it and get back to you soon.');
    }

    /**
     * Frontend: Update collector profile
     */
    public function frontendUpdate(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('front.login')->with('error', 'Please login to update your profile.');
        }

        $user = auth()->user();
        $collector = Collector::where('user_id', $user->id)->firstOrFail();

        // Validate the request
        $validator = CollectorValidator::validateFrontendApplication($request->all(), $user->id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Parse service areas
        $serviceAreas = $request->service_areas;
        if (is_string($serviceAreas)) {
            $serviceAreas = array_map('trim', explode(',', $serviceAreas));
            $serviceAreas = array_filter($serviceAreas);
        }

        // Update the collector profile (but keep verification_status as is)
        $collector->update([
            'company_name' => $request->company_name,
            'vehicle_type' => $request->vehicle_type,
            'service_areas' => $serviceAreas,
            'capacity_kg' => $request->capacity_kg,
            'availability_schedule' => $request->availability_schedule ?? [],
            'bio' => $request->bio,
        ]);

        return redirect()->route('front.collector-application')
                        ->with('success', 'Your collector profile has been updated successfully!');
    }

    /**
     * Frontend: Display collector dashboard with available requests
     */
    public function collectorDashboard(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('front.login')->with('error', 'Please login to access the collector dashboard.');
        }

        $user = auth()->user();

        // Check if user is a verified collector
        if (!$user->isVerifiedCollector()) {
            return redirect()->route('front.collector-application')
                            ->with('error', 'You need to be a verified collector to access the dashboard.');
        }

        // Get available waste requests (pending or unassigned)
        // Filter by collector's service areas
        $query = \App\Models\WasteRequest::with(['customer'])
            ->where('status', 'pending')
            ->whereNull('collector_id');

        // Only show requests in collector's service areas
        if ($user->collector && $user->collector->service_areas) {
            $serviceAreas = is_array($user->collector->service_areas) 
                ? $user->collector->service_areas 
                : json_decode($user->collector->service_areas, true);
            
            if (!empty($serviceAreas)) {
                $query->whereIn('state', $serviceAreas);
            }
        }

        // Apply governorate filter
        if ($request->filled('governorate') && $request->get('governorate') !== 'all') {
            $query->where('state', $request->get('governorate'));
        }

        // Apply waste type filter
        if ($request->filled('waste_type') && $request->get('waste_type') !== 'all') {
            $query->where('waste_type', $request->get('waste_type'));
        }

        $availableRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        $availableRequests->appends($request->query());

        // Get collector's assigned requests
        $myRequests = \App\Models\WasteRequest::with(['customer'])
            ->where('collector_id', $user->id)
            ->whereIn('status', ['accepted', 'collected'])
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Increased from 5 to 15

        // ============ PERFORMANCE STATISTICS ============
        $collector = $user->collector;
        
        // Total collections
        $totalCollections = $collector->total_collections ?? 0;
        
        // This month collections
        $thisMonthCollections = \App\Models\WasteRequest::where('collector_id', $user->id)
            ->whereMonth('collected_at', now()->month)
            ->whereYear('collected_at', now()->year)
            ->count();
        
        // Total waste collected (kg)
        $totalWasteCollected = \App\Models\WasteRequest::where('collector_id', $user->id)
            ->where('status', 'collected')
            ->sum('quantity');
        
        // Success rate (collected vs accepted)
        $acceptedCount = \App\Models\WasteRequest::where('collector_id', $user->id)
            ->whereIn('status', ['accepted', 'collected'])
            ->count();
        $collectedCount = \App\Models\WasteRequest::where('collector_id', $user->id)
            ->where('status', 'collected')
            ->count();
        $successRate = $acceptedCount > 0 ? round(($collectedCount / $acceptedCount) * 100, 1) : 0;
        
        // Average rating
        $averageRating = $collector->rating ?? 0;
        
        // Environmental impact calculations
        $co2Saved = round($totalWasteCollected * 0.5, 2); // Approx 0.5 kg CO2 saved per kg waste
        $treesSaved = round($totalWasteCollected / 20, 1); // Approx 1 tree per 20kg waste
        
        // ============ LEADERBOARD DATA ============
        // Get all verified collectors with their stats
        $leaderboard = Collector::with('user')
            ->where('verification_status', 'verified')
            ->orderBy('total_collections', 'desc')
            ->take(10)
            ->get()
            ->map(function($c, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $c->id,
                    'name' => $c->user->name ?? 'Unknown',
                    'total_collections' => $c->total_collections ?? 0,
                    'rating' => $c->rating ?? 0,
                    'company_name' => $c->company_name,
                ];
            });
        
        // Find current collector's rank
        $allCollectors = Collector::where('verification_status', 'verified')
            ->orderBy('total_collections', 'desc')
            ->pluck('id')
            ->toArray();
        $myRank = array_search($collector->id, $allCollectors) + 1;
        
        // Growth vs last month
        $lastMonthCollections = \App\Models\WasteRequest::where('collector_id', $user->id)
            ->whereMonth('collected_at', now()->subMonth()->month)
            ->whereYear('collected_at', now()->subMonth()->year)
            ->count();
        $growthPercentage = $lastMonthCollections > 0 
            ? round((($thisMonthCollections - $lastMonthCollections) / $lastMonthCollections) * 100, 1) 
            : 0;

        return view('front.pages.collector-dashboard', compact(
            'availableRequests', 
            'myRequests',
            'totalCollections',
            'thisMonthCollections',
            'totalWasteCollected',
            'successRate',
            'averageRating',
            'co2Saved',
            'treesSaved',
            'leaderboard',
            'myRank',
            'growthPercentage'
        ));
    }

    /**
     * Frontend: Accept a waste collection request
     */
    public function acceptRequest($requestId)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login to accept requests.'], 401);
        }

        $user = auth()->user();

        // Check if user is a verified collector
        if (!$user->isVerifiedCollector()) {
            return response()->json(['error' => 'You need to be a verified collector to accept requests.'], 403);
        }

        // Get the waste request
        $wasteRequest = \App\Models\WasteRequest::find($requestId);

        if (!$wasteRequest) {
            return response()->json(['error' => 'Request not found.'], 404);
        }

        // Check if request is still available
        if ($wasteRequest->status !== 'pending' || $wasteRequest->collector_id !== null) {
            return response()->json(['error' => 'This request is no longer available.'], 400);
        }

        // Assign the collector and update status
        $wasteRequest->update([
            'collector_id' => $user->id,
            'status' => 'accepted',
        ]);

        // Increment total collections for collector
        $user->collector->increment('total_collections');

        return response()->json([
            'success' => true,
            'message' => 'Request accepted successfully! You can now proceed with the collection.',
        ]);
    }

    /**
     * Frontend: Complete a collection
     */
    public function completeCollection($requestId)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login.'], 401);
        }

        $user = auth()->user();

        // Get the waste request
        $wasteRequest = \App\Models\WasteRequest::find($requestId);

        if (!$wasteRequest) {
            return response()->json(['error' => 'Request not found.'], 404);
        }

        // Check if this collector owns this request
        if ($wasteRequest->collector_id !== $user->id) {
            return response()->json(['error' => 'You are not assigned to this request.'], 403);
        }

        // Check if request is in correct status
        if ($wasteRequest->status !== 'accepted') {
            return response()->json(['error' => 'This request cannot be completed.'], 400);
        }

        // Mark as collected
        $wasteRequest->update([
            'status' => 'collected',
            'collected_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Collection marked as complete!',
        ]);
    }
}
