# 📁 Laravel File Upload API role-based access

A RESTful API built with Laravel that allows users to upload, view, and download files based on role-based access control.

---

## 🚀 Features

- 🔐 Authentication via Laravel Sanctum
- 👥 Role-based access: `admin`, `user`, `viewer`
- 📤 Upload files (private or public)
- 📥 Secure file download with permission check
- 📂 File visibility depends on user role

---

## 🧠 Role Permissions

| Role     | Can View Files                     | Can Download |
|----------|-------------------------------------|--------------|
| `admin`  | All files from all users            | ✅ Yes        |
| `user`   | Own files + public files            | ✅ Yes        |
| `viewer` | Only public files                   | ✅ Yes        |
| Guest    | Only public files (not authenticated) | ❌ No         |

---

## ⚙️ Getting Started

### 1. Clone the Project

```bash
git clone https://github.com/Rafeef2Rizq/File_upload_API.git
cd your-repo-name
composer install
cp .env.example .env
php artisan key:generate
