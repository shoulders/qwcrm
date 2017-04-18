<?php

require(INCLUDES_DIR.'modules/invoice.php');

// check if we have a invoice_id and if so get details
if($invoice_id == '' || $invoice_id == '0'){
    force_page('core', 'error', 'error_msg=Invoice Not found: Invoice ID: '.$invoice_id.'&menu=1');
    exit;
}

// Delete Invoice
if(!delete_invoice($db, $invoice_id)) {
     force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
} else {
    force_page('invoice' , 'paid');
    exit;
}