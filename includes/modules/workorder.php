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

####################################
# Display a single open workorder  # // just used in details.php and print.php
####################################

function display_single_workorder($db, $workorder_id){
    
    $sql = "SELECT ".PRFX."workorder.*,
            ".PRFX."workorder.   WORK_ORDER_STATUS,
            ".PRFX."customer.     *,            
            ".PRFX."user.     EMPLOYEE_ID, EMPLOYEE_EMAIL, EMPLOYEE_DISPLAY_NAME, EMPLOYEE_TYPE, EMPLOYEE_WORK_PHONE, EMPLOYEE_HOME_PHONE, EMPLOYEE_MOBILE_PHONE            
            FROM ".PRFX."workorder
            LEFT JOIN ".PRFX."customer ON ".PRFX."workorder.CUSTOMER_ID           = ".PRFX."customer.CUSTOMER_ID
            LEFT JOIN ".PRFX."user ON ".PRFX."workorder.WORK_ORDER_ASSIGN_TO  = ".PRFX."user.EMPLOYEE_ID             
            WHERE ".PRFX."workorder.WORK_ORDER_ID =".$db->qstr($workorder_id);

    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the Work Order record requested."));
        exit;
    } else { 
        $single_workorder = $rs->GetRowAssoc();
        
        if(empty($single_workorder)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to find the requested Work Order."));
            exit;            
        } else {
            
            return $single_workorder;
            
        }
        
    }
    
}

#####################################################
# Display all Work orders for the given status      #
#####################################################

function display_workorders($db, $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $status = null, $employee_id = null, $customer_id = null) {
    
    global $smarty;    
   
    /* Filter the Records */
    
    // Default Action
    $whereTheseRecords = " WHERE ".PRFX."workorder.WORK_ORDER_ID"; 
    
    // Restrict results by search category and search term
    if($search_term != null) {$whereTheseRecords .= " AND ".PRFX."user.$search_category LIKE '%$search_term%'";} 
    
    // Restrict by Status
    if($status != null) {$whereTheseRecords = " AND ".PRFX."workorder.WORK_ORDER_STATUS= ".$db->qstr($status);}        

    // Restrict by Employee
    if($employee_id != null) {$whereTheseRecords .= " AND ".PRFX."user.user_id=".$db->qstr($employee_id);}

    // Restrict by Customer
    if($customer_id != null) {$whereTheseRecords .= " AND ".PRFX."customer.CUSTOMER_ID=".$db->qstr($customer_id);}
    
    /* The SQL code */
    
    $sql =  "SELECT
            ".PRFX."user.     user_id, email, display_name, usergroup, work_phone, home_phone, work_mobile_phone,
            ".PRFX."customer.     CUSTOMER_ID, CUSTOMER_DISPLAY_NAME,
            ".PRFX."workorder.    WORK_ORDER_ID, WORK_ORDER_OPEN_DATE, WORK_ORDER_CLOSE_DATE, WORK_ORDER_ASSIGN_TO, WORK_ORDER_SCOPE, WORK_ORDER_STATUS            
            FROM ".PRFX."workorder
            LEFT JOIN ".PRFX."user ON ".PRFX."workorder.WORK_ORDER_ASSIGN_TO   = ".PRFX."user.user_id
            LEFT JOIN ".PRFX."customer ON ".PRFX."workorder.CUSTOMER_ID            = ".PRFX."customer.CUSTOMER_ID                 
            ".$whereTheseRecords."
            GROUP BY ".PRFX."workorder.WORK_ORDER_ID
            ORDER BY ".PRFX."workorder.WORK_ORDER_ID
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the matching Work Orders."));
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the matching Work Orders."));
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
            ".PRFX."user.EMPLOYEE_DISPLAY_NAME
            FROM
            ".PRFX."workorder_notes,
            ".PRFX."user
            WHERE WORK_ORDER_ID=".$db->qstr($workorder_id)."
            AND ".PRFX."user.EMPLOYEE_ID = ".PRFX."workorder_notes.WORK_ORDER_EMPLOYEE_ID";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return notes for the Work Order."));
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
            ".PRFX."user.EMPLOYEE_DISPLAY_NAME 
            FROM 
            ".PRFX."workorder_history, 
            ".PRFX."user 
            WHERE ".PRFX."workorder_history.WORK_ORDER_ID=".$db->qstr($workorder_id)." 
            AND ".PRFX."user.EMPLOYEE_ID = ".PRFX."workorder_history.ENTERED_BY
            ORDER BY ".PRFX."workorder_history.HISTORY_ID";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return history records for the Work Order."));
        exit;
    } else {
        
        return $rs->GetArray();  
        
    }
    
}

