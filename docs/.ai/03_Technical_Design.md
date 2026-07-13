# HMCIC CMS

# Technical Design Document (TDD)

Version: 2.0

---

# 1. Project Overview

Tên dự án: HMCIC CMS

Mục tiêu:

Xây dựng hệ thống CMS dành cho website hmcic.vn.

Framework:

* Laravel 12
* PHP 8.3+
* Inertia.js
* React
* TypeScript
* Filament v4
* MySQL

---

# 2. Architecture

Client
↓

Laravel Routing

↓

Controller / Filament Resource

↓

Service Layer

↓

Repository Layer

↓

Eloquent Models

↓

Database

Business logic tuyệt đối không viết trực tiếp trong Controller hoặc Filament Resource.

## 2.1 Frontend Runtime Ownership

Public Website runtime hiện tại:

- temp-laravel/resources/js/Pages/**
- Inertia render qua routes/web.php

Frontend experimental:

- frontend/src/**

Lưu ý:

- frontend/src/** không tham gia runtime production ở giai đoạn hiện tại.
- Mọi thay đổi ảnh hưởng giao diện user đang chạy phải thực hiện tại temp-laravel/resources/js/**.

## 2.2 Deploy-safe Migration Strategy

Chiến lược chuyển đổi sang frontend tách riêng (nếu có) phải theo pha:

1. Align API contract và dữ liệu hiển thị.
2. Chạy staging song song và so sánh UX/SEO.
3. UAT + performance + fallback verification.
4. Cutover theo cửa sổ deploy, có rollback rõ ràng.

---

# 3. Folder Structure

app/

Actions/

Enums/

Filament/

Models/

Policies/

Repositories/

Services/

Traits/

resources/

js/

Components/

Pages/

Layouts/

Types/

database/

migrations/

seeders/

docs/

---

# 4. Coding Convention

Model

Post

Category

Media

User

Controller

Không chứa Business Logic.

Service

Mỗi module có một Service riêng.

Ví dụ:

PostService

MediaService

CategoryService

Repository

Chỉ thao tác Database.

Không chứa Validation.

---

# 5. Database Convention

Tên bảng

snake_case

Ví dụ

posts

categories

media

Tên cột

snake_case

Foreign Key

post_id

category_id

thumbnail_id

---

# 6. Relationships

User

↓

hasMany(Post)

Post

↓

belongsToMany(Category)

Post

↓

belongsTo(Media)

Category

↓

hasMany(Post)

---

# 7. Upload Convention

Storage

storage/app/public

Folder

images/

pdf/

videos/

thumbnail/

Không upload vào public/.

---

# 8. Rich Editor

Sử dụng TipTap.

Không dùng CKEditor.

Media phải chọn từ Media Library.

---

# 9. Security

CSRF

XSS Protection

Laravel Validation

Policy

Permission

Soft Delete

---

# 10. Performance

Pagination

Queue Upload

Cache Category

Lazy Loading

---

# 11. Deployment

DirectAdmin

PHP 8.3

OPcache

Queue Worker

Scheduler

Storage Link

---

# 12. Future Modules

Banner

Page

Menu

Role Permission

Activity Log

Revision

SEO

Contact Form

Không thay đổi kiến trúc hiện tại khi thêm module mới.

---

# 13. Public Website Architecture

Website sử dụng:

React + Inertia.js

Flow

Browser

↓

Laravel Route

↓

Controller

↓

Service

↓

Repository

↓

Model

↓

Database

↓

React Component

Website và Admin sử dụng chung:

- Service Layer
- Repository Layer
- Models
- Database

Không duplicate Business Logic.

---

# 14. Rendering Strategy

Danh sách bài viết:

SSR thông qua Inertia.

Chi tiết bài viết:

Render từ TipTap JSON.

Media được lấy từ bảng media.

Không render trực tiếp URL đã lưu trong content.

---

# 15. SEO Strategy

Meta được sinh từ Database.

Canonical URL được sinh động.

Open Graph sử dụng Thumbnail của bài viết.