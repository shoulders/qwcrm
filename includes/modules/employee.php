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

#####################################
#    display Search                 #
#####################################

function display_employees($db, $search_term, $page_no) {

    global $smarty;    
    
    // Define the number of results per page
    $max_results = 20;
    
    // Figure out the limit for the query based on the current page number. 
    $from = (($page_no * $max_results) - $max_results);    
    
    $sql = "SELECT ".PRFX."EMPLOYEE.*,
            ".PRFX."EMPLOYEE_ACCOUNT_TYPES.TYPE_NAME
            FROM ".PRFX."EMPLOYEE 
            LEFT JOIN ".PRFX."EMPLOYEE_ACCOUNT_TYPES ON (".PRFX."EMPLOYEE. EMPLOYEE_TYPE = ".PRFX."EMPLOYEE_ACCOUNT_TYPES.TYPE_ID)    
            WHERE EMPLOYEE_DISPLAY_NAME LIKE '%$search_term%'
            ORDER BY EMPLOYEE_DISPLAY_NAME";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        $employee_search_result = $rs->GetArray();        
    }    

    // Figure out the total number of results in DB: 
    $sql = "SELECT COUNT(*) as Num FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME LIKE '%$search_term%'";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        $total_results = $rs->fields['Num'];
        $smarty->assign('total_results', $total_results);       
    }
    
    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results / $max_results);
    
    // Assign Total number of pages
    $smarty->assign('total_pages', $total_pages);
    
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
    
    // Assign remaining variables
    $smarty->assign('employee_searchTerm', $search_term);
    $smarty->assign('page_no', $page_no);
    $smarty->assign('previous', $prev);
    $smarty->assign('next', $next);

    return $employee_search_result;
    
}

/** New/Insert Functions **/

#####################################
#    insert new Employee            #
#####################################

function insert_employee($db, $VAR){
    
    global $smarty;
    
    $sql = "INSERT INTO ".PRFX."EMPLOYEE SET
            EMPLOYEE_LOGIN          =". $db->qstr( $VAR['employee_usr']             ).",
            EMPLOYEE_PASSWD         =". $db->qstr( md5($VAR['employee_pwd'])        ).",
            EMPLOYEE_EMAIL          =". $db->qstr( $VAR['employee_email']           ).", 
            EMPLOYEE_FIRST_NAME     =". $db->qstr( $VAR['employee_firstName']       ).",
            EMPLOYEE_LAST_NAME      =". $db->qstr( $VAR['employee_lastName']        ).",
            EMPLOYEE_DISPLAY_NAME   =". $db->qstr( $VAR['employee_displayName']     ).",
            EMPLOYEE_ADDRESS        =". $db->qstr( $VAR['employee_address']         ).",
            EMPLOYEE_CITY           =". $db->qstr( $VAR['employee_city']            ).",
            EMPLOYEE_STATE          =". $db->qstr( $VAR['employee_state']           ).", 
            EMPLOYEE_ZIP            =". $db->qstr( $VAR['employee_zip']             ).",
            EMPLOYEE_TYPE           =". $db->qstr( $VAR['employee_type']            ).",                    
            EMPLOYEE_WORK_PHONE     =". $db->qstr( $VAR['employee_workPhone']       ).",
            EMPLOYEE_HOME_PHONE     =". $db->qstr( $VAR['employee_homePhone']       ).",
            EMPLOYEE_MOBILE_PHONE   =". $db->qstr( $VAR['employee_mobilePhone']     ).",
            EMPLOYEE_BASED          =". $db->qstr( $VAR['employee_based']           ).",
            EMPLOYEE_ACL            =". $db->qstr( $VAR['employee_acl']             ).",    
            EMPLOYEE_STATUS         =". $db->qstr( $VAR['employee_status']          );          
          
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $db->insert_id();        
        
    }
    
}

/** Get Functions **/

#####################################
#     Get Employee Details          #
#####################################

function get_employee_details($db, $employee_id, $item = null) {
    
    global $smarty;

    $sql = "SELECT * FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_ID =".$employee_id;
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetArray(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
        
}

#####################################
# Get Employee Display Name from ID #  // not actually used anywhere
#####################################

function get_employee_display_name_by_id($db, $employee_id) {
    
    global $smarty;
    
    $sql = "SELECT ".PRFX."EMPLOYEE.*, ".PRFX."EMPLOYEE_ACCOUNT_TYPES.TYPE_NAME
            FROM ".PRFX."EMPLOYEE
            LEFT JOIN ".PRFX."EMPLOYEE_ACCOUNT_TYPES ON (".PRFX."EMPLOYEE.EMPLOYEE_TYPE = ".PRFX."EMPLOYEE_ACCOUNT_TYPES.TYPE_ID)
            WHERE EMPLOYEE_ID=". $db->qstr($employee_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        

        return $rs->fields['EMPLOYEE_DISPLAY_NAME'];
        
    }
    
}

#########################################
# Get Employee ID by username           # // moved from core
#########################################

/* 
 * Not used in core anywhere
 * it was used for getting user specific stats in theme_header_block.php
 * $login_id / $login_usr is not set via the auth session
 * i will leave this here just for now
 * no longer needed as I stored the id in the session
 * 
 */

