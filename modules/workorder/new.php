<?php

require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/customer.php');

// Check that there is a customer_id set
if($VAR['customer_id'] == '' ) {   
    force_page('workorder', 'overview', 'warning_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_new_nocustomerid'));
    exit;
}

// If a workorder is submitted
if(isset($VAR['submit'])){
    
    insert_workorder($db, $customer_id, $VAR['created_by'], $VAR['workorder_scope'], $VAR['workorder_description'], $VAR['workorder_comments']);      
    force_page('workorder', 'overview', 'information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_new_created'));
    exit;
        
   
// Fetch the page ready for a workorder submission   
} else {

    $smarty->assign('customer_details', get_customer_details($db, $customer_id));

    $BuildPage .= $smarty->fetch('workorder/new.tpl');
    
}