@extends('layouts.front')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-wrapper {
        padding-top: 150px;
        padding-bottom: 80px;
        background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
        min-height: 100vh;
    }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .profile-header {
        padding: 50px 40px;
        text-align: center;
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px 20px 0 0;
    }

    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 20px;
    }

    .profile-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .profile-name {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 15px 0 8px;
    }

    .profile-email {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 20px;
    }

    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        padding: 30px;
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        border-radius: 15px;
        margin: 0 40px 30px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #667eea;
        display: block;
    }

    .stat-label {
        font-size: 14px;
        color: #7f8c8d;
        margin-top: 5px;
    }

    .profile-details {
        padding: 40px;
    }

    .detail-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #667eea;
        font-size: 24px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .detail-item {
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
        border-radius: 12px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
    }

    .detail-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #7f8c8d;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .detail-value {
        font-size: 16px;
        color: #2c3e50;
        font-weight: 500;
    }

    .badge-custom {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 600;
        margin: 0 5px;
    }

    .badge-active {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .badge-inactive {
        background: rgba(255, 152, 0, 0.9);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .badge-verified {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .badge-pending {
        background: rgba(255, 193, 7, 0.9);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 30px;
    }

    .btn-custom {
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .btn-secondary-custom {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary-custom:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .activity-feed {
        margin-top: 30px;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .activity-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .activity-time {
        font-size: 13px;
        color: #7f8c8d;
    }

    /* Navbar styling for profile page only */
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .navbar-nav > li > a:hover,
    .navbar-nav > li > a:focus {
        color: #4CAF50 !important;
    }

    .navbar.white .navbar-nav > li > a,
    .navbar.navbar-scrolled .navbar-nav > li > a {
        color: #2c3e50 !important;
    }

    @media (max-width: 768px) {
        .profile-wrapper {
            padding-top: 100px;
        }

        .profile-header,
        .profile-details {
            padding: 30px 20px;
        }

        .profile-stats {
            flex-direction: column;
            gap: 20px;
            margin: 0 20px 30px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .profile-avatar-large {
            width: 100px;
            height: 100px;
        }

        .profile-name {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<!-- Start Profile Wrapper -->
<div class="profile-wrapper">
    <div class="profile-container">
    <div class="profile-card">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-wrapper">
                <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" class="profile-avatar-large">
            </div>
            <h1 class="profile-name">{{ $user->name }}</h1>
            <p class="profile-email">{{ $user->email }}</p>
            
            @if($user->is_active)
                <span class="badge-custom badge-active">
                    <i class="fas fa-check-circle"></i> Active Account
                </span>
            @else
                <span class="badge-custom badge-inactive">
                    <i class="fas fa-exclamation-circle"></i> Inactive Account
                </span>
            @endif

            @if($user->hasCollectorProfile())
                @if($user->isVerifiedCollector())
                    <span class="badge-custom badge-verified">
                        <i class="fas fa-badge-check"></i> Verified Collector
                    </span>
                @else
                    <span class="badge-custom badge-pending">
                        <i class="fas fa-clock"></i> Collector Pending
                    </span>
                @endif
            @endif
        </div>

        <!-- Profile Stats -->
        <div class="profile-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $user->wasteRequests()->count() }}</span>
                <span class="stat-label">Waste Requests</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $user->posts()->count() }}</span>
                <span class="stat-label">Posts</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $user->created_at->diffForHumans() }}</span>
                <span class="stat-label">Member Since</span>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="profile-details">
            <!-- Personal Information -->
            <div class="detail-section">
                <h2 class="section-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value">{{ $user->name }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value">{{ $user->email }}</div>
                    </div>
                    @if($user->phone)
                    <div class="detail-item">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value">{{ $user->phone }}</div>
                    </div>
                    @endif
                    @if($user->address)
                    <div class="detail-item">
                        <div class="detail-label">Address</div>
                        <div class="detail-value">{{ $user->address }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Account Information -->
            <div class="detail-section">
                <h2 class="section-title">
                    <i class="fas fa-cog"></i>
                    Account Information
                </h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Account Role</div>
                        <div class="detail-value">{{ ucfirst($user->role) }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Account Status</div>
                        <div class="detail-value">
                            @if($user->is_active)
                                <span style="color: #4CAF50; font-weight: 600;">Active</span>
                            @else
                                <span style="color: #ff9800; font-weight: 600;">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Member Since</div>
                        <div class="detail-value">{{ $user->created_at->format('F d, Y') }}</div>
                    </div>
                    @if($user->email_verified_at)
                    <div class="detail-item">
                        <div class="detail-label">Email Verified</div>
                        <div class="detail-value">
                            <span style="color: #4CAF50; font-weight: 600;">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="detail-section">
                <h2 class="section-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h2>
                <div class="action-buttons">
                    <a href="{{ route('front.waste-requests') }}" class="btn-custom btn-primary-custom">
                        <i class="fas fa-clipboard-list"></i>
                        My Waste Requests
                    </a>
                    <a href="{{ route('front.collector-application') }}" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-user-tie"></i>
                        Collector Application
                    </a>
                    @if($user->isVerifiedCollector())
                    <a href="{{ route('front.collector-dashboard') }}" class="btn-custom btn-primary-custom">
                        <i class="fas fa-truck"></i>
                        Collector Dashboard
                    </a>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="detail-section">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    Recent Activity
                </h2>
                <div class="activity-feed">
                    @if($user->wasteRequests()->exists())
                        @foreach($user->wasteRequests()->latest()->take(3)->get() as $request)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-recycle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Created waste request</div>
                                <div class="activity-time">{{ $request->waste_type_formatted }} - {{ $request->quantity }} kg · {{ $request->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">No recent activity</div>
                                <div class="activity-time">Start by creating your first waste request!</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<!-- End Profile Wrapper -->
@endsection
