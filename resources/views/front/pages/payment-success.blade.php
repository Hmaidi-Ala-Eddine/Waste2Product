@extends('layouts.front')

@section('title', 'Payment Successful')

@push('styles')
<style>
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .success-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .success-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 0 30px;
        text-align: center;
    }

    .success-card {
        background: white;
        border-radius: 20px;
        padding: 60px 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .success-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .success-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        animation: pulse 2s infinite;
    }

    .success-icon i {
        font-size: 60px;
        color: white;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
        }
        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 20px rgba(40, 167, 69, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
        }
    }

    .success-title {
        font-size: 36px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .success-subtitle {
        font-size: 20px;
        color: #28a745;
        font-weight: 600;
        margin-bottom: 30px;
    }

    .success-message {
        font-size: 16px;
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 40px;
    }

    .success-details {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 40px;
        text-align: left;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #6c757d;
    }

    .detail-value {
        font-weight: 500;
        color: #2c3e50;
    }

    .success-actions {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 15px 30px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary:hover {
        background: #667eea;
        color: white;
        transform: translateY(-3px);
    }

    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #ffd700;
        animation: confetti-fall 3s linear infinite;
    }

    .confetti:nth-child(1) { left: 10%; animation-delay: 0s; }
    .confetti:nth-child(2) { left: 20%; animation-delay: 0.5s; }
    .confetti:nth-child(3) { left: 30%; animation-delay: 1s; }
    .confetti:nth-child(4) { left: 40%; animation-delay: 1.5s; }
    .confetti:nth-child(5) { left: 50%; animation-delay: 2s; }
    .confetti:nth-child(6) { left: 60%; animation-delay: 2.5s; }
    .confetti:nth-child(7) { left: 70%; animation-delay: 0.2s; }
    .confetti:nth-child(8) { left: 80%; animation-delay: 0.7s; }
    .confetti:nth-child(9) { left: 90%; animation-delay: 1.2s; }

    @keyframes confetti-fall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }

    @media (max-width: 768px) {
        .success-card {
            padding: 40px 20px;
        }

        .success-title {
            font-size: 28px;
        }

        .success-subtitle {
            font-size: 18px;
        }

        .success-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="success-wrapper">
    <!-- Confetti Animation -->
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>

    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>

            <h1 class="success-title">Payment Successful!</h1>
            <h2 class="success-subtitle">Your order has been completed</h2>

            <p class="success-message">
                Thank you for your purchase! Your payment has been processed successfully and your order is now complete. 
                You will receive a confirmation email shortly with all the details.
            </p>

            <div class="success-details">
                <div class="detail-item">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">Credit Card</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Transaction Status</span>
                    <span class="detail-value" style="color: #28a745; font-weight: 600;">Completed</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Order Status</span>
                    <span class="detail-value" style="color: #28a745; font-weight: 600;">Completed</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value">TXN-{{ time() }}</span>
                </div>
            </div>

            <div class="success-actions">
                <a href="{{ route('front.my-orders') }}" class="btn-action btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    <span>View My Orders</span>
                </a>
                <a href="{{ route('front.shop') }}" class="btn-action btn-secondary">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Continue Shopping</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add some interactive effects
document.addEventListener('DOMContentLoaded', function() {
    // Add a subtle bounce effect to the success icon
    const icon = document.querySelector('.success-icon');
    icon.style.animation = 'pulse 2s infinite, bounce 1s ease-in-out 0.5s';
    
    // Add CSS for bounce animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush

