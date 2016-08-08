<?php

require_once (__DIR__.'/../include.php');

/* Open Work Orders */
$smarty->assign('opened', count_open_work_orders($db));

/* Assigned Work Orders */
$smarty->assign('assigned2', count_assigned_work_orders($db));

/* Unpaid Invoices */
$smarty->assign('unpaid', count_unpaid_invoices($db));

/* Paid Invoices */
$smarty->assign('paid', count_paid_invoices($db));

/* Get employee credentials */
$smarty->assign('cred', get_employee_credentials_by_username($db, $login_usr));

/* Get work order Information */
$smarty->assign('status2',display_single_workorder_record($db, $wo_id)); 




/* Un-Assigned Work Orders - Work out WO that are un-assigned */
$unassigned = $opened - $assigned2;
$smarty->assign('unassigned',$unassigned);
   
$smarty->display('core'.SEP.'blocks'.SEP.'theme_menu_block.tpl');