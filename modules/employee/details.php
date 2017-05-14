<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/employee.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Fetch the page with the employee details from the database 
$smarty->assign('open_workorders', display_workorders($db, '2', 'DESC', false, null, null, $employee_id ));
$smarty->assign('employee_details', get_employee_details($db, $employee_id));
$BuildPage .= $smarty->fetch('employee/details.tpl');