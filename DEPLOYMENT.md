# Deployment Guide - Machine Breakdown Reporting System
## 🎯 Untuk VPS dengan PHP 8.3 & Ubuntu 24.04

## 📋 Prasyarat VPS
- **OS**: Ubuntu 24.04 LTS (Noble Numbat)
- **PHP**: 8.3 (default di Ubuntu 24.04)
- **RAM**: Minimum 2GB (4GB direkomendasikan)
- **Storage**: Minimum 20GB
- **Domain**: Domain yang sudah diarahkan ke IP VPS (opsional untuk SSL)

## 🏠 Struktur Direktori Khusus
Untuk menghindari konflik dengan project lain, kita akan menggunakan struktur direktori khusus:
```
/var/www/reporting-system/          # Main directory
├── app/                            # Aplikasi Laravel
├── logs/                           # Logs terpisah
├── backups/                        # Backup database
└── deploy.sh                       # Script deployment
```

## 🚀 Langkah 1: Persiapan Server & Direktori Khusus

### 1.1 Update System
```bash
sudo apt update && sudo apt upgrade -y
```

### 1.2 Install Dependencies
```bash
sudo apt install -y software-properties-common curl git unzip build-essential
```

### 1.3 Buat Direktori Khusus
```bash
sudo mkdir -p /var/www/reporting-system
sudo mkdir -p /var/www/reporting-system/logs
sudo mkdir -p /var/www/reporting-system/backups
sudo chown -R $USER:$USER /var/www/reporting-system
sudo chmod -R 755 /var/www/reporting-system
```

## 🐘 Langkah 2: Install PHP 8.3 (Default di Ubuntu 24.04)

### 2.1 Install PHP 8.3 dan Extensions
```bash
sudo apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql \
php8.3-pgsql php8.3-sqlite3 php8.3-gd php8.3-curl \
php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath \
php8.3-intl php8.3-readline php8.3-ldap php8.3-imagick \
php8.3-redis php8.3-memcached php8.3-xdebug
```

### 2.2 Konfigurasi PHP-FPM Pool Khusus
Buat pool PHP-FPM khusus untuk project ini:
```bash
sudo nano /etc/php/8.3/fpm/pool.d/reporting-system.conf
```

Isi dengan:
```ini
[reporting-system]
user = www-data
group = www-data
listen = /run/php/php8.3-fpm-reporting.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3

php_admin_value[error_log] = /var/www/reporting-system/logs/php-error.log
php_admin_flag[log_errors] = on
```

### 2.3 Restart PHP-FPM
```bash
sudo systemctl restart php8.3-fpm
```

### 2.4 Verifikasi PHP
```bash
php --version
```

## 🗄️ Langkah 3: Install Database (MySQL/MariaDB)

### 3.1 Install MySQL
```bash
sudo apt install -y mysql-server mysql-client
```

### 3.2 Konfigurasi Keamanan MySQL
```bash
sudo mysql_secure_installation
```

### 3.3 Buat Database dan User
```bash
sudo mysql -u root -p
```

