<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'report.php');

// Global Workorder Stats
$smarty->assign('global_workorder_current_stats', get_workorders_stats('current'));
$smarty->assign('global_workorder_historic_stats', get_workorders_stats('historic'));

// Global Invoice Stats
$smarty->assign('global_invoice_current_stats', get_invoices_stats('current'));
$smarty->assign('global_invoice_historic_stats', get_invoices_stats('historic'));

// Global Client Stats
$smarty->assign('global_client_historic_stats', get_clients_stats('historic'));

// Employee Workorder Stats (Logged in user)
$smarty->assign('employee_workorder_current_stats', get_workorders_stats('current', null, null, $user->login_user_id));
$smarty->assign('employee_workorder_historic_stats', get_workorders_stats('historic', null, null, $user->login_user_id));