<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/workorder.php');

// Prevent undefined variable errors
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;
$VAR['search_category'] = isset($VAR['search_category']) ? $VAR['search_category'] : null;
$VAR['search_term'] = isset($VAR['search_term']) ? $VAR['search_term'] : null;

// Build the page
$smarty->assign('search_category',    $VAR['search_category']);
$smarty->assign('search_term',        $VAR['search_term']);
$smarty->assign('workorder_statuses', get_workorder_statuses());
$smarty->assign('workorders_closed', display_workorders('workorder_id', 'DESC', true, '25', $VAR['page_no'], null, null, 'closed'));
$BuildPage .= $smarty->fetch('workorder/closed.tpl');