<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an creditnote_id
if(!isset(\CMSApplication::$VAR['creditnote_id']) || !\CMSApplication::$VAR['creditnote_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Creditnote ID supplied."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Cancel Credit Note
if(!$this->app->components->invoice->cancelRecord(\CMSApplication::$VAR['creditnote_id'], \CMSApplication::$VAR['qform']['reason_for_cancelling'])) {    
    
    // Load the creditnote details page with error
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The creditnote failed to be cancelled."));
    $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.\CMSApplication::$VAR['creditnote_id']);
    
    
} else {   
    
    // Load the creditnote search page with success message
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The creditnote has been cancelled successfully."));
    $this->app->system->page->forcePage('creditnote', 'search');
    
}