@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Sửa Đơn Hàng #{{ $order->id }}</h3>
            <div>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- Alert messages -->
        <div id="alertBox" class="alert" style="display:none" role="alert"></div>

        <!-- Thông tin đơn hàng -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Thông tin cơ bản</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Khách hàng:</strong> {{ $order->user->name ?? 'Khách vãng lai' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Ngày tạo:</strong> {{ \Carbon\Carbon::parse($order->create_date)->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-4">
                        <strong>Tổng tiền hiện tại:</strong> 
                        <span class="text-success font-weight-bold">{{ number_format($order->total, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm hiện tại -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách sản phẩm</h5>
                <button type="button" class="btn btn-success btn-sm" id="addNewProductBtn">
                    <i class="fas fa-plus"></i> Thêm sản phẩm
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="productsTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Sản phẩm</th>
                                <th width="120">Lô thuốc</th>
                                <th width="120">Đơn vị</th>
                                <th width="100">Số lượng</th>
                                <th width="120">Đơn giá</th>
                                <th width="130">Tổng</th>
                                <th width="80">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="productsList">
                            @foreach($order->details as $index => $detail)
                                @if($detail->isactive == 1)
                                <tr data-detail-id="{{ $detail->id }}" data-is-existing="true">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $detail->product->name }}</strong>
                                        <input type="hidden" class="product-id" value="{{ $detail->product_id }}">
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm inventory-select" data-product-id="{{ $detail->product_id }}" data-unit-id="{{ $detail->product_unit_id }}">
                                            <option value="{{ $detail->inventory_id }}" selected>
                                                {{ $detail->code ?? 'N/A' }}
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm unit-select" data-product-id="{{ $detail->product_id }}">
                                            <option value="{{ $detail->product_unit_id }}" 
                                                data-price="{{ $detail->price }}"
                                                selected>
                                                {{ $detail->productUnit->unit->name ?? 'N/A' }}
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm quantity-input" 
                                            value="{{ $detail->quantity }}" min="0.01" step="0.01">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm price-input" 
                                            value="{{ $detail->price }}" readonly>
                                    </td>
                                    <td class="text-right total-cell">
                                        {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="6" class="text-right">Tổng cộng:</th>
                                <th class="text-right" id="grandTotal">0 đ</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Nút lưu -->
        <div class="text-right mb-4">
            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
            <button type="button" class="btn btn-success" id="saveOrderBtn">
                <span id="saveSpinner" class="spinner-border spinner-border-sm" style="display:none"></span>
                <i class="fas fa-save"></i> Lưu thay đổi
            </button>
        </div>

    </div>
</div>

<!-- Modal thêm sản phẩm -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Thêm sản phẩm mới</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tìm sản phẩm -->
                <div class="form-group">
                    <label>Tìm thuốc:</label>
                    <input type="text" id="modalSearchProduct" class="form-control" placeholder="Nhập tên thuốc...">
                    <div id="modalSearchResult" class="list-group mt-1" style="display:none"></div>
                </div>

                <!-- Chi tiết sản phẩm -->
                <div id="modalProductDetail" style="display:none">
                    <h5 id="modalProductName"></h5>
                    <input type="hidden" id="modalProductId">

                    <div class="row">
                        <div class="col-md-4">
                            <label>Đơn vị:</label>
                            <select id="modalUnitSelect" class="form-control"></select>
                        </div>
                        <div class="col-md-4">
                            <label>Lô thuốc:</label>
                            <select id="modalInventorySelect" class="form-control"></select>
                        </div>
                        <div class="col-md-4">
                            <label>Số lượng:</label>
                            <input type="number" id="modalQuantity" class="form-control" value="1" min="0.01" step="0.01">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Giá bán:</label>
                            <input type="text" id="modalPrice" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Tồn kho:</label>
                            <input type="text" id="modalStock" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="addToOrderBtn">Thêm vào đơn</button>
            </div>
        </div>
    </div>
</div>

<script>
let orderData = {
    items: []
};

const el = id => document.getElementById(id);

function showAlert(message, type = 'info') {
    const alertBox = el('alertBox');
    alertBox.className = `alert alert-${type}`;
    alertBox.textContent = message;
    alertBox.style.display = 'block';
    setTimeout(() => alertBox.style.display = 'none', 5000);
}

// Load dữ liệu ban đầu
function loadExistingData() {
    const rows = document.querySelectorAll('#productsList tr[data-is-existing="true"]');
    rows.forEach(row => {
        const detailId = row.getAttribute('data-detail-id');
        const productId = row.querySelector('.product-id').value;
        const unitId = row.querySelector('.unit-select').value;
        const inventoryId = row.querySelector('.inventory-select').value;
        const quantity = parseFloat(row.querySelector('.quantity-input').value);
        const price = parseFloat(row.querySelector('.price-input').value);

        orderData.items.push({
            detail_id: detailId,
            product_id: productId,
            unit_id: unitId,
            inventory_id: inventoryId,
            quantity: quantity,
            price: price
        });
    });
    
    // Load units và inventories cho mỗi row
    rows.forEach(row => {
        const productId = row.querySelector('.product-id').value;
        const unitSelect = row.querySelector('.unit-select');
        const inventorySelect = row.querySelector('.inventory-select');
        
        loadUnitsForRow(productId, unitSelect);
        loadInventoriesForRow(productId, unitSelect.value, inventorySelect);
    });
    
    calculateTotal();
}

// Load đơn vị cho row
function loadUnitsForRow(productId, selectElement) {
    fetch(`/admin/api/product/${productId}/units`)
        .then(r => r.json())
        .then(units => {
            const currentValue = selectElement.value;
            selectElement.innerHTML = '';
            units.forEach(u => {
                const opt = document.createElement('option');
                opt.value = u.id;
                opt.textContent = u.unit_name;
                opt.setAttribute('data-price', u.price);
                if (u.id == currentValue) opt.selected = true;
                selectElement.appendChild(opt);
            });
        });
}

// Load lô thuốc cho row
function loadInventoriesForRow(productId, unitId, selectElement) {
    fetch(`/admin/api/product/${productId}/unit/${unitId}/inventories`)
        .then(r => r.json())
        .then(inventories => {
            const currentValue = selectElement.value;
            selectElement.innerHTML = '';
            inventories.forEach(inv => {
                const opt = document.createElement('option');
                opt.value = inv.id;
                opt.textContent = `${inv.code} - Còn: ${inv.stock_units}`;
                opt.setAttribute('data-stock', inv.stock_base);
                if (inv.id == currentValue) opt.selected = true;
                selectElement.appendChild(opt);
            });
        });
}

// Thay đổi đơn vị
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('unit-select')) {
        const row = e.target.closest('tr');
        const productId = e.target.getAttribute('data-product-id');
        const newPrice = e.target.selectedOptions[0].getAttribute('data-price');
        
        row.querySelector('.price-input').value = newPrice;
        
        // Reload inventories
        const inventorySelect = row.querySelector('.inventory-select');
        loadInventoriesForRow(productId, e.target.value, inventorySelect);
        
        updateRowTotal(row);
    }
});

// Thay đổi số lượng
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantity-input')) {
        const row = e.target.closest('tr');
        updateRowTotal(row);
    }
});

