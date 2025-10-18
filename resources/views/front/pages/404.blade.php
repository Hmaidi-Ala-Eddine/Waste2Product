@extends('layouts.front')

@section('title', '404 - Page Not Found')

@push('styles')
<style>
    /* Navbar text color fix for 404 page */
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .navbar-nav > li > a:hover,
    .navbar-nav > li > a:focus {
        color: #667eea !important;
    }

    .error-page-wrapper {
        background: linear-gradient(135deg, #667eea15 0%, #764ba220 20%, #ffffff 50%, #f8f9fa 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 120px 20px 80px;
        position: relative;
        overflow: hidden;
    }

    .error-page-wrapper::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -15%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .error-page-wrapper::after {
        content: '';
        position: absolute;
        bottom: -25%;
        left: -15%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(118, 75, 162, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .error-container {
        max-width: 700px;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .error-content {
        background: rgba(255, 255, 255, 0.95);
        padding: 60px 40px;
        border-radius: 30px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 8px 20px rgba(102, 126, 234, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.1);
        backdrop-filter: blur(10px);
    }

    .error-number {
        font-size: 150px;
        font-weight: 900;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        margin-bottom: 20px;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    .error-icon {
        font-size: 80px;
        color: #667eea;
        margin-bottom: 25px;
        opacity: 0.8;
    }

    .error-title {
        font-size: 36px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .error-description {
        font-size: 18px;
        color: #7f8c8d;
        margin-bottom: 35px;
        line-height: 1.6;
    }

    .error-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-error {
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary-error {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-error:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary-error {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary-error:hover {
        background: #667eea;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .helpful-links {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid rgba(102, 126, 234, 0.1);
    }

    .helpful-links h4 {
        font-size: 16px;
        color: #7f8c8d;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
    }

    .link-item {
        padding: 12px 20px;
        background: rgba(102, 126, 234, 0.05);
        border-radius: 12px;
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .link-item:hover {
        background: rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
        color: #764ba2;
    }

    @media (max-width: 768px) {
        .error-number {
            font-size: 100px;
        }

        .error-title {
            font-size: 28px;
        }

        .error-description {
            font-size: 16px;
        }

        .error-content {
            padding: 40px 25px;
        }

        .btn-error {
            padding: 12px 25px;
            font-size: 14px;
        }

        .links-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="error-page-wrapper">
    <div class="error-container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="error-number">404</h1>
            <h2 class="error-title">Page Not Found</h2>
            <p class="error-description">
                Oops! The page you're looking for doesn't exist. It might have been moved or deleted.
            </p>

            <div class="error-actions">
                <a href="{{ route('front.home') }}" class="btn-error btn-primary-error">
                    <i class="fas fa-home"></i>
                    Back to Home
                </a>
                <a href="javascript:history.back()" class="btn-error btn-secondary-error">
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </a>
            </div>

            <div class="helpful-links">
                <h4>You might be looking for:</h4>
                <div class="links-grid">
                    <a href="{{ route('front.about') }}" class="link-item">About Us</a>
                    <a href="{{ route('front.services') }}" class="link-item">Services</a>
                    <a href="{{ route('front.project') }}" class="link-item">Projects</a>
                    <a href="{{ route('front.contact') }}" class="link-item">Contact</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


