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

    /* Form validation styles */
    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
        font-weight: 500;
    }

    .invalid-feedback.show {
        display: block;
    }

    /* Enhanced form control focus */
    .form-control:focus:not(.is-invalid) {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
                                <div class="invalid-feedback"></div>
            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control" required>
                                <div class="invalid-feedback"></div>
            </div>
            </div>
                    
                    <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback"></div>
        </div>

                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    
                    <div class="form-group">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                            <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                                <label class="form-label">City *</label>
                                <input type="text" name="city" class="form-control" required>
                                <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                                <label class="form-label">Postal Code *</label>
                                <input type="text" name="postal_code" class="form-control" required>
                                <div class="invalid-feedback"></div>
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
                                <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-row">
                        <div class="form-group">
                                    <label class="form-label">Expiry Date *</label>
                                    <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY">
                                    <div class="invalid-feedback"></div>
                        </div>
                            <div class="form-group">
                                    <label class="form-label">CVV *</label>
                                    <input type="text" name="card_cvv" class="form-control" placeholder="123">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Cardholder Name *</label>
                                <input type="text" name="card_name" class="form-control">
                                <div class="invalid-feedback"></div>
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
    console.log('processCheckout called');
    
    const form = document.getElementById('checkoutForm');
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    const formData = new FormData(form);
    
    // Clear previous errors
    clearFormErrors(form);
    
    // Validate form fields
    const errors = validateCheckoutForm(form);
    console.log('Validation errors:', errors);
    
    if (errors.length > 0) {
        displayFormErrors(form, errors);
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
    
    console.log('Sending data:', data);
    
    fetch('{{ route("front.checkout.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showNotification('Commande passée avec succès ! Redirection...', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("front.order.success") }}';
            }, 2000);
    } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock"></i> Complete Order';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Une erreur est survenue. Veuillez réessayer.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock"></i> Complete Order';
    });
}

// Validate checkout form
function validateCheckoutForm(form) {
    const errors = [];
    const formData = new FormData(form);
    
    console.log('Starting validation...');
    
    // Required fields validation
    const requiredFields = {
        'first_name': 'Le prénom est obligatoire',
        'last_name': 'Le nom est obligatoire',
        'email': 'L\'email est obligatoire',
        'phone': 'Le téléphone est obligatoire',
        'address': 'L\'adresse est obligatoire',
        'city': 'La ville est obligatoire',
        'postal_code': 'Le code postal est obligatoire',
        'payment_method': 'Le mode de paiement est obligatoire'
    };
    
    // Check required fields
    Object.keys(requiredFields).forEach(field => {
        const value = formData.get(field);
        console.log(`Checking ${field}:`, value);
        if (!value || value.trim() === '') {
            errors.push({ field, message: requiredFields[field] });
        }
    });
    
    // Email validation
    const email = formData.get('email');
    if (email && !isValidEmail(email)) {
        errors.push({ field: 'email', message: 'L\'email n\'est pas valide' });
    }
    
    // Phone validation
    const phone = formData.get('phone');
    if (phone && !isValidPhone(phone)) {
        errors.push({ field: 'phone', message: 'Le numéro de téléphone n\'est pas valide' });
    }
    
    // Card validation if card payment is selected
    const paymentMethod = formData.get('payment_method');
    if (paymentMethod === 'card') {
        const cardNumber = formData.get('card_number');
        const cardExpiry = formData.get('card_expiry');
        const cardCvv = formData.get('card_cvv');
        const cardName = formData.get('card_name');
        
        if (!cardNumber || cardNumber.trim() === '') {
            errors.push({ field: 'card_number', message: 'Le numéro de carte est obligatoire' });
        } else if (!isValidCardNumber(cardNumber)) {
            errors.push({ field: 'card_number', message: 'Le numéro de carte n\'est pas valide' });
        }
        
        if (!cardExpiry || cardExpiry.trim() === '') {
            errors.push({ field: 'card_expiry', message: 'La date d\'expiration est obligatoire' });
        } else if (!isValidCardExpiry(cardExpiry)) {
            errors.push({ field: 'card_expiry', message: 'La date d\'expiration n\'est pas valide (MM/YY)' });
        }
        
        if (!cardCvv || cardCvv.trim() === '') {
            errors.push({ field: 'card_cvv', message: 'Le CVV est obligatoire' });
        } else if (!isValidCardCvv(cardCvv)) {
            errors.push({ field: 'card_cvv', message: 'Le CVV doit contenir 3 ou 4 chiffres' });
        }
        
        if (!cardName || cardName.trim() === '') {
            errors.push({ field: 'card_name', message: 'Le nom du titulaire est obligatoire' });
        }
    }
    
    console.log('Validation complete. Errors:', errors);
    return errors;
}

