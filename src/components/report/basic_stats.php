<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/report.php');

/** Work Orders **/

// Global Workorder Stats
$smarty->assign('global_workorders_open_count',                 count_workorders('open')                               );
$smarty->assign('global_workorders_assigned_count',             count_workorders('assigned')                           );
$smarty->assign('global_workorders_waiting_for_parts_count',    count_workorders('waiting_for_parts')                  );
$smarty->assign('global_workorders_scheduled_count',            count_workorders('scheduled')                          );
$smarty->assign('global_workorders_with_client_count',          count_workorders('with_client')                        );
$smarty->assign('global_workorders_on_hold_count',              count_workorders('on_hold')                            );
$smarty->assign('global_workorders_management_count',           count_workorders('management')                         );
$smarty->assign('global_workorders_opened_count',               count_workorders('opened')                             );
$smarty->assign('global_workorders_closed_count',               count_workorders('closed')                             );

// Employee Workorder Stats
$smarty->assign('employee_workorders_open_count',               count_workorders('open', $user->login_user_id)               );
$smarty->assign('employee_workorders_assigned_count',           count_workorders('assigned', $user->login_user_id)           );
$smarty->assign('employee_workorders_waiting_for_parts_count',  count_workorders('waiting_for_parts', $user->login_user_id)  );
$smarty->assign('employee_workorders_scheduled_count',          count_workorders('scheduled', $user->login_user_id)          );
$smarty->assign('employee_workorders_with_client_count',        count_workorders('with_client', $user->login_user_id)        );
$smarty->assign('employee_workorders_on_hold_count',            count_workorders('on_hold', $user->login_user_id)            );
$smarty->assign('employee_workorders_management_count',         count_workorders('management', $user->login_user_id)         );
$smarty->assign('employee_workorders_opened_count',             count_workorders('opened', $user->login_user_id)             );
$smarty->assign('employee_workorders_closed_count',             count_workorders('closed', $user->login_user_id)             );

/** Invoices **/

// Global Invoice Stats
$smarty->assign('global_invoices_open_count',                   count_invoices('open')                                 );
$smarty->assign('global_invoices_pending_count',                count_invoices('pending')                              );
$smarty->assign('global_invoices_unpaid_count',                 count_invoices('unpaid')                               );
$smarty->assign('global_invoices_partially_paid_count',         count_invoices('partially_paid')                       );
$smarty->assign('global_invoices_paid_count',                   count_invoices('paid')                                 );
$smarty->assign('global_invoices_opened_count',                 count_invoices('opened')                               );
$smarty->assign('global_invoices_closed_count',                 count_invoices('closed')                               );
$smarty->assign('global_invoiced_total',                        sum_invoices_value('all', 'gross_amount')              );
$smarty->assign('global_received_monies',                       sum_invoices_value('all', 'paid_amount')               );
$smarty->assign('global_outstanding_balance',                   sum_invoices_value('all', 'balance')                   );

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

$date_year_start    = get_company_details('year_start');
$date_year_end      = get_company_details('year_end');

/** Customers **/

// Global Customer Stats
$smarty->assign('customer_month_count', count_customers('all', $date_month_start, $date_month_end)     );
$smarty->assign('customer_year_count',  count_customers('all', $date_year_start, $date_year_end)       );
$smarty->assign('customer_total_count', count_customers('all')                                         );

/** Build the Page **/

// Build the page
$BuildPage .= $smarty->fetch('report/basic_stats.tpl');