<?php

require(INCLUDES_DIR.'modules/employee.php');

// Is there an employee ID supplied - prevents incorrect access
if(empty($employee_id)) {
    force_page('core', 'error&error_msg=No Employee ID');
    exit;
}

// If the submit button has been pressed on the page, Process the submission
if(isset($VAR['submit'])) {
    update_employee($db, $VAR);
    force_page('employee', 'details&employee_id='.$employee_id);    
    exit;    
}

// Fetch the page with the employee information from the database
$smarty->assign('employee_type', get_employee_types($db));
$smarty->assign('employee_details', display_single_employee($db, $employee_id));

$BuildPage .= $smarty->fetch('employee/edit.tpl');