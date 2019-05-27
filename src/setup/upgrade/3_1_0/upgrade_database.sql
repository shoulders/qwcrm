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
RENAME TABLE `#__giftcert` TO `#__voucher_records`;
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
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__client_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'residential', 'Residential'),
(2, 'commercial', 'Commercial'),
(3, 'charity', 'Charity'),
(4, 'educational', 'Educational'),
(5, 'goverment', 'Goverment');

ALTER TABLE `#__client_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__company_tax_systems`
--

CREATE TABLE `#__company_tax_systems` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__company_tax_systems` (`id`, `type_key`, `display_name`) VALUES
(1, 'no_tax', 'No Tax'),
(2, 'sales_tax_cash', 'Sales Tax (Cash Basis)'),
(3, 'vat_standard', 'VAT Standard Accounting (UK)'),
(4, 'vat_cash', 'VAT Cash Accounting (UK)'),
(5, 'vat_flat_basic', 'VAT Flat Rate (Basic Turnover) (UK)'),
(6, 'vat_flat_cash', 'VAT Flat Rate (Cash based turnover) (UK)');

ALTER TABLE `#__company_tax_systems` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__company_vat_tax_codes`
--

CREATE TABLE `#__company_vat_tax_codes` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `tax_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rate` decimal(4,2) NOT NULL,
  `hidden` int(1) NOT NULL DEFAULT '0',
  `editable` int(1) NOT NULL DEFAULT '0',
  `system_tax_code` int(1) NOT NULL DEFAULT '0' COMMENT 'Is not a standard VAT code',
  `enabled` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__company_vat_tax_codes` (`id`, `tax_key`, `display_name`, `description`, `rate`, `hidden`, `editable`, `system_tax_code`, `enabled`) VALUES
(1, 'T0', 'Zero Rate', 'Zero rated transactions', '0.00', 0, 0, 0, 1),
(2, 'T1', 'Standard Rate', 'Standard rated transactions', '20.00', 0, 1, 0, 1),
(3, 'T2', 'Exempt', 'Exempt transactions', '0.00', 0, 0, 0, 1),
(4, 'T3', '', '', '0.00', 0, 0, 0, 0),
(5, 'T4', 'Sales - Goods - EC VAT Customers', 'Sale of goods to VAT registered customers in EC', '0.00', 0, 0, 0, 1),
(6, 'T5', 'Reduced Rate', 'Lower rated transactions', '5.00', 0, 1, 0, 1),
(7, 'T6', '', '', '0.00', 0, 0, 0, 0),
(8, 'T7', 'Zero Rate Purchases - Goods - EC', 'Zero rated purchases of goods from suppliers in EC', '0.00', 0, 0, 0, 1),
(9, 'T8', 'Standard Rate Purchases - Goods - EC', 'Standard rated purchases of goods from suppliers in EC', '0.00', 0, 0, 0, 1),
(10, 'T9', 'Transactions not involving VAT', 'Transactions not involving VAT. This is the default non-vatable tax code.', '0.00', 0, 0, 0, 1),
(11, 'T10', '', '', '0.00', 0, 0, 0, 0),
(12, 'T11', '', '', '0.00', 0, 0, 0, 0),
(13, 'T12', '', '', '0.00', 0, 0, 0, 0),
(14, 'T13', '', '', '0.00', 0, 0, 0, 0),
(15, 'T14', '', '', '0.00', 0, 0, 0, 0),
(16, 'T15', '', '', '0.00', 0, 0, 0, 0),
(17, 'T16', '', '', '0.00', 0, 0, 0, 0),
(18, 'T17', '', '', '0.00', 0, 0, 0, 0),
(19, 'T18', '', '', '0.00', 0, 0, 0, 0),
(20, 'T19', '', '', '0.00', 0, 0, 0, 0),
(21, 'T20', 'Reverse Charges', 'Sale or purchase of items that fall under the remit of carousel fraud', '0.00', 0, 0, 0, 1),
(22, 'T21', '', '', '0.00', 0, 0, 0, 0),
(23, 'T22', 'Sales - Services - EC VAT Customers', 'Sales of services to VAT registered customers in EC', '0.00', 0, 0, 0, 1),
(24, 'T23', 'Zero Rate / Exempt Purchases - Services - EC', 'Zero rated or exempt purchases of services from suppliers in EC', '0.00', 0, 0, 0, 1),
(25, 'T24', 'Standard Rate Purchases - Services - EC', 'Standard rated purchases of services from suppliers in EC', '0.00', 0, 0, 0, 1),
(26, 'T25', 'Flat Rate Capital Asset', 'UK Flat Rate scheme only - Purchase or sale of capital items, where the purchase amount is more than &pound;2,000 inclusive of VAT.', '0.00', 0, 0, 0, 1),
(1000, 'TNA', 'Not Applicable', 'VAT is not applicable on this tax system.', '0.00', 1, 0, 1, 1),
(1001, 'TVM', 'VAT Multi TCode', 'This record has sub records that might all have different T codes.', '0.00', 1, 0, 1, 1);

ALTER TABLE `#__company_vat_tax_codes` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__expense_types`
--

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
-- Create Table `#__payment_additional_info_types`
--

