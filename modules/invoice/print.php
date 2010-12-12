<?php

// BOF shared variables for invoicing

require_once ('include.php');
header('Content-type: text/html; charset=utf-8');
if(!xml2php("invoice")) {
	$smarty->assign('error_msg',"Error in language file");
}

// Load PHP Language Translations
$langvals = gateway_xml2php('invoice');

$invoice_id  = $VAR['invoice_id'];
$customer_id = $VAR['customer_id'];
$print_type = $VAR['print_type'];

/* Generic error control */
if(empty($invoice_id)) {
	/* If no work order ID then we dont belong here */
	force_page('core', 'error&error_msg=Invoice Not found: Invoice ID: '.$invoice_id.'&menu=1');
}

// Customer Section

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

// Invoice Section

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

// Work Order Section

        /* get specific workorder details from database */
	 $q = "SELECT * FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($invoice['WORKORDER_ID']);

	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		$stats = $rs->FetchRow();

        /* get workorder status  */
	 $q = "SELECT * FROM ".PRFX."CONFIG_WORK_ORDER_STATUS WHERE CONFIG_WORK_ORDER_STATUS_ID=".$db->qstr($stats['WORK_ORDER_STATUS']);

	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		$stats2 = $rs->FetchRow();

// Labour Section

	/* get any labour database rows */
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE_LABOR WHERE INVOICE_ID=".$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$labor = $rs->GetArray();
        
        /* Sum Labour Sub Totals */

        $labour_sub_total_sum = labour_sub_total_sum($db, $invoice_id);

        //Labour Lookup for PDF - uses a mysql query rather than an arrray
        $query=mysql_query('select INVOICE_LABOR_UNIT, INVOICE_LABOR_DESCRIPTION, INVOICE_LABOR_RATE, INVOICE_LABOR_SUBTOTAL from '.PRFX.'TABLE_INVOICE_LABOR WHERE INVOICE_ID='.$db->qstr($invoice['INVOICE_ID']));
        $labour_row_pdf = $query or die(mysql_error() . '<br />'. $query);

// Parts Section

	/* get any parts database rows */
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE_PARTS WHERE INVOICE_ID=".$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$parts = $rs->GetArray();

        /* Sum Parts Sub Total */

        $parts_sub_total_sum = parts_sub_total_sum($db, $invoice_id);

        //Parts Lookup for PDF - uses a mysql query rather than an arrray
        // mysql_select_db( $DB_NAME , $link );
        // $query=mysql_query('select INVOICE_PARTS_COUNT, INVOICE_PARTS_DESCRIPTION, INVOICE_PARTS_AMOUNT from '.PRFX.'TABLE_INVOICE_PARTS WHERE INVOICE_ID='.$db->qstr($invoice['INVOICE_ID']),$link);
        $query=mysql_query('select INVOICE_PARTS_COUNT, INVOICE_PARTS_DESCRIPTION, INVOICE_PARTS_AMOUNT, INVOICE_PARTS_SUBTOTAL from '.PRFX.'TABLE_INVOICE_PARTS WHERE INVOICE_ID='.$db->qstr($invoice['INVOICE_ID']));
        $parts_row_pdf = $query or die(mysql_error() . '<br />'. $query);

// Misc Section

        /* get payment history */
	 $q = "SELECT * FROM ".PRFX."TABLE_TRANSACTION WHERE WORKORDER_ID=".$db->qstr($invoice['WORKORDER_ID']);

	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		$payments = $rs->FetchRow();

/* get printing options */
$q = "SELECT * FROM ".PRFX."SETUP";
$rs = $db->execute($q);
//$html_print = $rs->fields['HTML_PRINT'];
//$pdf_print  = $rs->fields['PDF_PRINT'];
$thank_you  =  $rs->fields['INV_THANK_YOU'];
$CHECK_PAYABLE  =  $rs->fields['CHECK_PAYABLE'];
$DD_NAME  =  $rs->fields['DD_NAME'];
$DD_BANK  =  $rs->fields['DD_BANK'];
$DD_BSB  =  $rs->fields['DD_BSB'];
$DD_ACC  =  $rs->fields['DD_ACC'];
$DD_INS  =  $rs->fields['DD_INS'];
$PP_ID  =  $rs->fields['PP_ID'];
$PAYMATE_LOGIN  =  $rs->fields['PAYMATE_LOGIN'];
$PAYMATE_FEES  =  $rs->fields['PAYMATE_FEES'];

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
$currency_sym = utf8_decode($company1['COMPANY_CURRENCY_SYMBOL']);

