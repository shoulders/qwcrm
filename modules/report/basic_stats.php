<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/report.php');

/** Work Orders **/

// Overall Workorder Stats
$smarty->assign('overall_workorders_open_count',               count_workorders($db, 'open')                    );
$smarty->assign('overall_workorders_assigned_count',           count_workorders($db, '2')                       );
$smarty->assign('overall_workorders_waiting_for_parts_count',  count_workorders($db, '3')                       );
$smarty->assign('overall_workorders_on_hold_count',            count_workorders($db, '4')                       );
$smarty->assign('overall_workorders_management_count',         count_workorders($db, '5')                       );
$smarty->assign('overall_workorders_total_closed_count',       count_workorders($db, 'closed')                  );

// Employee Workorder Stats
$smarty->assign('employee_workorders_open_count',               count_workorders($db, 'open', $login_user_id)   );
$smarty->assign('employee_workorders_assigned_count',           count_workorders($db, '2', $login_user_id)      );
$smarty->assign('employee_workorders_waiting_for_parts_count',  count_workorders($db, '3', $login_user_id)      );
$smarty->assign('employee_workorders_on_hold_count',            count_workorders($db, '4', $login_user_id)      );
$smarty->assign('employee_workorders_management_count',         count_workorders($db, '5', $login_user_id)      );
$smarty->assign('employee_workorders_total_closed_count',       count_workorders($db, 'closed', $login_user_id) );

/** Invoices **/

// Overall Invoice Counts
$smarty->assign('overall_invoices_unpaid_count',            count_invoices($db, 'unpaid')                       );
$smarty->assign('overall_invoices_partially_paid_count',    count_invoices($db, 'partially_paid')               );
$smarty->assign('overall_invoices_paid_count',              count_invoices($db, 'paid')                         );
$smarty->assign('overall_invoices_count',                   count_invoices($db, 'all')                          );

// Invoice Totals
$smarty->assign('overall_invoiced_total',                   sum_invoices_value($db, 'total', 'all') );
$smarty->assign('overall_received_monies',                  sum_invoices_value($db, 'paid_amount', 'all')       );
$smarty->assign('overall_outstanding_balance',              sum_invoices_value($db, 'balance', 'all')           );


/** Totals **/

// Total Monies Invoiced
$smarty->assign('invoiced_total', $invoiced_total);

// Total Invoice Monies Recieved
$smarty->assign('monies_received_total', $monies_received_total);

// Outstanding Balance
$smarty->assign('invoiced_balance_total', $invoiced_total - $monies_received_total);

/** Dates **/

$dateObject = new DateTime();
$date_today = $dateObject->getTimestamp();

$dateObject->modify('first day of this month');
$date_month_start = $dateObject->getTimestamp();

$dateObject->modify('last day of this month');
$date_month_end = $dateObject->getTimestamp();

//$dateObject->modify('first day of this year');
//$date_year_start = $dateObject->getTimestamp();

//$dateObject->modify('last day of this year');
//$date_year_end = $dateObject->getTimestamp();

//if($requested_period === 'month')   {$period = mktime(0,0,0,date('m'),0,date('Y'));} - not used for reference only
//if($requested_period === 'year')    {$period = mktime(0,0,0,0,0,date('Y'));} - not used for reference only

$date_year_start = get_company_details($db, 'year_start');
$date_year_end = get_company_details($db, 'year_end');

/** Customers **/

// new customers this month
$smarty->assign('customer_month_count', count_customers($db, $status, $date_month_start, $date_month_end));

// new customers this year
$smarty->assign('customer_year_count', count_customers($db, $status, $date_year_start, $date_year_end));

// Count All Customers
$smarty->assign('customer_total_count', count_customers($db, 'all'));


/** Build the Page **/

// Build the page
$BuildPage .= $smarty->fetch('report/basic_stats.tpl');