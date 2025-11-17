/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

--
-- Remove invoice delete labour and parts page permissions
--

DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'invoice:delete_labour';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'invoice:delete_parts';

--
-- Add Cron Component
--

INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES
('cronjob:details', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('cronjob:edit', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('cronjob:overview', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('cronjob:run', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('cronjob:unlock', 1, 0, 0, 0, 0, 0, 0, 0, 0);

CREATE TABLE `#__cronjob_records` (
  `cronjob_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `pseudo_allowed` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `default_settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_run_time` datetime DEFAULT NULL,
  `last_run_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `locked` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `minute` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hour` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weekday` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `command` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__cronjob_records` (`cronjob_id`, `name`, `description`, `active`, `pseudo_allowed`, `default_settings`, `last_run_time`, `last_run_status`, `locked`, `minute`, `hour`, `day`, `month`, `weekday`, `command`) VALUES
(1, 'Test Cron', '<p>This cronjob is designed to check the basic functionality of the cronjob system. When enabled it will send an email every 15 minutes from QWcrm to the configured company email address. You can also run the cronjob manually to test immediately.</p>', 0, 1, '{\"active\":\"0\",\"pseudo_allowed\":\"1\",\"minute\":\"*\\/15\",\"hour\":\"*\",\"day\":\"*\",\"month\":\"*\",\"weekday\":\"*\"}', NULL, 1, 0, '*/15', '*', '*', '*', '*', '{\"class\":\"Cronjob\",\"function\":\"cronjobTest\"}'),
(2, 'Voucher Expiry', '<p>This cronjob when run, will check all vouchers for their expiry status. Vouchers that are expired and have not been flagged will have their status changed to expired.</p>', 1, 1, '{\"active\":\"1\",\"pseudo_allowed\":\"1\",\"minute\":\"0\",\"hour\":\"0\",\"day\":\"*\",\"month\":\"*\",\"weekday\":\"*\"}', NULL, 1, 0, '0', '0', '*', '*', '*', '{\"class\":\"Cronjob\",\"function\":\"cronjobCheckAllVouchersForExpiry\"}'),
(3, 'Credit Note Expiry', '<p>This cronjob when run, will check all credit notes for their expiry status. credit notes that are expired and have not been flagged will have their status changed to expired.</p>', 1, 1, '{\"active\":\"1\",\"pseudo_allowed\":\"1\",\"minute\":\"0\",\"hour\":\"0\",\"day\":\"*\",\"month\":\"*\",\"weekday\":\"*\"}', NULL, 1, 0, '0', '0', '*', '*', '*', '{\"class\":\"Cronjob\",\"function\":\"cronjobCheckAllCreditnotesForExpiry\"}');

ALTER TABLE `#__cronjob_records` ADD PRIMARY KEY (`cronjob_id`);

CREATE TABLE `#__cronjob_system` (
  `last_run_time` datetime NOT NULL,
  `last_run_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `locked` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__cronjob_system` (`last_run_time`, `last_run_status`, `locked`) VALUES
(NULL, 0, 0);

ALTER TABLE `#__cronjob_system` ADD PRIMARY KEY (`last_run_time`);

--
-- Convert sub_total -> subtotal
--

ALTER TABLE `#__invoice_labour` CHANGE `sub_total_net` `subtotal_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_labour` CHANGE `sub_total_tax` `subtotal_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_labour` CHANGE `sub_total_gross` `subtotal_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_parts` CHANGE `sub_total_net` `subtotal_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_parts` CHANGE `sub_total_tax` `subtotal_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_parts` CHANGE `sub_total_gross` `subtotal_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00';


--
-- Add Email permissions
--

INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES
('invoice:email', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('voucher:email', 1, 1, 0, 1, 1, 1, 0, 0, 0);

--
-- Add Email Voucher Message
--

ALTER TABLE `#__company_record` ADD `email_msg_voucher` TEXT NOT NULL AFTER `email_msg_workorder`;
UPDATE `#__company_record` SET `email_msg_voucher` = '<p>Hi {client_display_name}</p>\r\n<p>This is a voucher from {company_name} which is redeemable against our services and products.</p>\r\n<p><em><strong>Terms and conditions apply.</strong></em></p>\r\n<p>Thanks for your custom.</p>';
ALTER TABLE `#__company_record` CHANGE `email_msg_workorder` `email_msg_workorder` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `email_signature_active`;

--
-- Tidy up client_records
--

ALTER TABLE `#__client_records` CHANGE `unit_discount_rate` `discount_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00';

--
-- Standardise some database type fields
--

ALTER TABLE `#__expense_records` CHANGE `item_type` `type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `item_type` `type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__refund_records` CHANGE `item_type` `type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

--
-- Allow MySQL Strict mode (STRICT_TRANS_TABLES) and
-- Change all empty dates to NULL / improve Column types / change to utf8mb4_unicode_ci
--

-- client_notes --
ALTER TABLE `#__client_notes` CHANGE `client_note_id` `client_note_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__client_notes` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__client_notes` CHANGE `client_id` `client_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__client_notes` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__client_notes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- client_records --
ALTER TABLE `#__client_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__client_records` CHANGE `closed_on` `closed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__client_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__client_records` CHANGE `company_name` `company_name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `first_name` `first_name` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__client_records` CHANGE `last_name` `last_name` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__client_records` CHANGE `website` `website` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `email` `email` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `credit_terms` `credit_terms` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__client_records` CHANGE `active` `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__client_records` CHANGE `primary_phone` `primary_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `mobile_phone` `mobile_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `fax` `fax` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `address` `address` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `city` `city` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `state` `state` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `zip` `zip` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `country` `country` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__client_records` CHANGE `note` `note` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
UPDATE `#__client_records` SET `opened_on` = NULL WHERE `opened_on` = '0000-00-00 00:00:00';
UPDATE `#__client_records` SET `closed_on` = NULL WHERE `closed_on` = '0000-00-00 00:00:00';
UPDATE `#__client_records` SET `last_active` = NULL WHERE `last_active` = '0000-00-00 00:00:00';
ALTER TABLE `#__client_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- client_types --
ALTER TABLE `#__client_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__client_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__client_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__client_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- company_date_formats --
ALTER TABLE `#__company_date_formats` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__company_date_formats` CHANGE `date_format_key` `date_format_key` VARCHAR(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_date_formats` CHANGE `display_name` `display_name` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_date_formats` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- company_record --
ALTER TABLE `#__company_record` CHANGE `company_name` `company_name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `logo` `logo` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `address` `address` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `city` `city` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `state` `state` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `zip` `zip` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `country` `country` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `primary_phone` `primary_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `mobile_phone` `mobile_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `fax` `fax` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `email` `email` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `website` `website` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `company_number` `company_number` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `vat_number` `vat_number` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `welcome_msg` `welcome_msg` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `currency_symbol` `currency_symbol` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `currency_code` `currency_code` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `date_format` `date_format` VARCHAR(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `opening_hour` `opening_hour` INT(2) UNSIGNED NOT NULL DEFAULT '9';
ALTER TABLE `#__company_record` CHANGE `opening_minute` `opening_minute` INT(2) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__company_record` CHANGE `closing_hour` `closing_hour` INT(2) UNSIGNED NOT NULL DEFAULT '17';
ALTER TABLE `#__company_record` CHANGE `closing_minute` `closing_minute` INT(2) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__company_record` CHANGE `email_signature` `email_signature` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `email_signature_active` `email_signature_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__company_record` CHANGE `email_msg_workorder` `email_msg_workorder` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `email_msg_invoice` `email_msg_invoice` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CHANGE `email_msg_voucher` `email_msg_voucher` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__company_record` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- company_tax_systems --
ALTER TABLE `#__company_tax_systems` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__company_tax_systems` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_tax_systems` CHANGE `display_name` `display_name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_tax_systems` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- company_vat_tax_codes --
ALTER TABLE `#__company_vat_tax_codes` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__company_vat_tax_codes` CHANGE `tax_key` `tax_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_vat_tax_codes` CHANGE `display_name` `display_name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_vat_tax_codes` CHANGE `description` `description` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__company_vat_tax_codes` CHANGE `hidden` `hidden` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__company_vat_tax_codes` CHANGE `editable` `editable` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__company_vat_tax_codes` CHANGE `system_tax_code` `system_tax_code` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Is not a standard VAT code';
ALTER TABLE `#__company_vat_tax_codes` CHANGE `enabled` `enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__company_vat_tax_codes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- cronjob_records -- Done above

-- cronjob_system -- Done above

-- expense_records --
UPDATE `#__expense_records` SET `employee_id` = 0 WHERE `employee_id` = '';
ALTER TABLE `#__expense_records` CHANGE `expense_id` `expense_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__expense_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__expense_records` CHANGE `payee` `payee` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` CHANGE `date` `date` DATE NULL;
ALTER TABLE `#__expense_records` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` CHANGE `vat_tax_code` `vat_tax_code` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` CHANGE `opened_on` `opened_on` DATETIME NULL;
ALTER TABLE `#__expense_records` CHANGE `closed_on` `closed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__expense_records` CHANGE `items` `items` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
UPDATE `#__expense_records` SET `employee_id` = NULL WHERE `employee_id` = 0;
UPDATE `#__expense_records` SET `closed_on` = NULL WHERE `closed_on` = '0000-00-00 00:00:00';
ALTER TABLE `#__expense_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- expense_statuses --
ALTER TABLE `#__expense_statuses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__expense_statuses` CHANGE `status_key` `status_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_statuses` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_statuses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- expense_types --
ALTER TABLE `#__expense_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__expense_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__expense_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- invoice_labour --
ALTER TABLE `#__invoice_labour` CHANGE `invoice_labour_id` `invoice_labour_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__invoice_labour` CHANGE `invoice_id` `invoice_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__invoice_labour` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_labour` CHANGE `description` `description` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_labour` CHANGE `sales_tax_exempt` `sales_tax_exempt` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__invoice_labour` CHANGE `vat_tax_code` `vat_tax_code` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_labour` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- invoice_parts --
ALTER TABLE `#__invoice_parts` CHANGE `invoice_parts_id` `invoice_parts_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__invoice_parts` CHANGE `invoice_id` `invoice_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__invoice_parts` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_parts` CHANGE `description` `description` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_parts` CHANGE `sales_tax_exempt` `sales_tax_exempt` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__invoice_parts` CHANGE `vat_tax_code` `vat_tax_code` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_parts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- invoice_prefill_items --
ALTER TABLE `#__invoice_prefill_items` CHANGE `invoice_prefill_id` `invoice_prefill_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__invoice_prefill_items` CHANGE `description` `description` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_prefill_items` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_prefill_items` CHANGE `active` `active` TINYINT(1) UNSIGNED NOT NULL;
ALTER TABLE `#__invoice_prefill_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- invoice_records --
UPDATE `#__invoice_records` SET `workorder_id` = 0 WHERE `workorder_id` = '';
UPDATE `#__invoice_records` SET `refund_id` = 0 WHERE `refund_id` = '';
ALTER TABLE `#__invoice_records` CHANGE `invoice_id` `invoice_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__invoice_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__invoice_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__invoice_records` CHANGE `workorder_id` `workorder_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__invoice_records` CHANGE `refund_id` `refund_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__invoice_records` CHANGE `date` `date` DATE NULL;
ALTER TABLE `#__invoice_records` CHANGE `due_date` `due_date` DATE NULL;
ALTER TABLE `#__invoice_records` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_records` CHANGE `opened_on` `opened_on` DATETIME NULL;
ALTER TABLE `#__invoice_records` CHANGE `closed_on` `closed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__invoice_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__invoice_records` CHANGE `is_closed` `is_closed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
UPDATE `#__invoice_records` SET `workorder_id` = NULL WHERE `workorder_id` = 0;
UPDATE `#__invoice_records` SET `refund_id` = NULL WHERE `refund_id` = 0;
UPDATE `#__invoice_records` SET `opened_on` = NULL WHERE `opened_on` = '0000-00-00 00:00:00';
UPDATE `#__invoice_records` SET `closed_on` = NULL WHERE `closed_on` = '0000-00-00 00:00:00';
UPDATE `#__invoice_records` SET `last_active` = NULL WHERE `last_active` = '0000-00-00 00:00:00';
ALTER TABLE `#__invoice_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- invoice_statuses --
ALTER TABLE `#__invoice_statuses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__invoice_statuses` CHANGE `status_key` `status_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_statuses` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_statuses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- otherincome_records --
UPDATE `#__otherincome_records` SET `employee_id` = 0 WHERE `employee_id` = '';
ALTER TABLE `#__otherincome_records` CHANGE `otherincome_id` `otherincome_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__otherincome_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `payee` `payee` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `date` `date` DATE NULL;
ALTER TABLE `#__otherincome_records` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `vat_tax_code` `vat_tax_code` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `opened_on` `opened_on` DATETIME NULL;
ALTER TABLE `#__otherincome_records` CHANGE `closed_on` `closed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `items` `items` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
UPDATE `#__otherincome_records` SET `employee_id` = NULL WHERE `employee_id` = 0;
UPDATE `#__otherincome_records` SET `date` = NULL WHERE `date` = '0000-00-00 00:00:00';
UPDATE `#__otherincome_records` SET `opened_on` = NULL WHERE `opened_on` = '0000-00-00 00:00:00';
UPDATE `#__otherincome_records` SET `closed_on` = NULL WHERE `closed_on` = '0000-00-00 00:00:00';
UPDATE `#__otherincome_records` SET `last_active` = NULL WHERE `last_active` = '0000-00-00 00:00:00';
ALTER TABLE `#__otherincome_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- otherincome_statuses --
ALTER TABLE `#__otherincome_statuses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__otherincome_statuses` CHANGE `status_key` `status_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_statuses` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_statuses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- otherincome_types --
ALTER TABLE `#__otherincome_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__otherincome_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- payment_additional_info_types --
ALTER TABLE `#__payment_additional_info_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__payment_additional_info_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_additional_info_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_additional_info_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- payment_card_types --
ALTER TABLE `#__payment_card_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__payment_card_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_card_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_card_types` CHANGE `active` `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__payment_card_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- payment_methods --
ALTER TABLE `#__payment_methods` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__payment_methods` CHANGE `method_key` `method_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_methods` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_methods` CHANGE `send` `send` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__payment_methods` CHANGE `receive` `receive` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__payment_methods` CHANGE `send_protected` `send_protected` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'send cannot be changed';
ALTER TABLE `#__payment_methods` CHANGE `receive_protected` `receive_protected` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'receive cannot be changed';
ALTER TABLE `#__payment_methods` CHANGE `enabled` `enabled` TINYINT(1) UNSIGNED NULL DEFAULT '0';
ALTER TABLE `#__payment_methods` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- payment_options --
ALTER TABLE `#__payment_options` CHANGE `bank_account_name` `bank_account_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `bank_name` `bank_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `bank_account_number` `bank_account_number` VARCHAR(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `bank_sort_code` `bank_sort_code` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `bank_iban` `bank_iban` VARCHAR(34) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `paypal_email` `paypal_email` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `invoice_bank_transfer_msg` `invoice_bank_transfer_msg` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `invoice_cheque_msg` `invoice_cheque_msg` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `invoice_footer_msg` `invoice_footer_msg` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- payment_records --
UPDATE `#__payment_records` SET `employee_id` = 0 WHERE `employee_id` = '';
UPDATE `#__payment_records` SET `client_id` = 0 WHERE `client_id` = '';
UPDATE `#__payment_records` SET `workorder_id` = 0 WHERE `workorder_id` = '';
UPDATE `#__payment_records` SET `invoice_id` = 0 WHERE `invoice_id` = '';
UPDATE `#__payment_records` SET `voucher_id` = 0 WHERE `voucher_id` = '';
UPDATE `#__payment_records` SET `refund_id` = 0 WHERE `refund_id` = '';
UPDATE `#__payment_records` SET `expense_id` = 0 WHERE `expense_id` = '';
UPDATE `#__payment_records` SET `otherincome_id` = 0 WHERE `otherincome_id` = '';
ALTER TABLE `#__payment_records` CHANGE `payment_id` `payment_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__payment_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__payment_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__payment_records` CHANGE `workorder_id` `workorder_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__payment_records` CHANGE `invoice_id` `invoice_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__payment_records` CHANGE `voucher_id` `voucher_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__payment_records` CHANGE `refund_id` `refund_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__payment_records` CHANGE `expense_id` `expense_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__payment_records` CHANGE `otherincome_id` `otherincome_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__payment_records` CHANGE `date` `date` DATE NULL;
ALTER TABLE `#__payment_records` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_records` CHANGE `type` `type` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_records` CHANGE `method` `method` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__payment_records` CHANGE `additional_info` `additional_info` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_records` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
UPDATE `#__payment_records` SET `employee_id` = NULL WHERE `employee_id` = 0;
UPDATE `#__payment_records` SET `client_id` = NULL WHERE `client_id` = 0;
UPDATE `#__payment_records` SET `workorder_id` = NULL WHERE `workorder_id` = 0;
UPDATE `#__payment_records` SET `invoice_id` = NULL WHERE `invoice_id` = 0;
UPDATE `#__payment_records` SET `voucher_id` = NULL WHERE `voucher_id` = 0;
UPDATE `#__payment_records` SET `refund_id` = NULL WHERE `refund_id` = 0;
UPDATE `#__payment_records` SET `expense_id` = NULL WHERE `expense_id` = 0;
UPDATE `#__payment_records` SET `otherincome_id` = NULL WHERE `otherincome_id` = 0;
UPDATE `#__payment_records` SET `date` = NULL WHERE `date` = '0000-00-00 00:00:00';
UPDATE `#__payment_records` SET `last_active` = NULL WHERE `last_active` = '0000-00-00 00:00:00';
ALTER TABLE `#__payment_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- payment_statuses --
ALTER TABLE `#__payment_statuses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__payment_statuses` CHANGE `status_key` `status_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_statuses` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_statuses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- payment_types --
ALTER TABLE `#__payment_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__payment_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__payment_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- refund_records --
UPDATE `#__refund_records` SET `employee_id` = 0 WHERE `employee_id` = '';
UPDATE `#__refund_records` SET `client_id` = 0 WHERE `client_id` = '';
UPDATE `#__refund_records` SET `workorder_id` = 0 WHERE `workorder_id` = '';
UPDATE `#__refund_records` SET `invoice_id` = 0 WHERE `invoice_id` = '';
ALTER TABLE `#__refund_records` CHANGE `refund_id` `refund_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__refund_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__refund_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__refund_records` CHANGE `workorder_id` `workorder_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__refund_records` CHANGE `invoice_id` `invoice_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__refund_records` CHANGE `date` `date` DATE NULL;
ALTER TABLE `#__refund_records` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__refund_records` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__refund_records` CHANGE `vat_tax_code` `vat_tax_code` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__refund_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__refund_records` CHANGE `opened_on` `opened_on` DATETIME NULL;
ALTER TABLE `#__refund_records` CHANGE `closed_on` `closed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__refund_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__refund_records` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
UPDATE `#__refund_records` SET `employee_id` = NULL WHERE `employee_id` = 0;
UPDATE `#__refund_records` SET `client_id` = NULL WHERE `client_id` = 0;
UPDATE `#__refund_records` SET `workorder_id` = NULL WHERE `workorder_id` = 0;
UPDATE `#__refund_records` SET `invoice_id` = NULL WHERE `invoice_id` = 0;
UPDATE `#__refund_records` SET `date` = NULL WHERE `date` = '0000-00-00 00:00:00';
UPDATE `#__refund_records` SET `opened_on` = NULL WHERE `opened_on` = '0000-00-00 00:00:00';
UPDATE `#__refund_records` SET `closed_on` = NULL WHERE `closed_on` = '0000-00-00 00:00:00';
UPDATE `#__refund_records` SET `last_active` = NULL WHERE `last_active` = '0000-00-00 00:00:00';
ALTER TABLE `#__refund_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- refund_statuses --
ALTER TABLE `#__refund_statuses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__refund_statuses` CHANGE `status_key` `status_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__refund_statuses` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__refund_statuses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- refund_types --
ALTER TABLE `#__refund_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__refund_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__refund_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__refund_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- schedule_records --
ALTER TABLE `#__schedule_records` CHANGE `schedule_id` `schedule_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__schedule_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__schedule_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__schedule_records` CHANGE `workorder_id` `workorder_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__schedule_records` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__schedule_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- session --
ALTER TABLE `#__session` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- supplier_records --
ALTER TABLE `#__supplier_records` CHANGE `supplier_id` `supplier_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__supplier_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `company_name` `company_name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `first_name` `first_name` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `last_name` `last_name` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `website` `website` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `email` `email` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `primary_phone` `primary_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `mobile_phone` `mobile_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `fax` `fax` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `address` `address` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `city` `city` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `state` `state` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `zip` `zip` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `country` `country` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `closed_on` `closed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__supplier_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__supplier_records` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- supplier_statuses --
ALTER TABLE `#__supplier_statuses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__supplier_statuses` CHANGE `status_key` `status_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_statuses` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_statuses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- supplier_types --
ALTER TABLE `#__supplier_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__supplier_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- user_acl_page --
ALTER TABLE `#__user_acl_page` CHANGE `page` `page` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_acl_page` CHANGE `Administrator` `Administrator` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `#__user_acl_page` CHANGE `Manager` `Manager` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_acl_page` CHANGE `Supervisor` `Supervisor` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_acl_page` CHANGE `Technician` `Technician` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_acl_page` CHANGE `Clerical` `Clerical` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_acl_page` CHANGE `Counter` `Counter` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_acl_page` CHANGE `Client` `Client` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_acl_page` CHANGE `Guest` `Guest` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_acl_page` CHANGE `Public` `Public` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_acl_page` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- user_keys --
ALTER TABLE `#__user_keys` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- user_locations --
ALTER TABLE `#__user_locations` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__user_locations` CHANGE `location_key` `location_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_locations` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- user_records --
UPDATE `#__user_records` SET `client_id` = 0 WHERE `client_id` = '';
ALTER TABLE `#__user_records` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__user_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__user_records` CHANGE `username` `username` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `password` `password` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `email` `email` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `usergroup` `usergroup` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__user_records` CHANGE `active` `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__user_records` CHANGE `require_reset` `require_reset` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Require user to reset password on next login';
ALTER TABLE `#__user_records` CHANGE `last_reset_time` `last_reset_time` DATETIME NULL DEFAULT NULL COMMENT 'Date of last password reset';
ALTER TABLE `#__user_records` CHANGE `reset_count` `reset_count` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Count of password resets since last_reset_time';
ALTER TABLE `#__user_records` CHANGE `is_employee` `is_employee` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `#__user_records` CHANGE `first_name` `first_name` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `last_name` `last_name` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `work_primary_phone` `work_primary_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `work_mobile_phone` `work_mobile_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `work_fax` `work_fax` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_primary_phone` `home_primary_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_mobile_phone` `home_mobile_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_email` `home_email` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_address` `home_address` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_city` `home_city` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_state` `home_state` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_zip` `home_zip` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_country` `home_country` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `based` `based` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
UPDATE `#__user_records` SET `client_id` = NULL WHERE `client_id` = 0;
UPDATE `#__user_records` SET `last_active` = NULL WHERE `last_active` = '0000-00-00 00:00:00';
ALTER TABLE `#__user_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- user_reset --
UPDATE `#__user_reset` SET `expiry_time` = 0 WHERE `expiry_time` = '';
UPDATE `#__user_reset` SET `reset_code_expiry_time` = 0 WHERE `reset_code_expiry_time` = '';
ALTER TABLE `#__user_reset` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__user_reset` CHANGE `expiry_time` `expiry_time` INT(20) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__user_reset` CHANGE `token` `token` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__user_reset` CHANGE `reset_code` `reset_code` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__user_reset` CHANGE `reset_code_expiry_time` `reset_code_expiry_time` INT(20) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `#__user_reset` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- user_usergroups --
ALTER TABLE `#__user_usergroups` CHANGE `usergroup_id` `usergroup_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__user_usergroups` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__user_usergroups` CHANGE `user_type` `user_type` TINYINT(1) UNSIGNED NOT NULL;
ALTER TABLE `#__user_usergroups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- version --
ALTER TABLE `#__version` CHANGE `database_version` `database_version` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__version` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- voucher_records --
UPDATE `#__voucher_records` SET `employee_id` = 0 WHERE `employee_id` = '';
UPDATE `#__voucher_records` SET `client_id` = 0 WHERE `client_id` = '';
UPDATE `#__voucher_records` SET `workorder_id` = 0 WHERE `workorder_id` = '';
UPDATE `#__voucher_records` SET `invoice_id` = 0 WHERE `invoice_id` = '';
UPDATE `#__voucher_records` SET `payment_id` = 0 WHERE `payment_id` = '';
UPDATE `#__voucher_records` SET `refund_id` = 0 WHERE `refund_id` = '';
UPDATE `#__voucher_records` SET `redeemed_client_id` = 0 WHERE `redeemed_client_id` = '';
UPDATE `#__voucher_records` SET `redeemed_invoice_id` = 0 WHERE `redeemed_invoice_id` = '';
ALTER TABLE `#__voucher_records` CHANGE `voucher_id` `voucher_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__voucher_records` CHANGE `voucher_code` `voucher_code` VARCHAR(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__voucher_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__voucher_records` CHANGE `workorder_id` `workorder_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__voucher_records` CHANGE `invoice_id` `invoice_id` INT(10) UNSIGNED NULL;
ALTER TABLE `#__voucher_records` CHANGE `payment_id` `payment_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__voucher_records` CHANGE `refund_id` `refund_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__voucher_records` CHANGE `redeemed_client_id` `redeemed_client_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__voucher_records` CHANGE `redeemed_invoice_id` `redeemed_invoice_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__voucher_records` CHANGE `expiry_date` `expiry_date` DATE NULL;
ALTER TABLE `#__voucher_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `opened_on` `opened_on` DATETIME NULL;
ALTER TABLE `#__voucher_records` CHANGE `redeemed_on` `redeemed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__voucher_records` CHANGE `closed_on` `closed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__voucher_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__voucher_records` CHANGE `blocked` `blocked` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__voucher_records` CHANGE `tax_system` `tax_system` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `sales_tax_exempt` `sales_tax_exempt` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__voucher_records` CHANGE `vat_tax_code` `vat_tax_code` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
UPDATE `#__voucher_records` SET `employee_id` = NULL WHERE `employee_id` = 0;
UPDATE `#__voucher_records` SET `client_id` = NULL WHERE `client_id` = 0;
UPDATE `#__voucher_records` SET `workorder_id` = NULL WHERE `workorder_id` = 0;
UPDATE `#__voucher_records` SET `invoice_id` = NULL WHERE `invoice_id` = 0;
UPDATE `#__voucher_records` SET `payment_id` = NULL WHERE `payment_id` = 0;
UPDATE `#__voucher_records` SET `refund_id` = NULL WHERE `refund_id` = 0;
UPDATE `#__voucher_records` SET `redeemed_client_id` = NULL WHERE `redeemed_client_id` = 0;
UPDATE `#__voucher_records` SET `redeemed_invoice_id` = NULL WHERE `redeemed_invoice_id` = 0;
UPDATE `#__voucher_records` SET `expiry_date` = NULL WHERE `expiry_date` = '0000-00-00 00:00:00';
UPDATE `#__voucher_records` SET `opened_on` = NULL WHERE `opened_on` = '0000-00-00 00:00:00';
UPDATE `#__voucher_records` SET `redeemed_on` = NULL WHERE `redeemed_on` = '0000-00-00 00:00:00';
UPDATE `#__voucher_records` SET `closed_on` = NULL WHERE `closed_on` = '0000-00-00 00:00:00';
UPDATE `#__voucher_records` SET `last_active` = NULL WHERE `last_active` = '0000-00-00 00:00:00';
ALTER TABLE `#__voucher_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- voucher_statuses --
ALTER TABLE `#__voucher_statuses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__voucher_statuses` CHANGE `status_key` `status_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_statuses` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_statuses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- voucher_types --
ALTER TABLE `#__voucher_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL COMMENT 'only for display order';
ALTER TABLE `#__voucher_types` CHANGE `type_key` `type_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_types` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- workorder_history --
ALTER TABLE `#__workorder_history` CHANGE `history_id` `history_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__workorder_history` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__workorder_history` CHANGE `workorder_id` `workorder_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__workorder_history` CHANGE `note` `note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_history` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- workorder_notes --
ALTER TABLE `#__workorder_notes` CHANGE `workorder_note_id` `workorder_note_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__workorder_notes` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__workorder_notes` CHANGE `workorder_id` `workorder_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__workorder_notes` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_notes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- workorder_records --
UPDATE `#__workorder_records` SET `employee_id` = 0 WHERE `employee_id` = '';
UPDATE `#__workorder_records` SET `client_id` = 0 WHERE `client_id` = '';
UPDATE `#__workorder_records` SET `invoice_id` = 0 WHERE `invoice_id` = '';
UPDATE `#__workorder_records` SET `created_by` = 0 WHERE `created_by` = '';
UPDATE `#__workorder_records` SET `closed_by` = 0 WHERE `closed_by` = '';
ALTER TABLE `#__workorder_records` CHANGE `workorder_id` `workorder_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__workorder_records` CHANGE `employee_id` `employee_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__workorder_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__workorder_records` CHANGE `invoice_id` `invoice_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__workorder_records` CHANGE `created_by` `created_by` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__workorder_records` CHANGE `closed_by` `closed_by` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `#__workorder_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_records` CHANGE `opened_on` `opened_on` DATETIME NULL;
ALTER TABLE `#__workorder_records` CHANGE `closed_on` `closed_on` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__workorder_records` CHANGE `last_active` `last_active` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__workorder_records` CHANGE `is_closed` `is_closed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__workorder_records` CHANGE `scope` `scope` VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_records` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_records` CHANGE `comment` `comment` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__workorder_records` CHANGE `resolution` `resolution` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
UPDATE `#__workorder_records` SET `employee_id` = NULL WHERE `employee_id` = 0;
UPDATE `#__workorder_records` SET `client_id` = NULL WHERE `client_id` = 0;
UPDATE `#__workorder_records` SET `invoice_id` = NULL WHERE `invoice_id` = 0;
UPDATE `#__workorder_records` SET `created_by` = NULL WHERE `created_by` = 0;
UPDATE `#__workorder_records` SET `closed_by` = NULL WHERE `closed_by` = 0;
UPDATE `#__workorder_records` SET `opened_on` = NULL WHERE `opened_on` = '0000-00-00 00:00:00';
UPDATE `#__workorder_records` SET `closed_on` = NULL WHERE `closed_on` = '0000-00-00 00:00:00';
UPDATE `#__workorder_records` SET `last_active` = NULL WHERE `last_active` = '0000-00-00 00:00:00';
ALTER TABLE `#__workorder_records` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- workorder_statuses --
ALTER TABLE `#__workorder_statuses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'only for display order';
ALTER TABLE `#__workorder_statuses` CHANGE `status_key` `status_key` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_statuses` CHANGE `display_name` `display_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_statuses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

--
-- Correct and improve discounts
--

ALTER TABLE `#__invoice_labour` ADD `unit_discount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_net`;
ALTER TABLE `#__invoice_parts` ADD `unit_discount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_net`;

--
-- Upgrade Voucher system
--

ALTER TABLE `#__company_record` ADD `voucher_expiry_offset` INT(5) UNSIGNED NOT NULL DEFAULT '1827' AFTER `year_end`;
TRUNCATE TABLE `#__voucher_statuses`;
INSERT INTO `#__voucher_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unpaid', 'Unpaid'),
(2, 'partially_paid', 'Partially Paid'),
(3, 'paid', 'Paid (Unused)'),
(4, 'refunded', 'Refunded'),
(5, 'partially_redeemed', 'Partially Redeemed'),
(6, 'redeemed', 'Redeemed'),
(7, 'suspended', 'Suspended'),
(8, 'cancelled', 'Cancelled'),
(9, 'deleted', 'Deleted');
ALTER TABLE `#__voucher_records` CHANGE `balance` `balance` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
UPDATE `#__voucher_records` SET `type` = 'mpv' WHERE `type` = 'MPV';
UPDATE `#__voucher_records` SET `type` = 'spv' WHERE `type` = 'SPV';
UPDATE `#__voucher_records` SET `balance` = `unit_gross` WHERE `status` != 'redeemed';
UPDATE `#__voucher_records` SET `status` = 'paid' WHERE `status` = 'unused';
UPDATE `#__voucher_records` SET `status` = 'paid' WHERE `status` = 'expired';
ALTER TABLE `#__voucher_records` DROP `payment_id`;
ALTER TABLE `#__voucher_records` DROP `redeemed_on`;
ALTER TABLE `#__voucher_records` DROP `redeemed_client_id`;
ALTER TABLE `#__voucher_records` DROP `redeemed_invoice_id`;
UPDATE `#__voucher_records` SET `last_active` = NULL WHERE `status` = 'deleted';

--
-- Misc
--

ALTER TABLE `#__invoice_records` CHANGE `unit_discount` `unit_discount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_net`;

--
-- Remove workorder_id from payments
--

ALTER TABLE `#__payment_records` DROP `workorder_id`;
ALTER TABLE `#__refund_records` DROP `workorder_id`;

--
-- Add cancel message to invoice and allow for more information
--

ALTER TABLE `#__invoice_records` ADD `additional_info` TEXT NOT NULL AFTER `is_closed`;
UPDATE `#__invoice_records` SET `additional_info` = '{}';

--
-- Merging Labour and parts into invoice_items to allow credit note system
--

CREATE TABLE `#__invoice_items` (
  `invoice_item_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `tax_system` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sales_tax_exempt` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `vat_tax_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT 0.00,
  `unit_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_gross` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_gross` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__invoice_items` ADD PRIMARY KEY (`invoice_item_id`);

ALTER TABLE `#__invoice_items` MODIFY `invoice_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

INSERT
INTO    `#__invoice_items` (`invoice_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `unit_discount`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `subtotal_net`, `subtotal_tax`, `subtotal_gross`)
SELECT  `invoice_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `unit_discount`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `subtotal_net`, `subtotal_tax`, `subtotal_gross`
FROM    `#__invoice_labour`
UNION ALL
SELECT  `invoice_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `unit_discount`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `subtotal_net`, `subtotal_tax`, `subtotal_gross`
FROM    `#__invoice_parts`;

DROP TABLE `#__invoice_labour`;
DROP TABLE `#__invoice_parts`;

--

ALTER TABLE `#__invoice_prefill_items` DROP `type`;

--
-- Correct some small typos
--
TRUNCATE TABLE `#__company_tax_systems`;
INSERT INTO `#__company_tax_systems` (`id`, `type_key`, `display_name`) VALUES
(1, 'no_tax', 'No Tax'),
(2, 'sales_tax_cash', 'Sales Tax (Cash Basis)'),
(3, 'vat_standard', 'VAT Standard Accounting (UK)'),
(4, 'vat_cash', 'VAT Cash Accounting (UK)'),
(5, 'vat_flat_basic', 'VAT Flat Rate (Basic turnover) (UK)'),
(6, 'vat_flat_cash', 'VAT Flat Rate (Cash based turnover) (UK)');

--------------------------------------------------
-- Adding Credit Note System
--------------------------------------------------

--
-- Table structure for table `#__creditnote_items`
--

CREATE TABLE `#__creditnote_items` (
  `creditnote_item_id` int(10) UNSIGNED NOT NULL,
  `creditnote_id` int(10) UNSIGNED NOT NULL,
  `tax_system` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sales_tax_exempt` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `vat_tax_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT 0.00,
  `unit_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_gross` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_gross` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__creditnote_items` ADD PRIMARY KEY (`creditnote_item_id`);
ALTER TABLE `#__creditnote_items` MODIFY `creditnote_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Table structure for table `#__creditnote_records`
--

CREATE TABLE `#__creditnote_records` (
  `creditnote_id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'CR was generated from this client',
  `invoice_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'CR was generated from this invoice',
  `supplier_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'CR was generated from this supplier',
  `expense_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'CR was generated from this expense',
  `date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_system` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sales_tax_rate` decimal(4,2) NOT NULL DEFAULT 0.00,
  `unit_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_gross` decimal(10,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opened_on` datetime DEFAULT NULL,
  `closed_on` datetime DEFAULT NULL,
  `last_active` datetime DEFAULT NULL,
  `is_closed` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `reference` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_info` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{}'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__creditnote_records` ADD PRIMARY KEY (`creditnote_id`);
ALTER TABLE `#__creditnote_records` MODIFY `creditnote_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Table structure for table `#__creditnote_statuses`
--

CREATE TABLE `#__creditnote_statuses` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__creditnote_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'pending', 'Pending'),
(2, 'unused', 'Unused'),
(3, 'partially_used', 'Partially Used'),
(4, 'used', 'Used'),
(5, 'cancelled', 'Cancelled'),
(6, 'deleted', 'Deleted');

ALTER TABLE `#__creditnote_statuses` ADD PRIMARY KEY (`id`);

--
-- Table structure for table `#__creditnote_types`
--

CREATE TABLE `#__creditnote_types` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__creditnote_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'sales', 'Sales'),
(2, 'purchase', 'Purchase');

ALTER TABLE `#__creditnote_types` ADD PRIMARY KEY (`id`);

--

INSERT INTO `#__payment_methods` (`id`, `method_key`, `display_name`, `send`, `receive`, `send_protected`, `receive_protected`, `enabled`) VALUES
('9', 'creditnote', 'Credit Note', '1', '1', '1', '1', '0');
ALTER TABLE `#__invoice_records` DROP `unit_discount_rate`;
INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES
('creditnote:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('creditnote:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('creditnote:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('creditnote:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('creditnote:email', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('creditnote:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('creditnote:print', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('creditnote:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('creditnote:status', 1, 1, 0, 0, 1, 0, 0, 0, 0);

--

ALTER TABLE `#__payment_records` ADD `creditnote_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `otherincome_id`;
ALTER TABLE `#__company_record` ADD `email_msg_creditnote` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '<p>Hi {client_display_name}</p> <p>This is a credit note from {company_name} which is redeemable against our services and products.</p> <p><em><strong>Terms and conditions apply.</strong></em></p> <p>Thanks for your custom.</p>' AFTER `email_msg_voucher`;

--

ALTER TABLE `#__payment_records` CHANGE `client_id` `client_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Applied against';
ALTER TABLE `#__payment_records` CHANGE `invoice_id` `invoice_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Applied against';
ALTER TABLE `#__payment_records` CHANGE `expense_id` `expense_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Applied against' AFTER `invoice_id`;
ALTER TABLE `#__payment_records` CHANGE `otherincome_id` `otherincome_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Applied against' AFTER `expense_id`;
ALTER TABLE `#__payment_records` ADD `supplier_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Applied against' AFTER `client_id`;
ALTER TABLE `#__payment_records` CHANGE `refund_id` `refund_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Applied against' AFTER `invoice_id`;
ALTER TABLE `#__payment_records` CHANGE `voucher_id` `voucher_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Payment made with';
ALTER TABLE `#__payment_records` CHANGE `creditnote_id` `creditnote_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Applied against / Refunded against';
ALTER TABLE `#__company_record` ADD `creditnote_expiry_offset` INT(5) UNSIGNED NOT NULL AFTER `year_end`;
UPDATE `#__company_record` SET `creditnote_expiry_offset` = '366' WHERE `#__company_record`.`creditnote_expiry_offset` = 0;
ALTER TABLE `#__expense_records` ADD `supplier_id` INT(10) UNSIGNED NULL AFTER `employee_id`;
ALTER TABLE `#__payment_options` ADD `creditnote_footer_msg` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `invoice_footer_msg`;
UPDATE `#__payment_options` SET `creditnote_footer_msg` = '<p>This is a footer message where you can put extra information ...</p>\r\n<p>This message can be edited in payment options.</p>';

--

ALTER TABLE `#__payment_records` CHANGE `creditnote_id` `creditnote_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Applied against / Refunded against' AFTER `otherincome_id`;

--

UPDATE `#__company_vat_tax_codes` SET `hidden` = '0' WHERE `#__company_vat_tax_codes`.`id` = 10;

--------------------------------------------------

---
--- Add direction on to payments
---

ALTER TABLE `#__payment_records` ADD `direction` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `method`;
UPDATE `#__payment_records` SET `direction` = 'credit' WHERE `#__payment_records`.`type` = 'invoice';
UPDATE `#__payment_records` SET `direction` = 'credit' WHERE `#__payment_records`.`type` = 'otherincome';
UPDATE `#__payment_records` SET `direction` = 'debit' WHERE `#__payment_records`.`type` = 'expense';
UPDATE `#__payment_records` SET `direction` = 'debit' WHERE `#__payment_records`.`type` = 'refund';

--
-- Table structure for table `#__payment_directions`
--

CREATE TABLE `#__payment_directions` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'only for display order',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__payment_directions` (`id`, `key`, `display_name`) VALUES
(1, 'credit', 'Credit'),
(2, 'debit', 'Debit');

ALTER TABLE `#__payment_directions` ADD PRIMARY KEY (`id`);


---
--- Remove TNA and TVM Vat Codes
---

ALTER TABLE `#__company_vat_tax_codes` DROP `system_tax_code`;
DELETE FROM `#__company_vat_tax_codes` WHERE `#__company_vat_tax_codes`.`id` = 1000;
DELETE FROM `#__company_vat_tax_codes` WHERE `#__company_vat_tax_codes`.`id` = 1001;

UPDATE `#__invoice_items` SET `vat_tax_code` = 'T9' WHERE `#__invoice_items`.`vat_tax_code` = 'TNA';
UPDATE `#__refund_records` SET `vat_tax_code` = 'T9' WHERE `#__refund_records`.`vat_tax_code` = 'TNA';
UPDATE `#__voucher_records` SET `vat_tax_code` = 'T9' WHERE `#__voucher_records`.`vat_tax_code` = 'TNA';
UPDATE `#__expense_records` SET `vat_tax_code` = 'T9' WHERE `#__expense_records`.`vat_tax_code` = 'TNA';
UPDATE `#__otherincome_records` SET `vat_tax_code` = 'T9' WHERE `#__otherincome_records`.`vat_tax_code` = 'TNA';

-- I think this was only ever a thing on Refunds - I will use T1 to replace, but it is not ideal
UPDATE `#__invoice_items` SET `vat_tax_code` = 'T1' WHERE `#__invoice_items`.`vat_tax_code` = 'TVM';
UPDATE `#__refund_records` SET `vat_tax_code` = 'T1' WHERE `#__refund_records`.`vat_tax_code` = 'TVM';
UPDATE `#__voucher_records` SET `vat_tax_code` = 'T1' WHERE `#__voucher_records`.`vat_tax_code` = 'TVM';
UPDATE `#__expense_records` SET `vat_tax_code` = 'T1' WHERE `#__expense_records`.`vat_tax_code` = 'TVM';
UPDATE `#__otherincome_records` SET `vat_tax_code` = 'T1' WHERE `#__otherincome_records`.`vat_tax_code` = 'TVM';

---
--- Remove refund component and convert to Credit notes
----

TRUNCATE TABLE `#__payment_types`;
INSERT INTO `#__payment_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'invoice', 'Invoice'),
(2, 'expense', 'Expense'),
(3, 'otherincome', 'Other Income'),
(4, 'creditnote', 'Credit Note');

TRUNCATE TABLE `#__invoice_statuses`;
INSERT INTO `#__invoice_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'pending', 'Pending'),
(2, 'unpaid', 'Unpaid'),
(3, 'partially_paid', 'Partially Paid'),
(4, 'paid', 'Paid'),
(5, 'in_dispute', 'In Dispute'),
(6, 'overdue', 'Overdue'),
(7, 'collections', 'Collections'),
(8, 'cancelled', 'Cancelled'),
(9, 'deleted', 'Deleted');

TRUNCATE TABLE `#__voucher_statuses`;
INSERT INTO `#__voucher_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'pending', 'Pending'),
(2, 'unpaid', 'Unpaid'),
(3, 'partially_paid', 'Partially Paid'),
(4, 'paid', 'Paid (Unused)'),
(5, 'partially_redeemed', 'Partially Redeemed'),
(6, 'redeemed', 'Redeemed'),
(7, 'suspended', 'Suspended'),
(8, 'voided', 'Voided'),
(9, 'cancelled', 'Cancelled'),
(10, 'deleted', 'Deleted');
ALTER TABLE `#__invoice_records` DROP `refund_id`;
ALTER TABLE `#__voucher_records` DROP `refund_id`;
DROP TABLE `#__refund_statuses`;
DROP TABLE `#__refund_types`;

--
-- Upgrade expense and otherincome to use items
--

ALTER TABLE `#__expense_records` ADD `sales_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `unit_net`;
ALTER TABLE `#__expense_records` ADD `due_date` DATE NULL DEFAULT NULL AFTER `date`;
ALTER TABLE `#__expense_records` ADD `reference` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `last_active`;

ALTER TABLE `#__otherincome_records` ADD `sales_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `unit_net`;
ALTER TABLE `#__otherincome_records` ADD `due_date` DATE NULL DEFAULT NULL AFTER `date`;
ALTER TABLE `#__otherincome_records` ADD `reference` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `last_active`;

-- Table structure for table `#__expense_items`

CREATE TABLE `#__expense_items` (
  `expense_item_id` int(10) UNSIGNED NOT NULL,
  `expense_id` int(10) UNSIGNED NOT NULL,
  `tax_system` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sales_tax_exempt` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `vat_tax_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT 0.00,
  `unit_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_gross` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_gross` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__expense_items` ADD PRIMARY KEY (`expense_item_id`);
ALTER TABLE `#__expense_items` MODIFY `expense_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__expense_records` ADD `unit_discount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_net`;

TRUNCATE TABLE `#__expense_statuses`;
INSERT INTO `#__expense_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'pending', 'Pending'),
(2, 'unpaid', 'Unpaid'),
(3, 'partially_paid', 'Partially Paid'),
(4, 'paid', 'Paid'),
(5, 'cancelled', 'Cancelled'),
(6, 'deleted', 'Deleted');

-- Table structure for table `#__otherincome_items`

CREATE TABLE `#__otherincome_items` (
  `otherincome_item_id` int(10) UNSIGNED NOT NULL,
  `otherincome_id` int(10) UNSIGNED NOT NULL,
  `tax_system` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sales_tax_exempt` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `vat_tax_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT 0.00,
  `unit_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_gross` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_gross` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__otherincome_items` ADD PRIMARY KEY (`otherincome_item_id`);
ALTER TABLE `#__otherincome_items` MODIFY `otherincome_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

TRUNCATE TABLE `#__otherincome_statuses`;
INSERT INTO `#__otherincome_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'pending', 'Pending'),
(2, 'unpaid', 'Unpaid'),
(3, 'partially_paid', 'Partially Paid'),
(4, 'paid', 'Paid'),
(5, 'cancelled', 'Cancelled'),
(6, 'deleted', 'Deleted');

--
-- Update Types
--

TRUNCATE TABLE `#__expense_types`;
INSERT INTO `#__expense_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'bank_charges', 'Bank Charges'),
(2, 'commission', 'Commission'),
(3, 'consumables', 'Consumables'),
(4, 'credit_card', 'Credit Card'),
(5, 'donation', 'Donation'),
(6, 'equipment', 'Equipment'),
(7, 'fuel', 'Fuel'),
(8, 'loan', 'Loan'),
(9, 'marketing', 'Marketing'),
(10, 'office_supplies', 'Office Supplies'),
(11, 'online', 'Online'),
(12, 'other', 'Other'),
(13, 'parts', 'Parts'),
(14, 'postage', 'Postage'),
(15, 'rent', 'Rent'),
(16, 'royalties', 'Royalties'),
(17, 'services', 'Services'),
(18, 'software', 'Software'),
(19, 'telco', 'TelCo'),
(20, 'transport', 'Transport'),
(21, 'utilities', 'Utilities'),
(22, 'voucher', 'Voucher'),
(23, 'wages', 'Wages');
UPDATE `#__expense_records` SET `type` = 'other' WHERE `#__expense_records`.`type` = 'credit';

TRUNCATE TABLE `#__otherincome_types`;
INSERT INTO `#__otherincome_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'cancelled_services', 'Cancelled Services'),
(2, 'commission', 'Commission'),
(3, 'donation', 'Donation'),
(4, 'interest', 'Interest'),
(5, 'other', 'Other'),
(6, 'returned_goods', 'Returned Goods'),
(7, 'royalties', 'Royalties'),
(8, 'tips', 'Tips');
UPDATE `#__expense_records` SET `type` = 'other' WHERE `#__expense_records`.`type` = 'credit_note';

--
-- Final changes
--

-- Add Autosuggest for Supplier Name --
INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES
('supplier:autosuggest_name', 1, 1, 1, 1, 0, 1, 0, 0, 0);
ALTER TABLE `#__otherincome_records` ADD `supplier_id` int(10) UNSIGNED DEFAULT NULL AFTER `employee_id`;

-- Note field for invoice --
ALTER TABLE `#__invoice_records` ADD`note` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `additional_info`;

-- Move printing envelope to client and adding to supplier --
INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES
('client:print', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('supplier:print', 1, 1, 1, 1, 1, 1, 0, 0, 0);

-- Add employee traacking to client --
ALTER TABLE `#__client_records` ADD `employee_id` INT(10) UNSIGNED NULL AFTER `client_id`;

-- Alter Supplier status to something better --
TRUNCATE TABLE `#__supplier_statuses`;
INSERT INTO `#__supplier_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'active', 'Active'),
(2, 'suspended', 'Suspended'),
(3, 'cancelled', 'Cancelled'),
(4, 'deleted', 'Deleted');
UPDATE `#__supplier_records` SET `status` = 'active' WHERE `#__supplier_records`.`status` = 'valid';
