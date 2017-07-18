<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

if(isset($VAR['submit'])) {

    // Create the new Customer
    $customer_id = insert_customer($db, $VAR);
    
    // Load the new Customer's Details page
    force_page('customer', 'details&customer_id='.$customer_id);
    exit;  
              
    
} else {
    
    // Build the page
    $BuildPage .= $smarty->fetch('customer/new.tpl');

}