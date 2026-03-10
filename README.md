# he-thong-thi-trac-nghiem-service

Dự án này cung cấp các API RESTful để ứng dụng client sử dụng.

---

# Laravel Project Setup Guide

## 1. Clone repository

```bash
git clone <repository-url>
cd <project-folder>
```

## 2. Install PHP dependencies (Composer)

```bash
composer install
```

## 3. Install Node dependencies

```bash
npm install
```

## 4. Create environment file

Sao chép tệp .env.example sang tệp .env

```bash
cp .env.example .env
```

## 5. Configure database

Mở tệp .env và cập nhật cấu hình cơ sở dữ liệu

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=he_thong_thi_trac_nghiem_service
DB_USERNAME=root
DB_PASSWORD=
```

Tạo cơ sở dữ liệu trong MySQL (XAMPP / phpMyAdmin)

## 6. Generate application key

```bash
php artisan key:generate
```

## 7. Run database migrations

```bash
php artisan migrate
```

## 8. Start the development server

```bash
php artisan serve
```

Default URL: http://127.0.0.1:8000
Base API: http://127.0.0.1:8000/api

---

## API Endpoints (CRUD)

Base API: http://127.0.0.1:8000/api

### 1. Get All Users

**GET** `/api/users`

Lấy danh sách tất cả người dùng.

```bash
http://127.0.0.1:8000/api/users
```

### 2. Get User by ID

**GET** `/api/users/{id}`

Lấy thông tin một người dùng theo ID.

```bash
http://127.0.0.1:8000/api/users/1
```

### 3. Create User

**POST** `/api/users`

Tạo người dùng mới.

Body:

```json
{
  "name": "Le Hoang Than"
}
```

### 4. Update User

**PUT** `/api/users{id}`

Cập nhật thông tin người dùng.

Body:

```json
{
  "name": "Le Hoang Than DH52201426"
}
```

### 5. Delete User

**DELETE** `/api/users{id}`

Xóa người dùng.
