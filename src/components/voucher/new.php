<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'company.php'); // just for get_voucher_vat_tax_code()
require(CINCLUDES_DIR.'invoice.php');
require(CINCLUDES_DIR.'payment.php');
require(CINCLUDES_DIR.'report.php');
require(CINCLUDES_DIR.'voucher.php');
require(CINCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('voucher', 'new') && !check_page_accessed_via_qwcrm('invoice', 'edit')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    force_page('invoice', 'search');
}

// Check if voucher payment method is enabled
if(!check_payment_method_is_active('voucher')) {
    systemMessagesWrite('danger', _gettext("Voucher payment method is not enabled. Goto Payment Options and enable Vouchers there."));
    force_page('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

// if information submitted - add new Voucher
if(isset(\CMSApplication::$VAR['submit'])) {   
        
    // Create a new Voucher
    $voucher_id = insert_voucher(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['type'], \CMSApplication::$VAR['qform']['expiry_date'], \CMSApplication::$VAR['qform']['unit_net'], \CMSApplication::$VAR['qform']['note']);

    // Load the attached invoice Details page
    force_page('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['qform']['invoice_id'], 'msg_success'._gettext("Voucher").': '.$voucher_id.' '._gettext("has been added to this invoice."));

}
    
// Build the page
$smarty->assign('client_details', get_client_details(get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'client_id')));
$smarty->assign('voucher_types', get_voucher_types());
$smarty->assign('voucher_tax_system', get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'tax_system'));