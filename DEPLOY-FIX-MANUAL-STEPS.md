# Deploy Citizen Login Fix - Manual Steps for Production

## 🎯 Problem
Citizen login loop is caused by:
- **Missing `storage/framework/sessions` directory** on production server
- **Cached configuration** using wrong session driver

## ✅ Solution - Follow These Steps EXACTLY

### Step 1: Access Your Production Server

**Option A: Via SSH Terminal**
```bash
ssh your-username@your-server-ip
cd /path/to/your/facilities/project
```

**Option B: Via cPanel File Manager + Terminal**
1. Login to cPanel
2. Open "Terminal" app
3. Navigate to your project directory

---

### Step 2: Pull Latest Changes

```bash
git pull origin fix
```

Expected output:
```
Updating f72b666..df6355f
Fast-forward
 FIX-CITIZEN-LOGIN-LOOP.md                | 204 ++++++++++++++++++
 storage/framework/sessions/.gitignore     |   2 +
 2 files changed, 206 insertions(+)
```

---

### Step 3: Create Sessions Directory (CRITICAL!)

```bash
mkdir -p storage/framework/sessions
```

Verify it was created:
```bash
ls -la storage/framework/ | grep sessions
```

Should see:
```
drwxrwxr-x  2 username username 4096 Oct 22 13:34 sessions
```

---

### Step 4: Set Permissions (If You Have sudo/ownership)

```bash
chmod -R 775 storage/framework/sessions
```

If that doesn't work (no sudo), try:
```bash
chmod -R 755 storage/framework/sessions
```

---

### Step 5: Clear ALL Caches (CRITICAL!)

Run these commands **ONE BY ONE**:

```bash
php artisan config:clear
```

```bash
php artisan cache:clear
```

```bash
php artisan view:clear
```

```bash
php artisan route:clear
```

**Expected output for each:**
```
Configuration cache cleared successfully.
Application cache cleared successfully.
Compiled views cleared successfully.
Route cache cleared successfully.
```

---

### Step 6: Verify .env Settings

```bash
grep SESSION .env
```

Should show:
```
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

If `SESSION_DRIVER` is NOT `file`, edit `.env`:
```bash
nano .env
```

Change to:
```
SESSION_DRIVER=file
```

Save (Ctrl+X, Y, Enter) and clear config again:
```bash
php artisan config:clear
```

---

### Step 7: Test Session File Creation

Try creating a test session file:
```bash
touch storage/framework/sessions/test_file
```

If it works:
```bash
rm storage/framework/sessions/test_file
```

If it **fails with "Permission denied"**, you need to fix permissions:
```bash
ls -ld storage/framework/sessions/
```

Contact your hosting support if permissions can't be set.

---

## 🧪 Testing the Fix

### Test 1: Check Session Directory Exists
```bash
ls -la storage/framework/sessions/
```

Should show:
```
total 8
drwxrwxr-x 2 username username 4096 Oct 22 13:34 .
drwxr-xr-x 5 username username 4096 Oct 22 13:30 ..
-rw-r--r-- 1 username username   14 Oct 22 13:34 .gitignore
```

### Test 2: Check Config
```bash
php artisan config:show session | grep driver
```

Should output:
```
  'driver' => 'file',
```

If it shows `'driver' => 'database'`, run:
```bash
php artisan config:clear
php artisan config:cache
```

### Test 3: Citizen Login (The Real Test!)

1. **Clear your browser cookies** for `facilities.local-government-unit-1-ph.com`
   - Chrome: Press F12 → Application → Cookies → Right-click → Clear
   - Firefox: Press F12 → Storage → Cookies → Right-click → Delete All

2. **Go to SSO Login:**
   ```
   https://local-government-unit-1-ph.com/public/login.php
   ```

3. **Login as a CITIZEN** (NOT admin/staff):
   - Example: `1hawkeye101010101@gmail.com` / `#Llaneta8080`

4. **After OTP verification:**
   - ✅ Should land on: `https://facilities.local-government-unit-1-ph.com/citizen/dashboard`
   - ✅ Should see: Citizen dashboard with booking stats
   - ✅ **Should NOT redirect back to login** 🎉

5. **Verify session is working:**
   - Refresh the page (F5)
   - Should stay on dashboard (not redirect to login)
   - Check session files:
     ```bash
     ls -la storage/framework/sessions/
     ```
   - Should see session files like:
     ```
     -rw-r--r-- 1 username username  324 Oct 22 13:45 hG7fK3jL9mN2pQ5rT8vW1xY4zA6bC
     ```

---

## ❌ If Login Loop STILL Occurs

### Debug Step 1: Check Laravel Logs
```bash
tail -100 storage/logs/laravel.log
```

Look for:
```
CITIZEN DASHBOARD: No user found - redirecting to login
```

This means Auth::user() is returning null.

### Debug Step 2: Check Session Files Are Being Created
After attempting login:
```bash
ls -la storage/framework/sessions/
```

**If NO session files appear:**
- Sessions directory permissions are wrong
- Or web server user doesn't have write access

Fix:
```bash
chmod -R 777 storage/framework/sessions/
```

### Debug Step 3: Check Web Server User
```bash
ps aux | grep -E 'apache|nginx|httpd'
```

Common web server users:
- `www-data` (Ubuntu/Debian)
- `apache` (CentOS/RHEL)
- `nobody` (some shared hosting)

Set ownership:
```bash
chown -R www-data:www-data storage/framework/sessions/
```

Replace `www-data` with your web server user.

---

## 🆘 Alternative Fix: Use Database Sessions

If file sessions STILL don't work (permissions locked by hosting), switch to database sessions:

### Step 1: Create Sessions Table
```bash
php artisan session:table
php artisan migrate --force
```

### Step 2: Update .env
```bash
nano .env
```

Change:
```
SESSION_DRIVER=database
```

### Step 3: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Step 4: Test Again
Database sessions are more reliable on shared hosting!

---

## 📋 Summary

**What We Fixed:**
1. ✅ Created `storage/framework/sessions/` directory
2. ✅ Cleared cached configuration
3. ✅ Ensured `SESSION_DRIVER=file` is active

**Why It Was Failing:**
1. ❌ SSO login called `Auth::login($user)` → tried to write session
2. ❌ Session write failed (no directory)
3. ❌ Redirect to `/citizen/dashboard` → `Auth::user()` returned null
4. ❌ `CitizenDashboardController` redirected to `/login` → **LOOP**

**Why It Works Now:**
1. ✅ Sessions directory exists
2. ✅ Session write succeeds
3. ✅ Redirect to `/citizen/dashboard` → `Auth::user()` returns the user
4. ✅ Dashboard loads successfully → **NO LOOP!** 🎊

---

**Date:** October 22, 2025  
**Issue:** Citizen Login Loop After SSO Authentication  
**Root Cause:** Missing sessions directory + cached config  
**Fix:** Create sessions directory + clear config cache  
**Status:** Ready to Deploy ✅

