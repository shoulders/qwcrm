<?php

/* Pre-Processing */
// goes here
// validate gift certificate is valid - cannot split gift cards between inoices
//validate_giftcert_code();

// Check the Gift Certificate exists and return the details
if(!$giftcert_id = get_giftcert_id_by_code($db, $VAR['giftcert_code'])) {
    force_page('core', 'error', 'error_msg=There is no Gift certificate with that code');
    exit;    
}

// Make sure the Gift Certificate is valid and then pass the amount to the next process
if(validate_giftcert_code($db, $giftcert_id)) {
    $VAR['amount'] = get_giftcert_details($db, $giftcert_id['AMOUNT']);
} else {
    force_page('core', 'error', 'error_msg=This is not a valid Gift Certificate');
    exit;
}

/* Invoice Processing */

// Validate the basic invoice totals after the transaction is applied, then if successful return the results
if(!$new_invoice_totals = validate_payment_method_totals($db, $invoice_id, $VAR['amount'])) {
    
    // Do nothing - Specific Error information has already been set via postEmulation    
    
} else {

    /* Processing */

    // Live processing goes here

    // Create a specific memo string (if applicable)
    $method_memo = 'Gift Certificate Code: '.$VAR['giftcert_code'];    

    // Insert the transaction with the calculated information
    insert_payment_method_transaction($db, $invoice_id, $VAR['amount'], $method, $VAR['type'], $method_memo, $VAR['memo']);
    
    // Assign Success message
    $smarty->assign('information_msg', 'Gift Certificate applied successfully');
    
    /* Post-Processing */
    
    // Update the Gift Certificate
    update_giftcert_as_redeemed($db, $giftcert_id, $invoice_id);
        
    // After a sucessful process redirect to the invoice payment page
    //force_page('invoice', 'details&invoice_id='.$invoice_id, 'information_msg=Full Payment made successfully');
    
}