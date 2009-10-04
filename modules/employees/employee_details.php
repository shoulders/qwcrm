<?php
require_once("include.php");
if(!xml2php("employees")) {
	$smarty->assign('error_msg',"Error in language file");
}

// Get the customers id from the url
$employee_id = $VAR['employee_id'];



// assign the arrays
$smarty->assign('open_work_orders', display_open_workorders($db, $employee_id));
$smarty->assign('employee_details', display_employee_info($db, $employee_id));

$smarty->display('employees'.SEP.'employee_details.tpl');
?>