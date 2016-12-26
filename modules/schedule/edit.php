<?php

require(INCLUDES_DIR.'modules/schedule.php');

$y = $VAR['y'];
$m = $VAR['m'];
$d = $VAR['d'];


if(isset($VAR['submit'])) {
    $q = "UPDATE ".PRFX."TABLE_SCHEDULE SET
            SCHEDULE_NOTES      =". $db->qstr($VAR['schedule_notes']) ."
            WHERE SCHEDULE_ID =".$db->qstr($schedule_id);
        
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        } else {
            force_page('schedule', 'main&schedule_id='.$schedule_id.'&schedule_start_year='.$y.'&schedule_start_month='.$m.'&schedule_start_day='.$d); 
      //force_page('schedule', 'main&schedule_id='.$schedule_id.'&schedule_start_year='.$y.'&schedule_start_month='.$m.'&schedule_start_day='.$d);             
            exit;
        }
} else {
    $q = "SELECT SCHEDULE_NOTES FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_ID=".$db->qstr($schedule_id);
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    $smarty->assign('y',$y);
    $smarty->assign('d',$d);
    $smarty->assign('m',$m);
    $smarty->assign('schedule_notes', $rs->fields['SCHEDULE_NOTES']);
    $smarty->assign('schedule_id',$schedule_id);
    $smarty->display('schedule'.SEP.'edit.tpl');
}
