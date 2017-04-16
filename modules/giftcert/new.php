<?php

require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/payment.php');

// Make sure there is a customer_id
if($customer_id == '') {
    force_page('core', 'error', 'error_msg=No Customer ID');
    exit;
}

// check if giftcert payment method is enabled
if(!check_payment_method_is_active($db, 'gift_certificate_active')) {
    force_page('core', 'error','error_msg=Gift Certificate are not enabled. To enable gift certificates go to the Help menu and select Control Center. Then under the menu Billing Options select Payment Methods and check Gift Certificate.');
    exit;
}

// if information submitted - add new gift certificate
if(isset($VAR['submit'])) {   
        
    // Create a new gift certificate
    $giftcert_id = insert_giftcert($db, $customer_id, date_to_timestamp($VAR['date_expires']), generate_giftcert_code(), $VAR['amount'], $var['memo']);

    // Load the new Gift Certificate's Details page
    force_page('giftcert', 'details&giftcert_id='.$giftcert_id);

} else {
    
    // Fetch and display the page
    $BuildPage .= $smarty->fetch('giftcert/new.tpl');
}