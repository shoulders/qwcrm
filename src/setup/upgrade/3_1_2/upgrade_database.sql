/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */


--
-- Upgrade Expense Types Table
--

DROP TABLE IF EXISTS `#__expense_types`;
CREATE TABLE `#__expense_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__expense_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'bank_charges', 'Bank Charges'),
(2, 'credit', 'Credit'),
(3, 'commission', 'Commission'),
(4, 'consumables', 'Consumables'),
(5, 'equipment', 'Equipment'),
(6, 'fuel', 'Fuel'),
(7, 'marketing', 'Marketing'),
(8, 'office_supplies', 'Office Supplies'),
(9, 'online', 'Online'),
(10, 'other', 'Other'),
(11, 'parts', 'Parts'),
(12, 'postage', 'Postage'),
(13, 'rent', 'Rent'),
(14, 'royalty', 'Royalty'),
(15, 'services', 'Services'),
(16, 'software', 'Software'),
(17, 'telco', 'TelCo'),
(18, 'transport', 'Transport'),
(19, 'utilities', 'Utilities'),
(20, 'voucher', 'Voucher'),
(21, 'wages', 'Wages');

ALTER TABLE `#__expense_types` ADD PRIMARY KEY (`id`);

--
-- Upgrade Otherincome Types Table
--

DROP TABLE IF EXISTS `#__otherincome_types`;
CREATE TABLE `#__otherincome_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__otherincome_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'cancelled_services', 'Cancelled Services'),
(2, 'commission', 'Commission'),
(3, 'credit_note', 'Credit Note'),
(4, 'interest', 'Interest'),
(5, 'other', 'Other'),
(6, 'returned_goods', 'Returned Goods'),
(7, 'tip', 'Tip');

ALTER TABLE `#__otherincome_types` ADD PRIMARY KEY (`id`);


--
-- Misc
--

-- Fix session table
ALTER TABLE `#__session` CHANGE `clientid` `clientid` TINYINT(3) UNSIGNED NULL DEFAULT NULL;

-- Enable on/off for Other payment
UPDATE `#__payment_methods` SET `send_protected` = '0' WHERE `#__payment_methods`.`id` = 6;

-- Fix missing employee_id on closed Workorders
UPDATE #__workorder_records SET employee_id = closed_by WHERE employee_id = '' AND closed_by != '';

-- Remove expense_records.invoice_id column becasue it is not used
ALTER TABLE `#__expense_records` DROP `invoice_id`;

-- Change Voucher expiry date from DATE to DATETIME
ALTER TABLE `#__voucher_records` CHANGE `expiry_date` `expiry_date` DATETIME NOT NULL;

-- Update Voucher expiry_date to the proper minutes and seconds, the last line is optional and kept here for reference
UPDATE `#__voucher_records`
SET expiry_date = REPLACE(expiry_date, '00:00:00', '23:59:59');
--WHERE expiry_date LIKE '%00:00:00%';