function get_employee_id_by_username($db, $employee_usr){
    
    global $smarty;
    
    $sql = 'SELECT EMPLOYEE_ID FROM '.PRFX.'EMPLOYEE WHERE EMPLOYEE_LOGIN ='.$db->qstr($employee_usr);    
    if(!$rs = $db->execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['EMPLOYEE_ID'];
        
    }
        
}

#########################################
# Get employee record by username       #
#########################################

function get_employee_record_by_username($db, $employee_usr){
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_LOGIN =".$db->qstr($employee_usr);    
    if(!$rs = $db->execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->FetchRow();
        
    }
    
}

##################################
# Get Employee Types             #
##################################

function get_employee_types($db) {
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."EMPLOYEE_ACCOUNT_TYPES";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }
    
}

##################################################
# Get all active employees display name and ID   #
##################################################
    
function get_active_employees($db) {
        
    global $smarty;
    
    $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_DISPLAY_NAME FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_STATUS=1";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {    
        
        return $rs->GetArray();    
        
    }
    
}

/** Update Functions **/

#########################
#   Update Employee     #
#########################

function update_employee($db, $employee_id, $VAR) {
    
    global $smarty;
    
        $set .="    SET
                    EMPLOYEE_LOGIN          =". $db->qstr( $VAR['employee_usr']             ).",";

    if($VAR['login_pwd'] != '') {
        $set .="    EMPLOYEE_PASSWD         =". $db->qstr( md5($VAR['employee_pwd'])        ).",";
    }

        $set .="    EMPLOYEE_EMAIL          =". $db->qstr( $VAR['employee_email']           ).", 
                    EMPLOYEE_FIRST_NAME     =". $db->qstr( $VAR['employee_firstName']       ).",
                    EMPLOYEE_LAST_NAME      =". $db->qstr( $VAR['employee_lastName']        ).",
                    EMPLOYEE_DISPLAY_NAME   =". $db->qstr( $VAR['employee_displayName']     ).",
                    EMPLOYEE_ADDRESS        =". $db->qstr( $VAR['employee_address']         ).",
                    EMPLOYEE_CITY           =". $db->qstr( $VAR['employee_city']            ).",
                    EMPLOYEE_STATE          =". $db->qstr( $VAR['employee_state']           ).", 
                    EMPLOYEE_ZIP            =". $db->qstr( $VAR['employee_zip']             ).",
                    EMPLOYEE_TYPE           =". $db->qstr( $VAR['employee_type']            ).",                    
                    EMPLOYEE_WORK_PHONE     =". $db->qstr( $VAR['employee_workPhone']       ).",
                    EMPLOYEE_HOME_PHONE     =". $db->qstr( $VAR['employee_homePhone']       ).",
                    EMPLOYEE_MOBILE_PHONE   =". $db->qstr( $VAR['employee_mobilePhone']     ).",
                    EMPLOYEE_BASED          =". $db->qstr( $VAR['employee_based']           ).",
                    EMPLOYEE_ACL            =". $db->qstr( $VAR['employee_acl']             ).",    
                    EMPLOYEE_STATUS         =". $db->qstr( $VAR['employee_status']          );

    $sql = "UPDATE ".PRFX."EMPLOYEE ". $set ." WHERE EMPLOYEE_ID= ".$db->qstr($employee_id);

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else{
        
        return true;
        
    }
    
}

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

#################################################
# Count Employee Work Orders for a given status #
#################################################

function count_employee_workorders_with_status($db, $employee_id, $workorder_status){
    
    global $smarty;
    
    $sql = "SELECT COUNT(*) AS EMPLOYEE_WORKORDER_STATUS_COUNT
            FROM ".PRFX."WORKORDER
            WHERE WORK_ORDER_ASSIGN_TO=".$db->qstr($employee_id)."
            AND WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
   } else {
       
       return $rs->fields['EMPLOYEE_WORKORDER_STATUS_COUNT'];
       
   }
   
}

###############################################
# Count Employee Invoices for a given status  #
###############################################

function count_employee_invoices_with_status($db, $employee_id, $invoice_status){
    
    global $smarty;
    
    $sql = "SELECT COUNT(*) AS EMPLOYEE_INVOICE_COUNT
            FROM ".PRFX."INVOICE
            WHERE IS_PAID=".$db->qstr($invoice_status)."
            AND EMPLOYEE_ID=".$db->qstr($employee_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
   } else {
       
       return $rs->fields['EMPLOYEE_INVOICE_COUNT'];
       
   }
   
}

##############################################
#   Build an active employee <option> list   #  // keep for reference
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
    $sql = "SELECT EMPLOYEE_DISPLAY_NAME, EMPLOYEE_ID FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_STATUS=1";
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Get ADODB to build the form using the loaded dataset
        return $rs->GetMenu2('assign_employee', $assigned_employee_id, false);
        
    }
    
}

#################################################
#    Check if Employee username already exists  #
#################################################

function check_employee_username_exists($db, $username, $current_username){
    
    global $smarty;
    
    // This prevents self-checking of the current username of the record being edited
    if ($username === $current_username) {return false;}
    
    $sql = "SELECT COUNT(*) AS num_users FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_LOGIN =". $db->qstr($username);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        if ($rs->fields['num_users'] >= 1) {
            
            $smarty->assign('warning_msg', 'The employees Username, '.$username.',  already exists! Please use a different one.');
            return true;
            
        } else {
            
            return false;
            
        }        
        
    } 
    
}