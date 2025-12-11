@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh mục thuốc</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">DataTables</li>
                    </ol>
                </div>
            </div>
            <a href="{{ route('create_category') }}" class="btn btn-primary mb-3">+ Thêm Category</a>
        </div>

    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh mục thể loại thuốc</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('list_category') }}" class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Loại danh mục:</label>
                                        <select name="position" class="form-control">
                                            <option value="">Tất cả</option>
                                            <option value="1" {{ request('position') == '1' ? 'selected' : '' }}>Danh mục cha (1)</option>
                                            <option value="2" {{ request('position') == '2' ? 'selected' : '' }}>Danh mục con (2)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Trạng thái:</label>
                                        <select name="isactive" class="form-control">
                                            <option value="">Tất cả</option>
                                            <option value="1" {{ request('isactive') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                                            <option value="0" {{ request('isactive') == '0' ? 'selected' : '' }}>Ẩn</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">Lọc</button>
                                        <a href="{{ route('list_category') }}" class="btn btn-secondary">Xóa bộ lọc</a>
                                    </div>
                                </div>
                            </form>

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Hình ảnh</th>
                                        <th>Tên</th>
                                        <th>Loại</th>
                                        <th>Danh mục cha</th>
                                        <th>Thao tác</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $cate)
                                    <tr>
                                        <td>{{ $cate->id }}</td>
                                        
                                        <td>
                                            @php
                                            $img = $cate->image ?? 'uploads/no_image.png';
                                            @endphp
                                            
                                            <img src="{{ asset($img) }}"
                                                width="60" height="60" style="object-fit: cover;">
                                        </td>
                                        <td>{{ $cate->name }}</td>
                                        <td>
                                            @if($cate->position == 1)
                                                <span class="badge bg-primary">Danh mục cha</span>
                                            @else
                                                <span class="badge bg-info">Danh mục con</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cate->position == 2 && isset($cate->parent_name))
                                                {{ $cate->parent_name }}
                                            @elseif($cate->position == 1)
                                                <span class="text-muted">-</span>
                                            @else
                                                <span class="text-danger">Chưa chọn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('edit_category', $cate->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                            <a href="{{ route('toggle_category', $cate->id) }}" class="btn btn-info btn-sm">
                                                {{ $cate->isactive ? 'Ẩn' : 'Hiện' }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($cate->isactive)
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