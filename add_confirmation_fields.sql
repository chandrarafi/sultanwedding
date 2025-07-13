-- Add confirmation fields to pembayaran table
ALTER TABLE `pembayaran` 
ADD COLUMN `dp_confirmed` TINYINT(1) DEFAULT 0 COMMENT '1 = DP dikonfirmasi, 0 = belum dikonfirmasi' AFTER `status`,
ADD COLUMN `dp_confirmed_at` DATETIME NULL AFTER `dp_confirmed`,
ADD COLUMN `dp_confirmed_by` INT(11) NULL AFTER `dp_confirmed_at`,
ADD COLUMN `h1_paid` TINYINT(1) DEFAULT 0 COMMENT '1 = H-1 dibayar, 0 = belum dibayar' AFTER `dp_confirmed_by`,
ADD COLUMN `h1_confirmed` TINYINT(1) DEFAULT 0 COMMENT '1 = H-1 dikonfirmasi, 0 = belum dikonfirmasi' AFTER `h1_paid`,
ADD COLUMN `h1_confirmed_at` DATETIME NULL AFTER `h1_confirmed`,
ADD COLUMN `h1_confirmed_by` INT(11) NULL AFTER `h1_confirmed_at`,
ADD COLUMN `full_confirmed` TINYINT(1) DEFAULT 0 COMMENT '1 = pelunasan dikonfirmasi, 0 = belum dikonfirmasi' AFTER `h1_confirmed_by`,
ADD COLUMN `full_confirmed_at` DATETIME NULL AFTER `full_confirmed`,
ADD COLUMN `full_confirmed_by` INT(11) NULL AFTER `full_confirmed_at`,
ADD COLUMN `rejected_reason` TEXT NULL AFTER `full_confirmed_by`,
ADD COLUMN `rejected_at` DATETIME NULL AFTER `rejected_reason`,
ADD COLUMN `rejected_by` INT(11) NULL AFTER `rejected_at`; 