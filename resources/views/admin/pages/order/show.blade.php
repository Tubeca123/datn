@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <h3>Chi Tiết Đơn Hàng #{{ $order->id }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
                        </li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                @if($order->isactive == 1)
                <button type="button" class="btn btn-danger" id="cancelOrderBtn">
                    <i class="fas fa-times"></i> Hủy đơn
                </button>
                <a href="{{ route('edit_order', $order->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Sửa đơn
                </a>
                @endif

                
                
                <button class="btn btn-info" onclick="window.print()">
                    <i class="fas fa-print"></i> In đơn
                </button>
            </div>
        </div>

        <div class="print-only text-center mb-4" style="display:none">
            <h2>ĐơN THUỐC</h2>
            <p>Mã đơn: #{{ $order->id }}</p>
            <p>Ngày: {{ \Carbon\Carbon::parse($order->create_date)->format('d/m/Y H:i') }}</p>
        </div>

        <div class="row">
            <!-- Thông tin đơn hàng -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">

                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td>
                                    @if($order->isactive == 1)
                                    <span class="badge badge-success badge-lg">Đã thanh toán</span>
                                    @else
                                    <span class="badge badge-secondary badge-lg">Đã hủy</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($order->create_date)->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tên khách hàng:</strong></td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $order->user->name ?? 'Khách mua ngoài' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Người tạo đơn:</strong></td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $order->creator->name ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @if($order->update_date)
                            <tr>
                                <td><strong>Cập nhật:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($order->update_date)->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Tổng tiền -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator"></i> Thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng sản phẩm:</span>
                            <strong class="text-primary">{{ $totalItems ?? $order->details->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng số lượng:</span>
                            <strong class="text-primary">{{ $totalQuantity ?? $order->details->sum('quantity') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Tổng tiền:</h5>
                            <h5 class="text-success mb-0">
                                <strong>{{ number_format($order->total, 0, ',', '.') }} đ</strong>
                            </h5>
                        </div>

                        @php
                        $calculatedTotal = $order->details->sum(function($d) {
                        return $d->quantity * $d->getPrice();
                        });
                        @endphp

                        @if(abs($calculatedTotal - $order->total) > 1)
                        <div class="alert alert-warning mt-3 mb-0">
                            <small>
                                <i class="fas fa-exclamation-triangle"></i>
                                Tổng tính lại: {{ number_format($calculatedTotal, 0, ',', '.') }} đ
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Chi tiết sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        @if($order->details->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i>
                            Đơn hàng không có sản phẩm
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="50" class="text-center">#</th>
                                        <th>Sản phẩm</th>
                                        <th>Lô thuốc</th>
                                        <th width="100" class="text-center">Đơn vị</th>
                                        <th width="100" class="text-center">Số lượng</th>
                                        <th width="120" class="text-right">Đơn giá</th>
                                        <th width="130" class="text-right">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalAmount = 0; @endphp
                                    @foreach($order->details as $index => $detail)
                                    @php
                                    $amount = $detail->quantity * $detail->price;
                                    $totalAmount += $amount;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $detail->product->name ?? 'N/A' }}</strong>

                                        </td>
                                        <td><strong>{{ $detail->inventory->id ?? 'N/A' }}</strong></td>
                                        <td class="text-center">
                                            @if($detail->productUnit && $detail->productUnit->unit)
                                            <span class="badge badge-primary">
                                                {{ $detail->productUnit->unit->name }}
                                            </span>
                                            @else
                                            <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ number_format($detail->quantity, 2) }}</strong>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($detail->price, 0, ',', '.') }} đ
                                        </td>
                                        <td class="text-right">
                                            <strong>{{ number_format($amount, 0, ',', '.') }} đ</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <th colspan="5" class="text-right">Tổng cộng:</th>
                                        <th class="text-right text-success">
                                            <strong>{{ number_format($totalAmount, 0, ',', '.') }} đ</strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Lịch sử thay đổi -->
                <div class="card mt-4 no-print">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Lịch sử thay đổi</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Nếu đơn hàng bị hủy, hiển thị sự kiện hủy -->
                            @if($order->isactive == 0 && $order->update_date)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <p class="mb-1">
                                        <strong>
                                            <i class="fas fa-times-circle"></i> Đơn hàng bị hủy
                                        </strong>
                                    </p>
                                    <small class="text-muted d-block">
                                        {{ \Carbon\Carbon::parse($order->update_date)->format('d/m/Y H:i:s') }}
                                        @if($order->update_by)
                                        <br>Bởi: <strong>{{ App\Models\User::find($order->update_by)->name ?? 'N/A' }}</strong>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            @endif

                            <!-- Hiển thị lịch sử cập nhật từ OrderHistory -->
                            @forelse($history as $h)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <p class="mb-1">
                                        <strong>
                                            <i class="fas fa-sync-alt"></i> Cập nhật đơn hàng
                                            @if($h->total)
                                                - Tổng: <span class="text-success">{{ number_format($h->total, 0, ',', '.') }} đ</span>
                                            @endif
                                        </strong>
                                    </p>
                                    <small class="text-muted d-block mb-2">
                                        {{ \Carbon\Carbon::parse($h->create_date)->format('d/m/Y H:i:s') }}
                                        @if($h->creator)
                                        <br>Bởi: <strong>{{ $h->creator->name }}</strong>
                                        @endif
                                    </small>

                                    @if($h->details && $h->details->count() > 0)
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th class="text-center">Đơn vị</th>
                                                    <th class="text-center">Số lượng</th>
                                                    <th class="text-right">Đơn giá</th>
                                                    <th class="text-right">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($h->details as $detail)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $detail->product->name ?? 'N/A' }}</strong>
                                                        <br><small class="text-muted">Lô: {{ $detail->code ?? 'N/A' }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <small>{{ $detail->productUnit?->unit?->name ?? 'N/A' }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong>{{ number_format($detail->quantity, 2) }}</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($detail->price, 0, ',', '.') }} đ
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>{{ number_format($detail->quantity * $detail->price, 0, ',', '.') }} đ</strong>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            @endforelse

                            <!-- Hiển thị sự kiện tạo đơn -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <p class="mb-1">
                                        <strong>
                                            <i class="fas fa-plus-circle"></i> Đơn hàng được tạo
                                            - Tổng: <span class="text-success">{{ number_format($history->isEmpty() ? $order->total : $history->last()->total, 0, ',', '.') }} đ</span>
                                        </strong>
                                    </p>
                                    <small class="text-muted d-block mb-2">
                                        {{ \Carbon\Carbon::parse($order->create_date)->format('d/m/Y H:i:s') }}
                                        @if($order->create_by)
                                        <br>Bởi: <strong>{{ App\Models\User::find($order->create_by)->name ?? 'N/A' }}</strong>
                                        @endif
                                    </small>

                                    @php
                                        // Lấy chi tiết từ lịch sử đầu tiên hoặc từ order details hiện tại
                                        $firstHistory = $history->last();
                                        $creationDetails = $firstHistory ? $firstHistory->details : $order->details;
                                    @endphp

                                    @if($creationDetails && $creationDetails->count() > 0)
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th class="text-center">Đơn vị</th>
                                                    <th class="text-center">Số lượng</th>
                                                    <th class="text-right">Đơn giá</th>
                                                    <th class="text-right">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($creationDetails as $detail)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $detail->product->name ?? 'N/A' }}</strong>
                                                        <br><small class="text-muted">Lô: {{ $detail->code ?? 'N/A' }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <small>{{ $detail->productUnit?->unit?->name ?? 'N/A' }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong>{{ number_format($detail->quantity, 2) }}</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($detail->price, 0, ',', '.') }} đ
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>{{ number_format($detail->quantity * $detail->price, 0, ',', '.') }} đ</strong>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Xác nhận hủy đơn hàng
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="lead">Bạn có chắc chắn muốn hủy đơn hàng <strong class="text-danger">#{{ $order->id }}</strong>?</p>
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle"></i> <strong>Lưu ý:</strong></h6>
                    <ul class="mb-0">
                        <li>Tồn kho sẽ được hoàn trả cho tất cả sản phẩm</li>
                        <li>Đơn hàng sẽ được đánh dấu là đã hủy</li>

                    </ul>
                </div>
                <div class="form-group">
                    <label>Lý do hủy (tùy chọn):</label>
                    <textarea class="form-control" id="cancelReason" rows="3" placeholder="Nhập lý do hủy đơn..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Đóng
                </button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <span class="spinner-border spinner-border-sm d-none" id="cancelSpinner"></span>
                    <i class="fas fa-check"></i> Xác nhận hủy
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Print styles */
    @media print {

        .no-print,
        .btn,
        .breadcrumb,
        .card-header,
        nav,
        .modal,
        .timeline,
        .alert {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
        }

        .table {
            font-size: 11px;
        }

        .badge {
            border: 1px solid #000;
        }

        body {
            font-size: 12px;
        }

        @page {
            margin: 1.5cm;
        }
    }

    /* Timeline styles */
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 25px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .timeline-content {
        padding: 10px 15px;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Badge sizing */
    .badge-lg {
        padding: 0.5em 0.75em;
        font-size: 90%;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 12px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelBtn = document.getElementById('cancelOrderBtn');

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                $('#cancelOrderModal').modal('show');
            });
        }

        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            const spinner = document.getElementById('cancelSpinner');
            const btn = this;
            const reason = document.getElementById('cancelReason').value;

            btn.disabled = true;
            spinner.classList.remove('d-none');

            fetch('/admin/orders/{{ $order->id }}/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        reason: reason
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        $('#cancelOrderModal').modal('hide');

                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                    <strong>Thành công!</strong> ${data.message}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                `;
                        document.querySelector('.content-wrapper .container-fluid').insertBefore(
                            alertDiv,
                            document.querySelector('.content-wrapper .container-fluid').firstChild
                        );

                        // Reload sau 2 giây
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        alert('Lỗi: ' + (data.error || 'Không thể hủy đơn hàng'));
                        btn.disabled = false;
                        spinner.classList.add('d-none');
                    }
                })
                .catch(err => {
                    console.error('Cancel error:', err);
                    alert('Lỗi khi hủy đơn hàng. Vui lòng thử lại.');
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        });
    });
</script>

@endsection