@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
<section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{route('list_news')}}" class="text-info">Quản lý bài viết</a>
                        </li>
                        
                    </ol>
                </div>
            </div>
        </div>

    </section>
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Quản Lý Bài Viết</h3>
            <div>
            <a href="{{ route('create_news') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo mới
            </a>
            <a href="{{ route('list_news_category') }}" class="btn btn-success">
                 Quản lý thể loại
            </a>
            
            </div>
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
                <form method="GET" action="{{ route('list_news') }}" class="form-inline mb-3">
                    <div class="form-group mr-2">
                        <label for="category_id" class="sr-only">Thể loại</label>
                        <div class="input-group">
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Tất cả thể loại</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name ?? $category->description }}
                                </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="form-group mr-2">
                        <label for="isactive" class="sr-only">Trạng thái</label>
                        <select name="isactive" id="isactive" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('isactive') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="0" {{ request('isactive') == '0' ? 'selected' : '' }}>Ngừng hoạt động</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-info">Lọc</button>
                    <a href="{{ route('list_news') }}" class="btn btn-secondary ml-2">Reset</a>
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
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Thể loại</th>
                                <th>Người tạo</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                                <th>Trạng thái</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($news as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @php
                                    $img = $item->image ?? 'uploads/no_image.png';
                                    @endphp
                                    <img src="{{ asset($img) }}"
                                        width="60" height="60" style="object-fit: cover;">
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->category->name ?? $item->category->description ?? 'N/A' }}</td>
                                <td>{{ $item->creator->name ?? 'N/A' }}</td>
                                <td>
                                    @if($item->create_date)
                                    {{ \Carbon\Carbon::parse($item->create_date)->format('d/m/Y H:i') }}
                                    @else
                                    N/A
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('toggle_news', $item->id)}}" class="btn btn-info btn-sm">
                                        {{ $item->isactive ? 'Ẩn' : 'Hiện' }}
                                    </a>
                                    <a href="{{ route('edit_news', $item->id) }}" class="btn btn-sm btn-primary" title="Sửa">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('delete_news', $item->id) }}" method="GET" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($item->isactive)
                                    <span class="badge badge-success">Đang hoạt động</span>
                                    @else
                                    <span class="badge badge-danger">Ngừng hoạt động</span>
                                    @endif
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $news->links() }}
                </div>

            </div>
        </div>

    </div>
</div>

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
    });
</script>
@endpush