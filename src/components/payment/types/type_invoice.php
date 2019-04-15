<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// totals checks and button locations should be caclulated here

///////////////should some voucher code be here to allow calculations??/////////////////
// Set the value of the Voucher to the amount to be applied
$VAR['qpayment']['amount'] = get_voucher_details($VAR['qpayment']['voucher_id'], 'unit_net');

// Validate the basic invoice totals after the payment is applied, then if successful return the results
if(!validate_payment_invoice_totals($VAR['qpayment']['invoice_id'], $VAR['qpayment']['amount'])) {
    
    // Do nothing - Specific Error information has already been set via postEmulation  
    $payment_validated = false;
    
} else {
    
    $payment_validated = true;
    // Build additional information column
    
   
}