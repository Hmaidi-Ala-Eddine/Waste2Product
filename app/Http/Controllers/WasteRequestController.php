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

        $wasteRequest = WasteRequest::create([
            'user_id' => $data['user_id'],
            'collector_id' => $data['collector_id'] ?? null,
            'waste_type' => $data['waste_type'],
            'quantity' => $data['quantity'],
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

        $wasteRequest->update([
            'user_id' => $data['user_id'],
            'collector_id' => $data['collector_id'] ?? null,
            'waste_type' => $data['waste_type'],
            'quantity' => $data['quantity'],
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
     * Get collectors for dropdown
     */
    public function getCollectors()
    {
        $collectors = User::where('role', 'collector')
                         ->where('is_active', true)
                         ->select('id', 'name', 'email')
                         ->get();
        
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
        $wasteRequests = WasteRequest::with(['collector'])
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
}
