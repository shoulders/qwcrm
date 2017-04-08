<?php

require(INCLUDES_DIR.'modules/employee.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Fetch the page with the employee details from the database 
$smarty->assign('open_work_orders', display_employee_open_workorders($db, $employee_id));
$smarty->assign('employee_details', display_single_employee($db, $employee_id));
$BuildPage .= $smarty->fetch('employee/details.tpl');