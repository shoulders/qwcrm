<?php
require('include.php');
if(!xml2php("schedule")) {
	$smarty->assign('error_msg',"Error in language file");
}
/* load the date formate from the js calendar */
$wo_id = $_GET['wo_id'];

/* check if work order closed we don't want to reschedule a work order if it's closed */
if(isset($wo_id)) {
	$q = "SELECT WORK_ORDER_CURRENT_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;	
	} else {
		$status = $rs->fields['WORK_ORDER_CURRENT_STATUS'];
	}
	
	
	if($status == '6') {
		force_page('workorder', 'view&wo_id='.$wo_id.'&error_msg=Can not set a schedule for closed work order&page_title=Work Order ID '.$wo_id.'&type=warning');
	} elseif ($status == '7') {
		force_page('workorder', 'view&wo_id='.$wo_id.'&error_msg=Can not set a schedule for closed work order&page_title=Work Order ID '.$wo_id.'&type=warning');
	} elseif ($status == '8') {
		force_page('workorder', 'view&wo_id='.$wo_id.'&error_msg=Can not set a schedule for closed work order&page_title=Work Order ID '.$wo_id.'&type=warning');
	} elseif ($status == '9') {
		force_page('workorder', 'view&wo_id='.$wo_id.'&error_msg=Can not set a schedule for closed work order&page_title=Work Order ID '.$wo_id.'&type=warning');
	}
		
}


$y = $VAR['y'] ;
$m = $VAR['m'];
$d = $VAR['d'];
/* get Date Formatting value from database and assign it to $format*/
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$format = $rs->fields['COMPANY_DATE_FORMAT'];
	}
// Stripping out the percentage signs so php can render it correctly
$literals = "%";
$Dformat = str_replace($literals, "", $format);
//Now lets display the right date format
if($Dformat == 'd/m/Y' || $Dformat == 'd/m/y'  ){
$cur_date = $d."/".$m."/".$y;}
elseif($Dformat == 'm/d/Y' || $Dformat == 'm/d/y' ){
$cur_date = $m."/".$d."/".$y;}
//Assign it to Smarty
$smarty->assign('cur_date', $cur_date);

$date_array = array('y'=>$y, 'm'=>$m, 'd'=>$d, 'wo_id'=>$wo_id);
$smarty->assign('date_array',$date_array);

/* load start time from setup */
$q = "SELECT OFFICE_HOUR_START,OFFICE_HOUR_END FROM ".PRFX."SETUP";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$H = $rs->fields['OFFICE_HOUR_START'];
$E = $rs->fields['OFFICE_HOUR_END'];

if(empty($H) || empty($E)) {
	force_page('core', 'error&error_msg=You must first set a start and stop times in the control center');
	exit;
}

/* get the curent login */ 
if(!isset($VAR['tech'])) {
	$tech = $_SESSION['login_id'];
} else {
	$tech = $VAR['tech'];
}

/* get list of techs for dropdown*/
$q = "SELECT EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_ID=".$db->qstr($tech);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$smarty->assign('tech', $rs->fields['EMPLOYEE_DISPLAY_NAME']);

/* this will be pulled from the database latter */
$business_start = mktime($H,0,0,$m,$d,$y);
$business_end = mktime($E,0,0,$m,$d,$y);


/* look in the database for a scheduleed event and build the calander */	
	$q = "SELECT * FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_START >= " . $business_start. " AND SCHEDULE_START  <= " .$business_end. "
			AND  EMPLOYEE_ID ='".$tech."' ORDER BY SCHEDULE_START ASC";
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
	}
	
	$sch = array();
	while (!$rs->EOF ){
		array_push($sch, array(
				 "SCHEDULE_ID"		=> $rs->fields["SCHEDULE_ID"],
				 "SCHEDULE_START"	=> $rs->fields["SCHEDULE_START"],
				 "SCHEDULE_END"		=> $rs->fields["SCHEDULE_END"],	
				 "SCHEDULE_NOTES"	=> $rs->fields["SCHEDULE_NOTES"],
				 "WORK_ORDER_ID"	=> $rs->fields["WORK_ORDER_ID"]
				 ));
		$rs->MoveNext();
	}
		


