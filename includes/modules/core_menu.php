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
# Count Work Orders for a given status  #
#########################################

function menu_count_workorders_with_status($db, $status) {
    
    // Default Action
    $whereTheseRecords = " WHERE ".PRFX."workorder.workorder_id";
    
    // All Open workorders
    if($status == 'open') {

        $whereTheseRecords .= " AND ".PRFX."workorder.is_closed != '1'";

    // All Closed workorders
    } elseif($status == 'closed') {

        $whereTheseRecords .= " AND ".PRFX."workorder.is_closed = '1'";

    // Return Workorders for the given status
    } else {

        $whereTheseRecords .= " AND ".PRFX."workorder.status =".$db->qstr($status);

    }
    
    $sql = "SELECT COUNT(*) AS workorder_status_count
            FROM ".PRFX."workorder
            ".$whereTheseRecords;
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count workorders with a defined status."));
        exit;
    } else {
       
       return  $rs->fields['workorder_status_count']; 
       
    }
    
}

/** Invoices **/

############################################
# Count Invoices with Status (paid/unpaid) #
############################################

function menu_count_invoices_with_status($db, $invoice_status){
    
    $sql ="SELECT COUNT(*) AS invoice_count FROM ".PRFX."invoice WHERE is_paid=".$db->qstr($invoice_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count invoices with a defined status."));
        exit;
    } else {
       
        return $rs->fields['invoice_count'];
        
    }
    
}