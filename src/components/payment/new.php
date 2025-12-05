<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Make sure a payment type is set
if(!isset(\CMSApplication::$VAR['type']) && (\CMSApplication::$VAR['type'] == 'invoice' || \CMSApplication::$VAR['type'] == 'expense' || \CMSApplication::$VAR['type'] == 'otherincome' || \CMSApplication::$VAR['type'] == 'creditnote')) {
    $this->app->system->variables->systemMessagesWrite('success', _gettext("No Payment Type supplied."));
    $this->app->system->page->forcePage('payment', 'search');
}

/* Prevent direct access to this page, and validate requests */

// Invoice
if($this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'edit') || $this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details')) {

    // Check we have a valid request
    if(\CMSApplication::$VAR['type'] == 'invoice' && !(\CMSApplication::$VAR['invoice_id'] ?? false)) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
        $this->app->system->page->forcePage('invoice', 'search');
    }

// Expense
} elseif($this->app->system->security->checkPageAccessedViaQwcrm('expense', 'edit') || $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details')) {

    // Check we have a valid request
    if(\CMSApplication::$VAR['type'] == 'expense' && !(\CMSApplication::$VAR['expense_id'] ?? false)) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
        $this->app->system->page->forcePage('expense', 'search');
    }

// Otherincome
} elseif($this->app->system->security->checkPageAccessedViaQwcrm('otherincome', 'edit') || $this->app->system->security->checkPageAccessedViaQwcrm('otherincome', 'details')) {

    // Check we have a valid request
    if(\CMSApplication::$VAR['type'] == 'otherincome' && !(\CMSApplication::$VAR['otherincome_id'] ?? false)) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Otherincome ID supplied."));
        $this->app->system->page->forcePage('otherincome', 'search');
    }

// Credit Note
} elseif($this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'edit') || $this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'details')) {

    // Check we have a valid request
    if(\CMSApplication::$VAR['type'] == 'creditnote' && !(\CMSApplication::$VAR['creditnote_id'] ?? false)) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Credit Note ID supplied."));
        $this->app->system->page->forcePage('creditnote', 'search');
    }

// Allow for page reload
} elseif(!$this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Build the Payment Environment
$this->app->components->payment->buildPaymentEnvironment('new');

// If the form is submitted
if(isset(\CMSApplication::$VAR['submit']))
{
    // Wrap the submitted note - note is not wrapped in <p> by tinymce - this is pointless
    //if(\CMSApplication::$VAR['qpayment']['note']) {\CMSApplication::$VAR['qpayment']['note'] = '<p>'.\CMSApplication::$VAR['qpayment']['note'].'</p>';}

    // Process the payment (validations are done further in the process because of the different combinations of Types and Methods.)
    $this->app->components->payment->processPayment();
}

// Build the buttons
$this->app->components->payment->paymentType->buildButtons();

// Autofill the name on the card payment if not present - this code should perhaps be moved the the methods?
if(!\CMSApplication::$VAR['qpayment']['additional_info']['name_on_card'])
{
    switch(\CMSApplication::$VAR['qpayment']['type']){

        case 'invoice':
            $client_id = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['qpayment']['invoice_id'], 'client_id');
            \CMSApplication::$VAR['qpayment']['additional_info']['name_on_card'] = $this->app->components->client->getRecord($client_id, 'display_name');
            break;
        case 'expense':
            \CMSApplication::$VAR['qpayment']['additional_info']['name_on_card'] = $this->app->components->company->getRecord('company_name');
            break;
        case 'otherincome':
            \CMSApplication::$VAR['qpayment']['additional_info']['name_on_card'] = $this->app->components->otherincome->getRecord(\CMSApplication::$VAR['qpayment']['otherincome_id'], 'display_name');
            break;
        case 'creditnote':
            $creditnote_details = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['qpayment']['creditnote_id']);

            // Debit (When sending money to Client / Against Invoice)
            if($creditnote_details['type'] == 'sales'){
                \CMSApplication::$VAR['qpayment']['additional_info']['name_on_card'] = $this->app->components->company->getRecord('company_name');
            }

            // Credit (When receiving money from a Supplier / Against Expense)
            elseif($creditnote_details['type'] == 'purchase'){
                \CMSApplication::$VAR['qpayment']['additional_info']['name_on_card'] = $this->app->components->supplier->getRecord($creditnote_details['supplier_id'], 'display_name');
            }

            break;
        default:
            \CMSApplication::$VAR['qpayment']['additional_info']['name_on_card'] = '';
            break;

    }

}

// Build the page
$this->app->smarty->assign('display_payments',                  $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['qpayment']['invoice_id'], \CMSApplication::$VAR['qpayment']['expense_id'], \CMSApplication::$VAR['qpayment']['otherincome_id'], \CMSApplication::$VAR['qpayment']['creditnote_id']));
$this->app->smarty->assign('qpayment',                          \CMSApplication::$VAR['qpayment']);
$this->app->smarty->assign('payment_type',                      Payment::$type);
$this->app->smarty->assign('payment_method',                    Payment::$method);
$this->app->smarty->assign('record_balance',                    Payment::$record_balance);
$this->app->smarty->assign('buttons',                           Payment::$buttons);
$this->app->smarty->assign('payment_types',                     $this->app->components->payment->getTypes() );
$this->app->smarty->assign('payment_methods',                   $this->app->components->payment->getMethods());
$this->app->smarty->assign('payment_statuses',                  $this->app->components->payment->getStatuses());
$this->app->smarty->assign('payment_directions',                $this->app->components->payment->getDirections());
$this->app->smarty->assign('payment_active_card_types',         $this->app->components->payment->getActiveCardTypes());

// Make Credit note ID inputs readonly when closing an invoice or expense. It uses the presense of these variables in the URL
// This is not 100% needed because if the user swaps the target CR in the input box it still uses the one from the URL and you cannot access payments page directly
// This just indicates to the user they cannot change the CR number so they don't try.
$this->app->smarty->assign('creditNoteInputreadonly', (\CMSApplication::$VAR['creditnote_id'] ?? null && \CMSApplication::$VAR['creditnote_id'] ?? null) ? true : false);
