@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh mục thương hiệu</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">DataTables</li>
                    </ol>
                </div>
            </div>
            <a href="{{ route('create_brand') }}" class="btn btn-primary mb-3">+ Thêm Thương hiệu</a>
        </div><!-- /.container-fluid -->

    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh mục thương hiệu</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên thương hiệu</th>
                                        <th>Mô tả</th>
                                        <th>Thao tác</th>
                                        <th>Trạng thái</th>
                                        
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($brands as $b)
                                    <tr>
                                        <td>{{ $b->id }}</td>
                                        <td>{{ $b->name }}</td>
                                        <td>{{ $b->description }}</td>
                                        
                                        <td>
                                            <a href="{{ route('edit_brand', $b->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                            <a href="{{ route('toggle_brand', $b->id) }}" class="btn btn-info btn-sm">
                                                {{ $b->isactive ? 'Ẩn' : 'Hiện' }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($b->isactive)
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