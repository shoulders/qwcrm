<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/invoice.php');

// Check if we have an invoice_id
if($invoice_id == '') {
    force_page('invoice', 'search', 'warning_msg='.gettext("No Invoice ID supplied."));
    exit;
}

// Delete Invoice
if(!delete_invoice($db, $invoice_id)) {
    
    // Load the invoice details page with error
    force_page('invoice', 'details&invoice_id='.$invoice_id);
    exit;
    
} else {
    
    // load the work order invoice page
    force_page('invoice', 'search', 'information_msg='.gettext("The invoice has been deleted successfully."));
    exit;
    
}