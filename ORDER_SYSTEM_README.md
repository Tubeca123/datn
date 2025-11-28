# Hệ Thống Quản Lý Đơn Hàng - Nhà Thuốc Tú Phương

## Tính Năng

### 1. Tạo Đơn Hàng
- **Tìm Kiếm Thuốc**: Tìm kiếm theo tên hoặc nhà sản xuất (real-time search)
- **Chọn Đơn Vị**: Lựa chọn đơn vị bán (hộp, lọ, viên, v.v.)
- **Nhập Số Lượng**: Nhập số lượng muốn mua
- **Hiển Thị Thông Tin**:
  - Giá bán
  - Tồn kho hiện tại
  - Giá nhập và tỉ lệ quy đổi
- **Quản Lý Giỏ Hàng**: Thêm/xóa sản phẩm từ giỏ
- **Xác Nhận**: Xác nhận và tạo đơn hàng

### 2. Danh Sách Đơn Hàng
- Xem tất cả đơn hàng đã tạo
- Sắp xếp theo ngày tạo
- Phân trang (20 đơn hàng/trang)
- Thông tin khách hàng và tổng tiền

### 3. Chi Tiết Đơn Hàng
- Xem đầy đủ thông tin đơn hàng
- Danh sách sản phẩm trong đơn
- Thông tin khách hàng
- In đơn hàng

## Cấu Trúc Dự Án

```
app/Http/Controllers/admin/
├── OrderController.php          # Xử lý logic đơn hàng

app/Models/
├── Order.php                    # Model đơn hàng
├── OrderDetail.php              # Model chi tiết đơn hàng
├── Product.php                  # Model sản phẩm
├── ProductUnit.php              # Model đơn vị sản phẩm
└── Unit.php                     # Model đơn vị

resources/views/admin/pages/order/
├── create.blade.php             # Giao diện tạo đơn hàng
├── index.blade.php              # Danh sách đơn hàng
└── show.blade.php               # Chi tiết đơn hàng

routes/
└── web.php                      # Định nghĩa routes
```

## Routes

### Công khai
- `GET /register` - Trang đăng ký
- `POST /register` - Lưu đăng ký
- `GET /login` - Trang đăng nhập
- `POST /login` - Xử lý đăng nhập

### Bảo vệ (yêu cầu đăng nhập)

#### Đơn Hàng
- `GET /admin/order` - Danh sách đơn hàng (`order.index`)
- `GET /admin/order/create` - Tạo đơn hàng mới (`order.create`)
- `POST /admin/order` - Lưu đơn hàng (`order.store`)
- `GET /admin/order/{id}` - Chi tiết đơn hàng (`order.show`)
- `GET /admin/order/search?search=...` - Tìm kiếm thuốc (`order.search`)
- `GET /admin/order/stock` - Lấy tồn kho (`order.stock`)

## API Endpoints

### Tìm Kiếm Thuốc
```
GET /admin/order/search?search=tên_thuốc
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Aspirin 500mg",
            "manufacturer": "Công ty A",
            "brand": "Thương hiệu A",
            "category": "Hạ sốt",
            "stock": 100,
            "units": [
                {
                    "id": 1,
                    "unit_name": "Hộp",
                    "unit_code": "BOX",
                    "price_sale": 50000,
                    "price_import": 30000,
                    "quantity_per_unit": 10
                }
            ]
        }
    ]
}
```

### Lấy Tồn Kho
```
GET /admin/order/stock?product_id=1&product_unit_id=1
```

**Response:**
```json
{
    "success": true,
    "total_stock_base": 100,
    "stock_by_unit": {
        "units": 10,
        "remainder_base": 0
    }
}
```

### Tạo Đơn Hàng
```
POST /admin/order
Content-Type: application/json

{
    "items": [
        {
            "product_id": 1,
            "product_unit_id": 1,
            "quantity": 5
        }
    ]
}
```

**Response:**
```json
{
    "success": true,
    "message": "Tạo đơn hàng thành công!",
    "order_id": 1
}
```

## Cách Sử Dụng

