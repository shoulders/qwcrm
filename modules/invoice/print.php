<?php
require_once ('include.php');
if(!xml2php("invoice")) {
	$smarty->assign('error_msg',"Error in language file");
}

$invoice_id  = $VAR['invoice_id'];
$customer_id = $VAR['customer_id'];
//$workorder_id = $VAR['workorder_id';
//$amountpaid = $payments.AMOUNT;


/* Generic error control */
if(empty($invoice_id)) {
	/* If no work order ID then we dont belong here */
	force_page('core', 'error&error_msg=Invoice Not found: Invoice ID: '.$invoice_id.'&menu=1');
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
		force_page('core', 'error&error_msg=No Customer details found for Customer ID '.$customer_id.'.&menu=1');
		exit;
	}
	
	
}

	/* get invoice details */
	$q = "SELECT  ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM  ".PRFX."TABLE_INVOICE 
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE INVOICE_ID=".$db->qstr($invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$invoice = $rs->FetchRow();
	//print($invoice);
	
/* get workorder status */
	 $q = "SELECT * FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($invoice['WORKORDER_ID']);
	
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		$stats = $rs->FetchRow();
		
/* get workorder status description */
	 $q = "SELECT * FROM ".PRFX."CONFIG_WORK_ORDER_STATUS WHERE CONFIG_WORK_ORDER_STATUS_ID=".$db->qstr($stats['WORK_ORDER_STATUS']);
	
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		$stats2 = $rs->FetchRow();	
    	
	/* get any labor details */
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE_LABOR WHERE INVOICE_ID=".$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$labor = $rs->GetArray();

	/* get any parts */
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE_PARTS WHERE INVOICE_ID=".$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$parts = $rs->GetArray();
	
/* get payment history */
	 $q = "SELECT * FROM ".PRFX."TABLE_TRANSACTION WHERE WORKORDER_ID=".$db->qstr($invoice['WORKORDER_ID']);
	
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		$payments = $rs->FetchRow();
		
			

/* get printing options */
$q = "SELECT  HTML_PRINT, PDF_PRINT, INV_THANK_YOU, PP_ID, CHECK_PAYABLE, DD_NAME, DD_BANK, DD_BSB, DD_ACC, DD_INS  FROM ".PRFX."SETUP";
$rs = $db->execute($q);
$html_print = $rs->fields['HTML_PRINT'];
$pdf_print  = $rs->fields['PDF_PRINT'];
$thank_you  =  $rs->fields['INV_THANK_YOU'];
$CHECK_PAYABLE  =  $rs->fields['CHECK_PAYABLE'];
$DD_NAME  =  $rs->fields['DD_NAME'];
$DD_BANK  =  $rs->fields['DD_BANK'];
$DD_BSB  =  $rs->fields['DD_BSB'];
$DD_ACC  =  $rs->fields['DD_ACC'];
$DD_INS  =  $rs->fields['DD_INS'];
$PP_ID  =  $rs->fields['PP_ID'];

/* Assign company information */
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
$rs = $db->Execute($q);
$company = $rs->GetArray();

/* Get company information */
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
$rs = $db->Execute($q);
$company2 = $rs->FetchRow();

$q = "SELECT * FROM ".PRFX."SETUP;";
if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		$setup1 = $rs->FetchRow();


$q = "SELECT * FROM ".PRFX."TABLE_COMPANY;";
if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		$company1 = $rs->FetchRow();
		$smarty->assign('sss',$thank_you);

/* check if we have a customer id and if so get details */
	$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}

	$customer1 = $rs->FetchRow();
	if(empty($customer1)){
		force_page('core', 'error&error_msg=No Customer details found for Customer ID '.$customer_id.'.&menu=1');
		exit;
	}
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$invoice3 = $rs->FetchRow();


