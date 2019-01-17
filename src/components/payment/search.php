<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'payment.php');

// Prevent undefined variable errors
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;
$VAR['search_category'] = isset($VAR['search_category']) ? $VAR['search_category'] : null;
$VAR['search_term']   = isset($VAR['search_term']) ? $VAR['search_term'] : null;
$VAR['filter_type']   = isset($VAR['filter_type']) ? $VAR['filter_type'] : null;
$VAR['filter_method'] = isset($VAR['filter_method']) ? $VAR['filter_method'] : null;
$VAR['filter_status'] = isset($VAR['filter_status']) ? $VAR['filter_status'] : null;

// If a search is submitted
if(isset($VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of payments has been performed with the search term").' `'.$VAR['search_term'].'` '.'in the category'.' `'.$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
    // Redirect search so the variables are in the URL
    unset($VAR['submit']);
    force_page('payment', 'search', $VAR, 'get');
    
}

// Build the page
$smarty->assign('search_category',  $VAR['search_category']                                                                             );
$smarty->assign('search_term',      $VAR['search_term']                                                                                 );
$smarty->assign('filter_type',      $VAR['filter_type']                                                                                 );
$smarty->assign('filter_method',    $VAR['filter_method']                                                                               );
$smarty->assign('filter_status',    $VAR['filter_status']                                                                               );
$smarty->assign('payment_types',    get_payment_types()                                                                                 );
$smarty->assign('payment_methods',  get_payment_methods()                                                                               );
$smarty->assign('payment_statuses', get_payment_statuses()                                                                              );
$smarty->assign('display_payments', display_payments('payment_id', 'DESC', true, '25', $VAR['page_no'], $VAR['search_category'], $VAR['search_term'], $VAR['filter_type'], $VAR['filter_method'], $VAR['filter_status'])   );

$BuildPage .= $smarty->fetch('payment/search.tpl');