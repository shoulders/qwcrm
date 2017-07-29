<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/giftcert.php');

// a workaround until i add a full type search, this keeps the logic intact
$VAR['search_category'] = 'giftcert_code';

// Build the page
$smarty->assign('search_category',  $VAR['search_category']                                                                                                                 );
$smarty->assign('search_term',      $VAR['search_term']                                                                                                                     );
$smarty->assign('status',           $VAR['status']                                                                                                                          );
$smarty->assign('is_redeemed',      $VAR['is_redeemed']                                                                                                                     );
$smarty->assign('search_result',    display_giftcerts($db, 'DESC', true, $page_no, '25', $VAR['search_term'], $VAR['search_category'], $VAR['status'], $VAR['is_redeemed']) );
$BuildPage .= $smarty->fetch('giftcert/search.tpl');