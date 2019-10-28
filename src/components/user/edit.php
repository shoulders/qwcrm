<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an user_id
if(!isset(\CMSApplication::$VAR['user_id']) || !\CMSApplication::$VAR['user_id']) {
    systemMessagesWrite('danger', _gettext("No User ID supplied."));
    force_page('user', 'search');
}

// If user data has been submitted, Update the record
if(isset(\CMSApplication::$VAR['submit'])) {

    // Check if the username or email have been used (the extra vareiable is to ignore the users current username and email to prevent submission errors when only updating other values)
    if (
            check_user_username_exists(\CMSApplication::$VAR['qform']['username'], get_user_details(\CMSApplication::$VAR['qform']['user_id'], 'username')) ||
            check_user_email_exists(\CMSApplication::$VAR['qform']['email'], get_user_details(\CMSApplication::$VAR['qform']['user_id'], 'email'))
        ) {

        // Reload the page with the POST'ed data        
        $smarty->assign('user_details', \CMSApplication::$VAR['qform']);               
        
    } else {    
            
        // Insert user record
        update_user(\CMSApplication::$VAR['qform']);

        // Redirect to the new users's details page
        systemMessagesWrite('success', _gettext("User details updated."));
        force_page('user', 'details&user_id='.\CMSApplication::$VAR['qform']['user_id']);
            
    }

} else { 
  
    $smarty->assign('user_details', get_user_details(\CMSApplication::$VAR['user_id']));     
    
}

// Set the template for the correct user type (client/employee)
if(get_user_details(\CMSApplication::$VAR['user_id'], 'is_employee')) {
    $smarty->assign('is_employee', '1');
    $smarty->assign('usergroups', get_usergroups('employees'));
} else {    
    $smarty->assign('is_employee', '0');
    $smarty->assign('client_display_name', get_client_details(get_user_details(\CMSApplication::$VAR['user_id'], 'client_id'), 'client_display_name'));
    $smarty->assign('usergroups', get_usergroups('clients')); 
}

// Build the page
$smarty->assign('user_locations', get_user_locations());