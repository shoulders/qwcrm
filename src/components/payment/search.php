<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'payment.php');

// Prevent undefined variable errors
\CMSApplication::$VAR['page_no'] = isset(\CMSApplication::$VAR['page_no']) ? \CMSApplication::$VAR['page_no'] : null;
\CMSApplication::$VAR['search_category'] = isset(\CMSApplication::$VAR['search_category']) ? \CMSApplication::$VAR['search_category'] : null;
\CMSApplication::$VAR['search_term']   = isset(\CMSApplication::$VAR['search_term']) ? \CMSApplication::$VAR['search_term'] : null;
\CMSApplication::$VAR['filter_type']   = isset(\CMSApplication::$VAR['filter_type']) ? \CMSApplication::$VAR['filter_type'] : null;
\CMSApplication::$VAR['filter_method'] = isset(\CMSApplication::$VAR['filter_method']) ? \CMSApplication::$VAR['filter_method'] : null;
\CMSApplication::$VAR['filter_status'] = isset(\CMSApplication::$VAR['filter_status']) ? \CMSApplication::$VAR['filter_status'] : null;

// If a search is submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of payments has been performed with the search term").' `'.\CMSApplication::$VAR['search_term'].'` '.'in the category'.' `'.\CMSApplication::$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
    // Redirect search so the variables are in the URL
    unset(\CMSApplication::$VAR['submit']);
    force_page('payment', 'search', \CMSApplication::$VAR, 'get');
    
}

// Build the page
$smarty->assign('search_category',  \CMSApplication::$VAR['search_category']                                                                             );
$smarty->assign('search_term',      \CMSApplication::$VAR['search_term']                                                                                 );
$smarty->assign('filter_type',      \CMSApplication::$VAR['filter_type']                                                                                 );
$smarty->assign('filter_method',    \CMSApplication::$VAR['filter_method']                                                                               );
$smarty->assign('filter_status',    \CMSApplication::$VAR['filter_status']                                                                               );
$smarty->assign('payment_types',    get_payment_types()                                                                                 );
$smarty->assign('payment_methods',  get_payment_methods()                                                                               );
$smarty->assign('payment_statuses', get_payment_statuses()                                                                              );
$smarty->assign('display_payments', display_payments('payment_id', 'DESC', true, '25', \CMSApplication::$VAR['page_no'], \CMSApplication::$VAR['search_category'], \CMSApplication::$VAR['search_term'], \CMSApplication::$VAR['filter_type'], \CMSApplication::$VAR['filter_method'], \CMSApplication::$VAR['filter_status'])   );