@extends('layouts.front')

@section('title', 'Collector Application')

@push('styles')
<style>
    .collector-application-area {
        padding: 120px 0 80px;
        background: linear-gradient(135deg, #5a67d8 0%, #6b7fd7 100%);
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
    
    .section-title p {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.9);
        max-width: 700px;
        margin: 0 auto;
    }
    
    .application-card {
        background: white;
        border-radius: 16px;
        padding: 45px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
        margin-bottom: 40px;
        border-top: 4px solid #5a67d8;
    }
    
    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 45px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
        position: relative;
        overflow: hidden;
    }
    
    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px 16px 0 0;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
        display: block;
        font-size: 0.95rem;
    }
    
    .form-control, .form-select {
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
        font-weight: 400;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #5a67d8;
        box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1);
        background: white;
        outline: none;
    }
    
    textarea.form-control {
        resize: vertical;
    }
    
    .btn-submit {
        background: #5a67d8;
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 10px;
        font-size: 1.05rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
        margin-top: 25px;
        box-shadow: 0 4px 12px rgba(90, 103, 216, 0.25);
    }
    
    .btn-submit:hover {
        background: #4c5bc7;
        box-shadow: 0 6px 16px rgba(90, 103, 216, 0.35);
        color: white;
    }
    
    .btn-submit:active {
        transform: scale(0.98);
    }
    
    .btn-edit-profile {
        background: linear-gradient(135deg, #4EA685 0%, #57B894 100%);
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-edit-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 166, 133, 0.35);
        color: white;
    }
    
    .btn-update {
        background: linear-gradient(135deg, #4EA685 0%, #57B894 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 10px;
        font-size: 1.05rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(78, 166, 133, 0.25);
    }
    
    .btn-update:hover {
        background: linear-gradient(135deg, #45976D 0%, #4EA685 100%);
        box-shadow: 0 6px 16px rgba(78, 166, 133, 0.35);
        color: white;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 2;
    }
    
    .status-badge i {
        font-size: 1.1rem;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .status-pending {
        background: rgba(243, 156, 18, 0.95);
        color: white;
    }
    
    .status-verified {
        background: rgba(39, 174, 96, 0.95);
        color: white;
    }
    
    .status-suspended {
        background: rgba(231, 76, 60, 0.95);
        color: white;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 35px;
        border-radius: 16px 16px 0 0;
        margin: -45px -45px 0 -45px;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }
    
    .profile-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        transform: translate(-30%, 30%);
    }
    
    .profile-header .header-content {
        position: relative;
        z-index: 1;
    }
    
    .profile-header h3 {
        color: white;
        font-weight: 700;
        margin-bottom: 8px;
        font-size: 2rem;
        display: flex;
        align-items: center;
        gap: 15px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .profile-header h3 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 12px;
        border-radius: 50%;
        font-size: 1.5rem;
    }
    
    .profile-header .user-email {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.05rem;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .profile-header .user-email i {
        font-size: 0.9rem;
    }
    
    .profile-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin: 35px 0 30px;
    }
    
    .info-item {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        padding: 24px 20px;
        border-radius: 16px;
        border: 1px solid #e8ecf7;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .info-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .info-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        border-color: #d0d7f7;
    }
    
    .info-item:hover::before {
        opacity: 1;
    }
    
    .info-item label {
        font-size: 0.75rem;
        color: #8b92b0;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }
    
    .info-item label i {
        font-size: 0.85rem;
        opacity: 0.7;
    }
    
    .info-item .value {
        font-size: 1.25rem;
        color: #2d3748;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-item .value i {
        color: #667eea;
        font-size: 1rem;
    }
    
    .service-areas-list {
        background: linear-gradient(135deg, #f0f4ff 0%, #f8f9ff 100%);
        padding: 28px;
        border-radius: 20px;
        margin-top: 25px;
        border: 1px solid #e0e7ff;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
    }
    
    .service-areas-list h6 {
        color: #2d3748;
        font-weight: 700;
        margin-bottom: 18px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .service-areas-list h6 i {
        color: #667eea;
        font-size: 1.2rem;
    }
    
    .service-areas-list p {
        color: #4a5568;
        line-height: 1.7;
        font-size: 0.95rem;
    }
    
    .area-badge {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-right: 10px;
        margin-bottom: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.25);
        gap: 6px;
    }
    
    .area-badge::before {
        content: '📍';
        font-size: 0.9rem;
    }
    
    .area-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }
    
    /* Custom Checkbox Styling - Force Override */
    .form-check {
        padding-left: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        min-height: auto !important;
    }
    
    .form-check-input {
        width: 20px !important;
        height: 20px !important;
        min-width: 20px !important;
        min-height: 20px !important;
        border: 2px solid #d1d5db !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        margin: 0 !important;
        margin-top: 0 !important;
        flex-shrink: 0 !important;
        background-color: white !important;
        background-size: 70% 70% !important;
        background-position: center !important;
        position: relative !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
    }
    
    .form-check-input:checked {
        background-color: #5a67d8 !important;
        border-color: #5a67d8 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.15) !important;
        border-color: #5a67d8 !important;
        outline: none !important;
    }
    
    .form-check-input:hover {
        border-color: #5a67d8 !important;
    }
    
    .form-check-label {
        cursor: pointer !important;
        color: #374151 !important;
        font-size: 0.95rem !important;
        margin: 0 !important;
        margin-bottom: 0 !important;
        margin-left: 0 !important;
        user-select: none !important;
        padding-left: 0 !important;
    }
    
    .alert {
        border-radius: 16px;
        padding: 20px 24px;
        margin: 25px 0;
        border: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }
    
    .alert::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
    }
    
    .alert i {
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    
    .alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
        color: #1e40af;
    }
    
    .alert-info::before {
        background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
    }
    
    .alert-info i {
        color: #3b82f6;
    }
    
    .alert-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
        color: #92400e;
    }
    
    .alert-warning::before {
        background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
    }
    
    .alert-warning i {
        color: #f59e0b;
    }
    
    .alert strong {
        font-weight: 700;
    }
    
    /* Validation Styles */
    .form-control.is-invalid,
    .form-select.is-invalid,
    select.form-control.is-invalid {
        border-color: #e74c3c !important;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
        background-image: none !important;
        background-color: #fff !important;
    }
    
    .form-control.is-valid,
    .form-select.is-valid,
    select.form-control.is-valid {
        border-color: #27ae60 !important;
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1) !important;
        background-image: none !important;
        background-color: #fff !important;
    }
    
    .invalid-feedback {
        display: none;
        color: #e74c3c;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    .invalid-feedback.show {
        display: block;
    }
    
    .form-control.is-invalid ~ .invalid-feedback {
        display: block;
    }
    
    .char-count {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
        display: block;
    }
    
    .char-count.text-danger {
        color: #e74c3c !important;
    }
    
    .char-count.text-warning {
        color: #f39c12 !important;
    }
    
    @media (max-width: 768px) {
        .collector-application-area {
            padding: 60px 0;
        }
        
        .application-card, .profile-card {
            padding: 25px;
        }
        
        .section-title h2 {
            font-size: 2rem;
        }
        
        .profile-info {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="collector-application-area">
    <div class="container">
        <!-- Section Title -->
        <div class="section-title">
            <h2>
                @if($collector)
                    My Collector Profile
                @else
                    Become a Collector
                @endif
            </h2>
            <p>
                @if($collector)
                    View and manage your collector profile. Update your information to improve your service.
                @else
                    Join our network of waste collectors and contribute to a cleaner environment while earning income.
                @endif
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($collector)
                    <!-- Existing Collector Profile -->
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="header-content">
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div>
                                        <h3>
                                            <i class="fas fa-user-tie"></i>
                                            {{ auth()->user()->name }}
                                        </h3>
                                        <p class="user-email">
                                            <i class="fas fa-envelope"></i>
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                    <span class="status-badge status-{{ $collector->verification_status }}">
                                        <i class="fas fa-{{ $collector->verification_status === 'verified' ? 'check-circle' : ($collector->verification_status === 'pending' ? 'clock' : 'times-circle') }}"></i>
                                        {{ $collector->verification_status_formatted }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($collector->verification_status === 'pending')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Application Under Review:</strong> Your collector application is currently being reviewed by our admin team. We'll notify you once your profile is verified.
                                </div>
                            </div>
                        @elseif($collector->verification_status === 'verified')
                            <div class="alert alert-info">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Active Collector:</strong> Your profile is verified! You can now be assigned to waste collection requests.
                                </div>
                            </div>
                        @endif

                        <!-- Profile Information Display -->
                        <div class="profile-info">
                            @if($collector->company_name)
                                <div class="info-item">
                                    <label><i class="fas fa-building"></i> Company Name</label>
                                    <div class="value">{{ $collector->company_name }}</div>
                                </div>
                            @endif
                            <div class="info-item">
                                <label><i class="fas fa-truck"></i> Vehicle Type</label>
                                <div class="value">{{ $collector->vehicle_type_formatted }}</div>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-weight"></i> Collection Capacity</label>
                                <div class="value">{{ number_format($collector->capacity_kg, 2) }} kg</div>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-check-circle"></i> Total Collections</label>
                                <div class="value">{{ $collector->total_collections }} <i class="fas fa-recycle"></i></div>
                            </div>
                            @if($collector->rating > 0)
                                <div class="info-item">
                                    <label><i class="fas fa-star"></i> Rating</label>
                                    <div class="value">
                                        {{ number_format($collector->rating, 1) }}/5.0 
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($collector->bio)
                            <div class="service-areas-list">
                                <h6><i class="fas fa-info-circle me-2"></i>Bio</h6>
                                <p class="mb-0">{{ $collector->bio }}</p>
                            </div>
                        @endif

                        @if($collector->service_areas && count($collector->service_areas) > 0)
                            <div class="service-areas-list">
                                <h6><i class="fas fa-map-marked-alt me-2"></i>Service Areas</h6>
                                @foreach($collector->service_areas as $area)
                                    <span class="area-badge">{{ $area }}</span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Edit Profile Button -->
                        <div class="mt-4 text-center">
                            <button type="button" class="btn-edit-profile" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit"></i>
                                Edit Profile
                            </button>
                        </div>
                    </div>
                    
                    <!-- Edit Profile Modal -->
                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 16px; border: none;">
                                <div class="modal-header" style="border-bottom: 2px solid #e5e7eb; padding: 24px 30px;">
                                    <h5 class="modal-title" id="editProfileModalLabel" style="color: #2c3e50; font-weight: 700; font-size: 1.4rem;">
                                        <i class="fas fa-edit me-2" style="color: #4EA685;"></i>
                                        Update Your Profile
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="padding: 30px;">
                                    <form method="POST" action="{{ route('front.collector-application.update') }}" id="editProfileForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name">Company Name (Optional)</label>
                                        <input type="text" name="company_name" id="company_name" class="form-control" 
                                               placeholder="Your company or organization name" 
                                               value="{{ old('company_name', $collector->company_name) }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vehicle_type">Vehicle Type *</label>
                                        <select name="vehicle_type" id="vehicle_type" class="form-select" required>
                                            <option value="">Select your vehicle type</option>
                                            @foreach(\App\Models\Collector::getVehicleTypes() as $key => $label)
                                                <option value="{{ $key }}" {{ old('vehicle_type', $collector->vehicle_type) == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="capacity_kg">Collection Capacity (kg) *</label>
                                        <input type="number" name="capacity_kg" id="capacity_kg" class="form-control" 
                                               placeholder="Maximum capacity you can collect" 
                                               step="0.01" min="1" required
                                               value="{{ old('capacity_kg', $collector->capacity_kg) }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Service Areas (Governorates) *</label>
                                        <small class="text-muted d-block mb-2">Select all governorates where you provide collection services</small>
                                        <div class="row">
                                            @php $collectorAreas = is_array($collector->service_areas) ? $collector->service_areas : json_decode($collector->service_areas ?? '[]', true) @endphp
                                            @foreach(\App\Helpers\TunisiaStates::getStates() as $key => $label)
                                                <div class="col-md-4 col-sm-6 mb-2">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="service_areas[]" value="{{ $key }}" 
                                                               id="area_edit_{{ $key }}" class="form-check-input"
                                                               {{ in_array($key, $collectorAreas) ? 'checked' : '' }}>
                                                        <label for="area_edit_{{ $key }}" class="form-check-label">{{ $label }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea name="bio" id="bio" class="form-control" rows="4" 
                                          placeholder="Tell us about yourself and your collection service...">{{ old('bio', $collector->bio) }}</textarea>
                            </div>

                            <button type="submit" class="btn-update">
                                <i class="fas fa-save me-2"></i>
                                Update Profile
                            </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- New Collector Application Form -->
                    <div class="application-card">
                        <h3 class="mb-4" style="color: #2c3e50; font-weight: 700;">
                            <i class="fas fa-file-signature me-2"></i>Submit Collector Application
                        </h3>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Why become a collector?</strong> Help your community by collecting waste while earning income. Once approved, you'll be able to accept waste collection requests.
                        </div>
                        
                        <form method="POST" action="{{ route('front.collector-application.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name">Company Name (Optional)</label>
                                        <input type="text" name="company_name" id="company_name" class="form-control" 
                                               placeholder="Your company or organization name" 
                                               value="{{ old('company_name') }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vehicle_type">Vehicle Type *</label>
                                        <select name="vehicle_type" id="vehicle_type" class="form-select" required>
                                            <option value="">Select your vehicle type</option>
                                            @foreach(\App\Models\Collector::getVehicleTypes() as $key => $label)
                                                <option value="{{ $key }}" {{ old('vehicle_type') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="capacity_kg">Collection Capacity (kg) *</label>
                                <input type="number" name="capacity_kg" id="capacity_kg" class="form-control" 
                                       placeholder="Maximum capacity you can collect" 
                                       step="0.01" min="1" required
                                       value="{{ old('capacity_kg') }}">
                            </div>
                            
                            <div class="form-group">
                                <label>Service Areas (Governorates) *</label>
                                <small class="text-muted d-block mb-2">Select all governorates where you provide collection services</small>
                                <div class="row">
                                    @foreach(\App\Helpers\TunisiaStates::getStates() as $key => $label)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" name="service_areas[]" value="{{ $key }}" 
                                                       id="area_new_{{ $key }}" class="form-check-input"
                                                       {{ is_array(old('service_areas')) && in_array($key, old('service_areas')) ? 'checked' : '' }}>
                                                <label for="area_new_{{ $key }}" class="form-check-label">{{ $label }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea name="bio" id="bio" class="form-control" rows="4" 
                                          placeholder="Tell us about yourself and why you want to become a collector...">{{ old('bio') }}</textarea>
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Application
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const companyName = form.querySelector('[name="company_name"]');
        const vehicleType = form.querySelector('[name="vehicle_type"]');
        const capacity = form.querySelector('[name="capacity_kg"]');
        const serviceAreaCheckboxes = form.querySelectorAll('[name="service_areas[]"]');
        const bio = form.querySelector('[name="bio"]');
        
        // Validation functions
        function clearValidation(field) {
            field.classList.remove('is-valid', 'is-invalid');
            const errorEl = field.closest('.form-group')?.querySelector('.invalid-feedback');
            if (errorEl) errorEl.remove();
        }
        
        function setInvalid(field, message) {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            let errorEl = field.closest('.form-group')?.querySelector('.invalid-feedback');
            if (!errorEl) {
                errorEl = document.createElement('div');
                errorEl.className = 'invalid-feedback show';
                field.parentNode.appendChild(errorEl);
            }
            errorEl.textContent = message;
            return false;
        }
        
        function setValid(field) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            const errorEl = field.closest('.form-group')?.querySelector('.invalid-feedback');
            if (errorEl) errorEl.remove();
            return true;
        }
        
        function sanitizeCapacity(input) {
            let value = input.value.replace(/[^0-9.]/g, '');
            const parts = value.split('.');
            if (parts.length > 2) value = parts[0] + '.' + parts.slice(1).join('');
            if (parts[1] && parts[1].length > 2) value = parts[0] + '.' + parts[1].substring(0, 2);
            input.value = value;
        }
        
        function updateCharCount(field, maxLength) {
            const currentLength = field.value.length;
            let countEl = field.closest('.form-group')?.querySelector('.char-count');
            if (!countEl) {
                countEl = document.createElement('small');
                countEl.className = 'char-count';
                field.parentNode.appendChild(countEl);
            }
            countEl.textContent = `${currentLength}/${maxLength} characters`;
            if (currentLength > maxLength) countEl.classList.add('text-danger');
            else if (currentLength > maxLength * 0.9) countEl.classList.add('text-warning');
            else countEl.classList.remove('text-danger', 'text-warning');
        }
        
        function validateCompanyName(field) {
            if (!field) return true;
            const value = field.value.trim();
            clearValidation(field);
            
            if (value && value.length > 255) {
                return setInvalid(field, 'Company name cannot exceed 255 characters');
            } else if (value && !/^[a-zA-Z0-9\s\.\,\-\&]+$/.test(value)) {
                return setInvalid(field, 'Company name contains invalid characters');
            }
            if (value) setValid(field);
            return true;
        }
        
        function validateVehicleType(field) {
            if (!field) return true;
            clearValidation(field);
            if (!field.value) return setInvalid(field, 'Please select your vehicle type');
            return setValid(field);
        }
        
        function validateCapacity(field) {
            if (!field) return true;
            const value = field.value.trim();
            clearValidation(field);
            
            if (!value) return setInvalid(field, 'Please enter your collection capacity');
            const cap = parseFloat(value);
            if (isNaN(cap) || cap <= 0) return setInvalid(field, 'Capacity must be a positive number');
            if (cap < 1) return setInvalid(field, 'Capacity must be at least 1 kg');
            if (cap > 99999.99) return setInvalid(field, 'Capacity cannot exceed 99,999.99 kg');
            if (!/^\d+(\.\d{1,2})?$/.test(value)) return setInvalid(field, 'Capacity can have maximum 2 decimal places');
            return setValid(field);
        }
        
        function validateServiceAreas(checkboxes) {
            if (!checkboxes || checkboxes.length === 0) return true;
            const checked = Array.from(checkboxes).filter(cb => cb.checked);
            const container = checkboxes[0].closest('.form-group');
            const errorEl = container?.querySelector('.invalid-feedback');
            
            if (checked.length === 0) {
                if (!errorEl) {
                    const error = document.createElement('div');
                    error.className = 'invalid-feedback show';
                    error.textContent = 'Please select at least one governorate';
                    container.querySelector('.row').after(error);
                }
                return false;
            } else {
                if (errorEl) errorEl.remove();
                return true;
            }
        }
        
        function validateBio(field) {
            if (!field) return true;
            const value = field.value.trim();
            clearValidation(field);
            
            if (value && value.length > 1000) {
                return setInvalid(field, 'Bio cannot exceed 1000 characters');
            } else if (value && !/^[a-zA-Z0-9\s\.\,\-\!\?\(\)]*$/.test(value)) {
                return setInvalid(field, 'Bio contains invalid characters');
            }
            if (value) setValid(field);
            return true;
        }
        
        // Event listeners
        if (companyName) {
            companyName.addEventListener('blur', () => validateCompanyName(companyName));
            companyName.addEventListener('input', () => validateCompanyName(companyName));
        }
        
        if (vehicleType) {
            vehicleType.addEventListener('change', () => validateVehicleType(vehicleType));
            vehicleType.addEventListener('blur', () => validateVehicleType(vehicleType));
        }
        
        if (capacity) {
            capacity.addEventListener('input', function() { 
                sanitizeCapacity(this); 
                validateCapacity(this); 
            });
            capacity.addEventListener('blur', () => validateCapacity(capacity));
        }
        
        if (serviceAreaCheckboxes.length > 0) {
            serviceAreaCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => validateServiceAreas(serviceAreaCheckboxes));
            });
        }
        
        if (bio) {
            bio.addEventListener('input', function() { 
                validateBio(this); 
                updateCharCount(this, 1000); 
            });
            bio.addEventListener('blur', () => validateBio(bio));
        }
        
        // Form submission
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            if (companyName && companyName.value && !validateCompanyName(companyName)) isValid = false;
            if (vehicleType && !validateVehicleType(vehicleType)) isValid = false;
            if (capacity && !validateCapacity(capacity)) isValid = false;
            if (serviceAreaCheckboxes.length > 0 && !validateServiceAreas(serviceAreaCheckboxes)) isValid = false;
            if (bio && bio.value && !validateBio(bio)) isValid = false;
            
            if (!isValid) {
                e.preventDefault();
                const firstInvalid = form.querySelector('.is-invalid, .invalid-feedback.show');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                submitBtn.disabled = true;
            }
        });
    });
});
</script>
@endpush