/** New/Insert Functions **/

#########################
# Insert New Work Order #
#########################

function insert_workorder($db, $customer_id, $created_by, $scope, $workorder_description, $workorder_comments){
    
    $sql = "INSERT INTO ".PRFX."workorder SET 
            CUSTOMER_ID                                 = " . $db->qstr( $customer_id           ).",
            WORK_ORDER_OPEN_DATE                        = " . $db->qstr( time()                 ).",
            WORK_ORDER_STATUS                           = " . $db->qstr( 1                      ).",            
            WORK_ORDER_CREATE_BY                        = " . $db->qstr( $created_by            ).",
            WORK_ORDER_SCOPE                            = " . $db->qstr( $scope                 ).",
            WORK_ORDER_DESCRIPTION                      = " . $db->qstr( $workorder_description ).",
            LAST_ACTIVE                                 = " . $db->qstr( NULL                   ).",
            WORK_ORDER_COMMENT                          = " . $db->qstr( $workorder_comments    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the Work Order Record into the database."));
        exit;
        
    } else {

        // Get the new Workorders ID
        $workorder_id = $db->Insert_ID();

        // Creates a History record for the new work order             
        insert_workorder_history_note($db, $workorder_id, gettext("Created by").' '.$_SESSION['login_display_name']);
        
        // Log activity        
        write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("Created by").' '.$_SESSION['login_display_name']);

        return $workorder_id;
        
    }
    
}

######################################
# Insert New Work Order History Note #
######################################

// this might be go in the main include as different modules add work order history notes

function insert_workorder_history_note($db, $workorder_id, $workorder_history_note) {
    
    $sql = "INSERT INTO ".PRFX."workorder_history SET
        WORK_ORDER_ID   = " . $db->qstr( $workorder_id                      ).",
        DATE            = " . $db->qstr( time()                             ).",
        NOTE            = " . $db->qstr( $workorder_history_note            ).",
        ENTERED_BY      = " . $db->qstr( QFactory::getUser()->login_user_id );
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert a Work Order history note."));
        exit;
    } else {
        
        update_workorder_last_active($db, $workorder_id);        
        return true;
        
    }  
    
}

##############################
#    insert workorder note   #
##############################

function insert_workorder_note($db, $workorder_id, $workorder_note){
    
    $sql = "INSERT INTO ".PRFX."workorder_notes SET 
            WORK_ORDER_ID                  =". $db->qstr( $workorder_id                         ).",             
            WORK_ORDER_EMPLOYEE_ID         =". $db->qstr( QFactory::getUser()->login_user_id    ).",
            WORK_ORDER_NOTES_DATE          =". $db->qstr( time()                                ).",
            WORK_ORDER_NOTES_DESCRIPTION   =". $db->qstr( $workorder_note                       );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert a Work Order note."));
        exit;
        
    } else {
        
        // Get the new Note ID
        $note_id = $db->Insert_ID();
        
        // Creates a History record for the new work order        
        insert_workorder_history_note($db, $workorder_id, gettext("Work Order Note").' '.$note_id.' '.gettext("added by").' '.$_SESSION['login_display_name']);
        
        // Log activity        
        write_record_to_activity_log(gettext("Work Order Note").' '.$note_id.' '.gettext("added to Work Order").' '.$workorder_id.' '.gettext("by").' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);
        
        return true;
        
    }
    
}

/** Get Functions **/

########################################
#   Get single Workorder record        #
########################################

function get_workorder_details($db, $workorder_id, $item = null) {   

    // compensate for some invoices having no workorder    
    if($workorder_id == '') { return array(); }
    
    $sql = "SELECT * FROM ".PRFX."workorder WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get Work Order details."));
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
    
    $sql = "SELECT * FROM ".PRFX."workorder_notes WHERE WORK_ORDER_NOTES_ID=".$db->qstr( $workorder_note_id );    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get a Work Order Note."));
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
    
    $sql = "SELECT * FROM ".PRFX."customer_notes WHERE CUSTOMER_ID=".$db->qstr( $workorder_id );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get all Notes for a Work Order."));
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

/** Update Functions **/

###########################################
# Update Work Order Scope and Description #
###########################################

