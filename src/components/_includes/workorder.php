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
#  Display all Work orders for the given status     #
#####################################################

function display_workorders($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $status = null, $employee_id = null, $client_id = null) {
       
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'workorder_id';
    $havingTheseRecords = '';
    
    /* Records Search */
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."workorder_records.workorder_id\n";    
    
    // Restrict results by search category (client) and search term
    if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_namee LIKE ".$db->qstr('%'.$search_term.'%');}
    
   // Restrict results by search category (employee) and search term
    elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."workorder_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');}
    
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
            
            $whereTheseRecords .= " AND ".PRFX."workorder_records.status= ".$db->qstr($status);
            
        }
        
    }        

    // Restrict by Employee
    if($employee_id) {$whereTheseRecords .= " AND ".PRFX."user_records.user_id=".$db->qstr($employee_id);}

    // Restrict by Client
    if($client_id) {$whereTheseRecords .= " AND ".PRFX."client_records.client_id=".$db->qstr($client_id);}
    
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
            ".PRFX."workorder_records.open_date AS workorder_open_date,
            ".PRFX."workorder_records.close_date AS workorder_close_date,
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
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching work orders."));
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching work orders."));
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

#############################
# Display Work Order Notes  #
#############################

function display_workorder_notes($workorder_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT
            ".PRFX."workorder_notes.*,
            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name
                
            FROM
            ".PRFX."workorder_notes,
            ".PRFX."user_records
            WHERE workorder_id=".$db->qstr($workorder_id)."
            AND ".PRFX."user_records.user_id = ".PRFX."workorder_notes.employee_id";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return notes for the work order."));
    } else {
        
        return $rs->GetArray(); 
        
    }
    
}

##############################
# Display Work Order History #
##############################

function display_workorder_history($workorder_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT 
            ".PRFX."workorder_history.*,
            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name
                
            FROM 
            ".PRFX."workorder_history
            LEFT JOIN ".PRFX."user_records ON ".PRFX."workorder_history.employee_id = ".PRFX."user_records.user_id
            WHERE ".PRFX."workorder_history.workorder_id=".$db->qstr($workorder_id)." 
            AND ".PRFX."user_records.user_id = ".PRFX."workorder_history.employee_id
            ORDER BY ".PRFX."workorder_history.history_id";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return history records for the work order."));
    } else {
        
        return $rs->GetArray();  
        
    }
    
}

/** Insert Functions **/

#########################
# Insert New Work Order #
#########################

function insert_workorder($client_id, $scope, $description, $comment) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."workorder_records SET            
            client_id       =". $db->qstr( $client_id                           ).",
            open_date       =". $db->qstr( mysql_datetime()                     ).",
            status          =". $db->qstr( 'unassigned'                         ).",
            is_closed       =". $db->qstr( 0                                    ).", 
            created_by      =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            scope           =". $db->qstr( $scope                               ).",
            description     =". $db->qstr( $description                         ).",            
            comment         =". $db->qstr( $comment                             );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the work order Record into the database."));
        
    } else {

        // Get the new Workorders ID
        $workorder_id = $db->Insert_ID();

        // Create a Workorder History Note            
        insert_workorder_history_note($workorder_id, _gettext("Created by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Created by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $client_id, $workorder_id);
        
        // Update last active record        
        update_client_last_active(get_workorder_details($workorder_id, 'client_id'));

        return $workorder_id;
        
    }
    
}

######################################
# Insert New Work Order History Note #  // this might be go in the main include as different modules add work order history notes
######################################

function insert_workorder_history_note($workorder_id = null, $note = '') {
    
    $db = QFactory::getDbo();
    
    // If Work Order History Notes are not enabled, exit
    if(QFactory::getConfig()->get('workorder_history_notes') != true) { return; }    
    
    // This prevents errors from such functions as email.php where a workorder_id is not always present - not currently used
    if($workorder_id == null) { return; }
    
    $sql = "INSERT INTO ".PRFX."workorder_history SET            
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            workorder_id    =". $db->qstr( $workorder_id                        ).",
            date            =". $db->qstr( mysql_datetime()                     ).",
            note            =". $db->qstr( $note                                );
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert a work order history note."));
    } else {
        
        return true;
        
    }  
    
}

