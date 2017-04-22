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



/** Mandatory Code **/

/** Workorders **/ 

##########################################
# Get single Work Order status           #
##########################################

function menu_get_single_workorder_status($db, $workorder_id){
    
    global $smarty;
    
    $sql = "SELECT WORK_ORDER_STATUS FROM ".PRFX."WORKORDER WHERE WORK_ORDER_ID =".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_menu_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['WORK_ORDER_STATUS'];
        
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
    
    $sql = "SELECT COUNT(*) AS WORKORDER_STATUS_COUNT
            FROM ".PRFX."WORKORDER
            WHERE WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_menu_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
       
       return  $rs->fields['WORKORDER_STATUS_COUNT']; 
       
    }
    
}

/** Invoices **/

############################################
# Count Invoices with Status (paid/unpaid) #
############################################

function menu_count_invoices_with_status($db, $invoice_status){
    
    global $smarty;
    
    $sql ="SELECT COUNT(*) AS INVOICE_COUNT FROM ".PRFX."INVOICE WHERE IS_PAID=".$db->qstr($invoice_status);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_core_menu_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
       
        return $rs->fields['INVOICE_COUNT'];
        
    }
    
}