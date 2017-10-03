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
# Display all Work orders for the given status      #
#####################################################

function display_workorders($db, $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $status = null, $employee_id = null, $customer_id = null) {
    
    global $smarty;    
   
    /* Filter the Records */
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."workorder.workorder_id";
    
    // Restrict results by search category and search term
    if($search_term != null) {$whereTheseRecords .= " AND ".PRFX."user.$search_category LIKE '%$search_term%'";} 
    
    // Restrict by Status
    if($status != null) {
        
        // All Open workorders
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.is_closed != '1'";
        
        // All Closed workorders
        } elseif($status == 'closed') {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.is_closed = '1'";
        
        // Return Workorders for the given status
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.status= ".$db->qstr($status);
            
        }
        
    }        

    // Restrict by Employee
    if($employee_id != null) {$whereTheseRecords .= " AND ".PRFX."user.user_id=".$db->qstr($employee_id);}

    // Restrict by Customer
    if($customer_id != null) {$whereTheseRecords .= " AND ".PRFX."customer.customer_id=".$db->qstr($customer_id);}
    
    /* The SQL code */
    
    $sql =  "SELECT            
            ".PRFX."user.email AS employee_email,
            ".PRFX."user.display_name AS employee_display_name,
            ".PRFX."user.work_primary_phone AS employee_work_primary_phone,
            ".PRFX."user.work_mobile_phone AS employee_work_mobile_phone,
            ".PRFX."user.home_primary_phone AS employee_home_primary_phone,
                
            ".PRFX."customer.customer_id,
            ".PRFX."customer.display_name AS customer_display_name,
            ".PRFX."customer.first_name AS customer_first_name,
            ".PRFX."customer.last_name AS customer_last_name,            
            ".PRFX."customer.address AS customer_address,
            ".PRFX."customer.city AS customer_city,
            ".PRFX."customer.state AS customer_state,
            ".PRFX."customer.zip AS customer_zip,
            ".PRFX."customer.country AS customer_country,
            ".PRFX."customer.primary_phone AS customer_phone,
            ".PRFX."customer.mobile_phone AS customer_mobile_phone,
            ".PRFX."customer.fax AS customer_fax,
                
            ".PRFX."workorder.workorder_id, employee_id, invoice_id,
            ".PRFX."workorder.open_date AS workorder_open_date,
            ".PRFX."workorder.close_date AS workorder_close_date,
            ".PRFX."workorder.scope AS workorder_scope,
            ".PRFX."workorder.status AS workorder_status
               
            FROM ".PRFX."workorder
            LEFT JOIN ".PRFX."user ON ".PRFX."workorder.employee_id   = ".PRFX."user.user_id
            LEFT JOIN ".PRFX."customer ON ".PRFX."workorder.customer_id = ".PRFX."customer.customer_id                 
            ".$whereTheseRecords."
            GROUP BY ".PRFX."workorder.workorder_id
            ORDER BY ".PRFX."workorder.workorder_id
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching Work Orders."));
            exit;
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
        if($page_no > 1) {
            $previous = ($page_no - 1);            
        } else { 
            $previous = 1;            
        }
        $smarty->assign('previous', $previous);        
        
        // Assign the next page
        if($page_no < $total_pages){
            $next = ($page_no + 1);            
        } else {
            $next = $total_pages;
        }
        $smarty->assign('next', $next);      
        
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Work Orders."));
        exit;
    } else {
        
        $records = $rs->GetArray();   // do i need to add the check empty

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

#############################
# Display Work Order Notes  #
#############################

function display_workorder_notes($db, $workorder_id){
    
    $sql = "SELECT
            ".PRFX."workorder_notes.*,
            ".PRFX."user.display_name
            FROM
            ".PRFX."workorder_notes,
            ".PRFX."user
            WHERE workorder_id=".$db->qstr($workorder_id)."
            AND ".PRFX."user.user_id = ".PRFX."workorder_notes.employee_id";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return notes for the Work Order."));
        exit;
    } else {
        
        return $rs->GetArray(); 
        
    }
    
}

##############################
# Display Work Order History #
##############################

function display_workorder_history($db, $workorder_id){
    
    $sql = "SELECT 
            ".PRFX."workorder_history.*,
            ".PRFX."user.display_name
            FROM 
            ".PRFX."workorder_history, 
            ".PRFX."user 
            WHERE ".PRFX."workorder_history.workorder_id=".$db->qstr($workorder_id)." 
            AND ".PRFX."user.user_id = ".PRFX."workorder_history.employee_id
            ORDER BY ".PRFX."workorder_history.history_id";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return history records for the Work Order."));
        exit;
    } else {
        
        return $rs->GetArray();  
        
    }
    
}

