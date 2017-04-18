<?php

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_note_id
if($customer_note_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}
    
// Delete Customer Note
if($VAR['action'] == 'delete') {

    // Get the customer_id before we delete the record
    $customer_id = get_customer_note($db, $customer_note_id, 'CUSTOMER_ID');
    
    // Delete the record
    delete_customer_note($db, $VAR['customer_note_id']);
    
    // Reload the customers details page
    force_page('customer', 'details&customer_id='.$customer_id);

}

