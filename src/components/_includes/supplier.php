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
#     Display Suppliers       #
###############################

function display_suppliers($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $type = null, $status = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'supplier_id';
    $havingTheseRecords = '';
    
    /* Records Search */ 
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."supplier_records.supplier_id\n";
    
    // Search category (display_name) and search term
    if($search_category == 'display_name') {$havingTheseRecords .= " HAVING display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Search category (full_name) and search term
    elseif($search_category == 'full_name') {$havingTheseRecords .= " HAVING full_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."supplier_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');}
    
    /* Filter the Records */ 
    
    // Restrict by Type
    if($type) { $whereTheseRecords .= " AND ".PRFX."supplier_records.type= ".$db->qstr($type);}
    
    // Restrict by status
    if($status) {$whereTheseRecords .= " AND ".PRFX."supplier_records.status= ".$db->qstr($status);} 
    
    /* The SQL code */
    
    $sql =  "SELECT
            ".PRFX."supplier_records.*,
            IF(company_name !='', company_name, CONCAT(".PRFX."supplier_records.first_name, ' ', ".PRFX."supplier_records.last_name)) AS display_name,
            CONCAT(".PRFX."supplier_records.first_name, ' ', ".PRFX."supplier_records.last_name) AS full_name

            FROM ".PRFX."supplier_records                                                   
            ".$whereTheseRecords."            
            GROUP BY ".PRFX."supplier_records.".$order_by."
            ".$havingTheseRecords."
            ORDER BY ".PRFX."supplier_records.".$order_by."
            ".$direction;           
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching supplier records."));
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching supplier records."));
    } else {
        
        $records = $rs->GetArray();

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** Insert Functions **/

##########################################
#      Insert New Record                 #
##########################################

function insert_supplier($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."supplier_records SET       
            employee_id    =". $db->qstr( QFactory::getUser()->login_user_id ).",
            company_name   =". $db->qstr( $VAR['company_name']  ).",
            first_name     =". $db->qstr( $VAR['first_name']    ).",
            last_name      =". $db->qstr( $VAR['last_name']     ).",
            website        =". $db->qstr( process_inputted_url($VAR['website'])).",
            email          =". $db->qstr( $VAR['email']         ).",
            type           =". $db->qstr( $VAR['type']          ).",
            primary_phone  =". $db->qstr( $VAR['primary_phone'] ).",
            mobile_phone   =". $db->qstr( $VAR['mobile_phone']  ).",
            fax            =". $db->qstr( $VAR['fax']           ).",
            address        =". $db->qstr( $VAR['address']       ).",
            city           =". $db->qstr( $VAR['city']          ).",
            state          =". $db->qstr( $VAR['state']         ).",
            zip            =". $db->qstr( $VAR['zip']           ).",
            country        =". $db->qstr( $VAR['country']       ).",
            status         =". $db->qstr( 'valid'               ).",
            opened_on      =". $db->qstr( mysql_datetime()      ).", 
            description    =". $db->qstr( $VAR['description']   ).", 
            note           =". $db->qstr( $VAR['note']          );            

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the supplier record into the database."));
    } else {
        
        // Log activity        
        $record = _gettext("Supplier Record").' '.$db->Insert_ID().' ('.$VAR['company_name'].') '._gettext("created.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
        
        return $db->Insert_ID();
        
    }
    
} 

/** Get Functions **/

############################
#   Get supplier details   #
############################

function get_supplier_details($supplier_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."supplier_records WHERE supplier_id=".$db->qstr($supplier_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the supplier details."));
    } else {
        
        if($item === null){
            
            $results = $rs->GetRowAssoc();
            
            // Add these dynamically created fields           
            $results['display_name'] = $results['company_name'] ? $results['company_name'] : $results['first_name'].' '.$results['last_name'];
            $results['full_name'] = $results['first_name'].' '.$results['last_name'];
            
            return $results;          
            
        } else {
            
            // Return the dynamically created 'display_name'
            if($item == 'display_name') {
                $results = $rs->GetRowAssoc();
                return $results['company_name'] ? $results['company_name'] : $results['first_name'].' '.$results['last_name'];
            }
            
            // Return the dynamically created 'full_name'
            if($item == 'display_name') {
                $results = $rs->GetRowAssoc();
                return $results['first_name'].' '.$results['last_name']; 
            }
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#    Get Supplier Statuses          #
#####################################

function get_supplier_statuses($restricted_statuses = false) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."supplier_statuses";
    
    // Restrict statuses to those that are allowed to be changed by the user
    if($restricted_statuses) {
        $sql .= "\nWHERE status_key NOT IN ('invalid')";  // NB: 'invalid' does not currently exist
    }
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Supplier statuses."));
    } else {
        
        return $rs->GetArray();     
        
    }    
    
}

#######################################
#  Get Supplier status display name   #
#######################################

function get_supplier_status_display_name($status_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."supplier_statuses WHERE status_key=".$db->qstr($status_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the supplier status display name."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

#####################################
#    Get Supplier Types             #
#####################################

function get_supplier_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."supplier_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get supplier types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#     Update Record                 #
#####################################

function update_supplier($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."supplier_records SET
            employee_id    =". $db->qstr( QFactory::getUser()->login_user_id ).",
            company_name   =". $db->qstr( $VAR['company_name']  ).",
            first_name     =". $db->qstr( $VAR['first_name']    ).",
            last_name      =". $db->qstr( $VAR['last_name']     ).",
            website        =". $db->qstr( process_inputted_url($VAR['website'])).",
            email          =". $db->qstr( $VAR['email']         ).",
            type           =". $db->qstr( $VAR['type']          ).",
            primary_phone  =". $db->qstr( $VAR['primary_phone'] ).",
            mobile_phone   =". $db->qstr( $VAR['mobile_phone']  ).",
            fax            =". $db->qstr( $VAR['fax']           ).",
            address        =". $db->qstr( $VAR['address']       ).",
            city           =". $db->qstr( $VAR['city']          ).",
            state          =". $db->qstr( $VAR['state']         ).",
            zip            =". $db->qstr( $VAR['zip']           ).",
            country        =". $db->qstr( $VAR['country']       ).",
            last_active    =". $db->qstr( mysql_datetime()      ).",
            description    =". $db->qstr( $VAR['description']   ).", 
            note           =". $db->qstr( $VAR['note']          )."
            WHERE supplier_id = ". $db->qstr( $VAR['supplier_id'] );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the supplier details."));
    } else {
        
        // Log activity      
        $record = _gettext("Supplier Record").' '.$db->Insert_ID().' ('.$VAR['company_name'].') '._gettext("updated.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);

        return true;
        
    }
    
} 

#############################
# Update Supplier Status    #
#############################

function update_supplier_status($supplier_id, $new_status, $silent = false) {
    
    $db = QFactory::getDbo();
    
    // Get supplier details
    $supplier_details = get_supplier_details($supplier_id);
    
    // if the new status is the same as the current one, exit
    if($new_status == $supplier_details['status']) {        
        if (!$silent) { postEmulationWrite('warning_msg', _gettext("Nothing done. The new status is the same as the current status.")); }
        return false;
    }    
    
    // Unify Dates and Times
    $datetime = mysql_datetime();
    
    // Set the appropriate closed_on date
    $closed_on = ($new_status == 'closed') ? $datetime : '0000-00-00 00:00:00';
    
    $sql = "UPDATE ".PRFX."supplier_records SET
            status             =". $db->qstr( $new_status   )."
            closed_on          =". $db->qstr( $closed_on    )." 
            last_active        =". $db->qstr( $datetime     )." 
            WHERE supplier_id  =". $db->qstr( $supplier_id  );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update an supplier Status."));
        
    } else {    
        
        // Status updated message
        if (!$silent) { postEmulationWrite('information_msg', _gettext("supplier status updated.")); }
        
        // For writing message to log file, get supplier status display name
        $supplier_status_display_name = _gettext(get_supplier_status_display_name($new_status));
        
        // Log activity        
        $record = _gettext("Supplier").' '.$supplier_id.' '._gettext("Status updated to").' '.$supplier_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
        
        return true;
        
    }
    
}

/** Close Functions **/

#####################################
#   Cancel Supplier                 #
#####################################

function cancel_supplier($supplier) {
    
    // Make sure the supplier can be cancelled
    if(!check_supplier_can_be_cancelled($supplier)) {        
        return false;
    }
    
    // Get supplier details
    //$supplier_details = get_supplier_details($supplier);  
    
    // Change the supplier status to cancelled (I do this here to maintain consistency)
    update_supplier_status($supplier, 'cancelled');      
        
    // Log activity        
    $record = _gettext("Supplier").' '.$supplier.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, QFactory::getUser()->login_user_id);

    return true;
    
}

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_supplier($supplier_id) {
    
    $db = QFactory::getDbo();
    
    $display_name = get_supplier_details($supplier_id, 'display_name');
    
    // Make sure the supplier can be deleted 
    if(!check_supplier_can_be_deleted($supplier_id)) {        
        return false;
    }
    
    $sql = "DELETE FROM ".PRFX."supplier_records WHERE supplier_id=".$db->qstr($supplier_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the supplier record."));
    } else {
        
        // Log activity     
        $record = _gettext("Supplier Record").' '.$supplier_id.' ('.$display_name.') '._gettext("deleted.");
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id);
        
        return true;
        
    }
    
}

