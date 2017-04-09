<?php

require(INCLUDES_DIR.'modules/company.php');

// If changes submited
if(isset($VAR['submit'])) {
    
    // Update enabled payment methods
    update_payment_methods_status($db, $VAR);
    
    // update payment details - Update Payment Account information, bank details and notifications for invoices
    update_payment_settings($db, $VAR);

    // Assign success message    
    $smarty->assign( 'information_msg', 'Billing Options Updated.' );    
    
}


// Assign variables
$smarty->assign( 'payment_methods_status', get_payment_methods_status($db) );
$smarty->assign( 'payment_settings', get_payment_settings($db));

$BuildPage .= $smarty->fetch('company/payment_options.tpl');
