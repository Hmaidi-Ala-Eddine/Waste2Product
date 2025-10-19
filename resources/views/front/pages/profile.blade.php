@extends('layouts.front')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-wrapper {
        padding-top: 150px;
        padding-bottom: 80px;
        /* Simplified static gradient for better performance */
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        min-height: 100vh;
        position: relative;
    }

    .profile-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        background-position: bottom;
        pointer-events: none;
        opacity: 1;
    }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
        z-index: 1;
    }

    .profile-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 30px 90px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        animation: fadeInUp 0.5s ease forwards;
        border: 1px solid rgba(255, 255, 255, 0.3);
        /* GPU acceleration */
        transform: translateZ(0);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px) translateZ(0);
        }
        to {
            opacity: 1;
            transform: translateY(0) translateZ(0);
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
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        /* Simplified static shadow for better performance */
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3), 0 0 0 10px rgba(255, 255, 255, 0.1);
        transition: transform 0.3s ease;
        /* GPU acceleration */
        transform: translateZ(0);
        will-change: transform;
    }

    .profile-avatar-large:hover {
        transform: scale(1.05) translateZ(0);
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
        gap: 20px;
        padding: 0;
        margin: -30px 40px 30px;
        position: relative;
        z-index: 5;
    }

    .stat-item {
        text-align: center;
        background: white;
        padding: 25px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        flex: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
        /* GPU acceleration */
        transform: translateZ(0);
        will-change: transform;
    }

    .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .stat-item:hover {
        transform: translateY(-5px) translateZ(0);
        box-shadow: 0 15px 50px rgba(102, 126, 234, 0.3);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
    }

    .stat-label {
        font-size: 13px;
        color: #7f8c8d;
        margin-top: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .profile-details {
        padding: 40px;
        background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
    }

    .detail-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 15px;
        border-bottom: 3px solid transparent;
        border-image: linear-gradient(90deg, #667eea 0%, #764ba2 50%, transparent 100%);
        border-image-slice: 1;
    }

    .section-title i {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .detail-item {
        padding: 25px;
        background: white;
        border-radius: 15px;
        border: 2px solid #f0f2f5;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        position: relative;
        overflow: hidden;
        /* GPU acceleration */
        transform: translateZ(0);
        will-change: transform;
    }

    .detail-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        transform: scaleY(0) translateZ(0);
        transition: transform 0.3s ease;
    }

    .detail-item:hover {
        transform: translateY(-3px) translateZ(0);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        border-color: #667eea;
    }

    .detail-item:hover::before {
        transform: scaleY(1) translateZ(0);
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
        font-size: 17px;
        color: #2c3e50;
        font-weight: 600;
    }

    /* Simplified decorative elements - static for better performance */
    .floating-shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.08;
        pointer-events: none;
    }

    .shape-1 {
        width: 200px;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        top: 10%;
        left: 5%;
    }

    .shape-2 {
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        top: 60%;
        right: 10%;
    }

    .shape-3 {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        bottom: 15%;
        left: 15%;
    }

    /* Removed float animation for better performance */
        }
    }

    /* Sparkle effects removed for better performance */

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
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-custom::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-custom:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        background-size: 200% 100%;
        background-position: left;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-primary-custom:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
        color: white;
        background-position: right;
    }

    .btn-secondary-custom {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .btn-secondary-custom:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
    }

    .activity-feed {
        margin-top: 30px;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        padding: 25px;
        background: white;
        border-radius: 15px;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid #f0f2f5;
        position: relative;
        overflow: hidden;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .activity-item:hover {
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        border-color: #667eea;
        transform: translateX(5px);
    }

    .activity-item:hover::before {
        transform: scaleY(1);
    }

    .activity-icon {
        width: 55px;
        height: 55px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
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

    /* Edit Profile Button */
    .btn-edit-profile {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.4);
        padding: 10px 24px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        z-index: 10;
    }

    .btn-edit-profile:hover {
        background: white;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
    }

    /* Modal Styles */
    .edit-profile-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
        overflow-y: auto;
        padding: 20px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content-custom {
        background: white;
        border-radius: 20px;
        max-width: 700px;
        margin: 50px auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.4s ease;
        overflow: hidden;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        text-align: center;
        position: relative;
    }

    .modal-header-custom h2 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .modal-body-custom {
        padding: 40px;
    }

    .form-group-custom {
        margin-bottom: 25px;
    }

    .form-label-custom {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control-custom {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .profile-picture-upload {
        text-align: center;
        margin-bottom: 30px;
    }

    .profile-picture-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 15px;
        object-fit: cover;
        border: 4px solid #667eea;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
    }

    .upload-btn-wrapper {
        position: relative;
        display: inline-block;
    }

    .btn-upload {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 24px;
        border-radius: 25px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .upload-btn-wrapper input[type=file] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .modal-footer-custom {
        padding: 20px 40px;
        background: #f9fafb;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
    }

    .btn-modal {
        padding: 12px 30px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-modal-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-modal-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    .btn-modal-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-modal-secondary:hover {
        background: #667eea;
        color: white;
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

        .btn-edit-profile {
            position: static;
            margin-top: 15px;
        }

        .modal-content-custom {
            margin: 20px auto;
        }

        .modal-body-custom {
            padding: 25px;
        }
    }
</style>
@endpush

@section('content')
<!-- Start Profile Wrapper -->
<div class="profile-wrapper">
    <!-- Floating Decorative Shapes -->
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    <div class="floating-shape shape-3"></div>
    
    <!-- Sparkle effects removed for better performance -->
    
    <div class="profile-container">
    
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(46, 213, 115, 0.3);">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(255, 71, 87, 0.3);">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 15px; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(255, 193, 7, 0.3);">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="profile-card">
        <!-- Profile Header -->
        <div class="profile-header">
            <!-- Edit Profile Button -->
            <button class="btn-edit-profile" onclick="openEditModal()">
                <i class="fas fa-edit"></i>
                Edit Profile
            </button>
            
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

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="edit-profile-modal">
    <div class="modal-content-custom">
        <!-- Modal Header -->
        <div class="modal-header-custom">
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
            <h2>
                <i class="fas fa-user-edit me-2"></i>
                Edit Your Profile
            </h2>
        </div>

        <!-- Modal Body -->
        <form id="editProfileForm" action="{{ route('front.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="modal-body-custom">
                <!-- Profile Picture Upload -->
                <div class="profile-picture-upload">
                    <img id="previewImage" src="{{ $user->profile_picture_url }}" alt="Profile Picture" class="profile-picture-preview">
                    <div class="upload-btn-wrapper">
                        <button type="button" class="btn-upload">
                            <i class="fas fa-camera"></i>
                            Change Photo
                        </button>
                        <input type="file" name="profile_picture" accept="image/*" onchange="previewProfilePicture(event)">
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">JPG, PNG or GIF. Max size 2MB</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Full Name -->
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                <i class="fas fa-user me-1"></i> Full Name *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control-custom" 
                                   value="{{ $user->name }}" 
                                   required
                                   placeholder="Enter your full name">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                <i class="fas fa-envelope me-1"></i> Email Address *
                            </label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control-custom" 
                                   value="{{ $user->email }}" 
                                   required
                                   placeholder="Enter your email">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Phone -->
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                <i class="fas fa-phone me-1"></i> Phone Number
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   class="form-control-custom" 
                                   value="{{ $user->phone }}" 
                                   placeholder="Enter your phone number">
                        </div>
                    </div>

                    <!-- Password (Optional) -->
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                <i class="fas fa-lock me-1"></i> New Password
                            </label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control-custom" 
                                   placeholder="Leave blank to keep current">
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="fas fa-map-marker-alt me-1"></i> Address
                    </label>
                    <textarea name="address" 
                              class="form-control-custom" 
                              rows="3" 
                              placeholder="Enter your full address">{{ $user->address }}</textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeEditModal()">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <button type="submit" class="btn-modal btn-modal-primary">
                    <i class="fas fa-save me-1"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Open Edit Modal
function openEditModal() {
    document.getElementById('editProfileModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent background scroll
}

// Close Edit Modal
function closeEditModal() {
    document.getElementById('editProfileModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Re-enable scroll
}

// Close modal when clicking outside
document.getElementById('editProfileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Preview Profile Picture
function previewProfilePicture(event) {
    const file = event.target.files[0];
    if (file) {
        // Check file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }

        // Check file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            event.target.value = '';
            return;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Handle ESC key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});
</script>
@endpush
