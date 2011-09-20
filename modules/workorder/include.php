<?php
#####################################
# Display a single open work order  #
#####################################

function display_single_open_workorder($db, $wo_id){
	 $sql = "SELECT ".PRFX."TABLE_WORK_ORDER.*,
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
                        ".PRFX."TABLE_CUSTOMER.CUSTOMER_WWW,
			".PRFX."TABLE_CUSTOMER.DISCOUNT,
                        ".PRFX."TABLE_CUSTOMER.CREDIT_TERMS,
                        ".PRFX."TABLE_CUSTOMER.CUSTOMER_NOTES,
                        ".PRFX."TABLE_CUSTOMER.CREATE_DATE,
                        ".PRFX."TABLE_CUSTOMER.LAST_ACTIVE,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_WORK_PHONE,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_HOME_PHONE,
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_MOBILE_PHONE,
			".PRFX."TABLE_SCHEDULE.SCHEDULE_START,
			".PRFX."TABLE_SCHEDULE.SCHEDULE_END,
			".PRFX."TABLE_SCHEDULE.SCHEDULE_NOTES
			 FROM ".PRFX."TABLE_WORK_ORDER
			 LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID 		= ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
			 LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO 	= ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
			 LEFT JOIN ".PRFX."TABLE_SCHEDULE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID 		= ".PRFX."TABLE_SCHEDULE.WORK_ORDER_ID
			 WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID =".$db->qstr($wo_id)." LIMIT 1";

	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$single_workorder_array = $result->GetArray();
		if(empty($single_workorder_array)) {
			return false;
		} else {
			return $single_workorder_array;
		}
	}
}

#####################################
# Display all open Work orders to   #
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
			LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID 		= ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO 	= ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
			LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID
			".$where." GROUP BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID ORDER BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID DESC";
	 
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

#############################
# Display Work Order Notes  #
#############################
function display_workorder_notes($db, $wo_id){
	$sql = "SELECT ".PRFX."TABLE_WORK_ORDER_NOTES.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_WORK_ORDER_NOTES, ".PRFX."TABLE_EMPLOYEE WHERE WORK_ORDER_ID=".$db->qstr($wo_id)." AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_NOTES.WORK_ORDER_NOTES_ENTER_BY ";
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$work_order_notes = $result->GetArray();
	return $work_order_notes;
	
}

#############################
# Display Work Order Status #
#############################
function display_workorder_status($db, $wo_id){
	$sql = "SELECT ".PRFX."TABLE_WORK_ORDER_STATUS.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
				FROM ".PRFX."TABLE_WORK_ORDER_STATUS, ".PRFX."TABLE_EMPLOYEE 
				WHERE  ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_ID=".$db->qstr($wo_id)." 
				AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_STATUS_ENTER_BY ORDER BY ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_STATUS_ID";
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	$work_order_status = $result->GetArray();
	return $work_order_status;
	
}
$smarty->assign('wo_stat', display_workorder_status($db, $wo_id));

#########################################
# Display Customer Contact Information	#
#########################################
function display_customer_info($db, $customer_id){
	$sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	$customer_array = $result->GetArray();
	return $customer_array;
}

#############################################################
# Display all open Work orders to                           #
#############################################################
function display_tech($db){
	$sql = "SELECT  EMPLOYEE_ID, EMPLOYEE_LOGIN FROM ".PRFX."TABLE_EMPLOYEE"; 
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	while($row = $result->FetchRow())
	{
		$id = $row["EMPLOYEE_ID"];
		$tech = $row["EMPLOYEE_LOGIN"];
		
		$tech_array[$id]=$tech;
	}
	return $tech_array;
}

