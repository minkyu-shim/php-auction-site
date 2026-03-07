-- Add email verification columns to users table
-- Run this migration only for databases that were created before
-- email verification was added to sql/auction_db.sql

ALTER TABLE `users`
ADD COLUMN IF NOT EXISTS `email_verified` TINYINT(1) DEFAULT 0 AFTER `password`,
ADD COLUMN IF NOT EXISTS `verification_token` VARCHAR(64) DEFAULT NULL AFTER `email_verified`,
ADD COLUMN IF NOT EXISTS `token_created_at` DATETIME DEFAULT NULL AFTER `verification_token`;

-- Update existing users to be verified (so they can continue using the site)
UPDATE `users`
SET `email_verified` = 1
WHERE `email_verified` = 0
  AND `verification_token` IS NULL
  AND `token_created_at` IS NULL;