/** Insert Functions **/

#########################
# Insert New Work Order #
#########################

function insert_workorder($db, $customer_id, $scope, $description, $comments) {
    
    $sql = "INSERT INTO ".PRFX."workorder SET            
            customer_id     =". $db->qstr( $customer_id                         ).",
            open_date       =". $db->qstr( time()                               ).",
            status          =". $db->qstr( 'unassigned'                         ).",
            is_closed       =". $db->qstr( 0                                    ).", 
            created_by      =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            scope           =". $db->qstr( $scope                               ).",
            description     =". $db->qstr( $description                         ).",            
            comments        =". $db->qstr( $comments                            );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the Work Order Record into the database."));
        exit;
        
    } else {

        // Get the new Workorders ID
        $workorder_id = $db->Insert_ID();

        // Create a Workorder History Note            
        insert_workorder_history_note($db, $workorder_id, _gettext("Created by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("Created by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Update last active record        
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));

        return $workorder_id;
        
    }
    
}

######################################
# Insert New Work Order History Note #
######################################

// this might be go in the main include as different modules add work order history notes

function insert_workorder_history_note($db, $workorder_id = null, $note = '') {
    
    // If Work Order History Notes are not enabled, exit
    if(QFactory::getConfig()->get('workorder_history_notes') != true) { return; }    
    
    // This prevents errors from such functions as mail.php where a workorder_id is not always present - not currently used
    if($workorder_id == null) { return; }
    
    $sql = "INSERT INTO ".PRFX."workorder_history SET            
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            workorder_id    =". $db->qstr( $workorder_id                        ).",
            date            =". $db->qstr( time()                               ).",
            note            =". $db->qstr( $note                                );
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert a Work Order history note."));
        exit;
    } else {
        
        return true;
        
    }  
    
}

##############################
#    insert workorder note   #
##############################

function insert_workorder_note($db, $workorder_id, $note){
    
    $sql = "INSERT INTO ".PRFX."workorder_notes SET                        
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            workorder_id    =". $db->qstr( $workorder_id                        ).", 
            date            =". $db->qstr( time()                               ).",
            description     =". $db->qstr( $note                                );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert a Work Order note."));
        exit;
        
    } else {
        
        // Get the new Note ID
        $workorder_note_id = $db->Insert_ID();
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $workorder_id, _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("added by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        write_record_to_activity_log(_gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("added to Work Order").' '.$workorder_id.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Update last active record
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        update_workorder_last_active($db, $workorder_id);
        
        return true;
        
    }
    
}

/** Get Functions **/

########################################
#   Get a Workorder's details          #
########################################

function get_workorder_details($db, $workorder_id = null, $item = null) {  
    
    // This covers invoice only
    if(!$workorder_id){
        return;        
    }

    $sql = "SELECT * FROM ".PRFX."workorder WHERE workorder_id=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Work Order details."));
        exit;
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

