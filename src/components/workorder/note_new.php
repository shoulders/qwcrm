<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    $this->app->system->page->forcePage('workorder', 'search');
}

// If a note is submitted
if(isset(\CMSApplication::$VAR['submit'])){
    
    // insert the note into the database
    $this->app->components->workorder->insertNote(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['workorder_note']);
    
    // load the workorder details page    
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The note has been inserted."));
    $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
    
}