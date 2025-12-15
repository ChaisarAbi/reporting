# Sistem Pelaporan Kerusakan Mesin

Aplikasi web untuk pelaporan dan tracking kerusakan mesin pabrik menggunakan Laravel 11.

## Fitur Utama

### 🔐 Autentikasi & Role
- Login menggunakan email & password
- Dua role: Leader Operator & Leader Teknisi
- Redirect otomatis berdasarkan role setelah login

### 👷 Leader Operator
- Dashboard dengan statistik laporan
- Form pembuatan laporan kerusakan
- Melihat status laporan yang dibuat
- Filter laporan berdasarkan status, urgensi, mesin, tanggal

### 🔧 Leader Teknisi (Maintenance)
- Dashboard dengan overview semua laporan
- Menerima laporan dari operator
- Memulai dan menyelesaikan perbaikan
- Form detail maintenance dengan 41 jenis kerusakan, 25 penyebab, 50 part
- Analitik dan grafik kerusakan mesin

## Teknologi

- **Backend**: Laravel 11
- **Frontend**: Blade Template + TailwindCSS
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Charts**: Chart.js
- **Additional**: Alpine.js

## Instalasi & Setup

### 1. Clone & Setup Environment
```bash
cd machine-breakdown-reporting-system
cp .env.example .env
php artisan key:generate
```

### 2. Setup Database
- Buat database MySQL dengan nama `machine_breakdown`
- Update konfigurasi database di file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=machine_breakdown
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Jalankan Migration & Seeder
```bash
php artisan migrate --seed
```

### 4. Jalankan Development Server
```bash
php artisan serve
```

Aplikasi akan berjalan di: http://127.0.0.1:8000

## Akun Test

### Leader Operator
- **Email**: operator@example.com
- **Password**: password
- **URL Dashboard**: http://127.0.0.1:8000/operator/dashboard

### Leader Teknisi
- **Email**: teknisi@example.com
- **Password**: password
- **URL Dashboard**: http://127.0.0.1:8000/maintenance/dashboard

## Struktur Database

### Tabel Utama
- `users` - Data pengguna dengan role
- `machines` - Data mesin pabrik
- `breakdown_reports` - Laporan kerusakan
- `event_types` - 41 jenis kerusakan (7 kategori)
- `cause_types` - 25 penyebab kerusakan
- `part_types` - 50 part yang dapat diganti

### Tabel Relasi
- `breakdown_events` - Pivot table jenis kerusakan
- `breakdown_causes` - Pivot table penyebab kerusakan
- `breakdown_parts` - Part yang diganti per laporan
- `breakdown_responsibilities` - Tanggung jawab perbaikan

## Alur Kerja

### 1. Operator Membuat Laporan
1. Login sebagai Leader Operator
2. Klik "Buat Laporan Baru"
3. Isi form: mesin, bagian, line, shift, masalah, urgensi
4. Kirim laporan → Status: "Baru"

### 2. Teknisi Menerima & Memperbaiki
1. Login sebagai Leader Teknisi
2. Lihat daftar laporan "Baru"
3. Klik "Lihat Detail" → "Mulai Perbaikan"
4. Status berubah: "Sedang Diperbaiki"

### 3. Teknisi Menyelesaikan Perbaikan
1. Isi form detail maintenance:
   - Jenis kerusakan (multi-select dari 41 item)
   - Penyebab kerusakan (multi-select dari 25 item)
   - Part yang diganti (tabel dinamis)
   - Tanggung jawab
   - Status mesin
   - Catatan teknisi
2. Klik "Selesaikan Perbaikan"
3. Status berubah: "Selesai"

## Fitur yang Sudah Tersedia

- ✅ Autentikasi dengan role-based access
- ✅ Dashboard untuk kedua role
- ✅ Form pembuatan laporan operator
- ✅ Alur status: New → In Progress → Done
- ✅ Filter laporan
- ✅ Navigation menu berdasarkan role
- ✅ Seeder data master (41 jenis kerusakan, 25 penyebab, 50 part)
- ✅ Sample user dan mesin

## Fitur yang Akan Ditambahkan

- [ ] Form detail maintenance lengkap
- [ ] Export Excel, CSV, PDF
- [ ] Dashboard analitik dengan Chart.js
- [ ] View detail laporan lengkap
- [ ] Validasi form yang lebih komprehensif

## Troubleshooting

### Masalah: Dashboard masih menampilkan tampilan default Laravel
**Solusi**: Pastikan mengakses URL yang benar:
- Operator: `/operator/dashboard`
- Teknisi: `/maintenance/dashboard`

### Masalah: Error "Route [dashboard] not defined"
**Solusi**: Route `/dashboard` sudah dihapus. Gunakan `/home` untuk redirect otomatis berdasarkan role.

### Masalah: Database error
**Solusi**: Pastikan migration dan seeder sudah dijalankan:
```bash
php artisan migrate:fresh --seed
```

## Kontribusi

Sistem ini siap untuk pengembangan lebih lanjut dengan struktur yang solid dan clean code Laravel.
