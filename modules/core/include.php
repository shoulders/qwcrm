<?php

/** Mandatory Code **/

##############################
# Load language translations #
##############################

if(!xml2php('core')) {    
    $smarty->assign('error_msg', 'Error in core language file');
}

/** Misc **/

#########################################
# Get employee ID number from username  #
#########################################

/* I dont thinks this is needed in core
 * it was used for getting user specific stats in theme_header_block.php
 * i am using $login_id instead now
 * i will leave this here just for now
 */

function get_employee_id_by_username($db, $username){
    $q = 'SELECT EMPLOYEE_ID FROM '.PRFX.'TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN ='.$db->qstr($username);
    $rs = $db->Execute($q);
    return $rs->fields['EMPLOYEE_ID'];
}

#########################################
# Get employee credentials by username  #
#########################################

function get_employee_credentials_by_username($db, $employee_usr){
    $q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_LOGIN =".$db->qstr($employee_usr);
    $rs = $db->Execute($q);
    return $rs->FetchRow();
}

##########################################
# Display single Work Order information  #
##########################################

function display_single_workorder_record($db, $wo_id){
    $q = "SELECT * FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID ='".$wo_id."'" ;
    $rs = $db->Execute($q);
    return $rs->FetchRow();
}


/** Counting Functions - General **/

##############################
# Count Open Work Orders     #
##############################

function count_open_work_orders($db){
    $q = 'SELECT count(*) AS OPEN_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS='.$db->qstr(10);
    $rs = $db->Execute($q);    
    return  $rs->fields['OPEN_COUNT'];
}

##############################
# Count Assigned Work Orders #
##############################

function count_assigned_work_orders($db){
    $q = 'SELECT count(*) AS ASSIGNED_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(2);
    $rs = $db->Execute($q);
    return $rs->fields['ASSIGNED_COUNT'];
}

######################################
# Count Work Orders Awaiting Payment #
######################################

function count_work_orders_awaiting_payment($db){
    $q = 'SELECT count(*) AS AWAITING_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_CURRENT_STATUS='.$db->qstr(7);
    $rs = $db->Execute($q);
    return $rs->fields['AWAITING_COUNT'];
}

##############################
# Count Closed Work Orders   #
##############################

function count_closed_work_orders($db){
    $q = 'SELECT count(*) AS CLOSED_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE  WORK_ORDER_STATUS='.$db->qstr(6);
    $rs = $db->Execute($q);
    return $rs->fields['CLOSED_COUNT'];
}

##############################
# Count Unpaid Invoices      #
##############################

function count_unpaid_invoices($db){
    $q ='SELECT COUNT(*) AS UNPAID_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID=0';
    $rs = $db->Execute($q);
    return $rs->fields['UNPAID_COUNT'];
}

##############################
# Count Paid Invoices        #
##############################

function count_paid_invoices($db){
    $q ='SELECT COUNT(*) AS PAID_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID=1';
    $rs = $db->Execute($q);
    return $rs->fields['PAID_COUNT'];
}


/** Counting Functions - User Specific **/

###################################
# Count Employee Open Work Orders #
###################################

function count_employee_open_work_orders($db, $employee_id){
    $q = 'SELECT count(*) AS EMPLOYEE_OPEN_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_ASSIGN_TO='.$db->qstr($employee_id).' AND WORK_ORDER_STATUS='.$db->qstr(10) ;
    if(!$rs = $db->Execute($q)){
      echo 'Error:'. $db->ErrorMsg();
   } else {
       return $rs->fields['EMPLOYEE_OPEN_COUNT'];
   }
}

#######################################
# Count Employee Assigned Work Orders #
#######################################

function count_employee_assigned_work_orders($db, $employee_id){
    $q = 'SELECT count(*) AS EMPLOYEE_ASSIGNED_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_ASSIGN_TO='.$db->qstr($login_id).' AND WORK_ORDER_STATUS='.$db->qstr(2) ;
    if(!$rs = $db->Execute($q)) {
        echo 'Error:'. $db->ErrorMsg();
    } else {
        return $rs->fields['EMPLOYEE_ASSIGNED_COUNT'];
    }    
}

##################################################
# Count Employee Work Orders Awaiting payment    #
##################################################

function count_employee_work_orders_awaiting_payment($db, $employee_id){
    $q = 'SELECT count(*) AS EMPLOYEE_AWAITING_PAYMENT_COUNT FROM '.PRFX.'TABLE_WORK_ORDER WHERE WORK_ORDER_ASSIGN_TO='.$db->qstr($login_id).' AND WORK_ORDER_STATUS='.$db->qstr(7) ;
    if(!$rs = $db->Execute($q)) {
        echo 'Error:'. $db->ErrorMsg();
    } else {
        return $rs->fields['EMPLOYEE_AWAITING_PAYMENT_COUNT'];
    }
}

#####################################
# Count Employee Unpaid Invoice     #
#####################################

function count_employee_unpaid_invoices($db, $employee_id){
    $q = 'SELECT count(*) AS EMPLOYEE_UNPAID_COUNT FROM '.PRFX.'TABLE_INVOICE WHERE INVOICE_PAID=0 AND EMPLOYEE_ID='.$db->qstr($login_id) ;
    if(!$rs = $db->Execute($q)) {
        echo 'Error:'. $db->ErrorMsg();
   } else {
       return $rs->fields['EMPLOYEE_UNPAID_COUNT'];
   }
}