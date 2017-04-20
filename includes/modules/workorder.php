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

/** Mandatory Code **/

/** Display Functions **/

####################################
# Display a single open workorder  # // just used in details.php and print.php
####################################

function display_single_workorder($db, $workorder_id){
    
    global $smarty;
    
     $sql = "SELECT ".PRFX."TABLE_WORK_ORDER.*,
            ".PRFX."TABLE_WORK_ORDER.   WORK_ORDER_STATUS,
            ".PRFX."TABLE_CUSTOMER.     *,            
            ".PRFX."TABLE_EMPLOYEE.     EMPLOYEE_ID, EMPLOYEE_EMAIL, EMPLOYEE_DISPLAY_NAME, EMPLOYEE_TYPE, EMPLOYEE_WORK_PHONE, EMPLOYEE_HOME_PHONE, EMPLOYEE_MOBILE_PHONE            
            FROM ".PRFX."TABLE_WORK_ORDER
            LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID           = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO  = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID             
            WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID =".$db->qstr($workorder_id);

    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else { 
        $single_workorder = $rs->GetArray();
        
        if(empty($single_workorder)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_notfound'));
            exit;            
        } else {
            
            return $single_workorder;
            
        }
        
    }
    
}

#####################################################
# Display all Work orders for the given status      #
#####################################################

function display_workorders($db, $status = 'all', $direction = 'DESC', $use_pages = false, $page_no = 1, $records_per_page = 25, $employee_id = null, $customer_id = null) {
    
    global $smarty;    
   
    /* Filter the Records */
    
    // Status Restriction
    if($status != 'all') {
        // Restrict by status
        $whereTheseRecords = " WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_STATUS= ".$db->qstr($status);       
    } else {            
        // Do not restrict by status
        $whereTheseRecords = " WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID = *";
    }        

    // Restrict by Employee
    if($employee_id != null){
        $whereTheseRecords .= " AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID=".$db->qstr($employee_id);
    }

    // Restrict by Customer
    if($customer_id != null){
        $whereTheseRecords .= " AND ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID=".$db->qstr($customer_id);
    }
    
    /* The SQL code */
    
    $sql =  "SELECT
            ".PRFX."TABLE_EMPLOYEE.     EMPLOYEE_DISPLAY_NAME,
            ".PRFX."TABLE_CUSTOMER.     CUSTOMER_ID, CUSTOMER_DISPLAY_NAME,
            ".PRFX."TABLE_WORK_ORDER.   WORK_ORDER_ID, WORK_ORDER_OPEN_DATE, WORK_ORDER_CLOSE_DATE, WORK_ORDER_ASSIGN_TO, WORK_ORDER_SCOPE, WORK_ORDER_STATUS            
            FROM ".PRFX."TABLE_WORK_ORDER
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO   = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID   
            LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID            = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID".                 
            $whereTheseRecords.            
            " GROUP BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID".
            " ORDER BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_count'));
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    global $smarty;
    
    $sql = "SELECT ".PRFX."TABLE_WORK_ORDER_NOTES.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME
            FROM ".PRFX."TABLE_WORK_ORDER_NOTES, ".PRFX."TABLE_EMPLOYEE
            WHERE WORK_ORDER_ID=".$db->qstr($workorder_id)."
            AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_NOTES.WORK_ORDER_NOTES_ENTER_BY ";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->GetArray(); 
        
    }
    
}

##############################
# Display Work Order History #
##############################

function display_workorder_history($db, $workorder_id){
    
    global $smarty;
    
    $sql = "SELECT ".PRFX."TABLE_WORK_ORDER_HISTORY.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
            FROM ".PRFX."TABLE_WORK_ORDER_HISTORY, ".PRFX."TABLE_EMPLOYEE 
            WHERE ".PRFX."TABLE_WORK_ORDER_HISTORY.WORK_ORDER_ID=".$db->qstr($workorder_id)." 
            AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_HISTORY.ENTERED_BY
            ORDER BY ".PRFX."TABLE_WORK_ORDER_HISTORY.HISTORY_ID";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    global $smarty;

    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_description);
    stripslashes($workorder_comments);    

    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER SET 
            CUSTOMER_ID                                 = " . $db->qstr( $customer_id           ).",
            WORK_ORDER_OPEN_DATE                        = " . $db->qstr( time()                 ).",
            WORK_ORDER_STATUS                           = " . $db->qstr( 1                      ).",            
            WORK_ORDER_CREATE_BY                        = " . $db->qstr( $created_by            ).",
            WORK_ORDER_SCOPE                            = " . $db->qstr( $scope                 ).",
            WORK_ORDER_DESCRIPTION                      = " . $db->qstr( $workorder_description ).",
            LAST_ACTIVE                                 = " . $db->qstr( time()                 ).",
            WORK_ORDER_COMMENT                          = " . $db->qstr( $workorder_comments    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
        
    } else {

        // Get the new Workorders ID
        $workorder_id = $db->Insert_ID();

        // Creates a History record for the new work order
        //insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_function_insert_new_workorder'));
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_created').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Log activity
        //write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_created'));
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_created').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);

        return true;
        
    }
    
}