//Customer Details
$cusdisplay = $customer1['CUSTOMER_DISPLAY_NAME'];
$cusnamef = $customer1['CUSTOMER_FIRST_NAME'];
$cusnamel = $customer1['CUSTOMER_LAST_NAME'];
$cusaddress = $customer1['CUSTOMER_ADDRESS'];
$cuscity = $customer1['CUSTOMER_CITY'];
$cuszip = $customer1['CUSTOMER_ZIP'];
$cusstate = $customer1['CUSTOMER_STATE'];
$cusphone = $customer1['CUSTOMER_PHONE'];
$cusemail = $customer1['CUSTOMER_EMAIL'];
$custerms = $customer1['CREDIT_TERMS'];

// work Order Details
$wo_description = $stats['WORK_ORDER_DESCRIPTION'];
$wo_resolution = $stats['WORK_ORDER_RESOLUTION'];

//invoice details
$totalinv = $invoice3['SUB_TOTAL'];
$discinv = $invoice3['DISCOUNT'];
$shipinv = $invoice3['SHIPPING'];
$taxinv = $invoice3['TAX'];
$paidamntinv = $invoice3['PAID_AMOUNT'];

$amntinv = $invoice3['INVOICE_AMOUNT'];


//$balinv = sprintf( "%.2f",$balinv);
$balinv = sprintf( "%.2f",$invoice3['BALANCE']);

//PayPal Amount with 1.5% Surcharge Applied
$pamount= ($balinv)* 1.015;
$pamount = sprintf( "%.2f",$pamount);

//Paymate Amount with Surcharge Applied
$paymate_amt= ($balinv)* ((($setup1['PAYMATE_FEES'])/100)+1);
$paymate_amt = sprintf( "%.2f",$paymate_amt);

/* get Date Formatting value from database and assign it to $format*/
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$format = $rs->fields['COMPANY_DATE_FORMAT'];
	}

// Stripping out the percentage signs so php can render it correctly
$literals = "%";
$Dformat = str_replace($literals, "", $format);
//Now lets display the right date format
if($Dformat == 'd/m/Y' || $Dformat == 'd/m/y'  ){
$date_format = "d/m/Y";}
elseif($Dformat == 'm/d/Y' || $Dformat == 'm/d/y' ){
$date_format = "m/d/Y";}

// EOF shared variables for invoicing

// BOF HTML Printing Section

if($print_type == 'html') {

    /* html Print out */

	if(empty($labor)){$smarty->assign('labor', 0);} else {$smarty->assign('labor', $labor);}
	if(empty($parts)){$smarty->assign('parts', 0);} else {$smarty->assign('parts', $parts);}
	if(empty($stats)){$smarty->assign('stats', 0);} else {$smarty->assign('stats', $stats);}
	if(empty($stats2)){$smarty->assign('stats2', 0);} else {$smarty->assign('stats2', $stats2);}
	if(empty($payments)){$smarty->assign('payments', 0);} else {$smarty->assign('payments', $payments);}
	if(empty($paid)){$smarty->assign('paid', 0);} else {$smarty->assign('paid', $paid);}

	$smarty->assign('thank_you',$thank_you);
	$smarty->assign('trans',$trans);
	$smarty->assign('paid',$paid);
	$smarty->assign('customer_details',$customer_details);
        $smarty->assign('customer1',$customer1);
	$smarty->assign('invoice',$invoice);
	$smarty->assign('PP_ID', $PP_ID);
        $smarty->assign('DD_NAME', $DD_NAME);
        $smarty->assign('DD_BSB', $DD_BSB);
        $smarty->assign('DD_ACC', $DD_ACC);
        $smarty->assign('DD_INS', $DD_INS);
        $smarty->assign('DD_BANK', $DD_BANK);
        $smarty->assign('CHECK_PAYABLE',$CHECK_PAYABLE);
        $smarty->assign('PAYMATE_LOGIN',$PAYMATE_LOGIN);
	$smarty->assign('company',$company);
	$smarty->assign('company2',$company2);
	//$smarty->assign('CURRENCY_CODE',$CURRENCY_CODE);
        //$smarty->assign('currency_sym',$currency_sym);
        $smarty->assign('country',$country);
        $smarty->assign('pamount',$pamount);
        $smarty->assign('paymate_amt',$paymate_amt);
        $smarty->assign('PAYMATE_FEES',$PAYMATE_FEES);
        $smarty->assign('parts_sub_total_sum', $parts_sub_total_sum);
        $smarty->assign('labour_sub_total_sum', $labour_sub_total_sum);
        $smarty->assign('wo_description', $wo_description);
        $smarty->assign('wo_resolution', $wo_resolution);

	$smarty->display('invoice'.SEP.'print_html.tpl');

}	 else {

// EOF HTML Printing Section

// BOF PDF Printing Section

if($print_type == 'pdf') {

    require_once FILE_ROOT.'templates/invoice/print_pdf_tpl.php'; //This loads the PDF template file

        }	 else {
            
                        force_page('core', "error&menu=1&error_msg=No Printing Options set. Please set up printing options in the Control Center.&type=error");
                        exit;
}
}
// EOF PDF Printing Section

?>
