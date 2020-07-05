<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->check_page_accessed_via_qwcrm('invoice', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->force_page('invoice', 'search');
}

// Delete Invoice
if(!$this->app->components->invoice->deleteRecord(\CMSApplication::$VAR['invoice_id'])) {    
    
    // Load the invoice details page with error
    $this->app->system->page->force_page('invoice', 'details&invoice_id='.\CMSApplication::$VAR['invoice_id'], 'msg_success='._gettext("The invoice failed to be deleted."));    
    
} else {   
    
    // Load the invoice search page with success message
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The invoice has been deleted successfully."));
    $this->app->system->page->force_page('invoice', 'search');
    
}