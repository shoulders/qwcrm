<?php


//$customer_id $employee_id
        
/* Open Work Orders */
$smarty->assign('menu_workorders_open_count', count_workorders_with_status($db, 10));

/* Closed Work Orders */
$smarty->assign('menu_workorders_closed_count', count_workorders_with_status($db, 6));

/* Get Workorder Details */
$smarty->assign('menu_workorder_record', display_single_workorder_record($db, $wo_id)); 

/* Unpaid Invoices */
$smarty->assign('menu_workorders_unpaid_count', count_invoices_with_status($db, 0));

/* Paid Invoices */
$smarty->assign('menu_workorders_paid_count', count_invoices_with_status($db, 1));




/* Get Employee Details */ // set to get employee type - not used now
//$smarty->assign('menu_employee_record', get_employee_record_by_username($db, $login_usr));





/* Un-Assigned Work Orders - Work out WO that are un-assigned */
$smarty->assign('menu_workorders_unassigned', count_unassigned_workorders($db));

$smarty->display('core'.SEP.'blocks'.SEP.'theme_menu_block.tpl');