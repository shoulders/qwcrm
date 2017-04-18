<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

##########################
#  Get payment details   #
##########################

/*
 * This combined function allows you to pull any of the company information individually
 * or return them all as an array
 * supply the required field name or all to return all of them as an array
 */

function get_payment_details($db, $item = null){
    
    global $smarty;

    $sql = 'SELECT * FROM '.PRFX.'PAYMENT';
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetArray();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

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

#####################################
#    Get Payment methods status     #
#####################################

function get_payment_methods_status($db) {
    
    $sql = "SELECT * FROM ".PRFX."PAYMENT_METHODS";

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    return $rs->GetArray();
    
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

#####################################################
#   Insert transaction created by a payment method  #
#####################################################

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




#####################################
#    Update Payment details         #
#####################################

function update_payment_settings($db, $record) {
    
    $sql = "UPDATE ".PRFX."PAYMENT SET 
            
            BANK_ACCOUNT_NAME       =". $db->qstr( $record['bank_account_name']        ).",
            BANK_NAME               =". $db->qstr( $record['bank_name']                ).",
            BANK_ACCOUNT_NUMBER     =". $db->qstr( $record['bank_account_number']      ).",
            BANK_SORT_CODE          =". $db->qstr( $record['bank_sort_code']           ).",
            BANK_IBAN               =". $db->qstr( $record['bank_iban']                ).",
            PAYPAL_EMAIL            =". $db->qstr( $record['paypal_email']             ).",        
            BANK_TRANSACTION_MSG    =". $db->qstr( $record['bank_transaction_message'] ).",
            CHEQUE_PAYABLE_TO_MSG   =". $db->qstr( $record['cheque_payable_to_msg']    ).",
            INVOICE_FOOTER_MSG      =". $db->qstr( $record['invoice_footer_msg']       );
            

    if(!$rs = $db->execute($sql)) {
        echo $db->ErrorMsg();
    } else {
        
        return;
        
    }
    
}

#####################################
#   Update Payment Methods status   #
#####################################

function update_payment_methods_status($db, $record) {
    
    global $smarty;

    // Array of all valid payment methods
    $payment_methods = array(
                                array('smarty_tpl_key'=>'credit_card_active',       'payment_method_status'=>$record['credit_card_active']      ),
                                array('smarty_tpl_key'=>'cheque_active',            'payment_method_status'=>$record['cheque_active']           ),
                                array('smarty_tpl_key'=>'cash_active',              'payment_method_status'=>$record['cash_active']             ),
                                array('smarty_tpl_key'=>'gift_certificate_active',  'payment_method_status'=>$record['gift_certificate_active'] ),
                                array('smarty_tpl_key'=>'paypal_active',            'payment_method_status'=>$record['paypal_active']           ),
                                array('smarty_tpl_key'=>'direct_deposit_active',    'payment_method_status'=>$record['direct_deposit_active']   )    
                            );
   
    // Loop throught the various payment methods and update the database
    foreach($payment_methods as $payment_method) {
        
        // make empty status = zero (not nessasary but neater)
        if ($payment_method['payment_method_status'] == ''){$payment_method['payment_method_status'] = '0';}
        
        $sql = "UPDATE ".PRFX."PAYMENT_METHODS SET ACTIVE=". $db->qstr( $payment_method['payment_method_status'] )." WHERE SMARTY_TPL_KEY=". $db->qstr( $payment_method['smarty_tpl_key'] ); 
        
        if(!$rs = $db->execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_company_error_message_function_'.__FUNCTION__.'_failed'));
            exit;
        }
    }
    
}
    


