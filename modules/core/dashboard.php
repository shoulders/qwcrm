<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/core.php');
require(INCLUDES_DIR.'modules/report.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Display Welcome Note
$smarty->assign('welcome_note', display_welcome_msg($db));

// Employee Workorder Stats
$smarty->assign('employee_workorders_open_count',               count_workorders($db, 'open', $login_user_id)               );
$smarty->assign('employee_workorders_assigned_count',           count_workorders($db, 'assigned', $login_user_id)           );
$smarty->assign('employee_workorders_waiting_for_parts_count',  count_workorders($db, 'waiting_for_parts', $login_user_id)  );
$smarty->assign('employee_workorders_scheduled_count',          count_workorders($db, 'scheduled', $login_user_id)          );
$smarty->assign('employee_workorders_with_client_count',        count_workorders($db, 'with_client', $login_user_id)        );
$smarty->assign('employee_workorders_on_hold_count',            count_workorders($db, 'on_hold', $login_user_id)            );
$smarty->assign('employee_workorders_management_count',         count_workorders($db, 'management', $login_user_id)         );

// Employee Workorders
$smarty->assign('assigned_workorders',          display_workorders($db, 'DESC', false, $page_no, '25', null, null, 'assigned', $login_user_id)          );
$smarty->assign('waiting_for_parts_workorders', display_workorders($db, 'DESC', false, $page_no, '25', null, null, 'waiting_for_parts', $login_user_id) );
$smarty->assign('scheduled_workorders',         display_workorders($db, 'DESC', false, $page_no, '25', null, null, 'scheduled', $login_user_id)         );
$smarty->assign('with_client_workorders',       display_workorders($db, 'DESC', false, $page_no, '25', null, null, 'with_client', $login_user_id)       );
$smarty->assign('on_hold_workorders',           display_workorders($db, 'DESC', false, $page_no, '25', null, null, 'on_hold', $login_user_id)           );
$smarty->assign('management_workorders',        display_workorders($db, 'DESC', false, $page_no, '25', null, null, 'management', $login_user_id)        );

// Build the page
$smarty->assign('workorder_statuses', get_workorder_statuses($db));
$BuildPage .= $smarty->fetch('core/dashboard.tpl');