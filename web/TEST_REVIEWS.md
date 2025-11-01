# 🌟 Hướng dẫn Test Hệ thống Đánh giá Sản phẩm

## 📋 Chuẩn bị

### 1. Chạy Migration
```bash
cd F:\dacs\dacs\web
php artisan migrate
```

Kết quả mong đợi:
```
Migrating: 2025_11_01_135111_create_reviews_table
Migrated:  2025_11_01_135111_create_reviews_table
```

### 2. Khởi động Server
```bash
php artisan serve
```

---

## 🧪 Test Cases

### Test Case 1: Hiển thị đánh giá trên trang chi tiết sách

#### Bước 1: Xem trang chi tiết sách
1. Vào: http://127.0.0.1:8000
2. Click vào 1 cuốn sách bất kỳ
3. URL: http://127.0.0.1:8000/book/{id}

#### Kiểm tra:
- ✅ Hiển thị rating trung bình (0.0/5 nếu chưa có đánh giá)
- ✅ Số lượng đánh giá
- ✅ Sao vàng/trắng phù hợp với rating

#### Bước 2: Click tab "Đánh giá"
1. Ở phần tabs, click "Đánh giá"

#### Kiểm tra:
- ✅ Hiển thị rating trung bình lớn
- ✅ Biểu đồ phân bố 5-4-3-2-1 sao
- ✅ Progress bar cho từng mức sao
- ✅ Thông báo "Chưa có đánh giá" (nếu mới)

---

### Test Case 2: Kiểm tra quyền đánh giá - Chưa đăng nhập

#### Bước 1: Xem tab Đánh giá khi chưa đăng nhập
1. Logout nếu đang đăng nhập
2. Vào trang chi tiết sách
3. Click tab "Đánh giá"

#### Kiểm tra:
- ✅ Hiển thị thông báo: "Đăng nhập để viết đánh giá"
- ✅ Link đến trang login

---

### Test Case 3: Kiểm tra quyền đánh giá - Chưa mua

#### Bước 1: Đăng nhập
1. Login: http://127.0.0.1:8000/login

#### Bước 2: Xem sách chưa mua
1. Vào trang chi tiết 1 cuốn sách chưa mua
2. Click tab "Đánh giá"

#### Kiểm tra:
- ✅ Hiển thị spinner loading
- ✅ Sau vài giây hiển thị: "Bạn cần mua sản phẩm này để có thể đánh giá"
- ✅ Không có form đánh giá

---

### Test Case 4: Đánh giá sau khi mua và giao hàng

#### Bước 1: Mua sản phẩm
1. Thêm sách vào giỏ hàng
2. Thanh toán thành công
3. Note lại `order_id` và `book_id`

#### Bước 2: Admin đổi trạng thái đơn hàng
1. Đăng nhập admin: http://127.0.0.1:8000/admin
2. Vào Orders: http://127.0.0.1:8000/admin/orders
3. Tìm đơn vừa tạo
4. Click "Sửa"
5. Đổi status thành **"Đã giao"**
6. Click "Cập nhật"

#### Bước 3: Kiểm tra trang success
1. Quay lại trang checkout success (hoặc vào lại từ URL)
2. URL: http://127.0.0.1:8000/checkout/success/{orderId}

#### Kiểm tra:
- ✅ Hiển thị alert success: "Đơn hàng đã được giao!"
- ✅ Có nút "Đánh giá: [Tên sách]" cho từng sản phẩm
- ✅ Click vào nút sẽ chuyển đến book detail + tab reviews

#### Bước 4: Viết đánh giá
1. Click nút "Đánh giá: ..."
2. Hoặc vào trang chi tiết sách đã mua
3. Click tab "Đánh giá"

#### Kiểm tra form hiển thị:
- ✅ Hiển thị form đánh giá (không còn thông báo "cần mua")
- ✅ Có hệ thống chọn sao (1-5 sao)
- ✅ Textarea để nhập nhận xét
- ✅ Nút "Gửi đánh giá"

#### Bước 5: Gửi đánh giá
1. Click vào sao (VD: 5 sao)
2. Nhập nhận xét: "Sách rất hay, nội dung hấp dẫn!"
3. Click "Gửi đánh giá"

