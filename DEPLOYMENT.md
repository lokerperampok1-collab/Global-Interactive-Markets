# Deployment Guide

This document provides step-by-step instructions to deploy the **Global Interactive Markets** web application to a production server (Ubuntu VPS with Nginx, PHP-FPM, MySQL/PostgreSQL, and SSL).

---

## 📋 System Requirements

*   **PHP** 8.3 or higher (with extensions: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `curl`)
*   **Web Server**: Nginx or Apache
*   **Database**: MySQL 8.0+, PostgreSQL, or SQLite
*   **Composer** (PHP Package Manager)
*   **Node.js** 20+ & **npm** (for building assets)
*   **Git** (for version control & deployment)

---

## 🚀 Step-by-Step Production Deployment

### 1. Clone the Repository
Clone the codebase to your server's web directory (usually `/var/www/`):
```bash
cd /var/www
git clone https://github.com/lokerperampok1-collab/Global-Interactive-Markets.git global-interactive-markets
cd global-interactive-markets
```

### 2. Install PHP Dependencies
Run composer to install all backend dependencies for production:
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Install and Build Frontend Assets
Install Node.js packages and compile the production bundle:
```bash
npm install
npm run build
```

### 4. Configure Environment Variables
Copy the template configuration file:
```bash
cp .env.example .env
```
Generate the unique application key:
```bash
php artisan key:generate --force
```

Open the `.env` file and configure production parameters:
```env
APP_NAME="Global Interactive Markets"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_production_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_db_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 5. Run Database Migrations & Seeding
Prepare your database and insert initial data (including investment plans and admin credentials):
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 6. Set Up File Permissions
Laravel requires write permissions on the `storage` and `bootstrap/cache` directories.
```bash
sudo chown -R www-data:www-data /var/www/global-interactive-markets
sudo find /var/www/global-interactive-markets -type f -exec chmod 644 {} \;
sudo find /var/www/global-interactive-markets -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/global-interactive-markets/storage
sudo chmod -R 775 /var/www/global-interactive-markets/bootstrap/cache
```

### 7. Configure Nginx Server Block
Create a server configuration block at `/etc/nginx/sites-available/global-interactive-markets`:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/global-interactive-markets/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
Enable the site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/global-interactive-markets /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 8. Set Up SSL with Let's Encrypt
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 9. Optimize Configuration
Cache configuration, routes, and views for optimal performance:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔑 Default Credentials

### Admin Login
*   **URL**: `https://yourdomain.com/login`
*   **Email**: `admin@globalinteractivemarkets.com`
*   **Password**: `admin123`

---

## 🛠️ Maintenance & Troubleshooting

*   **Checking Logs**: Errors are logged in `storage/logs/laravel.log`.
*   **Clear Cache**: If you make modifications, clear caches using:
    ```bash
    php artisan optimize:clear
    ```
*   **Queue Worker**: Start a queue worker to handle background jobs:
    ```bash
    php artisan queue:work --queue=default --timeout=60 --tries=3
    ```
