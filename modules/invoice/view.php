<?php
require_once ('include.php');
if(!xml2php("invoice")) {
	$smarty->assign('error_msg',"Error in language file");
	
}

/* Assign company information */
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$company = $rs->GetArray();
	$smarty->assign('company', $company);

$invoice_id  = $VAR['invoice_id'];
$customer_id = $VAR['customer_id'];

/* Generic error control */
if(empty($invoice_id)) {
	/* If no work order ID then we dont belong here */
	force_page('core', 'error&error_msg=Invoice Not found: Invoice ID: '.$invoice_id.'&menu=1');
}

/* check if we have a customer id and if so get details */
if($customer_id == '' || $customer_id == '0'){
	force_page('core', 'error&error_msg=No Customer ID&menu=1');
	exit;
} else {
	$q = 'SELECT * FROM '.PRFX.'TABLE_CUSTOMER WHERE CUSTOMER_ID='.$db->qstr($customer_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
	$customer_details = $rs->GetAssoc();
	if(empty($customer_details)){
		force_page('core', 'error&error_msg=No Customer details found for Customer ID '.$customer_id.'.&menu=1');
		exit;
	}
	$smarty->assign('customer_details',$customer_details);
	
}

	$rs = $db->execute($q);
	$invoice = $rs->FetchRow();	
	$smarty->assign('invoice',$invoice);
	
	$q = 'SELECT  '.PRFX.'TABLE_INVOICE.*, '.PRFX.'TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM  '.PRFX.'TABLE_INVOICE 
			LEFT JOIN '.PRFX.'TABLE_EMPLOYEE ON ('.PRFX.'TABLE_INVOICE.EMPLOYEE_ID = '.PRFX.'TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE INVOICE_ID='.$db->qstr($invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$invoice = $rs->FetchRow();
	$smarty->assign('invoice',$invoice);
	$smarty->assign('wo_id', $invoice['WORKORDER_ID']);
        
	/* get any labor details */
	$q = 'SELECT * FROM '.PRFX.'TABLE_INVOICE_LABOR WHERE INVOICE_ID='.$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$labor = $rs->GetArray();

	if(empty($labor)){
		$smarty->assign('labor', 0);
	} else {
		$smarty->assign('labor', $labor);
	}

	/* get any parts */
	$q = 'SELECT * FROM '.PRFX.'TABLE_INVOICE_PARTS WHERE INVOICE_ID='.$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$parts = $rs->GetArray();
	
	if(empty($parts)){
		$smarty->assign('parts', 0);
	} else {
		$smarty->assign('parts', $parts);
	}
	
	/* Get transaction information */
	$q = 'SELECT * FROM '.PRFX.'TABLE_TRANSACTION WHERE INVOICE_ID ='.$db->qstr($invoice['INVOICE_ID']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	$trans = $rs->GetArray();

	$smarty->assign('trans',$trans);

        // Sub_total results
        $labour_sub_total_sum = labour_sub_total_sum($db, $VAR['invoice_id']);
        $parts_sub_total_sum = parts_sub_total_sum($db, $VAR['invoice_id']);
        $smarty->assign('labour_sub_total_sum', $labour_sub_total_sum);
        $smarty->assign('parts_sub_total_sum', $parts_sub_total_sum);
	 
	$smarty->display('invoice'.SEP.'view.tpl');
?>
