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
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;
$VAR['search_category'] = isset($VAR['search_category']) ? $VAR['search_category'] : null;
$VAR['search_term'] = isset($VAR['search_term']) ? $VAR['search_term'] : null;
$VAR['filter_status'] = isset($VAR['filter_status']) ? $VAR['filter_status'] : null;
$VAR['filter_redeemed'] = isset($VAR['filter_redeemed']) ? $VAR['filter_redeemed'] : null;

// If a search is submitted
if(isset($VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of Vouchers has been performed with the search term").' `'.$VAR['search_term'].'` '.'in the category'.' `'.$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
    // Redirect search so the variables are in the URL
    unset($VAR['submit']);
    force_page('voucher', 'search', $VAR, 'get');
    
}

// Build the page
$smarty->assign('search_category',      $VAR['search_category']                                                                                                                                 );
$smarty->assign('search_term',          $VAR['search_term']                                                                                                                                     );
$smarty->assign('filter_status',        $VAR['filter_status']                                                                                                                                          );
$smarty->assign('filter_redeemed',      $VAR['filter_redeemed']                                                                                                                                     );
$smarty->assign('voucher_statuses',    get_voucher_statuses()                                                                                                                                  );
$smarty->assign('display_vouchers',    display_vouchers('voucher_id', 'DESC', true, '25', $VAR['page_no'], $VAR['search_category'], $VAR['search_term'], $VAR['filter_status']));
$BuildPage .= $smarty->fetch('voucher/search.tpl');