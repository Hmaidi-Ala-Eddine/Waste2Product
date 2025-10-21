@extends('layouts.front')

@section('title', 'My Waste Requests')

@push('styles')
<style>
    /* Force dark navbar text on this page only */
    .navbar-nav > li > a,
    .navbar-brand,
    .attr-nav > ul > li > a {
        color: #2c3e50 !important;
    }
    
    .waste-request-area {
        padding: 100px 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .section-title h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .section-title p {
        font-size: 1.1rem;
        color: #7f8c8d;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .request-form-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        margin-bottom: 40px;
        border: 1px solid #e8ecef;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }
    
    .form-control {
        border: 2px solid #e8ecef;
        border-radius: 12px;
        padding: 15px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .form-control:focus {
        border-color: #4EA685;
        box-shadow: 0 0 0 0.2rem rgba(78, 166, 133, 0.25);
        background: white;
    }
    
    .form-select {
        border: 2px solid #e8ecef;
        border-radius: 12px;
        padding: 15px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .form-select:focus {
        border-color: #4EA685;
        box-shadow: 0 0 0 0.2rem rgba(78, 166, 133, 0.25);
        background: white;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #4EA685 0%, #57B894 100%);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 20px;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(78, 166, 133, 0.4);
        color: white;
    }
    
    .requests-list-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        border: 1px solid #e8ecef;
    }
    
    .request-item {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        border: 1px solid #e8ecef;
        transition: all 0.3s ease;
    }
    
    .request-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .request-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .waste-type-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: white;
    }
    
    .status-pending { background: #f39c12; }
    .status-accepted { background: #3498db; }
    .status-collected { background: #27ae60; }
    .status-cancelled { background: #e74c3c; }
    
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
        color: #4EA685;
        width: 20px;
    }
    
    .detail-item span {
        color: #2c3e50;
        font-weight: 500;
    }
    
    .request-description {
        background: white;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #4EA685;
        margin-top: 15px;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #bdc3c7;
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #2c3e50;
    }
    
    /* Enhanced validation styles */
    .form-control.is-invalid,
    .form-select.is-invalid,
    select.form-control.is-invalid {
        border-color: #e74c3c !important;
        box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.15) !important;
        background-image: none !important;
        background-color: #fff !important;
    }
    
    .form-control.is-valid,
    .form-select.is-valid,
    select.form-control.is-valid {
        border-color: #27ae60 !important;
        box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.15) !important;
        background-image: none !important;
        background-color: #fff !important;
    }
    
    .error-text {
        color: #e74c3c;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: none;
        font-weight: 500;
    }
    
    .error-text.show {
        display: block;
    }
    
    .char-count {
        font-size: 0.75rem;
        color: #7f8c8d;
        margin-top: 0.25rem;
        display: block;
    }
    
    .char-count.text-danger {
        color: #e74c3c !important;
    }
    
    .char-count.text-warning {
        color: #f39c12 !important;
    }
    
    /* AI Helper Styles */
    .ai-helper-box {
        background: linear-gradient(135deg, #667eea10 0%, #764ba220 100%);
        border: 2px dashed #667eea;
        border-radius: 12px;
        padding: 15px;
        margin-top: 8px;
    }

    .btn-ai-detect {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 10px;
    }

    .btn-ai-detect:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-ai-detect:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .ai-result {
        margin-top: 12px;
        padding: 12px;
        border-radius: 8px;
        font-size: 0.9rem;
        animation: slideDown 0.3s ease;
    }

    .ai-result.success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .ai-result.error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .ai-result strong {
        display: block;
        margin-bottom: 4px;
        font-size: 1rem;
    }

    .ai-suggestion {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: white;
        padding: 6px 12px;
        border-radius: 6px;
        margin-top: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .ai-suggestion:hover {
        background: #667eea;
        color: white;
        transform: scale(1.05);
    }

    .description-enhancer-wrapper {
        position: relative;
    }

    .btn-enhance {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: linear-gradient(135deg, #4EA685 0%, #57B894 100%);
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .btn-enhance:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 166, 133, 0.4);
    }

    .btn-enhance:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Toast Notifications */
    .toast-notification {
        position: fixed;
        top: 100px;
        right: -400px;
        background: white;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 10000;
        min-width: 320px;
        transition: right 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .toast-notification.show { right: 30px; }
    .toast-notification.success { border-left: 4px solid #4CAF50; }
    .toast-notification.error { border-left: 4px solid #f44336; }

    .toast-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-notification.success .toast-icon {
        background: #e8f5e9;
        color: #4CAF50;
    }

    .toast-notification.error .toast-icon {
        background: #ffebee;
        color: #f44336;
    }

    .toast-content { flex: 1; }
    .toast-message {
        color: #2c3e50;
        font-size: 15px;
        font-weight: 600;
    }

    .toast-close {
        background: none;
        border: none;
        color: #7f8c8d;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .toast-close:hover {
        background: #f0f0f0;
        color: #2c3e50;
    }

    @media (max-width: 768px) {
        .toast-notification {
            right: -100%;
            min-width: calc(100% - 40px);
            left: 20px;
        }

        .toast-notification.show { right: 0; }

        .waste-request-area {
            padding: 60px 0;
        }
        
        .request-form-card,
        .requests-list-card {
            padding: 25px;
        }
        
        .section-title h2 {
            font-size: 2rem;
        }
        
        .request-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .request-details {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="waste-request-area">
    <div class="container">
        <!-- Section Title -->
        <div class="section-title">
            <h2>Waste Collection Requests</h2>
            <p>Submit your waste collection requests and track their status. Help us create a cleaner environment together.</p>
        </div>

        <div class="row">
            <!-- Request Form -->
            <div class="col-lg-5 mb-4">
                <div class="request-form-card">
                    <h3 class="mb-4" style="color: #2c3e50; font-weight: 700;">Submit New Request</h3>
                    
                    <form id="wasteRequestForm" method="POST" action="{{ route('front.waste-requests.store') }}">
                        @csrf
                        
                        <!-- AI Waste Type Detector -->
                        <div class="form-group">
                            <label for="ai_waste_description">🤖 AI Waste Type Helper</label>
                            <div class="ai-helper-box">
                                <input type="text" id="ai_waste_description" class="form-control" 
                                       placeholder="Describe your waste... e.g., 'old laptop and chargers'" 
                                       maxlength="200">
                                <button type="button" id="detectWasteBtn" class="btn-ai-detect">
                                    <span class="btn-text">
                                        <i class="fas fa-magic"></i> Detect Type
                                    </span>
                                    <span class="btn-loader" style="display:none;">
                                        <i class="fas fa-spinner fa-spin"></i> Detecting...
                                    </span>
                                </button>
                                <div id="aiDetectionResult" class="ai-result" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="waste_type">Waste Type *</label>
                            <select name="waste_type" id="waste_type" class="form-select" required>
                                <option value="">Select waste type</option>
                                @foreach(\App\Models\WasteRequest::getWasteTypes() as $key => $label)
                                    <option value="{{ $key }}" {{ old('waste_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="error-text" id="waste_type_error">Please select a waste type</div>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity (kg) *</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" 
                                   placeholder="Enter quantity in kg" step="0.01" min="0.01" 
                                   value="{{ old('quantity') }}" required>
                            <div class="error-text" id="quantity_error">Please enter a valid quantity</div>
                        </div>

                        <div class="form-group">
                            <label for="state">Governorate / State *</label>
                            <select name="state" id="state" class="form-select" required>
                                <option value="">Select your governorate</option>
                                @foreach(\App\Helpers\TunisiaStates::getStates() as $key => $label)
                                    <option value="{{ $key }}" {{ old('state') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="error-text" id="state_error">Please select a governorate</div>
                        </div>

                        <div class="form-group">
                            <label for="address">Specific Address *</label>
                            <textarea name="address" id="address" class="form-control" rows="3" 
                                      placeholder="Enter specific address (street, building, floor, etc.)" required>{{ old('address') }}</textarea>
                            <div class="error-text" id="address_error">Please enter your specific address</div>
                        </div>

                        <div class="form-group">
                            <label for="description">Additional Notes</label>
                            <div class="description-enhancer-wrapper">
                                <textarea name="description" id="description" class="form-control" rows="3" 
                                          placeholder="Any additional information about your waste...">{{ old('description') }}</textarea>
                                <button type="button" id="enhanceDescBtn" class="btn-enhance" title="Enhance with AI">
                                    <span class="btn-text">
                                        <i class="fas fa-sparkles"></i> Enhance with AI
                                    </span>
                                    <span class="btn-loader" style="display:none;">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>
                            Submit Request
                        </button>
                    </form>
                </div>
            </div>

            <!-- Requests List -->
            <div class="col-lg-7">
                <div class="requests-list-card">
                    <h3 class="mb-4" style="color: #2c3e50; font-weight: 700;">My Requests</h3>
                    
                    @if($wasteRequests->count() > 0)
                        @foreach($wasteRequests as $request)
                            <div class="request-item">
                                <div class="request-header">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="waste-type-badge">{{ $request->waste_type_formatted }}</span>
                                        <span class="status-badge status-{{ $request->status }}">{{ $request->status_formatted }}</span>
                                    </div>
                                    <small class="text-muted">{{ $request->created_at->format('M d, Y') }}</small>
                                </div>
                                
                                <div class="request-details">
                                    <div class="detail-item">
                                        <i class="fas fa-weight-hanging"></i>
                                        <span>{{ $request->quantity }} kg</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-map-pin"></i>
                                        <span>{{ $request->state }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($request->address, 30) }}</span>
                                    </div>
                                    @if($request->collector)
                                        <div class="detail-item">
                                            <i class="fas fa-user-tie"></i>
                                            <div class="d-flex flex-column">
                                                <span>{{ $request->collector->name }}</span>
                                                
                                                @if($request->status === 'collected')
                                                    <!-- Star Rating System -->
                                                    <div class="star-rating mt-2" data-request-id="{{ $request->id }}" data-current-rating="{{ $request->rating->rating ?? 0 }}">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star star-icon" 
                                                               data-rating="{{ $i }}" 
                                                               style="color: {{ ($request->rating && $i <= $request->rating->rating) ? '#fbbf24' : '#e5e7eb' }}; 
                                                                      font-size: 1.2rem; 
                                                                      cursor: pointer; 
                                                                      transition: color 0.2s;">
                                                            </i>
                                                        @endfor
                                                        @if($request->rating)
                                                            <small class="text-success ms-2">Rated</small>
                                                        @else
                                                            <small class="text-muted ms-2">Rate</small>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if($request->collected_at)
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-check"></i>
                                            <span>{{ $request->collected_at->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($request->description)
                                    <div class="request-description">
                                        <strong>Notes:</strong> {{ $request->description }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        
                        <!-- Pagination -->
                        @if($wasteRequests->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $wasteRequests->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-recycle"></i>
                            <h3>No Requests Yet</h3>
                            <p>You haven't submitted any waste collection requests yet. Submit your first request using the form on the left.</p>
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('wasteRequestForm');
    const wasteType = document.getElementById('waste_type');
    const quantity = document.getElementById('quantity');
    const state = document.getElementById('state');
    const address = document.getElementById('address');
    const description = document.getElementById('description');
    
    // Clear validation state
    function clearValidation(field) {
        field.classList.remove('is-valid', 'is-invalid');
        const errorEl = document.getElementById(field.id + '_error');
        if (errorEl) {
            errorEl.classList.remove('show');
            errorEl.textContent = '';
        }
    }
    
    // Set field as invalid
    function setInvalid(field, message) {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        const errorEl = document.getElementById(field.id + '_error');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.add('show');
        }
        return false;
    }
    
    // Set field as valid
    function setValid(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        const errorEl = document.getElementById(field.id + '_error');
        if (errorEl) {
            errorEl.classList.remove('show');
            errorEl.textContent = '';
        }
        return true;
    }
    
    // Sanitize quantity input
    function sanitizeQuantity(input) {
        let value = input.value.replace(/[^0-9.]/g, '');
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        if (parts[1] && parts[1].length > 2) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        }
        input.value = value;
    }
    
    // Character counter
    function updateCharCount(field, maxLength) {
        const currentLength = field.value.length;
        let countEl = field.parentElement.querySelector('.char-count');
        if (!countEl) {
            countEl = document.createElement('small');
            countEl.className = 'char-count';
            field.parentElement.appendChild(countEl);
        }
        countEl.textContent = `${currentLength}/${maxLength} characters`;
        if (currentLength > maxLength) {
            countEl.classList.add('text-danger');
            countEl.classList.remove('text-warning');
        } else if (currentLength > maxLength * 0.9) {
            countEl.classList.add('text-warning');
            countEl.classList.remove('text-danger');
        } else {
            countEl.classList.remove('text-danger', 'text-warning');
        }
    }
    
    // Validate individual field
    function validateField(field) {
        const value = field.value.trim();
        clearValidation(field);
        
        switch(field.id) {
            case 'waste_type':
                if (!value) return setInvalid(field, 'Please select a waste type');
                return setValid(field);
                
            case 'quantity':
                if (!value) return setInvalid(field, 'Please enter the quantity');
                const qty = parseFloat(value);
                if (isNaN(qty) || qty <= 0) return setInvalid(field, 'Quantity must be a positive number');
                if (qty < 0.1) return setInvalid(field, 'Quantity must be at least 0.1 kg');
                if (qty > 999999.99) return setInvalid(field, 'Quantity cannot exceed 999,999.99 kg');
                if (!/^\d+(\.\d{1,2})?$/.test(value)) return setInvalid(field, 'Quantity can have maximum 2 decimal places');
                return setValid(field);
                
            case 'state':
                if (!value) return setInvalid(field, 'Please select a governorate');
                return setValid(field);
                
            case 'address':
                if (!value) return setInvalid(field, 'Please enter your specific address');
                if (value.length < 10) return setInvalid(field, 'Address must be at least 10 characters long');
                if (value.length > 1000) return setInvalid(field, 'Address cannot exceed 1000 characters');
                if (!/^[a-zA-Z0-9\s\.,\-\#\/]+$/.test(value)) return setInvalid(field, 'Address contains invalid characters');
                return setValid(field);
                
            case 'description':
                if (value && value.length > 2000) return setInvalid(field, 'Description cannot exceed 2000 characters');
                // Allow most common characters including quotes, apostrophes, colons, etc for AI-enhanced descriptions
                if (value && !/^[a-zA-Z0-9\s\.,\-\!\?\(\)\"\'\:\;\/\&\%\@\#\*\+\=\[\]\{\}]*$/.test(value)) return setInvalid(field, 'Description contains invalid characters');
                if (value) return setValid(field);
                return true;
                
            default:
                return true;
        }
    }
    
    // Event listeners
    wasteType.addEventListener('change', function() { validateField(this); });
    wasteType.addEventListener('blur', function() { validateField(this); });
    
    quantity.addEventListener('input', function() { 
        sanitizeQuantity(this);
        validateField(this);
    });
    quantity.addEventListener('blur', function() { validateField(this); });
    
    state.addEventListener('change', function() { validateField(this); });
    state.addEventListener('blur', function() { validateField(this); });
    
    address.addEventListener('input', function() { 
        validateField(this); 
        updateCharCount(this, 1000);
    });
    address.addEventListener('blur', function() { validateField(this); });
    
    if (description) {
        description.addEventListener('input', function() { 
            validateField(this); 
            updateCharCount(this, 2000);
        });
        description.addEventListener('blur', function() { validateField(this); });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate all required fields
        if (!validateField(wasteType)) isValid = false;
        if (!validateField(quantity)) isValid = false;
        if (!validateField(state)) isValid = false;
        if (!validateField(address)) isValid = false;
        if (description && description.value && !validateField(description)) isValid = false;
        
        if (!isValid) {
            e.preventDefault();
            // Focus on first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('.btn-submit');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
        submitBtn.disabled = true;
    });

    // ============ STAR RATING SYSTEM ============
    // Handle star hover effects
    document.querySelectorAll('.star-rating').forEach(ratingContainer => {
        const stars = ratingContainer.querySelectorAll('.star-icon');
        const requestId = ratingContainer.dataset.requestId;
        let currentRating = parseInt(ratingContainer.dataset.currentRating) || 0;
        
        stars.forEach((star, index) => {
            // Hover effect
            star.addEventListener('mouseenter', function() {
                const hoverRating = parseInt(this.dataset.rating);
                updateStarColors(stars, hoverRating);
            });
            
            // Reset on mouse leave
            ratingContainer.addEventListener('mouseleave', function() {
                updateStarColors(stars, currentRating);
            });
            
            // Click to rate
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                submitRating(requestId, rating, stars, ratingContainer);
            });
        });
    });

    function updateStarColors(stars, rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#fbbf24'; // Gold
            } else {
                star.style.color = '#e5e7eb'; // Gray
            }
        });
    }

    function submitRating(requestId, rating, stars, container) {
        // Show loading
        const originalHtml = container.innerHTML;
        container.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rating...';
        
        fetch(`/waste-requests/${requestId}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rating: rating })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update current rating
                container.dataset.currentRating = rating;
                
                // Show success message
                container.innerHTML = originalHtml.replace('Rate', 'Rated');
                updateStarColors(container.querySelectorAll('.star-icon'), rating);
                
                // Show success notification
                showNotification('Thank you for rating the collector!', 'success');
            } else {
                showToast(data.error || 'Failed to submit rating', 'error');
                container.innerHTML = originalHtml;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'error');
            container.innerHTML = originalHtml;
        });
    }

    // Toast Notification System
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // ============ AI WASTE TYPE DETECTOR ============
    const aiWasteInput = document.getElementById('ai_waste_description');
    const detectBtn = document.getElementById('detectWasteBtn');
    const aiResult = document.getElementById('aiDetectionResult');
    const wasteTypeSelect = document.getElementById('waste_type');

    detectBtn.addEventListener('click', async function() {
        const description = aiWasteInput.value.trim();
        
        if (!description || description.length < 3) {
            showToast('Please describe your waste (at least 3 characters)', 'error');
            return;
        }

        // Show loading state
        detectBtn.disabled = true;
        detectBtn.querySelector('.btn-text').style.display = 'none';
        detectBtn.querySelector('.btn-loader').style.display = 'inline-block';
        aiResult.style.display = 'none';

        try {
            const response = await fetch('/chatbot/detect-waste-type', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ description })
            });

            const data = await response.json();

            if (data.success) {
                // Show success result
                aiResult.className = 'ai-result success';
                aiResult.innerHTML = `
                    <strong>✨ AI Detected: ${data.label}</strong>
                    <div class="ai-suggestion" data-type="${data.type}">
                        <i class="fas fa-check-circle"></i>
                        Click to select "${data.label}"
                    </div>
                `;
                aiResult.style.display = 'block';

                // Add click handler to suggestion
                aiResult.querySelector('.ai-suggestion').addEventListener('click', function() {
                    wasteTypeSelect.value = this.dataset.type;
                    validateField(wasteTypeSelect);
                    showToast(`Waste type set to "${data.label}"`, 'success');
                    aiResult.style.display = 'none';
                    aiWasteInput.value = '';
                });
            } else {
                // Show error result
                aiResult.className = 'ai-result error';
                aiResult.innerHTML = `<strong>⚠️ ${data.message || 'Could not detect waste type'}</strong>`;
                aiResult.style.display = 'block';
            }
        } catch (error) {
            console.error('AI Detection error:', error);
            showToast('AI detection failed. Please try again.', 'error');
        } finally {
            // Reset button state
            detectBtn.disabled = false;
            detectBtn.querySelector('.btn-text').style.display = 'inline-block';
            detectBtn.querySelector('.btn-loader').style.display = 'none';
        }
    });

    // ============ AI DESCRIPTION ENHANCER ============
    const descriptionField = document.getElementById('description');
    const enhanceBtn = document.getElementById('enhanceDescBtn');

    enhanceBtn.addEventListener('click', async function() {
        const description = descriptionField.value.trim();
        
        if (!description || description.length < 3) {
            showToast('Please write a description first (at least 3 characters)', 'error');
            return;
        }

        // Show loading state
        enhanceBtn.disabled = true;
        enhanceBtn.querySelector('.btn-text').style.display = 'none';
        enhanceBtn.querySelector('.btn-loader').style.display = 'inline-block';

        try {
            const wasteType = wasteTypeSelect.value || null;
            const quantityValue = quantity.value ? parseFloat(quantity.value) : null;
            const stateValue = state.value || null;
            const stateText = state.value ? state.options[state.selectedIndex].text : null;

            const response = await fetch('/chatbot/enhance-description', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    description,
                    waste_type: wasteType,
                    quantity: quantityValue,
                    state: stateText
                })
            });

            const data = await response.json();

            if (data.success) {
                // Replace description with enhanced version
                descriptionField.value = data.enhanced;
                
                // Trigger validation to clear red border
                validateField(descriptionField);
                
                showToast('✨ Description enhanced by AI!', 'success');
                
                // Add visual feedback
                descriptionField.style.background = '#d4edda';
                setTimeout(() => {
                    descriptionField.style.background = '';
                }, 2000);
            } else {
                showToast(data.message || 'Could not enhance description', 'error');
            }
        } catch (error) {
            console.error('AI Enhancement error:', error);
            showToast('AI enhancement failed. Please try again.', 'error');
        } finally {
            // Reset button state
            enhanceBtn.disabled = false;
            enhanceBtn.querySelector('.btn-text').style.display = 'inline-block';
            enhanceBtn.querySelector('.btn-loader').style.display = 'none';
        }
    });
});
</script>
@endpush
