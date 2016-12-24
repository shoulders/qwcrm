<?php

require(INCLUDES_DIR.'modules/workorder.php');

$smarty->assign('new',      display_workorders($db, $page_no, 1)    );
$smarty->assign('assigned', display_workorders($db, $page_no, 2)    );
$smarty->assign('awaiting', display_workorders($db, $page_no, 3)    );
$smarty->assign('payment',  display_workorders($db, $page_no, 7)    );

$smarty->display('workorder'.SEP.'open.tpl');