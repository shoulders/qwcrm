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

function display_payments($db, $order_by = 'payment_id', $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $method = null, $employee_id = null, $customer_id = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    $smarty = QSmarty::getInstance();
   
    /* Records Search */
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";
    
    // Restrict results by search category (customer) and search term
    if($search_category == 'customer_display_name') {$whereTheseRecords .= " AND ".PRFX."customer_records.display_name LIKE '%$search_term%'";}
    
   // Restrict results by search category (employee) and search term
    elseif($search_category == 'employee_display_name') {$whereTheseRecords .= " AND ".PRFX."user_records.display_name LIKE '%$search_term%'";}     
    
    // Restrict results by search category and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."payment_records.$search_category LIKE '%$search_term%'";} 
    
    /* Filter the Records */
    
    // Restrict by Status
    if($method) {$whereTheseRecords .= " AND ".PRFX."payment_records.method= ".$db->qstr($method);}        

    // Restrict by Employee
    if($employee_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$db->qstr($employee_id);}

    // Restrict by Customer
    if($customer_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.customer_id=".$db->qstr($customer_id);}
    
    // Restrict by Invoice
    if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$db->qstr($invoice_id);}    
    
    /* The SQL code */
    
    $sql =  "SELECT                
            ".PRFX."customer_records.display_name AS customer_display_name,
                
            ".PRFX."payment_records.payment_id,
            ".PRFX."payment_records.employee_id,
            ".PRFX."payment_records.customer_id,
            ".PRFX."payment_records.workorder_id,
            ".PRFX."payment_records.invoice_id,
            ".PRFX."payment_records.date,
            ".PRFX."payment_records.method,
            ".PRFX."payment_records.amount,
            ".PRFX."payment_records.note,
                
            ".PRFX."user_records.display_name AS employee_display_name
               
            FROM ".PRFX."payment_records
            LEFT JOIN ".PRFX."user_records ON ".PRFX."payment_records.employee_id   = ".PRFX."user_records.user_id
            LEFT JOIN ".PRFX."customer_records ON ".PRFX."payment_records.customer_id = ".PRFX."customer_records.customer_id                 
            ".$whereTheseRecords."
            GROUP BY ".PRFX."payment_records.".$order_by."
            ORDER BY ".PRFX."payment_records.".$order_by."
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching payments."));
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
        $previous_page_no = ($page_no - 1);        
        $smarty->assign('previous_page_no', $previous_page_no);          
        
        // Assign the next page        
        if($page_no == $total_pages) {$next_page_no = 0;}
        elseif($page_no < $total_pages) {$next_page_no = ($page_no + 1);}
        else {$next_page_no = $total_pages;}
        $smarty->assign('next_page_no', $next_page_no);
        
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching payments."));
    } else {
        
        $records = $rs->GetArray();   // do i need to add the check empty

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** Insert Functions **/

############################
#   Insert Payment         #
############################

function insert_payment($db, $VAR) {
    
    $db = QFactory::getDbo();

    $invoice_details = get_invoice_details($db, $VAR['invoice_id']);
    
    $sql = "INSERT INTO ".PRFX."payment_records SET            
            employee_id     = ".$db->qstr( QFactory::getUser()->login_user_id          ).",
            customer_id     = ".$db->qstr( $invoice_details['customer_id']             ).",
            workorder_id    = ".$db->qstr( $invoice_details['workorder_id']            ).",
            invoice_id      = ".$db->qstr( $VAR['invoice_id']                          ).",
            date            = ".$db->qstr( date_to_timestamp($VAR['date'])             ).",
            method          = ".$db->qstr( $VAR['method_type']                         ).",
            amount          = ".$db->qstr( $VAR['amount']                              ).",
            note            = ".$db->qstr( $VAR['note']                                );

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert payment into the database."));
        
    } else {
        
        // Get Payment Record ID
        $insert_id = $db->Insert_ID();
        
        // Recalculate invoice totals
        recalculate_invoice($db, $VAR['invoice_id']);
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $invoice_details['workorder_id'], _gettext("Payment").' '.$insert_id.' '._gettext("added by").' '.QFactory::getUser()->login_display_name);
        
        // Log activity        
        $record = _gettext("Payment").' '.$insert_id.' '._gettext("added.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['customer_id'], $invoice_details['workorder_id'], $VAR['invoice_id']);
        
        // Update last active record    
        update_customer_last_active($db, $invoice_details['customer_id']);
        update_workorder_last_active($db, $invoice_details['workorder_id']);
        update_invoice_last_active($db, $VAR['invoice_id']);        
                
    }    
    
}

