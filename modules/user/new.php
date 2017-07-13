<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/user.php');

// If user data has been submitted
if(isset($VAR['submit'])) {   
            
    // Insert the record if the username does not exist or reload the page for correction
    if (check_user_username_exists($db, $VAR['username'])) {
        
        // Change the $VAR array into the correct format for a smarty loop
        $user_details = array();
        $user_details[] = $VAR;         
        
        // Reload the page with the POST'ed data
        $smarty->assign('user_details', $user_details); 
        
        $BuildPage .= $smarty->fetch('user/new.tpl');
            
        } else {    
            
            // Insert user record (and return the new ID)
            $user_id = insert_user($db, $VAR);
            
            // Redirect to the new user's details page
            force_page('user', 'details&user_id='.$user_id);
            exit;
            
        }

// Load a blank new user form        
} else {
    
    // Empty placeholder nested arrays needed for the smarty loop to keep the page working (this is intentionally empty)
    $smarty->assign('user_details', array(array()));  
    
    // Fetch the page from the database
    $smarty->assign('usergroups', get_usergroups($db, 'employees'));    
    $BuildPage .= $smarty->fetch('user/new.tpl');
    
} 