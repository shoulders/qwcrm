<?
require('include.php');
//print_r ($_POST);



/* get vars */
$card_type		= $_POST['card_type'];
$cc_number		= $_POST['cc_number'];
$cc_ccv			= $_POST['cc_ccv'];
$cc_expr_month	= $_POST['StartDateMonth'];
$cc_expr_year	= $_POST['StartDateYear'];
$customer_id		= $_POST['customer_id'];
$invoice_id		= $_POST['invoice_id'];
$workorder_id	= $_POST['workorder_id'];
$cc_amount		= $_POST['cc_amount'];

//$cc_enc   = encrypt($cc_number, $strKey);
//$cc_deenc = decrypt ($cc_enc, $strKey);
//$cc_num = safe_number($cc_number);
$cc_expiry_date = $cc_expr_month.$cc_expr_year;


/* get our excepted cards */
$q = "SELECT CARD_TYPE, CARD_NAME FROM ".PRFX."CONFIG_CC_CARDS WHERE ACTIVE='1'";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	
$card_type_accepted_arr = $rs->GetArray();

/* validation */
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
$q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$inv_msg        = $rs->fields['COMPANY_NAME']."(Phone ".$rs->fields['COMPANY_PHONE'].") Invoice#".$invoice_id;
$country	= $rs->fields['COMPANY_COUNTRY'];
$email		= $rs->fields['COMPANY_EMAIL'];


/* proccess CC card */
$post_values	= array(
"x_login"			=>$an_login,
"x_tran_key"                    =>$an_key,
    
"x_version"			=>"3.1",
"x_delim_data"  		=>"TRUE",
"x_delim_char"  		=>"|",
"x_relay_response"		=>"FALSE",

"x_type"			=>"AUTH_CAPTURE",
"x_method"			=>"CC",
"x_card_num"			=>$cc_number,    
"x_exp_date"			=>"0910", //$cc_expiry_date,
"x_amount"			=>$cc_amount,
//"x_invoice_num"			=>$invoice_id,
"x_description"			=>$inv_msg,
//"x_first_name"			=>$first_name,
//"x_last_name"			=>$last_name,
//"x_company"			=>$display_name,
//"x_address"			=>$address,
//"x_city"			=>$city,
//"x_state"			=>$state,
//"x_zip"				=>$zip,
//"x_country"			=>$country,
//"x_cust_id"			=>$cust_id,
//"x_email"			=>$cust_email,
//"x_phone"			=>$cust_phone,
//"x_merchant_email"		=>$email,

//"x_currency_code"		=>$curency_code,
//"x_card_code"			=>$cc_ccv,
//"x_Password"			=>$an_password,
//"x_Trans_ID"			=>"",
//"x_email_customer"		=>"FALSE",
//"x_tax"				=>"0.00",
//"x_Test_Request"		=>"TRUE"
);

