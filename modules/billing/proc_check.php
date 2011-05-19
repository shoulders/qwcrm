<?php
require('include.php');

/* get vars */
$cheque_amount	= $_POST['cheque_amount'];
$cheque_memo		= $_POST['cheque_memo'];
$cheque_recieved	= $_POST['cheque_recieved'];
$customer_id	= $_POST['customer_id'];
$invoice_id		= $_POST['invoice_id'];
$workorder_id	= $_POST['workorder_id'];


/* validation */
if(empty($cheque_amount)) {
	force_page("billing", "new&error_msg=Please Fill in the check amount.&wo_id=$workorder_id&customer_id=$customer_id&invoice_id=$invoice_id&page_title=Billing");
}

if(empty($cheque_recieved)) {
	force_page("billing", "new&error_msg=Please Fill in the Document Number.&wo_id=$workorder_id&customer_id=$customer_id&invoice_id=$invoice_id&page_title=Billing");
}

/* get invoice details */
$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
}

$invoice_details = $rs->FetchRow();

//Check to see if we are processing more then required
if($invoice_details['BALANCE'] < $cheque_amount){
		force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'	&invoice_id='.$invoice_id.'&error_msg= You can not bill more than the amount of the invoice.');
			exit;
	}

/* check if this is a partial payment */
if($invoice_details['INVOICE_AMOUNT'] > $cheque_amount){
		if($invoice_details['BALANCE'] > 0 ) {
			$balance = $invoice_details['BALANCE'] - $cheque_amount;
		} else {
			$balance = $invoice_details['INVOICE_AMOUNT'] - $cheque_amount; 
		}	
		$paid_amount = $cheque_amount + $invoice_details['PAID_AMOUNT'];
                $balance = sprintf("%01.2f", $balance);

		if($balance == 0 ) {
			$flag  = 1;
		} else {
			$flag = 0;
		}

	/* insert Transaction */
	$memo = "Partial Cheque Payment Made of $currency_sym$cheque_amount, Balance due: $currency_sym$balance, Cheque Number: $cheque_recieved, Cheque Memo: $cheque_memo";

	$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
		  DATE 			= ".$db->qstr(time()).",
		  TYPE 			= '2',
		  INVOICE_ID            = ".$db->qstr($invoice_id).",
		  WORKORDER_ID          = ".$db->qstr($workorder_id).",
		  CUSTOMER_ID           = ".$db->qstr($customer_id).",
		  MEMO 			= ".$db->qstr($memo).",
		  AMOUNT		= ".$db->qstr($cheque_amount);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
	/* update the invoice */	
	 if($balance == 0 ) {
			$q = "UPDATE ".PRFX."TABLE_INVOICE SET 
		  	PAID_DATE  	= ".$db->qstr(time()).",
		  	INVOICE_PAID	= ".$db->qstr($flag).",
		  	PAID_AMOUNT 	= ".$db->qstr($paid_amount).",
		  	balance 		= ".$db->qstr($balance).",
			INVOICE_PAID	='1' WHERE INVOICE_ID = ".$db->qstr($invoice_id);
	} else {
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET 
		  	PAID_DATE  	= ".$db->qstr(time()).",
		  	INVOICE_PAID	= ".$db->qstr($flag).",
		  	PAID_AMOUNT 	= ".$db->qstr($paid_amount).",
		  	balance 		= ".$db->qstr($balance)." WHERE INVOICE_ID = ".$db->qstr($invoice_id);
	}
		  
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
	/* update work order */
	$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
		  WORK_ORDER_ID				= ".$db->qstr($workorder_id).",
		  WORK_ORDER_STATUS_DATE 		= ".$db->qstr(time()).",
		  WORK_ORDER_STATUS_NOTES 		= ".$db->qstr($memo).",
		  WORK_ORDER_STATUS_ENTER_BY		= ".$db->qstr($employee);
	
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}

	if($balance == 0 ) {
	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS			= '6',
			WORK_ORDER_CURRENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=	".$db->qstr($workorder_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
	}

	force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");

} else {

	/* full payment made */
	if($invoice_details['INVOICE_AMOUNT'] < $cheque_amount) {
		force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'	&invoice_id='.$invoice_id.'&error_msg=You can not bill more than the amout of the invoice.');
			exit;
		} 
	if($invoice_details['INVOICE_AMOUNT'] == $cheque_amount){	
		/* insert Transaction */
		$memo = "Full Check Payment Made of $currency_sym$cheque_amount, Check Number: $cheque_recieved, Check Memo: $cheque_memo";
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '2',
			INVOICE_ID              = ".$db->qstr($invoice_id).",
			WORKORDER_ID            = ".$db->qstr($workorder_id).",
			CUSTOMER_ID             = ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT			= ".$db->qstr($cheque_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		/* update the invoice */	
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			PAID_DATE  		= ".$db->qstr(time()).", 
			PAID_AMOUNT 		= ".$db->qstr($cheque_amount).",
			INVOICE_PAID		= '1',
			balance 		= ".$db->qstr(0.00)."
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
		
		$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS		= '6',
			WORK_ORDER_CURRENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=".$db->qstr($workorder_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
			
		force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");
			
	}
}
?>