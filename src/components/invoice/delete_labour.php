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

// Check if we have an invoice labour_id
if(!isset(\CMSApplication::$VAR['labour_id']) || !\CMSApplication::$VAR['labour_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice Labour ID supplied."));
    $this->app->system->page->forcePage('invoice', 'search');
}

// Get invoice ID before deletion
\CMSApplication::$VAR['invoice_id'] = $this->app->components->invoice->getLabourItem(\CMSApplication::$VAR['labour_id'], 'invoice_id');

// Delete Invoice Labour item
$this->app->components->invoice->deleteLabourItem(\CMSApplication::$VAR['labour_id']);

// Load the edit invoice page
$this->app->system->page->forcePage('invoice' , 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
