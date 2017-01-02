<?php

require(INCLUDES_DIR.'modules/workorder.php');

$smarty->assign('new',      display_workorders($db, 1)    );
$smarty->assign('assigned', display_workorders($db, 2)    );
$smarty->assign('awaiting', display_workorders($db, 3)    );
$smarty->assign('payment',  display_workorders($db, 7)    );

$BuildPage .= $smarty->fetch('workorder/overview.tpl');