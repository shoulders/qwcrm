<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/core.php');
require(INCLUDES_DIR.'modules/user.php');

/** Misc **/

/* Display Welcome Note */
$smarty->assign('welcome_note', display_welcome_msg($db));

/** Work Orders **/

/* Created - New - Work Orders Count */ //workorders_created_count
$smarty->assign('workorders_open_count', count_workorders_with_status($db, 1));

/* Assigned - Work Orders Count - not used in theme header or menu */
$smarty->assign('workorders_assigned_count', count_workorders_with_status($db, 2));

/* Waiting For Parts - Work Orders count */
$smarty->assign('workorders_waiting_for_parts_count', count_workorders_with_status($db, 3));

/* Awaiting Payment - Work Orders count */
$smarty->assign('workorders_awaiting_payment_count', count_workorders_with_status($db, 7));

/* Closed - Work Orders count */
$smarty->assign('workorders_closed_count', count_workorders_with_status($db, 6));

/* WO total count */
$smarty->assign('wo_total_count', count_all_workorders($db));


/** Invoices **/

/* Unpaid - Count Unpaid Invoices */
$smarty->assign('in_unpaid_count', count_partially_paid_invoices($db));

/* Balance - Sum of Outstanding Balances for Unpaid Invoices */
$smarty->assign('in_unpaid_bal', sum_outstanding_balances_unpaid_invoices($db));

/* Partial Paid - Count Partially Paid Invoices */
$smarty->assign('in_part_count', count_partially_paid_invoices($db));

/* Partial Paid Balance - Sum of Outstanding Balances for Partially Paid Invoices */
$smarty->assign('in_part_bal', sum_outstanding_balances_partially_paid_invoices($db));

/* Recieved Monies Total - Count All Paid Invoices */
$smarty->assign('in_paid_count',count_all_paid_invoices($db));


/** Discounts **/

/* Sum of Discounts on Paid Invoices - NOT USED */
$all_discounts = sum_of_discounts_on_paid_invoices($db);

/* Sum of Discounts on Partially Paid Invoices - NOT USED */
$part_discounts = sum_of_discounts_on_partially_paid_invoices($db);

/* Sum of Discounts on Unpaid Invoices - NOT USED */
$unpaid_discounts = sum_of_discounts_on_unpaid_invoices($db);




/* need to check all of these */

/* Sum of Paid Invoices */
$in_total = sum_invoiceamounts_paid_invoices($db);

/* All Time Invoice Totals */
$in_total_bal = $in_total - $in_out_bal;
$in_total2 = $in_total ;

// Total Invoice Monies Recieved
$smarty->assign('in_total_bal',$in_total_bal);

// Total Monies Invoiced
$smarty->assign('in_total2',$in_total2);

// unknown use
$in_out_bal = $in_unpaid_bal ;
$smarty->assign('in_out_bal',$in_out_bal);




/** Customers **/

/* new customers this month */
$smarty->assign('cu_month_count', new_customers_during_period($db, 'month'));

/* new customers this year */
$smarty->assign('cu_year_count',new_customers_during_period($db, 'year'));

/* Count All Customers */
$smarty->assign('cu_total_count', count_all_customers($db));


/** Employee **/

/* Logged in Employee - Open Work Orders count */
$smarty->assign('employee_workorders_open_count', count_user_workorders_with_status($db, $login_id, 10));

/* Logged in Employee - Assigned Work Orders count */
$smarty->assign('employee_workorders_assigned_count', count_user_workorders_with_status($db, $login_id, 2));

/* Logged in Employee - Work Orders Waiting For Parts count */
$smarty->assign('employeee_workorders_waiting_for_parts_count', count_user_workorders_with_status($db, $login_id, 3));

/* Logged in Employee - Work Orders Awaiting Payment count */
$smarty->assign('employee_workorders_awaiting_payment_count', count_user_workorders_with_status($db, $login_id, 7));

/* Logged in Employee - Closed Work Orders Awaiting Payment count */
$smarty->assign('employee_workorders_awaiting_payment_count', count_user_workorders_with_status($db, $login_id, 6));

/* Logged in Employee - Unpaid Invoices count - NOT USED*/
$smarty->assign('employee_invoices_unpaid_count', count_user_invoices_with_status($db, $login_id, 0));

$BuildPage .= $smarty->fetch('core/dashboard.tpl');
