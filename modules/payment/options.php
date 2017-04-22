<?php

require(INCLUDES_DIR.'modules/payment.php');

// If changes submited
if(isset($VAR['submit'])) {
    
    // Update enabled payment methods
    update_payment_methods_status($db, $VAR);
    
    // Update Payment details
    update_payment_settings($db, $VAR);

    // Assign success message    
    $smarty->assign( 'information_msg', 'Payment Options Updated.' );    
    
}

// Assign variables
$smarty->assign( 'payment_methods_status',  get_payment_methods_status($db) );
$smarty->assign( 'payment_settings',        get_payment_details($db)        );

// Fetch page
$BuildPage .= $smarty->fetch('payment/options.tpl');