##############################
#    insert workorder note   #
##############################

function insert_workorder_note($workorder_id, $note) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."workorder_notes SET                        
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            workorder_id    =". $db->qstr( $workorder_id                        ).", 
            date            =". $db->qstr( mysql_datetime()                     ).",
            description     =". $db->qstr( $note                                );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert a work order note."));
        
    } else {
        
        // Get the new Note ID
        $workorder_note_id = $db->Insert_ID();
        
        // Get client id
        $client_id = get_workorder_details($workorder_id, 'client_id');
        
        // Create a Workorder History Note       
        insert_workorder_history_note($workorder_id, _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("added by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("added to Work Order").' '.$workorder_id.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $client_id, $workorder_id);
        
        // Update last active record
        update_client_last_active($client_id);
        update_workorder_last_active($workorder_id);
        
        return true;
        
    }
    
}

/** Get Functions **/

########################################
#   Get a Workorder's details          #
########################################

function get_workorder_details($workorder_id = null, $item = null) {  
    
    $db = QFactory::getDbo();
    
    // This covers invoice only
    if(!$workorder_id) {
        return;        
    }

    $sql = "SELECT * FROM ".PRFX."workorder_records WHERE workorder_id=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get work order details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#  Get a single workorder note      #
#####################################

function get_workorder_note_details($workorder_note_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."workorder_notes WHERE workorder_note_id=".$db->qstr($workorder_note_id);    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get a work order Note."));
    } else { 
        
        if($item === null){
            
            return $rs->GetRowAssoc(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#  Get ALL of a workorder's notes   # // not currently used
#####################################

function get_workorder_notes($workorder_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."workorder_notes WHERE workorder_id=".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get all notes for a work order."));
    } else {
        
        $records = $rs->GetArray();

        if(empty($records)){
            
            return false;
            
        } else {
            
             return $rs->GetArray(); 
            
        }
        
    }
    
}

#####################################
#    Get Workorder Statuses         #
#####################################

function get_workorder_statuses($restricted_statuses = false) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."workorder_statuses";
    
    // Restrict statuses to those that are allowed to be changed by the user
    if($restricted_statuses) {
        $sql .= "\nWHERE status_key NOT IN ('closed_with_invoice', 'deleted')";
    }

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get work order statuses."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

######################################
#  Get Workorder status display name #
######################################

function get_workorder_status_display_name($status_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."workorder_statuses WHERE status_key=".$db->qstr($status_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the work order status display name."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

#########################################
#  Get Workorder Scope Suggestions      #
#########################################
 
function get_workorder_scope_suggestions($scope_string, $return_count = 4) {
    
    $db = QFactory::getDbo();
    $BuildPage = '';
    
    // if the string is not long enough so dont bother with a DB lookup
    if(strlen($scope_string) < $return_count) { return; }
    
    // These SQL statements werre derived from https://stackoverflow.com/questions/19462919/mysql-select-distinct-should-be-case-sensitive
    
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
            LIKE ".$db->qstr($scope_string.'%')."
            LIMIT 10";    

    
    // Get Workorder Scope Suggestions from the database      
    if(!$rs = $db->Execute($sql)) {
        
        //force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get a work order scopes list from the database."));
        echo $db->ErrorMsg();
        
    } else {
        
        $record_count = $rs->RecordCount();           
        
        if($record_count) {

            $autosuggest_items = $rs->GetArray(); 

            // loop over the rows, outputting them to the page object in the required format
            foreach($autosuggest_items as $key => $value) {
                $BuildPage .= '<li onclick="fill(\''.$value['autoscope'].'\');">'.$value['autoscope'].'</li>';
            } 
            
            return $BuildPage;

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

function update_workorder_scope_and_description($workorder_id, $scope, $description) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."workorder_records SET           
            scope               =".$db->qstr($scope).",
            description         =".$db->qstr($description)."            
            WHERE workorder_id  =".$db->qstr($workorder_id);

    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a work order scope and description."));
    } else {
        
        // Get Work Order Details
        $workorder_details = get_workorder_details($workorder_id);
        
        // Creates a History record        
        insert_workorder_history_note($workorder_id, _gettext("Scope and Description updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Scope and Description updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_id);        
        
        // Update last active record        
        update_client_last_active($workorder_details['client_id']);
        update_workorder_last_active($workorder_id);
        
        return true;
        
    }
    
}

##################################
#   Update Workorder Comment     #
##################################

function update_workorder_comment($workorder_id, $comment) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."workorder_records SET            
            comment             =". $db->qstr($comment)."
            WHERE workorder_id  =". $db->qstr($workorder_id);

    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a work order Comment."));
    } else {
        
        // Get Work Order Details
        $workorder_details = get_workorder_details($workorder_id);
        
        // Create a Workorder History Note       
        insert_workorder_history_note($workorder_id, _gettext("Comment updated by").' '.QFactory::getUser()->login_display_name);
        
        // Log activity        
        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Comment updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_id);
        
        // Update last active record       
        update_client_last_active($workorder_details['client_id']); 
        update_workorder_last_active($workorder_id);
        
        return true;
        
    }
    
}

################################
# Update Work Order Resolution #
################################

function update_workorder_resolution($workorder_id, $resolution) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."workorder_records SET                        
            resolution          =". $db->qstr( $resolution      )."            
            WHERE workorder_id  =". $db->qstr( $workorder_id    );

    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a work order resolution."));
    } else {
        
        // Get Work Order Details
        $workorder_details = get_workorder_details($workorder_id);
        
        // Create a Workorder History Note       
        insert_workorder_history_note($workorder_id, _gettext("Resolution updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Resolution updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_id);
        
        // Update last active record        
        update_client_last_active($workorder_details['client_id']);
        update_workorder_last_active($workorder_id);
        
        return true;
            
    }
    
}

############################
# Update Workorder Status  #
############################

function update_workorder_status($workorder_id, $new_status) {
    
    $db = QFactory::getDbo();
    
    // Get current workorder details
    $workorder_details = get_workorder_details($workorder_id);
    
    // If the new status is the same as the current one, exit
    if($new_status == $workorder_details['status']) {        
        postEmulationWrite('warning_msg', _gettext("Nothing done. The new work order status is the same as the current work order status."));
        return false;
    }
    
    $sql = "UPDATE ".PRFX."workorder_records SET \n";
    
    // when unassigned there should be no employee the '\n' makes sql look neater
    if ($new_status == 'unassigned') { $sql .= "employee_id = '',\n"; }
    
    $sql .="status              =". $db->qstr( $new_status      )."            
            WHERE workorder_id  =". $db->qstr( $workorder_id    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a work order Status."));
        
    } else {
        
        // If there is no employee and status is not 'unassigned', set the current logged in user as the assigned employee
        if($workorder_details['employee_id'] == '' && $new_status != 'unassigned') {
            assign_workorder_to_employee($workorder_id, QFactory::getUser()->login_user_id);
        }
        
        // Update Workorder 'is_closed' boolean
        if($new_status == 'closed_without_invoice' || $new_status == 'closed_with_invoice') {
            update_workorder_closed_status($workorder_id, 'close');
        } else {
            update_workorder_closed_status($workorder_id, 'open');
        }
                
        // Status updated message
        postEmulationWrite('information_msg', _gettext("Work order status updated."));        
        
        // For writing message to log file, get work order status display name
        $wo_status_display_name = _gettext(get_workorder_status_display_name($new_status));
        
        // Create a Workorder History Note       
        insert_workorder_history_note($workorder_id, _gettext("Status updated to").' '.$wo_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("Status updated to").' '.$wo_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_id);
        
        // Update last active record        
        update_client_last_active(get_workorder_details($workorder_id, 'client_id'));
        update_workorder_last_active($workorder_id);        
        
        return true;
        
    }
    
}

###################################
# Update Workorder Closed Status  #
###################################

function update_workorder_closed_status($workorder_id, $new_closed_status) {
    
    $db = QFactory::getDbo();
    
    if($new_closed_status == 'open') {
        
        $sql = "UPDATE ".PRFX."workorder_records SET
                closed_by           ='',
                close_date          ='',
                is_closed           =". $db->qstr( 0                                    )."
                WHERE workorder_id  =". $db->qstr( $workorder_id                        );
        
    }
    
    if($new_closed_status == 'close') {        
        $sql = "UPDATE ".PRFX."workorder_records SET
                closed_by           =". $db->qstr( QFactory::getUser()->login_user_id   ).",
                close_date          =". $db->qstr( mysql_datetime()                     ).",
                is_closed           =". $db->qstr( 1                                    )."
                WHERE workorder_id  =". $db->qstr( $workorder_id                        );
        
    }
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a work order's Closed status."));
    }
    
}

#################################
#    Update Last Active         #
#################################

function update_workorder_last_active($workorder_id = null) {
    
    $db = QFactory::getDbo();
    
    // compensate for some invoices not having workorders
    if(!$workorder_id) { return; }
    
    $sql = "UPDATE ".PRFX."workorder_records SET
            last_active=".$db->qstr( mysql_datetime() )."
            WHERE workorder_id=".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a work order last active time."));
    }
    
}