### 1. Tạo Đơn Hàng Mới
1. Đăng nhập vào hệ thống
2. Vào menu "Đơn hàng" → "Tạo đơn hàng mới"
3. Nhập tên thuốc trong ô tìm kiếm
4. Chọn một thuốc từ kết quả
5. Chọn đơn vị muốn bán
6. Nhập số lượng
7. Xem giá và tồn kho
8. Nhấn "Thêm Vào Giỏ"
9. Lặp lại các bước 3-8 để thêm nhiều sản phẩm
10. Nhấn "Tạo Đơn Hàng"
11. Xác nhận trong modal
12. Đơn hàng được tạo thành công!

### 2. Xem Danh Sách Đơn Hàng
1. Vào menu "Đơn hàng" → "Danh sách đơn hàng"
2. Xem danh sách tất cả các đơn hàng
3. Nhấn "Xem" để xem chi tiết

### 3. Xem Chi Tiết Đơn Hàng
1. Từ danh sách, nhấn "Xem" trên đơn hàng muốn xem
2. Xem toàn bộ thông tin: khách hàng, sản phẩm, giá
3. Nhấn "In Đơn Hàng" để in (Ctrl+P hoặc File → Print)

## Tính Năng Nổi Bật

### ✅ Tìm Kiếm Real-time
- Tìm kiếm tự động khi người dùng gõ
- Kết quả hiển thị ngay lập tức
- Hỗ trợ tìm kiếm theo tên hoặc nhà sản xuất

### ✅ Quản Lý Tồn Kho
- Kiểm tra tồn kho theo từng đơn vị
- Hiển thị số lượng còn lại
- Cảnh báo nếu không đủ tồn kho
- Tự động giảm tồn kho khi tạo đơn hàng

### ✅ Phương Pháp FIFO
- Sử dụng phương pháp FIFO (First In First Out) để giảm tồn kho
- Đảm bảo hàng cũ được bán trước

### ✅ Giao Diện Thân Thiện
- Giao diện AdminLTE 3 hiện đại
- Responsive với các thiết bị khác nhau
- UX tốt với feedback rõ ràng
- Modal xác nhận trước khi tạo đơn hàng

### ✅ In Đơn Hàng
- Hỗ trợ in đơn hàng trực tiếp từ giao diện
- CSS in được tối ưu hóa

## Yêu Cầu Hệ Thống

- Laravel 10+
- PHP 8.1+
- MySQL 5.7+
- Bootstrap 4+
- AdminLTE 3+

## Database Schema

### Bảng: orders
```sql
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total DECIMAL(10, 2),
    create_date DATETIME,
    create_by INT,
    update_date DATETIME,
    update_by INT,
    isactive TINYINT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Bảng: order_detail
```sql
CREATE TABLE order_detail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT,
    isactive TINYINT,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES product(id)
);
```

## Lỗi Thường Gặp

### 1. "Không đủ tồn kho"
- Nguyên nhân: Số lượng muốn bán vượt quá tồn kho
- Giải pháp: Giảm số lượng hoặc nhập thêm hàng

### 2. "Thông tin đăng nhập không hợp lệ"
- Nguyên nhân: Email/phone hoặc mật khẩu sai
- Giải pháp: Kiểm tra lại thông tin đăng nhập

### 3. Tìm kiếm không có kết quả
- Nguyên nhân: Thuốc không hoạt động hoặc chưa tồn tại
- Giải pháp: Kiểm tra tên thuốc hoặc thêm thuốc mới

## Phát Triển Tiếp Theo

- [ ] Thêm tính năng chiết khấu
- [ ] Thêm tính năng xuất HĐ (invoice)
- [ ] Thêm tính năng đặt hàng trước
- [ ] Thêm tính năng quản lý thanh toán
- [ ] Thêm tính năng báo cáo doanh số
- [ ] Tích hợp SMS/Email thông báo

## Hỗ Trợ

Nếu gặp vấn đề, vui lòng liên hệ:
- Email: support@nhathuotuphương.com
- Điện thoại: 0123-456-789
