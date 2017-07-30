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
    
    $sql = "SELECT welcome_msg FROM ".PRFX."company";
       
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not display the welcome message."));
        exit;
    } else { 
        
        return $rs->fields['welcome_msg'];
        
    }
    
}

/** Workorders **/

#########################################
# Count Work Orders for a given status  #
#########################################

function count_workorders_with_status($db, $workorder_status){
    
    $sql = "SELECT COUNT(*) AS workorder_status_count
            FROM ".PRFX."workorder
            WHERE work_order_status=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count Work Orders for the defined status."));
        exit;
    } else {      
        
        return  $rs->fields['workorder_status_count'];
        
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
    
    $sql = "SELECT COUNT(*) AS workorder_total_count FROM ".PRFX."workorder";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count all Work Orders."));
        exit;
    } else {       
        
        return $rs->fields['workorder_total_count'];
        
    }
    
}

/** Invoices **/

############################################
# Count Invoices with Status (paid/unpaid) #
############################################

function count_invoices_with_status($db, $invoice_status){
    
    $sql ="SELECT COUNT(*) AS invoice_count FROM ".PRFX."invoice WHERE is_paid=".$db->qstr($invoice_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of Invoices for the defined status."));
        exit;
    } else {
        
       return $rs->fields['invoice_count']; 
       
    }
    
}


########################################
# Sum of Discounts on Unpaid Invoices  #
########################################

function sum_of_discounts_on_unpaid_invoices($db){
    
    $sql = "SELECT SUM(discount_amount) AS discount_amount_sum
            FROM ".PRFX."invoice
            WHERE is_paid=".$db->qstr(0)." AND BALANCE=".$db->qstr(0); 
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the discounts of unpaid invoices."));
        exit;
    } else {
        
        return $rs->fields['discount_amount_sum'];
        
    }    
    
}

########################################
# Sum of Discounts on Paid Invoices    #
########################################

function sum_of_discounts_on_paid_invoices($db){
    
    $sql = "SELECT SUM(discount_amount) AS discount_amount_sum
        FROM ".PRFX."invoice
        WHERE is_paid=".$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the discounts of paid invoices."));
        exit;
    } else {
        
        return $rs->fields['discount_amount_sum'];
        
    }    
    
}

##################################################
# Sum of Discounts on Partially Paid Invoices    #
##################################################

function sum_of_discounts_on_partially_paid_invoices($db){
    
    $sql = "SELECT SUM(discount_amount) AS discount_amount_sum
        FROM ".PRFX."invoice
        WHERE is_paid=".$db->qstr(0)." AND balance >".$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the discounts of partially paid invoices."));
        exit;
    } else {
        
        return $rs->fields['discount_amount_sum'];
        
    }
    
}

##################################################
# Count Unpaid Invoices                          #
##################################################

function count_upaid_invoices($db){
    
    $sql = "SELECT COUNT(*) AS invoice_count FROM ".PRFX."invoice WHERE is_paid=".$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of unpaid invoices."));
        exit;
    } else {
        
        return $rs->fields['invoice_count']; 
        
    }
    
}

###################################################
# Sum of Outstanding Balances for Unpaid Invoices #
###################################################

function sum_outstanding_balances_unpaid_invoices($db){
    
    $sql = "SELECT SUM(balance) AS balance_sum FROM ".PRFX."invoice WHERE is_paid=".$db->qstr(0)." AND balance >".$db->qstr(0);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the outstanding balances for unpaid invoices."));
        exit;
    } else {
        
        return $rs->fields['balance_sum']; 
        
    } 
    
}

##################################################
# Count Partially Paid Invoices                  #
##################################################

function count_partially_paid_invoices($db){
    
    $sql = "SELECT COUNT(*) AS balance_count FROM ".PRFX."invoice WHERE is_paid=".$db->qstr(0)." AND balance <> total";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of invoices with partially paid invoices."));
        exit;
    } else {
        
        return $rs->fields['balance_count']; 
        
    }  
    
}

###########################################################
# Sum of Outstanding Balances for Partially Paid Invoices #
###########################################################

function sum_outstanding_balances_partially_paid_invoices($db){
    
    $sql = "SELECT SUM(balance) AS balance_sum FROM ".PRFX."invoice WHERE is_paid=".$db->qstr(0)." AND balance <> total";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the outstanding balances for partially paid invoices."));
        exit;
    } else {
        
        return $rs->fields['balance_sum'];
        
    }
    
}

#############################################
# Count All Paid Invoices                   #
#############################################

function count_all_paid_invoices($db){
    
    $sql = "SELECT COUNT(*) AS invoice_count FROM ".PRFX."invoice WHERE is_paid=".$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of paid invoices."));
        exit;
    } else {
        
        return $rs->fields['invoice_count'];  
        
    }
    
}

###################################################
# Sum of Invoice Amount for All Paid Invoices     #
###################################################

function sum_invoiceamounts_paid_invoices($db){
    
    $sql = "SELECT SUM(total) AS total_sum FROM ".PRFX."invoice WHERE is_paid=".$db->qstr(1);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the total amount of paid invoices."));
        exit;
    } else {
        
        return $rs->fields['total_sum'];
        
    }    
    
}

/** Customers **/

#############################################
# New Customers during this period          #
#############################################

function new_customers_during_period($db, $requested_period){
    
    if($requested_period === 'month')   {$period = mktime(0,0,0,date('m'),0,date('Y'));}
    if($requested_period === 'year')    {$period = mktime(0,0,0,0,0,date('Y'));}
    
    $sql = "SELECT COUNT(*) AS customer_count FROM ".PRFX."customer WHERE create_date >= ".$db->qstr($period);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of new customers during the defined period."));
        exit;
    } else {
        
        return $rs->fields['customer_count'];
        
    }
    
}

#############################################
# Count All Customers                       #
#############################################

function count_all_customers($db){
    
    $sql = "SELECT COUNT(*) AS customer_count FROM ".PRFX."customer";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the total number of customers."));
        exit;
    } else {
        
        return $rs->fields['customer_count'];
        
    }    
    
}