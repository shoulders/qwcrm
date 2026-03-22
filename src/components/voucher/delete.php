<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('voucher', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->page->forcePage('voucher', 'search');
}

// Get invoice_id before deleting
$invoice_id = $this->app->components->voucher->getRecord(\CMSApplication::$VAR['voucher_id'], 'invoice_id');

// Run the delete function if allowed
if(!$this->app->components->voucher->checkRecordAllowsDelete(\CMSApplication::$VAR['voucher_id'])) {
    //$this->app->system->page->forcePage('invoice', 'details&invoice_id='.$invoice_id);
    $this->app->system->page->forcePage('voucher', 'details&voucher_id='.\CMSApplication::$VAR['voucher_id']);
} else {
    // Delete the voucher
    $this->app->components->voucher->deleteRecord(\CMSApplication::$VAR['voucher_id']);

    // Recalculate the invoice totals and update them
    $this->app->components->invoice->recalculateTotals($invoice_id);

    //$this->app->system->page->forcePage('invoice', 'details&invoice_id='.$invoice_id);
    $this->app->system->page->forcePage('voucher', 'search');
}
