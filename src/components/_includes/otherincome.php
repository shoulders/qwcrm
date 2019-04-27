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
   
###############################
#  Display otherincomes       #
###############################

function display_otherincomes($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $type = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'otherincome_id';    
    
    /* Records Search */    
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";
    
    // Restrict results by search category and search term
    if($search_term) {$whereTheseRecords .= " AND ".PRFX."otherincome_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');} 
    
    /* Filter the Records */  
    
    // Restrict by Type
    if($type) { $whereTheseRecords .= " AND ".PRFX."otherincome_records.type= ".$db->qstr($type);}
        
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."otherincome_records                                                   
            ".$whereTheseRecords."            
            GROUP BY ".PRFX."otherincome_records.".$order_by."
            ORDER BY ".PRFX."otherincome_records.".$order_by."
            ".$direction;           
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching otherincome records."));
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching otherincome records."));
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
#      Insert Otherincome                #
##########################################

function insert_otherincome($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."otherincome_records SET
            employee_id      =". $db->qstr( QFactory::getUser()->login_user_id ).",
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_mysql_date($VAR['date'])).",
            tax_system       =". $db->qstr(get_company_details('tax_system')).",            
            item_type        =". $db->qstr( $VAR['item_type']               ).",            
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_tax_code     =". $db->qstr( $VAR['vat_tax_code']            ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",
            last_active      =". $db->qstr( mysql_datetime()                ).",
            status           =". $db->qstr( 'unpaid'                        ).",
            items            =". $db->qstr( $VAR['items']                   ).",
            note             =". $db->qstr( $VAR['note']                    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the otherincome record into the database."));
    } else {
        
        // Log activity        
        $record = _gettext("Otherincome Record").' '.$db->Insert_ID().' '._gettext("created.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
                
        return $db->Insert_ID();
        
    } 
    
}

/** Get Functions **/

###############################
#   Get otherincome details   #
###############################

function get_otherincome_details($otherincome_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."otherincome_records WHERE otherincome_id=".$db->qstr($otherincome_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the otherincome details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#    Get Otherincome Statuses       #
#####################################

function get_otherincome_statuses($restricted_statuses = false) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."otherincome_statuses";
    
    // Restrict statuses to those that are allowed to be changed by the user
    if($restricted_statuses) {
        $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
    }
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Otherincome statuses."));
    } else {
        
        return $rs->GetArray();     
        
    }    
    
}

##########################################
#  Get Otherincome status display name   #
##########################################

function get_otherincome_status_display_name($status_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."otherincome_statuses WHERE status_key=".$db->qstr($status_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the otherincome status display name."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

#####################################
#    Get Otherincome Types          #
#####################################

function get_otherincome_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."otherincome_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get otherincome types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#     Update otherincome            #
#####################################

function update_otherincome($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."otherincome_records SET
            employee_id      =". $db->qstr( QFactory::getUser()->login_user_id ).",
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_mysql_date($VAR['date'])).",            
            item_type        =". $db->qstr( $VAR['item_type']               ).",            
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_tax_code     =". $db->qstr( $VAR['vat_tax_code']            ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",
            last_active      =". $db->qstr( mysql_datetime()                ).",
            items            =". $db->qstr( $VAR['items']                   ).",
            note             =". $db->qstr( $VAR['note']                    )."
            WHERE otherincome_id  =". $db->qstr( $VAR['otherincome_id']     );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the otherincome details."));
    } else {
        
        // Log activity        
        $record = _gettext("Otherincome Record").' '.$VAR['otherincome_id'].' '._gettext("updated.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
        
        return true;
      
    }
    
} 

#############################
# Update Otherincome Status #
#############################

function update_otherincome_status($otherincome_id, $new_status, $silent = false) {
    
    $db = QFactory::getDbo();
    
    // Get otherincome details
    $otherincome_details = get_otherincome_details($otherincome_id);
    
    // if the new status is the same as the current one, exit
    if($new_status == $otherincome_details['status']) {        
        if (!$silent) { postEmulationWrite('warning_msg', _gettext("Nothing done. The new status is the same as the current status.")); }
        return false;
    }    
    
    $sql = "UPDATE ".PRFX."otherincome_records SET
            status               =". $db->qstr( $new_status  )."            
            WHERE otherincome_id    =". $db->qstr( $otherincome_id );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update an otherincome Status."));
        
    } else {    
        
        // Status updated message
        if (!$silent) { postEmulationWrite('information_msg', _gettext("otherincome status updated.")); }
        
        // For writing message to log file, get otherincome status display name
        $otherincome_status_display_name = _gettext(get_otherincome_status_display_name($new_status));
        
        // Log activity        
        $record = _gettext("Otherincome").' '.$otherincome_id.' '._gettext("Status updated to").' '.$otherincome_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
        
        return true;
        
    }
    
}

