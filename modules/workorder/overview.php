<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

$smarty->assign('new_workorders',      display_workorders($db, 'DESC', false, $page_no, '25', null, null, '1')    );
$smarty->assign('assigned_workorders', display_workorders($db, 'DESC', false, $page_no, '25', null, null, '2')    );
$smarty->assign('awaiting_workorders', display_workorders($db, 'DESC', false, $page_no, '25', null, null, '3')    );
$smarty->assign('unpaid_workorders',   display_workorders($db, 'DESC', false, $page_no, '25', null, null, '7')    );

$BuildPage .= $smarty->fetch('workorder/overview.tpl');