# 05_PROJECT_DECISIONS.md

# HMCIC CMS

## Architecture Decision Record (ADR)

Đây là tài liệu lưu tất cả các quyết định kỹ thuật của dự án.

AI Agent phải đọc file này trước khi đề xuất thay đổi.

---

# Decision 001

Ngày

2026-06-27

Tiêu đề

Framework

Quyết định

Laravel 12

Lý do

LTS, cộng đồng lớn, dễ mở rộng.

---

# Decision 002

Admin Framework

Filament v4

Lý do

CRUD mạnh.

Ít code.

Maintain tốt.

---

# Decision 003

Frontend

React + Inertia

Không sử dụng Livewire cho Website.

---

# Decision 004

Ngôn ngữ

TypeScript

Strict Mode.

Không dùng JavaScript thuần.

---

# Decision 005

Database

MySQL

Không sử dụng PostgreSQL ở phiên bản đầu tiên.

---

# Decision 006

Rich Text Editor

TipTap

Không dùng CKEditor.

Không dùng TinyMCE.

---

# Decision 007

Media

Upload vào Storage.

Không upload vào public.

---

# Decision 008

Business Logic

Đặt trong Service Layer.

Không viết trong Controller.

Không viết trong Filament Resource.

---

# Decision 009

Repository

Repository chỉ thao tác Database.

Không Validate.

Không Business Logic.

---

# Decision 010

Filament

CRUD mặc định sử dụng Filament Resource.

Không tự tạo CRUD nếu Filament đã hỗ trợ.

---

# Decision 011

Slug

Slug sinh tự động.

Cho phép chỉnh sửa.

Slug phải Unique.

---

# Decision 012

Post Category

Quan hệ Many-to-Many.

Một bài viết có thể thuộc nhiều Category.

---

# Decision 013

Storage Structure

storage/app/public

images/

pdf/

videos/

thumbnail/

---

# Decision 014

Soft Delete

Áp dụng cho:

Post

Media

Không áp dụng cho Pivot Table.

---

# Decision 015

Coding Standard

PSR-12

Laravel Pint

TypeScript Strict

---

# Decision 016

Package Policy

Không cài package mới nếu:

Laravel đã hỗ trợ.

Filament đã hỗ trợ.

Chỉ cài khi có lợi ích rõ ràng và được ghi vào tài liệu này.

---

# Decision 017

Documentation Policy

Mọi thay đổi kiến trúc phải cập nhật:

Technical Design

Database Design

AI_CONTEXT

PROJECT_DECISIONS

Development Checklist (nếu ảnh hưởng tiến độ)

---

# Decision 018

AI Agent Rules

AI không được:

* Tự đổi kiến trúc.
* Tự thêm package.
* Tự đổi tên bảng.
* Tự đổi Model.
* Tự refactor lớn nếu chưa được yêu cầu.

Nếu phát hiện vấn đề kiến trúc, AI phải đề xuất trước khi sửa.

---

---

# Decision 019

Public Website

Website sử dụng React + Inertia.

Không sử dụng Livewire.

---

# Decision 025

Frontend Runtime Boundary (Deploy-safe)

Nguồn hiển thị cho user ở giai đoạn hiện tại chỉ nằm trong:

temp-laravel/resources/js/**

Route public render bằng Inertia từ routes/web.php.

Thư mục frontend/src/** được giữ ở trạng thái experimental/preparation.

Không được coi frontend/src/** là runtime production cho đến khi có quyết định migration chính thức.

Lý do:

Giữ ổn định tiến trình deploy hiện tại.

Tránh chạy song song 2 frontend runtime gây lệch dữ liệu, lệch UI và sửa nhầm luồng.

---

# Decision 026

Frontend Migration Policy

Nếu chuyển sang frontend tách riêng, phải đi theo từng pha:

1) Song song read-only.

2) So khớp API contract và SEO.

3) Staging/UAT đầy đủ.

4) Cutover có rollback plan.

Không cutover trực tiếp trên production khi chưa hoàn thành đầy đủ checklist.

---

# Decision 020

Content Rendering

Content lưu TipTap JSON.

Không lưu HTML có URL cố định.

---

# Decision 021

Media

Media trong nội dung chỉ tham chiếu media_id.

Không lưu đường dẫn tuyệt đối.

---

# Decision 022

Service Layer

Website và Admin sử dụng chung Service Layer.

Không duplicate Business Logic.

---

# Decision 023

Frontend

Website sử dụng React Components.

Không sử dụng Blade cho giao diện chính.

---

# Decision 024

SEO

SEO lấy dữ liệu từ Database.

Không hardcode.

# Future Decisions

Mọi quyết định mới phải được đánh số tiếp theo:

Decision 027

...
