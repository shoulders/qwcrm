<?php
$sch_id = $VAR['sch_id'];
$y = $VAR['y'];
$m = $VAR['m'];
$d = $VAR['d'];


if(isset($VAR['submit'])) {
	$q = "UPDATE ".PRFX."TABLE_SCHEDULE SET
			SCHEDULE_NOTES  	=". $db->qstr($VAR['schedule_notes']) ."
			WHERE SCHEDULE_ID =".$db->qstr($sch_id);
		
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			force_page('schedule', 'main&sch_id='.$sch_id.'&y='.$y.'&d='.$d.'&m='.$m); 
      //force_page('schedule', 'main&sch_id='.$sch_id.'&y='.$y.'&m='.$m.'&d='.$d); 			
			exit;
		}
} else {
	$q = "SELECT SCHEDULE_NOTES FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_ID=".$db->qstr($sch_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$smarty->assign('y',$y);
	$smarty->assign('d',$d);
	$smarty->assign('m',$m);
	$smarty->assign('schedule_notes', $rs->fields['SCHEDULE_NOTES']);
	$smarty->assign('sch_id',$sch_id);
	$smarty->display('schedule'.SEP.'edit.tpl');
}
?>
