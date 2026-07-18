<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a payment_id
if(!isset(\CMSApplication::$VAR['payment_id']) || !\CMSApplication::$VAR['payment_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Payment ID supplied."));
    $this->app->system->page->forcePage('payment', 'search');
}

// Get Record details
$payment_details = $this->app->components->payment->getRecord(\CMSApplication::$VAR['payment_id']);

// Get Permissions
$allowed_to_change_status = $this->app->components->payment->checkRecordAllowsManualStatusChange(\CMSApplication::$VAR['payment_id']);
$allowed_to_void = $this->app->components->payment->checkRecordAllowsVoid(\CMSApplication::$VAR['payment_id']);
$allowed_to_delete = $this->app->components->payment->checkRecordAllowsDelete(\CMSApplication::$VAR['payment_id']);

// Change Status (manually)
if(isset(\CMSApplication::$VAR['change_status']) && $allowed_to_change_status){
    $this->app->components->payment->updateStatus(\CMSApplication::$VAR['payment_id'], \CMSApplication::$VAR['assign_status']);
    $this->app->system->page->forcePage('payment', 'status&payment_id='.\CMSApplication::$VAR['payment_id']);
}

// Void Payment
if(isset(\CMSApplication::$VAR['void_payment']) && $allowed_to_void){
    // Build the Payment Environment
    $this->app->components->payment->buildPaymentEnvironment('void');

    // Perform payment action
    $this->app->components->payment->performPaymentAction();
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',        $allowed_to_change_status);
$this->app->smarty->assign('allowed_to_void',                 $allowed_to_void);
$this->app->smarty->assign('allowed_to_delete',               $allowed_to_delete);
$this->app->smarty->assign('payment_status',                  $payment_details['status']);
$this->app->smarty->assign('payment_statuses',                $this->app->components->payment->getStatuses());
$this->app->smarty->assign('payment_selectable_statuses',     $this->app->components->payment->getStatuses(true));
$this->app->smarty->assign('payment_status_display_name',     $this->app->components->payment->getStatusDisplayNames()[$payment_details['status']]);
