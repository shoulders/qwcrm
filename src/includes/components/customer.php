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
#   Display Customers               #
#####################################

function display_customers($order_by = 'customer_id', $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $status = null, $type = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();

    /* Records Search */
    
    // Default Action    
    $whereTheseRecords = " WHERE ".PRFX."customer_records.customer_id\n";    
    
    // Search category (contact) and search term
    if($search_category == 'contact') {$havingTheseRecords .= " HAVING contact LIKE '%$search_term%'";}
    
    // Search category with search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."customer_records.$search_category LIKE '%$search_term%'";}     
    
    /* Filter the Records */    
    
    // Restrict by Status
    if($status) {$whereTheseRecords .= " AND ".PRFX."customer_records.active=".$db->qstr($status);}
    
    // Restrict by Type
    if($type) {$whereTheseRecords .= " AND ".PRFX."customer_records.type= ".$db->qstr($type);}    

    /* The SQL code */    
    
    $sql = "SELECT        
        ".PRFX."customer_records.*,            
        CONCAT(".PRFX."customer_records.first_name, ' ', ".PRFX."customer_records.last_name) AS contact
        
        FROM ".PRFX."customer_records            
 
        ".$whereTheseRecords."
        GROUP BY ".PRFX."customer_records.".$order_by."
        ".$havingTheseRecords."
        ORDER BY ".PRFX."customer_records.".$order_by."
        ".$direction;  
   
    /* Restrict by pages */
        
    if($use_pages) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the number of matching customer records."));
        } else {        
            $total_results = $rs->RecordCount();            
            $smarty->assign('total_results', $total_results);
        }  
        
        // Figure out the total number of pages. Always round up using ceil()
        $total_pages = ceil($total_results / $records_per_page);
        $smarty->assign('total_pages', $total_pages);

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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching customer records."));
        
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
#    Insert new customer            #
#####################################

function insert_customer($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."customer_records SET
            display_name    =". $db->qstr( $VAR['display_name']     ).",
            first_name      =". $db->qstr( $VAR['first_name']       ).",
            last_name       =". $db->qstr( $VAR['last_name']        ).",
            website         =". $db->qstr( $VAR['website']          ).",
            email           =". $db->qstr( $VAR['email']            ).",     
            credit_terms    =". $db->qstr( $VAR['credit_terms']     ).",
            discount_rate   =". $db->qstr( $VAR['discount_rate']    ).",
            type            =". $db->qstr( $VAR['type']             ).",
            active          =". $db->qstr( $VAR['active']           ).",
            primary_phone   =". $db->qstr( $VAR['primary_phone']    ).",    
            mobile_phone    =". $db->qstr( $VAR['mobile_phone']     ).",
            fax             =". $db->qstr( $VAR['fax']              ).",
            address         =". $db->qstr( $VAR['address']          ).",
            city            =". $db->qstr( $VAR['city']             ).", 
            state           =". $db->qstr( $VAR['state']            ).", 
            zip             =". $db->qstr( $VAR['zip']              ).",
            country         =". $db->qstr( $VAR['country']          ).",
            note            =". $db->qstr( $VAR['note']            ).",
            create_date     =". $db->qstr( time()                   );          
                        
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the customer record into the database."));
    } else {
        
        // Log activity
        $record = _gettext("New customer").', '.$VAR['display_name'].', '._gettext("has been created.");
        write_record_to_activity_log($record, null, $db->Insert_ID());  
        
        return $db->Insert_ID();
        
    }
    
} 

#############################
#    Insert customer note   #
#############################

function insert_customer_note($customer_id, $note) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."customer_notes SET            
            employee_id =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            customer_id =". $db->qstr( $customer_id                         ).",
            date        =". $db->qstr( time()                               ).",
            note        =". $db->qstr( $note                                );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the customer note into the database."));
        
    } else {
        
        // Log activity        
        $record = _gettext("A new customer note was added to the customer").' '.get_customer_details($customer_id, 'display_name').' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $customer_id);
        
        // Update last active record      
        update_customer_last_active($customer_id);
        
        return true;
        
    }
    
}

