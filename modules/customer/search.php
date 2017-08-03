<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

 // a workaround until i add a full type search, this keeps the logic intact
$VAR['search_category'] = 'display_name';

// Build the page
$smarty->assign('search_category',  $VAR['search_category']                                                                                             );
$smarty->assign('search_term',      $VAR['search_term']                                                                                                 );
$smarty->assign('status',           $VAR['status']                                                                                                      );
$smarty->assign('search_result',    display_customers($db, 'DESC', true, $page_no, '25', $VAR['search_term'], $VAR['search_category'], $VAR['status'])  );

$BuildPage .= $smarty->fetch('customer/search.tpl');