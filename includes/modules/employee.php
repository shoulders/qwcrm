<?php

#####################################
# Get Employee Display Name from ID #  // not actually used anywhere
#####################################

function get_employee_display_name_by_id($db, $employee_id){
    
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
 *  * no longer needed as I sotre the id in the session
 * 
 */

function get_employee_id_by_username($db, $employee_usr){
    
    global $smarty;
    
    $sql = 'SELECT EMPLOYEE_ID FROM '.PRFX.'TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN ='.$db->qstr($employee_usr);    
    if(!$rs = $db->execute($sql)){
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->FetchRow();
        
    }
    
}


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
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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

##################################################
# List all active employees display name and ID  #
##################################################
    
function get_active_employees($db){
    
    $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
    
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {    
        
        return $rs->GetArray();    
        
    }    
}