<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if(empty($VAR['workorder_id'])){    
    force_page('core', 'error', 'error_type=warning&error_location=workorder:status&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_print_loadpage_failed').'&php_error_msg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
}
    
// Load the specifed workorder details
$single_work_order = display_single_open_workorder($db, $workorder_id);

// Get the Id of the employee assigned to the work order
$assigned_employee_id = $single_work_order['0']['WORK_ORDER_ASSIGN_TO'];

// Update Work Order Status
if(isset($VAR['assign_status'])){
    update_workorder_status($db, $workorder_id, $VAR['assign_status']);    
}

// Assign Work Order to another employee - This is automatically created by <form>{$employee_list}</form>
if (isset($VAR['assign_employee'])) {
    assign_work_order_to_employee($db, $workorder_id, $login_id, $assigned_employee_id, $VAR['assign_employee_val']);
}

// Delete a Work Order
if (isset($VAR['delete'])) {
    delete_work_order($db, $workorder_id, $login_id);            
}

// Display the page with the current status from the database 

$smarty->assign('employee_list',        build_active_employee_form_option_list($db, $assigned_employee_id)  );
$smarty->assign('single_workorder',     $single_work_order                                                  );
$smarty->assign('workorder_id',         $workorder_id                                                       );

$smarty->display('workorder'.SEP.'status.tpl');