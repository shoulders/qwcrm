<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/report.php');
require(INCLUDES_DIR.'components/workorder.php');

// Build the page
$smarty->assign('overview_workorders_unassigned',        display_workorders($db, 'workorder_id', 'DESC', false, $VAR['page_no'], '25', null, null, 'unassigned')        );
$smarty->assign('overview_workorders_assigned',          display_workorders($db, 'workorder_id', 'DESC', false, $VAR['page_no'], '25', null, null, 'assigned')          );
$smarty->assign('overview_workorders_waiting_for_parts', display_workorders($db, 'workorder_id', 'DESC', false, $VAR['page_no'], '25', null, null, 'waiting_for_parts') );
$smarty->assign('overview_workorders_scheduled',         display_workorders($db, 'workorder_id', 'DESC', false, $VAR['page_no'], '25', null, null, 'scheduled')         );
$smarty->assign('overview_workorders_with_client',       display_workorders($db, 'workorder_id', 'DESC', false, $VAR['page_no'], '25', null, null, 'with_client')       );
$smarty->assign('overview_workorders_on_hold',           display_workorders($db, 'workorder_id', 'DESC', false, $VAR['page_no'], '25', null, null, 'on_hold')           );
$smarty->assign('overview_workorders_management',        display_workorders($db, 'workorder_id', 'DESC', false, $VAR['page_no'], '25', null, null, 'management')        );

$smarty->assign('overview_workorder_stats', get_workorder_stats($db));
$smarty->assign('workorder_statuses', get_workorder_statuses($db));

$BuildPage .= $smarty->fetch('workorder/overview.tpl');