<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    $this->app->system->general->force_page('client', 'search');
}

if(isset(\CMSApplication::$VAR['submit'])) {    
        
    // Update the Client's Details
    $this->app->components->client->update_client(\CMSApplication::$VAR['qform']);
    
    // Load the client's details page
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The Client's information was updated."));
    $this->app->system->general->force_page('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);

} else {    

    // Build the page
    $this->app->smarty->assign('client_types',   $this->app->components->client->get_client_types());
    $this->app->smarty->assign('client_details', $this->app->components->client->get_client_details(\CMSApplication::$VAR['client_id']));
    
}