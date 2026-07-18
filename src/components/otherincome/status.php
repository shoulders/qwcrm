<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->page->forcePage('otherincome', 'search');
}

// Get Record details
$otherincome_details = $this->app->components->otherincome->getRecord(\CMSApplication::$VAR['otherincome_id']);

// Get Permissions
$allowed_to_change_status = $this->app->components->otherincome->checkRecordAllowsManualStatusChange(\CMSApplication::$VAR['otherincome_id']);
$allowed_to_void = $this->app->components->otherincome->checkRecordAllowsVoid(\CMSApplication::$VAR['otherincome_id']);
$allowed_to_delete = $this->app->components->otherincome->checkRecordAllowsDelete(\CMSApplication::$VAR['otherincome_id']);

// Change Status (manually)
if(isset(\CMSApplication::$VAR['change_status']) && $allowed_to_change_status){
    $this->app->components->otherincome->updateStatus(\CMSApplication::$VAR['otherincome_id'], \CMSApplication::$VAR['assign_status']);
    $this->app->system->page->forcePage('otherincome', 'status&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);
}

// Void Payment
if(isset(\CMSApplication::$VAR['void_otherincome']) && $allowed_to_void){
    $this->app->components->otherincome->voidRecord(\CMSApplication::$VAR['otherincome_id'], \CMSApplication::$VAR['qform']['reason_for_voiding']);
    $this->app->system->page->forcePage('otherincome', 'status&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);
}

// Delete
if(isset(\CMSApplication::$VAR['delete_otherincome']) && $allowed_to_delete){
    $this->app->components->otherincome->deleteRecord(\CMSApplication::$VAR['otherincome_id']);
    $this->app->system->page->forcePage('otherincome', 'search');
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',     $allowed_to_change_status);
$this->app->smarty->assign('allowed_to_void',               $allowed_to_void);
$this->app->smarty->assign('allowed_to_delete',            $allowed_to_delete);
$this->app->smarty->assign('otherincome_status',              $otherincome_details['status'] );
$this->app->smarty->assign('otherincome_status_display_name',$this->app->components->otherincome->getStatusDisplayName($otherincome_details['status']));
$this->app->smarty->assign('otherincome_statuses',            $this->app->components->otherincome->getStatuses() );
$this->app->smarty->assign('otherincome_selectable_statuses',     $this->app->components->otherincome->getStatuses(true));
