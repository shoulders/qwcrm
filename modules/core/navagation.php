<?php
if(!xml2php("core")) {
	$smarty->assign('error_msg',"Error in language file");
}
//$employee_id = $VAR['employee_id'];
$sch_id = $VAR['sch_id'];
$today2 = (Date("d")); 
if ( $cur_date > 0 )
{
$y1 = $VAR['y'] ;
$m1 = $VAR['m'];
$d1 = $VAR['d'];
} else {
$y1 =	(Date("Y"));
$m1 =	(Date("m"));
$d1 =	(Date("d"));
}
$smarty->assign('y1',$y1);
$smarty->assign('m1',$m1);
$smarty->assign('d1',$d1);
$smarty->assign('Y',$Y);
$smarty->assign('m',$m);
$smarty->assign('d',$d);
$smarty->assign('today2',$today2);



if ($VAR['wo_id'] == '' || $VAR['wo_id'] < "1" )
{
$wo_id = 0 ;
} else {
$wo_id = $VAR['wo_id'] ;
$woid = $VAR['wo_id'] ;
}

/*if ($VAR['woid'] == '' || $VAR['woid'] < "1" )
{
$wo_id = 0 ;
} else {
$wo_id = $VAR['woid'] ;
}
*/

/* get work order Information */
$q = "SELECT * FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID ='".$wo_id."'" ;
$rs = $db->Execute($q);
$status2 = $rs->FetchRow();

/* Un- Assigned Work Orders */
$q = 'SELECT count(*) AS OPEN_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS=10';
$rs = $db->Execute($q);
$opened = $rs->fields['OPEN_COUNT'];
$smarty->assign('opened', $opened);

$q = 'SELECT count(*) AS ASSIGNED FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS=2';
$rs = $db->Execute($q);
$assigned2 = $rs->fields['ASSIGNED'];
$smarty->assign('assigned2', $assigned2);

/* Unpaid Invoices */
$q ='SELECT COUNT(*) AS UNPAID FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID=0';
$rs = $db->Execute($q);
$unpaid = $rs->fields['UNPAID'];
$smarty->assign('unpaid', $unpaid);

/* Paid Invoices */
$q ='SELECT COUNT(*) AS PAID FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID=1';
$rs = $db->Execute($q);
$paid = $rs->fields['PAID'];
$smarty->assign('paid', $paid);

/* Work out WO that are un-assigned */
$unassigned = $opened - $assigned2 ;

/* Get employee credentials */
$q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME ='".$login."'" ;
$rs = $db->Execute($q);
$cred = $rs->FetchRow();


//$smarty->assign('login',$login);
$smarty->assign('cred',$cred);
$smarty->assign('unassigned',$unassigned);
$smarty->assign('status2',$status2);	
$smarty->display('core'.SEP.'navagation.tpl');

?>