<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a creditnote_id
if(!isset(\CMSApplication::$VAR['creditnote_id']) || !\CMSApplication::$VAR['creditnote_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Credit Note ID supplied."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Check creditnote for expiry
$this->app->components->creditnote->checkCreditnoteIsExpired(\CMSApplication::$VAR['creditnote_id']);

// Get Record details
$creditnote_details = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id']);

// Get Permissions
$allowed_to_change_status = $this->app->components->creditnote->checkRecordAllowsManualStatusChange(\CMSApplication::$VAR['creditnote_id']);
$allowed_to_change_employee = !$this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id'], 'closed_on');
$allowed_to_void = $this->app->components->creditnote->checkRecordAllowsVoid(\CMSApplication::$VAR['creditnote_id']);
$allowed_to_delete = $this->app->components->creditnote->checkRecordAllowsDelete(\CMSApplication::$VAR['creditnote_id']);

// Change Status (manually)
if(isset(\CMSApplication::$VAR['change_status']) && $allowed_to_change_status){
    $this->app->components->creditnote->updateStatus(\CMSApplication::$VAR['creditnote_id'], \CMSApplication::$VAR['assign_status']);
}

// Assign Creditnote to another employee
if(isset(\CMSApplication::$VAR['change_employee']) && $allowed_to_change_employee) {
    $this->app->components->creditnote->assignToEmployee(\CMSApplication::$VAR['creditnote_id'], \CMSApplication::$VAR['target_employee_id']);
}

// Void Payment
if(isset(\CMSApplication::$VAR['void_creditnote']) && $allowed_to_void){
    $this->app->components->creditnote->voidRecord(\CMSApplication::$VAR['creditnote_id'], \CMSApplication::$VAR['qform']['reason_for_voiding']);
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',          $allowed_to_change_status);
$this->app->smarty->assign('allowed_to_change_employee',        $allowed_to_change_employee);
$this->app->smarty->assign('allowed_to_void',                   $allowed_to_void);
$this->app->smarty->assign('allowed_to_delete',                 $allowed_to_delete);
$this->app->smarty->assign('active_employees',                  $this->app->components->user->getActiveUsers('employees')                           );
$this->app->smarty->assign('creditnote_statuses',               $this->app->components->creditnote->getStatuses()                                             );
$this->app->smarty->assign('creditnote_status',                 $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id'], 'status')       );
$this->app->smarty->assign('creditnote_status_display_name',    $this->app->components->creditnote->getStatusDisplayName($this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id'], 'status')));
$this->app->smarty->assign('assigned_employee_id',              $creditnote_details['employee_id']                                  );
$this->app->smarty->assign('assigned_employee_details',         $this->app->components->user->getRecord($creditnote_details['employee_id'])                 );
