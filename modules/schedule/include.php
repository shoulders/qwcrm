<?php
$wo_id = $VAR['wo_id'];
$smarty->assign('wo_id',$wo_id);
######################################
# Insert New schedule  				 #
######################################
	function insert_new_schedule($db,$VAR){
		global $smarty;
		$wo_id = $VAR['wo_id'];
		list($s_month, $s_day, $s_year) = split('[/.-]', $VAR['start']['SCHEDULE_date']);
		list($e_month, $e_day, $e_year) = split('[/.-]', $VAR['end']['SCHEDULE_date']);
		
		$s_hour = $VAR['start']['Time_Hour'];
		$s_min  = $VAR['start']['Time_Minute'];
		$s_med  = $VAR['start']['Time_Meridian'];
		
		$e_hour = $VAR['end']['Time_Hour'];
		$e_min  = $VAR['end']['Time_Minute'];
		$e_med  = $VAR['end']['Time_Meridian'];
		
		$secs   = "00";
		//$date1 = $VAR['date']
                /* get Date Formatting value from database and assign it to $format*/
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$date_format = $rs->fields['COMPANY_DATE_FORMAT'];
	}
                if($date_format == "%d/%m/%Y" || $date_format == "%d/%m/%y"){
		$start_time = strtotime("$s_day/$s_month/$s_year $s_hour:$s_min:$secs $s_med");
		$end_time   = strtotime("$e_day/$e_month/$e_year $e_hour:$e_min:$secs $e_med");
                } else if ($date_format == "%m/%d/%Y" || $date_format == "%m/%d/%y"){
                 $start_time = strtotime("$s_month/$s_day/$s_year $s_hour:$s_min:$secs $s_med");
		$end_time   = strtotime("$e_month/$e_day/$e_year $e_hour:$e_min:$secs $e_med"); 
                }
		
		
		/* check for stupid*/
		if($start_time > $end_time) {
			$error_msg  = 'Schedule ends before it starts.';
			$smarty->assign('error_msg',$error_msg);
			return false;
		}
		
		if($start_time == $end_time) {
			$error_msg = 'Start Time and End Time are the Same';
			$smarty->assign('error_msg',$error_msg);
			return false;
		}
		
		/*get todays schedule*/
		$db_start = mktime(0,0,0,date("m",$start_time),date("d",$start_time),date("Y",$start_time));
		$db_end   = mktime(23,59,59,date("m",$start_time),date("d",$start_time),date("Y",$start_time));
		
		$q = "SELECT  SCHEDULE_START,SCHEDULE_END, SCHEDULE_ID  FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_START >= ".$db_start." AND SCHEDULE_END <=".$db_end." AND  EMPLOYEE_ID ='".$VAR['tech']."' ORDER BY SCHEDULE_START ASC";
		//print $q;
		
		if(!$rs = $db->Execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		
		
		$counter = 1;

		while (!$rs->EOF ){
			//print $start_time . '>= '.$rs->fields["SCHEDULE_START"].' AND '.$start_time <= $rs->fields["SCHEDULE_END"].'<br>';

			/* Check if start time starts when another is already set */
			if($start_time >= $rs->fields["SCHEDULE_START"] && $start_time <= $rs->fields["SCHEDULE_END"]) {
				$error_msg = 'Start time starts before another schedule ends<br>';
				$smarty->assign('error_msg',$error_msg);	
				return false;
			}
			
			/* Check if start time starts befor one ends */

			//print $end_time.' >= '.$rs->fields["SCHEDULE_START"].' && '.$start_time.' <= '.$rs->fields["SCHEDULE_START"].'<br>';
			if($end_time >= $rs->fields["SCHEDULE_START"] && $start_time <= $rs->fields["SCHEDULE_START"]) {
			
				$error_msg = "Schedule conflict. End time runs into next schedule";
				$smarty->assign('error_msg',$error_msg);	
				return false;
			}
			
			$rs->MoveNext();
		}

		if($wo_id != 0 ) {
		
			/* Update work order and assign to tech */
			$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET 
				  WORK_ORDER_ASSIGN_TO		=".$db->qstr($VAR['tech']).",  	  
				  WORK_ORDER_CURRENT_STATUS	=".$db->qstr(2).",
				  LAST_ACTIVE 				=".$db->qstr(time())."  
				  WHERE  WORK_ORDER_ID=".$db->qstr($VAR['wo_id']);

			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}	

			/* get employee ID and Login */
			$q = "SELECT EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_ID=".$db->qstr($VAR['tech']);
			
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().' SQL: '.$q.'&menu=1&type=database');
				exit;
			} else {
				$tech = $rs->fields['EMPLOYEE_DISPLAY_NAME'];
			}
			
			
			/* update Notes */
			$msg ="Work Order Assigned to ".$tech;
			$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				  WORK_ORDER_ID					= ".$db->qstr($VAR['wo_id']).",
				  WORK_ORDER_STATUS_NOTES  	= ".$db->qstr($msg).",
				  WORK_ORDER_STATUS_ENTER_BY 	= ".$db->qstr($_SESSION['login_id']).",
				  WORK_ORDER_STATUS_DATE  		= ".$db->qstr(time());
			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}
				  
			/* update Notes */
			$msg ="Schedule has been set.";
			$q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
				  WORK_ORDER_ID					= ".$db->qstr($VAR['wo_id']).",
				  WORK_ORDER_STATUS_NOTES  	= ".$db->qstr($msg).",
				  WORK_ORDER_STATUS_ENTER_BY  	= ".$db->qstr($_SESSION['login_id']).",
				  WORK_ORDER_STATUS_DATE  		= ".$db->qstr(time());
			if(!$rs = $db->Execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}	  
				  
			/* build query */
			$q = "SELECT count(*) as count FROM ".PRFX."TABLE_SCHEDULE WHERE WORK_ORDER_ID='".$wo_id."'";
			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;
			}
			
			$count = $rs->fields['count'];
			
			if($count != 0) {
				$sql = "UPDATE ".PRFX."TABLE_SCHEDULE SET ";
				$where = " WHERE WORK_ORDER_ID='".$wo_id."'";
			} else {
				$sql = "INSERT INTO ".PRFX."TABLE_SCHEDULE SET ";
			}
		} else {
			$sql = "INSERT INTO ".PRFX."TABLE_SCHEDULE SET ";
		}	
		  
		
		$sql .="SCHEDULE_START	= '".$start_time."',
				 SCHEDULE_END		= '".$end_time."',
				 WORK_ORDER_ID		= '".$VAR['wo_id']."',
				 EMPLOYEE_ID			= '".$VAR['tech']."',
				 SCHEDULE_NOTES		= '".$VAR['schedule_notes']."'
				" .$where;
	
		if(!$rs = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		 
		return true;


	}
	
