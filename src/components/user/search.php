<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/user.php');

// A workaround until i add a full type search, this keeps the logic intact
$VAR['search_category'] = 'display_name';

// If a search is submitted
if(isset($VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of users has been performed with the search term").' `'.$VAR['search_term'].'` '.'in the category'.' `'.$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
}

// Build the page with the results for the current search (if there is no search term, all results are returned)
$smarty->assign('search_category',   $VAR['search_category']                                                                                                                                             );
$smarty->assign('search_term',       $VAR['search_term']                                                                                                                                                 );
$smarty->assign('filter_status',     $VAR['filter_status']                                                                                                                                               );
$smarty->assign('filter_usertype',   $VAR['filter_usertype']                                                                                                                                             );
$smarty->assign('filter_usergroup',  $VAR['filter_usergroup']                                                                                                                                             );
$smarty->assign('usergroups',       get_usergroups($db)                                                                                                                                                 );
$smarty->assign('display_users',    display_users($db, 'user_id', 'DESC', true, $VAR['page_no'], '25', $VAR['search_term'], $VAR['search_category'], $VAR['filter_status'], $VAR['filter_usertype'], $VAR['filter_usergroup'])    );

$BuildPage .= $smarty->fetch('user/search.tpl');