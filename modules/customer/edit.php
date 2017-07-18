<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

// Check if we have a customer_id
if($customer_id == '') {
    force_page('customer', 'search', 'warning_msg='.gettext("No Customer ID supplied."));
    exit;
}

if(isset($VAR['submit'])) {    
        
    // Update the Customer's Details
    update_customer($db, $customer_id, $VAR);
    
    // Load the customer's details page
    force_page('customer', 'details&customer_id='.$customer_id, 'information_msg='.gettext("The Customer's information was updated."));
    exit;    

} else {    

    // Build the page
    $smarty->assign('customer_details', get_customer_details($db, $customer_id));
    $BuildPage .= $smarty->fetch('customer/edit.tpl');
    
}