function get_workorder_note($db, $workorder_note_id, $item = null){
    
    $sql = "SELECT * FROM ".PRFX."workorder_notes WHERE workorder_note_id=".$db->qstr( $workorder_note_id );    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get a Work Order Note."));
        exit;
    } else { 
        
        if($item === null){
            
            return $rs->GetRowAssoc(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#  Get ALL of a workorder's notes   #
#####################################

function get_workorder_notes($db, $workorder_id) {
    
    $sql = "SELECT * FROM ".PRFX."customer_notes WHERE customer_id=".$db->qstr( $workorder_id );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get all Notes for a Work Order."));
        exit;
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

function get_workorder_statuses($db) {
    
    $sql = "SELECT * FROM ".PRFX."workorder_statuses";

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get work order statuses."));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

######################################
#  Get Workorder status display name #
######################################

function get_workorder_status_display_name($db, $status_key) {
    
    $sql = "SELECT display_name FROM ".PRFX."workorder_statuses WHERE status_key=".$db->qstr($status_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the work order status display name."));
        exit;
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

/** Update Functions **/

###########################################
# Update Work Order Scope and Description #
###########################################

function update_workorder_scope_and_description($db, $workorder_id, $scope, $description){
    
    $sql = "UPDATE ".PRFX."workorder SET           
            scope               =".$db->qstr( $scope        ).",
            description         =".$db->qstr( $description  )."            
            WHERE workorder_id  =".$db->qstr( $workorder_id );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Work Order Scope and Description."));
        exit;
    } else {
        
        // Creates a History record        
        insert_workorder_history_note($db, $workorder_id, _gettext("Scope and Description updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("Scope and Description updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Update last active record        
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        update_workorder_last_active($db, $workorder_id);
        
        return true;
        
    }
    
}

##################################
#   Update Workorder Comments    #
##################################

function update_workorder_comments($db, $workorder_id, $comments){
    
    $sql = "UPDATE ".PRFX."workorder SET            
            comments            =". $db->qstr( $comments        )."
            WHERE workorder_id  =". $db->qstr( $workorder_id    );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Work Order Comments."));
        exit;
    } else {
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $workorder_id, _gettext("Comments updated by").' '.QFactory::getUser()->login_display_name);
        
        // Log activity        
        write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("Comments updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Update last active record       
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id')); 
        update_workorder_last_active($db, $workorder_id);
        
        return true;
        
    }
    
}

################################
# Update Work Order Resolution #
################################

function update_workorder_resolution($db, $workorder_id, $resolution){
    
    $sql = "UPDATE ".PRFX."workorder SET                        
            resolution          =". $db->qstr( $resolution      )."            
            WHERE workorder_id  =". $db->qstr( $workorder_id    );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Work Order resolution."));
        exit;
    } else {
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $workorder_id, _gettext("Resolution updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("Resolution updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Update last active record        
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        update_workorder_last_active($db, $workorder_id);
        
        return true;
            
    }
    
}

############################
# Update Workorder Status  #
############################

function update_workorder_status($db, $workorder_id, $new_status) {
    
    // Get current workorder details
    $workorder_details = get_workorder_details($db, $workorder_id);
    
    // If the new status is the same as the current one, exit
    if($new_status == $workorder_details['status']) {        
        postEmulationWrite('warning_msg', _gettext("Nothing done. The new status is the same as the current status."));
        return false;
    }
    
    $sql = "UPDATE ".PRFX."workorder SET \n";
    
    // when unassigned there should be no employee the '\n' makes sql look neater
    if ($new_status == 'unassigned') { $sql .= "employee_id = '',\n"; }
    
    $sql .="status              =". $db->qstr( $new_status      )."            
            WHERE workorder_id  =". $db->qstr( $workorder_id    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Work Order Status."));
        exit;
        
    } else {
        
        // If status not unassigned and there is not employee set current logged in user as assigned
        if($workorder_details['employee_id'] == '' && $new_status != 'unassigned') {
            assign_workorder_to_employee($db, $workorder_id, QFactory::getUser()->login_user_id);
        }
        
        // Update Workorder 'is_closed' boolean
        if($new_status == 'closed_without_invoice' || $new_status == 'closed_with_invoice') {
            update_workorder_closed_status($db, $workorder_id, 'close');
        } else {
            update_workorder_closed_status($db, $workorder_id, 'open');
        }
                
        // Status updated message
        postEmulationWrite('information_msg', _gettext("Work Order status updated."));        
        
        // For writing message to log file, get work order status display name
        $wo_status_display_name = _gettext(get_workorder_status_display_name($db, $new_status));
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $workorder_id, _gettext("Status updated to").' '.$wo_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("Status updated to").' '.$wo_status_display_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Update last active record        
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }
    
}

###################################
# Update Workorder Closed Status  #
###################################

function update_workorder_closed_status($db, $workorder_id, $new_closed_status) {
    
    if($new_closed_status == 'open') {
        
        $sql = "UPDATE ".PRFX."workorder SET
                closed_by           ='',
                close_date          ='',
                is_closed           =". $db->qstr( 0                                    )."
                WHERE workorder_id  =". $db->qstr( $workorder_id                        );
        
    }
    
    if($new_closed_status == 'close') {        
        $sql = "UPDATE ".PRFX."workorder SET
                closed_by           =". $db->qstr( QFactory::getUser()->login_user_id   ).",
                close_date          =". $db->qstr( time()                               ).",
                is_closed           =". $db->qstr( 1                                    )."
                WHERE workorder_id  =". $db->qstr( $workorder_id                        );
        
    }
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Work Order's Closed status."));
        exit;
    }
    
}

#################################
#    Update Last Active         #
#################################

function update_workorder_last_active($db, $workorder_id = null) {
    
    // compensate for some invoices not having workorders
    if(!$workorder_id) { return; }
    
    $sql = "UPDATE ".PRFX."workorder SET
            last_active=".$db->qstr(time())."
            WHERE workorder_id=".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Work Order last active time."));
        exit;
    }
    
}

####################################
# Update a Workorder's Invoice ID  #
####################################

