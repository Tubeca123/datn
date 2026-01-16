@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">

    <!-- Notification Alert -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" id="successAlert" role="alert">
        <i class="fas fa-check-circle"></i> <strong>Thành công!</strong> {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

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
            <h3>Tổng Quan Tồn Kho</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-lg shadow-sm import-btn" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-file-upload"></i> <strong>Nhập Kho</strong>
                </button>
                <a href="{{ route('admin_inventory_history') }}" class="btn btn-outline-info btn-lg shadow-sm">
                    <i class="fas fa-history"></i> Lịch Sử Nhập
                </a>
            </div>

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

<!-- Modal Nhập File Excel -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-upload"></i> Nhập File Excel Thuốc
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="importForm" method="POST" enctype="multipart/form-data" action="{{ route('importExcel') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excelFile" class="font-weight-bold mb-3">Chọn File Excel</label>
                        <div class="custom-file-upload">
                            <input type="file" id="excelFile" name="file" class="form-control-file" accept=".xlsx,.xls,.csv" required>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Hỗ trợ định dạng: .xlsx, .xls, .csv
                            </small>
                        </div>
                        <div id="fileName" class="mt-3 d-none">
                            <div class="alert alert-info alert-sm" role="alert">
                                <i class="fas fa-check-circle"></i> File: <strong id="fileNameText"></strong>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Hướng dẫn:</label>
                        <ul class="small text-muted">
                            <li>File phải chứa các cột: Mã thuốc, Tên thuốc, Danh mục, Giá bán, Số lượng</li>
                            <li>Không được để trống các cột bắt buộc</li>
                            <li>Tối đa 10.000 dòng trong một file</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-upload"></i> Nhập File
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
   

</style>
@endpush

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

        // Auto close success alert after 5 seconds
        if ($('#successAlert').length) {
            setTimeout(function() {
                $('#successAlert').fadeOut('slow', function() {
                    $(this).alert('close');
                });
            }, 5000);
        }

        // Xử lý chọn file
        $('#excelFile').on('change', function(e) {
            let fileName = this.files[0]?.name;
            if (fileName) {
                $('#fileNameText').text(fileName);
                $('#fileName').removeClass('d-none');
                
                // Validate file size (max 5MB)
                let fileSize = this.files[0].size;
                let maxSize = 5 * 1024 * 1024; // 5MB
                if (fileSize > maxSize) {
                    alert('File quá lớn! Tối đa 5MB');
                    this.value = '';
                    $('#fileName').addClass('d-none');
                    return;
                }
            } else {
                $('#fileName').addClass('d-none');
            }
        });

        // Submit form
        $('#importForm').on('submit', function(e) {
            let fileInput = $('#excelFile')[0];
            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Vui lòng chọn file!');
                return;
            }

            let submitBtn = $('#submitBtn');
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Đang nhập...');
        });

        // Reset modal khi đóng
        $('#importModal').on('hide.bs.modal', function() {
            $('#importForm')[0].reset();
            $('#fileName').addClass('d-none');
            $('#submitBtn').prop('disabled', false);
            $('#submitBtn').html('<i class="fas fa-upload"></i> Nhập File');
        });
    });
</script>
@endpush