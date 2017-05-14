<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_id
if($customer_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}

if(isset($VAR['submit'])) {    
        
    if (!update_customer($db, $customer_id, $VAR)){
        force_page('customer', 'edit&customer_id='.$customer_id, 'error_msg=Failed to Update Customer Information');
        exit;
    } else {
        force_page('customer', 'details&customer_id='.$customer_id, 'msg=The Customers information was updated');
        exit;
    }

} else {    

    $smarty->assign('customer_details', get_customer_details($db, $customer_id));
    $BuildPage .= $smarty->fetch('customer/edit.tpl');
    
}