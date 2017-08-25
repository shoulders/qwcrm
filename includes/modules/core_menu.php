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

/* 
 * These are copied from includes/report.php but with menu added on the front of the name
 * These are only used to show numbers in the menu and could be removed 
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Workorders **/ 

##########################################
# Get single Work Order status           #
##########################################

function menu_get_single_workorder_status($db, $workorder_id){
    
    $sql = "SELECT status FROM ".PRFX."workorder WHERE workorder_id =".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to a get a single workorder status."));
        exit;
    } else {
        
        return $rs->fields['status'];
        
    }
    
}

#########################################
#      Count Work Orders                #   // also in report.php (without menu_ prefix)
#########################################

function menu_count_workorders($db, $status, $user_id = null, $start_date = null, $end_date = null) {
    
    // Default Action
    $whereTheseRecords = " WHERE workorder_id >= '1'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // All Open workorders
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.is_closed != '1'";
        
        // All Closed workorders
        } elseif($status == 'closed') {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.is_closed = '1'";
        
        // Return Workorders for the given status
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."workorder.status= ".$db->qstr($status);
            
        }
        
    }
    
    // Filter by user
    if($user_id) {
        $whereTheseRecords .= " AND employee_id=".$db->qstr($user_id);
    }
    
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND open_date >= ".$db->qstr($start_date)." AND open_date <= ".$db->qstr($end_date);
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

############################################
#         Count Invoices with Status       #   // also in report.php (without menu_ prefix)
############################################

function menu_count_invoices_with_status($db, $status, $user_id = null, $start_date = null, $end_date = null) {    
    
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // Filter by Unpaid Invoices
        if($status == 'unpaid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid != '1'";
        
        // Filter by Partially Paid Invoices
        } elseif($status == 'partially_paid') {
            
            $whereTheseRecords .= "AND is_paid != '1' AND paid_amount < gross_amount";
            
        // Filter by Paid Invoices
        } elseif($status == 'paid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            //$whereTheseRecords .= " AND ".PRFX."invoice.status = ".$db->qstr($status);
            
        }
        
    }
        
    // Filter by user
    if($user_id) {
        $whereTheseRecords .= " AND employee_id=".$db->qstr($user_id);
    }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
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