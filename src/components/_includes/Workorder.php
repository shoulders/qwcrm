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

class WorkOrder extends Components {
    
    /** Insert Functions **/

    #########################
    # Insert New Work Order #
    #########################

    public function insertRecord($client_id, $scope, $description, $comment) {

        $sql = "INSERT INTO ".PRFX."workorder_records SET            
                client_id       =". $this->app->db->qstr( $client_id                           ).",
                created_by      =". $this->app->db->qstr( $this->app->user->login_user_id   ).",
                status          =". $this->app->db->qstr( 'unassigned'                         ).",
                opened_on       =". $this->app->db->qstr( $this->app->system->general->mysql_datetime()                     ).",            
                is_closed       =". $this->app->db->qstr( 0                                    ).",            
                scope           =". $this->app->db->qstr( $scope                               ).",
                description     =". $this->app->db->qstr( $description                         ).",            
                comment         =". $this->app->db->qstr( $comment                             );

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to insert the work order Record into the database."));

        } else {

            // Get the new Workorders ID
            $workorder_id = $this->app->db->Insert_ID();

            // Create a Workorder History Note            
            $this->insertHistory($workorder_id, _gettext("Created by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Created by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $client_id, $workorder_id);

            // Update last active record        
            $this->app->components->client->updateLastActive($this->getRecord($workorder_id, 'client_id'));

            return $workorder_id;

        }

    }
    
    ##############################
    #    insert workorder note   #
    ##############################

    public function insertNote($workorder_id, $note) {

        $sql = "INSERT INTO ".PRFX."workorder_notes SET                        
                employee_id     =". $this->app->db->qstr( $this->app->user->login_user_id   ).",
                workorder_id    =". $this->app->db->qstr( $workorder_id                        ).", 
                date            =". $this->app->db->qstr( $this->app->system->general->mysql_datetime()                     ).",
                description     =". $this->app->db->qstr( $note                                );

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to insert a work order note."));

        } else {

            // Get the new Note ID
            $workorder_note_id = $this->app->db->Insert_ID();

            // Get client id
            $client_id = $this->getRecord($workorder_id, 'client_id');

            // Create a Workorder History Note       
            $this->insertHistory($workorder_id, _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("added by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("added to Work Order").' '.$workorder_id.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $client_id, $workorder_id);

            // Update last active record
            $this->app->components->client->updateLastActive($client_id);
            $this->updateLastActive($workorder_id);

            return true;

        }

    }    

    ######################################
    # Insert New Work Order History      #  // this might be go in the main include as different modules add work order history notes
    ######################################

    public function insertHistory($workorder_id = null, $note = '') {

        $this->db = \Factory::getDbo();

        // If Work Order History Notes are not enabled, exit
        if($this->app->config->get('workorder_history_notes') != true) { return; }    

        // This prevents errors from such public functions as email.php where a workorder_id is not always present - not currently used
        if($workorder_id == null) { return; }

        $sql = "INSERT INTO ".PRFX."workorder_history SET            
                employee_id     =". $this->app->db->qstr( $this->app->user->login_user_id   ).",
                workorder_id    =". $this->app->db->qstr( $workorder_id                        ).",
                date            =". $this->app->db->qstr( $this->app->system->general->mysql_datetime()                     ).",
                note            =". $this->app->db->qstr( $note                                );

        if(!$rs = $this->app->db->Execute($sql)) {        
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to insert a work order history note."));
        } else {

            return true;

        }  

    }    
    
    /** Get Records **/    
    

    #####################################################
    #  Display all Work orders for the given status     #
    #####################################################

    public function getRecords($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $status = null, $employee_id = null, $client_id = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';
        $search_category = $search_category ?: 'workorder_id';
        $havingTheseRecords = '';

        /* Records Search */

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."workorder_records.workorder_id\n";    

        // Restrict results by search category (client) and search term
        if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

       // Restrict results by search category (employee) and search term
        elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Restrict results by search category and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."workorder_records.$search_category LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        /* Filter the Records */

        // Restrict by Status
        if($status) {

            // All Open workorders
            if($status == 'open') {

                $whereTheseRecords .= " AND ".PRFX."workorder_records.is_closed != '1'";

            // All Closed workorders
            } elseif($status == 'closed') {

                $whereTheseRecords .= " AND ".PRFX."workorder_records.is_closed = '1'";

            // Return Workorders for the given status
            } else {

                $whereTheseRecords .= " AND ".PRFX."workorder_records.status= ".$this->app->db->qstr($status);

            }

        }        

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."user_records.user_id=".$this->app->db->qstr($employee_id);}

        // Restrict by Client
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."client_records.client_id=".$this->app->db->qstr($client_id);}

        /* The SQL code */

        $sql =  "SELECT            
                ".PRFX."user_records.email AS employee_email,
                CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name,
                ".PRFX."user_records.work_primary_phone AS employee_work_primary_phone,
                ".PRFX."user_records.work_mobile_phone AS employee_work_mobile_phone,
                ".PRFX."user_records.home_primary_phone AS employee_home_primary_phone,

                ".PRFX."client_records.client_id,
                IF(company_name !='', company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,
                ".PRFX."client_records.first_name AS client_first_name,
                ".PRFX."client_records.last_name AS client_last_name,            
                ".PRFX."client_records.address AS client_address,
                ".PRFX."client_records.city AS client_city,
                ".PRFX."client_records.state AS client_state,
                ".PRFX."client_records.zip AS client_zip,
                ".PRFX."client_records.country AS client_country,
                ".PRFX."client_records.primary_phone AS client_phone,
                ".PRFX."client_records.mobile_phone AS client_mobile_phone,
                ".PRFX."client_records.fax AS client_fax,

                ".PRFX."workorder_records.workorder_id, employee_id, invoice_id,
                ".PRFX."workorder_records.opened_on AS workorder_opened_on,
                ".PRFX."workorder_records.closed_on AS workorder_closed_on,
                ".PRFX."workorder_records.scope AS workorder_scope,
                ".PRFX."workorder_records.status AS workorder_status,
                ".PRFX."workorder_records.status AS workorder_is_closed

                FROM ".PRFX."workorder_records
                LEFT JOIN ".PRFX."user_records ON ".PRFX."workorder_records.employee_id = ".PRFX."user_records.user_id
                LEFT JOIN ".PRFX."client_records ON ".PRFX."workorder_records.client_id = ".PRFX."client_records.client_id                 
                ".$whereTheseRecords."
                GROUP BY ".PRFX."workorder_records.".$order_by."
                ".$havingTheseRecords."
                ORDER BY ".PRFX."workorder_records.".$order_by."
                ".$direction;           

        /* Restrict by pages */

        if($use_pages) {

            // Get Start Record
            $start_record = (($page_no * $records_per_page) - $records_per_page);

            // Figure out the total number of records in the database for the given search        
            if(!$rs = $this->app->db->Execute($sql)) {
                $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to count the matching work orders."));
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

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching work orders."));
        } else {

            $records = $rs->GetArray();   // do i need to add the check empty

            if(empty($records)){

                // This prevents undefined variable error when there are no search results
                return false;

            } else {

                return $records;

            }

        }

    } 
    

    ########################################
    #   Get a Workorder's details          #
    ########################################

    public function getRecord($workorder_id = null, $item = null) {  

        // This covers invoice only
        if(!$workorder_id) {
            return;        
        }

        $sql = "SELECT * FROM ".PRFX."workorder_records WHERE workorder_id=".$this->app->db->qstr($workorder_id);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get work order details."));
        } else {

            if($item === null){

                return $rs->GetRowAssoc(); 

            } else {

                return $rs->fields[$item];   

            } 

        }

    }
        
    #############################
    # Display Work Order Notes  #
    #############################

    public function getNotes($workorder_id) {

        $sql = "SELECT
                ".PRFX."workorder_notes.*,
                CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name
                FROM
                ".PRFX."workorder_notes,
                ".PRFX."user_records
                WHERE workorder_id=".$this->app->db->qstr($workorder_id)."
                AND ".PRFX."user_records.user_id = ".PRFX."workorder_notes.employee_id";

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return notes for the work order."));
        } else {

            return $rs->GetArray(); 

        }

    }    
   
