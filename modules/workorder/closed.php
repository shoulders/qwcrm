<?php

require(INCLUDES_DIR.'modules/workorder.php');

$smarty->assign('work_order', display_workorders($db, 6, 'DESC', true, $page_no));

$smarty->display('workorder/closed.tpl');