<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/core.php');
require(INCLUDES_DIR.'modules/report.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Display Welcome Note
$smarty->assign('welcome_note', display_welcome_msg($db));

// Employee Workorder Stats
$smarty->assign('employee_workorders_open_count', count_user_workorders_with_status($db, $login_user_id, 'open'));
$smarty->assign('employee_workorders_assigned_count', count_user_workorders_with_status($db, $login_user_id, '2'));
$smarty->assign('employee_workorders_waiting_for_parts_count', count_user_workorders_with_status($db, $login_user_id, '3'));
$smarty->assign('employee_workorders_on_hold_count', count_user_workorders_with_status($db, $login_user_id, '4'));
$smarty->assign('employee_workorders_management_count', count_user_workorders_with_status($db, $login_user_id, '5'));
$smarty->assign('employee_workorders_total_closed_count', count_user_invoices_with_status($db, $login_user_id, 'closed'));

// Employee Workorders
$smarty->assign('assigned_workorders',          display_workorders($db, 'DESC', false, $page_no, '25', null, null, '2', $login_user_id));
$smarty->assign('waiting_for_parts_workorders', display_workorders($db, 'DESC', false, $page_no, '25', null, null, '3', $login_user_id));
$smarty->assign('on_hold_workorders',           display_workorders($db, 'DESC', false, $page_no, '25', null, null, '4', $login_user_id));
$smarty->assign('management_workorders',        display_workorders($db, 'DESC', false, $page_no, '25', null, null, '5', $login_user_id));

// Build the page
$BuildPage .= $smarty->fetch('core/dashboard.tpl');