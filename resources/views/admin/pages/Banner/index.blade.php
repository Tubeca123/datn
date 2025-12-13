@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Quản Lý Banner</h3>
            <a href="{{ route('create_banner') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo mới
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
                <form method="GET" action="{{ route('list_banner') }}" class="form-inline">
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
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đang ẩn</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('list_banner') }}" class="btn btn-secondary ml-2">
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
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hình ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Đường dẫn</th>
                                <th>Thao tác</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $counter = 1;
                            @endphp
                            @foreach ($items as $item)
                            <tr id="slide-{{ $item->id }}">
                                <td>{{ $counter++ }}</td>
                                <td><img src="/{{ $item->image}}" alt="" style="width: 90px; height: 60px"></td>
                                <td>{{ $item->content ? $item->content : "Chưa cập nhật" }}</td>
                                <td>{{ $item->link ? $item->link : "Chưa cập nhật"  }}</td>
                                <td>
                                    <a href="{{route('toggle_banner', $item->id)}}" class="btn btn-info btn-sm">
                                        {{ $item->isactive ? 'Ẩn' : 'Hiện' }}
                                    </a>
                                    <a href="{{route('edit_banner', $item->id )}}" class="btn btn-info btn-sm" title="Xem thông tin slide">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{route('delete_banner', $item->id)}}" class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" title="Xóa banner">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                                <td>
                                    @if($item->isactive)
                                    <span class="badge bg-success">Đang hoạt động</span>
                                    @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                
            </div>
        </div>

    </div>
</div>

<!-- Cancel Order Modal -->




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

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-delete").forEach(function(btn) {
                btn.addEventListener("click", function(e) {
                    e.preventDefault(); // chặn load trang

                    let url = this.getAttribute("href");

                    if (confirm("Bạn có chắc chắn muốn xoá banner này không?")) {
                        window.location.href = url;
                    }
                });
            });
        });
    });
</script>
@endpush

