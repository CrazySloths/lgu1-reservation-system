# Manual Database Migration Guide

**Date:** October 21, 2025  
**Issue:** PHP MySQL driver not installed, can't run `php artisan migrate`  
**Solution:** Manual SQL execution via phpMyAdmin

---

## 🎯 WHAT WE'RE DOING

Since the PHP MySQL driver isn't working on your server, we'll bypass it completely by running SQL commands directly in phpMyAdmin.

---

## 📝 STEP-BY-STEP INSTRUCTIONS

### **Step 1: Open phpMyAdmin**

1. Go to your CyberPanel
2. Navigate to: **Websites** → **List Websites**
3. Click **"Manage"** for `facilities.local-government-unit-1-ph.com`
4. Look for **phpMyAdmin** link or go to:
   ```
   https://server10.indevfinite-server.com:2083/phpmyadmin
   ```

### **Step 2: Select the Database**

1. In phpMyAdmin, click on **`faci_facility`** database in the left sidebar
2. You should see all your existing tables (bookings, users, facilities, etc.)

### **Step 3: Open SQL Tab**

1. Click the **"SQL"** tab at the top
2. You'll see a large text box where you can paste SQL commands

### **Step 4: Copy & Paste the SQL**

1. Open the file: `MANUAL-MIGRATION-citizen_feedback.sql` (in your project folder)
2. **Copy ALL the content** from that file
3. **Paste it** into the SQL text box in phpMyAdmin

### **Step 5: Execute the SQL**

1. Click the **"Go"** button at the bottom right
2. Wait for it to process

### **Step 6: Verify Success**

You should see a green success message:
```
✓ 1 row inserted.
✓ MySQL returned an empty result set (i.e. zero rows).
```

**Then verify the table was created:**
1. Refresh the left sidebar in phpMyAdmin
2. You should now see **`citizen_feedback`** in the table list
3. Click on it to see the structure

---

## ✅ WHAT THIS DOES

The SQL file will:

1. ✅ Create the `citizen_feedback` table with all columns
2. ✅ Set up the foreign key to the `users` table
3. ✅ Insert a record into the `migrations` table so Laravel knows it's been run
4. ✅ Enable the Citizen Feedback feature

---

## 🔍 VERIFICATION

### **Check if Table Exists**

In phpMyAdmin SQL tab, run:
```sql
DESCRIBE citizen_feedback;
```

Should show all columns: id, name, email, category, question, status, etc.

### **Check if Migration Recorded**

```sql
SELECT * FROM migrations WHERE migration LIKE '%citizen_feedback%';
```

Should show one row with the migration name.

---

## 🎯 AFTER RUNNING THE SQL

Once the table is created, your **Citizen Feedback** feature will work!

Test it by visiting:
```
https://facilities.local-government-unit-1-ph.com/admin/feedback
```

It should load without the "no such table" error!

---

## 🔄 IF YOU NEED TO START OVER

If something goes wrong and you need to delete the table and try again:

```sql
DROP TABLE IF EXISTS `citizen_feedback`;
DELETE FROM `migrations` WHERE migration = '2025_10_15_045111_create_citizen_feedback_table';
```

Then run the creation SQL again.

---

## 📊 OTHER MIGRATIONS (If Needed)

If you need to create other tables manually, we can generate SQL files for:
- Any pending Laravel migrations
- New features you want to add
- Database structure changes

Just let me know!

---

## 🚨 IMPORTANT NOTES

1. **This is a temporary workaround** until your hosting provider fixes the PHP MySQL driver issue
2. **Your .env file is already configured** for MySQL (we set it up earlier)
3. **The website will work** once this table is created
4. **Future migrations** will need to be done the same way until PHP driver is fixed

---

## 💡 SUMMARY

**What you need to do:**
1. Open phpMyAdmin
2. Select `faci_facility` database
3. Go to SQL tab
4. Copy/paste content from `MANUAL-MIGRATION-citizen_feedback.sql`
5. Click "Go"
6. Done! ✅

**Time needed:** 2-3 minutes

---

**Next Steps After Migration:**
- Submit the hosting support ticket about MySQL driver
- Test the Citizen Feedback feature
- Continue with other features/fixes

---

**Questions?** Let me know if you run into any issues!

