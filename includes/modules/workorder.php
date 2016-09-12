<?php

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

/** Mandatory Code **/

/** Display Functions **/

####################################
# Display a single open work order #
####################################

// this returns all the relevant data for a single work order from the different database sections of QWcrm

function display_single_open_workorder($db, $wo_id){
    
    global $smarty;
    
     $sql = "SELECT ".PRFX."TABLE_WORK_ORDER.*,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_DISPLAY_NAME,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ADDRESS,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_CITY,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_STATE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ZIP,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_WORK_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_MOBILE_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_EMAIL,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_TYPE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_FIRST_NAME,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_LAST_NAME,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_WWW,
            ".PRFX."TABLE_CUSTOMER.DISCOUNT,
            ".PRFX."TABLE_CUSTOMER.CREDIT_TERMS,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_NOTES,
            ".PRFX."TABLE_CUSTOMER.CREATE_DATE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_EMAIL,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_TYPE,   
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_WORK_PHONE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_HOME_PHONE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_MOBILE_PHONE,
            ".PRFX."TABLE_SCHEDULE.SCHEDULE_START,
            ".PRFX."TABLE_SCHEDULE.SCHEDULE_END,
            ".PRFX."TABLE_SCHEDULE.SCHEDULE_NOTES
             FROM ".PRFX."TABLE_WORK_ORDER
             LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID           = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
             LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO  = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
             LEFT JOIN ".PRFX."TABLE_SCHEDULE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID         = ".PRFX."TABLE_SCHEDULE.WORK_ORDER_ID
             WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID =".$db->qstr($wo_id)." LIMIT 1";

    if(!$result = $db->Execute($sql)) {        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        $single_workorder_array = $result->GetArray();
        
        if(empty($single_workorder_array)) {
            force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_notfound'));
            exit;            
        } else {
            return $single_workorder_array;
        }
    }
}

#####################################################
# Display all open Work orders for the given status #
#####################################################

function display_workorders($db, $page_no, $status){
    
    global $smarty;
    
    $max_results = 5;
    
    $from = (($page_no * $max_results) - $max_results);
 
    $results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS =".$db->qstr($status));
                                                  
    $where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr($status);    
    
    $total_results = $results->FetchRow();
    
    $total_pages = ceil($total_results['Num'] / $max_results);
    
    if($page_no > 1){
        $prev = ($page_no - 1);
        $smarty->assign('previous', $prev);
    } 

    if($page_no < $total_pages){
        $next = ($page_no + 1);
        $smarty->assign('next', $next);
    }    
    
    $sql = "SELECT 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID, 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_OPEN_DATE,
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO,
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_SCOPE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_DISPLAY_NAME,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ADDRESS,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_CITY,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_STATE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ZIP,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_WORK_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_MOBILE_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_EMAIL,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_TYPE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_FIRST_NAME,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_LAST_NAME,
            ".PRFX."TABLE_CUSTOMER.DISCOUNT,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_WORK_PHONE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_HOME_PHONE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_MOBILE_PHONE,
            ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS
            FROM ".PRFX."TABLE_WORK_ORDER
            LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID                            = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO                   = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
            LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS    = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID
            ".$where." GROUP BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID ORDER BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID DESC";
     
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
    
        $workorders_array = $result->GetArray();

        if(empty($workorders_array)) {
            force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_notfound'));
            exit;
        } else {
            return $workorders_array;
        }
    }
}

##################################
# Display all Closed Work Orders #
##################################

