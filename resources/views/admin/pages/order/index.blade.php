@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Quản Lý Đơn Thuốc</h3>
            <a href="{{ route('order.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo đơn mới
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
                <form method="GET" action="{{ route('admin.orders.index') }}" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">Từ ngày:</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">Đến ngày:</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">Trạng thái:</label>
                        <select name="status" class="form-control">
                            <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary ml-2">
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
                                <th>Khách hàng</th>
                                
                                <th>Tổng tiền</th>
                                <th>Số SP</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->id }}</strong>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->create_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $order->user->name ?? 'Khách mua ngoài' }}</td>
                                    
                                    <td>
                                        <strong class="text-success">{{ number_format($order->total, 0, ',', '.') }} đ</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $order->details->count() }} sản phẩm</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($order->isactive == 1)
                                            <a href="{{ route('edit_order', $order->id) }}" class="btn btn-sm btn-warning" title="Sửa đơn">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger cancel-order-btn" 
                                                    data-order-id="{{ $order->id }}" 
                                                    title="Hủy đơn">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-ban"></i> Đã hủy
                                            </span>
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
                        Hiển thị {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} 
                        trong tổng số {{ $orders->total() }} đơn hàng
                    </div>
                    <div>
                        {{ $orders->links() }}
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
        $('#example2').DataTable({
            pageLength: 10,
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

        // Xử lý click nút Chi tiết
        $(document).on('click', '.viewInventory', function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            
            $('#inventoryModalLabel').text('Chi tiết Tồn kho - ' + productName);
            $('#inventoryTableBody').html('<tr><td colspan="6" class="text-center">Đang tải...</td></tr>');
            $('#inventoryModal').modal('show');

            // Fetch dữ liệu lô
            fetch('/admin/api/product/' + productId + '/inventories', {
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(inventories => {
                let html = '';
                if (inventories.length === 0) {
                    html = '<tr><td colspan="6" class="text-center text-muted">Không có lô nào</td></tr>';
                } else {
                    inventories.forEach(inv => {
                        const expiredClass = new Date(inv.date_end) < new Date() ? 'table-danger' : 'table-success';
                        const status = new Date(inv.date_end) < new Date() ? 'Hết hạn' : 'Còn hạn';
                        
                        html += `<tr class="${expiredClass}">
                            <td>${inv.code}- ${inv.id}</td>
                            <td>${inv.create_date || 'N/A'}</td>
                            <td>${inv.date_end || 'N/A'}</td>
                            <td>${inv.import_quantity}</td>
                            <td>${inv.stock_quantity}</td>
                            <td>${status}</td>
                        </tr>`;
                    });
                }
                $('#inventoryTableBody').html(html);
            })
            .catch(err => {
                console.error('Error:', err);
                $('#inventoryTableBody').html('<tr><td colspan="6" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>');
            });
        });
    });
</script>
@endpush