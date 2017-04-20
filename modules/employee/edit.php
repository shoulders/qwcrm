<?php

// employee type is done different in new than edit i.e. function

require(INCLUDES_DIR.'modules/employee.php');

// Is there an employee ID supplied - prevents incorrect access
if(empty($employee_id)) {
    force_page('core', 'error&error_msg=No Employee ID');
    exit;
}

// If employee data has been submitted
if(isset($VAR['submit'])) {
            
    // Insert the record if the username does not exist or reload the page for correction
    if (check_employee_username_exists($db, $VAR['employee_usr'], get_employee_details($db, $employee_id, 'EMPLOYEE_LOGIN'))) {        

        // Make the $VAR array have the correct format for a smarty loop and assign
        $employee_details = array();
        $employee_details[] = $VAR;        
        
        // Reload the page with the POST'ed data
        $smarty->assign('employee_type', get_employee_types($db));
        $smarty->assign('employee_details', $employee_details); 
        
        $BuildPage .= $smarty->fetch('employee/new.tpl');
            
        } else {    
            
            // Insert employee record
            update_employee($db, $employee_id, $VAR);
            
            // Redirect to the new employee's details page
            force_page('employee', 'details&employee_id='.$employee_id);
            exit;
            
        }

// Load the requested Employee's details and display        
} else {    
    
    // Load employee's details into $VAR
    $employee_record = get_employee_details($db, $employee_id);    
    $VAR['employee_id']             = $employee_id;
    $VAR['employee_usr']            = $employee_record['0']['EMPLOYEE_LOGIN'];
    $VAR['employee_pwd']            = $employee_record['0']['EMPLOYEE_PASSWD'];
    $VAR['employee_email']          = $employee_record['0']['EMPLOYEE_EMAIL'];
    $VAR['employee_firstName']      = $employee_record['0']['EMPLOYEE_FIRST_NAME'];         
    $VAR['employee_lastName']       = $employee_record['0']['EMPLOYEE_LAST_NAME'];
    $VAR['employee_displayName']    = $employee_record['0']['EMPLOYEE_DISPLAY_NAME']; 
    $VAR['employee_address']        = $employee_record['0']['EMPLOYEE_ADDRESS'];
    $VAR['employee_city']           = $employee_record['0']['EMPLOYEE_CITY'];
    $VAR['employee_state']          = $employee_record['0']['EMPLOYEE_STATE'];
    $VAR['employee_zip']            = $employee_record['0']['EMPLOYEE_ZIP'];      
    $VAR['employee_type']           = $employee_record['0']['EMPLOYEE_TYPE'];         
    $VAR['employee_workPhone']      = $employee_record['0']['EMPLOYEE_WORK_PHONE'];         
    $VAR['employee_homePhone']      = $employee_record['0']['EMPLOYEE_HOME_PHONE'];
    $VAR['employee_mobilePhone']    = $employee_record['0']['EMPLOYEE_MOBILE_PHONE'];          
    $VAR['employee_based']          = $employee_record['0']['EMPLOYEE_BASED'];      
    $VAR['employee_acl']            = $employee_record['0']['EMPLOYEE_ACL'];      
    $VAR['employee_status']         = $employee_record['0']['EMPLOYEE_STATUS'];
    
    // Change the $VAR array into the correct format for a smarty loop
    $employee_details = array();
    $employee_details[] = $VAR;
    
    // Fetch the page from the database   
    $smarty->assign('employee_type', get_employee_types($db));
    $smarty->assign('employee_details', $employee_details);
    $BuildPage .= $smarty->fetch('employee/edit.tpl');
    
}