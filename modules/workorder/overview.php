<?php

require(INCLUDES_DIR.'modules/workorder.php');

$smarty->assign('new_workorders',      display_workorders($db, '1')    );
$smarty->assign('assigned_workorders', display_workorders($db, '2')    );
$smarty->assign('awaiting_workorders', display_workorders($db, '3')    );
$smarty->assign('unpaid_workorders',  display_workorders($db, '7')    );

$BuildPage .= $smarty->fetch('workorder/overview.tpl');