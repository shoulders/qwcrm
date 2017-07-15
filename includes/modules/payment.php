<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records - linked tables
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update - no table linking
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Display Functions **/

/** New/Insert Functions **/

############################
#   insert transaction     #
############################

function insert_transaction($db, $invoice_id, $workorder_id, $customer_id, $type, $amount, $note) {
    
    $sql = "INSERT INTO ".PRFX."payment_transactions SET
            DATE            = ".$db->qstr(time()                                ).",
            TYPE            = ".$db->qstr( $type                                ).",
            INVOICE_ID      = ".$db->qstr( $invoice_id                          ).",
            WORKORDER_ID    = ".$db->qstr( $workorder_id                        ).",
            CUSTOMER_ID     = ".$db->qstr( $customer_id                         ).",
            EMPLOYEE_ID     = ".$db->qstr( QFactory::getUser()->login_user_id   ).",
            AMOUNT          = ".$db->qstr( $amount                              ).",
            NOTE            = ".$db->qstr( $note                                );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert transaction into the database."));
        exit;
    }
    
}

#####################################################
#   Insert transaction created by a payment method  #
#####################################################

function insert_payment_method_transaction($db, $invoice_id, $amount, $method, $type, $method_note, $note) {
    
    // Get invoice details
    $invoice_details = get_invoice_details($db, $invoice_id);    
            
    // Make amount into the correct format for the logs
    $formatted_amount = sprintf( "%.2f", $amount);
           
    // Other Variables
    $currency_sym   = get_company_details($db, 'CURRENCY_SYMBOL');
    $workorder_id   = $invoice_details['WORKORDER_ID'];
    $customer_id    = $invoice_details['CUSTOMER_ID'];
    
    // Calculate the new balance and paid amount    
    $new_invoice_paid_amount    = $invoice_details['PAID_AMOUNT'] + $amount;
    $new_invoice_balance        = $invoice_details['BALANCE'] - $amount;
            
    /* Partial Payment Transaction */
    
    if($new_invoice_balance != 0 ) {

        // Update the invoice        
        update_invoice_transaction_only($db, $invoice_id, 0, 0, $new_invoice_paid_amount, $new_invoice_balance);

        // Transaction log        
        $log_msg = "Partial Payment made by $method for $currency_sym$formatted_amount, Balance due: $currency_sym$new_invoice_balance, $method_note, Note: $note";

        // If the invoice has a workorder update it
        if(check_invoice_has_workorder($db, $invoice_id)) {

            // Creates a History record for the new workorder            
            insert_workorder_history_note($db, $workorder_id, gettext("Created by").' '.$_SESSION['login_display_name'].' - '.$log_msg);
        }    

        // Insert Transaction into log       
        insert_transaction($db, $invoice_id, $workorder_id, $customer_id, $type, $amount, $log_msg);
        
        // Now load the invoice to view
        //force_page('invoice', 'details&invoice_id='.$invoice_id, 'information_msg=Partial Payment made successfully');
        
        return true;

    }

    /* Full payment or the new Balance is 0.00 */
    
    if($new_invoice_balance == 0 ) {

        // Update the invoice
        update_invoice_transaction_only($db, $invoice_id, 1, time(), $new_invoice_paid_amount, $new_invoice_balance);   

        // log message   
        if($amount < $invoice_details['TOTAL']) {
            // Transaction is a partial payment
            $log_msg = "Partial Payment made by $method for $currency_sym$formatted_amount, closing the invoice. $method_note, Note: $note";
        } else {
            // Transaction is payment for the full amount
            $log_msg = "Full Payment made by $method for $currency_sym$formatted_amount, closing the invoice. $method_note, Note: $note";
        }

        // If the invoice has a workorder update it
        if(check_invoice_has_workorder($db, $invoice_id)) {

            // Update workorder status to 'payment made'
            update_workorder_status($db, $workorder_id, 8);   

            // Creates a History record for the new work order            
            insert_workorder_history_note($db, $workorder_id, gettext("Created by").' '.$_SESSION['login_display_name'].' - '.$log_msg);
        }    

        // Insert Transaction into log       
        insert_transaction($db, $invoice_id, $workorder_id, $customer_id, $type, $amount, $log_msg);

        // Now load the invoice to view
        //force_page('invoice', 'details&invoice_id='.$invoice_id, 'information_msg=Full Payment made successfully'); 
        
        return true;

    }
    
}

/** Get Functions **/

##########################
#  Get payment details   # // this gets payment details like bank details, not transactions
##########################

function get_payment_details($db, $item = null){
    
    $sql = "SELECT * FROM ".PRFX."payment";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get payment details."));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#########################################
#   Get get active payment methods      # // If i dont have METHOD in the select the array is not built corretly
#########################################

function get_active_payment_methods($db) {
    
    $sql = "SELECT
            SMARTY_TPL_KEY, ACTIVE
            FROM ".PRFX."payment_methods
            WHERE ACTIVE='1'";    
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get active payment methods."));
        exit;
    } else {
        
        return $rs->GetAssoc();
        
    }
    
}

#####################################
#    Get Payment methods status     #
#####################################

