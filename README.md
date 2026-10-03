# SIMPATIK — Sistem Informasi Manajemen Pengaduan, Aspirasi, dan Tindak Lanjut Sivitas

SIMPATIK adalah aplikasi web berbasis Laravel untuk mengelola pengaduan dan aspirasi dari sivitas akademika (internal) maupun masyarakat umum (eksternal). Sistem ini menyediakan alur lengkap mulai dari pengajuan, verifikasi, penugasan ke petugas, hingga tindak lanjut dan pelaporan.

---

## 🚀 Fitur Utama

### Multi-Role Access

- **Admin** — Akses penuh ke seluruh sistem termasuk manajemen user & master data
- **Staff** — Kelola pengaduan & aspirasi, verifikasi, assign ke petugas
- **Petugas** — Tangani tiket yang di-assign, update status, balas komentar

### Manajemen Tiket

- Pengaduan dari eksternal (masyarakat) & internal (sivitas)
- Aspirasi dari sivitas akademika
- Tracking status: baru → diproses → selesai/ditolak
- Prioritas: rendah, sedang, tinggi, urgent
- Sistem assign tiket ke petugas
- Kotak Masuk terpadu (union query complaint + aspiration)

### Manajemen Master

- Kategori (pengaduan/aspirasi/keduanya)
- Unit Kerja
- User & role

### Dashboard & Laporan

- Statistik tiket (per status, per kategori, per unit)
- Grafik tren 7 hari terakhir
- Distribusi status (doughnut chart)
- Top 5 kategori

### Profil & Autentikasi

- Login via Laravel Fortify
- Manajemen profil (nama, email, telepon, foto)
- Ganti password

---

## 🛠️ Tech Stack

| Layer          | Teknologi                        |
| -------------- | -------------------------------- |
| **Framework**  | Laravel 13.x                     |
| **PHP**        | PHP 8.4                          |
| **Database**   | MySQL                            |
| **Frontend**   | Tailwind CSS 3.x + Alpine.js 3.x |
| **Build Tool** | Vite                             |
| **Auth**       | Laravel Fortify                  |
| **Icon**       | Remix Icon 4.x                   |
| **Chart**      | Chart.js 4.x                     |
| **Testing**    | PHPUnit                          |

---

## 📦 Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Langkah Setup

```bash
# 1. Clone repository
git clone <repo-url> simpatik
cd simpatik

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_DATABASE=simpatik
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migrasi & seeding
php artisan migrate --seed

# 6. Buat symlink storage
php artisan storage:link

# 7. Build assets
npm run dev

# 8. Jalankan server
php artisan serve
```

### Akun Default (dari Seeder)

| Role    | Email                     | Password |
| ------- | ------------------------- | -------- |
| Admin   | admin@universitas.ac.id   | password |
| Staff   | staff@universitas.ac.id   | password |
| Petugas | petugas@universitas.ac.id | password |

---

## 🗂️ Struktur Direktori Penting

```text
app/
├── Helpers/
│   └── helpers.php                  # role_prefix(), role_route()
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── Dashboard.php
│   │       ├── InboxController.php
│   │       ├── ComplaintController.php
│   │       ├── AspirationController.php
│   │       ├── UserController.php
│   │       ├── ProfileController.php
│   │       └── Master/
│   │           ├── CategoryController.php
│   │           └── UnitController.php
│   ├── Middleware/
│   │   └── RoleMiddleware.php
│   └── Requests/
│       └── Admin/
│           ├── StoreUserRequest.php
│           ├── UpdateUserRequest.php
│           └── UpdateProfileRequest.php
└── Models/
    ├── Complaint.php
    ├── Aspiration.php
    ├── Category.php
    ├── Unit.php
    └── User.php

resources/views/
├── components/
│   ├── toast.blade.php
│   ├── pagination.blade.php
│   └── x-layout/
│       └── admin.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── inbox/index.blade.php
│   ├── complaints/{index,show,verification,assign}.blade.php
│   ├── aspirations/{index,show,follow_up}.blade.php
│   ├── users/{index,create,edit,officers,_form}.blade.php
│   ├── profile/edit.blade.php
│   └── master/
│       ├── categories/index.blade.php
│       └── units/index.blade.php
└── auth/login.blade.php

routes/
└── web.php                          # Role-based route loop
```

---

## 🔐 Hak Akses per Role

| Fitur             | Petugas            | Staff | Admin |
| ----------------- | ------------------ | ----- | ----- |
| Dashboard         | ✅                 | ✅    | ✅    |
| Kotak Masuk       | ✅ (terbatas)      | ✅    | ✅    |
| Pengaduan         | ✅ (tugas sendiri) | ✅    | ✅    |
| Aspirasi          | ✅ (tugas sendiri) | ✅    | ✅    |
| Verifikasi        | ❌                 | ✅    | ✅    |
| Assign ke Petugas | ❌                 | ✅    | ✅    |
| Daftar Petugas    | ❌                 | ✅    | ✅    |
| Manajemen User    | ❌                 | ❌    | ✅    |
| Master Data       | ❌                 | ❌    | ✅    |
| Pengaturan        | ❌                 | ❌    | ✅    |

---

## 🎨 Panduan Development

### Konvensi Umum

- Selalu gunakan `role_route()` atau `role_prefix()` untuk URL yang di-prefix role
- Validasi form di FormRequest (bukan di controller)
- Gunakan modal untuk form dengan ≤ 6 field, halaman terpisah untuk form lebih banyak
- Dropdown aksi di tabel harus pakai `x-teleport="body"` untuk menghindari clipping

### Tailwind CSS Safelist

Karena banyak class dinamis (`peer-checked:border-{color}-500`), pastikan `tailwind.config.js` punya safelist lengkap. Lihat `CONTEXT.md` untuk detail.

### Debug

Set `APP_DEBUG=true` di `.env` untuk melihat stack trace lengkap.

---

## 📝 Lisensi

Copyright © 2026 Riyan Triadi. All rights reserved.
