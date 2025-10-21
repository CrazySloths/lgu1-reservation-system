-- ==================================================
-- MANUAL MIGRATION: citizen_feedback table
-- ==================================================
-- Purpose: Create the citizen_feedback table for the 
--          Citizen Feedback feature
-- Date: October 21, 2025
-- Database: faci_facility
-- ==================================================

-- Check if table exists and drop it (optional, for fresh install)
-- DROP TABLE IF EXISTS `citizen_feedback`;

-- Create citizen_feedback table
CREATE TABLE IF NOT EXISTS `citizen_feedback` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `category` VARCHAR(255) NOT NULL,
  `question` TEXT NOT NULL,
  `status` ENUM('pending', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'pending',
  `admin_response` TEXT NULL,
  `responded_by` BIGINT UNSIGNED NULL,
  `responded_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `citizen_feedback_responded_by_foreign` 
    FOREIGN KEY (`responded_by`) 
    REFERENCES `users` (`id`) 
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==================================================
-- VERIFICATION QUERY
-- ==================================================
-- Run this after creating the table to verify:
-- SELECT * FROM citizen_feedback;
-- DESCRIBE citizen_feedback;
-- ==================================================

-- ==================================================
-- INSERT RECORD INTO MIGRATIONS TABLE
-- ==================================================
-- This tells Laravel that this migration has been run
INSERT INTO `migrations` (`migration`, `batch`) 
VALUES ('2025_10_15_045111_create_citizen_feedback_table', 
        (SELECT IFNULL(MAX(batch), 0) + 1 FROM (SELECT batch FROM migrations) AS temp))
ON DUPLICATE KEY UPDATE migration = migration;

-- ==================================================
-- DONE!
-- ==================================================

