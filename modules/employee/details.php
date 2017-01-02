<?php
require_once("include.php");


// Get the customers id from the url
$employee_id = $VAR['employee_id'];



// assign the arrays
$smarty->assign('open_work_orders', display_open_workorders($db, $employee_id));
$smarty->assign('employee_details', display_employee_info($db, $employee_id));

$BuildPage .= $smarty->fetch('employee'.SEP.'details.tpl');