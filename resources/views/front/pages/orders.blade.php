@extends('layouts.front')

@section('title', 'My Orders')

@push('styles')
<style>
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .orders-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    .orders-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .page-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .page-header h1 {
        font-size: 42px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .page-header p {
        font-size: 16px;
        color: #7f8c8d;
    }

    .orders-grid {
        display: grid;
        gap: 25px;
    }

    .order-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f5f7fa;
    }

    .order-id {
        font-size: 14px;
        color: #7f8c8d;
        font-weight: 600;
    }

    .order-date {
        font-size: 13px;
        color: #95a5a6;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #fff3e0;
        color: #e65100;
    }

    .status-paid {
        background: #e3f2fd;
        color: #1565c0;
    }

    .status-shipped {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-completed {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-cancelled {
        background: #ffebee;
        color: #c62828;
    }

    .order-content {
        display: flex;
        gap: 20px;
    }

    .product-image {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        background: #f5f7fa;
    }

    .order-details {
        flex: 1;
    }

    .product-name {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .product-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #7f8c8d;
        font-size: 14px;
    }

    .meta-item i {
        color: #667eea;
    }

    .order-price {
        text-align: right;
    }

    .price-label {
        font-size: 12px;
        color: #7f8c8d;
        margin-bottom: 5px;
    }

    .price-value {
        font-size: 28px;
        font-weight: 800;
        color: #667eea;
    }

    .order-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-view {
        background: #667eea;
        color: white;
    }

    .btn-view:hover {
        background: #5a6fd8;
        color: white;
    }

    .btn-cancel {
        background: #e74c3c;
        color: white;
    }

    .btn-cancel:hover {
        background: #c0392b;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
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

    .empty-state p {
        color: #7f8c8d;
        margin-bottom: 30px;
    }

    .btn-shop {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-shop:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    @media (max-width: 768px) {
        .order-content {
            flex-direction: column;
        }

        .product-image {
            width: 100%;
            height: 200px;
        }

        .order-price {
            text-align: left;
        }

        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="orders-wrapper">
    <div class="orders-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-shopping-bag"></i> My Orders</h1>
            <p>Track your order history and status</p>
        </div>

        @if($orders->count() > 0)
            <div class="orders-grid">
                @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Order #{{ $order->id }}</div>
                            <div class="order-date">{{ $order->ordered_at ? $order->ordered_at->format('M d, Y - H:i') : $order->created_at->format('M d, Y - H:i') }}</div>
                        </div>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="order-content">
                        <!-- Product Image -->
                        @if($order->product->image_path)
                            <img src="{{ asset('storage/' . $order->product->image_path) }}" alt="{{ $order->product->name }}" class="product-image">
                        @else
                            <img src="{{ asset('assets/img/default-product.jpg') }}" alt="{{ $order->product->name }}" class="product-image">
                        @endif

                        <!-- Order Details -->
                        <div class="order-details">
                            <h3 class="product-name">{{ $order->product->name }}</h3>
                            
                            <div class="product-meta">
                                <div class="meta-item">
                                    <i class="fas fa-box"></i>
                                    <span>Quantity: {{ $order->quantity }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-credit-card"></i>
                                    <span>{{ ucfirst($order->payment_method) }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>Seller: {{ $order->product->user->name }}</span>
                                </div>
                            </div>

                            @if($order->shipping_address)
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ Str::limit($order->shipping_address, 50) }}</span>
                            </div>
                            @endif

                            @if($order->order_notes)
                            <div class="meta-item">
                                <i class="fas fa-sticky-note"></i>
                                <span>{{ Str::limit($order->order_notes, 50) }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Price -->
                        <div class="order-price">
                            <div class="price-label">Total Paid</div>
                            <div class="price-value">{{ number_format($order->total_price, 2) }} TND</div>
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="order-actions">
                        <a href="{{ route('orders.show', $order) }}" class="btn-action btn-view">
                            <i class="fas fa-eye"></i>
                            <span>View Details</span>
                        </a>
                        
                        @if(in_array($order->status, ['pending', 'paid']))
                        <form method="POST" action="{{ route('orders.cancel', $order) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-action btn-cancel" onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="fas fa-times"></i>
                                <span>Cancel Order</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="pagination-wrapper" style="margin-top: 40px; text-align: center;">
                {{ $orders->links() }}
            </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-shopping-bag"></i>
                <h3>No Orders Yet</h3>
                <p>You haven't placed any orders yet. Start shopping!</p>
                <a href="{{ route('front.shop') }}" class="btn-shop">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Browse Products</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