function update_workorder_scope_and_description($db, $workorder_id, $workorder_scope, $workorder_description){
    
    $sql = "UPDATE ".PRFX."workorder SET
            WORK_ORDER_SCOPE        =".$db->qstr( $workorder_scope          ).",
            WORK_ORDER_DESCRIPTION  =".$db->qstr( $workorder_description    ).",
            LAST_ACTIVE             =".$db->qstr( time()                    )."
            WHERE WORK_ORDER_ID     =".$db->qstr( $workorder_id             );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a Work Order Scope and Description."));
        exit;
    } else {
        
        // Creates a History record        
        insert_workorder_history_note($db, $workorder_id, gettext("Scope and Description updated by").' '.$_SESSION['login_display_name']);
        
        // Log activity        
        write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("Scope and Description updated by").' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }
    
}

##################################
#   Update Workorder Comments    #
##################################

function update_workorder_comments($db, $workorder_id, $workorder_comments){
    
    $sql = "UPDATE ".PRFX."workorder SET
            WORK_ORDER_COMMENT              =".$db->qstr( $workorder_comments   ).",
            LAST_ACTIVE                     =".$db->qstr( time()                )."
            WHERE WORK_ORDER_ID             =".$db->qstr( $workorder_id         );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a Work Order Comments"));
        exit;
    } else {
        
        // Create a History record        
        insert_workorder_history_note($db, $workorder_id, gettext("Comments updated by").' '.$_SESSION['login_display_name']);
        
        // Log activity        
        write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("Comments updated by").' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }
    
}

################################
# Update Work Order Resolution #
################################

function update_workorder_resolution($db, $workorder_id, $workorder_resolution){
    
    $sql = "UPDATE ".PRFX."workorder SET
            WORK_ORDER_RESOLUTION   = " . $db->qstr( $workorder_resolution ).",
            LAST_ACTIVE             = " . $db->qstr( time()                )."
            WHERE  WORK_ORDER_ID    = " . $db->qstr( $workorder_id         );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a Work Order resolution."));
        exit;
    } else {
        
        // Create a History record        
        insert_workorder_history_note($db, $workorder_id, gettext("Resolution updated by").' '.$_SESSION['login_display_name']);
        
        // Log activity        
        write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("Resolution updated by").' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
            
    }
    
}

############################
# Update Workorder Status  #
############################

function update_workorder_status($db, $workorder_id, $assign_status){
    
    $sql = "UPDATE ".PRFX."workorder SET
            WORK_ORDER_STATUS       = " . $db->qstr( $assign_status     ).",
            LAST_ACTIVE             = " . $db->qstr( time()             )."
            WHERE WORK_ORDER_ID     = " . $db->qstr( $workorder_id      );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a Work Order Status."));
        exit;
        
    } else {
        
        if ($assign_status == '0'){
            
            $sql = "UPDATE ".PRFX."workorder SET 
                    WORK_ORDER_STATUS       = '1',
                    WORK_ORDER_ASSIGN_TO    = '0'                    
                    WHERE WORK_ORDER_ID     = " . $workorder_id;
            
            if(!$rs = $db->Execute($sql)) {
                force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to set Work Order status to unassigned."));
                exit;
            }
            
        }
    
        // for writing message to log file - this needs translating
        if($assign_status == '1') {$wo_status = gettext("WORKORDER_STATUS_1");
        } elseif ($assign_status == '2') {$wo_status = gettext("WORKORDER_STATUS_2");   
        } elseif ($assign_status == '3') {$wo_status = gettext("WORKORDER_STATUS_3");
        } elseif ($assign_status == '6') {$wo_status = gettext("WORKORDER_STATUS_6"); 
        } elseif ($assign_status == '7') {$wo_status = gettext("WORKORDER_STATUS_7");
        } elseif ($assign_status == '8') {$wo_status = gettext("WORKORDER_STATUS_8");
        } elseif ($assign_status == '9') {$wo_status = gettext("WORKORDER_STATUS_9");
        } elseif ($assign_status == '10') {$wo_status = gettext("WORKORDER_STATUS_10");    
        }
        
        // Create a History record        
        insert_workorder_history_note($db, $workorder_id, gettext("Status updated to").' '.$wo_status.' '.gettext("by").' '.$_SESSION['login_display_name']);
        
        // Log activity        
        write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("Status updated to").' '.$wo_status.' '.gettext("by").' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }
    
}

#################################
#    Update Last Active         #
#################################

function update_workorder_last_active($db, $workorder_id){
    
    $sql = "UPDATE ".PRFX."workorder SET LAST_ACTIVE=".$db->qstr(time())." WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a Work Order's last active time."));
        exit;
    }
    
}