###################
# Insert New Note #
###################

function insert_workorder_note($db, $workorder_id, $workorder_note){
    
    global $smarty;

    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_note);

    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_NOTES SET 
             WORK_ORDER_ID                  =". $db->qstr( $workorder_id            ).",
             WORK_ORDER_NOTES_DESCRIPTION   =". $db->qstr( $workorder_note          ).",
             WORK_ORDER_NOTES_ENTER_BY      =". $db->qstr( $_SESSION['login_id']    ).",
             WORK_ORDER_NOTES_DATE          =". $db->qstr( time()                   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
        
    } else {
        
        // Get the new Note ID
        $note_id = $db->Insert_ID();
        
        // Creates a History record for the new work order
        //insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_function_insert_new_workorder'));
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_note').' '.$smarty->get_template_vars('translate_workorder_log_message_added').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Log activity
        //write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_note').' '.$note_id.' '.$smarty->get_template_vars('translate_workorder_log_message_added'));
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_note').' '.$note_id.' '.$smarty->get_template_vars('translate_workorder_log_message_added').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);
        
        return true;
        
    }
    
}

######################################
# Insert New Work Order History Note #
######################################

// this might be go in the main include as diffferent modules add work order history notes

function insert_workorder_history_note($db, $workorder_id, $workorder_history_note){
    
    global $smarty;
    
    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
        WORK_ORDER_ID   = " . $db->qstr( $workorder_id              ).",
        DATE            = " . $db->qstr( time()                     ).",
        NOTE            = " . $db->qstr( $workorder_history_note    ).",
        ENTERED_BY      = " . $db->qstr( $_SESSION['login_id']      );
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        update_workorder_last_active($db, $workorder_id);        
        return true;
        
    }  
    
}

/** Get Functions **/

########################################
#   Get single Workorder record        #
########################################

function get_workorder_details($db, $workorder_id, $item = null){
    
    global $smarty;

    $sql = "SELECT * FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetArray(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

/** Update Functions **/

###########################################
# Update Work Order Scope and Description #
###########################################

function update_workorder_scope_and_description($db, $workorder_id, $workorder_scope, $workorder_description){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_description);

    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_SCOPE        =".$db->qstr( $workorder_scope          ).",
            WORK_ORDER_DESCRIPTION  =".$db->qstr( $workorder_description    ).",
            LAST_ACTIVE             =".$db->qstr( time()                    )."
            WHERE WORK_ORDER_ID     =".$db->qstr( $workorder_id             );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Creates a History record
        //insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_function_update_workorder_scope_and_description'));
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_scope_and_description').' '.$smarty->get_template_vars('translate_workorder_log_message_updated').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Log activity
        //write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_scope_and_description').' '.$smarty->get_template_vars('translate_workorder_log_message_has_been_updated'));
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_scope_and_description').' '.$smarty->get_template_vars('translate_workorder_log_message_updated').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }
    
}

##################################
#   Update Workorder Comments    #
##################################

function update_workorder_comments($db, $workorder_id, $workorder_comments){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_comments);

    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_COMMENT              =".$db->qstr( $workorder_comments   ).",
            LAST_ACTIVE                     =".$db->qstr( time()                )."
            WHERE WORK_ORDER_ID             =".$db->qstr( $workorder_id         );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Create a History record
        //insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_function_update_workorder_comments'));   
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_comments').' '.$smarty->get_template_vars('translate_workorder_log_message_updated').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Log activity
        //write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_comments').' '.$smarty->get_template_vars('translate_workorder_log_message_has_been_updated'));
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_comments').' '.$smarty->get_template_vars('translate_workorder_log_message_updated').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }
    
}

################################
# Update Work Order Resolution #
################################

function update_workorder_resolution($db, $workorder_id, $workorder_resolution){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_resolution);

    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_RESOLUTION   = " . $db->qstr( $workorder_resolution ).",
            LAST_ACTIVE             = " . $db->qstr( time()                )."
            WHERE  WORK_ORDER_ID    = " . $db->qstr( $workorder_id         );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Create a History record
        //insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_function_update_workorder_resolution'));
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_resolution').' '.$smarty->get_template_vars('translate_workorder_log_message_updated').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Log activity
        //write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_resolution').' '.$smarty->get_template_vars('translate_workorder_log_message_has_been_updated'));
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_resolution').' '.$smarty->get_template_vars('translate_workorder_log_message_updated').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
            
    }
    
}

############################
# Update Workorder Status  #
############################

