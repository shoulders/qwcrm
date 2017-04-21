<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Workorders - functions for wokorders
 * Invoices - functions for invoices
 * Customers - functions for customersd
 */

/** Home Page **/

#########################################
# Display Welcome Note                  #
#########################################

function display_welcome_msg($db){
    
    global $smarty;
    
    $sql = 'SELECT WELCOME_MSG FROM '.PRFX.'TABLE_COMPANY';
    //echo __FILE__;die;
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else { 
        
        return $rs->fields['WELCOME_MSG'];
        
    }
    
}

/** Workorders **/

#########################################
# Count Work Orders for a given status  #
#########################################

function count_workorders_with_status($db, $workorder_status){
    
    global $smarty;
    
    $sql = "SELECT COUNT(*) AS WORKORDER_STATUS_COUNT
            FROM ".PRFX."TABLE_WORK_ORDER
            WHERE WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
        
    $sql = 'SELECT COUNT(*) AS WORKORDER_TOTAL_COUNT FROM '.PRFX.'TABLE_WORK_ORDER';
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql ="SELECT COUNT(*) AS INVOICE_COUNT FROM ".PRFX."TABLE_INVOICE WHERE IS_PAID=".$db->qstr($invoice_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
            FROM ".PRFX."TABLE_INVOICE
            WHERE IS_PAID=".$db->qstr(0)." AND BALANCE=".$db->qstr(0); 
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
        FROM ".PRFX."TABLE_INVOICE
        WHERE IS_PAID=".$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
        FROM ".PRFX."TABLE_INVOICE
        WHERE IS_PAID=".$db->qstr(0)." AND BALANCE >".$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['DISCOUNT_SUM'];
        
    }
    
}

##################################################
# Count Unpaid Invoices                          #
##################################################

function count_upaid_invoices($db){
    
    global $smarty;
    
    $sql = 'SELECT COUNT(*) AS INVOICE_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE IS_PAID='.$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = 'SELECT SUM(BALANCE) AS BALANCE_SUM FROM '.PRFX.'TABLE_INVOICE WHERE IS_PAID='.$db->qstr(0).' AND BALANCE >'.$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = 'SELECT COUNT(*) AS BALANCE_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE IS_PAID='.$db->qstr(0).' AND BALANCE <> TOTAL';
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = 'SELECT SUM(BALANCE) AS BALANCE_SUM FROM '.PRFX.'TABLE_INVOICE WHERE IS_PAID='.$db->qstr(0).' AND BALANCE <> TOTAL';
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = 'SELECT COUNT(*) AS INVOICE_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE IS_PAID='.$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = 'SELECT SUM(TOTAL) AS TOTAL_SUM FROM '.PRFX.'TABLE_INVOICE WHERE IS_PAID='.$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['TOTAL_SUM'];
        
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
    
    $sql = 'SELECT COUNT(*) AS CUSTOMER_COUNT FROM '.PRFX.'TABLE_CUSTOMER WHERE CREATE_DATE >= '.$db->qstr($period);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $sql = 'SELECT COUNT(*) AS CUSTOMER_COUNT FROM '.PRFX.'TABLE_CUSTOMER';
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['CUSTOMER_COUNT'];
        
    }    
    
}