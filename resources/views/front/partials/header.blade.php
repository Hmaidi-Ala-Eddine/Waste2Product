<header>
    <nav class="navbar mobile-sidenav navbar-sticky navbar-default validnavs navbar-fixed {{ request()->routeIs('front.home') || request()->routeIs('front.onepage') ? 'white no-background' : '' }}">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ route('front.home') }}">
                    <img src="{{ asset('assets/front/img/logo-light.png') }}" class="logo logo-display" alt="Logo">
                    <img src="{{ asset('assets/front/img/logo.png') }}" class="logo logo-scrolled" alt="Logo">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="collapse-header">
                    <img src="{{ asset('assets/front/img/logo.png') }}" alt="Logo">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <ul class="nav navbar-nav navbar-center" data-in="fadeInDown" data-out="fadeOutUp">
                    <li class="dropdown megamenu-fw">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Home</a>
                        <ul class="dropdown-menu megamenu-content" role="menu">
                            <li>
                                <div class="col-menu-wrap">
                                    <div class="col-item">
                                        <div class="menu-thumb">
                                            <img src="{{ asset('assets/front/img/demo/home-1.jpg') }}" alt="Image Not Found">
                                            <div class="overlay">
                                                <a href="{{ route('front.home') }}">Multipage</a>
                                                <a href="{{ route('front.onepage') }}">Onepage</a>
                                            </div>
                                        </div>
                                        <h6 class="title"><a href="{{ route('front.home') }}">Business Consulting</a></h6>
                                    </div>
                                    <div class="col-item">
                                        <div class="menu-thumb">
                                            <img src="{{ asset('assets/front/img/demo/home-2.jpg') }}" alt="Image Not Found">
                                            <div class="overlay">
                                                <a href="{{ route('front.home3') }}">Multipage</a>
                                                <a href="{{ route('front.home3.onepage') }}">Onepage</a>
                                            </div>
                                        </div>
                                        <h6 class="title"><a href="{{ route('front.home3') }}">It Solutions</a></h6>
                                    </div>
                                    <div class="col-item">
                                        <div class="menu-thumb">
                                            <img src="{{ asset('assets/front/img/demo/home-6.jpg') }}" alt="Image Not Found">
                                            <div class="overlay">
                                                <a href="{{ route('front.home6') }}">Multipage</a>
                                                <a href="{{ route('front.home6.onepage') }}">Onepage</a>
                                            </div>
                                        </div>
                                        <h6 class="title"><a href="{{ route('front.home6') }}">Artificial Intelligence</a></h6>
                                    </div>
                                    <div class="col-item">
                                        <div class="menu-thumb">
                                            <img src="{{ asset('assets/front/img/demo/home-3.jpg') }}" alt="Image Not Found">
                                            <div class="overlay">
                                                <a href="{{ route('front.home4') }}">Multipage</a>
                                                <a href="{{ route('front.home4.onepage') }}">Onepage</a>
                                            </div>
                                        </div>
                                        <h6 class="title"><a href="{{ route('front.home4') }}">Creative Agency</a></h6>
                                    </div>
                                    <div class="col-item">
                                        <div class="menu-thumb">
                                            <img src="{{ asset('assets/front/img/demo/home-4.jpg') }}" alt="Image Not Found">
                                            <div class="overlay">
                                                <a href="{{ route('front.home5') }}">Multipage</a>
                                                <a href="{{ route('front.home5.onepage') }}">Onepage</a>
                                            </div>
                                        </div>
                                        <h6 class="title"><a href="{{ route('front.home5') }}">Transport & Logistics</a></h6>
                                    </div>
                                    <div class="col-item">
                                        <div class="menu-thumb">
                                            <img src="{{ asset('assets/front/img/demo/home-5.jpg') }}" alt="Image Not Found">
                                            <div class="overlay">
                                                <a href="{{ route('front.home6') }}">Multipage</a>
                                                <a href="{{ route('front.home6.onepage') }}">Onepage</a>
                                            </div>
                                        </div>
                                        <h6 class="title"><a href="{{ route('front.home6') }}">Financial Advisor</a></h6>
                                    </div>
                                </div>
                                <div class="megamenu-banner">
                                    <img src="{{ asset('assets/front/img/thumb/7.jpg') }}" alt="Image Not Found">
                                    <a href="https://www.youtube.com/watch?v=aTC_RNYtEb0" class="popup-youtube video-play-button"><i class="fas fa-play"></i></a>
                                    <h6 class="title"><a href="#">Watch Intro Video</a></h6>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pages</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('front.about') }}">About Us</a></li>
                            <li><a href="{{ route('front.about2') }}">About Us Two</a></li>
                            <li><a href="{{ route('front.team') }}">Team</a></li>
                            <li><a href="{{ route('front.team2') }}">Team Two</a></li>
                            <li><a href="{{ route('front.team.details') }}">Team Details</a></li>
                            <li><a href="{{ route('front.pricing') }}">Pricing</a></li>
                            <li><a href="{{ route('front.faq') }}">FAQ</a></li>
                            <li><a href="{{ route('front.contact') }}">Contact Us</a></li>
                            <li><a href="{{ route('front.404') }}">Error Page</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="{{ route('front.project') }}" class="dropdown-toggle" data-toggle="dropdown">Projects</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('front.project') }}">Project style one</a></li>
                            <li><a href="{{ route('front.project2') }}">Project style two</a></li>
                            <li><a href="{{ route('front.project3') }}">Project style three</a></li>
                            <li><a href="{{ route('front.project.details') }}">Project Details</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Services</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('front.services') }}">Services Version One</a></li>
                            <li><a href="{{ route('front.services2') }}">Services Version Two</a></li>
                            <li><a href="{{ route('front.services3') }}">Services Version Three</a></li>
                            <li><a href="{{ route('front.services.details') }}">Services Details</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Blog</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('front.blog.standard') }}">Blog Standard</a></li>
                            <li><a href="{{ route('front.blog.with_sidebar') }}">Blog With Sidebar</a></li>
                            <li><a href="{{ route('front.blog.2col') }}">Blog Grid Two Colum</a></li>
                            <li><a href="{{ route('front.blog.3col') }}">Blog Grid Three Colum</a></li>
                            <li><a href="{{ route('front.blog.single') }}">Blog Single</a></li>
                            <li><a href="{{ route('front.blog.single_with_sidebar') }}">Blog Single With Sidebar</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('front.contact') }}">contact</a></li>
                </ul>
            </div>

            <div class="attr-right">
                <div class="attr-nav">
                    <ul>
                        @auth
                            <li class="logout-item">
                                <form method="POST" action="{{ route('front.logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="logout-btn">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="login-item">
                                <a href="{{ url('/login') }}" class="login-link">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>Login</span>
                                </a>
                            </li>
                        @endauth
                        <li class="contact">
                            <div class="call">
                                <div class="icon">
                                    <i class="fas fa-comments-alt-dollar"></i>
                                </div>
                                <div class="info">
                                    <p>Have any Questions?</p>
                                    <h5><a href="mailto:info@bestup.com">info@bestup.com</a></h5>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="overlay-screen"></div>
    </nav>
</header>
