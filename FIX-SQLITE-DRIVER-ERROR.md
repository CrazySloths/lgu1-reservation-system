# Fix SQLite Driver Not Found Error

**Date:** October 21, 2025  
**Error:** `could not find driver (Connection: sqlite)`  
**Cause:** PHP SQLite extension is not installed or enabled on the server

---

## THE PROBLEM

When running any database command (like `php artisan migrate:status`), you get:

```
Illuminate\Database\QueryException

could not find driver (Connection: sqlite, SQL: select exists 
(select 1 from "main".sqlite_master where name = 'migrations' 
and type = 'table') as "exists")
```

**Root Cause:** PHP doesn't have the PDO SQLite extension loaded.

---

## SOLUTION 1: Enable SQLite Extension (If Already Installed)

### Step 1: Find Your php.ini File

```bash
php --ini
```

This will show you the path to your `php.ini` file.

### Step 2: Edit php.ini

```bash
sudo nano /etc/php/8.3/cli/php.ini
# Or wherever your php.ini is located
```

### Step 3: Uncomment/Add SQLite Extensions

Find and uncomment (remove the `;` at the beginning):

```ini
extension=pdo_sqlite
extension=sqlite3
```

If these lines don't exist, add them to the extensions section.

### Step 4: Also Check php.ini for Web Server (FPM/Apache)

```bash
# For PHP-FPM (most common)
sudo nano /etc/php/8.3/fpm/php.ini

# For Apache
sudo nano /etc/php/8.3/apache2/php.ini
```

Add/uncomment the same extensions:
```ini
extension=pdo_sqlite
extension=sqlite3
```

### Step 5: Restart PHP/Web Server

```bash
# For PHP-FPM
sudo systemctl restart php8.3-fpm

# For Apache
sudo systemctl restart apache2

# For Nginx + PHP-FPM
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

---

## SOLUTION 2: Install SQLite Extension (If Not Installed)

### For Ubuntu/Debian:

```bash
# Update package list
sudo apt update

# Install PHP SQLite extensions
sudo apt install php8.3-sqlite3 php8.3-pdo-sqlite

# Or for general PHP installation
sudo apt install php-sqlite3 php-pdo-sqlite

# Restart PHP service
sudo systemctl restart php8.3-fpm
# or
sudo systemctl restart apache2
```

### For CentOS/RHEL:

```bash
# Install SQLite extensions
sudo yum install php-pdo php-sqlite3

# Restart PHP service
sudo systemctl restart php-fpm
```

### For Other Systems:

```bash
# Generic approach
sudo apt install php-sqlite3
# or
sudo yum install php-sqlite3
```

---

## VERIFY THE FIX

### Check if SQLite is Enabled

```bash
php -m | grep -i sqlite
```

**Expected output:**
```
pdo_sqlite
sqlite3
```

If you see both `pdo_sqlite` and `sqlite3`, the extensions are loaded!

### Test Database Connection

```bash
cd /home/facilities.local-government-unit-1-ph.com/public_html/
php artisan tinker
```

In tinker, run:
```php
DB::connection()->getPdo();
```

If successful, you'll see: `PDO {...}` without errors. Type `exit` to quit.

---

## ALTERNATIVE: Use MySQL Instead of SQLite

If you can't install SQLite or prefer MySQL, here's how to switch:

### Step 1: Create MySQL Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE lgu1_facilities;
CREATE USER 'lgu1_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON lgu1_facilities.* TO 'lgu1_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 2: Update .env File

```bash
nano /home/facilities.local-government-unit-1-ph.com/public_html/.env
```

Change from:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite
```

To:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lgu1_facilities
DB_USERNAME=lgu1_user
DB_PASSWORD=your_password
```

### Step 3: Clear Config and Run Migrations

```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate --force
```

---

## TROUBLESHOOTING

### Error: "Package not found"

Your PHP version might be different. Check your PHP version:

```bash
php -v
```

Then install for your specific version:
```bash
# For PHP 8.2
sudo apt install php8.2-sqlite3 php8.2-pdo-sqlite

# For PHP 8.1
sudo apt install php8.1-sqlite3 php8.1-pdo-sqlite

# For PHP 8.0
sudo apt install php8.0-sqlite3 php8.0-pdo-sqlite
```

### Error: "Permission denied" when editing php.ini

Use `sudo`:
```bash
sudo nano /etc/php/8.3/cli/php.ini
```

### Changes Don't Take Effect

Make sure you:
1. Edited the correct php.ini file (both CLI and FPM/Apache)
2. Restarted the web server/PHP service
3. Cleared Laravel's config cache

```bash
php artisan config:clear
sudo systemctl restart php8.3-fpm
```

---

## QUICK DIAGNOSTIC COMMANDS

Run these to diagnose the issue:

```bash
# 1. Check PHP version
php -v

# 2. Check loaded PHP modules
php -m

# 3. Search for SQLite in loaded modules
php -m | grep -i sqlite

# 4. Find php.ini location
php --ini

# 5. Check if SQLite package is installed (Ubuntu/Debian)
dpkg -l | grep php | grep sqlite

# 6. Check PHP-FPM status
sudo systemctl status php8.3-fpm
```

---

## SUMMARY

**Most Common Fix (Ubuntu/Debian):**

```bash
# Install SQLite extensions
sudo apt update
sudo apt install php-sqlite3 php-pdo-sqlite

# Restart web server
sudo systemctl restart php8.3-fpm
# or
sudo systemctl restart apache2

# Verify
php -m | grep -i sqlite

# Test
cd /home/facilities.local-government-unit-1-ph.com/public_html/
php artisan migrate:status
```

**If Successful:** You'll see the migrations table and can proceed with `php artisan migrate --force`.

---

## CONTACT YOUR HOSTING PROVIDER

If you're on shared hosting and can't install packages:

1. **Contact your hosting provider** and ask them to enable the SQLite PHP extension
2. Or ask them to provide MySQL database access
3. Some hosting providers have a control panel (cPanel/Plesk) where you can enable PHP extensions

---

**Next Steps After Fix:**

Once SQLite is working, run:
```bash
php artisan migrate:status
php artisan migrate --force
```

Then all database features will work! ✅

