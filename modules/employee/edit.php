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

        // Load the incorrect varibles for editing        
        $employee_details['EMPLOYEE_LOGIN']         = $VAR['employee_usr'];
        $employee_details['EMPLOYEE_HASH']        = $VAR['employee_pwd'];
        $employee_details['EMPLOYEE_EMAIL']         = $VAR['employee_email'];
        $employee_details['EMPLOYEE_FIRST_NAME']    = $VAR['employee_firstName'];      
        $employee_details['EMPLOYEE_LAST_NAME']     = $VAR['employee_lastName'];
        $employee_details['EMPLOYEE_DISPLAY_NAME']  = $VAR['employee_displayName'];
        $employee_details['EMPLOYEE_ADDRESS']       = $VAR['employee_address'];
        $employee_details['EMPLOYEE_CITY']          = $VAR['employee_city'];
        $employee_details['EMPLOYEE_STATE']         = $VAR['employee_state'];
        $employee_details['EMPLOYEE_ZIP']           = $VAR['employee_zip'];   
        $employee_details['EMPLOYEE_TYPE']          = $VAR['employee_type'];       
        $employee_details['EMPLOYEE_WORK_PHONE']    = $VAR['employee_workPhone'];       
        $employee_details['EMPLOYEE_HOME_PHONE']    = $VAR['employee_homePhone'];
        $employee_details['EMPLOYEE_MOBILE_PHONE']  = $VAR['employee_mobilePhone'];        
        $employee_details['EMPLOYEE_BASED']         = $VAR['employee_based'];   
        $employee_details['EMPLOYEE_ACL']           = $VAR['employee_acl'];      
        $employee_details['EMPLOYEE_STATUS']        = $VAR['employee_status'];
        
        // Reload the page with the POST'ed data
        $smarty->assign('employee_type', get_employee_types($db));
        $smarty->assign('employee_details', $employee_details); 
        
        $BuildPage .= $smarty->fetch('employee/new.tpl');        
        
    } else {    
            
            // Insert employee record
            update_employee($db, $auth, $employee_id, $VAR);
            
            // Redirect to the new employee's details page
            force_page('employee', 'details&employee_id='.$employee_id);
            exit;
            
        }

// Load the requested Employee's details and display
} else { 
    
    // Fetch the page from the database   
    $smarty->assign('employee_type', get_employee_types($db));
    $smarty->assign('employee_details', get_employee_details($db, $employee_id));
    
    $BuildPage .= $smarty->fetch('employee/edit.tpl');
    
}