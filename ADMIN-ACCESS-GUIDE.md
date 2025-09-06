# Admin Access Guide

## Login Credentials

### Admin Access
**Email:** `admin@lgu1.gov.ph`  
**Password:** `admin123`

### Staff Access (for requirement verification)
**Elena Rodriguez:** `elena.rodriguez@lgu1.gov.ph` / `staff123`  
**Carlos Mendoza:** `carlos.mendoza@lgu1.gov.ph` / `staff123`

**Login Instructions:** Use the same admin login page (`/admin/login`), but staff will be automatically redirected to the staff dashboard.

## Admin Dashboard Features

The admin dashboard has been redesigned to align with LGU reservation management requirements:

### 🎯 Quick Overview Metrics
- **Pending Approvals:** Real-time count of bookings awaiting admin action
- **Total Bookings:** Complete booking statistics for the current month
- **Total Revenue:** Calculated from approved bookings
- **Active Facilities:** Count of operational facilities

### 📋 Pending Approvals Section
- Lists all pending booking requests requiring immediate attention
- Shows applicant details, event information, and requested dates
- Quick approve/reject actions available

### 🏢 Facility Status Overview
- Real-time status of all 4 LGU facilities:
  - **Buena Park** (Multi-purpose outdoor venue)
  - **Sports Complex** (Complete sports facility) 
  - **Bulwagan Katipunan** (Indoor conference hall)
  - **Pacquiao Court** (Covered basketball court)
- Displays current availability and upcoming events

### 🔔 Real-time Alerts
- Notifications for new booking requests
- Alerts for today's events
- System status indicators

## Navigation Features

### Enhanced Sidebar
- **Active State Management:** Correctly highlights the current page
- **Reservation Workflow Priority:** Dashboard focuses on approval workflow
- **Quick Access:** Direct links to pending approvals and facility management

### Dashboard Routes
- **Main Dashboard:** `/admin/dashboard` - Overview and metrics
- **Quick Stats API:** `/admin/dashboard/quick-stats` - Real-time data updates

## Sample Data Available

## LGU Booking Workflow

### ✅ **Correct Process:**
1. **Citizens** must use their own accounts to book facilities
2. **Staff** verify submitted requirements and documents  
3. **Staff** approve requirements (or reject if incomplete)
4. **Admin** review staff-approved bookings for final approval

### 🚫 **Policy Enforcement:**
- Bookings using other people's accounts will be **rejected by staff**
- Only the account holder can book facilities
- All applicant information must match the user account

### Current Database Status:
- **Users:** 9 total (1 admin, 2 staff, 6 citizens)
- **Facilities:** 4 active facilities 
- **Bookings:** 6 total (2 pending, 4 approved with staff verification)
- **Revenue:** ₱39,750.00 from approved bookings

### Test Scenarios Available:
1. **Pending Staff Verification:** 2 bookings waiting for staff to verify requirements
2. **Pending Admin Approval:** Staff-verified bookings waiting for final admin approval
3. **Today's Events:** 1 active event happening today  
4. **Upcoming Events:** Multiple approved events scheduled
5. **Monthly Statistics:** Historical booking data for reporting

## Staff Portal Features

### ✅ **Staff Verification Workflow:**
1. **Citizens** submit booking requests
2. **Staff** log in through admin portal → auto-redirected to staff dashboard
3. **Staff** verify citizen requirements (documents, policy compliance)
4. **Staff** approve/reject requirements with notes
5. **Admin** receives staff-verified bookings for final approval

### 🔧 **Staff Interface Features:**
- **Dashboard:** Personal verification metrics and workload
- **Requirement Review:** Document verification with approve/reject actions
- **My Statistics:** Personal performance tracking
- **Focused Navigation:** Core staff functions only (profiles managed by administrators)

### 📊 **Current Test Data:**
- **2 Bookings** pending staff verification (ready for testing)
- **4 Bookings** already staff-verified (shows completed workflow)
- **2 Staff Users** available for testing different staff accounts

## Access Instructions

1. Navigate to the application root URL
2. Click "Admin Login" or go to `/admin/login`
3. Use the credentials above
4. Upon login, you'll be automatically redirected to the new dashboard
5. The sidebar will show "Dashboard" as active with full metrics displayed

## Technical Notes

- All routes are properly protected with role-based middleware
- Dashboard loads real data from the database
- Metrics update automatically when new bookings are created
- Sidebar active states persist correctly across page navigation

---
*Last updated: September 2025*
