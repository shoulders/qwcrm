<?php
require_once('modules'.SEP.'workorder'.SEP.'include.php');
$smarty->assign('return_array', display_open_workorders($db));

$smarty->display('workorder'.SEP.'blocks'.SEP.'open_work_orders_block.tpl');
?>