<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('assets/back/img/recycle.png') }}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Waste2Product</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.dashboard') }}">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.users') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.users') }}">
            <i class="material-symbols-rounded opacity-5">group</i>
            <span class="nav-link-text ms-1">Users</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.waste-requests') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.waste-requests') }}">
            <i class="material-symbols-rounded opacity-5">recycling</i>
            <span class="nav-link-text ms-1">Waste Requests</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.collectors') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.collectors') }}">
            <i class="material-symbols-rounded opacity-5">local_shipping</i>
            <span class="nav-link-text ms-1">Collectors</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.events') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.events') }}">
            <i class="material-symbols-rounded opacity-5">event</i>
            <span class="nav-link-text ms-1">Events</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.eco-ideas') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.eco-ideas') }}">
            <i class="material-symbols-rounded opacity-5">lightbulb</i>
            <span class="nav-link-text ms-1">Eco Ideas</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.posts') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.posts') }}">
            <i class="material-symbols-rounded opacity-5">article</i>
            <span class="nav-link-text ms-1">Posts</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.events') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.events') }}">
            <i class="material-symbols-rounded opacity-5">event</i>
            <span class="nav-link-text ms-1">Events</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.products.index') }}">
            <i class="material-symbols-rounded opacity-5">inventory</i>
            <span class="nav-link-text ms-1">Products</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.eco-ideas*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.eco-ideas') }}">
            <i class="material-symbols-rounded opacity-5">lightbulb</i>
            <span class="nav-link-text ms-1">Eco Ideas</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.orders.index') }}">
            <i class="material-symbols-rounded opacity-5">shopping_cart</i>
            <span class="nav-link-text ms-1">Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.analytics.index') }}">
            <i class="material-symbols-rounded opacity-5">analytics</i>
            <span class="nav-link-text ms-1">Analytics</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.map') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.map') }}">
            <i class="material-symbols-rounded opacity-5">map</i>
            <span class="nav-link-text ms-1">Map</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.profile') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.profile') }}">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <!-- Sign In removed from sidebar - users are already logged in -->
      </ul>
    </div>
  </aside>
