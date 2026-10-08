# PRD — SIMPATIK

## Sistem Informasi Manajemen Pengaduan, Aspirasi, dan Tindak Lanjut Sivitas

|             |                   |
| ----------- | ----------------- |
| **Versi**   | 1.0               |
| **Tanggal** | 30 September 2026 |
| **Author**  | Riyan Triadi      |
| **Status**  | In Development    |

---

## 1. Latar Belakang

Sivitas akademika (mahasiswa, dosen, staff) dan masyarakat umum sering menyampaikan pengaduan atau aspirasi terkait fasilitas, layanan, dan kebijakan kampus. Saat ini, penyampaian masih dilakukan secara manual (verbal, email sporadis, atau kotak saran fisik), menyebabkan:

- Tidak ada tracking status pengaduan
- Sulitnya eskalasi ke unit yang tepat
- Tidak ada data historis untuk evaluasi
- Waktu respons lambat

SIMPATIK hadir sebagai solusi terpusat yang menyediakan alur lengkap dari pengajuan hingga tindak lanjut, dengan transparansi status dan akuntabilitas petugas.

---

## 2. Tujuan Produk

### Tujuan Bisnis

- Mempercepat waktu respons pengaduan & aspirasi
- Meningkatkan transparansi penanganan
- Menyediakan data historis untuk evaluasi kebijakan
- Mengurangi penyampaian manual via email/verbal

### Tujuan Pengguna

- **Pelapor**: Mudah melapor, dapat tracking status
- **Petugas**: Fokus pada tugas yang di-assign, mudah update status
- **Staff**: Kelola alur verifikasi & assignment
- **Admin**: Kontrol penuh sistem, kelola user & master data

---

## 3. Target Pengguna

### 3.1 Pelapor (Pengguna Publik)

- **Internal (Sivitas)**: Mahasiswa, dosen, tenaga kependidikan
- **Eksternal (Masyarakat)**: Warga sekitar kampus, tamu, alumni

### 3.2 Pengguna Panel (Admin/Staff/Petugas)

| Role        | Tanggung Jawab Utama                                        |
| ----------- | ----------------------------------------------------------- |
| **Petugas** | Menangani tiket yang di-assign, update status               |
| **Staff**   | Verifikasi tiket, assign ke petugas, tindak lanjut aspirasi |
| **Admin**   | Manajemen user, master data, konfigurasi sistem             |

---

## 4. Fitur & Requirement

### 4.1 Modul Publik (Tanpa Login)

#### 4.1.1 Form Pengaduan

- **FR-PUB-01**: User dapat mengisi form pengaduan dengan field:
    - Penyampai: Internal / Eksternal
    - Kategori (dropdown)
    - Anonim / Tidak anonim
    - Subjek & deskripsi
    - Tanggal & lokasi kejadian
    - Lampiran (opsional)
    - Nama, telepon, email (jika tidak anonim)
- **FR-PUB-02**: Sistem generate nomor tiket unik format `PGD-YYYYMMDD-XXXX`
- **FR-PUB-03**: User menerima nomor tiket untuk tracking

#### 4.1.2 Form Aspirasi

- **FR-PUB-04**: Field serupa pengaduan (tanpa tanggal/lokasi kejadian)
- **FR-PUB-05**: Nomor tiket format `ASP-YYYYMMDD-XXXX`

#### 4.1.3 Tracking Tiket

- **FR-PUB-06**: User dapat cek status via nomor tiket
- **FR-PUB-07**: Menampilkan timeline status

### 4.2 Modul Autentikasi

- **FR-AUTH-01**: Login dengan email & password (Laravel Fortify)
- **FR-AUTH-02**: Rate limiting 5 percobaan/menit
- **FR-AUTH-03**: Remember me
- **FR-AUTH-04**: Logout dari dropdown profil
- **FR-AUTH-05**: Manajemen profil (nama, email, telepon, foto)
- **FR-AUTH-06**: Ganti password (dengan verifikasi password lama)

### 4.3 Modul Dashboard

- **FR-DASH-01**: Stat cards: total pengaduan, aspirasi, diproses, belum di-assign
- **FR-DASH-02**: Mini stats: pengaduan baru, selesai, aspirasi baru, ditindaklanjuti
- **FR-DASH-03**: Grafik tren 7 hari terakhir (line chart)
- **FR-DASH-04**: Distribusi status pengaduan & aspirasi (doughnut chart)
- **FR-DASH-05**: Top 5 kategori berdasarkan jumlah tiket
- **FR-DASH-06**: Tabel tiket terbaru (8 tiket terakhir)
- **FR-DASH-07**: Aksi cepat (verifikasi, assign, tindak lanjut)

### 4.4 Modul Kotak Masuk (Inbox)

