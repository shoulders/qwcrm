<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

#########################################
#   Get get active payment methods      #
#########################################

function get_active_payment_methods($db) {
    
    $sql = "SELECT SMARTY_TPL_KEY, ACTIVE FROM ".PRFX."PAYMENT_METHODS WHERE ACTIVE='1'";    
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
   
   // you can call $rs->GetArray twice
   /* if(empty($rs->GetArray())) {
        force_page('core', 'error&error_msg=No Billing Methods Available. Please select billing options in the configuration&menu=1');
        exit;        
    }*/
    
    return $rs->GetAssoc();
    
}

#########################################
#   Get get active credit cards         #
#########################################

function get_active_credit_cards($db){
    
    $sql = "SELECT CARD_TYPE, CARD_NAME FROM ".PRFX."CONFIG_CC_CARDS WHERE ACTIVE='1'";
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    
    // you can call $rs->GetArray twice
    /*if(empty($rs->GetAssoc())) {
        force_page('core', 'error&error_msg=Credit Card Billing is Set on but no cards are active. Please enable at least on credit card in the control panel&menu=1');
        exit;
    }*/

    return $rs->GetAssoc();
    
}

#########################################
#   Get invoice transactions            #
#########################################

function get_invoice_transactions($db, $invoice_id){
    
    $sql ="SELECT * FROM ".PRFX."TABLE_TRANSACTION WHERE INVOICE_ID =".$db->qstr($invoice_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_payment_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {      
        
        return $rs->GetArray();
    }
    
}

############################
#   insert transaction     #
############################

function insert_transaction($db, $invoice_id, $workorder_id, $customer_id, $type, $amount, $memo) {    
    
    $sql = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
        DATE            = ".$db->qstr(time()                    ).",
        TYPE            = ".$db->qstr( $type                    ).",
        INVOICE_ID      = ".$db->qstr( $invoice_id              ).",
        WORKORDER_ID    = ".$db->qstr( $workorder_id            ).",
        CUSTOMER_ID     = ".$db->qstr( $customer_id             ).",
        EMPLOYEE_ID     = ".$db->qstr( $_SESSION['login_id']    ).",
        AMOUNT          = ".$db->qstr( $amount                  ).",
        MEMO            = ".$db->qstr( $memo                    );

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    
}

#########################################################################
#   validate and calculate new invoice totals for the payment method    #
#########################################################################

function validate_payment_method_totals($db, $invoice_id, $amount) {
    
    global $smarty;
    
    // Get invoice details
    $invoice_details = get_invoice_details($db, $invoice_id);

    // Has a zero amount been submitted, this is not allowed
    if($amount == 0){
        //force_page('payment', 'new&invoice_id='.$invoice_id, 'warning_msg=You can not enter a transaction with a zero (0.00) amount');
        //exit;
        $smarty->assign('warning_msg', 'You can not enter a transaction with a zero (0.00) amount');
        //postEmulation('warning_msg', 'You can not enter a transaction with a zero (0.00) amount');
        return false;
    }

    // Is the transaction larger than the outstanding invoice balance, this is not allowed
    if($amount > $invoice_details['0']['BALANCE']){
        //force_page('payment', 'new&invoice_id='.$invoice_id, 'warning_msg=You can not enter more than the outstanding balance of the invoice.');
        //exit;
        $smarty->assign('warning_msg', 'You can not enter more than the outstanding balance of the invoice');
        //postEmulation('warning_msg', 'You can not enter more than the outstanding balance of the invoice');
        return false;
    }
    
    return true;
   
}

####################################################
#   Insert transaction caused by a payment method  #
####################################################

function insert_payment_method_transaction($db, $invoice_id, $amount, $method, $type, $method_memo, $memo) {
    
    global $smarty;

    // Get invoice details
    $invoice_details = get_invoice_details($db, $invoice_id);    
            
    // Make amount into the correct format for the logs
    $formatted_amount = sprintf( "%.2f", $amount);
           
    // Other Variables
    $currency_sym   = get_company_details($db, 'CURRENCY_SYMBOL');
    $workorder_id   = $invoice_details['0']['WORKORDER_ID'];
    $customer_id    = $invoice_details['0']['CUSTOMER_ID'];
    
    // Calculate the new balance and paid amount    
    $new_invoice_paid_amount    = $invoice_details['0']['PAID_AMOUNT'] + $amount;
    $new_invoice_balance        = $invoice_details['0']['BALANCE'] - $amount;
    
   /*echo $amount.'--';
    echo $invoice_details['0']['BALANCE'].'--';
    echo $new_invoice_balance.'--';
    echo $invoice_details['0']['PAID_AMOUNT'].'--';
    echo $new_invoice_paid_amount*/
    
    
    //die;

    //$new_invoice_totals['$new_invoice_balance']     = $invoice_details['0']['BALANCE'] - $amount;
    //$new_invoice_totals['$new_invoice_paid_amount'] = $invoice_details['0']['PAID_AMOUNT'] + $amount;    
    //return $new_invoice_totals;
    
    // Extract the invoice information from the validation results
    //$new_invoice_paid_amount    = $new_invoice_totals['new_invoice_paid_amount'];
    //$new_invoice_balance        = $new_invoice_totals['new_invoice_balance'];
            
    /* Partial Payment Transaction */
    
    if($new_invoice_balance != 0 ) {

        // Update the invoice        
        update_invoice_transaction_only($db, $invoice_id, 0, 0, $new_invoice_paid_amount, $new_invoice_balance);

        // Transaction log        
        $log_msg = "Partial Payment made by $method for $currency_sym$formatted_amount, Balance due: $currency_sym$new_invoice_balance, $method_memo, Memo: $memo";

        // If the invoice has a workorder update it
        if(check_invoice_has_workorder($db, $invoice_id)) {

            // Creates a History record for the new workorder
            insert_new_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_created').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name'].$log_msg);

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
        if($amount < $invoice_details['0']['TOTAL']) {
            // Transaction is a partial payment
            $memo = "Partial Payment made by $method for $currency_sym$formatted_amount, closing the invoice. $method_memo, Memo: $memo";
        } else {
            // Transaction is payment for the full amount
            $memo = "Full Payment made by $method for $currency_sym$formatted_amount, closing the invoice. $method_memo, Memo: $memo";
        }

        // If the invoice has a workorder update it
        if(check_invoice_has_workorder($db, $invoice_id)) {

            // Update workorder status to 'payment made'
            update_workorder_status($db, $workorder_id, 8);   

            // Creates a History record for the new work order
            insert_new_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_created').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name'].$memo);

        }    

        // Insert Transaction into log       
        insert_transaction($db, $invoice_id, $workorder_id, $customer_id, $type, $amount, $memo);

        // Now load the invoice to view
        //force_page('invoice', 'details&invoice_id='.$invoice_id, 'information_msg=Full Payment made successfully'); 
        
        return true;

    }
    
}


####################################################
#      Check if a payment method is active         #
####################################################

function check_payment_method_is_active($db, $method) {
    
    global $smarty;
    
    $sql = "SELECT ACTIVE FROM ".PRFX."PAYMENT_METHODS WHERE SMARTY_TPL_KEY=".$db->qstr($method);   
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_payment_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    }
    
    if($rs->fields['ACTIVE'] != 1) {
        
        return false;
        
    } else {
        
        return true;
        
    }

}