<?php

require_once ('include.php');

if(!xml2php("invoice")) {
	$smarty->assign('error_msg',"Error in language file");
}

// Grab customers Information
$wo_id       = $VAR['wo_id'];
$customer_id = $VAR['customer_id'];
$submit     = $VAR['submit'];
$desc       = $VAR['desc'];

$smarty->assign('customer_id', $VAR['customer_id']);

//$smarty->assign('invoice_id', $VAR['invoice_id']);

/* get Date Formatting value from database and assign it to $format*/
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$format = $rs->fields['COMPANY_DATE_FORMAT'];
	}


// Stripping out the percentage signs so php can render it correctly
$literals = "%";
$Dformat = str_replace($literals, "", $format);
//Now lets display the right date format
if($Dformat == 'd/m/Y' || $Dformat == 'd/m/y'  ){
$cur_date = $d."/".$m."/".$y;}
elseif($Dformat == 'm/d/Y' || $Dformat == 'm/d/y' ){
$cur_date = $m."/".$d."/".$y;}
//Assign it to Smarty
$smarty->assign('cur_date', $cur_date);
$smarty->assign('format', $format);


/* Generic error control */
if($wo_id == '' && $wo_id != "0") {
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
# If We have a Submit 		 #
##################################

if(isset($submit)){
	
	if($VAR['invoice_id'] == ''){
		force_page('core', 'error&error_msg=No Invoice ID');
	}
     /* This formats the two dates from dd/mm/yyyy to proper sql string time*/
        // Invoice Date
        if($format == "%d/%m/%Y"){
         $date_part = explode("/",$VAR['date']);
         $timestamp = mktime(0,0,0,$date_part[1],$date_part[0],$date_part[2]);
         $datef = $timestamp;

         //Invoice Due Date
         $date_part2 = explode("/",$VAR['due_date']);
         $timestamp2 = mktime(0,0,0,$date_part2[1],$date_part2[0],$date_part2[2]);
         $datef2 = $timestamp2;
        }
        if($format == "%m/%d/%Y"){
         $date_part = explode("/",$VAR['date']);
         $timestamp = mktime(0,0,0,$date_part[0],$date_part[1],$date_part[2]);
         $datef = $timestamp;

         //Invoice Due Date
         $date_part2 = explode("/",$VAR['due_date']);
         $timestamp2 = mktime(0,0,0,$date_part2[0],$date_part2[1],$date_part2[2]);
         $datef2 = $timestamp2;
        }
	
	$date = $datef;
	$due_date = $datef2;
	$test = $desc2['LABOR_RATE_NAME'];
	$create_by = $VAR['create_by'];
	$wo_id = $VAR['wo_id'];

 	/* insert Labor into database */
	if($VAR['hour'] > 0 ) {
		$i = 1;
		$sql = "INSERT INTO ".PRFX."TABLE_INVOICE_LABOR (INVOICE_ID, EMPLOYEE_ID, INVOICE_LABOR_DESCRIPTION, INVOICE_LABOR_RATE, INVOICE_LABOR_UNIT, INVOICE_LABOR_SUBTOTAL) VALUES ";
		
		foreach($VAR['hour'] as $key=>$val) {
			$sql .="(".$db->qstr($VAR['invoice_id']).", '1', ".$db->qstr($VAR['description'][$i]).", ".$db->qstr($VAR['rate'][$i]).", ".$db->qstr($val).", ".$db->qstr($val * $VAR['rate'][$i])."),"; 
			$ss = $val * $VAR['rate'][$i];
                        $temp_sub_total = $temp_sub_total + $ss;
			//$sub_total = $sub_total + $ss;
			//$sub_total = $sub_total;
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
			//$sub_total = $sub_total + $ss;
                        $temp_sub_total = $temp_sub_total + $ss;
			$i++;
		}
		$sql = substr($sql ,0,-1);
		if(!$rs = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
                        }
	}	

###########################################
#	Update and Calculate Invoice      #
###########################################

        // Calculate Sub Total
        //$temp_sub_total seems to be the same as $subtotal below
        $labour_sub_total_sum = labour_sub_total_sum ($db, $VAR['invoice_id']);
        $parts_sub_total_sum = parts_sub_total_sum ($db, $VAR['invoice_id']);
        $sub_total = $labour_sub_total_sum + $parts_sub_total_sum;
        
       	// Calculate Discount
	if(empty($VAR['discount'])) {
		$q = "SELECT DISCOUNT FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID =$customer_id";
		$rs = $db->execute($q);
		$discount = $rs->fields['DISCOUNT'];
	} else {
		$discount_rate = $VAR['discount'];
                }
	if(($VAR['discount']) == 0) {
		$discount_rate = 0.0;
	} else {
		$discount_rate = $VAR['discount'];
                }

	$discount_rate = $discount_rate / 100; // turns 17.5 in to 0.175
	$discount_amount = $sub_total * $discount_rate;

        // Calculate Shipping
        $shipping = $VAR['shipping'];
	
        // Calculate Tax
	$q = "SELECT INVOICE_TAX FROM ".PRFX."SETUP";
	$rs = $db->execute($q);
	$tax = $rs->fields['INVOICE_TAX'];
	$tax_rate = $tax / 100; // turns 17.5 in to 0.175
        $tax_amount = ($sub_total - $discount_amount + $shipping) * $tax_rate;

        $smarty->assign('tax_rate', $tax);
        $smarty->assign('discount_rate', $discount_rate);

        // Calculate Totals
        $invoice_total = $sub_total - $discount_amount + $shipping + $tax_amount;

        // Calculate Balance - Prevents resubmissions balance errors
        if (!isset ($paid_amount)) {
		$q = "SELECT PAID_AMOUNT FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID =".$VAR['invoice_id'];
		$rs = $db->execute($q);
		$paid_amount = $rs->fields['PAID_AMOUNT'];
        }
        $invoice_balance = $invoice_total - $paid_amount;
       
        
	/* update database */
		$q = "UPDATE ".PRFX."TABLE_INVOICE SET
			INVOICE_DATE		=". $db->qstr( $date).",
			CUSTOMER_ID		=". $db->qstr( $customer_id).",
			EMPLOYEE_ID		=". $db->qstr( $_SESSION['login_id']).",
			DISCOUNT		=". $db->qstr( number_format($discount_amount, 2,'.', '')).",
			SUB_TOTAL 		=". $db->qstr( number_format($sub_total, 2,'.', '')).",
			INVOICE_AMOUNT	        =". $db->qstr( number_format($invoice_total, 2,'.', '')).",
                        TAX_RATE 	        =". $db->qstr( number_format($tax, 3,'.', '')).",
                        DISCOUNT_APPLIED        =". $db->qstr( number_format($discount_rate * 100, 2,'.', '')).",
                        BALANCE 	        =". $db->qstr( number_format($invoice_balance, 2,'.', '')).",
			TAX 			=". $db->qstr( number_format($tax_amount, 2,'.', '')).",
			INVOICE_DUE		=". $db->qstr( $due_date)." 
			WHERE INVOICE_ID        =".$db->qstr( $VAR['invoice_id']);

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
			WHERE INVOICE_ID                = ".$db->qstr( $VAR['invoice_id']);
			
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
		}
	}

        /* send back to the invoice page - this loads the page with no POST variables */
	force_page('invoice', 'new&wo_id='.$wo_id.'&customer_id='.$customer_id.'&invoice_id='.$VAR['invoice_id']);
        
