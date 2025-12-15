@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-info">Quản lý kho</a>
                        </li>
                        <li class="breadcrumb-item active">Tồn kho thuốc</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid mt-4">

        <!-- Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Tổng Quan Tồn Kho (Thuốc)</h3>
        </div>

        

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Mã thuốc</th>
                                <th>Tên thuốc</th>
                                <th>Danh mục</th>
                                <th>Giá bán</th>
                                <th>Tổng tồn kho</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $prd)
                                <tr>
                                    <td>{{ $prd->id }}</td>
                                    <td>{{ $prd->name }}</td>
                                    <td>{{ $prd->category->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $price = $prd->units->first()
                                                ? $prd->units->first()->price_sale
                                                : 0;
                                        @endphp
                                        {{ number_format($price) }} đ
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $prd->total_stock }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{route('admin_inventory_batch', $prd->id)}}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem lô
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $products->withQueryString()->links() }}
                </div>
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
    });
</script>
@endpush