function display_closed($db, $page_no) {
    
    global $smarty;   
    
    // Define the number of results per page
    $max_results = 25;
    
    // Figure out the limit for the Execute based on the current page number.
    $from = (($page_no * $max_results) - $max_results);  
    
    // Grab closed workorders by employee and return an array
    $sql = "SELECT 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID, 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_OPEN_DATE,
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO,
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_SCOPE, 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_DATE,
            ".PRFX."TABLE_CUSTOMER.*, 
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID, 
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME, 
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_WORK_PHONE, 
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_HOME_PHONE, 
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_MOBILE_PHONE, 
            ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS
            FROM ".PRFX."TABLE_WORK_ORDER
            LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
            LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID
            WHERE WORK_ORDER_STATUS=".$db->qstr(6)." GROUP BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID ORDER BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID DESC LIMIT $from, $max_results";    
    
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        $work_order = $rs->GetArray();
        
        if(empty($work_order)) {
            force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_notfound'));
            exit;
        }        
    }
    
    /* Other stuff */

    // Figure out the total number of closed work orders in the database 
    $q = "SELECT COUNT(*) as Num FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS=".$db->qstr(6);
    
    if(!$results = $db->Execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_count'));
        exit;
    } else {        
        $total_results = $results->FetchRow();          
        $smarty->assign('total_results', $total_results['Num']);
    }    
    
    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results['Num'] / $max_results); 
    $smarty->assign('total_pages', $total_pages);
    
    // Assign the first page
    if($page_no > 1) {
        $prev = ($page_no - 1);     
    }     

    // Build Next Link
    if($page_no < $total_pages){
        $next = ($page_no + 1); 
    }

    // Assign Smarty Variables
    $smarty->assign('name', $name);
    $smarty->assign('page_no', $page_no);
    $smarty->assign('previous', $prev);    
    $smarty->assign('next', $next);
    
    return $work_order;
}

######################
# Display Resolution #
######################

function display_resolution($db, $wo_id){
    
    global $smarty;
    
    $q = "SELECT ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_BY, 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_RESOLUTION, 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_DATE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID, 
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME
            FROM ".PRFX."TABLE_WORK_ORDER
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_BY = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
            WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID=".$db->qstr($wo_id);

    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        return $rs->GetArray();        
    }
}

################################
# Display Customer Details     #
################################

function display_customer_info($db, $customer_id){
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
       return $result->GetArray();    
    }     
}

###############################
# Display Work Order Schedule #
###############################

function display_workorder_schedule($db, $wo_id){
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."TABLE_SCHEDULE WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        return $rs->GetArray();        
    }
}

#############################
# Display Work Order Notes  #
#############################

function display_workorder_notes($db, $wo_id){
    
    global $smarty;
    
    $sql = "SELECT ".PRFX."TABLE_WORK_ORDER_NOTES.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME
            FROM ".PRFX."TABLE_WORK_ORDER_NOTES, ".PRFX."TABLE_EMPLOYEE
            WHERE WORK_ORDER_ID=".$db->qstr($wo_id)."
            AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_NOTES.WORK_ORDER_NOTES_ENTER_BY ";
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $result->GetArray();        
    }
 }

#########################################
# Display Parts                         #
#########################################

function display_parts($db, $wo_id) {
    
    global $smarty;
    
    $q = "SELECT * FROM ".PRFX."ORDERS WHERE  WO_ID=".$db->qstr($wo_id);
    
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->GetArray();  
    }
}

##############################
# Display Work Order History #
##############################

function display_workorder_history($db, $wo_id){
    
    global $smarty;
    
    $sql = "SELECT ".PRFX."TABLE_WORK_ORDER_HISTORY.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
            FROM ".PRFX."TABLE_WORK_ORDER_HISTORY, ".PRFX."TABLE_EMPLOYEE 
            WHERE ".PRFX."TABLE_WORK_ORDER_HISTORY.WORK_ORDER_ID=".$db->qstr($wo_id)." 
            AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_HISTORY.ENTERED_BY ORDER BY ".PRFX."TABLE_WORK_ORDER_HISTORY.HISTORY_ID";
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $result->GetArray();   
    }
}

###################################
# Display Work Order Status Types #
###################################

/*
 * This is currently NOT used
 * 
 * This creates an array of WO status types agaisnt the types ID number
 * This could be used on the status.tpl for the 'New Status:' option to build the form list
 */

function display_status_types($db){

    $sql = "SELECT * FROM ".PRFX."CONFIG_WORK_ORDER_STATUS WHERE DISPLAY='1'";
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        while($row = $result->FetchRow()){
            $status_id                  = $row["CONFIG_WORK_ORDER_STATUS_ID"];
            $status                     = $row["CONFIG_WORK_ORDER_STATUS"];
            $status_array[$status_id]   = $status;
        }
        
        return $status_array;
        
    }
}

