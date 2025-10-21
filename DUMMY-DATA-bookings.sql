-- ==================================================
-- DUMMY DATA: Approved Bookings for AI Forecast Testing
-- ==================================================
-- Purpose: Insert sample approved bookings to test the 
--          TensorFlow AI forecasting feature
-- Date: October 21, 2025
-- Database: faci_facility
-- ==================================================

-- Insert approved bookings (past and future dates for AI training)
INSERT INTO `bookings` (
  `user_id`,
  `user_name`,
  `facility_id`,
  `applicant_name`,
  `applicant_email`,
  `applicant_phone`,
  `applicant_address`,
  `event_name`,
  `event_description`,
  `event_date`,
  `start_time`,
  `end_time`,
  `expected_attendees`,
  `total_fee`,
  `status`,
  `admin_notes`,
  `created_at`,
  `updated_at`
) VALUES
-- August 2025 bookings (historical)
(NULL, 'Roberto Santos', 1, 'Roberto Santos', 'roberto@example.com', '09181234567', '100 Main St, City', 'Summer Festival', 'Community summer event', '2025-08-15', '10:00:00', '17:00:00', 200, 2500.00, 'approved', NULL, '2025-08-01 10:00:00', '2025-08-01 10:00:00'),
(NULL, 'Grace Reyes', 2, 'Grace Reyes', 'grace@example.com', '09191234567', '200 Oak Ave, City', 'Basketball Camp', 'Youth basketball training', '2025-08-22', '08:00:00', '16:00:00', 60, 1600.00, 'approved', NULL, '2025-08-05 09:00:00', '2025-08-05 09:00:00'),

-- September 2025 bookings (historical)
(NULL, 'Carlos Martinez', 3, 'Carlos Martinez', 'carlos@example.com', '09151234567', '300 Pine Rd, City', 'Business Conference', 'Regional business summit', '2025-09-10', '08:00:00', '18:00:00', 150, 3500.00, 'approved', NULL, '2025-09-01 08:00:00', '2025-09-01 08:00:00'),
(NULL, 'Linda Cruz', 1, 'Linda Cruz', 'linda@example.com', '09161234567', '400 Elm St, City', 'Anniversary Party', 'Golden anniversary celebration', '2025-09-20', '16:00:00', '22:00:00', 120, 2200.00, 'approved', NULL, '2025-09-05 10:00:00', '2025-09-05 10:00:00'),

-- October 2025 bookings
(NULL, 'Juan Dela Cruz', 1, 'Juan Dela Cruz', 'juan@example.com', '09171234567', '123 Main St, City', 'Birthday Party', '50th Birthday Celebration', '2025-10-18', '14:00:00', '18:00:00', 100, 2000.00, 'approved', NULL, '2025-10-10 10:00:00', '2025-10-10 10:00:00'),
(NULL, 'Maria Santos', 2, 'Maria Santos', 'maria@example.com', '09187654321', '456 Oak Ave, City', 'Basketball Tournament', 'Inter-Barangay Basketball League', '2025-10-20', '08:00:00', '17:00:00', 50, 1800.00, 'approved', NULL, '2025-10-11 09:30:00', '2025-10-11 09:30:00'),
(NULL, 'Pedro Reyes', 1, 'Pedro Reyes', 'pedro@example.com', '09191234567', '789 Pine Rd, City', 'Wedding Reception', 'Wedding celebration', '2025-10-22', '17:00:00', '23:00:00', 180, 3000.00, 'approved', NULL, '2025-10-12 14:20:00', '2025-10-12 14:20:00'),

-- November 2025 bookings
(NULL, 'Lisa Fernandez', 1, 'Lisa Fernandez', 'lisa@example.com', '09141234567', '987 Birch Ave, City', 'Corporate Event', 'Company Anniversary', '2025-11-02', '10:00:00', '16:00:00', 120, 2500.00, 'approved', NULL, '2025-10-15 16:00:00', '2025-10-15 16:00:00'),
(NULL, 'City Government', 2, 'City Government', 'sports@lgu1.gov.ph', '09131234567', 'City Hall', 'City Sports Fest', 'Annual City Sports Festival', '2025-11-05', '07:00:00', '18:00:00', 200, 0.00, 'approved', 'Major city event - full day', '2025-10-15 08:00:00', '2025-10-15 09:00:00'),
(NULL, 'Roberto Cruz', 3, 'Roberto Cruz', 'roberto@example.com', '09151234567', '654 Cedar Ln, City', 'Business Seminar', 'Entrepreneurship Workshop', '2025-11-08', '09:00:00', '16:00:00', 50, 2100.00, 'approved', NULL, '2025-10-16 08:30:00', '2025-10-16 08:30:00'),
(NULL, 'Carmen Reyes', 1, 'Carmen Reyes', 'carmen@example.com', '09171122334', '111 Elm St, City', 'Graduation Party', 'High School Graduation', '2025-11-10', '15:00:00', '20:00:00', 80, 1800.00, 'approved', NULL, '2025-10-17 10:00:00', '2025-10-17 10:00:00'),

-- December 2025 bookings (for AI forecasting)
(NULL, 'Michael Torres', 3, 'Michael Torres', 'michael@example.com', '09193344556', '222 Oak St, City', 'Training Workshop', 'IT Skills Development', '2025-12-05', '08:00:00', '17:00:00', 40, 1900.00, 'approved', NULL, '2025-11-01 11:00:00', '2025-11-01 11:00:00'),
(NULL, 'Sofia Martinez', 1, 'Sofia Martinez', 'sofia@example.com', '09164455667', '333 Pine St, City', 'Christmas Party', 'Company Christmas celebration', '2025-12-15', '18:00:00', '23:00:00', 150, 3000.00, 'approved', NULL, '2025-11-20 14:00:00', '2025-11-20 14:00:00'),
(NULL, 'Youth Organization', 2, 'Youth Organization', 'youth@example.com', '09175566778', '444 Maple Ave, City', 'Year-End Sports Fest', 'Annual youth sports event', '2025-12-20', '08:00:00', '17:00:00', 180, 2500.00, 'approved', NULL, '2025-11-25 10:00:00', '2025-11-25 10:00:00'),
(NULL, 'Business Chamber', 3, 'Business Chamber', 'chamber@example.com', '09186677889', '555 Cedar Rd, City', 'New Year Gala', 'Business year-end networking', '2025-12-28', '17:00:00', '23:00:00', 200, 3500.00, 'approved', NULL, '2025-12-01 15:00:00', '2025-12-01 15:00:00');

-- ==================================================
-- VERIFICATION QUERY
-- ==================================================
-- Run this to verify the data was inserted:
-- SELECT COUNT(*) as total_approved FROM bookings WHERE status = 'approved';
-- SELECT * FROM bookings WHERE status = 'approved' ORDER BY event_date DESC LIMIT 10;
-- ==================================================

-- ==================================================
-- NOTES
-- ==================================================
-- This SQL inserts 15 approved bookings across 5 MONTHS:
-- - August 2025: 2 bookings
-- - September 2025: 2 bookings
-- - October 2025: 3 bookings
-- - November 2025: 4 bookings
-- - December 2025: 4 bookings
-- - Spread across 3 facilities
-- - Mix of citizen events and city events
-- - All have status = 'approved' for AI forecasting
-- 
-- This provides 5 months of historical data for the AI model,
-- which requires minimum 4 months (WINDOW_SIZE = 3, so 3+1 = 4)
-- ==================================================

