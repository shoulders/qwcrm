<?php
#########################################################
#  This program is distributed under the terms and 		#
#  conditions of the GPL								#
#  Employee Functions									#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#														#
#########################################################
if(!xml2php("employees")) {
	$smarty->assign('error_msg',"Error in language file");
}

#####################################
#	Display Employee Info			#
#####################################

function display_employee_info($db, $employee_id) {

	$q = "SELECT ".PRFX."TABLE_EMPLOYEE.*, ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_NAME  FROM ".PRFX."TABLE_EMPLOYEE
			LEFT JOIN ".PRFX."CONFIG_EMPLOYEE_TYPE ON (".PRFX."TABLE_EMPLOYEE. EMPLOYEE_TYPE = ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_ID)
		   WHERE EMPLOYEE_ID=". $db->qstr($employee_id);
	
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$employee_array = $rs->GetArray();
	}

	return $employee_array;
	
}

#####################################
#	Search							#
#####################################

function display_employee_search($db, $name, $page_no) {

    $safe_name = strip_tags($name);
	
	global $smarty;
	// Define the number of results per page
	$max_results = 50;
	// Figure out the limit for the query based
	// on the current page number.
	$from = (($page_no * $max_results) - $max_results);
	
	
	$q = "SELECT ".PRFX."TABLE_EMPLOYEE.*,".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_NAME FROM ".PRFX."TABLE_EMPLOYEE 
			LEFT JOIN ".PRFX."CONFIG_EMPLOYEE_TYPE ON (".PRFX."TABLE_EMPLOYEE. EMPLOYEE_TYPE = ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_ID)	
			WHERE EMPLOYEE_DISPLAY_NAME LIKE '%$safe_name%' ORDER BY EMPLOYEE_DISPLAY_NAME";
	
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$employee_search_result = $rs->GetArray();
	}
	

	// Figure out the total number of results in DB: 
	$q = "SELECT COUNT(*) as Num FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME LIKE '$safe_name%'";
	
	if(!$results = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$total_results = $results->FetchRow();
		$smarty->assign('total_results', strip_tags($total_results['Num']));
	}
	
	// Figure out the total number of pages. Always round up using ceil()
	$total_pages = ceil($total_results["Num"] / $max_results); 
	
	// Figure out the total number of pages. Always round up using ceil()
	$total_pages = ceil($total_results["Num"] / $max_results); 
	$smarty->assign('total_pages', strip_tags($total_pages));
	
	// Assign the first page
	if($page_no > 1) {
    	$prev = ($page_no - 1);
    	 
	} 	

	// Build Next Link
	if($page_no < $total_pages){
    	$next = ($page_no + 1); 
	}
	
	$smarty->assign('name', strip_tags($name));
	$smarty->assign('page_no', strip_tags($page_no));
	$smarty->assign("previous", strip_tags($prev));
	$smarty->assign("next", strip_tags($next));

	return $employee_search_result;
}

#####################################
#	Display Open Work Orders		#
#####################################

function display_open_workorders($db, $employee_id) {
	$sql = "SELECT ".PRFX."TABLE_WORK_ORDER.*, ".PRFX."TABLE_CUSTOMER.*, ".PRFX."CONFIG_WORK_ORDER_STATUS.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME	
			FROM ".PRFX."TABLE_WORK_ORDER 
				LEFT JOIN ".PRFX."TABLE_CUSTOMER ON           (".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID)
				LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON (".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID)
				LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON           (".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CREATE_BY = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO=".$db->qstr($employee_id)." 
			AND WORK_ORDER_STATUS ='10' ";

	if(!$rs = $db->Execute($sql)) {
		echo "MySQL Error: ".$db->ErrorMsg();
	}

	$open_work_orders = $rs->GetArray();
	return $open_work_orders;
}

#####################################
#	Check For Employee				#
#####################################

function check_employee_ex($db,$VAR){
	$q = "SELECT COUNT(*) AS num_users FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME =". $db->qstr($VAR['displayName']);
	$result = $db->Execute($q);

	if ($rs->fields['num_users'] == 1) {
		return false;
	} else {
		return true;
	}
}


	
// A function for comparing password
    function cmpPass($element, $confirmPass) {
        global $form;
        $password = $form->getElementValue('password');
        return ($password == $confirmPass);
    }

    // A function to encrypt the password
    function encryptValue($value) {
        return md5($value);
    }

#####################################
#	Check For Employee				#
#####################################

function insert_new_employee($db,$VAR){

	$password = encryptValue($VAR["password"]);
	// $login    = strtolower($VAR["firstName"]{0}).strtolower($VAR["lastName"]);
	
	$q = "INSERT INTO ".PRFX."TABLE_EMPLOYEE SET
		  EMPLOYEE_PASSWD			= ". $db->qstr( $password           ).",
		  EMPLOYEE_DISPLAY_NAME	= ". $db->qstr( $VAR["displayName"] ).",
		  EMPLOYEE_ADDRESS			= ". $db->qstr( $VAR["address"]     ).", 
		  EMPLOYEE_CITY				= ". $db->qstr( $VAR["city"]        ).", 
		  EMPLOYEE_STATE			= ". $db->qstr( $VAR["state"]       ).", 
		  EMPLOYEE_ZIP				= ". $db->qstr( $VAR["zip"]         ).",
		  EMPLOYEE_HOME_PHONE		= ". $db->qstr( $VAR["homePhone"]   ).",
		  EMPLOYEE_WORK_PHONE		= ". $db->qstr( $VAR["workPhone"]   ).",
		  EMPLOYEE_MOBILE_PHONE	= ". $db->qstr( $VAR["mobilePhone"] ).",
		  EMPLOYEE_EMAIL			= ". $db->qstr( $VAR["email"]       ).",
		  EMPLOYEE_FIRST_NAME		= ". $db->qstr( $VAR["firstName"]   ).", 
		  EMPLOYEE_LAST_NAME		= ". $db->qstr( $VAR["lastName"]    ).",
		  EMPLOYEE_TYPE				= ". $db->qstr( $VAR["type"]        ).",
		  EMPLOYEE_BASED				= ". $db->qstr( $VAR["based"]        ).",
		  EMPLOYEE_STATUS			= ". $db->qstr( 1              	 ).",
		  EMPLOYEE_LOGIN			= ". $db->qstr( $VAR["login_id"]         );
		  
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$employee_id = $db->insert_id();
		return $employee_id;
	}
}

##################################
# Get Employee Type  		#
##################################
function employee_type($db) {
	$q = "SELECT * FROM ".PRFX."CONFIG_EMPLOYEE_TYPE";
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$arr = $rs->GetArray();
		return $arr;
	}
}

?>
