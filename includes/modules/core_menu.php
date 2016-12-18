<?php

/** Mandatory Code **/

##############################
# Load language translations #
##############################

if(!xml2php('core_menu')) {    
    $smarty->assign('error_msg', 'Error in core language file');
}   

/** Work Orders **/ 

##########################################
# Get single Work Order status           #
##########################################

function menu_get_single_workorder_status($db, $workorder_id){
    
    global $smarty;
    
    $q = "SELECT WORK_ORDER_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID =".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_core_menu_error_message_function_'.__FUNCTION__.'_failed'));
        exit;   
    } else {
        return $rs->fields['WORK_ORDER_STATUS'];
    }
    
}

/* 
 * These are copied from includes/core_home.php but with menu added on the front of the name
 * These are only used to show numbers in the menu and could be removed
 * 
 */

#########################################
# Count Work Orders for a given status  #
#########################################

function menu_count_workorders_with_status($db, $workorder_status){
    
    global $smarty;
    
    $q = "SELECT COUNT(*) AS WORKORDER_STATUS_COUNT
            FROM ".PRFX."TABLE_WORK_ORDER
            WHERE WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_core_menu_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    $q ="SELECT COUNT(*) AS INVOICE_COUNT FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_PAID=".$db->qstr($invoice_status);
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_core_menu_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
   } else {
        return $rs->fields['INVOICE_COUNT'];
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