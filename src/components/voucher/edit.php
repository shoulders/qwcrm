<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->general->force_page('voucher', 'search');
}

// Check if voucher payment method is enabled
if(!$this->app->components->payment->check_payment_method_is_active('voucher')) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher payment method is not enabled. Goto Payment Options and enable Vouchers there."));
    $this->app->system->general->force_page('index.php', 'null');
}

// Check if voucher can be edited
if(!$this->app->components->voucher->check_voucher_can_be_edited(\CMSApplication::$VAR['voucher_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this Voucher because its status does not allow it."));
    $this->app->system->general->force_page('voucher', 'details&voucher_id='.\CMSApplication::$VAR['voucher_id']);
}

// if information submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Create a new Voucher
    $this->app->components->voucher->update_voucher(\CMSApplication::$VAR['qform']['voucher_id'], \CMSApplication::$VAR['qform']['expiry_date'], \CMSApplication::$VAR['qform']['unit_net'], \CMSApplication::$VAR['qform']['note']);

    // Load the new Voucher's Details page
    $this->app->system->general->force_page('voucher', 'details&voucher_id='.\CMSApplication::$VAR['qform']['voucher_id']);    

} else {
    
    // Build the page    
    $this->app->smarty->assign('client_details',    $this->app->components->client->get_client_details($this->app->components->voucher->get_voucher_details(\CMSApplication::$VAR['voucher_id'], 'client_id'))); 
    $this->app->smarty->assign('voucher_statuses', $this->app->components->voucher->get_voucher_statuses());
    $this->app->smarty->assign('voucher_types', $this->app->components->voucher->get_voucher_types());
    $this->app->smarty->assign('voucher_details',  $this->app->components->voucher->get_voucher_details(\CMSApplication::$VAR['voucher_id']));
}