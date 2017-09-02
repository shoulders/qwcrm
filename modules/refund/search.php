<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/refund.php');
require(INCLUDES_DIR.'modules/payment.php');

// Build the page
$smarty->assign('refund_types',     get_refund_types($db)                                                                               );
$smarty->assign('payment_methods',  get_payment_manual_methods($db)                                                                     );
$smarty->assign('search_category',  $VAR['search_category']                                                                             );
$smarty->assign('search_term',      $VAR['search_term']                                                                                 );
$smarty->assign('search_result',    display_refunds($db, 'DESC', true, $page_no, '25', $VAR['search_term'], $VAR['search_category'])    );
$BuildPage .= $smarty->fetch('refund/search.tpl');