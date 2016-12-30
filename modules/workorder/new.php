<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a customer_id set
if($VAR['customer_id'] == '' ) {   
    force_page('workorder', 'open', 'warning_msg='.$smarty->get_template_vars('translate_workorder_error_message_new_loadpage_no_customer_id'));
    exit;
}

// If a workorder is submitted
if(isset($VAR['submit'])){
    
    insert_new_workorder($db, $customer_id, $VAR['created_by'], $VAR['workorder_scope'], $VAR['workorder_description'], $VAR['workorder_comments'], $VAR['workorder_note']);      
    force_page('workorder', 'details', 'workorder_id='.$workorder_id);
    exit;
        
   
// Display the page ready for a workorder submission   
} else {

    $smarty->assign('customer_details', display_customer_info($db, $customer_id));

    $smarty->display('workorder/new.tpl');
    
}