<?php

require_once('include.php');

// PHP side translation
$langvals = gateway_xml2php();

// Labour Delete Record
if(isset($VAR['deleteType']) && $VAR['deleteType'] == "labourRecord") {

    // Delete the labour record Function call
    if(!delete_labour_record($db, $VAR['labourID'])) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        force_page('invoice', 'new&invoice_id='.$invoice_id.'&workorder_id='.$workorder_id.'&page_title='.$langvals['invoice_invoice'].'&customer_id='.$customer_id);
        exit;
    }

}

// not sure these are used but i could employee them in making the delete invoice work without having to delete all items first.

// Parts Delete Record
if(isset($VAR['deleteType']) && $VAR['deleteType'] == "partsRecord") {

    // Delete the labour record Function call
    if(!delete_parts_record($db, $VAR['partsID'])) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        force_page('invoice', 'new&invoice_id='.$invoice_id.'&workorder_id='.$workorder_id.'&page_title='.$langvals['invoice_invoice'].'&customer_id='.$customer_id);
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