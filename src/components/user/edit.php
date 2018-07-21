<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'user.php');

// Check if we have an user_id
if(!isset($VAR['user_id']) || !$VAR['user_id']) {
    force_page('user', 'search', 'warning_msg='._gettext("No User ID supplied."));
}

// Set the template for the correct user type (client/employee)
if(get_user_details($VAR['user_id'], 'is_employee')) {
    $smarty->assign('is_employee', '1');
    $smarty->assign('usergroups', get_usergroups('employees'));
} else {    
    $smarty->assign('is_employee', '0');
    $smarty->assign('client_display_name', get_client_details(get_user_details($VAR['user_id'], 'client_id'), 'client_display_name'));
    $smarty->assign('usergroups', get_usergroups('clients')); 
}

// If user data has been submitted
if(isset($VAR['submit'])) {

    // Update the record - if the username or email have not been used
    if (check_user_username_exists($VAR['username'], get_user_details($VAR['user_id'], 'username')) ||
        check_user_email_exists($VAR['email'], get_user_details($VAR['user_id'], 'email'))) {

        // Send the posted data back to smarty
        $user_details = $VAR;
        
        // Reload the page with the POST'ed data        
        $smarty->assign('user_details', $user_details);               
        
    } else {    
            
        // Insert user record
        update_user($VAR);

        // Redirect to the new users's details page
        force_page('user', 'details&user_id='.$VAR['user_id']);
            
    }

} else { 
    
    // Build the page from the database    
    $smarty->assign('user_details', get_user_details($VAR['user_id']));     
    
}

// Build the page
$smarty->assign('user_locations', get_user_locations());
$BuildPage .= $smarty->fetch('user/edit.tpl');