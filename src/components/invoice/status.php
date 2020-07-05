<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->forcePage('invoice', 'search');
}

// Get the Id of the employee assigned to the invoice
$assigned_employee_id = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'employee_id');

// Update invoice Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->invoice->updateStatus(\CMSApplication::$VAR['invoice_id'], \CMSApplication::$VAR['assign_status']);    
    $this->app->system->page->forcePage('invoice', 'status&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

// Assign Work Order to another employee
if(isset(\CMSApplication::$VAR['change_employee'])) {
    $this->app->components->invoice->assignToEmployee(\CMSApplication::$VAR['invoice_id'], \CMSApplication::$VAR['target_employee_id']);    
    $this->app->system->page->forcePage('invoice', 'status&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

// Get statuses that can be changed by the user
$statuses = $this->app->components->invoice->getStatuses(true);

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',     $this->app->components->invoice->checkRecordAllowsChange(\CMSApplication::$VAR['invoice_id']) );
$this->app->smarty->assign('allowed_to_change_employee',   !$this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'is_closed')   );
$this->app->smarty->assign('allowed_to_refund',            $this->app->components->invoice->checkRecordAllowsRefund(\CMSApplication::$VAR['invoice_id'])       );
$this->app->smarty->assign('allowed_to_cancel',            $this->app->components->invoice->checkRecordAllowsCancel(\CMSApplication::$VAR['invoice_id'])      );
$this->app->smarty->assign('allowed_to_delete',            $this->app->components->invoice->checkRecordAllowsDelete(\CMSApplication::$VAR['invoice_id'])        );
$this->app->smarty->assign('active_employees',             $this->app->components->user->getActiveUsers('employees')                           );
$this->app->smarty->assign('invoice_statuses',             $statuses                                               );
$this->app->smarty->assign('invoice_status',               $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'status')       );
$this->app->smarty->assign('invoice_status_display_name',  $this->app->components->invoice->getStatusDisplayName($this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'status')));
$this->app->smarty->assign('assigned_employee_id',         $assigned_employee_id                                   );
$this->app->smarty->assign('assigned_employee_details',    $this->app->components->user->getRecord($assigned_employee_id)                 );