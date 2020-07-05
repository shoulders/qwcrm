<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    $this->app->system->page->force_page('workorder', 'search');
}

// Get the Id of the employee assigned to the workorder
$assigned_employee_id = $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'employee_id');

// Update Work Order Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->workorder->updateStatus(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['assign_status']);    
    $this->app->system->page->force_page('workorder', 'status&workorder_id='.\CMSApplication::$VAR['workorder_id']);
}

// Assign Work Order to another employee
if(isset(\CMSApplication::$VAR['change_employee'])) {
    $this->app->components->workorder->assignToEmployee(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['target_employee_id']);    
    $this->app->system->page->force_page('workorder', 'status&workorder_id='.\CMSApplication::$VAR['workorder_id']);
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',     $this->app->components->workorder->checkRecordAllowsChange(\CMSApplication::$VAR['workorder_id']) );
$this->app->smarty->assign('allowed_to_change_employee',   $this->app->components->workorder->checkRecordAllowsEmployeeUpdate(\CMSApplication::$VAR['workorder_id']));
$this->app->smarty->assign('allowed_to_delete',            $this->app->components->workorder->checkRecordAllowsDelete(\CMSApplication::$VAR['workorder_id'])  );
$this->app->smarty->assign('active_employees',             $this->app->components->user->getActiveUsers('employees')                                     );
$this->app->smarty->assign('workorder_statuses',           $this->app->components->workorder->getStatuses(true)                                      );
$this->app->smarty->assign('workorder_status',             $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'status')             );
$this->app->smarty->assign('workorder_status_display_name',$this->app->components->workorder->getStatusDisplayName($this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'status')));
$this->app->smarty->assign('assigned_employee_id',         $assigned_employee_id                                             );
$this->app->smarty->assign('assigned_employee_details',    $this->app->components->user->getRecord($assigned_employee_id)                           );