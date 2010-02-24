<?php

/* customer Id */
require_once ("include.php");
if(!xml2php("billing")) {
	$smarty->assign('error_msg',"Error in language file");
}
		
$customer_id 	= $VAR['customer_id'];
$customer_name	= $VAR['customer_name'];

$smarty->assign('customer_name', $customer_name);
$smarty->assign('customer_id',$customer_id);

/* if no customer id error */
if($customer_id == '') {
		force_page('core', 'error&error_msg=No Customer ID&menu=1&type=database');
		exit;
	}

/* check if gift cert is ebabled */
$q = "SELECT  ACTIVE FROM ".PRFX."CONFIG_BILLING_OPTIONS WHERE BILLING_OPTION='gift_billing'";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

/* if gift cert not enabled send them back */
if($rs->fields['ACTIVE'] != 1) {
	force_page('core', 'error&error_msg=Gift Certificate Billing is not enabled. To enbale gift certificates go to the Help menu and select Control Center. Then under the menu Billing Options select Payment Methods and check Gift Certificate. &menu=1&type=error');
	exit;
}


/* if submit */
if(isset($VAR['submit'])) {

	/* add */
	if($VAR['action'] == 'add') {
		/* generate a random string for the gift certificate id and assign it*/
		$acceptedChars = 'AZERTYUIOPQSDFGHJKLMWXCVBN0123456789';
		$max = strlen($acceptedChars)-1;
		$gift_code = null;
	
		for($i=0; $i < 16; $i++) {
			$gift_code .= $acceptedChars{mt_rand(0, $max)};
		}
	
	
		$amount 	= $VAR['amount'];
		$date_expire = explode("/",$VAR['expire']);
                if ($date_format == "%d/%m/%Y" || $date_format == "%d/%m/%Y") {
                $expire = mktime(0,0,0,$date_expire[1],$date_expire[0],$date_expire[2]);
                } else {
                $expire = mktime(0,0,0,$date_expire[0],$date_expire[1],$date_expire[2]);
                }
		$memo		= $VAR['memo'];

		/* insert the cert into the database */
		$q = "INSERT INTO ".PRFX."GIFT_CERT SET 
				MEMO 			=". $db->qstr( $memo				).",
				DATE_CREATE	=". $db->qstr( time()				).",
				EXPIRE			=". $db->qstr( $expire			).",
				GIFT_CODE		=". $db->qstr( $gift_code		).",
				CUSTOMER_ID	=". $db->qstr( $customer_id		).",
				AMOUNT			=". $db->qstr( $amount			).",
				ACTIVE			=". $db->qstr( 1					).",
				DATE_REDEMED	=". $db->qstr( 0					).",
				INVOICE_ID 	=". $db->qstr( 0					);

		if(!$db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			$gift_id = $db->insert_id();
			$smarty->assign('gift_id', $gift_id);

			/* display the results */
			$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE  CUSTOMER_ID=".$db->qstr($customer_id);
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}

			$arr = $rs->GetArray();
			$smarty->assign('customer', $arr);
			$smarty->assign('customer_name', $customer_name);
			$smarty->assign('create',time());
			$smarty->assign('expire',$expire);
			$smarty->assign('gift_code',$gift_code);
			$smarty->assign('amount',$amount);
			$smarty->display('billing'.SEP.'display_gift.tpl');
			exit;
		}
	} 

	/* delete */
	if($VAR['action'] == 'delete') {
		$gift_id = $VAR['gift_id'];
		$customer_id = $VAR['customer_id'];

		/* update and set non active */
		$q = "UPDATE ".PRFX."GIFT_CERT SET ACTIVE=". $db->qstr( 0) ."WHERE GIFT_ID=".$db->qstr($gift_id);
		if(!$db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			/* go back to customer page */
			force_page('customer','customer_details&customer_id='.$customer_id);
			exit;
		}
	}

	/* print */
	if($VAR['action'] == 'print') {
		$gift_id = $VAR['gift_id'];
		$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE  CUSTOMER_ID=".$db->qstr($customer_id);
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}
		$arr = $rs->GetArray();
		$smarty->assign('customer', $arr);

		$q = "SELECT * FROM ".PRFX."GIFT_CERT WHERE GIFT_ID=". $db->qstr( $gift_id );

		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		$gift = $rs->GetArray();

		$smarty->assign('customer', $arr);
		$smarty->assign('gift', $gift);
		$smarty->display('billing'.SEP.'print_gift.tpl');
		exit;
	}
	

/* else display the form */	
}	else {
	$smarty->display('billing'.SEP.'new_gift.tpl');
}


?>
