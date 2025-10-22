-- ==================================================
-- UPDATE BOOKING FEES TO MATCH CORRECT PRICING
-- ==================================================
-- This script updates the total_fee column in bookings table
-- to match the correct pricing structure:
-- - Base (3 hours): ₱5,000
-- - Extension (per 2 hours): ₱2,000
-- ==================================================

UPDATE bookings SET total_fee = 9000.00 WHERE id = 1;  -- Summer Festival (7 hrs)
UPDATE bookings SET total_fee = 11000.00 WHERE id = 2; -- Basketball Camp (8 hrs)
UPDATE bookings SET total_fee = 13000.00 WHERE id = 3; -- Business Conference (10 hrs)
UPDATE bookings SET total_fee = 7000.00 WHERE id = 4;  -- Anniversary Party (6 hrs)
UPDATE bookings SET total_fee = 7000.00 WHERE id = 5;  -- Birthday Party (4 hrs)
UPDATE bookings SET total_fee = 11000.00 WHERE id = 6; -- Basketball Tournament (9 hrs)
UPDATE bookings SET total_fee = 7000.00 WHERE id = 7;  -- Wedding Reception (6 hrs)
UPDATE bookings SET total_fee = 7000.00 WHERE id = 8;  -- Corporate Event (6 hrs)
UPDATE bookings SET total_fee = 0.00 WHERE id = 9;     -- City Sports Fest (FREE - city event)
UPDATE bookings SET total_fee = 9000.00 WHERE id = 10; -- Business Seminar (7 hrs)
UPDATE bookings SET total_fee = 7000.00 WHERE id = 11; -- Graduation Party (5 hrs)
UPDATE bookings SET total_fee = 11000.00 WHERE id = 12; -- Training Workshop (9 hrs)
UPDATE bookings SET total_fee = 7000.00 WHERE id = 13; -- Christmas Party (5 hrs)
UPDATE bookings SET total_fee = 11000.00 WHERE id = 14; -- Year-End Sports Fest (9 hrs)
UPDATE bookings SET total_fee = 7000.00 WHERE id = 15; -- New Year Gala (6 hrs)

-- Verification
SELECT id, event_name, 
       CONCAT(TIME_FORMAT(start_time, '%H:%i'), ' - ', TIME_FORMAT(end_time, '%H:%i')) as time_range,
       TIMESTAMPDIFF(HOUR, start_time, end_time) as hours,
       total_fee
FROM bookings 
WHERE id BETWEEN 1 AND 15
ORDER BY id;

