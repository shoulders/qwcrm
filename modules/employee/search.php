<?php

require(INCLUDES_DIR.'modules/employee.php');    

$smarty->assign('alpha', array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'));
$smarty->assign('employee_search_result', display_employee_search($db, $VAR['name'], $page_no) );

$BuildPage .= $smarty->fetch('employee/search.tpl');