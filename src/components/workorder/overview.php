<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent undefined variable errors
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;

// Build the page
$smarty->assign('overview_workorders_unassigned',        display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'unassigned')        );
$smarty->assign('overview_workorders_assigned',          display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'assigned')          );
$smarty->assign('overview_workorders_waiting_for_parts', display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'waiting_for_parts') );
$smarty->assign('overview_workorders_scheduled',         display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'scheduled')         );
$smarty->assign('overview_workorders_with_client',       display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'with_client')       );
$smarty->assign('overview_workorders_on_hold',           display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'on_hold')           );
$smarty->assign('overview_workorders_management',        display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'management')        );

$smarty->assign('overview_workorder_stats', get_workorder_stats('current'));
$smarty->assign('workorder_statuses', get_workorder_statuses());

$BuildPage .= $smarty->fetch('workorder/overview.tpl');