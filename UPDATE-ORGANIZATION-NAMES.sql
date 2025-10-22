-- ==================================================
-- UPDATE ORGANIZATION NAMES TO "UNIDENTIFIED"
-- ==================================================
-- Only citizens can book, so organization names 
-- should show as "Unidentified"
-- ==================================================

-- Update Business Chamber to Unidentified
UPDATE bookings 
SET applicant_name = 'Unidentified',
    user_name = 'Unidentified',
    applicant_email = 'unidentified@example.com',
    applicant_phone = '09000000000'
WHERE id = 15 
AND event_name = 'New Year Gala';

-- Update Youth Organization to Unidentified
UPDATE bookings 
SET applicant_name = 'Unidentified',
    user_name = 'Unidentified',
    applicant_email = 'unidentified@example.com',
    applicant_phone = '09000000000'
WHERE id = 14 
AND event_name = 'Year-End Sports Fest';

-- Verification
SELECT id, event_name, applicant_name, applicant_email, applicant_phone 
FROM bookings 
WHERE id IN (14, 15);

