<?php
$smarty->assign('VERSION', MYIT_CRM_VERSION);
if(!xml2php("core")) {
	$smarty->assign('error_msg',"Error in language file");
}
$ip = $_SERVER['REMOTE_ADDR'];
$login = $_SESSION['login'];
$wo_id = $VAR['wo_id'];
$cus_id = $VAR['customer_id'];
$employee_id = $VAR['employee_id'];
$today = (Date("l, j F Y")); 
$smarty->assign('today',$today);

if(!$login)
{
	$smarty->assign('login', '');
} else {
	$smarty->assign('login', $login);
	$smarty->assign('display_login', $login);
	$smarty->assign('login_id', $_SESSION['login_id']);
	$smarty->assign('wo_id', $wo_id);
	$smarty->assign('cust_id',$cus_id);
	$smarty->assign('ip',$ip);
	$smarty->assign('employee_id',$employee_id);
}

/* Stats */
$q = 'SELECT count(*) AS OPEN_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS='.$db->qstr(10);
$rs = $db->Execute($q);
$open_count = $rs->fields['OPEN_COUNT'];
$smarty->assign('open_count', $open_count);

/* Assigned Work Orders */
$q = 'SELECT count(*) AS ASSIGNED FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(2);
$rs = $db->Execute($q);
$assigned = $rs->fields['ASSIGNED'];
$smarty->assign('assigned', $assigned);

/* Awaiting Payment */
$q = 'SELECT count(*) AS AWAITING FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(7);
$rs = $db->Execute($q);
$awaiting = $rs->fields['AWAITING'];
$smarty->assign('awaiting', $awaiting);

/* Closed Works Orders */
$q = 'SELECT count(*) AS CLOSED FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_STATUS='.$db->qstr(6);
$rs = $db->Execute($q);
$closed = $rs->fields['CLOSED'];
$smarty->assign('closed', $closed);

$smarty->display('core'.SEP.'header.tpl');
?>