############################################
# Create New Invoice or load from database # // when page loads with no button presssed
############################################

} else {

	/* check if an invoice has been created else create a new invoice for this workorder section done by counting logic*/
	$q = "SELECT count(*) as count FROM ".PRFX."TABLE_INVOICE WHERE WORKORDER_ID=".$db->qstr($wo_id);
	$rs = $db->Execute($q);
	$count = $rs->fields['count'];
        //$invoice_id = $VAR['invoice_id']; // might not be able to use dynamic variables in if statement
        // if no invoice exists for this work order id / new invoice no WO - then create invoice
	if($count == 0 || ($wo_id == "0" && $VAR['invoice_type'] == 'invoice-only')) {

                        $q = "INSERT INTO ".PRFX."TABLE_INVOICE SET
                                        INVOICE_DATE            =".$db->qstr(time()).",
                                        CUSTOMER_ID             =".$db->qstr($customer_id).",
                                        WORKORDER_ID            =".$db->qstr($wo_id ).",
                                        EMPLOYEE_ID             =".$db->qstr($_SESSION['login_id']).",
                                        INVOICE_PAID            ='0',
                                        INVOICE_AMOUNT          ='0.00'";

                        if(!$rs = $db->Execute($q)) {
                                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
                                exit;
                        }

                        $invoice_id = $db->insert_id();

                        /* Update Work Order status and record invoice created */
                        $msg = "Invoice Created ID: ".$invoice_id;

                        // This runs when invoices have attached work orders
                        if($count == 0 && $wo_id > 0){
                                $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
                                                WORK_ORDER_ID			=".$db->qstr($wo_id).",
                                                WORK_ORDER_STATUS_DATE		=".$db->qstr(time()).",
                                                WORK_ORDER_STATUS_NOTES		=".$db->qstr($msg).",
                                                WORK_ORDER_STATUS_ENTER_BY      =".$db->qstr($_SESSION['login_id']);

                                                if(!$result = $db->Execute($sql)) {
                                                        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                                                        exit;
                                                        } else {
                                                               force_page('invoice', 'new&wo_id='.$wo_id.'&customer_id='.$customer_id.'&invoice_id='.$invoice_id);
                                                                }
                                } else {
                                        force_page('invoice', 'new&wo_id='.$wo_id.'&customer_id='.$customer_id.'&invoice_id='.$invoice_id);
                                       }


                // if an invoice exists for this work order id - this loads invoice data and employee display name
                } else if($count == 1 || ($wo_id == "0" && $VAR['invoice_id'] != '')) {
                            $q = "SELECT  ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM  ".PRFX."TABLE_INVOICE
                                            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
                                            WHERE INVOICE_ID=".$VAR['invoice_id'];

                            $rs = $db->execute($q);
                            $invoice = $rs->FetchRow();

                            if($invoice['INVOICE_PAID'] == 1) {
                                    force_page('invoice', "view&invoice_id=".$invoice['INVOICE_ID']."&page_title=Invoice&customer_id=".$invoice['CUSTOMER_ID']);
                                    exit;
                                }

                                   // if more than 1 invoice exists with the same work order id that is not work order id 0
                                   } else if($count > 1 && $wo_id > 0) {
                                            force_page("core", "error&error_msg=Duplicate Invoice's. - WO has more than 1 Invoice");
                                            exit;
                                   }

                                   // add } else { here for another error ie undefined to allow fail gracefully


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

                // Sub_total results
                $labour_sub_total_sum = labour_sub_total_sum($db, $invoice['INVOICE_ID']);
                $parts_sub_total_sum = parts_sub_total_sum($db, $invoice['INVOICE_ID']);
                $smarty->assign('labour_sub_total_sum', $labour_sub_total_sum);
                $smarty->assign('parts_sub_total_sum', $parts_sub_total_sum);

                $smarty->display('invoice'.SEP.'new.tpl');

                // If discount is greate than 100% then these close WO and mark the invoice as paid
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
# If We have a Submit2 		 #
##################################

if(isset($submit2) && $wo_id != "0"){
	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_STATUS		= '6',
			WORK_ORDER_CURRENT_STATUS 	= '8'
			WHERE WORK_ORDER_ID 		=".$db->qstr($wo_id);
			if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
			exit;
			}
            }
