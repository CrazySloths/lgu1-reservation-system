-- =====================================================
-- REMOVE DUPLICATE BOOKINGS FROM DATABASE
-- =====================================================
-- Purpose: Clean up duplicate booking records while keeping one copy of each event
-- 
-- Problem: Dummy data was inserted twice, causing duplicate events
-- Solution: Keep the booking with the LOWEST ID for each duplicate group
-- =====================================================

-- Step 1: Backup before deletion (optional - for safety)
-- CREATE TABLE bookings_backup AS SELECT * FROM bookings WHERE status = 'approved';

-- Step 2: Delete duplicate records, keeping only the one with the smallest ID
DELETE b1 FROM bookings b1
INNER JOIN bookings b2 
WHERE 
    b1.id > b2.id 
    AND b1.event_name = b2.event_name 
    AND b1.event_date = b2.event_date 
    AND b1.applicant_name = b2.applicant_name
    AND b1.start_time = b2.start_time
    AND b1.end_time = b2.end_time
    AND b1.status = 'approved'
    AND b2.status = 'approved';

-- Step 3: Verify cleanup - this should return 0 rows after cleanup
SELECT event_name, event_date, COUNT(*) as count 
FROM bookings 
WHERE status = 'approved' 
GROUP BY event_name, event_date 
HAVING COUNT(*) > 1;

-- Step 4: Verify final count - should be exactly 15 approved bookings
SELECT COUNT(*) as total_approved_bookings FROM bookings WHERE status = 'approved';

-- =====================================================
-- EXPECTED RESULTS AFTER CLEANUP:
-- - Step 3 query: 0 rows (no duplicates)
-- - Step 4 query: 15 total approved bookings
-- =====================================================
