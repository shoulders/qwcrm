<?php

// Load the Refund Functions
require_once('include.php');

// Load the Translation for this Module
if(!xml2php('invoice')) {
    $smarty->assign('error_msg',"Error in language file");
}

$langvals = gateway_xml2php(invoice);

$wo_id = $VAR['wo_id'];
$customer_id = $VAR['customer_id'];
$invoice_id = $VAR['invoice_id'];
$labourID = $VAR['labourID'];
$partsID = $VAR['partsID'];

// Labour Delete Record
if(isset($VAR['deleteType']) && $VAR['deleteType'] == "labourRecord") {

    // Delete the labour record Function call
    if(!delete_labour_record($db, $labourID)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        force_page('invoice', 'new&invoice_id='.$invoice_id.'&wo_id='.$wo_id.'&page_title='.$langvals['invoice_invoice'].'&customer_id='.$customer_id);
        exit;
    }

}

// Parts Delete Record
if(isset($VAR['deleteType']) && $VAR['deleteType'] == "partsRecord") {

    // Delete the labour record Function call
    if(!delete_parts_record($db, $partsID)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        force_page('invoice', 'new&invoice_id='.$invoice_id.'&wo_id='.$wo_id.'&page_title='.$langvals['invoice_invoice'].'&customer_id='.$customer_id);
        exit;
    }

}
// Delete Invoice
if(!delete_invoice($db, $invoice_id, $customer_id, $login_usr)) {
     force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
} else {
    force_page('invoice' , 'view_paid&page_title=Paid%20Invoices');
    exit;
}