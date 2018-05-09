<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/user.php');

// Check if we have an user_id
if($user_id == '') {
    force_page('user', 'search', 'warning_msg='._gettext("No User ID supplied."));
    exit;
}

// Set the template for the correct user type (customer/employee)
if(get_user_details($db, $user_id, 'is_employee')) {
    $smarty->assign('is_employee', '1');
    $smarty->assign('usergroups', get_usergroups($db, 'employees'));
} else {    
    $smarty->assign('is_employee', '0');
    $smarty->assign('customer_display_name', get_customer_details($db, get_user_details($db, $user_id, 'customer_id'), 'customer_display_name'));
    $smarty->assign('usergroups', get_usergroups($db, 'customers')); 
}

// If user data has been submitted
if(isset($VAR['submit'])) {

    // Update the record - if the username or email have not been used
    if (check_user_username_exists($db, $VAR['username'], get_user_details($db, $user_id, 'username')) ||
        check_user_email_exists($db, $VAR['email'], get_user_details($db, $user_id, 'email'))) {        

        // Send the posted data back to smarty
        $user_details = $VAR;
        
        // Reload the page with the POST'ed data        
        $smarty->assign('user_details', $user_details);         
        $BuildPage .= $smarty->fetch('user/edit.tpl');        
        
    } else {    
            
        // Insert user record
        update_user($db, $user_id, $VAR);

        // Redirect to the new users's details page
        force_page('user', 'details&user_id='.$user_id);
        exit;
            
    }

} else { 
    
    // Build the page from the database    
    $smarty->assign('user_details', get_user_details($db, $user_id));    
    $BuildPage .= $smarty->fetch('user/edit.tpl');
    
}