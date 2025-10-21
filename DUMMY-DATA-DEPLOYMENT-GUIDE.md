# Dummy Data Deployment Guide - AI Forecast Testing

**Date:** October 21, 2025  
**Purpose:** Insert dummy bookings to test TensorFlow AI forecasting feature  
**Database:** faci_facility  

---

## 🎯 WHAT THIS DOES

Inserts **16 approved bookings** into your database:
- ✅ **5 bookings in October** (past data for AI training)
- ✅ **11 bookings in November** (future data for forecasting)
- ✅ All marked as `status='approved'` and `payment_status='paid'`
- ✅ Spread across all 3 facilities
- ✅ Realistic event types and attendee counts

---

## 📝 STEP-BY-STEP INSTRUCTIONS

### **Step 1: Open phpMyAdmin**

1. Go to: `https://server10.indevfinite-server.com:2083/phpmyadmin`
2. Login with your credentials
3. Select **`faci_facility`** database from the left sidebar

### **Step 2: Open SQL Tab**

1. Click the **"SQL"** tab at the top
2. You'll see a text box for SQL commands

### **Step 3: Copy & Paste the SQL**

1. Open the file: **`DUMMY-DATA-bookings.sql`**
2. **Copy ALL** the content
3. **Paste** into the SQL text box in phpMyAdmin

### **Step 4: Execute**

1. Click the **"Go"** button at the bottom right
2. Wait for confirmation

### **Step 5: Verify Success**

You should see:
```
✓ 16 rows inserted.
```

**Then verify the data:**

Run this query in phpMyAdmin SQL tab:
```sql
SELECT COUNT(*) as total_approved 
FROM bookings 
WHERE status = 'approved';
```

Should return: **16** (or more if you already had approved bookings)

---

## ✅ AFTER RUNNING THE SQL

### **Test the AI Forecast**

1. Go to: `https://facilities.local-government-unit-1-ph.com/admin/ai-forecast`
2. The page should now show:
   - ✅ Usage Analytics chart with data points
   - ✅ Prediction chart showing forecast for next 30 days
   - ✅ No "0 data points" error

---

## 🔍 SAMPLE DUMMY DATA INCLUDED

### **October 2025 Bookings (Past - for training):**
- Oct 18: Birthday Party @ Community Hall (100 guests)
- Oct 20: Basketball Tournament @ Sports Complex (50 guests)
- Oct 22: Wedding Reception @ Community Hall (180 guests)
- Oct 25: Community Forum @ Conference Room (150 guests)
- Oct 27: Youth Sports Clinic @ Sports Complex (30 guests)

### **November 2025 Bookings (Future - for forecasting):**
- Nov 2: Corporate Event (120 guests)
- Nov 5: City Sports Fest (200 guests)
- Nov 8: Business Seminar (50 guests)
- Nov 10: Graduation Party (80 guests)
- Nov 12: Community Sports Day (150 guests)
- Nov 15: Training Workshop (40 guests)
- Nov 17: Engagement Party (90 guests)
- Nov 20: Dance Competition (100 guests)
- Nov 22: Networking Event (60 guests)
- Nov 25: Charity Event (140 guests)
- Nov 27: Sports Tournament (180 guests)

---

## 🗑️ IF YOU NEED TO REMOVE DUMMY DATA

To delete only the dummy data we just inserted:

```sql
-- Delete dummy data based on applicant emails
DELETE FROM bookings 
WHERE applicant_email IN (
  'juan@example.com',
  'maria@example.com',
  'pedro@example.com',
  'ana@example.com',
  'lisa@example.com',
  'roberto@example.com',
  'carmen@example.com',
  'michael@example.com',
  'sofia@example.com',
  'linda@example.com',
  'mayor@lgu1.gov.ph',
  'sports@lgu1.gov.ph',
  'brgy@example.com',
  'youth@example.com',
  'chamber@example.com',
  'school@example.com'
);
```

**Or delete all bookings (careful!):**
```sql
TRUNCATE TABLE bookings;
```

---

## 📊 CHECKING AI FORECAST RESULTS

After inserting the data and loading the AI Forecast page, you should see:

1. **Usage Analytics Chart:**
   - Shows historical booking trends
   - October data points visible

2. **Prediction Chart:**
   - Shows forecast for next 30 days
   - Based on historical patterns
   - Predictions for November bookings

3. **No Errors:**
   - "API returned successfully" message
   - Data points found (16+)

---

## 🚀 DEPLOYMENT CHECKLIST

- [ ] Pull latest code: `git pull origin main`
- [ ] Run SQL in phpMyAdmin: `DUMMY-DATA-bookings.sql`
- [ ] Verify data: Check bookings table in phpMyAdmin
- [ ] Test AI Forecast page: Visit `/admin/ai-forecast`
- [ ] Check for errors in browser console
- [ ] Verify charts are displaying

---

## 💡 TROUBLESHOOTING

### **Problem: Still shows "0 data points"**

**Check:**
1. Bookings have `status = 'approved'`
2. Bookings have `payment_status = 'paid'`
3. Clear browser cache and refresh

**Run this query:**
```sql
SELECT * FROM bookings 
WHERE status = 'approved' 
AND payment_status = 'paid'
ORDER BY event_date DESC;
```

### **Problem: AI forecast not loading**

**Check:**
1. Python AI service is running
2. TensorFlow dependencies installed
3. Check Laravel logs: `storage/logs/laravel.log`

---

## 📋 SUMMARY

**Quick Steps:**
1. Open phpMyAdmin → faci_facility database
2. SQL tab → Paste content from `DUMMY-DATA-bookings.sql`
3. Click "Go"
4. Verify 16 rows inserted
5. Test AI Forecast page

**Time:** 2-3 minutes  
**Data:** 16 realistic approved bookings  
**Result:** Working AI forecast with predictions 🎯

---

**Ready to deploy!** 🚀