function updateRowTotal(row) {
    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = qty * price;
    row.querySelector('.total-cell').textContent = formatCurrency(total);
    calculateTotal();
}

function calculateTotal() {
    let sum = 0;
    document.querySelectorAll('#productsList tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity-input')?.value) || 0;
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
        sum += qty * price;
    });
    el('grandTotal').textContent = formatCurrency(sum) + ' đ';
}

// Xóa row
document.addEventListener('click', function(e) {
    if (e.target.closest('.delete-row-btn')) {
        const row = e.target.closest('tr');
        if (confirm('Xóa sản phẩm này khỏi đơn?')) {
            row.remove();
            calculateTotal();
            renumberRows();
        }
    }
});

function renumberRows() {
    document.querySelectorAll('#productsList tr').forEach((row, i) => {
        row.querySelector('td:first-child').textContent = i + 1;
    });
}

// === THÊM SẢN PHẨM MỚI ===
el('addNewProductBtn').addEventListener('click', function() {
    $('#addProductModal').modal('show');
});

let modalSearchTimer = null;
el('modalSearchProduct').addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(modalSearchTimer);
    
    if (q.length < 2) {
        el('modalSearchResult').style.display = 'none';
        return;
    }

    modalSearchTimer = setTimeout(() => {
        fetch('/admin/api/products/search?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                let html = '';
                data.forEach(p => {
                    html += `<a href="#" class="list-group-item list-group-item-action modal-product-item" data-id="${p.id}" data-name="${p.name}">${p.name}</a>`;
                });
                el('modalSearchResult').innerHTML = html;
                el('modalSearchResult').style.display = data.length ? 'block' : 'none';
            });
    }, 300);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-product-item')) {
        e.preventDefault();
        const id = e.target.getAttribute('data-id');
        const name = e.target.getAttribute('data-name');
        selectModalProduct(id, name);
    }
});

function selectModalProduct(id, name) {
    el('modalProductId').value = id;
    el('modalProductName').textContent = name;
    el('modalSearchResult').style.display = 'none';
    el('modalProductDetail').style.display = 'block';

    // Load units
    fetch(`/admin/api/product/${id}/units`)
        .then(r => r.json())
        .then(units => {
            el('modalUnitSelect').innerHTML = '';
            units.forEach(u => {
                const opt = document.createElement('option');
                opt.value = u.id;
                opt.textContent = u.unit_name;
                opt.setAttribute('data-price', u.price);
                el('modalUnitSelect').appendChild(opt);
            });
            el('modalUnitSelect').dispatchEvent(new Event('change'));
        });
}

