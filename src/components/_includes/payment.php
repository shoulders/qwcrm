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

//function display_payments($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no =  null, $search_category = null, $search_term = null, $method = null, $employee_id = null, $client_id = null, $invoice_id = null) {

function display_payments($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no =  null, $search_category = null, $search_term = null, $type = null, $method = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'payment_id';
    $havingTheseRecords = '';
   
    /* Records Search */
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";
    
    // Restrict results by search category (client) and search term
    if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
   // Restrict results by search category (employee) and search term
    elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$db->qstr('%'.$search_term.'%');}     
    
    // Restrict results by search category and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."payment_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');} 
    
    /* Filter the Records */
    
    // Restrict by Type
    if($type) {
        
        // All received monies
        if($type == 'received') {
            
            $whereTheseRecords .= " AND ".PRFX."payment_records.type= ".$db->qstr('invoice');
        
        // All transmitted monies
        } elseif($type == 'transmitted') {
            
            $whereTheseRecords .= " AND ".PRFX."payment_records.type= ".$db->qstr('expense')."
                                    OR ".PRFX."payment_records.type= ".$db->qstr('refund');
        
        // Return records for the given type
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."payment_records.type= ".$db->qstr($type);
            
        }
        
    }
    
    // Restrict by method
    if($method) {$whereTheseRecords .= " AND ".PRFX."payment_records.method= ".$db->qstr($method);}
    
    // Restrict by status
    if($status) {$whereTheseRecords .= " AND ".PRFX."payment_records.status= ".$db->qstr($status);}   

    // Restrict by Employee
    if($employee_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$db->qstr($employee_id);}

    // Restrict by Client
    if($client_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$db->qstr($client_id);}
    
    // Restrict by Invoice
    if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$db->qstr($invoice_id);}    
    
    /* The SQL code */
    
    $sql =  "SELECT
            ".PRFX."payment_records.*,
            IF(company_name !='', company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,
            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name
               
            FROM ".PRFX."payment_records
            LEFT JOIN ".PRFX."user_records ON ".PRFX."payment_records.employee_id = ".PRFX."user_records.user_id
            LEFT JOIN ".PRFX."client_records ON ".PRFX."payment_records.client_id = ".PRFX."client_records.client_id
                
            ".$whereTheseRecords."
            GROUP BY ".PRFX."payment_records.".$order_by."
            ".$havingTheseRecords."
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

function insert_payment($qpayment) {
    
    $db = QFactory::getDbo();

    $invoice_details = get_invoice_details($qpayment['invoice_id']);
    
    $sql = "INSERT INTO ".PRFX."payment_records SET            
            employee_id     = ".$db->qstr( QFactory::getUser()->login_user_id          ).",
            client_id       = ".$db->qstr( $invoice_details['client_id']               ).",
            workorder_id    = ".$db->qstr( $invoice_details['workorder_id']            ).",
            invoice_id      = ".$db->qstr( $qpayment['invoice_id']                     ).",              
            date            = ".$db->qstr( date_to_mysql_date($qpayment['date'])       ).",
            type            = ".$db->qstr( $qpayment['type']                           ).",
            method          = ".$db->qstr( $qpayment['method']                         ).",
            status          = 'valid',
            amount          = ".$db->qstr( $qpayment['amount']                         ).",
            note            = ".$db->qstr( $qpayment['note']                           );

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert payment into the database."));
        
    } else {
        
        // Get Payment Record ID
        $insert_id = $db->Insert_ID();
        
        // Recalculate invoice totals
        recalculate_invoice($qpayment['invoice_id']);
        
        // Create a Workorder History Note       
        insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Payment").' '.$insert_id.' '._gettext("added by").' '.QFactory::getUser()->login_display_name);
        
        // Log activity        
        $record = _gettext("Payment").' '.$insert_id.' '._gettext("added.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $qpayment['invoice_id']);
        
        // Update last active record    
        update_client_last_active($invoice_details['client_id']);
        update_workorder_last_active($invoice_details['workorder_id']);
        update_invoice_last_active($qpayment['invoice_id']);
        
        // Return the payment_id
        return $insert_id;
                
    }    
    
}

/** Get Functions **/

#############################
#  Get payment details      #
#############################

function get_payment_details($payment_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."payment_records WHERE payment_id=".$db->qstr($payment_id);
    
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

function get_payment_options($item = null) {
    
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
#   Get get Payment Methods                    #
################################################

function get_payment_methods($direction = null, $status = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT *
            FROM ".PRFX."payment_methods";
    
    // If the send direction is specified
    if($direction == 'send') {
        $sql .= "\nWHERE send = '1'";
              
    // If the receive direction is specified    
    } elseif($direction == 'receive') {        
        $sql .= "\nWHERE receive = '1'";        
    }
    
    // Only return methods that are enabled
    if($direction && $status == 'enabled') { 
        $sql .= "\nAND enabled = '1'";        
    }
    
    if(!$rs = $db->execute($sql)) {        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment method types."));
    } else {
        
        return $rs->GetArray();            
        
    }    
    
}

#####################################
#    Get Payment Types              #  // i.e. invoice, refund
#####################################

function get_payment_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."payment_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment types."));
    } else {
        
        //return $rs->GetRowAssoc();
        return $rs->GetArray();
        
    }    
    
}

