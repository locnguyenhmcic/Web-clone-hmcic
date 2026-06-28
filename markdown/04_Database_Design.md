# 04_Database_Design.md

# HMCIC CMS

## Database Design

Version: 1.0

---

# 1. Database Information

Database Engine

MySQL 8+

Charset

utf8mb4

Collation

utf8mb4_unicode_ci

Timezone

Asia/Ho_Chi_Minh

---

# 2. Naming Convention

Table

snake_case

Column

snake_case

Foreign Key

xxx_id

Pivot Table

alphabetical_order

Ví dụ

category_post

Không dùng

post_category

---

# 3. Tables

## users

| Field      | Type           | Note |
| ---------- | -------------- | ---- |
| id         | bigint         | PK   |
| name       | varchar        |      |
| email      | varchar unique |      |
| password   | varchar        |      |
| created_at | timestamp      |      |
| updated_at | timestamp      |      |

---

## posts

| Field        | Type               |
| ------------ | ------------------ |
| id           | bigint             |
| title        | string             |
| slug         | string unique      |
| excerpt      | text nullable      |
| content      | longText           |
| thumbnail_id | bigint nullable    |
| status       | enum               |
| published_at | timestamp nullable |
| created_by   | bigint             |
| updated_by   | bigint             |
| created_at   | timestamp          |
| updated_at   | timestamp          |
| deleted_at   | timestamp          |

---

## categories

| Field      | Type            |
| ---------- | --------------- |
| id         | bigint          |
| name       | string          |
| slug       | string unique   |
| parent_id  | bigint nullable |
| created_at | timestamp       |
| updated_at | timestamp       |

---

## category_post

Pivot Table

| post_id |
| category_id |

Primary Key

post_id + category_id

---

## media

| Field         | Type            |
| ------------- | --------------- |
| id            | bigint          |
| filename      | string          |
| original_name | string          |
| mime_type     | string          |
| extension     | string          |
| disk          | string          |
| path          | string          |
| size          | bigint          |
| alt           | string nullable |
| caption       | text nullable   |
| created_by    | bigint          |
| created_at    | timestamp       |

---

# 4. Relationships

User

hasMany(Post)

Post

belongsTo(User)

Post

belongsTo(Media)

Post

belongsToMany(Category)

Category

belongsToMany(Post)

Category

belongsTo(Category)

Category

hasMany(Category)

---

# 5. Index Strategy

Unique

posts.slug

categories.slug

users.email

Normal Index

published_at

status

created_at

parent_id

mime_type

---

# 6. Soft Delete

Áp dụng

posts

media

Không áp dụng

category_post

---

# 7. Upload Structure

storage/app/public

images/

pdf/

videos/

thumbnail/

Mỗi file lưu theo năm/tháng để giảm số lượng file trong một thư mục.

Ví dụ

images/2026/06/

---

# 8. Future Tables

pages

menus

banners

activity_logs

notifications

roles

permissions

Không thay đổi cấu trúc bảng hiện tại khi bổ sung module mới.

---

# 9. Migration Rules

Không sửa Migration đã chạy Production.

Nếu thay đổi cấu trúc:

Luôn tạo Migration mới.

Không chỉnh sửa trực tiếp Database.
