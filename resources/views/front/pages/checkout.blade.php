@extends('layouts.front')

@section('title', 'Checkout')

@push('styles')
<style>
    .checkout-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .checkout-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .checkout-header h1 {
        color: #2c3e50;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    .checkout-form {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        color: #2c3e50;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e1e8ed;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .payment-method {
        position: relative;
    }

    .payment-method input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .payment-method label {
        display: block;
        padding: 15px;
        border: 2px solid #e1e8ed;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s ease;
    }

    .payment-method input[type="radio"]:checked + label {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }

    .payment-method label i {
        font-size: 1.5rem;
        margin-bottom: 8px;
        display: block;
    }

    .order-summary {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        height: fit-content;
        position: sticky;
        top: 140px;
    }

    .summary-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #666;
        font-size: 1rem;
    }

    .summary-value {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1rem;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        border-top: 2px solid #667eea;
        margin-top: 15px;
    }

    .total-label {
        color: #2c3e50;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .total-value {
        color: #667eea;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .btn-checkout {
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 15px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        margin-top: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-checkout:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-checkout:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-back {
        display: inline-block;
        color: #667eea;
        text-decoration: none;
        padding: 10px 20px;
        border: 2px solid #667eea;
        border-radius: 25px;
        font-weight: 600;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #667eea;
        color: white;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .checkout-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .order-summary {
            position: static;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="checkout-wrapper">
    <div class="checkout-container">
        <div class="checkout-header">
            <h1><i class="fas fa-lock"></i> Checkout</h1>
        </div>

        <div class="checkout-content">
            <!-- Checkout Form -->
            <div class="checkout-form">
                <form id="checkoutForm">
                    @csrf
                    
                    <!-- Shipping Information -->
                    <div class="form-section">
                        <h3><i class="fas fa-shipping-fast"></i> Shipping Information</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">City *</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code *</label>
                                <input type="text" name="postal_code" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-section">
                        <h3><i class="fas fa-credit-card"></i> Payment Method</h3>
                        
                        <div class="payment-methods">
                            <div class="payment-method">
                                <input type="radio" name="payment_method" value="card" id="card" required>
                                <label for="card">
                                    <i class="fas fa-credit-card"></i>
                                    Credit Card
                                </label>
                            </div>
                            
                            <div class="payment-method">
                                <input type="radio" name="payment_method" value="paypal" id="paypal" required>
                                <label for="paypal">
                                    <i class="fab fa-paypal"></i>
                                    PayPal
                                </label>
                            </div>
                            
                            <div class="payment-method">
                                <input type="radio" name="payment_method" value="cash" id="cash" required>
                                <label for="cash">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Cash on Delivery
                                </label>
                            </div>
                        </div>
                        
                        <!-- Card Details (shown when card is selected) -->
                        <div id="cardDetails" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">Card Number *</label>
                                <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Expiry Date *</label>
                                    <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">CVV *</label>
                                    <input type="text" name="card_cvv" class="form-control" placeholder="123">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Cardholder Name *</label>
                                <input type="text" name="card_name" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-section">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="checkbox" name="terms" required style="margin-right: 10px;">
                                I agree to the <a href="#" style="color: #667eea;">Terms and Conditions</a> and <a href="#" style="color: #667eea;">Privacy Policy</a>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h3 class="summary-title">Order Summary</h3>
                
                @foreach($cartItems as $item)
                <div class="summary-item">
                    <div>
                        <div style="font-weight: 600; color: #2c3e50;">{{ $item->product->name }}</div>
                        <div style="font-size: 0.9rem; color: #666;">Qty: {{ $item->quantity }}</div>
                    </div>
                    <div class="summary-value">{{ number_format($item->subtotal, 2) }} TND</div>
                </div>
                @endforeach
                
                <div class="summary-item">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">{{ number_format($subtotal, 2) }} TND</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Tax (19%)</span>
                    <span class="summary-value">{{ number_format($tax, 2) }} TND</span>
                </div>
                
                <div class="summary-total">
                    <span class="total-label">Total</span>
                    <span class="total-value">{{ number_format($total, 2) }} TND</span>
                </div>
                
                <button type="button" class="btn-checkout" onclick="processCheckout()">
                    <i class="fas fa-lock"></i> Complete Order
                </button>
                
                <a href="{{ route('front.cart') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Cart
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show/hide card details based on payment method
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const cardDetails = document.getElementById('cardDetails');
        if (this.value === 'card') {
            cardDetails.style.display = 'block';
        } else {
            cardDetails.style.display = 'none';
        }
    });
});

// Process checkout
function processCheckout() {
    const form = document.getElementById('checkoutForm');
    const formData = new FormData(form);
    
    // Validate form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Disable button
    const btn = document.querySelector('.btn-checkout');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    fetch('{{ route("front.checkout.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Order placed successfully! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("front.order.success") }}';
            }, 2000);
        } else {
            showNotification(data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock"></i> Complete Order';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock"></i> Complete Order';
    });
}
</script>
@endpush