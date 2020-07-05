<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a refund_id
if(!isset(\CMSApplication::$VAR['refund_id']) || !\CMSApplication::$VAR['refund_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->page->forcePage('refund', 'search');
}

// Update Voucher Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->refund->updateStatus(\CMSApplication::$VAR['refund_id'], \CMSApplication::$VAR['assign_status']);    
    $this->app->system->page->forcePage('refund', 'status&refund_id='.\CMSApplication::$VAR['refund_id']);
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',       false      );
$this->app->smarty->assign('refund_status',                  $this->app->components->refund->getRecord(\CMSApplication::$VAR['refund_id'], 'status')             );
$this->app->smarty->assign('refund_statuses',                $this->app->components->refund->getStatuses() );
$this->app->smarty->assign('allowed_to_cancel',              $this->app->components->refund->checkRecordAllowsCancel(\CMSApplication::$VAR['refund_id'])              );
$this->app->smarty->assign('allowed_to_delete',              $this->app->components->refund->checkRecordAllowsDelete(\CMSApplication::$VAR['refund_id'])              );
$this->app->smarty->assign('refund_selectable_statuses',     $this->app->components->refund->getStatuses(true) );