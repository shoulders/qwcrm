<?php
require('include.php');
if(!xml2php("schedule")) {
	$smarty->assign('error_msg',"Error in language file");
}
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


$y = $VAR['y'] ;
$m = $VAR['m'];
$d = $VAR['d'];
//$cur_date = $d."/".$m."/".$y; //added by Glen V
$cur_date = $d."/".$m."/".$y;




//$date_array2 = array('y'=>$y, 'd'=>$d, 'm'=>$m,);
$date_array = array('y'=>$y, 'd'=>$d, 'm'=>$m, 'wo_id'=>$wo_id);
$smarty->assign('date_array',$date_array);
$smarty->assign('date_array2',$date_array2);

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
$tech_array =  display_tech($db);
$smarty->assign('selected', $tech);
$smarty->assign('tech',$tech_array);
$smarty->assign('y',$y);
$smarty->assign('m',$m);
$smarty->assign('d',$d);

/* this will be pulled from the database later */
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
			<td class=\"olohead\" width=\"200\" align=\"center\" >$D </td>\n
		</tr>\n"	;

$i = 0;
$start = mktime($H,0,0,$m,$d,$y);
//$start = mktime($H,$d,$m,$y);

while($start <= $business_end){

	if(date("i",$start) == 0) {
	
		$calendar .= "<tr><td class=\"olotd\" nowrap>&nbsp;<b>".date("h:i a", $start)."</b></td>\n";
		
		if($start >= $sch[$i]['SCHEDULE_START'] && $start <= $sch[$i]['SCHEDULE_END']){
		
			if($start == $sch[$i]['SCHEDULE_START']){
				
					if($sch[$i]['WORK_ORDER_ID'] > 1 ) {
						//$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:view&wo_id=".$sch[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$sch[$i]['WORK_ORDER_ID ']."'\"><b>\n"; 
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&sch_id=".$sch[$i]['SCHEDULE_ID']."&y=".$y."&m=".$m."&d=".$d."'\">";
					  $calendar .= "Work Order ". $sch[$i]['WORK_ORDER_ID']." - Currently scheduled for ".date("h:i a",$sch[$i]['SCHEDULE_START'])." until ".date("h:i a",$sch[$i]['SCHEDULE_END'])." ".$sch[$i]['SCHEDULE_NOTES']."\n";
						$calendar . "</b></td>\n";
					} else {
						$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&sch_id=".$sch[$i]['SCHEDULE_ID']."&y=".$y."&m=".$m."&d=".$d."'\">";
						$calendar .= "<b>From: ".date("h:i a",$sch[$i]['SCHEDULE_START'])." to: ".date("h:i a",$sch[$i]['SCHEDULE_END']).' '.$sch[$i]['SCHEDULE_NOTES']."\n";
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
			
				if($sch[$i]['WORK_ORDER_ID'] > 1 ) {
					//$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:view&wo_id=".$sch[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$sch[$i]['WORK_ORDER_ID ']."'\"><b>\n"; 
					$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&sch_id=".$sch[$i]['SCHEDULE_ID']."&y=".$y."&m=".$m."&d=".$d."'\">";
					$calendar .= "Work Order ID ". $sch[$i]['WORK_ORDER_ID']." From: ".date("h:i a",$sch[$i]['SCHEDULE_START'])." To: ".date("h:i a",$sch[$i]['SCHEDULE_END'])." ".$sch[$i]['SCHEDULE_NOTES']."\n";
					$calendar . "</b></td>\n";
				} else {
					$calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&sch_id=".$sch[$i]['SCHEDULE_ID']."&y=".$y."&m=".$m."&d=".$d."'\">";
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




//Get weekdays calendar
$daystore = explode("-", $cur_date);
$dateline = mktime(0,0,0,$daystore[1], $daystore[0], $daystore[2]);
$nextday = $cur_date+(1*60*60*24);
$preday = $cur_date-(1*60*60*24);




$daysub = date("w", $dateline);

	if ($daysub != 1)
	{
		if ($daysub == 0)
		{
			// its a sunday, so we need to append one day to it..
			$startweekdateline = $dateline+86400;
			$eventdateline = $dateline;
		} else {
			$daysubt = $daysub-1;
			$startweekdateline = $dateline-($daysubt*86400);
		}
	} else {
		$startweekdateline = $dateline;
	}

	if (empty($eventdateline))
	{
		$eventdateline = $startweekdateline;
	}

	$prevweek = $dateline-(7 * 86400);
	$nextweek = $dateline+(7 * 86400);


	if ($loadentireweek == true)
	{
		$caltype = "week";
	} else {
		$caltype = "workweek";
	}

	// ======= HEADER =======
	$data .= '<table border="0" cellpadding="3" cellspacing="1" width="100%" class="tborder"><tr><td class="row1"><span class="smalltext">
	<table border="0" cellpadding="2" cellspacing="0" width="100%"><tr><td width="1" align="left"><a href="#" onClick="javascript:switchGridTab(\'tw'. $caltype .'\', \'teamwork\');fetchData(\'tw'. $caltype .'\', \''. date("d-m-Y", $prevweek) .'\');"><img src="'. $_SWIFT["themepath"] .'icon_back.gif" border="0" align="absmiddle" /></a></td>
	<td width="100%" align="center"><span class="ttexttitle">[%REPTITLE%]</span></td>
	<td width="1" align="right"><a href="#" onClick="javascript:switchGridTab(\'tw'. $caltype .'\', \'teamwork\');fetchData(\'tw'. $caltype .'\', \''. date("d-m-Y", $nextweek) .'\');"><img src="'. $_SWIFT["themepath"] .'icon_forward.gif" border="0" align="absmiddle" /></a></td></tr></table><BR />';

	$data .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="calborder">'.SWIFT_CRLF;

	$daylist = array();
	for ($ii=0; $ii<7; $ii++)
	{
		$day = array(1,2,3,4,5,6,7,8,9);
		if ($_schedule[$day."_enabled"] == 1 || $loadentireweek == true)
		{
			$daylist[] = $ii;
		}
	}

	$colspan = 2+(count($daylist)*2);
	if (!count($daylist))
	{
		$splitwidth = 100;
	} else {
		$splitwidth = round(100/count($daylist));
	}

	$data .= '<tr class="calhrbg"><td width="60" style="PADDING: 3px;" class="calhrbg"><span class="tabletitle">&nbsp;</span></td><td width="1" class="calendarsplitter"><img src="'. $_SWIFT["themepath"] .'space.gif" border="0" width="1" height="1" /></td>'.SWIFT_CRLF;

	for ($kk=0; $kk<7; $kk++)
	{
		// Sunday
		if ($kk == 0)
		{
			$daydateline = $startweekdateline-86400;
		} else if ($kk == 1) {
			$daydateline = $startweekdateline;
		} else {
			$daydateline = $startweekdateline+(($kk-1)*86400);
		}

		if (in_array($kk, $daylist))
		{
			if ($kk == $daylist[0])
			{
				$startdaydateline = $daydateline;
			} else if ($kk == $daylist[count($daylist)-1]) {
				$enddaydateline = $daydateline;
			}
			
			$data .= '<td width="'. $splitwidth .'%" align="center">'.SWIFT_CRLF;
			$data .= '<span class="tabletitle">'. date("D, d M", $daydateline) .'</span>'.SWIFT_CRLF;
			$data .= '</td>'.SWIFT_CRLF;
			if ($kk != $daylist[count($daylist)-1])
			{
				$data .= '<td width="1" class="calendarsplitter"><img src="'. $_SWIFT["themepath"] .'space.gif" border="0" width="1" height="1" /></td>'.SWIFT_CRLF;
			}
		}
	}
	$data .= '</tr>'.SWIFT_CRLF;

	for ($ii=0; $ii<24; $ii++)
	{
		$formattedhour = sprintf("%02d:00", $ii);
		$tdclass = "calinactivehour";
		$data .= '<tr class="'. $tdclass .'"><td width="60" style="PADDING: 3px;" class="calhrbg"><span class="tabletitle">'. $formattedhour .'</span></td><td width="1" class="calendarsplitter"><img src="'. $_SWIFT["themepath"] .'space.gif" border="0" width="1" height="1" /></td>';

		for ($kk=0; $kk<7; $kk++)
		{
			// Sunday
			if ($kk == 0)
			{
				$daydateline = $startweekdateline-86400;
			} else if ($kk == 1) {
				$daydateline = $startweekdateline;
			} else {
				$daydateline = $startweekdateline+(($kk-1)*86400);
			}

			$day = array(1,2,3,4,5,6,7,8,9);
//			echo $day."-".$_schedule[$day."_open"]."<BR />";
//			echo $day."-".$_schedule[$day."_close"]."<BR />";
			if (in_array($kk, $daylist))
			{
				$daycomp = $day."_enabled";
				$hrdateline = mktime($ii,0,0,date("m", $daydateline), date("d", $daydateline), date("Y", $daydateline));

				$opendateline = returnHourDateline($_schedule[$day."_open"], $daydateline);
				$closedateline = returnHourDateline($_schedule[$day."_close"], $daydateline);

				$tdclass = "calinactivehour";
				if ($_schedule[$daycomp] == "1" && $opendateline <= $hrdateline && $closedateline >= $hrdateline)
				{
					$tdclass = "calactivehour";
				}

				$verticalblocks = renderVerticalEventBlocks($_events, $hrdateline, ($hrdateline+3599), true, $_schedule);
 				$data .= '<td width="'. $splitwidth .'%" class="'. $tdclass .'"'. iif(empty($verticalblocks), ' style="CURSOR: pointer;" onMouseOver="javascript:this.className=\'calhrbg\';" onMouseOut="javascript:this.className=\''. $tdclass .'\';" onClick="javascript:window.location.href=\'index.php?_m=teamwork&_a=insertevent&startdateline='. $hrdateline .'\';" title="'. $_SWIFT["language"]["insertevent"] .'"') .'>';
 //				$data .= "Event: ".date("d m Y h:i:s A", $_events[8]["startdateline"])."<BR />";
//				$data .= "HR: ".date("d m Y h:i:s A", $hrdateline)."<BR />";
//				$data .= "Day: ".date("d m Y h:i:s A", $daydateline)."<BR />";
				$data .= $verticalblocks;
				$data .= '</td>';
				if ($kk != $daylist[count($daylist)-1])
				{
					$data .= '<td width="1" class="calendarsplitter"><img src="'. $_SWIFT["themepath"] .'space.gif" border="0" width="1" height="1" /></td>';
				}
				
			}
		}
	

/*		if ($opendateline < $hrdateline)
		{
			echo $formattedhour."Open is OK<BR />";
		} else {
			echo $formattedhour."Open Not OK<BR />";
		}

		if ($closedateline > $hrdateline)
		{
			echo $formattedhour."Close is OK<BR />";
		} else {
			echo $formattedhour."Close is NOT OK<BR />";
		}*/


		$data .= '</tr>';

		if ($ii != 23)
		{
			$data .= '<tr height="1" class="calendarsplitter"><td colspan="'. $colspan .'"><img src="'. $_SWIFT["themepath"] .'space.gif" border="0" width="1" height="1" /></td></tr>';
		}
	}
	$data .= '</table>';

	$data .= '</span></td></tr></table><BR />';

	return str_replace("[%REPTITLE%]", date("F d Y", $startdaydateline)." - ".date("F d Y", $enddaydateline), $data);
/* get employee Display Name */

/* feed smarty */
$smarty->assign('calendar', $calendar);
$smarty->assign('cur_date', $cur_date);
$smarty->display('schedule'.SEP.'main.tpl');


?>
