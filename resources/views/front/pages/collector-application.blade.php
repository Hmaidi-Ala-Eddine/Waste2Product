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
        border-top: 4px solid #5a67d8;
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
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-align: center;
        margin-bottom: 25px;
    }
    
    .status-badge i {
        font-size: 1.1rem;
    }
    
    .status-pending {
        background: #f39c12;
        color: white;
    }
    
    .status-verified {
        background: #27ae60;
        color: white;
    }
    
    .status-suspended {
        background: #e74c3c;
        color: white;
    }
    
    .profile-header {
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .profile-header h3 {
        color: #1f2937;
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .profile-header h3 i {
        color: #5a67d8;
    }
    
    .profile-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    
    .info-item {
        background: #f9fafb;
        padding: 20px;
        border-radius: 10px;
        border-left: 3px solid #5a67d8;
        transition: all 0.2s ease;
    }
    
    .info-item:hover {
        background: #f3f4f6;
    }
    
    .info-item label {
        font-size: 0.85rem;
        color: #7f8c8d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        display: block;
    }
    
    .info-item .value {
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .service-areas-list {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-top: 20px;
    }
    
    .service-areas-list h6 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .area-badge {
        display: inline-block;
        background: #eef2ff;
        color: #5a67d8;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-right: 8px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    
    .area-badge:hover {
        background: #ddd6fe;
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
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 25px;
        border: 1px solid;
        font-weight: 400;
    }
    
    .alert-info {
        background: #eff6ff;
        color: #1e40af;
        border-color: #3b82f6;
    }
    
    .alert-warning {
        background: #fffbeb;
        color: #92400e;
        border-color: #f59e0b;
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
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h3>
                                    <i class="fas fa-user-tie me-2"></i>
                                    {{ auth()->user()->name }}
                                </h3>
                                <span class="status-badge status-{{ $collector->verification_status }}">
                                    <i class="fas fa-{{ $collector->verification_status === 'verified' ? 'check-circle' : ($collector->verification_status === 'pending' ? 'clock' : 'times-circle') }} me-2"></i>
                                    {{ $collector->verification_status_formatted }}
                                </span>
                            </div>
                        </div>

                        @if($collector->verification_status === 'pending')
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Application Under Review:</strong> Your collector application is currently being reviewed by our admin team. We'll notify you once your profile is verified.
                            </div>
                        @elseif($collector->verification_status === 'verified')
                            <div class="alert alert-info">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Active Collector:</strong> Your profile is verified! You can now be assigned to waste collection requests.
                            </div>
                        @endif

                        <!-- Profile Information Display -->
                        <div class="profile-info">
                            <div class="info-item">
                                <label>Email</label>
                                <div class="value">{{ auth()->user()->email }}</div>
                            </div>
                            @if($collector->company_name)
                                <div class="info-item">
                                    <label>Company Name</label>
                                    <div class="value">{{ $collector->company_name }}</div>
                                </div>
                            @endif
                            <div class="info-item">
                                <label>Vehicle Type</label>
                                <div class="value">{{ $collector->vehicle_type_formatted }}</div>
                            </div>
                            <div class="info-item">
                                <label>Collection Capacity</label>
                                <div class="value">{{ $collector->capacity_kg }} kg</div>
                            </div>
                            <div class="info-item">
                                <label>Total Collections</label>
                                <div class="value">{{ $collector->total_collections }}</div>
                            </div>
                            @if($collector->rating > 0)
                                <div class="info-item">
                                    <label>Rating</label>
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
    // Simple form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const vehicleType = form.querySelector('[name="vehicle_type"]');
            const capacity = form.querySelector('[name="capacity_kg"]');
            const serviceAreas = form.querySelector('[name="service_areas"]');
            
            let isValid = true;
            
            if (vehicleType && !vehicleType.value) {
                isValid = false;
                vehicleType.classList.add('is-invalid');
            }
            
            if (capacity && (!capacity.value || capacity.value <= 0)) {
                isValid = false;
                capacity.classList.add('is-invalid');
            }
            
            if (serviceAreas && !serviceAreas.value.trim()) {
                isValid = false;
                serviceAreas.classList.add('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
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
    
    // Remove invalid class on input
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>
@endpush
