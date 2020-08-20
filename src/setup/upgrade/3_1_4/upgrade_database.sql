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
('cronjob:details', '1', '1', '0', '0', '0', '0', '0', '0', '0'),
('cronjob:edit', '1', '0', '0', '0', '0', '0', '0', '0', '0'),
('cronjob:overview', '1', '1', '0', '0', '0', '0', '0', '0', '0'),
('cronjob:run', '1', '1', '0', '0', '0', '0', '0', '0', '0'),
('cronjob:unlock', '1', '0', '0', '0', '0', '0', '0', '0', '0');

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
  `month` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `weekday` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `command` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__cronjob_records` (`cronjob_id`, `name`, `description`, `active`, `pseudo_allowed`, `default_settings`, `last_run_time`, `last_run_status`, `locked`, `minute`, `hour`, `day`, `month`, `weekday`, `command`) VALUES
(1, 'Test Cron', '<p>This cronjob is designed to check the basic functionality of the cronjob system. When enabled it will send an email every 15 minutes from QWcrm to the configured company email address. You can also run the cronjob manually to test immediately.</p>', 0, 1, '{\"active\":\"0\",\"pseudo_allowed\":\"1\",\"minute\":\"*\\/15\",\"hour\":\"*\",\"day\":\"*\",\"month\":\"*\",\"weekday\":\"*\"}', '0000-00-00 00:00:00', 1, 0, '*/15', '*', '*', '*', '*', '{\"class\":\"Cronjob\",\"function\":\"cronjobTest\"}');

ALTER TABLE `#__cronjob_records` ADD PRIMARY KEY (`cronjob_id`);

CREATE TABLE `#__cronjob_system` (
  `last_run_time` datetime NOT NULL,
  `last_run_status` int(1) NOT NULL DEFAULT 0,
  `locked` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `#__cronjob_system` (`last_run_time`, `last_run_status`, `locked`) VALUES
('0000-00-00 00:00:00', 0, 0);

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


