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
#         Display expenses                          #
#####################################################

function display_expenses($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $item_type = null, $status = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'expense_id';    

    /* Records Search */
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";
    
    // Restrict results by search category and search term
    if($search_term) {$whereTheseRecords .= " AND ".PRFX."expense_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');}     
    
    /* Filter the Records */  
        
    // Restrict by Item Type
    if($item_type) { $whereTheseRecords .= " AND ".PRFX."expense_records.item_type= ".$db->qstr($item_type);}
    
    // Restrict by status
    if($status) {$whereTheseRecords .= " AND ".PRFX."expense_records.status= ".$db->qstr($status);} 
        
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."expense_records                                                   
            ".$whereTheseRecords."            
            GROUP BY ".PRFX."expense_records.".$order_by."
            ORDER BY ".PRFX."expense_records.".$order_by."
            ".$direction;           
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);

        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching expense records."));
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
        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching expense records."));
        
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

##########################################
#      Insert Expense                    #
##########################################

function insert_expense($qform) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."expense_records SET
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id ).",
            payee           =". $db->qstr( $qform['payee']                   ).",
            date            =". $db->qstr( date_to_mysql_date($qform['date'])).",
            tax_system      =". $db->qstr( QW_TAX_SYSTEM                   ).",              
            item_type       =". $db->qstr( $qform['item_type']               ).",
            unit_net        =". $db->qstr( $qform['unit_net']                ).",
            vat_tax_code    =". $db->qstr( $qform['vat_tax_code']            ).",
            unit_tax_rate   =". $db->qstr( $qform['unit_tax_rate']           ).",
            unit_tax        =". $db->qstr( $qform['unit_tax']                ).",
            unit_gross      =". $db->qstr( $qform['unit_gross'  ]            ).",
            status          =". $db->qstr( 'unpaid'                        ).",            
            opened_on       =". $db->qstr( mysql_datetime()                ).",              
            items           =". $db->qstr( $qform['items']                   ).",
            note            =". $db->qstr( $qform['note']                    );            

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the expense record into the database."));
    } else {
        
        /* This code is not used because I removed 'invoice_id'
         * Get related invoice details
        $invoice_details = get_invoice_details($qform['invoice_id']);
        
        // Create a Workorder History Note
        insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$db->Insert_ID().' '._gettext("added").' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
                
        // Log activity        
        $record = _gettext("Expense Record").' '.$db->Insert_ID().' '._gettext("created.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['workorder_id'], $invoice_details['client_id'], $qform['invoice_id']);
        
        // Update last active record
        update_client_last_active($invoice_details['client_id']);
        update_workorder_last_active($invoice_details['workorder_id']);
        update_invoice_last_active($qform['invoice_id']);*/
    
        return $db->Insert_ID();
        
    }
    
} 

/** Get Functions **/

##########################
#  Get Expense Details   #
##########################

function get_expense_details($expense_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."expense_records WHERE expense_id=".$db->qstr($expense_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the expense details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#    Get Expense Statuses           #
#####################################

function get_expense_statuses($restricted_statuses = false) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."expense_statuses";
    
    // Restrict statuses to those that are allowed to be changed by the user
    if($restricted_statuses) {
        $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
    }
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Expense statuses."));
    } else {
        
        return $rs->GetArray();     
        
    }    
    
}

######################################
#  Get Expense status display name   #
######################################

function get_expense_status_display_name($status_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."expense_statuses WHERE status_key=".$db->qstr($status_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the expense status display name."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

#####################################
#    Get Expense Types              #
#####################################

function get_expense_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."expense_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get expense types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#     Update Expense                #
#####################################

function update_expense($qform) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."expense_records SET
            employee_id         =". $db->qstr( QFactory::getUser()->login_user_id ).",
            payee               =". $db->qstr( $qform['payee']                    ).",            
            date                =". $db->qstr( date_to_mysql_date($qform['date']) ).",            
            item_type           =". $db->qstr( $qform['item_type']                ).",
            unit_net            =". $db->qstr( $qform['unit_net']                 ).",
            vat_tax_code        =". $db->qstr( $qform['vat_tax_code']             ).",
            unit_tax_rate       =". $db->qstr( $qform['unit_tax_rate']            ).",
            unit_tax            =". $db->qstr( $qform['unit_tax']                 ).",
            unit_gross          =". $db->qstr( $qform['unit_gross']               ).",
            last_active         =". $db->qstr( mysql_datetime()                 ).",
            items               =". $db->qstr( $qform['items']                    ).",
            note                =". $db->qstr( $qform['note']                     )."
            WHERE expense_id    =". $db->qstr( $qform['expense_id']               );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the expense details."));
    } else {
        
        /* This code is not used because I removed 'invoice_id'
         * Get related invoice details
        $invoice_details = get_invoice_details($qform['invoice_id']);
        
        // Create a Workorder History Note
        insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("updated").' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity
        $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("updated.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['workorder_id'], $invoice_details['client_id'], $qform['invoice_id']);
        
        // Update last active record
        update_client_last_active($invoice_details['client_id']);
        update_workorder_last_active($invoice_details['workorder_id']);
        update_invoice_last_active($qform['invoice_id']);*/ 
        
        return true;
        
    }
    
} 

