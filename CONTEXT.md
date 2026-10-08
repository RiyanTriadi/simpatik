# CONTEXT.md — Konteks Teknis SIMPATIK

Dokumen ini menjelaskan keputusan arsitektur, struktur database, dan konvensi teknis yang dipakai di proyek SIMPATIK.

---

## 🏗️ Arsitektur Aplikasi

### Pola Multi-Role URL

Aplikasi menggunakan **URL prefix** untuk memisahkan konteks role:

```text
/admin/*   → untuk user dengan role 'admin'
/staff/*   → untuk user dengan role 'staff'
/petugas/* → untuk user dengan role 'petugas'
```

Route didefinisikan **via loop** di `routes/web.php`:

```php
foreach (['admin', 'staff', 'petugas'] as $role) {
    Route::prefix($role)->name("{$role}.")->middleware(['auth', "role:{$role}"])
        ->group(function () use ($role) {
            // ... route yang sama untuk ketiga role
        });
}
```

**Keuntungan:**

- Satu sumber kebenaran untuk logic CRUD
- Middleware `role:xxx` otomatis memproteksi akses
- URL eksplisit menunjukkan konteks role

**Konsekuensi:**

- Nama route berbeda: `admin.pengaduan.index`, `staff.pengaduan.index`, `petugas.pengaduan.index`
- Harus pakai helper `role_route()` agar otomatis resolve prefix

### Helper Global

```php
// app/Helpers/helpers.php
function role_prefix(): string {
    return auth()->check() ? auth()->user()->role : 'admin';
}

function role_route(string $name, $params = [], bool $absolute = true): string {
    return route(role_prefix() . '.' . $name, $params, $absolute);
}
```

Didaftarkan via `composer.json` → `autoload.files`.

---

## 🗄️ Skema Database

### `users`

| Kolom                   | Tipe                 | Catatan                              |
| ----------------------- | -------------------- | ------------------------------------ |
| id                      | bigint PK            |                                      |
| name                    | varchar(100)         |                                      |
| email                   | varchar(100)         | unique                               |
| password                | varchar              | bcrypt                               |
| role                    | enum                 | admin, staff, petugas                |
| unit_id                 | FK nullable          | references units, nullOnDelete       |
| phone                   | varchar(20) nullable |                                      |
| profile_image_path      | varchar nullable     | path relatif ke `storage/app/public` |
| timestamps, softDeletes |                      |                                      |

### `units`

| Kolom                   | Tipe                        | Catatan      |
| ----------------------- | --------------------------- | ------------ |
| id                      | bigint PK                   |              |
| name                    | varchar(100)                |              |
| code                    | varchar(50) unique nullable |              |
| description             | text nullable               |              |
| is_active               | boolean                     | default true |
| timestamps, softDeletes |                             |              |

### `categories`

| Kolom                   | Tipe                  | Catatan                       |
| ----------------------- | --------------------- | ----------------------------- |
| id                      | bigint PK             |                               |
| name                    | varchar(100)          | unique                        |
| type                    | enum                  | pengaduan, aspirasi, keduanya |
| description             | varchar(255) nullable |                               |
| color                   | varchar(20) nullable  |                               |
| is_active               | boolean               | default true                  |
| timestamps, softDeletes |                       |                               |

### `complaints`

| Kolom                   | Tipe                 | Catatan                          |
| ----------------------- | -------------------- | -------------------------------- |
| id                      | bigint PK            |                                  |
| ticket_number           | varchar unique       | format: `PGD-YYYYMMDD-XXXX`      |
| creator_type            | enum                 | internal, eksternal              |
| category_id             | FK                   | references categories            |
| is_anonymous            | boolean              |                                  |
| subject                 | varchar              |                                  |
| description             | text                 |                                  |
| incident_date           | date                 |                                  |
| incident_location       | varchar nullable     |                                  |
| attachment_path         | varchar nullable     |                                  |
| reporter_name           | varchar nullable     |                                  |
| reporter_phone          | varchar(20) nullable |                                  |
| reporter_email          | varchar nullable     |                                  |
| status                  | enum                 | baru, diverifikasi, di_assign, diproses, selesai, ditolak |
| priority                | enum                 | rendah, sedang, tinggi, urgent   |
| assigned_to             | FK nullable          | references users, nullOnDelete   |
| assigned_at             | timestamp nullable   |                                  |
| resolved_at             | timestamp nullable   |                                  |
| timestamps, softDeletes |                      |                                  |

