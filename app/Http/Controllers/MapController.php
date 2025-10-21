<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use App\Models\Collector;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Display the interactive map
     */
    public function index()
    {
        return view('back.pages.map');
    }

    /**
     * Get waste requests for map markers
     */
    public function getWasteRequests(Request $request)
    {
        $query = WasteRequest::with(['customer', 'collector']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $wasteRequests = $query->get()->map(function ($wr) {
            // Get coordinates for the governorate
            $coordinates = $this->getGovernorateCoordinates($wr->state);
            
            return [
                'id' => $wr->id,
                'customer_name' => $wr->customer->name ?? 'Unknown',
                'customer_email' => $wr->customer->email ?? '',
                'waste_type' => $wr->waste_type,
                'quantity' => $wr->quantity,
                'status' => $wr->status,
                'state' => $wr->state,
                'address' => $wr->address,
                'description' => $wr->description,
                'collector_name' => $wr->collector->name ?? 'Unassigned',
                'created_at' => $wr->created_at->format('Y-m-d H:i'),
                'latitude' => $coordinates['lat'],
                'longitude' => $coordinates['lng'],
            ];
        });

        return response()->json($wasteRequests);
    }

    /**
     * Get collectors for map markers
     */
    public function getCollectors()
    {
        $collectors = Collector::with('user')
            ->where('verification_status', 'verified')
            ->get()
            ->map(function ($collector) {
                // Get primary service area coordinates
                $primaryState = is_array($collector->service_areas) && count($collector->service_areas) > 0 
                    ? $collector->service_areas[0] 
                    : null;
                
                $coordinates = $this->getGovernorateCoordinates($primaryState);
                
                return [
                    'id' => $collector->id,
                    'name' => $collector->user->name ?? 'Unknown',
                    'email' => $collector->user->email ?? '',
                    'company_name' => $collector->company_name,
                    'vehicle_type' => $collector->vehicle_type,
                    'capacity_kg' => $collector->capacity_kg,
                    'service_areas' => $collector->service_areas,
                    'total_collections' => $collector->total_collections,
                    'rating' => $collector->rating,
                    'latitude' => $coordinates['lat'],
                    'longitude' => $coordinates['lng'],
                ];
            });

        return response()->json($collectors);
    }

    /**
     * Get statistics for map dashboard
     */
    public function getStatistics()
    {
        $requestsByState = WasteRequest::selectRaw('state, COUNT(*) as count, SUM(quantity) as total_weight')
            ->whereNotNull('state')
            ->groupBy('state')
            ->orderBy('count', 'desc')
            ->get();

        $stats = [
            'total_requests' => WasteRequest::count(),
            'pending_requests' => WasteRequest::where('status', 'pending')->count(),
            'accepted_requests' => WasteRequest::where('status', 'accepted')->count(),
            'collected_requests' => WasteRequest::where('status', 'collected')->count(),
            'total_collectors' => Collector::where('verification_status', 'verified')->count(),
            'total_waste_kg' => WasteRequest::where('status', 'collected')->sum('quantity') ?? 0,
            'requests_by_state' => $requestsByState->map(function($item) {
                return [
                    'state' => $item->state,
                    'count' => $item->count,
                    'weight' => $item->total_weight ?? 0
                ];
            })->values(),
        ];

        return response()->json($stats);
    }

    /**
     * Get coordinates for Tunisia governorates
     */
    private function getGovernorateCoordinates($state)
    {
        // Tunisia governorate coordinates (approximate center points)
        $coordinates = [
            'tunis' => ['lat' => 36.8065, 'lng' => 10.1815],
            'ariana' => ['lat' => 36.8625, 'lng' => 10.1956],
            'ben_arous' => ['lat' => 36.7538, 'lng' => 10.2297],
            'manouba' => ['lat' => 36.8089, 'lng' => 10.0976],
            'nabeul' => ['lat' => 36.4516, 'lng' => 10.7361],
            'zaghouan' => ['lat' => 36.4028, 'lng' => 10.1433],
            'bizerte' => ['lat' => 37.2746, 'lng' => 9.8739],
            'beja' => ['lat' => 36.7256, 'lng' => 9.1817],
            'jendouba' => ['lat' => 36.5011, 'lng' => 8.7803],
            'kef' => ['lat' => 36.1743, 'lng' => 8.7049],
            'siliana' => ['lat' => 36.0853, 'lng' => 9.3704],
            'sousse' => ['lat' => 35.8256, 'lng' => 10.6369],
            'monastir' => ['lat' => 35.7772, 'lng' => 10.8263],
            'mahdia' => ['lat' => 35.5047, 'lng' => 11.0622],
            'sfax' => ['lat' => 34.7406, 'lng' => 10.7603],
            'kairouan' => ['lat' => 35.6781, 'lng' => 10.0963],
            'kasserine' => ['lat' => 35.1676, 'lng' => 8.8360],
            'sidi_bouzid' => ['lat' => 35.0381, 'lng' => 9.4858],
            'gabes' => ['lat' => 33.8815, 'lng' => 10.0982],
            'medenine' => ['lat' => 33.3549, 'lng' => 10.5055],
            'tataouine' => ['lat' => 32.9297, 'lng' => 10.4517],
            'gafsa' => ['lat' => 34.4250, 'lng' => 8.7842],
            'tozeur' => ['lat' => 33.9197, 'lng' => 8.1335],
            'kebili' => ['lat' => 33.7051, 'lng' => 8.9690],
        ];

        // Return coordinates or default to Tunis if state not found
        return $coordinates[strtolower($state)] ?? ['lat' => 36.8065, 'lng' => 10.1815];
    }
}