############################
# Update Expense Status    #
############################

function update_expense_status($expense_id, $new_status, $silent = false) {
    
    $db = QFactory::getDbo();
    
    // Get expense details
    $expense_details = get_expense_details($expense_id);
    
    // if the new status is the same as the current one, exit
    if($new_status == $expense_details['status']) {        
        if (!$silent) { postEmulationWrite('msg_danger', _gettext("Nothing done. The new status is the same as the current status.")); }
        return false;
    }    
    
    // Unify Dates and Times
    $datetime = mysql_datetime();
    
    // Set the appropriate closed_on date
    $closed_on = ($new_status == 'paid') ? $datetime : '0000-00-00 00:00:00';
    
    $sql = "UPDATE ".PRFX."expense_records SET
            status             =". $db->qstr( $new_status   ).",
            closed_on          =". $db->qstr( $closed_on    ).",
            last_active        =". $db->qstr( $datetime     )."
            WHERE expense_id   =". $db->qstr( $expense_id   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update an Expense Status."));
        
    } else {        
        
        // Status updated message
        if (!$silent) { postEmulationWrite('msg_success', _gettext("Expense status updated.")); }
        
        // For writing message to log file, get expense status display name
        /*$expense_status_display_name = _gettext(get_expense_status_display_name($new_status));
        
        /* This code is not used because I removed 'invoice_id'
         * Get related invoice details
        $invoice_details = get_invoice_details($expense_details['invoice_id']);
        
        // Create a Workorder History Note (Not Used)      
        insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense Status updated to").' '.$expense_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Expense").' '.$expense_id.' '._gettext("Status updated to").' '.$expense_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $expense_details['invoice_id']);
        
        // Update last active record (Not Used)
        update_client_last_active($invoice_details['client_id']);
        update_workorder_last_active($invoice_details['workorder_id']);
        update_invoice_last_active($expense_details['invoice_id']);*/
        
        return true;
        
    }
    
}

/** Close Functions **/

#####################################
#   Cancel Expense                  #
#####################################

function cancel_expense($expense_id) {
    
    // Make sure the expense can be cancelled
    if(!check_expense_can_be_cancelled($expense_id)) {        
        return false;
    }
    
    // Get expense details
    $expense_details = get_expense_details($expense_id);
    
    // Get related invoice details
    //$invoice_details = get_invoice_details($expense_details['invoice_id']);
    
    // Change the expense status to cancelled (I do this here to maintain consistency)
    update_expense_status($expense_id, 'cancelled');      
        
    /*Create a Workorder History Note  
    insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.');*/

    // Log activity        
    $record = _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
        
    /* Log activity        
    $record = _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $expense_details['invoice_id']);
    
    // Update last active record
    update_client_last_active($invoice_details['client_id']);
    update_workorder_last_active($invoice_details['workorder_id']);
    update_invoice_last_active($expense_details['invoice_id']);*/
    
    return true;
    
}

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_expense($expense_id) {
    
    $db = QFactory::getDbo();
    
    /* Get invoice_id before deleting the record
    $invoice_id = get_expense_details($expense_id, 'invoice_id');
    
    // Get related invoice details before deleting the record
    $invoice_details = get_invoice_details($invoice_id);*/
    
    // Change the expense status to deleted (I do this here to maintain consistency)
    update_expense_status($expense_id, 'deleted');  
    
    $sql = "UPDATE ".PRFX."expense_records SET
            employee_id         = '',
            payee               = '',           
            date                = '0000-00-00', 
            tax_system          = '',  
            item_type           = '',
            unit_net            = '',
            vat_tax_code        = '',
            unit_tax_rate       = '0.00',
            unit_tax            = '0.00',
            unit_gross          = '0.00',
            balance             = '0.00',
            status              = 'deleted', 
            opened_on           = '0000-00-00 00:00:00',
            closed_on           = '0000-00-00 00:00:00',
            last_active         = '0000-00-00 00:00:00',
            items               = '',
            note                = ''
            WHERE expense_id    =". $db->qstr($expense_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the expense record."));
    } else {
        
        /* Create a Workorder History Note  
        insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.');*/

        // Log activity        
        $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("deleted.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
                
        /* Log activity        
        $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("deleted.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);
        
        // Update last active record
        update_client_last_active($invoice_details['client_id']);
        update_workorder_last_active($invoice_details['workorder_id']);
        //update_invoice_last_active($invoice_id);*/
    
        return true;
        
    } 
    
}

