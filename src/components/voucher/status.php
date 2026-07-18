<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->page->forcePage('voucher', 'search');
}

// Is Expired (Live Check)
$this->app->components->voucher->checkVoucherIsExpired(\CMSApplication::$VAR['voucher_id']);

// Get Record details
$voucher_details = $this->app->components->voucher->getRecord(\CMSApplication::$VAR['voucher_id']);

// Get Permissions
$allowed_to_change_status = $this->app->components->voucher->checkRecordAllowsManualStatusChange(\CMSApplication::$VAR['voucher_id']);
$allowed_to_delete = $this->app->components->voucher->checkRecordAllowsDelete(\CMSApplication::$VAR['voucher_id']);

// Update Voucher Status
if(isset(\CMSApplication::$VAR['change_status']) && $allowed_to_change_status){
    $this->app->components->voucher->updateStatus(\CMSApplication::$VAR['voucher_id'], \CMSApplication::$VAR['assign_status']);
    $this->app->system->page->forcePage('voucher', 'status&voucher_id='.\CMSApplication::$VAR['voucher_id']);
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',    $allowed_to_change_status        );
$this->app->smarty->assign('allowed_to_delete',           $allowed_to_delete               );

$this->app->smarty->assign('voucher_status',              $voucher_details['status']             );
$this->app->smarty->assign('voucher_status_display_name', $this->app->components->voucher->getStatusDisplayName($voucher_details['status']));
$this->app->smarty->assign('voucher_statuses',            $this->app->components->voucher->getStatuses() );
$this->app->smarty->assign('voucher_selectable_statuses',     $this->app->components->voucher->getStatuses(true) );
