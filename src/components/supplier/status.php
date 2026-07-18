<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a supplier_id
if(!isset(\CMSApplication::$VAR['supplier_id']) || !\CMSApplication::$VAR['supplier_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Supplier ID supplied."));
    $this->app->system->page->forcePage('supplier', 'search');
}

// Update Supplier Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->supplier->updateStatus(\CMSApplication::$VAR['supplier_id'], \CMSApplication::$VAR['assign_status']);
    $this->app->system->page->forcePage('supplier', 'status&supplier_id='.\CMSApplication::$VAR['supplier_id']);
}

// Activate TODO: activateRecord() + message
if(isset(\CMSApplication::$VAR['activate_supplier']) &&$this->app->components->supplier->checkRecordAllowsActivate(\CMSApplication::$VAR['supplier_id'])){
    $this->app->components->supplier->activateRecord(\CMSApplication::$VAR['supplier_id']);
    $this->app->system->page->forcePage('supplier', 'status&supplier_id='.\CMSApplication::$VAR['supplier_id']);
}

// Suspend TODO: suspendRecord() + message
if(isset(\CMSApplication::$VAR['suspend_supplier']) && $this->app->components->supplier->checkRecordAllowsSuspend(\CMSApplication::$VAR['supplier_id'])){
    $this->app->components->supplier->suspendRecord(\CMSApplication::$VAR['supplier_id'], \CMSApplication::$VAR['qform']['reason_for_suspending']);
    $this->app->system->page->forcePage('supplier', 'status&supplier_id='.\CMSApplication::$VAR['supplier_id']);
}

// Close
if(isset(\CMSApplication::$VAR['close_supplier']) &&$this->app->components->supplier->checkRecordAllowsClose(\CMSApplication::$VAR['supplier_id'])){
    $this->app->components->supplier->closeRecord(\CMSApplication::$VAR['supplier_id'], \CMSApplication::$VAR['qform']['reason_for_closing']);
    $this->app->system->page->forcePage('supplier', 'status&supplier_id='.\CMSApplication::$VAR['supplier_id']);
}

// Get supplier details
$supplier_details = $this->app->components->supplier->getRecord(\CMSApplication::$VAR['supplier_id']);

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',     $this->app->components->supplier->checkRecordAllowsManualStatusChange(\CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('allowed_to_activate',          $this->app->components->supplier->checkRecordAllowsActivate(\CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('allowed_to_suspend',           $this->app->components->supplier->checkRecordAllowsSuspend(\CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('allowed_to_close',             $this->app->components->supplier->checkRecordAllowsClose(\CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('allowed_to_delete',            $this->app->components->supplier->checkRecordAllowsDelete(\CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('reason_for_suspending',        json_decode($supplier_details['additional_info'], true)['reason_for_suspending'] ?? '');
$this->app->smarty->assign('reason_for_closing',           json_decode($supplier_details['additional_info'], true)['reason_for_closing'] ?? '');
$this->app->smarty->assign('supplier_status',              $supplier_details['status']);
$this->app->smarty->assign('supplier_statuses',            $this->app->components->supplier->getStatuses(true));
$this->app->smarty->assign('supplier_status_display_name', $this->app->components->supplier->getStatusDisplayName($supplier_details['status']));
