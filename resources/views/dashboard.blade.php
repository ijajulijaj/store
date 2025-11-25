<!-- This is Dashboard -->

@extends('layouts.app')
  
@section('contents')
<div class="container-fluid mb-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Page Heading -->
    <h3 class="mb-4">Dashboard</h3>
    
    <!-- First Row Order Summary Cards -->
    <div class="row">
        <!-- Pending Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow card-pending">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount['count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin.orders.pending.index') }}" class="text-xs text-warning">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                    <div class="h7 mb-0 font-weight-bold text-warning">
                        Tk {{ number_format($pendingCount['amount'], 2) }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Completed Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow card-completed">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completeCount['count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin.orders.complete.index') }}" class="text-xs text-success">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                    <div class="h7 mb-0 font-weight-bold text-success">
                        Tk {{ number_format($completeCount['amount'], 2) }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Canceled Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow card-canceled">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Canceled Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelCount['count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-danger-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin.orders.cancel.index') }}" class="text-xs text-danger">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                    <div class="h7 mb-0 font-weight-bold text-danger">
                        Tk {{ number_format($cancelCount['amount'], 2) }} (Including Bulk)
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Monthly Sales Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow card-sell">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Monthly Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Tk {{ number_format($monthlySales['amount'], 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-info-300 fa-spin"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="#" class="text-xs text-info">View Details <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                    <div class="h7 mb-0 font-weight-bold text-info">
                        Total Orders: {{ $monthlySales['orders'] }}
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Second Row Order Summary Cards -->
    <div class="row">
        <!-- Android Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow card-sell">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Android Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($androidOrders['orders'], 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fab fa-android fa-2x android-spin"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="#" class="text-xs text-secondary">
                            View Details <i class="fas fa-arrow-circle-right"></i> 
                        </a>
                    </div>
                    <div class="h7 mb-0 font-weight-bold text-secondary">
                        Tk {{ number_format($androidOrders['amount'], 0) }}
                    </div>
                </div>
            </div>
        </div>
    
        <!-- iOS Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow card-sell">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark mb-1">
                                iOS ORDERS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($iosOrders['orders'], 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fab fa-apple fa-2x ios-spin"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="#" class="text-xs text-dark">
                            View Details <i class="fas fa-arrow-circle-right"></i> 
                        </a>
                    </div>
                    <div class="h7 mb-0 font-weight-bold text-dark">
                        Tk {{ number_format($iosOrders['amount'], 0) }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top 3 Outlets MTD Sales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow card-sell">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase">
                                Top 3 Outlet Sales</div>
                            <div class="h7 mt-1 mb-2 font-weight-bold text-gray-800">
                                @if (!empty($topOutlets))
                                    @foreach($topOutlets as $outlet)
                                        @php
                                            $medal = '';
                                            if ($loop->iteration == 1) $medal = '🥇';
                                            elseif ($loop->iteration == 2) $medal = '🥈';
                                            elseif ($loop->iteration == 3) $medal = '🥉';
                                        @endphp
                                
                                        <div>{{ $medal }} {{ $outlet['outlet_code'] }} - Tk {{ number_format($outlet['total_sales'], 0) }}</div>
                                    @endforeach
                                @else
                                    <div>No Data Available</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Last Month Sales Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow card-sell">
                    <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Last Month Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Tk {{ number_format($lastMonthSales['amount'], 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x lastmonth-spin"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="#" class="text-xs text-primary">View Details <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                    <div class="h7 mb-0 font-weight-bold text-primary">
                        Total Orders: {{ $lastMonthSales['orders'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Growth Chart Section -->
    <div class="row">
        <!-- Weekly Trends Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Order Trends</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                            aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#" onclick="updateChart(7)">Last 7 Days</a>
                            <a class="dropdown-item" href="#" onclick="updateChart(30)">Last 30 Days</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="orderGrowthChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="mr-3"><i class="fas fa-circle" style="color: rgb(21, 93, 252);"></i> Pending</span>
                        <span class="mr-3"><i class="fas fa-circle text-success"></i> Completed</span>
                        <span><i class="fas fa-circle text-danger"></i> Canceled</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Forecast Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Order Forecast</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="forecastDropdown" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                            aria-labelledby="forecastDropdown">
                            <a class="dropdown-item" href="#" onclick="updateForecast(6)">6 Months</a>
                            <a class="dropdown-item" href="#" onclick="updateForecast(12)">12 Months</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="monthlyForecastChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="mr-3"><i class="fas fa-square text-primary"></i> Actual Orders</span>
                        <span class="mr-3"><i class="fas fa-square text-success"></i> Projected Orders</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Outlet Wise Order Summary -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 id="outletChartTitle" class="m-0 font-weight-bold text-primary">
                        Outlet Wise Order Trends
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="outletDropdownMenuLink" 
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                            aria-labelledby="outletDropdownMenuLink">
                            <a class="dropdown-item" href="#" onclick="updateOutletChart(7)">Last 7 Days</a>
                            <a class="dropdown-item" href="#" onclick="updateOutletChart(30)">Last 30 Days</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="outletOrderGrowthChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="mr-3"><i class="fas fa-circle" style="color: rgb(21, 93, 252);"></i> Pending</span>
                        <span class="mr-3"><i class="fas fa-circle text-success"></i> Completed</span>
                        <span><i class="fas fa-circle text-danger"></i> Canceled</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Top 10 Outlet Performance (Current Month)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="topOutletPerformanceChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="mr-3"><i class="fas fa-circle text-danger"></i> Pending Order</span>
                        <span><i class="fas fa-circle text-success"></i> Completed Order</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<!-- Chart.js -->
<script src="{{ asset('public/admin_assets/js/chart.js') }}"></script>
<script>
    window.monthlyForecastData = @json($monthlyForecast ?? []);
    window.growthData = @json($growthData ?? []);
    window.outletGrowthData = @json($outletGrowthData);
    window.topOutletPerformance = @json($topOutletPerformance);
    window.csrfToken = '{{ csrf_token() }}';
    window.routes = {
        orderStatistics: '{{ route("admin.order.statistics") }}',
        monthlyForecast: '{{ route("admin.monthly.forecast") }}',
        outletWiseOrderStatistics: '{{ route("admin.outletOrder.statistics") }}'
    };

</script>

<script src="{{ asset('public/admin_assets/js/style.js') }}"></script>
@endsection