function update_workorder_invoice_id($db, $workorder_id, $invoice_id) {
    
    // This prevents invoices with no workorders causing issues
    if($workorder_id == null) { return; }
    
    $sql = "UPDATE ".PRFX."workorder SET
            invoice_id          =". $db->qstr( $invoice_id      )."
            WHERE workorder_id  =". $db->qstr( $workorder_id    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Work Order Invoice ID."));
        exit;
    }    
    
}

##############################
#    update workorder note   #
##############################

function update_workorder_note($db, $workorder_note_id, $note) {
    
    $sql = "UPDATE ".PRFX."workorder_notes SET
            employee_id             =". $db->qstr( QFactory::getUser()->login_user_id   ).",            
            description             =". $db->qstr( $note                                )."
            WHERE workorder_note_id =". $db->qstr( $workorder_note_id                   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Work Order note."));
        exit;    
        
    } else {
        
        $workorder_id = get_workorder_note($db, $workorder_note_id, 'workorder_id');
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $workorder_id, _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("updated by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        write_record_to_activity_log(_gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("for Work Order").' '.$workorder_id.' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.', null, null, $workorder_id);
        
        // Update last active record        
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        update_workorder_last_active($db, $workorder_id);
        
    }
    
}

/** Close Functions **/

########################################
# Close Workorder without invoice      #
########################################

function close_workorder_without_invoice($db, $workorder_id, $resolution){
    
    // Insert resolution and close information
    $sql = "UPDATE ".PRFX."workorder SET
            closed_by           =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            close_date          =". $db->qstr( time()                               ).",            
            status              =". $db->qstr( 'closed_without_invoice'             ).",
            is_closed           =". $db->qstr( 1                                    ).",
            resolution          =". $db->qstr( $resolution                          )."
            WHERE workorder_id  =". $db->qstr( $workorder_id                        );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to close a Work Order without an invoice."));
        exit;
    } else {
        
        // Create a History record
        insert_workorder_history_note($db, $workorder_id, _gettext("Closed without Invoice by").' '.QFactory::getUser()->login_display_name.'.');
            
        // Log activity
        write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("has been closed without invoice by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Update last active record
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }
    
}

#################################
# Close Workorder with Invoice  #
#################################

function close_workorder_with_invoice($db, $workorder_id, $resolution){
    
    // Insert resolution and close information
    $sql = "UPDATE ".PRFX."workorder SET
            closed_by           =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            close_date          =". $db->qstr( time()                               ).",            
            status              =". $db->qstr( 'closed_with_invoice'                ).",
            is_closed           =". $db->qstr( 1                                    ).",
            resolution          =". $db->qstr( $resolution                          )."
            WHERE workorder_id  =". $db->qstr( $workorder_id                        );
    
    if(!$rs = $db->Execute($sql)){ 
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to close a Work Order with an invoice."));
        exit;
    } else {
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $workorder_id, _gettext("Closed with Invoice by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity
        write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("has been closed with invoice by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Update last active record
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }      
    
}

/** Delete Work Orders **/

#####################
# Delete Workorder  #
#####################

function delete_workorder($db, $workorder_id) {
    
    // Does the workorder have an invoice
    if(get_workorder_details($db, $workorder_id, 'invoice_id')) {        
        postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because it has an invoice."));
        return false;
    }
    
    // Is the workorder in an allowed state to be deleted
    if(!check_workorder_status_allows_for_deletion($db, $workorder_id)) {        
        postEmulationWrite('warning_msg', _gettext("This workorder cannot be deleted because its status does not allow it."));
        return false;
    }
    
    // get customer_id before deleletion
    $customer_id = get_workorder_details($db, $workorder_id, 'customer_id');
    
    // Delete the workorder primary record
    $sql = "DELETE FROM ".PRFX."workorder WHERE workorder_id=".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the Work Order").' '.$workorder_id.'.');
        exit;        
    
    // Delete the workorder history
    } else {        
       
        $sql = "DELETE FROM ".PRFX."workorder_history WHERE workorder_id=".$db->qstr($workorder_id);

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the history notes for Work Order").' '.$workorder_id.'.');
            exit;
            
        // Delete the workorder notes    
        } else {
            
            $sql = "DELETE FROM ".PRFX."workorder_notes WHERE workorder_id=".$db->qstr($workorder_id);

            if(!$rs = $db->Execute($sql)) {
                force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the notes for Work Order").' '.$workorder_id.'.');
                exit;        
             
                
            // Delete the workorder schedule events     
            } else {

                $sql = "DELETE FROM ".PRFX."schedule WHERE workorder_id=".$db->qstr($workorder_id);

                if(!$rs = $db->Execute($sql)) {
                    force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the schedules for Work Order").' '.$workorder_id.'.');
                    exit;

                // Log the workorder deletion
                } else {

                    // Write the record to the activity log                    
                    write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("has been deleted by").' '.QFactory::getUser()->login_display_name.'.');
                    
                    // Update last active record
                    update_customer_last_active($db, $customer_id);                    

                    return true;

                }        
        
            }
    
        }
    
    }
    
}

