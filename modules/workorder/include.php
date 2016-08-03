<?php

#####################################
# Load language translations        #
#####################################

if(!xml2php('workorder')) {
    $smarty->assign('error_msg', $smarty->get_template_vars('translate_workorder_error_message_error_in_language_file'));
}

#####################################
# Display a single open work order  #
#####################################

// this returns all the relevant data for a single work order from the different database sections of myticrm

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
        force_page('core', 'error&error_msg=' . $smarty->get_template_vars('translate_workorder_error_message_mysql_error') . ': ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $single_workorder_array = $result->GetArray();
        if(empty($single_workorder_array)) {
            force_page('core', 'error&menu=1&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_the_work_order_you_requested_was_not_found').'&type=error');
            exit;
            //return false;
        } else {
            return $single_workorder_array;
        }
    }
}

#####################################
#  Display all open Work orders to  #
#####################################

function display_workorders($db, $page_no, $status){
    
    global $smarty;
    
    $max_results = 5;
    $from = (($page_no * $max_results) - $max_results);
 
    $results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS =".$db->qstr($status));
                                                  
    $where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr($status);    
    
    $total_results = $results->FetchRow();
    
    $total_pages = ceil($total_results["Num"] / $max_results);
    
    if($page_no > 1){
        $prev = ($page_no - 1);
        $smarty->assign("previous", $prev);
    } 

    if($page_no < $total_pages){
        $next = ($page_no + 1);
        $smarty->assign("next", $next);
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
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $workorders_array = $result->GetArray();
    if(empty($workorders_array)) {
        return false;
    } else {
        return $workorders_array;
    }
}

#############################
# Display Work Order Notes  #
#############################

function display_workorder_notes($db, $wo_id){
    $sql = "SELECT ".PRFX."TABLE_WORK_ORDER_NOTES.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_WORK_ORDER_NOTES, ".PRFX."TABLE_EMPLOYEE WHERE WORK_ORDER_ID=".$db->qstr($wo_id)." AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_NOTES.WORK_ORDER_NOTES_ENTER_BY ";
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    $work_order_notes = $result->GetArray();
    return $work_order_notes;
    
}

#############################
# Display Work Order Status #
#############################

function display_workorder_status($db, $wo_id){
    $sql = "SELECT ".PRFX."TABLE_WORK_ORDER_STATUS.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
            FROM ".PRFX."TABLE_WORK_ORDER_STATUS, ".PRFX."TABLE_EMPLOYEE 
            WHERE  ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_ID=".$db->qstr($wo_id)." 
            AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_STATUS_ENTER_BY ORDER BY ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_STATUS_ID";
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $work_order_status = $result->GetArray();
    return $work_order_status;
    
}
$smarty->assign('wo_stat', display_workorder_status($db, $wo_id));

#########################################
# Display Customer Contact Information  #
#########################################

function display_customer_info($db, $customer_id){
    $sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $customer_array = $result->GetArray();
    return $customer_array;
}

#############################################################
# Display all open Work orders for an employee              #
#############################################################

function display_tech($db){
    $sql = "SELECT  EMPLOYEE_ID, EMPLOYEE_LOGIN FROM ".PRFX."TABLE_EMPLOYEE"; 
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    while($row = $result->FetchRow()){
        $id = $row["EMPLOYEE_ID"];
        $tech = $row["EMPLOYEE_LOGIN"];        
        $tech_array[$id]=$tech;
    }
    return $tech_array;
}

#############################################################
# Insert New Work Order                                     #
#############################################################

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
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    $wo_id = $db->Insert_ID();    
    $workorder_status_note = "Work Order Created";
    
    // Creates initial work order status (history)
    insert_new_status($db, $wo_id, $workorder_status_note);
     
    // If a note is is present insert it
    if(!empty($workorder_note)){        
        insert_new_note($db, $wo_id, $workorder_note);
    }

    return $wo_id;
}

