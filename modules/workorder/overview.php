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

// Overall Workorder Stats
$smarty->assign('overview_workorders_open_count',               count_workorders($db, 'open')                );
$smarty->assign('overview_workorders_assigned_count',           count_workorders($db, 'assigned')            );
$smarty->assign('overview_workorders_waiting_for_parts_count',  count_workorders($db, 'waiting_for_parts')   );
$smarty->assign('overview_workorders_scheduled_count',          count_workorders($db, 'scheduled')           );
$smarty->assign('overview_workorders_with_client_count',        count_workorders($db, 'with_client')         );
$smarty->assign('overview_workorders_on_hold_count',            count_workorders($db, 'on_hold')             );
$smarty->assign('overview_workorders_management_count',         count_workorders($db, 'management')          );

// Workorders
$smarty->assign('unassigned_workorders',        display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'unassigned')        );
$smarty->assign('assigned_workorders',          display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'assigned')          );
$smarty->assign('waiting_for_parts_workorders', display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'waiting_for_parts') );
$smarty->assign('scheduled_workorders',         display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'scheduled')         );
$smarty->assign('with_client_workorders',       display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'with_client')       );
$smarty->assign('on_hold_workorders',           display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'on_hold')           );
$smarty->assign('management_workorders',        display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'management')        );

// Build the page
$smarty->assign('workorder_statuses', get_workorder_statuses($db));
$BuildPage .= $smarty->fetch('workorder/overview.tpl');