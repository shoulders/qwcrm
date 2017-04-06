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











        // Reload the page with the POST'ed data        
        /*$smarty->assign('employee_usr',         $VAR['employee_usr']            );
        $smarty->assign('employee_pwd',         $VAR['employee_pwd']            );
        $smarty->assign('employee_email',       $VAR['employee_email']          );
        $smarty->assign('employee_firstName',   $VAR['employee_firstName']      );
        $smarty->assign('employee_lastName',    $VAR['employee_lastName']       );
        $smarty->assign('employee_displayName', $VAR['employee_displayName']    );
        $smarty->assign('employee_address',     $VAR['employee_address']        );
        $smarty->assign('employee_city',        $VAR['employee_city']           );
        $smarty->assign('employee_state',       $VAR['employee_state']          );
        $smarty->assign('employee_zip',         $VAR['employee_zip']            );
        $smarty->assign('employee_type',        $VAR['employee_type']           );
        $smarty->assign('employee_workPhone',   $VAR['employee_workPhone']      );
        $smarty->assign('employee_homePhone',   $VAR['employee_homePhone']      );
        $smarty->assign('employee_mobilePhone', $VAR['employee_mobilePhone']    );
        $smarty->assign('employee_based',       $VAR['employee_based']          );
        $smarty->assign('employee_acl',         $VAR['employee_acl']            );
        $smarty->assign('employee_status',      $VAR['employee_status']         );*/
        
        /*
        // Load record into an array for processing            
        $employee_record['employee_usr']            = $VAR['employee_usr'];
        $employee_record['employee_pwd']            = $VAR['employee_pwd'];
        $employee_record['employee_email']          = $VAR['employee_email'];
        $employee_record['employee_firstName']      = $VAR['employee_firstName'];            
        $employee_record['employee_lastName']       = $VAR['employee_lastName'];    
        $employee_record['employee_displayName']    = $VAR['employee_displayName'];    
        $employee_record['employee_address']        = $VAR['employee_address'];   
        $employee_record['employee_city']           = $VAR['employee_city'];   
        $employee_record['employee_state']          = $VAR['employee_state'];
        $employee_record['employee_zip']            = $VAR['employee_zip'];            
        $employee_record['employee_type']           = $VAR['employee_type'];            
        $employee_record['employee_workPhone']      = $VAR['employee_workPhone'];            
        $employee_record['employee_homePhone']      = $VAR['employee_homePhone'];
        $employee_record['employee_mobilePhone']    = $VAR['employee_mobilePhone'];            
        $employee_record['employee_based']          = $VAR['employee_based'];            
        $employee_record['employee_acl']            = $VAR['employee_acl'];            
        $employee_record['employee_status']         = $VAR['employee_status']; 
        
        // Make the array correct for a smarty loop
        $employee_details= array();
        $employee_details[] = $employee_record;        
        
        //$smarty->assign('employee_details', display_single_employee($db, $employee_id));
        */

            
            // Load record into an array for processing            
            /*$employee_record['employee_usr']            = $VAR['employee_usr'];
            $employee_record['employee_pwd']            = $VAR['employee_pwd'];
            $employee_record['employee_email']          = $VAR['employee_email'];
            $employee_record['employee_firstName']      = $VAR['employee_firstName'];            
            $employee_record['employee_lastName']       = $VAR['employee_lastName'];    
            $employee_record['employee_displayName']    = $VAR['employee_displayName'];    
            $employee_record['employee_address']        = $VAR['employee_address'];   
            $employee_record['employee_city']           = $VAR['employee_city'];   
            $employee_record['employee_state']          = $VAR['employee_state'];
            $employee_record['employee_zip']            = $VAR['employee_zip'];            
            $employee_record['employee_type']           = $VAR['employee_type'];            
            $employee_record['employee_workPhone']      = $VAR['employee_workPhone'];            
            $employee_record['employee_homePhone']      = $VAR['employee_homePhone'];
            $employee_record['employee_mobilePhone']    = $VAR['employee_mobilePhone'];            
            $employee_record['employee_based']          = $VAR['employee_based'];            
            $employee_record['employee_acl']            = $VAR['employee_acl'];            
            $employee_record['employee_status']         = $VAR['employee_status'];*/      