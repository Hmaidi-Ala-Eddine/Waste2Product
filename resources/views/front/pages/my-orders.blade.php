@extends('layouts.front')

@section('title', 'My Orders & Reservations')

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

    .tabs-container {
        display: flex;
        gap: 15px;
        margin-bottom: 40px;
        justify-content: center;
    }

    .tab-btn {
        padding: 15px 40px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        color: #7f8c8d;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .tab-btn:hover {
        transform: translateY(-2px);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
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

    .status-completed {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-cancelled {
        background: #ffebee;
        color: #c62828;
    }

    .status-active {
        background: #e3f2fd;
        color: #1565c0;
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

        .tabs-container {
            flex-direction: column;
        }

        .tab-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="orders-wrapper">
    <div class="orders-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-shopping-bag"></i> My Orders & Reservations</h1>
            <p>Track your purchases and reserved items</p>
        </div>

        <!-- Tabs -->
        <div class="tabs-container">
            <button class="tab-btn active" onclick="switchTab('orders')">
                <i class="fas fa-box"></i> My Orders ({{ $orders->count() }})
            </button>
            <button class="tab-btn" onclick="switchTab('reservations')">
                <i class="fas fa-bookmark"></i> My Reservations ({{ $reservations->count() }})
            </button>
        </div>

        <!-- Orders Tab -->
        <div class="tab-content active" id="orders-tab">
            @if($orders->count() > 0)
                <div class="orders-grid">
                    @foreach($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-id">Order #{{ $order->id }}</div>
                                <div class="order-date">{{ $order->ordered_at->format('M d, Y - H:i') }}</div>
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
                            </div>

                            <!-- Price -->
                            <div class="order-price">
                                <div class="price-label">Total Paid</div>
                                <div class="price-value">{{ number_format($order->total_price, 2) }} TND</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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

        <!-- Reservations Tab -->
        <div class="tab-content" id="reservations-tab">
            @if($reservations->count() > 0)
                <div class="orders-grid">
                    @foreach($reservations as $reservation)
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-id">Reservation #{{ $reservation->id }}</div>
                                <div class="order-date">{{ $reservation->reserved_at->format('M d, Y - H:i') }}</div>
                            </div>
                            <span class="status-badge status-{{ $reservation->status }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>

                        <div class="order-content">
                            <!-- Product Image -->
                            @if($reservation->product->image_path)
                                <img src="{{ asset('storage/' . $reservation->product->image_path) }}" alt="{{ $reservation->product->name }}" class="product-image">
                            @else
                                <img src="{{ asset('assets/img/default-product.jpg') }}" alt="{{ $reservation->product->name }}" class="product-image">
                            @endif

                            <!-- Reservation Details -->
                            <div class="order-details">
                                <h3 class="product-name">{{ $reservation->product->name }}</h3>
                                
                                <div class="product-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-bookmark"></i>
                                        <span>Reserved</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-tag"></i>
                                        <span>{{ ucfirst($reservation->product->category) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>Seller: {{ $reservation->product->user->name }}</span>
                                    </div>
                                    @if($reservation->reservation_data)
                                        <div class="meta-item">
                                            <i class="fas fa-envelope"></i>
                                            <span>Contact: {{ $reservation->reservation_data['email'] ?? 'N/A' }}</span>
                                        </div>
                                        @if(!empty($reservation->reservation_data['notes']))
                                            <div class="meta-item">
                                                <i class="fas fa-sticky-note"></i>
                                                <span>Notes: {{ Str::limit($reservation->reservation_data['notes'], 50) }}</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="order-price">
                                <div class="price-label">Price</div>
                                <div class="price-value">
                                    {{ $reservation->product->isFree() ? 'FREE' : number_format($reservation->product->price, 2) . ' TND' }}
                                </div>
                                @if($reservation->status === 'active')
                                    <button class="btn-cancel-reservation" onclick="cancelReservation({{ $reservation->id }}, this)" style="margin-top: 10px; padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                        <i class="fas fa-times"></i> Cancel Reservation
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-bookmark"></i>
                    <h3>No Reservations</h3>
                    <p>You haven't reserved any products yet.</p>
                    <a href="{{ route('front.shop') }}" class="btn-shop">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Browse Products</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tab) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tab + '-tab').classList.add('active');
    
    // Activate button
    event.target.closest('.tab-btn').classList.add('active');
}

// Cancel Reservation
function cancelReservation(reservationId, buttonElement) {
    if (!confirm('Are you sure you want to cancel this reservation?')) return;

    buttonElement.disabled = true;
    const originalHTML = buttonElement.innerHTML;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cancelling...';

    fetch(`/reservations/${reservationId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Reservation cancelled successfully!');
            // Reload page to update the display
            location.reload();
        } else {
            alert(data.message || 'Failed to cancel reservation');
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalHTML;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to cancel reservation');
        buttonElement.disabled = false;
        buttonElement.innerHTML = originalHTML;
    });
}
</script>
@endpush
