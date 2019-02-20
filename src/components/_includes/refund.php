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
   
#############################
#     Display refunds       #
#############################

function display_refunds($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $type = null, $payment_method = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'refund_id';    
    
    /* Records Search */    
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."refund_records.refund_id\n";
    $havingTheseRecords = '';
    
    // Restrict results by search category (client) and search term
    if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
        
    // Restrict results by search category (employee) and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."refund_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');} 
    
    /* Filter the Records */  
    
    // Restrict by Type
    if($type) { $whereTheseRecords .= " AND ".PRFX."refund_records.type= ".$db->qstr($type);}
        
    // Restrict by Method
    if($payment_method) { $whereTheseRecords .= " AND ".PRFX."refund_records.payment_method= ".$db->qstr($payment_method);} 
    
    /* The SQL code */
    
    $sql =  "SELECT
            ".PRFX."refund_records.*,
                
            IF(company_name !='', company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name

            FROM ".PRFX."refund_records
                
            LEFT JOIN ".PRFX."client_records ON ".PRFX."refund_records.client_id = ".PRFX."client_records.client_id  
                
            ".$whereTheseRecords."            
            GROUP BY ".PRFX."refund_records.".$order_by."
            ".$havingTheseRecords."
            ORDER BY ".PRFX."refund_records.".$order_by."
            ".$direction;           
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching refund records."));
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching refund records."));
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
#      Insert Refund                     #
##########################################

function insert_refund($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."refund_records SET
            client_id        =". $db->qstr( $VAR['client_id']               ).",
            invoice_id       =". $db->qstr( $VAR['invoice_id']              ).",                        
            date             =". $db->qstr( date_to_mysql_date($VAR['date'])).",
            tax_system       =". $db->qstr(get_company_details('tax_system')).",            
            item_type        =". $db->qstr( $VAR['item_type']               ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_tax_code     =". $db->qstr( $VAR['vat_tax_code']            ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            note             =". $db->qstr( $VAR['note']                    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the refund record into the database."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$db->Insert_ID().' '._gettext("created.");
        write_record_to_activity_log($record, null, null, null, $VAR['invoice_id']);
        
        return $db->Insert_ID();
        
    } 
    
}

/** Get Functions **/

##########################
#   Get refund details   #
##########################

function get_refund_details($refund_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."refund_records WHERE refund_id=".$db->qstr($refund_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the refund details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#    Get Refund Statuses            #
#####################################

function get_refund_statuses($restricted_statuses = false) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."refund_statuses";
    
    // Restrict statuses to those that are allowed to be changed by the user
    if($restricted_statuses) {
        $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
    }
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Refund statuses."));
    } else {
        
        return $rs->GetArray();     
        
    }    
    
}

######################################
#  Get Refund status display name    #
######################################

function get_refund_status_display_name($status_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."refund_statuses WHERE status_key=".$db->qstr($status_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the refund status display name."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

#####################################
#    Get Refund Types               #
#####################################

function get_refund_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."refund_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get refund types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#     Update refund                 #
#####################################

function update_refund($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."refund_records SET
            client_id        =". $db->qstr( $VAR['client_id']               ).",
            invoice_id       =". $db->qstr( $VAR['invoice_id']              ).",                        
            date             =". $db->qstr( date_to_mysql_date($VAR['date'])).",            
            item_type        =". $db->qstr( $VAR['item_type']               ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_tax_code     =". $db->qstr( $VAR['vat_tax_code']            ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            note             =". $db->qstr( $VAR['note']                    )."
            WHERE refund_id  =". $db->qstr( $VAR['refund_id']               );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the refund details."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$VAR['refund_id'].' '._gettext("updated.");
        write_record_to_activity_log($record, null, null, null, $VAR['invoice_id']);
        
        return true;
      
    }
    
} 

############################
# Update Refund Status     #
############################

function update_refund_status($refund_id, $new_status, $silent = false) {
    
    $db = QFactory::getDbo();
    
    // Get refund details
    $refund_details = get_refund_details($refund_id);
    
    // if the new status is the same as the current one, exit
    if($new_status == $refund_details['status']) {        
        if (!$silent) { postEmulationWrite('warning_msg', _gettext("Nothing done. The new status is the same as the current status.")); }
        return false;
    }    
    
    $sql = "UPDATE ".PRFX."refund_records SET
            status             =". $db->qstr( $new_status  )."            
            WHERE refund_id    =". $db->qstr( $refund_id );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update an refund Status."));
        
    } else {    
        
        // Get related workorder_id
        $workorder_id = get_invoice_details($refund_details['invoice_id'], 'workorder_id');
        
        // Status updated message
        if (!$silent) { postEmulationWrite('information_msg', _gettext("refund status updated.")); }
        
        // For writing message to log file, get refund status display name
        $refund_status_display_name = _gettext(get_refund_status_display_name($new_status));
        
        // Create a Workorder History Note
        insert_workorder_history_note($workorder_id, _gettext("refund Status updated to").' '.$refund_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Refund").' '.$refund_id.' '._gettext("Status updated to").' '.$refund_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $refund_details['client_id'], $workorder_id, $refund_details['invoice_id']);
                
        // Update last active record - // not used, the current user is updated elsewhere  
        update_client_last_active($refund_details['client_id']);
        update_workorder_last_active($workorder_id);
        update_invoice_last_active($refund_details['invoice_id']);              
        
        return true;
        
    }
    
}

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_refund($refund_id) {
    
    $db = QFactory::getDbo();
    
    // Get invoice_id before deleting the record
    $invoice_id = get_refund_details($refund_id, 'invoice_id');
    
    $sql = "UPDATE ".PRFX."refund_records SET
            client_id           = '',
            invoice_id          = '',
            date                = '0000-00-00', 
            tax_system          = '',  
            item_type           = '',
            payment_method      = '',
            net_amount          = '',
            vat_tax_code        = '',
            vat_rate            = '0.00',
            vat_amount          = '0.00',
            gross_amount        = '0.00',
            status              = '', 
            items               = '',
            note                = ''
            WHERE refund_id    =". $db->qstr($refund_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the refund records."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$refund_id.' '._gettext("deleted.");
        write_record_to_activity_log($record, null, null, null, $invoice_id);
        
        return true;
        
    }
    
}

/** Other Functions **/
   
##########################################
#      Last Record Look Up               #  // not currently used
##########################################

function last_refund_id_lookup() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."refund_records ORDER BY refund_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to lookup the last refund record ID."));
    } else {
        
        return $rs->fields['refund_id'];
        
    }
        
}