#############################################################
# Insert Work Order Status note (History)                   #
#############################################################

function insert_new_status($db, $wo_id, $workorder_status_note){
    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
        WORK_ORDER_ID               = " . $db->qstr( $wo_id                     ).",
        WORK_ORDER_STATUS_DATE      = " . $db->qstr( time()                     ).",
        WORK_ORDER_STATUS_NOTES     = " . $db->qstr( $workorder_status_note     ).",
        WORK_ORDER_STATUS_ENTER_BY  = " . $db->qstr( $_SESSION['login_id']      );
        
    if(!$result = $db->Execute($sql)) {        
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    update_last_active($db, $wo_id);
    return true;
}


########################################
# Add New Note (NOT History)           #
########################################

function insert_new_note($db, $wo_id, $workorder_note){

    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_note);

    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_NOTES SET 
             WORK_ORDER_ID                  =". $db->qstr( $wo_id                   ).",
             WORK_ORDER_NOTES_DESCRIPTION   =". $db->qstr( $workorder_note          ).",
             WORK_ORDER_NOTES_ENTER_BY      =". $db->qstr( $_SESSION['login_id']    ).",
             WORK_ORDER_NOTES_DATE          =". $db->qstr( time()                   );

        if(!$result = $db->Execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        } else {
            update_last_active($db, $wo_id);
            force_page('workorder', 'details&wo_id='.$wo_id.'&page_title=Work Order ID'.$wo_id);
            exit;
        }

    return true;
}

####################################################
# Update Work Order Status                         #
####################################################

function update_status($db, $wo_id, $assign_status){

    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_CURRENT_STATUS       = " . $db->qstr( $assign_status     ).",
            LAST_ACTIVE                     = " . $db->qstr( time()             )."
            WHERE WORK_ORDER_ID             = " . $db->qstr( $wo_id             );

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    if ($assign_status == '0'){
        $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
                    WORK_ORDER_CURRENT_STATUS       = '1',
                    WORK_ORDER_ASSIGN_TO            = '0'                    
                    WHERE WORK_ORDER_ID             = " . $wo_id    ;
        if(!$result = $db->Execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }
    }
    
    // for writing message to log file - this needs translating
    if($assign_status == '1') {
        $wo_status = "Created";
    } elseif ($assign_status == '2') {
        $wo_status = "Assigned";    
    } elseif ($assign_status == '3') {
        $wo_status = "Waiting For Parts";
    } elseif ($assign_status == '6' ){
        $wo_status = "Closed";
    } elseif ($assign_status == '7') {
        $wo_status = "Awaiting Payment";
    } elseif ($assign_status == '8') {
        $wo_status = "Payment Made";
    } elseif ($assign_status == '9') {
        $wo_status = "Pending";
    } elseif ($assign_status == '10') {
        $wo_status = "Open";    
    }
    
    $work_order_status_note = 'Work Order Changed status to ' . $wo_status . ' by the logged in user';
    
    insert_new_status($db, $wo_id, $workorder_status_note);
    
    force_page('workorder', 'details&wo_id='.$wo_id.'&page_title=Work Order ID '.$wo_id);
    exit;

}

########################################
# Display Status                       #
########################################

function display_status($db){

    $sql = "SELECT * FROM ".PRFX."CONFIG_WORK_ORDER_STATUS WHERE DISPLAY='1'";
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    while($row = $result->FetchRow()){
        $id                     = $row["CONFIG_WORK_ORDER_STATUS_ID"];
        $status                 = $row["CONFIG_WORK_ORDER_STATUS"];
        $status_array[$id]      = $status;
    }

    return $status_array; 
}

#########################################
# Display Parts                         #
#########################################

function display_parts($db, $wo_id) {
    $q = "SELECT * FROM ".PRFX."ORDERS WHERE  WO_ID=".$db->qstr($wo_id);
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    $arr = $rs->GetArray();
    return $arr;
}

##################################
# Display resolution             #
##################################

