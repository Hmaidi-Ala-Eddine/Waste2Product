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
                                    <a href="{{ config('site.intro_video_url') }}" class="popup-youtube video-play-button"><i class="fas fa-play"></i></a>
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
                        <li><a href="{{ route('front.team') }}">Posts</a></li>
                        <li><a href="{{ route('front.products') }}">Products</a></li>
                        <li><a href="{{ route('front.events') }}">Events</a></li>
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
                    @auth
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Services</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('front.waste-requests') }}"><i class="fas fa-clipboard-list me-2"></i>My Waste Requests</a></li>
                                <li><a href="{{ route('front.collector-application') }}"><i class="fas fa-user-tie me-2"></i>Collector Application</a></li>
                                @php
                                    $user = auth()->user();
                                    $isVerifiedCollector = $user->collector && $user->collector->verification_status === 'verified';
                                @endphp
                                @if($isVerifiedCollector)
                                    <li><a href="{{ route('front.collector-dashboard') }}"><i class="fas fa-truck me-2"></i>Collector Dashboard</a></li>
                                @endif
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>

            <div class="attr-right">
                <div class="attr-nav">
                    <ul>
                        <!-- Cart Icon -->
                        <li class="cart-item">
                            <a href="{{ route('front.cart') }}" class="cart-link">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-counter badge bg-primary" style="display: none;">0</span>
                            </a>
                        </li>
                        
                        @auth
                            @php
                                $ordersCount = \App\Models\Order::where('user_id', auth()->id())->count();
                                $recentOrders = \App\Models\Order::with('product')
                                    ->where('user_id', auth()->id())
                                    ->orderByDesc('ordered_at')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="My Orders">
                                    Orders
                                    <span class="badge" style="background:#777;color:#fff;border-radius:10px;padding:2px 6px;font-size:11px;vertical-align: top;">{{ $ordersCount }}</span>
                                </a>
                                <ul class="dropdown-menu" style="min-width:320px;padding:15px;">
                                    <li>
                                        <h6 style="margin:0 0 10px;">My Recent Orders</h6>
                                        @if($recentOrders->isEmpty())
                                            <p style="margin:0;">No orders yet.</p>
                                        @else
                                            <div style="max-height:260px;overflow:auto;">
                                                <ul style="list-style:none;margin:0;padding:0;">
                                                    @foreach($recentOrders as $order)
                                                        <li style="padding:8px 0;border-bottom:1px solid #eee;">
                                                            <div style="display:flex;justify-content:space-between;gap:8px;">
                                                                <div>
                                                                    <div style="font-weight:600;">{{ optional($order->product)->name ?? 'Product #'.$order->product_id }}</div>
                                                                    <div style="font-size:12px;color:#666;">Qty: {{ $order->quantity }} · {{ ucfirst($order->status) }}</div>
                                                                </div>
                                                                <div style="white-space:nowrap;font-weight:600;">${{ number_format((float)$order->total_price, 2) }}</div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </li>
                                    <li style="text-align:right;padding-top:8px;border-top:1px solid #eee;">
                                        <a href="{{ route('front.orders') }}" style="font-size:13px;">View all</a>
                                    </li>
                                </ul>
                            </li>
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

<script>
// Load cart counter on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCartCounter();
});

function loadCartCounter() {
    fetch('{{ route("front.cart.summary") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartCounter = document.querySelector('.cart-counter');
                if (cartCounter) {
                    cartCounter.textContent = data.cart_items_count;
                    cartCounter.style.display = data.cart_items_count > 0 ? 'inline' : 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error loading cart counter:', error);
        });
}
</script>

<style>
/* Cart Icon Styling */
.cart-item {
    position: relative;
    margin-right: 15px;
}

.cart-link {
    display: flex;
    align-items: center;
    color: #333;
    text-decoration: none;
    font-size: 18px;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 8px;
}

.cart-link:hover {
    color: #4a90e2;
    background-color: #f8f9fa;
    text-decoration: none;
}

.cart-counter {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    background: #dc3545 !important;
    color: white;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-counter:empty {
    display: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .cart-link {
        font-size: 16px;
        padding: 6px 10px;
    }
    
    .cart-counter {
        font-size: 9px;
        padding: 1px 4px;
        min-width: 16px;
        height: 16px;
    }
}
</style>
