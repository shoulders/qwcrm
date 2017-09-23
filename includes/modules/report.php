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

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Display Functions **/

/** Insert Functions **/

/** Get Functions **/

/** Update Functions **/

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

/** General Section */


/** Customers **/

#############################################
#    Count Customers                        #
#############################################

function count_customers($db, $status, $start_date = null, $end_date = null) {    
    
    // Default Action
    $whereTheseRecords = " WHERE customer_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {        
        $whereTheseRecords .= " AND ".PRFX."customer.active= ".$db->qstr($status);            
    }
        
    // Filter by Create Data
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND create_date >= ".$db->qstr($start_date)." AND create_date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."customer
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of customers."));
        exit;
    } else {
        
       return $rs->fields['count']; 
       
    }
    
}

/** Workorders **/

#########################################
#     Count Work Orders                 #
#########################################

function count_workorders($db, $status, $user_id = null, $start_date = null, $end_date = null) {
    
    // Default Action
    $whereTheseRecords = " WHERE workorder_id >= '1'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // All Open Status workorders
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.is_closed = '0'";
                    
        // All Close Status workorders
        } elseif($status == 'close') {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.is_closed = '1'";
                        
        // All Opened workorders
        } elseif($status == 'opened') {
            
            // do nothing here           

        // All Closed workorders
        } elseif($status == 'closed') {
            
            // these give slightly different results because of the ability to manually change status
            
            //$whereTheseRecords .= " AND ".PRFX."workorder.is_closed = '1'";
            $whereTheseRecords .= " AND ".PRFX."workorder.close_date != ''";   
        
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.status= ".$db->qstr($status);                       
            
        }
        
    }
    
    // Filter by user
    if($user_id) {
        $whereTheseRecords .= " AND employee_id=".$db->qstr($user_id);
    }
    
    // Filter by Date
    if($status == 'closed') {
        
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND close_date >= ".$db->qstr($start_date)." AND close_date <= ".$db->qstr($end_date);
        }
        
    } else {
        
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND open_date >= ".$db->qstr($start_date)." AND open_date <= ".$db->qstr($end_date);
        }
        
    }
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."workorder
            ".$whereTheseRecords;    
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count Work Orders for the defined status."));
        exit;
        
    } else {      
        
        return  $rs->fields['count'];
        
    }
    
}

/** Invoices **/

####################################################
#     Count Invoices                               #
####################################################

function count_invoices($db, $status = null, $user_id = null, $start_date = null, $end_date = null) {    
    
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // All Open Status invoices
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_closed = '0'";
                    
        // All Close Status invoices
        } elseif($status == 'close') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_closed = '1'";
                        
        // All Opened workorders
        } elseif($status == 'opened') {
            
            // do nothing here           

        // All Closed workorders
        } elseif($status == 'closed') {
            
            // these give slightly different results because of the ability to manually change status
            
            //$whereTheseRecords .= " AND ".PRFX."invoice.is_closed = '1'";
            $whereTheseRecords .= " AND ".PRFX."invoice.close_date != ''";            
        
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.status= ".$db->qstr($status);                       
            
        }
        
    }
    
    // Filter by user
    if($user_id) {
        $whereTheseRecords .= " AND employee_id=".$db->qstr($user_id);
    }
    
    // Filter by Date
    if($status == 'closed') {
        
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND close_date >= ".$db->qstr($start_date)." AND close_date <= ".$db->qstr($end_date);
        } 
        
    } else {
        
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND open_date >= ".$db->qstr($start_date)." AND open_date <= ".$db->qstr($end_date);
        }
        
    }
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."invoice
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of Invoices."));
        exit;
    } else {
        
       return $rs->fields['count']; 
       
    }
    
}

#########################################
#  Sum selected value of invoices       #
#########################################

function sum_invoices_value($db, $status, $value_name, $start_date = null, $end_date = null) {
        
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // Filter by Unpaid Invoices
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_closed != '1'";
        
        } elseif($status == 'close') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_closed = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.status= ".$db->qstr($status);
            
        }
        
    }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(".PRFX."invoice.$value_name) AS sum
            FROM ".PRFX."invoice
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the invoice discount amounts."));
        exit;
    } else {
        
       return $rs->fields['sum']; 
       
    }    
    
}

/** Labour **/

#########################
#  Count labour items   #
#########################

function count_labour_items($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(qty) AS count
            FROM ".PRFX."invoice_labour
            INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_labour.invoice_id
            WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the total number of labour items ordered."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

###################################
#  Count different labour items   #
###################################

function count_labour_different_items($db, $start_date, $end_date) {
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."invoice_labour
            INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_labour.invoice_id
            WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the total number of labour items ordered."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

#########################################
#  Sum selected value of labour items   #
#########################################

function sum_labour_items($db, $value_name, $start_date, $end_date) {
    
    $sql = "SELECT SUM(".PRFX."invoice_labour.$value_name) AS sum
            FROM ".PRFX."invoice_labour
            INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_labour.invoice_id
            WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sum of labour items ordered."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Parts **/

########################
#  Count parts items   #
########################

function count_parts_items($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(qty) AS count
            FROM ".PRFX."invoice_parts
            INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_parts.invoice_id
            WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the total number of different parts items ordered."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

##################################
#  Count different parts items   #
##################################

function count_parts_different_items($db, $start_date, $end_date) {
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."invoice_parts
            INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_parts.invoice_id
            WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the total number of parts items ordered."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

###################################
#  Sum selected value of Parts    #
###################################

function sum_parts_value($db, $value_name, $start_date, $end_date) {
    
    $sql = "SELECT SUM(".PRFX."invoice_parts.$value_name) AS sum
            FROM ".PRFX."invoice_parts
            INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_parts.invoice_id
            WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the total number of parts items ordered."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Expense **/

###################################
#  Sum selected value of expenses #
###################################

function sum_expenses_value($db, $value_name, $start_date, $end_date) {
    
    $sql = "SELECT SUM(".PRFX."expense.$value_name) AS sum
            FROM ".PRFX."expense
            WHERE date  >= ".$db->qstr($start_date)." AND date  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sum value for the selected expenses."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Refunds **/

###################################
#  Sum selected value of Refunds  #
###################################

function sum_refunds_value($db, $value_name, $start_date, $end_date) {
    
    $sql = "SELECT SUM(".PRFX."refund.$value_name) AS sum
            FROM ".PRFX."refund
            WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sum value for the selected refunds."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}