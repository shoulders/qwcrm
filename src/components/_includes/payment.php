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

function display_payments($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no =  null, $search_category = null, $search_term = null, $type = null, $method = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null, $refund_id = null, $expense_id = null, $otherincome_id = null) {
    
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
            $whereTheseRecords .= " AND ".PRFX."payment_records.type IN ('invoice', 'otherincome')";
            
        // All sent monies
        } elseif($type == 'sent') {            
            $whereTheseRecords .= " AND ".PRFX."payment_records.type IN ('expense', 'refund')";        
            
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
    
    // Restrict by Refund
    if($refund_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.refund_id=".$db->qstr($refund_id);} 
    
    // Restrict by Expense
    if($expense_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$db->qstr($expense_id);} 
    
    // Restrict by Otherincome
    if($otherincome_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$db->qstr($otherincome_id);}             
    
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
    
    $sql = "INSERT INTO ".PRFX."payment_records SET            
            employee_id     = ".$db->qstr( QFactory::getUser()->login_user_id       ).",
            client_id       = ".$db->qstr( $qpayment['client_id']                   ).",
            workorder_id    = ".$db->qstr( $qpayment['workorder_id']                ).",
            invoice_id      = ".$db->qstr( $qpayment['invoice_id']                  ).",
            voucher_id      = ".$db->qstr( $qpayment['voucher_id']                  ).",               
            refund_id       = ".$db->qstr( $qpayment['refund_id']                   ).", 
            expense_id      = ".$db->qstr( $qpayment['expense_id']                  ).", 
            otherincome_id  = ".$db->qstr( $qpayment['otherincome_id']              ).",
            date            = ".$db->qstr( date_to_mysql_date($qpayment['date'])    ).",
            tax_system      = ".$db->qstr( get_company_details('tax_system')        ).",   
            type            = ".$db->qstr( $qpayment['type']                        ).",
            method          = ".$db->qstr( $qpayment['method']                      ).",
            status          = 'valid',
            amount          = ".$db->qstr( $qpayment['amount']                      ).",
            last_active     =". $db->qstr( mysql_datetime()                         ).",
            additional_info = ".$db->qstr( $qpayment['additional_info']             ).",
            note            = ".$db->qstr( $qpayment['note']                        );

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert payment into the database."));
        
    } else {
        
        // Get Payment Record ID
        if($payment_id = $db->Insert_ID()) {
        
            // Create a Workorder History Note       
            insert_workorder_history_note($qpayment['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("added by").' '.QFactory::getUser()->login_display_name);

            // Log activity        
            $record = _gettext("Payment").' '.$payment_id.' '._gettext("created.");
            write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $qpayment['client_id'], $qpayment['workorder_id'], $qpayment['invoice_id']);

            // Update last active record    
            update_client_last_active($qpayment['client_id']);
            update_workorder_last_active($qpayment['workorder_id']);
            update_invoice_last_active($qpayment['invoice_id']);

            // Return the payment_id
            return $payment_id;
        
        } else {
            
            // This statement might not be reached if insert payment fails
            return false;
            
        }
        
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
            type_key,
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

function get_card_display_name_from_key($type_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."payment_card_types WHERE type_key=".$db->qstr($type_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Credit Card Name by key."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

#####################################
#  Get status names as an array     #
#####################################

function get_payment_status_names() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT status_key, display_name
            FROM ".PRFX."payment_statuses";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Status Names."));
    } else {
        
        $records = $rs->GetAssoc();

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }  
    
}

#####################################
#  Get Card names as an array       #
#####################################

function get_payment_card_names() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT type_key, display_name
            FROM ".PRFX."payment_card_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Card Names."));
    } else {
        
        $records = $rs->GetAssoc();

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }  
    
}

##########################################
#    Get Payment additional info names   #
##########################################

function get_payment_additional_info_names() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT type_key, display_name
            FROM ".PRFX."payment_additional_info_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get payment additional info names."));
    } else {
        
        $records = $rs->GetAssoc();

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }  
    
}

/** Update Functions **/

#####################
#   update payment  #
#####################

function update_payment($qpayment) {    
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."payment_records SET        
            employee_id     = ".$db->qstr( QFactory::getUser()->login_user_id    ).",
            date            = ".$db->qstr( date_to_mysql_date($qpayment['date']) ).",
            amount          = ".$db->qstr( $qpayment['amount']                   ).",
            last_active     =". $db->qstr( mysql_datetime()                      ).",
            note            = ".$db->qstr( $qpayment['note']                     )."
            WHERE payment_id =". $db->qstr( $qpayment['payment_id']              );

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the payment details."));
        
    } else {
                
        // Create a Workorder History Note       
        insert_workorder_history_note($qpayment['workorder_id'], _gettext("Payment").' '.$qpayment['payment_id'].' '._gettext("updated by").' '.QFactory::getUser()->login_display_name);           

        // Log activity 
        $record = _gettext("Payment").' '.$qpayment['payment_id'].' '._gettext("updated.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $qpayment['client_id'], $qpayment['workorder_id'], $qpayment['invoice_id']);
        
        // Update last active record    
        update_client_last_active($qpayment['client_id']);
        update_workorder_last_active($qpayment['workorder_id']);
        update_invoice_last_active($qpayment['invoice_id']);
    
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
                WHERE method_key = ". $db->qstr($payment_method['method_key']); 
        
        if(!$rs = $db->execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update payment method statuses."));
        }
        
    }
    
    // Log activity 
    // Done in payment:options controller
    
    return;
    
}