/** Insert New Functions **/

#########################
# Insert New Work Order #
#########################

function insert_new_workorder($db, $customer_id, $created_by, $scope, $workorder_description, $workorder_comments, $workorder_note){
    
    global $smarty;

    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_description);
    stripslashes($workorder_comments);
    stripslashes($workorder_note);

    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER SET 
            CUSTOMER_ID                                 = " . $db->qstr( $customer_id           ).",
            WORK_ORDER_OPEN_DATE                        = " . $db->qstr( time()                 ).",
            WORK_ORDER_STATUS                           = " . $db->qstr( 10                     ).",
            WORK_ORDER_CURRENT_STATUS                   = " . $db->qstr( 1                      ).",
            WORK_ORDER_CREATE_BY                        = " . $db->qstr( $created_by            ).",
            WORK_ORDER_SCOPE                            = " . $db->qstr( $scope                 ).",
            WORK_ORDER_DESCRIPTION                      = " . $db->qstr( $workorder_description ).",
            LAST_ACTIVE                                 = " . $db->qstr( time()                 ) .",
            WORK_ORDER_COMMENT                          = " . $db->qstr( $workorder_comments    );

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {

        $wo_id = $db->Insert_ID();

        // Creates a History record for the new work order
        insert_new_workorder_history_note($db, $wo_id, $smarty->get_template_vars('workorder_log_message_function_insert_new_workorder'));

        // If a submitted note is not empty
        if(!empty($workorder_note)){        
            insert_new_note($db, $wo_id, $workorder_note);
        }

        // redirects to the new Work Order page
        force_page('workorder', 'details', 'wo_id='.$wo_id.'&customer_id='.$customer_id.'&page_title='.$smarty->get_template_vars('translate_workorder_details_title'));
        exit;
    }
}

###################
# Insert New Note #
###################

function insert_new_note($db, $wo_id, $workorder_note){
    
    global $smarty;

    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_note);

    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_NOTES SET 
             WORK_ORDER_ID                  =". $db->qstr( $wo_id                   ).",
             WORK_ORDER_NOTES_DESCRIPTION   =". $db->qstr( $workorder_note          ).",
             WORK_ORDER_NOTES_ENTER_BY      =". $db->qstr( $_SESSION['login_id']    ).",
             WORK_ORDER_NOTES_DATE          =". $db->qstr( time()                   );

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        update_last_active($db, $wo_id);
        force_page('workorder', 'details', 'wo_id='.$wo_id.'&page_title='.$smarty->get_template_vars('translate_workorder_work_order_id').' '.$wo_id);
        exit;
    }
}

######################################
# Insert New Work Order History Note #
######################################

// this might be go in the main include as diffferent modules add work order history notes

function insert_new_workorder_history_note($db, $wo_id, $workorder_history_note){
    
    global $smarty;
    
    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
        WORK_ORDER_ID   = " . $db->qstr( $wo_id                     ).",
        DATE            = " . $db->qstr( time()                     ).",
        NOTE            = " . $db->qstr( $workorder_history_note    ).",
        ENTERED_BY      = " . $db->qstr( $_SESSION['login_id']      );
    
    if(!$result = $db->Execute($sql)) {        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        update_last_active($db, $wo_id);        
        return true;        
    }  
}
/** Get Functions **/

########################################
# Get Work Order Scope and Description #
########################################

function get_workorder_scope_and_description($db, $wo_id){
    
    global $smarty;
    
    $q = "SELECT WORK_ORDER_DESCRIPTION, WORK_ORDER_SCOPE FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
    
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {            
        return $rs;
    }
}

###########################
# Get Work Order Comments #
###########################

function get_workorder_comments($db, $wo_id){
    
    global $smarty;
    
    $q = "SELECT WORK_ORDER_COMMENT FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
    
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['WORK_ORDER_COMMENT'];
    }   
}

