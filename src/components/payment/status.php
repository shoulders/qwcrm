<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a payment_id
if(!isset(\CMSApplication::$VAR['payment_id']) || !\CMSApplication::$VAR['payment_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Payment ID supplied."));
    $this->app->system->general->force_page('payment', 'search');
}

// Update Payment Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->payment->update_payment_status(\CMSApplication::$VAR['payment_id'], \CMSApplication::$VAR['assign_status']);    
    $this->app->system->general->force_page('payment', 'status&payment_id='.\CMSApplication::$VAR['payment_id']);
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',        $this->app->components->payment->check_payment_status_can_be_changed(\CMSApplication::$VAR['payment_id'])      );
$this->app->smarty->assign('payment_status',                  $this->app->components->payment->get_payment_details(\CMSApplication::$VAR['payment_id'], 'status')             );
$this->app->smarty->assign('payment_statuses',                $this->app->components->payment->get_payment_statuses() );
$this->app->smarty->assign('allowed_to_cancel',               $this->app->components->payment->check_payment_can_be_cancelled(\CMSApplication::$VAR['payment_id'])   );
$this->app->smarty->assign('allowed_to_delete',               $this->app->components->payment->check_payment_can_be_deleted(\CMSApplication::$VAR['payment_id'])              );
$this->app->smarty->assign('payment_selectable_statuses',     $this->app->components->payment->get_payment_statuses(true) );