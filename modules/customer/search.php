<?php

require(INCLUDES_DIR.'modules/customer.php');

$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

$smarty->assign('alpha', $alpha);
$smarty->assign('customer_search_result', display_customer_search($db, $name = $VAR['name'], $page_no));

$BuildPage .= $smarty->fetch('customer'.SEP.'search.tpl');