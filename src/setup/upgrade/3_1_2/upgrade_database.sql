/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */


--
-- Upgrade Expense Types Table
--

DROP TABLE IF EXISTS `qw_expense_types`;
CREATE TABLE `qw_expense_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `qw_expense_types` (`id`, `type_key`, `display_name`) VALUES
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
(14, 'Royalties', 'royalties'),
(15, 'services', 'Services'),
(16, 'software', 'Software'),
(17, 'telco', 'TelCo'),
(18, 'transport', 'Transport'),
(19, 'utilities', 'Utilities'),
(20, 'voucher', 'Voucher'),
(21, 'wages', 'Wages');

ALTER TABLE `qw_expense_types` ADD PRIMARY KEY (`id`);