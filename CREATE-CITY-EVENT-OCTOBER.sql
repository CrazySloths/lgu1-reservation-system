-- Create a City Event for October 2025
-- This event is FREE (total_fee = 0) and auto-approved

INSERT INTO bookings (
    facility_id,
    user_id,
    user_name,
    applicant_name,
    applicant_email,
    applicant_phone,
    applicant_address,
    event_name,
    event_description,
    event_date,
    start_time,
    end_time,
    expected_attendees,
    total_fee,
    status,
    approved_by,
    approved_at,
    admin_notes,
    created_at,
    updated_at
) VALUES (
    1,  -- facility_id (adjust if needed: 1=Buena Park, 2=Sports Complex, etc.)
    1,  -- user_id (admin user ID)
    'City Government',  -- Identifies this as a City Event
    'City Mayor Office',  -- Official organizer
    'mayor@lgu1.gov.ph',  -- City email
    '(000) 000-0000',  -- City contact
    'City Hall, LGU1',  -- City address
    'CITY EVENT: Community Health Fair',  -- Event name (starts with "CITY EVENT:")
    'Annual community health fair organized by the City Government. Free health screening, vaccination, and medical consultation for all residents.\n\nMayor Authorization: Executive Order No. 2025-10-001',
    '2025-10-25',  -- event_date (October 25, 2025 - Saturday)
    '08:00:00',  -- start_time (8:00 AM)
    '17:00:00',  -- end_time (5:00 PM - 9 hours event)
    500,  -- expected_attendees (large community event)
    0,  -- total_fee (FREE - City Events don't charge)
    'approved',  -- Auto-approved
    1,  -- approved_by (admin user ID)
    NOW(),  -- approved_at (current timestamp)
    'City Event - Mayor Authorization: Executive Order No. 2025-10-001. Priority booking, overrides any conflicting citizen reservations.',
    NOW(),  -- created_at
    NOW()   -- updated_at
);

-- Verify the insert
SELECT 
    id,
    event_name,
    user_name,
    applicant_name,
    event_date,
    start_time,
    end_time,
    total_fee,
    status,
    'City Event' as event_type
FROM bookings 
WHERE user_name = 'City Government' 
   OR applicant_name = 'City Mayor Office'
ORDER BY event_date DESC
LIMIT 5;

