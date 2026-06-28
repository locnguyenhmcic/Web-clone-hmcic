# HMCIC CMS - Development Checklist

## Version 1.0

---

# PHASE 1 - Project Foundation

## 1.1 Project Initialization

### Backend

* [x] Khởi tạo Laravel 12
* [x] Kết nối MySQL/PostgreSQL
* [x] Cấu hình .env
* [x] Thiết lập Git Repository
* [x] Thiết lập Storage Link
* [x] Cấu hình Timezone
* [x] Cấu hình Locale
* [x] Cấu hình Queue
* [x] Cấu hình Cache

### Frontend

* [x] Cài đặt Inertia.js
* [x] Cài đặt React
* [x] Cài đặt TypeScript
* [x] Cấu hình Vite
* [x] Cấu hình TailwindCSS

### Filament

* [x] Cài đặt Filament Admin
* [x] Truy cập được /admin
* [x] Hiển thị Dashboard mặc định

### Acceptance Criteria

* [x] Laravel chạy ổn định.
* [x] Có thể truy cập Admin Panel.
* [x] Không còn lỗi cấu hình.

---

# PHASE 2 - Authentication

## 2.1 Login

* [ ] Trang Login
* [ ] Đăng nhập
* [ ] Đăng xuất
* [ ] Remember Me

## 2.2 Session

* [ ] Session Timeout
* [ ] Middleware Authentication
* [ ] Route Protection

### Acceptance Criteria

* Chỉ người đã đăng nhập mới được vào Admin.
* Session tự hết hạn đúng cấu hình.
* Logout hoạt động bình thường.

---

# PHASE 3 - Dashboard

## Dashboard Widgets

* [ ] Tổng số bài viết
* [ ] Tổng số danh mục
* [ ] Tổng số Media
* [ ] Tổng dung lượng lưu trữ
* [ ] Danh sách bài viết mới

### Acceptance Criteria

Dashboard hiển thị đầy đủ số liệu và cập nhật chính xác.

---

# PHASE 4 - Media Manager

## Upload

* [ ] Upload hình ảnh
* [ ] Upload PDF
* [ ] Upload Video

## Media Library

* [ ] Danh sách Media
* [ ] Phân trang
* [ ] Preview Image
* [ ] Preview PDF
* [ ] Preview Video

## CRUD

* [ ] Rename
* [ ] Delete

## Search & Filter

* [ ] Search theo tên
* [ ] Filter theo loại file
* [ ] Sort theo tên
* [ ] Sort theo ngày
* [ ] Sort theo loại

### Acceptance Criteria

* Upload thành công.
* Preview hoạt động.
* CRUD đầy đủ.
* Search và Filter chính xác.

---

# PHASE 5 - Category Manager

## Category Resource

* [ ] Danh sách Category
* [ ] Create
* [ ] Edit
* [ ] Delete

## Category Information

* [ ] Name
* [ ] Slug
* [ ] Parent Category

## Validation

* [ ] Name Required
* [ ] Slug Unique

### Acceptance Criteria

* CRUD hoàn chỉnh.
* Có thể chọn Parent Category.
* Không tạo được Slug trùng.

---

# PHASE 6 - Post Manager

## 6.1 Post List

* [ ] Danh sách bài viết
* [ ] Thumbnail
* [ ] Tiêu đề
* [ ] Danh mục
* [ ] Trạng thái
* [ ] Ngày đăng

## Search

* [ ] Search theo tiêu đề

## Filter

* [ ] Category
* [ ] Status
* [ ] Publish Date

## Sort

* [ ] Theo ngày
* [ ] Theo tiêu đề

## Pagination

* [ ] 10 bài viết / trang

### Acceptance Criteria

Danh sách hoạt động đầy đủ với Search, Filter và Pagination.

---

## 6.2 Create Post

### Basic Information

* [ ] Title
* [ ] Slug
* [ ] Thumbnail
* [ ] Category
* [ ] Content

