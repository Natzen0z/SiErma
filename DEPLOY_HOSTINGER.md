# =============================================================================
# HOSTINGER DEPLOYMENT GUIDE — SI ERMa
# =============================================================================
#
# OPSI 1: RECOMMENDED (Symlink)
# -----------------------------------------------
# 1. Upload seluruh project ke folder di luar public_html, contoh:
#    /home/u123456789/sierma/
#
# 2. Buat symlink dari public_html ke folder public:
#    Di terminal SSH:
#    ln -s /home/u123456789/sierma/public /home/u123456789/public_html
#
# 3. Buat file .env di /home/u123456789/sierma/.env
#    (copy dari .env.example dan isi dengan data Hostinger MySQL)
#
# 4. Jalankan:
#    cd /home/u123456789/sierma
#    php artisan key:generate
#    php artisan migrate
#    php artisan db:seed
#    php artisan storage:link
#
#
# OPSI 2: Upload ke public_html langsung
# -----------------------------------------------
# 1. Upload SEMUA file ke /home/u123456789/domains/yourdomain.com/
#    KECUALI isi folder /public — taruh isi /public di public_html
#
# 2. Edit public_html/index.php:
#    Ubah path require:
#
#    require __DIR__.'/../sierma/vendor/autoload.php';
#    $app = require_once __DIR__.'/../sierma/bootstrap/app.php';
#
# 3. Set .env DB credentials dari Hostinger MySQL panel
#
#
# HOSTINGER MYSQL SETUP:
# -----------------------------------------------
# 1. Login ke hPanel → Databases → MySQL Databases
# 2. Buat database baru (misal: u123456789_sierma)
# 3. Buat user baru atau gunakan yang ada
# 4. Assign user ke database dengan ALL PRIVILEGES
# 5. Isi .env:
#    DB_CONNECTION=mysql
#    DB_HOST=localhost     ← Hostinger pakai localhost
#    DB_PORT=3306
#    DB_DATABASE=u123456789_sierma
#    DB_USERNAME=u123456789_sierma_user
#    DB_PASSWORD=your_password_here
#
#
# POST-DEPLOY CHECKLIST:
# -----------------------------------------------
# [ ] APP_ENV=production
# [ ] APP_DEBUG=false
# [ ] APP_URL=https://yourdomain.com
# [ ] php artisan key:generate
# [ ] php artisan migrate
#    php artisan db:seed
# [ ] php artisan storage:link
# [ ] php artisan config:cache
# [ ] php artisan route:cache
# [ ] php artisan view:cache
# [ ] Pastikan folder storage/ dan bootstrap/cache/ writable (chmod 775)
#
# =============================================================================
