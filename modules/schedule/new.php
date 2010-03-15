<?php
require_once ("include.php");
if(!xml2php("schedule")) {
	$smarty->assign('error_msg',"Error in language file");

}
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
//Schedule Due Date
$date_part2 = explode("/",$VAR['day']);
//$timestamp2 = mktime(0,0,0,$date_part2[1],$date_part2[0],$date_part2[2]);
if($date_format == "%d/%m/%Y"){
$cur_date = $d."/".$m."/".$Y;}
if($date_format == "%m/%d/%Y"){
$cur_date = $m."/".$d."/".$Y;};
$smarty->assign('Y',$Y);
$smarty->assign('m',$m);
$smarty->assign('d',$d);


/* display new Workorders */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(1);
$smarty->assign('new', display_workorders($db, $page_no, $where));

/* display new Workorders */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(2);
$smarty->assign('assigned', display_workorders($db, $page_no, $where));

/* display new Workorders */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(3);
$smarty->assign('awaiting', display_workorders($db, $page_no, $where));

if(isset($VAR['submit'])){


		if (!insert_new_schedule($db,$VAR)) {
				/* If db insert fails send em the error */	
				$day        = $VAR['start']['schedule_date'];
				$start_time = $VAR['start']['Time_Hour'].":".$VAR['start']['Time_Minute']." ".$VAR['start']['Time_Meridian'];
				$notes      = $VAR['schedule_notes']; 
				$end_time   = $VAR['end']['Time_Hour'].":".$VAR['end']['Time_Minute']." ".$VAR['end']['Time_Meridian'];
				
				$smarty->assign('end_time', $end_time);
				$smarty->assign('start_day', $day);
				$smarty->assign('start_time', $start_time);
				$smarty->assign('schedule_notes', $notes);
				$smarty->assign('tech',  $VAR['tech']);
				$smarty->assign('wo_id', $VAR['wo_id']);
				$smarty->display("schedule/new.tpl");
				//force_page('schedule','main&y='.$s_year.'&d='.$s_month.'&m='.$s_day.'&wo_id='.$VAR['wo_id'].'&page_title=schedule&tech='.$VAR['tech']);
			} else {
				//list($s_day, $s_month, $s_year) = split('[/.-]', $VAR['start']['SCHEDULE_date']);
				list($s_month, $s_day, $s_year) = split('[/.-]', $VAR['start']['SCHEDULE_date']);
				force_page('schedule','main&y='.$s_year.'&d='.$s_month.'&m='.$s_day.'&wo_id='.$VAR['wo_id'].'&page_title=schedule&tech='.$VAR['tech']);
			}

	
} else {

		// Load html form to smarty
		$start_time = $VAR['starttime'];
		$day = $VAR['day'];
		$wo_id = $VAR['wo_id'];
		$tech  = $VAR['tech'];
		$smarty->assign('tech', $tech);
		$smarty->assign('wo_id', $wo_id);
		$smarty->assign('start_day', $day);
		$smarty->assign('start_time', $start_time);
		$smarty->display('schedule'.SEP.'new.tpl');
}

?>