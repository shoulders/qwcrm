<?php

require(INCLUDES_DIR.'modules/invoice.php');

// if not invoice_id
if($invoice_id == '') {
    /* If no work order ID then we dont belong here */
    force_page('core', 'error&error_msg=Invoice Not found: Invoice ID: '.$invoice_id.'&menu=1');
}

    
////////////////////////////////////////////////////////////////

// My fixed
$format = $date_format;
$smarty->assign('company2', get_company_details($db, 'INVOICE_MSG'));
$smarty->assign('sss', get_company_details($db, 'INVOICE_MSG'));
$customer1 = display_customer_info($db, $customer_id);
$invoice3 = get_invoice_details($db, $invoice_id);
$company1 = get_company_details($db);
$setup1 = get_payment_details($db);
$company = get_company_details($db);
$payments = get_invoice_transactions($db, $invoice_id);
$parts = get_invoice_parts_items($db, $invoice_id);
$labor = get_invoice_labour_items($db, $invoice_id);
$stats2 = get_workorder_status($db, $workorder_id);
$stats = get_workorder_details($db, $workorder_id);
$invoice = get_invoice_details($db, $invoice_id);
$labour_sub_total_sum = labour_sub_total($db, $invoice_id);
$parts_sub_total_sum =  parts_sub_total($db, $invoice_id);



////////////////////////////////////////////////////////////////
    



//Company Details
$cname = $company1['COMPANY_NAME'];
$caddress = $company1['COMPANY_ADDRESS'];
$ccity = $company1['COMPANY_CITY'];
$cstate = $company1['COMPANY_STATE'];
$cphone = $company1['COMPANY_PHONE'];
$cemail = $company1['COMPANY_EMAIL'];
$cabn = $company1['COMPANY_ABN'];
$cthankyou = $setup1['INV_THANK_YOU'];
$currency_sym = utf8_decode($company1['COMPANY_CURRENCY_SYMBOL']);

//Customer Details
$cusdisplay = $customer1['CUSTOMER_DISPLAY_NAME'];
$cusnamef = $customer1['CUSTOMER_FIRST_NAME'];
$cusnamel = $customer1['CUSTOMER_LAST_NAME'];
$cusaddress = $customer1['CUSTOMER_ADDRESS'];
$cuscity = $customer1['CUSTOMER_CITY'];
$cuszip = $customer1['CUSTOMER_ZIP'];
$cusstate = $customer1['CUSTOMER_STATE'];
$cusphone = $customer1['CUSTOMER_PHONE'];
$cusemail = $customer1['CUSTOMER_EMAIL'];
$custerms = $customer1['CREDIT_TERMS'];

// work Order Details
$wo_description = $stats['WORK_ORDER_DESCRIPTION'];
$wo_resolution = $stats['WORK_ORDER_RESOLUTION'];

//invoice details
$totalinv = $invoice3['SUB_TOTAL'];
$discinv = $invoice3['DISCOUNT'];
$shipinv = $invoice3['SHIPPING'];
$taxinv = $invoice3['TAX'];
$paidamntinv = $invoice3['PAID_AMOUNT'];
$amntinv = $invoice3['INVOICE_AMOUNT'];


// $balinv = sprintf( "%.2f",$balinv);
$balinv = sprintf( "%.2f",$invoice3['BALANCE']);

//PayPal Amount with 1.5% Surcharge Applied
$pamount= ($balinv)* 1.015;
$pamount = sprintf( "%.2f",$pamount);

// Paymate Amount with Surcharge Applied
$paymate_amt= ($balinv)* ((($setup1['PAYMATE_FEES'])/100)+1);
$paymate_amt = sprintf( "%.2f", $paymate_amt);



if(empty($labor)){$smarty->assign('labor', 0);} else {$smarty->assign('labor', $labor);}
if(empty($parts)){$smarty->assign('parts', 0);} else {$smarty->assign('parts', $parts);}
if(empty($stats)){$smarty->assign('stats', 0);} else {$smarty->assign('stats', $stats);}
if(empty($stats2)){$smarty->assign('stats2', 0);} else {$smarty->assign('stats2', $stats2);}
if(empty($payments)){$smarty->assign('payments', 0);} else {$smarty->assign('payments', $payments);}
if(empty($paid)){$smarty->assign('paid', 0);} else {$smarty->assign('paid', $paid);}


