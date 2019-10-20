<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'user.php');

// Prevent undefined variable errors
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;
$VAR['search_category'] = isset($VAR['search_category']) ? $VAR['search_category'] : null;
$VAR['search_term'] = isset($VAR['search_term']) ? $VAR['search_term'] : null;
$VAR['filter_usergroup'] = isset($VAR['filter_usergroup']) ? $VAR['filter_usergroup'] : null;
$VAR['filter_usertype'] = isset($VAR['filter_usertype']) ? $VAR['filter_usertype'] : null;
$VAR['filter_status'] = isset($VAR['filter_status']) ? $VAR['filter_status'] : null;

// A workaround until i add a full type search, this keeps the logic intact
$VAR['search_category'] = 'display_name';

// If a search is submitted
if(isset($VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of users has been performed with the search term").' `'.$VAR['search_term'].'` '.'in the category'.' `'.$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
    // Redirect search so the variables are in the URL
    unset($VAR['submit']);
    force_page('user', 'search', $VAR, 'get');
    
}

// Build the page with the results for the current search (if there is no search term, all results are returned)
$smarty->assign('search_category',   $VAR['search_category']                                                                                                                                             );
$smarty->assign('search_term',       $VAR['search_term']                                                                                                                                                 );
$smarty->assign('filter_status',     $VAR['filter_status']                                                                                                                                               );
$smarty->assign('filter_usertype',   $VAR['filter_usertype']                                                                                                                                             );
$smarty->assign('filter_usergroup',  $VAR['filter_usergroup']                                                                                                                                             );
$smarty->assign('usergroups',        get_usergroups()                                                                                                                                                 );
$smarty->assign('user_locations',    get_user_locations());
$smarty->assign('display_users',     display_users('user_id', 'DESC', true, '25', $VAR['page_no'], $VAR['search_category'], $VAR['search_term'], $VAR['filter_usergroup'], $VAR['filter_usertype'], $VAR['filter_status'])    );

\QFactory::$BuildPage .= $smarty->fetch('user/search.tpl');