CREATE TABLE `#__payment_additional_info_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__payment_additional_info_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'bank_transfer_reference', 'Bank Transfer Reference'),
(2, 'card_type_key', 'Card Type'),
(3, 'name_on_card', 'Name on Card'),
(4, 'cheque_number', 'Cheque Number'),
(5, 'direct_debit_reference', 'Direct Debit Reference'),
(6, 'paypal_transaction_id', 'PayPal Transaction ID');

ALTER TABLE `#__payment_additional_info_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__payment_methods`
--

CREATE TABLE `#__payment_methods` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `method_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `send` int(1) NOT NULL DEFAULT '0',
  `receive` int(1) NOT NULL DEFAULT '0',
  `send_protected` int(1) NOT NULL DEFAULT '1' COMMENT 'send cannot be changed',
  `receive_protected` int(1) NOT NULL DEFAULT '1' COMMENT 'receive cannot be changed',
  `enabled` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__payment_methods` (`id`, `method_key`, `display_name`, `send`, `receive`, `send_protected`, `receive_protected`, `enabled`) VALUES
(1, 'bank_transfer', 'Bank Transfer', 1, 1, 0, 0, 0),
(2, 'card', 'Card', 1, 1, 0, 0, 0),
(3, 'cash', 'Cash', 1, 1, 0, 0, 1),
(4, 'cheque', 'Cheque', 1, 1, 0, 0, 0),
(5, 'direct_debit', 'Direct Debit', 1, 1, 0, 0, 0),
(6, 'other', 'Other', 0, 1, 1, 0, 0),
(7, 'paypal', 'PayPal', 1, 1, 0, 0, 0),
(8, 'voucher', 'Voucher', 0, 1, 1, 1, 0);

ALTER TABLE `#__payment_methods` ADD PRIMARY KEY (`id`);

--
-- Create Table table `#__payment_statuses`
--

CREATE TABLE `#__payment_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__payment_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'valid', 'Valid'),
(2, 'cancelled', 'Cancelled'),
(3, 'deleted', 'Deleted');

ALTER TABLE `#__payment_statuses` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__payment_types`
--

CREATE TABLE `#__payment_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__payment_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'invoice', 'Invoice'),
(2, 'refund', 'Refund'),
(3, 'expense', 'Expense'),
(4, 'otherincome', 'Other Income');

ALTER TABLE `#__payment_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__refund_records`
--

