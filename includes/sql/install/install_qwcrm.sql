-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2017 at 08:17 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `install_qwcrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `qw_company`
--

CREATE TABLE `#__company` (
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `company_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `vat_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
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
  `tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
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
-- Dumping data for table `#__company`
--

INSERT INTO `#__company` (`display_name`, `logo`, `company_number`, `vat_number`, `address`, `city`, `state`, `zip`, `country`, `primary_phone`, `mobile_phone`, `fax`, `email`, `website`, `tax_rate`, `year_start`, `year_end`, `welcome_msg`, `currency_symbol`, `currency_code`, `date_format`, `opening_hour`, `opening_minute`, `closing_hour`, `closing_minute`, `email_signature`, `email_signature_active`, `email_msg_invoice`, `email_msg_workorder`) VALUES
('Lancastrian IT', 'media/logo.png', '2222', 'GB122131231232', '5 Keswick Grove,\r\nHeysham', 'Morecambe', 'Lancashire', 'LA3 2TF', 'United Kingdom', '123456', '', '123456', 'test@noreply.com', 'https://quantumwarp.com/', '17.00', '04/01/2017', '19/08/2017', '<p>welcome</p>', '&pound;', 'GBP', '%d/%m/%Y', 10, 0, 17, 0, '<p>{logo}</p>\r\n<p>Lancastrian IT</p>\r\n<p>Address:<br />1 bugle way<br />chicken house<br />London LA12 6WD<br />Tel: 07777 123456</p>', 1, '<p>Hi {customer_first_name} {customer_last_name}</p>\r\n<p>This is an invoice for the recent work at {customer_display_name}.</p>\r\n<p>Thanks for your custom.</p>', '');

-- --------------------------------------------------------

--
-- Table structure for table `#__customer`
--

