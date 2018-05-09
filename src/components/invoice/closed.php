<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/invoice.php');

// Build the page
$smarty->assign('invoices', display_invoices($db, 'invoice_id', 'DESC', true, $page_no, '25', null, null, 'closed'));
$smarty->assign('invoice_statuses', get_invoice_statuses($db));
$BuildPage .= $smarty->fetch('invoice/closed.tpl');