/** Get Functions **/

################################
#  Get Customer Details        #
################################

function get_customer_details($customer_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."customer_records WHERE customer_id=".$db->qstr($customer_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the customer's details."));
    } else { 
        
        if($item === null){
            
            return $rs->GetRowAssoc(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#  Get a single customer note       #
#####################################

function get_customer_note($customer_note_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."customer_notes WHERE customer_note_id=".$db->qstr( $customer_note_id );    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the customer note."));
    } else { 
        
        if($item === null){
            
            return $rs->GetRowAssoc(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#  Get ALL of a customer's notes    #
#####################################

function get_customer_notes($customer_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."customer_notes WHERE customer_id=".$db->qstr( $customer_id );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the customer's notes."));
    } else {
        
        return $rs->GetArray(); 
        
    }   
    
}

#####################################
#    Get Customer Types             #
#####################################

function get_customer_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."customer_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get customer types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#    Update Customer                #
#####################################

function update_customer($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."customer_records SET
            display_name    =". $db->qstr( $VAR['display_name']     ).",
            first_name      =". $db->qstr( $VAR['first_name']       ).",
            last_name       =". $db->qstr( $VAR['last_name']        ).",
            website         =". $db->qstr( $VAR['website']          ).",
            email           =". $db->qstr( $VAR['email']            ).",     
            credit_terms    =". $db->qstr( $VAR['credit_terms']     ).",               
            discount_rate   =". $db->qstr( $VAR['discount_rate']    ).",
            type            =". $db->qstr( $VAR['type']             ).", 
            active          =". $db->qstr( $VAR['active']           ).", 
            primary_phone   =". $db->qstr( $VAR['primary_phone']    ).",    
            mobile_phone    =". $db->qstr( $VAR['mobile_phone']     ).",
            fax             =". $db->qstr( $VAR['fax']              ).",
            address         =". $db->qstr( $VAR['address']          ).",
            city            =". $db->qstr( $VAR['city']             ).", 
            state           =". $db->qstr( $VAR['state']            ).", 
            zip             =". $db->qstr( $VAR['zip']              ).",
            country         =". $db->qstr( $VAR['country']          ).",
            note            =". $db->qstr( $VAR['note']             )."
            WHERE customer_id  =". $db->qstr( $VAR['customer_id']   );
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the Customer's details."));
    } else {
        
        // Log activity        
        $record = _gettext("The customer").' '.$VAR['display_name'].' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, null, $customer_id);
        
        // Update last active record      
        update_customer_last_active($customer_id);
        
      return true;
      
    }
    
} 

#############################
#    update customer note   #
#############################

function update_customer_note($customer_note_id, $note) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."customer_notes SET
            employee_id             =". $db->qstr( QFactory::getUser()->login_user_id   ).",            
            note                    =". $db->qstr( $note                                )."
            WHERE customer_note_id  =". $db->qstr( $customer_note_id                    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the customer note."));
        
    } else {
        
        // get customer_id
        $customer_id = get_customer_note($customer_note_id, 'customer_id');
        
        // Log activity        
        $record = _gettext("Customer Note").' '.$customer_note_id.' '._gettext("for").' '.get_customer_details($customer_id, 'display_name').' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $customer_id);
        
        // Update last active record        
        update_customer_last_active($customer_id);
        
    }
    
}

#################################
#    Update Last Active         #
#################################

function update_customer_last_active($customer_id = null) {
    
    $db = QFactory::getDbo();
    
    // compensate for some operations not having a customer_id - i.e. sending some emails
    if(!$customer_id) { return; }    
    
    $sql = "UPDATE ".PRFX."customer_records SET
            last_active=".$db->qstr(time())."
            WHERE customer_id=".$db->qstr($customer_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Customer's last active time."));
    }
    
}

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Customer                #
#####################################

