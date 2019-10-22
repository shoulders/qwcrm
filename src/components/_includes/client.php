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

#####################################
#   Display Clients                 #
#####################################

function display_clients($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $type = null, $status = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';   
    $search_category = $search_category ?: 'client_id';
    $havingTheseRecords = '';

    /* Records Search */
    
    // Default Action    
    $whereTheseRecords = " WHERE ".PRFX."client_records.client_id\n";    
    
    // Search category (display_name) and search term
    if($search_category == 'display_name') { $havingTheseRecords .= " HAVING display_name LIKE ".$db->qstr('%'.$search_term.'%'); }
    
    // Search category (full_name) and search term
    elseif($search_category == 'full_name') { $havingTheseRecords .= " HAVING full_name LIKE ".$db->qstr('%'.$search_term.'%'); }
    
    // Search category with search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."client_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');}     
    
    /* Filter the Records */     
    
    // Restrict by Type
    if($type) {$whereTheseRecords .= " AND ".PRFX."client_records.type= ".$db->qstr($type);}    
    
    // Restrict by Status (is null because using boolean/integer)
    if(!is_null($status)) {$whereTheseRecords .= " AND ".PRFX."client_records.active=".$db->qstr($status);}

    /* The SQL code */    
    
    $sql = "SELECT        
        ".PRFX."client_records.*,    
        IF(company_name !='', company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS display_name,
        CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name) AS full_name
        
        FROM ".PRFX."client_records            
 
        ".$whereTheseRecords."
        GROUP BY ".PRFX."client_records.".$order_by."
        ".$havingTheseRecords."
        ORDER BY ".PRFX."client_records.".$order_by."
        ".$direction; 
   
    /* Restrict by pages */
        
    if($use_pages) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the number of matching client records."));
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
        $rs = '';
    
    } else {
        
        // This make the drop down menu look correct
        $smarty->assign('total_pages', 1);
        
    }
  
    /* Return the records */
         
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching client records."));
        
    } else {        
        
        $records = $rs->GetArray();   // If I call this twice for this search, no results are shown on the TPL

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** Insert Functions **/

#####################################
#    Insert new client              #
#####################################

function insert_client($qform) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."client_records SET
            opened_on       =". $db->qstr( mysql_datetime()         ).",
            company_name    =". $db->qstr( $qform['company_name']     ).",
            first_name      =". $db->qstr( $qform['first_name']       ).",
            last_name       =". $db->qstr( $qform['last_name']        ).",
            website         =". $db->qstr( process_inputted_url($qform['website'])).",
            email           =". $db->qstr( $qform['email']            ).",     
            credit_terms    =". $db->qstr( $qform['credit_terms']     ).",
            unit_discount_rate   =". $db->qstr( $qform['unit_discount_rate']    ).",
            type            =". $db->qstr( $qform['type']             ).",
            active          =". $db->qstr( $qform['active']           ).",
            primary_phone   =". $db->qstr( $qform['primary_phone']    ).",    
            mobile_phone    =". $db->qstr( $qform['mobile_phone']     ).",
            fax             =". $db->qstr( $qform['fax']              ).",
            address         =". $db->qstr( $qform['address']          ).",
            city            =". $db->qstr( $qform['city']             ).", 
            state           =". $db->qstr( $qform['state']            ).", 
            zip             =". $db->qstr( $qform['zip']              ).",
            country         =". $db->qstr( $qform['country']          ).",
            note            =". $db->qstr( $qform['note']             );          
                        
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the client record into the database."));
    } else {
        
        $client_id = $db->Insert_ID();
        
        // Log activity
        $record = _gettext("New client").', '.get_client_details($client_id, 'display_name').', '._gettext("has been created.");
        write_record_to_activity_log($record, null, $db->Insert_ID());  
        
        return $client_id;
        
    }
    
} 

#############################
#    Insert client note     #
#############################

function insert_client_note($client_id, $note) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."client_notes SET            
            employee_id =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            client_id   =". $db->qstr( $client_id                           ).",
            date        =". $db->qstr( mysql_datetime()                     ).",
            note        =". $db->qstr( $note                                );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the client note into the database."));
        
    } else {
        
        // Log activity        
        $record = _gettext("A new client note was added to the client").' '.get_client_details($client_id, 'display_name').' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $client_id);
        
        // Update last active record      
        update_client_last_active($client_id);
        
        return true;
        
    }
    
}

/** Get Functions **/

################################
#  Get Client Details          #
################################

