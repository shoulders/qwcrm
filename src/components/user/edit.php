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
if(!isset(\QFactory::$VAR['user_id']) || !\QFactory::$VAR['user_id']) {
    force_page('user', 'search', 'warning_msg='._gettext("No User ID supplied."));
}

// If user data has been submitted, Update the record
if(isset(\QFactory::$VAR['submit'])) {

    // Check if the username or email have been used (the extra vareiable is to ignore the users current username and email to prevent submission errors when only updating other values)
    if (
            check_user_username_exists(\QFactory::$VAR['username'], get_user_details(\QFactory::$VAR['user_id'], 'username')) ||
            check_user_email_exists(\QFactory::$VAR['email'], get_user_details(\QFactory::$VAR['user_id'], 'email'))
        ) {

        // Reload the page with the POST'ed data        
        $smarty->assign('user_details', \QFactory::$VAR['qform']);               
        
    } else {    
            
        // Insert user record
        update_user(\QFactory::$VAR['qform']);

        // Redirect to the new users's details page
        force_page('user', 'details&user_id='.\QFactory::$VAR['qform']['user_id'], 'information_msg='._gettext("User details updated."));
            
    }

} else { 
  
    $smarty->assign('user_details', get_user_details(\QFactory::$VAR['qform']['user_id']));     
    
}

// Set the template for the correct user type (client/employee)
if(get_user_details(\QFactory::$VAR['user_id'], 'is_employee')) {
    $smarty->assign('is_employee', '1');
    $smarty->assign('usergroups', get_usergroups('employees'));
} else {    
    $smarty->assign('is_employee', '0');
    $smarty->assign('client_display_name', get_client_details(get_user_details(\QFactory::$VAR['user_id'], 'client_id'), 'client_display_name'));
    $smarty->assign('usergroups', get_usergroups('clients')); 
}

// Build the page
$smarty->assign('user_locations', get_user_locations());