@extends('layouts.front')

@section('title', 'Shopping Cart')

@push('styles')
<style>
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .cart-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .cart-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .cart-header h1 {
        font-size: 42px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 12px;
    }

    .cart-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    .cart-items {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .cart-item {
        display: flex;
        gap: 20px;
        padding: 20px;
        border: 2px solid #f0f0f0;
        border-radius: 12px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    }

    .item-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        background: #f5f7fa;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .item-meta {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
    }

    .item-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-category {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .item-seller {
        font-size: 13px;
        color: #7f8c8d;
    }

    .item-controls {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 12px;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 4px;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: #f5f7fa;
        color: #2c3e50;
        font-weight: 700;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .qty-btn:hover {
        background: #667eea;
        color: white;
    }

    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        font-weight: 700;
        color: #2c3e50;
    }

    .remove-btn {
        padding: 8px 16px;
        background: #ffebee;
        color: #c62828;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .remove-btn:hover {
        background: #c62828;
        color: white;
    }

    .item-price {
        text-align: right;
    }

    .price-label {
        font-size: 12px;
        color: #7f8c8d;
        margin-bottom: 4px;
    }

    .price-value {
        font-size: 24px;
        font-weight: 800;
        color: #667eea;
    }

    .cart-summary {
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

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
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
        align-items: center;
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

    .checkout-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .continue-shopping {
        width: 100%;
        padding: 12px;
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .continue-shopping:hover {
        background: #f5f7fa;
    }

    .empty-cart {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
    }

    .empty-cart i {
        font-size: 80px;
        color: #e0e0e0;
        margin-bottom: 20px;
    }

    .empty-cart h3 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .empty-cart p {
        color: #7f8c8d;
        margin-bottom: 30px;
    }

    @media (max-width: 992px) {
        .cart-content {
            grid-template-columns: 1fr;
        }

        .cart-summary {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
        }

        .item-image {
            width: 100%;
            height: 200px;
        }

        .item-price {
            text-align: left;
        }
    }

    /* Animations */
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="cart-wrapper">
    <div class="cart-container">
        <div class="cart-header">
            <h1><i class="fas fa-shopping-cart"></i> Shopping Cart</h1>
        </div>

        @if($cartItems->count() > 0)
        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cartItems as $item)
                <div class="cart-item" id="cart-item-{{ $item->id }}">
                    <!-- Item Image -->
                    @if($item->product->image_path)
                        <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="item-image">
                    @else
                        <img src="{{ asset('assets/img/default-product.jpg') }}" alt="{{ $item->product->name }}" class="item-image">
                    @endif

                    <!-- Item Details -->
                    <div class="item-details">
                        <h3 class="item-name">{{ $item->product->name }}</h3>
                        
                        <div class="item-meta">
                            <span class="item-badge badge-category">{{ ucfirst($item->product->category) }}</span>
                        </div>

                        <div class="item-seller">
                            Sold by: {{ $item->product->user->name }}
                        </div>

                        <div class="item-controls">
                            <!-- Quantity Control -->
                            <div class="quantity-control">
                                <button class="qty-btn" onclick="changeQuantity({{ $item->id }}, -1)" type="button">-</button>
                                <input type="number" class="qty-input" id="qty-{{ $item->id }}" value="{{ $item->quantity }}" min="1" max="10" readonly data-current="{{ $item->quantity }}">
                                <button class="qty-btn" onclick="changeQuantity({{ $item->id }}, 1)" type="button">+</button>
                            </div>

                            <!-- Remove Button -->
                            <button class="remove-btn" onclick="removeItem({{ $item->id }})">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>

                    <!-- Item Price -->
                    <div class="item-price">
                        <div class="price-label">Price</div>
                        <div class="price-value" id="item-subtotal-{{ $item->id }}">
                            {{ number_format($item->subtotal, 2) }} TND
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="summary-title">Order Summary</h3>

                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="subtotal">{{ number_format($subtotal, 2) }} TND</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Tax (19%)</span>
                    <span class="summary-value" id="tax">{{ number_format($tax, 2) }} TND</span>
                </div>

                <div class="summary-total">
                    <span class="total-label">Total</span>
                    <span class="total-value" id="total">{{ number_format($total, 2) }} TND</span>
                </div>

                <a href="{{ route('front.checkout') }}" class="checkout-btn">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </a>

                <a href="{{ route('front.shop') }}" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>

                <a href="{{ route('front.my-orders') }}" class="continue-shopping" style="margin-top: 10px;">
                    <i class="fas fa-shopping-bag"></i> View My Orders
                </a>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h3>Your Cart is Empty</h3>
            <p>Add some products to get started!</p>
            <a href="{{ route('front.shop') }}" class="checkout-btn" style="max-width: 300px; margin: 0 auto;">
                <i class="fas fa-shopping-bag"></i> Browse Products
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Change Quantity (wrapper to prevent bugs)
function changeQuantity(itemId, delta) {
    const qtyInput = document.getElementById(`qty-${itemId}`);
    const currentQty = parseInt(qtyInput.dataset.current) || parseInt(qtyInput.value);
    const newQty = currentQty + delta;
    
    if (newQty < 1 || newQty > 10) {
        return; // Don't allow invalid quantities
    }
    
    updateQuantity(itemId, newQty);
}

// Update Quantity with AJAX (No Page Refresh)
function updateQuantity(itemId, newQty) {
    if (newQty < 1 || newQty > 10) return;

    // Show loading state
    const qtyInput = document.querySelector(`#cart-item-${itemId} .qty-input`);
    const originalValue = qtyInput.value;
    qtyInput.style.opacity = '0.5';

    fetch(`/cart/update/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: newQty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update item subtotal
            document.getElementById(`item-subtotal-${itemId}`).textContent = 
                parseFloat(data.subtotal).toFixed(2) + ' TND';
            
            // Update quantity input and data attribute
            qtyInput.value = newQty;
            qtyInput.dataset.current = newQty; // Update data attribute to prevent bugs
            qtyInput.style.opacity = '1';

            // Recalculate and update totals without reload
            updateCartTotals();
            
            // Show success feedback
            showMiniToast('Cart updated!');
        } else {
            qtyInput.value = originalValue;
            qtyInput.dataset.current = originalValue;
            qtyInput.style.opacity = '1';
            alert('Failed to update cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        qtyInput.value = originalValue;
        qtyInput.style.opacity = '1';
        alert('Failed to update cart');
    });
}

// Calculate totals from DOM
function updateCartTotals() {
    let subtotal = 0;
    
    // Sum all item subtotals
    document.querySelectorAll('[id^="item-subtotal-"]').forEach(element => {
        const price = parseFloat(element.textContent.replace(' TND', '').replace(',', ''));
        subtotal += price;
    });
    
    const tax = subtotal * 0.19;
    const total = subtotal + tax;
    
    // Update display
    document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' TND';
    document.getElementById('tax').textContent = tax.toFixed(2) + ' TND';
    document.getElementById('total').textContent = total.toFixed(2) + ' TND';
}

// Mini toast notification
function showMiniToast(message) {
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;top:100px;right:30px;background:#4CAF50;color:white;padding:12px 20px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:10000;animation:slideIn 0.3s ease;';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

// Remove Item with smooth animation
function removeItem(itemId) {
    if (!confirm('Remove this item from cart?')) return;

    const itemElement = document.getElementById(`cart-item-${itemId}`);
    itemElement.style.opacity = '0.5';
    itemElement.style.pointerEvents = 'none';

    fetch(`/cart/remove/${itemId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animate removal
            itemElement.style.transform = 'translateX(-100%)';
            itemElement.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                itemElement.remove();
                
                // Update cart count in header
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    if (data.cart_count > 0) {
                        cartCount.textContent = data.cart_count;
                    } else {
                        cartCount.remove();
                    }
                }
                
                // Reload if cart is empty, otherwise recalculate
                if (data.cart_count === 0) {
                    location.reload();
                } else {
                    updateCartTotals();
                    showMiniToast('Item removed from cart');
                }
            }, 300);
        } else {
            itemElement.style.opacity = '1';
            itemElement.style.pointerEvents = 'auto';
            alert('Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        itemElement.style.opacity = '1';
        itemElement.style.pointerEvents = 'auto';
        alert('Failed to remove item');
    });
}
</script>
@endpush
