<?php

// Validate the basic invoice totals after the transaction is applied, then if successful return the results
if(!$new_invoice_totals = validate_payment_method_totals($db, $invoice_id, $VAR['amount'])) {
    
    // Do nothing
    
    // Specific Error information has already been set via postEmulation
    //force_page('invoice', 'details&invoice_id='.$invoice_id);
    
} else {

    /* Method specific stuff goes here */

    // Live processing (maybe at a later date)

    // Create a specific memo string (if applicable)
    $method_memo = '';

    /* Finalising */

    // Insert the transaction with the calculated information
    insert_payment_method_transaction($db, $invoice_id, $VAR['amount'], $VAR['method'], $VAR['type'], $method_memo, $VAR['memo']);

    // After a sucessful process redirect to the invoice payment page
    //force_page('invoice', 'details&invoice_id='.$invoice_id, 'information_msg=Full Payment made successfully');
    
    $smarty->assign('information_msg', 'Cash payment added successfully');
    
}