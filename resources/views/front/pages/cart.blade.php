@extends('layouts.front')

@section('title', 'Shopping Cart')

@section('content')
<!-- Page Title -->
<section class="page-title" style="background-image: url({{ asset('assets/front/img/banner/page-title.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-content text-center">
                    <h1 class="title">Shopping Cart</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cart Section -->
<section class="cart-section py-5">
    <div class="container">
        @if($cartItems->count() > 0)
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-items">
                        <div class="cart-header d-flex justify-content-between align-items-center mb-4">
                            <h3>Shopping Cart ({{ $cartItemsCount }} items)</h3>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                                <i class="fas fa-trash me-1"></i>Clear Cart
                            </button>
                        </div>

                        <div class="cart-items-list">
                            @foreach($cartItems as $item)
                                <div class="cart-item" data-cart-item-id="{{ $item->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <div class="cart-item-image">
                                                @if($item->product->image_path && file_exists(storage_path('app/public/' . $item->product->image_path)))
                                                    <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="img-fluid rounded cart-product-image">
                                                @else
                                                    <img src="{{ asset('assets/front/img/products/default-product.svg') }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="img-fluid rounded cart-product-image">
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="cart-item-details">
                                                <h5 class="cart-item-name">{{ $item->product->name }}</h5>
                                                <p class="cart-item-category text-muted">{{ ucfirst($item->product->category) }}</p>
                                                <span class="badge bg-{{ $item->product->status_badge_class }}">
                                                    {{ ucfirst($item->product->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="cart-item-quantity">
                                                <div class="quantity-controls">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" 
                                                            onclick="decreaseQuantity({{ $item->id }})">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           class="form-control form-control-sm text-center quantity-input" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" 
                                                           max="10"
                                                           data-cart-item-id="{{ $item->id }}"
                                                           onchange="updateQuantity({{ $item->id }}, this.value)">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" 
                                                            onclick="increaseQuantity({{ $item->id }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="cart-item-total">
                                                <div class="price-info">
                                                    <span class="unit-price text-muted small">${{ number_format($item->price, 2) }} each</span>
                                                    <span class="total-price">{{ $item->formatted_total_price }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="cart-item-actions mt-2">
                                        <button type="button" class="btn btn-remove" 
                                                onclick="removeItem({{ $item->id }})">
                                            <i class="fas fa-trash"></i>Remove
                                        </button>
                                    </div>
                                    
                                    <hr class="my-3">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <div class="summary-card">
                            <h4>Order Summary</h4>
                            
                            <div class="summary-details">
                                <div class="summary-row d-flex justify-content-between">
                                    <span>Items ({{ $cartItemsCount }}):</span>
                                    <span id="cart-items-count">{{ $cartItemsCount }}</span>
                                </div>
                                
                                <div class="summary-row d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span id="cart-subtotal">${{ number_format($cartTotal, 2) }}</span>
                                </div>
                                
                                <div class="summary-row d-flex justify-content-between">
                                    <span>Shipping:</span>
                                    <span class="text-success">Free</span>
                                </div>
                                
                                <hr>
                                
                                <div class="summary-row d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong id="cart-total">${{ number_format($cartTotal, 2) }}</strong>
                                </div>
                            </div>
                            
                            <div class="summary-actions mt-4">
                                <a href="{{ route('front.checkout') }}" class="btn btn-checkout mb-3">
                                    <i class="fas fa-credit-card"></i>Proceed to Checkout
                                </a>
                                
                                <button type="button" class="btn btn-clear-cart w-100 mb-3" onclick="clearCart()">
                                    <i class="fas fa-trash-alt"></i>Clear Cart
                                </button>
                                
                                <a href="{{ route('front.products') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart text-center py-5">
                <div class="empty-cart-icon mb-4">
                    <i class="fas fa-shopping-cart fa-5x text-muted"></i>
                </div>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('front.products') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.cart-section {
    background-color: #f8f9fa;
    min-height: 60vh;
}

.cart-item {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.cart-item:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.cart-item-image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.cart-product-image {
    width: 80px !important;
    height: 80px !important;
    object-fit: cover !important;
    border-radius: 8px !important;
    display: block !important;
}

.cart-item-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.cart-item-category {
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.price-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.unit-price {
    font-size: 0.8rem;
    color: #6c757d;
}

.total-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2c3e50;
    background: #e3f2fd;
    padding: 8px 12px;
    border-radius: 8px;
    border-left: 4px solid #2196f3;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

.cart-item-quantity {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 10px;
}

.quantity-btn {
    width: 32px !important;
    height: 32px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #4a90e2, #357abd);
    color: white;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.8rem !important;
    padding: 0;
    box-shadow: 0 2px 4px rgba(74, 144, 226, 0.2);
}

.quantity-btn:hover {
    background: linear-gradient(135deg, #357abd, #2c5aa0);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(74, 144, 226, 0.3);
}

.quantity-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(74, 144, 226, 0.2);
}

.quantity-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.quantity-input {
    width: 40px !important;
    border: none;
    border-radius: 4px;
    text-align: center;
    font-weight: 600;
    font-size: 0.8rem !important;
    padding: 4px 2px;
    background: white;
    color: #2c3e50;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 6px !important;
    justify-content: center;
    background: #f8f9fa;
    padding: 4px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    max-width: 100px !important;
    margin: 0 auto;
}

.quantity-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.25);
}

/* Action Buttons */
.btn-remove {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
}

.btn-remove:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4);
    color: white;
    text-decoration: none;
}

.btn-remove:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn-clear-cart {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 22px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2);
}

.btn-clear-cart:hover {
    background: linear-gradient(135deg, #5a6268, #495057);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(108, 117, 125, 0.4);
    color: white;
    text-decoration: none;
}

.btn-clear-cart:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

.btn-checkout {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.2);
    width: 100%;
    justify-content: center;
}

.btn-checkout:hover {
    background: linear-gradient(135deg, #218838, #1ea085);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    color: white;
    text-decoration: none;
}

.btn-checkout:active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Loading States */
.btn-loading {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-left: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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

.summary-row {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-row:last-child {
    border-bottom: none;
}

.empty-cart {
    background: white;
    border-radius: 15px;
    padding: 60px 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.empty-cart-icon {
    opacity: 0.3;
}

.cart-header {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .cart-item {
        padding: 15px;
    }
    
    .cart-item-image img {
        width: 60px;
        height: 60px;
    }
    
    .summary-card {
        margin-top: 30px;
        position: static;
    }
    
    .btn-remove,
    .btn-clear-cart,
    .btn-checkout {
        font-size: 0.8rem;
        padding: 8px 16px;
    }
    
    .quantity-btn {
        width: 28px !important;
        height: 28px !important;
        font-size: 0.7rem !important;
    }
    
    .quantity-input {
        width: 35px !important;
        font-size: 0.7rem !important;
    }
    
    .quantity-controls {
        max-width: 90px !important;
    }
    
    .cart-product-image {
        width: 60px !important;
        height: 60px !important;
    }
}

@media (max-width: 576px) {
    .cart-item-actions {
        text-align: center;
        margin-top: 10px;
    }
    
    .btn-remove {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .quantity-controls {
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .btn-checkout,
    .btn-clear-cart {
        font-size: 0.85rem;
        padding: 10px 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Debug: Check if functions are loaded
console.log('Cart functions loaded');

// Increase quantity
function increaseQuantity(cartItemId) {
    console.log('increaseQuantity called:', cartItemId);
    
    const quantityInput = document.querySelector(`[data-cart-item-id="${cartItemId}"] .quantity-input`);
    const currentQuantity = parseInt(quantityInput.value);
    const newQuantity = currentQuantity + 1;
    
    if (newQuantity <= 10) {
        updateQuantity(cartItemId, newQuantity);
    }
}

// Decrease quantity
function decreaseQuantity(cartItemId) {
    console.log('decreaseQuantity called:', cartItemId);
    
    const quantityInput = document.querySelector(`[data-cart-item-id="${cartItemId}"] .quantity-input`);
    const currentQuantity = parseInt(quantityInput.value);
    const newQuantity = currentQuantity - 1;
    
    if (newQuantity >= 1) {
        updateQuantity(cartItemId, newQuantity);
    } else {
        // If quantity becomes 0, remove the item
        removeItem(cartItemId);
    }
}

// Update quantity
function updateQuantity(cartItemId, quantity) {
    console.log('updateQuantity called:', cartItemId, quantity);
    
    if (quantity < 0) quantity = 0;
    if (quantity > 10) quantity = 10;
    
    fetch('{{ route("front.cart.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            cart_item_id: cartItemId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Update response:', data);
        if (data.success) {
            updateCartSummary(data.cart_items_count, data.cart_total);
            
            if (quantity === 0) {
                // Remove item from DOM
                document.querySelector(`[data-cart-item-id="${cartItemId}"]`).remove();
                
                // Check if cart is empty
                if (data.cart_items_count === 0) {
                    location.reload();
                }
            } else {
                // Update quantity input
                const quantityInput = document.querySelector(`[data-cart-item-id="${cartItemId}"] input[type="number"]`);
                if (quantityInput) {
                    quantityInput.value = quantity;
                }
                
                // Update individual item total price
                const itemTotalPrice = document.querySelector(`[data-cart-item-id="${cartItemId}"] .total-price`);
                if (itemTotalPrice && data.item_total_price) {
                    itemTotalPrice.textContent = '$' + data.item_total_price;
                }
                
                // Update unit price display (quantity x unit price = total)
                const unitPriceElement = document.querySelector(`[data-cart-item-id="${cartItemId}"] .unit-price`);
                if (unitPriceElement && data.item_total_price) {
                    const unitPrice = parseFloat(data.item_total_price) / quantity;
                    unitPriceElement.textContent = '$' + unitPrice.toFixed(2) + ' each';
                }
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

// Remove item
function removeItem(cartItemId) {
    console.log('removeItem called:', cartItemId);
    
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        fetch('{{ route("front.cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                cart_item_id: cartItemId
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Remove response:', data);
            if (data.success) {
                // Remove item from DOM
                document.querySelector(`[data-cart-item-id="${cartItemId}"]`).remove();
                
                updateCartSummary(data.cart_items_count, data.cart_total);
                
                // Check if cart is empty
                if (data.cart_items_count === 0) {
                    location.reload();
                }
                
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
}

// Clear cart
function clearCart() {
    console.log('clearCart called');
    
    if (confirm('Are you sure you want to clear your entire cart?')) {
        fetch('{{ route("front.cart.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Clear response:', data);
            if (data.success) {
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
}

// Update cart summary
function updateCartSummary(itemsCount, total) {
    document.getElementById('cart-items-count').textContent = itemsCount;
    document.getElementById('cart-subtotal').textContent = '$' + total;
    document.getElementById('cart-total').textContent = '$' + total;
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>
@endpush
