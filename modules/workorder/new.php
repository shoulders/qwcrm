<?php

require('includes'.SEP.'modules'.SEP.'workorder.php');

$wo_id                  = $VAR['wo_id']; 
$submit                 = $VAR['submit'];
$email                  = $VAR['email'];
$customer_id            = $VAR['customer_id'];
$created_by             = $VAR['created_by'];
$workorder_scope        = $VAR['workorder_scope'];
$workorder_description  = $VAR['workorder_description'];
$workorder_comments     = $VAR['workorder_comments'];
$workorder_note         = $VAR['workorder_note'];

/* email the work order - not sure this is enabled, maybe put in the submit funciton below */
if (isset($VAR['email']) && $VAR['email'] != '') {
    if (!email_new_workorder($db,$VAR)) {
        $smarty->display('workorder'.SEP.'new.tpl');
    }
}

/* If data submitted do this */
if(isset($VAR['submit'])){    
    insert_new_workorder($db, $customer_id, $created_by, $workorder_scope, $workorder_description, $workorder_comments, $workorder_note);        
}
    
/* Blank Page for submitting a new Work Order - but must have a Customer ID*/
if(!isset($VAR['customer_id']) || $VAR['customer_id'] === '' ) {   
    force_page('core', 'error', 'error_type=warning&error_location=workorder:new&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_new_loadpage_failed').'&php_error_msg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
} else {

    $smarty->assign('customer_details', display_customer_info($db, $customer_id));

    $smarty->display('workorder'.SEP.'new.tpl');
}