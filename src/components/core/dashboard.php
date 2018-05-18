<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/core.php');
require(INCLUDES_DIR.'components/report.php');
require(INCLUDES_DIR.'components/workorder.php');

// Display Welcome Note
$smarty->assign('welcome_note', display_welcome_msg($db));

// Employee Workorder Stats
$employee_workorder_stats = array(
    "open_count"                =>  count_workorders($db, 'open', $user->login_user_id),
    "assigned_count"            =>  count_workorders($db, 'assigned', $user->login_user_id),
    "waiting_for_parts_count"   =>  count_workorders($db, 'waiting_for_parts', $user->login_user_id),
    "scheduled_count"           =>  count_workorders($db, 'scheduled', $user->login_user_id),
    "with_client_count"         =>  count_workorders($db, 'with_client', $user->login_user_id),
    "on_hold_count"             =>  count_workorders($db, 'on_hold', $user->login_user_id),
    "management_count"          =>  count_workorders($db, 'management', $user->login_user_id)
);
$smarty->assign('employee_workorder_stats', $employee_workorder_stats);

// Employee Workorders
$smarty->assign('employee_workorders_assigned',          display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'assigned', $user->login_user_id)          );
$smarty->assign('employee_workorders_waiting_for_parts', display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'waiting_for_parts', $user->login_user_id) );
$smarty->assign('employee_workorders_scheduled',         display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'scheduled', $user->login_user_id)         );
$smarty->assign('employee_workorders_with_client',       display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'with_client', $user->login_user_id)       );
$smarty->assign('employee_workorders_on_hold',           display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'on_hold', $user->login_user_id)           );
$smarty->assign('employee_workorders_management',        display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'management', $user->login_user_id)        );

// Build the page
$smarty->assign('workorder_statuses', get_workorder_statuses($db));
$BuildPage .= $smarty->fetch('core/dashboard.tpl');