function display_resolution($db, $wo_id){
    $q = "SELECT ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_BY, 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_RESOLUTION, 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_DATE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID, 
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME
            FROM ".PRFX."TABLE_WORK_ORDER
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_BY = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
            WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID=".$db->qstr($wo_id);

    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        
        return $rs->GetArray();
        
    }
}

########################################
# get work order resolution resolution #
########################################

function get_workorder_resolution($db, $wo_id){
    $q = "SELECT WORK_ORDER_RESOLUTION FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr( $wo_id );
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        return $rs->fields['WORK_ORDER_RESOLUTION'];        
    }
}

##################################
# update resolution only         #
##################################

function update_workorder_resolution($db, $wo_id, $workorder_resolution){
    
    // Remove Extra Slashes caused by Magic Quotes    
    stripslashes($workorder_resolution);

    $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_RESOLUTION   = " . $db->qstr( $workorder_resolution ).",
            LAST_ACTIVE             = " . $db->qstr( time()                )."
            WHERE  WORK_ORDER_ID    = " . $db->qstr( $wo_id                );

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    $activity_log_msg = 'Resolution has been Updated';
    
    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
                WORK_ORDER_ID                 = " . $db->qstr( $wo_id                   ).",
                WORK_ORDER_STATUS_DATE        = " . $db->qstr( time()                   ).",
                WORK_ORDER_STATUS_NOTES       = " . $db->qstr( $activity_log_msg        ).",
                WORK_ORDER_STATUS_ENTER_BY    = " . $db->qstr( $_SESSION['login_id']    );

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    force_page('workorder', 'details&wo_id='.$wo_id);
    exit;
    
}


##############################################
#   Display all Closed Work Orders           #
##############################################

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
        force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_mysql_error').': '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $work_order = $rs->GetArray();
    }
    
    // Figure out the total number of closed work orders in the database 
    $q = "SELECT COUNT(*) as Num FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS=".$db->qstr(6);
    if(!$results = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
   
    if(!$total_results = $results->FetchRow()) {
        force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_mysql_error').': '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', $total_results['Num']);
    }
        
    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results); 
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
    $smarty->assign("previous", $prev);    
    $smarty->assign("next", $next);
    
    return $work_order;
}


###############################
# Get Work Order schedule     #
###############################

function display_work_order_schedule($db, $wo_id){
    $sql = "SELECT * FROM ".PRFX."TABLE_SCHEDULE WHERE WORK_ORDER_ID=".$db->qstr($wo_id); 
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    $schedule = $rs->GetArray();
    return $schedule;
}

#################################
#    Update Last Active         #
#################################

