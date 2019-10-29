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

class Client extends Components {

    /** Mandatory Code **/

    /** Display Functions **/

    #####################################
    #   Display Clients                 #
    #####################################

    public function display_clients($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $type = null, $status = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';   
        $search_category = $search_category ?: 'client_id';
        $havingTheseRecords = '';

        /* Records Search */

        // Default Action    
        $whereTheseRecords = " WHERE ".PRFX."client_records.client_id\n";    

        // Search category (display_name) and search term
        if($search_category == 'display_name') { $havingTheseRecords .= " HAVING display_name LIKE ".$this->db->qstr('%'.$search_term.'%'); }

        // Search category (full_name) and search term
        elseif($search_category == 'full_name') { $havingTheseRecords .= " HAVING full_name LIKE ".$this->db->qstr('%'.$search_term.'%'); }

        // Search category with search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."client_records.$search_category LIKE ".$this->db->qstr('%'.$search_term.'%');}     

        /* Filter the Records */     

        // Restrict by Type
        if($type) {$whereTheseRecords .= " AND ".PRFX."client_records.type= ".$this->db->qstr($type);}    

        // Restrict by Status (is null because using boolean/integer)
        if(!is_null($status)) {$whereTheseRecords .= " AND ".PRFX."client_records.active=".$this->db->qstr($status);}

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
            if(!$rs = $this->db->Execute($sql)) {
                $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to count the number of matching client records."));
            } else {        
                $total_results = $rs->RecordCount();            
                $this->smarty->assign('total_results', $total_results);
            }  

            // Figure out the total number of pages. Always round up using ceil()
            $total_pages = ceil($total_results / $records_per_page);
            $this->smarty->assign('total_pages', $total_pages);

            // Set the page number
            $this->smarty->assign('page_no', $page_no);

            // Assign the Previous page        
            $previous_page_no = ($page_no - 1);        
            $this->smarty->assign('previous_page_no', $previous_page_no);          

            // Assign the next page        
            if($page_no == $total_pages) {$next_page_no = 0;}
            elseif($page_no < $total_pages) {$next_page_no = ($page_no + 1);}
            else {$next_page_no = $total_pages;}
            $this->smarty->assign('next_page_no', $next_page_no);

            // Only return the given page's records
            $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;

            // add the restriction on to the SQL
            $sql .= $limitTheseRecords;
            $rs = '';

        } else {

            // This make the drop down menu look correct
            $this->smarty->assign('total_pages', 1);

        }

        /* Return the records */

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to return the matching client records."));

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

