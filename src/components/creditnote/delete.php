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
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Credit Note ID supplied."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Delete Invoice
if(!$this->app->components->creditnote->deleteRecord(\CMSApplication::$VAR['creditnote_id'])) {    
    
    // Load the creditnote record on the search page with the error
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The credit note failed to be deleted."));
    //$this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.\CMSApplication::$VAR['creditnote_id']);
    $this->app->system->page->forcePage('creditnote', 'search&search_category=creditnote_id&search_term='.\CMSApplication::$VAR['creditnote_id']);
    
} else {   
    
    // Load the creditnote search page with success message
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The credit note has been deleted successfully."));
    $this->app->system->page->forcePage('creditnote', 'search');
    
}