/** Get Functions **/

#############################
#  Get payment details      #
#############################

function get_payment_details($db, $payment_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."payment_records  WHERE payment_id=".$db->qstr($payment_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

##########################
#  Get payment options   #
##########################

function get_payment_options($db, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."payment_options";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment options."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

################################################
#   Get get active accepted payment methods    # // If i dont have 'accepted_method_id' and 'active' in the select, the array is not built correctly
################################################

function get_payment_active_accepted_methods($db) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT
            accepted_method_id, active
            FROM ".PRFX."payment_accepted_methods
            WHERE active='1'";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get active payment methods."));
    } else {
        
        return $rs->GetAssoc();
        
    }
    
}

#####################################
#    Get Accepted Payment methods   #  // These are the payment methods that QWcrm can accept for invoices
#####################################

function get_payment_accepted_methods($db) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."payment_accepted_methods";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment system methods."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

#####################################
#    Get Purchase Payment methods   #  // These are the payment methods that are used to purchase things (i.e. expenses)
#####################################

function get_payment_purchase_methods($db) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."payment_purchase_methods";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment manual methods."));
    } else {
        
        //return $rs->GetRowAssoc();
        return $rs->GetArray();
        
    }    
    
}

#########################################
#   Get get active credit cards         #
#########################################

function get_active_credit_cards($db) {
    
    $db = QFactory::getDbo();

    $sql = "SELECT card_key, display_name FROM ".PRFX."payment_credit_cards WHERE active='1'";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the active credit cards."));
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
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."payment_credit_cards WHERE card_key=".$db->qstr($card_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Credit Card Name by key."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

/** Update Functions **/

#####################
#   update payment  #
#####################

function update_payment($db, $VAR) {    
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."payment_records SET        
            employee_id     = ".$db->qstr( $VAR['employee_id']              ).",
            customer_id     = ".$db->qstr( $VAR['customer_id']              ).",
            workorder_id    = ".$db->qstr( $VAR['workorder_id']             ).",
            invoice_id      = ".$db->qstr( $VAR['invoice_id']               ).",
            date            = ".$db->qstr( date_to_timestamp($VAR['date'])  ).",
            method          = ".$db->qstr( $VAR['method']                   ).",
            amount          = ".$db->qstr( $VAR['amount']                   ).",
            note            = ".$db->qstr( $VAR['note']                     )."
            WHERE payment_id =". $db->qstr( $VAR['payment_id']              );

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the payment details."));
        
    } else {
                
        // Recalculate invoice totals
        recalculate_invoice($db, $VAR['invoice_id']);       

        // Create a Workorder History Note       
        insert_workorder_history_note($db, $VAR['workorder_id'], _gettext("Payment").' '.$VAR['payment_id'].' '._gettext("updated by").' '.QFactory::getUser()->login_display_name);           

        // Log activity 
        $record = _gettext("Payment").' '.$VAR['payment_id'].' '._gettext("updated.");
        write_record_to_activity_log($record, $VAR['employee_id'], $VAR['customer_id'], $VAR['workorder_id'], $VAR['invoice_id']);
        
        // Update last active record    
        update_customer_last_active($db, $VAR['customer_id']);
        update_workorder_last_active($db, $VAR['workorder_id']);
        update_invoice_last_active($db, $VAR['invoice_id']);
    
    }
    
    return;
        
}

#####################################
#    Update Payment options         #
#####################################

function update_payment_options($db, $VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."payment_options SET            
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update payment options."));
    } else {
        
        // Log activity        
        //write_record_to_activity_log(_gettext("Payment settings updated."));

        return;
        
    }
    
}

