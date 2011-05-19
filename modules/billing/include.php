<?php
function validate_any($val_any){
	foreach($val_any as $key=> $val) {
		if($val == "") {
			$error_arr[$key] = "Missing Field";
		}
	}
	if(!empty($error_arr)) {
		return $error_arr;
	} 
}


function validate_cc_exp($month, $year){
	if ($year > date("Y")){
		return true;
	} elseif ( eregi_replace("^0","", $year) == eregi_replace("^0","", date("Y")) && eregi_replace("^0","", $month) >= eregi_replace("^0","", date("m"))) {
		return true;
	} else {
		return false;
	}
}

function validate_cc( $ccNum, $card_type, $card_type_accepted_arr ){
	$v_ccNum = false;
	if ($card_type == "visa" || !$card_type) {
		// VISA
		if ( ereg('^4(.{12}|.{15})$', $ccNum) ) {
			$v_ccNum = true;
			$c_type  = 'visa';
		}
	} else if ($card_type == "mc" || !$card_type) {
		// MC
		if ( ereg("^5[1-5][0-9]{14}$", $ccNum) )  {
			$v_ccNum = true;
			$c_type  = 'mc';
		}
	} else if ($card_type == "amex" || !$card_type) {
		// AMEX
		if ( ereg("^3[47][0-9]{13}$", $ccNum) )  {
			$v_ccNum = true;
			$c_type  = 'amex';
		}
	} else if ($card_type == "discover" || !$card_type) {
		// DISCOVER
		if ( ereg("^6011[0-9]{12}$", $ccNum) )  {
			$v_ccNum = true;
			$c_type  = 'discover';
		}
	} else if ($card_type == "delta" || !$card_type) {
		// DELTA ?
		if ( eregi ( "^4(1373[3-7]|462[0-9]{2}|5397[8-9]|"
			."54313|5443[2-5]|54742|567(2[5-9]|3[0-9]|4[0-5])|"
			."658[3-7][0-9]|659(0[1-9]|[1-4][0-9]|50)|844(09|10)|"
			."909[6-7][0-9]|9218[1-2]|98824)[0-9]{10}$" ) ) {
			$v_ccNum = true;
			$c_type  = 'delta';
		}
	}else if ($card_type == "solo" || !$card_type) {
		// SOLO  ?
		if ( ereg("^6(3(34[5-9][0-9])|767[0-9]{2})[0-9]{10}([0-9]{2,3})?$") ) {
			$v_ccNum = true;
			$c_type  = 'solo';
		}
	}	else if ($card_type == "switch" || !$card_type) {
		// SWITCH  ?
		if ( ereg('^49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})[0-9]{10}([0-9]{2,3})?$', $ccNum) ||
			ereg('^564182[0-9]{10}([0-9]{2,3})?$', $ccNum) ||
			ereg('^6(3(33[0-4][0-9])|759[0-9]{2})[0-9]{10}([0-9]{2,3})?$', $ccNum) )  {
			$v_ccNum = true;
			$c_type  = 'switch';
		}
	} else if ($card_type == "jcb" || !$card_type) {
		// JCB
		if(ereg("^(3[0-9]{4}|2131|1800)[0-9]{11}$", $ccNum) )  {
			$v_ccNum = true;
			$c_type  = 'jcb';
		}
	} else if ($card_type == "diners" || !$card_type) {
		// DINERS
		if ( ereg("^3(0[0-5]|[68][0-9])[0-9]{11}$", $ccNum) ) {
			$v_ccNum = true;
			$c_type  = 'diners';
		}
	} else if ($card_type == "carteblanche" || !$card_type) {
		// CARTEBLANCHE
		if ( ereg("^3(0[0-5]|[68][0-9])[0-9]{11}$", $ccNum) ) {
			$v_ccNum = true;
			$c_type  = 'carteblanche';
		}
	} else if ($card_type == "enroute" || !$card_type) {
		// ENROUTE
		if (( (substr($ccNum, 0, 4) == "2014" || substr($ccNum, 0, 4) == "2149") && (strlen($ccNum) == 15) )) {
			$v_ccNum = true;
			$c_type  = 'enroute';
		}
	}
	
	// validate accepted card type
	if ($card_type_accepted_arr != false & $v_ccNum) {
		
		$v_ccNum = false;
		for($i=0; $i<count($card_type_accepted_arr); $i++)
			if($card_type_accepted_arr[$i]['CARD_TYPE'] == $c_type) $v_ccNum = true;

		}

		if ( $v_ccNum ){
			return TRUE;
		} else {
			return FALSE;
		}
} 

 function safe_number($ccNum){
 	$char = 'x';

  	$s_card_number = substr($ccNum, 0, 4);	
	$e_card_number = substr($ccNum, -4);
	
	$num_to_hide = strlen($ccNum) - 8;

  for($i = 0; $i < $num_to_hide; $i++){
     $pad .= $char;
  }

	 $safe_num .= $s_card_number;
	 $safe_num .= $pad;
	 $safe_num .= $e_card_number;

  return $safe_num;
}

#########################################
# Hex to bin coverter			#
#########################################

function hex2bin($data) {

	$len = strlen($data);
	for($i=0;$i<$len;$i+=2) {
		$newdata .= pack("C",hexdec(substr($data,$i,2)));
	}
	return $newdata;
} // End of hex2bin


function charge_an($post_string) {

	$ch = curl_init("https://test.authorize.net/gateway/transact.dll"); // URL of gateway for cURL to post to
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // use HTTP POST to send form data
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
	$resp = curl_exec($ch); //execute post and get results
	curl_close ($ch);

    

	
	/* debug only code */

	$debug =1;
	if($debug ==1) {
			$text = $resp;
			$tok = strtok($text,"|");
			while(!($tok === FALSE)){
				//while ($tok) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$tok."<br>";
				$tok = strtok("|");
			}
	}

	return $resp;
}

#####################################
#   Currency Symbol Lookup          #
#####################################

/* get company info for defaults */
$q = 'SELECT COMPANY_CURRENCY_SYMBOL FROM '.PRFX.'TABLE_COMPANY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
// $smarty->assign('currency_sym',$rs->fields['COMPANY_CURRENCY_SYMBOL']);
        $currency_sym = $rs->fields['COMPANY_CURRENCY_SYMBOL'];
?>
