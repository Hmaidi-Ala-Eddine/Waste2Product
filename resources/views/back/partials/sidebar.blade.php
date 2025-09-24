<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('assets/back/img/logo-ct-dark.png') }}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
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
          <a class="nav-link {{ request()->routeIs('admin.tables') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.tables') }}">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.billing') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.billing') }}">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Billing</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.virtual-reality') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.virtual-reality') }}">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Virtual Reality</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.rtl') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.rtl') }}">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1">RTL</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.notifications') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.notifications') }}">
            <i class="material-symbols-rounded opacity-5">notifications</i>
            <span class="nav-link-text ms-1">Notifications</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.icons') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.icons') }}">
            <i class="material-symbols-rounded opacity-5">face</i>
            <span class="nav-link-text ms-1">Icons</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.typography') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.typography') }}">
            <i class="material-symbols-rounded opacity-5">text_fields</i>
            <span class="nav-link-text ms-1">Typography</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.map') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.map') }}">
            <i class="material-symbols-rounded opacity-5">map</i>
            <span class="nav-link-text ms-1">Map</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.landing') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.landing') }}">
            <i class="material-symbols-rounded opacity-5">rocket_launch</i>
            <span class="nav-link-text ms-1">Landing</span>
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
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.sign-in') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.sign-in') }}">
            <i class="material-symbols-rounded opacity-5">login</i>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.sign-up') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.sign-up') }}">
            <i class="material-symbols-rounded opacity-5">assignment</i>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0">
      <div class="mx-3">
        <a class="btn btn-outline-dark mt-4 w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard?ref=sidebarfree" type="button">Documentation</a>
        <a class="btn bg-gradient-dark w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
      </div>
    </div>
  </aside>
