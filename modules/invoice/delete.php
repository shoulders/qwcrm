<?php

require(INCLUDES_DIR.'modules/invoice.php');

// Delete Invoice
if(!delete_invoice($db, $invoice_id)) {
     force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
} else {
    force_page('invoice' , 'paid');
    exit;
}