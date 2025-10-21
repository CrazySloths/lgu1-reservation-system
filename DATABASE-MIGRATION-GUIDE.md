# Database Migration Guide - Citizen Feedback Table

**Date:** October 21, 2025  
**Issue:** `citizen_feedback` table doesn't exist on production server  
**Solution:** Run pending migrations via SSH

---

## PROBLEM

When accessing `/admin/feedback`, you get this error:
```
SQLSTATE[HY000]: General error: 1 no such table: citizen_feedback
```

**Cause:** The migration file exists in the code but hasn't been executed on the production database.

---

## SOLUTION - Run Migration on Production Server

### Step 1: SSH into Your Server

```bash
ssh your-username@facilities.local-government-unit-1-ph.com
```

### Step 2: Navigate to Project Directory

```bash
cd /home/facilities.local-government-unit-1-ph.com/public_html/
```

### Step 3: Pull Latest Changes (if not done yet)

```bash
git pull origin main
```

### Step 4: Check Pending Migrations

```bash
php artisan migrate:status
```

This will show you which migrations are pending (marked as "N").

### Step 5: Run the Migration

```bash
php artisan migrate --force
```

**Note:** The `--force` flag is required because the environment is set to `production` (or should be).

### Step 6: Verify Table Was Created

```bash
php artisan tinker
```

Then in tinker:
```php
DB::table('citizen_feedback')->count();
```

Should return `0` (zero records, but table exists). Type `exit` to leave tinker.

---

## EXPECTED OUTPUT

When running `php artisan migrate --force`, you should see:

```
Migrating: 2025_10_15_045111_create_citizen_feedback_table
Migrated:  2025_10_15_045111_create_citizen_feedback_table (XX.XXms)
```

---

## TABLE STRUCTURE

The `citizen_feedback` table will have these columns:

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `name` | varchar | Citizen's name |
| `email` | varchar | Citizen's email |
| `category` | varchar | Question category |
| `question` | text | The question/feedback |
| `status` | enum | pending, in_progress, resolved, closed |
| `admin_response` | text (nullable) | Admin's response |
| `responded_by` | bigint (nullable) | Foreign key to users table |
| `responded_at` | timestamp (nullable) | When admin responded |
| `created_at` | timestamp | When feedback was submitted |
| `updated_at` | timestamp | Last update |

---

## TROUBLESHOOTING

### Error: "Database is locked"

```bash
# Check for running processes
ps aux | grep php

# Kill if needed
kill -9 <process_id>

# Or restart PHP-FPM
systemctl restart php-fpm
```

### Error: "Access denied"

```bash
# Check database file permissions
ls -la database/database.sqlite

# Fix if needed
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite
```

### Error: "Migration table not found"

```bash
# Create migrations table first
php artisan migrate:install

# Then run migrations
php artisan migrate --force
```

---

## VERIFICATION

After running the migration, test the feature:

1. Go to: `https://facilities.local-government-unit-1-ph.com/admin/feedback`
2. Page should load without errors
3. Should show "No feedback submissions yet" or existing feedback

---

## ROLLBACK (If Needed)

To undo this migration:

```bash
php artisan migrate:rollback --step=1 --force
```

This will drop the `citizen_feedback` table.

---

## RELATED FILES

- **Migration:** `database/migrations/2025_10_15_045111_create_citizen_feedback_table.php`
- **Model:** `app/Models/CitizenFeedback.php`
- **Controller:** `app/Http/Controllers/Admin/CitizenFeedbackController.php`
- **Views:** `resources/views/admin/feedback/index.blade.php` and `show.blade.php`

---

## SUMMARY

**Quick Commands (Run on Server):**

```bash
cd /home/facilities.local-government-unit-1-ph.com/public_html/
git pull origin main
php artisan migrate --force
```

That's it! The Citizen Feedback feature will be fully functional after running the migration.

