# SOFTWARE REQUIREMENT DOCUMENT (SRD)

## HMCIC CMS - Admin System

### Version 2.0

---

# 1. Mục tiêu dự án

Xây dựng hệ thống quản trị nội dung (CMS) cho website **hmcic.vn** trên nền tảng:

* Laravel 12
* Inertia.js
* React
* TypeScript
* Filament Admin
* MySQL/PostgreSQL

Hệ thống cho phép quản trị viên quản lý bài viết, danh mục, tệp đính kèm và các dữ liệu phục vụ website thông qua giao diện quản trị hiện đại, dễ sử dụng và dễ mở rộng.

---

# 2. Kiến trúc hệ thống

Hệ thống gồm hai phần độc lập:

## Frontend Website

* Hiển thị nội dung cho người dùng.
* Sử dụng React + Inertia.
* Lấy dữ liệu từ Laravel.

## Admin CMS

* Chỉ dành cho quản trị viên.
* Xây dựng bằng Filament.
* Quản lý toàn bộ dữ liệu của website.

---

# 3. Module Authentication

## Mục tiêu

Cho phép quản trị viên đăng nhập và sử dụng hệ thống một cách an toàn.

### Chức năng

* Đăng nhập bằng tài khoản quản trị.
* Đăng xuất.
* Ghi nhớ đăng nhập (Remember Me).
* Kiểm tra quyền truy cập.
* Tự động đăng xuất khi hết thời gian phiên làm việc.
* Chặn truy cập trực tiếp khi chưa đăng nhập.

### Tiêu chí hoàn thành

* Quản trị viên đăng nhập thành công.
* Không thể truy cập trang quản trị nếu chưa xác thực.
* Hệ thống tự động hết phiên theo cấu hình.
* Đăng xuất hoạt động chính xác.

---

# 4. Dashboard

## Mục tiêu

Hiển thị thông tin tổng quan sau khi đăng nhập.

### Nội dung

* Tổng số bài viết.
* Tổng số danh mục.
* Tổng số tệp.
* Dung lượng lưu trữ đã sử dụng.
* Danh sách bài viết mới nhất.

### Tiêu chí hoàn thành

Dashboard hiển thị đầy đủ dữ liệu thống kê và cập nhật theo thời gian thực.

---

# 5. Module Media Manager

## Mục tiêu

Quản lý toàn bộ tệp của website.

### Loại tệp hỗ trợ

* Hình ảnh
* PDF
* Video

### Chức năng

* Upload tệp.
* Xem danh sách.
* Xem trước nội dung.
* Đổi tên.
* Xóa.
* Tìm kiếm theo tên.
* Lọc theo loại.
* Sắp xếp theo ngày, tên và loại.
* Phân trang.

### Tiêu chí hoàn thành

Quản trị viên có thể quản lý toàn bộ tệp trong hệ thống mà không cần truy cập máy chủ.

---

# 6. Module Category Manager

## Mục tiêu

Quản lý danh mục bài viết.

### Thông tin danh mục

* Name
* Slug
* Parent Category

### Chức năng

* Tạo.
* Chỉnh sửa.
* Xóa.
* Hiển thị danh sách.
* Thiết lập danh mục cha.
* Kiểm tra trùng Slug.

### Tiêu chí hoàn thành

Danh mục có thể sử dụng trực tiếp trong phần tạo và chỉnh sửa bài viết.

---

# 7. Module Post Manager

## Mục tiêu

Quản lý toàn bộ bài viết của website.

---

## Danh sách bài viết

Hiển thị dạng bảng.

Bao gồm:

* Thumbnail
* Tiêu đề
* Danh mục
* Trạng thái
* Ngày đăng

Cho phép:

* Tìm kiếm theo tiêu đề.
* Lọc theo danh mục.
* Lọc theo trạng thái.
* Lọc theo ngày.
* Sắp xếp.
* Phân trang (10 bài/trang).

