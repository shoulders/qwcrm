<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an payment_id
if(!isset(\CMSApplication::$VAR['payment_id']) || !\CMSApplication::$VAR['payment_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Payment ID supplied."));
    $this->app->system->page->forcePage('payment', 'search');
}   

// Check if payment can be edited
if(!$this->app->components->payment->checkRecordAllowsEdit(\CMSApplication::$VAR['payment_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this payment because its status does not allow it."));
    $this->app->system->page->forcePage('payment', 'details&payment_id='.\CMSApplication::$VAR['payment_id']);
}
           
// Load the Type and Method classes (files only, no store)
\CMSApplication::classFilesLoad(COMPONENTS_DIR.'payment/types/'); 
//\CMSApplication::classFilesLoad(COMPONENTS_DIR.'payment/methods/');       

// Set Action Type
Payment::$action = 'update';

// Set Payment details
Payment::$payment_details = $this->app->components->payment->getRecord(\CMSApplication::$VAR['payment_id']);

// Set Payment into [qpayment]
$this->app->components->payment->buildQpaymentArray();

// Set the payment type class (Capitlaise the first letter, Workaround: removes underscores, these might go when i go full PSR-1)
$typeClassName = 'PaymentType'.ucfirst(str_replace('_', '', \CMSApplication::$VAR['qpayment']['type']));
$paymentType = new $typeClassName;

// Prep/validate the data        
$paymentType->preProcess();

// If the form is submitted
if(isset(\CMSApplication::$VAR['submit'])) {            

    // Process the update if valid
    if(Payment::$payment_valid) {  

        // process and update the payment record in the database
        $paymentType->update();

        // Get the updated details
        Payment::$payment_details = $this->app->components->payment->getRecord(\CMSApplication::$VAR['payment_id']);
    }

}

// Build the page
$this->app->smarty->assign('client_display_name',      $this->app->components->client->getRecord(Payment::$payment_details['client_id'], 'display_name'));
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord(Payment::$payment_details['employee_id'], 'display_name'));
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes());
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses() );
$this->app->smarty->assign('payment_details',          Payment::$payment_details);
$this->app->smarty->assign('record_balance',           Payment::$record_balance);
