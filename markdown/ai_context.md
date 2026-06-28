# AI_CONTEXT.md

# HMCIC CMS

Đọc toàn bộ file này trước khi viết bất kỳ dòng code nào.

---

## Project Goal

Đây là hệ thống CMS dành cho website hmcic.vn.

Mục tiêu là code sạch, dễ mở rộng, không ưu tiên code nhanh.

Luôn ưu tiên Maintainability.

---

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

---

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
