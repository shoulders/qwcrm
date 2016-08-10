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
#  Greeting Message Based on Time       #
#########################################

function greeting_message_based_on_time($employee_name){    
    
    $morning    = "Good morning! $employee_name";
    $afternoon  = "Good afternoon! $employee_name";
    $evening    = "Good evening! $employee_name";
    $night      = "Working late? $employee_name";
    
    $friday     = "Get ready for the weekend!";

    // Get the current hour
    $current_time = date('H');
    
    // Get the current day
    $current_day = date('l');
    
    // 06:00 - 11:59
    if ($current_time >= 6 && $current_time <=11) {
        $greeting_msg = $morning;
    }
    // 12:00 - 17:59
    elseif ($current_time >= 12 && $current_time <= 17) {
        $greeting_msg =  $afternoon;
    }
    // 18:00. - 23:59 p.m.
    elseif ($current_time >= 17 && $current_time <= 23) {
        $greeting_msg =  $evening;
    }
    // 00:00 - 05:59
    elseif ($current_time >= 0 && $current_time <= 5) {
        $greeting_msg = $night;
    }    
    
    // Friday
    if ($current_day === 'Friday'){
        $greeting_msg = $greeting_msg.' - '.$friday;
    }
    return $greeting_msg;
}

#########################################
# Display Welcome Note                  #
#########################################

function display_welcome_note($db){
    $q = 'SELECT WELCOME_NOTE FROM '.PRFX.'SETUP';
    if(!$rs = $db->execute($q)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    } else { 
        return $rs->fields['WELCOME_NOTE'];
    }
}
#########################################
# Get employee ID number from username  #
#########################################

/* I dont thinks this is needed in core
 * it was used for getting user specific stats in theme_header_block.php
 * i am using $login_id instead now
 * i will leave this here just for now
 */

function get_employee_id_by_username($db, $employee_usr){
    $q = 'SELECT EMPLOYEE_ID FROM '.PRFX.'TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN ='.$db->qstr($employee_usr);
    $rs = $db->Execute($q);
    return $rs->fields['EMPLOYEE_ID'];
}

#########################################
# Get employee record by username       #
#########################################

function get_employee_record_by_username($db, $employee_usr){
    $q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN =".$db->qstr($employee_usr);
    $rs = $db->Execute($q);
    return $rs->FetchRow();
}

##########################################
# Display single Work Order information  #
##########################################

function display_single_workorder_record($db, $wo_id){
    $q = "SELECT * FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID =".$db->qstr($wo_id);
    $rs = $db->Execute($q);
    return $rs->FetchRow();
}

/** Counting Functions - General **/

#########################################
# Count Work Orders for a given status  #
#########################################

function count_workorders_with_status($db, $workorder_status){
    $q = "SELECT count(*) AS WORKORDER_STATUS_COUNT FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    $rs = $db->Execute($q);    
    return  $rs->fields['WORKORDER_STATUS_COUNT'];
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
    $q = 'SELECT count(*) AS WORKORDER_TOTAL_COUNT FROM '.PRFX.'TABLE_WORK_ORDER';
    if(!$rs = $db->execute($q)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    } else {
        return $rs->fields['WORKORDER_TOTAL_COUNT'];
    }
}

############################################
# Count Invoices with Status (paid/unpaid) #
############################################

function count_invoices_with_status($db, $invoice_status){
    $q ="SELECT COUNT(*) AS UNPAID_COUNT FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_PAID=".$db->qstr($invoice_status);
    $rs = $db->Execute($q);
    return $rs->fields['UNPAID_COUNT'];
}

/** Counting Functions - Employee Specific **/

#################################################
# Count Employee Work Orders for a given status #
#################################################

function count_employee_workorders_with_status($db, $employee_id, $workorder_status){
    $q = "SELECT count(*) AS EMPLOYEE_WORKORDER_STATUS_COUNT
         FROM ".PRFX."TABLE_WORK_ORDER
         WHERE WORK_ORDER_ASSIGN_TO=".$db->qstr($employee_id)."
         AND WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    if(!$rs = $db->Execute($q)){
      echo 'Error:'. $db->ErrorMsg();
   } else {
       return $rs->fields['EMPLOYEE_WORKORDER_STATUS_COUNT'];
   }
}

###############################################
# Count Employee Invoices for a given status  #
###############################################

function count_employee_invoices_with_status($db, $employee_id, $invoice_status){
    $q = "SELECT count(*) AS EMPLOYEE_INVOICE_COUNT
         FROM ".PRFX."TABLE_INVOICE
         WHERE INVOICE_PAID=".$db->qstr($invoice_status)."
         AND EMPLOYEE_ID=".$db->qstr($employee_id);
    if(!$rs = $db->Execute($q)) {
        echo 'Error:'. $db->ErrorMsg();
   } else {
       return $rs->fields['EMPLOYEE_INVOICE_COUNT'];
   }
}











/** Discount Stats **/

########################################
# Sum of Unpaid Discounts on Invoices  #
########################################
function sum_unpaid_discounts_on_invoices($db){
    $q = "SELECT SUM(DISCOUNT) AS SUM_UNPAID_DISCOUNT
            FROM ".PRFX."TABLE_INVOICE
            WHERE INVOICE_PAID=".$db->qstr(0)." AND BALANCE=".$db->qstr(0);
    if(!$rs = $db->Execute($q)){
        echo 'Error: '. $db->ErrorMsg();
        die;
    } else {
        return $rs->fields['SUM_UNPAID_DISCOUNT'];
    }    
}

/*
 * unpaid   - sum of discount sitting on unpaid invoices
 * partial  - sum of discount paid on partially paid invoices
 * paid     - Total of discount paid
 */
function discounts_applied_on_invoices($db, $invoice_payment_status){
    
    if($invoice_payment_status === 'unpaid')    {$invoice_filter = "WHERE INVOICE_PAID=".$db->qstr(0)." AND BALANCE=".$db->qstr(0);}
    if($invoice_payment_status === 'partial')   {$invoice_filter = "WHERE INVOICE_PAID=".$db->qstr(1);}
    if($invoice_payment_status === 'paid')      {$invoice_filter = "WHERE INVOICE_PAID=".$db->qstr(0)." AND BALANCE >".$db->qstr(0);}
    
    $q = "SELECT SUM(DISCOUNT) AS SUM_APPLIED_DISCOUNT
            FROM ".PRFX."TABLE_INVOICE
            WHERE INVOICE_PAID=".$db->qstr(0)." AND BALANCE=".$db->qstr(0)
            .$invoice_filter;
    if(!$rs = $db->Execute($q)){
        echo 'Error: '. $db->ErrorMsg();
        die;
    } else {
        return $rs->fields['SUM_APPLIED_DISCOUNT'];
    }    
}