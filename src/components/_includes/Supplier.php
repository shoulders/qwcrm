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
 * Other Functions - All other public functions not covered above
 */

defined('_QWEXEC') or die;

class Supplier extends Components {

    /** Insert Functions **/

    ##########################################
    #      Insert New Record                 #
    ##########################################

    public function insertRecord($qform) {

        $sql = "INSERT INTO ".PRFX."supplier_records SET       
                employee_id    =". $this->app->db->qstr( $this->app->user->login_user_id ).",
                company_name   =". $this->app->db->qstr( $qform['company_name']  ).",
                first_name     =". $this->app->db->qstr( $qform['first_name']    ).",
                last_name      =". $this->app->db->qstr( $qform['last_name']     ).",
                website        =". $this->app->db->qstr( $this->app->system->general->processInputtedUrl($qform['website'])).",
                email          =". $this->app->db->qstr( $qform['email']         ).",
                type           =". $this->app->db->qstr( $qform['type']          ).",
                primary_phone  =". $this->app->db->qstr( $qform['primary_phone'] ).",
                mobile_phone   =". $this->app->db->qstr( $qform['mobile_phone']  ).",
                fax            =". $this->app->db->qstr( $qform['fax']           ).",
                address        =". $this->app->db->qstr( $qform['address']       ).",
                city           =". $this->app->db->qstr( $qform['city']          ).",
                state          =". $this->app->db->qstr( $qform['state']         ).",
                zip            =". $this->app->db->qstr( $qform['zip']           ).",
                country        =". $this->app->db->qstr( $qform['country']       ).",
                status         =". $this->app->db->qstr( 'valid'               ).",
                opened_on      =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()      ).", 
                description    =". $this->app->db->qstr( $qform['description']   ).", 
                note           =". $this->app->db->qstr( $qform['note']          );            

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to insert the supplier record into the database."));
        } else {

            // Log activity        
            $record = _gettext("Supplier Record").' '.$this->app->db->Insert_ID().' ('.$qform['company_name'].') '._gettext("created.");
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

            return $this->app->db->Insert_ID();

        }

    } 
    
    /** Get Functions **/

    ###############################
    #     Display Suppliers       #
    ###############################

    public function getRecords($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $type = null, $status = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';
        $search_category = $search_category ?: 'supplier_id';
        $havingTheseRecords = '';

        /* Records Search */ 

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."supplier_records.supplier_id\n";

        // Search category (display_name) and search term
        if($search_category == 'display_name') {$havingTheseRecords .= " HAVING display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Search category (full_name) and search term
        elseif($search_category == 'full_name') {$havingTheseRecords .= " HAVING full_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Restrict results by search category and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."supplier_records.$search_category LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        /* Filter the Records */ 

        // Restrict by Type
        if($type) { $whereTheseRecords .= " AND ".PRFX."supplier_records.type= ".$this->app->db->qstr($type);}

        // Restrict by status
        if($status) {$whereTheseRecords .= " AND ".PRFX."supplier_records.status= ".$this->app->db->qstr($status);} 

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
            if(!$rs = $this->app->db->execute($sql)) {
                $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to count the matching supplier records."));
            } else {        
                $total_results = $rs->RecordCount();            
                $this->app->smarty->assign('total_results', $total_results);
            }        

            // Figure out the total number of pages. Always round up using ceil()
            $total_pages = ceil($total_results / $records_per_page);
            $this->app->smarty->assign('total_pages', $total_pages);

            // Set the page number
            $this->app->smarty->assign('page_no', $page_no);

            // Assign the Previous page        
            $previous_page_no = ($page_no - 1);        
            $this->app->smarty->assign('previous_page_no', $previous_page_no);          

            // Assign the next page        
            if($page_no == $total_pages) {$next_page_no = 0;}
            elseif($page_no < $total_pages) {$next_page_no = ($page_no + 1);}
            else {$next_page_no = $total_pages;}
            $this->app->smarty->assign('next_page_no', $next_page_no);

            // Only return the given page's records
            $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;

            // add the restriction on to the SQL
            $sql .= $limitTheseRecords;

        } else {

            // This make the drop down menu look correct
            $this->app->smarty->assign('total_pages', 1);

        }

        /* Return the records */

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching supplier records."));
        } else {

            $records = $rs->GetArray();

            if(empty($records)){

                return false;

            } else {

                return $records;

            }

        }

    }

    ############################
    #   Get supplier details   #
    ############################

    public function getRecord($supplier_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."supplier_records WHERE supplier_id=".$this->app->db->qstr($supplier_id);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get the supplier details."));
        } else {

            if($item === null){

                $results = $rs->GetRowAssoc();

                // Add these dynamically created fields           
                $results['display_name'] = $results['company_name'] ?: $results['first_name'].' '.$results['last_name'];
                $results['full_name'] = $results['first_name'].' '.$results['last_name'];

                return $results;          

            } else {

                // Return the dynamically created 'display_name'
                if($item == 'display_name') {
                    $results = $rs->GetRowAssoc();
                    return $results['company_name'] ?: $results['first_name'].' '.$results['last_name'];
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

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."supplier_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('invalid')";  // NB: 'invalid' does not currently exist
        }

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get Supplier statuses."));
        } else {

            return $rs->GetArray();     

        }    

    }

    #######################################
    #  Get Supplier status display name   #
    #######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."supplier_statuses WHERE status_key=".$this->app->db->qstr($status_key);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get the supplier status display name."));
        } else {

            return $rs->fields['display_name'];

        }    

    }

    #####################################
    #    Get Supplier Types             #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."supplier_types";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get supplier types."));
        } else {

            return $rs->GetArray();

        }    

    }
    
    ############################################
    #      Last supplier Record ID Look Up     #  // not curently used
    ############################################

    public function getLastRecordId() {

        $sql = "SELECT * FROM ".PRFX."supplier_records ORDER BY supplier_id DESC LIMIT 1";

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to lookup the last supplier record ID."));
        } else {

            return $rs->fields['supplier_id'];

        }

    }
    

    /** Update Functions **/

    #####################################
    #     Update Record                 #
    #####################################

    public function updateRecord($qform) {

        $sql = "UPDATE ".PRFX."supplier_records SET
                employee_id    =". $this->app->db->qstr( $this->app->user->login_user_id ).",
                company_name   =". $this->app->db->qstr( $qform['company_name']  ).",
                first_name     =". $this->app->db->qstr( $qform['first_name']    ).",
                last_name      =". $this->app->db->qstr( $qform['last_name']     ).",
                website        =". $this->app->db->qstr( $this->app->system->general->processInputtedUrl($qform['website'])).",
                email          =". $this->app->db->qstr( $qform['email']         ).",
                type           =". $this->app->db->qstr( $qform['type']          ).",
                primary_phone  =". $this->app->db->qstr( $qform['primary_phone'] ).",
                mobile_phone   =". $this->app->db->qstr( $qform['mobile_phone']  ).",
                fax            =". $this->app->db->qstr( $qform['fax']           ).",
                address        =". $this->app->db->qstr( $qform['address']       ).",
                city           =". $this->app->db->qstr( $qform['city']          ).",
                state          =". $this->app->db->qstr( $qform['state']         ).",
                zip            =". $this->app->db->qstr( $qform['zip']           ).",
                country        =". $this->app->db->qstr( $qform['country']       ).",
                last_active    =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()      ).",
                description    =". $this->app->db->qstr( $qform['description']   ).", 
                note           =". $this->app->db->qstr( $qform['note']          )."
                WHERE supplier_id = ". $this->app->db->qstr( $qform['supplier_id'] );                        

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update the supplier details."));
        } else {

            // Log activity      
            $record = _gettext("Supplier Record").' '.$this->app->db->Insert_ID().' ('.$qform['company_name'].') '._gettext("updated.");
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

            return true;

        }

    } 

    #############################
    # Update Supplier Status    #
    #############################

    public function updateStatus($supplier_id, $new_status, $silent = false) {

        // Get supplier details
        $supplier_details = $this->getRecord($supplier_id);

        // if the new status is the same as the current one, exit
        if($new_status == $supplier_details['status']) {        
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }    

        // Unify Dates and Times
        $datetime = $this->app->system->general->mysqlDatetime();

        // Set the appropriate closed_on date
        $closed_on = ($new_status == 'closed') ? $datetime : '0000-00-00 00:00:00';

        $sql = "UPDATE ".PRFX."supplier_records SET
                status             =". $this->app->db->qstr( $new_status   )."
                closed_on          =". $this->app->db->qstr( $closed_on    )." 
                last_active        =". $this->app->db->qstr( $datetime     )." 
                WHERE supplier_id  =". $this->app->db->qstr( $supplier_id  );

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update an supplier Status."));

        } else {    

            // Status updated message
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("supplier status updated.")); }

            // For writing message to log file, get supplier status display name
            $supplier_status_display_name = _gettext($this->getStatusDisplayName($new_status));

            // Log activity        
            $record = _gettext("Supplier").' '.$supplier_id.' '._gettext("Status updated to").' '.$supplier_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

            return true;

        }

    }

    /** Close Functions **/

    #####################################
    #   Cancel Supplier                 #
    #####################################

    public function cancelRecord($supplier) {

        // Make sure the supplier can be cancelled
        if(!$this->checkRecordAllowsCancel($supplier)) {        
            return false;
        }

        // Get supplier details
        //$supplier_details = $this->get_supplier_details($supplier);  

        // Change the supplier status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($supplier, 'cancelled');      

        // Log activity        
        $record = _gettext("Supplier").' '.$supplier.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Record                  #
    #####################################

    public function deleteRecord($supplier_id) {

        $display_name = $this->getRecord($supplier_id, 'display_name');

        // Make sure the supplier can be deleted 
        if(!$this->checkRecordAllowsDelete($supplier_id)) {        
            return false;
        }

        $sql = "DELETE FROM ".PRFX."supplier_records WHERE supplier_id=".$this->app->db->qstr($supplier_id);

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to delete the supplier record."));
        } else {

            // Log activity     
            $record = _gettext("Supplier Record").' '.$supplier_id.' ('.$display_name.') '._gettext("deleted.");
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

            return true;

        }

    }

    /** Check Functions **/

    ###########################################################
    #  Check if the supplier status is allowed to be changed  #  // not currently used
    ###########################################################

     public function checkRecordAllowsChange($supplier_id) {

        $state_flag = true;

        // Get the supplier details
        //$supplier_details = $this->get_supplier_details($supplier_id); 

        /* Is cancelled
        if($supplier_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The supplier cannot be changed because the supplier has been deleted."));
            $state_flag = false;       
        }*/

        return $state_flag;   

     }


    ###############################################################
    #   Check to see if the supplier can be cancelled             #  // not currently used
    ###############################################################

    public function checkRecordAllowsCancel($supplier_id) {

        $state_flag = true;

        // Get the supplier details
        $supplier_details = $this->getRecord($supplier_id);   

        // Is cancelled
        if($supplier_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The supplier cannot be cancelled because the supplier has been deleted."));
            $state_flag = false;       
        }  

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the supplier can be deleted               #
    ###############################################################

    public function checkRecordAllowsDelete($supplier_id) {

        $state_flag = true;

        // Get the supplier details
        //$supplier_details = $this->get_supplier_details($supplier_id);

        /* Is cancelled
        if($supplier_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This supplier cannot be deleted because it has been cancelled."));
            $state_flag = false;       
        }*/

        return $state_flag;

    }

    ##########################################################
    #  Check if the supplier status allows editing           #  // not currently used
    ##########################################################

     public function checkRecordAllowsEdit($supplier_id) {

        $state_flag = true;

        // Get the supplier details
        $supplier_details = $this->getRecord($supplier_id);

        // Is cancelled
        if($supplier_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The supplier cannot be edited because it has been cancelled."));
            $state_flag = false;       
        }

        return $state_flag;

    }
    
        
    /** Other Functions **/
    
}