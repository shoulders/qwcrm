<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['page_no'] = \CMSApplication::$VAR['page_no'] ?? null;
\CMSApplication::$VAR['search_category'] = \CMSApplication::$VAR['search_category'] ?? null;
\CMSApplication::$VAR['search_term'] = \CMSApplication::$VAR['search_term'] ?? null;
\CMSApplication::$VAR['filter_status'] = \CMSApplication::$VAR['filter_status'] ?? null;
\CMSApplication::$VAR['filter_redeemed'] = \CMSApplication::$VAR['filter_redeemed'] ?? null;

// If a search is submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of Vouchers has been performed with the search term").' `'.\CMSApplication::$VAR['search_term'].'` '.'in the category'.' `'.\CMSApplication::$VAR['search_category'].'`.';
    $this->app->system->general->writeRecordToActivityLog($record);
    
    // Redirect search so the variables are in the URL
    unset(\CMSApplication::$VAR['submit']);
    $this->app->system->page->forcePage('voucher', 'search', \CMSApplication::$VAR, 'get');
    
}

// Build the page
$this->app->smarty->assign('search_category',      \CMSApplication::$VAR['search_category']                                                                                                                                 );
$this->app->smarty->assign('search_term',          \CMSApplication::$VAR['search_term']                                                                                                                                     );
$this->app->smarty->assign('filter_status',        \CMSApplication::$VAR['filter_status']                                                                                                                                          );
$this->app->smarty->assign('filter_redeemed',      \CMSApplication::$VAR['filter_redeemed']                                                                                                                                     );
$this->app->smarty->assign('voucher_statuses',    $this->app->components->voucher->getStatuses()                                                                                                                                  );
$this->app->smarty->assign('display_vouchers',    $this->app->components->voucher->getRecords('voucher_id', 'DESC', 25, true, \CMSApplication::$VAR['page_no'], \CMSApplication::$VAR['search_category'], \CMSApplication::$VAR['search_term'], \CMSApplication::$VAR['filter_status']));