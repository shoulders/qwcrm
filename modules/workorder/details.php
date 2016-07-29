<?php

require_once('include.php');

// change $login -> $assigned_employee - makes better sense
// $login = the login username

/* Grab passed varibles */
$submit                 = $VAR['submit'];
$wo_id                  = $VAR['wo_id'];
$logged_in_employee_id  = $_SESSION['login_id'];
$target_employee_id     = $VAR['assign_employee_val']; // This is automatically created by <form>{$employee_list}</form>







/* Check for Open Work Orders waiting for parts */
$q = "SELECT count(*) as count  FROM ".PRFX."ORDERS WHERE WO_ID=".$db->qstr($wo_id);
if(!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_mysql_error').': '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
}
$smarty->assign('part', $rs->fields['count']);







/* Grab specified Work Order information */
if(!$single_work_order = display_single_open_workorder($db, $wo_id)){
    force_page('core', 'error&menu=1&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_the_work_order_you_requested_was_not_found').'&type=error');
    exit;
}
$smarty->assign('single_workorder_array', $single_work_order);
$assigned_employee_id = $single_work_order['0']['WORK_ORDER_ASSIGN_TO'];





/* Build a <form></form> to select employee for 'Assign To' feature */

// select all employees and return their display name and ID as an array
$q = "SELECT EMPLOYEE_DISPLAY_NAME, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";

// i think $rs is adobd
if(!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_mysql_error').': '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
}
//echo $rs;die;
// add unassigned record - this is a bit of a hack
//print_r($rs); die;


// all working except I need to be able to add unnasigned, 0 into the list. so i can deslect

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













// Assign Varibles to smarty

$smarty->assign('work_order_notes', display_workorder_notes($db, $wo_id));
$smarty->assign('order', display_parts($db, $wo_id));             
$smarty->assign('work_order_status', display_workorder_status($db, $wo_id));
$smarty->assign('work_order_sched', get_work_order_schedule ($db, $wo_id));    
$smarty->assign('resolution', display_resolution($db, $wo_id));

$smarty->display('workorder'.SEP.'details.tpl');
