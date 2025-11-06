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
    // Wrap the submitted note - note is not wrapped in <p> by tinymce - this is popintless
    //if(\CMSApplication::$VAR['qpayment']['note']) {\CMSApplication::$VAR['qpayment']['note'] = '<p>'.\CMSApplication::$VAR['qpayment']['note'].'</p>';}

    // Process the payment
    $this->app->components->payment->processPayment();
}

// Build the buttons
$this->app->components->payment->paymentType->buildButtons();

// Autofill the name on the card payment if not present - this code should perhaps be moved the the methods?
if(!\CMSApplication::$VAR['qpayment']['name_on_card'])
{
    // Invoice
    if (\CMSApplication::$VAR['qpayment']['type'] == 'invoice')
    {
        $client_id = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['qpayment']['invoice_id'], 'client_id');
        \CMSApplication::$VAR['qpayment']['name_on_card'] = $this->app->components->client->getRecord($client_id, 'display_name');
    }

    // Expense
    elseif(\CMSApplication::$VAR['qpayment']['type'] == 'expense')
    {
        \CMSApplication::$VAR['qpayment']['name_on_card'] = $this->app->components->company->getRecord('company_name');
    }

    // Other Income
    elseif(\CMSApplication::$VAR['qpayment']['type'] == 'otherincome')
    {
        \CMSApplication::$VAR['qpayment']['name_on_card'] = $this->app->components->otherincome->getRecord(\CMSApplication::$VAR['qpayment']['otherincome_id'], 'display_name');
    }

    // Creditnote
    elseif (\CMSApplication::$VAR['qpayment']['type'] == 'creditnote')
    {
        if(\CMSApplication::$VAR['qpayment']['creditnote_action'] == 'sales_refund')
        {
            \CMSApplication::$VAR['qpayment']['name_on_card'] = $this->app->components->company->getRecord('company_name');
        }
        elseif(\CMSApplication::$VAR['qpayment']['creditnote_action'] == 'purchase_refund')
        {
            $supplier_id = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['qpayment']['creditnote_id'], 'supplier_id');
            \CMSApplication::$VAR['qpayment']['name_on_card'] = $this->app->components->supplier->getRecord($supplier_id, 'display_name');
        }
    }
    else
    {
        \CMSApplication::$VAR['qpayment']['name_on_card'] = '';
    }
}

// Build the page
$this->app->smarty->assign('display_payments',                  $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['qpayment']['invoice_id'], \CMSApplication::$VAR['qpayment']['expense_id'], \CMSApplication::$VAR['qpayment']['otherincome_id'], \CMSApplication::$VAR['qpayment']['creditnote_id']));
$this->app->smarty->assign('payment_method',                    \CMSApplication::$VAR['qpayment']['method']                                                      );
$this->app->smarty->assign('payment_type',                      \CMSApplication::$VAR['qpayment']['type']                                                        );
$this->app->smarty->assign('payment_types',                     $this->app->components->payment->getTypes()                                                             );
$this->app->smarty->assign('payment_methods',                   $this->app->components->payment->getMethods()                                                           );
$this->app->smarty->assign('payment_statuses',                  $this->app->components->payment->getStatuses()                                                          );
$this->app->smarty->assign('payment_creditnote_action_types',   $this->app->components->payment->getCreditnoteActionTypes());
$this->app->smarty->assign('payment_active_card_types',         $this->app->components->payment->getActiveCardTypes()                                                 );
$this->app->smarty->assign('name_on_card',                      \CMSApplication::$VAR['qpayment']['name_on_card']                                                );
$this->app->smarty->assign('voucher_code',                      \CMSApplication::$VAR['qpayment']['voucher_code'] ?? null);
$this->app->smarty->assign('creditnote_id',                     \CMSApplication::$VAR['qpayment']['creditnote_id'] ?? null); // This is needed becasue of the dualality of the Credit Note system, only works for form reloads
$this->app->smarty->assign('note',                              \CMSApplication::$VAR['qpayment']['note'] ?? null                                                    );
$this->app->smarty->assign('record_balance',                    Payment::$record_balance);
$this->app->smarty->assign('buttons',                           Payment::$buttons);
