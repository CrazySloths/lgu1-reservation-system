# Create City Event for October 2025 🏛️

## Problem Identified ✅
The diagnostic revealed:
- **Total Bookings in October**: 3 (all citizen bookings)
- **City Events in October**: **0** ❌
- **That's why Monthly Reports shows 3 instead of 4!**

---

## Solution: Create a City Event 🎯

We'll add an **official City Event** to October 2025:
- 🏛️ **Event**: Community Health Fair
- 📅 **Date**: October 25, 2025 (Saturday)
- ⏰ **Time**: 8:00 AM - 5:00 PM (9 hours)
- 🆓 **Fee**: FREE (City Events don't charge)
- ✅ **Status**: Auto-approved
- 👥 **Attendees**: 500 (large community event)

---

## Deployment Steps

### Step 1: Import the City Event
```bash
mysql -u your_user -p your_database < CREATE-CITY-EVENT-OCTOBER.sql
```

**Or manually in phpMyAdmin/HeidiSQL:**
1. Open the SQL tab
2. Copy and paste the contents of `CREATE-CITY-EVENT-OCTOBER.sql`
3. Click "Execute"

### Step 2: Verify the City Event Was Created
The SQL script includes a verification query at the end. You should see:
```
id | event_name                          | user_name       | applicant_name    | event_date | ...
XX | CITY EVENT: Community Health Fair   | City Government | City Mayor Office | 2025-10-25 | ...
```

### Step 3: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Step 4: Check the Monthly Report
1. Go to **Admin** → **Monthly Reports**
2. Select **October 2025**
3. You should now see:
   - **Total Bookings**: 4 (was 3)
   - **Approved**: 4 (was 3)
   - **Top Users**: Should include "City Government" or "City Mayor Office"

### Step 5: Check the Calendar
1. Go to **Admin** → **Calendar**
2. Look at **October 25, 2025**
3. You should see a **purple event** 🏛️ labeled:
   - "Community Health Fair" or "CITY EVENT: Community Health Fair"
4. This confirms the City Event is visible!

---

## How City Events Work 🏛️

### Identification
A booking is considered a **City Event** if ANY of these are true:
- `user_name = 'City Government'`
- `applicant_name = 'City Mayor Office'`
- `event_name` contains `'CITY EVENT'`

### Special Characteristics
1. ✅ **Auto-approved** - No manual approval needed
2. 🆓 **FREE** - `total_fee = 0` (no payment slip required)
3. 🎨 **Purple color** on calendar (distinguishes from citizen bookings)
4. 🏛️ **Icon**: Shows city building emoji 🏛️
5. 📋 **Priority** - Can override conflicting citizen bookings
6. 💰 **Not counted in revenue** - Since they're free

### Calendar Display Logic
The calendar now shows:
- **Green** 🟢 = Paid citizen bookings
- **Purple** 🟣 = City Events (free, official)
- **Yellow** 🟡 = Pending bookings

---

## Test Checklist ✅

After deploying the City Event:
- [ ] Monthly Report October shows **4 total bookings**
- [ ] Monthly Report shows **4 approved bookings**
- [ ] Calendar displays the City Event on **October 25** in **purple**
- [ ] City Event has **₱0** fee
- [ ] "Top Users" includes **City Government** or **City Mayor Office**
- [ ] Facility Usage shows the facility used for the City Event

---

## Creating More City Events

To create additional City Events in the future:

### Via Admin Panel
1. Go to **Official City Events** → **Create New Event**
2. Fill in the details
3. Provide **Mayor Authorization** number
4. Event is **auto-approved** and appears immediately

### Via SQL (Manual)
Use the same template in `CREATE-CITY-EVENT-OCTOBER.sql` but change:
- `event_name` - Make it descriptive
- `event_date` - Set your desired date
- `start_time` / `end_time` - Event duration
- `facility_id` - Which facility to use
- `expected_attendees` - Crowd size
- `admin_notes` - Mayor authorization details

---

## Important Notes 📝

1. **City Events don't need payment slips** - They're free!
2. **City Events override citizen bookings** - If there's a conflict, citizen booking is auto-rejected
3. **Revenue excluded** - City Events (₱0) don't affect revenue calculations
4. **No approval workflow** - City Events are instantly approved
5. **Calendar visibility** - Shows even without payment slips (unlike citizen bookings)

---

## Next Steps After City Event

Once the City Event is created:
1. ✅ Run `UPDATE-BOOKING-FEES.sql` to correct citizen booking fees
2. ✅ Run `DUMMY-DATA-payment_slips-CORRECTED.sql` for payment records
3. ✅ Run `UPDATE-ORGANIZATION-NAMES.sql` to change organization names

---

**Deploy the City Event now and your counts will match! 🎉🏛️**

