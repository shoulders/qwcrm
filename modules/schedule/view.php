<?php

require(INCLUDES_DIR.'modules/schedule.php');

$y =    $VAR['y'];
$m =    $VAR['m'];
$d =    $VAR['d'];

$arr = display_single_schedule($db, $schedule_id);

if($arr) {
    $smarty->assign('y',$y);
    $smarty->assign('m',$m);
    $smarty->assign('d',$d);
    //$smarty->assign('woid',$workorder_id); this does not sem to be used
    $smarty->assign('arr', $arr);
    $smarty->display('schedule'.SEP.'view.tpl');
} else {
    echo "No schedule found";
}