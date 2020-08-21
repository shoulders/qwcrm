<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Make sure a payment type is set
if(!isset(\CMSApplication::$VAR['type']) && (\CMSApplication::$VAR['type'] == 'invoice' || \CMSApplication::$VAR['type'] == 'refund' || \CMSApplication::$VAR['type'] == 'expense' || \CMSApplication::$VAR['type'] == 'otherincome')) {
    $this->app->system->variables->systemMessagesWrite('success', _gettext("No Payment Type supplied."));
    $this->app->system->page->forcePage('payment', 'search');  
}

// Prevent undefined variable errors (with and without submit)
\CMSApplication::$VAR['qpayment']['type']           = \CMSApplication::$VAR['type'];
\CMSApplication::$VAR['qpayment']['method']         = \CMSApplication::$VAR['qpayment']['method'] ?? null;
\CMSApplication::$VAR['qpayment']['invoice_id']     = \CMSApplication::$VAR['invoice_id'] ?? \CMSApplication::$VAR['qpayment']['invoice_id'] ?? '';
\CMSApplication::$VAR['qpayment']['voucher_id']     = $qpayment['voucher_id'] ?? ''; // Do i need this? probably!
\CMSApplication::$VAR['qpayment']['refund_id']      = \CMSApplication::$VAR['refund_id'] ?? \CMSApplication::$VAR['qpayment']['refund_id'] ?? '';
\CMSApplication::$VAR['qpayment']['expense_id']     = \CMSApplication::$VAR['expense_id'] ?? \CMSApplication::$VAR['qpayment']['expense_id'] ?? '';
\CMSApplication::$VAR['qpayment']['otherincome_id'] = \CMSApplication::$VAR['otherincome_id'] ?? \CMSApplication::$VAR['qpayment']['otherincome_id'] ?? '';
\CMSApplication::$VAR['qpayment']['name_on_card']   = \CMSApplication::$VAR['qpayment']['name_on_card'] ?? null;

// Prevent direct access to this page, and validate requests
if($this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'edit') || $this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details')) {  
    
    // Check we have a valid request
    if(\CMSApplication::$VAR['qpayment']['type'] == 'invoice' && (!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id'])) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
        $this->app->system->page->forcePage('invoice', 'search');    
    }    
    
} elseif($this->app->system->security->checkPageAccessedViaQwcrm('refund', 'new') || $this->app->system->security->checkPageAccessedViaQwcrm('refund', 'details')) {   
    
    // Check we have a valid request
    if(\CMSApplication::$VAR['qpayment']['type'] == 'refund' && (!isset(\CMSApplication::$VAR['refund_id']) || !\CMSApplication::$VAR['refund_id'])) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
        $this->app->system->page->forcePage('refund', 'search');    
    }    
    
} elseif($this->app->system->security->checkPageAccessedViaQwcrm('expense', 'new') || $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details')) {
    
    // Check we have a valid request
    if(\CMSApplication::$VAR['qpayment']['type'] == 'expense' && (!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id'])) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
        $this->app->system->page->forcePage('expense', 'search');    
    }
 
} elseif($this->app->system->security->checkPageAccessedViaQwcrm('otherincome', 'new') || $this->app->system->security->checkPageAccessedViaQwcrm('otherincome', 'details')) {
    
    // Check we have a valid request
    if(\CMSApplication::$VAR['qpayment']['type'] == 'otherincome' && (!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id'])) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Otherincome ID supplied."));
        $this->app->system->page->forcePage('otherincome', 'search');    
    }
     
} elseif(!$this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Load the Type and Method classes (files only, no store)
\CMSApplication::classFilesLoad(COMPONENTS_DIR.'payment/types/'); 
\CMSApplication::classFilesLoad(COMPONENTS_DIR.'payment/methods/'); 
       
// Build button array
$this->app->components->payment->prepareButtonsHolder();

// Set name on card to company name (if appropriate)
if(!\CMSApplication::$VAR['qpayment']['name_on_card'] && (\CMSApplication::$VAR['qpayment']['type'] == 'refund' || \CMSApplication::$VAR['qpayment']['type'] == 'expense'))
{
    \CMSApplication::$VAR['qpayment']['name_on_card'] = $this->app->components->company->getRecord('company_name');
}

// Set Action Type
Payment::$action = 'new';

// Set the payment type class (Capitlaise the first letter, Workaround: removes underscores, these might go when i go full PSR-1)
$typeClassName = 'PaymentType'.ucfirst(str_replace('_', '', \CMSApplication::$VAR['qpayment']['type']));
$paymentType = new $typeClassName;

// Is the payment allowed
$paymentType->check_payment_allowed();

// If the form is submitted
if(isset(\CMSApplication::$VAR['submit'])) {  

    // Wrap the submitted note
    if(\CMSApplication::$VAR['qpayment']['note'] != '') {\CMSApplication::$VAR['qpayment']['note'] = '<p>'.\CMSApplication::$VAR['qpayment']['note'].'</p>';}

    // Set the payment method class (Capitlaise the first letter, Workaround: removes underscores, these might go when i go full PSR-1)
    $methodClassName = 'PaymentMethod'.ucfirst(str_replace('_', '', \CMSApplication::$VAR['qpayment']['method']));
    $paymentMethod = new $methodClassName;

    // Prep/validate the data
    if(Payment::$payment_valid) {
        $paymentType->pre_process();
        $paymentMethod->pre_process();
    }

    // Process the payment
    if(Payment::$payment_valid) {                 
        $paymentMethod->process();
        $paymentType->process();
    }

    // Now do final things like set messages and build buttons        
    $paymentMethod->post_process();
    $paymentType->post_process();

}

// Build the buttons
$paymentType->build_buttons();
      
// Build the page
$this->app->smarty->assign('display_payments',                  $this->app->components->payment->getRecords('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['qpayment']['invoice_id'], \CMSApplication::$VAR['qpayment']['refund_id'], \CMSApplication::$VAR['qpayment']['expense_id'], \CMSApplication::$VAR['qpayment']['otherincome_id'])  );
$this->app->smarty->assign('payment_method',                    \CMSApplication::$VAR['qpayment']['method']                                                      );
$this->app->smarty->assign('payment_type',                      \CMSApplication::$VAR['qpayment']['type']                                                        );
$this->app->smarty->assign('payment_types',                     $this->app->components->payment->getTypes()                                                             );
$this->app->smarty->assign('payment_methods',                   $this->app->components->payment->getMethods()                                                           );
$this->app->smarty->assign('payment_statuses',                  $this->app->components->payment->getStatuses()                                                          );
$this->app->smarty->assign('payment_active_card_types',         $this->app->components->payment->getActiveCardTypes()                                                 );
$this->app->smarty->assign('name_on_card',                      \CMSApplication::$VAR['qpayment']['name_on_card']                                                );
$this->app->smarty->assign('record_balance',                    Payment::$record_balance                                                     );
$this->app->smarty->assign('buttons',                           Payment::$buttons                                                            );
