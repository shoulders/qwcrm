<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'invoice.php');

// Build the page
$smarty->assign('overview_invoices_pending',            display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'pending')           );
$smarty->assign('overview_invoices_unpaid',             display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'unpaid')            );
$smarty->assign('overview_invoices_partially_paid',     display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'partially_paid')    );
$smarty->assign('overview_invoices_paid',               display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'paid')              );
$smarty->assign('overview_invoices_in_dispute',         display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'in_dispute')        );
$smarty->assign('overview_invoices_overdue',            display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'overdue')           );
$smarty->assign('overview_invoices_collections',        display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'collections')       );
$smarty->assign('overview_invoices_refunded',           display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'refunded')          );
$smarty->assign('overview_invoices_cancelled',          display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'cancelled')         );
$smarty->assign('overview_invoice_stats',               get_invoices_stats('current'));
$smarty->assign('invoice_statuses',                     get_invoice_statuses());

$BuildPage .= $smarty->fetch('invoice/overview.tpl');