CREATE TABLE `#__customer` (
  `customer_id` int(10) NOT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
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
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `create_date` int(20) NOT NULL,
  `last_active` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__customer`
--

INSERT INTO `#__customer` (`customer_id`, `display_name`, `first_name`, `last_name`, `website`, `email`, `credit_terms`, `discount_rate`, `type`, `active`, `primary_phone`, `mobile_phone`, `fax`, `address`, `city`, `state`, `zip`, `country`, `notes`, `create_date`, `last_active`) VALUES
(1, 'Photography', 'Mike', 'Brown', 'https://quantumwarp.com/', 'jon@lancastrian-it.co.uk', 'pay when you can', '12.77', '3', 3, '01524850800', '07747684438', '01524833456', '24 Hawthorn Road\r\nBolton Le Sands', 'Carnforth', 'Lancashire', 'LA5 8EH', 'UK', '<p>moment</p>', 1469114377, '1503144994'),
(3, 'Chicken Chaser', '222', '333', '222', '222', '222', '0.00', '1', 1, '222', '222', '222', '222222', '222', '222', '222', '', '222', 1470143118, '1470143118'),
(4, 'David Curley', 'David', 'Curley', 'quantumwarp.com', 'davidcurley@outlook.com', 'pay when you can', '1.11', '1', 1, '111111111', '22222222', '33333333', 'my address', 'city', 'state', '2342342', 'brazil', '', 1502047677, '1503322091');

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

--
-- Dumping data for table `#__customer_notes`
--

INSERT INTO `#__customer_notes` (`customer_note_id`, `employee_id`, `customer_id`, `date`, `note`) VALUES
(10, '0', '1', '1492549408', '<p>holiday</p>'),
(11, '1', '1', '1492550009', '<p>sddsfsdfs</p>'),
(15, '1', '3', '1492764006', '<p>chieckn kanabs are aweful</p>'),
(16, '1', '1', '1492725600', '<p>terminator is back</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__expense`
--

CREATE TABLE `#__expense` (
  `expense_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `payee` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `items` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__expense`
--

INSERT INTO `#__expense` (`expense_id`, `invoice_id`, `payee`, `date`, `type`, `payment_method`, `net_amount`, `tax_rate`, `tax_amount`, `gross_amount`, `items`, `notes`) VALUES
(1, '', 'tt', '1502924400', '1', '1', '9.00', '17.00', '3.00', '12.00', '<p>fgd</p>', '<p>rge</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__giftcert`
--

CREATE TABLE `#__giftcert` (
  `giftcert_id` int(10) NOT NULL,
  `giftcert_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `employee_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_expires` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_redeemed` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `is_redeemed` int(1) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `active` int(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__giftcert`
--

INSERT INTO `#__giftcert` (`giftcert_id`, `giftcert_code`, `employee_id`, `customer_id`, `invoice_id`, `date_created`, `date_expires`, `date_redeemed`, `is_redeemed`, `amount`, `active`, `notes`) VALUES
(1, 'MV14KOVLLW75NY8O', '0', '1', '0', '1492284013', '1520035200', '0', 0, '51.00', 1, '<p>chicken chaser</p>'),
(2, '3IGBJXB2K0MLMNBF', '0', '1', '11', '1492359176', '1503442800', '1500392630', 1, '1.00', 0, '<p>these are some notes</p>'),
(3, '1Y7XKITETTKQNLGN', '0', '1', '7', '1492359580', '1492725600', '1492366037', 1, '15.17', 0, ''),
(4, 'XFT17CMXJPTQXPJQ', '1', '1', '0', '1492360299', '1493330400', '0', 0, '67.43', 0, ''),
(5, 'Y4XR9Q939DZLG1YF', '1', '1', '0', '1492863235', '1493420400', '0', 0, '333.00', 0, '<p>aaaaaaaaaaa</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice`
--

CREATE TABLE `#__invoice` (
  `invoice_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `due_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `discount_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_paid` int(1) NOT NULL DEFAULT '0',
  `paid_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__invoice`
--

INSERT INTO `#__invoice` (`invoice_id`, `employee_id`, `customer_id`, `workorder_id`, `date`, `due_date`, `discount_rate`, `tax_rate`, `sub_total`, `discount_amount`, `net_amount`, `tax_amount`, `gross_amount`, `paid_amount`, `balance`, `is_paid`, `paid_date`) VALUES
(1, '1', '4', '1', '1502924400', '1502924400', '1.11', '17.00', '28.44', '0.32', '28.12', '4.78', '32.91', '1.33', '31.58', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_labour`
--

CREATE TABLE `#__invoice_labour` (
  `invoice_labour_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qty` int(10) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__invoice_labour`
--

INSERT INTO `#__invoice_labour` (`invoice_labour_id`, `invoice_id`, `description`, `amount`, `qty`, `sub_total`) VALUES
(1, '1', 'test labour', '11.57', 1, '11.57');

-- --------------------------------------------------------

--
-- Table structure for table `#__invoice_parts`
--

CREATE TABLE `#__invoice_parts` (
  `invoice_parts_id` int(10) NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qty` int(10) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__invoice_parts`
--

INSERT INTO `#__invoice_parts` (`invoice_parts_id`, `invoice_id`, `description`, `amount`, `qty`, `sub_total`) VALUES
(1, '1', 'test part', '16.87', 1, '16.87');

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
(1, 'Callout', 'Labour', '35.00', 0),
(2, 'Basic Labour', 'Labour', '20.00', 0),
(3, 'Virus Removal', 'Labour', '65.00', 0),
(4, 'PC Clean', 'Labour', '55.00', 0),
(5, 'Diagnostics', 'Labour', '100.00', 0),
(6, '3.0ghz 8400 CPU', 'Parts', '88.00', 0),
(7, 'Server', 'Parts', '999.00', 0),
(8, 'Hard Drive', 'Parts', '66.50', 0),
(9, 'SSD', 'Parts', '112.00', 0),
(10, 'RAM', 'Parts', '78.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `#__payment`
--

CREATE TABLE `#__payment` (
  `tax_enabled` int(11) NOT NULL,
  `bank_account_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `bank_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `bank_account_number` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `bank_sort_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bank_iban` varchar(34) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bank_transaction_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `cheque_payable_to_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `invoice_footer_msg` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment`
--

INSERT INTO `#__payment` (`tax_enabled`, `bank_account_name`, `bank_name`, `bank_account_number`, `bank_sort_code`, `bank_iban`, `paypal_email`, `bank_transaction_msg`, `cheque_payable_to_msg`, `invoice_footer_msg`) VALUES
(1, '123', 'cOCONUT', '123', '123', '123', 'test@test.com', '<p>this is a bank transaction messagesssssss</p>', '<p>make cheques payable tosssssss</p>', '<p>This is my footer messagesssssss</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_credit_cards`
--

CREATE TABLE `#__payment_credit_cards` (
  `id` int(10) NOT NULL,
  `card_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `card_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_credit_cards`
--

INSERT INTO `#__payment_credit_cards` (`id`, `card_type`, `card_name`, `active`) VALUES
(1, 'visa', 'Visa', 1),
(2, 'mastercard', 'Master Card', 0),
(3, 'amex', 'Amex', 0),
(4, 'discover', 'Discover', 0),
(5, 'delta', 'Delta', 0),
(6, 'solo', 'Solo', 0),
(7, 'switch', 'Switch', 0),
(8, 'jcb', 'JCB', 0),
(9, 'diners', 'Diners', 0),
(10, 'carteblanche', 'Carta Blanche', 0),
(11, 'enroute', 'Enroute', 0);

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_methods`
--

CREATE TABLE `#__payment_methods` (
  `id` int(10) NOT NULL,
  `smarty_tpl_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_methods`
--

INSERT INTO `#__payment_methods` (`id`, `smarty_tpl_key`, `method`, `active`) VALUES
(1, 'credit_card_active', 'Credit Card', 1),
(2, 'cheque_active', 'Cheque', 1),
(3, 'cash_active', 'Cash', 1),
(4, 'gift_certificate_active', 'Gift Certificate', 1),
(5, 'paypal_active', 'PayPal', 1),
(6, 'direct_deposit_active', 'Direct Deposit', 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__payment_transactions`
--

CREATE TABLE `#__payment_transactions` (
  `transaction_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(1) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__payment_transactions`
--

INSERT INTO `#__payment_transactions` (`transaction_id`, `employee_id`, `customer_id`, `workorder_id`, `invoice_id`, `date`, `type`, `amount`, `note`) VALUES
(1, '1', '4', '1', '1', '1502924400', 3, '1.33', 'Partial Payment made by Cash for &pound;1.33, Balance due: &pound;31.58, , Note: gfhgf');

-- --------------------------------------------------------

--
-- Table structure for table `#__refund`
--

CREATE TABLE `#__refund` (
  `refund_id` int(10) NOT NULL,
  `payee` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `items` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__refund`
--

INSERT INTO `#__refund` (`refund_id`, `payee`, `date`, `type`, `payment_method`, `net_amount`, `tax_rate`, `tax_amount`, `gross_amount`, `items`, `notes`) VALUES
(1, 'sdsd', '1502924400', '1', '1', '2.77', '17.00', '0.54', '3.31', '<p>dsv</p>', '<p>dsvds</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__schedule`
--

CREATE TABLE `#__schedule` (
  `schedule_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `workorder_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` int(20) NOT NULL,
  `end_time` int(20) NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__schedule`
--

INSERT INTO `#__schedule` (`schedule_id`, `employee_id`, `customer_id`, `workorder_id`, `start_time`, `end_time`, `notes`) VALUES
(62, '1', '1', '1', 1499954400, 1499960699, '<p>sdfsdfsd</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__session`
--

CREATE TABLE `#__session` (
  `session_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `client_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `guest` tinyint(4) UNSIGNED DEFAULT '1',
  `time` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `userid` int(11) DEFAULT '0',
  `username` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__session`
--

INSERT INTO `#__session` (`session_id`, `client_id`, `guest`, `time`, `data`, `userid`, `username`) VALUES
('5m8n9qmilf6quft43eomhb05d6', 0, 0, '1503247403', 'qwcrm|s:768:"Tzo4OiJSZWdpc3RyeSI6Mzp7czo3OiIAKgBkYXRhIjtPOjg6InN0ZENsYXNzIjoxOntzOjk6Il9fZGVmYXVsdCI7Tzo4OiJzdGRDbGFzcyI6NTp7czo3OiJzZXNzaW9uIjtPOjg6InN0ZENsYXNzIjoyOntzOjc6ImNvdW50ZXIiO2k6MTtzOjU6InRpbWVyIjtPOjg6InN0ZENsYXNzIjozOntzOjU6InN0YXJ0IjtpOjE1MDMyNDc0MDM7czo0OiJsYXN0IjtpOjE1MDMyNDc0MDM7czozOiJub3ciO2k6MTUwMzI0NzQwMzt9fXM6ODoicmVnaXN0cnkiO086ODoiUmVnaXN0cnkiOjM6e3M6NzoiACoAZGF0YSI7Tzo4OiJzdGRDbGFzcyI6MDp7fXM6MTQ6IgAqAGluaXRpYWxpemVkIjtiOjA7czo5OiJzZXBhcmF0b3IiO3M6MToiLiI7fXM6NDoidXNlciI7Tzo1OiJKVXNlciI6MTp7czoyOiJpZCI7czoxOiIxIjt9czoyMDoicG9zdF9lbXVsYXRpb25fdGltZXIiO2k6MTUwMzI0NzQwMztzOjIwOiJwb3N0X2VtdWxhdGlvbl9zdG9yZSI7YToxOntzOjE1OiJpbmZvcm1hdGlvbl9tc2ciO3M6MTc6IkxvZ2luIHN1Y2Nlc3NmdWwuIjt9fX1zOjE0OiIAKgBpbml0aWFsaXplZCI7YjowO3M6OToic2VwYXJhdG9yIjtzOjE6Ii4iO30=";', 1, 'jon.brown'),
('fhbig6i2ndt4f28pvfsm6bkuc2', 0, 0, '1503243982', 'qwcrm|s:696:"Tzo4OiJSZWdpc3RyeSI6Mzp7czo3OiIAKgBkYXRhIjtPOjg6InN0ZENsYXNzIjoxOntzOjk6Il9fZGVmYXVsdCI7Tzo4OiJzdGRDbGFzcyI6NTp7czo3OiJzZXNzaW9uIjtPOjg6InN0ZENsYXNzIjoyOntzOjc6ImNvdW50ZXIiO2k6MztzOjU6InRpbWVyIjtPOjg6InN0ZENsYXNzIjozOntzOjU6InN0YXJ0IjtpOjE1MDMyNDM4OTI7czo0OiJsYXN0IjtpOjE1MDMyNDM4OTI7czozOiJub3ciO2k6MTUwMzI0Mzk4Mjt9fXM6ODoicmVnaXN0cnkiO086ODoiUmVnaXN0cnkiOjM6e3M6NzoiACoAZGF0YSI7Tzo4OiJzdGRDbGFzcyI6MDp7fXM6MTQ6IgAqAGluaXRpYWxpemVkIjtiOjA7czo5OiJzZXBhcmF0b3IiO3M6MToiLiI7fXM6NDoidXNlciI7Tzo1OiJKVXNlciI6MTp7czoyOiJpZCI7czoxOiIxIjt9czoyMDoicG9zdF9lbXVsYXRpb25fc3RvcmUiO2E6MDp7fXM6MjA6InBvc3RfZW11bGF0aW9uX3RpbWVyIjtzOjE6IjAiO319czoxNDoiACoAaW5pdGlhbGl6ZWQiO2I6MDtzOjk6InNlcGFyYXRvciI7czoxOiIuIjt9";', 1, 'jon.brown'),
('mh8ktohgg18p8f6gkg4p5qfv95', 0, 0, '1503322124', 'qwcrm|s:700:"Tzo4OiJSZWdpc3RyeSI6Mzp7czo3OiIAKgBkYXRhIjtPOjg6InN0ZENsYXNzIjoxOntzOjk6Il9fZGVmYXVsdCI7Tzo4OiJzdGRDbGFzcyI6NTp7czo3OiJzZXNzaW9uIjtPOjg6InN0ZENsYXNzIjoyOntzOjc6ImNvdW50ZXIiO2k6Mjc7czo1OiJ0aW1lciI7Tzo4OiJzdGRDbGFzcyI6Mzp7czo1OiJzdGFydCI7aToxNTAzMzIwMTQ3O3M6NDoibGFzdCI7aToxNTAzMzIyMTIzO3M6Mzoibm93IjtpOjE1MDMzMjIxMjQ7fX1zOjg6InJlZ2lzdHJ5IjtPOjg6IlJlZ2lzdHJ5IjozOntzOjc6IgAqAGRhdGEiO086ODoic3RkQ2xhc3MiOjA6e31zOjE0OiIAKgBpbml0aWFsaXplZCI7YjowO3M6OToic2VwYXJhdG9yIjtzOjE6Ii4iO31zOjQ6InVzZXIiO086NToiSlVzZXIiOjE6e3M6MjoiaWQiO3M6MToiMSI7fXM6MjA6InBvc3RfZW11bGF0aW9uX3N0b3JlIjthOjA6e31zOjIwOiJwb3N0X2VtdWxhdGlvbl90aW1lciI7czoxOiIwIjt9fXM6MTQ6IgAqAGluaXRpYWxpemVkIjtiOjA7czo5OiJzZXBhcmF0b3IiO3M6MToiLiI7fQ==";', 1, 'jon.brown');

-- --------------------------------------------------------

--
-- Table structure for table `#__supplier`
--

CREATE TABLE `#__supplier` (
  `supplier_id` int(10) NOT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `primary_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__supplier`
--

INSERT INTO `#__supplier` (`supplier_id`, `display_name`, `first_name`, `last_name`, `website`, `email`, `type`, `primary_phone`, `mobile_phone`, `fax`, `address`, `city`, `state`, `zip`, `country`, `description`, `notes`) VALUES
(1, 'chocoalte', 'wrewrew', '', 'https://www.chocolate..', 'jon@quantumnwarp.com', '5', '22222', '222', '23423', '2222', 'fgffff', 'ddd', 'ddd', '', '<p>ggg</p>', '<p>rrrrrrr</p>'),
(2, '1111111111', 'fiiirst', 'aaastr', 'http://google.com', 'jon@jon.com', '10', '33333333', '3333333', '3333333', '333333333', 'aaaaa', 'aaaaaaa', 'aaaaaa', 'united kingbbbbbbbbb', '<p>aaaaaaaa</p>', '<p>aaaaaaaaa</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__user`
--

CREATE TABLE `#__user` (
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
  `display_name` varchar(50) NOT NULL,
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
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__user`
--

INSERT INTO `#__user` (`user_id`, `customer_id`, `username`, `password`, `email`, `usergroup`, `active`, `last_active`, `register_date`, `require_reset`, `last_reset_time`, `reset_count`, `is_employee`, `display_name`, `first_name`, `last_name`, `work_primary_phone`, `work_mobile_phone`, `work_fax`, `home_primary_phone`, `home_mobile_phone`, `home_email`, `home_address`, `home_city`, `home_state`, `home_zip`, `home_country`, `based`, `notes`) VALUES
(1, '', 'jon.brown', '$2y$10$eBObFUhphxmfmkt5cLioFunuJe.4RCIGNebzmhdpziW3QIY6yVm9K', 'jon@lancastrian-it.co.uk', '1', 1, '1503322124', '', 0, '1500575441', 14, 1, 'Jon Brown', 'Jon', 'Brown', '07747684438', '07747684438', '07747684438', '07747684438', '07747684438', 'hosting@lancastrian-it.co.uk', '5 Keswick Grove\r\nHeysham', 'Morecambe', 'Lancashire', 'LA3 2TF', '', 1, '<p>these are jons notes</p>'),
(2, '', 'test.admin', '$2y$10$wT5tSJkijPALBb5Bw5YnKeZ/7CvJ1YJUmjG3XaX3E9SE6uwoNSxQG', 'hosting@lancastrian-it.co.uk', '1', 1, '1499930858', '', 0, '', 24, 1, 'Test Admin', 'Test', 'User', '07747684438', '07747684438', '07747684438', '07747684438', '07747684438', 'hosting@lancastrian-it.co.uk', '5 Keswick Grove\r\nHeysham', 'Morecambe', 'Lancashire', 'LA3 2TF', '', 1, '<p>these are jons notes</p>'),
(3, '', 'test.customer', '$2y$10$wT5tSJkijPALBb5Bw5YnKeZ/7CvJ1YJUmjG3XaX3E9SE6uwoNSxQG', 'info@lancastrian-it.co.uk', '7', 1, '1499930858', '', 0, '', 0, 0, 'Test Customer', 'Test', 'User', '07747684438', '07747684438', '07747684438', '07747684438', '07747684438', 'hosting@lancastrian-it.co.uk', '5 Keswick Grove\r\nHeysham', 'Morecambe', 'Lancashire', 'LA3 2TF', '', 1, '<p>these are jons notes</p>');

-- --------------------------------------------------------

--
-- Table structure for table `#__user_acl`
--

CREATE TABLE `#__user_acl` (
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
-- Dumping data for table `#__user_acl`
--

INSERT INTO `#__user_acl` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Customer`, `Guest`, `Public`) VALUES
('administrator:acl', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:config', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:phpinfo', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('administrator:update', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('company:business_hours', 1, 1, 0, 0, 0, 0, 0, 0, 0),
('company:edit', 1, 1, 0, 0, 0, 0, 0, 0, 0),
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
('help:about', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('help:attribution', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('help:license', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:delete_labour', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('invoice:delete_parts', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('invoice:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
('invoice:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:paid', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:prefill_items', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('invoice:print', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('invoice:search', 1, 1, 1, 0, 1, 1, 0, 0, 0),
('invoice:unpaid', 1, 1, 0, 0, 1, 0, 0, 0, 0),
('payment:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
('payment:options', 1, 1, 0, 0, 1, 0, 0, 0, 0),
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
('workorder:closed', 1, 1, 1, 0, 0, 0, 0, 0, 0),
('workorder:delete', 1, 1, 1, 0, 0, 0, 0, 0, 0),
('workorder:details', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:details_edit_comments', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:details_edit_description', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:details_edit_resolution', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:new', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:note_delete', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:note_edit', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:note_new', 1, 1, 1, 1, 0, 1, 0, 0, 0),
('workorder:open', 1, 1, 1, 0, 0, 0, 0, 0, 0),
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

--
-- Dumping data for table `#__user_keys`
--

INSERT INTO `#__user_keys` (`id`, `user_id`, `token`, `series`, `time`, `uastring`) VALUES
(7, 'jon.brown', '$2y$10$yeOI24IVCqS4MZC8SJ5JrOekOSyR1nMnc//YHkiYn19z/r8WN827C', 'BMrLQLzwfJ4NDDyjlV6h', '1508431403', 'qwcrm_remember_me_7b458569e912c182f68916605c217730');

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

--
-- Dumping data for table `#__user_reset`
--

INSERT INTO `#__user_reset` (`user_id`, `expiry_time`, `token`, `reset_code`, `reset_code_expiry_time`) VALUES
('2', '1502117920', 'XWSRHkXwFK56nitpKzQNiFp7XaTHcbwp1i1ARBA3HHWa0FjLK7pco8tkUjcsLKOs', '', '');

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
-- Table structure for table `#__workorder`
--

CREATE TABLE `#__workorder` (
  `workorder_id` int(10) NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `closed_by` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `open_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `close_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_active` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(2) NOT NULL,
  `is_closed` int(11) NOT NULL,
  `scope` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `resolution` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `#__workorder`
--

INSERT INTO `#__workorder` (`workorder_id`, `employee_id`, `customer_id`, `invoice_id`, `created_by`, `closed_by`, `open_date`, `close_date`, `last_active`, `status`, `is_closed`, `scope`, `description`, `comments`, `resolution`) VALUES
(1, '', '4', '1', '1', '1', '1502983436', '1502983444', '1503322091', 4, 0, 'test', '<p>test</p>', '<p>test</p>', '<p>test resolurtion</p>'),
(2, '', '4', '', '1', '', '1503001599', '', '1503062046', 1, 0, 'vvvv', '<p>vvvddddd</p>', '<p>vvvdddddddd</p>', '<p>ggg</p>');

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

--
-- Dumping data for table `#__workorder_history`
--

INSERT INTO `#__workorder_history` (`history_id`, `employee_id`, `workorder_id`, `date`, `note`) VALUES
(1, '1', '1', '1502983436', 'Created by Jon Brown'),
(2, '1', '1', '1502983444', 'Closed with Invoice by Jon Brown'),
(3, '1', '1', '1502983444', 'Status updated to Closed with Invoice by Jon Brown'),
(4, '1', '1', '1502983444', 'Invoice Created ID: 1'),
(5, '1', '1', '1502987415', 'Created by Jon Brown - Partial Payment made by Cash for &pound;1.33, Balance due: &pound;31.58, , Note: gfhgf'),
(6, '1', '2', '1503001599', 'Created by Jon Brown'),
(7, '1', '2', '1503001627', 'Resolution updated by Jon Brown'),
(8, '1', '2', '1503001643', 'Comments updated by Jon Brown'),
(9, '1', '2', '1503001655', 'Scope and Description updated by Jon Brown'),
(10, '1', '2', '1503006434', 'Work Order Note 1 added by Jon Brown'),
(11, '1', '2', '1503006521', 'Work Order Note 2 added by Jon Brown'),
(12, '1', '2', '1503006572', 'Work Order Note 3 added by Jon Brown'),
(13, '1', '2', '1503006898', 'Work Order Note 4 added by Jon Brown'),
(14, '1', '2', '1503041333', 'Resolution updated by Jon Brown'),
(15, '1', '2', '1503044483', 'Scope and Description updated by Jon Brown'),
(16, '1', '2', '1503044547', 'Scope and Description updated by Jon Brown'),
(17, '1', '2', '1503044561', 'Comments updated by Jon Brown'),
(18, '1', '2', '1503044570', 'Resolution updated by Jon Brown'),
(19, '1', '2', '1503044581', 'Work Order Note 4 has been deleted by Jon Brown'),
(20, '1', '2', '1503044594', 'Work Order Note 3 updated by Jon Brown'),
(21, '1', '2', '1503044605', 'Work Order Note 5 added by Jon Brown'),
(22, '1', '1', '1503057168', 'Successfully sent email to davidcurley@outlook.com (David Curley)with the subject: Invoice 1 by Jon Brown'),
(23, '1', '1', '1503057275', 'Successfully sent email to davidcurley@outlook.com (David Curley) with the subject ''Invoice 1'' by Jon Brown'),
(24, '1', '1', '1503057324', 'Successfully sent email to davidcurley@outlook.com (David Curley) with the subject ''Invoice 1'' and was sent by Jon Brown'),
(25, '1', '1', '1503057386', 'Successfully sent email to davidcurley@outlook.com (David Curley) with the subject ''Invoice 1'' and was sent by Jon Brown'),
(26, '1', '1', '1503057425', 'Successfully sent email to davidcurley@outlook.com (David Curley) with the subject ''Invoice 1'' and was sent by Jon Brown'),
(27, '1', '1', '1503057543', 'Successfully sent email to davidcurley@outlook.com (David Curley) with the subject : Invoice 1 : and was sent by Jon Brown'),
(28, '1', '1', '1503057617', 'Successfully sent email to davidcurley@outlook.com (David Curley) with the subject : Invoice 1 : and was sent by Jon Brown'),
(29, '1', '2', '1503062046', 'Resolution updated by Jon Brown'),
(30, '1', '1', '1503322050', 'Status updated to Unassigned by Jon Brown'),
(31, '1', '1', '1503322079', 'Status updated to Unassigned by Jon Brown'),
(32, '1', '1', '1503322091', 'Status updated to On Hold by Jon Brown');

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

--
-- Dumping data for table `#__workorder_notes`
--

INSERT INTO `#__workorder_notes` (`workorder_note_id`, `employee_id`, `workorder_id`, `date`, `description`) VALUES
(3, '1', '2', '1502924400', '<p>dfvdf</p>'),
(5, '1', '2', '1503044605', '<p>ddd</p>');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `#__company`
--
ALTER TABLE `#__company`
  ADD PRIMARY KEY (`display_name`);

--
-- Indexes for table `#__customer`
--
ALTER TABLE `#__customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `#__customer_notes`
--
ALTER TABLE `#__customer_notes`
  ADD PRIMARY KEY (`customer_note_id`);

--
-- Indexes for table `#__expense`
--
ALTER TABLE `#__expense`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `#__giftcert`
--
ALTER TABLE `#__giftcert`
  ADD PRIMARY KEY (`giftcert_id`);

--
-- Indexes for table `#__invoice`
--
ALTER TABLE `#__invoice`
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
-- Indexes for table `#__payment_credit_cards`
--
ALTER TABLE `#__payment_credit_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_methods`
--
ALTER TABLE `#__payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__payment_transactions`
--
ALTER TABLE `#__payment_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `#__refund`
--
ALTER TABLE `#__refund`
  ADD PRIMARY KEY (`refund_id`);

--
-- Indexes for table `#__schedule`
--
ALTER TABLE `#__schedule`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `#__session`
--
ALTER TABLE `#__session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `#__supplier`
--
ALTER TABLE `#__supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `#__user`
--
ALTER TABLE `#__user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `#__user_acl`
--
ALTER TABLE `#__user_acl`
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
-- Indexes for table `#__workorder`
--
ALTER TABLE `#__workorder`
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `#__customer`
--
ALTER TABLE `#__customer`
  MODIFY `customer_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `#__customer_notes`
--
ALTER TABLE `#__customer_notes`
  MODIFY `customer_note_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `#__expense`
--
ALTER TABLE `#__expense`
  MODIFY `expense_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `#__giftcert`
--
ALTER TABLE `#__giftcert`
  MODIFY `giftcert_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `#__invoice`
--
ALTER TABLE `#__invoice`
  MODIFY `invoice_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `#__invoice_labour`
--
ALTER TABLE `#__invoice_labour`
  MODIFY `invoice_labour_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `#__invoice_parts`
--
ALTER TABLE `#__invoice_parts`
  MODIFY `invoice_parts_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `#__invoice_prefill_items`
--
ALTER TABLE `#__invoice_prefill_items`
  MODIFY `invoice_prefill_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `#__payment_credit_cards`
--
ALTER TABLE `#__payment_credit_cards`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `#__payment_methods`
--
ALTER TABLE `#__payment_methods`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `#__payment_transactions`
--
ALTER TABLE `#__payment_transactions`
  MODIFY `transaction_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `#__refund`
--
ALTER TABLE `#__refund`
  MODIFY `refund_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `#__schedule`
--
ALTER TABLE `#__schedule`
  MODIFY `schedule_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `#__supplier`
--
ALTER TABLE `#__supplier`
  MODIFY `supplier_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `#__user`
--
ALTER TABLE `#__user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `#__user_keys`
--
ALTER TABLE `#__user_keys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `#__user_usergroups`
--
ALTER TABLE `#__user_usergroups`
  MODIFY `usergroup_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `#__workorder`
--
ALTER TABLE `#__workorder`
  MODIFY `workorder_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `#__workorder_history`
--
ALTER TABLE `#__workorder_history`
  MODIFY `history_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `#__workorder_notes`
--
ALTER TABLE `#__workorder_notes`
  MODIFY `workorder_note_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
