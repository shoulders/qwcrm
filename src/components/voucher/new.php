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

// if information submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Check the expiry date is valid, if not reload the page with an error message
    if($this->app->components->voucher->checkVoucherExpiryIsValid(\CMSApplication::$VAR['qform']['expiry_date']))
    {
        // Create a new Voucher
        $voucher_id = $this->app->components->voucher->insertRecord(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['type'], \CMSApplication::$VAR['qform']['expiry_date'], \CMSApplication::$VAR['qform']['unit_net'], \CMSApplication::$VAR['qform']['note']);

        // Load the attached invoice Details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Voucher").': '.$voucher_id.' '._gettext("has been added to this invoice."));
        $this->app->system->page->forcePage('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['qform']['invoice_id']);
    
    } else {
        
        // the reloaded page should have the submitted expiry date
        $voucher_expiry_date = \CMSApplication::$VAR['qform']['expiry_date'];
    }
    
} else {

    // Generate the Voucher expiry date
    $dateObject = new DateTime();    
    $dateObject->modify('+'.$this->app->components->company->getRecord('voucher_expiry_offset').' days');
    $voucher_expiry_date = $dateObject->format('Y-m-d');

}

// Build the page
$this->app->smarty->assign('client_details', $this->app->components->client->getRecord($this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'client_id')));
$this->app->smarty->assign('voucher_types', $this->app->components->voucher->getTypes());
$this->app->smarty->assign('voucher_tax_system', $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'tax_system'));
$this->app->smarty->assign('voucher_expiry_date', $voucher_expiry_date);