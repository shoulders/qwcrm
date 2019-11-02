<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// check if we have a client_note_id
if(!isset(\CMSApplication::$VAR['client_note_id']) || !\CMSApplication::$VAR['client_note_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client Note ID supplied."));
    $this->app->system->page->force_page('client', 'search');
}

// If record submitted for updating
if(isset(\CMSApplication::$VAR['submit'])) {
               
    $this->app->components->client->update_client_note(\CMSApplication::$VAR['client_note_id'], \CMSApplication::$VAR['note']);
    $this->app->system->page->force_page('client', 'details&client_id='.$this->app->components->client->get_client_note_details(\CMSApplication::$VAR['client_note_id'], 'client_id'));   
    
} else {    
        
    $this->app->smarty->assign('client_note_details', $this->app->components->client->get_client_note_details(\CMSApplication::$VAR['client_note_id']));
    
}