$post_string = "";
foreach( $post_values as $key => $value )
	{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
$post_string = rtrim( $post_string, "& " );

$request = curl_init("https://test.authorize.net/gateway/transact.dll?"); // initiate curl object
	curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
	$post_response = curl_exec($request); // execute curl post and store results in $post_response
	// additional options may be required depending upon your server configuration
	// you can find documentation on curl options at http://www.php.net/curl_setopt
        curl_close ($request); // close curl object

//$result = charge_an($post_string);
//$result = str_replace("\"", "", $post_string);
$result = explode(",", $post_response);
$response_array = explode($post_values["x_delim_char"],$post_response);


// The results are output to the screen in the form of an html numbered list.
echo "<OL>\n";
foreach ($response_array as $value)
{
	echo "<LI>" . $value . "&nbsp;</LI>\n";
	$i++;
}
echo "</OL>\n";


/* return codes 
	1	Approved
	2 	Declined
	3	Error
*/ 



if($post_response[0] == "1") {
	$q = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}

	$invoice_details = $rs->FetchRow();
	
	// check if this is a partial payment
	if($invoice_details['INVOICE_AMOUNT'] > $cc_amount){
		if($invoice_details['balance'] > 0 ) {
			$balance = $invoice_details['balance'] - $cc_amount;
		} else {
			$balance = $invoice_details['INVOICE_AMOUNT'] - $cc_amount; 
		}	
		$paid_amount = $cc_amount + $invoice_details['PAID_AMOUNT'];

		if($balance == 0 ) {
			$flag  = 1;
		} else {
			$flag = 0;
		}

		// insert Transaction
		$memo = "APPROVED: ".$post_response[3]." Partial Credit Card Payment Made of $$cc_amount Balance Due $: $balance, Card Number: $cc_num TRANS ID: ".$$post_response[37]." AUTH CODE: ".$$post_response[4];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '1',
			INVOICE_ID 	= ".$db->qstr($invoice_id).",
			WORKORDER_ID 	= ".$db->qstr($workorder_id).",
			CUSTOMER_ID 	= ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT		= ".$db->qstr($cc_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		// update the invoice
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
		  	balance 	= ".$db->qstr($balance)." WHERE INVOICE_ID = ".$db->qstr($invoice_id);
	}
			
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		// update work order
		$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			WORK_ORDER_ID					= ".$db->qstr($workorder_id).",
			WORK_ORDER_STATUS_DATE 		= ".$db->qstr(time()).",
			WORK_ORDER_STATUS_NOTES 		= ".$db->qstr($memo).",
			WORK_ORDER_STATUS_ENTER_BY	= ".$db->qstr($_SESSION['login_id']);
		
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		// update if balance = 0
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
	
		// full payment made
		
		if($invoice_details['INVOICE_AMOUNT'] == $cc_amount){	
			// insert Transaction
			$memo = "APPROVED: ".$post_response[3]." Amount:  $$cc_amount, Card Number: $cc_num TRANS ID: ".$post_response[37]."AUTH CODE ".$post_response[4];
		
			$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
				DATE 			= ".$db->qstr(time()).",
				TYPE 			= '1',
				INVOICE_ID 	= ".$db->qstr($invoice_id).",
				WORKORDER_ID 	= ".$db->qstr($workorder_id).",
				CUSTOMER_ID 	= ".$db->qstr($customer_id).",
				MEMO 			= ".$db->qstr($memo).",
				AMOUNT			= ".$db->qstr($cc_amount);
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
				exit;
			}
			
			// update the invoice
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
			
			// update work order
			$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				WORK_ORDER_ID					= ".$db->qstr($workorder_id).",
				WORK_ORDER_STATUS_DATE 		= ".$db->qstr(time()).",
				WORK_ORDER_STATUS_NOTES 		= ".$db->qstr($memo).",
				WORK_ORDER_STATUS_ENTER_BY	= ".$db->qstr($_SESSION['login_id']);
			
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
				exit;
			}
			
			$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
				WORK_ORDER_STATUS			= '6',
				WORK_ORDER_CURRENT_STATUS 	= '8'
				WHERE WORK_ORDER_ID 		=	".$db->qstr($workorder_id);
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
				exit;
			}
				
			force_page("invoice", "view&invoice_id=".$invoice_id."&customer_id=".$customer_id);
		}
	}
} else if($post_response[0] == "2"){
	// insert Transaction
		$memo = "DECLINED: ".$post_response[3]." Card Number: $cc_num TRANS ID: ".$post_response[37];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 			= ".$db->qstr(time()).",
			TYPE 			= '1',
			INVOICE_ID 	= ".$db->qstr($invoice_id).",
			WORKORDER_ID = ".$db->qstr($workorder_id).",
			CUSTOMER_ID 	= ".$db->qstr($customer_id).",
			MEMO 			= ".$db->qstr($memo).",
			AMOUNT		= ".$db->qstr($cc_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
	force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id	.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg='.$post_response[3]);
	exit;

} else if($post_response[0] == "3") {
	// insert Transaction
		$memo = "ERROR: ".$post_response[3]." Card Number: $cc_num TRANS ID: ".$post_response[37];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 				= ".$db->qstr(time()).",
			TYPE 				= '1',
			INVOICE_ID 		= ".$db->qstr($invoice_id).",
			WORKORDER_ID 	= ".$db->qstr($workorder_id).",
			CUSTOMER_ID 		= ".$db->qstr($customer_id).",
			MEMO 				= ".$db->qstr($memo).",
			AMOUNT				= ".$db->qstr($cc_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
	force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id	.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg='.$post_response[3]);
	exit;

} else  if($post_response[0] == "4"){
	// insert Transaction
		$memo = "ERROR: ".$post_response[3]." Card Number: $cc_num TRANS ID: ".$post_response[37];
	
		$q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
			DATE 				= ".$db->qstr(time()).",
			TYPE 				= '1',
			INVOICE_ID 		= ".$db->qstr($invoice_id).",
			WORKORDER_ID 	= ".$db->qstr($workorder_id).",
			CUSTOMER_ID 		= ".$db->qstr($customer_id).",
			MEMO 				= ".$db->qstr($memo).",
			AMOUNT				= ".$db->qstr($cc_amount);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
		force_page('billing', 'new&wo_id='.$workorder_id.'&customer_id='.$customer_id	.'&invoice_id='.$invoice_id.'&page_title=Billing&error_msg='.$post_response[3]);
		exit;
}



?>