CREATE TABLE `#__refund_records` (
  `refund_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,  
  `item_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `unit_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_tax_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',    
  `unit_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_gross` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `last_active` datetime NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `#__refund_records` ADD PRIMARY KEY (`refund_id`);

ALTER TABLE `#__refund_records` MODIFY `refund_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Create Table `#__refund_types`
--

CREATE TABLE `#__refund_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__refund_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'invoice', 'Invoice'),
(2, 'cash_purchase', 'Cash Purchase');

ALTER TABLE `#__refund_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__supplier_types`
--

CREATE TABLE `#__supplier_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__supplier_types` (`id`, `type_key`, `display_name`) VALUES
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
  `location_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__user_locations` (`id`, `location_key`, `display_name`) VALUES
(1, 'office', 'Office'),
(2, 'onsite', 'OnSite'),
(3, 'home', 'Home');

ALTER TABLE `#__user_locations` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__payment_card_types`
--

CREATE TABLE `#__payment_card_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__payment_card_types` (`id`, `type_key`, `display_name`, `active`) VALUES
(1, 'visa', 'Visa', 1),
(2, 'mastercard', 'MasterCard', 1),
(3, 'american_express', 'American Express', 1),
(4, 'debit_card', 'Debit Card', 1),
(5, 'other', 'Other', 1);

ALTER TABLE `#__payment_card_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__company_date_formats`
--

CREATE TABLE `#__company_date_formats` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `date_format_key` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__company_date_formats` (`id`, `date_format_key`, `display_name`) VALUES
(1, '%d/%m/%Y', 'dd/mm/yyyy'),
(2, '%m/%d/%Y', 'mm/dd/yyyy'),
(3, '%d/%m/%y', 'dd/mm/yy'),
(4, '%m/%d/%y', 'mm/dd/yy'),
(5, '%Y-%m-%d', 'yyyy-mm-dd');

ALTER TABLE `#__company_date_formats` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__voucher_statuses`
--

CREATE TABLE `#__voucher_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__voucher_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unused', 'Unused'),
(2, 'redeemed', 'Redeemed'),
(3, 'suspended', 'Suspended'),
(4, 'expired', 'Expired'),
(5, 'refunded', 'Refunded'),
(6, 'cancelled', 'Cancelled'),
(7, 'deleted', 'Deleted');

ALTER TABLE `#__voucher_statuses` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__voucher_types`
--

CREATE TABLE `#__voucher_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__voucher_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'MPV', 'Multi Purpose (MPV)'),
(2, 'SPV', 'Single Purpose (SPV)');

ALTER TABLE `#__voucher_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__otherincome_types`
--

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
(6, 'returned_goods', 'Returned Goods');

ALTER TABLE `#__otherincome_types` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__expense_statuses`
--

CREATE TABLE `#__expense_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__expense_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unpaid', 'Unpaid'),
(2, 'partially_paid', 'Partially Paid'),
(3, 'paid', 'Paid'),
(4, 'cancelled', 'Cancelled'),
(5, 'deleted', 'Deleted');

ALTER TABLE `#__expense_statuses` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__otherincome_statuses`
--

CREATE TABLE `#__otherincome_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__otherincome_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unpaid', 'Unpaid'),
(2, 'partially_paid', 'Partially Paid'),
(3, 'paid', 'Paid'),
(4, 'cancelled', 'Cancelled'),
(5, 'deleted', 'Deleted');

ALTER TABLE `#__otherincome_statuses` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__refund_statuses`
--

CREATE TABLE `#__refund_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__refund_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unpaid', 'Unpaid'),
(2, 'partially_paid', 'Partially Paid'),
(3, 'paid', 'Paid'),
(4, 'cancelled', 'Cancelled'),
(5, 'deleted', 'Deleted');

ALTER TABLE `#__refund_statuses` ADD PRIMARY KEY (`id`);

--
-- Create Table `#__supplier_statuses`
--

CREATE TABLE `#__supplier_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__supplier_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'valid', 'Valid'),
(2, 'cancelled', 'Cancelled');

ALTER TABLE `#__supplier_statuses` ADD PRIMARY KEY (`id`);

--
-- Rename 'notes' to 'note'
--

ALTER TABLE `#__client_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `notes` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
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
ALTER TABLE `#__voucher_records` CHANGE `customer_id` `client_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__payment_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__schedule_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_records` CHANGE `customer_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

--
-- Drop Columns
--

ALTER TABLE `#__invoice_records` DROP `paid_date`;
ALTER TABLE `#__user_records` DROP `display_name`;
ALTER TABLE `#__otherincome_records` DROP `invoice_id`;
ALTER TABLE `#__voucher_records` DROP `is_redeemed`;

--
-- Add Columns
--

ALTER TABLE `#__voucher_records` ADD `workorder_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `client_id`;
ALTER TABLE `#__voucher_records` ADD `close_date` DATETIME NOT NULL AFTER `date_redeemed`;
ALTER TABLE `#__voucher_records` ADD `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `close_date`;
ALTER TABLE `#__voucher_records` ADD `payment_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `invoice_id`;
ALTER TABLE `#__voucher_records` ADD `redeemed_client_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `payment_id`;
ALTER TABLE `#__voucher_records` ADD `refund_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `payment_id`;
ALTER TABLE `#__payment_records` ADD `type` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `date`;
ALTER TABLE `#__payment_records` ADD `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `method`;
ALTER TABLE `#__payment_records` ADD `voucher_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL NULL AFTER `invoice_id`;
ALTER TABLE `#__payment_records` ADD `refund_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL NULL AFTER `voucher_id`;
ALTER TABLE `#__payment_records` ADD `expense_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL NULL AFTER `refund_id`;
ALTER TABLE `#__payment_records` ADD `otherincome_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL NULL AFTER `expense_id`;
ALTER TABLE `#__payment_records` ADD `additional_info` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `amount`;
ALTER TABLE `#__payment_records` ADD `tax_system` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `date`;

ALTER TABLE `#__voucher_records` CHANGE `invoice_id` `redeemed_invoice_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `redeemed_client_id`;
ALTER TABLE `#__voucher_records` ADD `invoice_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `workorder_id`;


--
-- Rename Columns
--

ALTER TABLE `#__client_notes` CHANGE `customer_note_id` `client_note_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__voucher_records` CHANGE `giftcert_id` `voucher_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__voucher_records` CHANGE `giftcert_code` `voucher_code` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `active` `blocked` INT(1) NOT NULL DEFAULT '0';
ALTER TABLE `#__voucher_records` CHANGE `date_created` `open_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `date_expires` `expiry_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `date_redeemed` `redeem_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` CHANGE `refund_id` `otherincome_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__payment_options` CHANGE `bank_transaction_msg` `invoice_bank_transfer_msg` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__payment_options` CHANGE `cheque_payable_to_msg` `invoice_cheque_msg` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__payment_records` CHANGE `transaction_id` `payment_id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__user_acl_page` CHANGE `Customer` `Client` INT(1) NOT NULL DEFAULT '0';
ALTER TABLE `#__workorder_records` CHANGE `comments` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_usergroups` CHANGE `usergroup_display_name` `display_name` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '';

--
-- Move Columns
--

ALTER TABLE `#__voucher_records` CHANGE `blocked` `blocked` INT(1) NOT NULL DEFAULT '0' AFTER `status`;

--
-- Upgrade Labour and parts tables for new VAT and Tax system
--

ALTER TABLE `#__invoice_labour` ADD `tax_system` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `description`;
ALTER TABLE `#__invoice_labour` ADD `vat_tax_code` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `tax_system`;
ALTER TABLE `#__invoice_labour` ADD `unit_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `vat_tax_code`;
ALTER TABLE `#__invoice_labour` CHANGE `amount` `unit_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_labour` ADD `unit_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_net`;
ALTER TABLE `#__invoice_labour` ADD `unit_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_tax`;
ALTER TABLE `#__invoice_labour` CHANGE `qty` `unit_qty` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_labour` CHANGE `sub_total` `sub_total_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_labour` ADD `sub_total_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `sub_total_net`;
ALTER TABLE `#__invoice_labour` ADD `sub_total_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `sub_total_tax`;
ALTER TABLE `#__invoice_labour` CHANGE `description` `description` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `tax_system`;
ALTER TABLE `#__invoice_labour` CHANGE `unit_qty` `unit_qty` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `description`;
ALTER TABLE `#__invoice_labour` ADD `sales_tax_exempt` INT(1) NOT NULL DEFAULT '0' AFTER `unit_net`;

ALTER TABLE `#__invoice_parts` ADD `tax_system` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `description`;
ALTER TABLE `#__invoice_parts` ADD `vat_tax_code` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `tax_system`;
ALTER TABLE `#__invoice_parts` ADD `unit_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `vat_tax_code`;
ALTER TABLE `#__invoice_parts` CHANGE `amount` `unit_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_parts` ADD `unit_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_net`;
ALTER TABLE `#__invoice_parts` ADD `unit_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_tax`;
ALTER TABLE `#__invoice_parts` CHANGE `qty` `unit_qty` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_parts` CHANGE `sub_total` `sub_total_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_parts` ADD `sub_total_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `sub_total_net`;
ALTER TABLE `#__invoice_parts` ADD `sub_total_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `sub_total_tax`;
ALTER TABLE `#__invoice_parts` CHANGE `description` `description` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `tax_system`;
ALTER TABLE `#__invoice_parts` CHANGE `unit_qty` `unit_qty` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `description`;
ALTER TABLE `#__invoice_parts` ADD `sales_tax_exempt` INT(1) NOT NULL DEFAULT '0' AFTER `unit_net`;

--
-- Update Email signature and email
--

UPDATE `#__company_record` SET 
  `email_signature` = '<p>{company_logo}</p> <p><strong>{company_name}</strong></p> <p><strong>Address:</strong> <br />{company_address}</p> <p><strong>Tel:</strong> {company_telephone} <br /><strong>Website:</strong> {company_website}</p>',
  `email_msg_invoice` = '<p>Hi {client_first_name} {client_last_name}</p> <p>This is an invoice for the recent work at {client_display_name}.</p> <p>Thanks for your custom.</p>';

--
-- ACL changes
--

INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES 
('core:403', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('expense:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:overview', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('otherincome:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('otherincome:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('otherincome:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('otherincome:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('otherincome:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('otherincome:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('otherincome:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('voucher:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('workorder:details_edit_comment', 1, 1, 1, 1, 0, 1, 0, 0, 0);

UPDATE `#__user_acl_page` SET `page` = 'client:delete' WHERE `#__user_acl_page`.`page` = 'customer:delete';
UPDATE `#__user_acl_page` SET `page` = 'client:details' WHERE `#__user_acl_page`.`page` = 'customer:details';
UPDATE `#__user_acl_page` SET `page` = 'client:edit' WHERE `#__user_acl_page`.`page` = 'customer:edit';
UPDATE `#__user_acl_page` SET `page` = 'client:new' WHERE `#__user_acl_page`.`page` = 'customer:new';
UPDATE `#__user_acl_page` SET `page` = 'client:note_delete' WHERE `#__user_acl_page`.`page` = 'customer:note_delete';
UPDATE `#__user_acl_page` SET `page` = 'client:note_edit' WHERE `#__user_acl_page`.`page` = 'customer:note_edit';
UPDATE `#__user_acl_page` SET `page` = 'client:note_new' WHERE `#__user_acl_page`.`page` = 'customer:note_new';
UPDATE `#__user_acl_page` SET `page` = 'client:search' WHERE `#__user_acl_page`.`page` = 'customer:search';
UPDATE `#__user_acl_page` SET `page` = 'company:edit' WHERE `#__user_acl_page`.`page` = 'company:settings';

UPDATE `#__user_acl_page` SET `page` = 'voucher:delete' WHERE `#__user_acl_page`.`page` = 'giftcert:delete';
UPDATE `#__user_acl_page` SET `page` = 'voucher:details' WHERE `#__user_acl_page`.`page` = 'giftcert:details';
UPDATE `#__user_acl_page` SET `page` = 'voucher:edit' WHERE `#__user_acl_page`.`page` = 'giftcert:edit';
UPDATE `#__user_acl_page` SET `page` = 'voucher:new' WHERE `#__user_acl_page`.`page` = 'giftcert:new';
UPDATE `#__user_acl_page` SET `page` = 'voucher:print' WHERE `#__user_acl_page`.`page` = 'giftcert:print';
UPDATE `#__user_acl_page` SET `page` = 'voucher:search' WHERE `#__user_acl_page`.`page` = 'giftcert:search';

UPDATE `#__user_acl_page` SET `Clerical` = 0 WHERE `#__user_acl_page`.`page` = 'payment:options';

UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 0, `Technician` = 0, `Clerical` = 1, `Counter` = 0, `Client` = 0, `Guest` = 0, `Public` = 0 WHERE `#__user_acl_page`.`page` = 'refund:delete';
UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 0, `Technician` = 0, `Clerical` = 1, `Counter` = 0, `Client` = 0, `Guest` = 0, `Public` = 0 WHERE `#__user_acl_page`.`page` = 'refund:details';
UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 0, `Technician` = 0, `Clerical` = 1, `Counter` = 0, `Client` = 0, `Guest` = 0, `Public` = 0 WHERE `#__user_acl_page`.`page` = 'refund:edit';
UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 0, `Technician` = 0, `Clerical` = 1, `Counter` = 0, `Client` = 0, `Guest` = 0, `Public` = 0 WHERE `#__user_acl_page`.`page` = 'refund:new';
UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 0, `Technician` = 0, `Clerical` = 1, `Counter` = 0, `Client` = 0, `Guest` = 0, `Public` = 0 WHERE `#__user_acl_page`.`page` = 'refund:search';

UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 1, `Technician` = 1, `Clerical` = 1, `Counter` = 1, `Client` = 1, `Guest` = 1, `Public` = 1 WHERE `#__user_acl_page`.`page` = 'setup:choice';
UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 1, `Technician` = 1, `Clerical` = 1, `Counter` = 1, `Client` = 1, `Guest` = 1, `Public` = 1 WHERE `#__user_acl_page`.`page` = 'setup:install';
UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 1, `Technician` = 1, `Clerical` = 1, `Counter` = 1, `Client` = 1, `Guest` = 1, `Public` = 1 WHERE `#__user_acl_page`.`page` = 'setup:migrate';
UPDATE `#__user_acl_page` SET `Administrator` = 1, `Manager` = 1, `Supervisor` = 1, `Technician` = 1, `Clerical` = 1, `Counter` = 1, `Client` = 1, `Guest` = 1, `Public` = 1 WHERE `#__user_acl_page`.`page` = 'setup:upgrade';

UPDATE `#__user_usergroups` SET `display_name` = 'Client' WHERE `#__user_usergroups`.`usergroup_id` = 7;

DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'invoice:closed';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'invoice:open';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'workorder:closed';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'workorder:details_edit_comments';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'workorder:open';

--
-- Update Tax Type and Tax Rates to allow for the new VAT system
--

ALTER TABLE `#__invoice_records` CHANGE `tax_type` `tax_system` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_records` CHANGE `tax_rate` `sales_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__company_record` CHANGE `tax_type` `tax_system` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__company_record` CHANGE `tax_rate` `sales_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__company_record` ADD `vat_flat_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `vat_number`;
ALTER TABLE `#__invoice_labour` CHANGE `vat_tax_code` `vat_tax_code` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `unit_net`;
ALTER TABLE `#__invoice_labour` CHANGE `unit_tax_rate` `unit_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `vat_tax_code`;
ALTER TABLE `#__invoice_parts` CHANGE `vat_tax_code` `vat_tax_code` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `unit_net`;
ALTER TABLE `#__invoice_parts` CHANGE `unit_tax_rate` `unit_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `vat_tax_code`;

--
-- Misc Column Changes
--

ALTER TABLE `#__user_records` CHANGE `based` `based` VARCHAR(30) NOT NULL;
ALTER TABLE `#__invoice_prefill_items` CHANGE `amount` `net_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_records` ADD `refund_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `workorder_id`;

--
-- Correct #__user_reset index column
--

ALTER TABLE `#__user_reset` DROP `user_id`;
ALTER TABLE `#__user_reset` ADD `user_id` INT(10) NOT NULL FIRST;
ALTER TABLE `#__user_reset` ADD PRIMARY KEY(`user_id`);

--
-- Correct Collation issues (should all be utf8_unicode_ci)
--

ALTER TABLE `#__user_records` COLLATE = utf8_unicode_ci;
ALTER TABLE `#__user_reset` COLLATE = utf8_unicode_ci;

ALTER TABLE `#__user_records` CHANGE `client_id` `client_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `username` `username` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `password` `password` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `email` `email` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `usergroup` `usergroup` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `first_name` `first_name` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `last_name` `last_name` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `work_primary_phone` `work_primary_phone` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `work_mobile_phone` `work_mobile_phone` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `work_fax` `work_fax` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_primary_phone` `home_primary_phone` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_mobile_phone` `home_mobile_phone` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_email` `home_email` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_address` `home_address` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_city` `home_city` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_state` `home_state` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_zip` `home_zip` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `home_country` `home_country` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `based` `based` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_records` CHANGE `note` `note` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `#__user_reset` CHANGE `expiry_time` `expiry_time` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_reset` CHANGE `token` `token` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_reset` CHANGE `reset_code` `reset_code` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__user_reset` CHANGE `reset_code_expiry_time` `reset_code_expiry_time` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

--
-- Convert expense, otherincome, refunds to new VAT/TAX system
--

ALTER TABLE `#__expense_records` CHANGE `type` `item_type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__expense_records` ADD `tax_system` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `date`;
ALTER TABLE `#__expense_records` ADD `vat_tax_code` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `net_amount`;

ALTER TABLE `#__otherincome_records` CHANGE `type` `item_type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__otherincome_records` ADD `tax_system` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `date`;
ALTER TABLE `#__otherincome_records` ADD `vat_tax_code` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `net_amount`;

ALTER TABLE `#__voucher_records` ADD `tax_system` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `blocked`;
ALTER TABLE `#__voucher_records` ADD `type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `tax_system`;
ALTER TABLE `#__voucher_records` CHANGE `amount` `unit_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__voucher_records` ADD `vat_tax_code` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `unit_net`;
ALTER TABLE `#__voucher_records` ADD `unit_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `vat_tax_code`;
ALTER TABLE `#__voucher_records` ADD `unit_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_tax_rate`;
ALTER TABLE `#__voucher_records` ADD `unit_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_tax`;
ALTER TABLE `#__voucher_records` ADD `sales_tax_exempt` INT(1) NOT NULL DEFAULT '0' AFTER `unit_net`;
UPDATE `#__voucher_records` SET `unit_gross` = `unit_net`;

--
-- Add status column to allow for the new payment system (supplier is for futureproofing and not payments)
--

ALTER TABLE `#__expense_records` ADD `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `gross_amount`;
ALTER TABLE `#__otherincome_records` ADD `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `gross_amount`;
ALTER TABLE `#__supplier_records` ADD `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `country`;

--
-- Add employee_id to various components
--

ALTER TABLE `#__expense_records` ADD `employee_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `expense_id`;
ALTER TABLE `#__otherincome_records` ADD `employee_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `otherincome_id`;
ALTER TABLE `#__supplier_records` ADD `employee_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `supplier_id`;

--
-- Add balance and last_active for the new Payment system
--

ALTER TABLE `#__expense_records` ADD `balance` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `gross_amount`;
ALTER TABLE `#__otherincome_records` ADD `balance` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `gross_amount`;
ALTER TABLE `#__expense_records` ADD `last_active` DATETIME NOT NULL AFTER `balance`;
ALTER TABLE `#__otherincome_records` ADD `last_active` DATETIME NOT NULL AFTER `balance`;

--
-- Add new status to Workorders
--

INSERT INTO `#__workorder_statuses` (`id`, `status_key`, `display_name`) VALUES ('10', 'deleted', 'Deleted');

--
-- Add last active to payment records
--

ALTER TABLE `#__payment_records` ADD `last_active` DATETIME NOT NULL AFTER `amount`;
UPDATE `#__payment_records` SET `last_active` = `date`;

--
-- Change columns to match new payment system
--

ALTER TABLE `#__expense_records` CHANGE `net_amount` `unit_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__expense_records` CHANGE `vat_rate` `unit_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__expense_records` CHANGE `vat_amount` `unit_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__expense_records` CHANGE `gross_amount` `unit_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00';

ALTER TABLE `#__invoice_records` CHANGE `sales_tax_rate` `sales_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `net_amount`;
ALTER TABLE `#__invoice_records` DROP `sub_total`;
ALTER TABLE `#__invoice_records` CHANGE `discount_rate` `unit_discount_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00' AFTER `tax_system`;
ALTER TABLE `#__invoice_records` CHANGE `discount_amount` `unit_discount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `unit_discount_rate`;
ALTER TABLE `#__invoice_records` CHANGE `net_amount` `unit_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_records` CHANGE `tax_amount` `unit_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_records` CHANGE `gross_amount` `unit_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__invoice_records` CHANGE `paid_amount` `unit_paid` DECIMAL(10,2) NOT NULL DEFAULT '0.00';

ALTER TABLE `#__invoice_prefill_items` CHANGE `net_amount` `unit_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__client_records` CHANGE `discount_rate` `unit_discount_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00';

--
-- Misc corrections compare develop_qwcrm (Source) --> develop_blank (Target) (Operations are performed on the Target to match the source)
--

ALTER TABLE `#__invoice_labour` MODIFY COLUMN `tax_system` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `invoice_id`;
ALTER TABLE `#__invoice_labour` MODIFY COLUMN `sales_tax_exempt` int(1) NOT NULL DEFAULT 0 AFTER `unit_net`;
ALTER TABLE `#__invoice_labour` MODIFY COLUMN `unit_tax_rate` decimal(4, 2) NOT NULL DEFAULT 0.00 AFTER `vat_tax_code`;
ALTER TABLE `#__invoice_parts` MODIFY COLUMN `tax_system` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `invoice_id`;
ALTER TABLE `#__invoice_parts` MODIFY COLUMN `sales_tax_exempt` int(1) NOT NULL DEFAULT 0 AFTER `unit_net`;
ALTER TABLE `#__invoice_parts` MODIFY COLUMN `unit_tax_rate` decimal(4, 2) NOT NULL DEFAULT 0.00 AFTER `vat_tax_code`;
ALTER TABLE `#__otherincome_records` CHANGE `net_amount` `unit_net` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__otherincome_records` CHANGE `vat_rate` `unit_tax_rate` DECIMAL(4,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__otherincome_records` CHANGE `vat_amount` `unit_tax` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__otherincome_records` CHANGE `gross_amount` `unit_gross` DECIMAL(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__payment_records` MODIFY COLUMN `voucher_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `invoice_id`;
ALTER TABLE `#__payment_records` MODIFY COLUMN `refund_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `voucher_id`;
ALTER TABLE `#__payment_records` MODIFY COLUMN `expense_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `refund_id`;
ALTER TABLE `#__payment_records` MODIFY COLUMN `otherincome_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `expense_id`;

--
-- Unify date columns on records
--

ALTER TABLE `#__client_records` CHANGE `create_date` `opened_on` INT(20) NOT NULL AFTER `client_id`;
ALTER TABLE `#__client_records` CHANGE `last_active` `last_active` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `opened_on`;
ALTER TABLE `#__client_records` ADD `closed_on` DATETIME NOT NULL AFTER `opened_on`;
ALTER TABLE `#__workorder_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `closed_by`;
ALTER TABLE `#__workorder_records` CHANGE `open_date` `opened_on` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__workorder_records` CHANGE `close_date` `closed_on` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_records` CHANGE `open_date` `opened_on` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_records` CHANGE `close_date` `closed_on` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__invoice_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `balance`;
ALTER TABLE `#__voucher_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `redeemed_invoice_id`;
ALTER TABLE `#__voucher_records` CHANGE `open_date` `opened_on` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `expiry_date` `expiry_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci AFTER `redeemed_invoice_id`;
ALTER TABLE `#__voucher_records` CHANGE `redeem_date` `redeemed_on` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `#__voucher_records` CHANGE `close_date` `closed_on` DATETIME NOT NULL;
ALTER TABLE `#__voucher_records` ADD `last_active` DATETIME NOT NULL AFTER `closed_on`;
ALTER TABLE `#__refund_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `balance`;
ALTER TABLE `#__refund_records` ADD `opened_on` DATETIME NOT NULL AFTER `status`;
ALTER TABLE `#__refund_records` ADD `closed_on` DATETIME NOT NULL AFTER `opened_on`;
ALTER TABLE `#__expense_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `balance`;
ALTER TABLE `#__expense_records` ADD `opened_on` DATETIME NOT NULL AFTER `status`;
ALTER TABLE `#__expense_records` ADD `closed_on` DATETIME NOT NULL AFTER `opened_on`;
ALTER TABLE `#__otherincome_records` CHANGE `status` `status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `balance`;
ALTER TABLE `#__otherincome_records` ADD `opened_on` DATETIME NOT NULL AFTER `status`;
ALTER TABLE `#__otherincome_records` ADD `closed_on` DATETIME NOT NULL AFTER `opened_on`;
ALTER TABLE `#__supplier_records` ADD `opened_on` DATETIME NOT NULL AFTER `status`;
ALTER TABLE `#__supplier_records` ADD `closed_on` DATETIME NOT NULL AFTER `opened_on`;
ALTER TABLE `#__supplier_records` ADD `last_active` DATETIME NOT NULL AFTER `closed_on`;