#############################################################
# Insert New Work Order                                     #
#############################################################
function insert_new_workorder($db,$VAR){
	global $smarty;

//Remove Extra Slashes caused by Magic Quotes
$work_order_description_string = $VAR['work_order_description'];
$work_order_description_string = stripslashes($work_order_description_string);

$work_order_comments_string = $VAR['work_order_comments'];
$work_order_comments_string = stripslashes($work_order_comments_string);


	$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER  SET 
			CUSTOMER_ID			=".$db->qstr($VAR["customer_ID"]            ).",
			WORK_ORDER_OPEN_DATE		=".$db->qstr(time()                         ).",
			WORK_ORDER_STATUS		=".$db->qstr(10                             ).",
			WORK_ORDER_CURRENT_STATUS	=".$db->qstr(1                              ).",
			WORK_ORDER_CREATE_BY		=".$db->qstr($VAR["created_by"]             ).",
			WORK_ORDER_SCOPE		=".$db->qstr($VAR["scope"]                  ).",
			WORK_ORDER_DESCRIPTION		=".$db->qstr($work_order_description_string ).",
			LAST_ACTIVE			=".$db->qstr(time()                         ).",
			WORK_ORDER_COMMENT		=".$db->qstr($work_order_comments_string    );
	   
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
		$wo_id = $db->Insert_ID();
		$VAR['wo_id'] = $wo_id;
		$VAR['work_order_status_notes'] = "Work Order Created";
		
		insert_new_status($db,$VAR);
		insert_new_note($db,$VAR);

		if(!empty($VAR['SCHEDULE_notes'])){
			insert_new_note($db,$VAR);
		}
				
		$smarty->assign('wo_id', $wo_id);
                $smarty->assign('customer_id', $customer_id);
		$smarty->display("workorder/new_results.tpl");

	
}

#############################################################
# Insert Status	note                                        #
#############################################################

function insert_new_status($db,$VAR){
	$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			  WORK_ORDER_ID				=". $db->qstr( $VAR["wo_id"]                    ).",
			  WORK_ORDER_STATUS_DATE		=". $db->qstr( time()                           ).",
			  WORK_ORDER_STATUS_NOTES		=". $db->qstr( $VAR["work_order_status_notes"]  ).",
			  WORK_ORDER_STATUS_ENTER_BY            =". $db->qstr( $_SESSION['login_id']            );
		
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	update_last_active($db,$VAR["wo_id"]);
	return true;
}

####################################################
# Update Work Order Satus                          #
####################################################

function update_status($db,$VAR){

	$sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			  WORK_ORDER_CURRENT_STATUS	=". $db->qstr( $VAR["status"] ).",
			  LAST_ACTIVE			=". $db->qstr(time())."
	  		  WHERE WORK_ORDER_ID           =". $db->qstr($VAR["wo_id"]);

	if(!$result = $db->Execute($sql)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
	}
	
	if($VAR["status"] == '1') {
		$c_status = "Created";
	} elseif ($VAR["status"] == '2') {
		$c_status = "Assigned";	
	} elseif ($VAR["status"] == '3') {
		$c_status = "Waiting For Parts";
	} elseif ($VAR["status"] == '6' ){
		$c_status = "Closed";
	} elseif ($VAR["status"] == '7') {
		$c_status = "Awaiting Payment";
	} elseif ($VAR["status"] == '8') {
		$c_status = "Payment Made";
	} elseif ($VAR["status"] == '9') {
		$c_status = "Pending";
	} elseif ($VAR["status"] == '10') {
		$c_status = "Open";	
	}

	
	$VAR['work_order_status_notes'] = "Work Order Changed status to ".$c_status;
	insert_new_status($db,$VAR);
	return true;

}

########################################
# Display Status                       #
########################################

function display_status($db){

	$sql = "SELECT * FROM ".PRFX."CONFIG_WORK_ORDER_STATUS WHERE DISPLAY='1'";
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

		while($row = $result->FetchRow()){
			$id 					= $row["CONFIG_WORK_ORDER_STATUS_ID"];
			$status 				= $row["CONFIG_WORK_ORDER_STATUS"];
			$status_array[$id]	= $status;
		}

		return $status_array; 
}

#########################################
# Display Parts				#
#########################################
function display_parts($db,$wo_id) {
	$q = "SELECT * FROM ".PRFX."ORDERS WHERE  WO_ID=".$db->qstr( $wo_id );
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	$arr = $rs->GetArray();
	return $arr;
}

