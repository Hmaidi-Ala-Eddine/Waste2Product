@extends('layouts.front')

@section('title', 'Payment Failed - Order #' . $order->id)

@section('content')
<!-- Page Title -->
<section class="page-title" style="background-image: url({{ asset('assets/front/img/banner/page-title.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-content text-center">
                    <h1 class="title">Payment Failed</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payment Failed</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Failure Section -->
<section class="failure-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="failure-card text-center">
                    <div class="failure-icon mb-4">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    
                    <h2 class="failure-title mb-3">Payment Failed</h2>
                    <p class="failure-message mb-4">
                        Unfortunately, your payment could not be processed. Please try again or choose a different payment method.
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
                                    <strong>Payment Method:</strong>
                                    <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Amount:</strong>
                                    <span>{{ $order->formatted_total_price }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Status:</strong>
                                    <span class="text-danger">{{ ucfirst($order->payment_status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($order->payment_notes)
                    <div class="error-details mb-4">
                        <h5>Error Details</h5>
                        <div class="alert alert-danger">
                            {{ $order->payment_notes }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="troubleshooting mb-4">
                        <h5>Common Solutions</h5>
                        <div class="solutions">
                            <div class="solution">
                                <i class="fas fa-credit-card"></i>
                                <span>Check your card details and available balance</span>
                            </div>
                            <div class="solution">
                                <i class="fas fa-wifi"></i>
                                <span>Ensure you have a stable internet connection</span>
                            </div>
                            <div class="solution">
                                <i class="fas fa-shield-alt"></i>
                                <span>Contact your bank if the payment is blocked</span>
                            </div>
                            <div class="solution">
                                <i class="fas fa-redo"></i>
                                <span>Try a different payment method</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('front.payment.show', $order->id) }}" class="btn btn-primary me-3">
                            <i class="fas fa-redo me-2"></i>Try Again
                        </a>
                        <a href="{{ route('front.cart') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-shopping-cart me-2"></i>Back to Cart
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
.failure-section {
    background-color: #f8f9fa;
    min-height: 60vh;
}

.failure-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.failure-icon i {
    font-size: 4rem;
    color: #dc3545;
}

.failure-title {
    color: #2c3e50;
    font-weight: 700;
}

.failure-message {
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

.error-details h5 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.troubleshooting h5 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.solutions {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.solution {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px;
    background: #fff3cd;
    border-radius: 8px;
    border-left: 4px solid #ffc107;
}

.solution i {
    color: #ffc107;
    font-size: 1.2rem;
}

.solution span {
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
    .failure-card {
        padding: 30px 20px;
    }
    
    .failure-icon i {
        font-size: 3rem;
    }
    
    .solutions {
        gap: 10px;
    }
    
    .solution {
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
