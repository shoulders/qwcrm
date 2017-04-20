<?php

require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/employee.php');

// Check that there is a workorder_id set
if(empty($workorder_id)){
    force_page('workorder', 'overview', 'warning_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_noworkorderid'));
    exit;
}

// Get the Id of the employee assigned to the workorder
$assigned_employee_id = get_workorder_details($db, $workorder_id, 'WORK_ORDER_ASSIGN_TO');

// Update Work Order Status
if(isset($VAR['change_status'])){
    update_workorder_status($db, $workorder_id, $VAR['assign_status']);
    //force_page('workorder', 'details', 'workorder_id='.$workorder_id.'&information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_statusupdated'));
    force_page('workorder', 'status', 'workorder_id='.$workorder_id.'&information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_statusupdated'));
    exit; 
}

// Assign Work Order to another employee
if(isset($VAR['change_employee'])) {
    assign_workorder_to_employee($db, $workorder_id, $login_id, $assigned_employee_id, $VAR['target_employee_id']);    
    //force_page('workorder', 'details', 'workorder_id='.$workorder_id.'&information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_employeeupdated'));
    force_page('workorder', 'status', 'workorder_id='.$workorder_id.'&information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_status_employeeupdated'));
    exit; 
}

// Delete a Work Order
if(isset($VAR['delete'])) {    
    force_page('workorder', 'delete', 'workorder_id='.$workorder_id);
    exit;
}

// Fetch the page with the current status from the database
$smarty->assign('active_employees',                 get_active_employees($db)                                                   );
$smarty->assign('workorder_status',                 get_workorder_details($db, $workorder_id, 'WORK_ORDER_STATUS')              );
$smarty->assign('assigned_employee',                $assigned_employee_id                                                       );
$smarty->assign('assigned_employee_display_name',   get_employee_details($db, $assigned_employee_id, 'EMPLOYEE_DISPLAY_NAME')   );

$BuildPage .= $smarty->fetch('workorder/status.tpl');