<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/* Pre-Processing */

// Check the Voucher exists and set the voucher_id
if(!$VAR['qpayment']['voucher_id'] = get_voucher_id_by_voucher_code($VAR['qpayment']['voucher_code'])) {
    
    $smarty->assign('warning_msg', _gettext("There is no Voucher with that code."));

// Make sure the Voucher is valid and then pass the amount to the next process
} elseif(!check_voucher_can_be_redeemed($VAR['qpayment']['voucher_id'], $VAR['qpayment']['invoice_id'])) {
        
        $smarty->assign('warning_msg', _gettext("This Voucher is not valid or cannot be redeemed."));        
        
} else {

    // Set the value of the Voucher to the amount to be applied
    $VAR['qpayment']['amount'] = get_voucher_details($VAR['qpayment']['voucher_id'], 'unit_net');

    /* Processing */

    // change the status of the Voucher to prevent further use
    update_voucher_status($VAR['qpayment']['voucher_id'], 'redeemed', true);            

    // Build additional information column
    $VAR['qpayment']['additional_info'] = build_additional_info_json();    

    // Insert the payment with the calculated information
    $payment_id = insert_payment($VAR['qpayment']);

    // Update the redeemed Voucher with the missing redemption information
    update_voucher_as_redeemed($VAR['qpayment']['voucher_id'], $VAR['qpayment']['invoice_id'], $payment_id);

    // Assign Success message
    $smarty->assign('information_msg', _gettext("Voucher applied successfully"));        

}

/* Post-Processing */