<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
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
    </script>
    @stack('scripts')
</body>
</html>
