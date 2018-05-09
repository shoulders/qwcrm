<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/report.php');

/** Work Orders **/

// Global Workorder Stats
$smarty->assign('global_workorders_open_count',                 count_workorders($db, 'open')                               );
$smarty->assign('global_workorders_assigned_count',             count_workorders($db, 'assigned')                           );
$smarty->assign('global_workorders_waiting_for_parts_count',    count_workorders($db, 'waiting_for_parts')                  );
$smarty->assign('global_workorders_scheduled_count',            count_workorders($db, 'scheduled')                          );
$smarty->assign('global_workorders_with_client_count',          count_workorders($db, 'with_client')                        );
$smarty->assign('global_workorders_on_hold_count',              count_workorders($db, 'on_hold')                            );
$smarty->assign('global_workorders_management_count',           count_workorders($db, 'management')                         );
$smarty->assign('global_workorders_opened_count',               count_workorders($db, 'opened')                             );
$smarty->assign('global_workorders_closed_count',               count_workorders($db, 'closed')                             );

// Employee Workorder Stats
$smarty->assign('employee_workorders_open_count',               count_workorders($db, 'open', $login_user_id)               );
$smarty->assign('employee_workorders_assigned_count',           count_workorders($db, 'assigned', $login_user_id)           );
$smarty->assign('employee_workorders_waiting_for_parts_count',  count_workorders($db, 'waiting_for_parts', $login_user_id)  );
$smarty->assign('employee_workorders_scheduled_count',          count_workorders($db, 'scheduled', $login_user_id)          );
$smarty->assign('employee_workorders_with_client_count',        count_workorders($db, 'with_client', $login_user_id)        );
$smarty->assign('employee_workorders_on_hold_count',            count_workorders($db, 'on_hold', $login_user_id)            );
$smarty->assign('employee_workorders_management_count',         count_workorders($db, 'management', $login_user_id)         );
$smarty->assign('employee_workorders_opened_count',             count_workorders($db, 'opened', $login_user_id)             );
$smarty->assign('employee_workorders_closed_count',             count_workorders($db, 'closed', $login_user_id)             );

/** Invoices **/

// Global Invoice Stats
$smarty->assign('global_invoices_open_count',                   count_invoices($db, 'open')                                 );
$smarty->assign('global_invoices_pending_count',                count_invoices($db, 'pending')                              );
$smarty->assign('global_invoices_unpaid_count',                 count_invoices($db, 'unpaid')                               );
$smarty->assign('global_invoices_partially_paid_count',         count_invoices($db, 'partially_paid')                       );
$smarty->assign('global_invoices_paid_count',                   count_invoices($db, 'paid')                                 );
$smarty->assign('global_invoices_opened_count',                 count_invoices($db, 'opened')                               );
$smarty->assign('global_invoices_closed_count',                 count_invoices($db, 'closed')                               );
$smarty->assign('global_invoiced_total',                        sum_invoices_value($db, 'all', 'gross_amount')              );
$smarty->assign('global_received_monies',                       sum_invoices_value($db, 'all', 'paid_amount')               );
$smarty->assign('global_outstanding_balance',                   sum_invoices_value($db, 'all', 'balance')                   );

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

$date_year_start    = get_company_details($db, 'year_start');
$date_year_end      = get_company_details($db, 'year_end');

/** Customers **/

// Global Customer Stats
$smarty->assign('customer_month_count', count_customers($db, 'all', $date_month_start, $date_month_end)     );
$smarty->assign('customer_year_count',  count_customers($db, 'all', $date_year_start, $date_year_end)       );
$smarty->assign('customer_total_count', count_customers($db, 'all')                                         );

/** Build the Page **/

// Build the page
$BuildPage .= $smarty->fetch('report/basic_stats.tpl');