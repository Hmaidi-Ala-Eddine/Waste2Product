@extends('layouts.front')

@section('title', 'Checkout')

@section('content')
<!-- Page Title -->
<section class="page-title" style="background-image: url({{ asset('assets/front/img/banner/page-title.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-content text-center">
                    <h1 class="title">Checkout</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.cart') }}">Cart</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Checkout Section -->
<section class="checkout-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-form">
                    <div class="checkout-header mb-4">
                        <h3>Checkout Information</h3>
                        <p class="text-muted">Please fill in your details to complete your order</p>
                    </div>

                    <form id="checkout-form">
                        @csrf
                        
                        <!-- Payment Method -->
                        <div class="form-section mb-4">
                            <h5>Payment Method</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                        <label class="form-check-label" for="credit_card">
                                            <i class="fas fa-credit-card me-2"></i>Credit Card
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                        <label class="form-check-label" for="paypal">
                                            <i class="fab fa-paypal me-2"></i>PayPal
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                        <label class="form-check-label" for="bank_transfer">
                                            <i class="fas fa-university me-2"></i>Bank Transfer
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                                        <label class="form-check-label" for="cash">
                                            <i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="form-section mb-4">
                            <h5>Shipping Address</h5>
                            <div class="form-group">
                                <label for="shipping_address" class="form-label">Full Address *</label>
                                <textarea class="form-control" 
                                          id="shipping_address" 
                                          name="shipping_address" 
                                          rows="3" 
                                          placeholder="Enter your complete shipping address"
                                          required></textarea>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="form-section mb-4">
                            <h5>Additional Notes</h5>
                            <div class="form-group">
                                <label for="notes" class="form-label">Order Notes (Optional)</label>
                                <textarea class="form-control" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Any special instructions or notes for your order"></textarea>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="form-section mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-section">
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="checkout-btn">
                                <i class="fas fa-credit-card me-2"></i>Complete Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="order-summary">
                    <div class="summary-card">
                        <h4>Order Summary</h4>
                        
                        <div class="order-items">
                            @foreach($cartItems as $item)
                                <div class="order-item">
                                    <div class="row align-items-center">
                                        <div class="col-3">
                                            @if($item->product->image_path && file_exists(public_path('storage/' . $item->product->image_path)))
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="img-fluid rounded">
                                            @else
                                                <img src="{{ asset('assets/front/img/products/default-product.jpg') }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="img-fluid rounded">
                                            @endif
                                        </div>
                                        <div class="col-6">
                                            <h6 class="item-name">{{ $item->product->name }}</h6>
                                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                        </div>
                                        <div class="col-3 text-end">
                                            <span class="item-price">{{ $item->formatted_total_price }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr>
                        
                        <div class="summary-details">
                            <div class="summary-row d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>${{ number_format($cartTotal, 2) }}</span>
                            </div>
                            
                            <div class="summary-row d-flex justify-content-between">
                                <span>Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>
                            
                            <div class="summary-row d-flex justify-content-between">
                                <span>Tax:</span>
                                <span>$0.00</span>
                            </div>
                            
                            <hr>
                            
                            <div class="summary-row d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong>${{ number_format($cartTotal, 2) }}</strong>
                            </div>
                        </div>
                        
                        <div class="summary-actions mt-4">
                            <a href="{{ route('front.cart') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.checkout-section {
    background-color: #f8f9fa;
    min-height: 60vh;
}

.checkout-form {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.checkout-header h3 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 10px;
}

.form-section {
    padding: 20px 0;
    border-bottom: 1px solid #f0f0f0;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h5 {
    color: #4a90e2;
    font-weight: 600;
    margin-bottom: 15px;
}

.form-check {
    margin-bottom: 10px;
}

.form-check-input:checked {
    background-color: #4a90e2;
    border-color: #4a90e2;
}

.form-check-label {
    font-weight: 500;
    cursor: pointer;
}

.summary-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    position: sticky;
    top: 20px;
}

.summary-card h4 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-weight: 600;
}

.order-item {
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
}

.item-name {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.item-price {
    font-weight: 600;
    color: #4a90e2;
}

.summary-row {
    padding: 8px 0;
}

.summary-row:last-child {
    border-bottom: none;
}

#checkout-btn {
    background: linear-gradient(135deg, #4a90e2, #5aa3f0);
    border: none;
    padding: 15px;
    font-weight: 600;
    transition: all 0.3s ease;
}

#checkout-btn:hover {
    background: linear-gradient(135deg, #357abd, #4a90e2);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
}

#checkout-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Responsive */
@media (max-width: 768px) {
    .checkout-form {
        padding: 20px;
    }
    
    .summary-card {
        margin-top: 30px;
        position: static;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            return;
        }
        
        // Disable button and show loading
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        
        // Get form data
        const formData = new FormData(checkoutForm);
        
        // Submit form
        fetch('{{ route("front.checkout.process") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification('Checkout completed successfully! Redirecting to payment...', 'success');
                
                // Redirect to payment page
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 2000);
            } else {
                showNotification(data.message, 'error');
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Complete Order';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Complete Order';
        });
    });
});

function validateForm() {
    const shippingAddress = document.getElementById('shipping_address').value.trim();
    const terms = document.getElementById('terms').checked;
    
    if (!shippingAddress) {
        showNotification('Please enter your shipping address', 'error');
        return false;
    }
    
    if (!terms) {
        showNotification('Please accept the terms and conditions', 'error');
        return false;
    }
    
    return true;
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
@endpush
