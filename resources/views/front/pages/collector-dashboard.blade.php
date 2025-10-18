@extends('layouts.front')

@section('title', 'Collector Dashboard')

@push('styles')
<style>
    .collector-dashboard-area {
        padding: 120px 0 80px;
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        min-height: 100vh;
        position: relative;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .section-title h2 {
        font-size: 2.5rem;
        font-weight: 600;
        color: white;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        letter-spacing: -0.3px;
    }
    
    .section-title h2 i {
        color: white;
    }
    
    .section-title p {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.9);
        max-width: 700px;
        margin: 0 auto;
    }
    
    .dashboard-card {
        background: white;
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
        margin-bottom: 30px;
        border-top: 4px solid #10b981;
    }
    
    .card-header-custom {
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 18px;
        margin-bottom: 25px;
    }
    
    .card-header-custom h3 {
        color: #1f2937;
        font-weight: 700;
        margin: 0;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-header-custom h3 i {
        color: #10b981;
    }
    
    .search-bar {
        background: #f9fafb;
        padding: 22px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px solid #e5e7eb;
    }
    
    .request-item {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    
    .request-item:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-color: #10b981;
    }
    
    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .waste-type-badge {
        background: #5a67d8;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .request-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .detail-item i {
        color: #11998e;
        width: 20px;
    }
    
    .detail-item span {
        color: #2c3e50;
        font-weight: 500;
    }
    
    .btn-accept {
        background: #10b981;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }
    
    .btn-accept:hover {
        background: #059669;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
        color: white;
    }
    
    .btn-accept:active {
        transform: scale(0.98);
    }
    
    .btn-complete {
        background: #8b5cf6;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    
    .btn-complete:hover {
        background: #7c3aed;
        color: white;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: white;
    }
    
    .status-accepted { background: #3498db; }
    .status-collected { background: #27ae60; }
    
    .empty-state {
        text-align: center;
        padding: 60px 30px;
        color: #6b7280;
        background: #f9fafb;
        border-radius: 12px;
        border: 2px dashed #d1d5db;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #d1d5db;
    }
    
    .empty-state h3 {
        font-size: 1.4rem;
        margin-bottom: 10px;
        color: #374151;
        font-weight: 600;
    }
    
    .empty-state p {
        font-size: 1rem;
        color: #6b7280;
    }
    
    .btn-search {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        border: none !important;
        padding: 10px 20px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 0.95rem !important;
        line-height: 1.5 !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2) !important;
        text-transform: none !important;
        letter-spacing: normal !important;
        height: auto !important;
        min-height: auto !important;
    }
    
    .btn-search:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
        transform: translateY(-1px) !important;
        color: white !important;
        border: none !important;
    }
    
    .btn-search:focus,
    .btn-search:active {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
        outline: none !important;
    }
    
    .btn-search i {
        font-size: 0.9rem !important;
    }
    
    .form-control, .form-select {
        border: 2px solid #e8ecef;
        border-radius: 12px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        }
        
        .dashboard-card {
            padding: 20px;
        }
        
        .section-title h2 {
            font-size: 2rem;
        }
        
        .request-details {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="collector-dashboard-area">
    <div class="container">
        <!-- Section Title -->
        <div class="section-title">
            <h2>
                <i class="fas fa-truck me-2"></i>
                Collector Dashboard
            </h2>
            <p>View and accept available waste collection requests in your area</p>
        </div>

        <div class="row">
            <!-- Available Requests -->
            <div class="col-lg-8 mb-4">
                <div class="dashboard-card">
                    <div class="card-header-custom">
                        <h3><i class="fas fa-list-ul me-2"></i>Available Requests</h3>
                    </div>
                    
                    <!-- Search & Filter -->
                    <div class="search-bar">
                        <form method="GET" action="{{ route('front.collector-dashboard') }}">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <select name="governorate" class="form-select">
                                        <option value="all" {{ request('governorate') == 'all' ? 'selected' : '' }}>All Governorates</option>
                                        @foreach(\App\Helpers\TunisiaStates::getStates() as $key => $label)
                                            <option value="{{ $key }}" {{ request('governorate') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select name="waste_type" class="form-select">
                                        <option value="all" {{ request('waste_type') == 'all' ? 'selected' : '' }}>All Waste Types</option>
                                        @foreach(\App\Models\WasteRequest::getWasteTypes() as $key => $value)
                                            <option value="{{ $key }}" {{ request('waste_type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn-search w-100">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    @if($availableRequests->count() > 0)
                        @foreach($availableRequests as $request)
                            <div class="request-item" id="request-{{ $request->id }}">
                                <div class="request-header">
                                    <span class="waste-type-badge">{{ $request->waste_type_formatted }}</span>
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                </div>
                                
                                <div class="request-details">
                                    <div class="detail-item">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $request->customer->name }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-weight-hanging"></i>
                                        <span>{{ $request->quantity }} kg</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-map-pin"></i>
                                        <span><strong>{{ $request->state ?? 'N/A' }}</strong></span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($request->address, 30) }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $request->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                
                                @if($request->description)
                                    <div class="mb-3">
                                        <strong>Notes:</strong> {{ $request->description }}
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-map-marked-alt text-muted me-2"></i>
                                        <small class="text-muted">{{ $request->address }}</small>
                                    </div>
                                    <button class="btn btn-accept" onclick="acceptRequest({{ $request->id }})">
                                        <i class="fas fa-check me-2"></i>Accept Request
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($availableRequests->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $availableRequests->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>No Available Requests</h3>
                            <p>There are currently no pending waste collection requests in your area.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- My Accepted Requests -->
            <div class="col-lg-4">
                <div class="dashboard-card">
                    <div class="card-header-custom">
                        <h3><i class="fas fa-tasks me-2"></i>My Collections</h3>
                    </div>
                    
                    @if($myRequests->count() > 0)
                        @foreach($myRequests as $request)
                            <div class="request-item">
                                <div class="request-header">
                                    <span class="status-badge status-{{ $request->status }}">
                                        {{ $request->status_formatted }}
                                    </span>
                                </div>
                                
                                <div class="mb-2">
                                    <strong>{{ $request->waste_type_formatted }}</strong>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-user text-muted me-2"></i>
                                    <small>{{ $request->customer->name }}</small>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-weight-hanging text-muted me-2"></i>
                                    <small>{{ $request->quantity }} kg</small>
                                </div>
                                
                                @if($request->status === 'accepted')
                                    <button class="btn btn-complete btn-sm w-100 mt-2" onclick="completeCollection({{ $request->id }})">
                                        <i class="fas fa-check-circle me-2"></i>Mark as Collected
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <p>No active collections</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Accept request
function acceptRequest(requestId) {
    if (!confirm('Are you sure you want to accept this collection request?')) {
        return;
    }
    
    // Show loading state
    const requestItem = document.getElementById(`request-${requestId}`);
    const originalContent = requestItem.innerHTML;
    requestItem.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Accepting...</div>';
    
    fetch(`{{ url('/collector/accept-request') }}/${requestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            requestItem.innerHTML = `
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle me-2"></i>${data.message}
                </div>
            `;
            
            // Reload page after 2 seconds
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            alert(data.error || 'Failed to accept request');
            requestItem.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        requestItem.innerHTML = originalContent;
    });
}

// Complete collection
function completeCollection(requestId) {
    if (!confirm('Mark this collection as complete?')) {
        return;
    }
    
    fetch(`{{ url('/collector/complete-collection') }}/${requestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.error || 'Failed to complete collection');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endpush
