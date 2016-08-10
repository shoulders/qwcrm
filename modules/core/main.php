<?php

//require('includes'.SEP.'modules'.SEP.'core.php');

$wo_id = $VAR['wo_id'];

/** Misc **/

/* Get the page number we are on if first page set to 1 - this might not be needed */
    if(!isset($VAR['page_no'])){
        $page_no = 1;
    } else {
        $page_no = $VAR['page_no'];
    }

/* Display Welcome Note */
$smarty->assign('welcome_note', display_welcome_note($db));


/** Work Orders **/

/* New Work Order Counts */
$smarty->assign('wo_new_count', count_workorders_with_status($db, 1));

/* Assigned counts */
$smarty->assign('wo_ass_count', count_workorders_with_status($db, 2));

/* waiting for parts count */
$smarty->assign('wo_parts_count', count_workorders_with_status($db, 3));

/* waiting for payment */
$smarty->assign('wo_pay_count', count_workorders_with_status($db, 7));

/* closed */
$smarty->assign('wo_closed_count', count_workorders_with_status($db, 6));

/* WO total count */
$smarty->assign('wo_total_count', count_all_workorders($db));

/* Assigned Work Orders - not used in theme header or menu */
$smarty->assign('workorders_assigned_count', count_workorders_with_status($db, 2));



/** Invoices **/

/* Sum of Discounts on Unpaid Invoices  */
$unpaid_discounts = sum_of_discounts_on_unpaid_invoices($db);


/* Sum of Discounts on Paid Invoices */
$all_discounts = sum_of_discounts_on_paid_invoices($db);

/* Sum of Discounts on Partially Paid Invoices */
$part_discounts = sum_of_discounts_on_partially_paid_invoices($db);

/* Count Unpaid Invoices */
$smarty->assign('in_unpaid_count', count_partially_paid_invoices($db));

/* Sum of Outstanding Balances for Unpaid Invoices */
$smarty->assign('in_unpaid_bal', sum_outstanding_balances_unpaid_invoices($db));

/* Count Partially Paid Invoices */
$smarty->assign('in_part_count', count_partially_paid_invoices($db));

/* Sum of Outstanding Balances for Partially Paid Invoices */
$smarty->assign('in_part_bal', sum_outstanding_balances_partially_paid_invoices($db));

/* Count All Paid Invoices */
$smarty->assign('in_paid_count',count_all_paid_invoices($db));




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

/*new customers this month */
$smarty->assign('cu_month_count', new_customers_during_period($db, 'month'));

/* new customers this year */
$smarty->assign('cu_year_count',new_customers_during_period($db, 'year'));

/* Count All Customers */
$smarty->assign('cu_total_count', count_all_customers($db));

/** Employee **/

/* Get Employee Credentials */ // not used - replaced by session stuff - when remove from here add not to core.php function
//$smarty->assign('employee_record', get_employee_record_by_username($db, $login_usr));

/* Logged in Employee - Open Work Orders */
$smarty->assign('employee_workorders_open_count', count_employee_workorders_with_status($db, $login_id, 10));

/* Logged in Employee - Assigned Work Orders */
$smarty->assign('employee_workorders_assigned_count', count_employee_workorders_with_status($db, $login_id, 2));

/* Logged in Employee - Work Orders Awaiting Payment*/
$smarty->assign('employee_workorders_awaiting_payment_count', count_employee_workorders_with_status($db, $login_id, 7));

/* Logged in Employee - Unpaid Invoices */
$smarty->assign('employee_invoices_unpaid_count', count_employee_invoices_with_status($db, $login_id, 0));




$smarty->display('core'.SEP.'main.tpl');