#############################
# Get Work Order Resolution #
#############################

function get_workorder_resolution($db, $wo_id){
    
    global $smarty;
    
    $q = "SELECT WORK_ORDER_RESOLUTION FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
    
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['WORK_ORDER_RESOLUTION'];        
    }
}

#####################################
# Get Employee Display Name from ID #
#####################################

// this perhaps should be in employee

function get_employee_display_name_by_id($db, $employee_id){
    
    global $smarty;
    
    $q = "SELECT ".PRFX."TABLE_EMPLOYEE.*, ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_NAME
            FROM ".PRFX."TABLE_EMPLOYEE
            LEFT JOIN ".PRFX."CONFIG_EMPLOYEE_TYPE ON (".PRFX."TABLE_EMPLOYEE.EMPLOYEE_TYPE = ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_ID)
            WHERE EMPLOYEE_ID=". $db->qstr($employee_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        $employee_array = $rs->GetArray();
        return $employee_array['0']['EMPLOYEE_DISPLAY_NAME'];
    }
}

/** Update Functions **/

###########################################
# Update Work Order Scope and Description #
###########################################

function update_workorder_scope_and_description($db, $wo_id, $workorder_scope, $workorder_description){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_description);

    $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_SCOPE        =".$db->qstr( $workorder_scope          ).",
            WORK_ORDER_DESCRIPTION  =".$db->qstr( $workorder_description    ).",
            LAST_ACTIVE             =".$db->qstr( time()                    )."
            WHERE WORK_ORDER_ID     =".$db->qstr( $wo_id                    );

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        // Add History Note
        insert_new_workorder_history_note($db, $wo_id, $smarty->get_template_vars('translate_workorder_log_message_function_update_workorder_scope_and_description'));
        force_page('workorder', 'details','wo_id='.$wo_id);
        exit;
    }
}

##################################
#   Update Work Order Comments   #
##################################

function update_workorder_comments($db, $wo_id, $workorder_comments){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_comments);

    $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
        WORK_ORDER_COMMENT              =".$db->qstr( $workorder_comments   ).",
        LAST_ACTIVE                     =".$db->qstr( time()                )."
        WHERE WORK_ORDER_ID             =".$db->qstr( $wo_id                );

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        insert_new_workorder_history_note($db, $wo_id, $smarty->get_template_vars('translate_workorder_log_message_function_update_workorder_comments'));  
        force_page('workorder', 'details','wo_id='.$wo_id);
        exit;    
    }
}

################################
# Update Work Order Resolution #
################################

function update_workorder_resolution($db, $wo_id, $workorder_resolution){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_resolution);

    $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_RESOLUTION   = " . $db->qstr( $workorder_resolution ).",
            LAST_ACTIVE             = " . $db->qstr( time()                )."
            WHERE  WORK_ORDER_ID    = " . $db->qstr( $wo_id                );

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        insert_new_workorder_history_note($db, $wo_id, $smarty->get_template_vars('translate_workorder_log_message_function_update_workorder_resolution'));
        force_page('workorder', 'details', 'wo_id='.$wo_id);
        exit;     
    }
}

############################
# Update Work Order Status #
############################