/** Close Functions **/

#####################################
#   Cancel Otherincome              #
#####################################

function cancel_otherincome($otherincome_id) {
    
    // Make sure the otherincome can be cancelled
    if(!check_otherincome_can_be_cancelled($otherincome_id)) {        
        return false;
    }
    
    // Change the otherincome status to cancelled (I do this here to maintain consistency)
    update_otherincome_status($otherincome_id, 'cancelled');      
        
    // Log activity        
    $record = _gettext("Otherincome").' '.$otherincome_id.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, QFactory::getUser()->login_user_id);

    return true;
    
}

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_otherincome($otherincome_id) {
    
    $db = QFactory::getDbo();
    
    // Change the otherincome status to deleted (I do this here to maintain consistency)
    update_otherincome_status($otherincome_id, 'deleted'); 
    
    $sql = "UPDATE ".PRFX."otherincome_records SET
        employee_id         = '',
        payee               = '',           
        date                = '0000-00-00', 
        tax_system          = '',  
        item_type           = '',        
        net_amount          = '',
        vat_tax_code        = '',
        vat_rate            = '0.00',
        vat_amount          = '0.00',
        gross_amount        = '0.00',
        balance             = '0.00',
        last_active         = '0000-00-00 00:00:00',
        status              = 'deleted', 
        items               = '',
        note                = ''
        WHERE otherincome_id =". $db->qstr($otherincome_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the otherincome records."));
    } else {
        
        // Log activity        
        $record = _gettext("Otherincome Record").' '.$otherincome_id.' '._gettext("deleted.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
        
        return true;
        
    }
    
}

/** Other Functions **/
   
##########################################
#      Last Record Look Up               #  // not currently used
##########################################

function last_otherincome_id_lookup() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."otherincome_records ORDER BY otherincome_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to lookup the last otherincome record ID."));
    } else {
        
        return $rs->fields['otherincome_id'];
        
    }
        
}

function recalculate_otherincome_totals($otherincome_id) {
    
    $db = QFactory::getDbo();
    
    $otherincome_details            = get_otherincome_details($otherincome_id);    
    
    $gross_amount                   = $otherincome_details['gross_amount'];   
    $payments_sub_total             = (sum_payments(null, null, null, null, null, 'otherincome', null, null, null, null, null, $otherincome_id) - sum_payments(null, null, null, null, 'cancelled', 'otherincome', null, null, null, null, null, $otherincome_id));    
    $balance                        = $gross_amount - $payments_sub_total;

    $sql = "UPDATE ".PRFX."otherincome_records SET
            balance                 =". $db->qstr( $balance        )."
            WHERE otherincome_id    =". $db->qstr( $otherincome_id );

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to recalculate the otherincome totals."));
    } else {
     
        /* Update Status - only change if there is a change in status */        
        
        // Balance = Gross Amount (i.e no payments)
        if($gross_amount > 0 && $gross_amount == $balance && $otherincome_details['status'] != 'unpaid') {
            update_otherincome_status($otherincome_id, 'unpaid');
        }
        
        // Balance < Gross Amount (i.e some payments)
        elseif($gross_amount > 0 && $payments_sub_total > 0 && $payments_sub_total < $gross_amount && $otherincome_details['status'] != 'partially_paid') {            
            update_otherincome_status($otherincome_id, 'partially_paid');
        }
        
        // Balance = 0.00 (i.e has payments and is all paid)
        elseif($gross_amount > 0 && $gross_amount == $payments_sub_total && $otherincome_details['status'] != 'paid') {            
            update_otherincome_status($otherincome_id, 'paid');
        }        
        
        return;        
        
    }
    
}

##############################################################
#  Check if the otherincome status is allowed to be changed  #  // not currently used
##############################################################

 function check_otherincome_status_can_be_changed($otherincome_id) {
     
    // Get the otherincome details
    $otherincome_details = get_otherincome_details($otherincome_id);
    
    // Is partially paid
    if($otherincome_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome status cannot be changed because the otherincome has payments and is partially paid."));
        return false;        
    }
    
    // Is paid
    if($otherincome_details['status'] == 'paid') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome status cannot be changed because the otherincome has payments and is paid."));
        return false;        
    }
    
    // Is deleted
    if($otherincome_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome status cannot be changed because the otherincome has been deleted."));
        return false;        
    }
        
    // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(count_payments(null, null, null, null, null, 'otherincome', null, null, null, null, null, $otherincome_id)) {
        //postEmulationWrite('warning_msg', _gettext("The otherincome status cannot be changed because the otherincome has payments."));
        return false;        
    }

    // All checks passed
    return true;     
     
 }

