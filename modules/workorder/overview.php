<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

$smarty->assign('unassigned_workorders',        display_workorders($db, 'DESC', false, $page_no, '25', null, null, '1')    );
$smarty->assign('assigned_workorders',          display_workorders($db, 'DESC', false, $page_no, '25', null, null, '2')    );
$smarty->assign('waiting_for_parts_workorders', display_workorders($db, 'DESC', false, $page_no, '25', null, null, '3')    );
$smarty->assign('on_hold_workorders',           display_workorders($db, 'DESC', false, $page_no, '25', null, null, '4')    );
$smarty->assign('management_workorders',        display_workorders($db, 'DESC', false, $page_no, '25', null, null, '5')    );

$BuildPage .= $smarty->fetch('workorder/overview.tpl');