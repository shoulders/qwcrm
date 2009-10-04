<?php
####################################################
# IN 			#	
#	 				#
#  				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Parts															#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
if(!xml2php("parts")) {
	$smarty->assign('error_msg',"Error in language file");
}
/* if we have work order assign it */
if(isset($VAR['wo_id'])) {
	$smarty->assign('wo_id', $VAR['wo_id']);
} 

/* check to see if we have an open order for this WO */
$q = "SELECT count(*) as count  FROM ".PRFX."ORDERS WHERE WO_ID=".$db->qstr($VAR['wo_id']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$count = $rs->fields['count'];
if($count > 0) {
	force_page('workorder', 'view&wo_id='.$VAR['wo_id'].'&error&error_msg=A parts order already exists for this Work Order. &page_title=Work%20Order%20ID%20'.$VAR['wo_id']);
	exit;
}

##################################
# Load Configs							#
##################################

	$q = "SELECT PARTS_LO,PARTS_LOGIN,PARTS_PASSWORD,SERVICE_CODE,PARTS_MARKUP,UPS_LOGIN,UPS_PASSWORD,UPS_ACCESS_KEY FROM ".PRFX."SETUP ";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$local 			= $rs->fields['PARTS_LO'];
 	$login				= $rs->fields['PARTS_LOGIN'];
	$passwd			= $rs->fields['PARTS_PASSWORD'];
	$service_code	= $rs->fields['SERVICE_CODE'];
	$mark_up			= $rs->fields['PARTS_MARKUP'];
	$mark_up 			= $mark_up * .01;
	$ups_login 		= $rs->fields['UPS_LOGIN'];
	$ups_password	= $rs->fields['UPS_PASSWORD'];
	$ups_access_key	= $rs->fields['UPS_ACCESS_KEY'];

	/* assign service coed to smarty */
	if($service_code == "03") {
		$smarty->assign('service_code','UPS Ground');
	} else if($service_code == "02") {
		$smarty->assign('service_code','UPS 2nd Day Air');
	} else if($service_code == "01") {
		$smarty->assign('service_code','UPS Next Day Air');
	} else if($service_code == "07") {
		$smarty->assign('service_code','UPS Worldwide Express');
	} else if($service_code == "08") {
		$smarty->assign('service_code','UPS Worldwide Expedited');
	} else if($service_code == "11") {
		$smarty->assign('service_code','UPS Standard');
	} else if($service_code == "12") {
		$smarty->assign('service_code','UPS 3 Day Select');
	} else if($service_code == "13") {
		$smarty->assign('service_code','UPS Next Day Air Saver');
	} else if($service_code == "14") {
		$smarty->assign('service_code','UPS Next Day Air Early');
	} else if($service_code == "54") {
		$smarty->assign('service_code','UPS Worldwide Express Plus');
	} else if($service_code == "59") {
		$smarty->assign('service_code','UPS 2nd Day Air A.M.');
	} else if($service_code == "65") {
		$smarty->assign('service_code','UPS Express Saver');
	}
	
	/* assign smarty wharehoues location */
	if($local == "AT") {
		$smarty->assign('location', 'Atlanta');
	} else if($local == "CH") {
		$smarty->assign('location', 'Chicago');
	} else if($local == "DA") {
		$smarty->assign('location', 'Dallas');
	} else if($local == "FR") {
		$smarty->assign('location', 'Fremont');
	} else if($local == "HO") {
		$smarty->assign('location', 'Houston');
	} else if($local == "KA") {
		$smarty->assign('location', 'Kansas');
	} else if($local == "LR") {
		$smarty->assign('location', 'Laredo');
	} else if($local == "LA") {
		$smarty->assign('location', 'Los Angeles');
	} else if($local == "MI") {
		$smarty->assign('location', 'Miami');
	} else if($local == "NJ") {
		$smarty->assign('location', 'New Jersey');
	} else if($local == "PO") {
		$smarty->assign('location', 'Portland');
	} else if($local == "TP") {
		$smarty->assign('location', 'Tampa');
	}


	
##################################
# Load Category							#
##################################



$q = "SELECT * FROM ".PRFX."CAT";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	$arr = $rs->GetArray();
	$smarty->assign( 'CAT', $arr );
	
$q = "SELECT * FROM ".PRFX."SUB_CAT";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$arr = $rs->GetArray();
	$smarty->assign( 'SUB_CAT', $arr );	


##################################
# If Submit								#
##################################


if(isset($VAR['submit'])) {	

if(!isset($VAR['check_out'])) {
	/* get parts */
	$x = "<CRM_PARTS_LIST>
				<LOGIN>$login</LOGIN>
				<PASSWORD>$passwd</PASSWORD>
				<SUB_CATEGORY>".$VAR['CAT2']."</SUB_CATEGORY>
				<LOCATION>$local</LOCATION>
			</CRM_PARTS_LIST>";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, INCITCRM);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, "page=parts:list&xml=".$x."&escape=1");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$content = curl_exec ($ch); # This returns HTML
	curl_close ($ch); 



	/* return errors */
	if($content == 98) {
		$smarty->assign('crm_msg', 'Account Login Failed. Please Enter corect login information in the Control Center Under Company Edit. If you do not have an account please click here to create one <a href="https://www.incitecrm.com/?page=sign_up:main&page_title=Sign%20Up" target="new">Create Account</a>. To order parts we must have an active credit card on file. <br><br> If you feel this is an error please verify your account information on In-Cite CRM by logging in here <a href="https://www.incitecrm.com/?page=account:account" target="new">In-cite CRM Login</a>');
	} else if($content == 1) {
		$smarty->assign('crm_msg', 'Wharehouse Location Not Found. Please Select a Location in the Control Center');
	} else if($content == 2) {
		$smarty->assign('crm_msg', 'Please Select A category');
	} else if($content == 99) {
		$smarty->assign('crm_msg', 'Server Error');
	}

	/* parse Return */
	$parser = xml_parser_create();
   xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
   xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
   xml_parse_into_struct($parser, $content, $values, $tags);
   xml_parser_free($parser);
	

	/* build array from returned xml */

	foreach($values as $xml){
		if($xml['tag'] == "SKU" && $xml['value'] != ""){
			$sku = array('SKU' => $xml['value']);
		}

		if($xml['tag'] == "PART_ID" && $xml['value'] != ""){
			$part_id = array('ITEMID' => $xml['value']);
		}
		
		if($xml['tag'] == "VENDOR" && $xml['value'] != ""){
			$vendor = array('VENDOR' => $xml['value']);
		}

		if($xml['tag'] == "DESCRIPTION" && $xml['value'] != ""){
			$description = array('DESCRIPTION' => $xml['value']);
		}

		if($xml['tag'] == "PRICE" && $xml['value'] != ""){
			$price = array('PRICE' => $number = number_format(($xml['value'] * $mark_up) + $xml['value'], 2,'.', '') );
		}

		if($xml['tag'] == "Weight" && $xml['value'] != ""){
			$weight = array('Weight' => $xml['value']);
		}
		
		if($xml['tag'] == "ZIPCODE" && $xml['value'] != ""){
			$from_zip = $xml['value'];
		}
		if($xml['tag'] == "PART" && $xml['type'] == "close" ){
			$parts[] =  array_merge($sku,$part_id,$vendor,$description,$price,$weight);
		}
	}
	
	$smarty->assign('from_zip',$from_zip);
	$smarty->assign( 'parts', $parts );
	$smarty->assign('CAT2', $VAR['CAT2']);
}
###############################
# Add Part							#
###############################
	/* if parts where added */
	if(isset($VAR['add_part'])) {
		if($VAR['AMOUNT'] == '') {
			$VAR['AMOUNT'] =1;
	}

	 $sub = $VAR['AMOUNT'] * $VAR['PRICE'];
		$q = "INSERT INTO  ".PRFX."CART SET
				SKU 			=". $db->qstr($VAR['SKU']) .",
				AMOUNT			=". $db->qstr($VAR['AMOUNT']) .",
				DESCRIPTION	=". $db->qstr($VAR['DESCRIPTION']).",
				VENDOR 		=". $db->qstr($VAR['VENDOR']).",
				ITEMID 		=". $db->qstr($VAR['ITEMID']).",
				Weight 		=". $db->qstr($VAR['Weight']).",
				PRICE 			=". $db->qstr($VAR['PRICE']) .",
				SUB_TOTAL		=". $db->qstr($sub) .",
				ZIP				=". $db->qstr($VAR['from_zip']) .",
				WO_ID			=". $db->qstr($VAR['wo_id']) .",
				LAST			=". time();
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	}

##################################
# Remove part From Cart				#
##################################
	/* if parts where removed */
	if(isset($VAR['update_cart'])) {
	
		foreach($VAR['remove'] as $SKU){
			$q = "DELETE FROM ".PRFX."CART WHERE SKU=".$db->qstr($SKU);
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}
			
		}
	}
	
##################################
# Check Out								#
##################################
	/* if checkout selected */
if(isset($VAR['check_out'])) {
		$q = "SELECT * FROM ".PRFX."CART";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		$arr = $rs->GetArray();
		
		foreach($arr as $key=>$val) {
			$sub_total = $sub_total + $val['SUB_TOTAL'];
			$from_zip = $val['ZIP'];
			$amount = $val['AMOUNT'] * $val['Weight'];
			$cart_weight_total = $cart_weight_total + $amount;
		}
		
		$q = "SELECT COMPANY_ZIP FROM ".PRFX."TABLE_COMPANY";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		$to_zip  = $rs->fields['COMPANY_ZIP'];

		$length		= 10;
		$width		   = 10;
		$height		= 10;
	
	if($ups_login != '') {

			$activity = "activity"; 	
			$y = "<?xml version=\"1.0\"?><AccessRequest xml:lang=\"en-US\"><AccessLicenseNumber>$ups_access_key</AccessLicenseNumber><UserId>$ups_login</UserId><Password>$ups_password</Password></AccessRequest><?xml version=\"1.0\"?><RatingServiceSelectionRequest xml:lang=\"en-US\"><Request><TransactionReference><CustomerContext>Bare Bones Rate Request</CustomerContext><XpciVersion>1.0</XpciVersion></TransactionReference><RequestAction>Rate</RequestAction><RequestOption>Rate</RequestOption></Request><PickupType><Code>01</Code></PickupType><Shipment><Shipper><Address><PostalCode>$from_zip</PostalCode><CountryCode>US</CountryCode></Address></Shipper><ShipTo><Address><PostalCode>$to_zip</PostalCode><CountryCode>US</CountryCode></Address></ShipTo><ShipFrom><Address><PostalCode>$from_zip</PostalCode><CountryCode>US</CountryCode></Address></ShipFrom><Service><Code>$service_code</Code></Service><Package><PackagingType><Code>02</Code></PackagingType><Dimensions><UnitOfMeasurement><Code>IN</Code></UnitOfMeasurement><Length>$length</Length><Width>$width</Width><Height>$height</Height></Dimensions><PackageWeight><UnitOfMeasurement><Code>LBS</Code></UnitOfMeasurement><Weight>$cart_weight_total</Weight></PackageWeight></Package></Shipment></RatingServiceSelectionRequest>";  
			
			// cURL ENGINE 
			$ch = curl_init(); //initialize a cURL session  
			curl_setopt ($ch, CURLOPT_URL,"https://www.ups.com/ups.app/xml/Rate"); 
			curl_setopt ($ch, CURLOPT_HEADER, 0); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$y"); 
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			$response = curl_exec ($ch); 
			 
			curl_close ($ch); 

				$parser = xml_parser_create();
				xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
				xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
				xml_parse_into_struct($parser, $response, $values, $tags);
				xml_parser_free($parser);
				
				//print_r($values);
				
				foreach($values as $xml){
			
					if($xml['tag'] == "ResponseStatusCode" && $xml['value'] != "" ){
							$ResponseStatusCode  = array('ResponseStatusCode'=>$xml['value']);
					}
					 if ($xml['tag'] == "ResponseStatusCode" && $xml['value'] != "" ) {
							$ResponseStatusCode	= $xml['value'];	
					}
						
					if	($xml['tag'] == "ErrorDescription" && $xml['value'] != "" ) {
						$ErrorDescription		= $xml['value'];
					}

					if($xml['tag'] == "MonetaryValue" && $xml['value'] != "" ){
						$MonetaryValue = array('MonetaryValue'=>$xml['value']);
					}
					
					if($xml['tag'] == "GuaranteedDaysToDelivery" && $xml['value'] != "" ){
						$GuaranteedDaysToDelivery =  array('GuaranteedDaysToDelivery'=>$xml['value']);
					}	
				
					if($xml['tag'] == "RatedShipment" && $xml['type'] == "close" ){
						$rate[] = array_merge($ResponseStatusCode,$MonetaryValue,$GuaranteedDaysToDelivery);
					}
			
				}
 
			

			$shipping_charges = number_format($rate[0]['MonetaryValue'], 2, '.', '');
			$total_charges = $sub_total + $rate[0]['MonetaryValue'];
	} else {
		$shipping_charges = '0.00';
		$total_charges = $sub_total;
		$ResponseStatusCode	= 1;
		$ErrorDescription		= 'You have not set up UPS information';
	}

		/* get Cart Total */
		$smarty->assign('ResponseStatusCode',$ResponseStatusCode);
		$smarty->assign('ErrorDescription',$ErrorDescription);
		$smarty->assign('sub_total',number_format($sub_total, 2, '.', '')); 
		$smarty->assign('shipping_charges',$shipping_charges);
		$smarty->assign('total_charges', $total_charges);
		$smarty->assign('cart_weight_total', $cart_weight_total);
		$smarty->assign('cart_contents', $arr);
	}
}



##################################
# Get Cart Contents					#
##################################

$q = "SELECT * FROM ".PRFX."CART";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$arr = $rs->GetArray();
//print_r($arr);
foreach($arr as $key=>$val) {
	$cart_sub_total = $cart_sub_total + $val['SUB_TOTAL'];
}

$smarty->assign('cart_total',number_format($cart_sub_total, 2, '.', '')); 
$smarty->assign('cart_count',count($arr));
$smarty->assign('cart', $arr);

$smarty->display('parts'.SEP.'main.tpl');
?>