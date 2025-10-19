@extends('layouts.front')

@section('title', 'Checkout')

@push('styles')
<style>
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .checkout-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .checkout-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .checkout-header h1 {
        font-size: 42px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 12px;
    }

    .checkout-steps {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 50px;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .step-label {
        font-weight: 600;
        color: #2c3e50;
    }

    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    .checkout-form {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .payment-methods {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .payment-method {
        flex: 1;
        padding: 20px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .payment-method input[type="radio"] {
        display: none;
    }

    .payment-method:hover {
        border-color: #667eea;
    }

    .payment-method.active {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .payment-method i {
        font-size: 32px;
        color: #667eea;
        margin-bottom: 10px;
    }

    .payment-method label {
        font-weight: 600;
        color: #2c3e50;
        cursor: pointer;
    }

    .card-details {
        display: none;
    }

    .card-details.active {
        display: block;
    }

    .order-summary {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .order-item {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f5f7fa;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .order-item-details {
        flex: 1;
    }

    .order-item-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .order-item-qty {
        font-size: 12px;
        color: #7f8c8d;
    }

    .order-item-price {
        font-weight: 700;
        color: #667eea;
        text-align: right;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 15px;
    }

    .summary-label {
        color: #7f8c8d;
    }

    .summary-value {
        font-weight: 700;
        color: #2c3e50;
    }

    .summary-total {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
    }

    .total-label {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
    }

    .total-value {
        font-size: 28px;
        font-weight: 800;
        color: #667eea;
    }

    .place-order-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .place-order-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.3);
    }

    .secure-badge {
        text-align: center;
        margin-top: 15px;
        color: #7f8c8d;
        font-size: 13px;
    }

    .secure-badge i {
        color: #4CAF50;
    }

    @media (max-width: 992px) {
        .checkout-content {
            grid-template-columns: 1fr;
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
            <h1><i class="fas fa-credit-card"></i> Checkout</h1>
        </div>

        <div class="checkout-steps">
            <div class="step">
                <div class="step-number">1</div>
                <span class="step-label">Cart</span>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <span class="step-label">Payment</span>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <span class="step-label">Confirmation</span>
            </div>
        </div>

        <form method="POST" action="{{ route('front.checkout.process') }}">
            @csrf
            <div class="checkout-content">
                <!-- Checkout Form -->
                <div class="checkout-form">
                    <!-- Billing Information -->
                    <h3 class="section-title">Billing Information</h3>
                    
                    <div class="form-group">
                        <label for="billing_address">Address *</label>
                        <textarea id="billing_address" name="billing_address" rows="3" required>{{ auth()->user()->address ?? '' }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="billing_city">City *</label>
                            <input type="text" id="billing_city" name="billing_city" required>
                        </div>

                        <div class="form-group">
                            <label for="billing_postal_code">Postal Code *</label>
                            <input type="text" id="billing_postal_code" name="billing_postal_code" required>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <h3 class="section-title" style="margin-top: 40px;">Payment Method</h3>

                    <div class="payment-methods">
                        <div class="payment-method active" onclick="selectPayment('card', this)">
                            <input type="radio" name="payment_method" value="card" id="payment_card" checked>
                            <label for="payment_card">
                                <i class="fas fa-credit-card"></i>
                                <div>Credit Card</div>
                            </label>
                        </div>

                        <div class="payment-method" onclick="selectPayment('paypal', this)">
                            <input type="radio" name="payment_method" value="paypal" id="payment_paypal">
                            <label for="payment_paypal">
                                <i class="fab fa-paypal"></i>
                                <div>PayPal</div>
                            </label>
                        </div>

                        <div class="payment-method" onclick="selectPayment('cash', this)">
                            <input type="radio" name="payment_method" value="cash" id="payment_cash">
                            <label for="payment_cash">
                                <i class="fas fa-money-bill-wave"></i>
                                <div>Cash</div>
                            </label>
                        </div>
                    </div>

                    <!-- Card Details -->
                    <div class="card-details active" id="cardDetails">
                        <div class="form-group">
                            <label for="card_name">Cardholder Name *</label>
                            <input type="text" id="card_name" name="card_name" placeholder="John Doe">
                        </div>

                        <div class="form-group">
                            <label for="card_number">Card Number *</label>
                            <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="card_expiry">Expiry Date *</label>
                                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5">
                            </div>

                            <div class="form-group">
                                <label for="card_cvv">CVV *</label>
                                <input type="text" id="card_cvv" name="card_cvv" placeholder="123" maxlength="3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3 class="summary-title">Order Summary</h3>

                    <!-- Order Items -->
                    @foreach($cartItems as $item)
                    <div class="order-item">
                        @if($item->product->image_path)
                            <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="order-item-image">
                        @else
                            <img src="{{ asset('assets/img/default-product.jpg') }}" alt="{{ $item->product->name }}" class="order-item-image">
                        @endif

                        <div class="order-item-details">
                            <div class="order-item-name">{{ $item->product->name }}</div>
                            <div class="order-item-qty">Qty: {{ $item->quantity }}</div>
                        </div>

                        <div class="order-item-price">
                            {{ number_format($item->subtotal, 2) }} TND
                        </div>
                    </div>
                    @endforeach

                    <!-- Summary -->
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #f0f0f0;">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">{{ number_format($subtotal, 2) }} TND</span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Tax (19%)</span>
                            <span class="summary-value">{{ number_format($tax, 2) }} TND</span>
                        </div>

                        <div class="summary-total">
                            <span class="total-label">Total</span>
                            <span class="total-value">{{ number_format($total, 2) }} TND</span>
                        </div>
                    </div>

                    <button type="submit" class="place-order-btn">
                        <i class="fas fa-check-circle"></i> Place Order
                    </button>

                    <div class="secure-badge">
                        <i class="fas fa-lock"></i> Secure Payment
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select Payment Method
function selectPayment(method, element) {
    // Remove active class from all
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('active');
    });

    // Add active class to selected
    element.classList.add('active');

    // Check the radio button
    document.getElementById('payment_' + method).checked = true;

    // Show/hide card details
    const cardDetails = document.getElementById('cardDetails');
    if (method === 'card') {
        cardDetails.classList.add('active');
        // Make card fields required
        document.getElementById('card_name').required = true;
        document.getElementById('card_number').required = true;
        document.getElementById('card_expiry').required = true;
        document.getElementById('card_cvv').required = true;
    } else {
        cardDetails.classList.remove('active');
        // Make card fields optional
        document.getElementById('card_name').required = false;
        document.getElementById('card_number').required = false;
        document.getElementById('card_expiry').required = false;
        document.getElementById('card_cvv').required = false;
    }
}

// Format card number
document.getElementById('card_number')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s+/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Format expiry date
document.getElementById('card_expiry')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value;
});

// CVV numbers only
document.getElementById('card_cvv')?.addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});
</script>
@endpush
