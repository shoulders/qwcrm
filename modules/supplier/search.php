<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

$smarty->assign('search_category',  $VAR['search_category']                                                                             );
$smarty->assign('search_term',      $VAR['search_term']                                                                                 );
$smarty->assign('search_result',    display_suppliers($db, 'DESC', true, $page_no, '25', $VAR['search_term'], $VAR['search_category'])  );
$BuildPage .= $smarty->fetch('supplier/search.tpl');