    public function insert_client($qform) {

        $sql = "INSERT INTO ".PRFX."client_records SET
                opened_on       =". $this->db->qstr( $this->app->system->general->mysql_datetime()         ).",
                company_name    =". $this->db->qstr( $qform['company_name']     ).",
                first_name      =". $this->db->qstr( $qform['first_name']       ).",
                last_name       =". $this->db->qstr( $qform['last_name']        ).",
                website         =". $this->db->qstr( $this->app->components->general->process_inputted_url($qform['website'])).",
                email           =". $this->db->qstr( $qform['email']            ).",     
                credit_terms    =". $this->db->qstr( $qform['credit_terms']     ).",
                unit_discount_rate   =". $this->db->qstr( $qform['unit_discount_rate']    ).",
                type            =". $this->db->qstr( $qform['type']             ).",
                active          =". $this->db->qstr( $qform['active']           ).",
                primary_phone   =". $this->db->qstr( $qform['primary_phone']    ).",    
                mobile_phone    =". $this->db->qstr( $qform['mobile_phone']     ).",
                fax             =". $this->db->qstr( $qform['fax']              ).",
                address         =". $this->db->qstr( $qform['address']          ).",
                city            =". $this->db->qstr( $qform['city']             ).", 
                state           =". $this->db->qstr( $qform['state']            ).", 
                zip             =". $this->db->qstr( $qform['zip']              ).",
                country         =". $this->db->qstr( $qform['country']          ).",
                note            =". $this->db->qstr( $qform['note']             );          

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to insert the client record into the database."));
        } else {

            $client_id = $this->db->Insert_ID();

            // Log activity
            $record = _gettext("New client").', '.$this->get_client_details($client_id, 'display_name').', '._gettext("has been created.");
            $this->app->system->general->write_record_to_activity_log($record, null, $this->db->Insert_ID());  

            return $client_id;

        }

    } 

    #############################
    #    Insert client note     #
    #############################

    public function insert_client_note($client_id, $note) {

        $sql = "INSERT INTO ".PRFX."client_notes SET            
                employee_id =". $this->db->qstr( $this->app->user->login_user_id   ).",
                client_id   =". $this->db->qstr( $client_id                           ).",
                date        =". $this->db->qstr( $this->app->system->general->mysql_datetime()                     ).",
                note        =". $this->db->qstr( $note                                );

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to insert the client note into the database."));

        } else {

            // Log activity        
            $record = _gettext("A new client note was added to the client").' '.$this->get_client_details($client_id, 'display_name').' '._gettext("by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $client_id);

            // Update last active record      
            $this->update_client_last_active($client_id);

            return true;

        }

    }

    /** Get Functions **/

    ################################
    #  Get Client Details          #
    ################################

    public function get_client_details($client_id, $item = null) {

        // This allows blank calls (i.e. payment:details, not all records have a client_id)
        if(!$client_id) {
            return;        
        }

        $sql = "SELECT * FROM ".PRFX."client_records WHERE client_id=".$this->db->qstr($client_id);

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get the client's details."));
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

    public function get_client_note_details($client_note_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."client_notes WHERE client_note_id=".$this->db->qstr($client_note_id);    

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get the client note."));
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

    public function get_client_notes($client_id) {

        $sql = "SELECT 
                ".PRFX."client_notes.*,
                ".PRFX."user_records.first_name,
                ".PRFX."user_records.last_name,

                CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name

                FROM ".PRFX."client_notes
                LEFT JOIN ".PRFX."user_records ON ".PRFX."client_notes.employee_id = ".PRFX."user_records.user_id
                WHERE ".PRFX."client_notes.client_id=".$this->db->qstr($client_id);

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get the client's notes."));
        } else {

            return $rs->GetArray(); 

        }   

    }

    #####################################
    #    Get Client Types               #
    #####################################

    public function get_client_types() {

        $sql = "SELECT * FROM ".PRFX."client_types";

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get client types."));
        } else {

            return $rs->GetArray();

        }    

    }

    /** Update Functions **/

    #####################################
    #    Update Client                  #
    #####################################

    public function update_client($qform) {

        $sql = "UPDATE ".PRFX."client_records SET
                company_name    =". $this->db->qstr( $qform['company_name']     ).",
                first_name      =". $this->db->qstr( $qform['first_name']       ).",
                last_name       =". $this->db->qstr( $qform['last_name']        ).",
                website         =". $this->db->qstr( $this->app->components->general->process_inputted_url($qform['website'])).",
                email           =". $this->db->qstr( $qform['email']            ).",     
                credit_terms    =". $this->db->qstr( $qform['credit_terms']     ).",               
                unit_discount_rate   =". $this->db->qstr( $qform['unit_discount_rate']    ).",
                type            =". $this->db->qstr( $qform['type']             ).", 
                active          =". $this->db->qstr( $qform['active']           ).", 
                primary_phone   =". $this->db->qstr( $qform['primary_phone']    ).",    
                mobile_phone    =". $this->db->qstr( $qform['mobile_phone']     ).",
                fax             =". $this->db->qstr( $qform['fax']              ).",
                address         =". $this->db->qstr( $qform['address']          ).",
                city            =". $this->db->qstr( $qform['city']             ).", 
                state           =". $this->db->qstr( $qform['state']            ).", 
                zip             =". $this->db->qstr( $qform['zip']              ).",
                country         =". $this->db->qstr( $qform['country']          ).",
                note            =". $this->db->qstr( $qform['note']             )."
                WHERE client_id  =". $this->db->qstr( $qform['client_id']       );

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update the Client's details."));
        } else {

            // Log activity        
            $record = _gettext("The client").' '.$this->get_client_details($qform['client_id'], 'display_name').' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, null, $qform['client_id']);

            // Update last active record      
            $this->update_client_last_active($qform['client_id']);

          return true;

        }

    } 

    #############################
    #   update client note      #
    #############################

    public function update_client_note($client_note_id, $note) {

        $sql = "UPDATE ".PRFX."client_notes SET
                employee_id             =". $this->db->qstr( $this->app->user->login_user_id   ).",            
                note                    =". $this->db->qstr( $note                                )."
                WHERE client_note_id    =". $this->db->qstr( $client_note_id                      );

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update the client note."));

        } else {

            // get client_id
            $client_id = $this->get_client_note_details($client_note_id, 'client_id');

            // Log activity        
            $record = _gettext("Client Note").' '.$client_note_id.' '._gettext("for").' '.$this->get_client_details($client_id, 'display_name').' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $client_id);

            // Update last active record        
            $this->update_client_last_active($client_id);

        }

    }

    #################################
    #    Update Last Active         #
    #################################

    public function update_client_last_active($client_id = null) {

        // compensate for some operations not having a client_id - i.e. sending some emails
        if(!$client_id) { return; }    

        $sql = "UPDATE ".PRFX."client_records SET
                last_active=".$this->db->qstr( $this->app->system->general->mysql_datetime() )."
                WHERE client_id=".$this->db->qstr($client_id);

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update a Client's last active time."));
        }

    }

    /** Close Functions **/

    /** Delete Functions **/

    #####################################
    #    Delete Client                  #
    #####################################

    public function delete_client($client_id) {

        // Make sure the client can be deleted 
        if(!$this->check_client_can_be_deleted($client_id)) {        
            return false;
        }

        /* We can now delete the client */

        // Get client details for logging before we delete anything
        $client_details = $this->get_client_details($client_id);

        // Delete any Client user accounts
        $sql = "DELETE FROM ".PRFX."user_records WHERE client_id=".$this->db->qstr($client_id);    
        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to delete the client's users from the database."));
        }

        // Delete Client
        $sql = "DELETE FROM ".PRFX."client_records WHERE client_id=".$this->db->qstr($client_id);    
        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to delete the client from the database."));
        }

        // Write the record to the activity log                    
        $record = _gettext("The client").' '.$client_details['display_name'].' '._gettext("has been deleted by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->write_record_to_activity_log($record, null, $client_id);

        return true;

    }

    ##################################
    #    Delete a client's note      #
    ##################################

    public function delete_client_note($client_note_id) {

        // Get information before deleting the record
        $client_id = $this->get_client_note_details($client_note_id, 'client_id');
        $employee_id = $this->get_client_note_details($client_note_id, 'employee_id');

        $sql = "DELETE FROM ".PRFX."client_notes WHERE client_note_id=".$this->db->qstr($client_note_id);

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to delete the client note."));

        } else {        

            $client_details = $this->get_client_details($client_id);

            // Log activity        
            $record = _gettext("Client Note").' '.$client_note_id.' '._gettext("for Client").' '.$client_details['display_name'].' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $employee_id, $client_id);

            // Update last active record        
            $this->update_client_last_active($client_id);

        }

    }

    /** Other Functions **/

    #########################################
    #    check for Duplicate display name   #  // is not currently used
    #########################################

    public function check_client_display_name_exists($display_name) {

        $sql = "SELECT COUNT(*) AS count FROM ".PRFX."client_records WHERE display_name=".$this->db->qstr($display_name);

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to check the submitted Display Name for duplicates in the database."));
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

    public function build_googlemap_directions_string($client_id, $employee_id) {

        $company_details    = $this->app->components->company->get_company_details();
        $client_details     = $this->get_client_details($client_id);
        $employee_details   = $this->app->components->user->get_user_details($employee_id);

        // Get google server or use default value, then removes a trailing slash if present
        $google_server = rtrim($this->app->config->get('google_server', 'https://www.google.com/'), '/');

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

    public function check_client_can_be_deleted($client_id) {

        $state_flag = true;

        // Check if client has any workorders
        $sql = "SELECT count(*) as count FROM ".PRFX."workorder_records WHERE client_id=".$this->db->qstr($client_id);    
        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to count the client's Workorders in the database."));
        }  
        if($rs->fields['count'] > 0 ) {
            $this->app->system->variables->systemMessagesWrite('danger', 'You can not delete a client who has work orders.');
            $state_flag = false;
        }

        // Check if client has any invoices
        $sql = "SELECT count(*) as count FROM ".PRFX."invoice_records WHERE client_id=".$this->db->qstr($client_id);    
        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to count the client's Invoices in the database."));
        }    
        if($rs->fields['count'] > 0 ) {
            $this->app->system->variables->systemMessagesWrite('danger', 'You can not delete a client who has invoices.');
            $state_flag = false;
        }    

        // Check if client has any Vouchers
        $sql = "SELECT count(*) as count FROM ".PRFX."voucher_records WHERE client_id=".$this->db->qstr($client_id);
        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to count the client's Vouchers in the database."));
        }  
        if($rs->fields['count'] > 0 ) {
            $this->app->system->variables->systemMessagesWrite('danger', 'You can not delete a client who has Vouchers.');
            $state_flag = false;
        }

        // Check if client has any client notes
        $sql = "SELECT count(*) as count FROM ".PRFX."client_notes WHERE client_id=".$this->db->qstr($client_id);
        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to count the client's Notes in the database."));
        }    
        if($rs->fields['count'] > 0 ) {
            $this->app->system->variables->systemMessagesWrite('danger', 'You can not delete a client who has client notes.');
            $state_flag = false;
        }

        return $state_flag;

    }
    
}