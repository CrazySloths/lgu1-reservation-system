-- ==================================================
-- DUMMY DATA: Payment Slips with CORRECT PRICING
-- ==================================================
-- Pricing Structure:
-- - Base (3 hours): ₱5,000
-- - Extension (per 2 hours): ₱2,000
-- ==================================================

-- CALCULATED AMOUNTS:
-- 1. Summer Festival (10:00-17:00 = 7hrs) = ₱5,000 + ₱4,000 = ₱9,000
-- 2. Basketball Camp (08:00-16:00 = 8hrs) = ₱5,000 + ₱6,000 = ₱11,000
-- 3. Business Conference (08:00-18:00 = 10hrs) = ₱5,000 + ₱8,000 = ₱13,000
-- 4. Anniversary Party (16:00-22:00 = 6hrs) = ₱5,000 + ₱2,000 = ₱7,000
-- 5. Birthday Party (14:00-18:00 = 4hrs) = ₱5,000 + ₱2,000 = ₱7,000
-- 6. Basketball Tournament (08:00-17:00 = 9hrs) = ₱5,000 + ₱6,000 = ₱11,000
-- 7. Wedding Reception (17:00-23:00 = 6hrs) = ₱5,000 + ₱2,000 = ₱7,000
-- 8. Corporate Event (10:00-16:00 = 6hrs) = ₱5,000 + ₱2,000 = ₱7,000
-- 9. City Sports Fest (07:00-18:00 = 11hrs) = ₱0 (City event - waived)
-- 10. Business Seminar (09:00-16:00 = 7hrs) = ₱5,000 + ₱4,000 = ₱9,000
-- 11. Graduation Party (15:00-20:00 = 5hrs) = ₱5,000 + ₱2,000 = ₱7,000
-- 12. Training Workshop (08:00-17:00 = 9hrs) = ₱5,000 + ₱6,000 = ₱11,000
-- 13. Christmas Party (18:00-23:00 = 5hrs) = ₱5,000 + ₱2,000 = ₱7,000
-- 14. Year-End Sports Fest (08:00-17:00 = 9hrs) = ₱5,000 + ₱6,000 = ₱11,000
-- 15. New Year Gala (17:00-23:00 = 6hrs) = ₱5,000 + ₱2,000 = ₱7,000
--
-- TOTAL REVENUE (PAID): ₱64,000 (bookings 1-7)
-- TOTAL REVENUE (UNPAID): ₱66,000 (bookings 8, 10-15, excluding #9 waived)
-- GRAND TOTAL: ₱130,000

-- First, delete existing payment slips to avoid duplicates
DELETE FROM payment_slips;

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
-- August 2025 payments (PAID) - ₱20,000
('PS-2025-08-001', 1, 1, 1, 9000.00, 'paid', '2025-08-10 23:59:59', '2025-08-09 14:30:00', 'bank_transfer', '7 hours: Base ₱5K + 2 extensions ₱4K', 1, '2025-08-01 10:00:00', '2025-08-09 14:30:00'),
('PS-2025-08-002', 2, 1, 1, 11000.00, 'paid', '2025-08-12 23:59:59', '2025-08-11 09:15:00', 'online', '8 hours: Base ₱5K + 3 extensions ₱6K', 1, '2025-08-05 09:00:00', '2025-08-11 09:15:00'),

-- September 2025 payments (PAID) - ₱20,000
('PS-2025-09-001', 3, 1, 1, 13000.00, 'paid', '2025-09-08 23:59:59', '2025-09-07 16:00:00', 'bank_transfer', '10 hours: Base ₱5K + 4 extensions ₱8K', 1, '2025-09-01 08:00:00', '2025-09-07 16:00:00'),
('PS-2025-09-002', 4, 1, 1, 7000.00, 'paid', '2025-09-15 23:59:59', '2025-09-14 10:30:00', 'cash', '6 hours: Base ₱5K + 1 extension ₱2K', 1, '2025-09-05 10:00:00', '2025-09-14 10:30:00'),

-- October 2025 payments (PAID) - ₱25,000
('PS-2025-10-001', 5, 1, 1, 7000.00, 'paid', '2025-10-15 23:59:59', '2025-10-14 11:20:00', 'online', '4 hours: Base ₱5K + 1 extension ₱2K', 1, '2025-10-10 10:00:00', '2025-10-14 11:20:00'),
('PS-2025-10-002', 6, 1, 1, 11000.00, 'paid', '2025-10-18 23:59:59', '2025-10-17 15:45:00', 'bank_transfer', '9 hours: Base ₱5K + 3 extensions ₱6K', 1, '2025-10-11 09:30:00', '2025-10-17 15:45:00'),
('PS-2025-10-003', 7, 1, 1, 7000.00, 'paid', '2025-10-20 23:59:59', '2025-10-19 13:00:00', 'bank_transfer', '6 hours: Base ₱5K + 1 extension ₱2K', 1, '2025-10-12 14:20:00', '2025-10-19 13:00:00'),

-- November 2025 payments (UNPAID - future bookings) - ₱30,000
('PS-2025-11-001', 8, 1, 1, 7000.00, 'unpaid', '2025-10-28 23:59:59', NULL, NULL, '6 hours: Base ₱5K + 1 extension ₱2K', NULL, '2025-10-15 16:00:00', '2025-10-15 16:00:00'),
('PS-2025-11-002', 9, 1, 1, 0.00, 'paid', '2025-11-01 23:59:59', '2025-10-15 09:00:00', 'waived', 'City event - fee waived', 1, '2025-10-15 08:00:00', '2025-10-15 09:00:00'),
('PS-2025-11-003', 10, 1, 1, 9000.00, 'unpaid', '2025-11-03 23:59:59', NULL, NULL, '7 hours: Base ₱5K + 2 extensions ₱4K', NULL, '2025-10-16 08:30:00', '2025-10-16 08:30:00'),
('PS-2025-11-004', 11, 1, 1, 7000.00, 'unpaid', '2025-11-05 23:59:59', NULL, NULL, '5 hours: Base ₱5K + 1 extension ₱2K', NULL, '2025-10-17 10:00:00', '2025-10-17 10:00:00'),

-- December 2025 payments (UNPAID - future bookings) - ₱36,000
('PS-2025-12-001', 12, 1, 1, 11000.00, 'unpaid', '2025-11-28 23:59:59', NULL, NULL, '9 hours: Base ₱5K + 3 extensions ₱6K', NULL, '2025-11-01 11:00:00', '2025-11-01 11:00:00'),
('PS-2025-12-002', 13, 1, 1, 7000.00, 'unpaid', '2025-12-08 23:59:59', NULL, NULL, '5 hours: Base ₱5K + 1 extension ₱2K', NULL, '2025-11-20 14:00:00', '2025-11-20 14:00:00'),
('PS-2025-12-003', 14, 1, 1, 11000.00, 'unpaid', '2025-12-13 23:59:59', NULL, NULL, '9 hours: Base ₱5K + 3 extensions ₱6K', NULL, '2025-11-25 10:00:00', '2025-11-25 10:00:00'),
('PS-2025-12-004', 15, 1, 1, 7000.00, 'unpaid', '2025-12-21 23:59:59', NULL, NULL, '6 hours: Base ₱5K + 1 extension ₱2K', NULL, '2025-12-01 15:00:00', '2025-12-01 15:00:00');

-- ==================================================
-- VERIFICATION QUERY
-- ==================================================
-- SELECT COUNT(*) as total_payments FROM payment_slips;
-- SELECT COUNT(*) as paid_payments FROM payment_slips WHERE status = 'paid';
-- SELECT SUM(amount) as total_revenue FROM payment_slips WHERE status = 'paid';
-- SELECT COUNT(*) as unpaid_payments FROM payment_slips WHERE status = 'unpaid';
-- SELECT SUM(amount) as unpaid_amount FROM payment_slips WHERE status = 'unpaid';
-- ==================================================

-- ==================================================
-- EXPECTED RESULTS (CORRECTED)
-- ==================================================
-- Total Payment Slips: 15
-- Paid Slips: 8 (including 1 waived city event)
-- Unpaid Slips: 7
-- Total Revenue (Paid): ₱65,000.00
--   - August: ₱20,000.00
--   - September: ₱20,000.00
--   - October: ₱25,000.00
-- Unpaid Amount: ₱65,000.00
-- GRAND TOTAL (if all paid): ₱130,000.00
-- ==================================================