#### Kiểm tra:
- ✅ Redirect về trang sách
- ✅ Hiển thị thông báo: "Cảm ơn bạn đã đánh giá!"
- ✅ Rating trung bình cập nhật
- ✅ Đánh giá mới hiển thị ở danh sách
- ✅ Có badge "Đã mua hàng" (verified purchase)
- ✅ Hiển thị tên người đánh giá
- ✅ Hiển thị thời gian (VD: "2 phút trước")

---

### Test Case 5: Không thể đánh giá 2 lần

#### Bước 1: Thử đánh giá lại sách đã đánh giá
1. Vào lại trang chi tiết sách vừa đánh giá
2. Click tab "Đánh giá"

#### Kiểm tra:
- ✅ Hiển thị alert success: "Bạn đã đánh giá sản phẩm này rồi!"
- ✅ Không hiển thị form đánh giá
- ✅ Đánh giá cũ vẫn hiển thị trong danh sách

---

### Test Case 6: Kiểm tra UI đánh giá

#### Bước 1: Xem tab đánh giá có nhiều reviews
1. Tạo thêm vài đánh giá từ các account khác
2. Vào trang chi tiết sách

#### Kiểm tra phần tổng quan:
- ✅ Rating trung bình hiển thị to, rõ ràng
- ✅ Sao vàng tương ứng với rating
- ✅ Tổng số đánh giá đúng
- ✅ Biểu đồ phân bố sao:
  - Progress bar đúng tỷ lệ
  - Số lượng từng mức sao chính xác

#### Kiểm tra danh sách reviews:
- ✅ Reviews xếp theo: Verified purchase trước, mới nhất trước
- ✅ Mỗi review hiển thị:
  - Tên người đánh giá
  - Badge "Đã mua hàng" (nếu verified)
  - Số sao đầy đủ
  - Nội dung nhận xét
  - Thời gian (relative: "5 phút trước", "1 ngày trước")

---

### Test Case 7: Star rating animation

#### Bước 1: Test hover effect
1. Vào form đánh giá (sau khi đã mua)
2. Hover chuột qua các sao

#### Kiểm tra:
- ✅ Sao đổi màu vàng khi hover
- ✅ Tất cả sao bên phải cũng đổi màu
- ✅ Cursor hiển thị pointer (hand)

#### Bước 2: Test click sao
1. Click vào sao thứ 4
2. Click vào sao thứ 2

#### Kiểm tra:
- ✅ Sao được chọn giữ màu vàng
- ✅ Các sao bên phải cũng vàng
- ✅ Có thể thay đổi số sao đã chọn

---

### Test Case 8: Đánh giá với đơn hàng chưa giao

#### Bước 1: Mua sản phẩm mới
1. Thêm sách vào giỏ và checkout
2. Đơn hàng status = "pending"

#### Bước 2: Thử đánh giá
1. Vào trang chi tiết sách vừa mua
2. Click tab "Đánh giá"

#### Kiểm tra:
- ✅ Hiển thị: "Chỉ có thể đánh giá sau khi đơn hàng được giao"
- ✅ Alert màu warning (vàng)
- ✅ Không có form đánh giá

#### Bước 3: Admin đổi status
1. Admin đổi status sang "Đã giao"
2. Refresh trang sách

#### Kiểm tra:
- ✅ Form đánh giá xuất hiện
- ✅ Có thể gửi đánh giá bình thường

---

### Test Case 9: Validation

#### Test 9.1: Gửi form không chọn sao
1. Vào form đánh giá
2. Nhập comment nhưng không chọn sao
3. Click "Gửi đánh giá"

#### Kiểm tra:
- ✅ Form không submit
- ✅ Hiển thị lỗi validation (required)

#### Test 9.2: Nhận xét dài quá 1000 ký tự
1. Nhập comment > 1000 ký tự
2. Click "Gửi đánh giá"

#### Kiểm tra:
- ✅ Hiển thị lỗi validation
- ✅ Form không submit

---

### Test Case 10: Đánh giá không verified purchase

