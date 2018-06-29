<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/report.php');

// Global Workorder Stats
$smarty->assign('global_workorder_stats', get_workorder_stats('current'));
$smarty->assign('global_workorder_overall_stats', get_workorder_stats('overall'));

// Employee Workorder Stats
$smarty->assign('employee_workorder_stats', get_workorder_stats('current', $user->login_user_id));
$smarty->assign('employee_workorder_overall_stats', get_workorder_stats('overall', $user->login_user_id));

// Global Invoice Stats
$smarty->assign('global_invoice_stats', get_invoices_stats('current'));
$smarty->assign('global_invoice_overall_stats', get_invoices_stats('overall'));

// Global Customer Stats
$smarty->assign('global_customer_overall_stats', get_customer_overall_stats());

// Build the page
$BuildPage .= $smarty->fetch('report/basic_stats.tpl');