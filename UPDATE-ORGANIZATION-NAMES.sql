-- ==================================================
-- UPDATE ORGANIZATION NAMES TO DEFAULT PLACEHOLDER
-- ==================================================
-- Only citizens can book, so organization names 
-- should show as "Juan Dela Cruz" (Filipino "John Doe")
-- ==================================================

-- Update Business Chamber to Juan Dela Cruz (default placeholder name)
UPDATE bookings 
SET applicant_name = 'Juan Dela Cruz',
    user_name = 'Juan Dela Cruz',
    applicant_email = 'juandelacruz@example.com',
    applicant_phone = '09123456789'
WHERE id = 15 
AND event_name = 'New Year Gala';

-- Update Youth Organization to Juan Dela Cruz (default placeholder name)
UPDATE bookings 
SET applicant_name = 'Juan Dela Cruz',
    user_name = 'Juan Dela Cruz',
    applicant_email = 'juandelacruz@example.com',
    applicant_phone = '09123456789'
WHERE id = 14 
AND event_name = 'Year-End Sports Fest';

-- Verification
SELECT id, event_name, applicant_name, applicant_email, applicant_phone 
FROM bookings 
WHERE id IN (14, 15);