####################################
# Update a Workorder's Invoice ID  #
####################################

function update_workorder_invoice_id($workorder_id = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    
    // This prevents invoices with no workorders causing issues
    if(!$workorder_id) { return; }
    
    $sql = "UPDATE ".PRFX."workorder_records SET
            invoice_id          =". $db->qstr( $invoice_id      )."
            WHERE workorder_id  =". $db->qstr( $workorder_id    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a work order Invoice ID."));
    }    
    
}

##############################
#    update workorder note   #
##############################

function update_workorder_note($workorder_note_id, $note) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."workorder_notes SET
            employee_id             =". $db->qstr( QFactory::getUser()->login_user_id   ).",            
            description             =". $db->qstr( $note                                )."
            WHERE workorder_note_id =". $db->qstr( $workorder_note_id                   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a work order note."));
        
    } else {
        
        $workorder_details = get_workorder_details(get_workorder_note_details($workorder_note_id, 'workorder_id'));
        
        // Create a Workorder History Note       
        insert_workorder_history_note($workorder_details['workorder_id'], _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("for Work Order").' '.$workorder_details['workorder_id'].' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id']);
        
        // Update last active record        
        update_client_last_active($workorder_details['client_id']);
        update_workorder_last_active($workorder_details['workorder_id']);
        
    }
    
}

