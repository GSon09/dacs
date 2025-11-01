# 🧪 Hướng dẫn Test Chức năng Thanh toán

## 📋 Chuẩn bị

### 1. Khởi động MySQL
1. Mở XAMPP Control Panel (`C:\xampp\xampp-control.exe`)
2. Click nút **Start** ở dòng MySQL
3. Đợi đến khi Status hiện chữ "Running"

### 2. Chạy Migration
```bash
cd F:\dacs\dacs\web
php artisan migrate
```

Kết quả mong đợi:
```
Migration table created successfully.
Migrating: 2025_11_01_115951_create_order_items_table
Migrated:  2025_11_01_115951_create_order_items_table
Migrating: 2025_11_01_132823_add_checkout_fields_to_orders_table
Migrated:  2025_11_01_132823_add_checkout_fields_to_orders_table
```

### 3. Khởi động Laravel Server
```bash
php artisan serve
```

Mở trình duyệt: http://127.0.0.1:8000

---

## 🛒 Test Case 1: Mua hàng - Khách không đăng nhập

### Bước 1: Thêm sách vào giỏ hàng
1. Vào trang chủ: http://127.0.0.1:8000
2. Chọn 1 danh mục (VD: "Văn học", "Khoa học", etc.)
3. Click **"Xem chi tiết"** trên 1 cuốn sách
4. Click **"Thêm vào giỏ hàng"**
5. Kiểm tra icon giỏ hàng trên navbar có số lượng

### Bước 2: Xem giỏ hàng
1. Click vào icon giỏ hàng trên navbar
2. URL: http://127.0.0.1:8000/cart
3. Kiểm tra:
   - ✅ Sách hiển thị đúng (ảnh, tên, giá)
   - ✅ Tăng/giảm số lượng hoạt động
   - ✅ Tổng tiền được tính đúng
   - ✅ Nút "Thanh toán" hiển thị

### Bước 3: Thanh toán
1. Click nút **"Thanh toán"**
2. URL: http://127.0.0.1:8000/checkout
3. Điền form:
   - **Họ và tên**: Nguyễn Văn A
   - **Email**: test@example.com
   - **Số điện thoại**: 0987654321
   - **Địa chỉ giao hàng**: 123 Đường ABC, Quận 1, TP.HCM
   - **Ghi chú**: (tùy chọn)
4. Kiểm tra bên phải:
   - ✅ Danh sách sản phẩm hiển thị
   - ✅ Tạm tính đúng
   - ✅ Phí ship: Miễn phí nếu >= 150,000đ, ngược lại 30,000đ
   - ✅ Tổng cộng = Tạm tính + Phí ship
5. Click **"Xác nhận đặt hàng"**

### Bước 4: Xác nhận đơn hàng
1. Redirect đến: http://127.0.0.1:8000/checkout/success/{orderId}
2. Kiểm tra:
   - ✅ Hiển thị mã đơn hàng (ORD-...)
   - ✅ Thông tin khách hàng đúng
   - ✅ Danh sách sản phẩm đầy đủ
   - ✅ Tổng tiền chính xác
   - ✅ Trạng thái: "Chờ xử lý"

### Bước 5: Kiểm tra giỏ hàng đã xóa
1. Quay lại giỏ hàng: http://127.0.0.1:8000/cart
2. Kiểm tra:
   - ✅ Giỏ hàng trống
   - ✅ Hiển thị "Giỏ hàng của bạn đang trống"

---

## 👤 Test Case 2: Mua hàng - Người dùng đã đăng nhập

### Bước 1: Đăng nhập
1. Login: http://127.0.0.1:8000/login
2. Dùng tài khoản admin hoặc tạo user mới

### Bước 2: Thêm sách và thanh toán
- Lặp lại Test Case 1 (Bước 1-4)
- Kiểm tra thêm:
  - ✅ Order có `user_id` trong database
  - ✅ Tên khách hàng tự động fill từ account

---

## 🔧 Test Case 3: Kiểm tra Admin - Xem đơn hàng

### Bước 1: Đăng nhập Admin
1. URL: http://127.0.0.1:8000/admin/login
2. Login với tài khoản admin

### Bước 2: Dashboard
1. URL: http://127.0.0.1:8000/admin
2. Kiểm tra thống kê:
   - ✅ **Tổng đơn hàng**: Tăng sau mỗi lần đặt
   - ✅ **Đơn chờ xử lý**: Số đơn status = pending
   - ✅ **Tổng doanh thu**: Tổng tiền đơn đã giao
   - ✅ **Biểu đồ doanh thu**: 7 ngày gần nhất
   - ✅ **Đơn gần đây**: 10 đơn mới nhất
   - ✅ Status hiển thị badge màu

### Bước 3: Danh sách đơn hàng
1. URL: http://127.0.0.1:8000/admin/orders
2. Kiểm tra:
   - ✅ Tất cả đơn hàng hiển thị
   - ✅ **Filter theo trạng thái**: Chọn "Chờ xử lý", click "Lọc"
   - ✅ **Tìm kiếm**: Nhập mã đơn/tên/SĐT, click "Lọc"
   - ✅ Status có badge màu (vàng/xanh/đỏ/xanh dương)
   - ✅ Nút "Sửa" và "Xóa"

### Bước 4: Xem chi tiết đơn
1. Click **"Sửa"** hoặc vào đơn hàng
2. Kiểm tra view chi tiết:
   - ✅ Thông tin đơn hàng (mã, ngày, trạng thái)
   - ✅ Thông tin khách hàng đầy đủ
   - ✅ Danh sách sản phẩm (tên, tác giả, SL, giá)
   - ✅ Breakdown: Tạm tính + Phí ship = Tổng
   - ✅ Ghi chú (nếu có)