Di dalam MySQL:
```sql
CREATE DATABASE breakdown_reporting;
CREATE USER 'reporting_user'@'localhost' IDENTIFIED BY 'password_kuat_disini';
GRANT ALL PRIVILEGES ON breakdown_reporting.* TO 'reporting_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 🌐 Langkah 4: Install Web Server (Nginx)

### 4.1 Install Nginx
```bash
sudo apt install -y nginx
```

### 4.2 Konfigurasi Nginx untuk Laravel (Direktori Khusus)
Buat file konfigurasi khusus:
```bash
sudo nano /etc/nginx/sites-available/reporting-system
```

Isi dengan konfigurasi yang menggunakan socket PHP-FPM khusus:
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/reporting-system/app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    # Logs khusus untuk project ini
    access_log /var/www/reporting-system/logs/nginx-access.log;
    error_log /var/www/reporting-system/logs/nginx-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;
    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/html;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm-reporting.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Timeout settings
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### 4.3 Nonaktifkan default site dan aktifkan site baru
```bash
sudo rm -f /etc/nginx/sites-enabled/default
sudo ln -s /etc/nginx/sites-available/reporting-system /etc/nginx/sites-enabled/
sudo nginx -t
```

### 4.4 Restart Nginx
```bash
sudo systemctl restart nginx
sudo systemctl enable nginx
```

## 📦 Langkah 5: Install Composer

### 5.1 Download dan Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### 5.2 Verifikasi Composer
```bash
composer --version
```

## 🚀 Langkah 6: Deploy Aplikasi ke Direktori Khusus

### 6.1 Clone Repository ke Direktori Khusus
```bash
cd /var/www/reporting-system
git clone https://github.com/ChaisarAbi/reporting.git app
cd app
```

### 6.2 Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 6.3 Konfigurasi Environment untuk Production
```bash
cp .env.example .env
nano .env
```

Update konfigurasi penting untuk production:
```env
APP_NAME="Machine Breakdown Reporting System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=breakdown_reporting
DB_USERNAME=reporting_user
DB_PASSWORD=password_kuat_disini

# Session Configuration (gunakan database untuk session)
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache Configuration
CACHE_DRIVER=file

# Queue Configuration
QUEUE_CONNECTION=database

# Logging Configuration
LOG_CHANNEL=stack
LOG_STACK=single,daily
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null
```

### 6.4 Generate Application Key
```bash
php artisan key:generate
```

### 6.5 Set Permission untuk Direktori Khusus
```bash
sudo chown -R www-data:www-data /var/www/reporting-system
sudo chmod -R 755 /var/www/reporting-system
sudo chmod -R 775 /var/www/reporting-system/app/storage
sudo chmod -R 775 /var/www/reporting-system/app/bootstrap/cache
sudo chmod -R 775 /var/www/reporting-system/logs
```

### 6.6 Konfigurasi Logging ke Direktori Khusus
Edit file `.env` tambahkan:
```env
LOG_CHANNEL=daily
LOG_LEVEL=debug
LOG_DAILY_DAYS=14
LOG_SLACK_WEBHOOK_URL=
LOG_PAPERTRAIL_URL=
LOG_PAPERTRAIL_PORT=
LOG_STDERR_FORMATTER=
LOG_STDERR_FORMATTER_WITH_CONTEXT=true
```

Buat symlink untuk logs Laravel ke direktori khusus:
```bash
ln -sf /var/www/reporting-system/logs/laravel.log /var/www/reporting-system/app/storage/logs/laravel.log
```

## 🗃️ Langkah 7: Setup Database

### 7.1 Migrasi Database
```bash
cd /var/www/reporting-system/app
php artisan migrate --force
```

### 7.2 Seed Data (Opsional)
```bash
php artisan db:seed --force
```

### 7.3 Generate Storage Link
```bash
php artisan storage:link
```

### 7.4 Backup Database Script
Buat script backup otomatis:
```bash
sudo nano /var/www/reporting-system/backup-db.sh
```

Isi dengan:
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/www/reporting-system/backups"
DB_NAME="breakdown_reporting"
DB_USER="reporting_user"

mysqldump -u $DB_USER -p $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql
gzip $BACKUP_DIR/db_backup_$DATE.sql

# Hapus backup lebih dari 7 hari
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete

echo "Backup completed: db_backup_$DATE.sql.gz"
```

Beri permission:
```bash
chmod +x /var/www/reporting-system/backup-db.sh
```

## 🔒 Langkah 8: Konfigurasi SSL (Opsional tapi Direkomendasikan)

