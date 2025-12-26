# Panduan Deployment Lokal - Machine Breakdown Reporting System
## 🏠 Untuk Pengembangan di Local Environment (Windows/Linux/Mac)

## 📋 Prasyarat Sistem
- **PHP**: 8.1 atau lebih tinggi (disarankan 8.3)
- **Composer**: Versi terbaru
- **Node.js**: 18.x atau lebih tinggi
- **NPM**: Versi terbaru
- **Database**: MySQL 8.0+ atau MariaDB 10.4+
- **Git**: Untuk clone repository

## 🚀 Langkah 1: Persiapan Environment

### 1.1 Clone Repository
```bash
# Clone repository dari GitHub
git clone https://github.com/ChaisarAbi/reporting.git machine-breakdown-reporting-system
cd machine-breakdown-reporting-system
```

### 1.2 Install PHP (Jika Belum)
#### Untuk Windows:
1. Download XAMPP dari https://www.apachefriends.org/
2. Install dengan pilihan PHP 8.3
3. Tambahkan PHP ke PATH:
   - C:\xampp\php (sesuaikan dengan lokasi instalasi)

#### Untuk Linux (Ubuntu/Debian):
```bash
sudo apt update
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql \
php8.3-pgsql php8.3-sqlite3 php8.3-gd php8.3-curl \
php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath \
php8.3-intl php8.3-readline
```

#### Untuk Mac:
```bash
# Menggunakan Homebrew
brew install php@8.3
brew link php@8.3
```

### 1.3 Install Composer
```bash
# Download dan install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Pindahkan ke directory system (Linux/Mac)
sudo mv composer.phar /usr/local/bin/composer

# Untuk Windows, pindahkan ke directory yang ada di PATH
# atau gunakan Composer Installer dari https://getcomposer.org/
```

### 1.4 Install Node.js & NPM
Download dari https://nodejs.org/ (disarankan versi LTS)

## 🗄️ Langkah 2: Setup Database

### 2.1 Buat Database
```sql
-- Buka MySQL/MariaDB
mysql -u root -p

-- Buat database
CREATE DATABASE breakdown_reporting;

-- Buat user (opsional)
CREATE USER 'reporting_user'@'localhost' IDENTIFIED BY 'password_aman';
GRANT ALL PRIVILEGES ON breakdown_reporting.* TO 'reporting_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2.2 Alternatif: Gunakan XAMPP phpMyAdmin
1. Buka http://localhost/phpmyadmin
2. Buat database baru: `breakdown_reporting`
3. Buat user baru atau gunakan `root` (untuk development)

## 📦 Langkah 3: Konfigurasi Aplikasi

### 3.1 Copy Environment File
```bash
# Copy file .env.example ke .env
cp .env.example .env
```

### 3.2 Konfigurasi Environment (.env)
Edit file `.env` dengan text editor favorit Anda:

```env
APP_NAME="Machine Breakdown Reporting System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=breakdown_reporting
DB_USERNAME=root
DB_PASSWORD=

# Untuk XAMPP default:
# DB_USERNAME=root
# DB_PASSWORD=

# Untuk user khusus:
# DB_USERNAME=reporting_user
# DB_PASSWORD=password_aman
```

### 3.3 Generate Application Key
```bash
php artisan key:generate
```

## 📦 Langkah 4: Install Dependencies

### 4.1 Install PHP Dependencies
```bash
composer install
```

### 4.2 Install NPM Dependencies
```bash
npm install
```

### 4.3 Build Assets
```bash
npm run build
```

## 🗃️ Langkah 5: Setup Database

### 5.1 Run Database Migrations
```bash
php artisan migrate
```

### 5.2 Seed Database (Data Contoh)
```bash
php artisan db:seed
```

### 5.3 Generate Storage Link
```bash
php artisan storage:link
```

## 🚀 Langkah 6: Menjalankan Aplikasi

### 6.1 Serve dengan Laravel Development Server
```bash
php artisan serve
```
Aplikasi akan berjalan di: http://localhost:8000

### 6.2 Serve dengan Vite (Hot Reload untuk Development)
```bash
# Terminal 1: Jalankan Vite dev server
npm run dev

# Terminal 2: Jalankan Laravel server
php artisan serve
```

### 6.3 Build untuk Production Mode
```bash
npm run build
php artisan serve
```

## 👤 Langkah 7: Setup User & Testing

### 7.1 Akses Aplikasi
1. Buka browser: http://localhost:8000
2. Register user baru atau login dengan:
   - Email: admin@example.com
   - Password: password

### 7.2 User Default dari Seeder
```bash
# Jalankan seeder untuk membuat user default
php artisan db:seed --class=UserSeeder
```

User default yang dibuat:
1. **Admin**:
   - Email: admin@example.com
   - Password: password
   - Role: admin

2. **Maintenance**:
   - Email: maintenance@example.com
   - Password: password
   - Role: maintenance

3. **Operator**:
   - Email: operator@example.com
   - Password: password
   - Role: operator

## 🔧 Langkah 8: Konfigurasi Tambahan

### 8.1 Mail Configuration (Opsional)
Untuk testing email, gunakan Mailtrap:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 8.2 Queue Configuration (Opsional)
```env
QUEUE_CONNECTION=database
```

Jalankan queue worker:
```bash
php artisan queue:work
```

## 🧪 Langkah 9: Testing Aplikasi

### 9.1 Unit Testing
```bash
php artisan test
```

### 9.2 Manual Testing
1. **Login** dengan user berbeda (admin, maintenance, operator)
2. **Buat laporan kerusakan** sebagai operator
3. **Proses perbaikan** sebagai maintenance
4. **Lihat analytics** sebagai admin/maintenance

### 9.3 Test Fitur Utama:
- ✅ Buat laporan kerusakan
- ✅ Lihat dashboard
- ✅ Proses perbaikan
- ✅ Export PDF/Excel
- ✅ Analytics & chart

## 🐛 Langkah 10: Troubleshooting

### 10.1 Common Issues & Solutions

#### Issue 1: "Class 'PDO' not found"
**Solution:**
```bash
# Install PHP PDO extension
sudo apt install php8.3-mysql  # Ubuntu/Debian
# atau aktifkan extension di php.ini
```

#### Issue 2: "SQLSTATE[HY000] [1045] Access denied"
**Solution:** Periksa konfigurasi database di `.env`

#### Issue 3: "Vite manifest not found"
**Solution:**
```bash
npm run build
```

#### Issue 4: "Permission denied" untuk storage
**Solution:**
```bash
# Linux/Mac
sudo chmod -R 775 storage bootstrap/cache

