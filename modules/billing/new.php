<?php
require_once ("include.php");
if(!xml2php("billing")) {
	$smarty->assign('error_msg',"Error in language file");
}
// Grab customers Information
$wo_id       = $VAR['wo_id'];
$customer_id = $VAR['customer_id'];
$tech        = $_SESSION['login_id'];
$invoice_id  = $VAR['invoice_id'];


/* Generic error control */
if($wo_id == '' && $wo_id != "0") {
	force_page('core', 'error&error_msg=No Work Order ID&menu=1');
	exit;
}

/* check if we have a customer id and if so get details */
if($customer_id == "" || $customer_id == "0"){
	force_page('core', 'error&error_msg=No Customer ID&menu=1');
	exit;
} else {
	$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$customer_details = $rs->GetAssoc();
	if(empty($customer_details)){
		force_page('core', 'error&error_msg=No Customer details found.&menu=1');
		exit;
	}
	$smarty->assign('customer_details',$customer_details);
}

	
/* make sure we have an invoice id*/
if($invoice_id == "" || $invoice_id == "0") {
	force_page('core', 'error&error_msg=No Invoice ID&menu=1');
	exit;
}

/* check if invoice is already paid or there is at least an amount to bill */
$q = "SELECT count(*) as count, INVOICE_AMOUNT, INVOICE_DATE, INVOICE_DUE,INVOICE_ID, PAID_AMOUNT, BALANCE, WORKORDER_ID  FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_PAID='0' AND INVOICE_ID=".$db->qstr($invoice_id)." GROUP BY INVOICE_ID";

if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
}

$invoice_details = $rs->GetAssoc();

if($invoice_details[1]['count'] != 1 ){
	force_page('core', 'error&error_msg=No invoice found for billing&menu=1');
	exit;
}


if ($invoice_details[1]['INVOICE_AMOUNT'] <= 0) {
	force_page('core', 'error&error_msg=Invoice Does not have any amount to bill.&menu=1');
	exit;
}

if($invoice_details[1]['INVOICE_AMOUNT'] > 0 ){
		$q ="SELECT * FROM ".PRFX."TABLE_TRANSACTION WHERE INVOICE_ID =".$db->qstr($invoice_id);
		$rs = $db->execute($q);
		$trans = $rs->GetArray();
		$smarty->assign('trans', $trans);
	}
	
$smarty->assign('invoice_details',$invoice_details);


	/* get billing settings from db */
	$q = "SELECT BILLING_OPTION, ACTIVE FROM ".PRFX."CONFIG_BILLING_OPTIONS WHERE  ACTIVE='1'";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$billing_options = $rs->GetAssoc();	

	if(empty($billing_options)) {
		force_page('core', 'error&error_msg=No Billing Methods Available. Please select billing options in the configuration&menu=1');
		exit;
	}
	
	$smarty->assign('billing_options', $billing_options);

	/* get Accepted Credit cards*/
	if($billing_options['cc_billing'] == '1') {
		
		$q = "SELECT CARD_TYPE, CARD_NAME FROM ".PRFX."CONFIG_CC_CARDS WHERE ACTIVE='1'";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		$cc_cards = $rs->GetAssoc();
		
		if(empty($cc_cards)) {
			force_page('core', 'error&error_msg=Credit Card Billing is Set on but no cards are active. Please enable at least on credit card in the control panel&menu=1');
			exit;
		}
		
		$smarty->assign('cc_cards',$cc_cards);
		
	}	

	
$smarty->display('billing'.SEP.'new.tpl');

?>
