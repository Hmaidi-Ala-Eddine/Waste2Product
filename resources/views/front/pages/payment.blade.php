@extends('layouts.front')

@section('title', 'Payment - Order #' . $order->id)

@section('content')
<!-- Page Title -->
<section class="page-title" style="background-image: url({{ asset('assets/front/img/banner/page-title.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-content text-center">
                    <h1 class="title">Payment</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.cart') }}">Cart</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.checkout') }}">Checkout</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payment</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Section -->
<section class="payment-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="payment-form">
                    <div class="payment-header mb-4">
                        <h3>Complete Your Payment</h3>
                        <p class="text-muted">Order #{{ $order->id }} - {{ $order->formatted_total_price }}</p>
                    </div>

                    <form id="payment-form">
                        @csrf
                        
                        <!-- Payment Method -->
                        <div class="form-section mb-4">
                            <h5>Payment Method</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="payment-method-card" data-method="credit_card">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                            <label class="form-check-label" for="credit_card">
                                                <i class="fas fa-credit-card me-2"></i>Credit Card
                                            </label>
                                        </div>
                                        <div class="payment-method-info">
                                            <small class="text-muted">Visa, Mastercard, American Express</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="payment-method-card" data-method="paypal">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                            <label class="form-check-label" for="paypal">
                                                <i class="fab fa-paypal me-2"></i>PayPal
                                            </label>
                                        </div>
                                        <div class="payment-method-info">
                                            <small class="text-muted">Pay with your PayPal account</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="payment-method-card" data-method="bank_transfer">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                            <label class="form-check-label" for="bank_transfer">
                                                <i class="fas fa-university me-2"></i>Bank Transfer
                                            </label>
                                        </div>
                                        <div class="payment-method-info">
                                            <small class="text-muted">Direct bank transfer</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="payment-method-card" data-method="cash">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                                            <label class="form-check-label" for="cash">
                                                <i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery
                                            </label>
                                        </div>
                                        <div class="payment-method-info">
                                            <small class="text-muted">Pay when you receive the item</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credit Card Details (shown when credit card is selected) -->
                        <div class="form-section mb-4" id="credit-card-details" style="display: none;">
                            <h5>Card Details</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="card_number" class="form-label">Card Number *</label>
                                        <input type="text" class="form-control" id="card_number" name="card_number" 
                                               placeholder="1234 5678 9012 3456" maxlength="19">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="expiry_date" class="form-label">Expiry Date *</label>
                                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" 
                                               placeholder="MM/YY" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="cvv" class="form-label">CVV *</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" 
                                               placeholder="123" maxlength="4">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="cardholder_name" class="form-label">Cardholder Name *</label>
                                        <input type="text" class="form-control" id="cardholder_name" name="cardholder_name" 
                                               placeholder="John Doe">
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
                                          required>{{ $order->shipping_address }}</textarea>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="form-section mb-4">
                            <h5>Additional Notes</h5>
                            <div class="form-group">
                                <label for="order_notes" class="form-label">Order Notes (Optional)</label>
                                <textarea class="form-control" 
                                          id="order_notes" 
                                          name="order_notes" 
                                          rows="3" 
                                          placeholder="Any special instructions or notes for your order">{{ $order->order_notes }}</textarea>
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
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="payment-btn">
                                <i class="fas fa-credit-card me-2"></i>Pay {{ $order->formatted_total_price }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="order-summary">
                    <div class="summary-card">
                        <h4>Order Summary</h4>
                        
                        <div class="order-item">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    @if($order->product->image_path && file_exists(storage_path('app/public/' . $order->product->image_path)))
                                        <img src="{{ asset('storage/' . $order->product->image_path) }}" 
                                             alt="{{ $order->product->name }}" 
                                             class="img-fluid rounded">
                                    @else
                                        <img src="{{ asset('assets/front/img/products/default-product.svg') }}" 
                                             alt="{{ $order->product->name }}" 
                                             class="img-fluid rounded">
                                    @endif
                                </div>
                                <div class="col-6">
                                    <h6 class="item-name">{{ $order->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $order->quantity }}</small>
                                </div>
                                <div class="col-3 text-end">
                                    <span class="item-price">{{ $order->formatted_total_price }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="summary-details">
                            <div class="summary-row d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>{{ $order->formatted_total_price }}</span>
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
                                <strong>{{ $order->formatted_total_price }}</strong>
                            </div>
                        </div>
                        
                        <div class="summary-actions mt-4">
                            <a href="{{ route('front.checkout') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Back to Checkout
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
.payment-section {
    background-color: #f8f9fa;
    min-height: 60vh;
}

.payment-form {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.payment-header h3 {
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

.payment-method-card {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-method-card:hover {
    border-color: #4a90e2;
    background-color: #f8f9ff;
}

.payment-method-card.selected {
    border-color: #4a90e2;
    background-color: #e3f2fd;
}

.payment-method-card .form-check-input:checked {
    background-color: #4a90e2;
    border-color: #4a90e2;
}

.payment-method-card .form-check-label {
    font-weight: 500;
    cursor: pointer;
}

.payment-method-info {
    margin-top: 5px;
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

#payment-btn {
    background: linear-gradient(135deg, #4a90e2, #5aa3f0);
    border: none;
    padding: 15px;
    font-weight: 600;
    transition: all 0.3s ease;
}

#payment-btn:hover {
    background: linear-gradient(135deg, #357abd, #4a90e2);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
}

#payment-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Responsive */
@media (max-width: 768px) {
    .payment-form {
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
    const paymentForm = document.getElementById('payment-form');
    const paymentBtn = document.getElementById('payment-btn');
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    const creditCardDetails = document.getElementById('credit-card-details');
    
    // Handle payment method selection
    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            paymentMethodCards.forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Show/hide credit card details
            if (radio.value === 'credit_card') {
                creditCardDetails.style.display = 'block';
            } else {
                creditCardDetails.style.display = 'none';
            }
        });
    });
    
    // Format card number input
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }
    
    // Format expiry date input
    const expiryDateInput = document.getElementById('expiry_date');
    if (expiryDateInput) {
        expiryDateInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
    
    // Handle form submission
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            return;
        }
        
        // Disable button and show loading
        paymentBtn.disabled = true;
        paymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing Payment...';
        
        // Get form data
        const formData = new FormData(paymentForm);
        
        // Submit form
        fetch('{{ route("front.payment.process", $order->id) }}', {
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
                showNotification('Payment completed successfully! Redirecting...', 'success');
                
                // Redirect to success page
                setTimeout(() => {
                    window.location.href = '{{ route("front.payment.success", $order->id) }}';
                }, 2000);
            } else {
                showNotification(data.message, 'error');
                paymentBtn.disabled = false;
                paymentBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay {{ $order->formatted_total_price }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
            paymentBtn.disabled = false;
            paymentBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay {{ $order->formatted_total_price }}';
        });
    });
});

function validateForm() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
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
    
    // Validate credit card details if credit card is selected
    if (paymentMethod === 'credit_card') {
        const cardNumber = document.getElementById('card_number').value.trim();
        const expiryDate = document.getElementById('expiry_date').value.trim();
        const cvv = document.getElementById('cvv').value.trim();
        const cardholderName = document.getElementById('cardholder_name').value.trim();
        
        if (!cardNumber || cardNumber.replace(/\s/g, '').length < 16) {
            showNotification('Please enter a valid card number', 'error');
            return false;
        }
        
        if (!expiryDate || expiryDate.length < 5) {
            showNotification('Please enter a valid expiry date', 'error');
            return false;
        }
        
        if (!cvv || cvv.length < 3) {
            showNotification('Please enter a valid CVV', 'error');
            return false;
        }
        
        if (!cardholderName) {
            showNotification('Please enter the cardholder name', 'error');
            return false;
        }
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