function update_workorder_status($db, $workorder_id, $assign_status){
    
    global $smarty;

    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_STATUS       = " . $db->qstr( $assign_status     ).",
            LAST_ACTIVE             = " . $db->qstr( time()             )."
            WHERE WORK_ORDER_ID     = " . $db->qstr( $workorder_id      );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
        
    } else {
        
        if ($assign_status == '0'){
            
            $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET 
                    WORK_ORDER_STATUS       = '1',
                    WORK_ORDER_ASSIGN_TO    = '0'                    
                    WHERE WORK_ORDER_ID     = " . $workorder_id;
            
            if(!$rs = $db->Execute($sql)) {
                force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_unassigned'));
                exit;
            }
            
        }
    
        // for writing message to log file - this needs translating
        if($assign_status == '1') {$wo_status = $smarty->get_template_vars('translate_workorder_created');
        } elseif ($assign_status == '2') {$wo_status = $smarty->get_template_vars('translate_workorder_assigned');   
        } elseif ($assign_status == '3') {$wo_status = $smarty->get_template_vars('translate_workorder_waiting_for_parts');
        } elseif ($assign_status == '6') {$wo_status = $smarty->get_template_vars('translate_workorder_closed'); 
        } elseif ($assign_status == '7') {$wo_status = $smarty->get_template_vars('translate_workorder_waiting_for_payment');
        } elseif ($assign_status == '8') {$wo_status = $smarty->get_template_vars('translate_workorder_payment_made');
        } elseif ($assign_status == '9') {$wo_status = $smarty->get_template_vars('translate_workorder_pending');
        } elseif ($assign_status == '10') {$wo_status = $smarty->get_template_vars('translate_workorder_open');    
        }
        
        // Create a History record
        //insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_function_update_status_work_order_status_changed_to'). ' ' . $wo_status . ' ' .$smarty->get_template_vars('translate_workorder_log_message_by_the_logged_in_user'));
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_status').' '.$smarty->get_template_vars('translate_workorder_log_message_updated').' '.$smarty->get_template_vars('translate_workorder_log_message_to').' '.$smarty->get_template_vars('translate_workorder_log_message_to').' '.$wo_status.' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Log activity
        //write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_status').' '.$smarty->get_template_vars('translate_workorder_log_message_has_been_changed_to').' '.$wo_status);
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_status').' '.$smarty->get_template_vars('translate_workorder_log_message_updated').' '.$smarty->get_template_vars('translate_workorder_log_message_to').' '.$wo_status.' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }
    
}

#################################
#    Update Last Active         #
#################################

function update_workorder_last_active($db, $workorder_id){
    
    global $smarty;
    
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET LAST_ACTIVE=".$db->qstr(time())." WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)) {    
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    }
    
}

/** Close Functions **/

#################################
# Close Workorder with Invoice  #
#################################

function close_workorder_with_invoice($db, $workorder_id, $workorder_resolution){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_resolution);

    /* Insert resolution and close information */
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_STATUS          = ". $db->qstr( 9                       ).",
            WORK_ORDER_CLOSE_DATE      = ". $db->qstr( time()                  ).",
            WORK_ORDER_RESOLUTION      = ". $db->qstr( $workorder_resolution   ).",
            WORK_ORDER_CLOSE_BY        = ". $db->qstr( $_SESSION['login_id']   ).",
            WORK_ORDER_ASSIGN_TO       = ". $db->qstr( $_SESSION['login_id']   )."             
            WHERE WORK_ORDER_ID        = ". $db->qstr( $workorder_id           );
    
    if(!$rs = $db->Execute($sql)){ 
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Create a History record
        //insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_function_close_workorder_with_invoice'));
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_closed_with_invoice').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Log activity
        //write_record_to_activity_log($smarty->get_template_vars('translateworkorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_has_been_closed_with_invoice'));
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_closed_with_invoice').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);        
        
        return true;
        
    }      
    
}

########################################
# Close Workorder without invoice      #
########################################

