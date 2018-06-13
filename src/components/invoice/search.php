<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/invoice.php');

// If a search is submitted
if(isset($VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of invoices has been performed with the search term").' `'.$VAR['search_term'].'` '.'in the category'.' `'.$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
}

// Build the page
$smarty->assign('search_category',  $VAR['search_category']                                                                             );
$smarty->assign('search_term',      $VAR['search_term']                                                                                 );
$smarty->assign('filter_status',    $VAR['filter_status']                                                                               );
$smarty->assign('invoice_statuses', get_invoice_statuses()                                                                           );
$smarty->assign('display_invoices', display_invoices('invoice_id', 'DESC', true, $VAR['page_no'], '25', $VAR['search_term'], $VAR['search_category'], $VAR['filter_status'])   );

$BuildPage .= $smarty->fetch('invoice/search.tpl');