##############################
#    update workorder note   #
##############################

function update_workorder_note($db, $workorder_note_id, $date, $note) {
    
    $sql = "UPDATE ".PRFX."workorder_notes SET
            WORK_ORDER_EMPLOYEE_ID          =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            WORK_ORDER_NOTES_DATE           =". $db->qstr( $date                                ).",
            WORK_ORDER_NOTES_DESCRIPTION    =". $db->qstr( $note                                )."
            WHERE WORK_ORDER_NOTES_ID       =". $db->qstr( $workorder_note_id                   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a Work Order note."));
        exit;
    }
    
}

/** Close Functions **/

#################################
# Close Workorder with Invoice  #
#################################

function close_workorder_with_invoice($db, $workorder_id, $workorder_resolution){
    
    /* Insert resolution and close information */
    $sql = "UPDATE ".PRFX."workorder SET
            WORK_ORDER_STATUS          = ". $db->qstr( 9                                    ).",
            WORK_ORDER_CLOSE_DATE      = ". $db->qstr( time()                               ).",
            WORK_ORDER_RESOLUTION      = ". $db->qstr( $workorder_resolution                ).",
            WORK_ORDER_CLOSE_BY        = ". $db->qstr( QFactory::getUser()->login_user_id   ).",
            WORK_ORDER_ASSIGN_TO       = ". $db->qstr( QFactory::getUser()->login_user_id   )."             
            WHERE WORK_ORDER_ID        = ". $db->qstr( $workorder_id                        );
    
    if(!$rs = $db->Execute($sql)){ 
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to close a Work Order with an invoice."));
        exit;
    } else {
        
        // Create a History record        
        insert_workorder_history_note($db, $workorder_id, gettext("Closed with Invoice by").' '.$_SESSION['login_display_name']);
        
        // Log activity
        write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("has been closed with invoice by").' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }      
    
}

########################################
# Close Workorder without invoice      #
########################################

function close_workorder_without_invoice($db, $workorder_id, $workorder_resolution){
    
    /* Insert resolution and close information */
    $sql = "UPDATE ".PRFX."workorder SET
            WORK_ORDER_STATUS          = ". $db->qstr( 6                                    ).",
            WORK_ORDER_CLOSE_DATE      = ". $db->qstr( time()                               ).",
            WORK_ORDER_RESOLUTION      = ". $db->qstr( $workorder_resolution                ).",
            WORK_ORDER_CLOSE_BY        = ". $db->qstr( QFactory::getUser()->login_user_id   ).",
            WORK_ORDER_ASSIGN_TO       = ". $db->qstr( QFactory::getUser()->login_user_id   )."             
            WHERE WORK_ORDER_ID        = ". $db->qstr( $workorder_id                        );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to close a Work Order without an invoice."));
        exit;
    } else {
        
        // Create a History record
        insert_workorder_history_note($db, $workorder_id, gettext("Closed without Invoice by").' '.$_SESSION['login_display_name']);
            
        // Log activity
        write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("has been closed without invoice by").' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
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
    if(check_workorder_has_invoice($db, $workorder_id)) {        
        postEmulation('warning_msg', gettext("This workorder cannot be deleted because it has an invoice."));
        return false;
    }
    
    // Is the workorder in an allowed state to be deleted
    if(!check_workorder_status_is_allowed_for_deletion($db, $workorder_id)) {        
        postEmulation('warning_msg', gettext("This workorder cannot be deleted because its status does not allow it."));
        return false;
    }
    
    // Delete the workorder primary record
    $sql = "DELETE FROM ".PRFX."workorder WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the Work Order").' '.$workorder_id);
        exit;        
    
    // Delete the workorder history
    } else {        
       
        $sql = "DELETE FROM ".PRFX."workorder_history WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the history notes for Work Order").' '.$workorder_id);
            exit;
            
        // Delete the workorder notes    
        } else {
            
            $sql = "DELETE FROM ".PRFX."workorder_notes WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);

            if(!$rs = $db->Execute($sql)) {
                force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the notes for Work Order").' '.$workorder_id);
                exit;        
             
                
            // Delete the workorder schedule events     
            } else {

                $sql = "DELETE FROM ".PRFX."schedule WHERE WORKORDER_ID=".$db->qstr($workorder_id);

                if(!$rs = $db->Execute($sql)) {
                    force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the schedules for Work Order").' '.$workorder_id);
                    exit;

                // Log the workorder deletion
                } else {

                    // Write the record to the activity log                    
                    write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("has been deleted by").' '.$_SESSION['login_display_name']);

                    return true;

                }        
        
            }
    
        }
    
    }
    
}

