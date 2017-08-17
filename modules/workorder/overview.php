<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/report.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Overall Workorder Stats
$smarty->assign('overall_workorders_open_count',               count_workorders($db, 'open')    );
$smarty->assign('overall_workorders_assigned_count',           count_workorders($db, '2')       );
$smarty->assign('overall_workorders_waiting_for_parts_count',  count_workorders($db, '3')       );
$smarty->assign('overall_workorders_on_hold_count',            count_workorders($db, '4')       );
$smarty->assign('overall_workorders_management_count',         count_workorders($db, '5')       );
$smarty->assign('overall_workorders_total_closed_count',       count_workorders($db, 'closed')  );

// Workorders
$smarty->assign('unassigned_workorders',        display_workorders($db, 'DESC', false, $page_no, '25', null, null, '1')    );
$smarty->assign('assigned_workorders',          display_workorders($db, 'DESC', false, $page_no, '25', null, null, '2')    );
$smarty->assign('waiting_for_parts_workorders', display_workorders($db, 'DESC', false, $page_no, '25', null, null, '3')    );
$smarty->assign('on_hold_workorders',           display_workorders($db, 'DESC', false, $page_no, '25', null, null, '4')    );
$smarty->assign('management_workorders',        display_workorders($db, 'DESC', false, $page_no, '25', null, null, '5')    );

$BuildPage .= $smarty->fetch('workorder/overview.tpl');