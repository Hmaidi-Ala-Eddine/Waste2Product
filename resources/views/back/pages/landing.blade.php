<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/back/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/back/img/favicon.png') }}">
  <title>Landing - Waste2Product Admin</title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/back/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/back/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/back/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
</head>

<body class="landing-page bg-gray-200">
  <!-- Navbar -->
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-fixed shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid px-0">
            <a class="navbar-brand font-weight-bolder ms-sm-3" href="{{ route('admin.dashboard') }}" title="Waste2Product Admin">
              Waste2Product Admin
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm bg-gradient-dark mb-0 ms-auto d-lg-none d-block">Dashboard</a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse w-auto me-auto" id="navigation">
              <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                    Dashboard
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ route('admin.profile') }}">
                    <i class="fa fa-user opacity-6 text-dark me-1"></i>
                    Profile
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ route('admin.sign-up') }}">
                    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                    Sign Up
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ route('admin.sign-in') }}">
                    <i class="fas fa-key opacity-6 text-dark me-1"></i>
                    Sign In
                  </a>
                </li>
              </ul>
              <ul class="navbar-nav d-lg-block d-none">
                <li class="nav-item">
                  <a href="{{ route('admin.dashboard') }}" class="btn btn-sm mb-0 me-1 bg-gradient-dark">View Dashboard</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
  
  <header>
    <div class="page-header min-vh-75">
      <div class="container">
        <div class="row">
          <div class="col-lg-5 mt-8 position-relative z-index-1">
            <div class="d-flex align-items-center">
              <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18.5714 7.07812C18.5714 7.24182 18.4747 7.42039 18.2813 7.61384L14.2299 11.5647L15.1897 17.1451C15.1972 17.1972 15.2009 17.2716 15.2009 17.3683C15.2009 17.5246 15.16 17.6548 15.0781 17.7589C15.0037 17.8705 14.8921 17.9263 14.7433 17.9263C14.6019 17.9263 14.4531 17.8817 14.2969 17.7924L9.28571 15.1585L4.27455 17.7924C4.11086 17.8817 3.96205 17.9263 3.82813 17.9263C3.67188 17.9263 3.55283 17.8705 3.47098 17.7589C3.39658 17.6548 3.35938 17.5246 3.35938 17.3683C3.35938 17.3237 3.36682 17.2493 3.3817 17.1451L4.34152 11.5647L0.279018 7.61384C0.093006 7.41295 0 7.23437 0 7.07812C0 6.80283 0.208333 6.6317 0.625 6.56473L6.22768 5.75L8.73884 0.671874C8.88021 0.366815 9.0625 0.214285 9.28571 0.214285C9.50893 0.214285 9.69122 0.366815 9.83259 0.671874L12.3438 5.75L17.9464 6.56473C18.3631 6.6317 18.5714 6.80283 18.5714 7.07812Z" fill="#EC407A" />
              </svg>
              <p class="text-primary font-weight-bold mb-0 ms-2">
                10,000+ Github Stars
              </p>
            </div>
            <h1>Material Dashboard</h1>
            <p class="text-lg mt-3">
              Creative Tim's Most Popular Product. Download it now for free to create professional-looking and functional admin dashboards for your web projects.
            </p>
            <div class="d-flex align-items-center mb-4">
              <div class="avatar-group">
                <a href="javascript:;" class="avatar avatar-sm rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Martin Doe">
                  <img alt="Image placeholder" src="{{ asset('assets/back/img/bruce-mars.jpg') }}">
                </a>
                <a href="javascript:;" class="avatar avatar-sm rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Team Member">
                  <img alt="Image placeholder" src="{{ asset('assets/back/img/team-5.jpg') }}">
                </a>
                <a href="javascript:;" class="avatar avatar-sm rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Team Member">
                  <img alt="Image placeholder" src="{{ asset('assets/back/img/team-3.jpg') }}">
                </a>
              </div>
              <p class="mb-0 ms-2">Join 1,300,000+ Users</p>
            </div>
            <div class="d-block d-md-flex">
              <a href="{{ route('admin.dashboard') }}" class="btn w-100 w-md-auto btn-primary">View Dashboard</a>
              <button class="btn bg-white ms-md-3 text-lowercase w-100 w-md-auto">
                npm i material-dashboard
                <span class="ms-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy code">
                  <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.8672 2.55469C12.1133 2.82812 12.25 3.12891 12.25 3.48438V10.8125C12.25 11.1953 12.1133 11.4961 11.8672 11.7422C11.5938 12.0156 11.293 12.125 10.9375 12.125H8.75V13.4375C8.75 13.8203 8.61328 14.1211 8.36719 14.3672C8.09375 14.6406 7.79297 14.75 7.4375 14.75H1.3125C0.929688 14.75 0.628906 14.6406 0.382812 14.3672C0.109375 14.1211 0 13.8203 0 13.4375V4.6875C0 4.33203 0.109375 4.03125 0.382812 3.75781C0.628906 3.51172 0.929688 3.375 1.3125 3.375H3.5V2.0625C3.5 1.70703 3.60938 1.40625 3.88281 1.13281C4.12891 0.886719 4.42969 0.75 4.8125 0.75H9.51562C9.87109 0.75 10.1719 0.886719 10.4453 1.13281L11.8672 2.55469ZM7.27344 13.4375C7.30078 13.4375 7.32812 13.4375 7.38281 13.3828C7.41016 13.3555 7.4375 13.3281 7.4375 13.2734V12.125H4.8125C4.42969 12.125 4.12891 12.0156 3.88281 11.7422C3.60938 11.4961 3.5 11.1953 3.5 10.8125V4.6875H1.47656C1.42188 4.6875 1.39453 4.71484 1.36719 4.74219C1.3125 4.79688 1.3125 4.82422 1.3125 4.85156V13.2734C1.3125 13.3281 1.3125 13.3555 1.36719 13.3828C1.39453 13.4375 1.42188 13.4375 1.47656 13.4375H7.27344ZM10.7734 10.8125C10.8008 10.8125 10.8281 10.8125 10.8828 10.7578C10.9102 10.7305 10.9375 10.7031 10.9375 10.6484V5.125H8.53125C8.33984 5.125 8.17578 5.07031 8.06641 4.93359C7.92969 4.82422 7.875 4.66016 7.875 4.46875V2.0625H4.97656C4.92188 2.0625 4.89453 2.08984 4.86719 2.11719C4.8125 2.17188 4.8125 2.19922 4.8125 2.22656V10.6484C4.8125 10.7031 4.8125 10.7305 4.86719 10.7578C4.89453 10.8125 4.92188 10.8125 4.97656 10.8125H10.7734ZM10.9375 3.8125V3.53906C10.9375 3.51172 10.9102 3.48438 10.8828 3.42969L9.57031 2.11719C9.51562 2.08984 9.48828 2.0625 9.46094 2.0625H9.1875V3.8125H10.9375Z" fill="#64748B" />
                  </svg>
                </span>
              </button>
            </div>
          </div>
          <svg class="position-absolute top-0" width="1231" height="1421" viewBox="0 0 1231 1421" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g opacity="0.12786" filter="url(#filter0_f_31_15)">
              <ellipse cx="811.5" cy="602.5" rx="675.5" ry="682.5" fill="url(#paint0_linear_31_15)" />
            </g>
            <defs>
              <filter id="filter0_f_31_15" x="0.085907" y="-215.914" width="1622.83" height="1636.83" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                <feGaussianBlur stdDeviation="67.957" result="effect1_foregroundBlur_31_15" />
              </filter>
              <linearGradient id="paint0_linear_31_15" x1="804.405" y1="-136.203" x2="160.281" y2="643.776" gradientUnits="userSpaceOnUse">
                <stop stop-color="#7B4CFF" />
                <stop offset="0.469471" stop-color="#EC407A" />
                <stop offset="1" stop-color="white" />
              </linearGradient>
            </defs>
          </svg>
          <img class="position-absolute top-0 mt-n7 me-n12 end-0 w-70 z-index-3" src="{{ asset('assets/back/img/perspective.png') }}" alt="header-image">
        </div>
      </div>
    </div>
  </header>
  
  <div class="card card-body mx-3 mx-md-4 mt-n6 z-index-1 position-relative">
    <!-- Stats Section -->
    <section class="py-7" id="stats">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mx-auto text-center pb-6">
            <h2>Trusted by over <span class="text-gradient text-primary">1,679,477+ web developers</span></h2>
            <p class="lead">Many Fortune 500 companies, startups, universities and governmental institutions love Creative Tim's products.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-primary">70+</h1>
              <h5 class="mt-3">Coded Elements</h5>
              <p class="text-sm">From buttons, to complex navigation bars, we have everything covered.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-primary">15+</h1>
              <h5 class="mt-3">Design Blocks</h5>
              <p class="text-sm">Mix the sections, change the colors and unleash your creativity.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-primary">4</h1>
              <h5 class="mt-3">Pages</h5>
              <p class="text-sm">Save 3-4 weeks of work when you use our pre-made pages for your website.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="p-3 text-center">
              <h1 class="text-gradient text-primary">24/7</h1>
              <h5 class="mt-3">Support</h5>
              <p class="text-sm">Join our Discord community and get help from our team and community.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Technologies Section -->
    <section class="py-5" id="technologies">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mx-auto text-center pb-6">
            <h2>Built with the latest technologies</h2>
            <p class="lead">We're constantly trying to express ourselves and actualize our dreams. If you have the opportunity to play.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 mb-4 text-center">
            <div class="p-3">
              <img class="w-10 mb-4" src="{{ asset('assets/back/img/logos/gray-logos/logo-coinbase.svg') }}" alt="coinbase">
              <h5>Bootstrap 5</h5>
              <p>Built on top of Bootstrap 5, Material Dashboard is mobile first and responsive.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4 text-center">
            <div class="p-3">
              <img class="w-10 mb-4" src="{{ asset('assets/back/img/logos/gray-logos/logo-nasa.svg') }}" alt="nasa">
              <h5>Sass Files</h5>
              <p>All Sass files are included. You can change any color or size that you want.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4 text-center">
            <div class="p-3">
              <img class="w-10 mb-4" src="{{ asset('assets/back/img/logos/gray-logos/logo-netflix.svg') }}" alt="netflix">
              <h5>Premium Support</h5>
              <p>Premium support included. Join our Discord community for help and guidance.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4 text-center">
            <div class="p-3">
              <img class="w-10 mb-4" src="{{ asset('assets/back/img/logos/gray-logos/logo-spotify.svg') }}" alt="spotify">
              <h5>Modular Design</h5>
              <p>Each component is built to be independent and reusable across projects.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mb-4 mx-auto text-center">
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Company
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              About Us
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Team
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Products
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Blog
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Pricing
            </a>
          </div>
          <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-dribbble"></span>
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-twitter"></span>
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-instagram"></span>
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-pinterest"></span>
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-github"></span>
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-8 mx-auto text-center mt-1">
            <p class="mb-0 text-secondary">
              Copyright © <script>document.write(new Date().getFullYear())</script> Material Dashboard by Creative Tim.
            </p>
          </div>
        </div>
      </div>
    </footer>
  </div>
  
  <!--   Core JS Files   -->
  <script src="{{ asset('assets/back/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/back/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/back/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/back/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="{{ asset('assets/back/js/material-dashboard.min.js?v=3.2.0') }}"></script>
</body>

</html>
