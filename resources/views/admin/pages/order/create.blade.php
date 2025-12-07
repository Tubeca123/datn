@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container mt-4">

        <h3>Tạo Đơn Thuốc</h3>

        <!-- Alert for messages -->
        <div id="alertBox" class="alert" style="display:none" role="alert"></div>

        <!-- Search Product -->
        <div class="form-group mt-3">
            <label>Tìm thuốc:</label>
            <div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="Nhập tên thuốc...">
                <div class="input-group-append">
                    <span id="searchSpinner" class="input-group-text" style="display:none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </div>
            </div>
            <div id="searchResult" class="list-group mt-1" style="display:none" role="listbox"></div>
        </div>
        <div class="mt-4">
            <label>Số điện thoại khách:</label>
            <input type="text" id="customerPhone" class="form-control" placeholder="Nhập SĐT khách hàng...">

            <small id="customerInfo" class="text-primary mt-1" style="font-weight:bold;"></small>
        </div>
        <!-- Product detail selection -->
        <div id="productArea" class="mt-4" style="display:none">
            <h5 id="productName"></h5>
            <input type="hidden" id="currentProductId">

            <div class="row">
                <div class="col-md-6">
                    <label>Đơn vị thuốc:</label>
                    <select id="unitSelect" class="form-control"></select>
                </div>

                <div class="col-md-6">
                    <label>Chọn lô thuốc:</label>
                    <select id="inventorySelect" class="form-control"></select>
                    <small id="inventoryInfo" class="text-muted"></small>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <label>Số lượng:</label>
                    <input type="number" id="quantity" class="form-control" value="1" min="0.01" step="0.01">
                </div>

                <div class="col-md-4">
                    <label>Giá bán:</label>
                    <input type="text" id="price" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label>Tồn kho của lô:</label>
                    <input type="text" id="stock" class="form-control" readonly>
                </div>
            </div>

            <button id="addBtn" class="btn btn-primary mt-3">Thêm vào đơn</button>


        </div>

        <hr>

        <!-- Order Table -->
        <h4>Chi tiết Đơn Thuốc</h4>
        <table class="table table-bordered" id="orderTable">
            <thead>
                <tr>
                    <th>Thuốc</th>
                    <th>Đơn vị</th>
                    <th>SL</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <h4 class="mt-3">Tổng tiền: <span id="total">0</span> VNĐ</h4>

        <button id="saveOrder" class="btn btn-success mt-3">
            <span id="saveSpinner" class="spinner-border spinner-border-sm" role="status" style="display:none"></span>
            Tạo đơn thuốc
        </button>

    </div>
</div>

