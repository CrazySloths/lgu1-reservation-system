# SSO Session Deployment Checklist

This document lists all files that need to be uploaded to fix the SSO authentication issue.

## Problem
Citizens get "403 Forbidden" when accessing pages without SSO URL parameters.

## Solution
Implement middleware to capture SSO parameters in session, so they persist across page loads.

---

## Files to Upload via Bitvise SFTP

### 1. **SSO Middleware** (NEW FILE)
- **Local**: `app/Http/Middleware/CaptureSSO.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/app/Http/Middleware/CaptureSSO.php`
- **What it does**: Captures `?user_id=60&username=...` from URL and stores in session

### 2. **Bootstrap Configuration** (MODIFIED)
- **Local**: `bootstrap/app.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/bootstrap/app.php`
- **What it does**: Registers the CaptureSSO middleware globally

### 3. **Payment Slip Controller** (MODIFIED)
- **Local**: `app/Http/Controllers/PaymentSlipController.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/app/Http/Controllers/PaymentSlipController.php`
- **What it does**: Gets user ID from session instead of requiring URL params

### 4. **Citizen Sidebar** (MODIFIED)
- **Local**: `resources/views/citizen/partials/sidebar.blade.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/resources/views/citizen/partials/sidebar.blade.php`
- **What it does**: Removed SSO parameter helper (no longer needed)

### 5. **Citizen Layout** (MODIFIED)
- **Local**: `resources/views/citizen/layouts/app-sidebar.blade.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/resources/views/citizen/layouts/app-sidebar.blade.php`
- **What it does**: Fixed Vite manifest error with Tailwind CDN fallback

### 6. **Admin Layout** (MODIFIED - Optional but recommended)
- **Local**: `resources/views/layouts/app.blade.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/resources/views/layouts/app.blade.php`
- **What it does**: Tailwind CDN fallback + scripts stack for real-time clock

### 7. **Admin Controller** (MODIFIED - For approvals)
- **Local**: `app/Http/Controllers/Admin/ReservationReviewController.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/app/Http/Controllers/Admin/ReservationReviewController.php`
- **What it does**: Handles payment slip creation with foreign key fixes

---

## Test Files (Optional - for debugging)

### 8. **Session Test Tool**
- **Local**: `public/test_session.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/test_session.php`
- **What it does**: Tests if PHP sessions are working

### 9. **SSO Users Diagnostic**
- **Local**: `public/fix_sso_users.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/fix_sso_users.php`
- **What it does**: Checks database for missing users

### 10. **Create SSO User**
- **Local**: `public/create_sso_user.php`
- **Cloud**: `/home/facilities.local-government-unit-1-ph.com/public_html/create_sso_user.php`
- **What it does**: Creates user accounts from SSO parameters

---

## Testing Procedure

### Step 1: Test Session Capture

1. Upload `public/test_session.php` to the cloud
2. Visit: `https://facilities.local-government-unit-1-ph.com/test_session.php?user_id=60&username=Test&email=test@example.com&role=citizen`
3. **Expected**: Should show "SSO parameters captured"
4. Visit: `https://facilities.local-government-unit-1-ph.com/test_session.php` (WITHOUT parameters)
5. **Expected**: Should still show your SSO data (from session)

### Step 2: Test Real Application

1. Visit: `https://facilities.local-government-unit-1-ph.com/citizen/dashboard?user_id=60&username=Cristian+Mark+Angelo&email=user60@sso.local&role=citizen`
2. **Expected**: Dashboard loads successfully
3. Click "Payment Slips" in the sidebar
4. **Expected**: Payment slips page loads WITHOUT URL parameters
5. Click any other sidebar link
6. **Expected**: Everything works without 403 errors!

### Step 3: Test Booking Flow

1. Create a new reservation
2. Submit it
3. Have staff verify it
4. Have admin approve it (creates payment slip)
5. Check citizen payment slips
6. **Expected**: Citizen can see their payment slip without errors

---

## How It Works

```
User Login (SSO) → ?user_id=60&username=...
                    ↓
            CaptureSSO Middleware
                    ↓
        Stores in $_SESSION['sso_user']
                    ↓
            All Pages Work!
      (No URL parameters needed)
```

---

## Troubleshooting

### If sessions don't work:
1. Check if `storage/framework/sessions` directory exists
2. Check permissions: `chmod 775 storage/framework/sessions`
3. Check `config/session.php` - driver should be 'file'

### If 403 still happens:
1. Make sure `bootstrap/app.php` was uploaded
2. Clear cache: Run `php artisan config:clear` on server
3. Clear sessions: Delete all files in `storage/framework/sessions/`

### If middleware not running:
1. Verify `app/Http/Middleware/CaptureSSO.php` exists on cloud
2. Check `bootstrap/app.php` has the middleware registered
3. Restart PHP-FPM (if using it)

---

## Quick Upload Script

For convenience, here's a list of all critical files to upload:

```
app/Http/Middleware/CaptureSSO.php
bootstrap/app.php
app/Http/Controllers/PaymentSlipController.php
resources/views/citizen/partials/sidebar.blade.php
resources/views/citizen/layouts/app-sidebar.blade.php
resources/views/layouts/app.blade.php
app/Http/Controllers/Admin/ReservationReviewController.php
```

Test files (optional):
```
public/test_session.php
public/fix_sso_users.php
public/create_sso_user.php
```

---

## Summary

The SSO session fix ensures that:
✅ Citizens only need SSO parameters on **first page load**
✅ All subsequent navigation works **without URL parameters**
✅ No more 403 errors when clicking sidebar links
✅ Booking process works smoothly end-to-end