/** Other Functions **/

##########################################
#      Last Record Look Up               #  // not currently used
##########################################

function last_expense_id_lookup() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."expense_records ORDER BY expense_id DESC LIMIT 1";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to lookup the last expense record ID."));
    } else {
        
        return $rs->fields['expense_id'];
        
    }
    
}

#####################################
#   Recalculate Expense Totals      #
#####################################

function recalculate_expense_totals($expense_id) {
    
    $db = QFactory::getDbo();
    
    $expense_details            = get_expense_details($expense_id);    
    $unit_gross                 = $expense_details['unit_gross'];   
    $payments_sub_total         = sum_payments(null, null, 'date', null, 'valid', 'expense', null, null, null, null, null, $expense_id);
    $balance                    = $unit_gross - $payments_sub_total;

    $sql = "UPDATE ".PRFX."expense_records SET
            balance             =". $db->qstr( $balance    )."
            WHERE expense_id    =". $db->qstr( $expense_id );

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to recalculate the expense totals."));
    } else {
     
        /* Update Status - only change if there is a change in status */        
        
        // Balance = Gross Amount (i.e no payments)
        if($unit_gross > 0 && $unit_gross == $balance && $expense_details['status'] != 'unpaid') {
            update_expense_status($expense_id, 'unpaid');
        }
        
        // Balance < Gross Amount (i.e some payments)
        elseif($unit_gross > 0 && $payments_sub_total > 0 && $payments_sub_total < $unit_gross && $expense_details['status'] != 'partially_paid') {            
            update_expense_status($expense_id, 'partially_paid');
        }
        
        // Balance = 0.00 (i.e has payments and is all paid)
        elseif($unit_gross > 0 && $unit_gross == $payments_sub_total && $expense_details['status'] != 'paid') {            
            update_expense_status($expense_id, 'paid');
        }        
        
        return;        
        
    }
    
}

##########################################################
#  Check if the expense status is allowed to be changed  #  // not currently used
##########################################################

 function check_expense_status_can_be_changed($expense_id) {
     
    $state_flag = true;
     
    // Get the expense details
    $expense_details = get_expense_details($expense_id);
    
    // Is partially paid
    if($expense_details['status'] == 'partially_paid') {
        systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments and is partially paid."));
        $state_flag = false;        
    }
    
    // Is paid
    if($expense_details['status'] == 'paid') {
        systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments and is paid."));
        $state_flag = false;        
    }
    
    // Is deleted
    if($expense_details['status'] == 'deleted') {
        systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has been deleted."));
        $state_flag = false;        
    }
        
    // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
        systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments."));
        $state_flag = false;        
    }

    return $state_flag;    
     
 }

###############################################################
#   Check to see if the expense can be refunded (by status)   #  // not currently used - i DONT think i will use this
###############################################################

