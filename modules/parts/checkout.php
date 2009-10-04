<?php
####################################################
# IN 			#	
#	 				#
#  				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Parts Check Out file										#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################

$q = "SELECT PARTS_LO,PARTS_LOGIN,PARTS_PASSWORD,SERVICE_CODE,PARTS_MARKUP,INVOICE_TAX   FROM ".PRFX."SETUP ";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	$local 			= $rs->fields['PARTS_LO'];
 	$login				= $rs->fields['PARTS_LOGIN'];
	$passwd			= $rs->fields['PARTS_PASSWORD'];
	$service_code	= $rs->fields['SERVICE_CODE'];
	$tax 				= $rs->fields['INVOICE_TAX'];
	$tax 				= $tax * 0.01;
	$mark_up			= $rs->fields['PARTS_MARKUP'];
	$mark_up 			= $mark_up * 0.01;

$q = "SELECT COMPANY_ZIP FROM ".PRFX."TABLE_COMPANY";
if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$from_zip = $rs->fields['COMPANY_ZIP'];
$workorder_id = $VAR['wo_id'];

$q = "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_WORK_ORDER  WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$customer_id = $rs->fields['CUSTOMER_ID'];



$q = "SELECT SKU,AMOUNT FROM ".PRFX."CART";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

if($rs->fields['SKU'] == ''){
	   force_page('parts', 'main&error_msg=You  have no parts in your Cart. Please select the parts you wish to order and click add.&wo_id='.$VAR['wo_id'].'&page_title=Order%20Parts');
		exit;
}

$cc .= "
	<CRMPARTSREQUEST>
		<ACCOUNT>
			<LOGIN>$login</LOGIN>
			<PASSWORD>$passwd</PASSWORD>
			<FROMZIP>$from_zip</FROMZIP>
			<LOCAL>$local</LOCAL>
			<SERVICECODE>$service_code</SERVICECODE>
			<WORKORDER>$workorder_id</WORKORDER>
		</ACCOUNT>";
$count=0;
while ($arr = $rs->FetchRow()) {
	$cc .= "<ITEM>";
	$cc .= "<SKU>".  $arr['SKU'].   "</SKU>";
	$cc .=	 "<COUNT>".$arr['AMOUNT']."</COUNT>";
	$cc .= "</ITEM>";
$count++;
}


$cc .="</CRMPARTSREQUEST>" ;


 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, INCITCRM);
 curl_setopt ($ch, CURLOPT_POST, 1);
 curl_setopt ($ch, CURLOPT_POSTFIELDS, "page=parts:processes&xml=".$cc."&escape=1");
 curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 $content = curl_exec ($ch); # This returns HTML
 curl_close ($ch); 

/*
print_r($content);
die;
*/
	
