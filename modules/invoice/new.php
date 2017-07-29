<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Create an invoice for the supplied workorder
if($workorder_id != '0' && !check_workorder_has_invoice($db, $workorder_id)) {

    // Get Customer_id from the workorder    
    $customer_id = get_workorder_details($db, $workorder_id, 'customer_id');
    
    // Create the invoice and return the new invoice_id
    $invoice_id = insert_invoice($db, $customer_id, $workorder_id, get_customer_details($db, $customer_id, 'discount_rate'), get_company_details($db, 'tax_rate'));

    // Update Work Order Status (to waiting for payment)
    update_workorder_status($db, $workorder_id, 7);
    
    // Add a workorder history note
    insert_workorder_history_note($db, $workorder_id, gettext("Invoice Created ID").': '.$invoice_id);

    // Load the newly created invoice edit page
    force_page('invoice', 'edit&invoice_id='.$invoice_id);
    exit;
    
} 

// Invoice only
if(($customer_id != '' && $workorder_id == '0' && $VAR['invoice_type'] == 'invoice-only')) {
    
    // Create the invoice and return the new invoice_id
    $invoice_id = insert_invoice($db, $customer_id, $workorder_id, 0, get_company_details($db,'tax_rate'));

    // Load the newly created invoice edit page
    force_page('invoice', 'edit&invoice_id='.$invoice_id);
    exit;    
}    
  
// Fallback Error Control 
force_page('workorder', 'search', 'warning_msg='.gettext("You cannot create an invoice by the method you just tried, report to admins"));
exit;
