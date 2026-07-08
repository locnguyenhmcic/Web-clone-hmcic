# HMCIC CMS - Development Checklist

## Version 2.0

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

* [x] Trang Login
* [x] Đăng nhập
* [x] Đăng xuất
* [x] Remember Me

## 2.2 Session

* [x] Session Timeout
* [x] Middleware Authentication
* [x] Route Protection

### Acceptance Criteria

* [x] Chỉ người đã đăng nhập mới được vào Admin.
* [x] Session tự hết hạn đúng cấu hình.
* [x] Logout hoạt động bình thường.

---

# PHASE 3 - Dashboard

## Dashboard Widgets

* [x] Tổng số bài viết
* [x] Tổng số danh mục
* [x] Tổng số Media
* [x] Tổng dung lượng lưu trữ
* [x] Danh sách bài viết mới

### Acceptance Criteria

* [x] Dashboard hiển thị đầy đủ số liệu và cập nhật chính xác.

---

# PHASE 4 - Media Manager

## Upload

* [x] Upload hình ảnh
* [x] Upload PDF
* [x] Upload Video

## Media Library

* [x] Danh sách Media
* [x] Phân trang
* [x] Preview Image
* [x] Preview PDF
* [x] Preview Video

## CRUD

* [x] Rename
* [x] Delete

## Search & Filter

* [x] Search theo tên
* [x] Filter theo loại file
* [x] Sort theo tên
* [x] Sort theo ngày
* [x] Sort theo loại

### Acceptance Criteria

* [x] Upload thành công.
* [x] Preview hoạt động.
* [x] CRUD đầy đủ.
* [x] Search và Filter chính xác.

---

# PHASE 5 - Category Manager

## Category Resource

* [x] Danh sách Category
* [x] Create
* [x] Edit
* [x] Delete

## Category Information

* [x] Name
* [x] Slug
* [x] Parent Category

## Validation

* [x] Name Required
* [x] Slug Unique

### Acceptance Criteria

* CRUD hoàn chỉnh.
* Có thể chọn Parent Category.
* Không tạo được Slug trùng.

---

# PHASE 6 - Post Manager

## 6.1 Post List

* [x] Danh sách bài viết
* [x] Thumbnail
* [x] Tiêu đề
* [x] Danh mục
* [x] Trạng thái
* [x] Ngày đăng

## Search

* [x] Search theo tiêu đề

## Filter

* [x] Category
* [x] Status
* [x] Publish Date

## Sort

* [x] Theo ngày
* [x] Theo tiêu đề

## Pagination

* [x] 10 bài viết / trang

### Acceptance Criteria

Danh sách hoạt động đầy đủ với Search, Filter và Pagination.

---

## 6.2 Create Post

### Basic Information

* [x] Title
* [x] Slug
* [x] Thumbnail
* [x] Category
* [x] Content

### Publish

* [x] Draft
* [x] Published
* [x] Hidden

### SEO

* [x] SEO Title
* [x] SEO Description
* [x] Canonical URL

### Acceptance Criteria

Có thể tạo bài viết đầy đủ thông tin.

---

## 6.3 Edit Post

* [x] Chỉnh sửa bài viết
* [x] Thay Thumbnail
* [x] Đổi Category
* [x] Cập nhật nội dung
* [x] Cập nhật SEO

### Acceptance Criteria

Bài viết được cập nhật chính xác.

---

## 6.4 Delete Post

* [x] Soft Delete
* [x] Restore (nếu áp dụng)

### Acceptance Criteria

Không mất dữ liệu ngoài ý muốn.

---

# PHASE 7 - Rich Text Editor

## Editor

* [x] Heading
* [x] Paragraph
* [x] Bold
* [x] Italic
* [x] Underline
* [x] Text Color
* [x] Hyperlink
* [x] Ordered List
* [x] Unordered List
* [x] Table
* [x] Quote
* [x] Code Block

## Media

* [x] Insert Image
* [x] Insert PDF
* [x] Insert Video

### Acceptance Criteria

Editor hoạt động tương tự Microsoft Word và hỗ trợ đầy đủ Media.

---

# PHASE 8 - File Storage

## Storage

* [x] Image Folder
* [x] PDF Folder
* [x] Video Folder

## Validation

* [x] File Size
* [x] MIME Type
* [x] Extension

### Acceptance Criteria

File được lưu đúng thư mục và đúng định dạng.

---

# PHASE 9 - Security

## Authentication

* [x] CSRF Protection
* [x] XSS Protection
* [x] Validation Request

## Authorization

* [x] Middleware
* [x] Policy
* [x] Permission

### Acceptance Criteria

Không có lỗi bảo mật cơ bản.

---

# PHASE 10 - Performance

## Optimization

* [x] Pagination
* [x] Lazy Loading
* [x] Cache Category
* [x] Cache Homepage
* [x] Queue Upload

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

---

# PHASE 13 - Public Website Foundation

## Layout

- [x] Main Layout
- [x] Header
- [x] Navigation
- [x] Footer
- [x] Responsive Layout

### Acceptance Criteria

- [x] Có thể hiển thị giao diện website hoàn chỉnh.

---

# PHASE 14 - Homepage

- [ ] Hero Banner
- [ ] Featured News
- [ ] Latest News
- [ ] Category Section
- [ ] Quick Links

---

# PHASE 15 - News

## News List

- [ ] Danh sách bài viết
- [ ] Pagination
- [ ] Category Filter
- [ ] Search

## News Detail

- [ ] Breadcrumb
- [ ] Content
- [ ] Render Image
- [ ] Render PDF
- [ ] Related Posts

---

# PHASE 16 - Search

- [ ] Search Box
- [ ] Search Result
- [ ] Empty Result

---

# PHASE 17 - SEO

- [ ] Meta Title
- [ ] Meta Description
- [ ] Open Graph
- [ ] Sitemap
- [ ] robots.txt

---

# PHASE 18 - Performance

- [ ] Image Lazy Load
- [ ] Route Cache
- [ ] Homepage Cache
- [ ] Optimize Assets

---

# PHASE 19 - Production Ready

- [ ] Responsive Testing
- [ ] SEO Testing
- [ ] Lighthouse > 90
- [ ] Browser Compatibility

# PROJECT COMPLETION CHECK

## Foundation

* [ ] Hoàn thành

## Authentication

* [x] Hoàn thành

## Dashboard

* [x] Hoàn thành

## Media Manager

* [x] Hoàn thành

## Category Manager

* [x] Hoàn thành

## Post Manager

* [x] Hoàn thành

## Rich Text Editor

* [x] Hoàn thành

## Security

* [ ] Hoàn thành

## Performance

* [ ] Hoàn thành

## Deployment

* [ ] Hoàn thành

---

# FINAL ACCEPTANCE

* [ ] Admin đăng nhập ổn định.
* [x] Dashboard hoạt động.
* [x] Quản lý Media hoàn chỉnh.
* [x] Quản lý Category hoàn chỉnh.
* [x] Quản lý Post hoàn chỉnh.
* [x] Editor hỗ trợ đầy đủ tính năng.
* [ ] Website hiển thị đúng dữ liệu từ CMS.
* [ ] Hệ thống hoạt động ổn định trên DirectAdmin.
* [ ] Sẵn sàng triển khai Production.
