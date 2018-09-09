/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

--
-- Rename Tables
--

RENAME TABLE `#__company` TO `#__company_record`;
RENAME TABLE `#__customer` TO `#__client_records`;
RENAME TABLE `#__customer_notes` TO `#__client_notes`;
RENAME TABLE `#__customer_types` TO `#__client_types`;
RENAME TABLE `#__expense` TO `#__expense_records`;
RENAME TABLE `#__giftcert` TO `#__giftcert_records`;
RENAME TABLE `#__invoice` TO `#__invoice_records`;
RENAME TABLE `#__payment` TO `#__payment_options`;
RENAME TABLE `#__payment_transactions` TO `#__payment_records`;
RENAME TABLE `#__refund` TO `#__refund_records`;
RENAME TABLE `#__schedule` TO `#__schedule_records`;
RENAME TABLE `#__supplier` TO `#__supplier_records`;
RENAME TABLE `#__user` TO `#__user_records`;
RENAME TABLE `#__workorder` TO `#__workorder_records`;

RENAME TABLE `#__payment_system_methods` TO `#__payment_accepted_methods`;
RENAME TABLE `#__payment_manual_methods` TO `#__payment_purchase_methods`;
RENAME TABLE `#__user_acl` TO `#__user_acl_page`;

--
-- Rename 'notes' to 'note'
--

ALTER TABLE `#__client_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__refund_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__schedule_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__supplier_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

--
-- Rename 'display_name' to 'company_name'
--
ALTER TABLE `fcyd_company_record` CHANGE `display_name` `company_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_client_records` CHANGE `display_name` `company_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_supplier_records` CHANGE `display_name` `company_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

--
-- Change 'customer_id' to 'client_id'
--

ALTER TABLE `fcyd_session` CHANGE `client_id` `clientid` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `fcyd_client_records` CHANGE `customer_id` `client_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `fcyd_client_notes` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_giftcert_records` CHANGE `customer_id` `client_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_invoice_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_payment_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_schedule_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_user_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `fcyd_workorder_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;



--
-- Misc Column Changes
--

ALTER TABLE `#__payment_records` CHANGE `transaction_id` `payment_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__payment_accepted_methods` CHANGE `system_method_id` `system_method_id` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__payment_purchase_methods` CHANGE `manual_method_id` `purchase_method_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_records` CHANGE `comments` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `based` `based` VARCHAR(30) NOT NULL;
ALTER TABLE `fcyd_payment_options` CHANGE `bank_transaction_msg` `invoice_direct_deposit_msg` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_payment_options` CHANGE `cheque_payable_to_msg` `invoice_cheque_msg` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_client_notes` CHANGE `customer_note_id` `client_note_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `fcyd_client_types` CHANGE `customer_type_id` `client_type_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `fcyd_user_acl_page` CHANGE `Customer` `Client` INT(1) NOT NULL DEFAULT '0';
ALTER TABLE `fcyd_giftcert_records` CHANGE `is_redeemed` `redeemed` INT(1) NOT NULL DEFAULT '0' AFTER `active`;
ALTER TABLE `fcyd_giftcert_records` ADD `workorder_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `client_id`;
ALTER TABLE `fcyd_invoice_labour` CHANGE `qty` `qty` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `fcyd_invoice_parts` CHANGE `qty` `qty` DECIMAL(10,2) NOT NULL;
ALTER TABLE `fcyd_giftcert_records` ADD `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `date_redeemed`;
ALTER TABLE `#__invoice_records` DROP `paid_date`;
ALTER TABLE `fcyd_user_records` DROP `display_name`;