function update_last_active($db, $wo_id) {
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET LAST_ACTIVE=".$db->qstr(time())." WHERE WORK_ORDER_ID=".$db->qstr($wo_id);    
    if(!$rs = $db->execute($sql)) {    
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
}

#################################
#    Delete Work Order          #
#################################

function delete_work_order($db, $wo_id, $assigned_employee) {
    
    global $smarty;
    
    $sql = "DELETE FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id) ;
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_mysql_error').': '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    // Record to add
    $record = $smarty->get_template_vars('translate_workorder_log_message_work_order') . ' ' . $wo_id . ' ' .$smarty->get_template_vars('translate_workorder_log_message_has_been_deleted') . ', ' . $assigned_employee;
    
    // Write the record to the access log file 
    write_record_to_activity_log($record);

    // Redirect to the Open Work Orders Page
    force_page('workorder', 'open&page_title='.$smarty->get_template_vars('translate_workorder_open_title'));
    exit;
}

###############################################
#    Assign Work Order to another employee    #
###############################################

function assign_work_order_to_employee($db, $wo_id, $logged_in_employee_id, $assigned_employee_id ,$target_employee_id){
    
    //echo $assigned_employee_id; die;
    global $smarty;
    
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET WORK_ORDER_ASSIGN_TO=".$db->qstr($target_employee_id).", WORK_ORDER_CURRENT_STATUS=2 WHERE WORK_ORDER_ID=".$db->qstr($wo_id) ;
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=' . $smarty->get_template_vars('translate_workorder_error_message_mysql_error') . ': ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }    

    // Change Employee ID into Display Names.
    $logged_in_employee_display_name = get_employee_display_name_by_id($db, $logged_in_employee_id);
    if($assigned_employee_id === '0'){$assigned_employee_display_name = $smarty->get_template_vars('translate_workorder_log_message_unassigned');} else {$assigned_employee_display_name = get_employee_display_name_by_id($db, $assigned_employee_id);}
    $target_employee_display_name   = get_employee_display_name_by_id($db, $target_employee_id);    
    
    // Record to add
    $record = $smarty->get_template_vars('translate_workorder_log_message_work_order') . ' ' . $wo_id . ' ' . $smarty->get_template_vars('translate_workorder_log_message_has_been_assigned_to') . ' ' . $target_employee_display_name . ' ' . $smarty->get_template_vars('translate_workorder_log_message_from') . ' ' . $assigned_employee_display_name . ' ' . $smarty->get_template_vars('translate_workorder_log_message_by') . ' ' . $logged_in_employee_display_name;
    
    // Write the record to the access log file 
    write_record_to_activity_log($record);

    // Redirect to the Open Work Orders Page
    force_page('workorder', 'open&page_title='.$smarty->get_template_vars('translate_workorder_open_title'));
    exit;   
}

##############################################
#    Get Employee Display Name from ID       #
##############################################

function get_employee_display_name_by_id($db, $employee_id) {

    // was function display_employee_info($db, $employee_id)
    
    global $smarty;
    
    $q = "SELECT ".PRFX."TABLE_EMPLOYEE.*, ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_NAME  FROM ".PRFX."TABLE_EMPLOYEE
            LEFT JOIN ".PRFX."CONFIG_EMPLOYEE_TYPE ON (".PRFX."TABLE_EMPLOYEE.EMPLOYEE_TYPE = ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_ID)
            WHERE EMPLOYEE_ID=". $db->qstr($employee_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=' . $smarty->get_template_vars('translate_workorder_error_message_mysql_error') . ': ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $employee_array = $rs->GetArray();
    }

    //return $employee_array;
    return $employee_array['0']['EMPLOYEE_DISPLAY_NAME'];
    
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
    
    // select all employees and return their display name and ID as an array
    $sql = "SELECT EMPLOYEE_DISPLAY_NAME, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
    
    // i think $rs is ADODB
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_mysql_error').': '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    // Get ADODB to build the form using the loaded dataset $rs
    $employee_list = $rs->GetMenu2('assign_employee_val', $assigned_employee_id, false ); 
    
    return $employee_list;
       
}

###############################
#   Get Work Order Comments   #
###############################

function get_workorder_comments($db, $wo_id){
    $q = "SELECT WORK_ORDER_COMMENT FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr( $wo_id );
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    return $rs->fields['WORK_ORDER_COMMENT'];
}

##################################
#   Update Work Order Comments   #
##################################

function update_workorder_comments($db, $wo_id, $workorder_comments){
    
    // Remove Extra Slashes caused by Magic Quotes    
    $workorder_comments = stripslashes($workorder_comments);

    $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
        WORK_ORDER_COMMENT              =".$db->qstr( $workorder_comments   ).",
        LAST_ACTIVE                     =".$db->qstr( time()                )."
        WHERE WORK_ORDER_ID             =".$db->qstr( $wo_id                );

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } 

    $activity_log_msg = 'Comment has been Updated';
    
    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
        WORK_ORDER_ID                   =". $db->qstr( $wo_id                   ).",
        WORK_ORDER_STATUS_DATE          =". $db->qstr( time()                   ).",
        WORK_ORDER_STATUS_NOTES         =". $db->qstr( $activity_log_msg        ).",
        WORK_ORDER_STATUS_ENTER_BY      =". $db->qstr( $_SESSION['login_id']    );

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    force_page('workorder', 'details&wo_id='.$wo_id);
    exit;    
}