function delete_customer($customer_id) {
    
    $db = QFactory::getDbo();
    
    // Check if customer has any workorders
    $sql = "SELECT count(*) as count FROM ".PRFX."workorder_records WHERE customer_id=".$db->qstr($customer_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the customer's Workorders in the database."));
    }  
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', 'You can not delete a customer who has work orders.');
        return false;
    }
    
    // Check if customer has any invoices
    $sql = "SELECT count(*) as count FROM ".PRFX."invoice_records WHERE customer_id=".$db->qstr($customer_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the customer's Invoices in the database."));
    }    
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', 'You can not delete a customer who has invoices.');
        return false;
    }    
    
    // Check if customer has any gift certificates
    $sql = "SELECT count(*) as count FROM ".PRFX."giftcert_records WHERE customer_id=".$db->qstr($customer_id);
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the customer's Gift Certificates in the database."));
    }  
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', 'You can not delete a customer who has gift certificates.');
        return false;
    }
    
    // Check if customer has any customer notes
    $sql = "SELECT count(*) as count FROM ".PRFX."customer_notes WHERE customer_id=".$db->qstr($customer_id);
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the customer's Notes in the database."));
    }    
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', 'You can not delete a customer who has customer notes.');
        return false;
    }
    
    /* We can now delete the customer */
    
    // Get customer details foe loggin before we delete anything
    $customer_details = get_customer_details($customer_id);
    
    // Delete any Customer user accounts
    $sql = "DELETE FROM ".PRFX."user_records WHERE customer_id=".$db->qstr($customer_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the customer's users from the database."));
    }
    
    // Delete Customer
    $sql = "DELETE FROM ".PRFX."customer_records WHERE customer_id=".$db->qstr($customer_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the customer from the database."));
    }
    
    // Write the record to the activity log                    
    $record = _gettext("The customer").' '.$customer_details['display_name'].' '._gettext("has been deleted by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, null, $customer_id);
    
    return true;
    
}

##################################
#    delete a customer's note    #
##################################

function delete_customer_note($customer_note_id) {
    
    $db = QFactory::getDbo();
    
    // get customer_id before deleting the record
    $customer_id = get_customer_note($customer_note_id, 'customer_id');
    
    $sql = "DELETE FROM ".PRFX."customer_notes WHERE customer_note_id=".$db->qstr( $customer_note_id );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the customer note."));
        
    } else {        
        
        $customer_details = get_customer_details($customer_id);
        
        // Log activity        
        $record = _gettext("Customer Note").' '.$customer_note_id.' '._gettext("for Customer").' '.$customer_details['display_name'].' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $customer_details['employee_id'], $customer_id);
        
        // Update last active record        
        update_customer_last_active($customer_id);
        
    }
    
}

/** Other Functions **/

#########################################
#    check for Duplicate display name   #  // is not currently used
#########################################
    
function check_customer_display_name_exists($display_name) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT COUNT(*) AS count FROM ".PRFX."customer_records WHERE display_name=".$db->qstr($display_name);
    
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

function build_googlemap_directions_string($customer_id, $employee_id) {
    
    $company_details    = get_company_details();
    $customer_details   = get_customer_details($customer_id);
    $employee_details   = get_user_details($employee_id);
    
    // Get google server or use default value, then removes a trailing slash if present
    $google_server = rtrim(QFactory::getConfig()->get('google_server', 'https://www.google.com/'), '/');
    
    // Determine the employee's start location (1 = Office, 2 = Home, Onsite = 3)
    if ($employee_details['based'] == 1 || $employee_details['based'] == 3){
        
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
    
    // Get Customer's Address    
    $customer_address   = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $customer_details['address']);
    $customer_city      = $customer_details['city'];
    $customer_zip       = $customer_details['zip'];
    
    // return the built google map string
    return "$google_server/maps?f=d&source=s_d&hl=en&geocode=&saddr=$employee_address,$employee_city,$employee_zip&daddr=$customer_address,$customer_city,$customer_zip";
   
}