    #####################################
    #  Get a single workorder note      #
    #####################################

    public function getNote($workorder_note_id, $item = null) {

        $this->db = \Factory::getDbo();

        $sql = "SELECT * FROM ".PRFX."workorder_notes WHERE workorder_note_id=".$this->app->db->qstr($workorder_note_id);    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get a work order Note."));
        } else { 

            if($item === null){

                return $rs->GetRowAssoc(); 

            } else {

                return $rs->fields[$item];   

            } 

        }

    }       

    ##############################
    # Display Work Order History #
    ##############################

    public function getHistory($workorder_id) {

        $sql = "SELECT 
                ".PRFX."workorder_history.*,
                CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name
                FROM ".PRFX."workorder_history
                LEFT JOIN ".PRFX."user_records ON ".PRFX."workorder_history.employee_id = ".PRFX."user_records.user_id
                WHERE ".PRFX."workorder_history.workorder_id=".$this->app->db->qstr($workorder_id)." 
                AND ".PRFX."user_records.user_id = ".PRFX."workorder_history.employee_id
                ORDER BY ".PRFX."workorder_history.history_id";

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return history records for the work order."));
        } else {

            return $rs->GetArray();  

        }

    } 
    
    #####################################
    #    Get Workorder Statuses         #
    #####################################

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."workorder_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('closed_with_invoice', 'deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get work order statuses."));
        } else {

            return $rs->GetArray();

        }    

    }

    ######################################
    #  Get Workorder status display name #
    ######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."workorder_statuses WHERE status_key=".$this->app->db->qstr($status_key);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get the work order status display name."));
        } else {

            return $rs->fields['display_name'];

        }    

    }

    #########################################
    #  Get Workorder Scope Suggestions      # // Used by ajax thing
    #########################################

    public function getScopeSuggestions($scope_string, $minimum_characters = 4) {

        $pagePayload = '';

        // if the string is not long enough so dont bother with a DB lookup
        if(strlen($scope_string) < $minimum_characters) { return; }

        // These SQL statements were derived from https://stackoverflow.com/questions/19462919/mysql-select-distinct-should-be-case-sensitive

        /* This search removes case insensitive duplicates from the results i.e. 'chicken', 'CHICKEN' would return only 'chicken'
        $sql = "SELECT
                DISTINCT scope AS autoscope
                FROM ".PRFX."workorder_records
                WHERE scope
                LIKE '$scope_string%'
                LIMIT 10";*/

        /* This search removes case sensitive duplicates from the results i.e. 'chicken', 'CHICKEN' would return both 'chicken' and 'CHICKEN'
        $sql = "SELECT
                DISTINCT BINARY scope AS autoscope
                FROM ".PRFX."workorder_records
                WHERE scope
                LIKE '$scope_string%'
                LIMIT 10";*/

        // This search removes case sensitive duplicates from the results i.e. 'chicken', 'CHICKEN' would return both 'chicken' and 'CHICKEN'
        $sql = "SELECT
                DISTINCT (CAST(scope AS CHAR CHARACTER SET utf8) COLLATE utf8_bin) AS autoscope
                FROM ".PRFX."workorder_records
                WHERE scope
                LIKE ".$this->app->db->qstr('%'.$scope_string.'%')."
                LIMIT 10";    


        // Get Workorder Scope Suggestions from the database      
        if(!$rs = $this->app->db->Execute($sql)) {

            //$this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get a work order scopes list from the database."));
            echo $this->app->db->ErrorMsg();

        } else {

            $record_count = $rs->RecordCount();           

            if($record_count) {

                $autosuggest_items = $rs->GetArray(); 

                // loop over the rows, outputting them to the page object in the required format
                foreach($autosuggest_items as $key => $value) {
                    $pagePayload .= '<li onclick="fill(\''.$value['autoscope'].'\');">'.$value['autoscope'].'</li>';
                } 

                return $pagePayload;

            } else {

                // No records found - do nothing 
                return;

            }

        }

    }  

    /** Update Functions **/

    ###########################################
    # Update Work Order Scope and Description #
    ###########################################

    public function updateScopeDescription($workorder_id, $scope, $description) {

        $sql = "UPDATE ".PRFX."workorder_records SET           
                scope               =".$this->app->db->qstr($scope).",
                description         =".$this->app->db->qstr($description)."            
                WHERE workorder_id  =".$this->app->db->qstr($workorder_id);

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a work order scope and description."));
        } else {

            // Get Work Order Details
            $workorder_details = $this->getRecord($workorder_id);

            // Creates a History record        
            $this->insertHistory($workorder_id, _gettext("Scope and Description updated by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Scope and Description updated by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_id);        

            // Update last active record        
            $this->app->components->client->updateLastActive($workorder_details['client_id']);
            $this->updateLastActive($workorder_id);

            return true;

        }

    }

    ##################################
    #   Update Workorder Comment     #
    ##################################

    public function updateComment($workorder_id, $comment) {

        $sql = "UPDATE ".PRFX."workorder_records SET            
                comment             =". $this->app->db->qstr($comment)."
                WHERE workorder_id  =". $this->app->db->qstr($workorder_id);

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a work order Comment."));
        } else {

            // Get Work Order Details
            $workorder_details = $this->getRecord($workorder_id);

            // Create a Workorder History Note       
            $this->insertHistory($workorder_id, _gettext("Comment updated by").' '.$this->app->user->login_display_name);

            // Log activity        
            $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Comment updated by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_id);

            // Update last active record       
            $this->app->components->client->updateLastActive($workorder_details['client_id']); 
            $this->updateLastActive($workorder_id);

            return true;

        }

    }

    ################################
    # Update Work Order Resolution #
    ################################

    public function updateResolution($workorder_id, $resolution) {

        $sql = "UPDATE ".PRFX."workorder_records SET                        
                resolution          =". $this->app->db->qstr( $resolution      )."            
                WHERE workorder_id  =". $this->app->db->qstr( $workorder_id    );

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a work order resolution."));
        } else {

            // Get Work Order Details
            $workorder_details = $this->getRecord($workorder_id);

            // Create a Workorder History Note       
            $this->insertHistory($workorder_id, _gettext("Resolution updated by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Resolution updated by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_id);

            // Update last active record        
            $this->app->components->client->updateLastActive($workorder_details['client_id']);
            $this->updateLastActive($workorder_id);

            return true;

        }

    }
    
    ##############################
    #    update workorder note   #
    ##############################

    public function updateNote($workorder_note_id, $note) {

        $sql = "UPDATE ".PRFX."workorder_notes SET
                employee_id             =". $this->app->db->qstr( $this->app->user->login_user_id   ).",            
                description             =". $this->app->db->qstr( $note                                )."
                WHERE workorder_note_id =". $this->app->db->qstr( $workorder_note_id                   );

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a work order note."));

        } else {

            $workorder_details = $this->getRecord($this->getNote($workorder_note_id, 'workorder_id'));

            // Create a Workorder History Note       
            $this->insertHistory($workorder_details['workorder_id'], _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("updated by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("for Work Order").' '.$workorder_details['workorder_id'].' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id']);

            // Update last active record        
            $this->app->components->client->updateLastActive($workorder_details['client_id']);
            $this->updateLastActive($workorder_details['workorder_id']);

        }

    }

    ############################
    # Update Workorder Status  #
    ############################

    public function updateStatus($workorder_id, $new_status) {

        // Get current workorder details
        $workorder_details = $this->getRecord($workorder_id);

        // If the new status is the same as the current one, exit
        if($new_status == $workorder_details['status']) {        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new work order status is the same as the current work order status."));
            return false;
        }

        // Set the appropriate employee_id
        $employee_id = ($new_status == 'unassigned') ? '' : $workorder_details['employee_id'];

        // Set the appropriate closed_on date
        $closed_on = ($new_status == 'closed') ? $this->app->system->general->mysql_datetime() : '0000-00-00 00:00:00';

        $sql = "UPDATE ".PRFX."workorder_records SET   
                employee_id         =". $this->app->db->qstr( $employee_id     ).",
                status              =". $this->app->db->qstr( $new_status      ).",
                closed_on           =". $this->app->db->qstr( $closed_on       )."  
                WHERE workorder_id  =". $this->app->db->qstr( $workorder_id    );

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a work order Status."));

        } else {

            // If there is no employee and status is not 'unassigned', set the current logged in user as the assigned employee
            if($workorder_details['employee_id'] == '' && $new_status != 'unassigned') {
                $this->assignToEmployee($workorder_id, $this->app->user->login_user_id);
            }

            // Update Workorder 'is_closed' boolean
            if($new_status == 'closed_without_invoice' || $new_status == 'closed_with_invoice') {
                $this->updateClosedStatus($workorder_id, 'close');
            } else {
                $this->updateClosedStatus($workorder_id, 'open');
            }

            // Status updated message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Work order status updated."));        

            // For writing message to log file, get work order status display name
            $wo_status_display_name = _gettext($this->getStatusDisplayName($new_status));

            // Create a Workorder History Note       
            $this->insertHistory($workorder_id, _gettext("Status updated to").' '.$wo_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Status updated to").' '.$wo_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_id);

            // Update last active record        
            $this->app->components->client->updateLastActive($this->getRecord($workorder_id, 'client_id'));
            $this->updateLastActive($workorder_id);        

            return true;

        }

    }

    ###################################
    # Update Workorder Closed Status  #  // This should be moved to $this->app->components->workorder->update_workorder_status()
    ###################################

    public function updateClosedStatus($workorder_id, $new_closed_status) {

        if($new_closed_status == 'open') {

            $sql = "UPDATE ".PRFX."workorder_records SET
                    closed_by           ='',
                    closed_on           ='0000-00-00 00:00:00',
                    is_closed           =". $this->app->db->qstr( 0                                    )."
                    WHERE workorder_id  =". $this->app->db->qstr( $workorder_id                        );

        }

        if($new_closed_status == 'close') {        
            $sql = "UPDATE ".PRFX."workorder_records SET
                    closed_by           =". $this->app->db->qstr( $this->app->user->login_user_id   ).",
                    closed_on           =". $this->app->db->qstr( $this->app->system->general->mysql_datetime()                     ).",
                    is_closed           =". $this->app->db->qstr( 1                                    )."
                    WHERE workorder_id  =". $this->app->db->qstr( $workorder_id                        );

        }

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a work order's Closed status."));
        }

    }



    ####################################
    # Update a Workorder's Invoice ID  #
    ####################################

    public function updateInvoiceId($workorder_id = null, $invoice_id = null) {

        // This prevents invoices with no workorders causing issues
        if(!$workorder_id) { return; }

        $sql = "UPDATE ".PRFX."workorder_records SET
                invoice_id          =". $this->app->db->qstr( $invoice_id      )."
                WHERE workorder_id  =". $this->app->db->qstr( $workorder_id    );

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a work order Invoice ID."));
        }    

    }
    
    #################################
    #    Update Last Active         #
    #################################

    public function updateLastActive($workorder_id = null) {

        // compensate for some invoices not having workorders
        if(!$workorder_id) { return; }

        $sql = "UPDATE ".PRFX."workorder_records SET
                last_active=".$this->app->db->qstr( $this->app->system->general->mysql_datetime() )."
                WHERE workorder_id=".$this->app->db->qstr($workorder_id);

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a work order last active time."));
        }

    }    

    
    /** Delete Work Orders **/

    #####################
    # Delete Workorder  #
    #####################

    public function deleteRecord($workorder_id) {

        // Does the workorder have an invoice
        if($this->getRecord($workorder_id, 'invoice_id')) {        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it has an invoice."));
            return false;
        }

        // Is the workorder in an allowed state to be deleted
        if(!$this->checkStatusAllowsDelete($workorder_id)) {        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because its status does not allow it."));
            return false;
        }

        // get client_id before deleletion
        $client_id = $this->getRecord($workorder_id, 'client_id');

        // Change the workorder status to deleted (I do this here to maintain consistency)
        $this->app->components->workorder->updateStatus($workorder_id, 'deleted'); 

        // Delete the workorder primary record
        //$sql = "DELETE FROM ".PRFX."workorder_records WHERE workorder_id=".$this->app->db->qstr($workorder_id); (this use to delete the whole record)
        $sql = "UPDATE ".PRFX."workorder_records SET
            employee_id         = '',
            client_id           = '',   
            invoice_id          = '',
            created_by          = '',
            closed_by           = '',
            status              = 'deleted',
            opened_on           = '0000-00-00 00:00:00',
            closed_on           = '0000-00-00 00:00:00',
            last_active         = '0000-00-00 00:00:00',        
            is_closed           = '1',
            scope               = '',
            description         = '',
            comment             = '',
            resolution          = ''
            WHERE workorder_id =". $this->app->db->qstr($workorder_id);

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to delete the Work Order").' '.$workorder_id.'.');

        // Delete the workorder history
        } else {        

            $sql = "DELETE FROM ".PRFX."workorder_history WHERE workorder_id=".$this->app->db->qstr($workorder_id);

            if(!$rs = $this->app->db->Execute($sql)) {
                $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to delete the history notes for Work Order").' '.$workorder_id.'.');

            // Delete the workorder notes    
            } else {

                $sql = "DELETE FROM ".PRFX."workorder_notes WHERE workorder_id=".$this->app->db->qstr($workorder_id);

                if(!$rs = $this->app->db->Execute($sql)) {
                    $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to delete the notes for Work Order").' '.$workorder_id.'.');


                // Delete the workorder schedule events     
                } else {

                    $sql = "DELETE FROM ".PRFX."schedule_records WHERE workorder_id=".$this->app->db->qstr($workorder_id);

                    if(!$rs = $this->app->db->Execute($sql)) {
                        $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to delete the schedules for Work Order").' '.$workorder_id.'.');

                    // Log the workorder deletion
                    } else {

                        // Write the record to the activity log                    
                        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("has been deleted by").' '.$this->app->user->login_display_name.'.';
                        $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $client_id, $workorder_id);

                        // Update last active record
                        $this->app->components->client->updateLastActive($client_id);                    

                        return true;

                    }        

                }

            }

        }

    }

    ####################################
    #    delete a workorders's note    #
    ####################################

    public function deleteNote($workorder_note_id) {

        // Get workorder details before any deleting
        $workorder_details = $this->getRecord($this->getNote($workorder_note_id, 'workorder_id'));

        $sql = "DELETE FROM ".PRFX."workorder_notes WHERE workorder_note_id=".$this->app->db->qstr( $workorder_note_id );

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to delete a work order note."));

        } else {        

            // Create a Workorder History Note       
            $this->insertHistory($workorder_details['workorder_id'], _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("has been deleted by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("for Work Order").' '.$workorder_details['workorder_id'].' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id']);

            // Update last active record        
            $this->app->components->client->updateLastActive($workorder_details['client_id']);
            $this->updateLastActive($workorder_details['workorder_id']);

        }

    }    


    /** Close Functions **/

    ########################################
    # Close Workorder without invoice      #
    ########################################

    public function closeWithoutInvoice($workorder_id, $resolution) {

        // Insert resolution and close information
        $sql = "UPDATE ".PRFX."workorder_records SET
                closed_by           =". $this->app->db->qstr( $this->app->user->login_user_id   ).",                        
                status              =". $this->app->db->qstr( 'closed_without_invoice'             ).",
                closed_on           =". $this->app->db->qstr( $this->app->system->general->mysql_datetime()                     ).",
                is_closed           =". $this->app->db->qstr( 1                                    ).",
                resolution          =". $this->app->db->qstr( $resolution                          )."
                WHERE workorder_id  =". $this->app->db->qstr( $workorder_id                        );

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to close a work order without an invoice."));
        } else {

            // Update Work Order Status - not needed
            //$this->app->components->workorder->update_workorder_status($workorder_id, 'closed_without_invoice');

            // Get client_id
            $workorder_details = $this->getRecord($workorder_id);

            // If there is no employee assigned, set the current logged in user as the assigned employee
            if(!$workorder_details['employee_id']) {
                $this->assignToEmployee($workorder_id, $this->app->user->login_user_id);
            }

            // Create a History record
            $this->insertHistory($workorder_id, _gettext("Closed without invoice by").' '.$this->app->user->login_display_name.'.');

            // Log activity
            $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("has been closed without invoice by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $workorder_details['client_id'], $workorder_id);

            // Update last active record
            $this->app->components->client->updateLastActive($workorder_details['client_id']);
            $this->updateLastActive($workorder_id);        

            return true;

        }

    }

    #################################
    # Close Workorder with Invoice  #
    #################################

    public function closeWithInvoice($workorder_id, $resolution) {

        // Insert resolution and close information
        $sql = "UPDATE ".PRFX."workorder_records SET
                closed_by           =". $this->app->db->qstr( $this->app->user->login_user_id   ).",
                status              =". $this->app->db->qstr( 'closed_with_invoice'                ).",
                closed_on          =". $this->app->db->qstr( $this->app->system->general->mysql_datetime()                     ).",            
                is_closed           =". $this->app->db->qstr( 1                                    ).",
                resolution          =". $this->app->db->qstr( $resolution                          )."
                WHERE workorder_id  =". $this->app->db->qstr( $workorder_id                        );

        if(!$rs = $this->app->db->Execute($sql)){ 
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to close a work order with an invoice."));
        } else {

            // Update Work Order Status - not needed
            //$this->app->components->workorder->update_workorder_status($workorder_id, 'closed_with_invoice');

            // Get workorder details
            $workorder_details = $this->getRecord($workorder_id);

            // If there is no employee assigned, set the current logged in user as the assigned employee
            if(!$workorder_details['employee_id']) {
                $this->assignToEmployee($workorder_id, $this->app->user->login_user_id);
            }

            // Create a Workorder History Note       
            $this->insertHistory($workorder_id, _gettext("Closed with invoice by").' '.$this->app->user->login_display_name.'.');

            // Log activity
            $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("has been closed with invoice by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $workorder_details['client_id'], $workorder_id);

            // Update last active record
            $this->app->components->client->updateLastActive($workorder_details['client_id']);
            $this->updateLastActive($workorder_id);   

            return true;

        }      

    }


    /** Check Functions **/


    ############################################################
    #  Check if the workorder status is allowed to be changed  #
    ############################################################

     public function checkStatusAllowsChange($workorder_id) {

        $state_flag = true;

       // Get the otherincome details
        $workorder_details = $this->getRecord($workorder_id);

        /* Is Unassigned
        if($workorder_details['status'] == 'unassigned') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it is unassigned."));
            $state_flag = false;        
        }*/

        /* Is Assigned
        if($workorder_details['status'] == 'assigned') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it is assigned"));
            $state_flag = false;        
        }*/

        /* Is Waiting for Parts
        if($workorder_details['status'] == 'waiting_for_parts') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it is waiting for parts."));
            $state_flag = false;        
        }*/

        /* Is Scheduled
        if($workorder_details['status'] == 'scheduled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it is scheduled."));
            $state_flag = false;        
        }*/

        /* With Client
        if($workorder_details['status'] == 'with_client') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it is with the client."));
            $state_flag = false;        
        }*/

        /* Is On Hold
        if($workorder_details['status'] == 'on_hold') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it is on hold."));
            $state_flag = false;        
        }*/

        /* Is with Management
        if($workorder_details['status'] == 'management') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it is with mangement."));
            $state_flag = false;        
        }*/

        /* Closed without Invoice
        if($workorder_details['status'] == 'closed_without_invoice') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it has been closed without an invoice."));
            $state_flag = false;        
        }*/

        // Closed with Invoice
        if($workorder_details['status'] == 'closed_with_invoice') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it has been closed with an invoice."));
            $state_flag = false;        
        }

        // Is deleted
        if($workorder_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder status cannot be changed because it has already been deleted."));
            $state_flag = false;        
        }

        return $state_flag;

     }

    ######################################################
    # Is the workorder in an allowed state to be deleted #
    ######################################################

    public function checkStatusAllowsDelete($workorder_id) {

        $state_flag = true;

        // Get the otherincome details
        $workorder_details = $this->getRecord($workorder_id);

        /* Is Unassigned
        if($workorder_details['status'] == 'unassigned') {
            $this->app->system->variables->systemMessagesWrite('danger',  _gettext("This workorder cannot be deleted because it is unassigned."));
            $state_flag = false;        
        }*/

        // Is Assigned
        if($workorder_details['status'] == 'assigned') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it is assigned"));
            $state_flag = false;        
        }

        // Is Waiting for Parts
        if($workorder_details['status'] == 'waiting_for_parts') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it is waiting for parts."));
            $state_flag = false;        
        }

        // Is Scheduled
        if($workorder_details['status'] == 'scheduled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it is scheduled."));
            $state_flag = false;        
        }

        // With Client
        if($workorder_details['status'] == 'with_client') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it is with the client."));
            $state_flag = false;        
        }

        // Is On Hold
        if($workorder_details['status'] == 'on_hold') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it is on hold."));
            $state_flag = false;        
        }

        /* Is with Management
        if($workorder_details['status'] == 'management') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it is with mangement."));
            $state_flag = false;        
        }*/

        // Closed without Invoice
        if($workorder_details['status'] == 'closed_without_invoice') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it has been closed without an invoice."));
            $state_flag = false;        
        }

        // Closed with Invoice
        if($workorder_details['status'] == 'closed_with_invoice') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder cannot be deleted because it has been closed with an invoice."));
            $state_flag = false;        
        }

        // Is deleted
        if($workorder_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The workorder cannot be deleted because it has already been deleted."));
            $state_flag = false;        
        }

        return $state_flag;  

    }
    
    ################################
    # Resolution Edit Status Check #  // not currently used
    ################################

    public function checkStatusAllowsResolutionUpdate($workorder_id) {    

        $wo_is_closed   = $this->getRecord($workorder_id, 'is_closed');
        $wo_status      = $this->getRecord($workorder_id, 'status');

        // Workorder is Closed
        if($wo_is_closed == '1') {

            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Cannot edit the resolution because the work order is already closed."));
            return false;
        }

        // Waiting For Parts
        if ($wo_status == 'waiting_for_parts') {           

            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Can not close a work order if it is Waiting for Parts. Please Adjust the status."));
            return false;

        }

        return true;   

    }
    
    ##############################################################
    #  Check if the workorder employee is allowed to be changed  #
    ##############################################################

     public function checkStatusAllowsEmployeeUpdate($workorder_id) {

        $state_flag = true;

        // Get the otherincome details
        $workorder_details = $this->getRecord($workorder_id);

        /* Is Unassigned
        if($workorder_details['status'] == 'unassigned') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it is unassigned."));
            $state_flag = false;        
        }*/

        /* Is Assigned
        if($workorder_details['status'] == 'assigned') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it is assigned"));
            $state_flag = false;        
        }*/

        /* Is Waiting for Parts
        if($workorder_details['status'] == 'waiting_for_parts') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it is waiting for parts."));
            $state_flag = false;        
        }*/

        /* Is Scheduled
        if($workorder_details['status'] == 'scheduled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it is scheduled."));
            $state_flag = false;        
        }*/

        /* With Client
        if($workorder_details['status'] == 'with_client') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it is with the client."));
            $state_flag = false;        
        }*/

        /* Is On Hold
        if($workorder_details['status'] == 'on_hold') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it is on hold."));
            $state_flag = false;        
        }*/

        /* Is with Management
        if($workorder_details['status'] == 'management') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it is with mangement."));
            $state_flag = false;        
        }*/

        // Closed without Invoice
        if($workorder_details['status'] == 'closed_without_invoice') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it has been closed without an invoice."));
            $state_flag = false;        
        }

        // Closed with Invoice
        if($workorder_details['status'] == 'closed_with_invoice') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it has been closed with an invoice."));
            $state_flag = false;        
        }

        // Is deleted
        if($workorder_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it has already been deleted."));
            $state_flag = false;        
        }

        /* Is Closed (old Fallback method)
        if(!$this->get_workorder_details($workorder_details['workorder_id'], 'is_closed')) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This workorder employee cannot be changed because it has been closes."));
            $state_flag = false;  
        }*/

        return $state_flag;  

     }
         

    /** Other Functions **/
     
    #########################################
    # Assign Workorder to another employee  #
    #########################################

    public function assignToEmployee($workorder_id, $target_employee_id) {

        // Get the workorder details
        $workorder_details = $this->getRecord($workorder_id);

        // If the new employee is the same as the current one, exit
        if($target_employee_id == $workorder_details['employee_id']) {        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new employee is the same as the current employee."));
            return false;
        }    

        // Only change workorder status if unassigned
        if($workorder_details['status'] == 'unassigned') {

            $sql = "UPDATE ".PRFX."workorder_records SET
                    employee_id         =". $this->app->db->qstr( $target_employee_id  ).",
                    status              =". $this->app->db->qstr( 'assigned'           )."
                    WHERE workorder_id  =". $this->app->db->qstr( $workorder_id        );

        // Keep the same workorder status    
        } else {    

            $sql = "UPDATE ".PRFX."workorder_records SET
                    employee_id         =". $this->app->db->qstr( $target_employee_id  )."            
                    WHERE workorder_id  =". $this->app->db->qstr( $workorder_id        );

        }

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to assign a work order to an employee."));

        } else {

            // Assigned employee success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Assigned employee updated.")); 

            // Get Logged in Employee's Display Name        
            $logged_in_employee_display_name = $this->app->user->login_display_name;

            // Get the currently assigned employee ID
            $assigned_employee_id = $workorder_details['employee_id'];

            // Get the Display Name of the currently Assigned Employee
            if($assigned_employee_id == ''){
                $assigned_employee_display_name = _gettext("Unassigned");            
            } else {            
                $assigned_employee_display_name = $this->app->components->user->getRecord($assigned_employee_id, 'display_name');
            }

            // Get the Display Name of the Target Employee        
            $target_employee_display_name = $this->app->components->user->getRecord($target_employee_id, 'display_name');

            // Creates a History record
            $this->insertHistory($workorder_id, _gettext("Work Order").' '.$workorder_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.');

            // Log activity
            $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $target_employee_id, $workorder_details['client_id'], $workorder_id);

            // Update last active record
            $this->app->components->user->updateLastActive($workorder_details['employee_id']);
            $this->app->components->user->updateLastActive($target_employee_id);
            $this->app->components->client->updateLastActive($workorder_details['client_id']);
            $this->updateLastActive($workorder_id);

            return true;

        }

     }

}