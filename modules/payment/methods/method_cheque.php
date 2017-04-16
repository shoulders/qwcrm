<?php

/* Pre-Processing */
// goes here

// Validate the basic invoice totals after the transaction is applied, then if successful return the results
if(!$new_invoice_totals = validate_payment_method_totals($db, $invoice_id, $VAR['amount'])) {
    
    // Do nothing - Specific Error information has already been set via postEmulation    
    
} else {

    /* Processing */

    // Live processing goes here

    // Create a specific memo string (if applicable)
    $method_memo = 'Cheque Number: '.$VAR['cheque_number'];

    // Insert the transaction with the calculated information
    insert_payment_method_transaction($db, $invoice_id, $VAR['amount'], $method, $VAR['type'], $method_memo, $VAR['memo']);
    
    // Assign Success message
    $smarty->assign('information_msg', 'Cheque payment added successfully');
    
    /* Post-Processing */
    // goes here    
        
    // After a sucessful process redirect to the invoice payment page
    //force_page('invoice', 'details&invoice_id='.$invoice_id, 'information_msg=Full Payment made successfully');
    
}