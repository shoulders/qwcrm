<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/* Pre-Processing */
// goes here

// Check the Gift Certificate exists and set the giftcert_id
if(!$VAR['qpayment']['giftcert_id'] = get_giftcert_id_by_gifcert_code($VAR['qpayment']['giftcert_code'])) {
    
    $smarty->assign('warning_msg', _gettext("There is no Gift certificate with that code."));

    // Make sure the Gift Certificate is valid and then pass the amount to the next process
    } elseif(!check_giftcert_can_be_redeemed($VAR['qpayment']['giftcert_id'], $VAR['qpayment']['invoice_id'])) {
        
        $smarty->assign('warning_msg', _gettext("This Gift Certificate is not valid or cannot be redeemed."));        
        
    } else {       
        
        // Set the value of the gift certificate to the amount to be applied
        $VAR['qpayment']['amount'] = get_giftcert_details($VAR['qpayment']['giftcert_id'], 'amount');
        
        /* Invoice Processing */

        // Validate the basic invoice totals after the payment is applied, then if successful return the results
        if(!$new_invoice_totals = validate_payment_method_totals($VAR['qpayment']['invoice_id'], $VAR['qpayment']['amount'])) {

            // Do nothing - Specific Error information has already been set via postEmulation    

        } else {

            /* Processing */
            
            // change the status of the giftcert to prevent further use
            update_giftcert_status($VAR['qpayment']['giftcert_id'], 'redeemed', true);            

            // Live processing goes here

            // Create a specific note string (if applicable)
            $note = '<p>'._gettext("Gift Certificate Code").': '.$VAR['qpayment']['giftcert_code'].'</p>';
            if($VAR['qpayment']['note']) { $note .= '<p>'.$VAR['qpayment']['note'].'</p>'; }
            $VAR['qpayment']['note'] = $note;

            // Insert the payment with the calculated information
            $payment_id = insert_payment($VAR['qpayment']);
            
            // Update the redeemed Gift Certificate with the missing redemption information
            update_giftcert_as_redeemed($VAR['qpayment']['giftcert_id'], $VAR['qpayment']['invoice_id'], $payment_id);

            // Assign Success message
            $smarty->assign('information_msg', _gettext("Gift Certificate applied successfully"));

            /* Post-Processing */

            

            // After a sucessful process redirect to the invoice payment page
            //force_page('invoice', 'details&invoice_id='.$VAR['qpayment']['invoice_id'], 'information_msg=Full Payment made successfully');

        }
        
    }