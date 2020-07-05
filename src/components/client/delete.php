<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    $this->app->system->page->forcePage('client', 'search');
}

// Run the delete function and return the results
if(!$this->app->components->client->deleteRecord(\CMSApplication::$VAR['client_id'])) {
    
    // Reload client details page with error messages
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This client cannot be deleted."));
    $this->app->system->page->forcePage('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);
    
} else {
    
    // Load the Client search page
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The client has been deleted."));
    $this->app->system->page->forcePage('client', 'search');
    
}