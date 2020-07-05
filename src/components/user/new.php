<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Set the template for the correct user type (client/employee)
if(isset(\CMSApplication::$VAR['client_id']) && \CMSApplication::$VAR['client_id']) {
    
    // check if there is already a user for the client (and error if there is)
    if(!$this->app->components->user->checkClientLoginExists(\CMSApplication::$VAR['client_id'])) {
        
        $this->app->smarty->assign('is_employee', '0');
        $this->app->smarty->assign('client_display_name', $this->app->components->client->getRecord(\CMSApplication::$VAR['client_id'], 'client_display_name'));
        $this->app->smarty->assign('usergroups', $this->app->components->user->getUsergroups('clients'));
        
    } else {
        
        $this->app->system->page->forcePage('client', 'details', 'client_id='.\CMSApplication::$VAR['client_id'].'&msg_danger='._gettext("The client already has a login."));
        
    }    
    
} else {
    $this->app->smarty->assign('is_employee', '1');    
    $this->app->smarty->assign('usergroups', $this->app->components->user->getUsergroups('employees'));
}

// If user data has been submitted
if(isset(\CMSApplication::$VAR['submit'])) { 
            
    // Insert the record - if the username or email have not been used
    if ($this->app->components->user->checkUserUsernameExists(\CMSApplication::$VAR['qform']['username']) || $this->app->components->user->checkEmailExists(\CMSApplication::$VAR['qform']['email'])) {     
        
        // send the posted data back to smarty
        $user_details = \CMSApplication::$VAR['qform'];
        
        // Reload the page with the POST'ed data
        $this->app->smarty->assign('user_details', $user_details);        
            
        } else {    
            
            // Insert user record (and return the new ID)
            $user_id = $this->app->components->user->insertRecord(\CMSApplication::$VAR['qform']);
            
            // Redirect to the new user's details page
            $this->app->system->variables->systemMessagesWrite('success', _gettext("New user has been created."));
            $this->app->system->page->forcePage('user', 'details&user_id='.$user_id);
            
        }

} else {
    
    // Prevent undefined variable errors
    $this->app->smarty->assign('user_details', null);
    
}

// Build the page
$this->app->smarty->assign('user_locations', $this->app->components->user->getLocations());