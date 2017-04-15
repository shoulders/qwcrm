<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

#################################################
#    Make sure all array values are not empty   #
#################################################

function validate_any($val_any){
    foreach($val_any as $key=> $val) {
        if($val == "") {
            $error_arr[$key] = "Missing Field";
        }
    }
    if(!empty($error_arr)) {
        return $error_arr;
    }
}

########################################
#   validate credit card expiry date   #
########################################

function validate_cc_exp($month, $year){
    if ($year > date("Y")){
        return true;
    } elseif ( preg_replace("^0","", $year) == preg_replace("^0","", date("Y")) && preg_replace("^0","", $month) >= preg_replace("^0","", date("m"))) {
        return true;
    } else {
        return false;
    }
}

#####################################
#   Validate Credit Card number     #
#####################################

function validate_cc( $ccNum, $card_type, $card_type_accepted_arr ){
    $v_ccNum = false;
    if ($card_type == "visa" || !$card_type) {
        // VISA
        if ( preg_match('^4(.{12}|.{15})$', $ccNum) ) {
            $v_ccNum = true;
            $c_type  = 'visa';
        }
    } else if ($card_type == "mc" || !$card_type) {
        // MC
        if ( preg_match("^5[1-5][0-9]{14}$", $ccNum) )  {
            $v_ccNum = true;
            $c_type  = 'mc';
        }
    } else if ($card_type == "amex" || !$card_type) {
        // AMEX
        if ( preg_match("^3[47][0-9]{13}$", $ccNum) )  {
            $v_ccNum = true;
            $c_type  = 'amex';
        }
    } else if ($card_type == "discover" || !$card_type) {
        // DISCOVER
        if ( preg_match("^6011[0-9]{12}$", $ccNum) )  {
            $v_ccNum = true;
            $c_type  = 'discover';
        }
    } else if ($card_type == "delta" || !$card_type) {
        // DELTA ?
        if ( preg_match ( "^4(1373[3-7]|462[0-9]{2}|5397[8-9]|"
            ."54313|5443[2-5]|54742|567(2[5-9]|3[0-9]|4[0-5])|"
            ."658[3-7][0-9]|659(0[1-9]|[1-4][0-9]|50)|844(09|10)|"
            ."909[6-7][0-9]|9218[1-2]|98824)[0-9]{10}$", $ccNum ) ) {
            $v_ccNum = true;
            $c_type  = 'delta';
        }
    }else if ($card_type == "solo" || !$card_type) {
        // SOLO  ?
        if ( preg_match("^6(3(34[5-9][0-9])|767[0-9]{2})[0-9]{10}([0-9]{2,3})?$",$ccNum )) {
            $v_ccNum = true;
            $c_type  = 'solo';
        }
    }    else if ($card_type == "switch" || !$card_type) {
        // SWITCH  ?
        if ( preg_match('^49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})[0-9]{10}([0-9]{2,3})?$', $ccNum) ||
            preg_match('^564182[0-9]{10}([0-9]{2,3})?$', $ccNum) ||
            preg_match('^6(3(33[0-4][0-9])|759[0-9]{2})[0-9]{10}([0-9]{2,3})?$', $ccNum) )  {
            $v_ccNum = true;
            $c_type  = 'switch';
        }
    } else if ($card_type == "jcb" || !$card_type) {
        // JCB
        if(preg_match("^(3[0-9]{4}|2131|1800)[0-9]{11}$", $ccNum) )  {
            $v_ccNum = true;
            $c_type  = 'jcb';
        }
    } else if ($card_type == "diners" || !$card_type) {
        // DINERS
        if ( preg_match("^3(0[0-5]|[68][0-9])[0-9]{11}$", $ccNum) ) {
            $v_ccNum = true;
            $c_type  = 'diners';
        }
    } else if ($card_type == "carteblanche" || !$card_type) {
        // CARTEBLANCHE
        if ( preg_match("^3(0[0-5]|[68][0-9])[0-9]{11}$", $ccNum) ) {
            $v_ccNum = true;
            $c_type  = 'carteblanche';
        }
    } else if ($card_type == "enroute" || !$card_type) {
        // ENROUTE
        if (( (substr($ccNum, 0, 4) == "2014" || substr($ccNum, 0, 4) == "2149") && (strlen($ccNum) == 15) )) {
            $v_ccNum = true;
            $c_type  = 'enroute';
        }
    }

    // validate accepted card type
    if ($card_type_accepted_arr != false & $v_ccNum) {

        $v_ccNum = false;
        for($i=0; $i<count($card_type_accepted_arr); $i++)
            if($card_type_accepted_arr[$i]['CARD_TYPE'] == $c_type) $v_ccNum = true;

        }

        if ( $v_ccNum ){
            return tru;
        } else {
            return false;
        }
}

