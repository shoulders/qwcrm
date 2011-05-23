<?php
$amount			= $VAR['gift_amount'];
$gift_code		= $VAR['gift_code'];
$customer_id		= $VAR['customer_id'];
$invoice_id		= $VAR['invoice_id'];
$workorder_id           = $VAR['workorder_id'];
$date = time();

/* check for valid code */
$q = "SELECT * FROM ".PRFX."GIFT_CERT WHERE GIFT_CODE LIKE".$db->qstr( $gift_code );
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

if($rs->fields['GIFT_ID'] == '') {
	force_page('billing','new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg=Not a valid gift code.');
}

$gift_expire		= $rs->fields['EXPIRE'];
$gift_amount		= $rs->fields['AMOUNT'];
$gift_active		= $rs->fields['ACTIVE'];
$gift_id		= $rs->fields['GIFT_ID'];

/* do some checks to see if it is a valid gift certificate */

/* check active */
if($gift_active != 1) {
	force_page('billing','new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg=This gift certificate is not active');
	exit;
}

/* check if expired */
if($gift_expire < $date) {
	force_page('billing','new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg=This gift certificate is expired.');
	exit;
}


/* get invoice details */
$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
	exit;
}

$invoice_details = $rs->FetchRow();
//Check to see if we are processing more then required
if($invoice_details['BALANCE'] < $gift_amount){
		force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'	&invoice_id='.$invoice_id.'&error_msg= You can not bill more than the amount of the invoice.');
			exit;
	}

/* check if this is a partial payment */
if($invoice_details['INVOICE_AMOUNT'] > $gift_amount){

	if($invoice_details['balance'] > 0 ) {
		$balance = $invoice_details['balance'] - $gift_amount;
	} else {
		$balance = $invoice_details['INVOICE_AMOUNT'] - $gift_amount; 
	}	
	$paid_amount = $gift_amount + $invoice_details['PAID_AMOUNT'];
        $balance = sprintf("%01.2f", $balance);
	
	if($balance == 0 ) {
		$flag  = 1;
	} else {
		$flag = 0;
	}

	/* insert Transaction */
	$gift_amount = number_format($gift_amount, 2,'.', '');
	$balance    = number_format($balance, 2,'.', '');
	$memo       = "Partial Gift Certificate Payment Made of $currency_sym$gift_amount, Balance due: $currency_sym$balance, ID: $gift_code";

	$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
		  DATE 			= ".$db->qstr(time()).",
		  TYPE 			= '3',
		  INVOICE_ID            = ".$db->qstr($invoice_id).",
		  WORKORDER_ID          = ".$db->qstr($workorder_id).",
		  CUSTOMER_ID           = ".$db->qstr($customer_id).",
		  MEMO 			= ".$db->qstr($memo).",
		  AMOUNT		= ".$db->qstr($gift_amount);
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
		  	balance 	= ".$db->qstr($balance).",
			INVOICE_PAID	='1' WHERE INVOICE_ID = ".$db->qstr($invoice_id);
	} else {
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET 
		  	PAID_DATE  	= ".$db->qstr(time()).",
		  	INVOICE_PAID	= ".$db->qstr($flag).",
		  	PAID_AMOUNT 	= ".$db->qstr($paid_amount).",
		  	balance 	= ".$db->qstr($balance)." WHERE INVOICE_ID = ".$db->qstr($invoice_id);
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
		  WORK_ORDER_STATUS_ENTER_BY            = ".$db->qstr($_SESSION['login_id']);
	
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}

	/* update if balance = 0 */
		if($balance == 0 ) {
			$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS		= '6',
			WORK_ORDER_CURRENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=".$db->qstr($workorder_id);
			if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
			}
		}

} else {

	/* full payment made */
	if($invoice_details['INVOICE_AMOUNT'] < $gift_amount) {
			/* update gift amnount and set paid amount full */
			$remain_gift = $gift_amount - $invoice_details['INVOICE_AMOUNT'];
			
			$q = "UPDATE ".PRFX."GIFT_CERT SET AMOUNT=".$db->qstr( $remain_gift )." WHERE GIFT_ID=".$db->qstr( $gift_id );
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
				exit;
			}
			$gift_amount = $invoice_details['INVOICE_AMOUNT'];
			$flag = 1;
	} 

	if($invoice_details['INVOICE_AMOUNT'] = $gift_amount){	
		/* insert Transaction */
		$gift_amount = number_format($gift_amount, 2,'.', '');

		$memo = "Full Gift Certificate Payment Made of $currency_sym$gift_amount, ID: $gift_code";
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '3',
			INVOICE_ID              = ".$db->qstr($invoice_id).",
			WORKORDER_ID            = ".$db->qstr($workorder_id).",
			CUSTOMER_ID             = ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT			= ".$db->qstr($gift_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		/* update the invoice */	
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			PAID_DATE  			= ".$db->qstr(time()).", 
			PAID_AMOUNT 			= ".$db->qstr($gift_amount).",
			INVOICE_PAID			= '1',
			balance 			= ".$db->qstr(0.00)."
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
	}
}


/* update gift certificate */
$q = "UPDATE ".PRFX."GIFT_CERT SET";
if($flag != 1) {
	$q .= "ACTIVE		=". $db->qstr( 0 ).",";
} else {
	$q .= "DATE_REDEMED	=". $db->qstr( time() ).",
		INVOICE_ID	=". $db->qstr( $invoice_id )."
		WHERE GIFT_ID=".$db->qstr( $gift_id );
}

	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");

?>