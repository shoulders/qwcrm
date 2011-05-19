<?php
$invoice_id	= $VAR['invoice_id'];
$workorder_id	= $VAR['wo_id'];
$customer_id	= $VAR['customer_id'];
$amount = $VAR['amount'];

if($VAR['submit']) {

	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
	$invoice_details    = $rs->FetchRow();
	$customer_id        = $invoice_details['CUSTOMER_ID'];
	$amount             = $VAR['amount'];

		$memo = "PayPal Payment Made of $currency_sym$amount, PayPal ID ".$VAR['pp_invoice'];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '5',
			INVOICE_ID              = ".$db->qstr($invoice_id).",
			WORKORDER_ID            = ".$db->qstr($workorder_id).",
			CUSTOMER_ID             = ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT			= ".$db->qstr($amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		/* update the invoice */	
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			PAID_DATE  			= ".$db->qstr(time()).", 
			PAID_AMOUNT 			= ".$db->qstr($amount).",
			INVOICE_PAID			= '1',
			BALANCE 			= ".$db->qstr(0.00)."
			WHERE INVOICE_ID                = ".$db->qstr($invoice_id);
			
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		/* update work order */
		$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			WORK_ORDER_ID			= ".$db->qstr($workorder_id).",
			WORK_ORDER_STATUS_DATE 		= ".$db->qstr(time()).",
			WORK_ORDER_STATUS_NOTES 	= ".$db->qstr($memo).",
			WORK_ORDER_STATUS_ENTER_BY	= ".$db->qstr($_SESSION['login_id']);
		
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS		= '6',
			WORK_ORDER_CURRENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=".$db->qstr($workorder_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
			
		force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");
	} else {

##################################
# Payment Failed						#
##################################
if($VAR['submit2']) {
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
	$invoice_details = $rs->FetchRow();
	$customer_id = $invoice_details['CUSTOMER_ID'];
	$amount	= $VAR['amount'];
	

		$memo = "PayPal Payment of $".$VAR['amount'].", Failed";
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '5',
			INVOICE_ID              = ".$db->qstr($invoice_id).",
			WORKORDER_ID            = ".$db->qstr($workorder_id).",
			CUSTOMER_ID             = ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT			= '0' ";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		/* update the invoice */	
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			PAID_DATE  		= ".$db->qstr(time()).", 
			PAID_AMOUNT 		= '0',
			INVOICE_PAID		= '0'
			WHERE INVOICE_ID 	= ".$db->qstr($invoice_id);
			
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		/* update work order */
		$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			WORK_ORDER_ID			= ".$db->qstr($workorder_id).",
			WORK_ORDER_STATUS_DATE 		= ".$db->qstr(time()).",
			WORK_ORDER_STATUS_NOTES 	= ".$db->qstr($memo).",
			WORK_ORDER_STATUS_ENTER_BY	= ".$db->qstr($_SESSION['login_id']);
		
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");
	}
}

?>