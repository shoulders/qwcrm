<?php
if(!xml2php("core")) {
	$smarty->assign('error_msg',"Error in language file");
}
$ip = $_SERVER['REMOTE_ADDR'];
$id = $login_id;
$login = $_SESSION['login'];
$wo_id = $VAR['wo_id'];
$cus_id = $VAR['customer_id'];
$expenseID = $VAR['expenseID'];
$refundID = $VAR['refundID'];
$supplierID = $VAR['supplierID'];
$employee_id = $VAR['employee_id'];
$today = (Date('l, j F Y'));
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
        $smarty->assign('expenseID', $expenseID);
        $smarty->assign('refundID', $refundID);
        $smarty->assign('supplierID', $supplierID);
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

//Lets add a welcome message based on time

$afternoon = "Good afternoon! $login. ";
$evening = "Good evening! $login. ";
$late = "Working late? $login. ";
$morning = "Good morning! $login. ";
$friday = "Get ready for the weekend! $login. ";

//No need to edit any further

//Get the current hour
$current_time = date('H');
//Get the current day
$current_day = date('l');

//12 a.m. - 5 a.m.
if ($current_time >= 1 && $current_time <= 5) {
$msg= $late;
}
// 6 a.m. to 11 a.m.
elseif ($current_time >= 6 && $current_time <=11) {
$msg = $morning;
}
//12 p.m. - 4 p.m.
elseif ($current_time >= 12 && $current_time <= 16) {
$msg =  $afternoon;
}
// 5 p.m. to 11 p.m.
elseif ($current_time >= 17 && $current_time <= 24) {
$msg =  $evening;
}
//Let get a summary here of my things
/* Lets get employee ID number */
$q = 'SELECT EMPLOYEE_ID FROM '.PRFX.'TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN ='.$db->qstr($login) ;
$rs = $db->Execute($q);
if(!$rs = $db->Execute($q)) {
      echo 'Error:'. $db->ErrorMsg();
   }
$my_id = $rs->fields['EMPLOYEE_ID'];


/* Now lets grab open Works Orders, Assigned and closed as well */
/*Opened to me */
$q = 'SELECT count(*) AS MINE FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_ASSIGN_TO='.$db->qstr($my_id).' AND WORK_ORDER_STATUS='.$db->qstr(10) ;
$rs = $db->Execute($q);
if(!$rs = $db->Execute($q)) {
      echo 'Error:'. $db->ErrorMsg();
   }
$mine = $rs->fields['MINE'];
$smarty->assign('mine', $mine);
/*Assigned to me */
$q = 'SELECT count(*) AS MINE2 FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_ASSIGN_TO='.$db->qstr($my_id).' AND WORK_ORDER_STATUS='.$db->qstr(2) ;
$rs = $db->Execute($q);
if(!$rs = $db->Execute($q)) {
      echo 'Error:'. $db->ErrorMsg();
   }
$mine2 = $rs->fields['MINE2'];
$smarty->assign('mine2', $mine2);
/*Awaiting Payment processing from me*/
$q = 'SELECT count(*) AS MINE3 FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_ASSIGN_TO='.$db->qstr($my_id).' AND WORK_ORDER_STATUS='.$db->qstr(7) ;
$rs = $db->Execute($q);
if(!$rs = $db->Execute($q)) {
      echo 'Error:'. $db->ErrorMsg();
   }
$mine3 = $rs->fields['MINE3'];
$smarty->assign('mine3', $mine3);
/*Un Paid from me*/
$q = 'SELECT count(*) AS MINE4 FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID=0 AND EMPLOYEE_ID='.$db->qstr($my_id) ;
$rs = $db->Execute($q);
if(!$rs = $db->Execute($q)) {
      echo 'Error:'. $db->ErrorMsg();
   }
$mine4 = $rs->fields['MINE4'];
$smarty->assign('mine4', $mine4);

$smarty->assign('msg', $msg);
$smarty->display('core'.SEP.'header.tpl');
?>