function check_expense_can_be_refunded($expense_id) {
    
    $state_flag = true;
    
    // Get the expense details
    $expense_details = get_expense_details($expense_id);
    
    // Is partially paid
    if($expense_details['status'] == 'partially_paid') {
        systemMessagesWrite('danger', _gettext("This expense cannot be refunded because the expense is partially paid."));
        $state_flag = false;
    }
        
    // Is refunded
    if($expense_details['status'] == 'refunded') {
        systemMessagesWrite('danger', _gettext("The expense cannot be refunded because the expense has already been refunded."));
        $state_flag = false;        
    }
    
    // Is cancelled
    if($expense_details['status'] == 'cancelled') {
        systemMessagesWrite('danger', _gettext("The expense cannot be refunded because the expense has been cancelled."));
        $state_flag = false;        
    }
    
    // Is deleted
    if($expense_details['status'] == 'deleted') {
        systemMessagesWrite('danger', _gettext("The expense cannot be refunded because the expense has been deleted."));
        $state_flag = false;        
    }    
    
    // Has no payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(!count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
        systemMessagesWrite('danger', _gettext("This expense cannot be refunded because the expense has no payments."));
        $state_flag = false;        
    }
    
    return $state_flag;
    
}

###############################################################
#   Check to see if the expense can be cancelled              #  // not currently used
###############################################################

function check_expense_can_be_cancelled($expense_id) {
    
    $state_flag = true;
    
    // Get the expense details
    $expense_details = get_expense_details($expense_id);
    
    // Is partially paid
    if($expense_details['status'] == 'partially_paid') {
        systemMessagesWrite('danger', _gettext("This expense cannot be cancelled because the expense is partially paid."));
        $state_flag = false;
    }
    
    // Is paid
    if($expense_details['status'] == 'paid') {
        systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is paid."));
        $state_flag = false;        
    }
        
    // Is cancelled
    if($expense_details['status'] == 'cancelled') {
        systemMessagesWrite('danger', _gettext("The expense cannot be cancelled because the expense has already been cancelled."));
        $state_flag = false;        
    }
    
    // Is deleted
    if($expense_details['status'] == 'deleted') {
        systemMessagesWrite('danger', _gettext("The expense cannot be cancelled because the expense has been deleted."));
        $state_flag = false;        
    }    
    
    // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
        systemMessagesWrite('danger', _gettext("This expense cannot be cancelled because the expense has payments."));
        $state_flag = false;        
    }
   
    return $state_flag;
    
}

###############################################################
#   Check to see if the expense can be deleted                #
###############################################################

function check_expense_can_be_deleted($expense_id) {
    
    $state_flag = true;
    
    // Get the expense details
    $expense_details = get_expense_details($expense_id);
    
    // Is partially paid
    if($expense_details['status'] == 'partially_paid') {
        systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is partially paid."));
        $state_flag = false;        
    }
    
    // Is paid
    if($expense_details['status'] == 'paid') {
        systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is paid."));
        $state_flag = false;        
    }
    
    // Is cancelled
    if($expense_details['status'] == 'cancelled') {
        systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has been cancelled."));
        $state_flag = false;        
    }
    
    // Is deleted
    if($expense_details['status'] == 'deleted') {
        systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it already been deleted."));
        $state_flag = false;        
    }
    
    // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
        systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments."));
        $state_flag = false;        
    }
     
    return $state_flag;
    
}

##########################################################
#  Check if the expense status allows editing            #       
##########################################################

 function check_expense_can_be_edited($expense_id) {
     
    $state_flag = true;
     
    // Get the expense details
    $expense_details = get_expense_details($expense_id);
    
    // Is on a different tax system
    if($expense_details['tax_system'] != QW_TAX_SYSTEM) {
        systemMessagesWrite('danger', _gettext("The expense cannot be edited because it is on a different Tax system."));
        $state_flag = false;        
    }
    
    // Is partially paid
    if($expense_details['status'] == 'partially_paid') {
        systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments and is partially paid."));
        $state_flag = false;        
    }
    
    // Is paid
    if($expense_details['status'] == 'paid') {
        systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments and is paid."));
        $state_flag = false;        
    }
    
    // Is cancelled
    if($expense_details['status'] == 'cancelled') {
        systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has been cancelled."));
        $state_flag = false;        
    }
    
    // Is deleted
    if($expense_details['status'] == 'deleted') {
        systemMessagesWrite('danger', _gettext("The expense cannot be edited because it has been deleted."));
        $state_flag = false;        
    }
    
    // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
        systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments."));
        $state_flag = false;        
    }   
    
    // The current record VAT code is enabled
    if(!get_vat_tax_code_status($expense_details['vat_tax_code'])) {
        systemMessagesWrite('danger', _gettext("This expense cannot be edited because it's current VAT Tax Code is not enabled."));
        $state_flag = false; 
    }

    return $state_flag;    
     
}