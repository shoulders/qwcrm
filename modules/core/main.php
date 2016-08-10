<?php

//require('includes'.SEP.'modules'.SEP.'core.php');

$wo_id = $VAR['wo_id'];
//$status = 10;
/* Main Home Page */
// Get the page number we are on if first page set to 1
    if(!isset($VAR['page_no']))
    {
        $page_no = 1;
    } else {
        $page_no = $VAR['page_no'];
    }


/* display welcome note */
$smarty->assign('welcome', display_welcome_note($db));



/* work order stats */

/* New Work Order Counts */
$smarty->assign('wo_new_count',count_workorders_with_status($db, 1));

/* Assigned counts */
$smarty->assign('wo_ass_count', count_workorders_with_status($db, 2));


/* waiting for parts count */
$smarty->assign('wo_parts_count', count_workorders_with_status($db, 3));

/* waiting for payment */
$smarty->assign('wo_pay_count', count_workorders_with_status($db, 7));

/* closed */
$smarty->assign('wo_closed_count',count_workorders_with_status($db, 6));

/* WO total count */
$smarty->assign('wo_total_count',count_all_workorders($db));






/** Discount stats **/

/* Sum unpaid Discounts on Invoices */
$unpaid_discounts = sum_unpaid_discounts_on_invoices($db);


// Sum Paid Discounts on Invoices
$q = "SELECT SUM(DISCOUNT) AS DISCOUNT
        FROM ".PRFX."TABLE_INVOICE
        WHERE INVOICE_PAID=".$db->qstr(1);
if(!$rs = $db->Execute($q)){
    echo 'Error: '. $db->ErrorMsg();
    die;
}
$all_discounts = $rs->fields['DISCOUNT'];

// Sum partial Discounts on Invoices
$q = "SELECT SUM(DISCOUNT) AS DISCOUNT
        FROM ".PRFX."TABLE_INVOICE
        WHERE INVOICE_PAID=".$db->qstr(0)." AND BALANCE >".$db->qstr(0);
if(!$rs = $db->Execute($q)){
    echo 'Error: '. $db->ErrorMsg();
    die;
}
$part_discounts = $rs->fields['DISCOUNT'];






/* invoice stats */

/* No. of Invoices with an outstanding balance */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0);
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $in_unpaid_count = $rs->fields['count'];
    $smarty->assign('in_unpaid_count',$in_unpaid_count);
}
/* Sum of balances of Invoices with an outstanding balance */
$q = 'SELECT SUM(balance) as sum FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND  balance >'.$db->qstr(0);
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $in_unpaid_bal = $rs->fields['sum'] ;
    $smarty->assign('in_unpaid_bal',$in_unpaid_bal);
}

/* No. of invoices with a partialy paid balance */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND  balance <> INVOICE_AMOUNT';
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $in_part_count = $rs->fields['count'];
    $smarty->assign('in_part_count',$in_part_count);
}

/* Sum of balances of invoices with a partialy paid balance */
$q = 'SELECT SUM(balance) as sum FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND  balance <> INVOICE_AMOUNT';
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $in_part_bal = $rs->fields['sum'] ;
    $smarty->assign('in_part_bal',$in_part_bal);
}

// unknown use
$in_out_bal = $in_unpaid_bal ;
$smarty->assign('in_out_bal',$in_out_bal);

/* No. of paid invoices (of all time) */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(1);
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $in_paid_count = $rs->fields['count'];
    $smarty->assign('in_paid_count',$in_paid_count);
}

/* All Time Invoice Totals */
$q = 'SELECT SUM(INVOICE_AMOUNT) as sum FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(1);
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $in_total = $rs->fields['sum'];
    $in_total_bal = $in_total - $in_out_bal;
    $in_total2 = $in_total ;

        // Total Invoice Monies Recieved
    $smarty->assign('in_total_bal',$in_total_bal);

        // Total Monies Invoiced
    $smarty->assign('in_total2',$in_total2);
}





/* customer stats */

/*new this month */
$month = mktime(0,0,0,date('m'),0,date('Y'));

$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_CUSTOMER WHERE  CREATE_DATE >= '.$db->qstr($month);
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $cu_month_count = $rs->fields['count'];
    $smarty->assign('cu_month_count',$cu_month_count);
}

/* new this year */
$year = mktime(0,0,0,0,0,date('Y'));

$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_CUSTOMER WHERE  CREATE_DATE >= '.$db->qstr($year);
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $cu_year_count = $rs->fields['count'];
    $smarty->assign('cu_year_count',$cu_year_count);
}

/* total */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_CUSTOMER';
if(!$rs = $db->execute($q)){
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
    exit;
} else {
    $cu_total_count = $rs->fields['count'];
    $smarty->assign('cu_total_count',$cu_total_count);
}



/* Get employee credentials */
$q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN ='".$login_usr."'" ;
$rs = $db->Execute($q);
$cred2 = $rs->FetchRow();

$smarty->display('core'.SEP.'main.tpl');


/* ------------------  company.tpl code ----------------------- */
/* Stats */
/*
$q = 'SELECT count(*) AS OPEN_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS='.$db->qstr(10);
$rs = $db->Execute($q);
$open_count = $rs->fields['OPEN_COUNT'];
$smarty->assign('open_count', $open_count);

/* Assigned Work Orders 
$q = 'SELECT count(*) AS ASSIGNED FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(2);
$rs = $db->Execute($q);
$assigned = $rs->fields['ASSIGNED'];
$smarty->assign('assigned', $assigned);

/* Awaiting Payment 
$q = 'SELECT count(*) AS AWAITING FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(7);
$rs = $db->Execute($q);
$awaiting = $rs->fields['AWAITING'];
$smarty->assign('awaiting', $awaiting);

/* Closed Works Orders 
$q = 'SELECT count(*) AS CLOSED FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_STATUS='.$db->qstr(6);
$rs = $db->Execute($q);
$closed = $rs->fields['CLOSED'];
$smarty->assign('closed', $closed);


$smarty->display('core'.SEP.'company.tpl');
 
*/

////////////////

//These are currently not used anywhere - from header and menu

/* Get Employee Id by Username */
$login_id = get_employee_id_by_username($db, $login_usr);
// or
//$login_id = $_SESSION['login_id'];//
//echo $login_id;

/* Logged in Employee - Open Work Orders */
$smarty->assign('employee_workorders_open_count', count_employee_workorders_with_status($db, $login_id, 10));

/* Logged in Employee - Assigned Work Orders */
$smarty->assign('employee_workorders_assigned_count', count_employee_workorders_with_status($db, $login_id, 2));

/* Logged in Employee - Work Orders Awaiting Payment*/
$smarty->assign('employee_workorders_awaiting_payment_count', count_employee_workorders_with_status($db, $login_id, 7));

/* Logged in Employee - Unpaid Invoices */
$smarty->assign('employee_invoices_unpaid_count', count_employee_invoices_with_status($db, $login_id, 0));

/* Assigned Work Orders - not used in theme header or menu */
$smarty->assign('workorders_assigned_count', count_workorders_with_status($db, 2));


///////////////