function close_workorder_without_invoice($db, $workorder_id, $workorder_resolution){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_resolution);

    /* Insert resolution and close information */
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_STATUS          = ". $db->qstr( 6                       ).",
            WORK_ORDER_CLOSE_DATE      = ". $db->qstr( time()                  ).",
            WORK_ORDER_RESOLUTION      = ". $db->qstr( $workorder_resolution   ).",
            WORK_ORDER_CLOSE_BY        = ". $db->qstr( $_SESSION['login_id']   ).",
            WORK_ORDER_ASSIGN_TO       = ". $db->qstr( $_SESSION['login_id']   )."             
            WHERE WORK_ORDER_ID        = ". $db->qstr( $workorder_id           );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Create a History record
        //insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_function_close_workorder_without_invoice'));
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_closed_without_invoice').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
        // Log activity
        //write_record_to_activity_log($smarty->get_template_vars('translateworkorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_has_been_closed_without_invoice>'));
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_closed_without_invoice').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);
        
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
    
    global $smarty;
    
    // Does the workorder have an invoice
    if(check_workorder_has_invoice($db, $workorder_id)) {        
        postEmulation('warning_msg', $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_hasinvoice'));
        return false;
    }
    
    // Is the workorder in an allowed state to be deleted
    if(!check_workorder_status_is_allowed_for_deletion($db, $workorder_id)) {        
        postEmulation('warning_msg', $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_statusnotallowed'));
        return false;
    }
    
    // Delete the workorder primary record
    $sql = "DELETE FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;        
    
    // Delete the workorder history
    } else {        
       
        $sql = "DELETE FROM ".PRFX."TABLE_WORK_ORDER_HISTORY WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
            exit;
            
        // Delete the workorder notes    
        } else {
            
            $sql = "DELETE FROM ".PRFX."TABLE_WORK_ORDER_NOTES WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);

            if(!$rs = $db->Execute($sql)) {
                force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
                exit;        
             
                
            // Delete the workorder schedule events     
            } else {

                $sql = "DELETE FROM ".PRFX."TABLE_SCHEDULE WHERE WORKORDER_ID=".$db->qstr($workorder_id);

                if(!$rs = $db->Execute($sql)) {
                    force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
                    exit;

                // Log the workorder deletion
                } else {

                    // Write the record to the access log                    
                    write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_deleted').' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '.$_SESSION['login_display_name']);

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
    
    global $smarty;
    
    $sql = "SELECT WORK_ORDER_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$workorder_id;
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        
        if($rs->fields['WORK_ORDER_STATUS'] == 1 || $rs->fields['WORK_ORDER_STATUS'] == 10) {
            
            return true;
            
        } else {             
            
            return false;
            
        }
        
    }
    
}

/** Other Functions **/

##################################
# Does workorder have an invoice #
##################################

function check_workorder_has_invoice($db, $workorder_id) {
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE WORKORDER_ID=".$workorder_id;
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    global $smarty;
    
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET WORK_ORDER_ASSIGN_TO=".$db->qstr($target_employee_id).",
            WORK_ORDER_STATUS=2
            WHERE WORK_ORDER_ID=".$db->qstr($workorder_id) ;
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Get Logged in Employee's Display Name
        $logged_in_employee_display_name = get_employee_display_name_by_id($db, $logged_in_employee_id);        
        
        // Get the Display Name of the currently Assigned Employee
        if($assigned_employee_id === '0'){
            $assigned_employee_display_name = $smarty->get_template_vars('translate_workorder_log_message_unassigned');            
        } else {
            $assigned_employee_display_name = get_employee_display_name_by_id($db, $assigned_employee_id);            
        }
        
        // Get the Display Name of the Target Employee
        $target_employee_display_name = get_employee_display_name_by_id($db, $target_employee_id);
        
        // Creates a History record
        insert_workorder_history_note($db, $workorder_id, $smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$smarty->get_template_vars('translate_workorder_log_message_has_been_assigned_to').' '.$target_employee_display_name.' '.$smarty->get_template_vars('translate_workorder_log_message_from').' '.$assigned_employee_display_name.' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '. $logged_in_employee_display_name);

        // Log activity
        write_record_to_activity_log($smarty->get_template_vars('translate_workorder_log_message_work_order').' '.$workorder_id.' '.$smarty->get_template_vars('translate_workorder_log_message_has_been_assigned_to').' '.$target_employee_display_name.' '.$smarty->get_template_vars('translate_workorder_log_message_from').' '.$assigned_employee_display_name.' '.$smarty->get_template_vars('translate_workorder_log_message_by').' '. $logged_in_employee_display_name);

        return true;
        
    }
    
 }

################################
# Resolution Edit Status Check #
################################

function resolution_edit_status_check($db, $workorder_id) {
    
    global $smarty;
    
    $sql = "SELECT WORK_ORDER_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
        
    } else {        
    
        // waiting for parts
        if ($rs->fields['WORK_ORDER_STATUS'] == 3) {           
            
            postEmulation('warning_msg', $smarty->get_template_vars('translate_workorder_advisory_message_function_resolution_edit_status_check_waitingforparts'));
            return false;
            
        }
        
        // closed
        if($rs->fields['WORK_ORDER_STATUS'] == 6) {
            
            postEmulation('warning_msg', $smarty->get_template_vars('translate_workorder_advisory_message_function_resolution_edit_status_check_workorderalreadyclosed'));
            return false;
        }
        
        return true;        
       
    }
   
}

###############################################
#      Check if a workorder is open           #  //this can be partial repalces with the get function
###############################################

function check_workorder_is_open($db, $workorder_id) {
       
    if(!$workorder_id){return false;}
    
    $sql = "SELECT WORK_ORDER_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
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