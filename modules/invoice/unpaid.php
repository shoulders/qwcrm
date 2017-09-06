<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/invoice.php');

// Build the page
$smarty->assign('invoices', display_invoices($db, 'DESC', true, $page_no, '25', null, null, 'open'));
$smarty->assign('invoice_statuses', get_invoice_statuses($db));
$BuildPage .= $smarty->fetch('invoice/unpaid.tpl');