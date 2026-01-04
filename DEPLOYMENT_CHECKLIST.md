# 🚀 Laravel Production Deployment Checklist

## ✅ 500 Error Fix Karne Ke Steps

### 1. **Environment File Setup**
```bash
# .env file create karo hosting pe
cp .env.example .env  # Ya manually .env file banayo

# .env file mein ye values set karo:
APP_NAME="Solar ERP"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Connection
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

### 2. **Composer Install (Dependencies)**
```bash
# SSH se login karke project folder mein jao
composer install --no-dev --optimize-autoloader
```

### 3. **Generate Application Key**
```bash
php artisan key:generate
```

### 4. **Storage & Cache Permissions**
```bash
# Storage folder ko writable banao
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Ownership set karo (agar shared hosting hai to server username use karo)
chown -R www-data:www-data storage bootstrap/cache
```

### 5. **Create Storage Link**
```bash
php artisan storage:link
```

### 6. **Run Migrations**
```bash
php artisan migrate --force
```

### 7. **Clear All Caches**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 8. **Optimize for Production**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. **Check PHP Version**
- **Minimum Required:** PHP 8.1
- Check: `php -v`
- Hosting panel mein PHP version set karo

### 10. **Check Laravel Logs**
```bash
# Error dekhne ke liye
tail -n 50 storage/logs/laravel.log
```

### 11. **Required Extensions**
Hosting pe ye PHP extensions enable hone chahiye:
- ✅ `openssl`
- ✅ `pdo`
- ✅ `mbstring`
- ✅ `tokenizer`
- ✅ `xml`
- ✅ `ctype`
- ✅ `json`
- ✅ `fileinfo`
- ✅ `gd` (images ke liye)

### 12. **Shared Hosting Issues**

Agar shared hosting use kar rahe ho, to:

**Option 1: Document Root**
```
/home/username/public_html -> project ka public/ folder point karo
```

**Option 2: Subdirectory**
```php
// public/index.php mein
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

### 13. **Quick Debug Command**
```bash
# Temporary error enable karo (.env mein)
APP_DEBUG=true

# Phir browser mein check karo ki exact error kya hai
```

### 14. **Common Issues & Solutions**

#### Issue: "500 Internal Server Error"
- ✅ .env file check karo
- ✅ Storage permissions check karo (775)
- ✅ Composer install run karo
- ✅ APP_KEY generated hai check karo

#### Issue: "No application encryption key"
```bash
php artisan key:generate
```

#### Issue: "Class not found"
```bash
composer dump-autoload
```

#### Issue: "Permission denied"
```bash
chmod -R 775 storage bootstrap/cache
```

#### Issue: "Database connection failed"
- ✅ .env mein DB credentials check karo
- ✅ Database server accessible hai verify karo

---

## 📋 Step-by-Step Deployment Commands

```bash
# 1. Project folder mein jao
cd /path/to/your/project

# 2. Dependencies install karo
composer install --no-dev --optimize-autoloader

# 3. Environment setup
cp .env.example .env
# Ya manually .env file edit karo

# 4. Application key generate karo
php artisan key:generate

# 5. Permissions set karo
chmod -R 775 storage bootstrap/cache

# 6. Storage link create karo
php artisan storage:link

# 7. Database migrate karo
php artisan migrate --force

# 8. Optimize karo
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Permissions fix karo (if needed)
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔍 Error Debugging

### Laravel Logs Check:
```bash
tail -f storage/logs/laravel.log
```

### Server Error Logs:
Hosting panel mein "Error Logs" section check karo

### Temporary Debug Mode:
`.env` file mein:
```
APP_DEBUG=true
```
(Production mein phir se `false` kar dena)

---

## 📞 Support

Agar phir bhi error aa raha hai, to:
1. `storage/logs/laravel.log` file share karo
2. Server error logs share karo
3. PHP version check karke batao
4. Hosting provider ka naam batao





