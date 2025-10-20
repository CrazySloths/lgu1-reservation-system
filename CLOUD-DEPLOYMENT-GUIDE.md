# Cloud Server Deployment Guide

**Date:** October 6, 2025  
**Purpose:** Deploy database fixes and code updates to production server

---

## ⚠️ IMPORTANT - READ FIRST

Your **local** database has been fixed, but your **LIVE CLOUD SERVER** needs the same fixes!

---

## 📦 Step 1: Upload Files to Cloud Server

Upload these files via **FileZilla/FTP/Bitvise**:

### A. Updated Code Files
```
app/Http/Controllers/Staff/RequirementVerificationController.php
resources/views/admin/reservations/index.blade.php
resources/views/staff/verification/show.blade.php
```

### B. Database Migration Scripts (NEW)
```
public/cloud_add_columns.php
public/cloud_migrate_json.php
```

---

## 🗄️ Step 2: Fix Cloud Database

### A. Add Missing Columns

**Via Bitvise Terminal:**
```bash
cd /path/to/your/project
php public/cloud_add_columns.php
```

**Expected Output:**
```
✅ Added column: event_name
✅ Added column: event_description
✅ Added column: event_date
... (20 columns total)
✅ Column migration complete!
```

### B. Migrate JSON Data to Database

**Via Bitvise Terminal:**
```bash
php public/cloud_migrate_json.php
```

**Expected Output:**
```
✅ Booking #1 - Kasal - CREATED
✅ Created: 1
📊 Database now has 1 bookings
✅ Migration complete!
```

---

## ✅ Step 3: Verify Everything Works

### Test the Flow:
1. **Citizen** submits a booking
2. **Staff** verifies and approves
3. **Admin** sees it in Reservation Review with "Staff Verified" badge
4. **Admin** can do final approval

---

## 🔍 Troubleshooting

### If `cloud_add_columns.php` fails:

**Error:** "Database connection failed"
- Check your `.env` file has correct cloud database credentials
- Make sure MySQL is running on cloud server

**Error:** "Duplicate column name"
- Some columns already exist - this is OK
- Script will skip them and continue

### If `cloud_migrate_json.php` fails:

**Error:** "JSON file not found"
- Make sure `storage/app/bookings_data.json` exists on cloud server
- Upload it if missing

**Error:** "Booking already exists"
- This is OK - script skips existing bookings
- Only creates new ones

---

## 🧹 Step 4: Cleanup (Optional)

After successful deployment, you can delete the migration scripts:

```bash
rm public/cloud_add_columns.php
rm public/cloud_migrate_json.php
```

---

## 📊 What Gets Fixed

### Before:
- ❌ Admin sees "No Reservations Found"
- ❌ Database missing most columns
- ❌ Only JSON has booking data

### After:
- ✅ Admin sees all bookings
- ✅ Database has all required columns
- ✅ Data synced from JSON to database
- ✅ Staff verification shows in admin panel

---

## 🚨 CRITICAL: Don't Forget

1. ✅ Upload the **3 updated code files**
2. ✅ Run **cloud_add_columns.php** (adds columns)
3. ✅ Run **cloud_migrate_json.php** (migrates data)
4. ✅ Test the complete workflow

**All 4 steps are required!**

---

## 📞 If You Need Help

If something goes wrong:
1. Check the error message
2. Take a screenshot
3. Ask for help with the exact error

**Do NOT skip any steps!**

---

## ✅ Verification Checklist

- [ ] Uploaded 3 code files
- [ ] Uploaded 2 migration scripts
- [ ] Ran cloud_add_columns.php successfully
- [ ] Ran cloud_migrate_json.php successfully
- [ ] Can see "Kasal" booking in Admin Reservation Review
- [ ] Staff verification badge shows for verified bookings

---

**Last Updated:** October 6, 2025  
**Status:** Ready for deployment


