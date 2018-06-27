<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/invoice.php');

// Prevent undefined variable errors
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;

// Build the page
$smarty->assign('search_category',    null );
$smarty->assign('search_term',        null );
$smarty->assign('filter_status',      null );
$smarty->assign('display_invoices', display_invoices('invoice_id', 'DESC', true, '25', $VAR['page_no'], null, null, 'closed'));
$smarty->assign('invoice_statuses', get_invoice_statuses());
$BuildPage .= $smarty->fetch('invoice/closed.tpl');