<?php
require_once ('include.php');

if(!xml2php("invoice")) {
	$smarty->assign('error_msg',"Error in language file");
}

// Grab customers Information
$wo_id       = $VAR['wo_id'];
$customer_id = $VAR['customer_id'];
$submit		 = $VAR['submit'];
$desc = $VAR['desc'];

/* Generic error control */
if(!$wo_id) {
	/* If no work order ID then we dont belong here */
	force_page('core', 'error&error_msg=No Work Order ID');
} else {
	$q = "SELECT WORK_ORDER_STATUS  FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$smarty->assign('wo_status', $rs->fields['WORK_ORDER_STATUS']);
	$smarty->assign('wo_id', $wo_id);
}
	
/* check if we have a customer id and if so get details */
if($customer_id == "" || $customer_id == "0"){
		force_page('core', 'error&error_msg=No Customer ID&menu=1');
		exit;
} else {
	$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}

	$customer_details = $rs->GetAssoc();

	if(empty($customer_details)){
		force_page('core', 'error&error_msg=No Customer details found.&menu=1');
		exit;
	}
	$smarty->assign('customer_details',$customer_details);	
}


##################################
# If We have a Submit 				#
##################################
if(isset($submit)){
	
	if($VAR['invoice_id'] == ''){
		force_page('core', 'error&error_msg=No Invoice ID');
	}
     /* This formats the two dates from dd/mm/yyyy to proper sql string time*/
     // Invoice Date
        if($date_format == '%d/%m/%Y'){
         $date_part = explode("/",$VAR['date']);
         $timestamp = mktime(0,0,0,$date_part[1],$date_part[0],$date_part[2]);
         $datef = $timestamp;

         //Invoice Due Date
         $date_part2 = explode("/",$VAR['due_date']);
         $timestamp2 = mktime(0,0,0,$date_part2[1],$date_part2[0],$date_part2[2]);
         $datef2 = $timestamp2;
        }
        if($date_format == '%m/%d/%Y'){
         //$date_part = explode("/",$VAR['date']);
         //$timestamp = mktime(0,0,0,$date_part[1],$date_part[0],$date_part[2]);
         $datef = (strtotime($VAR['date']));

         //Invoice Due Date
         //$date_part2 = explode("/",$VAR['due_date']);
         //$timestamp2 = mktime(0,0,0,$date_part2[1],$date_part2[0],$date_part2[2]);
         $datef2 = (strtotime($VAR['due_date']));
        }
	
	$date				= $datef;
	$due_date			= $datef2;
	$test = $desc2['LABOR_RATE_NAME'];
	$create_by		= $VAR['create_by'];
	$wo_id				= $VAR['wo_id'];
	$sub_total     = number_format($VAR['sub_total'], 2,'.', '');
	$shipping      = number_format($VAR['shipping'], 2,'.', '');

  //Get Description from DB
  //$sql = "SELECT * FROM ".PRFX."TABLE_labor_rate WHERE LABOR_RATE_ID=".$db->qstr($VAR['description'][$i]).
	//$rs = $db->Execute($q);
	//$desc2 = $rs->GetArray();
	//print $desc2['LABOR_RATE_NAME'];
  //$smarty->assign('desc2', $desc2);
	
	
	/* insert Labor into database */
	if($VAR['hour'] > 0 ) {
		$i = 1;
		$sql = "INSERT INTO ".PRFX."TABLE_INVOICE_LABOR (INVOICE_ID, EMPLOYEE_ID, INVOICE_LABOR_DESCRIPTION, INVOICE_LABOR_RATE, INVOICE_LABOR_UNIT, INVOICE_LABOR_SUBTOTAL) VALUES ";
		
		foreach($VAR['hour'] as $key=>$val) {
			$sql .="(".$db->qstr($VAR['invoice_id']).", '1', ".$db->qstr($VAR['description'][$i]).", ".$db->qstr($VAR['rate'][$i]).", ".$db->qstr($val).", ".$db->qstr($val * $VAR['rate'][$i])."),"; 
			$ss = $val * $VAR['rate'][$i];
			$sub_total = $sub_total + $ss;
			$sub_total = $sub_total;
			$i++;
		}
		
		/* Strip off last , */
		$sql = substr($sql ,0,-1);
		if(!$rs = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		
	}
	
	/* insert Parts if set */
	if($VAR['count'] > 0 ) {
		$i = 1;
		$sql = "INSERT INTO ".PRFX."TABLE_INVOICE_PARTS (INVOICE_ID,INVOICE_PARTS_MANUF,INVOICE_PARTS_MFID,INVOICE_PARTS_DESCRIPTION,INVOICE_PARTS_WARRANTY,INVOICE_PARTS_AMOUNT,INVOICE_PARTS_COUNT,INVOICE_PARTS_SUBTOTAL) VALUES ";
		foreach($VAR['count'] as $key=>$val) {
			$sql .="(".$db->qstr($VAR['invoice_id']).",".$db->qstr($VAR['manufacture'][$i]).",'',".$db->qstr($VAR['parts_description'][$i]).",'',".$db->qstr($VAR['parts_price'][$i]).",".$db->qstr($val).", ".$db->qstr($val * $VAR['parts_price'][$i])."),";
			$ss =  $val * $VAR['parts_price'][$i];
			$sub_total = $sub_total + $ss;
			$i++;
		}
		$sql = substr($sql ,0,-1);
		if(!$rs = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
	}
	
	
	/* Update Invoice */
	
	/* calculate Tax */
	$q = "SELECT INVOICE_TAX FROM ".PRFX."SETUP";
	$rs = $db->execute($q);
	$tax = $rs->fields['INVOICE_TAX'];

	
	$tax = $tax * .01;
	$tax_amount = $sub_total * 	$tax;
	$invoice_total = $sub_total + $shipping + $tax_amount;

	
	
	/* get customer discount */
	if(empty($VAR['discount'])) {
		$q = "SELECT DISCOUNT FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID =$customer_id";
		$rs = $db->execute($q);
		$discount = $rs->fields['DISCOUNT'];
	} else {
		$discount = $VAR['discount'];
	}
	if(($VAR['discount']) == 0) {
		$discount = 0.0;
	} else {
		$discount = $VAR['discount'];
	}
		
	$discount = $discount * .01;
	$discount_amount = $sub_total * $discount;
	$invoice_total = $invoice_total - $discount_amount;
	
	/* update database */
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			INVOICE_DATE		=". $db->qstr( $date).",
			CUSTOMER_ID		=". $db->qstr( $customer_id).",
			EMPLOYEE_ID		=". $db->qstr( $_SESSION['login_id']).",
			DISCOUNT		=". $db->qstr( number_format($discount_amount, 2,'.', '')).",
			SUB_TOTAL 		=". $db->qstr( number_format($sub_total, 2,'.', '')).",
			INVOICE_AMOUNT	        =". $db->qstr( number_format($invoice_total, 2,'.', '')).",
			TAX 			=". $db->qstr( number_format($tax_amount, 2,'.', '')).",
			INVOICE_DUE		=". $db->qstr( $due_date)." 
			WHERE INVOICE_ID=".$db->qstr( $VAR['invoice_id']);

	if(!$rs = $db->Execute($q)){
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
		exit;
	}
	if( $VAR['discount'] >= 100){
	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS       	= '6',
			WORK_ORDER_CURRENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=".$db->qstr($wo_id);
			if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
			}
	}
	if( $VAR['discount'] >= 100){
	/* update the invoice */	
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			PAID_DATE  			= ".$db->qstr(time()).", 
			PAID_AMOUNT 			= '0',
			INVOICE_PAID			= '1'
			WHERE INVOICE_ID 	= ".$db->qstr( $VAR['invoice_id']);
			
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
	}
	/* send back to the invoice page */
	force_page('invoice', 'new&wo_id='.$wo_id.'&customer_id='.$customer_id);	

##################################
# Create New Invoice 					#
##################################
} else {

	/* check if invoice has been created else create a new invoice for this workorder */
	$q = "SELECT count(*) as count FROM ".PRFX."TABLE_INVOICE WHERE WORKORDER_ID=".$db->qstr($wo_id);
	$rs = $db->Execute($q);
	$count = $rs->fields['count'];

	if($count == 0) {
	
		$q = "INSERT INTO ".PRFX."TABLE_INVOICE SET
				INVOICE_DATE            =".$db->qstr(time()).",
				CUSTOMER_ID		=".$db->qstr($customer_id).", 
				WORKORDER_ID		=".$db->qstr($wo_id ).",
				EMPLOYEE_ID		=".$db->qstr($_SESSION['login_id']).", 
				INVOICE_PAID            ='0',
				INVOICE_AMOUNT          ='0.00'";
			
		if(!$rs = $db->Execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
		$invoice_id = $db->insert_id();
	
		/* Update Work Order status and record invoice created */
		$msg = "Invoice Created ID: ".$invoice_id;
		
		$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				WORK_ORDER_ID			=".$db->qstr($wo_id).",
				WORK_ORDER_STATUS_DATE		=".$db->qstr(time()).",
				WORK_ORDER_STATUS_NOTES		=".$db->qstr($msg).",
				WORK_ORDER_STATUS_ENTER_BY 	=".$db->qstr($_SESSION['login_id']);	
		if(!$result = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			force_page('invoice', 'new&wo_id='.$wo_id.'&customer_id='.$customer_id);
		}


	} else if($count == 1) {
		$q = "SELECT  ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM  ".PRFX."TABLE_INVOICE 
				LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
				WHERE WORKORDER_ID=".$db->qstr($wo_id);
		$rs = $db->execute($q);
		$invoice = $rs->FetchRow();

		if($invoice['INVOICE_PAID'] == 1) {
			force_page('invoice', "view&invoice_id=".$invoice['INVOICE_ID']."&page_title=Invoice&customer_id=".$invoice['CUSTOMER_ID']);
			exit;	
		} 
	}	else if($count > 1) {
		force_page("core", "error&error_msg=Duplicate Invoice's.");
			exit;	
	}


			/* get any labor details */
				$q = "SELECT * FROM ".PRFX."TABLE_INVOICE_LABOR WHERE INVOICE_ID=".$db->qstr($invoice['INVOICE_ID']);
				$rs = $db->execute($q);
				$labor = $rs->GetArray();
			
				if(empty($labor)){
					$smarty->assign('labor', 0);
				} else {
					$smarty->assign('labor', $labor);
				}
			
				/* get any parts */
				$q = "SELECT * FROM ".PRFX."TABLE_INVOICE_PARTS WHERE INVOICE_ID=".$db->qstr($invoice['INVOICE_ID']);
				$rs = $db->execute($q);
				$parts = $rs->GetArray();
				
				if(empty($parts)){
					$smarty->assign('parts', 0);
				} else {
					$smarty->assign('parts', $parts);
				}
				
				if($invoice['balance'] > 0){
					$q ="SELECT * FROM ".PRFX."TABLE_TRANSACTION WHERE INVOICE_ID =".$db->qstr($invoice['INVOICE_ID']);
					$rs = $db->execute($q);
					$trans = $rs->GetArray();
					$smarty->assign('trans', $trans);
				}
			
				/* load labor rate into array */
				$q = "SELECT * FROM ".PRFX."TABLE_LABOR_RATE WHERE LABOR_RATE_ACTIVE='1'";
				$rs = $db->execute($q);
				$rate = $rs->GetArray();
				$smarty->assign('rate', $rate); 
				
					/* Assign company information */
					$q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
					$rs = $db->Execute($q);
					$company = $rs->GetArray();
					$smarty->assign('company', $company);

				$smarty->assign('invoice',$invoice);
				$smarty->display('invoice'.SEP.'new.tpl');
				
	if( $VAR['discount'] >= 100){
	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS		= '6',
			WORK_ORDER_CURRENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=".$db->qstr($wo_id);
			if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
			}
	}
	if( $VAR['discount'] >= 100){
	/* update the invoice */	
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			PAID_DATE  		= ".$db->qstr(time()).", 
			PAID_AMOUNT 		= '0',
			INVOICE_PAID		= '1'
			WHERE INVOICE_ID 	= ".$db->qstr( $VAR['invoice_id']);
			
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
	}
} 
##################################
# If We have a Submit2 				#
##################################
if(isset($submit2)){
	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS		= '6',
			WORK_ORDER_CURRENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=".$db->qstr($wo_id);
			if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
			}
}
?>
