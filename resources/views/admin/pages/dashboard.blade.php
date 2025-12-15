@extends('admin.master_layout')
@section('page_content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thống Kê Doanh Thu</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Bộ lọc thời gian -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('trang_chu') }}" class="form-inline">
                                <label class="mr-2">Từ ngày:</label>
                                <input type="date" name="start_date" class="form-control mr-3" value="{{ $startDate }}">
                                
                                <label class="mr-2">Đến ngày:</label>
                                <input type="date" name="end_date" class="form-control mr-3" value="{{ $endDate }}">
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Lọc
                                </button>
                                
                                <a href="{{ route('trang_chu') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-redo"></i> Đặt lại
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Các thẻ thống kê tổng quan -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($totalRevenue, 0, ',', '.') }}đ</h3>
                            <p>Tổng Doanh Thu</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        @if($revenueGrowth != 0)
                        <div class="small-box-footer">
                            <span class="{{ $revenueGrowth > 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-arrow-{{ $revenueGrowth > 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($revenueGrowth), 1) }}% so với kỳ trước
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($totalOrders) }}</h3>
                            <p>Tổng Đơn Hàng</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($totalProductsSold) }}</h3>
                            <p>Sản Phẩm Đã Bán</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($avgOrderValue, 0, ',', '.') }}đ</h3>
                            <p>Giá Trị ĐH Trung Bình</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ doanh thu theo ngày -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Biểu Đồ Doanh Thu Theo Ngày
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ doanh thu theo tháng -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Doanh Thu Theo Tháng (Năm {{ date('Y') }})
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyRevenueChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top sản phẩm -->
            <div class="row">
                <!-- Top sản phẩm theo doanh thu -->
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-trophy"></i>
                                Top 10 Sản Phẩm Theo Doanh Thu
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>SL Bán</th>
                                        <th class="text-right">Doanh Thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProductsByRevenue as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->total_quantity) }}</td>
                                        <td class="text-right">{{ number_format($item->total_revenue, 0, ',', '.') }}đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top sản phẩm theo số lượng -->
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-star"></i>
                                Top 10 Sản Phẩm Bán Chạy
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Số Lượng</th>
                                        <th class="text-right">Doanh Thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProductsByQuantity as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td><span class="badge badge-success">{{ number_format($item->total_quantity) }}</span></td>
                                        <td class="text-right">{{ number_format($item->total_revenue, 0, ',', '.') }}đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ tròn doanh thu theo danh mục -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie"></i>
                                Doanh Thu Theo Danh Mục
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Thống kê nhanh -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i>
                                Thống Kê Chi Tiết
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Giai đoạn thống kê</span>
                                            <span class="info-box-number">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="info-box bg-info">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Doanh thu trung bình/ngày</span>
                                            <span class="info-box-number">
                                                {{ number_format($totalRevenue / max(1, \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1), 0, ',', '.') }}đ
                                            </span>
                                        </div>
                                    </div>

                                    <div class="info-box bg-success">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Số đơn hàng trung bình/ngày</span>
                                            <span class="info-box-number">
                                                {{ number_format($totalOrders / max(1, \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1), 1) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="info-box bg-warning">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Sản phẩm trung bình/đơn</span>
                                            <span class="info-box-number">
                                                {{ $totalOrders > 0 ? number_format($totalProductsSold / $totalOrders, 1) : 0 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ doanh thu theo ngày
var revenueData = {
    labels: [
        @foreach($revenueByDate as $item)
            '{{ \Carbon\Carbon::parse($item->date)->format("d/m") }}',
        @endforeach
    ],
    datasets: [{
        label: 'Doanh Thu (VNĐ)',
        data: [
            @foreach($revenueByDate as $item)
                {{ $item->daily_revenue }},
            @endforeach
        ],
        backgroundColor: 'rgba(60, 141, 188, 0.2)',
        borderColor: 'rgba(60, 141, 188, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
    }]
};

var revenueCtx = document.getElementById('revenueChart').getContext('2d');
var revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: revenueData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});

// Biểu đồ doanh thu theo tháng
var monthlyData = {
    labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
    datasets: [{
        label: 'Doanh Thu (VNĐ)',
        data: [
            @for($i = 1; $i <= 12; $i++)
                {{ $revenueByMonth->where('month', $i)->first()->monthly_revenue ?? 0 }},
            @endfor
        ],
        backgroundColor: 'rgba(0, 166, 90, 0.7)',
        borderColor: 'rgba(0, 166, 90, 1)',
        borderWidth: 2
    }]
};

var monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
var monthlyChart = new Chart(monthlyCtx, {
    type: 'bar',
    data: monthlyData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});

// Biểu đồ tròn theo danh mục
var categoryCtx = document.getElementById('categoryChart').getContext('2d');
var categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($revenueByCategory as $item)
                '{{ $item->category_name ?? "Khác" }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($revenueByCategory as $item)
                    {{ $item->category_revenue }},
                @endforeach
            ],
            backgroundColor: [
                '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc',
                '#d2d6de', '#605ca8', '#ff851b', '#01ff70', '#39cccc'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
@endsection