################################################
#   Get Work Order Scope and Description       #
################################################

function get_workorder_scope_and_description($db, $wo_id){
    $q = "SELECT WORK_ORDER_DESCRIPTION, WORK_ORDER_SCOPE FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr( $wo_id );
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        } else {
            
            return $rs;

        }
}

###############################################
#   Update Work Order Scope and Description   #
###############################################

function update_workorder_scope_and_description($db, $wo_id, $workorder_scope, $workorder_description){
    
    // Remove Extra Slashes caused by Magic Quotes    
    //stripslashes($workorder_description);

    $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_SCOPE        =".$db->qstr( $workorder_scope          ).",
            WORK_ORDER_DESCRIPTION  =".$db->qstr( $workorder_description    ).",
            LAST_ACTIVE             =".$db->qstr( time()                    )."
            WHERE WORK_ORDER_ID     =".$db->qstr( $wo_id                    );

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } 

    /* add history note */
    $msg = 'Description has been Updated';
    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
            WORK_ORDER_ID                 =". $db->qstr( $wo_id                  ).",
            WORK_ORDER_STATUS_DATE        =". $db->qstr( time()                  ).",
            WORK_ORDER_STATUS_NOTES       =". $db->qstr( $msg                    ).",
            WORK_ORDER_STATUS_ENTER_BY    =". $db->qstr( $_SESSION['login_id']   );
        
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    force_page('workorder', 'details&wo_id='.$wo_id);
    exit;    
}


########################################
# Close Work Order with invoice        #
########################################

function close_workorder_with_invoice($db, $wo_id, $workorder_resolution){
    
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
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=tttttMySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        //force_page('workorder', "details&wo_id=$wo_id&error_msg=Failed to Close Work Order.&page_title=Work Order ID $wo_id");
        exit;
    } else {
        $q = "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=nnnnnMySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }
        
        /* Update status notes */
        $workorder_status_note = 'Work Order has been closed and set to Awaiting Payment';
        insert_new_status($db, $wo_id, $workorder_status_note);
        
        $customer_id = $rs->fields['CUSTOMER_ID'];
        force_page('invoice', 'new&wo_id='.$wo_id.'&customer_id='.$customer_id.'&page_title=Create Invoice for Work Order# wo_id='.$wo_id);
    
    }
}

########################################
# Close Work Order with no invoice     #
########################################

function close_workorder_without_invoice($db, $wo_id, $workorder_resolution){
    
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
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        //force_page('workorder', "details&wo_id=$wo_id&error_msg=Failed to Close Work Order.&page_title=Work Order ID $wo_id");
        exit;
    }
    
    /* Update status notes */
    $work_order_status_notes = 'Work Order has been closed and No Invoice Required';
     
    insert_new_status($db, $wo_id, $workorder_status_note);
    
    force_page('workorder', 'details&wo_id='.$wo_id);
    exit;    

}

########################################
# Resolution Edit Status Check         #
########################################

function resolution_edit_status_check($db, $wo_id){
    $q = "SELECT WORK_ORDER_STATUS,WORK_ORDER_CURRENT_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    if($rs->fields['WORK_ORDER_STATUS'] == 9) {
        force_page('workorder', "details&wo_id=$wo_id&error_msg=Work Order Is already Closed. Please Create an Invoice.&page_title=Work Order ID $wo_id&type=info");
    } elseif ($rs->fields['WORK_ORDER_CURRENT_STATUS'] == 3) {
        force_page('workorder', "details&wo_id=$wo_id&error_msg=Can not close a work order if it is Waiting For Parts. Please Adjust the status.&page_title=Work Order ID $wo_id&type=warning");
    }    
}