<?php

require(INCLUDES_DIR.'modules/workorder.php');

$smarty->assign('work_order', display_closed($db, $page_no));

$smarty->display('workorder'.SEP.'closed.tpl');