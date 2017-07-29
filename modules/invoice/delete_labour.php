<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/invoice.php');

// Prevent direct access to this file
if(!check_page_accessed_via_qwcrm()) {
    force_page('invoice', 'search', 'warning_msg='.gettext("No Direct Access Allowed"));
}

// Check if we have an invoice labour_id
if($VAR['labour_id'] == '') {
    force_page('invoice', 'search', 'warning_msg='.gettext("No Invoice Labour ID supplied."));
    exit;
}

// Get invoice ID before deletion
$invoice_id = get_invoice_labour_item_details($db, $VAR['labour_id'], 'invoice_id');

// Delete Invoice Labour item
delete_invoice_labour_item($db, $VAR['labour_id']);

// recalculate the invoice totals and update them
recalculate_invoice_totals($db, $invoice_id);

// load the edit invoice page
force_page('invoice' , 'edit&invoice_id='.$invoice_id);
exit;
