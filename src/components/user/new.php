<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'user.php');

// Set the template for the correct user type (client/employee)
if(isset(\CMSApplication::$VAR['client_id']) && \CMSApplication::$VAR['client_id']) {
    
    // check if there is already a user for the client (and error if there is)
    if(!check_client_already_has_login(\CMSApplication::$VAR['client_id'])) {
        
        $smarty->assign('is_employee', '0');
        $smarty->assign('client_display_name', get_client_details(\CMSApplication::$VAR['client_id'], 'client_display_name'));
        $smarty->assign('usergroups', get_usergroups('clients'));
        
    } else {
        
        force_page('client', 'details', 'client_id='.\CMSApplication::$VAR['client_id'].'&msg_danger='._gettext("The client already has a login."));
        
    }    
    
} else {
    $smarty->assign('is_employee', '1');    
    $smarty->assign('usergroups', get_usergroups('employees'));
}

// If user data has been submitted
if(isset(\CMSApplication::$VAR['submit'])) { 
            
    // Insert the record - if the username or email have not been used
    if (check_user_username_exists(\CMSApplication::$VAR['qform']['username']) || check_user_email_exists(\CMSApplication::$VAR['qform']['email'])) {     
        
        // send the posted data back to smarty
        $user_details = \CMSApplication::$VAR['qform'];
        
        // Reload the page with the POST'ed data
        $smarty->assign('user_details', $user_details);        
            
        } else {    
            
            // Insert user record (and return the new ID)
            \CMSApplication::$VAR['user_id'] = insert_user(\CMSApplication::$VAR['qform']);
            
            // Redirect to the new user's details page
            systemMessagesWrite('success', _gettext("New user has been created."));
            force_page('user', 'details&user_id='.\CMSApplication::$VAR['qform']['user_id']);
            
        }

} else {
    
    // Prevent undefined variable errors
    $smarty->assign('user_details', null);
    
}

// Build the page
$smarty->assign('user_locations', get_user_locations());