######################################
# View New schedule  				 #
######################################
	function view_schedule($db, $sch_id) {
	
		$q = "SELECT ".PRFX."TABLE_SCHEDULE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_SCHEDULE 
				LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_SCHEDULE.EMPLOYEE_ID=".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID )
				WHERE SCHEDULE_ID='".$sch_id."'";
		
		if(!$rs = $db->Execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
		
		$arr = $rs->GetAll();	
		return $arr;

	}
	
######################################
# Tech List  						 #
######################################	
function display_tech($db){
	$sql = "SELECT  EMPLOYEE_ID, EMPLOYEE_TYPE, EMPLOYEE_LOGIN FROM ".PRFX."TABLE_EMPLOYEE"; 
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	$tech_array = $result->GetArray();
	return $tech_array;
}
#####################################
# Display all open Work orders to 	#
#####################################

function display_workorders($db, $page_no, $where){
	global $smarty;
	$max_results = 5;
	$from = (($page_no * $max_results) - $max_results);
 
	$results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS =".$db->qstr($status));
	$total_results = $results->FetchRow();
	
	$total_pages = ceil($total_results["Num"] / $max_results);
	
	if($page_no > 1){
    	$prev = ($page_no - 1);
    	$smarty->assign("previous", $prev);
	} 

	if($page_no < $total_pages){
    	$next = ($page_no + 1);
    	$smarty->assign("next", $next);
	} 
	
	
	$sql = "SELECT 
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID, 
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_OPEN_DATE,
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO,
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_SCOPE,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_ID,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_DISPLAY_NAME,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_ADDRESS,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_CITY,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_STATE,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_ZIP,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_PHONE,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_WORK_PHONE,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_MOBILE_PHONE,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_EMAIL,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_TYPE,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_FIRST_NAME,
			".PRFX."TABLE_CUSTOMER.CUSTOMER_LAST_NAME,
			".PRFX."TABLE_CUSTOMER.DISCOUNT,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_WORK_PHONE,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_HOME_PHONE,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_MOBILE_PHONE,
			".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS
			FROM ".PRFX."TABLE_WORK_ORDER
			LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID 				= ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO 	= ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
			LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID
			".$where." GROUP BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID";
	 
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	$workorders_array = $result->GetArray();
	if(empty($workorders_array)) {
		return false;
	} else {
		return $workorders_array;
	}
}
?>
