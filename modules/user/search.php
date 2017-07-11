<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/user.php');    

// Fetch the page with the results for the current search (if there is no search term, all results are returned)
$smarty->assign('search_category', $VAR['search_category']);
$smarty->assign('search_term', $VAR['search_term']);
$smarty->assign('search_result', display_users($db, 'all', 'DESC', true, $page_no, 25, 'display_name', $VAR['search_term']));
$BuildPage .= $smarty->fetch('user/search.tpl');