<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/report.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Workorder Stats
$overview_workorder_stats = array(
    "open_count"                =>  count_workorders($db, 'open'),
    "assigned_count"            =>  count_workorders($db, 'assigned'),
    "waiting_for_parts_count"   =>  count_workorders($db, 'waiting_for_parts'),
    "scheduled_count"           =>  count_workorders($db, 'scheduled'),
    "with_client_count"         =>  count_workorders($db, 'with_client'),
    "on_hold_count"             =>  count_workorders($db, 'on_hold'),
    "management_count"          =>  count_workorders($db, 'management')
);
$smarty->assign('overview_workorder_stats', $overview_workorder_stats);

// Workorders
$smarty->assign('overview_workorders_unassigned',        display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'unassigned')        );
$smarty->assign('overview_workorders_assigned',          display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'assigned')          );
$smarty->assign('overview_workorders_waiting_for_parts', display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'waiting_for_parts') );
$smarty->assign('overview_workorders_scheduled',         display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'scheduled')         );
$smarty->assign('overview_workorders_with_client',       display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'with_client')       );
$smarty->assign('overview_workorders_on_hold',           display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'on_hold')           );
$smarty->assign('overview_workorders_management',        display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'management')        );

// Build the page
$smarty->assign('workorder_statuses', get_workorder_statuses($db));
$BuildPage .= $smarty->fetch('workorder/overview.tpl');