### 8.1 Install Certbot untuk Ubuntu 24.04
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 8.2 Generate SSL Certificate untuk Domain
```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### 8.3 Auto-renew SSL dengan Cron
```bash
sudo crontab -e
```

Tambahkan:
```bash
0 12 * * * /usr/bin/certbot renew --quiet
```

### 8.4 Verifikasi SSL
```bash
sudo certbot renew --dry-run
```

## ⚙️ Langkah 9: Konfigurasi Queue & Scheduler (Direktori Khusus)

### 9.1 Setup Supervisor untuk Queue dengan Konfigurasi Khusus
```bash
sudo apt install -y supervisor
sudo nano /etc/supervisor/conf.d/reporting-worker.conf
```

Isi dengan konfigurasi untuk direktori khusus:
```ini
[program:reporting-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/reporting-system/app/artisan queue:work --sleep=3 --tries=3 --queue=default
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/reporting-system/logs/worker.log
stopwaitsecs=3600
environment=HOME="/var/www/reporting-system",USER="www-data"
```

### 9.2 Setup Cron Job untuk Scheduler dengan Direktori Khusus
```bash
sudo crontab -u www-data -e
```

Tambahkan:
```bash
* * * * * cd /var/www/reporting-system/app && php artisan schedule:run >> /var/www/reporting-system/logs/scheduler.log 2>&1
```

### 9.3 Setup Supervisor untuk Horizon (jika digunakan)
```bash
sudo nano /etc/supervisor/conf.d/reporting-horizon.conf
```

Isi dengan:
```ini
[program:reporting-horizon]
process_name=%(program_name)s
command=php /var/www/reporting-system/app/artisan horizon
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/reporting-system/logs/horizon.log
stopwaitsecs=3600
```

### 9.4 Restart Supervisor
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start reporting-worker:*
sudo supervisorctl start reporting-horizon
```

## 🧪 Langkah 10: Testing Deployment

### 10.1 Clear Cache
```bash
cd /var/www/reporting-system/app
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 10.2 Test Aplikasi
```bash
# Test local
curl -I http://localhost

# Test dengan domain
curl -I https://your-domain.com

# Test API endpoint
curl -I https://your-domain.com/api/health
```

### 10.3 Check Logs di Direktori Khusus
```bash
tail -f /var/www/reporting-system/logs/laravel.log
tail -f /var/www/reporting-system/logs/nginx-error.log
tail -f /var/www/reporting-system/logs/worker.log
```

### 10.4 Test Database Connection
```bash
cd /var/www/reporting-system/app
php artisan tinker
>>> DB::connection()->getPdo()
```

## 🔄 Langkah 11: Update Aplikasi (Deployment Script Khusus)

Buat file `deploy.sh` di direktori khusus:
```bash
sudo nano /var/www/reporting-system/deploy.sh
```

Isi dengan script deployment yang lengkap:
```bash
#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🚀 Starting deployment for Reporting System...${NC}"

# Navigate to app directory
cd /var/www/reporting-system/app

# Backup database before deployment
echo -e "${YELLOW}📦 Creating database backup...${NC}"
/var/www/reporting-system/backup-db.sh

# Pull latest changes
echo -e "${YELLOW}⬇️  Pulling latest changes from Git...${NC}"
git pull origin master

# Install PHP dependencies
echo -e "${YELLOW}📦 Installing PHP dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction

# Install NPM dependencies and build assets
echo -e "${YELLOW}📦 Installing NPM dependencies...${NC}"
npm install --silent
npm run build --silent

# Run database migrations
echo -e "${YELLOW}🗄️  Running database migrations...${NC}"
php artisan migrate --force

# Clear all caches
echo -e "${YELLOW}🧹 Clearing caches...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan cache:clear

# Set permissions
echo -e "${YELLOW}🔒 Setting permissions...${NC}"
sudo chown -R www-data:www-data /var/www/reporting-system
sudo chmod -R 775 /var/www/reporting-system/app/storage
sudo chmod -R 775 /var/www/reporting-system/app/bootstrap/cache
sudo chmod -R 775 /var/www/reporting-system/logs

# Restart services
echo -e "${YELLOW}🔄 Restarting services...${NC}"
sudo supervisorctl restart reporting-worker:*
sudo supervisorctl restart reporting-horizon
sudo systemctl reload php8.3-fpm
sudo systemctl reload nginx

echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo -e "${GREEN}🌐 Application is live at: https://your-domain.com${NC}"
```

Beri permission:
```bash
sudo chmod +x /var/www/reporting-system/deploy.sh
sudo chown www-data:www-data /var/www/reporting-system/deploy.sh
```

### 11.1 Cara Menggunakan Deployment Script
```bash
# Jalankan deployment
sudo -u www-data /var/www/reporting-system/deploy.sh

# Atau dengan user biasa
cd /var/www/reporting-system
./deploy.sh
```

## 🚨 Troubleshooting (PHP 8.3 & Direktori Khusus)

### 1. Permission Issues di Direktori Khusus
```bash
# Fix permissions untuk struktur direktori khusus
sudo chown -R www-data:www-data /var/www/reporting-system
sudo chmod -R 755 /var/www/reporting-system
sudo chmod -R 775 /var/www/reporting-system/app/storage
sudo chmod -R 775 /var/www/reporting-system/app/bootstrap/cache
sudo chmod -R 775 /var/www/reporting-system/logs

# Check ownership
ls -la /var/www/reporting-system/
```

### 2. Nginx 502 Bad Gateway (PHP 8.3)
```bash
# Check PHP-FPM 8.3 status
sudo systemctl status php8.3-fpm

# Check socket khusus
ls -la /run/php/php8.3-fpm-reporting.sock

# Check PHP-FPM pool configuration
sudo nano /etc/php/8.3/fpm/pool.d/reporting-system.conf

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm
sudo systemctl reload php8.3-fpm
```

### 3. Database Connection Error
```bash
# Test MySQL connection
mysql -u reporting_user -p breakdown_reporting

# Check .env configuration di direktori khusus
cat /var/www/reporting-system/app/.env | grep DB_

# Test connection dari Laravel
cd /var/www/reporting-system/app
php artisan tinker
>>> DB::connection()->getPdo()
```

### 4. Application Key Missing
```bash
cd /var/www/reporting-system/app
php artisan key:generate
php artisan config:cache
```

### 5. Nginx Error: "Primary script unknown"
```bash
# Check root path di Nginx config
sudo nano /etc/nginx/sites-available/reporting-system

# Pastikan socket path benar
# Harus: fastcgi_pass unix:/run/php/php8.3-fpm-reporting.sock;

# Reload Nginx
sudo nginx -t
sudo systemctl reload nginx
```

### 6. Supervisor Worker Tidak Berjalan
```bash
# Check supervisor status
sudo supervisorctl status

# Restart worker
sudo supervisorctl restart reporting-worker:*

# Check logs
tail -f /var/www/reporting-system/logs/worker.log

# Reload supervisor config
sudo supervisorctl reread
sudo supervisorctl update
```

### 7. SSL Certificate Issues
```bash
# Check SSL certificate
sudo certbot certificates

# Renew certificate
sudo certbot renew --force-renewal

# Check Nginx SSL config
sudo nginx -t
```

## 📊 Monitoring (Direktori Khusus)

### 1. Check Server Status
```bash
# CPU & Memory
htop

# Disk Usage
df -h

# Check space di direktori khusus
du -sh /var/www/reporting-system/

# Check inode usage
df -i
```

### 2. Application Monitoring di Direktori Khusus
```bash
# Check queue workers
sudo supervisorctl status

# Check scheduled tasks
cd /var/www/reporting-system/app
php artisan schedule:list

# View application logs
tail -f /var/www/reporting-system/logs/laravel.log

# View Nginx logs khusus
tail -f /var/www/reporting-system/logs/nginx-access.log
tail -f /var/www/reporting-system/logs/nginx-error.log

# View PHP-FPM logs
tail -f /var/www/reporting-system/logs/php-error.log
```

### 3. Database Monitoring
```bash
# Check database size
mysql -u reporting_user -p -e "SELECT table_schema 'Database', SUM(data_length + index_length) / 1024 / 1024 'Size (MB)' FROM information_schema.TABLES WHERE table_schema = 'breakdown_reporting' GROUP BY table_schema;"

# Check slow queries
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
# Tambahkan: slow_query_log = 1
#           slow_query_log_file = /var/www/reporting-system/logs/mysql-slow.log
#           long_query_time = 2
```

### 4. Performance Monitoring
```bash
# Check PHP-FPM status
sudo systemctl status php8.3-fpm

# Check active connections
sudo netstat -anp | grep php8.3-fpm

# Check Nginx status
sudo systemctl status nginx

# Check memory usage
free -h
```

## 🔧 Environment Variables Penting untuk Production

```env
# ========= SECURITY =========
APP_NAME="Machine Breakdown Reporting System"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain.com

# ========= DATABASE =========
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=breakdown_reporting
DB_USERNAME=reporting_user
DB_PASSWORD=password_kuat_disini

# ========= SESSION =========
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true

# ========= CACHE =========
CACHE_DRIVER=file

# ========= QUEUE =========
QUEUE_CONNECTION=database

# ========= LOGGING =========
LOG_CHANNEL=daily
LOG_LEVEL=error
LOG_DAILY_DAYS=14

# ========= MAIL (jika digunakan) =========
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

# ========= FILE SYSTEM =========
FILESYSTEM_DISK=local
```

## 📞 Support & Maintenance

### Jika mengalami masalah:
1. **Check logs di direktori khusus**:
   ```bash
   # Application logs
   tail -100 /var/www/reporting-system/logs/laravel.log
   
   # Nginx logs
   tail -100 /var/www/reporting-system/logs/nginx-error.log
   
   # PHP-FPM logs
   tail -100 /var/www/reporting-system/logs/php-error.log
   
   # Worker logs
   tail -100 /var/www/reporting-system/logs/worker.log
   ```

2. **Restart services**:
   ```bash
   sudo systemctl restart nginx
   sudo systemctl restart php8.3-fpm
   sudo supervisorctl restart reporting-worker:*
   sudo supervisorctl restart reporting-horizon
   ```

3. **Clear caches**:
   ```bash
   cd /var/www/reporting-system/app
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

4. **Check disk space**:
   ```bash
   df -h
   du -sh /var/www/reporting-system/
   ```

### Backup Routine:
```bash
# Manual backup
/var/www/reporting-system/backup-db.sh

# Schedule backup di crontab (setiap hari jam 2 pagi)
0 2 * * * /var/www/reporting-system/backup-db.sh
```

### Update Routine:
```bash
# Update aplikasi
cd /var/www/reporting-system
./deploy.sh

# Update server packages (bulanan)
sudo apt update && sudo apt upgrade -y
```

---

## 🎯 **Deployment Checklist**
- [ ] Domain diarahkan ke IP VPS
- [ ] SSL certificate terinstal
- [ ] Database dibuat dan user dikonfigurasi
- [ ] Environment variables di-set dengan benar
- [ ] Permission direktori di-set dengan benar
- [ ] Supervisor workers berjalan
- [ ] Cron jobs aktif
- [ ] Backup system berfungsi
- [ ] Monitoring logs aktif

## 🔐 **Security Checklist**
- [ ] APP_DEBUG=false
- [ ] APP_ENV=production
- [ ] Strong database password
- [ ] SSL/TLS enabled
- [ ] Firewall aktif (ufw)
- [ ] Regular security updates
- [ ] Backup encryption (opsional)

---

**Catatan Penting**: 
1. Ganti `your-domain.com` dengan domain Anda
2. Ganti `password_kuat_disini` dengan password yang kuat untuk database user
3. Simpan backup script dan deployment script di safe location
4. Monitor logs secara berkala untuk mendeteksi issues dini
5. Lakukan testing setelah setiap deployment

**🚀 Deployment selesai!** Aplikasi sekarang dapat diakses di:
- **HTTP**: `http://your-domain.com` (redirect ke HTTPS)
- **HTTPS**: `https://your-domain.com`
- **Admin Panel**: `https://your-domain.com/login`

**📧 Untuk support**: Simpan dokumentasi ini dan konfigurasi server untuk referensi maintenance.
