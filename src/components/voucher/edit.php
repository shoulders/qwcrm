<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have an voucher_id
if(!isset($VAR['voucher_id']) || !$VAR['voucher_id']) {
    force_page('voucher', 'search', 'warning_msg='._gettext("No Voucher ID supplied."));
}

// Check if voucher payment method is enabled
if(!check_payment_method_is_active('voucher')) {
    force_page('index.php', 'null', 'warning_msg='._gettext("Voucher payment method is not enabled. Goto Payment Options and enable Vouchers there."));
}

// Check if voucher can be edited
if(!check_voucher_can_be_edited($VAR['voucher_id'])) {
    force_page('voucher', 'details&voucher_id='.$VAR['voucher_id'], 'warning_msg='._gettext("You cannot edit this Voucher because its status does not allow it."));
}

// if information submitted
if(isset($VAR['submit'])) {
    
    // Create a new Voucher
    update_voucher($VAR['voucher_id'], $VAR['expiry_date'], $VAR['unit_net'], $VAR['note']);

    // Load the new Voucher's Details page
    force_page('voucher', 'details&voucher_id='.$VAR['voucher_id']);    

} else {
    
    // Build the page    
    $smarty->assign('client_details',    get_client_details(get_voucher_details($VAR['voucher_id'], 'client_id'))); 
    $smarty->assign('voucher_statuses', get_voucher_statuses());
    $smarty->assign('voucher_types', get_voucher_types());
    $smarty->assign('voucher_details',  get_voucher_details($VAR['voucher_id']));
    $BuildPage .= $smarty->fetch('voucher/edit.tpl');
}