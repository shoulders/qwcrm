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
\CMSApplication::$VAR['search_term']   = \CMSApplication::$VAR['search_term'] ?? null;
\CMSApplication::$VAR['filter_type']   = \CMSApplication::$VAR['filter_type'] ?? null;
\CMSApplication::$VAR['filter_method'] = \CMSApplication::$VAR['filter_method'] ?? null;
\CMSApplication::$VAR['filter_direction'] = \CMSApplication::$VAR['filter_direction'] ?? null;
\CMSApplication::$VAR['filter_status'] = \CMSApplication::$VAR['filter_status'] ?? null;

// If a search is submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of payments has been performed with the search term").' `'.\CMSApplication::$VAR['search_term'].'` '.'in the category'.' `'.\CMSApplication::$VAR['search_category'].'`.';
    $this->app->system->general->writeRecordToActivityLog($record);
    
    // Redirect search so the variables are in the URL
    unset(\CMSApplication::$VAR['submit']);
    $this->app->system->page->forcePage('payment', 'search', \CMSApplication::$VAR, 'get');
    
}

// Build the page
$this->app->smarty->assign('search_category',  \CMSApplication::$VAR['search_category']                                                                             );
$this->app->smarty->assign('search_term',      \CMSApplication::$VAR['search_term']                                                                                 );
$this->app->smarty->assign('filter_type',      \CMSApplication::$VAR['filter_type']                                                                                 );
$this->app->smarty->assign('filter_method',    \CMSApplication::$VAR['filter_method']                                                                               );
$this->app->smarty->assign('filter_direction',    \CMSApplication::$VAR['filter_direction']                                                                               );
$this->app->smarty->assign('filter_status',    \CMSApplication::$VAR['filter_status']                                                                               );
$this->app->smarty->assign('payment_types',    $this->app->components->payment->getTypes()                                                                                 );
$this->app->smarty->assign('payment_methods',  $this->app->components->payment->getMethods()                                                                               );
$this->app->smarty->assign('payment_directions',  $this->app->components->payment->getDirections());
$this->app->smarty->assign('payment_statuses', $this->app->components->payment->getStatuses()                                                                              );
$this->app->smarty->assign('payment_creditnote_action_types', $this->app->components->payment->getCreditnoteActionTypes());
$this->app->smarty->assign('display_payments', $this->app->components->payment->getRecords('payment_id', 'DESC', 25, true, \CMSApplication::$VAR['page_no'], \CMSApplication::$VAR['search_category'], \CMSApplication::$VAR['search_term'], \CMSApplication::$VAR['filter_type'], \CMSApplication::$VAR['filter_method'], null, \CMSApplication::$VAR['filter_status']));