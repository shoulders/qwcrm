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

// Build the Payment Environment
$this->app->components->payment->buildPaymentEnvironment('edit');

// If the form is submitted
if(isset(\CMSApplication::$VAR['submit']))
{
    // Process the payment
    $this->app->components->payment->processPayment();
}

// Build the page
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord(Payment::$payment_details['employee_id'], 'display_name'));
$this->app->smarty->assign('client_display_name',      $this->app->components->client->getRecord(Payment::$payment_details['client_id'], 'display_name'));
$this->app->smarty->assign('supplier_display_name',    $this->app->components->supplier->getRecord(Payment::$payment_details['supplier_id'], 'display_name'));
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes());
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses());
$this->app->smarty->assign('payment_creditnote_action_types', $this->app->components->payment->getCreditnoteActionTpes());
$this->app->smarty->assign('payment_details',          Payment::$payment_details);
$this->app->smarty->assign('parent_record_balance',    Payment::$record_balance + Payment::$payment_details['amount']);
