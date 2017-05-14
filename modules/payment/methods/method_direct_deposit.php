<?php

defined('_QWEXEC') or die;

/* Pre-Processing */
// goes here

/* Invoice Processing */

// Validate the basic invoice totals after the transaction is applied, then if successful return the results
if(!$new_invoice_totals = validate_payment_method_totals($db, $invoice_id, $VAR['amount'])) {
    
    // Do nothing - Specific Error information has already been set via postEmulation    
    
} else {

    /* Processing */

    // Live processing goes here

    // Create a specific note string (if applicable)
    $method_note = 'Deposit Reference:  '.$VAR['deposit_reference'];    

    // Insert the transaction with the calculated information
    insert_payment_method_transaction($db, $invoice_id, $VAR['amount'], $method, $VAR['type'], $method_note, $VAR['note']);
    
    // Assign Success message
    $smarty->assign('information_msg', 'Direct Deposit payment added successfully');
    
    /* Post-Processing */
    // goes here    
        
    // After a sucessful process redirect to the invoice payment page
    //force_page('invoice', 'details&invoice_id='.$invoice_id, 'information_msg=Full Payment made successfully');
    
}