#####################################
#   Update Payment Methods status   #
#####################################

function update_active_payment_accepted_methods($db, $VAR) {
    
    $db = QFactory::getDbo();
    
    // Array of all valid payment methods (name / active state)
    $payment_accepted_methods =
            array(
                array('accepted_method_id'=>'credit_card',       'active'=>$VAR['credit_card']      ),
                array('accepted_method_id'=>'cheque',            'active'=>$VAR['cheque']           ),
                array('accepted_method_id'=>'cash',              'active'=>$VAR['cash']             ),
                array('accepted_method_id'=>'gift_certificate',  'active'=>$VAR['gift_certificate'] ),
                array('accepted_method_id'=>'paypal',            'active'=>$VAR['paypal']           ),
                array('accepted_method_id'=>'direct_deposit',    'active'=>$VAR['direct_deposit']   )    
            );
   
    // Loop throught the various payment system methods and update the database
    foreach($payment_accepted_methods as $payment_method) {
        
        // When not selected no value is sent - this set zero for those
        if($payment_method['active'] == ''){$payment_method['active'] = '0';}
        
        $sql = "UPDATE ".PRFX."payment_accepted_methods
                SET active=". $db->qstr( $payment_method['active'] )."
                WHERE accepted_method_id=". $db->qstr( $payment_method['accepted_method_id'] ); 
        
        if(!$rs = $db->execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a payment method active state."));
        }
        
    }
    
    // Log activity        
    //write_record_to_activity_log(_gettext("Available payment methods updated."));
    
}

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Payement                #
#####################################

function delete_payment($db, $payment_id) {
    
    $db = QFactory::getDbo();
    
    // Get payment details before deleting the record
    $payment_details = get_payment_details($db, $payment_id);
    
    $sql = "DELETE FROM ".PRFX."payment_records WHERE payment_id=".$db->qstr($payment_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the payment record."));
    } else {
        
        // Recalculate invoice totals
        recalculate_invoice($db, $payment_details['invoice_id']);
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $payment_details['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("has been deleted by").' '.QFactory::getUser()->login_display_name);           
        
        // Log activity        
        $record = _gettext("Payment").' '.$payment_id.' '._gettext("has been deleted.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $payment_details['customer_id'], $payment_details['workorder_id'], $payment_details['invoice_id']);
                
        // Update last active record    
        update_customer_last_active($db, $payment_details['customer_id']);
        update_workorder_last_active($db, $payment_details['workorder_id']);
        update_invoice_last_active($db, $payment_details['invoice_id']);
        
        return true;        
        
    } 
    
}

/** Other Functions **/

####################################################
#      Check if a payment method is active         #
####################################################

function check_payment_method_is_active($db, $method) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT active FROM ".PRFX."payment_accepted_methods WHERE accepted_method_id=".$db->qstr($method);   
    
    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the payment method is active."));
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
    
    $db = QFactory::getDbo();
    $smarty = QSmarty::getInstance();

    // Has a zero amount been submitted, this is not allowed
    if($amount == 0){
        
        $smarty->assign('warning_msg', _gettext("You can not enter a payment with a zero (0.00) amount."));
        
        return false;
        
    }

    // Is the payment larger than the outstanding invoice balance, this is not allowed
    if($amount > get_invoice_details($db, $invoice_id, 'balance')){
        
        $smarty->assign('warning_msg', _gettext("You can not enter more than the outstanding balance of the invoice."));
        
        return false;
        
    }
    
    return true;
   
}

#########################################
#  Sum Payments Sub Total (ny inovice)  #
#########################################

function payments_sub_total($db, $invoice_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(amount) AS sub_total_sum FROM ".PRFX."payment_records WHERE invoice_id=". $db->qstr($invoice_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to calculate the payments sub total."));
    } else {
        
        return $rs->fields['sub_total_sum'];
        
    }    
    
}