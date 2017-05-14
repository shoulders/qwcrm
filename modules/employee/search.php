<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/employee.php');    

// Fetch the page with the results for the current search (if there is no search term, all results are returned)
$smarty->assign('search_category', $VAR['search_category']);
$smarty->assign('search_term', $VAR['search_term']);
$smarty->assign('search_result', display_employees($db, $VAR['search_term'], $page_no) );
$BuildPage .= $smarty->fetch('employee/search.tpl');