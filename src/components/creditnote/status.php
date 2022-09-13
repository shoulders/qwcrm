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

// Get the Id of the employee assigned to the creditnote
$assigned_employee_id = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id'], 'employee_id');

// Update creditnote Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->creditnote->updateStatus(\CMSApplication::$VAR['creditnote_id'], \CMSApplication::$VAR['assign_status']);    
    $this->app->system->page->forcePage('creditnote', 'status&creditnote_id='.\CMSApplication::$VAR['creditnote_id']);
}

// Assign Creditnote to another employee
if(isset(\CMSApplication::$VAR['change_employee'])) {
    $this->app->components->creditnote->assignToEmployee(\CMSApplication::$VAR['creditnote_id'], \CMSApplication::$VAR['target_employee_id']);    
    $this->app->system->page->forcePage('creditnote', 'status&creditnote_id='.\CMSApplication::$VAR['creditnote_id']);
}

// Check creditnote for expiry
$this->app->components->creditnote->checkCreditnoteIsExpired(\CMSApplication::$VAR['creditnote_id']);

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',     $this->app->components->creditnote->checkRecordAllowsManualStatusChange(\CMSApplication::$VAR['creditnote_id']) );
$this->app->smarty->assign('allowed_to_change_employee',   !$this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id'], 'is_closed')   );
$this->app->smarty->assign('allowed_to_cancel',            $this->app->components->creditnote->checkRecordAllowsCancel(\CMSApplication::$VAR['creditnote_id'])      );
$this->app->smarty->assign('allowed_to_delete',            $this->app->components->creditnote->checkRecordAllowsDelete(\CMSApplication::$VAR['creditnote_id'])        );
$this->app->smarty->assign('active_employees',             $this->app->components->user->getActiveUsers('employees')                           );
$this->app->smarty->assign('creditnote_statuses',             $this->app->components->creditnote->getStatuses()                                             );
$this->app->smarty->assign('creditnote_status',               $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id'], 'status')       );
$this->app->smarty->assign('creditnote_status_display_name',  $this->app->components->creditnote->getStatusDisplayName($this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id'], 'status')));
$this->app->smarty->assign('assigned_employee_id',         $assigned_employee_id                                   );
$this->app->smarty->assign('assigned_employee_details',    $this->app->components->user->getRecord($assigned_employee_id)                 );