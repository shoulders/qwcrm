<?php

/* Display functions */

#####################################
#    Display Employee Info          #
#####################################

function display_single_employee($db, $employee_id) {
    
    global $smarty;

    $sql = "SELECT ".PRFX."TABLE_EMPLOYEE.*,
            ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_NAME
            FROM ".PRFX."TABLE_EMPLOYEE
            LEFT JOIN ".PRFX."CONFIG_EMPLOYEE_TYPE ON (".PRFX."TABLE_EMPLOYEE. EMPLOYEE_TYPE = ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_ID)
            WHERE EMPLOYEE_ID=". $db->qstr($employee_id);    

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }
    
}

################################################
# Display all open Work orders for an employee # not used
################################################

// this was taken from workorders/include.php and I could not find it used anywhere

function display_employee_info_version2($db){
    
    global $smarty;
    
    $sql = "SELECT  EMPLOYEE_ID, EMPLOYEE_LOGIN FROM ".PRFX."TABLE_EMPLOYEE";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else { 
    
        while($row = $rs->FetchRow()){
            $employee_id                    = $row["EMPLOYEE_ID"];
            $employee_login                 = $row["EMPLOYEE_LOGIN"];        
            $employee_array[$employee_id]   = $employee_login;
        }

        return $employee_array;
    
    }
    
}

#####################################
#    display Search                 #
#####################################

function display_employee_search($db, $name, $page_no) {

    global $smarty;
    
    $safe_name = strip_tags($name);    
    
    // Define the number of results per page
    $max_results = 50;
    
    // Figure out the limit for the query based on the current page number. 
    $from = (($page_no * $max_results) - $max_results);    
    
    $sql = "SELECT ".PRFX."TABLE_EMPLOYEE.*,
            ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_NAME
            FROM ".PRFX."TABLE_EMPLOYEE 
            LEFT JOIN ".PRFX."CONFIG_EMPLOYEE_TYPE ON (".PRFX."TABLE_EMPLOYEE. EMPLOYEE_TYPE = ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_ID)    
            WHERE EMPLOYEE_DISPLAY_NAME LIKE '%$safe_name%'
            ORDER BY EMPLOYEE_DISPLAY_NAME";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        $employee_search_result = $rs->GetArray();
        
    }
    

    // Figure out the total number of results in DB: 
    $sql = "SELECT COUNT(*) as Num FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME LIKE '$safe_name%'";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        $total_results = $rs->FetchRow();
        $smarty->assign('total_results', strip_tags($total_results['Num']));
    }
    
    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results['Num'] / $max_results); 
    
    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results['Num'] / $max_results); 
    $smarty->assign('total_pages', strip_tags($total_pages));
    
    // Assign the first page
    if($page_no > 1) {
        $prev = ($page_no - 1);         
    }     

    // Build Next Link
    if($page_no < $total_pages){
        $next = ($page_no + 1); 
    }
    
    $smarty->assign('name', strip_tags($name));
    $smarty->assign('page_no', strip_tags($page_no));
    $smarty->assign('previous', strip_tags($prev));
    $smarty->assign('next', strip_tags($next));

    return $employee_search_result;
    
}

/* New/Insert Functions  */

#####################################
#    insert new Employee            #
#####################################

function insert_new_employee($db, $employee_record){
    
    global $smarty;
    
    $sql = "INSERT INTO ".PRFX."TABLE_EMPLOYEE SET
            EMPLOYEE_LOGIN          =". $db->qstr( $employee_record['employee_usr']             ).",
            EMPLOYEE_PASSWD         =". $db->qstr( md5($employee_record['employee_pwd'])        ).",
            EMPLOYEE_EMAIL          =". $db->qstr( $employee_record['employee_email']           ).", 
            EMPLOYEE_FIRST_NAME     =". $db->qstr( $employee_record['employee_firstName']       ).",
            EMPLOYEE_LAST_NAME      =". $db->qstr( $employee_record['employee_lastName']        ).",
            EMPLOYEE_DISPLAY_NAME   =". $db->qstr( $employee_record['employee_displayName']     ).",
            EMPLOYEE_ADDRESS        =". $db->qstr( $employee_record['employee_address']         ).",
            EMPLOYEE_CITY           =". $db->qstr( $employee_record['employee_city']            ).",
            EMPLOYEE_STATE          =". $db->qstr( $employee_record['employee_state']           ).", 
            EMPLOYEE_ZIP            =". $db->qstr( $employee_record['employee_zip']             ).",
            EMPLOYEE_TYPE           =". $db->qstr( $employee_record['employee_type']            ).",                    
            EMPLOYEE_WORK_PHONE     =". $db->qstr( $employee_record['employee_workPhone']       ).",
            EMPLOYEE_HOME_PHONE     =". $db->qstr( $employee_record['employee_homePhone']       ).",
            EMPLOYEE_MOBILE_PHONE   =". $db->qstr( $employee_record['employee_mobilePhone']     ).",
            EMPLOYEE_BASED          =". $db->qstr( $employee_record['employee_based']           ).",
            EMPLOYEE_ACL            =". $db->qstr( $employee_record['employee_acl']             ).",    
            EMPLOYEE_STATUS         =". $db->qstr( $employee_record['employee_status']          );          
          
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $db->insert_id();        
        
    }
    
}

