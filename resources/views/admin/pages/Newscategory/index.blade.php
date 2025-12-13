@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Quản Lý Thể Loại Bài Viết</h3>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCategoryModal">
                <i class="fas fa-plus"></i> Thêm thể loại
            </button>
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
                <form method="GET" action="{{ route('list_news_category') }}" class="form-inline">
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
                    <a href="{{ route('list_news_category') }}" class="btn btn-secondary ml-2">
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
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th>Ngày tạo</th>
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
                                <td>{{ $item->name ? $item->name : "Chưa cập nhật" }}</td>

                                <td>{{ $item->description ? $item->description : "Chưa cập nhật"  }}</td>
                                <td>{{ $item->create_date ? $item->create_date : "Chưa cập nhật"  }}</td>
                                <td>
                                    <a href="{{route('toggle_news_category', $item->id)}}" class="btn btn-info btn-sm">
                                        {{ $item->isactive ? 'Ẩn' : 'Hiện' }}
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary btn-edit-category" data-id="{{ $item->id }}" title="Sửa chi">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('delete_news_category', $item->id) }}" method="GET" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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



@include('admin.pages.new.modalcategory')
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

    $(document).on('click', '.btn-edit-category', function() {
        var cid = $(this).data('id');
        window.openEditCategoryModal(cid);
    });
</script>
@endpush