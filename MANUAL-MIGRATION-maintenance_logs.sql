-- ========================================
-- MANUAL MIGRATION: Create maintenance_logs Table
-- ========================================
-- Run this SQL in phpMyAdmin to create the maintenance_logs table
-- Database: faci_facility
-- ========================================

CREATE TABLE IF NOT EXISTS `maintenance_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_type` enum('repair','cleaning','inspection','preventive','emergency','other') NOT NULL DEFAULT 'repair',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `reported_by` varchar(255) DEFAULT NULL COMMENT 'Admin/Staff name',
  `reported_by_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'User ID if available',
  `assigned_to` varchar(255) DEFAULT NULL COMMENT 'Contractor/Staff assigned',
  `assigned_contact` varchar(255) DEFAULT NULL COMMENT 'Phone/Email of assignee',
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `scheduled_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `actual_cost` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `completion_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_logs_facility_id_foreign` (`facility_id`),
  CONSTRAINT `maintenance_logs_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`facility_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- SUCCESS!
-- ========================================
-- The maintenance_logs table has been created successfully.
-- You can now access the Maintenance Logs module.
-- ========================================