---

## Tạo và chỉnh sửa bài viết

Thông tin bài viết gồm:

* Tiêu đề.
* Slug.
* Thumbnail.
* Danh mục.
* Nội dung.
* Trạng thái.
* Ngày đăng.
* Canonical URL.

Slug được sinh tự động từ tiêu đề nhưng vẫn cho phép chỉnh sửa.

---

## Trình soạn thảo

Editor hỗ trợ:

* Heading
* Bold
* Italic
* Underline
* Danh sách
* Bảng
* Hyperlink
* Chèn hình ảnh
* Chèn PDF
* Chèn video
* Block Quote
* Code Block

Media được chọn từ Media Manager thay vì upload trực tiếp.

---

## Trạng thái bài viết

* Draft
* Published
* Hidden

---

### Tiêu chí hoàn thành

Quản trị viên có thể tạo, sửa, xóa, lưu nháp và xuất bản bài viết với đầy đủ nội dung và phương tiện đính kèm.

---

# 8. Quản lý lưu trữ

Toàn bộ tệp được lưu trong Storage của Laravel.

Phân loại theo:

* Images
* PDF
* Video

Có thể mở rộng thêm các loại tệp khác trong tương lai.

---

# 9. Bảo mật

Hệ thống phải đảm bảo:

* CSRF Protection.
* Validation dữ liệu đầu vào.
* Kiểm tra định dạng tệp.
* Giới hạn dung lượng upload.
* Kiểm tra quyền truy cập.
* Ngăn chặn XSS.

---

# 10. Hiệu năng

Hệ thống cần hỗ trợ:

* Phân trang.
* Lazy Loading.
* Cache dữ liệu.
* Queue xử lý upload.
* Tối ưu hình ảnh.

---

# 11. Khả năng mở rộng

Kiến trúc phải cho phép bổ sung các module mới mà không ảnh hưởng đến hệ thống hiện có.

Các module dự kiến:

* Banner Manager
* Menu Manager
* Page Manager
* User & Permission
* Activity Log
* Revision History
* Contact Form Manager
* SEO Manager
* Notification Manager

---

# 12. Tiêu chuẩn hoàn thành dự án

Hệ thống được xem là hoàn thiện khi:

* Quản trị viên có thể đăng nhập và quản lý toàn bộ dữ liệu website.
* Tất cả bài viết, danh mục và tệp được quản lý thông qua Admin CMS.
* Dữ liệu hiển thị chính xác trên website.
* Hệ thống bảo mật, ổn định và dễ bảo trì.
* Kiến trúc sẵn sàng cho việc mở rộng các chức năng trong tương lai.

---

# 13. Public Website

## 13.1 Mục tiêu

Xây dựng giao diện website dành cho người dùng cuối (không cần đăng nhập) sử dụng React + Inertia.js.

Website phải sử dụng dữ liệu được quản lý từ Admin CMS và đảm bảo hiệu năng, SEO và trải nghiệm người dùng.

---

## 13.2 Homepage

Bao gồm:

- Hero Banner
- Tin nổi bật
- Tin mới nhất
- Danh mục tin
- Liên kết nhanh
- Footer

---

## 13.3 Danh sách bài viết

Hiển thị:

- Thumbnail
- Tiêu đề
- Mô tả ngắn
- Danh mục
- Ngày đăng

Hỗ trợ:

- Pagination
- Filter Category
- Search

---

## 13.4 Chi tiết bài viết

Hiển thị:

- Tiêu đề
- Breadcrumb
- Ngày đăng
- Danh mục
- Thumbnail
- Nội dung
- Media trong nội dung
- File PDF đính kèm
- Tin liên quan

---

## 13.5 SEO

Mỗi trang phải hỗ trợ:

- Meta Title
- Meta Description
- Open Graph
- Canonical URL

---

## 13.6 Responsive

Website phải hoạt động trên:

- Desktop
- Tablet
- Mobile