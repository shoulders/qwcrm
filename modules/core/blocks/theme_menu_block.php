<?php

require_once('includes'.SEP.'modules'.SEP.'core_menu.php');
 
/* Get Workorder Status */
$smarty->assign('menu_workorder_status', menu_get_single_workorder_status($db, $workorder_id)); 

/* Open Work Orders */
$smarty->assign('menu_workorders_open_count', menu_count_workorders_with_status($db, 10));

/* Closed Work Orders */
$smarty->assign('menu_workorders_closed_count', menu_count_workorders_with_status($db, 6));

/* Un-Assigned Work Orders - Work out WO that are un-assigned */
$smarty->assign('menu_workorders_unassigned', menu_count_unassigned_workorders($db));

/* Unpaid Invoices */
$smarty->assign('menu_workorders_unpaid_count', menu_count_invoices_with_status($db, 0));

/* Paid Invoices */
$smarty->assign('menu_workorders_paid_count', menu_count_invoices_with_status($db, 1));

$smarty->display('core'.SEP.'blocks'.SEP.'theme_menu_block.tpl');