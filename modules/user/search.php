<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/user.php');

 // a workaround until i add a full type search, this keeps the logic intact
$VAR['search_category'] = 'display_name';

// Build the page with the results for the current search (if there is no search term, all results are returned)
$smarty->assign('search_category',  $VAR['search_category']                                                                                                                     );
$smarty->assign('search_term',      $VAR['search_term']                                                                                                                         );
$smarty->assign('search_active',    $VAR['search_active']                                                                                                                       );
$smarty->assign('search_type',      $VAR['search_type']                                                                                                                         );
$smarty->assign('usergroups',       get_usergroups($db)                                                                                                                         );
$smarty->assign('search_result',    display_users($db, 'DESC', true, $page_no, '25', $VAR['search_term'], $VAR['search_category'], $VAR['search_active'], $VAR['search_type'])  );

$BuildPage .= $smarty->fetch('user/search.tpl');