function get_client_details($client_id, $item = null) {
    
    // This allows blank calls (i.e. payment:details, not all records have a client_id)
    if(!$client_id) {
        return;        
    }
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."client_records WHERE client_id=".$db->qstr($client_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the client's details."));
    } else { 
        
        if($item === null) {
            
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
            if($item == 'full_name') {
                $results = $rs->GetRowAssoc();                
                return $results['first_name'].' '.$results['last_name'];                               
            } 
            
            // Return static item
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#  Get a single client note         #
#####################################

function get_client_note_details($client_note_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."client_notes WHERE client_note_id=".$db->qstr($client_note_id);    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the client note."));
    } else { 
        
        if($item === null){
            
            return $rs->GetRowAssoc(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#  Get ALL of a client's notes      #
#####################################

function get_client_notes($client_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT 
            ".PRFX."client_notes.*,
            ".PRFX."user_records.first_name,
            ".PRFX."user_records.last_name,
                
            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name
            
            FROM ".PRFX."client_notes
            LEFT JOIN ".PRFX."user_records ON ".PRFX."client_notes.employee_id = ".PRFX."user_records.user_id
            WHERE ".PRFX."client_notes.client_id=".$db->qstr($client_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the client's notes."));
    } else {
        
        return $rs->GetArray(); 
        
    }   
    
}

#####################################
#    Get Client Types               #
#####################################

function get_client_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."client_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get client types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#    Update Client                  #
#####################################

function update_client($qform) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."client_records SET
            company_name    =". $db->qstr( $qform['company_name']     ).",
            first_name      =". $db->qstr( $qform['first_name']       ).",
            last_name       =". $db->qstr( $qform['last_name']        ).",
            website         =". $db->qstr( process_inputted_url($qform['website'])).",
            email           =". $db->qstr( $qform['email']            ).",     
            credit_terms    =". $db->qstr( $qform['credit_terms']     ).",               
            unit_discount_rate   =". $db->qstr( $qform['unit_discount_rate']    ).",
            type            =". $db->qstr( $qform['type']             ).", 
            active          =". $db->qstr( $qform['active']           ).", 
            primary_phone   =". $db->qstr( $qform['primary_phone']    ).",    
            mobile_phone    =". $db->qstr( $qform['mobile_phone']     ).",
            fax             =". $db->qstr( $qform['fax']              ).",
            address         =". $db->qstr( $qform['address']          ).",
            city            =". $db->qstr( $qform['city']             ).", 
            state           =". $db->qstr( $qform['state']            ).", 
            zip             =". $db->qstr( $qform['zip']              ).",
            country         =". $db->qstr( $qform['country']          ).",
            note            =". $db->qstr( $qform['note']             )."
            WHERE client_id  =". $db->qstr( $qform['client_id']       );
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the Client's details."));
    } else {
        
        // Log activity        
        $record = _gettext("The client").' '.get_client_details($qform['client_id'], 'display_name').' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, null, $qform['client_id']);
        
        // Update last active record      
        update_client_last_active($qform['client_id']);
        
      return true;
      
    }
    
} 

#############################
#   update client note      #
#############################

function update_client_note($client_note_id, $note) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."client_notes SET
            employee_id             =". $db->qstr( QFactory::getUser()->login_user_id   ).",            
            note                    =". $db->qstr( $note                                )."
            WHERE client_note_id    =". $db->qstr( $client_note_id                      );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the client note."));
        
    } else {
        
        // get client_id
        $client_id = get_client_note_details($client_note_id, 'client_id');
        
        // Log activity        
        $record = _gettext("Client Note").' '.$client_note_id.' '._gettext("for").' '.get_client_details($client_id, 'display_name').' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $client_id);
        
        // Update last active record        
        update_client_last_active($client_id);
        
    }
    
}

#################################
#    Update Last Active         #
#################################

function update_client_last_active($client_id = null) {
    
    $db = QFactory::getDbo();
    
    // compensate for some operations not having a client_id - i.e. sending some emails
    if(!$client_id) { return; }    
    
    $sql = "UPDATE ".PRFX."client_records SET
            last_active=".$db->qstr( mysql_datetime() )."
            WHERE client_id=".$db->qstr($client_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Client's last active time."));
    }
    
}

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Client                  #
#####################################

