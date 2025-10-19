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
        padding: 0 20px;
    }

    .cart-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .cart-header h1 {
        color: #2c3e50;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .cart-content {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
        margin-bottom: 30px;
    }

    .cart-items {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .cart-item {
        display: flex;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item:hover {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin: 0 -20px;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        margin-right: 20px;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .item-meta {
        margin-bottom: 8px;
    }

    .item-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-right: 8px;
    }

    .badge-category {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .item-seller {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .item-controls {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 25px;
        padding: 5px;
    }

    .qty-btn {
        width: 35px;
        height: 35px;
        border: none;
        background: #667eea;
        color: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .qty-btn:hover {
        background: #5a6fd8;
        transform: scale(1.1);
    }

    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #2c3e50;
    }

    .remove-btn {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .remove-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }

    .item-price {
        text-align: right;
        min-width: 120px;
    }

    .price-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }

    .price-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .cart-summary {
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

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .summary-row:last-of-type {
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

    .checkout-btn {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        text-decoration: none;
        padding: 15px 20px;
        border-radius: 25px;
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .continue-shopping {
        display: block;
        width: 100%;
        background: transparent;
        color: #667eea;
        text-decoration: none;
        padding: 12px 20px;
        border: 2px solid #667eea;
        border-radius: 25px;
        text-align: center;
        font-weight: 600;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .continue-shopping:hover {
        background: #667eea;
        color: white;
        text-decoration: none;
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .empty-cart i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 20px;
    }

    .empty-cart h3 {
        color: #2c3e50;
        font-size: 1.8rem;
        margin-bottom: 15px;
    }

    .empty-cart p {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .cart-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .cart-summary {
            position: static;
        }
        
        .cart-item {
            flex-direction: column;
            text-align: center;
        }
        
        .item-image {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .item-controls {
            justify-content: center;
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
                <div class="cart-item" id="cart-item-{{ $item->product_id }}">
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
                                <button class="qty-btn" onclick="changeQuantity({{ $item->product_id }}, -1)" type="button">-</button>
                                <input type="number" class="qty-input" id="qty-{{ $item->product_id }}" value="{{ $item->quantity }}" min="1" max="10" readonly data-current="{{ $item->quantity }}">
                                <button class="qty-btn" onclick="changeQuantity({{ $item->product_id }}, 1)" type="button">+</button>
                            </div>

                            <!-- Remove Button -->
                            <button class="remove-btn" onclick="removeItem({{ $item->product_id }})">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>

                    <!-- Item Price -->
                    <div class="item-price">
                        <div class="price-label">Price</div>
                        <div class="price-value" id="item-subtotal-{{ $item->product_id }}">
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
function changeQuantity(productId, delta) {
    const qtyInput = document.getElementById(`qty-${productId}`);
    const currentQty = parseInt(qtyInput.dataset.current) || parseInt(qtyInput.value);
    const newQty = currentQty + delta;
    
    if (newQty < 1 || newQty > 10) {
        return; // Don't allow invalid quantities
    }
    
    updateQuantity(productId, newQty);
}

// Update Quantity with AJAX (No Page Refresh)
function updateQuantity(productId, newQty) {
    if (newQty < 1 || newQty > 10) return;

    // Show loading state
    const qtyInput = document.querySelector(`#cart-item-${productId} .qty-input`);
    const originalValue = qtyInput.value;
    qtyInput.style.opacity = '0.5';

    fetch(`/cart/update/${productId}`, {
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
            document.getElementById(`item-subtotal-${productId}`).textContent = 
                parseFloat(data.subtotal).toFixed(2) + ' TND';
            
            // Update quantity input and data attribute
            qtyInput.value = newQty;
            qtyInput.dataset.current = newQty; // Update data attribute to prevent bugs
            qtyInput.style.opacity = '1';

            // Recalculate and update totals without reload
            updateCartTotals();
            
            // Show success feedback
            showNotification('Cart updated!');
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
        qtyInput.dataset.current = originalValue;
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

// Remove Item with smooth animation
function removeItem(productId) {
    if (!confirm('Remove this item from cart?')) return;

    const itemElement = document.getElementById(`cart-item-${productId}`);
    itemElement.style.opacity = '0.5';
    itemElement.style.pointerEvents = 'none';

    fetch(`/cart/remove/${productId}`, {
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
                    showNotification('Item removed from cart');
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

// Clear cart
function clearCart() {
    console.log('clearCart called');
    
    if (confirm('Are you sure you want to clear your entire cart?')) {
        fetch('/cart/clear', {
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
                showNotification(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
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
</script>
@endpush