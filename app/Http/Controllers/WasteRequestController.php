<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        
        // Order and paginate results
        $wasteRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'waste_type' => ['required', Rule::in(array_keys(WasteRequest::getWasteTypes()))],
            'quantity' => 'required|numeric|min:0.1|max:999999.99',
            'address' => 'required|string|max:1000',
            'description' => 'nullable|string|max:2000',
            'collector_id' => 'nullable|exists:users,id',
        ]);

        // Validate collector role if provided
        if ($request->filled('collector_id')) {
            $collector = User::find($request->collector_id);
            if (!$collector || $collector->role !== 'collector') {
                return back()->withErrors(['collector_id' => 'Selected user is not a collector.']);
            }
        }

        $wasteRequest = WasteRequest::create([
            'user_id' => $request->user_id,
            'collector_id' => $request->collector_id,
            'waste_type' => $request->waste_type,
            'quantity' => $request->quantity,
            'address' => $request->address,
            'description' => $request->description,
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

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'waste_type' => ['required', Rule::in(array_keys(WasteRequest::getWasteTypes()))],
            'quantity' => 'required|numeric|min:0.1|max:999999.99',
            'address' => 'required|string|max:1000',
            'description' => 'nullable|string|max:2000',
            'collector_id' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(array_keys(WasteRequest::getStatuses()))],
        ]);

        // Validate collector role if provided
        if ($request->filled('collector_id')) {
            $collector = User::find($request->collector_id);
            if (!$collector || $collector->role !== 'collector') {
                return back()->withErrors(['collector_id' => 'Selected user is not a collector.']);
            }
        }

        // Set collected_at timestamp when status changes to collected
        $collectedAt = $wasteRequest->collected_at;
        if ($request->status === 'collected' && $wasteRequest->status !== 'collected') {
            $collectedAt = now();
        } elseif ($request->status !== 'collected') {
            $collectedAt = null;
        }

        $wasteRequest->update([
            'user_id' => $request->user_id,
            'collector_id' => $request->collector_id,
            'waste_type' => $request->waste_type,
            'quantity' => $request->quantity,
            'address' => $request->address,
            'description' => $request->description,
            'status' => $request->status,
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
        
        $request->validate([
            'collector_id' => 'required|exists:users,id',
        ]);

        $collector = User::find($request->collector_id);
        if ($collector->role !== 'collector') {
            return response()->json(['error' => 'Selected user is not a collector.'], 400);
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
        
        $request->validate([
            'status' => ['required', Rule::in(array_keys(WasteRequest::getStatuses()))],
        ]);

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
}