- **FR-INBOX-01**: Menampilkan semua tiket (complaint + aspiration) dalam satu tabel
- **FR-INBOX-02**: Filter:
    - Tipe (pengaduan/aspirasi)
    - Status (baru, diverifikasi, di_assign, diproses, selesai, ditolak, ditindaklanjuti)
    - Kategori
    - Prioritas
    - Rentang tanggal (hari ini, 7 hari, 30 hari, custom)
    - Petugas (belum di-assign, sudah di-assign, atau nama petugas)
- **FR-INBOX-03**: Quick filter chips dengan counter
- **FR-INBOX-04**: Search by tiket/topik
- **FR-INBOX-05**: Aksi per baris: detail, assign petugas, ubah status, ubah prioritas
- **FR-INBOX-06**: Petugas hanya melihat tiket yang di-assign ke dia

### 4.5 Modul Pengaduan

- **FR-PGD-01**: List semua pengaduan (dengan filter status, prioritas, search)
- **FR-PGD-02**: Halaman detail menampilkan:
    - Info lengkap pengaduan
    - Lampiran
    - Status & prioritas (editable via icon pensil)
    - Info pelapor
    - Petugas yang menangani
    - Waktu lapor
- **FR-PGD-03**: Inline edit status & prioritas dari halaman detail

#### 4.5.1 Verifikasi Pengaduan (Staff & Admin)

- **FR-PGD-04**: List pengaduan dengan status `baru`
- **FR-PGD-05**: Modal verifikasi:
    - Setujui (status → diverifikasi) → wajib set prioritas
    - Tolak (status → ditolak)
- **FR-PGD-05a**: Admin/staff dapat assign pengaduan berstatus `diverifikasi`; status berubah menjadi `di_assign`.
- **FR-PGD-05b**: Re-assign mengembalikan status menjadi `di_assign`.

#### 4.5.2 Assign ke Petugas (Staff & Admin)

- **FR-PGD-06**: List pengaduan aktif (diverifikasi + di_assign + diproses)
- **FR-PGD-07**: Filter: belum di-assign / sudah di-assign
- **FR-PGD-08**: Modal assign dengan dropdown petugas (grouped by unit)
- **FR-PGD-09**: Tombol "Batalkan Assign"

### 4.6 Modul Aspirasi

- **FR-ASP-01**: List semua aspirasi (dengan filter status, search)
- **FR-ASP-02**: Halaman detail
- **FR-ASP-03**: Admin/staff dapat mengubah status aspirasi
- **FR-ASP-04**: List tindak lanjut menampilkan aspirasi berstatus `baru` dan `ditindaklanjuti`
- **FR-ASP-05**: Tombol tindak lanjut → status `ditindaklanjuti`
- **FR-ASP-06**: Aspirasi tidak memiliki assignment petugas; seluruh pengelolaan dilakukan admin/staff.
- **FR-ASP-07**: Status aspirasi dapat diubah menjadi `selesai` atau `ditolak`.

### 4.7 Modul Manajemen User (Admin Only)

- **FR-USER-01**: List user dengan filter role & unit, search
- **FR-USER-02**: Tambah user (halaman terpisah):
    - Foto profil (upload)
    - Nama, email, password
    - Role (admin/staff/petugas)
    - Unit kerja
    - Telepon
- **FR-USER-03**: Edit user (halaman terpisah, password opsional)
- **FR-USER-04**: Hapus user (dengan proteksi self-delete)
- **FR-USER-05**: Halaman daftar petugas (read-only untuk staff) dengan:
    - Card grid
    - Filter unit
    - Beban tugas aktif (badge berwarna)

### 4.8 Modul Master Data (Admin Only)

#### 4.8.1 Kategori

- **FR-CAT-01**: CRUD via modal
- **FR-CAT-02**: Field: nama, tipe (pengaduan/aspirasi/keduanya), deskripsi, status aktif
- **FR-CAT-03**: Proteksi: tidak bisa hapus jika dipakai tiket

#### 4.8.2 Unit Kerja

- **FR-UNIT-01**: CRUD via modal
- **FR-UNIT-02**: Field: nama, kode, deskripsi, status aktif
- **FR-UNIT-03**: Proteksi: tidak bisa hapus jika punya user

---

## 5. Role-Based Access Control (RBAC)

