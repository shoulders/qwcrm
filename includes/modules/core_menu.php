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
 * These are copied from includes/core_home.php but with menu added on the front of the name
 * These are only used to show numbers in the menu and could be removed 
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Workorders **/ 

##########################################
# Get single Work Order status           #
##########################################

function menu_get_single_workorder_status($db, $workorder_id){
    
    $sql = "SELECT work_order_status FROM ".PRFX."workorder WHERE work_order_id =".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to a get a single workorder status."));
        exit;
    } else {
        
        return $rs->fields['work_order_status'];
        
    }
    
}

#########################################
# Count Work Orders that are unassigned #
#########################################

// Open - Assigned
// This might not be 100% correct

function menu_count_unassigned_workorders($db){
    
    return (menu_count_workorders_with_status($db, 10) - menu_count_workorders_with_status($db, 2));
    
}

#########################################
# Count Work Orders for a given status  #
#########################################

function menu_count_workorders_with_status($db, $workorder_status){
    
    global $smarty;
    
    $sql = "SELECT COUNT(*) AS workorder_status_count
            FROM ".PRFX."workorder
            WHERE work_order_status=".$db->qstr($workorder_status);
    
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