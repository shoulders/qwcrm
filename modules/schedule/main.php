<?php
require('include.php');
if(!xml2php("schedule")) {
	$smarty->assign('error_msg',"Error in language file");
}
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
/* display new Workorders */
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(1);
$smarty->assign('new', display_workorders($db, $page_no, $where));

/* display new Workorders */
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(2);
$smarty->assign('assigned', display_workorders($db, $page_no, $where));

/* display new Workorders */
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(3);
$smarty->assign('awaiting', display_workorders($db, $page_no, $where));

/* Get employee credentials */
$q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME ='".$login."'" ;
$rs = $db->Execute($q);
$cred = $rs->FetchRow();

$smarty->assign('cred',$cred);

/* load the date format from the js calendar */
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


$y = $VAR['y'];
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


$date_array = array('y'=>$y, 'd'=>$d, 'm'=>$m, 'wo_id'=>$wo_id);
$smarty->assign('date_array',$date_array);

/* load start time from setup */
$q = "SELECT OFFICE_HOUR_START,OFFICE_HOUR_END FROM ".PRFX."SETUP";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$H = $rs->fields['OFFICE_HOUR_START'];
$E = $rs->fields['OFFICE_HOUR_END'];

if(($H) == ($E)) {
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
$tech_array =  display_tech($db);
$smarty->assign('selected', $tech);
$smarty->assign('tech',$tech_array);
$smarty->assign('y',$y);
$smarty->assign('m',$m);
$smarty->assign('d',$d);

/* this will be pulled from the database later */
$business_start = mktime($H,0,0,$m,$d,$y);
$business_end = mktime($E,0,0,$m,$d,$y);
//$business_end = mktime($E,0,0,$nextday);

/* look in the database for a scheduled event and build the calander */
	$q = "SELECT ".PRFX."TABLE_SCHEDULE.*,
	".PRFX."TABLE_CUSTOMER.CUSTOMER_DISPLAY_NAME
	FROM ".PRFX."TABLE_SCHEDULE
	LEFT JOIN ".PRFX."TABLE_WORK_ORDER ON ".PRFX."TABLE_SCHEDULE.WORK_ORDER_ID
    LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID
	WHERE ".PRFX."TABLE_SCHEDULE.SCHEDULE_START >= " . $business_start. " AND ".PRFX."TABLE_SCHEDULE.SCHEDULE_START  <= " .$business_end. "
	AND  ".PRFX."TABLE_SCHEDULE.EMPLOYEE_ID ='".$tech."'  ORDER BY ".PRFX."TABLE_SCHEDULE.SCHEDULE_START ASC";
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
				 "CUSTOMER_NAME"	=> $rs->fields["CUSTOMER_DISPLAY_NAME"],
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
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:view&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$sch[$i]['WORK_ORDER_ID ']."'\"><b>\n";
						$calendar .= " <b><font color=\"red\">Work Order ". $sch[$i]['WORK_ORDER_ID']." for ". $sch[$i]['CUSTOMER_NAME']."<br>".date("h:i a",$sch[$i]['SCHEDULE_START'])." - ".date("h:i a",$sch[$i]['SCHEDULE_END'])."</font><br><font color=\"blue\">NOTES-  ".$sch[$i]['SCHEDULE_NOTES']."</font><br>
						<a href=\"index.php?page=schedule:edit&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&y=".$y."&m=".$m."&d=".$d."&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."\">Add Note</a> -
						<a href=\"index.php?page=schedule:sync&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."&escape=1\">Sync</a> -
						<a href=\"index.php?page=schedule:delete&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&amp;y=".$y."&amp;m=".$m."&amp;d=".$d."&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."\">Delete</a>\n";
						$calendar . "</b></td>\n";
					} else {
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&y=".$y."&m=".$m."&d=".$d."'\">";
					  $calendar .= " <b><font color=\"red\">Work Order ". $sch[$i]['WORK_ORDER_ID']."for ". $sch[$i]['CUSTOMER_NAME']."<br>".date("h:i a",$sch[$i]['SCHEDULE_START'])." - ".date("h:i a",$sch[$i]['SCHEDULE_END'])."</font><br><font color=\"blue\">NOTES-  ".$sch[$i]['SCHEDULE_NOTES']."</font><br>
                        <a href=\"index.php?page=schedule:edit&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&amp;y=".$y."&amp;m=".$m."&amp;d=".$d."&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."\">Add Note</a> -
						<a href=\"index.php?page=schedule:sync&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."&escape=1\">Sync</a> -
						<a href=\"index.php?page=schedule:delete&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&y=".$y."&m=".$m."&d=".$d."&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."\">Delete</a>\n";
						$calendar . "</b></td>\n";
					}

			} else {
				$calendar .= "<td class=\"menutd2\">&nbsp;</td>\n";
			}

		} else {

 			$calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedule:new&amp;starttime=".date("h:i a", $start)."&amp;day=".$cur_date."&amp;wo_id=".$wo_id."&amp;tech=".$tech."'\"></td>\n";
		}

		$calendar .= "</tr>";
	} else {
		$calendar .= "<tr>\n<td></td>\n";

		if($start >= $sch[$i]['SCHEDULE_START'] && $start <= $sch[$i]['SCHEDULE_END']){

			if($start == $sch[$i]['SCHEDULE_START']) {

				if($sch[$i]['WORK_ORDER_ID'] != 0) {
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:view&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$sch[$i]['WORK_ORDER_ID ']."'\"><b>\n";
						$calendar .= " <b><font color=\"red\">Work Order ". $sch[$i]['WORK_ORDER_ID']." for ". $sch[$i]['CUSTOMER_NAME']."<br>".date("h:i a",$sch[$i]['SCHEDULE_START'])." - ".date("h:i a",$sch[$i]['SCHEDULE_END'])."</font><br><font color=\"blue\">NOTES-  ".$sch[$i]['SCHEDULE_NOTES']."</font><br>
						<a href=\"index.php?page=schedule:edit&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&amp;y=".$y."&amp;m=".$m."&amp;d=".$d."&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."\">Add Note</a> -
						<a href=\"index.php?page=schedule:sync&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."&amp;escape=1\">Sync</a> -
						<a href=\"index.php?page=schedule:delete&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&amp;y=".$y."&amp;m=".$m."&amp;d=".$d."&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."\">Delete</a>\n";
						$calendar . "</b></td>\n";
					} else {
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&y=".$y."&m=".$m."&d=".$d."'\">";
						$calendar .= " <b><font color=\"red\">Work Order ". $sch[$i]['WORK_ORDER_ID']." for ". $sch[$i]['CUSTOMER_NAME']."<br>".date("h:i a",$sch[$i]['SCHEDULE_START'])." - ".date("h:i a",$sch[$i]['SCHEDULE_END'])."</font><br><font color=\"blue\">NOTES-  ".$sch[$i]['SCHEDULE_NOTES']."</font><br>
						<a href=\"index.php?page=schedule:edit&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&amp;y=".$y."&amp;m=".$m."&amp;d=".$d."&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."\">Add Note</a> -
						<a href=\"index.php?page=schedule:sync&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."&amp;escape=1\">Sync</a> -
						<a href=\"index.php?page=schedule:delete&amp;sch_id=".$sch[$i]['SCHEDULE_ID']."&amp;y=".$y."&amp;m=".$m."&amp;d=".$d."&amp;wo_id=".$sch[$i]['WORK_ORDER_ID']."\">Delete</a>\n";
						$calendar . "</b></td>\n";
					}

			}  else {
				$calendar .= "<td class=\"menutd2\"><br></td>\n</tr>";
			}

		} else {
			$calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&amp;starttime=".date("h:i a", $start) ."&amp;day=".$cur_date."&amp;wo_id=".$wo_id."&amp;tech=".$tech."'\">&nbsp; ".date("h:i a", $start)."</td>\n</tr>";
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
$smarty->display('schedule'.SEP.'main.tpl');


?>
