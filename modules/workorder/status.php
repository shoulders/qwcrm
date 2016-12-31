<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if(empty($VAR['workorder_id'])){    
    force_page('workorder', 'overview', 'warning_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_noworkorderid'));
    exit;
}
    
// Load the specifed workorder details
$single_work_order = display_single_workorder($db, $workorder_id);

// Get the Id of the employee assigned to the work order
$assigned_employee_id = $single_work_order['0']['WORK_ORDER_ASSIGN_TO'];

// Update Work Order Status
if(isset($VAR['assign_status'])){
    update_workorder_status($db, $workorder_id, $VAR['assign_status']);
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_statusupdated'));
    exit; 
}

// Assign Work Order to another employee
if (isset($VAR['assign_employee'])) {
    assign_workorder_to_employee($db, $workorder_id, $login_id, $assigned_employee_id, $VAR['assign_employee_val']);    
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_employeeupdated'));
    exit; 
}

// Delete a Work Order
if (isset($VAR['delete'])) {
    delete_workorder($db, $workorder_id, $login_id);
    force_page('workorder', 'overview', 'information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_deleted'));
    exit;
}

// Display the page with the current status from the database 

$smarty->assign('employee_list',        build_active_employee_form_option_list($db, $assigned_employee_id)  );
$smarty->assign('single_workorder',     $single_work_order                                                  );
$smarty->assign('workorder_id',         $workorder_id                                                       );

$smarty->display('workorder/status.tpl');