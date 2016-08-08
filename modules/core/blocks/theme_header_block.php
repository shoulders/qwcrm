<?php

require_once (__DIR__.'/../include.php');

/* General Stats */
/* Open Work Orders */
$smarty->assign('open_count', count_open_work_orders($db));

/* Assigned Work Orders */
$smarty->assign('assigned', count_assigned_work_orders($db));

/* Awaiting Payment */
$smarty->assign('awaiting', count_work_orders_awaiting_payment($db));

/* Closed Works Orders */
$smarty->assign('closed', count_closed_work_orders($db));



// Lets add a welcome message based on time

$afternoon  = "Good afternoon! $login_usr. ";
$evening    = "Good evening! $login_usr. ";
$late       = "Working late? $login_usr. ";
$morning    = "Good morning! $login_usr. ";
$friday     = "Get ready for the weekend! $login_usr. ";

// No need to edit any further

// Get the current hour
$current_time = date('H');
// Get the current day
$current_day = date('l');

// 12 a.m. - 5 a.m.
if ($current_time >= 1 && $current_time <= 5) {
    $msg= $late;
}
// 6 a.m. to 11 a.m.
elseif ($current_time >= 6 && $current_time <=11) {
    $msg = $morning;
}
// 12 p.m. - 4 p.m.
elseif ($current_time >= 12 && $current_time <= 16) {
    $msg =  $afternoon;
}
// 5 p.m. to 11 p.m.
elseif ($current_time >= 17 && $current_time <= 24) {
    $msg =  $evening;
}



/* Lets get employee ID number */
// this might be called in several places and is a prime candidate for double code */
$login_id = get_employee_id_by_username($db, $login_usr);   


/* Logged in Employee - Open Work Orders */
$smarty->assign('mine', count_employee_open_work_orders($db, $login_id));

/* Logged in Employee - Assigned Work Orders */
$smarty->assign('mine2', count_employee_assigned_work_orders($db, $login_id));

/* Logged in Employee - Work Orders Awaiting Payment*/
$smarty->assign('mine3', count_employee_work_orders_awaiting_payment($db, $login_id));

/* Logged in Employee - Unpaid Invocies */
$smarty->assign('mine4', count_employee_unpaid_invoices($db, $login_id));




$smarty->display('core'.SEP.'blocks'.SEP.'theme_header_block.tpl');