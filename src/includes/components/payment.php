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

#####################################################
#  Display all payments the given status            #
#####################################################

function display_payments($db, $order_by = 'transaction_id', $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $method = null, $employee_id = null, $customer_id = null) {
    
    global $smarty;
   
    /* Filter the Records */
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."payment_transactions.transaction_id";
    
    // Restrict results by search category (customer) and search term
    if($search_category == 'customer_display_name') {$whereTheseRecords .= " AND ".PRFX."customer.display_name LIKE '%$search_term%'";}
    
   // Restrict results by search category (employee) and search term
    elseif($search_category == 'employee_display_name') {$whereTheseRecords .= " AND ".PRFX."user.display_name LIKE '%$search_term%'";}     
    
    // Restrict results by search category and search term
    elseif($search_term != null) {$whereTheseRecords .= " AND ".PRFX."payment_transactions.$search_category LIKE '%$search_term%'";} 
    
    // Restrict by Status
    if($method != null) {$whereTheseRecords .= " AND ".PRFX."payment_transactions.method= ".$db->qstr($method);}        

    // Restrict by Employee
    if($employee_id != null) {$whereTheseRecords .= " AND ".PRFX."user.user_id=".$db->qstr($employee_id);}

    // Restrict by Customer
    if($customer_id != null) {$whereTheseRecords .= " AND ".PRFX."customer.customer_id=".$db->qstr($customer_id);}
    
    /* The SQL code */
    
    $sql =  "SELECT                
            ".PRFX."customer.display_name AS customer_display_name,
                
            ".PRFX."payment_transactions.transaction_id,
            ".PRFX."payment_transactions.employee_id,
            ".PRFX."payment_transactions.customer_id,
            ".PRFX."payment_transactions.workorder_id,
            ".PRFX."payment_transactions.invoice_id,
            ".PRFX."payment_transactions.date,
            ".PRFX."payment_transactions.method,
            ".PRFX."payment_transactions.amount,
            ".PRFX."payment_transactions.note,
                
            ".PRFX."user.display_name AS employee_display_name
               
            FROM ".PRFX."payment_transactions
            LEFT JOIN ".PRFX."user ON ".PRFX."payment_transactions.employee_id   = ".PRFX."user.user_id
            LEFT JOIN ".PRFX."customer ON ".PRFX."payment_transactions.customer_id = ".PRFX."customer.customer_id                 
            ".$whereTheseRecords."
            GROUP BY ".PRFX."payment_transactions.".$order_by."
            ORDER BY ".PRFX."payment_transactions.".$order_by."
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching payments."));
            exit;
        } else {        
            $total_results = $rs->RecordCount();            
            $smarty->assign('total_results', $total_results);
        }        

        // Figure out the total number of pages. Always round up using ceil()
        $total_pages = ceil($total_results / $records_per_page);
        $smarty->assign('total_pages', $total_pages);
        
        // Set the page number
        $smarty->assign('page_no', $page_no);
        
        // Assign the Previous page        
        $previous = ($page_no - 1);        
        $smarty->assign('previous', $previous);          
        
        // Assign the next page        
        if($page_no == $total_pages) {$next = 0;}
        elseif($page_no < $total_pages) {$next = ($page_no + 1);}
        else {$next = $total_pages;}
        $smarty->assign('next', $next);
        
        // Only return the given page's records
        $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;
        
        // add the restriction on to the SQL
        $sql .= $limitTheseRecords;
        
    } else {
        
        // This make the drop down menu look correct
        $smarty->assign('total_pages', 1);
        
    }
  
    /* Return the records */
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching payments."));
        exit;
    } else {
        
        $records = $rs->GetArray();   // do i need to add the check empty

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

####################################################
#   Display transactions for the given invoice_id  #  // Only basic return needed for now
####################################################

function display_transactions($db, $invoice_id){
    
    $sql ="SELECT * FROM ".PRFX."payment_transactions WHERE invoice_id =".$db->qstr($invoice_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the invoice's transactions."));
        exit;
    } else {      
        
        return $rs->GetArray();
    }
    
}

/** Insert Functions **/

############################
#   insert transaction     #
############################

function insert_transaction($db, $customer_id, $workorder_id, $invoice_id,  $date, $method, $amount, $note) {
    
    $sql = "INSERT INTO ".PRFX."payment_transactions SET            
            employee_id     = ".$db->qstr( QFactory::getUser()->login_user_id   ).",
            customer_id     = ".$db->qstr( $customer_id                         ).",
            workorder_id    = ".$db->qstr( $workorder_id                        ).",
            invoice_id      = ".$db->qstr( $invoice_id                          ).",
            date            = ".$db->qstr( $date                                ).",
            method          = ".$db->qstr( $method                              ).",
            amount          = ".$db->qstr( $amount                              ).",
            note            = ".$db->qstr( $note                                );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert transaction into the database."));
        exit;
        
    } else {
        
        // Log activity 
        $record = _gettext("Payment made on Invoice").' '.$invoice_id.' '._gettext("with transaction").' '.$db->Insert_ID().'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $customer_id, $workorder_id, $invoice_id);
                
    }    
    
}

