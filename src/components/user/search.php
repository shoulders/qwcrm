<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['page_no'] = isset(\CMSApplication::$VAR['page_no']) ? \CMSApplication::$VAR['page_no'] : null;
\CMSApplication::$VAR['search_category'] = isset(\CMSApplication::$VAR['search_category']) ? \CMSApplication::$VAR['search_category'] : null;
\CMSApplication::$VAR['search_term'] = isset(\CMSApplication::$VAR['search_term']) ? \CMSApplication::$VAR['search_term'] : null;
\CMSApplication::$VAR['filter_usergroup'] = isset(\CMSApplication::$VAR['filter_usergroup']) ? \CMSApplication::$VAR['filter_usergroup'] : null;
\CMSApplication::$VAR['filter_usertype'] = isset(\CMSApplication::$VAR['filter_usertype']) ? \CMSApplication::$VAR['filter_usertype'] : null;
\CMSApplication::$VAR['filter_status'] = isset(\CMSApplication::$VAR['filter_status']) ? \CMSApplication::$VAR['filter_status'] : null;

// A workaround until i add a full type search, this keeps the logic intact
\CMSApplication::$VAR['search_category'] = 'display_name';

// If a search is submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of users has been performed with the search term").' `'.\CMSApplication::$VAR['search_term'].'` '.'in the category'.' `'.\CMSApplication::$VAR['search_category'].'`.';
    $this->app->system->general->write_record_to_activity_log($record);
    
    // Redirect search so the variables are in the URL
    unset(\CMSApplication::$VAR['submit']);
    $this->app->system->page->force_page('user', 'search', \CMSApplication::$VAR, 'get');
    
}

// Build the page with the results for the current search (if there is no search term, all results are returned)
$this->app->smarty->assign('search_category',   \CMSApplication::$VAR['search_category']                                                                                                                                             );
$this->app->smarty->assign('search_term',       \CMSApplication::$VAR['search_term']                                                                                                                                                 );
$this->app->smarty->assign('filter_status',     \CMSApplication::$VAR['filter_status']                                                                                                                                               );
$this->app->smarty->assign('filter_usertype',   \CMSApplication::$VAR['filter_usertype']                                                                                                                                             );
$this->app->smarty->assign('filter_usergroup',  \CMSApplication::$VAR['filter_usergroup']                                                                                                                                             );
$this->app->smarty->assign('usergroups',        $this->app->components->user->get_usergroups()                                                                                                                                                 );
$this->app->smarty->assign('user_locations',    $this->app->components->user->get_user_locations());
$this->app->smarty->assign('display_users',     $this->app->components->user->display_users('user_id', 'DESC', true, '25', \CMSApplication::$VAR['page_no'], \CMSApplication::$VAR['search_category'], \CMSApplication::$VAR['search_term'], \CMSApplication::$VAR['filter_usergroup'], \CMSApplication::$VAR['filter_usertype'], \CMSApplication::$VAR['filter_status'])    );