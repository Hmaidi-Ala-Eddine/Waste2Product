<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/back/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/back/img/favicon.png') }}">
  <title>@yield('title', 'Waste2Product Admin')</title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/back/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/back/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/back/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
  
  <!-- Custom CSS for dropdown logout button -->
  <style>
    .logout-btn {
      background: transparent !important;
      border: none !important;
      width: 100% !important;
      text-align: left !important;
      color: #67748e !important;
      font-family: inherit !important;
      cursor: pointer !important;
      font-size: inherit !important;
      line-height: inherit !important;
      display: block !important;
      padding: 0.5rem 1rem !important;
      clear: both !important;
      font-weight: 400 !important;
      text-decoration: none !important;
      white-space: nowrap !important;
      border-radius: 0.5rem !important;
      transition: all 0.15s ease-in !important;
    }
    
    .logout-btn:hover,
    .logout-btn:focus {
      color: #344767 !important;
      background-color: #f0f2f5 !important;
      text-decoration: none !important;
      outline: none !important;
      box-shadow: none !important;
      transform: translateY(-1px) !important;
    }
    
    .logout-btn:active {
      color: #344767 !important;
      background-color: #e3e6ea !important;
      transform: translateY(0px) !important;
    }
  </style>
  
  @stack('styles')
</head>

<body class="g-sidenav-show bg-gray-100">
  @include('back.partials.sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg d-flex flex-column">
    @include('back.partials.navbar')
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-2" role="alert">
            <strong>Error!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="container-fluid py-2 flex-grow-1">
      @yield('content')
    </div>
    
    <div class="container-fluid">
      @include('back.partials.footer')
    </div>
  </main>
  @include('back.partials.configurator')
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
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('assets/back/js/material-dashboard.min.js?v=3.2.0') }}"></script>
  
  @stack('scripts')
</body>

</html>