#####################################################
#   Insert transaction created by a payment method  #
#####################################################

function insert_payment_method_transaction($db, $invoice_id, $date, $amount, $method_name, $method_type, $method_note, $note) {
    
    // Get invoice details
    $invoice_details = get_invoice_details($db, $invoice_id);
    
    // Convert date into timestamp
    $date =  date_to_timestamp($date);
            
    // Make amount into the correct format for the logs
    $formatted_amount = sprintf( "%.2f", $amount);
           
    // Other Variables
    $currency_sym               = get_company_details($db, 'currency_symbol');    
    $customer_id                = $invoice_details['customer_id'];
    $workorder_id               = $invoice_details['workorder_id'];
    
    // Calculate the new balance and paid amount    
    $new_invoice_paid_amount    = $invoice_details['paid_amount'] + $amount;
    $new_invoice_balance        = $invoice_details['balance'] - $amount;
            
    /* Partial Payment Transaction */
    
    if($new_invoice_balance != 0 ) {
        
        // Set the new invoice status
        $new_status = 'partially_paid';

        // Update the invoice        
        update_invoice_transaction_only($db, $invoice_id, $new_invoice_paid_amount, $new_invoice_balance, '0');

        // Transaction log        
        $log_msg = _gettext("Partial Payment made by")." $method_name "._gettext("for")." $currency_sym$formatted_amount, "._gettext("Balance due").": $currency_sym$new_invoice_balance, $method_note, "._gettext("Note").": $note";
        
        // Insert Transaction into log       
        insert_transaction($db, $customer_id, $workorder_id, $invoice_id, $date, $method_type, $amount, $log_msg);
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $workorder_id, _gettext("Transaction inserted by").' '.QFactory::getUser()->login_display_name.' - '.$log_msg);           

    }

    /* Full payment or the new Balance is 0.00 */
    
    if($new_invoice_balance == 0 ) {
        
        // Set the new invoice status
        $new_status = 'paid';   

        // Update the invoice
        update_invoice_transaction_only($db, $invoice_id, $new_invoice_paid_amount, $new_invoice_balance, '1', time());   

        // log message   
        if($amount < $invoice_details['total']) {
            
            // Transaction is a partial payment
            $log_msg = _gettext("Partial Payment made by")." $method_name "._gettext("for")." $currency_sym$formatted_amount, "._gettext("closing the invoice.")." $method_note, "._gettext("Note").": $note";
        
            
        } else {
            
            // Transaction is payment for the full amount
            $log_msg = _gettext("Full Payment made by")." $method_name "._gettext("for")." $currency_sym$formatted_amount, "._gettext("closing the invoice.")." $method_note, "._gettext("Note").": $note";
                    
        }

        // Insert Transaction into log       
        insert_transaction($db, $customer_id, $workorder_id, $invoice_id, $date, $method_type, $amount, $log_msg);
        
        // Create a Workorder History Note
        insert_workorder_history_note($db, $workorder_id, _gettext("Transaction inserted by").' '.QFactory::getUser()->login_display_name.' - '.$log_msg);            

    }
    
    // Update invoice status only if it is different from the current status
    if($invoice_details['status'] != $new_status) {
        update_invoice_status($db, $invoice_id, $new_status);        
    }
    
    // Update last active record    
    update_customer_last_active($db, $invoice_details['customer_id']);
    update_workorder_last_active($db, $invoice_details['workorder_id']);
    update_invoice_last_active($db, $invoice_id);
    
    return;
        
}

/** Get Functions **/

##########################
#  Get payment details   # // this gets payment details like bank details (not transactions)
##########################

function get_payment_details($db, $item = null){
    
    $sql = "SELECT * FROM ".PRFX."payment";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment details."));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

################################################
#   Get get active system payment methods      # // If i dont have 'system_method_id' and 'active' in the select, the array is not built correctly
################################################

function get_active_payment_system_methods($db) {
    
    $sql = "SELECT
            system_method_id, active
            FROM ".PRFX."payment_system_methods
            WHERE active='1'";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get active payment methods."));
        exit;
    } else {
        
        return $rs->GetAssoc();
        
    }
    
}

