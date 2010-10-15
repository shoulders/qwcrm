<?php
if(!xml2php("billing")) {
	$smarty->assign('error_msg',"Error in language file");
}
$paypal_amount  = $VAR['paypal_amount'];
$customer_id	= $VAR['customer_id'];
$invoice_id	= $VAR['invoice_id'];
$workorder_id	= $VAR['workorder_id'];

/* get company Info */
$q = "SELECT COMPANY_NAME, COMPANY_COUNTRY FROM ".PRFX."TABLE_COMPANY";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$company			= $rs->fields['COMPANY_NAME'];
$country			= $rs->fields['COMPANY_COUNTRY'];

/* get invoice details */
$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
}
$invoice_details = $rs->FetchRow();
//Check to see if we are processing more then required
if($invoice_details['BALANCE'] < $paypal_amount){
		force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'	&invoice_id='.$invoice_id.'&error_msg= You can not bill more than the amount of the invoice.');
			exit;
	}

/* get pay pal login */
$q = "SELECT PP_ID FROM ".PRFX."SETUP";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$pay_pal_email	= $rs->fields['PP_ID'];

/* get invoice totals */
$amount		= $VAR['paypal_amount'];
$invoice_id	= $VAR['invoice_id'];

//$content = "cmd=_xclick&business=".$pay_pal_email."&item_name=".$company."&item_number=".$invoice_id."&description=Invoice#&amount=".$amount."&no_note=0&currency_code=".$curency_code."&lc=".$country."&bn=PP-BuyNowBF";
//
//// Include the paypal library
//include_once ('payments/Paypal.php');
//
//// Create an instance of the paypal library
//$myPaypal = new Paypal();
//
//// Specify your paypal email
//$myPaypal->addField('business', $pay_pal_email);
//
//// Specify the currency
//$myPaypal->addField('currency_code', $currency_code);
//
//// Specify the url where paypal will send the user on success/failure
//$myPaypal->addField('return', 'payments/paypal_success.php');
//$myPaypal->addField('cancel_return', 'payments/paypal_failure.php');
//
//// Specify the url where paypal will send the IPN
//$myPaypal->addField('notify_url', 'payments/paypal_ipn.php');
//
//// Specify the product information
//$myPaypal->addField('item_name', $invoice_id);
//$myPaypal->addField('amount', $amount);
//$myPaypal->addField('item_number', $VAR['workorder_id']);
//$myPaypal->addField('description', $company);
//
//// Specify any custom value
////$myPaypal->addField('description', $company 'Invoice#'$invoice_id );
//
//// Enable test mode if needed
////$myPaypal->enableTestMode();
//
//// Let's start the train!
//$myPaypal->submitPayment();

$smarty->assign('invoice_id', $invoice_id);
$smarty->assign('amount', $amount);
$smarty->assign('wo_id', $VAR['workorder_id']);
$smarty->display('billing'.SEP.'proc_paypal.tpl');

?>