function update_status($db, $wo_id, $assign_status){
    
    global $smarty;

    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_CURRENT_STATUS       = " . $db->qstr( $assign_status     ).",
            LAST_ACTIVE                     = " . $db->qstr( time()             )."
            WHERE WORK_ORDER_ID             = " . $db->qstr( $wo_id             );

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        if ($assign_status == '0'){
            
            $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET 
                    WORK_ORDER_CURRENT_STATUS       = '1',
                    WORK_ORDER_ASSIGN_TO            = '0'                    
                    WHERE WORK_ORDER_ID             = " . $wo_id;
            
            if(!$result = $db->Execute($sql)) {
                force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_unassigned'));
                exit;
            }
        }
    
        // for writing message to log file - this needs translating
        if($assign_status == '1') {
            $wo_status = $smarty->get_template_vars('translate_workorder_created');
        } elseif ($assign_status == '2') {
            $wo_status = $smarty->get_template_vars('translate_workorder_assigned');   
        } elseif ($assign_status == '3') {
            $wo_status = $smarty->get_template_vars('translate_workorder_waiting_for_parts');
        } elseif ($assign_status == '6' ){
            $wo_status = $smarty->get_template_vars('translate_workorder_closed'); 
        } elseif ($assign_status == '7') {
            $wo_status = $smarty->get_template_vars('translate_workorder_waiting_for_payment');
        } elseif ($assign_status == '8') {
            $wo_status = $smarty->get_template_vars('translate_workorder_payment_made');
        } elseif ($assign_status == '9') {
            $wo_status = $smarty->get_template_vars('translate_workorder_pending');
        } elseif ($assign_status == '10') {
            $wo_status = $smarty->get_template_vars('translate_workorder_open');    
        }

        $workorder_history_note = $smarty->get_template_vars('translate_workorder_log_message_function_update_status_work_order_status_changed_to'). ' ' . $wo_status . ' ' .$smarty->get_template_vars('translate_workorder_log_message_by_the_logged_in_user');    
        insert_new_workorder_history_note($db, $wo_id, $workorder_history_note);
        force_page('workorder', 'details','wo_id='.$wo_id.'&page_title='.$smarty->get_template_vars('translate_workorder_work_order_id').' '.$wo_id);
        exit;  
    }
}

#################################
#    Update Last Active         #
#################################

function update_last_active($db, $wo_id){
    
    global $smarty;
    
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET LAST_ACTIVE=".$db->qstr(time())." WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
    
    if(!$rs = $db->execute($sql)) {    
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return;
    }
}

/** Close Functions **/

#################################
# Close Work Order with Invoice #
#################################

function close_workorder_with_invoice($db, $wo_id, $workorder_resolution){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_resolution);

    /* Insert resolution and close information */
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
             WORK_ORDER_STATUS          = '9',
             WORK_ORDER_CLOSE_DATE      = ". $db->qstr( time()                  ).",
             WORK_ORDER_RESOLUTION      = ". $db->qstr( $workorder_resolution   ).",
             WORK_ORDER_CLOSE_BY        = ". $db->qstr( $_SESSION['login_id']   ).",
             WORK_ORDER_ASSIGN_TO       = ". $db->qstr( $_SESSION['login_id']   ).",
             WORK_ORDER_CURRENT_STATUS  = ". $db->qstr( 7                       )."
             WHERE WORK_ORDER_ID        = ". $db->qstr( $wo_id                  );
    
    if(!$result = $db->Execute($sql)){ 
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        $q = "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
        
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_customerid'));
            exit;
        }
        $customer_id = $rs->fields['CUSTOMER_ID'];
        
        insert_new_workorder_history_note($db, $wo_id, $smarty->get_template_vars('translate_workorder_log_message_function_close_workorder_with_invoice'));
        force_page('invoice', 'new','wo_id='.$wo_id.'&customer_id='.$customer_id.'&page_title='.$smarty->get_template_vars('translate_workorder_details_edit_resolution_create_invoice_for_work_order_id').' '.$wo_id);
        exit;
    }
}

########################################
# Close Work Order without invoice     #
########################################

function close_workorder_without_invoice($db, $wo_id, $workorder_resolution){
    
    global $smarty;
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_resolution);

    /* Insert resolution and close information */
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
             WORK_ORDER_STATUS          = '6',
             WORK_ORDER_CLOSE_DATE      = ". $db->qstr( time()                  ).",
             WORK_ORDER_RESOLUTION      = ". $db->qstr( $workorder_resolution   ).",
             WORK_ORDER_CLOSE_BY        = ". $db->qstr( $_SESSION['login_id']   ).",
             WORK_ORDER_ASSIGN_TO       = ". $db->qstr( $_SESSION['login_id']   ).",
             WORK_ORDER_CURRENT_STATUS  = ". $db->qstr( 6                       )."
             WHERE WORK_ORDER_ID        = ". $db->qstr( $wo_id                  );
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        insert_new_workorder_history_note($db, $wo_id, $smarty->get_template_vars('translate_workorder_log_message_function_close_workorder_without_invoice'));
        force_page('workorder', 'details','wo_id='.$wo_id);
        exit;         
    }
}