function delete_client($client_id) {
    
    $db = QFactory::getDbo();
    
    // Make sure the client can be deleted 
    if(!check_client_can_be_deleted($client_id)) {        
        return false;
    }
        
    /* We can now delete the client */
    
    // Get client details for logging before we delete anything
    $client_details = get_client_details($client_id);
    
    // Delete any Client user accounts
    $sql = "DELETE FROM ".PRFX."user_records WHERE client_id=".$db->qstr($client_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the client's users from the database."));
    }
    
    // Delete Client
    $sql = "DELETE FROM ".PRFX."client_records WHERE client_id=".$db->qstr($client_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the client from the database."));
    }
    
    // Write the record to the activity log                    
    $record = _gettext("The client").' '.$client_details['display_name'].' '._gettext("has been deleted by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, null, $client_id);
    
    return true;
    
}

##################################
#    Delete a client's note      #
##################################

function delete_client_note($client_note_id) {
    
    $db = QFactory::getDbo();
    
    // Get information before deleting the record
    $client_id = get_client_note_details($client_note_id, 'client_id');
    $employee_id = get_client_note_details($client_note_id, 'employee_id');
    
    $sql = "DELETE FROM ".PRFX."client_notes WHERE client_note_id=".$db->qstr($client_note_id);

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the client note."));
        
    } else {        
        
        $client_details = get_client_details($client_id);
        
        // Log activity        
        $record = _gettext("Client Note").' '.$client_note_id.' '._gettext("for Client").' '.$client_details['display_name'].' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $employee_id, $client_id);
        
        // Update last active record        
        update_client_last_active($client_id);
        
    }
    
}

/** Other Functions **/

#########################################
#    check for Duplicate display name   #  // is not currently used
#########################################
    
function check_client_display_name_exists($display_name) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT COUNT(*) AS count FROM ".PRFX."client_records WHERE display_name=".$db->qstr($display_name);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check the submitted Display Name for duplicates in the database."));
    } else {
        $row = $rs->FetchRow();
    }

    if ($row['count'] == 1) {
        
        return false;    
        
    } else {
        
        return true;
        
    }
    
}

#####################################
#    Build a Google map string      #
#####################################

function build_googlemap_directions_string($client_id, $employee_id) {
    
    $company_details    = get_company_details();
    $client_details     = get_client_details($client_id);
    $employee_details   = get_user_details($employee_id);
    
    // Get google server or use default value, then removes a trailing slash if present
    $google_server = rtrim(QFactory::getConfig()->get('google_server', 'https://www.google.com/'), '/');
    
    // Determine the employee's start location
    if ($employee_details['based'] == 'office' || $employee_details['based'] == 'onsite') {
        
        // Works from the office
        $employee_address  = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $company_details['address']);
        $employee_city     = $company_details['city'];
        $employee_zip      = $company_details['zip'];
        
    } else {        
        
        // Works from home
        $employee_address  = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $employee_details['home_address']);
        $employee_city     = $employee_details['home_city'];
        $employee_zip      = $employee_details['home_zip'];
        
    }
    
    // Get Client's Address    
    $client_address   = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $client_details['address']);
    $client_city      = $client_details['city'];
    $client_zip       = $client_details['zip'];
    
    // return the built google map string
    return "$google_server/maps?f=d&source=s_d&hl=en&geocode=&saddr=$employee_address,$employee_city,$employee_zip&daddr=$client_address,$client_city,$client_zip";
   
}

###############################################################
#   Check to see if the client can be deleted                 #
###############################################################

function check_client_can_be_deleted($client_id) {
    
    $db = QFactory::getDbo();
    
    // Check if client has any workorders
    $sql = "SELECT count(*) as count FROM ".PRFX."workorder_records WHERE client_id=".$db->qstr($client_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the client's Workorders in the database."));
    }  
    if($rs->fields['count'] > 0 ) {
        //postEmulationWrite('warning_msg', 'You can not delete a client who has work orders.');
        return false;
    }
    
    // Check if client has any invoices
    $sql = "SELECT count(*) as count FROM ".PRFX."invoice_records WHERE client_id=".$db->qstr($client_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the client's Invoices in the database."));
    }    
    if($rs->fields['count'] > 0 ) {
        //postEmulationWrite('warning_msg', 'You can not delete a client who has invoices.');
        return false;
    }    
    
    // Check if client has any Vouchers
    $sql = "SELECT count(*) as count FROM ".PRFX."voucher_records WHERE client_id=".$db->qstr($client_id);
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the client's Vouchers in the database."));
    }  
    if($rs->fields['count'] > 0 ) {
        //postEmulationWrite('warning_msg', 'You can not delete a client who has Vouchers.');
        return false;
    }
    
    // Check if client has any client notes
    $sql = "SELECT count(*) as count FROM ".PRFX."client_notes WHERE client_id=".$db->qstr($client_id);
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the client's Notes in the database."));
    }    
    if($rs->fields['count'] > 0 ) {
        //postEmulationWrite('warning_msg', 'You can not delete a client who has client notes.');
        return false;
    }

    // All checks passed
    return true;
    
}