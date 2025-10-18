@extends('layouts.front')

@section('title', 'Payment Success - Order #' . $order->id)

@section('content')
<!-- Page Title -->
<section class="page-title" style="background-image: url({{ asset('assets/front/img/banner/page-title.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-content text-center">
                    <h1 class="title">Payment Successful</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payment Success</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Section -->
<section class="success-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="success-card text-center">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    
                    <h2 class="success-title mb-3">Payment Completed Successfully!</h2>
                    <p class="success-message mb-4">
                        Thank you for your purchase. Your order has been confirmed and payment has been processed.
                    </p>
                    
                    <div class="order-details mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Order Number:</strong>
                                    <span>#{{ $order->id }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Transaction ID:</strong>
                                    <span>{{ $order->transaction_id ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Payment Method:</strong>
                                    <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Total Amount:</strong>
                                    <span>{{ $order->formatted_total_price }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-item-summary mb-4">
                        <h5>Order Summary</h5>
                        <div class="order-item">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    @if($order->product->image_path && file_exists(storage_path('app/public/' . $order->product->image_path)))
                                        <img src="{{ asset('storage/' . $order->product->image_path) }}" 
                                             alt="{{ $order->product->name }}" 
                                             class="img-fluid rounded">
                                    @else
                                        <img src="{{ asset('assets/front/img/products/default-product.svg') }}" 
                                             alt="{{ $order->product->name }}" 
                                             class="img-fluid rounded">
                                    @endif
                                </div>
                                <div class="col-6">
                                    <h6 class="item-name">{{ $order->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $order->quantity }}</small>
                                </div>
                                <div class="col-3 text-end">
                                    <span class="item-price">{{ $order->formatted_total_price }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="next-steps mb-4">
                        <h5>What's Next?</h5>
                        <div class="steps">
                            <div class="step">
                                <i class="fas fa-envelope"></i>
                                <span>You will receive a confirmation email shortly</span>
                            </div>
                            <div class="step">
                                <i class="fas fa-truck"></i>
                                <span>Your order will be prepared for shipping</span>
                            </div>
                            <div class="step">
                                <i class="fas fa-home"></i>
                                <span>You will receive tracking information</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('front.home') }}" class="btn btn-primary me-3">
                            <i class="fas fa-home me-2"></i>Continue Shopping
                        </a>
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-download me-2"></i>Download Receipt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.success-section {
    background-color: #f8f9fa;
    min-height: 60vh;
}

.success-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.success-icon i {
    font-size: 4rem;
    color: #28a745;
}

.success-title {
    color: #2c3e50;
    font-weight: 700;
}

.success-message {
    color: #6c757d;
    font-size: 1.1rem;
}

.order-details {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.detail-item {
    margin-bottom: 10px;
}

.detail-item strong {
    color: #2c3e50;
    display: block;
    margin-bottom: 5px;
}

.detail-item span {
    color: #4a90e2;
    font-weight: 600;
}

.order-item-summary {
    border-top: 1px solid #e9ecef;
    padding-top: 20px;
}

.order-item-summary h5 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.order-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
}

.order-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
}

.item-name {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.item-price {
    font-weight: 600;
    color: #4a90e2;
}

.next-steps h5 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.steps {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.step {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px;
    background: #e3f2fd;
    border-radius: 8px;
}

.step i {
    color: #4a90e2;
    font-size: 1.2rem;
}

.step span {
    color: #2c3e50;
    font-weight: 500;
}

.action-buttons {
    margin-top: 30px;
}

.btn {
    padding: 12px 24px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4a90e2, #5aa3f0);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #357abd, #4a90e2);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .success-card {
        padding: 30px 20px;
    }
    
    .success-icon i {
        font-size: 3rem;
    }
    
    .steps {
        gap: 10px;
    }
    
    .step {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>
@endpush