### Publish

* [ ] Draft
* [ ] Published
* [ ] Hidden

### SEO

* [ ] SEO Title
* [ ] SEO Description
* [ ] Canonical URL

### Acceptance Criteria

Có thể tạo bài viết đầy đủ thông tin.

---

## 6.3 Edit Post

* [ ] Chỉnh sửa bài viết
* [ ] Thay Thumbnail
* [ ] Đổi Category
* [ ] Cập nhật nội dung
* [ ] Cập nhật SEO

### Acceptance Criteria

Bài viết được cập nhật chính xác.

---

## 6.4 Delete Post

* [ ] Soft Delete
* [ ] Restore (nếu áp dụng)

### Acceptance Criteria

Không mất dữ liệu ngoài ý muốn.

---

# PHASE 7 - Rich Text Editor

## Editor

* [ ] Heading
* [ ] Paragraph
* [ ] Bold
* [ ] Italic
* [ ] Underline
* [ ] Text Color
* [ ] Hyperlink
* [ ] Ordered List
* [ ] Unordered List
* [ ] Table
* [ ] Quote
* [ ] Code Block

## Media

* [ ] Insert Image
* [ ] Insert PDF
* [ ] Insert Video

### Acceptance Criteria

Editor hoạt động tương tự Microsoft Word và hỗ trợ đầy đủ Media.

---

# PHASE 8 - File Storage

## Storage

* [ ] Image Folder
* [ ] PDF Folder
* [ ] Video Folder

## Validation

* [ ] File Size
* [ ] MIME Type
* [ ] Extension

### Acceptance Criteria

File được lưu đúng thư mục và đúng định dạng.

---

# PHASE 9 - Security

## Authentication

* [ ] CSRF Protection
* [ ] XSS Protection
* [ ] Validation Request

## Authorization

* [ ] Middleware
* [ ] Policy
* [ ] Permission

### Acceptance Criteria

Không có lỗi bảo mật cơ bản.

---

# PHASE 10 - Performance

## Optimization

* [ ] Pagination
* [ ] Lazy Loading
* [ ] Cache Category
* [ ] Cache Homepage
* [ ] Queue Upload

### Acceptance Criteria

Website hoạt động ổn định với dữ liệu lớn.

---

# PHASE 11 - Deployment

## Production

* [ ] Storage Link
* [ ] Queue Worker
* [ ] Scheduler
* [ ] Environment Production
* [ ] SSL
* [ ] Backup Database

### Acceptance Criteria

Website hoạt động ổn định trên DirectAdmin.

---

# PHASE 12 - Future Modules

## Content

* [ ] Page Manager
* [ ] Banner Manager
* [ ] Menu Manager

## User

* [ ] Role
* [ ] Permission

## System

* [ ] Activity Log
* [ ] Revision History
* [ ] Notification

---

# PROJECT COMPLETION CHECK

## Foundation

* [ ] Hoàn thành

## Authentication

* [ ] Hoàn thành

## Dashboard

* [ ] Hoàn thành

## Media Manager

* [ ] Hoàn thành

## Category Manager

* [ ] Hoàn thành

## Post Manager

* [ ] Hoàn thành

## Rich Text Editor

* [ ] Hoàn thành

## Security

* [ ] Hoàn thành

## Performance

* [ ] Hoàn thành

## Deployment

* [ ] Hoàn thành

---

# FINAL ACCEPTANCE

* [ ] Admin đăng nhập ổn định.
* [ ] Dashboard hoạt động.
* [ ] Quản lý Media hoàn chỉnh.
* [ ] Quản lý Category hoàn chỉnh.
* [ ] Quản lý Post hoàn chỉnh.
* [ ] Editor hỗ trợ đầy đủ tính năng.
* [ ] Website hiển thị đúng dữ liệu từ CMS.
* [ ] Hệ thống hoạt động ổn định trên DirectAdmin.
* [ ] Sẵn sàng triển khai Production.