################################################
#   I thing this is card number Obfuscation?   #
################################################

function safe_number($ccNum){
    $char = 'x';

    $s_card_number = substr($ccNum, 0, 4);
    $e_card_number = substr($ccNum, -4);
    $num_to_hide = strlen($ccNum) - 8;

    for($i = 0; $i < $num_to_hide; $i++){
        $pad = $char;
  }

    //$safe_num = $s_card_number;
    //$safe_num = $pad;
    $safe_num = $s_card_number & $pad & $e_card_number;

  return $safe_num;
  
}

#########################################
# Hex to bin coverter            #
#########################################
//
//function hex2bin($data, $newdata) {
//
//    $len = strlen($data);
//    for($i=0;$i<$len;$i+=2) {
//        $newdata = pack("C",hexdec(substr($data,$i,2)));
//    }
//    return $newdata;
//} // End of hex2bin

//function charge_an($post_string, $fields) {
//
//    $ch = curl_init("https://test.authorize.net/gateway/transact.dll"); // URL of gateway for cURL to post to
//    curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // use HTTP POST to send form data
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
//    $resp = curl_exec($ch); //execute post and get results
//    curl_close ($ch);
//
//
//
//
//    /* debug only code */
//
//    $qwcrm_debug = 0;
//    if($qwcrm_debug == 1) {
//            $text = $resp;
//            $tok = strtok($text,"|");
//            while(!($tok === FALSE)){
//                //while ($tok) {
//                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$tok."<br>";
//                $tok = strtok("|");
//            }
//    }
//
//    return $resp;
//}




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
    
    //global $smarty;
    
    // Get invoice details
    $invoice_details = get_invoice_details($db, $invoice_id);

    // Has a zero amount been submitted, this is not allowed
    if($amount == '0' || $amount == '0.00' || $amount == ''){
        //force_page('payment', 'new&invoice_id='.$invoice_id, 'warning_msg=You can not enter a transaction with a zero (0.00) amount');
        //exit;
        //$smarty->assign('warning_msg', 'You can not enter a transaction with a zero (0.00) amount');
        postEmulation('warning_msg', 'You can not enter a transaction with a zero (0.00) amount');
        return false;
    }

    // Is the transaction larger than the outstanding invoice balance, this is not allowed
    if($amount > $invoice_details['0']['BALANCE']){
        //force_page('payment', 'new&invoice_id='.$invoice_id, 'warning_msg=You can not enter more than the outstanding balance of the invoice.');
        //exit;
        //$smarty->assign('warning_msg', 'You can not enter more than the outstanding balance of the invoice');
        postEmulation('warning_msg', 'You can not enter more than the outstanding balance of the invoice');
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

        // Transaction log message
        $log_msg = "Partial $method Payment Made of $currency_sym$amount, Balance due: $currency_sym$new_invoice_balance, Method Memo: $method_memo, Memo: $memo";

        // If the invoice has a workorder update it
        if(check_invoice_has_workorder($db, $invoice_id)) {

            // Creates a History record for the new work order ***** need to sort the message properly *****  
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
            $memo = "Partial $method Payment Made of $currency_sym$amount, closing the invoice. Method Memo: $method_memo, Memo: $memo";
        } else {
            // Transaction is payment for the full amount
            $memo = "Full $method Payment Made of $currency_sym$amount, closing the invoice. Method Memo: $method_memo, Memo: $memo";
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