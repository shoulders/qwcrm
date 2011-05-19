<?
require('include.php');
//print_r ($_POST);
/* get vars */
$card_type		= $_POST['card_type'];
$cc_number		= $_POST['cc_number'];
$cc_ccv			= $_POST['cc_ccv'];
$cc_expr_month          = $_POST['StartDateMonth'];
$cc_expr_year           = $_POST['StartDateYear'];
$customer_id		= $_POST['customer_id'];
$invoice_id		= $_POST['invoice_id'];
$workorder_id           = $_POST['workorder_id'];
$cc_amount		= $_POST['cc_amount'];

$cc_enc   = encrypt($cc_number, $strKey); 
$cc_deenc = decrypt ($cc_enc, $strKey);
$cc_num = safe_number($cc_number);
$cc_expiry_date = $cc_expr_month.$cc_expr_year;


/* get our excepted cards */
$q = "SELECT CARD_TYPE, CARD_NAME FROM ".PRFX."CONFIG_CC_CARDS WHERE ACTIVE='1'";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
$card_type_accepted_arr = $rs->GetArray();

/* validation */
//Check to see if we are processing more then required
if($invoice_details['BALANCE'] < $cc_amount){
		force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id.'	&invoice_id='.$invoice_id.'&error_msg= You can not bill more than the amount of the invoice.');
			exit;
	}
if(!validate_cc( $cc_number, $card_type, $card_type_accepted_arr )){
	force_page("billing", "new&error_msg=Card number is invalid.&wo_id=$workorder_id&customer_id=$customer_id&invoice_id=$invoice_id&page_title=Billing");
	exit;
}


if(!validate_cc_exp($cc_expr_month, $cc_expr_year)) {
	force_page("billing", "new&error_msg=Card expiration month or year is invalid.&wo_id=$workorder_id&customer_id=$customer_id&invoice_id=$invoice_id&page_title=Billing");
	exit;
}

/* get customer account */
$q = "SELECT CUSTOMER_ID,CUSTOMER_DISPLAY_NAME,CUSTOMER_FIRST_NAME,CUSTOMER_LAST_NAME,CUSTOMER_ADDRESS,CUSTOMER_CITY,CUSTOMER_STATE,CUSTOMER_ZIP,CUSTOMER_EMAIL,CUSTOMER_PHONE FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$cust_id	= $rs->fields['CUSTOMER_ID'];
$first_name 	= $rs->fields['CUSTOMER_FIRST_NAME'];
$last_name 	= $rs->fields['CUSTOMER_LAST_NAME'];
$display_name	= $rs->fields['CUSTOMER_DISPLAY_NAME'];
$address	= $rs->fields['CUSTOMER_ADDRESS'];
$city		= $rs->fields['CUSTOMER_CITY'];
$state		= $rs->fields['CUSTOMER_STATE'];
$zip		= $rs->fields['CUSTOMER_ZIP'];
$cust_email	= $rs->fields['CUSTOMER_EMAIL'];
$cust_phone	= $rs->fields['CUSTOMER_PHONE'];
/* get cc Plug in information */
$q = "SELECT AN_LOGIN_ID,AN_PASSWORD,AN_TRANS_KEY FROM ".PRFX."SETUP";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$an_login 	= $rs->fields['AN_LOGIN_ID'];
$an_password 	= ($rs->fields['AN_PASSWORD']);
$an_key 	= $rs->fields['AN_TRANS_KEY'];

/* get company Display Name for bill */
$q = "SELECT *	 FROM ".PRFX."TABLE_COMPANY";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$inv_msg = $rs->fields['COMPANY_NAME']."(Phone ".$rs->fields['COMPANY_PHONE'].") Repair Charge";
$country		= $rs->fields['COMPANY_COUNTRY'];
$email		= $rs->fields['COMPANY_EMAIL'];


/* proccess CC card */
$authnet_values				= array
(
"x_ADC_Delim_Data"		=>"TRUE",
"x_ADC_Relay_Response"		=>"TRUE",
"x_ADC_URL"			=>"FALSE",
"x_Amount"			=>$cc_amount,
"x_currency_code"		=>$curency_code,
"x_Card_Num"			=>$cc_number,
"x_card_code"			=>$cc_ccv,
"x_Exp_Date"			=>$cc_expiry_date,
"x_Login"			=>$an_login,
"x_merchant_email"		=>$email,
"x_Method"			=>"CC",
"x_Password"			=>$an_password,
"x_Trans_ID"			=>"",
"x_Type"			=>"AUTH_CAPTURE",
"x_cust_id"			=>$cust_id,
"x_first_name"			=>$first_name,
"x_last_name"			=>$last_name,
"x_company"			=>$display_name,
"x_address"			=>$address,
"x_city"			=>$city,
"x_state"			=>$state,
"x_zip"				=>$zip,
"x_country"			=>$country,
"x_email"			=>$cust_email,
"x_phone"			=>$cust_phone,
"x_email_customer"		=>"FALSE",
"x_ship_to_first_name"  	=>$first_name,
"x_ship_to_last_name"		=>$last_name,
"x_ship_to_company"		=>$display_name,
"x_ship_to_address"		=>$address,
"x_ship_to_city"		=>$city,
"x_ship_to_state"		=>$state,
"x_ship_to_zip"			=>$zip,
"x_ship_to_country"		=>$country,
"x_tax"				=>"0.00",
"x_invoice_num"			=>$invoice_id,
"x_description"			=>$inv_msg,
"x_Version"			=>"3.0",
"x_Test_Request"		=>"TRUE"
);