### `aspirations`

| Kolom                   | Tipe                 | Catatan                                                              |
| ----------------------- | -------------------- | -------------------------------------------------------------------- |
| id                      | bigint PK            |                                                                      |
| ticket_number           | varchar unique       | format: `ASP-YYYYMMDD-XXXX`                                          |
| creator_type            | enum                 | internal, eksternal                                                  |
| category_id             | FK                   | references categories                                                |
| is_anonymous            | boolean              |                                                                      |
| subject                 | varchar              |                                                                      |
| description             | text                 |                                                                      |
| attachment_path         | varchar nullable     |                                                                      |
| reporter_name           | varchar nullable     |                                                                      |
| reporter_phone          | varchar(20) nullable |                                                                      |
| reporter_email          | varchar nullable     |                                                                      |
| status                  | enum                 | baru, ditindaklanjuti, selesai, ditolak                              |
| timestamps, softDeletes |                      |                                                                      |

### `sessions`

Tabel standar Laravel untuk session driver database.

---

## 🔗 Relasi Eloquent

```text
User belongsTo Unit
User hasMany Complaint (assigned_to)

Unit hasMany User
Unit hasMany Complaint (via unit_id di complaint — opsional)

Category hasMany Complaint
Category hasMany Aspiration

Complaint belongsTo Category
Complaint belongsTo User (as officer, via assigned_to)

Aspiration belongsTo Category
```

---

## 🎨 Konvensi Frontend

### Layout

Semua halaman admin/staff/petugas pakai komponen `<x-layout.admin>`. Nama "admin" di sini generik (bukan spesifik role), karena sidebar di dalamnya sudah otomatis menyesuaikan role user yang login via `auth()->user()->role`.

### Toast Notification

Komponen global `<x-toast />` di layout. Dipanggil otomatis saat ada flash message `success` atau `error` dari controller.

### Modal Form

- ≤ 6 field → Modal (`x-show="modalOpen"`)
- \> 6 field atau ada file upload → Halaman terpisah

### Dropdown Aksi di Tabel

Karena tabel punya `overflow-x-auto`, dropdown pakai teknik:

```html
<template x-teleport="body">
    <div
        x-show="open"
        :style="`top: ${top}px; left: ${left}px;`"
        class="fixed z-[100] ..."
    ></div>
</template>
```

Posisi `top` & `left` dihitung via `getBoundingClientRect()` di fungsi `toggle()`.

### Pagination Custom

Komponen `<x-pagination>` (view: `components/pagination.blade.php`) dipakai untuk konsistensi UI di seluruh halaman.

---

## ⚠️ Tailwind Safelist (PENTING)

Banyak class dibuat dinamis via interpolasi string:

```blade
<div class="peer-checked:border-{{ $opt['color'] }}-500">
```

Tailwind tidak bisa mendeteksi class seperti ini saat build. Solusinya, tambahkan di `tailwind.config.js`:

