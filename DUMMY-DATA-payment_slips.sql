-- ==================================================
-- DUMMY DATA: Payment Slips for Approved Bookings
-- ==================================================
-- Purpose: Insert payment slips to match approved bookings
--          so revenue shows on dashboard
-- Date: October 22, 2025
-- Database: faci_facility
-- ==================================================

-- NOTE: Get the booking IDs first, then create payment slips
-- This assumes bookings table has sequential IDs 1-15

-- NOTE: Adjust booking_id, user_id, and generated_by values to match your actual data
-- Run these queries first to get the correct IDs:
-- SELECT id FROM bookings ORDER BY id;
-- SELECT id FROM users WHERE role = 'admin' LIMIT 1;

-- For this example, assuming:
-- - Bookings have IDs 1-15
-- - Admin user ID is 1
-- - Citizens have user_ids matching booking order

INSERT INTO `payment_slips` (
  `slip_number`,
  `booking_id`,
  `user_id`,
  `generated_by`,
  `amount`,
  `status`,
  `due_date`,
  `paid_at`,
  `payment_method`,
  `cashier_notes`,
  `paid_by_cashier`,
  `created_at`,
  `updated_at`
) VALUES
-- August 2025 payments (PAID)
('PS-2025-08-001', 1, 1, 1, 2500.00, 'paid', '2025-08-10 23:59:59', '2025-08-09 14:30:00', 'bank_transfer', 'Payment for Summer Festival', 1, '2025-08-01 10:00:00', '2025-08-09 14:30:00'),
('PS-2025-08-002', 2, 1, 1, 1600.00, 'paid', '2025-08-12 23:59:59', '2025-08-11 09:15:00', 'online', 'Payment for Basketball Camp via GCash', 1, '2025-08-05 09:00:00', '2025-08-11 09:15:00'),

-- September 2025 payments (PAID)
('PS-2025-09-001', 3, 1, 1, 3500.00, 'paid', '2025-09-08 23:59:59', '2025-09-07 16:00:00', 'bank_transfer', 'Payment for Business Conference', 1, '2025-09-01 08:00:00', '2025-09-07 16:00:00'),
('PS-2025-09-002', 4, 1, 1, 2200.00, 'paid', '2025-09-15 23:59:59', '2025-09-14 10:30:00', 'cash', 'Payment for Anniversary Party', 1, '2025-09-05 10:00:00', '2025-09-14 10:30:00'),

-- October 2025 payments (PAID)
('PS-2025-10-001', 5, 1, 1, 2000.00, 'paid', '2025-10-15 23:59:59', '2025-10-14 11:20:00', 'online', 'Payment for Birthday Party via GCash', 1, '2025-10-10 10:00:00', '2025-10-14 11:20:00'),
('PS-2025-10-002', 6, 1, 1, 1800.00, 'paid', '2025-10-18 23:59:59', '2025-10-17 15:45:00', 'bank_transfer', 'Payment for Basketball Tournament', 1, '2025-10-11 09:30:00', '2025-10-17 15:45:00'),
('PS-2025-10-003', 7, 1, 1, 3000.00, 'paid', '2025-10-20 23:59:59', '2025-10-19 13:00:00', 'bank_transfer', 'Payment for Wedding Reception', 1, '2025-10-12 14:20:00', '2025-10-19 13:00:00'),

-- November 2025 payments (UNPAID - future bookings)
('PS-2025-11-001', 8, 1, 1, 2500.00, 'unpaid', '2025-10-28 23:59:59', NULL, NULL, 'Awaiting payment for Corporate Event', NULL, '2025-10-15 16:00:00', '2025-10-15 16:00:00'),
('PS-2025-11-002', 9, 1, 1, 0.00, 'paid', '2025-11-01 23:59:59', '2025-10-15 09:00:00', 'waived', 'City event - fee waived', 1, '2025-10-15 08:00:00', '2025-10-15 09:00:00'),
('PS-2025-11-003', 10, 1, 1, 2100.00, 'unpaid', '2025-11-03 23:59:59', NULL, NULL, 'Awaiting payment for Business Seminar', NULL, '2025-10-16 08:30:00', '2025-10-16 08:30:00'),
('PS-2025-11-004', 11, 1, 1, 1800.00, 'unpaid', '2025-11-05 23:59:59', NULL, NULL, 'Awaiting payment for Graduation Party', NULL, '2025-10-17 10:00:00', '2025-10-17 10:00:00'),

-- December 2025 payments (UNPAID - future bookings)
('PS-2025-12-001', 12, 1, 1, 1900.00, 'unpaid', '2025-11-28 23:59:59', NULL, NULL, 'Awaiting payment for Training Workshop', NULL, '2025-11-01 11:00:00', '2025-11-01 11:00:00'),
('PS-2025-12-002', 13, 1, 1, 3000.00, 'unpaid', '2025-12-08 23:59:59', NULL, NULL, 'Awaiting payment for Christmas Party', NULL, '2025-11-20 14:00:00', '2025-11-20 14:00:00'),
('PS-2025-12-003', 14, 1, 1, 2500.00, 'unpaid', '2025-12-13 23:59:59', NULL, NULL, 'Awaiting payment for Year-End Sports Fest', NULL, '2025-11-25 10:00:00', '2025-11-25 10:00:00'),
('PS-2025-12-004', 15, 1, 1, 3500.00, 'unpaid', '2025-12-21 23:59:59', NULL, NULL, 'Awaiting payment for New Year Gala', NULL, '2025-12-01 15:00:00', '2025-12-01 15:00:00');

-- ==================================================
-- VERIFICATION QUERY
-- ==================================================
-- Run this to verify the data was inserted:
-- SELECT COUNT(*) as total_payments FROM payment_slips;
-- SELECT COUNT(*) as paid_payments FROM payment_slips WHERE status = 'paid';
-- SELECT SUM(amount) as total_revenue FROM payment_slips WHERE status = 'paid';
-- SELECT COUNT(*) as unpaid_payments FROM payment_slips WHERE status = 'unpaid';
-- ==================================================

-- ==================================================
-- EXPECTED RESULTS
-- ==================================================
-- Total Payment Slips: 15
-- Paid Slips: 8 (including 1 waived city event)
-- Unpaid Slips: 7
-- Total Revenue (Paid): ₱18,600.00
--   - August: ₱4,100.00
--   - September: ₱5,700.00
--   - October: ₱6,800.00
-- Unpaid Amount: ₱13,300.00
-- ==================================================

