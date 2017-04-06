<?php

// employee type is done different in new than edit i.e. function

require(INCLUDES_DIR.'modules/employee.php');
 
if(isset($VAR['submit'])) {   
            
    // Insert the record if the username does not exist or reload the page for correction
    if (check_employee_username_exists($db, $VAR['employee_usr'])) { 
            
        // Reload the page with the POST'ed data        
        $smarty->assign('employee_usr',         $VAR['employee_usr']            );
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
        $smarty->assign('employee_status',      $VAR['employee_status']         );
        
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
        
        // Make the array correct for a smarty loop
        $employee_details= array();
        $employee_details[] = $VAR;        
        $smarty->assign('employee_details', $employee_details);
        
        
        
        $BuildPage .= $smarty->fetch('employee/new.tpl');
            
        } else {
            
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
            
            // Insert employee record (and return the new ID)
            $employee_id = insert_new_employee($db, $employee_record);
            
            // Redirect to new employee's details page
            force_page('employee', 'details&employee_id='.$employee_id);
            exit;
            
        }

} else {
    
    // Fetch the page from the database
    $BuildPage .= $smarty->fetch('employee/new.tpl');
    
}