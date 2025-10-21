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
-- Past bookings (October 2025)
(1, 1, 'Juan Dela Cruz', 'juan@example.com', '09171234567', '123 Main St, City', 'Birthday Party', '50th Birthday Celebration', '2025-10-18', '14:00:00', '18:00:00', 100, 2000.00, 'approved', NULL, '2025-10-10 10:00:00', '2025-10-10 10:00:00'),
(2, 2, 'Maria Santos', 'maria@example.com', '09187654321', '456 Oak Ave, City', 'Basketball Tournament', 'Inter-Barangay Basketball League', '2025-10-20', '08:00:00', '17:00:00', 50, 1800.00, 'approved', NULL, '2025-10-11 09:30:00', '2025-10-11 09:30:00'),
(3, 1, 'Pedro Reyes', 'pedro@example.com', '09191234567', '789 Pine Rd, City', 'Wedding Reception', 'Wedding celebration', '2025-10-22', '17:00:00', '23:00:00', 180, 3000.00, 'approved', NULL, '2025-10-12 14:20:00', '2025-10-12 14:20:00'),
(1, 3, 'City Mayor Office', 'mayor@lgu1.gov.ph', '09121234567', 'City Hall', 'Community Forum', 'Monthly Community Town Hall Meeting', '2025-10-25', '14:00:00', '17:00:00', 150, 0.00, 'approved', 'Priority city event', '2025-10-13 09:00:00', '2025-10-13 10:00:00'),
(4, 2, 'Ana Garcia', 'ana@example.com', '09161234567', '321 Maple St, City', 'Youth Sports Clinic', 'Free basketball coaching for youth', '2025-10-27', '08:00:00', '12:00:00', 30, 800.00, 'approved', NULL, '2025-10-14 11:00:00', '2025-10-14 11:00:00'),

-- Future bookings (November 2025 - for forecasting)
(5, 1, 'Lisa Fernandez', 'lisa@example.com', '09141234567', '987 Birch Ave, City', 'Corporate Event', 'Company Anniversary', '2025-11-02', '10:00:00', '16:00:00', 120, 2500.00, 'approved', NULL, '2025-10-15 16:00:00', '2025-10-15 16:00:00'),
(2, 2, 'City Government', 'sports@lgu1.gov.ph', '09131234567', 'City Hall', 'City Sports Fest', 'Annual City Sports Festival', '2025-11-05', '07:00:00', '18:00:00', 200, 0.00, 'approved', 'Major city event - full day', '2025-10-15 08:00:00', '2025-10-15 09:00:00'),
(6, 3, 'Roberto Cruz', 'roberto@example.com', '09151234567', '654 Cedar Ln, City', 'Business Seminar', 'Entrepreneurship Workshop', '2025-11-08', '09:00:00', '16:00:00', 50, 2100.00, 'approved', NULL, '2025-10-16 08:30:00', '2025-10-16 08:30:00'),
(1, 1, 'Carmen Reyes', 'carmen@example.com', '09171122334', '111 Elm St, City', 'Graduation Party', 'High School Graduation', '2025-11-10', '15:00:00', '20:00:00', 80, 1800.00, 'approved', NULL, '2025-10-17 10:00:00', '2025-10-17 10:00:00'),
(3, 2, 'Barangay Office', 'brgy@example.com', '09182233445', 'Barangay Hall', 'Community Sports Day', 'Barangay sports activities', '2025-11-12', '06:00:00', '17:00:00', 150, 1500.00, 'approved', NULL, '2025-10-18 09:00:00', '2025-10-18 09:00:00'),

-- More future bookings (mid-November)
(4, 3, 'Michael Torres', 'michael@example.com', '09193344556', '222 Oak St, City', 'Training Workshop', 'IT Skills Development', '2025-11-15', '08:00:00', '17:00:00', 40, 1900.00, 'approved', NULL, '2025-10-19 11:00:00', '2025-10-19 11:00:00'),
(5, 1, 'Sofia Martinez', 'sofia@example.com', '09164455667', '333 Pine St, City', 'Engagement Party', 'Engagement celebration', '2025-11-17', '18:00:00', '23:00:00', 90, 2200.00, 'approved', NULL, '2025-10-20 14:00:00', '2025-10-20 14:00:00'),
(1, 2, 'Youth Organization', 'youth@example.com', '09175566778', '444 Maple Ave, City', 'Dance Competition', 'Regional dance contest', '2025-11-20', '10:00:00', '19:00:00', 100, 2000.00, 'approved', NULL, '2025-10-21 10:00:00', '2025-10-21 10:00:00'),
(2, 3, 'Business Chamber', 'chamber@example.com', '09186677889', '555 Cedar Rd, City', 'Networking Event', 'Business networking night', '2025-11-22', '17:00:00', '21:00:00', 60, 1600.00, 'approved', NULL, '2025-10-21 15:00:00', '2025-10-21 15:00:00'),

-- Late November bookings
(6, 1, 'Linda Garcia', 'linda@example.com', '09197788990', '666 Birch Ln, City', 'Charity Event', 'Fundraising gala', '2025-11-25', '16:00:00', '22:00:00', 140, 2800.00, 'approved', NULL, '2025-10-22 09:00:00', '2025-10-22 09:00:00'),
(3, 2, 'School District', 'school@example.com', '09168899001', '777 Elm Ave, City', 'Sports Tournament', 'Inter-school basketball', '2025-11-27', '08:00:00', '18:00:00', 180, 2000.00, 'approved', NULL, '2025-10-22 11:00:00', '2025-10-22 11:00:00');

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
-- This SQL inserts 16 approved bookings:
-- - 5 in October (past data for training)
-- - 11 in November (future data for forecasting)
-- - Spread across 3 facilities
-- - Mix of citizen events and city events
-- - All have payment_status = 'paid' and status = 'approved'
-- ==================================================

