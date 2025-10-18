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
    
    /* Leaderboard Enhancements */
    .leaderboard-item {
        transition: all 0.3s ease !important;
    }
    
    .leaderboard-item:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    }
    
    .leaderboard-item.my-rank {
        animation: pulse-green 2s infinite;
    }
    
    @keyframes pulse-green {
        0%, 100% {
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        50% {
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
    }
    
    /* Scrollbar Styling */
    .leaderboard-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .leaderboard-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .leaderboard-scroll::-webkit-scrollbar-thumb {
        background: #10b981;
        border-radius: 10px;
    }
    
    .leaderboard-scroll::-webkit-scrollbar-thumb:hover {
        background: #059669;
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

        <!-- Performance Dashboard Row -->
        <div class="row mb-4">
            <!-- Performance Stats Cards -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card text-center" style="border-top-color: #10b981;">
                    <div class="mb-3">
                        <i class="fas fa-trophy" style="font-size: 2.5rem; color: #10b981;"></i>
                    </div>
                    <h2 class="mb-1" style="color: #10b981; font-weight: 700;">{{ $totalCollections }}</h2>
                    <p class="text-muted mb-0 small">Total Collections</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card text-center" style="border-top-color: #3b82f6;">
                    <div class="mb-3">
                        <i class="fas fa-calendar-check" style="font-size: 2.5rem; color: #3b82f6;"></i>
                    </div>
                    <h2 class="mb-1" style="color: #3b82f6; font-weight: 700;">{{ $thisMonthCollections }}</h2>
                    <p class="text-muted mb-0 small">This Month</p>
                    @if($growthPercentage > 0)
                        <small class="text-success"><i class="fas fa-arrow-up"></i> +{{ $growthPercentage }}%</small>
                    @elseif($growthPercentage < 0)
                        <small class="text-danger"><i class="fas fa-arrow-down"></i> {{ $growthPercentage }}%</small>
                    @endif
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card text-center" style="border-top-color: #f59e0b;">
                    <div class="mb-3">
                        <i class="fas fa-weight-hanging" style="font-size: 2.5rem; color: #f59e0b;"></i>
                    </div>
                    <h2 class="mb-1" style="color: #f59e0b; font-weight: 700;">{{ number_format($totalWasteCollected, 1) }}</h2>
                    <p class="text-muted mb-0 small">Total Waste (kg)</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card text-center" style="border-top-color: #8b5cf6;">
                    <div class="mb-3">
                        <i class="fas fa-chart-line" style="font-size: 2.5rem; color: #8b5cf6;"></i>
                    </div>
                    <h2 class="mb-1" style="color: #8b5cf6; font-weight: 700;">{{ $successRate }}%</h2>
                    <p class="text-muted mb-0 small">Success Rate</p>
                </div>
            </div>
        </div>

        <!-- Performance & Leaderboard Row -->
        <div class="row mb-4">
            <!-- Performance Details -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header-custom">
                        <h3><i class="fas fa-chart-bar me-2"></i>My Performance</h3>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-4 text-center">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-star" style="font-size: 2rem; color: #fbbf24;"></i>
                                <h4 class="mt-2 mb-0" style="color: #1f2937;">{{ number_format($averageRating, 1) }}/5.0</h4>
                                <small class="text-muted">Average Rating</small>
                            </div>
                        </div>
                        <div class="col-6 mb-4 text-center">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-medal" style="font-size: 2rem; color: #10b981;"></i>
                                <h4 class="mt-2 mb-0" style="color: #1f2937;">#{{ $myRank }}</h4>
                                <small class="text-muted">My Rank</small>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-cloud" style="font-size: 2rem; color: #06b6d4;"></i>
                                <h4 class="mt-2 mb-0" style="color: #1f2937;">{{ number_format($co2Saved, 1) }} kg</h4>
                                <small class="text-muted">CO₂ Saved</small>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-tree" style="font-size: 2rem; color: #22c55e;"></i>
                                <h4 class="mt-2 mb-0" style="color: #1f2937;">{{ number_format($treesSaved, 1) }}</h4>
                                <small class="text-muted">Trees Saved</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header-custom">
                        <h3><i class="fas fa-trophy me-2"></i>Top Collectors Leaderboard</h3>
                        <small class="text-muted">Best performers this month</small>
                    </div>
                    
                    <div class="leaderboard-scroll" style="max-height: 450px; overflow-y: auto; padding-right: 5px;">
                        @foreach($leaderboard as $leader)
                            <div class="leaderboard-item {{ $leader['id'] == auth()->user()->collector->id ? 'my-rank' : '' }}" 
                                 style="display: flex; 
                                        align-items: center; 
                                        padding: 18px; 
                                        margin-bottom: 12px; 
                                        border-radius: 12px; 
                                        {{ $leader['id'] == auth()->user()->collector->id ? 'background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 2px solid #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);' : 'background: #f9fafb; border: 1px solid #e5e7eb;' }}
                                        transition: all 0.3s ease;
                                        cursor: default;">
                                
                                <!-- Rank Badge -->
                                <div style="margin-right: 15px; min-width: 50px; text-align: center;">
                                    @if($leader['rank'] == 1)
                                        <div style="position: relative;">
                                            <i class="fas fa-crown" style="font-size: 2.2rem; color: #fbbf24; filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.5));"></i>
                                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3); font-size: 0.75rem;">1</div>
                                        </div>
                                    @elseif($leader['rank'] == 2)
                                        <div style="position: relative;">
                                            <i class="fas fa-medal" style="font-size: 2rem; color: #c0c0c0; filter: drop-shadow(0 2px 4px rgba(192, 192, 192, 0.5));"></i>
                                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; color: #666; font-size: 0.7rem;">2</div>
                                        </div>
                                    @elseif($leader['rank'] == 3)
                                        <div style="position: relative;">
                                            <i class="fas fa-medal" style="font-size: 2rem; color: #cd7f32; filter: drop-shadow(0 2px 4px rgba(205, 127, 50, 0.5));"></i>
                                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; color: #fff; font-size: 0.7rem;">3</div>
                                        </div>
                                    @else
                                        <div style="width: 45px; 
                                                    height: 45px; 
                                                    border-radius: 50%; 
                                                    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); 
                                                    display: flex; 
                                                    align-items: center; 
                                                    justify-content: center; 
                                                    font-weight: 800; 
                                                    color: #6b7280; 
                                                    font-size: 1.1rem;
                                                    border: 2px solid #fff;
                                                    box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                            {{ $leader['rank'] }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Collector Info -->
                                <div style="flex-grow: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                        <!-- Name & Company -->
                                        <div style="flex: 1;">
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                <strong style="color: #1f2937; font-size: 1.05rem;">{{ $leader['name'] }}</strong>
                                                @if($leader['id'] == auth()->user()->collector->id)
                                                    <span class="badge" style="background: #10b981; color: white; font-size: 0.7rem; padding: 3px 8px; border-radius: 8px;">YOU</span>
                                                @endif
                                            </div>
                                            @if($leader['company_name'])
                                                <div style="color: #6b7280; font-size: 0.85rem; margin-bottom: 6px;">
                                                    <i class="fas fa-building" style="font-size: 0.75rem; margin-right: 4px;"></i>
                                                    {{ $leader['company_name'] }}
                                                </div>
                                            @endif
                                            
                                            <!-- Star Rating with Number -->
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star" style="color: {{ $i <= $leader['rating'] ? '#fbbf24' : '#e5e7eb' }}; font-size: 0.9rem;"></i>
                                                @endfor
                                                <span style="color: #fbbf24; font-weight: 700; font-size: 0.95rem; margin-left: 4px;">
                                                    {{ number_format($leader['rating'], 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Collections Badge -->
                                        <div style="text-align: right;">
                                            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
                                                        color: white; 
                                                        padding: 8px 14px; 
                                                        border-radius: 10px; 
                                                        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
                                                        min-width: 80px;">
                                                <div style="font-size: 1.3rem; font-weight: 800; line-height: 1;">
                                                    {{ $leader['total_collections'] }}
                                                </div>
                                                <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; opacity: 0.9;">
                                                    Collections
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Leaderboard Footer -->
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #e5e7eb; text-align: center;">
                        <small style="color: #6b7280;">
                            <i class="fas fa-info-circle me-1"></i>
                            Rankings updated in real-time based on total collections
                        </small>
                    </div>
                </div>
            </div>
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
