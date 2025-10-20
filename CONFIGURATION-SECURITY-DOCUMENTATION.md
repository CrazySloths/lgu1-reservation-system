# LGU1 Facility Reservation System - Configuration & Security Documentation

**Version:** 1.0  
**Last Updated:** October 21, 2025  
**System:** Laravel 12.24.0 + PHP 8.3.24  
**Database:** SQLite  
**Server:** https://facilities.local-government-unit-1-ph.com

---

## 📋 TABLE OF CONTENTS

1. [System Overview](#1-system-overview)
2. [Current Production Environment](#2-current-production-environment)
3. [Authentication & Authorization](#3-authentication--authorization)
4. [Session Management](#4-session-management)
5. [Multi-Factor Authentication (MFA)](#5-multi-factor-authentication-mfa)
6. [Email Verification](#6-email-verification)
7. [SMS Verification](#7-sms-verification)
8. [Single Sign-On (SSO)](#8-single-sign-on-sso)
9. [Middleware Security](#9-middleware-security)
10. [Database Security (SQLite)](#10-database-security-sqlite)
11. [Environment Variables (ACTUAL)](#11-environment-variables-actual)
12. [Security Warnings & Recommendations](#12-security-warnings--recommendations)
13. [Deployment Checklist](#13-deployment-checklist)
14. [Troubleshooting](#14-troubleshooting)
15. [Maintenance & Support](#15-maintenance--support)

---

## 1. SYSTEM OVERVIEW

### 1.1 Architecture
- **Framework:** Laravel 12.24.0
- **PHP Version:** 8.3.24
- **Authentication:** Laravel Auth + Custom SSO Integration
- **Session Driver:** File-based (stored in `storage/framework/sessions`)
- **Database:** SQLite (file-based)
- **Frontend:** Vite + Tailwind CSS
- **Mail:** Gmail SMTP
- **SMS:** Twilio

### 1.2 User Roles
1. **Admin** - Full system access, facility management, approvals
2. **Staff** - Document verification, requirement checking
3. **Citizen** - Public facility reservation, booking management

### 1.3 Server Environment
- **Domain:** https://facilities.local-government-unit-1-ph.com
- **Server Path:** `/home/facilities.local-government-unit-1-ph.com/public_html/`
- **Database Path:** `/home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite`

---

## 2. CURRENT PRODUCTION ENVIRONMENT

### 2.1 Actual Environment Variables

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:I4c5rsegKtPyNAHX9DxHhd6PyXlOTtJispblZFgD0rs=
APP_DEBUG=true
APP_URL=https://facilities.local-government-unit-1-ph.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug
```

### 2.2 Database Configuration (SQLite)

```env
DB_CONNECTION=sqlite
DB_DATABASE=/home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite
```

**Why SQLite:**
- ✅ Zero configuration
- ✅ File-based (easy backup)
- ✅ Fast for small-to-medium traffic
- ✅ No separate database server needed
- ✅ Perfect for single-server deployments

### 2.3 Session Configuration (File Driver)

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

**Session Storage:** `storage/framework/sessions/`

### 2.4 Cache & Queue Configuration

```env
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
BROADCAST_CONNECTION=log
```

---

## 3. AUTHENTICATION & AUTHORIZATION

### 3.1 Authentication Guards

**Location:** `config/auth.php`

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
```

### 3.2 Password Security

**Current Settings:**
- **Bcrypt Rounds:** 12 (more secure than default 10)
- **Password Reset Expiry:** 60 minutes
- **Password Confirmation Timeout:** 3 hours (10,800 seconds)
- **Reset Throttling:** 60 seconds between requests

**Minimum Requirements:**
- Minimum 8 characters
- Must include uppercase, lowercase, numbers
- Symbols recommended

### 3.3 Role-Based Access Control

**Middleware:** `AdminAuthMiddleware`, `RoleMiddleware`

| Role | Access Level | Routes Protected |
|------|-------------|------------------|
| **Admin** | Full system access | `/admin/*` |
| **Staff** | Verification only | `/staff/*` |
| **Citizen** | Public booking | `/citizen/*` |

---

## 4. SESSION MANAGEMENT

### 4.1 File-Based Sessions

**Storage Location:**
```
/home/facilities.local-government-unit-1-ph.com/public_html/storage/framework/sessions/
```

**Configuration:**
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120        # 2 hours
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

### 4.2 Session Security Features

✅ **CSRF Protection** - Automatic token validation  
✅ **Session Regeneration** - After login/logout  
✅ **HTTP Only Cookies** - JavaScript cannot access  
✅ **Secure Cookies** - HTTPS only (production)  
✅ **SameSite: Lax** - CSRF protection  

### 4.3 Required Permissions

```bash
# Session directory must be writable
chmod -R 775 storage/framework/sessions
chown -R www-data:www-data storage/framework/sessions
```

### 4.4 Session Cleanup

Sessions are automatically cleaned up after expiration. Manual cleanup:

```bash
php artisan session:gc
```

---

## 5. MULTI-FACTOR AUTHENTICATION (MFA)

### 5.1 Two-Factor Authentication (2FA/TOTP)

**Service:** `App\Services\AuthSecurityService`  
**Library:** `pragmarx/google2fa`

#### Features:
- ✅ Google Authenticator compatible
- ✅ Time-based One-Time Password (TOTP)
- ✅ QR Code generation
- ✅ 8 recovery codes per user
- ✅ Recovery code one-time use
- ✅ Audit logging

#### Database Fields:
```php
two_factor_enabled          // boolean
two_factor_secret           // string (encrypted)
two_factor_recovery_codes   // text (JSON array)
two_factor_enabled_at       // timestamp
```

#### Implementation Example:
```php
// Enable 2FA
$authService->generateTotpSecret($user);
$qrCodeUrl = $authService->generateQrCodeUrl($user);

// Verify TOTP code
$isValid = $authService->verifyTotpCode($user, $code);

// Verify recovery code
$isValid = $authService->verifyRecoveryCode($user, $code);
```

---

## 6. EMAIL VERIFICATION

### 6.1 Gmail SMTP Configuration

```env
# === GMAIL SMTP SETTINGS ===
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=lazysloths227@gmail.com
MAIL_PASSWORD=mhjgzjdivzvmpxal        # App Password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="lazysloths227@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
# ===========================
```

### 6.2 Gmail App Password Setup

**Important:** The `MAIL_PASSWORD` is a **Gmail App Password**, not your regular Gmail password.

**To Generate App Password:**
1. Enable 2FA on Gmail account
2. Go to Google Account → Security
3. Select "App passwords"
4. Generate password for "Mail"
5. Use generated password in `.env`

### 6.3 Verification Flow

1. **Registration** → Email sent with verification token
2. **Token Storage** → Saved in `email_verification_token` field
3. **Verification Link** → `route('citizen.auth.verify-email', ['token' => $token])`
4. **Token Expiration** → Tracked via `email_verification_sent_at`

### 6.4 Database Fields

```php
email_verified              // boolean
email_verification_token    // string (hashed)
email_verification_sent_at  // timestamp
```

### 6.5 Mailable Classes

- `App\Mail\EmailVerificationMail` - For existing users
- `App\Mail\RegistrationVerificationMail` - For new registrations

---

## 7. SMS VERIFICATION

### 7.1 Twilio Configuration

```env
TWILIO_SID=AC9e2096ac21ce2381b534b66ed1
TWILIO_AUTH_TOKEN=629b97e4808bd544fe5c1f19609b23ed
TWILIO_FROM=+19789694845
```

**Configuration Location:** `config/services.php`

```php
'twilio' => [
    'sid' => env('TWILIO_SID'),
    'token' => env('TWILIO_AUTH_TOKEN'),
    'from' => env('TWILIO_FROM'),
],
```

### 7.2 Philippine Number Formatting

Auto-formats Philippine mobile numbers:

| Input Format | Converted To |
|-------------|-------------|
| `09XX XXX XXXX` | `+639XXXXXXXX` |
| `9XXXXXXXXX` | `+639XXXXXXXXX` |
| `639XXXXXXXXX` | `+639XXXXXXXXX` |

### 7.3 Database Fields

```php
phone_verified              // boolean
phone_verification_code     // string (6-digit)
phone_verification_sent_at  // timestamp
phone_verification_attempts // integer
```

### 7.4 Rate Limiting & Security

- **Code Length:** 6 digits
- **Code Expiry:** 10 minutes
- **Max Attempts:** 3 failed verifications
- **Lockout:** Temporary account lock after max attempts
- **Development Mode:** Codes stored in session for testing

### 7.5 Development Testing

In `local` environment, SMS codes are logged:

```php
session('dev_sms_verification') => [
    'phone' => '09XX XXX XXXX',
    'code' => '123456',
    'expires_at' => '2025-10-21 12:30:00'
]
```

---

## 8. SINGLE SIGN-ON (SSO)

### 8.1 SSO Configuration

```env
SSO_SHARED_SECRET=your-strong-shared-secret
```

**Central Login Portal:**  
`https://local-government-unit-1-ph.com/public/login.php`

### 8.2 SSO Authentication Flow

```
Main LGU1 Portal Login
         ↓
User clicks "Facility Reservation"
         ↓
Redirects with SSO parameters:
?user_id=60&username=JohnDoe&email=user@lgu1.com&role=citizen
         ↓
/sso/login endpoint (SsoController)
         ↓
CaptureSSO Middleware
         ↓
Session stored: session('sso_user')
         ↓
User authenticated
         ↓
Redirect to role-specific dashboard
```

### 8.3 SSO URL Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `user_id` | integer | Yes | External SSO user ID |
| `username` | string | Yes | User's login name |
| `email` | string | No | User's email address |
| `role` | string | No | User role (admin/staff/citizen) |
| `subsystem_role_name` | string | No | Detailed role name |
| `sig` | string | Optional | HMAC signature |
| `ts` | integer | Optional | Unix timestamp |

### 8.4 Role Mapping

```php
// SSO Role → System Role Mapping
'admin' / 'Admin' / 'Administrator' → 'admin'
'staff' / 'Staff' / 'Employee'      → 'staff'
'citizen' / 'Citizen' / 'User'      → 'citizen'
```

### 8.5 Session Persistence (CaptureSSO Middleware)

**File:** `app/Http/Middleware/CaptureSSO.php`

Stores SSO data in session:
```php
session('sso_user') => [
    'id' => 60,
    'username' => 'JohnDoe',
    'email' => 'user@lgu1.com',
    'role' => 'citizen'
]
```

Available globally in all views as `$ssoUser`.

### 8.6 Logout Flow

```php
// Clear Laravel session
Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();

// Redirect to main LGU1 login
return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
```

**Logout Route:** `POST /logout`

---

## 9. MIDDLEWARE SECURITY

### 9.1 Registered Middleware

**Location:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    // Global web middleware
    $middleware->web(append: [
        \App\Http\Middleware\CaptureSSO::class,
    ]);
    
    // Route-specific middleware aliases
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'sso' => \App\Http\Middleware\SsoAuthMiddleware::class,
        'admin.auth' => \App\Http\Middleware\AdminAuthMiddleware::class,
    ]);
})
```

### 9.2 Admin Authentication Middleware

**File:** `app/Http/Middleware/AdminAuthMiddleware.php`

**Functionality:**
- Checks if user is authenticated as admin
- Auto-login from URL parameters (SSO integration)
- Session regeneration for security
- Logging of authentication attempts

**Protected Routes:**
```php
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    // All admin routes protected
});
```

### 9.3 CaptureSSO Middleware (Global)

**File:** `app/Http/Middleware/CaptureSSO.php`

**Runs on:** All web requests

**Functionality:**
- Captures SSO parameters from URL
- Stores in session for persistence across pages
- Makes user data available to all views
- Eliminates need for SSO parameters in every URL

### 9.4 SSO Authentication Middleware

**File:** `app/Http/Middleware/SsoAuthMiddleware.php`

**Functionality:**
- API-based token verification with main LGU1 system
- User creation/update from SSO data
- Automatic role mapping
- Session regeneration after login
- Error handling and logging

---

## 10. DATABASE SECURITY (SQLite)

### 10.1 SQLite Configuration

**Database Type:** SQLite (file-based)  
**Location:** `/home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite`

### 10.2 Advantages of SQLite for This System

✅ **Zero Configuration** - No database server setup required  
✅ **File-Based** - Easy backup with simple file copy  
✅ **Fast Performance** - Excellent for small-to-medium traffic  
✅ **Atomic Writes** - ACID compliant  
✅ **Cross-Platform** - Works identically everywhere  
✅ **Low Memory** - Perfect for VPS/shared hosting  

### 10.3 Security Considerations

```bash
# Ensure correct permissions
chmod 664 /home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite
chmod 775 /home/facilities.local-government-unit-1-ph.com/public_html/database/

# Ownership (replace www-data with your web server user)
chown www-data:www-data /home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite
```

### 10.4 Backup Strategy

**Daily Backup:**
```bash
#!/bin/bash
# Save as: /home/facilities.local-government-unit-1-ph.com/scripts/backup-db.sh

BACKUP_DIR="/home/facilities.local-government-unit-1-ph.com/backups"
DB_FILE="/home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Copy database with timestamp
cp $DB_FILE $BACKUP_DIR/database_$DATE.sqlite

# Keep only last 30 days of backups
find $BACKUP_DIR -name "database_*.sqlite" -mtime +30 -delete

echo "Backup completed: database_$DATE.sqlite"
```

**Cron Job (daily at 2 AM):**
```cron
0 2 * * * /home/facilities.local-government-unit-1-ph.com/scripts/backup-db.sh
```

### 10.5 User Security Fields

**Migration:** `2025_09_03_170942_add_authentication_security_to_users_table.php`

```php
// Email Verification
email_verified
email_verification_token
email_verification_sent_at

// Phone Verification
phone_verified
phone_verification_code
phone_verification_sent_at
phone_verification_attempts

// Two-Factor Authentication
two_factor_enabled
two_factor_secret
two_factor_recovery_codes
two_factor_enabled_at

// Security & Rate Limiting
failed_verification_attempts
verification_locked_until
last_security_check

// SSO Integration
external_id
sso_token
sso_token_expires_at
```

---

## 11. ENVIRONMENT VARIABLES (ACTUAL)

### 11.1 Complete Production .env File

```env
# Application
APP_NAME=Laravel
APP_ENV=local                    # ⚠️ CHANGE TO "production"
APP_KEY=base64:I4c5rsegKtPyNAHX9DxHhd6PyXlOTtJispblZFgD0rs=
APP_DEBUG=true                    # ⚠️ CHANGE TO "false"
APP_URL=https://facilities.local-government-unit-1-ph.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4

# Security
BCRYPT_ROUNDS=12

# Logging
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug                   # ⚠️ CHANGE TO "error"

# Database (SQLite)
DB_CONNECTION=sqlite
DB_DATABASE=/home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite
# DB_HOST=localhost               # Not used (SQLite)
# DB_PORT=3306                    # Not used (SQLite)
# DB_USERNAME=root                # Not used (SQLite)
# DB_PASSWORD=                    # Not used (SQLite)

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

# Broadcasting & Queues
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

# Cache
CACHE_STORE=file
# CACHE_PREFIX=

# Memcached (Not in use)
MEMCACHED_HOST=127.0.0.1

# Redis (Not in use)
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# === GMAIL SMTP SETTINGS ===
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=lazysloths227@gmail.com
MAIL_PASSWORD=mhjgzjdivzvmpxal      # Gmail App Password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="lazysloths227@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
# ===========================

# Twilio SMS
TWILIO_SID=AC9e2096ac21ce2381b534b66ed1
TWILIO_AUTH_TOKEN=629b97e4808bd544fe5c1f19609b23ed
TWILIO_FROM=+19789694845

# AWS (Not in use)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Vite
VITE_APP_NAME="${APP_NAME}"

# SSO
SSO_SHARED_SECRET=your-strong-shared-secret
```

---

## 12. SECURITY WARNINGS & RECOMMENDATIONS

### 12.1 ⚠️ CRITICAL: Production Settings

**Current Status:** Development mode (UNSAFE for production!)

#### Must Change Before Going Live:

```env
# ❌ CURRENT (DANGEROUS):
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug

# ✅ CHANGE TO:
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

### 12.2 Why APP_DEBUG=true is Dangerous

When `APP_DEBUG=true`, Laravel exposes:
- ❌ Full stack traces with file paths
- ❌ Database queries and structure
- ❌ Environment variables
- ❌ Configuration values
- ❌ Sensitive internal data

**Impact:** Hackers can see your entire system architecture!

### 12.3 Why APP_ENV=local is Wrong

`APP_ENV=local` affects:
- ❌ Error handling (verbose errors)
- ❌ Caching behavior (disabled)
- ❌ Performance optimizations (disabled)
- ❌ Logging verbosity (excessive)

**Impact:** Poor performance and security exposure!

### 12.4 Gmail App Password Security

✅ **Good:** Using Gmail App Password  
✅ **Good:** Not using main Gmail password  
⚠️ **Warning:** App password visible in `.env` file  

**Recommendation:** Ensure `.env` file is:
- Not committed to Git (in `.gitignore`)
- Has restrictive permissions: `chmod 600 .env`
- Only readable by web server user

### 12.5 SSL/HTTPS Configuration

**Current:** HTTPS enabled via `APP_URL`

**Ensure:**
```env
# Force HTTPS in production
APP_URL=https://facilities.local-government-unit-1-ph.com

# In config/session.php (set via .env):
SESSION_SECURE_COOKIE=true      # Only send cookies over HTTPS
SESSION_HTTP_ONLY=true          # Prevent JavaScript access
SESSION_SAME_SITE=lax          # CSRF protection
```

### 12.6 File Permissions Security

```bash
# Application files (read-only for web server)
find /home/facilities.local-government-unit-1-ph.com/public_html -type f -exec chmod 644 {} \;
find /home/facilities.local-government-unit-1-ph.com/public_html -type d -exec chmod 755 {} \;

# Writable directories
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod 775 database
chmod 664 database/database.sqlite

# Protect .env file
chmod 600 .env

# Ownership
chown -R www-data:www-data /home/facilities.local-government-unit-1-ph.com/public_html
```

---

## 13. DEPLOYMENT CHECKLIST

### 13.1 Pre-Deployment (Code & Configuration)

- [ ] **Set `APP_ENV=production`** (Currently: `local`)
- [ ] **Set `APP_DEBUG=false`** (Currently: `true`) ⚠️ CRITICAL
- [ ] **Set `LOG_LEVEL=error`** (Currently: `debug`)
- [ ] Verify `APP_KEY` is set (✅ Already set)
- [ ] Verify `APP_URL` is correct (✅ Already set)
- [ ] Verify database path is correct (✅ Already set)
- [ ] Verify Gmail SMTP credentials (✅ Already set)
- [ ] Verify Twilio credentials (✅ Already set)
- [ ] Update `SSO_SHARED_SECRET` to strong value
- [ ] Ensure `.env` is in `.gitignore` (✅ Already is)

### 13.2 Server Setup & Permissions

```bash
cd /home/facilities.local-government-unit-1-ph.com/public_html

# 1. Fix file permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# 2. Make writable directories
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod 775 database
chmod 664 database/database.sqlite

# 3. Protect sensitive files
chmod 600 .env

# 4. Set ownership
chown -R www-data:www-data .

# 5. Verify SQLite database exists
ls -la database/database.sqlite
```

### 13.3 Build Assets (Vite)

```bash
cd /home/facilities.local-government-unit-1-ph.com/public_html

# Install Node dependencies
npm install

# Build production assets
npm run build

# Fix permissions for build directory
chmod -R 755 public/build
```

### 13.4 Laravel Optimization

```bash
cd /home/facilities.local-government-unit-1-ph.com/public_html

# Clear all caches first
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations (if needed)
php artisan migrate --force

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 13.5 Database Setup

```bash
# Verify database exists
php artisan db:show

# Run all migrations
php artisan migrate --force

# Seed admin user (if not exists)
php artisan db:seed --class=AdminUserSeeder

# Seed staff user (if not exists)
php artisan db:seed --class=StaffUserSeeder
```

### 13.6 Post-Deployment Testing

- [ ] Test SSO login from main LGU1 portal
- [ ] Test admin dashboard access
- [ ] Test staff dashboard access
- [ ] Test citizen dashboard access
- [ ] Test facility booking flow
- [ ] Test email verification
- [ ] Test SMS verification (if enabled)
- [ ] Test 2FA setup (if enabled)
- [ ] Test logout functionality
- [ ] Verify all pages load without errors
- [ ] Check browser console for JavaScript errors
- [ ] Verify assets (CSS/JS) are loading

---

## 14. TROUBLESHOOTING

### 14.1 SQLite Database Issues

**Problem:** "Database file not found"  
**Solution:**
```bash
# Check if file exists
ls -la /home/facilities.local-government-unit-1-ph.com/public_html/database/database.sqlite

# If missing, run migrations
php artisan migrate --force
```

**Problem:** "Database locked"  
**Solution:**
```bash
# SQLite file is being accessed by another process
# Check for running PHP processes
ps aux | grep php

# Kill stuck processes if needed
kill -9 <process_id>

# Or restart PHP-FPM
systemctl restart php-fpm
```

**Problem:** "Unable to write to database"  
**Solution:**
```bash
# Fix permissions
chmod 664 database/database.sqlite
chmod 775 database/
chown www-data:www-data database/database.sqlite
```

### 14.2 File Session Issues

**Problem:** "Session data not persisting"  
**Solution:**
```bash
# Check storage/framework/sessions directory exists
ls -la storage/framework/sessions/

# If missing, create it
mkdir -p storage/framework/sessions

# Fix permissions
chmod -R 775 storage/framework/sessions
chown -R www-data:www-data storage/framework/sessions
```

**Problem:** "Permission denied" for sessions  
**Solution:**
```bash
# Ensure web server can write
chown -R www-data:www-data storage/framework
chmod -R 775 storage/framework
```

### 14.3 Gmail SMTP Issues

**Problem:** "Authentication failed"  
**Solution:**
1. Verify 2FA is enabled on Gmail account
2. Generate new App Password:
   - Google Account → Security → App passwords
   - Create new password for "Mail"
3. Update `MAIL_PASSWORD` in `.env`
4. Clear config cache: `php artisan config:clear`

**Problem:** "Could not send mail"  
**Solution:**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Verify SMTP settings
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });

# If error, check:
# - MAIL_PORT=587 (not 465)
# - MAIL_ENCRYPTION=tls (not ssl)
# - Gmail App Password is correct
```

### 14.4 SSO Login Issues

**Problem:** Users redirected to main login  
**Solution:**
```bash
# Check if SSO parameters are being captured
tail -f storage/logs/laravel.log | grep SSO

# Verify CaptureSSO middleware is registered
grep -r "CaptureSSO" bootstrap/app.php

# Check session storage
php artisan tinker
>>> session()->all();
```

**Problem:** "Invalid token" errors  
**Solution:**
```bash
# Verify SSO_SHARED_SECRET matches main LGU1 system
grep SSO_SHARED_SECRET .env

# Check with main system administrator
```

### 14.5 Vite Asset Issues

**Problem:** "Vite manifest not found"  
**Solution:**
```bash
# Build assets
npm install
npm run build

# Verify build directory exists
ls -la public/build/

# Clear view cache
php artisan view:clear
```

**Problem:** "Assets not loading (404)"  
**Solution:**
```bash
# Fix permissions
chmod -R 755 public/build

# Verify .htaccess or nginx config allows /build/ access
```

### 14.6 Permission Errors

**Problem:** "500 Internal Server Error"  
**Solution:**
```bash
# Fix all permissions at once
cd /home/facilities.local-government-unit-1-ph.com/public_html

chmod -R 755 storage bootstrap/cache
chmod 664 database/database.sqlite
chown -R www-data:www-data storage bootstrap/cache database

# Check error logs
tail -50 storage/logs/laravel.log
```

---

## 15. MAINTENANCE & SUPPORT

### 15.1 Log Files

**Laravel Application Log:**
```bash
tail -f /home/facilities.local-government-unit-1-ph.com/public_html/storage/logs/laravel.log
```

**SSO Debug Log:**
```bash
tail -f /home/facilities.local-government-unit-1-ph.com/public_html/public/sso_debug.log
```

**Web Server Error Log:**
```bash
# Apache
tail -f /var/log/apache2/error.log

# Nginx
tail -f /var/log/nginx/error.log
```

### 15.2 Cache Clearing Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Or use optimize:clear (clears all at once)
php artisan optimize:clear
```

### 15.3 Regular Maintenance Tasks

**Daily:**
- [ ] Monitor error logs
- [ ] Check disk space
- [ ] Verify database backups

**Weekly:**
- [ ] Review failed login attempts
- [ ] Check security logs
- [ ] Test email delivery
- [ ] Test SMS delivery (if used)

**Monthly:**
- [ ] Update Laravel dependencies: `composer update`
- [ ] Update npm packages: `npm update`
- [ ] Review and update security patches
- [ ] Test backup restoration
- [ ] Rotate logs: `php artisan log:clear`

### 15.4 Monitoring Commands

```bash
# Check disk space
df -h

# Check database size
ls -lh database/database.sqlite

# Check session directory size
du -sh storage/framework/sessions/

# Check log file size
ls -lh storage/logs/laravel.log

# Count active sessions
ls storage/framework/sessions/ | wc -l
```

### 15.5 Performance Optimization

```bash
# Enable OPcache (check php.ini)
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000

# Use production caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
composer install --optimize-autoloader --no-dev
```

### 15.6 Backup & Recovery

**Manual Backup:**
```bash
# Backup database
cp database/database.sqlite backups/database-$(date +%Y%m%d).sqlite

# Backup entire application
tar -czf backup-$(date +%Y%m%d).tar.gz \
  --exclude='node_modules' \
  --exclude='vendor' \
  --exclude='storage/logs' \
  /home/facilities.local-government-unit-1-ph.com/public_html/
```

**Restore Database:**
```bash
# Stop web server
systemctl stop apache2  # or nginx

# Restore database file
cp backups/database-20251020.sqlite database/database.sqlite

# Fix permissions
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite

# Start web server
systemctl start apache2  # or nginx
```

---

## 16. PRODUCTION READINESS FINAL CHECKLIST

### 16.1 Configuration

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `LOG_LEVEL=error`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] Strong `APP_KEY` generated
- [ ] Strong `SSO_SHARED_SECRET` set

### 16.2 Security

- [ ] HTTPS/SSL certificate installed
- [ ] `.env` file has `chmod 600`
- [ ] Database file has `chmod 664`
- [ ] Storage directories have `chmod 775`
- [ ] Web server user owns all files
- [ ] Firewall configured
- [ ] Failed login rate limiting enabled

### 16.3 Performance

- [ ] Config cached: `php artisan config:cache`
- [ ] Routes cached: `php artisan route:cache`
- [ ] Views cached: `php artisan view:cache`
- [ ] Composer optimized: `composer install --optimize-autoloader --no-dev`
- [ ] Assets built: `npm run build`
- [ ] OPcache enabled in PHP

### 16.4 Monitoring

- [ ] Error logging configured
- [ ] Log rotation set up
- [ ] Uptime monitoring enabled
- [ ] Database backup automated
- [ ] Disk space monitoring
- [ ] Email delivery monitoring

### 16.5 Testing

- [ ] All routes tested
- [ ] Email sending tested
- [ ] SMS sending tested (if used)
- [ ] SSO login tested
- [ ] Booking flow tested end-to-end
- [ ] Error pages tested (404, 500)
- [ ] Mobile responsive tested

---

**Document Version:** 1.0  
**Last Updated:** October 21, 2025  
**Author:** Development Team  
**System:** LGU1 Facility Reservation System

---

**For Support:**
- Check logs: `storage/logs/laravel.log`
- Review this documentation
- Contact system administrator

**Next Steps:**
1. ⚠️ Change `APP_DEBUG=false`
2. ⚠️ Change `APP_ENV=production`
3. Run deployment checklist (Section 13)
4. Test all functionality
5. Monitor logs for 24 hours

---

**END OF DOCUMENTATION**

