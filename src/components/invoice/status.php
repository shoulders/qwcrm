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
    $this->app->system->page->force_page('invoice', 'search');
}

// Get the Id of the employee assigned to the invoice
$assigned_employee_id = $this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'employee_id');

// Update invoice Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->invoice->update_invoice_status(\CMSApplication::$VAR['invoice_id'], \CMSApplication::$VAR['assign_status']);    
    $this->app->system->page->force_page('invoice', 'status&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

// Assign Work Order to another employee
if(isset(\CMSApplication::$VAR['change_employee'])) {
    $this->app->components->invoice->assign_invoice_to_employee(\CMSApplication::$VAR['invoice_id'], \CMSApplication::$VAR['target_employee_id']);    
    $this->app->system->page->force_page('invoice', 'status&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

// Get statuses that can be changed by the user
$statuses = $this->app->components->invoice->get_invoice_statuses(true);

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',     $this->app->components->invoice->check_invoice_status_can_be_changed(\CMSApplication::$VAR['invoice_id']) );
$this->app->smarty->assign('allowed_to_change_employee',   !$this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'is_closed')   );
$this->app->smarty->assign('allowed_to_refund',            $this->app->components->invoice->check_invoice_can_be_refunded(\CMSApplication::$VAR['invoice_id'])       );
$this->app->smarty->assign('allowed_to_cancel',            $this->app->components->invoice->check_invoice_can_be_cancelled(\CMSApplication::$VAR['invoice_id'])      );
$this->app->smarty->assign('allowed_to_delete',            $this->app->components->invoice->check_invoice_can_be_deleted(\CMSApplication::$VAR['invoice_id'])        );
$this->app->smarty->assign('active_employees',             $this->app->components->user->get_active_users('employees')                           );
$this->app->smarty->assign('invoice_statuses',             $statuses                                               );
$this->app->smarty->assign('invoice_status',               $this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'status')       );
$this->app->smarty->assign('invoice_status_display_name',  $this->app->components->invoice->get_invoice_status_display_name($this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'status')));
$this->app->smarty->assign('assigned_employee_id',         $assigned_employee_id                                   );
$this->app->smarty->assign('assigned_employee_details',    $this->app->components->user->get_user_details($assigned_employee_id)                 );