#####################################
#    Get Payment Statuses           #
#####################################

function get_payment_statuses() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."payment_statuses";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment statuses."));
    } else {
        
        //return $rs->GetRowAssoc();
        return $rs->GetArray();
        
    }    
    
}

#########################################
#   Get get active credit cards         #
#########################################

function get_payment_active_card_types() {
    
    $db = QFactory::getDbo();

    $sql = "SELECT
            card_key,
            display_name
            FROM ".PRFX."payment_card_types
            WHERE active='1'";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the active cards."));
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
#  Get Card name from type          #
#####################################

function get_card_display_name_from_key($card_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."payment_card_types WHERE card_key=".$db->qstr($card_key);

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

function update_payment($VAR) {    
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."payment_records SET        
            employee_id     = ".$db->qstr( $VAR['employee_id']              ).",
            client_id       = ".$db->qstr( $VAR['client_id']                ).",
            workorder_id    = ".$db->qstr( $VAR['workorder_id']             ).",
            invoice_id      = ".$db->qstr( $VAR['invoice_id']               ).",            
            date            = ".$db->qstr( date_to_mysql_date($VAR['date']) ).",
            type            = ".$db->qstr( $VAR['type']                     ).",
            method          = ".$db->qstr( $VAR['method']                   ).",
            amount          = ".$db->qstr( $VAR['amount']                   ).",
            note            = ".$db->qstr( $VAR['note']                     )."
            WHERE payment_id =". $db->qstr( $VAR['payment_id']              );

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the payment details."));
        
    } else {
                
        // Recalculate invoice totals
        recalculate_invoice($VAR['invoice_id']);       

        // Create a Workorder History Note       
        insert_workorder_history_note($VAR['workorder_id'], _gettext("Payment").' '.$VAR['payment_id'].' '._gettext("updated by").' '.QFactory::getUser()->login_display_name);           

        // Log activity 
        $record = _gettext("Payment").' '.$VAR['payment_id'].' '._gettext("updated.");
        write_record_to_activity_log($record, $VAR['employee_id'], $VAR['client_id'], $VAR['workorder_id'], $VAR['invoice_id']);
        
        // Update last active record    
        update_client_last_active($VAR['client_id']);
        update_workorder_last_active($VAR['workorder_id']);
        update_invoice_last_active($VAR['invoice_id']);
    
    }
    
    return;
        
}

#####################################
#    Update Payment options         #
#####################################

function update_payment_options($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."payment_options SET            
            bank_account_name           =". $db->qstr( $VAR['bank_account_name']            ).",
            bank_name                   =". $db->qstr( $VAR['bank_name']                    ).",
            bank_account_number         =". $db->qstr( $VAR['bank_account_number']          ).",
            bank_sort_code              =". $db->qstr( $VAR['bank_sort_code']               ).",
            bank_iban                   =". $db->qstr( $VAR['bank_iban']                    ).",
            paypal_email                =". $db->qstr( $VAR['paypal_email']                 ).",        
            invoice_bank_transfer_msg   =". $db->qstr( $VAR['invoice_bank_transfer_msg']    ).",
            invoice_cheque_msg          =". $db->qstr( $VAR['invoice_cheque_msg']           ).",
            invoice_footer_msg          =". $db->qstr( $VAR['invoice_footer_msg']           );            

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update payment options."));
    } else {
        
        // Log activity 
        // Done in payment:options controller
        
        return;
        
    }
    
}

#####################################
#  Update Payment Methods statuses  #
#####################################