$fields = "";
foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";


$result = charge_an($fields);
$result = str_replace("\"", "", $result);
$result = explode(",", $result);

/* return codes 
	1	Approved
	2 	Declined
	3	Error
*/ 



if($result[0] == "1") {
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}

	$invoice_details = $rs->FetchRow();
	
	/* check if this is a partial payment */
	if($invoice_details['INVOICE_AMOUNT'] > $cc_amount){
		if($invoice_details['balance'] > 0 ) {
			$balance = $invoice_details['balance'] - $cc_amount;
		} else {
			$balance = $invoice_details['INVOICE_AMOUNT'] - $cc_amount; 
		}	
		$paid_amount = $cc_amount + $invoice_details['PAID_AMOUNT'];
                $balance = sprintf("%01.2f", $balance);

		if($balance == 0 ) {
			$flag  = 1;
		} else {
			$flag = 0;
		}

		/* insert Transaction */
		$memo = "APPROVED: ".$result[3]." Partial Credit Card Payment Made of $currency_sym$cc_amount, Balance Due: $currency_sym$balance, Card Number: $cc_num TRANS ID: ".$result[37]." AUTH CODE: ".$result[4];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 		= ".$db->qstr(time()).",
			TYPE 		= '1',
			INVOICE_ID 	= ".$db->qstr($invoice_id).",
			WORKORDER_ID 	= ".$db->qstr($workorder_id).",
			CUSTOMER_ID 	= ".$db->qstr($customer_id).",
			MEMO 		= ".$db->qstr($memo).",
			AMOUNT		= ".$db->qstr($cc_amount);
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
			WORK_ORDER_ID			= ".$db->qstr($workorder_id).",
			WORK_ORDER_STATUS_DATE 		= ".$db->qstr(time()).",
			WORK_ORDER_STATUS_NOTES 	= ".$db->qstr($memo).",
			WORK_ORDER_STATUS_ENTER_BY	= ".$db->qstr($_SESSION['login_id']);
		
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

		force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");

	} else {
	
		/* full payment made */
		
		if($invoice_details['INVOICE_AMOUNT'] == $cc_amount){	
			/* insert Transaction */
			$memo = "APPROVED: ".$result[3]." Amount: $currency_sym$cc_amount, Card Number: $cc_num TRANS ID: ".$result[37]."AUTH CODE ".$result[4];
		
			$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
				DATE 			= ".$db->qstr(time()).",
				TYPE 			= '1',
				INVOICE_ID              = ".$db->qstr($invoice_id).",
				WORKORDER_ID            = ".$db->qstr($workorder_id).",
				CUSTOMER_ID             = ".$db->qstr($customer_id).",
				MEMO 			= ".$db->qstr($memo).",
				AMOUNT			= ".$db->qstr($cc_amount);
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
				exit;
			}
			
			/* update the invoice */	
			$q = "UPDATE ".PRFX."TABLE_INVOICE SET
				PAID_DATE  			= ".$db->qstr(time()).", 
				PAID_AMOUNT 			= ".$db->qstr($cc_amount).",
				INVOICE_PAID			= '1',
				EMPLOYEE_ID			= ".$db->qstr($_SESSION['login_id'])."
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
				
			force_page("invoice", "view&invoice_id=".$invoice_id."&customer_id=".$customer_id);
		}
	}
} else if($result[0] == "2"){
	/* insert Transaction */
		$memo = "DECLINED: ".$result[3]." Card Number: $cc_num TRANS ID: ".$result[37];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '1',
			INVOICE_ID              = ".$db->qstr($invoice_id).",
			WORKORDER_ID            = ".$db->qstr($workorder_id).",
			CUSTOMER_ID             = ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT                  = ".$db->qstr($cc_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
	force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id	.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg='.$result[3]);
	exit;

} else if($result[0] == "3") {
	/* insert Transaction */
		$memo = "ERROR: ".$result[3]." Card Number: $cc_num TRANS ID: ".$result[37];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '1',
			INVOICE_ID 		= ".$db->qstr($invoice_id).",
			WORKORDER_ID            = ".$db->qstr($workorder_id).",
			CUSTOMER_ID 		= ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT			= ".$db->qstr($cc_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
	force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id	.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg='.$result[3]);
	exit;

} else  if($result[0] == "4"){
	/* insert Transaction */
		$memo = "ERROR: ".$result[3]." Card Number: $cc_num TRANS ID: ".$result[37];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '1',
			INVOICE_ID 		= ".$db->qstr($invoice_id).",
			WORKORDER_ID            = ".$db->qstr($workorder_id).",
			CUSTOMER_ID 		= ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT			= ".$db->qstr($cc_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id	.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg='.$result[3]);
		exit;
} else {

}

?>