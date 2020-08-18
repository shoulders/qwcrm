<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['assign_to_employee'] = \CMSApplication::$VAR['assign_to_employee'] ?? null;

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    $this->app->system->page->forcePage('client', 'search');
}

// If a workorder is submitted
if(isset(\CMSApplication::$VAR['submit'])){
    
    // insert the submitted workorder and return it's id
    \CMSApplication::$VAR['workorder_id'] = $this->app->components->workorder->insertRecord(\CMSApplication::$VAR['client_id'], \CMSApplication::$VAR['scope'], \CMSApplication::$VAR['description'], \CMSApplication::$VAR['comment']);

    // If workorder is to be assigned to an employee
    if(\CMSApplication::$VAR['assign_to_employee'] === '1') {       
        $this->app->components->workorder->assignToEmployee(\CMSApplication::$VAR['workorder_id'], $this->app->user->login_user_id);  
    }
    
    // load the workorder details page
    $this->app->system->variables->systemMessagesWrite('success', _gettext("New Work Order created."));
    $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
        
}

// Build the page
$this->app->smarty->assign('client_display_name', $this->app->components->client->getRecord(\CMSApplication::$VAR['client_id'], 'display_name'));