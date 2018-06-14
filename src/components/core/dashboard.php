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

// Employee Workorders
$smarty->assign('employee_workorders_assigned',          display_workorders('workorder_id', 'DESC', false, null, null, null, null, 'assigned', $user->login_user_id)          );
$smarty->assign('employee_workorders_waiting_for_parts', display_workorders('workorder_id', 'DESC', false, null, null, null, null, 'waiting_for_parts', $user->login_user_id) );
$smarty->assign('employee_workorders_scheduled',         display_workorders('workorder_id', 'DESC', false, null, null, null, null, 'scheduled', $user->login_user_id)         );
$smarty->assign('employee_workorders_with_client',       display_workorders('workorder_id', 'DESC', false, null, null, null, null, 'with_client', $user->login_user_id)       );
$smarty->assign('employee_workorders_on_hold',           display_workorders('workorder_id', 'DESC', false, null, null, null, null, 'on_hold', $user->login_user_id)           );
$smarty->assign('employee_workorders_management',        display_workorders('workorder_id', 'DESC', false, null, null, null, null, 'management', $user->login_user_id)        );

// Misc
$smarty->assign('welcome_msg', display_welcome_msg());
$smarty->assign('employee_workorder_stats', get_workorder_stats('current', $user->login_user_id));
$smarty->assign('workorder_statuses', get_workorder_statuses());

// Build the page
$BuildPage .= $smarty->fetch('core/dashboard.tpl');