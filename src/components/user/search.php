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
\QFactory::$VAR['page_no'] = isset(\QFactory::$VAR['page_no']) ? \QFactory::$VAR['page_no'] : null;
\QFactory::$VAR['search_category'] = isset(\QFactory::$VAR['search_category']) ? \QFactory::$VAR['search_category'] : null;
\QFactory::$VAR['search_term'] = isset(\QFactory::$VAR['search_term']) ? \QFactory::$VAR['search_term'] : null;
\QFactory::$VAR['filter_usergroup'] = isset(\QFactory::$VAR['filter_usergroup']) ? \QFactory::$VAR['filter_usergroup'] : null;
\QFactory::$VAR['filter_usertype'] = isset(\QFactory::$VAR['filter_usertype']) ? \QFactory::$VAR['filter_usertype'] : null;
\QFactory::$VAR['filter_status'] = isset(\QFactory::$VAR['filter_status']) ? \QFactory::$VAR['filter_status'] : null;

// A workaround until i add a full type search, this keeps the logic intact
\QFactory::$VAR['search_category'] = 'display_name';

// If a search is submitted
if(isset(\QFactory::$VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of users has been performed with the search term").' `'.\QFactory::$VAR['search_term'].'` '.'in the category'.' `'.\QFactory::$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
    // Redirect search so the variables are in the URL
    unset(\QFactory::$VAR['submit']);
    force_page('user', 'search', \QFactory::$VAR, 'get');
    
}

// Build the page with the results for the current search (if there is no search term, all results are returned)
$smarty->assign('search_category',   \QFactory::$VAR['search_category']                                                                                                                                             );
$smarty->assign('search_term',       \QFactory::$VAR['search_term']                                                                                                                                                 );
$smarty->assign('filter_status',     \QFactory::$VAR['filter_status']                                                                                                                                               );
$smarty->assign('filter_usertype',   \QFactory::$VAR['filter_usertype']                                                                                                                                             );
$smarty->assign('filter_usergroup',  \QFactory::$VAR['filter_usergroup']                                                                                                                                             );
$smarty->assign('usergroups',        get_usergroups()                                                                                                                                                 );
$smarty->assign('user_locations',    get_user_locations());
$smarty->assign('display_users',     display_users('user_id', 'DESC', true, '25', \QFactory::$VAR['page_no'], \QFactory::$VAR['search_category'], \QFactory::$VAR['search_term'], \QFactory::$VAR['filter_usergroup'], \QFactory::$VAR['filter_usertype'], \QFactory::$VAR['filter_status'])    );

\QFactory::$BuildPage .= $smarty->fetch('user/search.tpl');