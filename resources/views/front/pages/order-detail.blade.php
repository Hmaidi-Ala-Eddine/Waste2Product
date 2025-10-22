@extends('layouts.front')

@section('title', 'Order Details')

@push('styles')
<style>
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .order-detail-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    .order-detail-container {
        max-width: 1000px;
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

    .order-detail-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .order-status {
        text-align: center;
        margin-bottom: 40px;
    }

    .status-badge {
        display: inline-block;
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 16px;
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

    .order-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .info-section h3 {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f5f7fa;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f5f7fa;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #7f8c8d;
    }

    .info-value {
        font-weight: 500;
        color: #2c3e50;
        text-align: right;
    }

    .product-section {
        margin-bottom: 40px;
    }

    .product-card {
        display: flex;
        gap: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .product-image {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        background: white;
    }

    .product-details {
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

    .product-price {
        text-align: right;
        font-size: 24px;
        font-weight: 800;
        color: #667eea;
    }

    .order-actions {
        text-align: center;
        padding-top: 30px;
        border-top: 2px solid #f5f7fa;
    }

    .btn-action {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin: 0 10px;
    }

    .btn-back {
        background: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background: #5a6268;
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

    .timeline {
        margin-top: 40px;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f5f7fa;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
    }

    .timeline-icon.pending {
        background: #ff9800;
    }

    .timeline-icon.paid {
        background: #2196f3;
    }

    .timeline-icon.shipped {
        background: #4caf50;
    }

    .timeline-icon.completed {
        background: #4caf50;
    }

    .timeline-icon.cancelled {
        background: #f44336;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .timeline-date {
        font-size: 14px;
        color: #7f8c8d;
    }

    @media (max-width: 768px) {
        .order-info-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .product-card {
            flex-direction: column;
        }

        .product-image {
            width: 100%;
            height: 200px;
        }

        .product-price {
            text-align: left;
        }

        .order-actions {
            text-align: center;
        }

        .btn-action {
            display: block;
            width: 100%;
            margin: 10px 0;
        }
    }
</style>
@endpush

@section('content')
<div class="order-detail-wrapper">
    <div class="order-detail-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-receipt"></i> Order Details</h1>
            <p>Order #{{ $order->id }}</p>
        </div>

        <div class="order-detail-card">
            <!-- Order Status -->
            <div class="order-status">
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <!-- Order Information Grid -->
            <div class="order-info-grid">
                <!-- Order Information -->
                <div class="info-section">
                    <h3><i class="fas fa-info-circle"></i> Order Information</h3>
                    <div class="info-item">
                        <span class="info-label">Order ID</span>
                        <span class="info-value">#{{ $order->id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Order Date</span>
                        <span class="info-value">{{ $order->ordered_at ? $order->ordered_at->format('M d, Y - H:i') : $order->created_at->format('M d, Y - H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Quantity</span>
                        <span class="info-value">{{ $order->quantity }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total Amount</span>
                        <span class="info-value">{{ number_format($order->total_price, 2) }} TND</span>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="info-section">
                    <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                    <div class="info-item">
                        <span class="info-label">Payment Method</span>
                        <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    @if($order->payment_status)
                    <div class="info-item">
                        <span class="info-label">Payment Status</span>
                        <span class="info-value">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    @endif
                    @if($order->transaction_id)
                    <div class="info-item">
                        <span class="info-label">Transaction ID</span>
                        <span class="info-value">{{ $order->transaction_id }}</span>
                    </div>
                    @endif
                    @if($order->payment_processed_at)
                    <div class="info-item">
                        <span class="info-label">Payment Date</span>
                        <span class="info-value">{{ $order->payment_processed_at->format('M d, Y - H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Information -->
            <div class="product-section">
                <h3><i class="fas fa-box"></i> Product Information</h3>
                <div class="product-card">
                    @if($order->product->image_path)
                        <img src="{{ asset('storage/' . $order->product->image_path) }}" alt="{{ $order->product->name }}" class="product-image">
                    @else
                        <img src="{{ asset('assets/img/default-product.jpg') }}" alt="{{ $order->product->name }}" class="product-image">
                    @endif

                    <div class="product-details">
                        <h4 class="product-name">{{ $order->product->name }}</h4>
                        
                        <div class="product-meta">
                            <div class="meta-item">
                                <i class="fas fa-tag"></i>
                                <span>{{ ucfirst($order->product->category) }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-star"></i>
                                <span>{{ ucfirst($order->product->condition) }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>Seller: {{ $order->product->user->name }}</span>
                            </div>
                        </div>

                        @if($order->product->description)
                        <p style="color: #7f8c8d; margin-top: 10px;">{{ Str::limit($order->product->description, 150) }}</p>
                        @endif
                    </div>

                    <div class="product-price">
                        {{ number_format($order->total_price, 2) }} TND
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            @if($order->shipping_address)
            <div class="info-section">
                <h3><i class="fas fa-shipping-fast"></i> Shipping Information</h3>
                <div class="info-item">
                    <span class="info-label">Shipping Address</span>
                    <span class="info-value">{{ $order->shipping_address }}</span>
                </div>
            </div>
            @endif

            <!-- Order Notes -->
            @if($order->order_notes)
            <div class="info-section">
                <h3><i class="fas fa-sticky-note"></i> Order Notes</h3>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; color: #2c3e50;">
                    {{ $order->order_notes }}
                </div>
            </div>
            @endif

            <!-- Order Timeline -->
            <div class="timeline">
                <h3><i class="fas fa-history"></i> Order Timeline</h3>
                
                <div class="timeline-item">
                    <div class="timeline-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Order Placed</div>
                        <div class="timeline-date">{{ $order->created_at->format('M d, Y - H:i') }}</div>
                    </div>
                </div>

                @if($order->status === 'paid' || $order->status === 'shipped' || $order->status === 'completed')
                <div class="timeline-item">
                    <div class="timeline-icon paid">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Payment Confirmed</div>
                        <div class="timeline-date">{{ $order->payment_processed_at ? $order->payment_processed_at->format('M d, Y - H:i') : 'Payment confirmed' }}</div>
                    </div>
                </div>
                @endif

                @if($order->status === 'shipped' || $order->status === 'completed')
                <div class="timeline-item">
                    <div class="timeline-icon shipped">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Order Shipped</div>
                        <div class="timeline-date">Order has been shipped</div>
                    </div>
                </div>
                @endif

                @if($order->status === 'completed')
                <div class="timeline-item">
                    <div class="timeline-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Order Completed</div>
                        <div class="timeline-date">Order has been completed</div>
                    </div>
                </div>
                @endif

                @if($order->status === 'cancelled')
                <div class="timeline-item">
                    <div class="timeline-icon cancelled">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Order Cancelled</div>
                        <div class="timeline-date">Order has been cancelled</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Order Actions -->
            <div class="order-actions">
                <a href="{{ route('orders.index') }}" class="btn-action btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Orders</span>
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
    </div>
</div>
@endsection