/** Close Functions **/

########################################
# Close Workorder without invoice      #
########################################

function close_workorder_without_invoice($workorder_id, $resolution) {
    
    $db = QFactory::getDbo();
    
    // Insert resolution and close information
    $sql = "UPDATE ".PRFX."workorder_records SET
            closed_by           =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            close_date          =". $db->qstr( mysql_datetime()                     ).",            
            status              =". $db->qstr( 'closed_without_invoice'             ).",
            is_closed           =". $db->qstr( 1                                    ).",
            resolution          =". $db->qstr( $resolution                          )."
            WHERE workorder_id  =". $db->qstr( $workorder_id                        );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to close a work order without an invoice."));
    } else {
        
        // Update Work Order Status - not needed
        //update_workorder_status($workorder_id, 'closed_without_invoice');
        
        // Get client_id
        $client_id = get_workorder_details($workorder_id, 'client_id');
        
        // Create a History record
        insert_workorder_history_note($workorder_id, _gettext("Closed without invoice by").' '.QFactory::getUser()->login_display_name.'.');
            
        // Log activity
        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("has been closed without invoice by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $client_id, $workorder_id);
        
        // Update last active record
        update_client_last_active($client_id);
        update_workorder_last_active($workorder_id);        
        
        return true;
        
    }
    
}

#################################
# Close Workorder with Invoice  #
#################################

