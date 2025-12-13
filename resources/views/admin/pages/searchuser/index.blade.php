@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh mục tài khoản</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tài khoản</li>
                    </ol>
                </div>
            </div>

            <!-- Filter -->
            <form method="GET" class="mb-3" id="filterForm">
                <div class="row">
                    <div class="col-md-4">
                        <label>Trạng thái:</label>
                        <select name="status" class="form-control" id="statusFilter">
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Loại tài khoản:</label>
                        <select name="role" class="form-control" id="roleFilter">
                            @foreach($roles as $roleItem)
                            <option value="{{ $roleItem->id }}"
                                {{ (int) $role == $roleItem->id ? 'selected' : '' }}>
                                {{ $roleItem->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-primary form-control" id="applyFilterBtn">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>


        </div>

    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Số điện thoại</th>
                                        <th>Email</th>
                                        <th>Loại TK</th>
                                        <th>Thao tác</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone ?? 'N/A' }}</td>
                                        <td>{{ $user->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $user->role?->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <a href=" {{ route('orders_user_show', $user->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i> Xem đơn
                                            </a>
                                            <button type="button" class="btn btn-sm toggleStatus"
                                                data-user-id="{{ $user->id }}"
                                                title="{{ $user->isactive ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}">
                                                @if($user->isactive)
                                                <i class="fas fa-lock"></i> Khóa
                                                @else
                                                <i class="fas fa-unlock"></i> Mở khóa
                                                @endif
                                            </button>

                                        </td>
                                        <td>
                                            @if($user->isactive)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> Đang hoạt động
                                            </span>
                                            @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-ban"></i> Bị khóa
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có tài khoản nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

</div>



@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo DataTable với cấu hình cơ bản
        let table = $('#example2').DataTable({
            pageLength: 10,
            ordering: true,
            searching: true,
            paging: true,
            info: true,
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

        // Xử lý filter button
        $('#applyFilterBtn').on('click', function() {
            $('#filterForm').submit();
        });

        $('#statusFilter, #roleFilter').on('change', function() {});

        // Handle toggle status button với SweetAlert2
        $(document).on('click', '.toggleStatus', function() {
            const userId = $(this).data('user-id');
            const btn = $(this);
            const isActive = btn.closest('tr').find('.badge-success').length > 0;
            const action = isActive ? 'khóa' : 'mở khóa';
            const actionText = isActive ? 'Khóa' : 'Mở khóa';

            // SweetAlert Confirm Dialog
            Swal.fire({
                title: `${actionText} tài khoản?`,
                text: `Bạn có chắc chắn muốn ${action} tài khoản này?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#dc3545' : '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: ` ${action}!`,
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disable button while processing
                    btn.prop('disabled', true);
                    const originalText = btn.html();
                    btn.html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');

                    // AJAX Request
                    fetch(`/admin/api/user/${userId}/toggle-status`, {
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