function get_payment_methods_status($db) {
    
    $sql = "SELECT * FROM ".PRFX."payment_methods";

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get payment methods status."));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

#########################################
#   Get get active credit cards         #
#########################################

function get_active_credit_cards($db) {
    
    $sql = "SELECT CARD_TYPE, CARD_NAME FROM ".PRFX."payment_credit_cards WHERE ACTIVE='1'";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the active credit cards."));
        exit;
    } else {
        
        $records = $rs->GetArray();

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }  
    
}

#########################################
#   Get invoice transactions            #
#########################################

function get_invoice_transactions($db, $invoice_id){
    
    $sql ="SELECT * FROM ".PRFX."payment_transactions WHERE INVOICE_ID =".$db->qstr($invoice_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the invoice's transactions."));
        exit;
    } else {      
        
        return $rs->GetArray();
    }
    
}

/** Update Functions **/

#####################################
#    Update Payment details         #
#####################################

function update_payment_settings($db, $VAR) {
    
    $sql = "UPDATE ".PRFX."payment SET            
            BANK_ACCOUNT_NAME       =". $db->qstr( $VAR['bank_account_name']        ).",
            BANK_NAME               =". $db->qstr( $VAR['bank_name']                ).",
            BANK_ACCOUNT_NUMBER     =". $db->qstr( $VAR['bank_account_number']      ).",
            BANK_SORT_CODE          =". $db->qstr( $VAR['bank_sort_code']           ).",
            BANK_IBAN               =". $db->qstr( $VAR['bank_iban']                ).",
            PAYPAL_EMAIL            =". $db->qstr( $VAR['paypal_email']             ).",        
            BANK_TRANSACTION_MSG    =". $db->qstr( $VAR['bank_transaction_message'] ).",
            CHEQUE_PAYABLE_TO_MSG   =". $db->qstr( $VAR['cheque_payable_to_msg']    ).",
            INVOICE_FOOTER_MSG      =". $db->qstr( $VAR['invoice_footer_msg']       );            

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update payment settings."));
        exit;
    } else {
        
        return;
        
    }
    
}

#####################################
#   Update Payment Methods status   #
#####################################

function update_payment_methods_status($db, $VAR) {
    
    // Array of all valid payment methods
    $payment_methods = array(
                                array('smarty_tpl_key'=>'credit_card_active',       'payment_method_status'=>$VAR['credit_card_active']      ),
                                array('smarty_tpl_key'=>'cheque_active',            'payment_method_status'=>$VAR['cheque_active']           ),
                                array('smarty_tpl_key'=>'cash_active',              'payment_method_status'=>$VAR['cash_active']             ),
                                array('smarty_tpl_key'=>'gift_certificate_active',  'payment_method_status'=>$VAR['gift_certificate_active'] ),
                                array('smarty_tpl_key'=>'paypal_active',            'payment_method_status'=>$VAR['paypal_active']           ),
                                array('smarty_tpl_key'=>'direct_deposit_active',    'payment_method_status'=>$VAR['direct_deposit_active']   )    
                            );
   
    // Loop throught the various payment methods and update the database
    foreach($payment_methods as $payment_method) {
        
        // make empty status = zero (not nessasary but neater)
        if ($payment_method['payment_method_status'] == ''){$payment_method['payment_method_status'] = '0';}
        
        $sql = "UPDATE ".PRFX."payment_methods SET ACTIVE=". $db->qstr( $payment_method['payment_method_status'] )." WHERE SMARTY_TPL_KEY=". $db->qstr( $payment_method['smarty_tpl_key'] ); 
        
        if(!$rs = $db->execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a payment method status."));
            exit;
        }
        
    }
    
}

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

####################################################
#      Check if a payment method is active         #
####################################################

function check_payment_method_is_active($db, $method) {
    
    $sql = "SELECT ACTIVE FROM ".PRFX."payment_methods WHERE SMARTY_TPL_KEY=".$db->qstr($method);   
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if the payment method is active."));
        exit;
    }
    
    if($rs->fields['ACTIVE'] != 1) {
        
        return false;
        
    } else {
        
        return true;
        
    }

}

#########################################################################
#   validate and calculate new invoice totals for the payment method    #
#########################################################################

function validate_payment_method_totals($db, $invoice_id, $amount) {
    
    global $smarty;

    // Has a zero amount been submitted, this is not allowed
    if($amount == 0){
        //force_page('payment', 'new&invoice_id='.$invoice_id, 'warning_msg=You can not enter a transaction with a zero (0.00) amount');
        //exit;
        $smarty->assign('warning_msg', gettext("You can not enter a transaction with a zero (0.00) amount"));
        //postEmulationWrite('warning_msg', 'You can not enter a transaction with a zero (0.00) amount');
        return false;
    }

    // Is the transaction larger than the outstanding invoice balance, this is not allowed
    if($amount > get_invoice_details($db, $invoice_id, 'BALANCE')){
        //force_page('payment', 'new&invoice_id='.$invoice_id, 'warning_msg=You can not enter more than the outstanding balance of the invoice.');
        //exit;
        $smarty->assign('warning_msg', gettext("You can not enter more than the outstanding balance of the invoice"));
        //postEmulationWrite('warning_msg', 'You can not enter more than the outstanding balance of the invoice');
        return false;
    }
    
    return true;
   
}