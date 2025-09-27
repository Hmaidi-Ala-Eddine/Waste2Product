<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/back/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/back/img/favicon.png') }}">
  <title>RTL - Waste2Product Admin</title>
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

<body class="g-sidenav-show rtl bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-end me-2 rotate-caret bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute start-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('assets/back/img/recycle.png') }}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="me-1 text-sm text-dark">Waste2Product</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse px-0 w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="{{ route('admin.dashboard') }}">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text me-1">لوحة القيادة</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="{{ route('admin.rtl') }}">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text me-1">RTL</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0">
      <div class="mx-3">
        <a class="btn btn-outline-dark mt-4 w-100" href="#" type="button">التوثيق</a>
        <a class="btn bg-gradient-dark w-100" href="#" type="button">الترقية إلى برو</a>
      </div>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">الصفحات</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">RTL</li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">اكتب هنا...</label>
              <input type="text" class="form-control">
            </div>
          </div>
          <ul class="navbar-nav d-flex align-items-center justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank" href="{{ url('/') }}">منشئ الموقع</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid py-2">
<div class="row">
  <div class="ms-3">
    <h3 class="mb-0 h4 font-weight-bolder">لوحة القيادة</h3>
    <p class="mb-4">
      تحقق من المبيعات والقيمة ومعدل الارتداد حسب البلد.
    </p>
  </div>
  <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between p-3 pt-2">
        <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
          <i class="material-symbols-rounded opacity-10">weekend</i>
        </div>
        <div class="text-start pt-1">
          <p class="text-sm mb-0 text-capitalize">أموال اليوم</p>
          <h4 class="mb-0">$53k</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0 text-start"><span class="text-success text-sm font-weight-bolder ms-1">+55% </span>من الأسبوع الماضي</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between p-3 pt-2">
        <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
          <i class="material-symbols-rounded opacity-10">leaderboard</i>
        </div>
        <div class="text-start pt-1">
          <p class="text-sm mb-0 text-capitalize">مستخدمو اليوم</p>
          <h4 class="mb-0">2,300</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0 text-start"><span class="text-success text-sm font-weight-bolder ms-1">+33% </span>من الأسبوع الماضي</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between p-3 pt-2">
        <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
          <i class="material-symbols-rounded opacity-10">store</i>
        </div>
        <div class="text-start pt-1">
          <p class="text-sm mb-0 text-capitalize">عملاء جدد</p>
          <h4 class="mb-0">
            <span class="text-danger text-sm font-weight-bolder ms-1">-2%</span>
            +3,462
          </h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0 text-start"><span class="text-success text-sm font-weight-bolder ms-1">+5% </span>من الشهر الماضي</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between p-3 pt-2">
        <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
          <i class="material-symbols-rounded opacity-10">weekend</i>
        </div>
        <div class="text-start pt-1">
          <p class="text-sm mb-0 text-capitalize">مبيعات</p>
          <h4 class="mb-0">$103,430</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0 text-start"><span class="text-success text-sm font-weight-bolder ms-1">+7% </span>مقارنة بيوم أمس</p>
      </div>
    </div>
  </div>
</div>
<div class="row mt-4">
  <div class="col-lg-4 col-md-6 mt-4 mb-4">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-0 ">مشاهدات الموقع</h6>
        <p class="text-sm ">آخر أداء للحملة</p>
        <div class="pe-2">
          <div class="chart">
            <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="d-flex ">
          <i class="material-symbols-rounded text-sm my-auto ms-1">schedule</i>
          <p class="mb-0 text-sm">لحملة أرسلت قبل يومين </p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-6 mt-4 mb-4">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-0 ">المبيعات اليومية</h6>
        <p class="text-sm ">(<span class="font-weight-bolder text-success">+15%</span>) زيادة في مبيعات اليوم</p>
        <div class="pe-2">
          <div class="chart">
            <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="d-flex ">
          <i class="material-symbols-rounded text-sm my-auto ms-1">schedule</i>
          <p class="mb-0 text-sm">تم التحديث منذ 4 دقائق</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 mt-4 mb-3">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-0 ">المهام المكتملة</h6>
        <p class="text-sm ">آخر أداء للحملة</p>
        <div class="pe-2">
          <div class="chart">
            <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="d-flex ">
          <i class="material-symbols-rounded text-sm my-auto ms-1">schedule</i>
          <p class="mb-0 text-sm">تم التحديث للتو</p>
        </div>
      </div>
    </div>
  </div>
</div>
      @include('back.partials.footer')
    </div>
  </main>
  @include('back.partials.configurator')
  <!--   Core JS Files   -->
  <script src="{{ asset('assets/back/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/back/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/back/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/back/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/back/js/plugins/chartjs.min.js') }}"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");
    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["M", "T", "W", "T", "F", "S", "S"],
        datasets: [{
          label: "Views",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#43A047",
          data: [50, 45, 22, 28, 50, 60, 76],
          barThickness: 'flex'
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            grid: { drawBorder: false, display: true, drawOnChartArea: true, drawTicks: false, borderDash: [5, 5], color: '#e5e5e5' },
            ticks: { suggestedMin: 0, suggestedMax: 500, beginAtZero: true, padding: 10, font: { size: 14, lineHeight: 2 }, color: "#737373" }
          },
          x: {
            grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false, borderDash: [5, 5] },
            ticks: { display: true, color: '#737373', padding: 10, font: { size: 14, lineHeight: 2 } }
          }
        }
      }
    });

    var ctx2 = document.getElementById("chart-line").getContext("2d");
    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
        datasets: [{
          label: "Sales", tension: 0, borderWidth: 2, pointRadius: 3, pointBackgroundColor: "#43A047",
          pointBorderColor: "transparent", borderColor: "#43A047", backgroundColor: "transparent",
          fill: true, data: [120, 230, 130, 440, 250, 360, 270, 180, 90, 300, 310, 220], maxBarThickness: 6
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            grid: { drawBorder: false, display: true, drawOnChartArea: true, drawTicks: false, borderDash: [4, 4], color: '#e5e5e5' },
            ticks: { display: true, color: '#737373', padding: 10, font: { size: 12, lineHeight: 2 } }
          },
          x: {
            grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false, borderDash: [5, 5] },
            ticks: { display: true, color: '#737373', padding: 10, font: { size: 12, lineHeight: 2 } }
          }
        }
      }
    });

    var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");
    new Chart(ctx3, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Tasks", tension: 0, borderWidth: 2, pointRadius: 3, pointBackgroundColor: "#43A047",
          pointBorderColor: "transparent", borderColor: "#43A047", backgroundColor: "transparent",
          fill: true, data: [50, 40, 300, 220, 500, 250, 400, 230, 500], maxBarThickness: 6
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            grid: { drawBorder: false, display: true, drawOnChartArea: true, drawTicks: false, borderDash: [5, 5], color: '#e5e5e5' },
            ticks: { suggestedMin: 0, suggestedMax: 500, beginAtZero: true, padding: 10, font: { size: 14, lineHeight: 2 }, color: "#737373" }
          },
          x: {
            grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false, borderDash: [5, 5] },
            ticks: { display: true, color: '#737373', padding: 10, font: { size: 14, lineHeight: 2 } }
          }
        }
      }
    });
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="{{ asset('assets/back/js/material-dashboard.min.js?v=3.2.0') }}"></script>
</body>
</html>
