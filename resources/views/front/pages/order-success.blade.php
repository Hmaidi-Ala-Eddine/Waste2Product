@extends('layouts.front')

@section('title', 'Order Successful')

@push('styles')
<style>
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .success-wrapper {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 50%, #a5d6a7 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 120px 30px 80px;
    }

    .success-container {
        max-width: 700px;
        width: 100%;
    }

    .success-card {
        background: white;
        border-radius: 20px;
        padding: 60px 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        text-align: center;
        animation: slideUp 0.6s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.6s ease 0.3s both;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    .success-icon i {
        font-size: 60px;
        color: white;
    }

    .success-title {
        font-size: 36px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .success-message {
        font-size: 18px;
        color: #7f8c8d;
        margin-bottom: 40px;
        line-height: 1.6;
    }

    .order-info {
        background: #f5f7fa;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        border-left: 4px solid #4CAF50;
    }

    .order-info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 15px;
    }

    .order-info-row:last-child {
        margin-bottom: 0;
    }

    .order-label {
        color: #7f8c8d;
        font-weight: 500;
    }

    .order-value {
        color: #2c3e50;
        font-weight: 700;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 16px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary:hover {
        background: #f5f7fa;
        transform: translateY(-2px);
    }

    .success-footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f0f0f0;
    }

    .footer-text {
        color: #7f8c8d;
        font-size: 14px;
        line-height: 1.6;
    }

    .footer-text i {
        color: #4CAF50;
    }

    @media (max-width: 768px) {
        .success-card {
            padding: 40px 30px;
        }

        .success-title {
            font-size: 28px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="success-wrapper">
    <div class="success-container">
        <div class="success-card">
            <!-- Success Icon -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>

            <!-- Success Title -->
            <h1 class="success-title">Order Placed Successfully!</h1>

            <!-- Success Message -->
            <p class="success-message">
                Thank you for your purchase! Your order has been received and is being processed.
                You will receive an email confirmation shortly.
            </p>

            <!-- Order Info -->
            <div class="order-info">
                <div class="order-info-row">
                    <span class="order-label">Order Date</span>
                    <span class="order-value">{{ now()->format('M d, Y - H:i') }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-label">Status</span>
                    <span class="order-value" style="color: #4CAF50;">Pending</span>
                </div>
                <div class="order-info-row">
                    <span class="order-label">Payment Method</span>
                    <span class="order-value">{{ ucfirst(session('payment_method', 'Card')) }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('front.my-orders') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    <span>View My Orders</span>
                </a>
                <a href="{{ route('front.shop') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>Continue Shopping</span>
                </a>
            </div>

            <!-- Footer -->
            <div class="success-footer">
                <p class="footer-text">
                    <i class="fas fa-info-circle"></i> Your order will be processed within 24-48 hours.
                    <br>You can track your order status from your profile.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
