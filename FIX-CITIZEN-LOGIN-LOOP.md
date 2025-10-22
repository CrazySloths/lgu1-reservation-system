# Fix Citizen Login Loop - Session Storage Issue

## 🔍 Problem Identified
The citizen login loop was caused by:
1. **Missing `storage/framework/sessions` directory** - Laravel couldn't store file-based sessions
2. **Cached configuration** - Server was using old config that defaulted to database sessions

## ✅ Solution Applied

### Local Fix (Already Done)
- ✅ Created `storage/framework/sessions` directory
- ✅ Added `.gitignore` to the sessions directory

### What You Need to Do on Production Server

#### Step 1: Connect to Your Server via SSH or Terminal

#### Step 2: Navigate to Your Project Directory
```bash
cd /path/to/your/project
# Example: cd /home/username/public_html/facilities
```

#### Step 3: Run These Commands One by One

```bash
# 1. Clear all cached configuration
php artisan config:clear

# 2. Clear application cache
php artisan cache:clear

# 3. Clear view cache
php artisan view:clear

# 4. Create sessions directory if it doesn't exist
mkdir -p storage/framework/sessions

# 5. Set proper permissions (if you have permission)
chmod -R 775 storage/framework/sessions

# 6. Verify the .env file has SESSION_DRIVER=file
grep SESSION_DRIVER .env
# Should output: SESSION_DRIVER=file

# 7. Cache the config again (optional, but recommended)
php artisan config:cache
```

---

## 🧪 Testing

### Test Locally First
1. Clear your browser cookies for `localhost` or `facilities.local-government-unit-1-ph.test`
2. Go to the SSO login URL with citizen credentials
3. After SSO login, you should land on the citizen dashboard **WITHOUT** being redirected back to login

### Test on Production
1. Clear your browser cookies for `facilities.local-government-unit-1-ph.com`
2. Go to: `https://local-government-unit-1-ph.com/public/login.php`
3. Login as a citizen
4. You should stay on the citizen dashboard

---

## 🔧 Alternative Fix (If File Sessions Still Don't Work)

If file-based sessions still fail on the server, you can switch to database sessions:

### Option A: Create Sessions Table
```bash
# Create the sessions table migration
php artisan session:table

# Run the migration
php artisan migrate --force
```

### Option B: Update .env to Use Database Sessions
```env
SESSION_DRIVER=database
```

Then run:
```bash
php artisan config:clear
php artisan cache:clear
php artisan session:table
php artisan migrate --force
```

---

## 📋 Commit This Fix

Once tested locally, commit and push:

```bash
git add storage/framework/sessions/.gitignore
git add FIX-CITIZEN-LOGIN-LOOP.md
git commit -m "Fix citizen login loop: Add sessions directory for file-based session storage"
git push origin fix
```

---

## ✅ Expected Behavior After Fix

**Before Fix:**
- Citizen logs in via SSO ➜ Redirects to dashboard ➜ **Immediately redirects back to login** (loop)

**After Fix:**
- Citizen logs in via SSO ➜ Redirects to dashboard ➜ **Stays on dashboard** ✅

---

## 🆘 Troubleshooting

### If Login Loop Still Occurs:

1. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```
   Look for session-related errors.

2. **Check Session Files Are Being Created:**
   ```bash
   ls -la storage/framework/sessions/
   ```
   You should see session files being created when you log in.

3. **Verify Permissions:**
   ```bash
   ls -ld storage/framework/sessions/
   ```
   Should show `drwxrwxr-x` or similar (writable by web server).

4. **Check .env File:**
   ```bash
   cat .env | grep SESSION
   ```
   Should show:
   ```
   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   ```

5. **Check if Config is Cached with Wrong Value:**
   ```bash
   php artisan config:show session
   ```
   Look for `driver` - should be `"file"` not `"database"`.

---

## 📖 Technical Explanation

### Why This Happened

1. **Laravel's Session Configuration**
   - `config/session.php` defaults to `SESSION_DRIVER=database` if `.env` value is missing
   - Your `.env` correctly has `SESSION_DRIVER=file`
   - But if the config was cached before `.env` was set, it uses the default

2. **File-Based Sessions Require Directory**
   - Laravel stores session files in `storage/framework/sessions/`
   - This directory was missing from the repository (not in Git)
   - Without it, sessions fail silently

3. **SSO Login Flow**
   - `SsoController::login()` calls `Auth::login($user)` ✅
   - Laravel tries to write session to `storage/framework/sessions/` ❌ (directory missing)
   - Session write fails silently
   - Browser redirects to `/citizen/dashboard`
   - `CitizenDashboardController::getAuthenticatedUser()` checks `Auth::check()` ❌ (no session)
   - Redirects back to login → **LOOP**

4. **The Fix**
   - Created `storage/framework/sessions/` directory ✅
   - Cleared cached config to pick up `SESSION_DRIVER=file` from `.env` ✅
   - Sessions now write successfully ✅
   - `Auth::check()` returns `true` on dashboard ✅
   - No more loop! ✅

---

## 📝 Related Files

- `config/session.php` - Session configuration
- `.env` - Environment variables (SESSION_DRIVER=file)
- `app/Http/Controllers/SsoController.php` - SSO login (lines 192-203)
- `app/Http/Controllers/CitizenDashboardController.php` - getAuthenticatedUser() method
- `storage/framework/sessions/` - Session file storage directory (NOW EXISTS!)

---

**Date Fixed:** October 22, 2025  
**Issue:** Citizen Login Loop After SSO Authentication  
**Root Cause:** Missing sessions directory + potentially cached config  
**Solution:** Create sessions directory + clear config cache