| Fitur                     | Petugas             | Staff | Admin |
| ------------------------- | ------------------- | ----- | ----- |
| Dashboard                 | ✅                  | ✅    | ✅    |
| Kotak Masuk               | ✅ (hanya tugasnya) | ✅    | ✅    |
| Pengaduan — lihat         | ✅ (hanya tugasnya) | ✅    | ✅    |
| Pengaduan — update status | ❌ (read-only)       | ✅    | ✅    |
| Pengaduan — verifikasi    | ❌                  | ✅    | ✅    |
| Pengaduan — assign        | ❌                  | ✅    | ✅    |
| Pengaduan — hapus         | ❌                  | ❌    | ✅    |
| Aspirasi — lihat          | ❌                  | ✅    | ✅    |
| Aspirasi — tindak lanjut  | ❌                  | ✅    | ✅    |
| Aspirasi — hapus          | ❌                  | ❌    | ✅    |
| Daftar Petugas            | ❌                  | ✅    | ✅    |
| Manajemen User            | ❌                  | ❌    | ✅    |
| Master Data               | ❌                  | ❌    | ✅    |
| Pengaturan                | ❌                  | ❌    | ✅    |

---

## 6. User Flow Utama

### 6.1 Alur Pengaduan (End-to-End)

```text
User mengisi form pengaduan
        ↓
Sistem generate nomor tiket (PGD-...)
        ↓
Staff melihat di "Verifikasi"
        ↓
Admin/staff verifikasi: setujui + set prioritas → diverifikasi
        ├── Tolak → ditolak
        ↓
Admin/staff assign ke petugas → di_assign
        ↓
Petugas melihat tiket di "Tugas Saya"
        ↓
Petugas pilih Proses → diproses
        ↓
Petugas pilih Selesai → selesai

Petugas tidak dapat melompati status diproses.
```

### 6.2 Alur Aspirasi

```text
User mengisi form aspirasi
        ↓
Sistem generate nomor tiket (ASP-...)
        ↓
Staff melihat di "Tindak Lanjut"
        ↓
Admin/staff update status → ditindaklanjuti → selesai
        ↓
Status dapat berakhir sebagai selesai atau ditolak
```

---

## 7. Non-Functional Requirements

### Performance

- **NFR-PERF-01**: Waktu load halaman < 2 detik pada 1000 records
- **NFR-PERF-02**: Pagination 10-15 item/halaman
- **NFR-PERF-03**: Query menggunakan eager loading untuk relasi

### Security

- **NFR-SEC-01**: CSRF protection di semua form
- **NFR-SEC-02**: Rate limiting login (5 percobaan/menit)
- **NFR-SEC-03**: Password hashing bcrypt
- **NFR-SEC-04**: Role middleware pada semua route panel
- **NFR-SEC-05**: Proteksi self-delete user

### Usability

- **NFR-UX-01**: Responsive di mobile, tablet, desktop
- **NFR-UX-02**: Toast notification untuk feedback
- **NFR-UX-03**: Konfirmasi untuk aksi destruktif
- **NFR-UX-04**: Empty state informatif

### Compatibility

- **NFR-COMP-01**: Browser modern (Chrome, Firefox, Safari, Edge)
- **NFR-COMP-02**: PHP >= 8.2, MySQL >= 8.0

---

## 8. Batasan & Asumsi

### Batasan

- Belum ada fitur notifikasi email/WhatsApp
- Belum ada sistem komentar/chat antar user
- Aspirasi belum punya fitur assign ke petugas (hanya status)
- Laporan export (PDF/Excel) belum diimplementasi

### Asumsi

- Semua user panel mendapat kredensial dari admin (tidak ada self-register)
- Satu user hanya punya satu role
- Satu pengaduan hanya di-assign ke satu petugas

---

## 9. Roadmap

### Fase 1 — MVP (✅ Selesai)

- Autentikasi multi-role
- CRUD pengaduan & aspirasi
- Verifikasi & assign
- Dashboard dasar

### Fase 2 — Enhancement (Sedang Berjalan)

- Notifikasi email
- Sistem komentar pada tiket
- Export laporan PDF/Excel
- Pengaturan sistem

### Fase 3 — Advanced (Rencana)

- Mobile app (Flutter/React Native)
- Fitur SLA (Service Level Agreement) tracking
- Eskalasi otomatis
- Analytics lanjutan
- Integrasi dengan sistem akademik

---

## 10. Metrik Keberhasilan

| Metrik                       | Target            |
| ---------------------------- | ----------------- |
| Waktu respons rata-rata      | < 24 jam          |
| Waktu penyelesaian rata-rata | < 7 hari          |
| Persentase tiket selesai     | > 80%             |
| User satisfaction (survey)   | > 4/5             |
| Penggunaan sistem            | > 100 tiket/bulan |

---

## 11. Kontak & Referensi

- **Product Owner**: Riyan Triadi
- **Repository**: (private)
- **Dokumentasi Teknis**: Lihat `CONTEXT.md`
- **Setup Guide**: Lihat `README.md`

---

## Approval

| Nama | Jabatan       | Tanda Tangan | Tanggal |
| ---- | ------------- | ------------ | ------- |
|      | Product Owner |              |         |
|      | Tech Lead     |              |         |
