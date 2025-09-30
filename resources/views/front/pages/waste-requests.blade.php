@extends('layouts.front')

@section('title', 'My Waste Requests')

@push('styles')
<style>
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
    
    /* Simple validation error text */
    .error-text {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        display: none;
    }
    
    @media (max-width: 768px) {
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
                            <label for="address">Collection Address *</label>
                            <textarea name="address" id="address" class="form-control" rows="3" 
                                      placeholder="Enter your full address for collection" required>{{ old('address') }}</textarea>
                            <div class="error-text" id="address_error">Please enter your address</div>
                        </div>

                        <div class="form-group">
                            <label for="description">Additional Notes</label>
                            <textarea name="description" id="description" class="form-control" rows="3" 
                                      placeholder="Any additional information about your waste...">{{ old('description') }}</textarea>
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
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($request->address, 30) }}</span>
                                    </div>
                                    @if($request->collector)
                                        <div class="detail-item">
                                            <i class="fas fa-user-tie"></i>
                                            <span>{{ $request->collector->name }}</span>
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
    const address = document.getElementById('address');
    
    // Simple validation function
    function validateField(field) {
        const errorElement = document.getElementById(field.id + '_error');
        
        if (field.id === 'waste_type' && !field.value) {
            errorElement.style.display = 'block';
            return false;
        } else if (field.id === 'quantity' && (!field.value || field.value <= 0)) {
            errorElement.style.display = 'block';
            return false;
        } else if (field.id === 'address' && !field.value.trim()) {
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.style.display = 'none';
            return true;
        }
    }
    
    // Add event listeners
    wasteType.addEventListener('blur', function() { validateField(this); });
    quantity.addEventListener('blur', function() { validateField(this); });
    address.addEventListener('blur', function() { validateField(this); });
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate all required fields
        if (!validateField(wasteType)) isValid = false;
        if (!validateField(quantity)) isValid = false;
        if (!validateField(address)) isValid = false;
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        const submitBtn = form.querySelector('.btn-submit');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
        submitBtn.disabled = true;
    });
});
</script>
@endpush