//Company Details
$cname = $company1['COMPANY_NAME'];
$caddress = $company1['COMPANY_ADDRESS'];
$ccity = $company1['COMPANY_CITY'];
$cstate = $company1['COMPANY_STATE'];
$cphone = $company1['COMPANY_PHONE'];
$cemail = $company1['COMPANY_EMAIL'];
$cabn = $company1['COMPANY_ABN'];
$cthankyou = $setup1['INV_THANK_YOU'];

//Customer Details
$cusnamef = $customer1['CUSTOMER_FIRST_NAME'];
$cusnamel = $customer1['CUSTOMER_LAST_NAME'];
$cusaddress = $customer1['CUSTOMER_ADDRESS'];
$cuscity = $customer1['CUSTOMER_CITY'];
$cuszip = $customer1['CUSTOMER_ZIP'];
$cusstate = $customer1['CUSTOMER_STATE'];
$cusphone = $customer1['CUSTOMER_PHONE'];
$cusemail = $customer1['CUSTOMER_EMAIL'];

//invoice details
$totalinv = $invoice3['SUB_TOTAL'];
$taxinv = $invoice3['TAX'];
//$balinv = $invoice3['BALANCE'];
$paidamntinv = $invoice3['PAID_AMOUNT'];
$discinv = $invoice3['DISCOUNT'];
$amntinv = $invoice3['INVOICE_AMOUNT'];
$shipinv = $invoice3['SHIPPING'];

if ($invoice3['INVOICE_PAID'] = 1){
	$balinv = $invoice3['BALANCE'];}

if ($invoice3['BALANCE'] < 1){
	$balinv = ($amntinv-$paidamntinv);
	}
$balinv = sprintf( "%.2f",$balinv);

//PayPal Amount with 1.5% Surcharge Applied
  $pamount= ($balinv)* 1.015;
  $pamount = sprintf( "%.2f",$pamount);


if($html_print == 1) {
/* html Print out */
	if(empty($labor)){
		$smarty->assign('labor', 0);
	} else {
		$smarty->assign('labor', $labor);
	}
	
	if(empty($parts)){
		$smarty->assign('parts', 0);
	} else {
		$smarty->assign('parts', $parts);
	}
	if(empty($stats)){
		$smarty->assign('stats', 0);
	} else {
		$smarty->assign('stats', $stats);
	}
	if(empty($stats2)){
		$smarty->assign('stats2', 0);
	} else {
		$smarty->assign('stats2', $stats2);
	}
	if(empty($payments)){
		$smarty->assign('payments', 0);
	} else {
		$smarty->assign('payments', $payments);
	}
	if(empty($paid)){
		$smarty->assign('paid', 0);
	} else {
		$smarty->assign('paid', $paid);
	}
	
	//$ppamount = $invoice.INVOICE_AMOUNT-$payments.AMOUNT ;
	
	$smarty->assign('thank_you',$thank_you);
	$smarty->assign('trans',$trans);
	$smarty->assign('paid',$paid);
	$smarty->assign('customer_details',$customer_details);
	$smarty->assign('invoice',$invoice);
	$smarty->assign('PP_ID', $PP_ID);
        $smarty->assign('DD_NAME', $DD_NAME);
        $smarty->assign('DD_BSB', $DD_BSB);
        $smarty->assign('DD_ACC', $DD_ACC);
        $smarty->assign('DD_INS', $DD_INS);
        $smarty->assign('DD_BANK', $DD_BANK);
        $smarty->assign('CHECK_PAYABLE',$CHECK_PAYABLE);
	$smarty->assign('company',$company);
	$smarty->assign('company2',$company2);
	$smarty->assign('currency_code',$currency_code);
        $smarty->assign('currency_sym',$currency_sym);
         $smarty->assign('country',$country);
        $smarty->assign('pamount',$pamount);
	$smarty->display('invoice'.SEP.'print.tpl');
	

}	 else {
	force_page('core', "error&menu=1&error_msg=No Printing Options set. Please set up printing options in the Control Center.&type=error");
	exit;
}?>
