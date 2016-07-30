<?php

require_once('include.php');

if(empty($VAR['wo_id'])){
    force_page('core', 'error&error_msg=No Work Order ID');
    exit;
}
    













/* new shit */

$logged_in_employee_id  = $_SESSION['login_id'];
$target_employee_id     = $VAR['assign_employee_val']; // This is automatically created by <form>{$employee_list}</form>


/* Grab specified Work Order information */
$single_work_order = display_single_open_workorder($db, $wo_id);

/* Get the Id of the employee assigned to the work order */
$assigned_employee_id = $single_work_order['0']['WORK_ORDER_ASSIGN_TO'];




/* Build <option></option> list for a <form></form> to select employee for 'Assign To' feature */

// select all employees and return their display name and ID as an array
$q = "SELECT EMPLOYEE_DISPLAY_NAME, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
// i think $rs is adobd
if(!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_mysql_error').': '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
}
// Get ADODB to build the form using the loaded dataset $rs
$employee_list = $rs->GetMenu2('assign_employee_val', $assigned_employee_id ,false ); //GetMenu2('assign_employee_val, null, false') will turn off the blank option
// Assign to smarty
$smarty->assign('employee_list', $employee_list);









/* Delete a Work Order */
if (isset($VAR['delete'])) {
    delete_work_order($db, $wo_id, $logged_in_employee_id);            
}
//$smarty->assign('submit', $submit);

/* Assign Work Order to another employee and log it */
if (isset($VAR['assign_employee'])) {
     assign_work_order_to_employee($db, $wo_id, $logged_in_employee_id, $assigned_employee_id, $target_employee_id);
}
//$smarty->assign('assign', $assign);



$smarty->assign('single_workorder',         display_single_open_workorder($db, $wo_id)  );


//////////////////
$assign_status = $VAR['assign_status'];

if(isset($VAR['assign_status'])){

    if (!update_status($db, $wo_id, $assign_status)){
        force_page('core', 'error&error_msg=Failed to update work order status');
        exit;
    } else {
        force_page('workorder', 'details&wo_id='.$VAR['wo_id'].'&page_title=Work Order ID '.$VAR['wo_id']);
        exit;
    }

} else {
    $smarty->assign('wo_id', $VAR['wo_id']);
    $smarty->display('workorder'.SEP.'status.tpl');
}
