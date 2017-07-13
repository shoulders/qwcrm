<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/user.php');

// Is there an User ID supplied - prevents incorrect access
if(empty($user_id)) {    
    force_error_page($_GET['page'], 'system', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("No User ID supplied."));    
    exit;
}

// If user data has been submitted
if(isset($VAR['submit'])) {

    // Insert the record if the username does not exist or reload the page for correction with POST'ed data
    if (check_user_username_exists($db, $VAR['username'], get_user_details($db, $user_id, 'username'))) {        

        // Change the $VAR array into the correct format for a smarty loop
        $user_details = array();
        $user_details[] = $VAR;
        
        // Reload the page with the POST'ed data
        $smarty->assign('usergroups', get_usergroups($db, 'employees'));
        $smarty->assign('user_details', $user_details); 
        
        $BuildPage .= $smarty->fetch('user/new.tpl');        
        
    } else {    
            
        // Insert user record
        update_user($db, $user_id, $VAR);

        // Redirect to the new users's details page
        force_page('user', 'details&user_id='.$user_id);
        exit;
            
    }

// Load the requested User's details and display
} else { 
    
    // Fetch the page from the database   
    $smarty->assign('usergroups', get_usergroups($db, 'employees'));
    $smarty->assign('user_details', get_user_details($db, $user_id));
    
    $BuildPage .= $smarty->fetch('user/edit.tpl');
    
}