###################################################################
#   Check to see if the otherincome can be refunded (by status)   #  // not currently used - i DONT think i will use this
###################################################################

function check_otherincome_can_be_refunded($otherincome_id) {
    
    // Get the otherincome details
    $otherincome_details = get_otherincome_details($otherincome_id);
    
    // Is partially paid
    if($otherincome_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be refunded because the otherincome is partially paid."));
        return false;
    }
        
    // Is refunded
    if($otherincome_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome cannot be refunded because the otherincome has already been refunded."));
        return false;        
    }
    
    // Is cancelled
    if($otherincome_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome cannot be refunded because the otherincome has been cancelled."));
        return false;        
    }
    
    // Is deleted
    if($otherincome_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome cannot be refunded because the otherincome has been deleted."));
        return false;        
    }    

    // Has no payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(!count_payments(null, null, null, null, null, 'otherincome', null, null, null, null, null, $otherincome_id)) {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be refunded because the otherincome has no payments."));
        return false;        
    }
    
    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the otherincome can be cancelled          #  // not currently used
###############################################################

function check_otherincome_can_be_cancelled($otherincome_id) {
    
    // Get the otherincome details
    $otherincome_details = get_otherincome_details($otherincome_id);
    
    // Is partially paid
    if($otherincome_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be cancelled because the otherincome is partially paid."));
        return false;
    }
        
    // Is paid
    if($otherincome_details['status'] == 'paid') {
        //postEmulationWrite('warning_msg', _gettext("This expense cannot be deleted because it has payments and is paid."));
        return false;        
    }
    
    // Is cancelled
    if($otherincome_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome cannot be cancelled because the otherincome has already been cancelled."));
        return false;        
    }
    
    // Is deleted
    if($otherincome_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome cannot be cancelled because the otherincome has been deleted."));
        return false;        
    }    
    
    // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(count_payments(null, null, null, null, null, 'otherincome', null, null, null, null, null, $otherincome_id)) {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be cancelled because the otherincome has payments."));
        return false;        
    }
    
    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the otherincome can be deleted            #
###############################################################

function check_otherincome_can_be_deleted($otherincome_id) {
    
    // Get the otherincome details
    $otherincome_details = get_otherincome_details($otherincome_id);
    
    // Is partially paid
    if($otherincome_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be deleted because it has payments and is partially paid."));
        return false;        
    }
    
    // Is paid
    if($otherincome_details['status'] == 'paid') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be deleted because it has payments and is paid."));
        return false;        
    }
    
    // Is cancelled
    if($otherincome_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be deleted because it has been cancelled."));
        return false;        
    }
    
    // Is deleted
    if($otherincome_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be deleted because it already been deleted."));
        return false;        
    }
    
    // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(count_payments(null, null, null, null, null, 'otherincome', null, null, null, null, null, $otherincome_id)) {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be deleted because it has payments."));
        return false;        
    }
     
    // All checks passed
    return true;
    
}

##########################################################
#  Check if the otherincome status allows editing        #       
##########################################################

 function check_otherincome_can_be_edited($otherincome_id) {
     
    // Get the otherincome details
    $otherincome_details = get_otherincome_details($otherincome_id);
    
    // Is partially paid
    if($otherincome_details['status'] == 'partially_paid') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be edited because it has payments and is partially paid."));
        return false;        
    }
    
    // Is paid
    if($otherincome_details['status'] == 'paid') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be edited because it has payments and is paid."));
        return false;        
    }
    
    // Is cancelled
    if($otherincome_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be edited because it already been deleted."));
        return false;        
    }
    
    // Is deleted
    if($otherincome_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The otherincome cannot be edited because it has been deleted."));
        return false;        
    }
    
    // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
    if(count_payments(null, null, null, null, null, 'otherincome', null, null, null, null, null, $otherincome_id)) {
        //postEmulationWrite('warning_msg', _gettext("This otherincome cannot be edited because it has payments."));
        return false;        
    }

    // All checks passed
    return true;    
     
}