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
                
            </div>
            <form method="POST" action="/admin/inventory/import" enctype="multipart/form-data">
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
                                        <th>Thao tác</th>
                                        <th>Trạng thái</th>
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

                                        

                                        <td>
                                            <button class="btn btn-primary btn-sm viewInventory" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                                Chi tiết
                                            </button>
                                        </td>

                                        <td>
                                            <a href="/admin/edit_product/{{ $product->id }}" class="btn btn-warning btn-sm">Sửa</a>
                                            <a href="{{ route('toggle_product', $product->id) }}" class="btn btn-info btn-sm">
                                                {{ $product->isactive ? 'Ẩn' : 'Hiện' }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($product->isactive)
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
    <!-- /.content -->
</div>

<!-- Modal Chi tiết Tồn kho -->
<div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryModalLabel">Chi tiết Tồn kho</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Số Lô</th>
                            <th>Ngày Nhập</th>
                            <th>Hạn Sử Dụng</th>
                            <th>SL Nhập</th>
                            <th>SL Hiện Tại</th>
                            <th>Tình Trạng</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody">
                        <tr><td colspan="6" class="text-center">Đang tải...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

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