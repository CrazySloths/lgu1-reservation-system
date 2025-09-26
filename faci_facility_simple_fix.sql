-- Simple fix for faci_facility database
-- This script will add missing columns and insert required users
-- Works with existing database structure

-- Use the faci_facility database
USE faci_facility;

-- Add missing columns to users table (if they don't exist)
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `role` enum('citizen','staff','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'citizen',
ADD COLUMN IF NOT EXISTS `status` enum('active','inactive','suspended','pending_verification') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_verification',
ADD COLUMN IF NOT EXISTS `external_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `sso_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `sso_token_expires_at` timestamp NULL DEFAULT NULL;

-- Add indexes for new columns (if they don't exist)
ALTER TABLE `users` 
ADD UNIQUE KEY IF NOT EXISTS `users_external_id_unique` (`external_id`),
ADD UNIQUE KEY IF NOT EXISTS `users_sso_token_unique` (`sso_token`);

-- Insert the staff user (only with basic columns that should exist)
INSERT IGNORE INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
('Staff-Facilities123', 'staff-facilities123@sso.local', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'active', NOW(), NOW());

-- Insert admin user (only with basic columns that should exist)
INSERT IGNORE INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
('Admin-Facilities123', 'admin-facilities123@sso.local', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW(), NOW());

-- Create sessions table for session storage (if it doesn't exist)
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Completion message
SELECT 'Database update completed successfully!' AS status;
SELECT 'Added missing columns and users' AS info;
