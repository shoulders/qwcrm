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
if(!$VAR['giftcert_id'] = get_giftcert_id_by_gifcert_code($db, $VAR['giftcert_code'])) {
    
    $smarty->assign('warning_msg', _gettext("There is no Gift certificate with that code."));

    // Make sure the Gift Certificate is valid and then pass the amount to the next process
    } elseif(!validate_giftcert_for_payment($db, $VAR['giftcert_id'])) {
        
        $smarty->assign('warning_msg', _gettext("This Gift Certificate is not valid."));        
        
    } else {       
        
        // Set the value of the gift certificate to the amount to be applied
        $VAR['amount'] = get_giftcert_details($db, $VAR['giftcert_id'], 'amount');
        
        /* Invoice Processing */

        // Validate the basic invoice totals after the transaction is applied, then if successful return the results
        if(!$new_invoice_totals = validate_payment_method_totals($db, $VAR['invoice_id'], $VAR['amount'])) {

            // Do nothing - Specific Error information has already been set via postEmulation    

        } else {

            /* Processing */

            // Live processing goes here

            // Create a specific note string (if applicable)
            $method_note = _gettext("Gift Certificate Code").': '.$VAR['giftcert_code'];    

            // Insert the transaction with the calculated information
            insert_payment_method_transaction($db, $VAR['invoice_id'], $VAR['date'], $VAR['amount'], $VAR['method_name'], $VAR['method_type'], $method_note, $VAR['note']);

            // Assign Success message
            $smarty->assign('information_msg', _gettext("Gift Certificate applied successfully"));

            /* Post-Processing */

            // Update the Gift Certificate
            update_giftcert_as_redeemed($db, $VAR['giftcert_id'], $VAR['invoice_id']);

            // After a sucessful process redirect to the invoice payment page
            //force_page('invoice', 'details&invoice_id='.$VAR['invoice_id'], 'information_msg=Full Payment made successfully');

        }
        
    }