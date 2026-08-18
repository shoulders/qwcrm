<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('voucher', 'new') && !$this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'edit')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->forcePage('invoice', 'search');
}

// Check if voucher payment method is enabled
if(!$this->app->components->payment->checkMethodActive('voucher')) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher payment method is not enabled. Goto Payment Options and enable Vouchers there."));
    $this->app->system->page->forcePage('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

// Check if the record can be created
if(!$this->app->components->payment->checkRecordCanBeCreated(\CMSApplication::$VAR['invoice_id'])) {
    $this->app->system->page->forcePage('invoice', 'details&invoice_id='.\CMSApplication::$VAR['invoice_id']);
} else {

    // If data has been submitted, validate and then update the record (in this case create voucher)
    if(isset(\CMSApplication::$VAR['submit'])) {

        // Holding variable for validation tests
        $submitFailedValidation = false;

        // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
        if($this->app->components->voucher->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'])) {

            // Create the voucher record and return the voucher_id
            \CMSApplication::$VAR['voucher_id'] = $this->app->components->voucher->insertRecord(\CMSApplication::$VAR['qform']);

            // Success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Voucher with ID").': '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been added to this invoice."));

        } else {
            $submitFailedValidation = true;
        }

        // If submission was successful, load the details page
        if(!$submitFailedValidation) {
            $this->app->system->page->forcePage('voucher', 'details&voucher_id='.\CMSApplication::$VAR['voucher_id']);
        }

    }

    // Get invoice details
    $invoice_details = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id']);

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $qform = \CMSApplication::$VAR['qform'];
    } else {

        // Generate the Voucher expiry date
        $dateObject = new DateTime();
        $dateObject->modify('+'.$this->app->components->company->getRecord('voucher_expiry_offset').' days');

        // Build QForm
        $qform['invoice_id'] = $invoice_details['invoice_id'];
        $qform['type'] = 'mpv';
        $qform['expiry_date'] = $dateObject->format('Y-m-d');
        $qform['unit_net'] = 0.00;
        $qform['note'] = '';

    }

    // Build the page
    $this->app->smarty->assign('qform', $qform);
    $this->app->smarty->assign('client_details', $this->app->components->client->getRecord($invoice_details['client_id']));
    $this->app->smarty->assign('voucher_types', $this->app->components->voucher->getTypes());
    $this->app->smarty->assign('voucher_tax_system', $invoice_details['tax_system']);

}
