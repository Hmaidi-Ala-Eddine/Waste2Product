@extends('layouts.front')

@section('title', 'Shop - Products')

@push('styles')
<style>
    /* Navbar fix */
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .navbar-nav > li > a:hover {
        color: #667eea !important;
    }

    /* Shop Wrapper */
    .shop-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    .shop-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
    }

    /* Shop Header */
    .shop-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .shop-header h1 {
        font-size: 48px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 12px;
    }

    .shop-header p {
        font-size: 18px;
        color: #7f8c8d;
    }

    /* Filters & Controls */
    .shop-controls {
        background: white;
        padding: 25px 30px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
    }

    .controls-row {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .control-group {
        flex: 1;
        min-width: 200px;
    }

    .control-group label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .control-group select,
    .control-group input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .control-group select:focus,
    .control-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .view-toggle {
        display: flex;
        gap: 10px;
    }

    .view-btn {
        padding: 12px 16px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 18px;
        color: #7f8c8d;
    }

    .view-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
    }

    .view-btn:hover {
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .filter-btn {
        padding: 12px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 35px;
        margin-bottom: 50px;
    }

    .products-grid.list-view {
        grid-template-columns: 1fr;
    }

    /* Product Card */
    .product-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        border: 2px solid transparent;
    }

    .product-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.25);
        border-color: rgba(102, 126, 234, 0.2);
    }

    .products-grid.list-view .product-card {
        flex-direction: row;
    }

    .product-image {
        width: 100%;
        height: 320px;
        object-fit: cover;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8ebef 100%);
        position: relative;
        overflow: hidden;
    }

    .product-image::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.03) 100%);
    }

    .products-grid.list-view .product-image {
        width: 350px;
        height: 280px;
    }

    .product-content {
        padding: 30px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .products-grid.list-view .product-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-badges {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .badge {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .badge-category {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-condition-excellent {
        background: #e3f2fd;
        color: #1565c0;
    }

    .badge-condition-good {
        background: #f3e5f5;
        color: #6a1b9a;
    }

    .badge-condition-fair {
        background: #fff3e0;
        color: #e65100;
    }

    .badge-condition-poor {
        background: #ffebee;
        color: #c62828;
    }

    .badge-free {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .badge-status-available {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-status-sold {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-status-donated {
        background: #fff3e0;
        color: #ef6c00;
    }

    .badge-status-reserved {
        background: #f5f5f5;
        color: #616161;
    }

    .product-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .product-description {
        font-size: 14px;
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 15px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .products-grid.list-view .product-description {
        -webkit-line-clamp: 2;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        margin-bottom: 15px;
    }

    .product-seller {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #7f8c8d;
    }

    .seller-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
    }

    .product-price {
        margin-left: auto;
        font-size: 24px;
        font-weight: 800;
        color: #667eea;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    .btn-add-cart {
        flex: 1;
        padding: 14px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .btn-add-cart:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-add-cart:active:not(:disabled) {
        transform: translateY(-1px);
    }

    .btn-add-cart:disabled {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        cursor: not-allowed;
        opacity: 0.7;
        box-shadow: none;
    }

    .btn-add-cart.in-cart {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        cursor: default;
    }

    .btn-add-cart.in-cart:hover {
        transform: none;
        box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
    }

    .btn-view {
        padding: 12px 20px;
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .login-prompt-card {
        text-align: center;
        padding: 12px 20px;
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        border-radius: 10px;
        color: #856404;
        font-size: 14px;
        font-weight: 600;
        border: 1.5px solid #ffeaa7;
    }

    .login-prompt-card a {
        color: #667eea;
        text-decoration: none;
        font-weight: 700;
    }

    .login-prompt-card a:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 80px;
        color: #e0e0e0;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    /* Admin Add Product Button */
    .admin-controls {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .btn-add-product {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.25);
        text-decoration: none;
    }

    .btn-add-product:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.35);
        color: white;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 100px;
        right: 30px;
        background: white;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.4s ease;
        min-width: 320px;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification.success {
        border-left: 4px solid #4CAF50;
    }

    .toast-notification.error {
        border-left: 4px solid #f44336;
    }

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

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
        font-size: 15px;
    }

    .toast-message {
        color: #7f8c8d;
        font-size: 14px;
    }

    .toast-close {
        background: none;
        border: none;
        color: #7f8c8d;
        font-size: 20px;
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
        .shop-header h1 {
            font-size: 36px;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }

        .products-grid.list-view .product-card {
            flex-direction: column;
        }

        .products-grid.list-view .product-image {
            width: 100%;
        }

        .controls-row {
            flex-direction: column;
        }

        .control-group {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="shop-wrapper">
    <div class="shop-container">
        <!-- Shop Header -->
        <div class="shop-header">
            <h1>Eco Products Shop</h1>
            <p>Discover sustainable recycled products from waste materials</p>
        </div>

        <!-- Admin Add Product Button -->
        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="admin-controls">
            <a href="{{ route('admin.products.index') }}" class="btn-add-product">
                <i class="fas fa-plus-circle"></i>
                <span>Manage Products</span>
            </a>
        </div>
        @endif

        <!-- Shop Controls -->
        <form method="GET" action="{{ route('front.shop') }}" id="filterForm">
            <div class="shop-controls">
                <div class="controls-row">
                    <div class="control-group">
                        <label>Search</label>
                        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
                    </div>

                    <div class="control-group">
                        <label>Category</label>
                        <select name="category" onchange="document.getElementById('filterForm').submit()">
                            <option value="all">All Categories</option>
                            <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                            <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="plastic" {{ request('category') == 'plastic' ? 'selected' : '' }}>Plastic</option>
                            <option value="textile" {{ request('category') == 'textile' ? 'selected' : '' }}>Textile</option>
                            <option value="metal" {{ request('category') == 'metal' ? 'selected' : '' }}>Metal</option>
                        </select>
                    </div>

                    <div class="control-group">
                        <label>Condition</label>
                        <select name="condition" onchange="document.getElementById('filterForm').submit()">
                            <option value="all">All Conditions</option>
                            <option value="excellent" {{ request('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ request('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>

                    <div class="control-group">
                        <label>Status</label>
                        <select name="status" onchange="document.getElementById('filterForm').submit()">
                            <option value="all">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                        </select>
                    </div>

                    <div class="control-group">
                        <label>Price</label>
                        <select name="price_type" onchange="document.getElementById('filterForm').submit()">
                            <option value="all">All Prices</option>
                            <option value="free" {{ request('price_type') == 'free' ? 'selected' : '' }}>Free</option>
                            <option value="paid" {{ request('price_type') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <div class="control-group">
                        <label>Sort By</label>
                        <select name="sort" onchange="document.getElementById('filterForm').submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </div>

                    <div class="view-toggle">
                        <button type="button" class="view-btn active" data-view="grid" onclick="toggleView('grid')">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="view-btn" data-view="list" onclick="toggleView('list')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="products-grid" id="productsGrid">
                @foreach($products as $product)
                <div class="product-card">
                    <!-- Product Image -->
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="product-image">
                    @else
                        <img src="{{ asset('assets/img/default-product.jpg') }}" alt="{{ $product->name }}" class="product-image">
                    @endif

                    <!-- Product Content -->
                    <div class="product-content">
                        <!-- Badges -->
                        <div class="product-badges">
                            <span class="badge badge-status-{{ $product->status }}">{{ ucfirst($product->status) }}</span>
                            <span class="badge badge-category">{{ ucfirst($product->category) }}</span>
                            @if($product->condition)
                                <span class="badge badge-condition-{{ $product->condition }}">{{ ucfirst($product->condition) }}</span>
                            @endif
                            @if($product->isFree())
                                <span class="badge badge-free">FREE</span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h3 class="product-title">{{ $product->name }}</h3>

                        <!-- Description -->
                        <p class="product-description">{{ $product->description ?? 'No description available.' }}</p>

                        <!-- Meta -->
                        <div class="product-meta">
                            <div class="product-seller">
                                <img src="{{ $product->user->profile_picture_url }}" alt="{{ $product->user->name }}" class="seller-avatar">
                                <span>{{ $product->user->name }}</span>
                            </div>
                            <div class="product-price">
                                {{ $product->isFree() ? 'FREE' : number_format($product->price, 2) . ' TND' }}
                            </div>
                        </div>

                        <!-- Actions -->
                        @auth
                            @php
                                $isInCart = in_array($product->id, $cartProductIds);
                                $isAvailable = $product->isAvailableForPurchase();
                            @endphp
                            <div class="product-actions">
                                @if(!$isAvailable)
                                    <button class="btn-add-cart" disabled style="background: #e0e0e0; color: #999; cursor: not-allowed;">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Not Available</span>
                                    </button>
                                @elseif($isInCart)
                                    <button class="btn-add-cart in-cart" disabled>
                                        <i class="fas fa-check-circle"></i>
                                        <span>In Cart</span>
                                    </button>
                                @else
                                    <button class="btn-add-cart" onclick="addToCart({{ $product->id }}, this)">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Add to Cart</span>
                                    </button>
                                @endif
                                <a href="{{ route('front.shop.show', $product->id) }}" class="btn-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @else
                            <div class="login-prompt-card">
                                <a href="{{ route('front.login') }}">Login</a> to purchase products
                            </div>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Products Found</h3>
                <p>Try adjusting your filters or check back later!</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toast Notification
function showToast(title, message, type = 'success') {
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
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
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

// Toggle View
function toggleView(view) {
    const grid = document.getElementById('productsGrid');
    const buttons = document.querySelectorAll('.view-btn');
    
    buttons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.view === view) {
            btn.classList.add('active');
        }
    });

    if (view === 'list') {
        grid.classList.add('list-view');
    } else {
        grid.classList.remove('list-view');
    }

    localStorage.setItem('shop_view', view);
}

// Restore View Preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('shop_view');
    if (savedView) {
        toggleView(savedView);
    }
});

// Add to Cart
function addToCart(productId, buttonElement) {
    // Disable button during request
    buttonElement.disabled = true;
    const originalHTML = buttonElement.innerHTML;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Adding...</span>';

    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Success!', data.message, 'success');
            
            // Update cart count in header
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
                cartCount.style.display = 'inline';
            } else {
                // Create badge if it doesn't exist
                const cartLink = document.querySelector('a[href*="cart"]');
                if (cartLink) {
                    const badge = document.createElement('span');
                    badge.className = 'badge-count cart-count';
                    badge.textContent = data.cart_count;
                    cartLink.appendChild(badge);
                }
            }
            
            // Change button to "Already in Cart" state
            buttonElement.className = 'btn-add-cart in-cart';
            buttonElement.innerHTML = '<i class="fas fa-check-circle"></i><span>Already in Cart</span>';
            buttonElement.onclick = null; // Remove click handler
        } else {
            showToast('Error', data.message, 'error');
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalHTML;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'Failed to add product to cart', 'error');
        buttonElement.disabled = false;
        buttonElement.innerHTML = originalHTML;
    });
}
</script>
@endpush
