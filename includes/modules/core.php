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

defined('_QWEXEC') or die;

/** Home Page **/

#########################################
# Display Welcome Note                  #
#########################################

function display_welcome_msg($db){
    
    $sql = "SELECT WELCOME_MSG FROM ".PRFX."company";
       
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not display the welcome message."));
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
    
    $sql = "SELECT COUNT(*) AS WORKORDER_STATUS_COUNT
            FROM ".PRFX."workorder
            WHERE WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count Work Orders for the defined status."));
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
    
    $sql = "SELECT COUNT(*) AS WORKORDER_TOTAL_COUNT FROM ".PRFX."workorder";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count all Work Orders."));
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
    
    $sql ="SELECT COUNT(*) AS INVOICE_COUNT FROM ".PRFX."invoice WHERE IS_PAID=".$db->qstr($invoice_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of Invoices for the defined status."));
        exit;
    } else {
        
       return $rs->fields['INVOICE_COUNT']; 
       
    }
    
}


########################################
# Sum of Discounts on Unpaid Invoices  #
########################################

function sum_of_discounts_on_unpaid_invoices($db){
    
    $sql = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
            FROM ".PRFX."invoice
            WHERE IS_PAID=".$db->qstr(0)." AND BALANCE=".$db->qstr(0); 
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the discounts of unpaid invoices."));
        exit;
    } else {
        
        return $rs->fields['DISCOUNT_SUM'];
        
    }    
    
}

########################################
# Sum of Discounts on Paid Invoices    #
########################################

function sum_of_discounts_on_paid_invoices($db){
    
    $sql = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
        FROM ".PRFX."invoice
        WHERE IS_PAID=".$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the discounts of paid invoices."));
        exit;
    } else {
        
        return $rs->fields['DISCOUNT_SUM'];
        
    }    
    
}

##################################################
# Sum of Discounts on Partially Paid Invoices    #
##################################################

function sum_of_discounts_on_partially_paid_invoices($db){
    
    $sql = "SELECT SUM(DISCOUNT) AS DISCOUNT_SUM
        FROM ".PRFX."invoice
        WHERE IS_PAID=".$db->qstr(0)." AND BALANCE >".$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the discounts of partially paid invoices."));
        exit;
    } else {
        
        return $rs->fields['DISCOUNT_SUM'];
        
    }
    
}

##################################################
# Count Unpaid Invoices                          #
##################################################

function count_upaid_invoices($db){
    
    $sql = "SELECT COUNT(*) AS INVOICE_COUNT FROM ".PRFX."invoice WHERE IS_PAID=".$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of unpaid invoices."));
        exit;
    } else {
        
        return $rs->fields['INVOICE_COUNT']; 
        
    }
    
}

###################################################
# Sum of Outstanding Balances for Unpaid Invoices #
###################################################

function sum_outstanding_balances_unpaid_invoices($db){
    
    $sql = "SELECT SUM(BALANCE) AS BALANCE_SUM FROM ".PRFX."invoice WHERE IS_PAID=".$db->qstr(0)." AND BALANCE >".$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the outstanding balances for unpaid invoices."));
        exit;
    } else {
        
        return $rs->fields['BALANCE_SUM']; 
        
    } 
    
}

##################################################
# Count Partially Paid Invoices                  #
##################################################

function count_partially_paid_invoices($db){
    
    $sql = "SELECT COUNT(*) AS BALANCE_COUNT FROM ".PRFX."invoice WHERE IS_PAID=".$db->qstr(0)." AND BALANCE <> TOTAL";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of invoices with partially paid invoices."));
        exit;
    } else {
        
        return $rs->fields['BALANCE_COUNT']; 
        
    }  
    
}

###########################################################
# Sum of Outstanding Balances for Partially Paid Invoices #
###########################################################

function sum_outstanding_balances_partially_paid_invoices($db){
    
    $sql = "SELECT SUM(BALANCE) AS BALANCE_SUM FROM ".PRFX."invoice WHERE IS_PAID=".$db->qstr(0)." AND BALANCE <> TOTAL";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the outstanding balances for partially paid invoices."));
        exit;
    } else {
        
        return $rs->fields['BALANCE_SUM'];
        
    }
    
}

#############################################
# Count All Paid Invoices                   #
#############################################

function count_all_paid_invoices($db){
    
    $sql = "SELECT COUNT(*) AS INVOICE_COUNT FROM ".PRFX."invoice WHERE IS_PAID=".$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of paid invoices."));
        exit;
    } else {
        
        return $rs->fields['INVOICE_COUNT'];  
        
    }
    
}

###################################################
# Sum of Invoice Amount for All Paid Invoices     #
###################################################

function sum_invoiceamounts_paid_invoices($db){
    
    $sql = "SELECT SUM(TOTAL) AS TOTAL_SUM FROM ".PRFX."invoice WHERE IS_PAID=".$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the total amount of paid invoices."));
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
    
    if($requested_period === 'month')   {$period = mktime(0,0,0,date('m'),0,date('Y'));}
    if($requested_period === 'year')    {$period = mktime(0,0,0,0,0,date('Y'));}
    
    $sql = "SELECT COUNT(*) AS CUSTOMER_COUNT FROM ".PRFX."customer WHERE CREATE_DATE >= ".$db->qstr($period);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of new customers during the defined period."));
        exit;
    } else {
        
        return $rs->fields['CUSTOMER_COUNT'];
        
    }
    
}

#############################################
# Count All Customers                       #
#############################################

function count_all_customers($db){
    
    $sql = "SELECT COUNT(*) AS CUSTOMER_COUNT FROM ".PRFX."customer";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the total number of customers."));
        exit;
    } else {
        
        return $rs->fields['CUSTOMER_COUNT'];
        
    }    
    
}