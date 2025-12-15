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
        <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Mã lô</th>
                    
                    <th>Số lượng nhập</th>
                    <th>Số lượng tồn</th>
                    <th>Ngày hết hạn</th>
                    <th>Ngày nhập </th>
                    <th>Người nhập </th>
                </tr>
            </thead>
            <tbody>
                @foreach($batches as $batch)
                <tr>
                    <td>{{ $batch->code }}</td>
                    <td>{{ $batch->date_end ? $batch->date_end->format('d/m/Y') : '-' }}</td>

                    <td>{{ $batch->import_quantity }}</td>

                    <td>{{ $batch->stock_quantity > 0 ? $batch->stock_quantity : 'hết' }}</td>
                    </td>
                    <td>{{ $batch->create_date }}</td>
                    <td>{{ $batch->creator->name ?? '-'}}</td>
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