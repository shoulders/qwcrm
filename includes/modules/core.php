<?php

// will only be needed by main when stuff is moved. get functions in header working first, then move

/** Mandatory Code **/

##############################
# Load language translations #
##############################

if(!xml2php('core')) {    
    $smarty->assign('error_msg', 'Error in core language file');
}

/** Misc **/



#########################################
# Display Welcome Note                  #
#########################################

function display_welcome_note($db){
    
    global $smarty;
    
    $q = 'SELECT WELCOME_NOTE FROM '.PRFX.'SETUP';
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else { 
        return $rs->fields['WELCOME_NOTE'];
    }
}

/** Work Orders **/

#########################################
# Count Work Orders for a given status  #
#########################################

function count_workorders_with_status($db, $workorder_status){
    
    global $smarty;
    
    $q = "SELECT COUNT(*) AS WORKORDER_STATUS_COUNT
            FROM ".PRFX."TABLE_WORK_ORDER
            WHERE WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        return  $rs->fields['WORKORDER_STATUS_COUNT'];
    }
    
}

#########################################
# Count Work Orders that are unassigned #
#########################################

// Open - Assigned
// This might not be 100% correct

function count_unassigned_workorders($db){    
    return (count_workorders_with_status($db, 10) - count_workorders_with_status($db, 2));    
}

#############################################
# Count All Work Orders (All Time Total)    #
#############################################

function count_all_workorders($db){
    
    global $smarty;
        
    $q = 'SELECT COUNT(*) AS WORKORDER_TOTAL_COUNT FROM '.PRFX.'TABLE_WORK_ORDER';
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        return $rs->fields['WORKORDER_TOTAL_COUNT'];
    }
}

/** Invoices **/

############################################
# Count Invoices with Status (paid/unpaid) #
############################################

function count_invoices_with_status($db, $invoice_status){
    
    global $smarty;
    
    $q ="SELECT COUNT(*) AS INVOICE_COUNT FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_PAID=".$db->qstr($invoice_status);
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
       return $rs->fields['INVOICE_COUNT']; 
    }
    
}


########################################
# Sum of Discounts on Unpaid Invoices  #
########################################

function sum_of_discounts_on_unpaid_invoices($db){
    
    global $smarty;
    
    $q = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
            FROM ".PRFX."TABLE_INVOICE
            WHERE INVOICE_PAID=".$db->qstr(0)." AND BALANCE=".$db->qstr(0); 
    
    if(!$rs = $db->Execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['DISCOUNT_SUM'];
    }    
}

########################################
# Sum of Discounts on Paid Invoices    #
########################################

function sum_of_discounts_on_paid_invoices($db){
    
    global $smarty;
    
    $q = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
        FROM ".PRFX."TABLE_INVOICE
        WHERE INVOICE_PAID=".$db->qstr(1);
    
    if(!$rs = $db->Execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['DISCOUNT_SUM'];
    }    
}

##################################################
# Sum of Discounts on Partially Paid Invoices    #
##################################################

function sum_of_discounts_on_partially_paid_invoices($db){
    
    global $smarty;
    
    $q = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
        FROM ".PRFX."TABLE_INVOICE
        WHERE INVOICE_PAID=".$db->qstr(0)." AND BALANCE >".$db->qstr(0);
    
    if(!$rs = $db->Execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        die;
    } else {
        return $rs->fields['DISCOUNT_SUM'];
    }
}

##################################################
# Count Unpaid Invoices                          #
##################################################

function count_upaid_invoices($db){
    
    global $smarty;
    
    $q = 'SELECT COUNT(*) AS INVOICE_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0);
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['INVOICE_COUNT'];        
    }
}

###################################################
# Sum of Outstanding Balances for Unpaid Invoices #
###################################################

function sum_outstanding_balances_unpaid_invoices($db){
    
    global $smarty;
    
    $q = 'SELECT SUM(BALANCE) AS BALANCE_SUM FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND BALANCE >'.$db->qstr(0);
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['BALANCE_SUM'];        
    } 
}

##################################################
# Count Partially Paid Invoices                  #
##################################################

function count_partially_paid_invoices($db){
    
    global $smarty;
    
    $q = 'SELECT COUNT(*) AS BALANCE_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND BALANCE <> INVOICE_AMOUNT';
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['BALANCE_COUNT'];       
    }  
}

###########################################################
# Sum of Outstanding Balances for Partially Paid Invoices #
###########################################################

function sum_outstanding_balances_partially_paid_invoices($db){
    
    global $smarty;
    
    $q = 'SELECT SUM(BALANCE) AS BALANCE_SUM FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(0).' AND BALANCE <> INVOICE_AMOUNT';
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['BALANCE_SUM'];
    }
}

#############################################
# Count All Paid Invoices                   #
#############################################

function count_all_paid_invoices($db){
    
    global $smarty;
    
    $q = 'SELECT COUNT(*) AS INVOICE_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(1);
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['INVOICE_COUNT'];        
    }
}

###################################################
# Sum of Invoice Amount for All Paid Invoices     #
###################################################

function sum_invoiceamounts_paid_invoices($db){
    
    global $smarty;
    
    $q = 'SELECT SUM(INVOICE_AMOUNT) AS INVOICE_AMOUNT_SUM FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID='.$db->qstr(1);
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['INVOICE_AMOUNT_SUM'];
    }    
}

/** Customers **/

#############################################
# New Customers during this period          #
#############################################

function new_customers_during_period($db, $requested_period){
    
    global $smarty;
    
    if($requested_period === 'month')   {$period = mktime(0,0,0,date('m'),0,date('Y'));}
    if($requested_period === 'year')    {$period = mktime(0,0,0,0,0,date('Y'));}
    
    $q = 'SELECT COUNT(*) AS CUSTOMER_COUNT FROM '.PRFX.'TABLE_CUSTOMER WHERE CREATE_DATE >= '.$db->qstr($period);
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['CUSTOMER_COUNT'];       
    }
}

#############################################
# Count All Customers                       #
#############################################

function count_all_customers($db){
    
    global $smarty;
    
    $q = 'SELECT COUNT(*) AS CUSTOMER_COUNT FROM '.PRFX.'TABLE_CUSTOMER';
    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['CUSTOMER_COUNT'];
    }    
}

/** Employee **/

#########################################
# Get Employee ID by username           #
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
    
    $q = 'SELECT EMPLOYEE_ID FROM '.PRFX.'TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN ='.$db->qstr($employee_usr);    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN =".$db->qstr($employee_usr);    
    if(!$rs = $db->execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $q = "SELECT COUNT(*) AS EMPLOYEE_WORKORDER_STATUS_COUNT
         FROM ".PRFX."TABLE_WORK_ORDER
         WHERE WORK_ORDER_ASSIGN_TO=".$db->qstr($employee_id)."
         AND WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($q)){
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $q = "SELECT COUNT(*) AS EMPLOYEE_INVOICE_COUNT
         FROM ".PRFX."TABLE_INVOICE
         WHERE INVOICE_PAID=".$db->qstr($invoice_status)."
         AND EMPLOYEE_ID=".$db->qstr($employee_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error', 'error_type=database&error_location=includes:modules:core&php_function='.__FUNCTION__.'&database_error='.$db->ErrorMsg().'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
   } else {
       return $rs->fields['EMPLOYEE_INVOICE_COUNT'];
   }
}