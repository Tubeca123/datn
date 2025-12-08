@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3>Đơn hàng của: <strong>{{ $user->name ?? 'Khách hàng' }}</strong></h3>
                <small class="text-muted">ID: #{{ $user->id ?? '' }} | SĐT: {{ $user->phone ?? 'N/A' }} | Email: {{ $user->email ?? 'N/A' }}</small>
            </div>
            <a href="{{ route('list_user') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        <!-- Alert messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">Từ ngày:</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                    </div>

                    <div class="form-group mr-3">
                        <label class="mr-2">Đến ngày:</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                    </div>

                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Lọc
                    </button>

                    <div class="form-group mr-3">
                        <label class="mr-2">Trạng thái:</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="all" {{ (isset($status) && $status == 'all') ? 'selected' : '' }}>-- Tất cả --</option>
                            <option value="success" {{ (isset($status) && $status == 'success') ? 'selected' : '' }}>Đơn thành công</option>
                            <option value="cancelled" {{ (isset($status) && $status == 'cancelled') ? 'selected' : '' }}>Đơn hủy</option>
                        </select>
                    </div>
                    
                    <a href="{{ route('orders_user_show', $user->id ?? 0) }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Làm mới
                    </a>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="80">Mã ĐH</th>
                                <th>Ngày tạo</th>
                                <th>Tổng tiền</th>
                                <th width="100">Số SP</th>
                                <th width="120">Trạng thái</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->id }}</strong>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($order->create_date)->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <strong class="text-success">{{ number_format($order->total, 0, ',', '.') }} đ</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $order->details->count() }} sản phẩm</span>
                                </td>
                                <td>
                                    @if($order->isactive == 1)
                                    <span class="badge badge-success">Thành công</span>
                                    @else
                                    <span class="badge badge-danger">Hủy</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>

                                    @if($order->isactive == 1)
                                    <button type="button" class="btn btn-sm btn-danger cancel-order-btn"
                                        data-order-id="{{ $order->id }}"
                                        title="Hủy đơn">
                                        <i class="fas fa-times"></i> Hủy
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Chưa có đơn hàng nào</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Tổng cộng: <strong>{{ count($orders) }}</strong> đơn hàng
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận hủy đơn hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy đơn hàng <strong>#<span id="cancelOrderId"></span></strong>?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Thao tác này sẽ hoàn trả tồn kho và không thể hoàn tác.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <span class="spinner-border spinner-border-sm" role="status" style="display:none" id="cancelSpinner"></span>
                    Xác nhận hủy
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentOrderId = null;

        // Mở modal hủy đơn
        document.querySelectorAll('.cancel-order-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentOrderId = this.getAttribute('data-order-id');
                document.getElementById('cancelOrderId').textContent = currentOrderId;
                $('#cancelOrderModal').modal('show');
            });
        });

        // Xác nhận hủy đơn
        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            if (!currentOrderId) return;

            const spinner = document.getElementById('cancelSpinner');
            const btn = this;

            btn.disabled = true;
            spinner.style.display = 'inline-block';

            fetch(`/admin/orders/${currentOrderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        $('#cancelOrderModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Lỗi: ' + (data.error || 'Không thể hủy đơn hàng'));
                    }
                })
                .catch(err => {
                    console.error('Cancel error:', err);
                    alert('Lỗi khi hủy đơn hàng');
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.style.display = 'none';
                });
        });
    });
</script>

@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Không cần DataTable cho trang này
    });
</script>
@endpush