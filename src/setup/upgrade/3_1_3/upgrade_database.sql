/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

--
-- Correct Payment Status on some records, issue introduced in an earlier version and fixed but records were never corrected
--

UPDATE `#__invoice_records`
SET `status` = 'paid', `closed_on` = `last_active`, `is_closed` = 1
WHERE `status` = 'partially_paid' AND `balance` = 0 AND `unit_gross` > 0;

UPDATE `#__refund_records`
SET `status` = 'paid', `closed_on` = `last_active`
WHERE `status` = 'partially_paid' AND `balance` = 0 AND `unit_gross` > 0;

UPDATE `#__expense_records`
SET `status` = 'paid', `closed_on` = `last_active`
WHERE `status` = 'partially_paid' AND `balance` = 0 AND `unit_gross` > 0;

UPDATE `#__other_records`
SET `status` = 'paid', `closed_on` = `last_active`
WHERE `status` = 'partially_paid' AND `balance` = 0 AND `unit_gross` > 0;