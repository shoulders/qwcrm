<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/giftcert.php');
//require(INCLUDES_DIR.'modules/payment.php');

// Make sure there is a giftcert_id
if($giftcert_id == '') {
    force_page('core', 'error&error_msg=No Customer ID&menu=1&type=database');
    exit;
}

/* check if giftcert payment method is enabled
if(!check_payment_method_is_active($db, 'gift_certificate_active')) {
    force_page('core', 'error','error_msg=Gift Certificate are not enabled. To enable gift certificates go to the Help menu and select Control Center. Then under the menu Billing Options select Payment Methods and check Gift Certificate.');
    exit;
}*/

// if information submitted - add new gift certificate
if(isset($VAR['submit'])) {   
        
    // Create a new gift certificate
    update_giftcert($db, $giftcert_id, date_to_timestamp($VAR['date_expires']), $VAR['amount'], $VAR['status'], $VAR['notes']);

    // Load the new Gift Certificate's Details page
    force_page('giftcert', 'details&giftcert_id='.$giftcert_id);

} else {
    
    // Fetch and display the page
    $smarty->assign('customer_details', get_customer_details($db, get_giftcert_details($db, $giftcert_id, 'CUSTOMER_ID')));
    $smarty->assign('giftcert_details', get_giftcert_details($db, $giftcert_id));
    $BuildPage .= $smarty->fetch('giftcert/edit.tpl');
}