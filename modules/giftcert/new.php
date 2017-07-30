<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/payment.php');

// Check if we have a customer_id
if($customer_id == '') {
    force_page('customer', 'search', 'warning_msg='.gettext("No Customer ID supplied."));
    exit;
}

// Check if giftcert payment method is enabled
if(!check_payment_method_is_active($db, 'gift_certificate_active')) {
    force_page('core', 'dashboard', 'warning_msg='.gettext("Gift Certificate payment method is not enabled. To enable Gift Certificates go to the Help menu and select Control Center. Then under the menu Billing Options select Payment Methods and check Gift Certificate."));
    exit;
}

// if information submitted - add new gift certificate
if(isset($VAR['submit'])) {   
        
    // Create a new gift certificate
    $giftcert_id = insert_giftcert($db, $customer_id, date_to_timestamp($VAR['date_expires']), $VAR['amount'], $VAR['status'], $VAR['notes']);

    // Load the new Gift Certificate's Details page
    force_page('giftcert', 'details&giftcert_id='.$giftcert_id);
    exit;

} else {
    
    // Build the page
    $smarty->assign('customer_details', get_customer_details($db, $customer_id));
    $BuildPage .= $smarty->fetch('giftcert/new.tpl');
}