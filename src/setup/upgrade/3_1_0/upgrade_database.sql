/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

--
-- Drop Tables
--

DROP TABLE `#__customer_types`;
DROP TABLE `#__expense_types`;
DROP TABLE `#__invoice_statuses`;
DROP TABLE `#__payment_system_methods`;
DROP TABLE `#__payment_credit_cards`;
DROP TABLE `#__payment_manual_methods`;
DROP TABLE `#__refund_types`;
DROP TABLE `#__supplier_types`;

--
-- Rename Tables
--

RENAME TABLE `#__company` TO `#__company_record`;
RENAME TABLE `#__customer` TO `#__client_records`;
RENAME TABLE `#__customer_notes` TO `#__client_notes`;
RENAME TABLE `#__expense` TO `#__expense_records`;
RENAME TABLE `#__giftcert` TO `#__giftcert_records`;
RENAME TABLE `#__invoice` TO `#__invoice_records`;
RENAME TABLE `#__payment` TO `#__payment_options`;
RENAME TABLE `#__payment_transactions` TO `#__payment_records`;
RENAME TABLE `#__refund` TO `#__otherincome_records`;
RENAME TABLE `#__schedule` TO `#__schedule_records`;
RENAME TABLE `#__supplier` TO `#__supplier_records`;
RENAME TABLE `#__user` TO `#__user_records`;
RENAME TABLE `#__workorder` TO `#__workorder_records`;
RENAME TABLE `#__user_acl` TO `#__user_acl_page`;

--
-- Create Table `#__client_types`
--

CREATE TABLE `#__client_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `client_type_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__client_types` (`id`, `client_type_id`, `display_name`) VALUES
(1, 'residential', 'Residential'),
(2, 'commercial', 'Commercial'),
(3, 'charity', 'Charity'),
(4, 'educational', 'Educational'),
(5, 'goverment', 'Goverment');

ALTER TABLE `#__client_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__expense_types`
--

CREATE TABLE `#__expense_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `expense_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__expense_types` (`id`, `expense_type_id`, `display_name`) VALUES
(1, 'bank_charges', 'Bank Charges'),
(2, 'credit', 'Credit'),
(3, 'client_refund', 'Client Refund'),
(4, 'equipment', 'Equipment'),
(5, 'fuel', 'Fuel'),
(6, 'gift_certificate', 'Gift Certificate'),
(7, 'marketing', 'Marketing'),
(8, 'office_supplies', 'Office Supplies'),
(9, 'online', 'Online'),
(10, 'other', 'Other'),
(11, 'parts', 'Parts'),
(12, 'postage', 'Postage'),
(13, 'rent', 'Rent'),
(14, 'services', 'Services'),
(15, 'software', 'Software'),
(16, 'telco', 'TelCo'),
(17, 'transport', 'Transport'),
(18, 'utilities', 'Utilities'),
(19, 'voucher', 'Voucher'),
(20, 'wages', 'Wages');

ALTER TABLE `#__expense_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__invoice_statuses`
--

CREATE TABLE `#__invoice_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__invoice_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'pending', 'Pending'),
(2, 'unpaid', 'Unpaid'),
(3, 'partially_paid', 'Partially Paid'),
(4, 'paid', 'Paid'),
(5, 'in_dispute', 'In Dispute'),
(6, 'overdue', 'Overdue'),
(7, 'collections', 'Collections'),
(8, 'refunded', 'Refunded'),
(9, 'cancelled', 'Cancelled'),
(10, 'deleted', 'Deleted');

ALTER TABLE `#__invoice_statuses` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__payment_accepted_methods`
--

CREATE TABLE `#__payment_accepted_methods` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `accepted_method_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__payment_accepted_methods` (`id`, `accepted_method_id`, `display_name`, `active`) VALUES
(1, 'cash', 'Cash', 1),
(2, 'cheque', 'Cheque', 1),
(3, 'credit_card', 'Credit Card', 1),
(4, 'direct_deposit', 'Direct Deposit', 1),
(5, 'gift_certificate', 'Gift Certificate', 1),
(6, 'paypal', 'PayPal', 1);

ALTER TABLE `#__payment_accepted_methods` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__payment_purchase_methods`
--

CREATE TABLE `#__payment_purchase_methods` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `purchase_method_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__payment_purchase_methods` (`id`, `purchase_method_id`, `display_name`) VALUES
(1, 'bank_transfer', 'Bank Transfer'),
(2, 'card', 'Card'),
(3, 'cash', 'Cash'),
(4, 'cheque', 'Cheque'),
(5, 'credit', 'Credit'),
(6, 'direct_debit', 'Direct Debit'),
(7, 'gift_certificate', 'Gift Certificate'),
(8, 'google_checkout', 'Google Checkout'),
(9, 'other', 'Other'),
(10, 'paypal', 'PayPal'),
(11, 'voucher', 'Voucher');

ALTER TABLE `#__payment_purchase_methods` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__refund_records`
--

CREATE TABLE `#__refund_records` (
  `refund_id` int(10) NOT NULL,
  `client_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `giftcert_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `#__refund_records` ADD PRIMARY KEY (`refund_id`);