### Bước 5: Cập nhật trạng thái
1. Trong trang chi tiết, phần "Thao tác"
2. Chọn trạng thái mới: **"Chờ lấy hàng"**
3. Click **"Cập nhật trạng thái"**
4. Kiểm tra:
   - ✅ Badge status thay đổi màu
   - ✅ Quay lại danh sách, status đã update

### Bước 6: Sửa đơn hàng
1. Click **"Sửa đơn hàng"**
2. URL: http://127.0.0.1:8000/admin/orders/{id}/edit
3. Thay đổi status thành **"Đã giao"**
4. Click **"Cập nhật"**
5. Kiểm tra:
   - ✅ Redirect về trang chi tiết
   - ✅ Status = "Đã giao"
   - ✅ Dashboard: Đơn đã giao +1, Doanh thu tăng

---

## 📦 Test Case 4: Kiểm tra Stock (Số lượng sản phẩm)

### Bước 1: Xem stock trước khi mua
1. Vào admin books: http://127.0.0.1:8000/admin/books
2. Chọn 1 sách, note lại số lượng (VD: stock = 50)

### Bước 2: Mua sản phẩm
1. Mua 3 cuốn sách đó từ trang user
2. Hoàn tất checkout

### Bước 3: Kiểm tra stock đã giảm
1. Quay lại admin books
2. Kiểm tra:
   - ✅ Stock = 47 (giảm 3)

### Bước 4: Xóa đơn hàng
1. Vào admin orders
2. Tìm đơn vừa tạo
3. Click **"Xóa"**
4. Confirm xóa

### Bước 5: Kiểm tra stock đã hoàn trả
1. Quay lại admin books
2. Kiểm tra:
   - ✅ Stock = 50 (hoàn về số ban đầu)

---

## 🎯 Test Case 5: Edge Cases

### Test 5.1: Mua nhiều hơn stock
1. Thêm sách có stock = 5 vào giỏ
2. Đổi quantity thành 10
3. Thanh toán
4. Kiểm tra:
   - ✅ Hiển thị lỗi "Số lượng không đủ"

### Test 5.2: Giỏ hàng trống
1. Vào cart khi chưa thêm gì
2. Kiểm tra:
   - ✅ Hiển thị "Giỏ hàng trống"
   - ✅ Không có nút thanh toán

### Test 5.3: Phí ship
**Test A: Đơn < 150k**
1. Mua sách tổng 100,000đ
2. Kiểm tra:
   - ✅ Phí ship = 30,000đ
   - ✅ Tổng = 130,000đ

**Test B: Đơn >= 150k**
1. Mua sách tổng 200,000đ
2. Kiểm tra:
   - ✅ Phí ship = 0đ (Miễn phí)
   - ✅ Tổng = 200,000đ

### Test 5.4: Filter và Search
1. Tạo 5+ đơn hàng với status khác nhau
2. Filter:
   - ✅ Chọn "Chờ xử lý" → Chỉ show đơn pending
   - ✅ Chọn "Đã giao" → Chỉ show đơn delivered
3. Search:
   - ✅ Nhập mã đơn → Show đúng đơn
   - ✅ Nhập tên khách → Show các đơn của khách đó
   - ✅ Nhập SĐT → Show đúng đơn

---

## ✅ Checklist Tổng Quát

### Frontend (User)
- [ ] Thêm sách vào giỏ hoạt động
- [ ] Cập nhật số lượng trong giỏ
- [ ] Xóa sản phẩm khỏi giỏ
- [ ] Form checkout validate đúng (required fields)
- [ ] Checkout thành công
- [ ] Trang success hiển thị đầy đủ
- [ ] Giỏ hàng tự động xóa sau checkout

### Backend (Admin)
- [ ] Dashboard hiển thị thống kê đúng
- [ ] Biểu đồ doanh thu render
- [ ] Danh sách orders hiển thị
- [ ] Filter theo status hoạt động
- [ ] Search đơn hàng hoạt động
- [ ] Xem chi tiết đơn
- [ ] Cập nhật status đơn
- [ ] Xóa đơn hàng

### Database
- [ ] Order được tạo đúng
- [ ] OrderItem được tạo đúng
- [ ] Stock giảm khi đặt hàng
- [ ] Stock tăng khi xóa đơn
- [ ] Cart tự động xóa sau checkout

---

## 🐛 Nếu có lỗi

### Lỗi 1: "No connection to MySQL"
**Giải pháp:**
```bash
# Mở XAMPP Control Panel và Start MySQL
# Hoặc:
net start MySQL
```

### Lỗi 2: "Table 'order_items' doesn't exist"
**Giải pháp:**
```bash
php artisan migrate:fresh --seed
```

### Lỗi 3: "Class 'Order' not found"
**Giải pháp:**
```bash
composer dump-autoload
```

### Lỗi 4: "404 Not Found" cho routes
**Giải pháp:**
```bash
php artisan route:clear
php artisan route:cache
```

### Lỗi 5: Views không hiển thị
**Giải pháp:**
```bash
php artisan view:clear
```

---

## 📊 Kết quả mong đợi

Sau khi test xong, bạn sẽ có:
- ✅ Hệ thống thanh toán hoàn chỉnh
- ✅ Quản lý đơn hàng admin đầy đủ
- ✅ Dashboard với thống kê real-time
- ✅ Inventory tracking tự động
- ✅ Transaction đảm bảo data consistency

**Happy Testing! 🚀**
