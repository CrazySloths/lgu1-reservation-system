-- ==================================================
-- UPDATE ORGANIZATION NAMES TO DEFAULT PLACEHOLDERS
-- ==================================================
-- Only citizens can book, so organization names 
-- should show as default placeholder names (like "John Doe" / "Jane Doe")
-- ==================================================

-- Update Business Chamber to Juan Dela Cruz (Filipino "John Doe")
UPDATE bookings 
SET applicant_name = 'Juan Dela Cruz',
    user_name = 'Juan Dela Cruz',
    applicant_email = 'juandelacruz@example.com',
    applicant_phone = '09123456789'
WHERE id = 15 
AND event_name = 'New Year Gala';

-- Update Youth Organization to Maria Clara (Filipino "Jane Doe")
UPDATE bookings 
SET applicant_name = 'Maria Clara',
    user_name = 'Maria Clara',
    applicant_email = 'mariaclara@example.com',
    applicant_phone = '09987654321'
WHERE id = 14 
AND event_name = 'Year-End Sports Fest';

-- Verification
SELECT id, event_name, applicant_name, applicant_email, applicant_phone 
FROM bookings 
WHERE id IN (14, 15);

