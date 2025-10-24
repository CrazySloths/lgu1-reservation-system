# 🚨 Localhost Emergency Backup - ACTIVE

## Quick Setup for Localhost (NO SSO)

Your domain is down. The system is now configured to run completely on **localhost** without SSO.

## ✅ Changes Already Made (NOT PUSHED TO GITHUB)

1. **SSO Middleware Disabled** - Modified `app/Http/Middleware/SsoAuthMiddleware.php` to bypass external SSO on localhost
2. **Citizen Routes Updated** - Modified `routes/web.php` to remove SSO requirement
3. **Login Redirect Updated** - `/login` now redirects to admin dashboard on localhost

## 🚀 How to Access the System

### For Admin Dashboard:
```
http://localhost/admin/dashboard?user_id=1&username=admin&email=admin@test.com
```

### For Citizen Dashboard:
```
http://localhost/citizen/dashboard?user_id=4&username=Cristian+Mark&email=cristian@test.com
```

### Alternative (Auto-login):
Just visit: `http://localhost/login`
- It will automatically redirect you to admin dashboard with test credentials

## 📝 What Was Modified

### 1. `routes/web.php` (Line 195)
```php
// BEFORE:
Route::middleware(['web', 'sso', 'citizen'])->prefix('citizen')...

// AFTER (LOCALHOST ONLY):
Route::middleware(['web'])->prefix('citizen')...
```

### 2. `routes/web.php` (Line 28-34)
```php
Route::get('/login', function() {
    // For localhost, redirect to admin dashboard with dummy user
    if (config('app.env') === 'local') {
        return redirect('/admin/dashboard?user_id=1&username=admin&email=admin@test.com');
    }
    return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
})->name('login');
```

### 3. `app/Http/Middleware/SsoAuthMiddleware.php` (Lines 15-37)
Added localhost detection to skip external SSO redirects

## 🔄 How to Restore When Domain is Back Up

### Option 1: Revert the changes
```bash
git checkout routes/web.php
git checkout app/Http/Middleware/SsoAuthMiddleware.php
```

### Option 2: Manual restore
1. In `routes/web.php` line 195, change back to:
   ```php
   Route::middleware(['web', 'sso', 'citizen'])->prefix('citizen')
   ```

2. In `routes/web.php` line 28-34, remove the localhost check or revert

3. In `app/Http/Middleware/SsoAuthMiddleware.php`, remove lines 15-37 (localhost mode section)

## ⚠️ IMPORTANT

**DO NOT COMMIT OR PUSH THESE CHANGES TO GITHUB**

These are temporary localhost-only changes. When your domain is back up, revert them.

---

## Current Status: 🟢 LOCALHOST MODE ACTIVE

