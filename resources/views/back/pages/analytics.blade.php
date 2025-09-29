@extends('layouts.back')

@section('title', 'Analytics')
@section('page-title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Analytics Dashboard</h6>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm me-2" onclick="generateTodayStats()">
                                <i class="fas fa-chart-line me-1"></i>Today's Statistics
                            </button>
                            <button type="button" class="btn btn-info btn-sm" onclick="checkMissingAnalytics()">
                                <i class="fas fa-search me-1"></i>Check Missing Analytics
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mx-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="alert alert-info mx-4">
                            {{ session('info') }}
                        </div>
                    @endif
                    
                    <!-- Today's Summary -->
                    @if($todayAnalytics)
                    <div class="row mx-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">Today's Summary ({{ $todayAnalytics->date->format('F d, Y') }})</h6>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Waste Requests</p>
                                                <h5 class="font-weight-bolder mb-0">{{ number_format($todayAnalytics->total_waste_quantity, 2) }} kg</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Products Created</p>
                                                <h5 class="font-weight-bolder mb-0">{{ $todayAnalytics->total_products }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                                <i class="ni ni-box-2 text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders Made</p>
                                                <h5 class="font-weight-bolder mb-0">{{ $todayAnalytics->total_orders }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                                <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Income</p>
                                                <h5 class="font-weight-bolder mb-0">${{ number_format($todayAnalytics->total_income, 2) }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Charts Section -->
                    <div class="row mx-3">
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header pb-0">
                                    <h6>Waste Types Distribution (Monthly)</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="wasteTypesPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header pb-0">
                                    <h6>Daily Waste Requests Created (Monthly)</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="wasteQuantityBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header pb-0">
                                    <h6>Product Categories Distribution (Monthly)</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="productCategoriesPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header pb-0">
                                    <h6>Product Status Distribution (Monthly)</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="productStatusPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header pb-0">
                                    <h6>Product Conditions (Monthly)</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="productConditionsBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header pb-0">
                                    <h6>Order Status Distribution (Monthly)</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="orderStatusPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Daily Orders Created (Monthly)</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="incomeLineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Monthly Analytics Table -->
                    @if($monthlyAnalytics && $monthlyAnalytics->count() > 0)
                    <div class="table-responsive mx-3">
                        <h6 class="mb-3">Monthly Analytics Records</h6>
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Waste (kg)</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Products</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Orders</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Income</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyAnalytics as $analytics)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $analytics->date->format('M d, Y') }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($analytics->total_waste_quantity, 2) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $analytics->total_products }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $analytics->total_orders }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">${{ number_format($analytics->total_income, 2) }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <form method="POST" action="{{ route('admin.analytics.destroy', $analytics) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0" 
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="far fa-trash-alt me-2"></i>Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="mx-3">
                        <div class="alert alert-info">
                            <p class="mb-0">No analytics data available for this month. Click "Today's Statistics" to generate analytics for today.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from PHP
const chartData = @json($chartData ?? []);

// Initialize all charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Waste Types Pie Chart
    if (chartData.waste_types_pie && Object.keys(chartData.waste_types_pie).length > 0) {
        createPieChart('wasteTypesPieChart', 'Waste Types Distribution', chartData.waste_types_pie);
    }
    
    // Product Categories Pie Chart
    if (chartData.product_categories_pie && Object.keys(chartData.product_categories_pie).length > 0) {
        createPieChart('productCategoriesPieChart', 'Product Categories', chartData.product_categories_pie);
    }
    
    // Product Status Pie Chart
    if (chartData.product_status_pie && Object.keys(chartData.product_status_pie).length > 0) {
        createPieChart('productStatusPieChart', 'Product Status', chartData.product_status_pie);
    }
    
    // Order Status Pie Chart
    if (chartData.order_status_pie && Object.keys(chartData.order_status_pie).length > 0) {
        createPieChart('orderStatusPieChart', 'Order Status', chartData.order_status_pie);
    }
    
    // Waste Quantity Bar Chart
    if (chartData.waste_quantity_bar && Object.keys(chartData.waste_quantity_bar).length > 0) {
        createBarChart('wasteQuantityBarChart', 'Daily Waste Requests (kg)', chartData.waste_quantity_bar);
    }
    
    // Product Conditions Bar Chart
    if (chartData.product_conditions_bar && Object.keys(chartData.product_conditions_bar).length > 0) {
        createBarChart('productConditionsBarChart', 'Product Conditions', chartData.product_conditions_bar);
    }
    
    // Income Line Chart
    if (chartData.income_line_chart && Object.keys(chartData.income_line_chart).length > 0) {
        createLineChart('incomeLineChart', 'Daily Orders Value ($)', chartData.income_line_chart);
    }
}

function createPieChart(canvasId, title, data) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(data),
            datasets: [{
                data: Object.values(data),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: title },
                legend: { position: 'bottom' }
            }
        }
    });
}

function createBarChart(canvasId, title, data) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: title,
                data: Object.values(data),
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: title }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

function createLineChart(canvasId, title, data) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: title,
                data: Object.values(data),
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: title }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

// Action functions
function generateTodayStats() {
    if (confirm('Generate today\'s statistics? This will collect data from all related tables.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.analytics.generate-today") }}';
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}

function checkMissingAnalytics() {
    if (confirm('Check for missing analytics and update today\'s record?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.analytics.check-missing") }}';
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection