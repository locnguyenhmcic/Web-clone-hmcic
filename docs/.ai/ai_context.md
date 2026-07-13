# AI_CONTEXT.md

# HMCIC CMS

Đọc toàn bộ file này trước khi viết bất kỳ dòng code nào.

---

## Project Goal

Đây là hệ thống CMS dành cho website hmcic.vn.

Phạm vi module giai đoạn đầu: News, Projects, Services, FAQ, Contact, Downloads.

Mục tiêu là code sạch, dễ mở rộng, không ưu tiên code nhanh.

Luôn ưu tiên Maintainability.

---
---

## Current Project Status

Admin CMS:

✅ Completed

Current Phase:

Public Website Development

Current Priority:

- Website Layout
- Homepage
- News
- Search
- SEO

AI phải ưu tiên code Public Website.

Không được refactor Admin nếu không có yêu cầu.

Admin CMS được xem là nền tảng ổn định.

## Tech Stack

Laravel 12

PHP 8.3

Filament v4

Inertia.js

React

TypeScript

MySQL

---

## Architecture Rules

Không viết Business Logic trong:

* Controller
* Filament Resource
* Livewire Component

Business Logic luôn nằm trong Service.

Repository chỉ truy cập Database.

Model chỉ định nghĩa Relationship và Scope.

Backend và frontend được tách riêng để dễ bảo trì.

---

Website và Admin dùng chung:

- Models
- Service
- Repository

Không tạo Business Logic riêng cho Website nếu đã tồn tại trong Admin.

## Coding Rules

Ưu tiên:

SOLID

DRY

KISS

Không Duplicate Code.

Không Hardcode.

Không viết code "đủ chạy".

---

## Database Rules

Luôn sử dụng Migration.

Không sửa trực tiếp Database.

Không dùng Raw SQL nếu Eloquent đáp ứng được.

---

## Upload Rules

Tất cả file upload phải lưu vào Storage.

Không lưu trong public/.

Phân loại:

images/

pdf/

videos/

thumbnail/

---

## Filament Rules

CRUD sử dụng Filament Resource.

Không tự tạo CRUD nếu Filament đã hỗ trợ.

Chỉ custom khi thật sự cần.

---

## Naming Convention

Class

PascalCase

Method

camelCase

Database

snake_case

Component

PascalCase

Ưu tiên đặt tên mô tả rõ domain và hành vi.

---

## Before Coding

AI phải tự hỏi:

* Chức năng này đã tồn tại chưa?
* Có vi phạm kiến trúc không?
* Có tạo duplicate code không?
* Có thể tái sử dụng không?
* Có ảnh hưởng module khác không?

Nếu câu trả lời là Có thì phải refactor thay vì viết mới.

---

## Documentation Rules

Sau khi hoàn thành một module:

* Cập nhật Development Checklist.
* Nếu thay đổi kiến trúc thì cập nhật TDD.
* Nếu thêm chức năng thì cập nhật SRD.

Không được để tài liệu và source code lệch nhau.

---

## Absolute Rules

Không tự ý đổi kiến trúc.

Không đổi tên Model.

Không đổi tên Table.

Không đổi Folder Structure.

Không tạo Package mới nếu chưa thật sự cần.

Không cài thư viện mới khi chưa giải thích lý do.

Luôn giữ code thống nhất với toàn bộ dự án.

---

## Public Website Rules

Website chỉ đọc dữ liệu.

Không thao tác CRUD.

Mọi dữ liệu đều đến từ Admin CMS.

React chỉ chịu trách nhiệm hiển thị.

---

## Runtime Clarification (Critical)

User-facing runtime hiện tại chạy qua Inertia trong:

- temp-laravel/resources/js/**

Frontend trong:

- frontend/src/**

đang là experimental/preparation, chưa phải production runtime.

Khi nhận yêu cầu chỉnh giao diện đang chạy cho user, ưu tiên sửa ở temp-laravel/resources/js/**.

Business Logic nằm ở Backend.

TipTap JSON phải được render thông qua Renderer.

Media phải lấy từ Media Library.

Không hardcode URL.

Không query Database trong React.