######################################################
# Is the workorder in an allowed state to be deleted #
######################################################

function check_workorder_status_allows_for_deletion($db, $workorder_id) {
    
    $sql = "SELECT status FROM ".PRFX."workorder WHERE workorder_id=".$workorder_id;
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if a Work Order is allowed to be deleted."));
        exit;
    } else {        
        
        // Unassigned and Management are allowed status for deleteion
        if($rs->fields['status'] == 'unassigned' || $rs->fields['status'] == 'management') {
            
            return true;
            
        } else {             
            
            return false;
            
        }
        
    }
    
}

####################################
#    delete a workorders's note    #
####################################

function delete_workorder_note($db, $workorder_note_id) {
    
    // get workorder_id before any deleting
    $workorder_id = get_workorder_note($db, $workorder_note_id, 'workorder_id');
    
    $sql = "DELETE FROM ".PRFX."workorder_notes WHERE workorder_note_id=".$db->qstr( $workorder_note_id );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete a Work Order note."));
        exit;
        
    } else {        
        
        // Create a Workorder History Note       
        insert_workorder_history_note($db, $workorder_id, _gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("has been deleted by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        write_record_to_activity_log(_gettext("Work Order Note").' '.$workorder_note_id.' '._gettext("for Work Order").' '.$workorder_id.' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.', null, null, $workorder_id);
        
        // Update last active record        
        update_customer_last_active($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        update_workorder_last_active($db, $workorder_id);
        
    }
    
}

/** Other Functions **/

################################
# Resolution Edit Status Check #
################################

function resolution_edit_status_check($db, $workorder_id) {    
    
    $wo_is_closed   = get_workorder_details($db, $workorder_id, 'is_closed');
    $wo_status      = get_workorder_details($db, $workorder_id, 'status');

    // Workorder is Closed
    if($wo_is_closed == '1') {

        postEmulationWrite('warning_msg', _gettext("Cannot edit the resolution because the Work Order is already closed."));
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

function assign_workorder_to_employee($db, $workorder_id, $target_employee_id) {
    
    // Get the workorder details
    $workorder_details = get_workorder_details($db, $workorder_id);
    
    // If the new employee is the same as the current one, exit
    if($target_employee_id == $workorder_details['employee_id']) {        
        postEmulationWrite('warning_msg', _gettext("Nothing done. The new employee is the same as the current employee."));
        return false;
    }    
    
    // Only change workorder status if unassigned
    if($workorder_details['status'] == 'unassigned') {
        
        $sql = "UPDATE ".PRFX."workorder SET
                employee_id         =". $db->qstr( $target_employee_id  ).",
                status              =". $db->qstr( 'assigned'           )."
                WHERE workorder_id  =". $db->qstr( $workorder_id        );

    // Keep the same workorder status    
    } else {    
        
        $sql = "UPDATE ".PRFX."workorder SET
                employee_id         =". $db->qstr( $target_employee_id  )."            
                WHERE workorder_id  =". $db->qstr( $workorder_id        );

    }
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to assign a Work Order to an employee."));
        exit;
        
    } else {
        
        // Assigned employee success message
        postEmulationWrite('information_msg', _gettext("Assigned employee updated.")); 
        
        // Get Logged in Employee's Display Name        
        $logged_in_employee_display_name = QFactory::getUser()->display_name;
        
        // Get the currently assigned employee ID
        $assigned_employee_id = $workorder_details['employee_id'];
        
        // Get the Display Name of the currently Assigned Employee
        if($assigned_employee_id == ''){
            $assigned_employee_display_name = _gettext("Unassigned");            
        } else {            
            $assigned_employee_display_name = get_user_details($db, $assigned_employee_id, 'display_name');
        }
        
        // Get the Display Name of the Target Employee        
        $target_employee_display_name = get_user_details($db, $target_employee_id, 'display_name');
        
        // Creates a History record
        insert_workorder_history_note($db, $workorder_id, _gettext("Work Order").' '.$workorder_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.');

        // Log activity
        write_record_to_activity_log(_gettext("Work Order").' '.$workorder_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.', $target_employee_id);

        // Update last active record
        update_user_last_active($db, $workorder_details['employee_id']);
        update_user_last_active($db, $target_employee_id);
        update_customer_last_active($db, $workorder_details['customer_id']);
        update_workorder_last_active($db, $workorder_id);
        
        return true;
        
    }
    
 }