<?php

require(INCLUDES_DIR.'modules/employee.php');    

// Fetch the page with the results for the current search (if there is no search terma ll results are returned)
$smarty->assign('employee_search_result', display_employee_search($db, $VAR['employee_searchTerm'], $page_no) );

$BuildPage .= $smarty->fetch('employee/search.tpl');