<?php

require(INCLUDES_DIR.'modules/invoice.php');

// Get invoice ID before deletion
$invoice_id = get_invoice_parts_item_details($db, $VAR['parts_id'], 'INVOICE_ID');
    
// Delete Invoice Labour item
if(!delete_invoice_parts_item($db, $VAR['parts_id'])) {
     force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
} else {
    recalculate_invoice_totals($db, $invoice_id);
    force_page('invoice' , 'edit&invoice_id='.$invoice_id);
    exit;
}