# Windows (jalankan sebagai administrator)
icacls storage /grant Users:(OI)(CI)F
icacls bootstrap/cache /grant Users:(OI)(CI)F
```

#### Issue 5: "GD extension not available" untuk chart
**Solution:**
```bash
# Install GD extension
sudo apt install php8.3-gd  # Ubuntu/Debian

# Windows: Aktifkan extension di php.ini
# Cari: ;extension=gd dan hapus titik koma
```

### 10.2 Debug Mode
Untuk debugging, pastikan di `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

Lihat log di: `storage/logs/laravel.log`

## 📁 Struktur Project
```
machine-breakdown-reporting-system/
├── app/                    # Source code aplikasi
│   ├── Http/Controllers/  # Controller
│   ├── Models/            # Eloquent models
│   ├── Helpers/           # Helper classes
│   └── ...
├── database/              # Migrations & seeders
├── resources/             # Views, CSS, JS
├── routes/               # Route definitions
├── storage/              # Storage, logs, cache
├── public/               # Public assets
└── tests/                # Test files
```

## 🛠️ Development Tools

### 1. Tinker (Laravel REPL)
```bash
php artisan tinker
# Test models, relationships, dll
```

### 2. Route List
```bash
php artisan route:list
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 4. Generate Resources
```bash
# Generate controller
php artisan make:controller NamaController

# Generate model dengan migration
php artisan make:model NamaModel -m

# Generate seeder
php artisan make:seeder NamaSeeder
```

## 🔄 Workflow Development

### 1. Pull Perubahan Terbaru
```bash
git pull origin master
composer install
npm install
npm run build
php artisan migrate
```

### 2. Buat Fitur Baru
```bash
# Buat branch baru
git checkout -b fitur-baru

# Setelah selesai development
git add .
git commit -m "Tambahkan fitur baru"
git push origin fitur-baru
```

### 3. Update Database Schema
```bash
# Buat migration
php artisan make:migration nama_migration

# Jalankan migration
php artisan migrate

# Rollback migration
php artisan migrate:rollback
```

## 📊 Database Schema Overview

### Tabel Utama:
1. **users** - User authentication & roles
2. **machines** - Data mesin
3. **breakdown_reports** - Laporan kerusakan
4. **event_types** - Jenis event kerusakan
5. **cause_types** - Penyebab kerusakan
6. **part_types** - Jenis spare part
7. **breakdown_events** - Relasi report dengan event
8. **breakdown_causes** - Relasi report dengan cause
9. **breakdown_parts** - Spare part yang digunakan
10. **breakdown_responsibilities** - Tanggung jawab perbaikan

## 🎯 Checklist Deployment Lokal

- [ ] PHP 8.1+ terinstall
- [ ] Composer terinstall
- [ ] Node.js & NPM terinstall
- [ ] Database MySQL/MariaDB tersedia
- [ ] Repository di-clone
- [ ] File .env dikonfigurasi
- [ ] Dependencies di-install (composer & npm)
- [ ] Database migrated & seeded
- [ ] Storage link dibuat
- [ ] Aplikasi bisa diakses di http://localhost:8000
- [ ] User default bisa login
- [ ] Fitur utama berfungsi

## 📞 Support & Troubleshooting

### Jika mengalami masalah:
1. **Cek log**: `tail -f storage/logs/laravel.log`
2. **Clear cache**: `php artisan optimize:clear`
3. **Reinstall dependencies**: `composer install && npm install`
4. **Re-migrate database**: `php artisan migrate:fresh --seed`

### Resources:
- **Laravel Documentation**: https://laravel.com/docs
- **GitHub Repository**: https://github.com/ChaisarAbi/reporting
- **Stack Overflow**: Tag [laravel], [php]

---

## 🚀 **Quick Start Script (Windows)**
Buat file `start.bat`:
```batch
@echo off
echo Starting Machine Breakdown Reporting System...
cd /d "%~dp0"

echo 1. Installing PHP dependencies...
composer install

echo 2. Installing NPM dependencies...
npm install

echo 3. Building assets...
npm run build

echo 4. Running migrations...
php artisan migrate --seed

echo 5. Starting development server...
php artisan serve
```

## 🚀 **Quick Start Script (Linux/Mac)**
Buat file `start.sh`:
```bash
#!/bin/bash
echo "Starting Machine Breakdown Reporting System..."

echo "1. Installing PHP dependencies..."
composer install

echo "2. Installing NPM dependencies..."
npm install

echo "3. Building assets..."
npm run build

echo "4. Running migrations..."
php artisan migrate --seed

echo "5. Starting development server..."
php artisan serve
```

Jalankan: `chmod +x start.sh && ./start.sh`

---

**Selamat!** 🎉 Aplikasi Machine Breakdown Reporting System siap digunakan di local environment Anda. Untuk deployment ke production, lihat file `DEPLOYMENT.md`.