#### Scenario: User mua nhưng order bị cancel
1. Tạo order mới
2. Admin cancel order
3. User cố đánh giá

#### Kiểm tra:
- ✅ Không hiển thị form đánh giá
- ✅ Thông báo: "Bạn cần mua sản phẩm này để có thể đánh giá"

---

## 🔍 Kiểm tra Database

### Kiểm tra bảng reviews
```sql
SELECT 
    r.*,
    b.title as book_title,
    u.name as user_name,
    o.order_number
FROM reviews r
LEFT JOIN books b ON r.book_id = b.id
LEFT JOIN users u ON r.user_id = u.id
LEFT JOIN orders o ON r.order_id = o.id
ORDER BY r.created_at DESC;
```

#### Kiểm tra:
- ✅ `book_id` tồn tại
- ✅ `user_id` đúng người đánh giá
- ✅ `order_id` trỏ đến đơn đã giao
- ✅ `rating` trong khoảng 1-5
- ✅ `is_verified_purchase` = 1 nếu từ đơn đã giao
- ✅ `created_at` đúng thời gian

---

## 📊 Kiểm tra tính năng nâng cao

### Feature 1: Tính rating trung bình
```php
// Trong Book model
$book = Book::find(1);
echo $book->averageRating(); // VD: 4.3
echo $book->totalReviews();  // VD: 15
```

### Feature 2: Sắp xếp reviews
- ✅ Verified purchase lên đầu
- ✅ Mới nhất ở trên

### Feature 3: Prevent duplicate reviews
- ✅ 1 user chỉ review 1 lần cho mỗi sách
- ✅ Unique constraint: (book_id, user_id, order_id)

---

## 🎨 UI/UX Checklist

### Trang chi tiết sách
- [ ] Rating stars hiển thị đúng màu
- [ ] Số đánh giá hiển thị rõ ràng
- [ ] Tab "Đánh giá" dễ nhận ra
- [ ] Form đánh giá layout đẹp
- [ ] Star rating có animation smooth

### Card đánh giá
- [ ] Badge "Đã mua hàng" nổi bật
- [ ] Avatar hoặc icon cho reviewer
- [ ] Thời gian dễ đọc
- [ ] Comment xuống dòng đẹp
- [ ] Khoảng cách hợp lý giữa các reviews

### Biểu đồ rating
- [ ] Progress bar màu vàng
- [ ] % hiển thị đúng
- [ ] Responsive trên mobile

---

## 🚀 Performance Check

### Test với nhiều reviews
1. Tạo 50+ reviews cho 1 sách
2. Load trang chi tiết

#### Kiểm tra:
- ✅ Trang load nhanh (< 2s)
- ✅ Pagination nếu > 10 reviews
- ✅ Không lag khi scroll

---

## 🐛 Troubleshooting

### Lỗi 1: "Table 'reviews' doesn't exist"
**Giải pháp:**
```bash
php artisan migrate
```

### Lỗi 2: Form không hiển thị
**Kiểm tra:**
- User đã đăng nhập?
- Đơn hàng status = 'delivered'?
- Console browser có lỗi?

### Lỗi 3: Rating không cập nhật
**Giải pháp:**
```bash
php artisan cache:clear
php artisan view:clear
```

### Lỗi 4: Star rating không work
**Kiểm tra:**
- CSS đã load?
- JavaScript có lỗi?
- Radio input có name="rating"?

---

## ✅ Final Checklist

### Chức năng cốt lõi
- [ ] User có thể đánh giá sau khi mua
- [ ] Hiển thị rating trung bình
- [ ] Badge "Đã mua hàng" cho verified
- [ ] Không thể đánh giá 2 lần
- [ ] Chỉ đánh giá được khi delivered

### UI/UX
- [ ] Star rating đẹp và dễ dùng
- [ ] Biểu đồ phân bố rõ ràng
- [ ] Reviews sắp xếp hợp lý
- [ ] Responsive mobile

### Security
- [ ] Validate rating 1-5
- [ ] Prevent duplicate reviews
- [ ] Check ownership (mua mới được review)

**Chúc test thành công! 🌟**