/* Get functions */

#####################################
# Get Employee Display Name from ID #  // not actually used anywhere
#####################################

function get_employee_display_name_by_id($db, $employee_id) {
    
    global $smarty;
    
    $sql = "SELECT ".PRFX."TABLE_EMPLOYEE.*, ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_NAME
            FROM ".PRFX."TABLE_EMPLOYEE
            LEFT JOIN ".PRFX."CONFIG_EMPLOYEE_TYPE ON (".PRFX."TABLE_EMPLOYEE.EMPLOYEE_TYPE = ".PRFX."CONFIG_EMPLOYEE_TYPE.TYPE_ID)
            WHERE EMPLOYEE_ID=". $db->qstr($employee_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        //$employee_array = $rs->GetArray();
        //return $employee_array['0']['EMPLOYEE_DISPLAY_NAME'];
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
    
    $sql = 'SELECT EMPLOYEE_ID FROM '.PRFX.'TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN ='.$db->qstr($employee_usr);    
    if(!$rs = $db->execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN =".$db->qstr($employee_usr);    
    if(!$rs = $db->execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = "SELECT * FROM ".PRFX."CONFIG_EMPLOYEE_TYPE";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {    
        
        return $rs->GetArray();    
        
    }
    
}

/* Update Functions */

#########################
#   Update Employee     #
#########################

function update_employee($db, $employee_record) {
    // Build the update statement with reguards to if the password is changing
        $set .="    SET
                    EMPLOYEE_LOGIN          =". $db->qstr( $employee_record['employee_usr']             ).",";

    if($employee_record['login_pwd'] != '') {
        $set .="    EMPLOYEE_PASSWD         =". $db->qstr( md5($employee_record['employee_pwd'])        ).",";
    }

        $set .="    EMPLOYEE_EMAIL          =". $db->qstr( $employee_record['employee_email']           ).", 
                    EMPLOYEE_FIRST_NAME     =". $db->qstr( $employee_record['employee_firstName']       ).",
                    EMPLOYEE_LAST_NAME      =". $db->qstr( $employee_record['employee_lastName']        ).",
                    EMPLOYEE_DISPLAY_NAME   =". $db->qstr( $employee_record['employee_displayName']     ).",
                    EMPLOYEE_ADDRESS        =". $db->qstr( $employee_record['employee_address']         ).",
                    EMPLOYEE_CITY           =". $db->qstr( $employee_record['employee_city']            ).",
                    EMPLOYEE_STATE          =". $db->qstr( $employee_record['employee_state']           ).", 
                    EMPLOYEE_ZIP            =". $db->qstr( $employee_record['employee_zip']             ).",
                    EMPLOYEE_TYPE           =". $db->qstr( $employee_record['employee_type']            ).",                    
                    EMPLOYEE_WORK_PHONE     =". $db->qstr( $employee_record['employee_workPhone']       ).",
                    EMPLOYEE_HOME_PHONE     =". $db->qstr( $employee_record['employee_homePhone']       ).",
                    EMPLOYEE_MOBILE_PHONE   =". $db->qstr( $employee_record['employee_mobilePhone']     ).",
                    EMPLOYEE_BASED          =". $db->qstr( $employee_record['employee_based']           ).",
                    EMPLOYEE_ACL            =". $db->qstr( $employee_record['employee_acl']             ).",    
                    EMPLOYEE_STATUS         =". $db->qstr( $employee_record['employee_status']          );

    $sql = "UPDATE ".PRFX."TABLE_EMPLOYEE ". $set ." WHERE EMPLOYEE_ID= ".$db->qstr($employee_record['employee_id']);

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=Error updating Employee Information');    
    }
    
}

/* Delete Functions */

/* Other Functions */

#################################################
# Count Employee Work Orders for a given status #
#################################################

function count_employee_workorders_with_status($db, $employee_id, $workorder_status){
    
    global $smarty;
    
    $sql = "SELECT COUNT(*) AS EMPLOYEE_WORKORDER_STATUS_COUNT
            FROM ".PRFX."TABLE_WORK_ORDER
            WHERE WORK_ORDER_ASSIGN_TO=".$db->qstr($employee_id)."
            AND WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
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
         FROM ".PRFX."TABLE_INVOICE
         WHERE INVOICE_PAID=".$db->qstr($invoice_status)."
         AND EMPLOYEE_ID=".$db->qstr($employee_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
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
    $sql = "SELECT EMPLOYEE_DISPLAY_NAME, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = "SELECT COUNT(*) AS num_users FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN =". $db->qstr($username);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_employee_error_message_function_'.__FUNCTION__.'_failed'));
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



// check these and improve them etc..

// A function for comparing password
    function cmpPass($element, $confirmPassword) {
        global $form;
        $password = $form->getElementValue('password');
        return ($password == $confirmPassword);
    }
    // A function to encrypt the password
    function encryptValue($value) {
        return md5($value);
    }