<?php

require('include.php');
//require(INCLUDES_DIR.'modules/payment.php');
require(INCLUDES_DIR.'modules/workorder.php');

/* get vars */
$cash_amount    = $VAR['cash_amount'];
$cash_memo      = $VAR['cash_memo'];

// get invoice details
$invoice_details = get_single_invoice_details($db, $invoice_id);

/* Financial validation */

/* If 0.00 has been submitted - this is allowed for failed payments
if($cash_amount == '0' || $cash_amount == '0.00' || $cash_amount == ''){
    force_page('payment', 'new','invoice_id='.$invoice_id.'&error_msg= You can not enter a transaction with a zero (0.00) amount');
    exit;
}*/

// Check to see if we are processing more then required
if($cash_amount > $invoice_details['BALANCE']){
    force_page('payment', 'new','invoice_id='.$invoice_id.'&error_msg= You can not enter more than the outstanding balance of the invoice.');
    exit;
}
    
// Calculate the new balance and paid amount - this allows 0.00 transactions to be added
if($invoice_details['BALANCE'] > 0 ) {                  
    $new_balance        = $invoice_details['BALANCE'] - $cash_amount;
    $new_paid_amount    = $invoice_details['PAID_AMOUNT'] + $cash_amount;
} else {        
    $new_balance        = $invoice_details['BALANCE'];
    $new_paid_amount    = $invoice_details['PAID_AMOUNT'];
}      

// Partial Payment Transaction
if($new_balance != 0 ) {

    // Update the invoice
    transaction_update_invoice($db, $invoice_id, 0, 0, $new_paid_amount, $new_balance);

    // log message
    $memo = "Partial Cash Payment Made of $currency_sym$cash_amount, Balance due: $currency_sym$new_balance, Memo: $cash_memo";

    // Creates a History record for the new work order ***** need to sort the message properly *****  
    insert_new_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_created').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name'].$memo);

    // Insert Transaction into log       
    insert_transaction($db, 3, $invoice_id, $workorder_id, $customer_id, $cash_amount, $memo);

    // Now load the invoice to view
    force_page('invoice', 'details', 'invoice_id='.$invoice_id);

}

// Full payment or new Balance is 0.00
if($new_balance == 0 ) {

    // Update the invoice
    transaction_update_invoice($db, $invoice_id, 1, time(), $new_paid_amount, $new_balance);    
   
    // Update workorder status to 'payment made'
    update_workorder_status($db, $workorder_id, 8);   

    // log message   
    if($cash_amount < $invoice_details['INVOICE_AMOUNT']) {
        // Transaction is a partial payment
        $memo = "Partial Cash Payment Made of $currency_sym$cash_amount, closing the invoice. Memo: $cash_memo";
    } else {
        // Transaction is payment for the full amount
        $memo = "Full Cash Payment Made of $currency_sym$cash_amount, closing the invoice. Memo: $cash_memo";
    }

    // Creates a History record for the new work order ***** need to sort the message properly *****  
    insert_new_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_created').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name'].$memo);

    // Insert Transaction into log       
    insert_transaction($db, 3, $invoice_id, $workorder_id, $customer_id, $cash_amount, $memo);

    // Now load the invoice to view
    force_page('invoice', 'details', 'invoice_id='.$invoice_id);   
   
}