--
-- Create Table `#__refund_types`
--

CREATE TABLE `#__refund_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `refund_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__refund_types` (`id`, `refund_type_id`, `display_name`) VALUES
(1, 'giftcert', 'Gift Certificate'),
(2, 'invoice', 'Invoice');

ALTER TABLE `#__refund_types` ADD PRIMARY KEY (`refund_type_id`);

--
-- Create Table `#__supplier_types`
--

CREATE TABLE `#__supplier_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `supplier_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__supplier_types` (`id`, `supplier_type_id`, `display_name`) VALUES
(1, 'courier', 'Courier'),
(2, 'drop_shipping', 'Drop Shipping'),
(3, 'equipment', 'Equipment'),
(4, 'financial', 'Financial'),
(5, 'fuel', 'Fuel'),
(6, 'general', 'General'),
(7, 'human_resources', 'Human Resources'),
(8, 'landlord', 'Landlord'),
(9, 'marketing', 'Marketing'),
(10, 'office_supplies', 'Office Supplies'),
(11, 'online', 'Online'),
(12, 'other', 'Other'),
(13, 'parts', 'Parts'),
(14, 'services', 'Services'),
(15, 'software', 'Software'),
(16, 'telco', 'TelCo'),
(17, 'transport', 'Transport'),
(18, 'utilities', 'Utilities'),
(19, 'wholesale', 'Wholesale');

ALTER TABLE `#__supplier_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__user_locations`
--

CREATE TABLE `#__user_locations` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `user_location_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__user_locations` (`id`, `user_location_id`, `display_name`) VALUES
(1, 'office', 'Office'),
(2, 'onsite', 'OnSite'),
(3, 'home', 'Home');

ALTER TABLE `#__user_locations` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__payment_credit_cards`
--

CREATE TABLE `#__payment_credit_cards` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `card_key` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__payment_credit_cards` (`id`, `card_key`, `display_name`, `active`) VALUES
(1, 'visa', 'Visa', 1),
(2, 'mastercard', 'MasterCard', 1),
(3, 'american_express', 'American Express', 1),
(4, 'debit_card', 'Debit Card', 1),
(5, 'other', 'Other', 1);

ALTER TABLE `#__payment_credit_cards` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__date_formats`
--

CREATE TABLE `#__date_formats` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `date_format_key` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__date_formats` (`id`, `date_format_key`, `display_name`) VALUES
(1, '%d/%m/%Y', 'dd/mm/yyyy'),
(2, '%m/%d/%Y', 'mm/dd/yyyy'),
(3, '%d/%m/%y', 'dd/mm/yy'),
(4, '%m/%d/%y', 'mm/dd/yy'),
(5, '%Y-%m-%d', 'yyyy-mm-dd');

ALTER TABLE `#__date_formats` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__giftcert_statuses`
--

CREATE TABLE `#__giftcert_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__giftcert_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unused', 'Unused'),
(2, 'redeemed', 'Redeemed'),
(3, 'expired', 'Expired'),
(4, 'suspended', 'Suspended'),
(5, 'cancelled', 'Cancelled'),
(6, 'refunded', 'Refunded');

ALTER TABLE `#__giftcert_statuses` ADD PRIMARY KEY (`id`);

--
-- Rename 'notes' to 'note'
--

ALTER TABLE `#__client_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__schedule_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

--
-- Rename 'display_name' to 'company_name'
--

ALTER TABLE `#__company_record` CHANGE `display_name` `company_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__client_records` CHANGE `display_name` `company_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `display_name` `company_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

--
-- Change 'customer_id' to 'client_id'
--

ALTER TABLE `#__session` CHANGE `client_id` `clientid` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `#__client_records` CHANGE `customer_id` `client_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__client_notes` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__giftcert_records` CHANGE `customer_id` `client_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__payment_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__schedule_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

--
-- ACL changes
--

INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES 
('core:403', '1', '1', '1', '1', '1', '1', '1', '1', '1'),
('giftcert:status', '1', '1', '0', '0', '1', '0', '0', '0', '0'),
('invoice:overview', '1', '1', '0', '0', '1', '0', '0', '0', '0'),
('payment:delete', '1', '1', '0', '0', '0', '0', '0', '0', '0'),
('payment:details', '1', '1', '0', '0', '1', '0', '0', '0', '0'),
('payment:edit', '1', '1', '0', '0', '1', '0', '0', '0', '0'),
('payment:search', '1', '1', '0', '0', '1', '0', '0', '0', '0'),
('workorder:details_edit_comment', '1', '1', '1', '1', '0', '1', '0', '0', '0');