######################################################
# Is the workorder in an allowed state to be deleted #
######################################################

function check_workorder_status_is_allowed_for_deletion($db, $workorder_id) {
    
    $sql = "SELECT WORK_ORDER_STATUS FROM ".PRFX."workorder WHERE WORK_ORDER_ID=".$workorder_id;
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if a Work Order is allowed to be deleted."));
        exit;
    } else {        
        
        if($rs->fields['WORK_ORDER_STATUS'] == 1 || $rs->fields['WORK_ORDER_STATUS'] == 10) {
            
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
    
    $sql = "DELETE FROM ".PRFX."workorder_notes WHERE WORK_ORDER_NOTES_ID=".$db->qstr( $workorder_note_id );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete a Work Order note."));
        exit;
    }
    
}

/** Other Functions **/

##################################
# Does workorder have an invoice #
##################################

function check_workorder_has_invoice($db, $workorder_id) {
    
    $sql = "SELECT * FROM ".PRFX."invoice WHERE WORKORDER_ID=".$workorder_id;
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if a Work Order has an invoice."));
        exit;
    } else {        
        
        if($rs->RecordCount() != 0) {
            
            return true;
            
        } else {          
            
            return false;
            
        }
        
    }
    
}

#########################################
# Assign Workorder to another employee  #
#########################################

function assign_workorder_to_employee($db, $workorder_id, $logged_in_employee_id, $assigned_employee_id, $target_employee_id) {
    
    $sql = "UPDATE ".PRFX."workorder SET
            WORK_ORDER_ASSIGN_TO=".$db->qstr($target_employee_id).",
            WORK_ORDER_STATUS=2
            WHERE WORK_ORDER_ID=".$db->qstr($workorder_id) ;
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to assign a Work Order to an employee."));
        exit;
    } else {
        
        // Get Logged in Employee's Display Name
        $logged_in_employee_display_name = get_user_display_name_by_id($db, $logged_in_employee_id);        
        
        // Get the Display Name of the currently Assigned Employee
        if($assigned_employee_id === '0'){
            $assigned_employee_display_name = gettext("Unassigned");            
        } else {
            $assigned_employee_display_name = get_user_display_name_by_id($db, $assigned_employee_id);            
        }
        
        // Get the Display Name of the Target Employee
        $target_employee_display_name = get_user_display_name_by_id($db, $target_employee_id);
        
        // Creates a History record
        insert_workorder_history_note($db, $workorder_id, gettext("Work Order").' '.gettext("has been assigned to").' '.$target_employee_display_name.' '.gettext("from").' '.$assigned_employee_display_name.' '.gettext("by").' '. $logged_in_employee_display_name);

        // Log activity
        write_record_to_activity_log(gettext("Work Order").' '.$workorder_id.' '.gettext("has been assigned to").' '.$target_employee_display_name.' '.gettext("from").' '.$assigned_employee_display_name.' '.gettext("by").' '. $logged_in_employee_display_name);

        return true;
        
    }
    
 }

################################
# Resolution Edit Status Check #
################################

function resolution_edit_status_check($db, $workorder_id) {
    
    $sql = "SELECT WORK_ORDER_STATUS FROM ".PRFX."workorder WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to edit a Work Order status."));
        exit;
        
    } else {        
    
        // waiting for parts
        if ($rs->fields['WORK_ORDER_STATUS'] == 3) {           
            
            postEmulation('warning_msg', gettext("Can not close a work order if it is Waiting For Parts. Please Adjust the status."));
            return false;
            
        }
        
        // closed
        if($rs->fields['WORK_ORDER_STATUS'] == 6) {
            
            postEmulation('warning_msg', gettext("Work Order Is already Closed. Please Create an Invoice."));
            return false;
        }
        
        return true;        
       
    }
   
}

###############################################
#      Check if a workorder is open           #  // this can be partial replaced with the get function
###############################################

function check_workorder_is_open($db, $workorder_id) {
    
    if(!$workorder_id){return false;}
    
    $sql = "SELECT WORK_ORDER_STATUS FROM ".PRFX."workorder WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if a Work Order is open."));
        exit;
    } else {
        $status = $rs->fields['WORK_ORDER'];
    }

    if($status == '6' || $status == '7' || $status == '8' || $status == '9') {        
        return false;
    } else {
        
        return true;
        
    }    
    
}