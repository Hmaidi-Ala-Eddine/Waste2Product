@extends('layouts.front')

@section('title', 'Order Success')

@push('styles')
<style>
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
        padding: 0 20px;
        text-align: center;
    }

    .success-card {
        background: white;
        border-radius: 20px;
        padding: 50px 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        animation: bounceIn 0.8s ease-out;
    }

    .success-icon i {
        font-size: 3rem;
        color: white;
    }

    .success-title {
        color: #2c3e50;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .success-message {
        color: #666;
        font-size: 1.2rem;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .success-actions {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-success-action {
        padding: 15px 30px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: transparent;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary:hover {
        background: #667eea;
        color: white;
        text-decoration: none;
    }

    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .success-actions {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-success-action {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="success-wrapper">
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <h1 class="success-title">Order Successful!</h1>
            
            <p class="success-message">
                Thank you for your purchase! Your order has been placed successfully and you will receive a confirmation email shortly.
            </p>
            
            <div class="success-actions">
                <a href="{{ route('front.my-orders') }}" class="btn-success-action btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    View My Orders
                </a>
                
                <a href="{{ route('front.shop') }}" class="btn-success-action btn-secondary">
                    <i class="fas fa-store"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection