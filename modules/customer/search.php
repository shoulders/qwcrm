<?php

require(INCLUDES_DIR.'modules/customer.php');

$smarty->assign('customer_search_result', display_customers($db, 'all', 'DESC', true, $page_no, 25, 'display_name', $VAR['search_term']));
$smarty->assign('search_term', $VAR['search_term']);

$BuildPage .= $smarty->fetch('customer/search.tpl');