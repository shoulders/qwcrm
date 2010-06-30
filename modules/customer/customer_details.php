<?php
require_once("include.php");
if(!xml2php("customer")) {
	$smarty->assign('error_msg',"Error in language file");
}
// Get the customers id from the url
$customer_id = $VAR['customer_id'];

$q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME ='".$login."'" ;
$rs = $db->Execute($q);
$employee_details = $rs->FetchRow();
$smarty->assign('employee_details', $employee_details);
//To Get Customer address information for gmaps URL
$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID ='".$customer_id."'" ;
$rs = $db->Execute($q);
$customer_details = $rs->FetchRow();
$smarty->assign('customer_details', $customer_details);

$q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
$rs = $db->Execute($q);
$company_details2 = $rs->FetchRow(1);
$smarty->assign('company_details2', $employee_details2);

//Determine if Employye Work from Hoem or Office for Google Maps URL former
if ($employee_details['EMPLOYEE_BASED'] > 0){
$caddress2 = $employee_details['EMPLOYEE_ADDRESS'];
$ccity2 = $employee_details['EMPLOYEE_CITY'];
$czip2 = $employee_details['EMPLOYEE_ZIP'];
} else {
$caddress2 = $company_details2['COMPANY_ADDRESS'];
$ccity2 = $company_details2['COMPANY_CITY'];
$czip2 = $company_details2['COMPANY_ZIP'];

}
//Employee Details for parsing to Google Maps URL
$smarty->assign('caddress2',$caddress2);
$smarty->assign('ccity2',$ccity2);
$smarty->assign('czip2',$czip2);

//Customer Address for parsing to google maps URL
$cusaddress3 = $customer_details['CUSTOMER_ADDRESS'];
$cuscity3 = $customer_details['CUSTOMER_CITY'];
$cuszip3 = $customer_details['CUSTOMER_ZIP'];

//Assign these to Smarty
$smarty->assign('cusaddress3',$cusaddress3);
$smarty->assign('cuscity3',$cuscity3);
$smarty->assign('cuszip3',$cuszip3);

//Google Maps URL for IFrame in customer_details.tpl
$f_caddress2 = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $caddress2);
$f_cusaddress3 = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $cusaddress3);

$src= "http://maps.google.com/maps?f=d&source=s_d&hl=en&geocode=&saddr=$f_caddress2,$ccity2,$czip2&daddr=$f_cusaddress3,$cuscity3,$cuszip3";
$smarty->assign('src',$src);

// assign the arrays
$smarty->assign('open_work_orders',	display_open_workorders($db, $customer_id));
$smarty->assign('closed_work_orders',	display_closed_workorders($db, $customer_id));
$smarty->assign('customer_details',	display_customer_info($db, $customer_id));
$smarty->assign('unpaid_invoices', display_unpaid_invoices($db,$customer_id));
$smarty->assign('paid_invoices', display_paid_invoices($db,$customer_id));
$smarty->assign('memo',	display_memo($db,$customer_id));
$smarty->assign('gift',	display_gift($db, $customer_id));
$smarty->assign('company_details',display_company_info($db, $company_id));


$smarty->display('customer'.SEP.'customer_details.tpl');


?>
