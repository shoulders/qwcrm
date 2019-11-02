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
    $this->app->system->page->force_page('client', 'search');
    exit;
}

// Insert the client note
if(isset(\CMSApplication::$VAR['submit'])) {   
    
    $this->app->components->client->insert_client_note(\CMSApplication::$VAR['client_id'], \CMSApplication::$VAR['note']); 
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Client note created."));
    $this->app->system->page->force_page('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);    

} else {  

}