/** Other Functions **/

############################################
#      Last supplier Record ID Look Up     #  // not cureently used
############################################

function last_supplier_id_lookup() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."supplier_records ORDER BY supplier_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to lookup the last supplier record ID."));
    } else {
        
        return $rs->fields['supplier_id'];
        
    }
    
}

###########################################################
#  Check if the supplier status is allowed to be changed  #  // not currently used
###########################################################

 function check_supplier_status_can_be_changed($supplier_id) {
     
    // Get the supplier details
    //$supplier_details = get_supplier_details($supplier_id); 
    
    /* Is cancelled
    if($supplier_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The supplier cannot be changed because the supplier has been deleted."));
        return false;        
    }*/

    // All checks passed
    return true;     
     
 }


###############################################################
#   Check to see if the supplier can be cancelled             #  // not currently used
###############################################################

function check_supplier_can_be_cancelled($supplier_id) {
    
    // Get the supplier details
    $supplier_details = get_supplier_details($supplier_id);   
   
    // Is cancelled
    if($supplier_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The supplier cannot be cancelled because the supplier has been deleted."));
        return false;        
    }  
   
    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the supplier can be deleted               #
###############################################################

function check_supplier_can_be_deleted($supplier_id) {
    
    // Get the supplier details
    //$supplier_details = get_supplier_details($supplier_id);
    
    /* Is cancelled
    if($supplier_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("This supplier cannot be deleted because it has been cancelled."));
        return false;        
    }*/
     
    // All checks passed
    return true;
    
}

##########################################################
#  Check if the supplier status allows editing           #  // not currently used
##########################################################

 function check_supplier_can_be_edited($supplier_id) {
     
    // Get the supplier details
    $supplier_details = get_supplier_details($supplier_id);
    
    // Is cancelled
    if($supplier_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The supplier cannot be edited because it has been cancelled."));
        return false;        
    }
    
    // All checks passed
    return true;    
     
}