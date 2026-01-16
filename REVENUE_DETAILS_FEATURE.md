# Tính Năng Chi Tiết Doanh Thu & Lợi Nhuận

## 📋 Tổng Quan
Thêm tính năng chi tiết doanh thu và lợi nhuận khi click vào thẻ **"Tổng Doanh Thu"** trên dashboard.

## ✨ Tính Năng

### 1. **Clickable Revenue Box**
- Thẻ "Tổng Doanh Thu" trên dashboard bây giờ có thể click để xem chi tiết
- Hiển thị loading placeholder khi navigate
- Chuỗi query string bao gồm start_date và end_date để maintain filter

### 2. **Trang Chi Tiết Doanh Thu**
**URL:** `/admin/dashboard/revenue-details`
**Route Name:** `admin.dashboard.revenue-details`

#### Thống Kê Tổng Quan (4 Boxes):
1. **Tổng Doanh Thu** - SUM(quantity × price) cho tất cả order
2. **Tổng Giá Vốn** - SUM(quantity × price_import) từ ProductUnit
3. **Tổng Lợi Nhuận** - Doanh Thu - Giá Vốn
4. **Tỷ Suất Lợi Nhuận** - (Lợi Nhuận / Doanh Thu) × 100%

#### 2 Tabs Xem Chi Tiết:

**Tab 1: Chi Tiết Sản Phẩm**
Bảng hiển thị (pagination: 20 items/page):
- # (STT)
- Tên Sản Phẩm
- Danh Mục (badge)
- Số Lượng (badge)
- Giá Nhập/Đơn Vị (từ ProductUnit.price_import)
- Giá Bán/Đơn Vị (từ ProductUnit.price_sale)
- Doanh Thu (tính toán)
- Giá Vốn (tính toán)
- Lợi Nhuận (tính toán)
- Tỷ Suất Lợi Nhuận (%) - color coding:
  - Verde (Lợi nhuận cao)
  - Đỏ (Lỗ)
  - Xám (Không lợi nhuận)

**Tab 2: Chi Tiết Danh Mục**
Bảng hiển thị:
- # (STT)
- Tên Danh Mục
- Số Sản Phẩm (badge)
- Tổng Số Lượng (badge)
- Tổng Doanh Thu
- Tổng Giá Vốn
- Tổng Lợi Nhuận
- Tỷ Suất Lợi Nhuận (%)

### 3. **Bộ Lọc Thời Gian**
- Từ ngày - Đến ngày
- Nút "Lọc"
- Nút "Đặt lại" (reset về 30 ngày gần nhất)
- Nút "Quay lại Dashboard"

### 4. **Công Thức Tính Toán**

```
Doanh Thu = SUM(order_detail.quantity × order_detail.price)
Giá Vốn = SUM(order_detail.quantity × product_unit.price_import)
Lợi Nhuận = Doanh Thu - Giá Vốn
Tỷ Suất Lợi Nhuận = (Lợi Nhuận / Doanh Thu) × 100%
```

## 🛠️ Implementation Details

### Database Joins:
```
order_detail
├── order (join on order_detail.order_id = order.id)
├── product (join on order_detail.product_id = product.id)
├── categories (join on product.category_id = categories.id)
└── product_unit (join on order_detail.product_unit_id = product_unit.id)
```

### Controller Method:
`DashboardController::revenueDetails()`
- Lấy chi tiết sản phẩm với pagination (20/page)
- Lấy chi tiết danh mục
- Tính tổng thống kê
- Tính tỷ suất lợi nhuận

### View File:
`resources/views/admin/pages/revenue-details.blade.php`
- Responsive table layout
- Bootstrap badges & colors
- Tab navigation
- Info box với hướng dẫn

## 🎯 Color Coding

| Phần Tử | Màu | Ý Nghĩa |
|--------|-----|--------|
| Doanh Thu | Text Success (Xanh) | Positive |
| Giá Vốn | Text Warning (Vàng) | Cost |
| Lợi Nhuận | Text Primary (Xanh dương) | Profit |
| Tỷ Suất > 0% | Badge Success | Lợi nhuận tốt |
| Tỷ Suất < 0% | Badge Danger | Bị lỗ |
| Tỷ Suất = 0% | Badge Secondary | Hoà vốn |

## 📊 Ví Dụ Dữ Liệu

### Sản Phẩm A:
- Giá Nhập: 50,000đ
- Giá Bán: 100,000đ
- Số Lượng Bán: 100
- Doanh Thu: 10,000,000đ
- Giá Vốn: 5,000,000đ
- Lợi Nhuận: 5,000,000đ (50%)

## 📱 Responsive Design
- Table responsive wrapper
- Bootstrap grid system
- Tabs support mobile view
- Pagination compatible

## 🔒 Security
- Middleware CheckLogin bảo vệ route
- Chỉ lấy order có isactive = 1
- Input validation cho date range

## ⚡ Performance
- Eager loading quan hệ
- Single query cho tất cả dữ liệu
- Groupby efficient
- Pagination tránh overload

## 📝 Future Enhancements
1. Export CSV/PDF
2. Biểu đồ lợi nhuận theo thời gian
3. Sorting columns
4. Product search/filter
5. Compare period data
6. Profit margin by category chart

---

**Status:** ✅ Production Ready  
**Last Updated:** 2024-01-09
