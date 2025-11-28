@extends('admin.master_layout')
@section('page_content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thuốc chữa bệnh</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">DataTables</li>
                    </ol>
                </div>
            </div>
            <form method="POST" action="/inventory/import" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" required>
                <button type="submit">Import</button>
            </form>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh mục các sản phẩm là thuốc chữa bệnh</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Nhãn hiệu</th>
                                        <th>Tồn kho</th>
                                        <th>Hạn dùng gần nhất</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            @php
                                            $img = $product->images->first()->src ?? 'uploads/no_image.png';
                                            @endphp
                                            
                                            <img src="{{ asset($img) }}"
                                                width="60" height="60" style="object-fit: cover;">
                                        </td>

                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? '' }}</td>
                                        <td>{{ $product->brand->name ?? '' }}</td>

                                        <!-- Tổng tồn kho = sum(stock_quantity) -->
                                        <td>
                                            {{ $product->inventory->sum('stock_quantity') }}
                                        </td>

                                        <td>
                                            {{ optional($product->inventory->sortBy('date_end')->first())->date_end }}
                                        </td>

                                        <td>
                                            <a href="/admin/edit_product/{{ $product->id }}" class="btn btn-warning btn-sm">Sửa</a>
                                            <a href="/admin/product/delete/{{ $product->id }}" class="btn btn-danger btn-sm">Xóa</a>
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
    <!-- /.content -->
</div>
@endsection