el('modalUnitSelect').addEventListener('change', function() {
    const productId = el('modalProductId').value;
    const unitId = this.value;
    const price = this.selectedOptions[0].getAttribute('data-price');
    
    el('modalPrice').value = formatCurrency(price);
    
    // Load inventories
    fetch(`/admin/api/product/${productId}/unit/${unitId}/inventories`)
        .then(r => r.json())
        .then(inventories => {
            el('modalInventorySelect').innerHTML = '';
            inventories.forEach(inv => {
                const opt = document.createElement('option');
                opt.value = inv.id;
                opt.textContent = `${inv.code} (Lô: ${inv.id}) - Còn: ${inv.stock_units}`;
                opt.setAttribute('data-stock', inv.stock_base);
                el('modalInventorySelect').appendChild(opt);
            });
            if (inventories.length > 0) {
                el('modalStock').value = inventories[0].stock_units;
            }
        });
});

el('modalInventorySelect').addEventListener('change', function() {
    const stock = this.selectedOptions[0].getAttribute('data-stock');
    el('modalStock').value = stock + ' viên';
});

el('addToOrderBtn').addEventListener('click', function() {
    const productId = el('modalProductId').value;
    const productName = el('modalProductName').textContent;
    const unitId = el('modalUnitSelect').value;
    const unitName = el('modalUnitSelect').selectedOptions[0].textContent;
    const inventoryId = el('modalInventorySelect').value;
    const inventoryCode = el('modalInventorySelect').selectedOptions[0].textContent.split(' ')[0];
    const quantity = parseFloat(el('modalQuantity').value);
    const price = parseFloat(el('modalUnitSelect').selectedOptions[0].getAttribute('data-price'));
    const total = quantity * price;

    // Thêm row mới
    const tbody = el('productsList');
    const tr = document.createElement('tr');
    tr.setAttribute('data-is-existing', 'false');
    tr.innerHTML = `
        <td class="text-center">${tbody.children.length + 1}</td>
        <td>
            <strong>${productName}</strong>
            <input type="hidden" class="product-id" value="${productId}">
        </td>
        <td>
            <select class="form-control form-control-sm inventory-select" data-product-id="${productId}" data-unit-id="${unitId}">
                <option value="${inventoryId}" selected>${inventoryCode}</option>
            </select>
        </td>
        <td>
            <select class="form-control form-control-sm unit-select" data-product-id="${productId}">
                <option value="${unitId}" data-price="${price}" selected>${unitName}</option>
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm quantity-input" value="${quantity}" min="0.01" step="0.01">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm price-input" value="${price}" readonly>
        </td>
        <td class="text-right total-cell">${formatCurrency(total)}</td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(tr);
    loadUnitsForRow(productId, tr.querySelector('.unit-select'));
    loadInventoriesForRow(productId, unitId, tr.querySelector('.inventory-select'));
    
    calculateTotal();
    $('#addProductModal').modal('hide');
    
    // Reset modal
    el('modalSearchProduct').value = '';
    el('modalProductDetail').style.display = 'none';
    el('modalQuantity').value = '1';
});

// LƯU ĐƠN HÀNG
el('saveOrderBtn').addEventListener('click', function() {
    const items = [];
    
    document.querySelectorAll('#productsList tr').forEach(row => {
        const detailId = row.getAttribute('data-detail-id');
        items.push({
            detail_id: detailId || null,
            product_id: parseInt(row.querySelector('.product-id').value),
            unit_id: parseInt(row.querySelector('.unit-select').value),
            inventory_id: parseInt(row.querySelector('.inventory-select').value),
            quantity: parseFloat(row.querySelector('.quantity-input').value),
            price: parseFloat(row.querySelector('.price-input').value)
        });
    });

    if (items.length === 0) {
        showAlert('Đơn hàng phải có ít nhất 1 sản phẩm', 'warning');
        return;
    }

    this.disabled = true;
    el('saveSpinner').style.display = 'inline-block';

    fetch('/admin/edit_order/{{ $order->id }}/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ items: items })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showAlert(res.message, 'success');
            setTimeout(() => {
                window.location.href = '/admin/orders/{{ $order->id }}';
            }, 1500);
        } else {
            showAlert('Lỗi: ' + (res.error || 'Không thể cập nhật'), 'danger');
            el('saveOrderBtn').disabled = false;
            el('saveSpinner').style.display = 'none';
        }
    })
    .catch(err => {
        console.error(err);
        showAlert('Lỗi khi lưu đơn hàng', 'danger');
        el('saveOrderBtn').disabled = false;
        el('saveSpinner').style.display = 'none';
    });
});

function formatCurrency(num) {
    return new Intl.NumberFormat('vi-VN').format(num);
}

// Init
loadExistingData();
</script>

@endsection