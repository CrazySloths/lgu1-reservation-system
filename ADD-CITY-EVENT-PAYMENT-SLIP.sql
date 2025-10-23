-- Add Exempt Payment Slip for City Event
-- This creates a payment record for City Government events with "exempt" status
-- No amount, slip number, or cashier details needed - just tracking the event exists

-- Insert exempt payment slip for existing City Event (ID 31)
INSERT INTO payment_slips (
    booking_id,
    user_id,
    status,
    created_at,
    updated_at
) VALUES (
    31,  -- Booking ID for "CITY EVENT: Community Health Fair"
    1,   -- Admin user ID (as the system generator)
    'exempt',
    NOW(),
    NOW()
);

-- Verify the insert
SELECT 
    ps.id as payment_slip_id,
    ps.booking_id,
    b.event_name,
    b.applicant_name,
    b.event_date,
    ps.status,
    ps.created_at
FROM payment_slips ps
JOIN bookings b ON ps.booking_id = b.id
WHERE ps.status = 'exempt'
ORDER BY ps.id DESC;

