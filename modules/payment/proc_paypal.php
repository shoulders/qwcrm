<?php

$paypal_amount  = $VAR['paypal_amount'];

$company        = get_company_details($db, 'NAME');
$country        = get_company_details($db, 'COUNTRY');

$invoice = get_invoice_details($db, $invoice_id);

// Check to see if we are processing more then required
if($invoice['BALANCE'] < $paypal_amount){
    force_page('payment', 'new&workorder_id='.$workorder_id.'&customer_id='.$customer_id.'    &invoice_id='.$invoice_id.'&error_msg= You can not bill more than the amount of the invoice.');
    exit;
}

/* get pay pal login */
$q = "SELECT PP_ID FROM ".PRFX."SETUP";
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
$pay_pal_email    = $rs->fields['PP_ID'];

$smarty->assign('amount', $VAR['paypal_amount']);

$BuildPage .= $smarty->fetch('payment/proc_paypal.tpl');