/** Delete Work Orders **/

#####################
# Delete Work Order #
#####################

function delete_work_order($db, $wo_id, $assigned_employee) {
    
    global $smarty;
    
    $sql = "DELETE FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Record to add
        $record = $smarty->get_template_vars('translate_workorder_log_message_work_order') . ' ' . $wo_id . ' ' .$smarty->get_template_vars('translate_workorder_log_message_function_delete_work_order_has_been_deleted') . ', ' . $assigned_employee;

        // Write the record to the access log file 
        write_record_to_activity_log($record);

        // Redirect to the Open Work Orders Page
        force_page('workorder', 'open','page_title='.$smarty->get_template_vars('translate_workorder_open_title'));
        exit;    
    }
}

/** Other Functions **/

#########################################
# Assign Work Order to another employee #
#########################################

function assign_work_order_to_employee($db, $wo_id, $logged_in_employee_id, $assigned_employee_id ,$target_employee_id){
    
    global $smarty;
    
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET WORK_ORDER_ASSIGN_TO=".$db->qstr($target_employee_id).",
            WORK_ORDER_CURRENT_STATUS=2
            WHERE WORK_ORDER_ID=".$db->qstr($wo_id) ;
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
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

        // Record to add to log
        $record = $smarty->get_template_vars('translate_workorder_log_message_work_order') . ' ' . $wo_id . ' ' . $smarty->get_template_vars('translate_workorder_log_message_has_been_assigned_to') . ' ' . $target_employee_display_name . ' ' . $smarty->get_template_vars('translate_workorder_log_message_from') . ' ' . $assigned_employee_display_name . ' ' . $smarty->get_template_vars('translate_workorder_log_message_by') . ' ' . $logged_in_employee_display_name;

        // Write the record to the access log file 
        write_record_to_activity_log($record);

        // Redirect to the Open Work Orders Page
        force_page('workorder', 'open','page_title='.$smarty->get_template_vars('translate_workorder_open_title'));
        exit;       
    }
 }

##############################################
#   Build an active employee <option> list   #
##############################################

/*
 * This utilises the ADODB PHP Framework for building a <option> list from the supplied data set.
 * 
 * Build <option></option> list for a <form></form> to select employee for 'Assign To' feature
 * GetMenu2('assign_employee_val, null, false') will turn off the blank option
 * GetMenu2('dataset values', 'default option', 'blank 1st record' )
 * 
 * The assigned employee is the default option selected
 * 
 */

function build_active_employee_form_option_list($db, $assigned_employee_id){
    
    global $smarty;
    
    // select all employees and return their display name and ID as an array
    $sql = "SELECT EMPLOYEE_DISPLAY_NAME, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        // Get ADODB to build the form using the loaded dataset $rs
        return $rs->GetMenu2('assign_employee_val', $assigned_employee_id, false );       
    }
}

################################
# Resolution Edit Status Check #
################################

function resolution_edit_status_check($db, $wo_id){
    
    global $smarty;
    
    $q = "SELECT WORK_ORDER_STATUS,WORK_ORDER_CURRENT_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
    
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        if($rs->fields['WORK_ORDER_STATUS'] == 9) {
           force_page('workorder', 'details','warning_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_function_resolution_edit_status_check_workorderalreadyclosed').'&wo_id='.$wo_id.'&page_title='.$smarty->get_template_vars('translate_workorder_work_order_id').' '.$wo_id);
           //force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=warning&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_workorderalreadyclosed'));
           exit;
       } elseif ($rs->fields['WORK_ORDER_CURRENT_STATUS'] == 3) {
           force_page('workorder', 'details','warning_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_function_resolution_edit_status_check_waitingforparts').'&wo_id='.$wo_id.'&page_title='.$smarty->get_template_vars('translate_workorder_work_order_id').' '.$wo_id);      
           //force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=warning&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_waitingforparts'));
           exit;
       }          
    }
    
    return;
    
}