function update_payment_methods_statuses($payment_methods) {
    
    $db = QFactory::getDbo();
    
    // Loop throught the various payment system methods and update the database
    foreach($payment_methods as $payment_method) {
        
        // When not checked, no value is sent so this sets zero for those cases
        if(!isset($payment_method['send'])) { $payment_method['send'] = '0'; }
        if(!isset($payment_method['receive'])) { $payment_method['receive'] = '0'; }
        if(!isset($payment_method['enabled'])) { $payment_method['enabled'] = '0'; }
        
        $sql = "UPDATE ".PRFX."payment_methods
                SET
                send                    = ". $db->qstr($payment_method['send']).",
                receive                 = ". $db->qstr($payment_method['receive']).",
                enabled                 = ". $db->qstr($payment_method['enabled'])."   
                WHERE payment_method_id = ". $db->qstr($payment_method['payment_method_id']); 
        
        if(!$rs = $db->execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update payment method statuses."));
        }
        
    }
    
    // Log activity 
    // Done in payment:options controller
    
    return;
    
}

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Payment                 #
#####################################

function delete_payment($payment_id) {
    
    $db = QFactory::getDbo();
    
    // Get payment details before deleting the record
    $payment_details = get_payment_details($payment_id);
        
    $sql = "UPDATE ".PRFX."payment_records SET        
            employee_id     = '',
            client_id       = '',
            workorder_id    = '',
            invoice_id      = '',            
            date            = '0000-00-00',
            type            = '',
            method          = '',
            status          = 'deleted',
            amount          = '0.00',
            note            = ''
            WHERE payment_id =". $db->qstr( $payment_id );    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the payment record."));
    } else {
        
        // Recalculate invoice totals
        recalculate_invoice($payment_details['invoice_id']);
        
        // Create a Workorder History Note       
        insert_workorder_history_note($payment_details['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("has been deleted by").' '.QFactory::getUser()->login_display_name);           
        
        // Log activity        
        $record = _gettext("Payment").' '.$payment_id.' '._gettext("has been deleted.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $payment_details['client_id'], $payment_details['workorder_id'], $payment_details['invoice_id']);
                
        // Update last active record    
        update_client_last_active($payment_details['client_id']);
        update_workorder_last_active($payment_details['workorder_id']);
        update_invoice_last_active($payment_details['invoice_id']);
        
        return true;        
        
    } 
    
}

/** Other Functions **/

##########################################################
#  Check if the payment status allows editing            #       
##########################################################

 function check_payment_can_be_edited($payment_id) {
     
    // Get the payment details
    $payment_details = get_payment_details($payment_id);    
           
    // Is Deleted
    if($payment_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The payment status cannot be changed because it has been deleted."));
        return false;        
    }

    // All checks passed
    return true;    
     
}

####################################################
#      Check if a payment method is active         #
####################################################

function check_payment_method_is_active($method, $direction = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT *
            FROM ".PRFX."payment_methods
            WHERE payment_method_id=".$db->qstr($method);
        
    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the payment method is active."));    
        
    } else {
    
        // If module is disabled, always return disabled for both directions
        if(!$rs->fields['enabled']) { return false; }
        
        // If send direction is specified
        if($direction == 'send') { return $rs->fields['send']; }
        
        // If receive direction is specified
        if($direction == 'receive') { return $rs->fields['receive']; }
        
        // Fallback behaviour
        return true;
        
    }
    
}

#########################################################################
#   validate and calculate new invoice totals for the payment method    #
#########################################################################

function validate_payment_method_totals($invoice_id, $amount) {
    
    $smarty = QFactory::getSmarty();

    // Has a zero amount been submitted, this is not allowed
    if($amount == 0){
        
        $smarty->assign('warning_msg', _gettext("You can not enter a payment with a zero (0.00) amount."));
        
        return false;
        
    }

    // Is the payment larger than the outstanding invoice balance, this is not allowed
    if($amount > get_invoice_details($invoice_id, 'balance')){
        
        $smarty->assign('warning_msg', _gettext("You can not enter more than the outstanding balance of the invoice."));
        
        return false;
        
    }
    
    return true;
   
}

#########################################
#  Sum Invoice Payments Sub Total       #
#########################################

function payments_sub_total($invoice_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(amount) AS sub_total_sum FROM ".PRFX."payment_records WHERE invoice_id=". $db->qstr($invoice_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to calculate the payments sub total."));
    } else {
        
        return $rs->fields['sub_total_sum'];
        
    }    
    
}