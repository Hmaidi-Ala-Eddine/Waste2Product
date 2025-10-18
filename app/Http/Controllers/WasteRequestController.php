<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Validators\WasteRequestValidator;

class WasteRequestController extends Controller
{
    /**
     * Display a listing of waste requests with search and filtering
     */
    public function index(Request $request)
    {
        $query = WasteRequest::with(['customer', 'collector']);
        
        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('waste_type', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'LIKE', "%{$search}%")
                                  ->orWhere('email', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('collector', function($collectorQuery) use ($search) {
                      $collectorQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Handle status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('status', $request->get('status'));
        }
        
        // Handle waste type filter
        if ($request->filled('waste_type') && $request->get('waste_type') !== 'all') {
            $query->where('waste_type', $request->get('waste_type'));
        }
        
        // Handle collector filter
        if ($request->filled('collector_id') && $request->get('collector_id') !== 'all') {
            if ($request->get('collector_id') === 'unassigned') {
                $query->whereNull('collector_id');
            } else {
                $query->where('collector_id', $request->get('collector_id'));
            }
        }
        
        // Order and paginate results (fixed 5 per page)
        $wasteRequests = $query->orderBy('created_at', 'desc')->paginate(5);
        
        // Preserve query parameters in pagination links
        $wasteRequests->appends($request->query());
        
        // Get collectors for filter dropdown
        $collectors = User::where('role', 'collector')->where('is_active', true)->get();
        
        return view('back.pages.waste-requests', compact('wasteRequests', 'collectors'));
    }

    /**
     * Store a newly created waste request
     */
    public function store(Request $request)
    {
        // Sanitize and validate via centralized validator
        $data = WasteRequestValidator::sanitize($request->all());
        $validator = WasteRequestValidator::validateStore($data);
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

        // Admin creates waste requests for themselves - never on behalf of others
        $wasteRequest = WasteRequest::create([
            'user_id' => auth()->id(),
            'collector_id' => $data['collector_id'] ?? null, // Optional: can assign during creation or later
            'waste_type' => $data['waste_type'],
            'quantity' => $data['quantity'],
            'state' => $data['state'] ?? null,
            'address' => $data['address'],
            'description' => $data['description'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.waste-requests')->with('success', 'Waste request created successfully!');
    }

    /**
     * Get waste request data for editing
     */
    public function getData($id)
    {
        $wasteRequest = WasteRequest::with(['customer', 'collector'])->findOrFail($id);
        return response()->json($wasteRequest);
    }

    /**
     * Update the specified waste request
     */
    public function update(Request $request, $id)
    {
        $wasteRequest = WasteRequest::findOrFail($id);
        
        // Sanitize and validate via centralized validator
        $data = WasteRequestValidator::sanitize($request->all(), true);
        $validator = WasteRequestValidator::validateUpdate($data, $wasteRequest);
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

        // Set collected_at timestamp when status changes to collected
        $collectedAt = $wasteRequest->collected_at;
        if ($data['status'] === 'collected' && $wasteRequest->status !== 'collected') {
            $collectedAt = now();
        } elseif ($data['status'] !== 'collected') {
            $collectedAt = null;
        }

        // Ownership cannot be transferred - user_id stays with original creator
        // Admin can update fields and assign collectors
        $wasteRequest->update([
            'collector_id' => $data['collector_id'] ?? null,
            'waste_type' => $data['waste_type'],
            'quantity' => $data['quantity'],
            'state' => $data['state'] ?? $wasteRequest->state,
            'address' => $data['address'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'collected_at' => $collectedAt,
        ]);

        return redirect()->route('admin.waste-requests')->with('success', 'Waste request updated successfully!');
    }

    /**
     * Delete the specified waste request
     */
    public function destroy($id)
    {
        $wasteRequest = WasteRequest::findOrFail($id);
        $wasteRequest->delete();

        return redirect()->route('admin.waste-requests')->with('delete_success', 'Waste request deleted successfully!');
    }

    /**
     * Assign collector to waste request
     */
    public function assignCollector(Request $request, $id)
    {
        $wasteRequest = WasteRequest::findOrFail($id);
        
        // Validate through central validator
        $validator = WasteRequestValidator::validateAssign($request->all());
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $wasteRequest->update([
            'collector_id' => $request->collector_id,
            'status' => 'accepted',
        ]);

        return response()->json(['success' => 'Collector assigned successfully!']);
    }

    /**
     * Update waste request status
     */
    public function updateStatus(Request $request, $id)
    {
        $wasteRequest = WasteRequest::findOrFail($id);
        
        // Validate through central validator
        $validator = WasteRequestValidator::validateStatus($request->all(), $wasteRequest);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $collectedAt = $wasteRequest->collected_at;
        if ($request->status === 'collected' && $wasteRequest->status !== 'collected') {
            $collectedAt = now();
        } elseif ($request->status !== 'collected') {
            $collectedAt = null;
        }

        $wasteRequest->update([
            'status' => $request->status,
            'collected_at' => $collectedAt,
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }

    /**
     * Get customers for dropdown
     */
    public function getCustomers()
    {
        $customers = User::where('role', '!=', 'admin')
                        ->where('is_active', true)
                        ->select('id', 'name', 'email')
                        ->get();
        
        return response()->json($customers);
    }

    /**
     * Get collectors for dropdown (verified collectors only)
     */
    public function getCollectors()
    {
        $collectors = \App\Models\Collector::with('user')
                         ->where('verification_status', 'verified')
                         ->whereHas('user', function($query) {
                             $query->where('is_active', true);
                         })
                         ->get()
                         ->map(function($collector) {
                             return [
                                 'id' => $collector->user_id,
                                 'name' => $collector->user->name,
                                 'email' => $collector->user->email,
                                 'service_areas' => $collector->service_areas,
                             ];
                         });
        
        return response()->json($collectors);
    }

    /**
     * Frontend: Display user's waste requests
     */
    public function frontendIndex(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('front.login')->with('error', 'Please login to access waste requests.');
        }

        $user = auth()->user();
        
        // Get user's waste requests with pagination
        $wasteRequests = WasteRequest::with(['collector', 'rating'])
                                   ->where('user_id', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('front.pages.waste-requests', compact('wasteRequests'));
    }

    /**
     * Frontend: Store a new waste request from logged-in user
     */
    public function frontendStore(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('front.login')->with('error', 'Please login to submit waste requests.');
        }

        $user = auth()->user();

        // Validate the request
        $request->validate([
            'waste_type' => 'required|in:' . implode(',', array_keys(\App\Models\WasteRequest::getWasteTypes())),
            'quantity' => 'required|numeric|min:0.01|max:10000',
            'state' => 'required|in:' . implode(',', \App\Helpers\TunisiaStates::getStateValues()),
            'address' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        // Create the waste request
        WasteRequest::create([
            'user_id' => $user->id,
            'waste_type' => $request->waste_type,
            'quantity' => $request->quantity,
            'state' => $request->state,
            'address' => $request->address,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('front.waste-requests')
                        ->with('success', 'Your waste collection request has been submitted successfully! We will contact you soon.');
    }

    /**
     * Frontend: Submit rating for a collector
     */
    public function submitRating(Request $request, $wasteRequestId)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login to rate collectors.'], 401);
        }

        $user = auth()->user();

        // Validate rating
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        // Get the waste request
        $wasteRequest = \App\Models\WasteRequest::find($wasteRequestId);

        if (!$wasteRequest) {
            return response()->json(['error' => 'Request not found.'], 404);
        }

        // Check if user owns this request
        if ($wasteRequest->user_id !== $user->id) {
            return response()->json(['error' => 'You can only rate your own requests.'], 403);
        }

        // Check if request is collected
        if ($wasteRequest->status !== 'collected') {
            return response()->json(['error' => 'You can only rate collected requests.'], 400);
        }

        // Check if collector is assigned
        if (!$wasteRequest->collector_id) {
            return response()->json(['error' => 'No collector assigned to this request.'], 400);
        }

        // Get collector profile
        $collector = \App\Models\Collector::where('user_id', $wasteRequest->collector_id)->first();
        if (!$collector) {
            return response()->json(['error' => 'Collector not found.'], 404);
        }

        // Create or update rating
        $rating = \App\Models\CollectorRating::updateOrCreate(
            ['waste_request_id' => $wasteRequestId],
            [
                'collector_id' => $collector->id,
                'customer_id' => $user->id,
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]
        );

        // Update collector's average rating
        $this->updateCollectorAverageRating($collector->id);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for rating the collector!',
            'rating' => $rating->rating,
        ]);
    }

    /**
     * Update collector's average rating
     */
    private function updateCollectorAverageRating($collectorId)
    {
        $averageRating = \App\Models\CollectorRating::where('collector_id', $collectorId)
            ->avg('rating');

        \App\Models\Collector::where('id', $collectorId)
            ->update(['rating' => round($averageRating, 2)]);
    }
}
