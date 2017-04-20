<?php

require(INCLUDES_DIR.'modules/refund.php');

$smarty->assign('search_category', $VAR['search_category']);
$smarty->assign('search_term', $VAR['search_term']);
$smarty->assign('search_result', display_refunds($db, 'DESC', true, $page_no, 25, $VAR['search_category'], $VAR['search_term']));
$BuildPage .= $smarty->fetch('refund/search.tpl');