#####################################
#    Get system Payment methods     #  // These are the payment methods that QWcrm can accept for invoices
#####################################

function get_payment_system_methods($db) {
    
    $sql = "SELECT * FROM ".PRFX."payment_system_methods";

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment system methods."));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

#####################################
#    Get manual Payment methods     #  // These are the payment methods that are used to purchase things (i.e. expenses)
#####################################

function get_payment_manual_methods($db) {
    
    $sql = "SELECT * FROM ".PRFX."payment_manual_methods";

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment manual methods."));
        exit;
    } else {
        
        //return $rs->GetRowAssoc();
        return $rs->GetArray();
        
    }    
    
}

#########################################
#   Get get active credit cards         #
#########################################

function get_active_credit_cards($db) {

    $sql = "SELECT card_key, display_name FROM ".PRFX."payment_credit_cards WHERE active='1'";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the active credit cards."));
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

#####################################
#  Get Credit card name from type   #
#####################################

function get_credit_card_display_name_from_key($db, $card_key) {
    
    $sql = "SELECT display_name FROM ".PRFX."payment_credit_cards WHERE card_key=".$db->qstr($card_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Credit Card Name by key."));
        exit;
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

/** Update Functions **/

#####################################
#    Update Payment details         #
#####################################

function update_payment_settings($db, $VAR) {
    
    $sql = "UPDATE ".PRFX."payment SET            
            bank_account_name       =". $db->qstr( $VAR['bank_account_name']        ).",
            bank_name               =". $db->qstr( $VAR['bank_name']                ).",
            bank_account_number     =". $db->qstr( $VAR['bank_account_number']      ).",
            bank_sort_code          =". $db->qstr( $VAR['bank_sort_code']           ).",
            bank_iban               =". $db->qstr( $VAR['bank_iban']                ).",
            paypal_email            =". $db->qstr( $VAR['paypal_email']             ).",        
            bank_transaction_msg    =". $db->qstr( $VAR['bank_transaction_message'] ).",
            cheque_payable_to_msg   =". $db->qstr( $VAR['cheque_payable_to_msg']    ).",
            invoice_footer_msg      =". $db->qstr( $VAR['invoice_footer_msg']       );            

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update payment options."));
        exit;
    } else {
        
        // Log activity        
        //write_record_to_activity_log(_gettext("Payment settings updated."));

        return;
        
    }
    
}

#####################################
#   Update Payment Methods status   #
#####################################

function update_active_payment_system_methods($db, $VAR) {
    
    // Array of all valid payment methods (name / active state)
    $payment_system_methods =
            array(
                array('system_method_id'=>'credit_card',       'active'=>$VAR['credit_card']      ),
                array('system_method_id'=>'cheque',            'active'=>$VAR['cheque']           ),
                array('system_method_id'=>'cash',              'active'=>$VAR['cash']             ),
                array('system_method_id'=>'gift_certificate',  'active'=>$VAR['gift_certificate'] ),
                array('system_method_id'=>'paypal',            'active'=>$VAR['paypal']           ),
                array('system_method_id'=>'direct_deposit',    'active'=>$VAR['direct_deposit']   )    
            );
   
    // Loop throught the various payment system methods and update the database
    foreach($payment_system_methods as $payment_method) {
        
        // When not selected no value is sent - this set zero for those
        if($payment_method['active'] == ''){$payment_method['active'] = '0';}
        
        $sql = "UPDATE ".PRFX."payment_system_methods
                SET active=". $db->qstr( $payment_method['active'] )."
                WHERE system_method_id=". $db->qstr( $payment_method['system_method_id'] ); 
        
        if(!$rs = $db->execute($sql)) {
            force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a payment method active state."));
            exit;
        }
        
    }
    
    // Log activity        
    //write_record_to_activity_log(_gettext("Available payment methods updated."));
    
}

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

####################################################
#      Check if a payment method is active         #
####################################################

function check_payment_method_is_active($db, $method) {
    
    $sql = "SELECT active FROM ".PRFX."payment_system_methods WHERE system_method_id=".$db->qstr($method);   
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the payment method is active."));
        exit;
    }
    
    if($rs->fields['active'] != 1) {
        
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
        
        $smarty->assign('warning_msg', _gettext("You can not enter a transaction with a zero (0.00) amount."));
        
        return false;
        
    }

    // Is the transaction larger than the outstanding invoice balance, this is not allowed
    if($amount > get_invoice_details($db, $invoice_id, 'balance')){
        
        $smarty->assign('warning_msg', _gettext("You can not enter more than the outstanding balance of the invoice."));
        
        return false;
        
    }
    
    return true;
   
}