##################################
# Display resolution		 #
##################################
function display_resolution($db,$wo_id) {
	$q="SELECT ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_BY, 
				  ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_RESOLUTION, 
				  ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_DATE,
				  ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID, 
                                  ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME
		FROM ".PRFX."TABLE_WORK_ORDER
		LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_BY = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
		WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID=".$db->qstr($wo_id);

	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$resolution = $rs->GetArray();
		return $resolution;
	}
}
########################################
# Add New Note                         #
########################################

function insert_new_note($db,$VAR){

//Remove Extra Slashes caused by Magic Quotes
$work_order_notes_string = $VAR['work_order_notes'];
$work_order_notes_string = stripslashes($work_order_notes_string);

	$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_NOTES SET 
		     WORK_ORDER_ID			=". $db->qstr( $VAR["wo_id"]            ).",
		     WORK_ORDER_NOTES_DESCRIPTION	=". $db->qstr( $work_order_notes_string	).",
		     WORK_ORDER_NOTES_ENTER_BY		=". $db->qstr( $_SESSION["login_id"]	).",
		     WORK_ORDER_NOTES_DATE              =". $db->qstr( time()			);
		 
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		update_last_active($db,$VAR["wo_id"]);
	}

    return true;
}

########################################
# Close Work Order                     #
########################################
function close_work_order($db,$VAR){

//Remove Extra Slashes caused by Magic Quotes
$resolution_string = $VAR['resolution'];
$resolution_string = stripslashes($resolution_string);

	/* Insert resolution and close information */
	$sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			 WORK_ORDER_STATUS          ='9',
			 WORK_ORDER_CLOSE_DATE      = ". $db->qstr( time()                  ).",
			 WORK_ORDER_RESOLUTION      = ". $db->qstr( $resolution_string      ).",
			 WORK_ORDER_CLOSE_BY        = ". $db->qstr( $_SESSION['login_id']   ).",
			 WORK_ORDER_ASSIGN_TO       = ". $db->qstr( $_SESSION['login_id']   ).",
			 WORK_ORDER_CURRENT_STATUS  ='7'
			 WHERE WORK_ORDER_ID        = ". $db->qstr( $VAR['wo_id']           );
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	/* Update status notes */
	$arr = array('wo_id'=>$VAR["wo_id"], 'work_order_status_notes'=>'Work Order has been closed and set to Awating Payment');
	 
	insert_new_status($db,$arr);
	return true;

}


########################################
# Close Work Order with no invoice     #
########################################
function close_work_order_no_invoice($db,$VAR){

//Remove Extra Slashes caused by Magic Quotes
$resolution_string = $VAR['resolution'];
$resolution_string = stripslashes($resolution_string);

	/* Insert resolution and close information */
	$sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			 WORK_ORDER_STATUS        ='6',
			 WORK_ORDER_CLOSE_DATE    = ". $db->qstr( time()                    ).",
			 WORK_ORDER_RESOLUTION    = ". $db->qstr( $resolution_string        ).",
			 WORK_ORDER_CLOSE_BY      = ". $db->qstr( $_SESSION['login_id']     ).",
			 WORK_ORDER_ASSIGN_TO      = ". $db->qstr( $_SESSION['login_id']    ).",
			 WORK_ORDER_CURRENT_STATUS ='6' 
			 WHERE WORK_ORDER_ID      = ". $db->qstr( $VAR['wo_id']             );
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	/* Update status notes */
	$arr = array('wo_id'=>$VAR["wo_id"], 'work_order_status_notes'=>'Work Order has been closed and No Invoice Required');
	 
	insert_new_status($db,$arr);
	return true;

}

###############################
# Get Work Order schedule     #
###############################
function get_work_order_schedule ($db,$wo_id){
	$sql = "SELECT * FROM ".PRFX."TABLE_SCHEDULE WHERE WORK_ORDER_ID=".$db->qstr($wo_id); 
	if(!$rs = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	$schedule = $rs->GetArray();
	return $schedule;
}

#################################
#	Update Last Active	#
#################################

function update_last_active($db,$wo_id) {
	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET LAST_ACTIVE=".$db->qstr(time())." WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
	if(!$rs = $db->execute($q)) {
	
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
}

?>
