<?php
$wo_id = $VAR['wo_id'];
$status = 10;
/* Main Home Page */
// Get the page number we are on if first page set to 1
	if(!isset($VAR['page_no']))
	{
		$page_no = 1;
	} else {
		$page_no = $VAR['page_no'];
	}
// Load the required includes
require_once ('.'.SEP.'modules'.SEP.'workorder'.SEP.'include.php');


/* stats */
/* New Work Order Counts */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(1);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$wo_new_count = $rs->fields['count'];
	$smarty->assign('wo_new_count',$wo_new_count);
}

/* Assigned counts */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(2);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$wo_ass_count = $rs->fields['count'];
	$smarty->assign('wo_ass_count',$wo_ass_count);
}

/* waiting for parts count */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(3);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$wo_parts_count = $rs->fields['count'];
	$smarty->assign('wo_parts_count',$wo_parts_count);
}

/* waiting for payment */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(7);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$wo_pay_count = $rs->fields['count'];
	$smarty->assign('wo_pay_count',$wo_pay_count);
}

/* closed */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_STATUS='.$db->qstr(6);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$wo_closed_count = $rs->fields['count'];
	$smarty->assign('wo_closed_count',$wo_closed_count);
}

/* total */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_WORK_ORDER';
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$wo_total_count = $rs->fields['count'];
	$smarty->assign('wo_total_count',$wo_total_count);
}

/* invoice stats */
/* unpaid Invoices */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND  balance='.$db->qstr(0);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$in_unpaid_count = $rs->fields['count'];
	$smarty->assign('in_unpaid_count',$in_unpaid_count);
}
/* sum of unpaid */
$q = 'SELECT SUM(INVOICE_AMOUNT) as sum FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND  balance='.$db->qstr(0);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$in_unpaid_bal = $rs->fields['sum'];
	$smarty->assign('in_unpaid_bal',$in_unpaid_bal);
}

/* Partial*/
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND  balance > '.$db->qstr(0);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$in_part_count = $rs->fields['count'];
	$smarty->assign('in_part_count',$in_part_count);
}

/* sum */
$q = 'SELECT SUM(balance) as sum FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND  balance >'.$db->qstr(0);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$in_part_bal = $rs->fields['sum'];
	$smarty->assign('in_part_bal',$in_part_bal);
}

$in_out_bal = $in_unpaid_bal + $in_part_bal;
$smarty->assign('in_out_bal',$in_out_bal);

/* paid */
$q = 'SELECT count(*) as count FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(1);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$in_paid_count = $rs->fields['count'];
	$smarty->assign('in_paid_count',$in_paid_count);
}

/* total */
$q = 'SELECT SUM(INVOICE_AMOUNT) as sum FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(1);
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$in_total = $rs->fields['sum'];
	$in_total_bal = $in_total - $in_out_bal; 
	$in_total2 = $in_total + $in_out_bal;
	$smarty->assign('in_total_bal',$in_total_bal);
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

/* display welcome note */
$q = 'SELECT WELCOME_NOTE FROM '.PRFX.'SETUP';
if(!$rs = $db->execute($q)){
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
} else {
	$smarty->assign('welcome',$rs->fields['WELCOME_NOTE']);
}
/* Get employee credentials */
$q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME ='".$login."'" ;
$rs = $db->Execute($q);
$cred2 = $rs->FetchRow();

$smarty->display('core'.SEP.'main.tpl');

?>
