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
                    <li>
                        <a href="{{ route('front.home') }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('front.about') }}">About Us</a>
                    </li>
                    <li>
                        <a href="{{ route('front.project') }}">Projects</a>
                    </li>
                    <li>
                        <a href="{{ route('front.services') }}">Services</a>
                    </li>
                    <li>
                        <a href="{{ route('front.blog.standard') }}">Blog</a>
                    </li>
                    <li>
                        <a href="{{ route('front.contact') }}">Contact</a>
                    </li>
                    @auth
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Services</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('front.posts') }}"><i class="fas fa-newspaper me-2"></i>Posts</a></li>
                                <li><a href="{{ route('front.waste-requests') }}"><i class="fas fa-clipboard-list me-2"></i>My Waste Requests</a></li>
                                <li><a href="{{ route('front.collector-application') }}"><i class="fas fa-user-tie me-2"></i>Collector Application</a></li>
                                @php
                                    $user = auth()->user();
                                    $isVerifiedCollector = $user->collector && $user->collector->verification_status === 'verified';
                                @endphp
                                @if($isVerifiedCollector)
                                    <li><a href="{{ route('front.collector-dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Collector Dashboard</a></li>
                                @endif
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>

            <div class="attr-right">
                <div class="attr-nav">
                    <ul>
                        @auth
                            <li class="profile-dropdown-item dropdown">
                                <a href="#" class="profile-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" class="profile-avatar">
                                </a>
                                <ul class="dropdown-menu profile-dropdown">
                                    <li class="profile-header">
                                        <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}">
                                        <div class="profile-info">
                                            <h6>{{ auth()->user()->name }}</h6>
                                            <p>{{ auth()->user()->email }}</p>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="{{ route('front.profile') }}">
                                            <i class="fas fa-user"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('front.logout') }}">
                                            @csrf
                                            <button type="submit" class="logout-dropdown-btn">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
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
