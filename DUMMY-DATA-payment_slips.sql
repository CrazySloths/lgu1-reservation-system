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

INSERT INTO `payment_slips` (
  `booking_id`,
  `amount`,
  `reference_number`,
  `payment_method`,
  `status`,
  `due_date`,
  `paid_at`,
  `notes`,
  `created_at`,
  `updated_at`
) VALUES
-- August 2025 payments (PAID)
(1, 2500.00, 'PAY-2025-08-001', 'bank_transfer', 'paid', '2025-08-10', '2025-08-09 14:30:00', 'Payment for Summer Festival', '2025-08-01 10:00:00', '2025-08-09 14:30:00'),
(2, 1600.00, 'PAY-2025-08-002', 'gcash', 'paid', '2025-08-12', '2025-08-11 09:15:00', 'Payment for Basketball Camp', '2025-08-05 09:00:00', '2025-08-11 09:15:00'),

-- September 2025 payments (PAID)
(3, 3500.00, 'PAY-2025-09-001', 'bank_transfer', 'paid', '2025-09-08', '2025-09-07 16:00:00', 'Payment for Business Conference', '2025-09-01 08:00:00', '2025-09-07 16:00:00'),
(4, 2200.00, 'PAY-2025-09-002', 'cash', 'paid', '2025-09-15', '2025-09-14 10:30:00', 'Payment for Anniversary Party', '2025-09-05 10:00:00', '2025-09-14 10:30:00'),

-- October 2025 payments (PAID)
(5, 2000.00, 'PAY-2025-10-001', 'gcash', 'paid', '2025-10-15', '2025-10-14 11:20:00', 'Payment for Birthday Party', '2025-10-10 10:00:00', '2025-10-14 11:20:00'),
(6, 1800.00, 'PAY-2025-10-002', 'bank_transfer', 'paid', '2025-10-18', '2025-10-17 15:45:00', 'Payment for Basketball Tournament', '2025-10-11 09:30:00', '2025-10-17 15:45:00'),
(7, 3000.00, 'PAY-2025-10-003', 'bank_transfer', 'paid', '2025-10-20', '2025-10-19 13:00:00', 'Payment for Wedding Reception', '2025-10-12 14:20:00', '2025-10-19 13:00:00'),

-- November 2025 payments (PENDING - future bookings)
(8, 2500.00, 'PAY-2025-11-001', NULL, 'pending', '2025-10-28', NULL, 'Awaiting payment for Corporate Event', '2025-10-15 16:00:00', '2025-10-15 16:00:00'),
(9, 0.00, 'PAY-2025-11-002', 'waived', 'paid', '2025-11-01', '2025-10-15 09:00:00', 'City event - fee waived', '2025-10-15 08:00:00', '2025-10-15 09:00:00'),
(10, 2100.00, 'PAY-2025-11-003', NULL, 'pending', '2025-11-03', NULL, 'Awaiting payment for Business Seminar', '2025-10-16 08:30:00', '2025-10-16 08:30:00'),
(11, 1800.00, 'PAY-2025-11-004', NULL, 'pending', '2025-11-05', NULL, 'Awaiting payment for Graduation Party', '2025-10-17 10:00:00', '2025-10-17 10:00:00'),

-- December 2025 payments (PENDING - future bookings)
(12, 1900.00, 'PAY-2025-12-001', NULL, 'pending', '2025-11-28', NULL, 'Awaiting payment for Training Workshop', '2025-11-01 11:00:00', '2025-11-01 11:00:00'),
(13, 3000.00, 'PAY-2025-12-002', NULL, 'pending', '2025-12-08', NULL, 'Awaiting payment for Christmas Party', '2025-11-20 14:00:00', '2025-11-20 14:00:00'),
(14, 2500.00, 'PAY-2025-12-003', NULL, 'pending', '2025-12-13', NULL, 'Awaiting payment for Year-End Sports Fest', '2025-11-25 10:00:00', '2025-11-25 10:00:00'),
(15, 3500.00, 'PAY-2025-12-004', NULL, 'pending', '2025-12-21', NULL, 'Awaiting payment for New Year Gala', '2025-12-01 15:00:00', '2025-12-01 15:00:00');

-- ==================================================
-- VERIFICATION QUERY
-- ==================================================
-- Run this to verify the data was inserted:
-- SELECT COUNT(*) as total_payments FROM payment_slips;
-- SELECT COUNT(*) as paid_payments FROM payment_slips WHERE status = 'paid';
-- SELECT SUM(amount) as total_revenue FROM payment_slips WHERE status = 'paid';
-- ==================================================

-- ==================================================
-- EXPECTED RESULTS
-- ==================================================
-- Total Payment Slips: 15
-- Paid Slips: 8
-- Total Revenue: ₱18,600.00
--   - August: ₱4,100.00
--   - September: ₱5,700.00
--   - October: ₱6,800.00
-- Pending Revenue: ₱13,300.00
-- ==================================================

