<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Front Office')</title>
    <link rel="shortcut icon" href="{{ asset('assets/front/img/favicon.png') }}" type="image/x-icon">
    <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/validnavs.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/unit-test.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/overrides.css') }}" rel="stylesheet">
    
    <!-- Clean logout/login button styling -->
    <style>
        .logout-btn, .login-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            margin-right: 15px;
        }
        
        .logout-btn:hover, .login-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .logout-btn:active {
            transform: translateY(0);
        }
        
        .logout-item, .login-item {
            display: flex;
            align-items: center;
        }

        /* Navbar Menu Items - Always visible */
        .navbar-nav > li > a {
            color: #2c3e50 !important; /* make navbar links black/dark */
            text-shadow: none; /* remove white text shadow */
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        /* White navbar and scrolled state - dark text */
        .navbar.white .navbar-nav > li > a,
        .navbar.navbar-scrolled .navbar-nav > li > a,
        .navbar.sticked .navbar-nav > li > a,
        .navbar-sticky.navbar-scrolled .navbar-nav > li > a {
            color: #2c3e50 !important;
            text-shadow: none;
        }
        
        /* Logo color on scrolled */
        .navbar.navbar-scrolled .navbar-brand,
        .navbar.sticked .navbar-brand {
            color: #2c3e50 !important;
        }
        
        /* Navbar Green Hover Effects */
        .navbar-nav > li > a:hover,
        .navbar-nav > li > a:focus {
            color: #4CAF50 !important;
        }

        .navbar-nav > li.dropdown:hover > a,
        .navbar-nav > li.dropdown.open > a {
            color: #4CAF50 !important;
        }

        /* Dropdown Menu Styling - White background with dark text */
        .dropdown-menu {
            background-color: white !important;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            padding: 8px 0;
            border: 1px solid rgba(0,0,0,0.08);
            margin-top: 10px;
        }
        
        .dropdown-menu > li > a {
            color: #2c3e50 !important;
            font-weight: 500;
            padding: 12px 24px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .dropdown-menu > li > a i {
            color: #667eea;
            width: 24px;
            display: inline-block;
            font-size: 14px;
        }
        
        .dropdown-menu > li > a:hover,
        .dropdown-menu > li > a:focus {
            background: linear-gradient(135deg, #E8F5E8 0%, #D4F1D4 100%) !important;
            color: #2E7D32 !important;
            padding-left: 28px;
            transform: translateX(4px);
        }
        
        .dropdown-menu > li > a:hover i,
        .dropdown-menu > li > a:focus i {
            color: #2E7D32 !important;
        }
        
        /* Dropdown separator */
        .dropdown-menu > li {
            border-bottom: 1px solid #f0f0f0;
        }
        
        .dropdown-menu > li:last-child {
            border-bottom: none;
        }

        /* Profile Dropdown Styling */
        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .profile-dropdown {
            min-width: 280px !important;
            padding: 0 !important;
            margin-top: 15px !important;
        }

        .profile-header {
            display: flex !important;
            align-items: center;
            gap: 12px;
            padding: 20px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px 10px 0 0 !important;
            border-bottom: none !important;
        }

        .profile-header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .profile-info h6 {
            margin: 0;
            color: white !important;
            font-size: 16px;
            font-weight: 600;
        }

        .profile-info p {
            margin: 0;
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 13px;
        }

        .profile-dropdown li.divider {
            height: 1px;
            background: #e0e0e0;
            margin: 8px 0;
            padding: 0 !important;
        }

        .profile-dropdown > li > a,
        .logout-dropdown-btn {
            color: #2c3e50 !important;
            padding: 12px 20px !important;
            display: flex !important;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .profile-dropdown > li > a i,
        .logout-dropdown-btn i {
            width: 20px;
            color: #667eea;
        }

        .profile-dropdown > li > a:hover,
        .logout-dropdown-btn:hover {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8eeff 100%) !important;
            color: #667eea !important;
            transform: translateX(4px);
        }

        .profile-dropdown > li > a:hover i,
        .logout-dropdown-btn:hover i {
            color: #667eea !important;
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
        }

        /* Notification and Cart Icons - Match Navbar Colors */
        .attr-nav > ul > li.icon-item {
            display: inline-flex !important;
            align-items: center !important;
            margin: 0 8px !important;
            padding: 0 !important;
            height: 100% !important;
        }

        /* Default state - DARK icons (for white/scrolled navbar) */
        .attr-nav > ul > li.icon-item > a.icon-link {
            position: relative !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            background: transparent !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .attr-nav > ul > li.icon-item > a.icon-link i {
            font-size: 20px !important;
            line-height: 1 !important;
            margin: 0 !important;
            color: #2c3e50 !important;
            text-shadow: none !important;
            transition: all 0.3s ease !important;
        }

        .attr-nav > ul > li.icon-item > a.icon-link:hover {
            background: transparent !important;
            transform: scale(1.15) !important;
        }

        .attr-nav > ul > li.icon-item > a.icon-link:hover i {
            color: #4CAF50 !important;
            filter: drop-shadow(0 0 8px rgba(76, 175, 80, 0.6)) !important;
        }

        /* Badge count - Always visible */
        .attr-nav > ul > li.icon-item > a.icon-link .badge-count {
            position: absolute !important;
            top: -4px !important;
            right: -4px !important;
            background: #ff4757 !important;
            color: white !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 2px 6px !important;
            border-radius: 10px !important;
            min-width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 8px rgba(255, 71, 87, 0.6) !important;
            border: 2px solid white !important;
            line-height: 1 !important;
        }

        /* Home page/Transparent navbar - WHITE icons to match white text */
        .navbar.white .attr-nav > ul > li.icon-item > a.icon-link i,
        .navbar.no-background .attr-nav > ul > li.icon-item > a.icon-link i {
            color: white !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
        }

        .navbar.white .attr-nav > ul > li.icon-item > a.icon-link:hover i,
        .navbar.no-background .attr-nav > ul > li.icon-item > a.icon-link:hover i {
            color: #4CAF50 !important;
            filter: drop-shadow(0 0 10px rgba(76, 175, 80, 0.8)) !important;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 40px 0;
            padding: 0;
            list-style: none;
            gap: 8px;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            margin: 0 2px;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            background: white;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .pagination .active span {
            background: #667eea;
            color: white;
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .pagination .disabled span {
            background: #f8f9fa;
            color: #adb5bd;
            border-color: #e9ecef;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Pagination wrapper */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin: 40px 0;
        }

        /* Responsive pagination */
        @media (max-width: 768px) {
            .pagination {
                flex-wrap: wrap;
                gap: 4px;
            }
            
            .pagination a,
            .pagination span {
                min-width: 36px;
                height: 36px;
                font-size: 13px;
                padding: 6px 10px;
            }
        }

        /* Hide pagination icons that might be too large */
        .pagination i {
            font-size: 12px !important;
            line-height: 1 !important;
        }

        .pagination .fa-chevron-left,
        .pagination .fa-chevron-right,
        .pagination .fa-angle-left,
        .pagination .fa-angle-right {
            font-size: 10px !important;
        }
    </style>
    
    @stack('styles')
</head>

<body>
    <div id="preloader">
        <div id="gixus-preloader" class="gixus-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="G" class="letters-loading">G</span>
                    <span data-text-preloader="I" class="letters-loading">I</span>
                    <span data-text-preloader="X" class="letters-loading">X</span>
                    <span data-text-preloader="U" class="letters-loading">U</span>
                    <span data-text-preloader="S" class="letters-loading">S</span>
                </div>
            </div>
            <div class="loader">
                <div class="row">
                    <div class="col-3 loader-section section-left"><div class="bg"></div></div>
                    <div class="col-3 loader-section section-left"><div class="bg"></div></div>
                    <div class="col-3 loader-section section-right"><div class="bg"></div></div>
                    <div class="col-3 loader-section section-right"><div class="bg"></div></div>
                </div>
            </div>
        </div>
    </div>

    @include('front.partials.header')

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show auto-dismiss-alert" role="alert" style="margin: 0; border-radius: 0; z-index: 1050; background-color: #4CAF50; border-color: #4CAF50; color: white;">
            <div class="container">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 0; border-radius: 0; z-index: 1050;">
            <div class="container">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 0; border-radius: 0; z-index: 1050;">
            <div class="container">
                <strong>Error!</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('front.partials.footer')

    <script src="{{ asset('assets/front/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/progress-bar.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/count-to.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/circle-progress.js') }}"></script>
    <script src="{{ asset('assets/front/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/YTPlayer.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/validnavs.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.lettering.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.circleType.js') }}"></script>
    <script src="{{ asset('assets/front/js/gsap.js') }}"></script>
    <script src="{{ asset('assets/front/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/SplitText.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/main.js') }}"></script>
    <script>
        (function(){
            // Balanced preloader: brief display for animation, then fade out fast
            var MIN_PRELOAD_MS = 1000; // tweak between 800-1200 for taste
            var t0 = (window.performance && performance.now) ? performance.now() : 0;
            window.addEventListener('load', function(){
                var elapsed = (window.performance && performance.now) ? (performance.now() - t0) : 0;
                var remaining = Math.max(0, MIN_PRELOAD_MS - elapsed);
                setTimeout(function(){
                    document.body.classList.add('loaded');
                }, remaining);
            });
        })();

        // Auto-dismiss success alerts after 4 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const autoDismissAlerts = document.querySelectorAll('.auto-dismiss-alert');
            autoDismissAlerts.forEach(function(alert) {
                setTimeout(function() {
                    // Use Bootstrap's alert dismiss method
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 4000); // 4 seconds
            });
        });

        // Global notification system
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.global-notification');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = `global-notification ${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <div class="notification-icon">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    </div>
                    <div class="notification-message">${message}</div>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            // Add styles
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                max-width: 400px;
                animation: slideInRight 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .notification-content {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .notification-icon {
                font-size: 18px;
            }
            .notification-message {
                flex: 1;
                font-weight: 500;
            }
            .notification-close {
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 0;
                font-size: 14px;
            }
        `;
        document.head.appendChild(style);
    </script>
    @stack('scripts')
</body>
</html>
