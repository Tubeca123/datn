@extends('admin.master_layout')
@section('page_content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chi Tiết Doanh Thu & Lợi Nhuận</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('trang_chu') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Chi Tiết Doanh Thu</li>
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
                            <form method="GET" action="{{ route('admin.dashboard.revenue-details') }}" class="form-inline">
                                <label class="mr-2">Từ ngày:</label>
                                <input type="date" name="start_date" class="form-control mr-3" value="{{ $startDate }}">
                                
                                <label class="mr-2">Đến ngày:</label>
                                <input type="date" name="end_date" class="form-control mr-3" value="{{ $endDate }}">
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Lọc
                                </button>
                                
                                <a href="{{ route('admin.dashboard.revenue-details') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-redo"></i> Đặt lại
                                </a>

                                <a href="{{ route('trang_chu', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-info ml-2">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê tổng quan -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($totalStats->total_revenue ?? 0, 0, ',', '.') }}đ</h3>
                            <p>Tổng Doanh Thu</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($totalStats->total_cost ?? 0, 0, ',', '.') }}đ</h3>
                            <p>Tổng Giá Vốn</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($totalStats->total_profit ?? 0, 0, ',', '.') }}đ</h3>
                            <p>Tổng Lợi Nhuận</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($profitMargin, 1) }}%</h3>
                            <p>Tỷ Suất Lợi Nhuận</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs để chuyển giữa các view -->
            <div class="card">
                <div class="card-header p-0 pt-3">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $view == 'products' ? 'active' : '' }}" href="{{ route('admin.dashboard.revenue-details', ['start_date' => $startDate, 'end_date' => $endDate, 'view' => 'products']) }}" role="tab">
                                <i class="fas fa-box mr-2"></i> Chi Tiết Sản Phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $view == 'categories' ? 'active' : '' }}" href="{{ route('admin.dashboard.revenue-details', ['start_date' => $startDate, 'end_date' => $endDate, 'view' => 'categories']) }}" role="tab">
                                <i class="fas fa-list mr-2"></i> Chi Tiết Danh Mục
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if ($view == 'products')
                        <!-- Bảng chi tiết sản phẩm -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        
                                        <th style="width: 20%">Tên Sản Phẩm</th>
                                        <th style="width: 15%">Danh Mục</th>
                                        <th style="width: 10%">Số Lượng</th>
                                        <th style="width: 12%">Giá Nhập/Đơn Vị</th>
                                        <th style="width: 12%">Giá Bán/Đơn Vị</th>
                                        <th style="width: 12%">Doanh Thu</th>
                                        <th style="width: 12%">Giá Vốn</th>
                                        <th style="width: 12%">Lợi Nhuận</th>
                                        <th style="width: 10%">Tỷ Suất</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productDetails as $index => $item)
                                        @php
                                            $profitMarginItem = $item->total_revenue > 0 
                                                ? (($item->profit ?? 0) / $item->total_revenue) * 100 
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $productDetails->firstItem() + $index }}</td>

                                            <td><strong>{{ $item->product_name }}</strong></td>
                                            <td><span class="badge badge-primary">{{ $item->category_name ?? 'N/A' }}</span></td>
                                            <td><span class="badge badge-info">{{ number_format($item->total_quantity ?? 0) }}</span></td>
                                            <td>{{ number_format($item->price_import ?? 0, 0, ',', '.') }}đ</td>
                                            <td>{{ number_format($item->price_sale ?? 0, 0, ',', '.') }}đ</td>
                                            <td class="text-success"><strong>{{ number_format($item->total_revenue ?? 0, 0, ',', '.') }}đ</strong></td>
                                            <td class="text-warning">{{ number_format($item->total_cost ?? 0, 0, ',', '.') }}đ</td>
                                            <td class="text-primary"><strong>{{ number_format($item->profit ?? 0, 0, ',', '.') }}đ</strong></td>
                                            <td>
                                                @if ($profitMarginItem > 0)
                                                    <span class="badge badge-success">{{ number_format($profitMarginItem, 1) }}%</span>
                                                @elseif ($profitMarginItem < 0)
                                                    <span class="badge badge-danger">{{ number_format($profitMarginItem, 1) }}%</span>
                                                @else
                                                    <span class="badge badge-secondary">0%</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $productDetails->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <!-- Bảng chi tiết danh mục -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%">Tên Danh Mục</th>
                                        <th style="width: 15%">Số Sản Phẩm</th>
                                        <th style="width: 15%">Tổng Số Lượng</th>
                                        <th style="width: 15%">Doanh Thu</th>
                                        <th style="width: 15%">Giá Vốn</th>
                                        <th style="width: 15%">Lợi Nhuận</th>
                                        <th style="width: 10%">Tỷ Suất</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categoryDetails as $index => $item)
                                        @php
                                            $profitMarginCat = $item->total_revenue > 0 
                                                ? (($item->profit ?? 0) / $item->total_revenue) * 100 
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $item->category_name ?? 'N/A' }}</strong></td>
                                            <td><span class="badge badge-secondary">{{ $item->product_count ?? 0 }}</span></td>
                                            <td><span class="badge badge-info">{{ number_format($item->total_quantity ?? 0) }}</span></td>
                                            <td class="text-success"><strong>{{ number_format($item->total_revenue ?? 0, 0, ',', '.') }}đ</strong></td>
                                            <td class="text-warning">{{ number_format($item->total_cost ?? 0, 0, ',', '.') }}đ</td>
                                            <td class="text-primary"><strong>{{ number_format($item->profit ?? 0, 0, ',', '.') }}đ</strong></td>
                                            <td>
                                                @if ($profitMarginCat > 0)
                                                    <span class="badge badge-success">{{ number_format($profitMarginCat, 1) }}%</span>
                                                @elseif ($profitMarginCat < 0)
                                                    <span class="badge badge-danger">{{ number_format($profitMarginCat, 1) }}%</span>
                                                @else
                                                    <span class="badge badge-secondary">0%</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            
        </div>
    </section>
</div>
@endsection
