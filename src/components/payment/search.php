<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/payment.php');

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
$smarty->assign('filter_method',    $VAR['filter_method']                                                                               );
$smarty->assign('payment_methods',  get_payment_accepted_methods()                                                                     );
$smarty->assign('display_payments', display_payments('payment_id', 'DESC', true, $VAR['page_no'], '25', $VAR['search_term'], $VAR['search_category'], $VAR['filter_method'])   );

$BuildPage .= $smarty->fetch('payment/search.tpl');