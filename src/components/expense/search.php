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
\CMSApplication::$VAR['filter_type'] = isset(\CMSApplication::$VAR['filter_type']) ? \CMSApplication::$VAR['filter_type'] : null;
\CMSApplication::$VAR['filter_status'] = isset(\CMSApplication::$VAR['filter_status']) ? \CMSApplication::$VAR['filter_status'] : null;

// If a search is submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of expenses has been performed with the search term").' `'.\CMSApplication::$VAR['search_term'].'` '.'in the category'.' `'.\CMSApplication::$VAR['search_category'].'`.';
    $this->app->system->general->write_record_to_activity_log($record);
    
    // Redirect search so the variables are in the URL
    unset(\CMSApplication::$VAR['submit']);
    $this->app->system->page->force_page('expense', 'search', \CMSApplication::$VAR, 'get');
    
}

// Build the page
$this->app->smarty->assign('search_category',          \CMSApplication::$VAR['search_category']                                                                                         );
$this->app->smarty->assign('search_term',              \CMSApplication::$VAR['search_term']                                                                                             );
$this->app->smarty->assign('filter_type',              \CMSApplication::$VAR['filter_type']                                                                                             );
$this->app->smarty->assign('filter_status',            \CMSApplication::$VAR['filter_status']                                                                                           );
$this->app->smarty->assign('expense_statuses',         $this->app->components->expense->get_expense_statuses()                                                                                          );
$this->app->smarty->assign('expense_types',            $this->app->components->expense->get_expense_types()                                                                                             );
$this->app->smarty->assign('display_expenses',         $this->app->components->expense->display_expenses('expense_id', 'DESC', true, '25', \CMSApplication::$VAR['page_no'], \CMSApplication::$VAR['search_category'], \CMSApplication::$VAR['search_term'], \CMSApplication::$VAR['filter_type'], \CMSApplication::$VAR['filter_status']));