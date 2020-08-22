/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2018 at 04:55 PM
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
-- Table structure for table `#__client_notes`
--

CREATE TABLE `#__client_notes` (
  `client_note_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__client_records`
--

CREATE TABLE `#__client_records` (
  `client_id` int(10) NOT NULL,
  `opened_on` datetime NOT NULL,
  `closed_on` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `company_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `credit_terms` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `unit_discount_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
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
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__client_types`
--

CREATE TABLE `#__client_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__client_types`
--

INSERT INTO `#__client_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'residential', 'Residential'),
(2, 'commercial', 'Commercial'),
(3, 'charity', 'Charity'),
(4, 'educational', 'Educational'),
(5, 'goverment', 'Goverment');

-- --------------------------------------------------------

--
-- Table structure for table `#__company_date_formats`
--

CREATE TABLE `#__company_date_formats` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `date_format_key` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__company_date_formats`
--

INSERT INTO `#__company_date_formats` (`id`, `date_format_key`, `display_name`) VALUES
(1, '%d/%m/%Y', 'dd/mm/yyyy'),
(2, '%m/%d/%Y', 'mm/dd/yyyy'),
(3, '%d/%m/%y', 'dd/mm/yy'),
(4, '%m/%d/%y', 'mm/dd/yy'),
(5, '%Y-%m-%d', 'yyyy-mm-dd');

-- --------------------------------------------------------

--
-- Table structure for table `#__company_record`
--

CREATE TABLE `#__company_record` (
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
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sales_tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `vat_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `vat_flat_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `year_start` date NOT NULL,
  `year_end` date NOT NULL,
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
  `email_msg_workorder` text COLLATE utf8_unicode_ci NOT NULL,
  `email_msg_invoice` text COLLATE utf8_unicode_ci NOT NULL,
  `email_msg_voucher` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__company_record`
--

INSERT INTO `#__company_record` (`company_name`, `logo`, `address`, `city`, `state`, `zip`, `country`, `primary_phone`, `mobile_phone`, `fax`, `email`, `website`, `company_number`, `tax_system`, `sales_tax_rate`, `vat_number`, `vat_flat_rate`, `year_start`, `year_end`, `welcome_msg`, `currency_symbol`, `currency_code`, `date_format`, `opening_hour`, `opening_minute`, `closing_hour`, `closing_minute`, `email_signature`, `email_signature_active`, `email_msg_invoice`, `email_msg_workorder`) VALUES
('', 'qw-logo.png', '', '', '', '', '', '', '', '', '', '', '', 'no_tax', '0.00', '', '0.00', '0000-00-00', '0000-00-00', '<p>Welcome to QWcrm - The Best Open Source Repairs Business CRM program available!</p>\r\n<p>CRM, Customer Relations Management, Work Orders, Invoicing, Billing, Payment Processing, Simple to use.</p>\r\n<p>This message is shown to everyone when they log in and can be changed in the company settings.</p>', '&pound;', 'GBP', '%Y-%m-%d', 10, 0, 17, 0, '<p>{company_logo}</p>\r\n<p><strong>{company_name}</strong></p>\r\n<p><strong>Address:</strong> <br />{company_address}</p>\r\n<p><strong>Tel:</strong> {company_telephone} <br /><strong>Website:</strong> {company_website}</p>', 1, '<p>There is currently no message here for Work Orders.</p>', '<p>Hi {client_display_name}</p>\r\n<p>This is an invoice for the recent work at carried out by {company_name}.</p>\r\n<p>Thanks for your custom.</p>', '<p>Hi {client_display_name}</p>\r\n<p>This is a voucher from {company_name} which is redeemable against our services and products.</p>\r\n<p><em><strong>Terms and conditions apply.</strong></em></p>\r\n<p>Thanks for your custom.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__company_tax_systems`
--

CREATE TABLE `#__company_tax_systems` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__company_tax_systems`
--

INSERT INTO `#__company_tax_systems` (`id`, `type_key`, `display_name`) VALUES
(1, 'no_tax', 'No Tax'),
(2, 'sales_tax_cash', 'Sales Tax (Cash Basis)'),
(3, 'vat_standard', 'VAT Standard Accounting (UK)'),
(4, 'vat_cash', 'VAT Cash Accounting (UK)'),
(5, 'vat_flat_basic', 'VAT Flat Rate (Basic Turnover) (UK)'),
(6, 'vat_flat_cash', 'VAT Flat Rate (Cash Based Turnover) (UK)');

-- --------------------------------------------------------

--
-- Table structure for table `#__company_vat_tax_codes`
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

--
-- Dumping data for table `#__company_vat_tax_codes`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `#__cronjob_records`
--

CREATE TABLE `#__cronjob_records` (
  `cronjob_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `pseudo_allowed` int(1) NOT NULL DEFAULT 1,
  `default_settings` text COLLATE utf8_unicode_ci NOT NULL,
  `last_run_time` datetime NOT NULL,
  `last_run_status` int(1) NOT NULL DEFAULT 0,
  `locked` int(1) NOT NULL DEFAULT 0,
  `minute` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `hour` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `day` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `month` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `weekday` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `command` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__cronjob_records`
--

INSERT INTO `#__cronjob_records` (`cronjob_id`, `name`, `description`, `active`, `pseudo_allowed`, `default_settings`, `last_run_time`, `last_run_status`, `locked`, `minute`, `hour`, `day`, `month`, `weekday`, `command`) VALUES
(1, 'Test Cron', '<p>This cronjob is designed to check the basic functionality of the cronjob system. When enabled it will send an email every 15 minutes from QWcrm to the configured company email address. You can also run the cronjob manually to test immediately.</p>', 0, 1, '{\"active\":\"0\",\"pseudo_allowed\":\"1\",\"minute\":\"*\\/15\",\"hour\":\"*\",\"day\":\"*\",\"month\":\"*\",\"weekday\":\"*\"}', '0000-00-00 00:00:00', 1, 0, '*/15', '*', '*', '*', '*', '{\"class\":\"Cronjob\",\"function\":\"cronjobTest\"}');

-- --------------------------------------------------------

--
-- Table structure for table `#__cronjob_system`
--

CREATE TABLE `#__cronjob_system` (
  `last_run_time` datetime NOT NULL,
  `last_run_status` int(1) NOT NULL DEFAULT 0,
  `locked` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__cronjob_system`
--

INSERT INTO `#__cronjob_system` (`last_run_time`, `last_run_status`, `locked`) VALUES
('0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `#__expense_records`
--

CREATE TABLE `#__expense_records` (
  `expense_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,  
  `payee` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,  
  `item_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `unit_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_tax_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `unit_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_gross` decimal(10,2) NOT NULL DEFAULT '0.00',  
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `opened_on` datetime NOT NULL,
  `closed_on` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `items` text COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__expense_statuses`
--

CREATE TABLE `#__expense_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__expense_statuses`
--

INSERT INTO `#__expense_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unpaid', 'Unpaid'),
(2, 'partially_paid', 'Partially Paid'),
(3, 'paid', 'Paid'),
(4, 'cancelled', 'Cancelled'),
(5, 'deleted', 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `#__expense_types`
--

CREATE TABLE `#__expense_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__expense_types`
--

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
(14, 'royalties', 'Royalties'),
(15, 'services', 'Services'),
(16, 'software', 'Software'),
(17, 'telco', 'TelCo'),
(18, 'transport', 'Transport'),
(19, 'utilities', 'Utilities'),
(20, 'voucher', 'Voucher'),
(21, 'wages', 'Wages');

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_labour`
--

CREATE TABLE `#__invoice_labour` (
  `invoice_labour_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `unit_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sales_tax_exempt` INT(1) NOT NULL DEFAULT '0',
  `vat_tax_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,  
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `unit_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_gross` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal_gross` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_parts`
--

CREATE TABLE `#__invoice_parts` (
  `invoice_parts_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `unit_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sales_tax_exempt` INT(1) NOT NULL DEFAULT '0',
  `vat_tax_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `unit_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_gross` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal_gross` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_prefill_items`
--

CREATE TABLE `#__invoice_prefill_items` (
  `invoice_prefill_id` int(10) NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `unit_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__invoice_prefill_items`
--

INSERT INTO `#__invoice_prefill_items` (`invoice_prefill_id`, `description`, `type`, `unit_net`, `active`) VALUES
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
-- Table structure for table `#__invoice_records`
--

CREATE TABLE `#__invoice_records` (
  `invoice_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `refund_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `due_date` date NOT NULL,
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unit_discount_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `unit_discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sales_tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `unit_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_gross` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `opened_on` datetime NOT NULL,
  `closed_on` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `is_closed` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(7, 'collections', 'Collections'),
(8, 'refunded', 'Refunded'),
(9, 'cancelled', 'Cancelled'),
(10, 'deleted', 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `#__otherincome_records`
--

CREATE TABLE `#__otherincome_records` (
  `otherincome_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `payee` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,  
  `item_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `unit_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_tax_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `unit_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_gross` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `opened_on` datetime NOT NULL,
  `closed_on` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `items` text COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__otherincome_statuses`
--

CREATE TABLE `#__otherincome_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__otherincome_statuses`
--

INSERT INTO `#__otherincome_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unpaid', 'Unpaid'),
(2, 'partially_paid', 'Partially Paid'),
(3, 'paid', 'Paid'),
(4, 'cancelled', 'Cancelled'),
(5, 'deleted', 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `#__otherincome_types`
--

CREATE TABLE `#__otherincome_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__otherincome_types`
--

INSERT INTO `#__otherincome_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'cancelled_services', 'Cancelled Services'),
(2, 'commission', 'Commission'),
(3, 'credit_note', 'Credit Note'),
(4, 'interest', 'Interest'),
(5, 'other', 'Other'),
(6, 'returned_goods', 'Returned Goods'),
(7, 'royalties', 'Royalties'),
(8, 'tips', 'Tips');

--
-- Table structure for table `#__payment_additional_info_types`
--

CREATE TABLE `#__payment_additional_info_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_additional_info_types`
--

INSERT INTO `#__payment_additional_info_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'bank_transfer_reference', 'Bank Transfer Reference'),
(2, 'card_type_key', 'Card Type'),
(3, 'name_on_card', 'Name on Card'),
(4, 'cheque_number', 'Cheque Number'),
(5, 'direct_debit_reference', 'Direct Debit Reference'),
(6, 'paypal_transaction_id', 'PayPal Transaction ID');

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_card_types`
--

CREATE TABLE `#__payment_card_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_card_types`
--

INSERT INTO `#__payment_card_types` (`id`, `type_key`, `display_name`, `active`) VALUES
(1, 'visa', 'Visa', 1),
(2, 'mastercard', 'MasterCard', 1),
(3, 'american_express', 'American Express', 1),
(4, 'debit_card', 'Debit Card', 1),
(5, 'other', 'Other', 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_methods`
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

--
-- Dumping data for table `#__payment_methods`
--

INSERT INTO `#__payment_methods` (`id`, `method_key`, `display_name`, `send`, `receive`, `send_protected`, `receive_protected`, `enabled`) VALUES
(1, 'bank_transfer', 'Bank Transfer', 1, 1, 0, 0, 0),
(2, 'card', 'Card', 1, 1, 0, 0, 0),
(3, 'cash', 'Cash', 1, 1, 0, 0, 1),
(4, 'cheque', 'Cheque', 1, 1, 0, 0, 0),
(5, 'direct_debit', 'Direct Debit', 1, 1, 0, 0, 0),
(6, 'other', 'Other', 0, 1, 0, 0, 0),
(7, 'paypal', 'PayPal', 1, 1, 0, 0, 0),
(8, 'voucher', 'Voucher', 0, 1, 1, 1, 0);

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
  `invoice_bank_transfer_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `invoice_cheque_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `invoice_footer_msg` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_options`
--

INSERT INTO `#__payment_options` (`bank_account_name`, `bank_name`, `bank_account_number`, `bank_sort_code`, `bank_iban`, `paypal_email`, `invoice_bank_transfer_msg`, `invoice_cheque_msg`, `invoice_footer_msg`) VALUES
('', '', '', '', '', '', '<p>Use your invoice number as the reference ...</p>\r\n<p>This message can be edited in payment options.</p>', '<p>Make cheques payable to ....</p>\r\n<p>This message can be edited in payment options.</p>', '<p>This is a footer message where you can put extra information ...</p>\r\n<p>This message can be edited in payment options.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_records`
--

CREATE TABLE `#__payment_records` (
  `payment_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `voucher_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `refund_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `expense_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `otherincome_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `last_active` datetime NOT NULL,
  `additional_info` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,  
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_statuses`
--

CREATE TABLE `#__payment_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_statuses`
--

INSERT INTO `#__payment_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'valid', 'Valid'),
(2, 'cancelled', 'Cancelled'),
(3, 'deleted', 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_types`
--

CREATE TABLE `#__payment_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_types`
--

INSERT INTO `#__payment_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'invoice', 'Invoice'),
(2, 'refund', 'Refund'),
(3, 'expense', 'Expense'),
(4, 'otherincome', 'Other Income');

-- --------------------------------------------------------

--
-- Table structure for table `#__refund_records`
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
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `opened_on` datetime NOT NULL,
  `closed_on` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__refund_statuses`
--

CREATE TABLE `#__refund_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__refund_statuses`
--

INSERT INTO `#__refund_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unpaid', 'Unpaid'),
(2, 'partially_paid', 'Partially Paid'),
(3, 'paid', 'Paid'),
(4, 'cancelled', 'Cancelled'),
(5, 'deleted', 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `#__refund_types`
--

CREATE TABLE `#__refund_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__refund_types`
--

INSERT INTO `#__refund_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'invoice', 'Invoice'),
(2, 'cash_purchase', 'Cash Purchase');

-- --------------------------------------------------------

--
-- Table structure for table `#__schedule_records`
--

CREATE TABLE `#__schedule_records` (
  `schedule_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__session`
--

CREATE TABLE `#__session` (
  `session_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `clientid` tinyint(3) UNSIGNED DEFAULT NULL,
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
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
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
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `opened_on` datetime NOT NULL,
  `closed_on` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__supplier_statuses`
--

CREATE TABLE `#__supplier_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__supplier_statuses`
--

INSERT INTO `#__supplier_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'valid', 'Valid'),
(2, 'cancelled', 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `#__supplier_types`
--

CREATE TABLE `#__supplier_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__supplier_types`
--

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
  `Client` int(1) NOT NULL DEFAULT '0',
  `Guest` int(1) NOT NULL DEFAULT '0',
  `Public` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__user_acl_page`
--

INSERT INTO `#__user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES
('administrator:acl', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:config', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:phpinfo', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:update', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('client:delete', 1, 1, 1, 0, 1, 0, 0, 0, 0),
('client:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('client:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('client:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('client:note_delete', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('client:note_edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('client:note_new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('client:search', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('company:business_hours', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('company:edit', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('core:403', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('core:404', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('core:dashboard', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('core:error', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('core:home', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('core:maintenance', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('cronjob:details', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('cronjob:edit', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('cronjob:overview', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('cronjob:run', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('cronjob:unlock', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('expense:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('expense:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('help:about', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('help:attribution', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('help:license', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('invoice:email', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:overview', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:prefill_items', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:print', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:search', 1, 1, 1, 0, 1, 1, 0, 0, 0),
('invoice:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
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
('payment:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('payment:options', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('payment:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('refund:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('report:basic_stats', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('report:financial', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('schedule:day', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:delete', 1, 1, 1, 0, 0, 0, 0, 0, 0),
('schedule:details', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:edit', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:icalendar', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:new', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('schedule:search', 1, 1, 1, 1, 0, 0, 0, 0, 0),
('setup:choice', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('setup:install', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('setup:migrate', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('setup:upgrade', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('supplier:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:details', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('supplier:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('supplier:search', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('supplier:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('user:delete', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('user:details', 1, 1, 1, 0, 1, 0, 0, 0, 0),
('user:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('user:login', 1, 1, 1, 1, 1, 1, 1, 1, 1),
('user:new', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('user:reset', 0, 0, 0, 0, 0, 0, 0, 0, 1),
('user:search', 1, 1, 1, 0, 1, 0, 0, 0, 0),
('voucher:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('voucher:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('voucher:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('voucher:new', 1, 1, 0, 0, 1, 1, 0, 0, 0),
('voucher:print', 1, 1, 0, 0, 1, 1, 0, 0, 0),
('voucher:search', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('voucher:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
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
-- Table structure for table `#__user_locations`
--

CREATE TABLE `#__user_locations` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `location_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__user_locations`
--

INSERT INTO `#__user_locations` (`id`, `location_key`, `display_name`) VALUES
(1, 'office', 'Office'),
(2, 'onsite', 'OnSite'),
(3, 'home', 'Home');

-- --------------------------------------------------------

--
-- Table structure for table `#__user_records`
--

CREATE TABLE `#__user_records` (
  `user_id` int(10) NOT NULL,
  `client_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usergroup` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `last_active` datetime NOT NULL,
  `register_date` datetime NOT NULL,
  `require_reset` int(1) NOT NULL DEFAULT '0' COMMENT 'Require user to reset password on next login',
  `last_reset_time` datetime NOT NULL COMMENT 'Date of last password reset',
  `reset_count` int(10) NOT NULL DEFAULT '0' COMMENT 'Count of password resets since last_reset_time',
  `is_employee` int(1) NOT NULL DEFAULT '0',
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `work_primary_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `work_mobile_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `work_fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `home_primary_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `home_mobile_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `home_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `home_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `home_city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `home_state` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `home_zip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `home_country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `based` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__user_reset`
--

CREATE TABLE `#__user_reset` (
  `user_id` int(10) NOT NULL,
  `expiry_time` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `reset_code` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `reset_code_expiry_time` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__user_usergroups`
--

CREATE TABLE `#__user_usergroups` (
  `usergroup_id` int(4) NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__user_usergroups`
--

INSERT INTO `#__user_usergroups` (`usergroup_id`, `display_name`, `user_type`) VALUES
(1, 'Administrator', 1),
(2, 'Manager', 1),
(3, 'Supervisor', 1),
(4, 'Technician', 1),
(5, 'Clerical', 1),
(6, 'Counter', 1),
(7, 'Client', 2),
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
('3.1.4');

-- --------------------------------------------------------

--
-- Table structure for table `#__voucher_records`
--

CREATE TABLE `#__voucher_records` (
  `voucher_id` int(10) NOT NULL,
  `voucher_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `employee_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payment_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `refund_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `redeemed_client_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `redeemed_invoice_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `expiry_date` date NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `opened_on` datetime NOT NULL,
  `redeemed_on` datetime NOT NULL,
  `closed_on` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `blocked` int(1) NOT NULL DEFAULT '0',
  `tax_system` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `unit_net` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sales_tax_exempt` int(1) NOT NULL DEFAULT '0',
  `vat_tax_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unit_tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `unit_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_gross` decimal(10,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__voucher_statuses`
--

CREATE TABLE `#__voucher_statuses` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `status_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__voucher_statuses`
--

INSERT INTO `#__voucher_statuses` (`id`, `status_key`, `display_name`) VALUES
(1, 'unused', 'Unused'),
(2, 'redeemed', 'Redeemed'),
(3, 'suspended', 'Suspended'),
(4, 'expired', 'Expired'),
(5, 'refunded', 'Refunded'),
(6, 'cancelled', 'Cancelled'),
('7', 'deleted', 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `#__voucher_types`
--

CREATE TABLE `#__voucher_types` (
  `id` int(10) NOT NULL COMMENT 'only for display order',
  `type_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__voucher_types`
--

INSERT INTO `#__voucher_types` (`id`, `type_key`, `display_name`) VALUES
(1, 'MPV', 'Multi Purpose (MPV)'),
(2, 'SPV', 'Single Purpose (SPV)');

-- --------------------------------------------------------

--
-- Table structure for table `#__workorder_history`
--

CREATE TABLE `#__workorder_history` (
  `history_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
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
  `date` datetime NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__workorder_records`
--

CREATE TABLE `#__workorder_records` (
  `workorder_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `closed_by` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `opened_on` datetime NOT NULL,
  `closed_on` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `is_closed` int(1) NOT NULL,
  `scope` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `resolution` text COLLATE utf8_unicode_ci NOT NULL
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
(9, 'closed_with_invoice', 'Closed with Invoice'),
(10, 'deleted', 'Deleted');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `#__client_notes`
--
ALTER TABLE `#__client_notes`
  ADD PRIMARY KEY (`client_note_id`);

--
-- Indexes for table `#__client_records`
--
ALTER TABLE `#__client_records`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `#__client_types`
--
ALTER TABLE `#__client_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__company_date_formats`
--
ALTER TABLE `#__company_date_formats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__company_record`
--
ALTER TABLE `#__company_record`
  ADD PRIMARY KEY (`company_name`);

--
-- Indexes for table `#__company_tax_systems`
--
ALTER TABLE `#__company_tax_systems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__company_vat_tax_codes`
--
ALTER TABLE `#__company_vat_tax_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__cronjob_records`
--
ALTER TABLE `#__cronjob_records`
  ADD PRIMARY KEY (`cronjob_id`);

--
-- Indexes for table `#__cronjob_system`
--
ALTER TABLE `#__cronjob_system`
  ADD PRIMARY KEY (`last_run_time`);

--
-- Indexes for table `#__expense_records`
--
ALTER TABLE `#__expense_records`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `#__expense_statuses`
--
ALTER TABLE `#__expense_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__expense_types`
--
ALTER TABLE `#__expense_types`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `#__invoice_records`
--
ALTER TABLE `#__invoice_records`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `#__invoice_statuses`
--
ALTER TABLE `#__invoice_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__otherincome_records`
--
ALTER TABLE `#__otherincome_records`
  ADD PRIMARY KEY (`otherincome_id`);

--
-- Indexes for table `#__otherincome_statuses`
--
ALTER TABLE `#__otherincome_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__otherincome_types`
--
ALTER TABLE `#__otherincome_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_additional_info_types`
--
ALTER TABLE `#__payment_additional_info_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_card_types`
--
ALTER TABLE `#__payment_card_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_methods`
--
ALTER TABLE `#__payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_options`
--
ALTER TABLE `#__payment_options`
  ADD PRIMARY KEY (`bank_account_name`);

--
-- Indexes for table `#__payment_records`
--
ALTER TABLE `#__payment_records`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `#__payment_statuses`
--
ALTER TABLE `#__payment_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_types`
--
ALTER TABLE `#__payment_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__refund_records`
--
ALTER TABLE `#__refund_records`
  ADD PRIMARY KEY (`refund_id`);

--
-- Indexes for table `#__refund_statuses`
--
ALTER TABLE `#__refund_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__refund_types`
--
ALTER TABLE `#__refund_types`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `#__supplier_statuses`
--
ALTER TABLE `#__supplier_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__supplier_types`
--
ALTER TABLE `#__supplier_types`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `#__user_locations`
--
ALTER TABLE `#__user_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__user_records`
--
ALTER TABLE `#__user_records`
  ADD PRIMARY KEY (`user_id`);

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
-- Indexes for table `#__voucher_records`
--
ALTER TABLE `#__voucher_records`
  ADD PRIMARY KEY (`voucher_id`);

--
-- Indexes for table `#__voucher_types`
--
ALTER TABLE `#__voucher_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__voucher_statuses`
--
ALTER TABLE `#__voucher_statuses`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `#__workorder_records`
--
ALTER TABLE `#__workorder_records`
  ADD PRIMARY KEY (`workorder_id`);

--
-- Indexes for table `#__workorder_statuses`
--
ALTER TABLE `#__workorder_statuses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `#__client_notes`
--
ALTER TABLE `#__client_notes`
  MODIFY `client_note_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__client_records`
--
ALTER TABLE `#__client_records`
  MODIFY `client_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__expense_records`
--
ALTER TABLE `#__expense_records`
  MODIFY `expense_id` int(10) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `#__invoice_records`
--
ALTER TABLE `#__invoice_records`
  MODIFY `invoice_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__otherincome_records`
--
ALTER TABLE `#__otherincome_records`
  MODIFY `otherincome_id` int(10) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `#__user_keys`
--
ALTER TABLE `#__user_keys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__user_records`
--
ALTER TABLE `#__user_records`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `#__user_usergroups`
--
ALTER TABLE `#__user_usergroups`
  MODIFY `usergroup_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `#__voucher_records`
--
ALTER TABLE `#__voucher_records`
  MODIFY `voucher_id` int(10) NOT NULL AUTO_INCREMENT;
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
--
-- AUTO_INCREMENT for table `#__workorder_records`
--
ALTER TABLE `#__workorder_records`
  MODIFY `workorder_id` int(10) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
