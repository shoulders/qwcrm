<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/giftcert.php');

 // a workaround until i add a full type search, this keeps the logic intact
$VAR['search_category'] = 'GIFTCERT_CODE';

// Fetch the page with the results for the current search (if there is no search term, all results are returned)
$smarty->assign('search_category', $VAR['search_category']);
$smarty->assign('search_term', $VAR['search_term']);
$smarty->assign('status', $VAR['status']);
$smarty->assign('is_redeemed', $VAR['is_redeemed']);
$smarty->assign('search_result', display_giftcerts($db, 'DESC', true, $page_no, '25', $VAR['search_term'], $VAR['search_category'], $VAR['status'], $VAR['is_redeemed']));

$BuildPage .= $smarty->fetch('giftcert/search.tpl');