//$html_print = $rs->fields['HTML_PRINT'];
//$pdf_print  = $rs->fields['PDF_PRINT'];
$CHECK_PAYABLE  =  $rs->fields['CHECK_PAYABLE'];
$DD_NAME  =  $rs->fields['DD_NAME'];
$DD_BANK  =  $rs->fields['DD_BANK'];
$DD_BSB  =  $rs->fields['DD_BSB'];
$DD_ACC  =  $rs->fields['DD_ACC'];
$DD_INS  =  $rs->fields['DD_INS'];
$PP_ID  =  $rs->fields['PP_ID'];
$PAYMATE_LOGIN  =  $rs->fields['PAYMATE_LOGIN'];
$PAYMATE_FEES  =  $rs->fields['PAYMATE_FEES'];

$smarty->assign('thank_you',$thank_you);
$smarty->assign('trans',$trans);
$smarty->assign('paid',$paid);
$smarty->assign('customer_details',$customer_details);
$smarty->assign('customer1',$customer1);
$smarty->assign('invoice',$invoice);
$smarty->assign('PP_ID', $PP_ID);
$smarty->assign('DD_NAME', $DD_NAME);
$smarty->assign('DD_BSB', $DD_BSB);
$smarty->assign('DD_ACC', $DD_ACC);
$smarty->assign('DD_INS', $DD_INS);
$smarty->assign('DD_BANK', $DD_BANK);
$smarty->assign('CHECK_PAYABLE',$CHECK_PAYABLE);
$smarty->assign('PAYMATE_LOGIN',$PAYMATE_LOGIN);
$smarty->assign('company',$company);
    
//$smarty->assign('CURRENCY_CODE',$CURRENCY_CODE);
//$smarty->assign('currency_sym',$currency_sym);
/*$smarty->assign('country',$country);*/  // this causes headers to be sent
$smarty->assign('pamount',$pamount);
$smarty->assign('paymate_amt',$paymate_amt);
$smarty->assign('PAYMATE_FEES',$PAYMATE_FEES);
$smarty->assign('parts_sub_total_sum', $parts_sub_total_sum);
$smarty->assign('labour_sub_total_sum', $labour_sub_total_sum);
$smarty->assign('wo_description', $wo_description);
$smarty->assign('wo_resolution', $wo_resolution);

    

/////////////////////////////////////////////////////////////////////////////////////////    
    
    
    
/* Invoice Print Routine */
if($VAR['print_content'] == 'invoice') {
    
    // Print HTML Invoice
    if ($VAR['print_type'] == 'print_html') {        
        $BuildPage .= $smarty->fetch('invoice/printing/print_invoice.tpl');    
        
    // Print PDF Invoice
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        // Get Print Invoice as HTML into a variable
        $pdf_output = $smarty->fetch('invoice/printing/print_invoice.tpl');    
        // Call mPDF and output as PDF to page      
        require_once(INCLUDES_DIR.'mpdf.php');         
        
    // Email PDF Invoice
    } elseif($VAR['print_type'] == 'email_pdf') {        
        // add pdf creation routing here
    
    // if print options are set but no valid    
    } else {            
        force_page('core', "error&menu=1&error_msg=No Printing Options set. Please set up printing options in the Control Center.&type=error - print content selected but no print type");
        exit; 
    }
}

/* Address Only Print Routine */
if($VAR['print_content'] == 'address') {
    
    // Print HTML Address
    if ($VAR['print_type'] == 'print_html') {        
        $BuildPage .= $smarty->fetch('invoice/printing/print_address.tpl');     

    // if print options are set but no valid    
    } else {            
        force_page('core', "error&menu=1&error_msg=No Printing Options set. Please set up printing options in the Control Center.&type=error - print content selected but no print type");
        exit; 
    }
}