UPDATE `#__user_acl_page` SET `page` = 'client:delete' WHERE `#__user_acl_page`.`page` = 'customer:delete';
UPDATE `#__user_acl_page` SET `page` = 'client:details' WHERE `#__user_acl_page`.`page` = 'customer:details';
UPDATE `#__user_acl_page` SET `page` = 'client:edit' WHERE `#__user_acl_page`.`page` = 'customer:edit';
UPDATE `#__user_acl_page` SET `page` = 'client:new' WHERE `#__user_acl_page`.`page` = 'customer:new';
UPDATE `#__user_acl_page` SET `page` = 'client:note_delete' WHERE `#__user_acl_page`.`page` = 'customer:note_delete';
UPDATE `#__user_acl_page` SET `page` = 'client:note_edit' WHERE `#__user_acl_page`.`page` = 'customer:note_edit';
UPDATE `#__user_acl_page` SET `page` = 'client:note_new' WHERE `#__user_acl_page`.`page` = 'customer:note_new';
UPDATE `#__user_acl_page` SET `page` = 'client:search' WHERE `#__user_acl_page`.`page` = 'customer:search';
UPDATE `#__user_acl_page` SET `page` = 'company:edit' WHERE `#__user_acl_page`.`page` = 'company:settings';
UPDATE `#__user_acl_page` SET `Clerical` = '0' WHERE `#__user_acl_page`.`page` = 'payment:options';
UPDATE `#__user_acl_page` SET `Administrator` = '1', `Manager` = '1', `Supervisor` = '1', `Technician` = '1', `Clerical` = '1', `Counter` = '1', `Client` = '1', `Guest` = '1', `Public` = '1' WHERE `#__user_acl_page`.`page` = 'setup:choice';
UPDATE `#__user_acl_page` SET `Administrator` = '1', `Manager` = '1', `Supervisor` = '1', `Technician` = '1', `Clerical` = '1', `Counter` = '1', `Client` = '1', `Guest` = '1', `Public` = '1' WHERE `#__user_acl_page`.`page` = 'setup:install';
UPDATE `#__user_acl_page` SET `Administrator` = '1', `Manager` = '1', `Supervisor` = '1', `Technician` = '1', `Clerical` = '1', `Counter` = '1', `Client` = '1', `Guest` = '1', `Public` = '1' WHERE `#__user_acl_page`.`page` = 'setup:migrate';
UPDATE `#__user_acl_page` SET `Administrator` = '1', `Manager` = '1', `Supervisor` = '1', `Technician` = '1', `Clerical` = '1', `Counter` = '1', `Client` = '1', `Guest` = '1', `Public` = '1' WHERE `#__user_acl_page`.`page` = 'setup:upgrade';

UPDATE `#__user_usergroups` SET `usergroup_display_name` = 'Client' WHERE `#__user_usergroups`.`usergroup_id` = 7;

DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'invoice:closed';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'invoice:open';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'workorder:closed';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'workorder:details_edit_comments';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'workorder:open';

--
-- Drop Columns
--

ALTER TABLE `#__invoice_records` DROP `paid_date`;
ALTER TABLE `#__user_records` DROP `display_name`;
ALTER TABLE `#__otherincome_records` DROP `invoice_id`;

--
-- Add Columns
--

ALTER TABLE `#__giftcert_records` ADD `workorder_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `client_id`;
ALTER TABLE `#__giftcert_records` ADD `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `date_redeemed`;

--
-- Rename Columns
--

ALTER TABLE `#__payment_records` CHANGE `transaction_id` `payment_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__workorder_records` CHANGE `comments` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `bank_transaction_msg` `invoice_direct_deposit_msg` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `cheque_payable_to_msg` `invoice_cheque_msg` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__client_notes` CHANGE `customer_note_id` `client_note_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__user_acl_page` CHANGE `Customer` `Client` INT(1) NOT NULL DEFAULT '0';
ALTER TABLE `#__giftcert_records` CHANGE `is_redeemed` `redeemed` INT(1) NOT NULL DEFAULT '0' AFTER `active`;
ALTER TABLE `#__giftcert_records` CHANGE `active` `blocked` INT(1) NOT NULL DEFAULT '0' AFTER `redeemed`;
ALTER TABLE `#__giftcert_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `refund_type_id` `otherincome_id` INT(10) NOT NULL AUTO_INCREMENT;

--
-- Convert from integer to currency
--

ALTER TABLE `#__invoice_labour` CHANGE `qty` `qty` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_parts` CHANGE `qty` `qty` DECIMAL(10,2) NOT NULL;
ALTER TABLE `#__giftcert_records` MODIFY COLUMN `amount` decimal(10, 2) NOT NULL DEFAULT 0.00 AFTER `blocked`;
ALTER TABLE `#__invoice_parts` MODIFY COLUMN `qty` decimal(10, 2) NOT NULL DEFAULT 0.00 AFTER `amount`;

--
-- Misc Column Changes
--

ALTER TABLE `#__user_records` CHANGE `based` `based` VARCHAR(30) NOT NULL;
ALTER TABLE `#__user_records` COLLATE = utf8_unicode_ci;
ALTER TABLE `#__user_reset` COLLATE = utf8_unicode_ci;

--
-- Change from int(10) to int(11)
--

ALTER TABLE `#__client_notes` CHANGE `client_note_id` `client_note_id` INT(11) NOT NULL AUTO_INCREMENT;
