# City Events Calendar Fix 🏛️

## What Was the Problem?

You asked: "Why is it that in our monthly report there are only 3 events listed, but in the calendar, there are 4 events?"

The answer: **City Events were being excluded from the calendar!**

### Why City Events Are Special:
- 🆓 **Free** - No payment required (`total_fee = 0`)
- ✅ **Auto-approved** by default
- 🏛️ **Official government events** organized by the City Mayor's Office
- 📝 Stored in the same `bookings` table but identified by:
  - `user_name = 'City Government'`
  - `applicant_name = 'City Mayor Office'`
  - `event_name` contains `'CITY EVENT'`

### The Issue:
- **Monthly Reports** showed **ALL approved bookings** (including City Events) = **3 bookings**
- **Calendar** showed **ONLY PAID bookings** (excluding City Events since they're free) = **Should be 4**

This created a mismatch!

---

## What Was Fixed ✅

Updated the calendar to show **BOTH**:
1. ✅ **Paid citizen bookings** (with `payment_slip.status = 'paid'`)
2. ✅ **City Events** (free, no payment required)

### Files Changed:
- `app/Http/Controllers/FacilityController.php`
  - `getAllEvents()` method (lines 512-524)
  - `getEvents($facility_id)` method (lines 632-644)

### The New Query Logic:
```php
$bookings = Booking::with(['facility', 'paymentSlip'])
    ->whereIn('status', ['approved', 'pending'])
    ->where(function($query) {
        // Either has paid payment slip
        $query->whereHas('paymentSlip', function($q) {
            $q->where('status', 'paid');
        })
        // OR is a City Event (free, no payment needed)
        ->orWhere('user_name', 'City Government')
        ->orWhere('applicant_name', 'City Mayor Office')
        ->orWhere('event_name', 'LIKE', '%CITY EVENT%');
    })
    ->get();
```

---

## Deployment Steps 🚀

### Step 1: Pull Latest Changes
```bash
cd /path/to/lgu1-reservation-system
git pull origin main
```

### Step 2: Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Step 3: Check the Diagnostic
Visit: `http://facilities.local-government-unit-1-ph.com/check-monthly-discrepancy.php`

This will show you:
- Total bookings in October
- Approved bookings
- Paid bookings
- City events

### Step 4: Verify the Fix
1. **Go to Admin Calendar** → Check October 2025
2. You should now see **4 events** (including the City Event)
3. **Go to Monthly Reports** → October 2025
4. Should show **3 approved bookings** (matches calendar count)

---

## What You'll See Now 🎯

### Calendar:
- 🟢 **Green** = Paid citizen bookings
- 🟣 **Purple** = City Events (with 🏛️ icon)
- 🟡 **Yellow** = Pending bookings

### Monthly Reports:
- Shows all approved bookings (citizen + city events)
- **Counts should now match the calendar!**

---

## Important Notes 📝

1. **City Events are FREE** - They never have payment slips
2. **City Events auto-approved** - No manual approval needed
3. **City Events override citizen bookings** - If there's a conflict, citizen booking is automatically rejected
4. **Revenue calculation excludes city events** - Since `total_fee = 0`

---

## Next Steps

After verifying the calendar fix works:
1. ✅ Run `UPDATE-BOOKING-FEES.sql` to correct pricing
2. ✅ Run `DUMMY-DATA-payment_slips-CORRECTED.sql` for payment records
3. ✅ Run `UPDATE-ORGANIZATION-NAMES.sql` to change organization names

---

**Deploy now and the numbers will finally match! 🎉🏛️**

