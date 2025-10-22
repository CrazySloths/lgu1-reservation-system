# Payment Slips Dummy Data Deployment Guide

## 📋 Overview
This guide explains how to import payment slip dummy data to match the existing booking dummy data so the admin dashboard shows proper revenue statistics.

---

## 🎯 What This Fixes
- **Dashboard showing ₱0 revenue** → Will show ₱18,600 total revenue
- **No payment history** → Will have 15 payment slips (8 paid, 7 pending)
- **Empty payment reports** → Will have data from August-December 2025

---

## 📊 Expected Results After Import

### Payment Slip Summary
- **Total Payment Slips**: 15
- **Paid Slips**: 8
- **Pending Slips**: 7
- **Total Revenue**: ₱18,600.00
  - August 2025: ₱4,100.00
  - September 2025: ₱5,700.00
  - October 2025: ₱6,800.00
- **Pending Revenue**: ₱13,300.00

---

## 🚀 Deployment Steps

### Option 1: Via Production Server SSH

```bash
# 1. SSH into your server
ssh your-username@facilities.local-government-unit-1-ph.com

# 2. Navigate to project directory
cd /home/facilities.local-government-unit-1-ph.com/public_html

# 3. Pull latest code (includes DUMMY-DATA-payment_slips.sql)
git pull origin main

# 4. Import payment slip data
mysql -u faci_facility_user -p faci_facility < DUMMY-DATA-payment_slips.sql

# Enter your database password when prompted

# 5. Verify import
mysql -u faci_facility_user -p faci_facility -e "SELECT COUNT(*) as total FROM payment_slips; SELECT SUM(amount) as revenue FROM payment_slips WHERE status='paid';"

# 6. Clear caches
php artisan cache:clear
php artisan view:clear
```

### Option 2: Via phpMyAdmin (CyberPanel)

1. **Login to CyberPanel**
   - URL: `https://server10.indevfinite-server.com:2083`
   
2. **Access phpMyAdmin**
   - Navigate to: Databases → phpMyAdmin
   - Select database: `faci_facility`

3. **Import SQL File**
   - Click "Import" tab
   - Choose file: `DUMMY-DATA-payment_slips.sql` (from your local copy or download from GitHub)
   - Click "Go"

4. **Verify Import**
   ```sql
   SELECT COUNT(*) as total_payments FROM payment_slips;
   SELECT COUNT(*) as paid_payments FROM payment_slips WHERE status = 'paid';
   SELECT SUM(amount) as total_revenue FROM payment_slips WHERE status = 'paid';
   ```

   Expected results:
   - total_payments: 15
   - paid_payments: 8
   - total_revenue: 18600.00

5. **Clear Application Cache**
   - Via CyberPanel Terminal or SSH:
   ```bash
   cd /home/facilities.local-government-unit-1-ph.com/public_html
   php artisan cache:clear
   php artisan view:clear
   ```

### Option 3: Via Local Import then Export to Production

```bash
# On your local machine (Laragon)

# 1. Import to local database
cd C:\laragon\www\lgu1-reservation-system
mysql -u root faci_facility < DUMMY-DATA-payment_slips.sql

# 2. Verify locally
mysql -u root faci_facility -e "SELECT COUNT(*) FROM payment_slips;"

# 3. Export entire database
mysqldump -u root faci_facility > faci_facility_with_payments.sql

# 4. Upload to production via phpMyAdmin or scp
```

---

## ✅ Verification Checklist

After importing, verify these on the **Admin Dashboard**:

- [ ] **Total Bookings** shows 15 (not 3)
- [ ] **Approved Events** shows 15 (not 3)
- [ ] **Revenue Collected** shows ₱18,600 (not ₱0)
- [ ] **Pending Payment** shows ₱13,300 (not ₱0)
- [ ] **Facility Utilization** shows bookings for all 3 facilities
- [ ] **Upcoming Events** lists future approved bookings

---

## 🔍 Troubleshooting

### Problem: "Revenue still shows ₱0 after import"

**Possible causes:**
1. Payment slips imported but `status` column is not 'paid'
2. Wrong database (check you're using `faci_facility`)
3. Cache not cleared

**Solution:**
```sql
-- Check payment slips
SELECT * FROM payment_slips WHERE status = 'paid';

-- Check if table exists
SHOW TABLES LIKE 'payment_slips';

-- Verify column structure
DESCRIBE payment_slips;
```

### Problem: "Foreign key constraint fails"

**Cause:** Booking IDs in payment_slips.sql don't match your actual booking IDs

**Solution:**
```sql
-- Check existing booking IDs
SELECT id, event_name FROM bookings ORDER BY id;

-- Adjust payment_slips.sql booking_id values to match
```

### Problem: "Dashboard still shows old numbers"

**Solution:**
```bash
# Clear ALL caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Hard refresh browser (Ctrl + Shift + R)
```

---

## 📁 Related Files

- **DUMMY-DATA-payment_slips.sql** - Payment slip dummy data
- **DUMMY-DATA-bookings.sql** - Original booking dummy data
- **app/Http/Controllers/Admin/AdminDashboardController.php** - Dashboard logic
- **resources/views/admin/dashboard.blade.php** - Dashboard view

---

## 🎓 Understanding the Data

### Payment Timeline:
- **August-October**: All payments marked as PAID (historical data)
- **November-December**: Payments marked as PENDING (future events)

### Payment Methods Used:
- Bank Transfer
- GCash
- Cash
- Waived (for city government events)

This realistic distribution helps test various payment scenarios in the system.

---

## 🔒 Security Note

This is **DUMMY DATA** for development/testing. Before going to production with real citizens:
1. Delete all dummy data
2. Reset auto-increment counters
3. Import only real payment transactions

---

## 📞 Support

If you encounter issues:
1. Check `storage/logs/laravel.log` for errors
2. Verify database connection in `.env`
3. Ensure you're on the correct database and table

---

**Last Updated:** October 22, 2025
**Status:** Ready for deployment