##########################################################
#  Check if the refund status is allowed to be changed   #  // not currently used
##########################################################

 function check_refund_status_can_be_changed($refund_id) {
     
    // Get the refund details
    $refund_details = get_refund_details($refund_id);
    
    // Is partially paid
    if($refund_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("The refund status cannot be changed because the refund has payments and is partially paid."));
        return false;        
    }
    
    // Is paid
    if($refund_details['status'] == 'paid') {
        //postEmulationWrite('warning_msg', _gettext("The refund status cannot be changed because the refund has payments and is paid."));
        return false;        
    }
    
    /* Is cancelled
    if($refund_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The refund status cannot be changed because the refund has been cancelled."));
        return false;        
    }*/
    
    // Is deleted
    if($refund_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The refund status cannot be changed because the refund has been deleted."));
        return false;        
    }
        
    /* Has payments (Fallback - is not needed because of statuses)
    if(count_payments(null, null, null, null, null, null, $refund_id)) {        
        //postEmulationWrite('warning_msg', _gettext("The refund status cannot be changed because the refund has payments."));
        return false;        
    }*/

    // All checks passed
    return true;     
     
 }

###############################################################
#   Check to see if the refund can be refunded (by status)    #  // not currently used - i DONT think i will use this
###############################################################

function check_refund_can_be_refunded($refund_id) {
    
    // Get the refund details
    $refund_details = get_refund_details($refund_id);
    
    // Is partially paid
    if($refund_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be refunded because the refund is partially paid."));
        return false;
    }
        
    // Is refunded
    if($refund_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The refund cannot be refunded because the refund has already been refunded."));
        return false;        
    }
    
    // Is cancelled
    if($refund_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The refund cannot be refunded because the refund has been cancelled."));
        return false;        
    }
    
    // Is deleted
    if($refund_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The refund cannot be refunded because the refund has been deleted."));
        return false;        
    }    

    /* Has no payments (Fallback - is not needed because of statuses)
    if(!count_payments(null, null, null, null, null, null, $refund_id)) {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be refunded because the refund has no payments."));
        return false;        
    }*/
    
    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the refund can be cancelled               #  // not currently used
###############################################################

function check_refund_can_be_cancelled($refund_id) {
    
    // Get the refund details
    $refund_details = get_refund_details($refund_id);
    
    // Does not have a balance
    if($refund_details['balance'] == 0) {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be cancelled because the refund does not have a balance."));
        return false;
    }
    
    // Is partially paid
    if($refund_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be cancelled because the refund is partially paid."));
        return false;
    }
        
    // Is cancelled
    if($refund_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The refund cannot be cancelled because the refund has already been cancelled."));
        return false;        
    }
    
    // Is deleted
    if($refund_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The refund cannot be cancelled because the refund has been deleted."));
        return false;        
    }    
    
    /* Has payments (Fallback - is not needed because of statuses)
    if(count_payments(null, null, null, null, null, null, $refund_id)) {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be cancelled because the refund has payments."));
        return false;        
    }*/
    
    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the refund can be deleted                 #
###############################################################

function check_refund_can_be_deleted($refund_id) {
    
    // Get the refund details
    $refund_details = get_refund_details($refund_id);
       
    // Is partially paid
    if($refund_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be deleted because it has payments and is partially paid."));
        return false;        
    }
    
    // Is paid
    if($refund_details['status'] == 'paid') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be deleted because it has payments and is paid."));
        return false;        
    }
    
    /* Is cancelled
    if($refund_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be deleted because it has been cancelled."));
        return false;        
    }*/
    
    // Is deleted
    if($refund_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be deleted because it already been deleted."));
        return false;        
    }
    
    /* Has payments (Fallback - is not needed because of statuses)
    if(count_payments(null, null, null, null, null, null, $refund_id)) {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be deleted because it has payments."));
        return false;        
    }*/ 
     
    // All checks passed
    return true;
    
}

##########################################################
#  Check if the refund status allows editing             #       
##########################################################

 function check_refund_can_be_edited($refund_id) {
     
    // Get the refund details
    $refund_details = get_refund_details($refund_id);
    
    // Is partially paid
    if($refund_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be edited because it has payments and is partially paid."));
        return false;        
    }
    
    // Is paid
    if($refund_details['status'] == 'paid') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be edited because it has payments and is paid."));
        return false;        
    }
    
    /* Is cancelled
    if($refund_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be edited because it already been deleted."));
        return false;        
    }*/
    
    // Is deleted
    if($refund_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The refund cannot be edited because it has been deleted."));
        return false;        
    }
    
    /* Has payments (Fallback - is not needed because of statuses)
    if(count_payments(null, null, null, null, null, null, $invoice_id)) {
        //postEmulationWrite('warning_msg', _gettext("This refund cannot be edited because it has payments."));
        return false;        
    }*/    

    // All checks passed
    return true;    
     
}