@extends('layouts.back')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.collector-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.collector-item:hover .fa-crown {
  animation: crownBounce 0.6s ease-in-out;
}

@keyframes crownBounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}
</style>
@endpush

@section('content')
<!-- Header -->
<div class="row">
  <div class="col-12">
    <div class="mb-4">
      <h3 class="mb-0 h4 font-weight-bolder">Waste2Product Analytics Dashboard</h3>
      <p class="mb-0 text-sm">
        <i class="material-symbols-rounded text-sm">insights</i>
        Real-time overview of your waste collection and recycling ecosystem
      </p>
    </div>
  </div>
</div>

<!-- Primary Stats Cards with Growth -->
<div class="row">
  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-symbols-rounded opacity-10">group</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Total Users</p>
          <h4 class="mb-0">{{ number_format($totalUsers) }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0">
          @if($userGrowth > 0)
            <span class="text-success text-sm font-weight-bolder">+{{ $userGrowth }}% </span>
          @elseif($userGrowth < 0)
            <span class="text-danger text-sm font-weight-bolder">{{ $userGrowth }}% </span>
          @else
            <span class="text-secondary text-sm font-weight-bolder">{{ $userGrowth }}% </span>
          @endif
          than last month
        </p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-symbols-rounded opacity-10">delete</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Waste Requests</p>
          <h4 class="mb-0">{{ number_format($totalWasteRequests) }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0">
          @if($requestGrowth > 0)
            <span class="text-success text-sm font-weight-bolder">+{{ $requestGrowth }}% </span>
          @elseif($requestGrowth < 0)
            <span class="text-danger text-sm font-weight-bolder">{{ $requestGrowth }}% </span>
          @else
            <span class="text-secondary text-sm font-weight-bolder">{{ $requestGrowth }}% </span>
          @endif
          than last month
        </p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-symbols-rounded opacity-10">inventory_2</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Recycled Products</p>
          <h4 class="mb-0">{{ number_format($totalProducts) }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0">
          @if($productGrowth > 0)
            <span class="text-success text-sm font-weight-bolder">+{{ $productGrowth }}% </span>
          @elseif($productGrowth < 0)
            <span class="text-danger text-sm font-weight-bolder">{{ $productGrowth }}% </span>
          @else
            <span class="text-secondary text-sm font-weight-bolder">{{ $productGrowth }}% </span>
          @endif
          than last month
        </p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-symbols-rounded opacity-10">local_shipping</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Active Collectors</p>
          <h4 class="mb-0">{{ number_format($totalCollectors) }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0">
          <span class="text-success text-sm font-weight-bolder"><i class="material-symbols-rounded text-sm">verified</i></span> Verified & ready
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Secondary Stats Cards -->
<div class="row mt-2">
  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-symbols-rounded opacity-10">shopping_cart</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Total Orders</p>
          <h4 class="mb-0">{{ number_format($totalOrders) }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0">
          @if($orderGrowth > 0)
            <span class="text-success text-sm font-weight-bolder">+{{ $orderGrowth }}% </span>
          @elseif($orderGrowth < 0)
            <span class="text-danger text-sm font-weight-bolder">{{ $orderGrowth }}% </span>
          @else
            <span class="text-secondary text-sm font-weight-bolder">{{ $orderGrowth }}% </span>
          @endif
          than last month
        </p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-secondary shadow-secondary text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-symbols-rounded opacity-10">article</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Community Posts</p>
          <h4 class="mb-0">{{ number_format($totalPosts) }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0">
          <span class="text-info text-sm"><i class="material-symbols-rounded text-sm">forum</i></span> User engagement
        </p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-symbols-rounded opacity-10">person_check</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Active Users</p>
          <h4 class="mb-0">{{ number_format($activeUsers) }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0">
          <span class="text-success text-sm font-weight-bolder">+{{ $newUsersThisMonth }} </span>
          new this month
        </p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-symbols-rounded opacity-10">paid</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Combined Revenue</p>
          <h4 class="mb-0">${{ number_format($combinedRevenue, 2) }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0">
          <span class="text-success text-sm">Products: ${{ number_format($totalRevenue, 2) }}</span> • 
          <span class="text-info text-sm">Orders: ${{ number_format($orderRevenue, 2) }}</span>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Impact Metrics -->
<div class="row">
  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card bg-gradient-success">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-white text-sm mb-0 text-capitalize font-weight-bold opacity-7">Total Waste Collected</p>
              <h5 class="text-white font-weight-bolder mb-0">
                {{ number_format($totalWasteCollected, 2) }} kg
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
              <i class="material-symbols-rounded text-lg text-success opacity-10">scale</i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card bg-gradient-info">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-white text-sm mb-0 text-capitalize font-weight-bold opacity-7">This Month</p>
              <h5 class="text-white font-weight-bolder mb-0">
                {{ number_format($thisMonthCollected, 2) }} kg
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
              <i class="material-symbols-rounded text-lg text-info opacity-10">trending_up</i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card bg-gradient-primary">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-white text-sm mb-0 text-capitalize font-weight-bold opacity-7">Total Revenue</p>
              <h5 class="text-white font-weight-bolder mb-0">
                ${{ number_format($totalRevenue, 2) }}
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
              <i class="material-symbols-rounded text-lg text-primary opacity-10">attach_money</i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card bg-gradient-warning">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-white text-sm mb-0 text-capitalize font-weight-bold opacity-7">Collection Rate</p>
              <h5 class="text-white font-weight-bolder mb-0">
                {{ $collectionRate }}%
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
              <i class="material-symbols-rounded text-lg text-warning opacity-10">percent</i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
  <div class="col-lg-4 mb-4">
    <div class="card z-index-2">
      <div class="card-header p-3 pb-0">
        <h6 class="mb-0">Daily Requests (Last 7 Days)</h6>
        <p class="text-sm mb-0">
          <i class="fa fa-arrow-up text-success"></i>
          <span class="font-weight-bold">Real-time tracking</span>
        </p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="chart-bars" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 mb-4">
    <div class="card z-index-2">
      <div class="card-header p-3 pb-0">
        <h6 class="mb-0">Monthly Trend</h6>
        <p class="text-sm mb-0">
          <span class="font-weight-bold">Last 6 months</span>
        </p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 mb-4">
    <div class="card z-index-2">
      <div class="card-header p-3 pb-0">
        <h6 class="mb-0">Waste Type Distribution</h6>
        <p class="text-sm mb-0">
          <span class="font-weight-bold">Breakdown by category</span>
        </p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="chart-doughnut" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Additional Charts Row -->
<div class="row mt-2">
  <div class="col-lg-6 mb-4">
    <div class="card z-index-2">
      <div class="card-header p-3 pb-0">
        <h6 class="mb-0">Product Categories</h6>
        <p class="text-sm mb-0">Distribution by type</p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="chart-product-categories" class="chart-canvas" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6 mb-4">
    <div class="card z-index-2">
      <div class="card-header p-3 pb-0">
        <h6 class="mb-0">User Role Distribution</h6>
        <p class="text-sm mb-0">By user type</p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="chart-user-roles" class="chart-canvas" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Content Row -->
<div class="row">
  <!-- Recent Requests Table -->
  <div class="col-lg-8 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <div class="row">
          <div class="col-lg-6">
            <h6>Recent Waste Requests</h6>
            <p class="text-sm mb-0">
              <span class="font-weight-bold">{{ $collectedRequests }}</span> collected, 
              <span class="font-weight-bold">{{ $pendingRequests }}</span> pending
            </p>
          </div>
          <div class="col-lg-6 text-end">
            <a href="{{ route('admin.waste-requests') }}" class="btn btn-sm btn-outline-primary mb-0">
              <i class="material-symbols-rounded text-sm">visibility</i> View All
            </a>
          </div>
        </div>
      </div>
      <div class="card-body px-0 pb-2">
        <div class="table-responsive">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Details</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentRequests as $request)
              <tr>
                <td>
                  <div class="d-flex px-2 py-1">
                    <div>
                      <img src="{{ $request->customer->profile_picture_url ?? asset('assets/back/img/team-2.jpg') }}" class="avatar avatar-sm me-3 border-radius-lg" alt="customer">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm">{{ $request->customer->name ?? 'Unknown' }}</h6>
                      <p class="text-xs text-secondary mb-0">
                        <i class="material-symbols-rounded text-xs">location_on</i>{{ $request->state ?? 'N/A' }}
                      </p>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="d-flex flex-column">
                    <h6 class="mb-0 text-xs">{{ ucfirst($request->waste_type) }}</h6>
                    <p class="text-xs text-secondary mb-0">{{ $request->created_at->diffForHumans() }}</p>
                  </div>
                </td>
                <td class="align-middle text-center text-sm">
                  <span class="badge badge-sm bg-gradient-info">{{ number_format($request->quantity, 2) }} kg</span>
                </td>
                <td class="align-middle text-center">
                  @php
                    $statusBadges = [
                      'pending' => 'bg-gradient-warning',
                      'accepted' => 'bg-gradient-info',
                      'collected' => 'bg-gradient-success',
                      'cancelled' => 'bg-gradient-danger'
                    ];
                  @endphp
                  <span class="badge badge-sm {{ $statusBadges[$request->status] ?? 'bg-gradient-secondary' }}">
                    {{ ucfirst($request->status) }}
                  </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center py-4">
                  <p class="text-sm text-secondary mb-0">No waste requests yet</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column - Top Performers & Activity -->
  <div class="col-lg-4">
    <!-- Top Collectors -->
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="mb-0">
              <i class="material-symbols-rounded text-warning" style="vertical-align: middle;">emoji_events</i>
              Top Collectors
            </h6>
            <p class="text-xs mb-0 text-secondary">Leaderboard rankings</p>
          </div>
          <div>
            <span class="badge badge-sm bg-gradient-success">Live</span>
          </div>
        </div>
      </div>
      <div class="card-body p-3">
        @forelse($topCollectors as $index => $collector)
        <div class="collector-item mb-3 p-3" style="background: linear-gradient(135deg, {{ $index == 0 ? '#fff9e6' : ($index == 1 ? '#f0f0f0' : ($index == 2 ? '#fff5e6' : '#ffffff')) }} 0%, #ffffff 100%); border-radius: 12px; border: 2px solid {{ $index == 0 ? '#fbbf24' : ($index == 1 ? '#c0c0c0' : ($index == 2 ? '#cd7f32' : '#e5e7eb')) }}; transition: all 0.3s ease; cursor: pointer;">
          <div class="d-flex align-items-center justify-content-between">
            <!-- Rank Badge & Info -->
            <div class="d-flex align-items-center flex-grow-1">
              <!-- Rank Badge -->
              <div class="me-3" style="min-width: 50px; text-align: center;">
                @if($index == 0)
                  <div style="position: relative; display: inline-block;">
                    <i class="fas fa-crown" style="font-size: 2rem; color: #fbbf24; filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.5));"></i>
                    <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; color: #fff; font-size: 0.7rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">1</span>
                  </div>
                @elseif($index == 1)
                  <div style="position: relative; display: inline-block;">
                    <i class="fas fa-medal" style="font-size: 1.8rem; color: #c0c0c0; filter: drop-shadow(0 2px 4px rgba(192, 192, 192, 0.5));"></i>
                    <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; color: #666; font-size: 0.65rem;">2</span>
                  </div>
                @elseif($index == 2)
                  <div style="position: relative; display: inline-block;">
                    <i class="fas fa-medal" style="font-size: 1.8rem; color: #cd7f32; filter: drop-shadow(0 2px 4px rgba(205, 127, 50, 0.5));"></i>
                    <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; color: #fff; font-size: 0.65rem;">3</span>
                  </div>
                @else
                  <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #6b7280; font-size: 1rem; border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    {{ $index + 1 }}
                  </div>
                @endif
              </div>

              <!-- Collector Info -->
              <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-1">
                  <h6 class="mb-0 text-sm font-weight-bold" style="color: #2c3e50;">
                    {{ $collector->user->name ?? 'Unknown' }}
                  </h6>
                  @if($collector->verification_status === 'verified')
                    <i class="material-symbols-rounded text-success ms-2" style="font-size: 1rem;" title="Verified">verified</i>
                  @endif
                </div>
                
                <!-- Stats Row -->
                <div class="d-flex align-items-center gap-3">
                  <!-- Collections -->
                  <div class="d-flex align-items-center">
                    <i class="material-symbols-rounded text-success me-1" style="font-size: 0.9rem;">inventory_2</i>
                    <span class="text-xs font-weight-bold text-success">{{ $collector->total_collections ?? 0 }}</span>
                  </div>
                  
                  <!-- Star Rating -->
                  <div class="d-flex align-items-center">
                    @php
                      $rating = $collector->rating ?? 0;
                      $fullStars = floor($rating);
                      $hasHalfStar = ($rating - $fullStars) >= 0.5;
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                      @if($i <= $fullStars)
                        <i class="fas fa-star" style="color: #fbbf24; font-size: 0.7rem;"></i>
                      @elseif($i == $fullStars + 1 && $hasHalfStar)
                        <i class="fas fa-star-half-alt" style="color: #fbbf24; font-size: 0.7rem;"></i>
                      @else
                        <i class="fas fa-star" style="color: #e5e7eb; font-size: 0.7rem;"></i>
                      @endif
                    @endfor
                    <span class="text-xs font-weight-bold ms-1" style="color: #fbbf24;">{{ number_format($rating, 1) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Collections Badge -->
            <div class="ms-auto">
              <div class="text-center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 8px 12px; border-radius: 10px; min-width: 65px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);">
                <div style="font-size: 1.1rem; font-weight: 800; line-height: 1;">
                  {{ $collector->total_collections ?? 0 }}
                </div>
                <div style="font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">
                  Collected
                </div>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="text-center py-4">
          <i class="material-symbols-rounded text-secondary" style="font-size: 3rem; opacity: 0.3;">person_off</i>
          <p class="text-sm text-secondary mb-0 mt-2">No collectors yet</p>
        </div>
        @endforelse
      </div>
    </div>

    <!-- Top Governorates -->
    <div class="card">
      <div class="card-header pb-0">
        <h6>Top Governorates <i class="material-symbols-rounded text-sm text-success">location_city</i></h6>
        <p class="text-sm mb-0">By waste volume</p>
      </div>
      <div class="card-body p-3">
        @forelse($topStates as $state)
        <div class="d-flex mb-3">
          <div class="flex-grow-1">
            <h6 class="text-sm mb-0">{{ $state->state }}</h6>
            <p class="text-xs mb-0 text-secondary">
              {{ $state->total }} requests • {{ number_format($state->total_weight, 2) }} kg
            </p>
          </div>
          <div class="ms-auto">
            <div class="badge badge-sm bg-gradient-success">
              {{ round(($state->total / $totalWasteRequests) * 100, 1) }}%
            </div>
          </div>
        </div>
        @empty
        <p class="text-sm text-secondary mb-0">No data available</p>
        @endforelse
      </div>
    </div>

    <!-- Top Products -->
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Top Products <i class="material-symbols-rounded text-sm text-primary">workspace_premium</i></h6>
        <p class="text-sm mb-0">Highest value items</p>
      </div>
      <div class="card-body p-3">
        <ul class="list-group">
          @forelse($topProducts as $index => $product)
          <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
            <div class="d-flex align-items-center">
              <div class="icon icon-shape icon-xs me-2 bg-gradient-{{ ['success', 'info', 'warning', 'primary', 'dark'][$index % 5] }} shadow text-center border-radius-sm">
                <i class="material-symbols-rounded opacity-10 text-xs text-white">inventory_2</i>
              </div>
              <div class="d-flex flex-column">
                <h6 class="mb-0 text-dark text-xs">{{ $product->name }}</h6>
                <span class="text-xxs text-secondary">{{ ucfirst($product->category) }}</span>
              </div>
            </div>
            <div class="d-flex align-items-center text-success text-xs font-weight-bold">
              ${{ number_format($product->price, 2) }}
            </div>
          </li>
          @empty
          <li class="list-group-item border-0 ps-0 text-sm">
            <span class="text-secondary">No products sold yet</span>
          </li>
          @endforelse
        </ul>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Recent Orders <i class="material-symbols-rounded text-sm text-dark">shopping_bag</i></h6>
        <p class="text-sm mb-0">Latest transactions</p>
      </div>
      <div class="card-body p-3">
        @forelse($recentOrders as $order)
        <div class="d-flex mb-3 pb-2 border-bottom">
          <div class="flex-grow-1">
            <h6 class="text-sm mb-0">{{ $order->buyer->name ?? 'Guest' }}</h6>
            <p class="text-xs mb-0 text-secondary">
              Order #{{ $order->id }} • {{ $order->created_at->diffForHumans() }}
            </p>
          </div>
          <div class="ms-auto text-end">
            <h6 class="text-sm mb-0 text-success">${{ number_format($order->total_price, 2) }}</h6>
            <span class="badge badge-xs bg-gradient-{{ 
              $order->status === 'completed' ? 'success' : 
              ($order->status === 'processing' ? 'info' : 'warning') 
            }}">
              {{ ucfirst($order->status) }}
            </span>
          </div>
        </div>
        @empty
        <p class="text-sm text-secondary mb-0">No orders yet</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<!-- Status Overview -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Quick Statistics</h6>
      </div>
      <div class="card-body p-3">
        <div class="row">
          <div class="col-lg-3 col-sm-6">
            <div class="border-radius-md bg-gradient-dark p-3 mb-3">
              <h6 class="text-white mb-0">Request Status</h6>
              <hr class="horizontal light my-2">
              <p class="text-white text-sm mb-1">
                <span class="badge badge-warning">{{ $pendingRequests }}</span> Pending
              </p>
              <p class="text-white text-sm mb-1">
                <span class="badge badge-info">{{ $acceptedRequests }}</span> Accepted
              </p>
              <p class="text-white text-sm mb-0">
                <span class="badge badge-success">{{ $collectedRequests }}</span> Collected
              </p>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="border-radius-md bg-gradient-dark p-3 mb-3">
              <h6 class="text-white mb-0">Product Status</h6>
              <hr class="horizontal light my-2">
              <p class="text-white text-sm mb-1">
                <span class="badge badge-success">{{ $availableProducts }}</span> Available
              </p>
              <p class="text-white text-sm mb-1">
                <span class="badge badge-info">{{ $soldProducts }}</span> Sold
              </p>
              <p class="text-white text-sm mb-0">
                <span class="badge badge-primary">{{ $donatedProducts }}</span> Donated
              </p>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="border-radius-md bg-gradient-dark p-3 mb-3">
              <h6 class="text-white mb-0">Order Status</h6>
              <hr class="horizontal light my-2">
              <p class="text-white text-sm mb-1">
                <span class="badge badge-warning">{{ $pendingOrders }}</span> Pending
              </p>
              <p class="text-white text-sm mb-1">
                <span class="badge badge-info">{{ $processingOrders }}</span> Processing
              </p>
              <p class="text-white text-sm mb-0">
                <span class="badge badge-success">{{ $completedOrders }}</span> Completed
              </p>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="border-radius-md bg-gradient-success p-3 mb-3">
              <h6 class="text-white mb-0">Performance</h6>
              <hr class="horizontal light my-2">
              <p class="text-white text-sm mb-1">
                Collection Rate: <strong>{{ $collectionRate }}%</strong>
              </p>
              <p class="text-white text-sm mb-1">
                Product Sale Rate: <strong>{{ $productSaleRate }}%</strong>
              </p>
              <p class="text-white text-sm mb-0">
                This Month: <strong>{{ number_format($thisMonthCollected) }} kg</strong>
              </p>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="border-radius-md bg-gradient-primary p-3 mb-3">
              <h6 class="text-white mb-0">Quick Actions</h6>
              <hr class="horizontal light my-2">
              <a href="{{ route('admin.waste-requests') }}" class="text-white text-sm d-block mb-1">
                <i class="material-symbols-rounded text-xs">delete</i> Manage Requests
              </a>
              <a href="{{ route('admin.collectors') }}" class="text-white text-sm d-block mb-1">
                <i class="material-symbols-rounded text-xs">local_shipping</i> Manage Collectors
              </a>
              <a href="{{ route('admin.products.index') }}" class="text-white text-sm d-block mb-1">
                <i class="material-symbols-rounded text-xs">inventory_2</i> Manage Products
              </a>
              <a href="{{ route('admin.orders.index') }}" class="text-white text-sm d-block mb-1">
                <i class="material-symbols-rounded text-xs">shopping_cart</i> Manage Orders
              </a>
              <a href="{{ route('admin.users') }}" class="text-white text-sm d-block mb-0">
                <i class="material-symbols-rounded text-xs">people</i> Manage Users
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/back/js/plugins/chartjs.min.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Chart 1 - Bar Chart (Daily Requests)
    var ctx1 = document.getElementById("chart-bars");
    if (ctx1) {
      var requestsData = @json($requestsPerDay);
      var daysLabels = @json($daysLabels);
      
      new Chart(ctx1, {
        type: "bar",
        data: {
          labels: daysLabels,
          datasets: [{
            label: "Requests",
            tension: 0.4,
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            backgroundColor: "rgba(52, 71, 103, 0.8)",
            data: requestsData,
            maxBarThickness: 6
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: '#c1c4ce5c'
              },
              ticks: {
                suggestedMin: 0,
                suggestedMax: Math.max(...requestsData) + 2,
                beginAtZero: true,
                padding: 10,
                font: { size: 14, weight: 300, family: "Roboto" },
                color: "#9ca2b7"
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: '#c1c4ce5c'
              },
              ticks: {
                display: true,
                color: '#9ca2b7',
                padding: 10,
                font: { size: 14, weight: 300, family: "Roboto" }
              }
            },
          },
        },
      });
    }

    // Chart 2 - Line Chart (Monthly Trend)
    var ctx2 = document.getElementById("chart-line");
    if (ctx2) {
      var monthlyData = @json($monthlyTrend);
      var monthLabels = @json($monthLabels);
      
      new Chart(ctx2, {
        type: "line",
        data: {
          labels: monthLabels,
          datasets: [{
            label: "Requests",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: "rgba(52, 71, 103, 1)",
            pointBorderColor: "transparent",
            borderColor: "rgba(52, 71, 103, 1)",
            backgroundColor: "transparent",
            fill: true,
            data: monthlyData,
            maxBarThickness: 6
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: '#c1c4ce5c'
              },
              ticks: {
                display: true,
                color: '#9ca2b7',
                padding: 10,
                font: { size: 14, weight: 300, family: "Roboto" }
              }
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false
              },
              ticks: {
                display: true,
                color: '#9ca2b7',
                padding: 10,
                font: { size: 14, weight: 300, family: "Roboto" }
              }
            },
          },
        },
      });
    }

    // Chart 3 - Doughnut Chart (Waste Type Distribution)
    var ctx3 = document.getElementById("chart-doughnut");
    if (ctx3) {
      var wasteTypes = @json($wasteTypeBreakdown);
      var labels = wasteTypes.map(item => item.waste_type.charAt(0).toUpperCase() + item.waste_type.slice(1));
      var data = wasteTypes.map(item => item.count);
      
      new Chart(ctx3, {
        type: "doughnut",
        data: {
          labels: labels,
          datasets: [{
            label: "Waste Type",
            data: data,
            backgroundColor: [
              '#344767',
              '#17ad37',
              '#3a86ff',
              '#fb8500',
              '#e63946',
              '#6a4c93'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: 'bottom',
              labels: {
                padding: 10,
                font: { size: 12, family: "Roboto" }
              }
            }
          }
        }
      });
    }

    // Chart 4 - Product Categories Bar Chart
    var ctx4 = document.getElementById("chart-product-categories");
    if (ctx4) {
      var productCategories = @json($productsByCategory);
      var categoryLabels = productCategories.map(item => item.category.charAt(0).toUpperCase() + item.category.slice(1));
      var categoryData = productCategories.map(item => item.count);
      
      new Chart(ctx4, {
        type: "bar",
        data: {
          labels: categoryLabels,
          datasets: [{
            label: "Products",
            data: categoryData,
            backgroundColor: ['#17ad37', '#3a86ff', '#fb8500', '#e63946', '#6a4c93', '#344767'],
            borderWidth: 0,
            borderRadius: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                borderDash: [5, 5],
                color: '#c1c4ce5c'
              },
              ticks: {
                beginAtZero: true,
                color: "#9ca2b7",
                font: { size: 12, family: "Roboto" }
              }
            },
            x: {
              grid: { display: false },
              ticks: {
                color: '#9ca2b7',
                font: { size: 12, family: "Roboto" }
              }
            }
          }
        }
      });
    }

    // Chart 5 - User Roles Pie Chart
    var ctx5 = document.getElementById("chart-user-roles");
    if (ctx5) {
      var userRoles = @json($usersByRole);
      var roleLabels = userRoles.map(item => item.role.charAt(0).toUpperCase() + item.role.slice(1));
      var roleData = userRoles.map(item => item.count);
      
      new Chart(ctx5, {
        type: "pie",
        data: {
          labels: roleLabels,
          datasets: [{
            data: roleData,
            backgroundColor: ['#344767', '#17ad37', '#3a86ff', '#fb8500'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: 'bottom',
              labels: {
                padding: 10,
                font: { size: 12, family: "Roboto" }
              }
            }
          }
        }
      });
    }
  });
</script>
@endpush
