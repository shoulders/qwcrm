<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/user.php');

// Is there an User ID supplied - prevents incorrect access
if(empty($user_id)) {    
    force_error_page($_GET['page'], 'system', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("No User ID supplied."));    
    exit;
}

// Set the template for the correct user type (customer/employee)
if(get_user_details($db, $user_id, 'is_employee')) {
    $smarty->assign('is_employee', '1');
    $smarty->assign('usergroups', get_usergroups($db, 'employees'));
} else {    
    $smarty->assign('is_employee', '0');
    $smarty->assign('customer_display_name', get_customer_details($db, get_user_details($db, $user_id, 'customer_id'), 'CUSTOMER_DISPLAY_NAME'));
    $smarty->assign('usergroups', get_usergroups($db, 'customers')); 
}


// If user data has been submitted
if(isset($VAR['submit'])) {

    // Insert the record if the username does not exist or reload the page for correction with POST'd data
    if (check_user_username_exists($db, $VAR['username'], get_user_details($db, $user_id, 'username'))) {        

        // send the posted data back to smarty
        $user_details = $VAR;
        
        // Reload the page with the POST'ed data        
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
    $smarty->assign('user_details', get_user_details($db, $user_id));
    
    $BuildPage .= $smarty->fetch('user/edit.tpl');
    
}