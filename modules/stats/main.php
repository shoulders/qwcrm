<?php
$submit = $VAR['submit'];
$from = $VAR['start_date'];
$to = $VAR['end_date'];
//$start_date = $VAR['report_start'];
//$end_date = $VAR['report_end'];
$smarty->assign('end_date', $to);
$smarty->assign('start_date', $from);

//$today_start = mktime(0,0,0,date("m"), date("d"), date("Y"));
//$today_end 	 = mktime(23,59,59,date("m"), date("d"), date("Y"));

//$month_start = mktime(0,0,0,$start_date);

//$month_start = mktime(0,0,0,date("m"), 1, date("Y"));
//$month_end	 = mktime(0,0,0,date("m")+1, 0, date("Y"));


if(isset($submit)){
$from = $VAR['start_date'];
$to = $VAR['end_date'];


/* This formats the two dates from dd/mm/yyyy to proper sql string time*/
     // Start Date
     $date_part = explode("/",$VAR['start_date']);
     $timestamp = mktime(0,0,0,$date_part[1],$date_part[0],$date_part[2]);
     $month_start = ($timestamp);
     
     //End Due Date
     $date_part2 = explode("/",$VAR['end_date']);
     $timestamp2 = mktime(0,0,0,$date_part2[1],$date_part2[0],$date_part2[2]);
     $month_end = ($timestamp2);
     
     
/* open work orders this month */
$q = "SELECT count(*) AS count FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_OPEN_DATE  >= '$month_start' AND WORK_ORDER_OPEN_DATE  <= '$month_end'";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$month_open = $rs->fields['count'];
$smarty->assign('month_open', $month_open);

/* closed work orders this month */
$q = "SELECT count(*) AS count FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_CLOSE_DATE  >= '$month_start' AND WORK_ORDER_CLOSE_DATE  <= '$month_end'";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$month_close = $rs->fields['count'];
$smarty->assign('month_close', $month_close);

/* New Customers this month */
$q = "SELECT count(*) AS count FROM ".PRFX."TABLE_CUSTOMER WHERE CREATE_DATE  >= '$month_start' AND CREATE_DATE  <= '$month_end'";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$new_customers = $rs->fields['count'];
$smarty->assign('new_customers', $new_customers);

/* Total Customers */
$q = "SELECT COUNT(*) AS count FROM ".PRFX."TABLE_CUSTOMER";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$total_customers = $rs->fields['count'];
$smarty->assign('total_customers', $total_customers);

/* Created Invoices */
$q = "SELECT count(*) AS count FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$month_start' AND INVOICE_DUE  <= '$month_end'";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$new_invoices = $rs->fields['count'];
$smarty->assign('new_invoices', $new_invoices);

/* Closed Invoices */
$q = "SELECT count(*) AS count FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$month_start' AND INVOICE_DUE  <= '$month_end' AND INVOICE_PAID = 1";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$paid_invoices = $rs->fields['count'];
$smarty->assign('paid_invoices' , $paid_invoices);

// Sum Costs Invoices
$q = "SELECT SUM(DISCOUNT) AS DISCOUNT FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$month_start' AND INVOICE_DUE  <= '$month_end'";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$discounts = $rs->fields['DISCOUNT'];

//Sum Invoices
$q = "SELECT SUM(SUB_TOTAL) AS sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$month_start' AND INVOICE_DUE  <= '$month_end'";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$sum_invoices = $rs->fields['sum'];
$rev_invoices = $sum_invoices - $discounts;
$smarty->assign('rev_invoices', $rev_invoices);

/* Sum Costs Invoices 
$q = "SELECT SUM(COST) AS cost FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$month_start' AND INVOICE_DUE  <= '$month_end'";
if(!$rs = $db->Execute($q)){
	echo 'Error: '. $db->ErrorMsg();
	die;
}
$costs = $rs->fields['cost'];
$smarty->assign('costs', $costs);*/

}


$smarty->assign('end_date', $to);
$smarty->assign('start_date', $from);
$smarty->display('stats'.SEP.'main.tpl');

?>
