<?php

require(INCLUDES_DIR.'modules/customer.php');

$smarty->assign('customer_search_result', search_customers($db, $VAR['search_term'], $page_no));

$BuildPage .= $smarty->fetch('customer/search.tpl');