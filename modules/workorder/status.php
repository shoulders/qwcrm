<?php

require_once('include.php');

if(empty($VAR['wo_id'])){    
    force_page('core', 'error', 'error_type=warning&error_location=workorder:status&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_print_loadpage_failed').'&database_error='.$db->ErrorMsg());
    exit;
}
    
$wo_id                  = $VAR['wo_id'];
$assign_status          = $VAR['assign_status'];
$logged_in_employee_id  = $_SESSION['login_id'];
$target_employee_id     = $VAR['assign_employee_val']; // This is automatically created by <form>{$employee_list}</form>

/* Load the specifed work order details */
$single_work_order = display_single_open_workorder($db, $wo_id);

/* Get the Id of the employee assigned to the work order */
$assigned_employee_id = $single_work_order['0']['WORK_ORDER_ASSIGN_TO'];

/* Update Work Order Status */
if(isset($VAR['assign_status'])){
    update_status($db, $wo_id, $assign_status);    
}

/* Assign Work Order to another employee */
if (isset($VAR['assign_employee'])) {
    assign_work_order_to_employee($db, $wo_id, $logged_in_employee_id, $assigned_employee_id, $target_employee_id);
}

/* Delete a Work Order */
if (isset($VAR['delete'])) {
    delete_work_order($db, $wo_id, $logged_in_employee_id);            
}

$smarty->assign('employee_list',        build_active_employee_form_option_list($db, $assigned_employee_id)  );
$smarty->assign('single_workorder',     $single_work_order                                                  );
$smarty->assign('wo_id',                $wo_id                                                              );

$smarty->display('workorder'.SEP.'status.tpl');