function close_workorder_with_invoice($workorder_id, $resolution) {
    
    $db = QFactory::getDbo();
    
    // Insert resolution and close information
    $sql = "UPDATE ".PRFX."workorder_records SET
            closed_by           =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            close_date          =". $db->qstr( mysql_datetime()                     ).",            
            status              =". $db->qstr( 'closed_with_invoice'                ).",
            is_closed           =". $db->qstr( 1                                    ).",
            resolution          =". $db->qstr( $resolution                          )."
            WHERE workorder_id  =". $db->qstr( $workorder_id                        );
    
    if(!$rs = $db->Execute($sql)){ 
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to close a work order with an invoice."));
    } else {
        
        // Update Work Order Status - not needed
        //update_workorder_status($workorder_id, 'closed_with_invoice');
        
        // Get client_id
        $client_id = get_workorder_details($workorder_id, 'client_id');
    
        // Create a Workorder History Note       
        insert_workorder_history_note($workorder_id, _gettext("Closed with invoice by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity
        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("has been closed with invoice by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $client_id, $workorder_id);
        
        // Update last active record
        update_client_last_active($client_id);
        update_workorder_last_active($workorder_id);   
        
        return true;
        
    }      
    
}

/** Delete Work Orders **/

#####################
# Delete Workorder  #
#####################

function delete_workorder($workorder_id) {
    
    $db = QFactory::getDbo();
    
    // Does the workorder have an invoice
    if(get_workorder_details($workorder_id, 'invoice_id')) {        
        postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it has an invoice."));
        return false;
    }
    
    // Is the workorder in an allowed state to be deleted
    if(!check_workorder_status_allows_for_deletion($workorder_id)) {        
        postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because its status does not allow it."));
        return false;
    }
    
    // get client_id before deleletion
    $client_id = get_workorder_details($workorder_id, 'client_id');
    
    // Change the workorder status to deleted (I do this here to maintain consistency)
    update_workorder_status($workorder_id, 'deleted'); 
    
    // Delete the workorder primary record
    //$sql = "DELETE FROM ".PRFX."workorder_records WHERE workorder_id=".$db->qstr($workorder_id); (this use to delete the whole record)
    $sql = "UPDATE ".PRFX."workorder_records SET
        employee_id         = '',
        client_id           = '',   
        invoice_id          = '',
        created_by          = '',
        closed_by           = '',
        open_date           = '0000-00-00 00:00:00',
        close_date          = '0000-00-00 00:00:00',
        last_active         = '0000-00-00 00:00:00',
        status              = 'deleted',
        is_closed           = '1',
        scope               = '',
        description         = '',
        comment             = '',
        resolution          = ''
        WHERE workorder_id =". $db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the Work Order").' '.$workorder_id.'.');
    
    // Delete the workorder history
    } else {        
       
        $sql = "DELETE FROM ".PRFX."workorder_history WHERE workorder_id=".$db->qstr($workorder_id);

        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the history notes for Work Order").' '.$workorder_id.'.');
            
        // Delete the workorder notes    
        } else {
            
            $sql = "DELETE FROM ".PRFX."workorder_notes WHERE workorder_id=".$db->qstr($workorder_id);

            if(!$rs = $db->Execute($sql)) {
                force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the notes for Work Order").' '.$workorder_id.'.');
             
                
            // Delete the workorder schedule events     
            } else {

                $sql = "DELETE FROM ".PRFX."schedule_records WHERE workorder_id=".$db->qstr($workorder_id);

                if(!$rs = $db->Execute($sql)) {
                    force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the schedules for Work Order").' '.$workorder_id.'.');

                // Log the workorder deletion
                } else {

                    // Write the record to the activity log                    
                    $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("has been deleted by").' '.QFactory::getUser()->login_display_name.'.';
                    write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $client_id, $workorder_id);
                    
                    // Update last active record
                    update_client_last_active($client_id);                    

                    return true;

                }        
        
            }
    
        }
    
    }
    
}

####################################
#    delete a workorders's note    #
####################################