############################
# Update Payment Status    #
############################

function update_payment_status($payment_id, $new_status, $silent = false) {
    
    $db = QFactory::getDbo();
    
    // Get payment details
    $payment_details = get_payment_details($payment_id);
    
    // if the new status is the same as the current one, exit
    if($new_status == $payment_details['status']) {        
        if (!$silent) { postEmulationWrite('warning_msg', _gettext("Nothing done. The new status is the same as the current status.")); }
        return false;
    }    
    
    $sql = "UPDATE ".PRFX."payment_records SET
            status               =". $db->qstr( $new_status      ).",
            last_active          =". $db->qstr( mysql_datetime() ).",
            WHERE payment_id     =". $db->qstr( $payment_id      );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Payment Status."));
        
    } else {        
        
        // Status updated message
        if (!$silent) { postEmulationWrite('information_msg', _gettext("Payment status updated.")); }
        
        // For writing message to log file, get payment status display name
        $payment_status_names = get_payment_status_names();
        $payment_status_display_name = _gettext($payment_status_names[$new_status]);
        
        // Create a Workorder History Note (Not Used)      
        insert_workorder_history_note($payment_details['workorder_id'], _gettext("Payment Status updated to").' '.$payment_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Expense").' '.$payment_id.' '._gettext("Status updated to").' '.$payment_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $payment_details['client_id'], $payment_details['workorder_id'], $payment_details['invoice_id']);
        
        // Update last active record (Not Used)
        update_client_last_active($payment_details['client_id']);
        update_workorder_last_active($payment_details['workorder_id']);
        update_invoice_last_active($payment_details['invoice_id']);
        
        return true;
        
    }
    
}

/** Close Functions **/

