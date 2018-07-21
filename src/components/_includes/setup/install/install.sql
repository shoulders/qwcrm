-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2017 at 12:35 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qwcrm_install`
--

-- --------------------------------------------------------

--
-- Table structure for table `#__company_options`
--

CREATE TABLE `#__company_options` (
  `company_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `primary_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `company_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tax_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `vat_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `year_start` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `year_end` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `welcome_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `currency_symbol` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `date_format` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `opening_hour` int(2) NOT NULL DEFAULT '9',
  `opening_minute` int(2) NOT NULL DEFAULT '0',
  `closing_hour` int(2) NOT NULL DEFAULT '17',
  `closing_minute` int(2) NOT NULL DEFAULT '0',
  `email_signature` text COLLATE utf8_unicode_ci NOT NULL,
  `email_signature_active` int(1) NOT NULL,
  `email_msg_invoice` text COLLATE utf8_unicode_ci NOT NULL,
  `email_msg_workorder` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__company_options`
--

INSERT INTO `#__options` (`company_name`, `logo`, `address`, `city`, `state`, `zip`, `country`, `primary_phone`, `mobile_phone`, `fax`, `email`, `website`, `company_number`, `tax_type`, `tax_rate`, `vat_number`, `year_start`, `year_end`, `welcome_msg`, `currency_symbol`, `currency_code`, `date_format`, `opening_hour`, `opening_minute`, `closing_hour`, `closing_minute`, `email_signature`, `email_signature_active`, `email_msg_invoice`, `email_msg_workorder`) VALUES
('', 'media/logo.png', '', '', '', '', '', '', '', '', '', '', '', '', '0.00', '', '', '', '<p>Welcome to QWcrm - The Best Open Source Repairs Business CRM program available!</p>\r\n<p>CRM, Customer Relations Management, Work Orders, Invoicing, Billing, Payment Processing, Simple to use.</p>\r\n<p>This message is shown to everyone when they log in and can be changed in the company settings.</p>', '', '', '%d/%m/%Y', 10, 0, 17, 0, '<p>{logo}</p>\n<p>QuantumWarp</p>\n<p><strong>Address:</strong><br />QWcrm House<br />Easy Street<br />London<br />SW1A 1AA</p>\n<p><strong>Tel:</strong> 07777 123456<br /><strong>Web:</strong> <a href="https://quantumwarp.com/">quantumwarp.com</a></p>', 1, '<p>Hi {customer_first_name} {customer_last_name}</p>\r\n<p>This is an invoice for the recent work at {customer_company}.</p>\r\n<p>Thanks for your custom.</p>', '<p>There is currently no message here.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__customer_records`
--

CREATE TABLE `#__customer_records` (
  `customer_id` int(10) NOT NULL,
  `company_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `credit_terms` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `discount_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `primary_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `create_date` int(20) NOT NULL,
  `last_active` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__customer_notes`
--

CREATE TABLE `#__customer_notes` (
  `customer_note_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__customer_types`
--

CREATE TABLE `#__customer_types` (
  `customer_type_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__customer_types`
--

INSERT INTO `#__customer_types` (`customer_type_id`, `display_name`) VALUES
('charity', 'Charity'),
('commercial', 'Commercial'),
('educational', 'Educational'),
('goverment', 'Goverment'),
('residential', 'Residential');

-- --------------------------------------------------------

--
-- Table structure for table `#__expense_records`
--

CREATE TABLE `#__expense_records` (
  `expense_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `payee` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `items` text COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__expense_types`
--

CREATE TABLE `#__expense_types` (
  `expense_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__expense_types`
--

INSERT INTO `#__expense_types` (`expense_type_id`, `display_name`) VALUES
('bank_charges', 'Bank Charges'),
('credit', 'Credit'),
('customer_refund', 'Customer Refund'),
('equipment', 'Equipment'),
('fuel', 'Fuel'),
('gift_certificate', 'Gift Certificate'),
('marketing', 'Marketing'),
('office_supplies', 'Office Supplies'),
('online', 'Online'),
('other', 'Other'),
('parts', 'Parts'),
('postage', 'Postage'),
('rent', 'Rent'),
('services', 'Services'),
('software', 'Software'),
('telco', 'TelCo'),
('transport', 'Transport'),
('utilities', 'Utilities'),
('voucher', 'Voucher'),
('wages', 'Wages');

-- --------------------------------------------------------

--
-- Table structure for table `#__giftcert_records`
--

CREATE TABLE `#__giftcert_records` (
  `giftcert_id` int(10) NOT NULL,
  `giftcert_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `employee_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_expires` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_redeemed` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `redeemed` int(1) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',  
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_records`
--

CREATE TABLE `#__invoice_records` (
  `invoice_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `due_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `discount_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `tax_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `open_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `close_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_active` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `is_closed` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_labour`
--

CREATE TABLE `#__invoice_labour` (
  `invoice_labour_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qty` int(10) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_parts`
--

CREATE TABLE `#__invoice_parts` (
  `invoice_parts_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qty` int(10) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_prefill_items`
--

CREATE TABLE `#__invoice_prefill_items` (
  `invoice_prefill_id` int(10) NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__invoice_prefill_items`
--

INSERT INTO `#__invoice_prefill_items` (`invoice_prefill_id`, `description`, `type`, `amount`, `active`) VALUES
(1, 'Callout', 'Labour', '35.00', 1),
(2, 'Basic Labour', 'Labour', '20.00', 1),
(3, 'Virus Removal', 'Labour', '65.00', 1),
(4, 'PC Clean', 'Labour', '55.00', 1),
(5, 'Diagnostics', 'Labour', '100.00', 1),
(6, '3.0ghz 8400 CPU', 'Parts', '88.00', 1),
(7, 'Server', 'Parts', '999.00', 1),
(8, 'Hard Drive', 'Parts', '66.50', 1),
(9, 'SSD', 'Parts', '112.00', 1),
(10, 'RAM', 'Parts', '78.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_statuses`
--

CREATE TABLE `#__invoice_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__invoice_statuses`
--

INSERT INTO `#__invoice_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'pending', 'Pending'),
(2, 'unpaid', 'Unpaid'),
(3, 'partially_paid', 'Partially Paid'),
(4, 'paid', 'Paid'),
(5, 'in_dispute', 'In Dispute'),
(6, 'overdue', 'Overdue'),
(7, 'cancelled', 'Cancelled'),
(8, 'refunded', 'Refunded'),
(9, 'collections', 'Collections');

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_records`
--

CREATE TABLE `#__payment_records` (
  `payment_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_credit_cards`
--

CREATE TABLE `#__payment_credit_cards` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `card_key` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_credit_cards`
--

INSERT INTO `#__payment_credit_cards` (`id`, `card_key`, `display_name`, `active`) VALUES
(1, 'visa', 'Visa', 1),
(2, 'mastercard', 'MasterCard', 1),
(3, 'american_express', 'American Express', 1),
(4, 'debit_card', 'Debit Card', 1),
(5, 'other', 'Other', 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_purchase_methods`
--

CREATE TABLE `#__payment_purchase_methods` (
  `purchase_method_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_purchase_methods`
--

INSERT INTO `#__payment_purchase_methods` (`purchase_method_id`, `display_name`) VALUES
('bank_transfer', 'Bank Transfer'),
('card', 'Card'),
('cash', 'Cash'),
('cheque', 'Cheque'),
('credit', 'Credit'),
('direct_debit', 'Direct Debit'),
('gift_certificate', 'Gift Certificate'),
('google_checkout', 'Google Checkout'),
('other', 'Other'),
('paypal', 'PayPal'),
('voucher', 'Voucher');

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_options`
--

CREATE TABLE `#__payment_options` (
  `bank_account_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `bank_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `bank_account_number` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `bank_sort_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bank_iban` varchar(34) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_direct_deposit_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `invoice_cheque_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `invoice_footer_msg` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_options`
--

INSERT INTO `#__payment_options` (`bank_account_name`, `bank_name`, `bank_account_number`, `bank_sort_code`, `bank_iban`, `paypal_email`, `invoice_direct_deposit_msg`, `invoice_cheque_msg`, `invoice_footer_msg`) VALUES
('', '', '', '', '', '', '<p>Use your invoice number as the reference ...</p>\r\n<p>This message can be edited in payment options.</p>', '<p>Make cheques payable to ....</p>\r\n<p>This message can be edited in payment options.</p>', '<p>This is a footer message where you can put extra information ...</p>\r\n<p>This message can be edited in payment options.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_accepted_methods`
--

CREATE TABLE `#__payment_accepted_methods` (
  `accepted_method_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_accepted_methods`
--

INSERT INTO `#__payment_accepted_methods` (`accepted_method_id`, `display_name`, `active`) VALUES
('cash', 'Cash', 1),
('cheque', 'Cheque', 1),
('credit_card', 'Credit Card', 1),
('direct_deposit', 'Direct Deposit', 1),
('gift_certificate', 'Gift Certificate', 1),
('paypal', 'PayPal', 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__refund_records`
--

CREATE TABLE `#__refund_records` (
  `refund_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `payee` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `items` text COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__refund_types`
--

CREATE TABLE `#__refund_types` (
  `refund_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__refund_types`
--

INSERT INTO `#__refund_types` (`refund_type_id`, `display_name`) VALUES
('credit_note', 'Credit Note'),
('other', 'Other'),
('proxy_invoice', 'Proxy Invoice'),
('returned_goods', 'Returned Goods'),
('returned_services', 'Returned Services');

-- --------------------------------------------------------

--
-- Table structure for table `#__schedule_records`
--

CREATE TABLE `#__schedule_records` (
  `schedule_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` int(20) NOT NULL,
  `end_time` int(20) NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__session`
--

CREATE TABLE `#__session` (
  `session_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `clientid` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `guest` tinyint(4) UNSIGNED DEFAULT '1',
  `time` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `userid` int(11) DEFAULT '0',
  `username` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__supplier_records`
--

CREATE TABLE `#__supplier_records` (
  `supplier_id` int(10) NOT NULL,
  `company_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `primary_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__supplier_types`
--

CREATE TABLE `#__supplier_types` (
  `supplier_type_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__supplier_types`
--

INSERT INTO `#__supplier_types` (`supplier_type_id`, `display_name`) VALUES
('courier', 'Courier'),
('drop_shipping', 'Drop Shipping'),
('equipment', 'Equipment'),
('financial', 'Financial'),
('fuel', 'Fuel'),
('general', 'General'),
('human_resources', 'Human Resources'),
('landlord', 'Landlord'),
('marketing', 'Marketing'),
('office_supplies', 'Office Supplies'),
('online', 'Online'),
('other', 'Other'),
('parts', 'Parts'),
('services', 'Services'),
('software', 'Software'),
('telco', 'TelCo'),
('transport', 'Transport'),
('utilities', 'Utilities'),
('wholesale', 'Wholesale');

-- --------------------------------------------------------

--
-- Table structure for table `#__user_records`
--

CREATE TABLE `#__user_records` (
  `user_id` int(10) NOT NULL,
  `customer_id` varchar(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(50) NOT NULL,
  `usergroup` varchar(2) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `last_active` varchar(20) NOT NULL,
  `register_date` varchar(20) NOT NULL,
  `require_reset` int(1) NOT NULL DEFAULT '0' COMMENT 'Require user to reset password on next login',
  `last_reset_time` varchar(20) NOT NULL COMMENT 'Date of last password reset',
  `reset_count` int(10) NOT NULL DEFAULT '0' COMMENT 'Count of password resets since last_reset_time',
  `is_employee` int(1) NOT NULL DEFAULT '0',
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `work_primary_phone` varchar(20) NOT NULL,
  `work_mobile_phone` varchar(20) NOT NULL,
  `work_fax` varchar(20) NOT NULL,
  `home_primary_phone` varchar(20) NOT NULL,
  `home_mobile_phone` varchar(20) NOT NULL,
  `home_email` varchar(50) NOT NULL,
  `home_address` varchar(100) NOT NULL,
  `home_city` varchar(20) NOT NULL,
  `home_state` varchar(20) NOT NULL,
  `home_zip` varchar(20) NOT NULL,
  `home_country` varchar(50) NOT NULL,
  `based` int(1) NOT NULL DEFAULT '1',
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__user_acl_page`
--

CREATE TABLE `#__user_acl_page` (
  `page` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Administrator` int(1) NOT NULL DEFAULT '1',
  `Manager` int(1) NOT NULL DEFAULT '0',
  `Supervisor` int(1) NOT NULL DEFAULT '0',
  `Technician` int(1) NOT NULL DEFAULT '0',
  `Clerical` int(1) NOT NULL DEFAULT '0',
  `Counter` int(1) NOT NULL DEFAULT '0',
  `Customer` int(1) NOT NULL DEFAULT '0',
  `Guest` int(1) NOT NULL DEFAULT '0',
  `Public` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__user_acl_page`
--

INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Customer`, `Guest`, `Public`) VALUES
('administrator:acl', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:config', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:phpinfo', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:update', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('company:business_hours', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('company:options', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('core:403', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('core:404', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('core:dashboard', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('core:error', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('core:home', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('core:maintenance', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('customer:delete', 1, 1, 1, 0, 1, 0, 0, 0, 0),
('customer:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('customer:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('customer:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('customer:note_delete', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('customer:note_edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('customer:note_new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('customer:search', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('expense:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('giftcert:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('giftcert:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('giftcert:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('giftcert:new', 1, 1, 0, 0, 1, 1, 0, 0, 0),
('giftcert:print', 1, 1, 0, 0, 1, 1, 0, 0, 0),
('giftcert:search', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('giftcert:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('help:about', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('help:attribution', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('help:license', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:delete_labour', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('invoice:delete_parts', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('invoice:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('invoice:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:overview', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:prefill_items', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:print', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:search', 1, 1, 1, 0, 1, 1, 0, 0, 0),
('invoice:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:delete', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('payment:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('payment:options', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('report:basic_stats', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('report:financial', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('schedule:day', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:delete', 1, 1, 1, 0, 0, 0, 0, 0, 0),
('schedule:details', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:edit', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:icalendar', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:new', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:search', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('setup:choice', 0, 0, 0, 0, 0, 0, 0, 0, 0),
('setup:install', 0, 0, 0, 0, 0, 0, 0, 0, 0),
('setup:migrate', 0, 0, 0, 0, 0, 0, 0, 0, 0),
('setup:upgrade', 0, 0, 0, 0, 0, 0, 0, 0, 0),
('supplier:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:details', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('supplier:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:search', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('user:delete', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('user:details', 1, 1, 1, 0, 1, 0, 0, 0, 0),
('user:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('user:login', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('user:new', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('user:reset', 0, 0, 0, 0, 0, 0, 0, 0, 1),
('user:search', 1, 1, 1, 0, 1, 0, 0, 0, 0),
('workorder:autosuggest_scope', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:delete', 1, 1, 1, 0, 0, 0, 0, 0, 0),
('workorder:details', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:details_edit_comment', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:details_edit_description', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:details_edit_resolution', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:new', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:note_delete', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:note_edit', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:note_new', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:overview', 1, 1, 1, 0, 0, 0, 0, 0, 0),
('workorder:print', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:search', 1, 1, 1, 0, 0, 0, 0, 0, 0),
('workorder:status', 1, 1, 1, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `#__user_keys`
--

CREATE TABLE `#__user_keys` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uastring` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__user_reset`
--

CREATE TABLE `#__user_reset` (
  `user_id` varchar(2) NOT NULL,
  `expiry_time` varchar(20) NOT NULL,
  `token` varchar(64) NOT NULL,
  `reset_code` varchar(64) NOT NULL,
  `reset_code_expiry_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__user_usergroups`
--

CREATE TABLE `#__user_usergroups` (
  `usergroup_id` int(4) NOT NULL,
  `usergroup_display_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__user_usergroups`
--

INSERT INTO `#__user_usergroups` (`usergroup_id`, `usergroup_display_name`, `user_type`) VALUES
(1, 'Administrator', 1),
(2, 'Manager', 1),
(3, 'Supervisor', 1),
(4, 'Technician', 1),
(5, 'Clerical', 1),
(6, 'Counter', 1),
(7, 'Customer', 2),
(8, 'Guest', 3),
(9, 'Public', 3);

-- --------------------------------------------------------

--
-- Table structure for table `#__version`
--

CREATE TABLE `#__version` (
  `database_version` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__version`
--

INSERT INTO `#__version` (`database_version`) VALUES
('3.0.0');

-- --------------------------------------------------------

--
-- Table structure for table `#__workorder_records`
--

CREATE TABLE `#__workorder_records` (
  `workorder_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `closed_by` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `open_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `close_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_active` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `is_closed` int(1) NOT NULL,
  `scope` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `resolution` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__workorder_history`
--

CREATE TABLE `#__workorder_history` (
  `history_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__workorder_notes`
--

CREATE TABLE `#__workorder_notes` (
  `workorder_note_id` int(10) NOT NULL,
  `employee_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__workorder_statuses`
--

CREATE TABLE `#__workorder_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__workorder_statuses`
--

INSERT INTO `#__workorder_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unassigned', 'Unassigned'),
(2, 'assigned', 'Assigned'),
(3, 'waiting_for_parts', 'Waiting for Parts'),
(4, 'scheduled', 'Scheduled'),
(5, 'with_client', 'With Client'),
(6, 'on_hold', 'On Hold'),
(7, 'management', 'Management'),
(8, 'closed_without_invoice', 'Closed without Invoice'),
(9, 'closed_with_invoice', 'Closed with Invoice');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `#__company_options`
--
ALTER TABLE `#__company_options`
  ADD PRIMARY KEY (`display_name`);

--
-- Indexes for table `#__customer_records`
--
ALTER TABLE `#__customer_records`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `#__customer_notes`
--
ALTER TABLE `#__customer_notes`
  ADD PRIMARY KEY (`customer_note_id`);

--
-- Indexes for table `#__customer_types`
--
ALTER TABLE `#__customer_types`
  ADD PRIMARY KEY (`customer_type_id`);

--
-- Indexes for table `#__expense_records`
--
ALTER TABLE `#__expense_records`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `#__expense_types`
--
ALTER TABLE `#__expense_types`
  ADD PRIMARY KEY (`expense_type_id`);

--
-- Indexes for table `#__giftcert_records`
--
ALTER TABLE `#__giftcert_records`
  ADD PRIMARY KEY (`giftcert_id`);

--
-- Indexes for table `#__invoice_records`
--
ALTER TABLE `#__invoice_records`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `#__invoice_labour`
--
ALTER TABLE `#__invoice_labour`
  ADD PRIMARY KEY (`invoice_labour_id`);

--
-- Indexes for table `#__invoice_parts`
--
ALTER TABLE `#__invoice_parts`
  ADD PRIMARY KEY (`invoice_parts_id`);

--
-- Indexes for table `#__invoice_prefill_items`
--
ALTER TABLE `#__invoice_prefill_items`
  ADD PRIMARY KEY (`invoice_prefill_id`);

--
-- Indexes for table `#__invoice_statuses`
--
ALTER TABLE `#__invoice_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_records`
--
ALTER TABLE `#__payment_records`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `#__payment_credit_cards`
--
ALTER TABLE `#__payment_credit_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_purchase_methods`
--
ALTER TABLE `#__payment_purchase_methods`
  ADD PRIMARY KEY (`purchase_method_id`);

--
-- Indexes for table `#__payment_options`
--
ALTER TABLE `#__payment_options`
  ADD PRIMARY KEY (`bank_account_name`);

--
-- Indexes for table `#__payment_accepted_methods`
--
ALTER TABLE `#__payment_accepted_methods`
  ADD PRIMARY KEY (`accepted_method_id`);

--
-- Indexes for table `#__refund_records`
--
ALTER TABLE `#__refund_records`
  ADD PRIMARY KEY (`refund_id`);

--
-- Indexes for table `#__refund_types`
--
ALTER TABLE `#__refund_types`
  ADD PRIMARY KEY (`refund_type_id`);

--
-- Indexes for table `#__schedule_records`
--
ALTER TABLE `#__schedule_records`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `#__session`
--
ALTER TABLE `#__session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `#__supplier_records`
--
ALTER TABLE `#__supplier_records`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `#__supplier_types`
--
ALTER TABLE `#__supplier_types`
  ADD PRIMARY KEY (`supplier_type_id`);

--
-- Indexes for table `#__user_records`
--
ALTER TABLE `#__user_records`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `#__user_acl_page`
--
ALTER TABLE `#__user_acl_page`
  ADD PRIMARY KEY (`page`);

--
-- Indexes for table `#__user_keys`
--
ALTER TABLE `#__user_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `series` (`series`),
  ADD UNIQUE KEY `series_2` (`series`),
  ADD UNIQUE KEY `series_3` (`series`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `#__user_reset`
--
ALTER TABLE `#__user_reset`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `#__user_usergroups`
--
ALTER TABLE `#__user_usergroups`
  ADD PRIMARY KEY (`usergroup_id`);

--
-- Indexes for table `#__version`
--
ALTER TABLE `#__version`
  ADD PRIMARY KEY (`database_version`);

--
-- Indexes for table `#__workorder_records`
--
ALTER TABLE `#__workorder_records`
  ADD PRIMARY KEY (`workorder_id`);

--
-- Indexes for table `#__workorder_history`
--
ALTER TABLE `#__workorder_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `#__workorder_notes`
--
ALTER TABLE `#__workorder_notes`
  ADD PRIMARY KEY (`workorder_note_id`);

--
-- Indexes for table `#__workorder_statuses`
--
ALTER TABLE `#__workorder_statuses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `#__customer_records`
--
ALTER TABLE `#__customer_records`
  MODIFY `customer_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__customer_notes`
--
ALTER TABLE `#__customer_notes`
  MODIFY `customer_note_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__expense_records`
--
ALTER TABLE `#__expense_records`
  MODIFY `expense_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__giftcert_records`
--
ALTER TABLE `#__giftcert_records`
  MODIFY `giftcert_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__invoice_records`
--
ALTER TABLE `#__invoice_records`
  MODIFY `invoice_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__invoice_labour`
--
ALTER TABLE `#__invoice_labour`
  MODIFY `invoice_labour_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__invoice_parts`
--
ALTER TABLE `#__invoice_parts`
  MODIFY `invoice_parts_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__invoice_prefill_items`
--
ALTER TABLE `#__invoice_prefill_items`
  MODIFY `invoice_prefill_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `#__payment_records`
--
ALTER TABLE `#__payment_records`
  MODIFY `payment_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__refund_records`
--
ALTER TABLE `#__refund_records`
  MODIFY `refund_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__schedule_records`
--
ALTER TABLE `#__schedule_records`
  MODIFY `schedule_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__supplier_records`
--
ALTER TABLE `#__supplier_records`
  MODIFY `supplier_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__user_records`
--
ALTER TABLE `#__user_records`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__user_keys`
--
ALTER TABLE `#__user_keys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__user_usergroups`
--
ALTER TABLE `#__user_usergroups`
  MODIFY `usergroup_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `#__workorder_records`
--
ALTER TABLE `#__workorder_records`
  MODIFY `workorder_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__workorder_history`
--
ALTER TABLE `#__workorder_history`
  MODIFY `history_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__workorder_notes`
--
ALTER TABLE `#__workorder_notes`
  MODIFY `workorder_note_id` int(10) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
