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
    </script>
    @stack('scripts')
</body>
</html>