// Display form errors
function displayFormErrors(form, errors) {
    console.log('Displaying errors:', errors);
    
    errors.forEach(error => {
        const field = form.querySelector(`[name="${error.field}"]`);
        if (field) {
            field.classList.add('is-invalid');
            const errorElement = field.parentNode.querySelector('.invalid-feedback');
            if (errorElement) {
                errorElement.textContent = error.message;
                errorElement.style.display = 'block';
            }
        }
    });
    
    // Scroll to first error
    const firstError = form.querySelector('.is-invalid');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
    }
    
    // Show notification
    showNotification('Veuillez corriger les erreurs dans le formulaire', 'error');
}

// Clear form errors
function clearFormErrors(form) {
    const fields = form.querySelectorAll('.form-control');
    fields.forEach(field => {
        field.classList.remove('is-invalid');
        const errorElement = field.parentNode.querySelector('.invalid-feedback');
        if (errorElement) {
            errorElement.style.display = 'none';
            errorElement.textContent = '';
        }
    });
}

// Validation helper functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
    return phoneRegex.test(phone);
}

function isValidCardNumber(cardNumber) {
    const cleanNumber = cardNumber.replace(/\s/g, '');
    return /^\d{13,19}$/.test(cleanNumber);
}

function isValidCardExpiry(expiry) {
    const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    if (!expiryRegex.test(expiry)) return false;
    
    const [month, year] = expiry.split('/');
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear() % 100;
    const currentMonth = currentDate.getMonth() + 1;
    
    const expYear = parseInt(year);
    const expMonth = parseInt(month);
    
    if (expYear < currentYear) return false;
    if (expYear === currentYear && expMonth < currentMonth) return false;
    
    return true;
}

function isValidCardCvv(cvv) {
    return /^\d{3,4}$/.test(cvv);
}

// Add real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    if (form) {
        // Add real-time validation for email
        const emailField = form.querySelector('input[name="email"]');
        if (emailField) {
            emailField.addEventListener('blur', function() {
                if (this.value && !isValidEmail(this.value)) {
                    this.classList.add('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.textContent = 'L\'email n\'est pas valide';
                        errorElement.style.display = 'block';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });
        }
        
        // Add real-time validation for phone
        const phoneField = form.querySelector('input[name="phone"]');
        if (phoneField) {
            phoneField.addEventListener('blur', function() {
                if (this.value && !isValidPhone(this.value)) {
                    this.classList.add('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.textContent = 'Le numéro de téléphone n\'est pas valide';
                        errorElement.style.display = 'block';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });
        }
        
        // Add real-time validation for card fields
        const cardNumberField = form.querySelector('input[name="card_number"]');
        if (cardNumberField) {
            cardNumberField.addEventListener('blur', function() {
                if (this.value && !isValidCardNumber(this.value)) {
                    this.classList.add('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.textContent = 'Le numéro de carte n\'est pas valide';
                        errorElement.style.display = 'block';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });
        }
        
        const cardExpiryField = form.querySelector('input[name="card_expiry"]');
        if (cardExpiryField) {
            cardExpiryField.addEventListener('blur', function() {
                if (this.value && !isValidCardExpiry(this.value)) {
                    this.classList.add('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.textContent = 'La date d\'expiration n\'est pas valide (MM/YY)';
                        errorElement.style.display = 'block';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });
        }
        
        const cardCvvField = form.querySelector('input[name="card_cvv"]');
        if (cardCvvField) {
            cardCvvField.addEventListener('blur', function() {
                if (this.value && !isValidCardCvv(this.value)) {
                    this.classList.add('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.textContent = 'Le CVV doit contenir 3 ou 4 chiffres';
                        errorElement.style.display = 'block';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    const errorElement = this.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });
        }
    }
});
</script>
@endpush