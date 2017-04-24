<?php

require(INCLUDES_DIR.'modules/customer.php');

if(isset($VAR['submit'])) {

    if (!$customer_id = insert_customer($db, $VAR)){
        
        $smarty->assign('error_msg', 'Failed to insert customer');
        $BuildPage .= $smarty->fetch('core/error.tpl');
        
    } else {
        force_page('customer', 'details&customer_id='.$customer_id, 'information_msg=Added New Customer successfully');
        exit;    
    }            
    
} else {
    
    $BuildPage .= $smarty->fetch('customer/new.tpl');

}