if($content == '') {
	echo "No response from server";
	exit;
} else if($content == '0') {
	echo "Error 0 -- Failed login";
	exit;
} else if ($content == '1'){
	echo "Error 1 -- Could Not Get Warehouse Location";	
	exit;
} else if ($content == '2'){
	echo "Error 2 -- Could not get Shipping Service Type";
	exit;
} else if ($content == '3'){	
	echo "Error 3 -- Could get Shipping information";
	exit;
} else if ($content == '4'){
	echo "Error 4 -- Server Error Could not complete request";
	exit;
} else if ($content == '5'){
	echo "Error 5 -- No response from UPS Server";
	exit;
} else if ($content == '6'){
	echo "Error 6 -- Credit Card On file Declined. Please Update your Account Information";
	exit;
} if ($content == '7') {
	echo "Error 7 -- Error with Credit Card On file Declined. Please Update your Account Information";
	exit;
}	else {
	$parser = xml_parser_create();
   xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
   xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
   xml_parse_into_struct($parser, $content, $values, $tags);
   xml_parser_free($parser);


	foreach($values as $xml){
		if($xml['tag'] == "ORDER_ID" && $xml['value'] != ""){
			$crm_invoice_id = $xml['value'];
		}

		if($xml['tag'] == "CART_TOTAL" && $xml['value'] != ""){
			$cart_total = number_format( ($xml['value'] * $mark_up) + $xml['value'], 2,'.', '');
		}
		
		if($xml['tag'] == "SHIPPING" && $xml['value'] != ""){
			$shipping = $xml['value'];
		}

		if($xml['tag'] == "WEIGHT" && $xml['value'] != ""){
			$weight = $xml['value'];
		}
		if($xml['tag'] == "TOTAL_ITEMS" && $xml['value'] != ""){
			$total_items = $xml['value'];
		}
		
		if($xml['tag'] == "WORKORDER" && $xml['value'] != ""){
			$wo_id = $xml['value'];
		}
	
		/* get order details */
		if($xml['tag'] == "SKU" && $xml['value'] != ""){
			$sku= array('SKU'=> $xml['value']);
		}

		if($xml['tag'] == "COUNT" && $xml['value'] != ""){
			$count= array('COUNT'=> $xml['value']);
		}

		if($xml['tag'] == "PRICE" && $xml['value'] != ""){
			$price= array('PRICE'=>  number_format( ($xml['value'] * $mark_up) + $xml['value'], 2,'.', '') );
		}

		if($xml['tag'] == "SUB_TOTAL" && $xml['value'] != ""){
			$sub_total= array('SUB_TOTAL'=> number_format( ($xml['value'] * $mark_up) + $xml['value'], 2,'.', '') );
		}
		
		if($xml['tag'] == "VENDOR" && $xml['value'] != ""){
			$vendor= array('VENDOR'=>   $xml['value']);
		}
		
		if($xml['tag'] == "DESCRIPTION" && $xml['value'] != ""){
			$description= array('DESCRIPTION'=> $xml['value']);
		}

		
		if($xml['tag'] == "ITEM" && $xml['type'] == "close" ){
			$details[] = array_merge($sku,$count,$price,$sub_total,$vendor,$description);	
		}
	}
	
	$total = $cart_total + $shipping;
	/* Insert Order */
	$q= "INSERT INTO ".PRFX."ORDERS SET
			INVOICE_ID	=".$db->qstr($crm_invoice_id								).",
			WO_ID 			=".$db->qstr($wo_id											).",
			DATE_CREATE	='".time()."',
			DATE_LAST		='".time()."',
			SUB_TOTAL		=".$db->qstr( number_format($cart_total, 2,'.', '')	).",
			SHIPPING 		=".$db->qstr( number_format($shipping, 2,'.', '')		).",
			TOTAL			=".$db->qstr( number_format($total, 2,'.', '')			).",
			WEIGHT			=".$db->qstr( number_format($weight, 2,'.', '')		).",
			ITEMS			=".$db->qstr( $total_items									).",
			TRACKING_NO	=".$db->qstr(0													).",
			STATUS			=".$db->qstr(1													);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	$order_id = $db->insert_id();


	/* Update Work Order status and record invoice created */
	if($wo_id != '') {

		/* create Invoice */

		$q = "SELECT  count(*) as count FROM ".PRFX."TABLE_INVOICE WHERE WORKORDER_ID=".$db->qstr($wo_id);
		$rs = $db->Execute($q);
		$count = $rs->fields['count'];
	
		

		if($count == 0) {
			$tax_amount = number_format($total * $tax, 2, '.', ',');
			$total = $total + $tax_amount;

			$q = "INSERT INTO ".PRFX."TABLE_INVOICE SET
				INVOICE_DATE 	=".$db->qstr(time()											).",
				CUSTOMER_ID		=".$db->qstr($customer_id									).", 
				WORKORDER_ID		=".$db->qstr($wo_id											).",
				EMPLOYEE_ID		=".$db->qstr($_SESSION['login_id']							).", 
				INVOICE_PAID	   ='0', 
				INVOICE_AMOUNT	=".$db->qstr( number_format($total, 2, '.', ',') 		).",
				SHIPPING			=".$db->qstr( number_format($shipping, 2, '.', ',')	).",
				TAX 				=".$db->qstr( number_format($tax_amount, 2, '.', ',')	).",
				SUB_TOTAL			=".$db->qstr( number_format($cart_total, 2, '.', ',')	);
				
			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
				exit;
			}
	
			$invoice_id = $db->insert_id();

			/* Update Work Order status and record invoice created */
			$msg = "Invoice Created ID: ".$invoice_id;
		
			$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				WORK_ORDER_ID					=".$db->qstr($wo_id).",
				WORK_ORDER_STATUS_DATE			=".$db->qstr(time()).",
				WORK_ORDER_STATUS_NOTES		=".$db->qstr($msg).",
				WORK_ORDER_STATUS_ENTER_BY 	=".$db->qstr($_SESSION['login_id']);	

			if(!$result = $db->Execute($sql)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}

		} else if($count == 1) {
			/* get curent Invoice details */
			$q = "SELECT INVOICE_ID,INVOICE_AMOUNT, SUB_TOTAL, TAX FROM ".PRFX."TABLE_INVOICE WHERE WORKORDER_ID=".$db->qstr($wo_id);
			$rs = $db->Execute($q);
			$invoice_id	= $rs->fields['INVOICE_ID'];
			$tax_amount = number_format($total * $tax, 2, '.', ',');
			$total = $total + $tax_amount;
			$invoice_total = $total + $rs->fields['INVOICE_AMOUNT'];
			$invoice_sub_total = $total + $rs->fields['SUB_TOTAL'];

			$q = "UPDATE ".PRFX."TABLE_INVOICE SET
				INVOICE_AMOUNT		=".$db->qstr( number_format($invoice_total, 2, '.', ',')			).",
				SUB_TOTAL				=".$db->qstr( number_format($invoice_sub_total, 2, '.', ',')	).",
				SHIPPING				=".$db->qstr( number_format($shipping, 2, '.', ',')				).",
				TAX 					=".$db->qstr( number_format($tax_amount, 2, '.', ',')				)."
				WHERE INVOICE_ID 	=".$db->qstr($invoice_id);

		}

		/* update work order Status */
		$msg = "Parts Ordered. Cite CRM Orderd ID: ".$crm_invoice_id." Amount: $".number_format($cart_total, 2, '.', ',')." Shipping: $".number_format($shipping, 2, '.', ',')." Total: $".number_format($cart_total + $shipping, 2, '.', ',');
		
		$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				WORK_ORDER_ID					=".$db->qstr($wo_id).",
				WORK_ORDER_STATUS_DATE			=".$db->qstr(time()).",
				WORK_ORDER_STATUS_NOTES		=".$db->qstr($msg).",
				WORK_ORDER_STATUS_ENTER_BY 	=".$db->qstr($_SESSION['login_id']);	

		if(!$result = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} 

		/* mark work order waiting for parts */
		$sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			  WORK_ORDER_CURRENT_STATUS	='3',
			  LAST_ACTIVE					=". $db->qstr(time())."
	  		  WHERE WORK_ORDER_ID			=". $db->qstr($wo_id);

		if(!$result = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

		$msg = "Work Order Changed status to Waiting For Parts";
		$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			  WORK_ORDER_ID					=". $db->qstr( $wo_id).",
			  WORK_ORDER_STATUS_DATE		=". $db->qstr( time()).",
			  WORK_ORDER_STATUS_NOTES		=". $db->qstr( $msg).",
			  WORK_ORDER_STATUS_ENTER_BY =". $db->qstr( $_SESSION['login_id']);
		
		if(!$result = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		
	}

	/* insert order details */

	$i = 0;
	foreach($details as $val) {
		$q = "INSERT INTO ".PRFX."ORDERS_DETAILS (DETAILS_ID ,ORDER_ID ,SKU,DESCRIPTION,VENDOR,COUNT,PRICE,SUB_TOTAL) 
		VALUES ('',".$db->qstr($order_id).",".$db->qstr($details[$i]['SKU']).",".$db->qstr($details[$i]['DESCRIPTION']).",".$db->qstr($details[$i]['VENDOR']).",".$db->qstr($details[$i]['COUNT']).",".$db->qstr($details[$i]['PRICE']).",".$db->qstr($details[$i]['SUB_TOTAL']).")";
	
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;	
		}
	
		if($wo_id != '') {
			/* insert into Invoice Parts */
			$q = "INSERT INTO ".PRFX."TABLE_INVOICE_PARTS SET
			INVOICE_ID 						=".$db->qstr($invoice_id).",
			INVOICE_PARTS_MANUF			=".$db->qstr($details[$i]['VENDOR']).", 
			INVOICE_PARTS_MFID				=".$db->qstr($details[$i]['SKU']).",
			INVOICE_PARTS_DESCRIPTION		=".$db->qstr($details[$i]['DESCRIPTION']).",
			INVOICE_PARTS_AMOUNT			=".$db->qstr($details[$i]['PRICE']).",
			INVOICE_PARTS_SUBTOTAL			=".$db->qstr($details[$i]['SUB_TOTAL']).", 
			INVOICE_PARTS_COUNT			=".$db->qstr($details[$i]['COUNT']);
	
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;	
			}
		}
		$i++;
	}

	
	
	/* clear cart */
	$q = "TRUNCATE TABLE ".PRFX."CART";
	$rs = $db->execute($q);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;	
	}
	/* assign smarty and display page */

	$q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$arr = $rs->GetArray();
	$smarty->assign('customer', $arr);

	if(!xml2php("parts")) {
	$smarty->assign('error_msg',"Error in language file");
	}
	
	/* get CRM ORDER details */
	$q = "SELECT * FROM ".PRFX."ORDERS WHERE  INVOICE_ID=".$db->qstr($crm_invoice_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$invoice_details = array('ORDER_ID'=>$rs->fields['INVOICE_ID'],
									'CART_TOTAL'=>$rs->fields['SUB_TOTAL'],
									'SHIPPING'=>$rs->fields['SHIPPING'],
									'TAX'=>'0.00'	,
									'TOTAL'=>$rs->fields['TOTAL'],
									'WEIGHT'=>$rs->fields['WEIGHT'],
									'TOTAL_ITEMS'=>$rs->fields['ITEMS'],
									'WORKORDER'=>$rs->fields['WO_ID'], 
									'DATE'=>time());
	$smarty->assign('invoice_details',$invoice_details);	
	$smarty->assign('details',$details);

	$smarty->display('parts'.SEP.'results.tpl');
}

?>