<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/user.php');

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Get the Id of the employee assigned to the workorder
$assigned_employee_id = get_workorder_details($db, $workorder_id, 'WORK_ORDER_ASSIGN_TO');

// Update Work Order Status
if(isset($VAR['change_status'])){
    update_workorder_status($db, $workorder_id, $VAR['assign_status']);    
    force_page('workorder', 'status', 'workorder_id='.$workorder_id.'&information_msg='.gettext("Work Order status updated."));
    exit; 
}

// Assign Work Order to another employee
if(isset($VAR['change_employee'])) {
    assign_workorder_to_employee($db, $workorder_id, $login_user_id, $assigned_employee_id, $VAR['target_employee_id']);    
    force_page('workorder', 'status', 'workorder_id='.$workorder_id.'&information_msg='.gettext("Assigned employee updated."));
    exit; 
}

// Delete a Work Order
if(isset($VAR['delete'])) {    
    force_page('workorder', 'delete', 'workorder_id='.$workorder_id);
    exit;
}

// Build the page with the current status from the database
$smarty->assign('active_employees',             get_active_users($db, 'employees')                              );
$smarty->assign('workorder_status',             get_workorder_details($db, $workorder_id, 'WORK_ORDER_STATUS')  );
$smarty->assign('assigned_employee_id',         $assigned_employee_id                                           );
$smarty->assign('assigned_employee_details',    get_user_details($db, $assigned_employee_id)                    );

$BuildPage .= $smarty->fetch('workorder/status.tpl');