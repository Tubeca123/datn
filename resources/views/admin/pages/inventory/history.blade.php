@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-info">Quản lý kho</a>
                        </li>
                        <li class="breadcrumb-item active">Lịch sử nhập kho</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid mt-4">
        <!-- Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Lịch Sử Nhập Kho</h3>
            <a href="{{ route('admin_inventory') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Bộ lọc</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin_inventory_history') }}" method="GET" class="form-inline gap-3">
                    <div class="form-group">
                        <label class="mr-2">Từ ngày:</label>
                        <input type="date" name="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="form-group">
                        <label class="mr-2">Đến ngày:</label>
                        <input type="date" name="date_to" class="form-control" 
                               value="{{ request('date_to') }}">
                    </div>

                    <div class="form-group">
                        <label class="mr-2">Tình trạng hạn sử dụng:</label>
                        <select name="status" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>
                                Sắp hết hạn (≤ 30 ngày)
                            </option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>
                                Đã hết hạn
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('admin_inventory_history') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-redo"></i> Đặt lại
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="historyTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Mã lô</th>
                                <th>Số lượng nhập</th>
                                <th>Số lượng tồn</th>
                                <th>Tình trạng</th>
                                <th>Hạn sử dụng</th>
                                <th>Ngày nhập</th>
                                <th>Người nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $history)
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $daysUntilExpiry = null;
                                    
                                    if ($history->date_end) {
                                        // Tính từ hôm nay sang ngày hết hạn (dương nếu chưa hết hạn)
                                        $daysUntilExpiry = $today->startOfDay()->diffInDays($history->date_end->startOfDay());
                                    }
                                    
                                    if ($history->date_end && $history->date_end < $today) {
                                        $statusBadge = '<span class="badge badge-danger">Đã hết hạn</span>';
                                    } elseif ($history->date_end && isset($daysUntilExpiry) && $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30) {
                                        $statusBadge = '<span class="badge badge-warning">Sắp hết hạn (' . $daysUntilExpiry . ' ngày)</span>';
                                    } elseif ($history->stock_quantity <= 0) {
                                        $statusBadge = '<span class="badge badge-secondary">Đã bán hết</span>';
                                    } else {
                                        $statusBadge = '<span class="badge badge-success">Còn hàng</span>';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $history->id }}</td>
                                    <td>
                                        <strong>{{ $history->product->name ?? '-' }}</strong>
                                    </td>
                                    <td>{{ $history->code }}</td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ $history->import_quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($history->stock_quantity > 0)
                                            <span class="badge badge-info">{{ $history->stock_quantity }}</span>
                                        @else
                                            <span class="badge badge-danger">Hết</span>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $statusBadge !!}
                                    </td>
                                    <td>
                                        {{ $history->date_end ? $history->date_end->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        {{ $history->create_date ? $history->create_date->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $history->creator->name ?? 'Hệ thống' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        Không có lịch sử nhập kho
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $histories->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#historyTable').DataTable({
            pageLength: 10,
            order:[],
            language: {
                lengthMenu: "Hiển thị _MENU_ dòng",
                search: "Tìm kiếm:",
                zeroRecords: "Không tìm thấy dữ liệu",
                info: "Hiển thị từ _START_ đến _END_ của _TOTAL_ mục",
                infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
                infoFiltered: "(lọc từ _MAX_ tổng số mục)",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Tiếp",
                    previous: "Trước"
                }
            }
        });
    });
</script>
@endpush
