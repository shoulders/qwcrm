<?php

defined('_QWEXEC') or die;

require_once(INCLUDES_DIR.'modules/core_menu.php');
 
// Get Workorder Status
$smarty->assign('menu_workorder_status', menu_get_single_workorder_status($db, $workorder_id)); 

// Open Work Orders
$smarty->assign('menu_workorders_open_count', menu_count_workorders_with_status($db, 'open'));

// Closed Work Orders
$smarty->assign('menu_workorders_closed_count', menu_count_workorders_with_status($db, 'closed'));

// Unpaid Invoices
$smarty->assign('menu_workorders_unpaid_count', menu_count_invoices_with_status($db, 0));

// Paid Invoices
$smarty->assign('menu_workorders_paid_count', menu_count_invoices_with_status($db, 1));

// Display menu block
$BuildPage .= $smarty->fetch('core/blocks/theme_menu_block.tpl');