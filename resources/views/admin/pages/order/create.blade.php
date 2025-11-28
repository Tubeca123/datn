@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container mt-4">

        <h3>Tạo Đơn Thuốc</h3>

        <!-- Search Product -->
        <div class="form-group mt-3">
            <label>Tìm thuốc:</label>
            <input type="text" id="search" class="form-control" placeholder="Nhập tên thuốc...">
            <div id="searchResult" class="list-group mt-1" style="display:none"></div>
        </div>

        <!-- Product detail selection -->
        <div id="productArea" class="mt-4" style="display:none">
            <h5 id="productName"></h5>

            <label>Đơn vị thuốc:</label>
            <select id="unitSelect" class="form-control"></select>

            <div class="row mt-3">
                <div class="col-md-4">
                    <label>Số lượng:</label>
                    <input type="number" id="quantity" class="form-control" min="1" value="1">
                </div>

                <div class="col-md-4">
                    <label>Giá bán:</label>
                    <input type="text" id="price" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label>Tồn kho:</label>
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

        <button id="saveOrder" class="btn btn-success mt-3">Tạo đơn thuốc</button>

    </div>
</div>
<script>
    let selectedProduct = null;
    let selectedUnits = [];

    $("#search").on("keyup", function() {
        let q = $(this).val();
        if (q.length < 2) {
            $("#searchResult").hide();
            return;
        }

        $.get("/admin/api/products/search?q=" + q, function(data) {
            let html = "";
            data.forEach(p => {
                html += `<a href="#" class="list-group-item list-group-item-action" 
                        onclick="selectProduct(${p.id}, '${p.name}')">${p.name}</a>`;
            });

            $("#searchResult").html(html).show();
        });
    });

    function selectProduct(id, name) {
        selectedProduct = id;
        $("#productName").text(name);
        $("#searchResult").hide();
        $("#productArea").show();

        $.get("/admin/api/product/" + id + "/units", function(units) {
            selectedUnits = units;
            let html = "";

            units.forEach(u => {
                html += `<option value="${u.id}" data-price="${u.price}" 
                     data-stock="${u.stock_units}">${u.unit_name}</option>`;
            });

            $("#unitSelect").html(html).change();
        });
    }

    $("#unitSelect").on("change", function() {
        let u = selectedUnits.find(x => x.id == this.value);
        $("#price").val(u.price);
        $("#stock").val(u.stock_units + " " + u.unit_name);
    });

    $("#addBtn").on("click", function() {
        let qty = $("#quantity").val();
        let unit = $("#unitSelect option:selected").text();
        let price = $("#price").val();
        let total = qty * price;

        let row = `
        <tr>
            <td>${$("#productName").text()}</td>
            <td>${unit}</td>
            <td>${qty}</td>
            <td>${price}</td>
            <td>${total}</td>
            <td><button class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
        </tr>
    `;

        $("#orderTable tbody").append(row);
        calcTotal();
    });

    function removeRow(btn) {
        $(btn).closest("tr").remove();
        calcTotal();
    }

    function calcTotal() {
        let sum = 0;
        $("#orderTable tbody tr").each(function() {
            sum += parseFloat($(this).find("td:eq(4)").text());
        });
        $("#total").text(sum);
    }

    $("#saveOrder").on("click", function() {
        let items = [];

        $("#orderTable tbody tr").each(function() {
            items.push({
                product_id: selectedProduct,
                unit_id: $("#unitSelect").val(),
                quantity: $(this).find("td:eq(2)").text(),
                price: $(this).find("td:eq(3)").text()
            });
        });

        $.ajax({
            url: "/orders/store",
            method: "POST",
            data: {
                items: items,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                alert("Tạo đơn thành công! Mã đơn: " + res.order_id);
            }
        });
    });
</script>

@endsection