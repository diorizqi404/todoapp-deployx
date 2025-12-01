# Simple Todo App - PHP Native

Project sederhana untuk testing automatic deployment.

## Setup

1. Copy `.env.example` ke `.env` dan sesuaikan konfigurasi:
   - `APP_NAME` - Nama aplikasi
   - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT` - Konfigurasi database
2. Import `schema.sql` ke MySQL
3. Jalankan: `php -S localhost:8000`
4. Buka `http://localhost:8000`

## Struktur File

```
├── index.php          # Landing page
├── login.php          # Halaman login
├── register.php       # Halaman register
├── dashboard.php      # Halaman todo list
├── config.php         # Konfigurasi database & session
├── style.css          # Styling
├── schema.sql         # Database schema
└── .env               # Environment variables
```

## Fitur

- Register & Login dengan password hashing
- CRUD Todo List
- Mark todo sebagai completed
- Session management
