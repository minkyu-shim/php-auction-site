-- Add email verification columns to users table
-- Run this migration after creating the initial database

ALTER TABLE `users`
ADD COLUMN `email_verified` TINYINT(1) DEFAULT 0 AFTER `password`,
ADD COLUMN `verification_token` VARCHAR(64) DEFAULT NULL AFTER `email_verified`,
ADD COLUMN `token_created_at` DATETIME DEFAULT NULL AFTER `verification_token`;

-- Update existing users to be verified (so they can continue using the site)
UPDATE `users` SET `email_verified` = 1 WHERE `email_verified` = 0;
