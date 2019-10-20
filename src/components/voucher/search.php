<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'voucher.php');

// Prevent undefined variable errors
\QFactory::$VAR['page_no'] = isset(\QFactory::$VAR['page_no']) ? \QFactory::$VAR['page_no'] : null;
\QFactory::$VAR['search_category'] = isset(\QFactory::$VAR['search_category']) ? \QFactory::$VAR['search_category'] : null;
\QFactory::$VAR['search_term'] = isset(\QFactory::$VAR['search_term']) ? \QFactory::$VAR['search_term'] : null;
\QFactory::$VAR['filter_status'] = isset(\QFactory::$VAR['filter_status']) ? \QFactory::$VAR['filter_status'] : null;
\QFactory::$VAR['filter_redeemed'] = isset(\QFactory::$VAR['filter_redeemed']) ? \QFactory::$VAR['filter_redeemed'] : null;

// If a search is submitted
if(isset(\QFactory::$VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of Vouchers has been performed with the search term").' `'.\QFactory::$VAR['search_term'].'` '.'in the category'.' `'.\QFactory::$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
    // Redirect search so the variables are in the URL
    unset(\QFactory::$VAR['submit']);
    force_page('voucher', 'search', \QFactory::$VAR, 'get');
    
}

// Build the page
$smarty->assign('search_category',      \QFactory::$VAR['search_category']                                                                                                                                 );
$smarty->assign('search_term',          \QFactory::$VAR['search_term']                                                                                                                                     );
$smarty->assign('filter_status',        \QFactory::$VAR['filter_status']                                                                                                                                          );
$smarty->assign('filter_redeemed',      \QFactory::$VAR['filter_redeemed']                                                                                                                                     );
$smarty->assign('voucher_statuses',    get_voucher_statuses()                                                                                                                                  );
$smarty->assign('display_vouchers',    display_vouchers('voucher_id', 'DESC', true, '25', \QFactory::$VAR['page_no'], \QFactory::$VAR['search_category'], \QFactory::$VAR['search_term'], \QFactory::$VAR['filter_status']));
\QFactory::$BuildPage .= $smarty->fetch('voucher/search.tpl');