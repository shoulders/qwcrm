<?php

require(INCLUDES_DIR.'modules/employee.php');

// If employee data has been submitted
if(isset($VAR['submit'])) {   
            
    // Insert the record if the username does not exist or reload the page for correction
    if (check_employee_username_exists($db, $VAR['employee_usr'])) {
        
        // Change the $VAR array into the correct format for a smarty loop
        $employee_details = array();
        $employee_details[] = $VAR;         
        
        // Reload the page with the POST'ed data
        $smarty->assign('employee_details', $employee_details); 
        
        $BuildPage .= $smarty->fetch('employee/new.tpl');
            
        } else {    
            
            // Insert employee record (and return the new ID)
            $employee_id = insert_new_employee($db, $VAR);
            
            // Redirect to the new employee's details page
            force_page('employee', 'details&employee_id='.$employee_id);
            exit;
            
        }

// Load a blank new employee form        
} else {
    
    // Empty placeholder nested arrays needed for the smarty loop to keep the page working (this is intentionally empty)
    $smarty->assign('employee_details', array(array()));  
    
    // Fetch the page from the database
    $smarty->assign('employee_type', get_employee_types($db));
    
    $BuildPage .= $smarty->fetch('employee/new.tpl');
    
} 