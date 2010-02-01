<?php
if(isset($VAR['submit'])) {
    //Update Payment Details for invoice printing
    		$q = "UPDATE ".PRFX."SETUP SET
				CHECK_PAYABLE	=". $db->qstr( $VAR['CHECK_PAYABLE']).",
				DD_NAME	=". $db->qstr( $VAR['DD_NAME']).",
                                DD_BANK	=". $db->qstr( $VAR['DD_BANK']).",
                                DD_BSB	=". $db->qstr( $VAR['DD_BSB']).",
                                DD_ACC	=". $db->qstr( $VAR['DD_ACC']).",
                                DD_INS	=". $db->qstr( $VAR['DD_INS']).",
                                PAYMATE_LOGIN	=". $db->qstr( $VAR['PAYMATE_LOGIN']).",
                                PAYMATE_PASSWORD	=". $db->qstr( $VAR['PAYMATE_PASSWORD']).",
                                PAYMATE_FEES	=". $db->qstr( $VAR['PAYMATE_FEES']);


		if(!$rs = $db->execute($q)) {
			echo $db->ErrorMsg();
                }

	/* update billing information */
	if($VAR['cc_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='cc_billing'";
		$rs = $db->execute($q);
		
		/* enter AN setup */
		$enc_passwd = encrypt ($VAR['AN_PASSWORD'], $strKey);

		$q = "UPDATE ".PRFX."SETUP SET 
				AN_LOGIN_ID	=". $db->qstr( $VAR['AN_LOGIN_ID'] 		).",
				AN_PASSWORD	=". $db->qstr( $VAR['AN_PASSWORD']				).",
				AN_TRANS_KEY	=". $db->qstr( $VAR['AN_TRANS_KEY']	);
		if(!$rs = $db->execute($q)) {
			echo $db->ErrorMsg();
		}
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='cc_billing'";
		$rs = $db->execute($q);
	}

	if($VAR['cheque_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='cheque_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='cheque_billing'";
		$rs = $db->execute($q);
	}

	if($VAR['cash_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='cash_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='cash_billing'";
		$rs = $db->execute($q);
	}
	
	if($VAR['gift_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='gift_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='gift_billing'";
		$rs = $db->execute($q);
	}
	
	if($VAR['paypal_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='paypal_billing'";
		$rs = $db->execute($q);
		
		$q = "UPDATE ".PRFX."SETUP SET PP_ID=".$db->qstr($VAR['PP_ID']);
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='paypal_billing'";
		$rs = $db->execute($q);
	}

        if($VAR['deposit_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='deposit_billing'";
		$rs = $db->execute($q);
		
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='deposit_billing'";
		$rs = $db->execute($q);
        }

        if($VAR['paymate_billing'] == 1 ) {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=1 WHERE  BILLING_OPTION='paymate_billing'";
		$rs = $db->execute($q);
	} else {
		$q = "UPDATE ".PRFX."CONFIG_BILLING_OPTIONS SET ACTIVE=0 WHERE  BILLING_OPTION='paymate_billing'";
		$rs = $db->execute($q);
	}

	force_page('control', 'payment_options&msg=Billing Options Updated.');
	exit;	

} else {
	/* load billing options */
	$q = "SELECT * FROM ".PRFX."CONFIG_BILLING_OPTIONS";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$arr = $rs->GetArray();

	/* load setup configuration for billing options */
	$q = "SELECT * FROM ".PRFX."SETUP";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	

	$opts = $rs->GetArray();
        //$pass = $rs->fields['AN_PASSWORD'];
        //$epass = decrypt ($opts['AN_PASSWORD'], $strKey);

        $smarty->assign( 'opts', $opts );
	$smarty->assign( 'arr', $arr );
        //Assign details for smarty template to php
        $smarty->assign( 'CHECK_PAYABLE', $CHECK_PAYABLE );
        $smarty->assign( 'DD_NAME', $DD_NAME );
        $smarty->assign( 'DD_BANK', $DD_BANK );
        $smarty->assign( 'DD_BSB', $DD_BSB );
        $smarty->assign( 'DD_ACC', $DD_ACC );
        $smarty->assign( 'DD_INS', $DD_INS );
        $smarty->assign( 'PAYMATE_LOGIN', $PAYMATE_LOGIN );
        $smarty->assign( 'PAYMATE_PASSWORD', $PAYMATE_PASSWORD );
        $smarty->assign( 'PAYMATE_FEES', $PAYMATE_FEES );
        $smarty->assign( 'AN_LOGIN_ID', $AN_LOGIN_ID );
        //$smarty->assign( 'epass', $epass );

	$smarty->display('control'.SEP.'payment_options.tpl');

}
?>