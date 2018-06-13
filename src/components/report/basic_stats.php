<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/invoice.php');
require(INCLUDES_DIR.'components/report.php');
require(INCLUDES_DIR.'components/workorder.php');

/** Work Orders **/

// Global Workorder Stats
$smarty->assign('global_workorder_stats', get_workorder_stats());
$smarty->assign('global_workorder_overall_stats', get_workorder_overall_stats());

// Employee Workorder Stats
$smarty->assign('employee_workorder_stats', get_workorder_stats($user->login_user_id));
$smarty->assign('employee_workorder_overall_stats', get_workorder_overall_stats($user->login_user_id));


/** Invoices **/

// Global Invoice Stats
$smarty->assign('global_invoice_stats', get_invoices_stats());
$smarty->assign('global_invoice_overall_stats', get_invoices_overall_stats());

/** Customers **/

// Global Customer Stats
$smarty->assign('global_customer_overall_stats', get_customer_overall_stats($date_year_start, $date_year_end, $date_month_start, $date_month_end));



/** Build the Page **/

// Build the page
$BuildPage .= $smarty->fetch('report/basic_stats.tpl');