@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-info">Quản lý kho</a>
                        </li>
                        <li class="breadcrumb-item active">Lô hàng tồn kho: {{ $product->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid mt-3">
        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Bộ lọc</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin_inventory_batch', $product->id) }}" method="GET" class="form-inline gap-3">
                    <div class="form-group">
                        <label class="mr-2">Tình trạng hạn sử dụng:</label>
                        <select name="status" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                Còn hàng (chưa hết hạn)
                            </option>
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
                        <a href="{{ route('admin_inventory_batch', $product->id) }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-redo"></i> Đặt lại
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Mã lô</th>
                    <th>Số lượng nhập</th>
                    <th>Số lượng tồn</th>
                    <th>Tình trạng</th>
                    <th>Ngày hết hạn</th>
                    <th>Ngày nhập</th>
                    <th>Người nhập</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batches as $batch)
                @php
                $today = \Carbon\Carbon::today();
                $daysUntilExpiry = null;

                if ($batch->date_end) {
                // Tính từ hôm nay sang ngày hết hạn (dương nếu chưa hết hạn)
                $daysUntilExpiry = $today->startOfDay()->diffInDays($batch->date_end->startOfDay());
                }

                if ($batch->date_end && $batch->date_end < $today) {
                    $status='<span class="badge badge-danger">Đã hết hạn</span>' ;
                    $statusType='expired' ;
                    } elseif ($batch->date_end && isset($daysUntilExpiry) && $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30) {
                        $status='<span class="badge badge-warning">Sắp hết hạn (' . $daysUntilExpiry . ' ngày)</span>' ;
                        $statusType='expiring' ;
                        } elseif ($batch->stock_quantity <= 0) {
                            $status='<span class="badge badge-secondary">Đã bán hết</span>' ;
                            $statusType='sold' ;
                            } else {
                            $status='<span class="badge badge-success">Còn hàng</span>' ;
                            $statusType='active' ;
                            }

                            // Kiểm tra filter
                            $statusFilter=request('status');
                            if ($statusFilter && $statusFilter !==$statusType) {
                            continue;
                            }
                            @endphp
                            <tr>
                            <td>{{ $batch->code }}</td>
                            <td>{{ $batch->import_quantity }}</td>
                            <td>{{ $batch->stock_quantity > 0 ? $batch->stock_quantity : 'hết' }}</td>
                            <td>{!! $status !!}</td>
                            <td>{{ $batch->date_end ? $batch->date_end->format('d/m/Y') : '-' }}</td>
                            <td>{{ $batch->create_date ? $batch->create_date->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $batch->creator->name ?? '-'}}</td>
                            <td>
                                <button type="button" class="btn btn-sm toggleStatus"
                                    data-batch-id="{{ $batch->id }}"
                                    title="{{ $batch->isactive ? 'Vô hiệu hóa lô hàng' : 'Kích hoạt lô hàng' }}">
                                    @if($batch->isactive)
                                    <i class="fas fa-check-circle text-success"></i> Kích hoạt
                                    @else
                                    <i class="fas fa-times-circle text-danger"></i> Vô hiệu
                                    @endif
                                </button>
                            </td>
                            </tr>
                            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>

    $(document).ready(function() {
        $('#example2').DataTable({
            pageLength: 10,
            order: [],
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

        // Handle toggle status button với SweetAlert2
        $(document).on('click', '.toggleStatus', function() {
            const batchId = $(this).data('batch-id');
            const btn = $(this);
            const isActive = btn.find('i').hasClass('fa-check-circle');
            const action = isActive ? 'vô hiệu hóa' : 'kích hoạt';
            const actionText = isActive ? 'Vô hiệu hóa' : 'Kích hoạt';

            // SweetAlert Confirm Dialog
            Swal.fire({
                title: `${actionText} lô hàng?`,
                text: `Bạn có chắc chắn muốn ${action} lô hàng này?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#dc3545' : '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `${action}!`,
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disable button while processing
                    btn.prop('disabled', true);
                    const originalText = btn.html();
                    btn.html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');

                    // AJAX Request
                    fetch(`/admin/api/inventory/${batchId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                // Success Alert
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                // Error Alert
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: data.message || 'Không thể cập nhật trạng thái',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'OK'
                                });
                                btn.prop('disabled', false);
                                btn.html(originalText);
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Lỗi khi cập nhật trạng thái',
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                            btn.prop('disabled', false);
                            btn.html(originalText);
                        });
                }
            });
        });
    });
    
</script>
@endpush