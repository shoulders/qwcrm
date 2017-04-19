<?php

require(INCLUDES_DIR.'modules/expense.php');

// This sets page to number. goto_page-->page--> sets as 1 if no value  - do i have a better script, also just change this ti set $page_no instead of goto_page_no
if(isset($VAR['goto_page_no'])) {
    $page_no = $VAR['goto_page_no'];
}

$smarty->assign('search_category', $VAR['search_category']);
$smarty->assign('search_term', $VAR['search_term']);
$smarty->assign('search_result', display_expenses($db, 'DESC', true, $page_no, 2, $VAR['search_category'], $VAR['search_term']));
$BuildPage .= $smarty->fetch('expense/search.tpl');