function delete_workorder_note($workorder_note_id) {
    
    $db = QFactory::getDbo();
    
    // Get workorder details before any deleting
    $workorder_details = get_workorder_details(get_workorder_note_details($workorder_note_id, 'workorder_id'));
    
    $sql = "DELETE FROM ".PRFX."workorder_notes WHERE workorder_note_id=".$db->qstr( $workorder_note_id );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete a work order note."));
        
    } else {        
        
        // Create a Workorder History Note       
        insert_workorder_history_note($workorder_details['workorder_id'], _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("has been deleted by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("for Work Order").' '.$workorder_details['workorder_id'].' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id']);
        
        // Update last active record        
        update_client_last_active($workorder_details['client_id']);
        update_workorder_last_active($workorder_details['workorder_id']);
        
    }
    
}

/** Other Functions **/

################################
# Resolution Edit Status Check #
################################

function resolution_edit_status_check($workorder_id) {    
    
    $wo_is_closed   = get_workorder_details($workorder_id, 'is_closed');
    $wo_status      = get_workorder_details($workorder_id, 'status');

    // Workorder is Closed
    if($wo_is_closed == '1') {

        postEmulationWrite('warning_msg', _gettext("Cannot edit the resolution because the work order is already closed."));
        return false;
    }
    
    // Waiting For Parts
    if ($wo_status == 'waiting_for_parts') {           

        postEmulationWrite('warning_msg', _gettext("Can not close a work order if it is Waiting for Parts. Please Adjust the status."));
        return false;

    }

    return true;   
   
}

#########################################
# Assign Workorder to another employee  #
#########################################

function assign_workorder_to_employee($workorder_id, $target_employee_id) {
    
    $db = QFactory::getDbo();
    
    // Get the workorder details
    $workorder_details = get_workorder_details($workorder_id);
    
    // If the new employee is the same as the current one, exit
    if($target_employee_id == $workorder_details['employee_id']) {        
        postEmulationWrite('warning_msg', _gettext("Nothing done. The new employee is the same as the current employee."));
        return false;
    }    
    
    // Only change workorder status if unassigned
    if($workorder_details['status'] == 'unassigned') {
        
        $sql = "UPDATE ".PRFX."workorder_records SET
                employee_id         =". $db->qstr( $target_employee_id  ).",
                status              =". $db->qstr( 'assigned'           )."
                WHERE workorder_id  =". $db->qstr( $workorder_id        );

    // Keep the same workorder status    
    } else {    
        
        $sql = "UPDATE ".PRFX."workorder_records SET
                employee_id         =". $db->qstr( $target_employee_id  )."            
                WHERE workorder_id  =". $db->qstr( $workorder_id        );

    }
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to assign a work order to an employee."));
        
    } else {
        
        // Assigned employee success message
        postEmulationWrite('information_msg', _gettext("Assigned employee updated.")); 
        
        // Get Logged in Employee's Display Name        
        $logged_in_employee_display_name = QFactory::getUser()->login_display_name;
        
        // Get the currently assigned employee ID
        $assigned_employee_id = $workorder_details['employee_id'];
        
        // Get the Display Name of the currently Assigned Employee
        if($assigned_employee_id == ''){
            $assigned_employee_display_name = _gettext("Unassigned");            
        } else {            
            $assigned_employee_display_name = get_user_details($assigned_employee_id, 'display_name');
        }
        
        // Get the Display Name of the Target Employee        
        $target_employee_display_name = get_user_details($target_employee_id, 'display_name');
        
        // Creates a History record
        insert_workorder_history_note($workorder_id, _gettext("Work Order").' '.$workorder_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.');

        // Log activity
        $record = _gettext("Work Order").' '.$workorder_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.';
        write_record_to_activity_log($record, $target_employee_id, $workorder_details['client_id'], $workorder_id);
        
        // Update last active record
        update_user_last_active($workorder_details['employee_id']);
        update_user_last_active($target_employee_id);
        update_client_last_active($workorder_details['client_id']);
        update_workorder_last_active($workorder_id);
        
        return true;
        
    }
    
 }


######################################################
# Is the workorder in an allowed state to be deleted #
######################################################

function check_workorder_status_allows_for_deletion($workorder_id) {
     
    // Get the otherincome details
    $workorder_details = get_workorder_details($workorder_id);
    
    /* Is Unassigned
    if($workorder_details['status'] == 'unassigned') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it is unassigned."));
        return false;        
    }*/
    
    // Is Assigned
    if($workorder_details['status'] == 'assigned') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it is assigned"));
        return false;        
    }
    
    // Is Waiting for Parts
    if($workorder_details['status'] == 'waiting_for_parts') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it is waiting for parts."));
        return false;        
    }
    
    // Is Scheduled
    if($workorder_details['status'] == 'scheduled') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it is scheduled."));
        return false;        
    }
    
    // With Client
    if($workorder_details['status'] == 'with_client') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it is with the client."));
        return false;        
    }
    
    // Is On Hold
    if($workorder_details['status'] == 'on_hold') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it is on hold."));
        return false;        
    }
    
    /* Is with Management
    if($workorder_details['status'] == 'management') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it is with mangement."));
        return false;        
    }*/
    
    // Closed without Invoice
    if($workorder_details['status'] == 'closed_without_invoice') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it has been closed without an invoice."));
        return false;        
    }
    
    // Closed with Invoice
    if($workorder_details['status'] == 'closed_with_invoice') {
        //postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it has been closed with an invoice."));
        return false;        
    }
    
    // Is deleted
    if($workorder_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The workorder cannot be deleted because it has already been deleted."));
        return false;        
    }
    
    // All checks passed
    return true;    
     
}

