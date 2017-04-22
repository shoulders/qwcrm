<?php

require(INCLUDES_DIR.'modules/workorder.php');

$smarty->assign('workorders', display_workorders($db, '6', 'DESC', true, $page_no));

$BuildPage .= $smarty->fetch('workorder/closed.tpl');