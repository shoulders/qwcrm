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
\CMSApplication::$VAR['filter_type'] = \CMSApplication::$VAR['filter_type'] ?? null;
\CMSApplication::$VAR['filter_status'] = \CMSApplication::$VAR['filter_status'] ?? null;

// If a search is submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of clients has been performed with the search term").' `'.\CMSApplication::$VAR['search_term'].'` '.'in the category'.' `'.\CMSApplication::$VAR['search_category'].'`.';
    $this->app->system->general->writeRecordToActivityLog($record);
    
    // Redirect search so the variables are in the URL
    unset(\CMSApplication::$VAR['submit']);
    $this->app->system->page->forcePage('client', 'search', \CMSApplication::$VAR, 'get');
    
}

// Build the page
$this->app->smarty->assign('search_category',      \CMSApplication::$VAR['search_category']                                                                                                             );
$this->app->smarty->assign('search_term',          \CMSApplication::$VAR['search_term']                                                                                                                 );
$this->app->smarty->assign('filter_status',        \CMSApplication::$VAR['filter_status']                                                                                                               );
$this->app->smarty->assign('filter_type',          \CMSApplication::$VAR['filter_type']                                                                                                                 );
$this->app->smarty->assign('client_types',       $this->app->components->client->getTypes()                                                                                                                    );
$this->app->smarty->assign('display_clients',    $this->app->components->client->getRecords('client_id', 'DESC', true, 25, \CMSApplication::$VAR['page_no'], \CMSApplication::$VAR['search_category'], \CMSApplication::$VAR['search_term'], \CMSApplication::$VAR['filter_type'], \CMSApplication::$VAR['filter_status'])   );

$chicken = 55;