############################################################
#  Check if the workorder status is allowed to be changed  #
############################################################

 function check_workorder_status_can_be_changed($workorder_id) {
     
   // Get the otherincome details
    $workorder_details = get_workorder_details($workorder_id);
    
    /* Is Unassigned
    if($workorder_details['status'] == 'unassigned') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it is unassigned."));
        return false;        
    }*/
    
    /* Is Assigned
    if($workorder_details['status'] == 'assigned') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it is assigned"));
        return false;        
    }*/
    
    /* Is Waiting for Parts
    if($workorder_details['status'] == 'waiting_for_parts') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it is waiting for parts."));
        return false;        
    }*/
    
    /* Is Scheduled
    if($workorder_details['status'] == 'scheduled') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it is scheduled."));
        return false;        
    }*/
    
    /* With Client
    if($workorder_details['status'] == 'with_client') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it is with the client."));
        return false;        
    }*/
    
    /* Is On Hold
    if($workorder_details['status'] == 'on_hold') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it is on hold."));
        return false;        
    }*/
    
    /* Is with Management
    if($workorder_details['status'] == 'management') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it is with mangement."));
        return false;        
    }*/
    
    /* Closed without Invoice
    if($workorder_details['status'] == 'closed_without_invoice') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it has been closed without an invoice."));
        return false;        
    }*/
    
    // Closed with Invoice
    if($workorder_details['status'] == 'closed_with_invoice') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it has been closed with an invoice."));
        return false;        
    }
    
    // Is deleted
    if($workorder_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("This workorder status cannot be changed because it has already been deleted."));
        return false;        
    }
    
    // All checks passed
    return true;    
     
 }
 
 #############################################################
#  Check if the workorder employee is allowed to be changed  #
##############################################################

 function check_workorder_allowed_to_change_employee($workorder_id) {
     
    // Get the otherincome details
    $workorder_details = get_workorder_details($workorder_id);
    
    /* Is Unassigned
    if($workorder_details['status'] == 'unassigned') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it is unassigned."));
        return false;        
    }*/
    
    /* Is Assigned
    if($workorder_details['status'] == 'assigned') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it is assigned"));
        return false;        
    }*/
    
    /* Is Waiting for Parts
    if($workorder_details['status'] == 'waiting_for_parts') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it is waiting for parts."));
        return false;        
    }*/
    
    /* Is Scheduled
    if($workorder_details['status'] == 'scheduled') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it is scheduled."));
        return false;        
    }*/
    
    /* With Client
    if($workorder_details['status'] == 'with_client') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it is with the client."));
        return false;        
    }*/
    
    /* Is On Hold
    if($workorder_details['status'] == 'on_hold') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it is on hold."));
        return false;        
    }*/
    
    /* Is with Management
    if($workorder_details['status'] == 'management') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it is with mangement."));
        return false;        
    }*/
    
    // Closed without Invoice
    if($workorder_details['status'] == 'closed_without_invoice') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it has been closed without an invoice."));
        return false;        
    }
    
    // Closed with Invoice
    if($workorder_details['status'] == 'closed_with_invoice') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it has been closed with an invoice."));
        return false;        
    }
    
    // Is deleted
    if($workorder_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it has already been deleted."));
        return false;        
    }
    
    /* Is Closed (old Fallback method)
    if(!get_workorder_details($workorder_details['workorder_id'], 'is_closed')) {
        //postEmulationWrite('warning_msg', _gettext("This workorder employee cannot be changed because it has been closes."));
        return false;  
    }*/
    
    // All checks passed
    return true;    
     
 }