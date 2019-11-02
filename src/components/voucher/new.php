<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->check_page_accessed_via_qwcrm('voucher', 'new') && !$this->app->system->security->check_page_accessed_via_qwcrm('invoice', 'edit')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->force_page('invoice', 'search');
}

// Check if voucher payment method is enabled
if(!$this->app->components->payment->check_payment_method_is_active('voucher')) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher payment method is not enabled. Goto Payment Options and enable Vouchers there."));
    $this->app->system->page->force_page('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

// if information submitted - add new Voucher
if(isset(\CMSApplication::$VAR['submit'])) {   
        
    // Create a new Voucher
    $voucher_id = $this->app->components->voucher->insert_voucher(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['type'], \CMSApplication::$VAR['qform']['expiry_date'], \CMSApplication::$VAR['qform']['unit_net'], \CMSApplication::$VAR['qform']['note']);

    // Load the attached invoice Details page
    $this->app->system->page->force_page('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['qform']['invoice_id'], 'msg_success'._gettext("Voucher").': '.$voucher_id.' '._gettext("has been added to this invoice."));

}
    
// Build the page
$this->app->smarty->assign('client_details', $this->app->components->client->get_client_details($this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'client_id')));
$this->app->smarty->assign('voucher_types', $this->app->components->voucher->get_voucher_types());
$this->app->smarty->assign('voucher_tax_system', $this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'tax_system'));