<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'company.php');
require(CINCLUDES_DIR.'invoice.php');
require(CINCLUDES_DIR.'payment.php');
require(CINCLUDES_DIR.'report.php');
require(CINCLUDES_DIR.'voucher.php');
require(CINCLUDES_DIR.'workorder.php');

// Check if we have an voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    force_page('voucher', 'search');
}

// Check if voucher payment method is enabled
if(!check_payment_method_is_active('voucher')) {
    systemMessagesWrite('danger', _gettext("Voucher payment method is not enabled. Goto Payment Options and enable Vouchers there."));
    force_page('index.php', 'null');
}

// Check if voucher can be edited
if(!check_voucher_can_be_edited(\CMSApplication::$VAR['voucher_id'])) {
    systemMessagesWrite('danger', _gettext("You cannot edit this Voucher because its status does not allow it."));
    force_page('voucher', 'details&voucher_id='.\CMSApplication::$VAR['voucher_id']);
}

// if information submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Create a new Voucher
    update_voucher(\CMSApplication::$VAR['qform']['voucher_id'], \CMSApplication::$VAR['qform']['expiry_date'], \CMSApplication::$VAR['qform']['unit_net'], \CMSApplication::$VAR['qform']['note']);

    // Load the new Voucher's Details page
    force_page('voucher', 'details&voucher_id='.\CMSApplication::$VAR['qform']['voucher_id']);    

} else {
    
    // Build the page    
    $smarty->assign('client_details',    get_client_details(get_voucher_details(\CMSApplication::$VAR['voucher_id'], 'client_id'))); 
    $smarty->assign('voucher_statuses', get_voucher_statuses());
    $smarty->assign('voucher_types', get_voucher_types());
    $smarty->assign('voucher_details',  get_voucher_details(\CMSApplication::$VAR['voucher_id']));
}