function cancel_payment($payment_id) {
    
    // Make sure the payment can be cancelled
    if(!check_payment_can_be_cancelled($payment_id)) {        
        return false;
    }
    
    // Get payment details
    $payment_details = get_payment_details($payment_id);
    
    // Change the payment status to cancelled (I do this here to maintain consistency)
    update_payment_status($payment_id, 'cancelled');      
        
    // Create a Workorder History Note  
    insert_workorder_history_note($payment_details['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.');

    // Log activity        
    $record = _gettext("Expense").' '.$payment_id.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $payment_details['client_id'], $payment_details['workorder_id'], $payment_details['invoice_id']);
    
    // Update last active record
    update_client_last_active($payment_details['client_id']);
    update_workorder_last_active($payment_details['workorder_id']);
    update_invoice_last_active($payment_details['invoice_id']);
    
    return true;
    
}

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
            voucher_id      = '',
            refund_id       = '',
            expense_id      = '',
            otherincome_id  = '',
            date            = '0000-00-00',
            tax_system      = '',
            type            = '',
            method          = '',
            status          = 'deleted',
            amount          = '0.00',
            additional_info = '',
            last_active         = '0000-00-00 00:00:00',
            note            = ''
            WHERE payment_id =". $db->qstr( $payment_id );    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the payment record."));
    } else {
        
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

######################################################
#   Make sure the submitted payment amount is valid  #
######################################################

function validate_payment_amount($record_balance, $payment_amount) {
    
    $smarty = QFactory::getSmarty();
    
    // If a negative amount has been submitted. (This should not be allowed because of the <input> masks.)
    if($payment_amount < 0){
        
        $smarty->assign('warning_msg', _gettext("You can not enter a payment with a negative amount."));
        
        return false;
        
    }

    // Has a zero amount been submitted, this is not allowed
    if($payment_amount == 0){
        
        $smarty->assign('warning_msg', _gettext("You can not enter a payment with a zero (0.00) amount."));
        
        return false;
        
    }

    // Is the payment larger than the outstanding invoice balance, this is not allowed
    if($payment_amount > $record_balance){
        
        $smarty->assign('warning_msg', _gettext("You can not enter an payment with an amount greater than the outstanding balance."));
        
        return false;
        
    }
    
    return true;
   
}

####################################################
#      Check if a payment method is active         #
####################################################

function check_payment_method_is_active($method, $direction = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT *
            FROM ".PRFX."payment_methods
            WHERE method_key=".$db->qstr($method);
        
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

##########################################################
#  Check if the payment status is allowed to be changed  #  // not currently used
##########################################################

 function check_payment_status_can_be_changed($payment_id) {
     
    // Disable the ability to manually change status for now
    return false;
     
    // Get the payment details
    $payment_details = get_payment_details($payment_id);
    
    // Is the current payment method active, if not you cannot change status
    if(!check_payment_method_is_active($payment_details['method'], 'receive')) {
        //postEmulationWrite('warning_msg', _gettext("The payment status cannot be changed because it's current payment method is not available."));
        return false;        
    }
    
    // Is deleted
    if($payment_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The payment status cannot be changed because the payment has been deleted."));
        return false;        
    }
    
    // Is this an invoice payment and parent invoice has been refunded
    if($payment_details['type'] == 'invoice' && get_invoice_details($payment_details['invoice_id'], 'status') == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be changed because the parent invoice has been refunded."));
        return false;  
    }
        
    // All checks passed
    return true;     
     
 }

###############################################################
#   Check to see if the payment can be refunded (by status)   #  // not currently used - i DONT think i will use this , you cant refund a payment?
###############################################################

function check_payment_can_be_refunded($payment_id) {
    
    // Get the payment details
    $payment_details = get_payment_details($payment_id);
    
    // Is partially paid
    if($payment_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This payment cannot be refunded because the payment is partially paid."));
        return false;
    }
        
    // Is refunded
    if($payment_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be refunded because the payment has already been refunded."));
        return false;        
    }
    
    // Is cancelled
    if($payment_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be refunded because the payment has been cancelled."));
        return false;        
    }
    
    // Is deleted
    if($payment_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be refunded because the payment has been deleted."));
        return false;        
    }    
    
    // Is this an invoice payment and parent invoice has been refunded
    if($payment_details['type'] == 'invoice' && get_invoice_details($payment_details['invoice_id'], 'status') == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be refunded because the parent invoice has been refunded."));
        return false;  
    }
    
    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the payment can be cancelled              #  // not currently used
###############################################################

function check_payment_can_be_cancelled($payment_id) {
    
    // Get the payment details
    $payment_details = get_payment_details($payment_id);
    
    // Is cancelled
    if($payment_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be cancelled because the payment has already been cancelled."));
        return false;        
    }
    
    // Is deleted
    if($payment_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be cancelled because the payment has been deleted."));
        return false;        
    }
    
    // Is this an invoice payment and parent invoice has been refunded
    if($payment_details['type'] == 'invoice' && get_invoice_details($payment_details['invoice_id'], 'status') == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be cancelled because the parent invoice has been refunded."));
        return false;  
    }
    
    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the payment can be deleted                #
###############################################################

function check_payment_can_be_deleted($payment_id) {
    
    // Get the payment details
    $payment_details = get_payment_details($payment_id);
    
    // Is cancelled
    if($payment_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("This payment cannot be deleted because it has been cancelled."));
        return false;        
    }
    
    // Is deleted
    if($payment_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("This payment cannot be deleted because it already been deleted."));
        return false;        
    }
    
    // Is this an invoice payment and parent invoice has been refunded
    if($payment_details['type'] == 'invoice' && get_invoice_details($payment_details['invoice_id'], 'status') == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be deleted because the parent invoice has been refunded."));
        return false;  
    }
    
    // All checks passed
    return true;
    
}

##########################################################
#  Check if the payment status allows editing            #       
##########################################################

 function check_payment_can_be_edited($payment_id) {
     
    // Get the payment details
    $payment_details = get_payment_details($payment_id);
    
    // Is on a different tax system
    if($payment_details['tax_system'] != get_company_details('tax_system')) {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be edited because it is on a different Tax system."));
        return false;        
    }
    
    /* Is the current payment method active, if not you cannot change status
    if(!check_payment_method_is_active($payment_details['method'], 'receive')) {
        //postEmulationWrite('warning_msg', _gettext("The payment status cannot be edited because it's current payment method is not available."));
        return false;        
    }*/
    
    // Is Cancelled
    if($payment_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be edited because it has been cancelled."));
        return false;        
    }
           
    // Is Deleted
    if($payment_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be edited because it has been deleted."));
        return false;        
    }
    
    // Is this an invoice payment and parent invoice has been refunded
    if($payment_details['type'] == 'invoice' && get_invoice_details($payment_details['invoice_id'], 'status') == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The payment cannot be edited because the parent invoice has been refunded."));
        return false;  
    }

    // All checks passed
    return true;   
     
}

#########################################
#  Build additional_info JSON           #       
#########################################

 function build_additional_info_json($bank_transfer_reference = null, $card_type_key = null, $name_on_card = null, $cheque_number = null, $direct_debit_reference = null, $paypal_transaction_id = null) {
    
    $additional_info = array();
    
    // Build Array
    $additional_info['bank_transfer_reference'] = $bank_transfer_reference ? $bank_transfer_reference : '';
    $additional_info['card_type_key'] = $card_type_key ? $card_type_key : '';
    $additional_info['name_on_card'] = $name_on_card ? $name_on_card : '';
    $additional_info['cheque_number'] = $cheque_number ? $cheque_number : '';
    $additional_info['direct_debit_reference'] = $direct_debit_reference ? $direct_debit_reference : '';
    $additional_info['paypal_transaction_id'] = $paypal_transaction_id ? $paypal_transaction_id : '';

    // Return the JSON data
    return json_encode($additional_info);
     
}