```js
safelist: [
    // Quick filter chips
    'border-prussian-blue-500', 'bg-prussian-blue-50', 'text-prussian-blue-700',
    'border-blue-500', 'bg-blue-50', 'text-blue-700',
    'border-yellow-500', 'bg-yellow-50', 'text-yellow-700',
    'border-emerald-500', 'bg-emerald-50', 'text-emerald-700',
    'border-red-500', 'bg-red-50', 'text-red-700',
    'border-purple-500', 'bg-purple-50', 'text-purple-700',
    'border-teal-500', 'bg-teal-50', 'text-teal-700',
    'bg-prussian-blue-500', 'bg-blue-500', 'bg-yellow-500',
    'bg-emerald-500', 'bg-red-500', 'bg-purple-500', 'bg-teal-500',

    // Peer-checked (untuk kartu keputusan & prioritas)
    'peer-checked:border-blue-500', 'peer-checked:bg-blue-50', 'hover:border-blue-300',
    'peer-checked:border-yellow-500', 'peer-checked:bg-yellow-50', 'hover:border-yellow-300',
    'peer-checked:border-emerald-500', 'peer-checked:bg-emerald-50', 'hover:border-emerald-300',
    'peer-checked:border-red-500', 'peer-checked:bg-red-50', 'hover:border-red-300',
    'peer-checked:border-purple-500', 'peer-checked:bg-purple-50', 'hover:border-purple-300',
    'peer-checked:border-teal-500', 'peer-checked:bg-teal-50', 'hover:border-teal-300',
    'peer-checked:border-gray-500', 'peer-checked:bg-gray-50', 'hover:border-gray-300',
    'peer-checked:border-orange-500', 'peer-checked:bg-orange-50', 'hover:border-orange-300',
],
```

---

## 🐛 Common Bugs & Solusi

### 1. Query Builder Tertimpa

❌ **Salah:**

```php
$query = Model::where(...);
$data = Model::latest();       // ← variabel baru, bukan lanjutan
$data->where(...);             // ← filter di $data
$result = $query->paginate();  // ← filter hilang!
```

✅ **Benar:**

```php
$query = Model::where(...);
if ($kondisi) $query->where(...);
$result = $query->latest()->paginate();
```

### 2. `where()` untuk Multiple Values

- ❌ `where('status', ['a', 'b'])` → error
- ✅ `whereIn('status', ['a', 'b'])`

### 3. Route Order

Route dengan parameter dinamis harus setelah route statis:

```php
Route::get('pengaduan/verifikasi', ...);   // statis dulu
Route::get('pengaduan/{complaint}', ...);  // dinamis belakangan
```

### 4. Missing `use` untuk Relasi

Setiap return type `HasMany`, `BelongsTo`, `HasOne`, `BelongsToMany` harus di-import:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
```

### 5. Sidebar Mobile Tidak Tertutup

Cek typo pada Alpine binding `:class`:

```html
:class="{ '-translate-x-full': !mobileOpen, 'translate-x-0': mobileOpen }"
```

Bukan `-transate-x-full` (typo).

---

## 🚦 Alur Status Tiket

### Pengaduan (Complaint)

```text
baru → diverifikasi → di_assign → diproses → selesai
       └────────────→ ditolak

Petugas hanya dapat di_assign → diproses → selesai. Admin/staff dapat mengubah status tanpa batasan transisi.
```

### Aspirasi

```text
baru → ditindaklanjuti → selesai
       └──────────────→ ditolak

Aspirasi dikelola admin/staff tanpa assignment petugas.
```

---

## 📅 Changelog Fitur Besar

| Tanggal    | Fitur                                                         |
| ---------- | ------------------------------------------------------------- |
| 2026-09-24 | Setup awal, modul Pengaduan & Aspirasi                        |
| 2026-09-25 | Pagination custom, modal verifikasi                           |
| 2026-09-26 | Auth Fortify, multi-role, layout responsive                   |
| 2026-09-27 | Master Data (Kategori, Unit), Manajemen User                  |
| 2026-09-28 | Assign ke Petugas, profil user                                |
| 2026-09-29 | Kotak Masuk terpadu (union query), Dashboard                  |
| 2026-09-30 | Multi-role URL (`/admin`, `/staff`, `/petugas`), petugas view |

---

## 📞 Referensi Internal

- **Helpers:** `app/Helpers/helpers.php`
- **Middleware Role:** `app/Http/Middleware/RoleMiddleware.php`
- **Layout Utama:** `resources/views/components/x-layout/admin.blade.php`
- **Routes:** `routes/web.php` (loop-based)
