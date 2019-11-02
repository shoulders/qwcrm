<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice labour_id
if(!isset(\CMSApplication::$VAR['labour_id']) || !\CMSApplication::$VAR['labour_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice Labour ID supplied."));
    $this->app->system->page->force_page('invoice', 'search');
}

// Get invoice ID before deletion
\CMSApplication::$VAR['invoice_id'] = $this->app->components->invoice->get_invoice_labour_item_details(\CMSApplication::$VAR['labour_id'], 'invoice_id');

// Delete Invoice Labour item
$this->app->components->invoice->delete_invoice_labour_item(\CMSApplication::$VAR['labour_id']);

// Load the edit invoice page
$this->app->system->page->force_page('invoice' , 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