/* start the calendar var */
$calendar .= "<table  cellpadding=\"0\" cellspacing=\"0\"  class=\"olotable\">\n
		<tr>\n
			<td class=\"olohead\" width=\"75\">&nbsp;</td>\n
			<td class=\"olohead\" width=\"600\">&nbsp;</td>\n
		</tr>\n"	;

$i = 0;
$start = mktime($H,0,0,$m,$d,$y);

while($start <= $business_end){

	if(date("i",$start) == 0) {
	
		$calendar .= "<tr><td class=\"olotd\" nowrap>&nbsp;<b>".date("h:i a", $start)."</b></td>\n";
		
		if($start >= $sch[$i]['SCHEDULE_START'] && $start <= $sch[$i]['SCHEDULE_END']){
		
			if($start == $sch[$i]['SCHEDULE_START']){
				
					if($sch[$i]['WORK_ORDER_ID'] != 0) {
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:view&wo_id=".$sch[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$sch[$i]['WORK_ORDER_ID ']."'\"><b>\n";
						$calendar .= "Work Order ID ". $sch[$i]['WORK_ORDER_ID']." From: ".date("h:i a",$sch[$i]['SCHEDULE_START'])." To: ".date("h:i a",$sch[$i]['SCHEDULE_END'])." ".$sch[$i]['SCHEDULE_NOTES']."\n";
						$calendar . "</b></td>\n";
					} else {
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&sch_id=".$sch[$i]['SCHEDULE_ID']."'\">";
						$calendar .= "<b>From: ".date("h:i a",$sch[$i]['SCHEDULE_START'])." To: ".date("h:i a",$sch[$i]['SCHEDULE_END']).' '.$sch[$i]['SCHEDULE_NOTES']."\n";
						$calendar .= "</b></td>\n";
					}
					
			} else {
				$calendar .= "<td class=\"menutd2\">&nbsp;</td>\n";
			}
			
		} else {
		
			$calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedule:new&starttime=".date("h:i a", $start)."&day=".$cur_date."&wo_id=".$wo_id."&tech=".$tech."'\"></td>\n";
		}
		
		$calendar .= "</tr>";
	} else {
		$calendar .= "<tr>\n<td></td>\n";
		
		if($start >= $sch[$i]['SCHEDULE_START'] && $start <= $sch[$i]['SCHEDULE_END']){
		
			if($start == $sch[$i]['SCHEDULE_START']) {
			
				if($sch[$i]['WORK_ORDER_ID'] != 0) {
					$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:view&wo_id=".$sch[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$sch[$i]['WORK_ORDER_ID ']."'\"><b>\n";
					$calendar .= "Work Order ID ". $sch[$i]['WORK_ORDER_ID']." From: ".date("h:i a",$sch[$i]['SCHEDULE_START'])." To: ".date("h:i a",$sch[$i]['SCHEDULE_END'])." ".$sch[$i]['SCHEDULE_NOTES']."\n";
					$calendar . "</b></td>\n";
				} else {
					$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&sch_id=".$sch[$i]['SCHEDULE_ID']."'\">";
					$calendar .= "<b>From: ".date("h:i a",$sch[$i]['SCHEDULE_START'])." To: ".date("h:i a",$sch[$i]['SCHEDULE_END']).' '.$sch[$i]['SCHEDULE_NOTES']."\n";
					$calendar .= "</b></td>\n";
				}
					
			}  else {
				$calendar .= "<td class=\"menutd2\"><br></td>\n</tr>";
			}
			
		} else {
			$calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&starttime=".date("h:i a", $start) ."&day=".$cur_date."&wo_id=".$wo_id."&tech=".$tech."'\">&nbsp; ".date("h:i a", $start)."</td>\n</tr>";
		}
		
	}

	if($start == $sch[$i]['SCHEDULE_END']) {
		$i++;
	}
	$start = mktime(date("H",$start),date("i",$start)+15,0,$m,$d,$y);
	
		
}

$calendar .= "\n</table>";

/* feed smarty */
$smarty->assign('calendar', $calendar);
$smarty->assign('cur_date', $cur_date);
$smarty->assign('d', $d);
$smarty->assign('m', $m);
$smarty->assign('y', $y);
$smarty->display('schedule'.SEP.'print.tpl');


?>
