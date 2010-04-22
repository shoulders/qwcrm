<?php
require_once("include.php");
if(!xml2php("employees")) {
	$smarty->assign('error_msg',"Error in language file");
}


if(isset($VAR['submit']) ) {
	/* check if we have an ID */
	if(!isset($VAR['employee_id'])) {
		force_page('core', 'error&error_msg=No Employee ID');	
	}

	/* if we are changing password update */
	if($VAR['password'] != '' || $VAR['login_id'] != '') {
		$update = "SET EMPLOYEE_PASSWD  		=". $db->qstr( md5($VAR['password']) ).",
							EMPLOYEE_EMAIL			=". $db->qstr( $VAR['email']         ).", 
							EMPLOYEE_FIRST_NAME		=". $db->qstr( $VAR['firstName']     ).",
							EMPLOYEE_LAST_NAME		=". $db->qstr( $VAR['lastName']      ).",
							EMPLOYEE_DISPLAY_NAME =". $db->qstr( $VAR['displayName']   ).",
                                                        EMPLOYEE_LOGIN =". $db->qstr( $VAR['login_id']   ).",
							EMPLOYEE_SSN				=". $db->qstr( $VAR['']              ).",
							EMPLOYEE_ADDRESS		=". $db->qstr( $VAR['address']       ).",
							EMPLOYEE_CITY			=". $db->qstr( $VAR['city']          ).",
							EMPLOYEE_STATE			=". $db->qstr( $VAR['state']         ).", 
							EMPLOYEE_ZIP 			=". $db->qstr( $VAR['zip']           ).",
							EMPLOYEE_TYPE			=". $db->qstr( $VAR['type']          ).",
							EMPLOYEE_BASED			=". $db->qstr( $VAR['based']          ).",
							EMPLOYEE_WORK_PHONE	=". $db->qstr( $VAR['workPhone']     ).",
							EMPLOYEE_HOME_PHONE 	=". $db->qstr( $VAR['homePhone']     ).",
							EMPLOYEE_MOBILE_PHONE	=". $db->qstr( $VAR['mobilePhone']   ).",
							EMPLOYEE_STATUS			=". $db->qstr( $VAR['active']        ); 
	} else {
		$update ="		SET
							EMPLOYEE_EMAIL			=". $db->qstr( $VAR['email']         ).",
							EMPLOYEE_FIRST_NAME		=". $db->qstr( $VAR['firstName']     ).",
							EMPLOYEE_LAST_NAME		=". $db->qstr( $VAR['lastName']      ).",
							EMPLOYEE_DISPLAY_NAME =". $db->qstr( $VAR['displayName']   ).",                                                        
							EMPLOYEE_SSN				=". $db->qstr( $VAR['']              ).",
							EMPLOYEE_ADDRESS		=". $db->qstr( $VAR['address']       ).",
							EMPLOYEE_CITY			=". $db->qstr( $VAR['city']          ).",
							EMPLOYEE_STATE			=". $db->qstr( $VAR['state']         ).", 
							EMPLOYEE_ZIP 			=". $db->qstr( $VAR['zip']           ).",
							EMPLOYEE_TYPE			=". $db->qstr( $VAR['type']          ).",
							EMPLOYEE_BASED			=". $db->qstr( $VAR['based']          ).",							
							EMPLOYEE_WORK_PHONE	=". $db->qstr( $VAR['workPhone']     ).",
							EMPLOYEE_HOME_PHONE 	=". $db->qstr( $VAR['homePhone']     ).",
							EMPLOYEE_MOBILE_PHONE	=". $db->qstr( $VAR['mobilePhone']   ).",
							EMPLOYEE_STATUS			=". $db->qstr( $VAR['active']        ); 
	}

	$q = "UPDATE ".PRFX."TABLE_EMPLOYEE ". $update ."
			WHERE  EMPLOYEE_ID= ".$db->qstr($VAR['employee_id']);

	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=Error updateing Employee Information');	
	}

	force_page('employees', 'employee_details&employee_id='.$VAR['employee_id'].'&page_title=Employees');	

} else {
	$smarty->assign('employee_type', employee_type($db));
	$smarty->assign('employee_details', display_employee_info($db, $VAR['employee_id']));
	$smarty->display('employees'.SEP.'edit.tpl');
}
?>