--
-- ACL changes
--
UPDATE `fcyd_user_acl_page` SET `page` = 'client:delete' WHERE `fcyd_user_acl_page`.`page` = 'customer:delete';
UPDATE `fcyd_user_acl_page` SET `page` = 'client:details' WHERE `fcyd_user_acl_page`.`page` = 'customer:details';
UPDATE `fcyd_user_acl_page` SET `page` = 'client:edit' WHERE `fcyd_user_acl_page`.`page` = 'customer:edit';
UPDATE `fcyd_user_acl_page` SET `page` = 'client:new' WHERE `fcyd_user_acl_page`.`page` = 'customer:new';
UPDATE `fcyd_user_acl_page` SET `page` = 'client:note_delete' WHERE `fcyd_user_acl_page`.`page` = 'customer:note_delete';
UPDATE `fcyd_user_acl_page` SET `page` = 'client:note_edit' WHERE `fcyd_user_acl_page`.`page` = 'customer:note_edit';
UPDATE `fcyd_user_acl_page` SET `page` = 'client:note_new' WHERE `fcyd_user_acl_page`.`page` = 'customer:note_new';
UPDATE `fcyd_user_acl_page` SET `page` = 'client:search' WHERE `fcyd_user_acl_page`.`page` = 'customer:search';
UPDATE `fcyd_user_acl_page` SET `page` = 'company:edit' WHERE `fcyd_user_acl_page`.`page` = 'company:settings';
INSERT INTO `fcyd_user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES ('core:403', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `fcyd_user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES ('giftcert:status', '1', '1', '0', '0', '1', '0', '0', '0', '0');
DELETE FROM `fcyd_user_acl_page` WHERE `fcyd_user_acl_page`.`page` = \'invoice:closed\';
DELETE FROM `fcyd_user_acl_page` WHERE `fcyd_user_acl_page`.`page` = \'invoice:open\';
INSERT INTO `fcyd_user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES ('invoice:overview', '1', '1', '0', '0', '1', '0', '0', '0', '0');
INSERT INTO `fcyd_user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES ('payment:delete', '1', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fcyd_user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES ('payment:details', '1', '1', '0', '0', '1', '0', '0', '0', '0');
INSERT INTO `fcyd_user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES ('payment:edit', '1', '1', '0', '0', '1', '0', '0', '0', '0');
UPDATE `fcyd_user_acl_page` SET `Clerical` = '0' WHERE `fcyd_user_acl_page`.`page` = 'payment:options';
INSERT INTO `fcyd_user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES ('payment:search', '1', '1', '0', '0', '1', '0', '0', '0', '0');
UPDATE `fcyd_user_acl_page` SET `Administrator` = '1', `Manager` = '1', `Supervisor` = '1', `Technician` = '1', `Clerical` = '1', `Counter` = '1', `Client` = '1', `Guest` = '1', `Public` = '1' WHERE `fcyd_user_acl_page`.`page` = 'setup:choice';
UPDATE `fcyd_user_acl_page` SET `Administrator` = '1', `Manager` = '1', `Supervisor` = '1', `Technician` = '1', `Clerical` = '1', `Counter` = '1', `Client` = '1', `Guest` = '1', `Public` = '1' WHERE `fcyd_user_acl_page`.`page` = 'setup:install';
UPDATE `fcyd_user_acl_page` SET `Administrator` = '1', `Manager` = '1', `Supervisor` = '1', `Technician` = '1', `Clerical` = '1', `Counter` = '1', `Client` = '1', `Guest` = '1', `Public` = '1' WHERE `fcyd_user_acl_page`.`page` = 'setup:migrate';
UPDATE `fcyd_user_acl_page` SET `Administrator` = '1', `Manager` = '1', `Supervisor` = '1', `Technician` = '1', `Clerical` = '1', `Counter` = '1', `Client` = '1', `Guest` = '1', `Public` = '1' WHERE `fcyd_user_acl_page`.`page` = 'setup:upgrade';
DELETE FROM `fcyd_user_acl_page` WHERE `fcyd_user_acl_page`.`page` = \'workorder:closed\';
DELETE FROM `fcyd_user_acl_page` WHERE `fcyd_user_acl_page`.`page` = \'workorder:details_edit_comments\';
INSERT INTO `fcyd_user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES ('workorder:details_edit_comment', '1', '1', '1', '1', '0', '1', '0', '0', '0');
DELETE FROM `fcyd_user_acl_page` WHERE `fcyd_user_acl_page`.`page` = \'workorder:open\';



--
-- Data Update
--

UPDATE `fcyd_user_usergroups` SET `usergroup_display_name` = 'Client' WHERE `fcyd_user_usergroups`.`usergroup_id` = 7;

-- --------------------------------------------------------

--
-- Add Tables
--

-- --------------------------------------------------------
DROP TABLE `fcyd_client_types`;
DROP TABLE `fcyd_expense_types`;
DROP TABLE `fcyd_payment_accepted_methods`;
DROP TABLE `fcyd_payment_credit_cards`;
DROP TABLE `fcyd_payment_purchase_methods`;
DROP TABLE `fcyd_refund_types`;
DROP TABLE `fcyd_supplier_types`;

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table `fcyd_client_types`
--

CREATE TABLE `fcyd_client_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `client_type_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_client_types`
--

INSERT INTO `fcyd_client_types` (`id`, `client_type_id`, `display_name`) VALUES
(1, 'residential', 'Residential'),
(2, 'commercial', 'Commercial'),
(3, 'charity', 'Charity'),
(4, 'educational', 'Educational'),
(5, 'goverment', 'Goverment');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_client_types`
--
ALTER TABLE `fcyd_client_types`
  ADD PRIMARY KEY (`id`);



-- --------------------------------------------------------

--
-- Table structure for table `fcyd_expense_types`
--

CREATE TABLE `fcyd_expense_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `expense_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_expense_types`
--

INSERT INTO `fcyd_expense_types` (`id`, `expense_type_id`, `display_name`) VALUES
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_expense_types`
--
ALTER TABLE `fcyd_expense_types`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

--
-- Table structure for table `fcyd_payment_accepted_methods`
--

CREATE TABLE `fcyd_payment_accepted_methods` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `accepted_method_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_payment_accepted_methods`
--

INSERT INTO `fcyd_payment_accepted_methods` (`id`, `accepted_method_id`, `display_name`, `active`) VALUES
(1, 'cash', 'Cash', 1),
(2, 'cheque', 'Cheque', 1),
(3, 'credit_card', 'Credit Card', 1),
(4, 'direct_deposit', 'Direct Deposit', 1),
(5, 'gift_certificate', 'Gift Certificate', 1),
(6, 'paypal', 'PayPal', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_payment_accepted_methods`
--
ALTER TABLE `fcyd_payment_accepted_methods`
  ADD PRIMARY KEY (`id`);


-- --------------------------------------------------------

--
-- Table structure for table `fcyd_payment_purchase_methods`
--

CREATE TABLE `fcyd_payment_purchase_methods` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `purchase_method_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_payment_purchase_methods`
--

INSERT INTO `fcyd_payment_purchase_methods` (`id`, `purchase_method_id`, `display_name`) VALUES
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_payment_purchase_methods`
--
ALTER TABLE `fcyd_payment_purchase_methods`
  ADD PRIMARY KEY (`id`);


-- --------------------------------------------------------

--
-- Table structure for table `fcyd_refund_types`
--

CREATE TABLE `fcyd_refund_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `refund_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_refund_types`
--

INSERT INTO `fcyd_refund_types` (`id`, `refund_type_id`, `display_name`) VALUES
(1, 'credit_note', 'Credit Note'),
(2, 'other', 'Other'),
(3, 'proxy_invoice', 'Proxy Invoice'),
(4, 'returned_goods', 'Returned Goods'),
(5, 'returned_services', 'Returned Services');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_refund_types`
--
ALTER TABLE `fcyd_refund_types`
  ADD PRIMARY KEY (`refund_type_id`);



-- --------------------------------------------------------

--
-- Table structure for table `fcyd_supplier_types`
--

CREATE TABLE `fcyd_supplier_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `supplier_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_supplier_types`
--

INSERT INTO `fcyd_supplier_types` (`id`, `supplier_type_id`, `display_name`) VALUES
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_supplier_types`
--
ALTER TABLE `fcyd_supplier_types`
  ADD PRIMARY KEY (`id`);


-- --------------------------------------------------------

--
-- Table structure for table `fcyd_user_locations`
--

CREATE TABLE `fcyd_user_locations` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `user_location_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_user_locations`
--

INSERT INTO `fcyd_user_locations` (`id`, `user_location_id`, `display_name`) VALUES
(1, 'office', 'Office'),
(2, 'onsite', 'OnSite'),
(3, 'home', 'Home');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_user_locations`
--
ALTER TABLE `fcyd_user_locations`
  ADD PRIMARY KEY (`id`);



-- --------------------------------------------------------

--
-- Table structure for table `fcyd_payment_credit_cards`
--

CREATE TABLE `fcyd_payment_credit_cards` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `card_key` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_payment_credit_cards`
--

INSERT INTO `fcyd_payment_credit_cards` (`id`, `card_key`, `display_name`, `active`) VALUES
(1, 'visa', 'Visa', 1),
(2, 'mastercard', 'MasterCard', 1),
(3, 'american_express', 'American Express', 1),
(4, 'debit_card', 'Debit Card', 1),
(5, 'other', 'Other', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_payment_credit_cards`
--
ALTER TABLE `fcyd_payment_credit_cards`
  ADD PRIMARY KEY (`id`);



-- --------------------------------------------------------

--
-- Table structure for table `fcyd_date_formats`
--

CREATE TABLE `fcyd_date_formats` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `date_format_key` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fcyd_date_formats`
--

INSERT INTO `fcyd_date_formats` (`id`, `date_format_key`, `display_name`) VALUES
(1, '%d/%m/%Y', 'dd/mm/yyyy'),
(2, '%m/%d/%Y', 'mm/dd/yyyy'),
(3, '%d/%m/%y', 'dd/mm/yy'),
(4, '%m/%d/%y', 'mm/dd/yy'),
(5, '%Y-%m-%d', 'yyyy-mm-dd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcyd_date_formats`
--
ALTER TABLE `fcyd_date_formats`
  ADD PRIMARY KEY (`id`);


-- --------------------------------------------------------

--
-- Table structure for table `qw_giftcert_statuses`
--

CREATE TABLE `qw_giftcert_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `qw_giftcert_statuses`
--

INSERT INTO `qw_giftcert_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unused', 'Unused'),
(2, 'redeemed', 'Redeemed'),
(3, 'expired', 'Expired'),
(4, 'suspended', 'Suspended'),
(5, 'cancelled', 'Cancelled'),
(6, 'refunded', 'Refunded');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `qw_giftcert_statuses`
--
ALTER TABLE `qw_giftcert_statuses`
  ADD PRIMARY KEY (`id`);
