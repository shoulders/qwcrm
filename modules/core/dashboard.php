<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/core.php');
require(INCLUDES_DIR.'modules/report.php');

// Display Welcome Note
$smarty->assign('welcome_note', display_welcome_msg($db));

/** Employee **/

/* Logged in Employee - Open/creted Work Orders count */
$smarty->assign('employee_workorders_open_count', count_user_workorders_with_status($db, $login_user_id, 'open'));

/* Logged in Employee - Assigned Work Orders count */
$smarty->assign('employee_workorders_assigned_count', count_user_workorders_with_status($db, $login_user_id, '2'));

/* Logged in Employee - Work Orders Waiting For Parts count */
$smarty->assign('employeee_workorders_waiting_for_parts_count', count_user_workorders_with_status($db, $login_user_id, '3'));

/* Logged in Employee - Work Orders Awaiting Payment count */
$smarty->assign('employee_workorders_awaiting_payment_count', count_user_workorders_with_status($db, $login_user_id, '7'));

/* Logged in Employee - Closed Work Orders total*/
$smarty->assign('employee_workorders_total_closed_count', count_user_invoices_with_status($db, $login_user_id, 'closed'));

// Build the page
$BuildPage .= $smarty->fetch('core/dashboard.tpl');