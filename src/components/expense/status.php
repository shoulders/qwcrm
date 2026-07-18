<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a expense_id
if(!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    $this->app->system->page->forcePage('expense', 'search');
}

// Get Record details
$expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);

// Get Permissions
$allowed_to_change_status = $this->app->components->expense->checkRecordAllowsManualStatusChange(\CMSApplication::$VAR['expense_id']);
$allowed_to_delete = $this->app->components->expense->checkRecordAllowsDelete(\CMSApplication::$VAR['expense_id']);

// Update Expense Status
if(isset(\CMSApplication::$VAR['change_status']) && $allowed_to_change_status ){
    $this->app->components->expense->updateStatus(\CMSApplication::$VAR['expense_id'], \CMSApplication::$VAR['assign_status']);
}

// Delete
if(isset(\CMSApplication::$VAR['delete_expense']) && $allowed_to_delete){
    $this->app->components->expense->deleteRecord(\CMSApplication::$VAR['expense_id']);
    $this->app->system->page->forcePage('expense', 'search');
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',        $allowed_to_change_status      );
$this->app->smarty->assign('allowed_to_delete',               $allowed_to_delete       );
$this->app->smarty->assign('expense_status',                  $expense_details['status']            );
$this->app->smarty->assign('expense_status_display_name',     $this->app->components->expense->getStatusDisplayName($expense_details['status']));
$this->app->smarty->assign('expense_statuses',                $this->app->components->expense->getStatuses() );
$this->app->smarty->assign('expense_selectable_statuses',     $this->app->components->expense->getStatuses(true) );
