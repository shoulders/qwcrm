<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/schedule.php');

/* If a search is submitted
if(isset($VAR['submit'])) {
    
    // Log activity
    $record = _gettext("A search of schedules has been performed with the search term").' `'.$VAR['search_term'].'` '.'in the category'.' `'.$VAR['search_category'].'`.';
    write_record_to_activity_log($record);
    
}

// Build the page
$smarty->assign('search_category',      $VAR['search_category']                                                                             );
$smarty->assign('search_term',          $VAR['search_term']                                                                                 );
$smarty->assign('display_schedules',    display_schedules($db, 'DESC', true, $VAR['page_no'], '25', $VAR['search_term'], $VAR['search_category'])  );*/

$BuildPage .= $smarty->fetch('schedule/search.tpl');