<?php
require('include.php');
if(!xml2php("schedule")) {
    $smarty->assign('error_msg',"Error in language file");
}
$schedule_id = $VAR['schedule_id'];
$woid = $VAR['woid'];
$y =    $VAR['y'];
$m =    $VAR['m'];
$d =    $VAR['d'];

$arr = view_schedule($db, $schedule_id);
//echo $arr;
if($arr) {
    $smarty->assign('y',$y);
    $smarty->assign('m',$m);
    $smarty->assign('d',$d);
    $smarty->assign('woid',$woid);
    $smarty->assign('arr', $arr);
    $smarty->display('schedule'.SEP.'view.tpl');
} else {
    echo "No schedule found";
}