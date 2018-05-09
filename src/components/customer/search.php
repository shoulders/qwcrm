<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');

// A workaround until i add a full type search, this keeps the logic intact
$VAR['search_category'] = 'display_name';

// If a search is submitted
if(isset($VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of customers has been performed with the search term").' `'.$VAR['search_term'].'` '.'in the category'.' `'.$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
}

// Build the page
$smarty->assign('customer_types',       get_customer_types($db)                                                                                                             );
$smarty->assign('search_category',      $VAR['search_category']                                                                                                             );
$smarty->assign('search_term',          $VAR['search_term']                                                                                                                 );
$smarty->assign('status',               $VAR['status']                                                                                                                      );
$smarty->assign('display_customers',    display_customers($db, 'customer_id', 'DESC', true, $page_no, '25', $VAR['search_term'], $VAR['search_category'], $VAR['status'])   );
$BuildPage .= $smarty->fetch('customer/search.tpl');