<script>
    let selectedProduct = null;
    let selectedUnits = [];
    let orderItems = [];

    const el = id => document.getElementById(id);

    let searchTimer = null;
    let currentFocus = -1;

    function showAlert(message, type = 'info') {
        const alertBox = el('alertBox');
        alertBox.className = `alert alert-${type}`;
        alertBox.textContent = message;
        alertBox.style.display = 'block';
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000);
    }

    function showSpinner() {
        el('searchSpinner').style.display = 'inline-block';
    }

    function hideSpinner() {
        el('searchSpinner').style.display = 'none';
    }

    el('search').addEventListener('input', function() {
        const q = this.value.trim();
        clearTimeout(searchTimer);
        if (q.length < 2) {
            el('searchResult').style.display = 'none';
            return;
        }

        searchTimer = setTimeout(() => {
            showSpinner();
            fetch('/admin/api/products/search?q=' + encodeURIComponent(q), {
                    credentials: 'same-origin'
                })
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    data.forEach((p, idx) => {
                        html += `<a href="#" class="list-group-item list-group-item-action" data-id="${p.id}" data-name="${escapeHtml(p.name)}" data-idx="${idx}" role="option">${escapeHtml(p.name)}</a>`;
                    });

                    el('searchResult').innerHTML = html;
                    el('searchResult').style.display = data.length ? 'block' : 'none';
                    currentFocus = -1;

                    Array.from(el('searchResult').querySelectorAll('a[data-id]')).forEach((a, i) => {
                        a.addEventListener('click', function(ev) {
                            ev.preventDefault();
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name');
                            selectProduct(id, name);
                        });
                        a.addEventListener('mousemove', function() {
                            setActiveIndex(i);
                        });
                    });
                })
                .catch(err => {
                    console.error('Search error', err);
                    showAlert('Lỗi tìm kiếm sản phẩm', 'danger');
                })
                .finally(() => {
                    hideSpinner();
                });
        }, 300);
    });

    el('search').addEventListener('keydown', function(e) {
        const items = el('searchResult').querySelectorAll('a[data-id]');
        if (el('searchResult').style.display === 'none' || items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentFocus = (currentFocus + 1) % items.length;
            setActive(items[currentFocus]);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentFocus = (currentFocus - 1 + items.length) % items.length;
            setActive(items[currentFocus]);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus >= 0 && items[currentFocus]) {
                items[currentFocus].click();
            }
        } else if (e.key === 'Escape') {
            el('searchResult').style.display = 'none';
        }
    });

    function setActive(node) {
        const items = el('searchResult').querySelectorAll('a[data-id]');
        items.forEach(i => i.classList.remove('active'));
        if (!node) return;
        node.classList.add('active');
        node.scrollIntoView({
            block: 'nearest'
        });
    }

    function setActiveIndex(i) {
        const items = el('searchResult').querySelectorAll('a[data-id]');
        currentFocus = i;
        setActive(items[i]);
    }

    function selectProduct(id, name) {
        selectedProduct = parseInt(id);
        el('currentProductId').value = id;
        el('productName').textContent = name;
        el('searchResult').style.display = 'none';
        el('productArea').style.display = 'block';

        fetch('/admin/api/product/' + selectedProduct + '/units', {
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(units => {
                selectedUnits = units;
                let html = '';
                units.forEach(u => {
                    html += `<option value="${u.id}" data-price="${u.price}" data-stock="${u.stock_units}" data-remainder="${u.remainder}" data-qty-per-unit="${u.quantity_per_unit}" data-unit-name="${escapeHtml(u.unit_name)}">${escapeHtml(u.unit_name)}</option>`;
                });
                el('unitSelect').innerHTML = html;
                el('unitSelect').dispatchEvent(new Event('change'));
            })
            .catch(err => {
                console.error('Units error', err);
                showAlert('Lỗi tải đơn vị sản phẩm', 'danger');
            });
    }

    el('unitSelect').addEventListener('change', function() {
        // Khi đổi unit, load danh sách lô
        const productId = parseInt(el('currentProductId').value);
        const unitId = parseInt(this.value);

        if (!productId || !unitId) return;

        fetch(`/admin/api/product/${productId}/unit/${unitId}/inventories`, {
            credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(inventories => {
            let html = '<option value="">-- Chọn lô --</option>';
            inventories.forEach(inv => {
                const label = `Lô ${inv.code} - Hạn: ${inv.date_end} (${inv.stock_units} cái)`;
                html += `<option value="${inv.id}" data-stock-units="${inv.stock_units}" data-stock-base="${inv.stock_base}" data-remainder="${inv.remainder}">${label}</option>`;
            });
            el('inventorySelect').innerHTML = html;
            el('inventorySelect').value = '';
            updateStockDisplay();
        })
        .catch(err => {
            console.error('Inventories error', err);
            showAlert('Lỗi tải danh sách lô', 'danger');
            el('inventorySelect').innerHTML = '<option value="">-- Lỗi tải lô --</option>';
        });
    });

    el('inventorySelect').addEventListener('change', function() {
        updateStockDisplay();
    });

    function updateStockDisplay() {
        const unit = selectedUnits.find(u => u.id == el('unitSelect').value);
        const inventoryOption = el('inventorySelect').selectedOptions[0];

        if (!unit || !inventoryOption || !inventoryOption.value) {
            el('price').value = '';
            el('stock').value = '';
            el('inventoryInfo').textContent = '';
            return;
        }

        const stockUnits = parseInt(inventoryOption.dataset.stockUnits) || 0;
        const stockBase = parseInt(inventoryOption.dataset.stockBase) || 0;
        const remainder = parseInt(inventoryOption.dataset.remainder) || 0;

        el('price').value = formatCurrency(unit.price);
        el('stock').value = `${stockUnits} ${unit.unit_name}`;
        el('inventoryInfo').textContent = `(Còn ${stockBase} viên, dư ${remainder} viên)`;
    }

    el('addBtn').addEventListener('click', function() {
        const qty = parseFloat(el('quantity').value) || 0;
        const unitId = parseInt(el('unitSelect').value);
        const inventoryId = parseInt(el('inventorySelect').value);
        const unit = selectedUnits.find(x => x.id == unitId);
        const unitText = el('unitSelect').selectedOptions[0]?.textContent || '';
        const inventoryCode = el('inventorySelect').selectedOptions[0]?.textContent || '';
        const price = parseFloat(unit.price) || 0;
        const productId = parseInt(el('currentProductId').value);
        const productName = el('productName').textContent;

        if (qty <= 0) {
            showAlert('Số lượng phải lớn hơn 0', 'warning');
            return;
        }

        if (!inventoryId) {
            showAlert('Vui lòng chọn lô thuốc', 'warning');
            return;
        }

        // Kiểm tra tồn kho của lô được chọn
        const inventoryOption = el('inventorySelect').selectedOptions[0];
        const stockUnits = parseInt(inventoryOption.dataset.stockUnits) || 0;

        if (qty > stockUnits) {
            showAlert(
                `Lô này không đủ!\n` +
                `Yêu cầu: ${qty} ${unitText}\n` +
                `Lô còn: ${stockUnits} ${unitText}`,
                'danger'
            );
            return;
        }

        const total = qty * price;

        
        const itemData = {
            product_id: productId,
            unit_id: unitId,
            inventory_id: inventoryId,  
            quantity: qty,
            price: price,
            product_name: productName,
            unit_name: unitText,
            inventory_code: inventoryCode.split(' - ')[0],  
            total: total,
            quantity_per_unit: unit.quantity_per_unit
        };
        orderItems.push(itemData);

        // Tạo row trong bảng
        const tr = document.createElement('tr');
        const itemIndex = orderItems.length - 1;
        tr.setAttribute('data-item-index', itemIndex);
        tr.innerHTML = `
            <td>${escapeHtml(productName)}</td>
            <td>${escapeHtml(inventoryCode.split(' - ')[0])}<br><small class="text-muted">${escapeHtml(unitText)}</small></td>
            <td>${qty}</td>
            <td>${formatCurrency(price)}</td>
            <td>${formatCurrency(total)}</td>
            <td><button class="btn btn-danger btn-sm removeBtn">Xóa</button></td>
        `;

        const removeBtn = tr.querySelector('.removeBtn');
        removeBtn.addEventListener('click', function() {
            const idx = parseInt(tr.getAttribute('data-item-index'));
            orderItems.splice(idx, 1);
            tr.remove();

            // Cập nhật lại index cho các row còn lại
            Array.from(el('orderTable').querySelector('tbody').children).forEach((row, i) => {
                row.setAttribute('data-item-index', i);
            });

            calcTotal();
        });

        el('orderTable').querySelector('tbody').appendChild(tr);
        calcTotal();

        // Reset form
        el('quantity').value = '1';
        el('inventorySelect').value = '';
        showAlert(`Đã thêm ${qty} ${unitText} từ lô ${inventoryCode.split(' - ')[0]} vào đơn`, 'success');
    });

    function calcTotal() {
        const sum = orderItems.reduce((acc, item) => acc + item.total, 0);
        el('total').textContent = formatCurrency(sum);
    }

    el('saveOrder').addEventListener('click', function() {
        if (orderItems.length === 0) {
            showAlert('Đơn hàng trống', 'warning');
            return;
        }

        // Disable button và hiện spinner
        this.disabled = true;
        el('saveSpinner').style.display = 'inline-block';

        fetch('/admin/orders/store', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items: orderItems,
                    customer_id: window.customer_id || null
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showAlert(`Tạo đơn thành công! Mã: ${res.order_id}`, 'success');
                    // Reset toàn bộ
                    orderItems = [];
                    tempStockReductions = {};
                    el('orderTable').querySelector('tbody').innerHTML = '';
                    calcTotal();
                    el('productArea').style.display = 'none';
                    el('search').value = '';
                    selectedProduct = null;
                    selectedUnits = [];
                } else {
                    showAlert('Lỗi: ' + (res.error || res.message || 'Không tạo được đơn'), 'danger');
                }
            })
            .catch(err => {
                console.error('Store error', err);
                showAlert('Lỗi khi tạo đơn', 'danger');
            })
            .finally(() => {
                el('saveOrder').disabled = false;
                el('saveSpinner').style.display = 'none';
            });
    });

    el('customerPhone').addEventListener('input', function() {
        const phone = this.value.trim();
        const info = el('customerInfo');

        if (phone.length < 8) {
            info.textContent = "";
            window.customer_id = null;
            return;
        }

        fetch('/admin/api/customer?phone=' + encodeURIComponent(phone), {
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.id) {
                    window.customer_id = data.id;
                    info.textContent = `Khách: ${data.name} `;
                    info.classList.remove("text-danger");
                    info.classList.add("text-primary");
                } else {
                    info.textContent = "Không tìm thấy khách hàng!";
                    info.classList.remove("text-primary");
                    info.classList.add("text-danger");
                    window.customer_id = null;
                }
            })
            .catch(err => {
                console.error(err);
                info.textContent = "Lỗi tìm khách!";
                info.classList.add("text-danger");
            });
    });

    function formatCurrency(num) {
        return new Intl.NumberFormat('vi-VN').format(num);
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>

@endsection