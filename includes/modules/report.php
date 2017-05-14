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

/** New/Insert Functions **/

/** Get Functions **/

/** Update Functions **/

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

/** General Section */

###############################################
#  Count open work orders in selected period  #
###############################################

function count_open_workorders_in_seleceted_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."WORKORDER WHERE WORK_ORDER_OPEN_DATE >= ".$db->qstr($start_date)." AND WORK_ORDER_OPEN_DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count closed work orders in selected period #
################################################

function count_open_workorders_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."WORKORDER WHERE WORK_ORDER_CLOSE_DATE >= ".$db->qstr($start_date)." AND WORK_ORDER_CLOSE_DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count New Customers in selected period      #
################################################

function count_new_customers_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."CUSTOMER WHERE CREATE_DATE >= ".$db->qstr($start_date)." AND CREATE_DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count Total Customers in QWcrm              #
################################################

function count_total_customers_in_qwcrm($db) {
    
    global $smarty;
    
    $sql = "SELECT COUNT(*) AS count FROM ".PRFX."CUSTOMER";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count Created Invoices in selected period   #
################################################

function count_created_invoices_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;

    $sql = "SELECT count(*) AS count FROM ".PRFX."INVOICE WHERE DATE >= ".$db->qstr($start_date)." AND DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count Paid Invoices in selected period      #
################################################

function count_paid_invoices_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."INVOICE WHERE DATE >= ".$db->qstr($start_date)." AND DATE <= ".$db->qstr($end_date)." AND IS_PAID = 1";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

/* Parts Section */

###########################################################################
#  Count Total number of different part items ordered in selected period  #
###########################################################################

function count_total_number_of_different_part_items_ordered_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT COUNT(*) AS count FROM ".PRFX."INVOICE_PARTS INNER JOIN ".PRFX."INVOICE ON ".PRFX."INVOICE.INVOICE_ID = ".PRFX."INVOICE_PARTS.INVOICE_ID WHERE ".PRFX."INVOICE.DATE >= ".$db->qstr($start_date)." AND ".PRFX."INVOICE.DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

##########################################################################
#  Sum Total quantity of part items ordered in selected period           #
##########################################################################

function sum_total_quantity_of_part_items_ordered_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(INVOICE_PARTS_COUNT) AS sum FROM ".PRFX."INVOICE_PARTS INNER JOIN ".PRFX."INVOICE ON ".PRFX."INVOICE.INVOICE_ID = ".PRFX."INVOICE_PARTS.INVOICE_ID WHERE ".PRFX."INVOICE.DATE >= ".$db->qstr($start_date)." AND ".PRFX."INVOICE.DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

####################################################
#  Sum Parts Sub Total in selected period          #
####################################################

function sum_parts_sub_total_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(INVOICE_PARTS_SUBTOTAL) AS sum FROM ".PRFX."INVOICE_PARTS INNER JOIN ".PRFX."INVOICE ON ".PRFX."INVOICE.INVOICE_ID = ".PRFX."INVOICE_PARTS.INVOICE_ID WHERE ".PRFX."INVOICE.DATE >= ".$db->qstr($start_date)." AND ".PRFX."INVOICE.DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Labour Section **/

##########################################################################
#  Count Total number of different labour items in selected period       #
##########################################################################

function count_total_number_of_different_labour_items_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT COUNT(*) AS count FROM ".PRFX."INVOICE_LABOUR INNER JOIN ".PRFX."INVOICE ON ".PRFX."INVOICE.INVOICE_ID = ".PRFX."INVOICE_LABOUR.INVOICE_ID WHERE ".PRFX."INVOICE.DATE >= ".$db->qstr($start_date)." AND ".PRFX."INVOICE.DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}
################################################################
#  Sum Total quantity of labour items in selected period       #
################################################################

function sum_total_quantity_of_labour_items_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(INVOICE_LABOUR_UNIT) AS sum FROM ".PRFX."INVOICE_LABOUR INNER JOIN ".PRFX."INVOICE ON ".PRFX."INVOICE.INVOICE_ID = ".PRFX."INVOICE_LABOUR.INVOICE_ID WHERE ".PRFX."INVOICE.DATE >= ".$db->qstr($start_date)." AND ".PRFX."INVOICE.DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

##################################################
#  Sum Labour Sub Totals in selected period      #
##################################################

function sum_labour_sub_totals_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(INVOICE_LABOUR_SUBTOTAL) AS sum FROM ".PRFX."INVOICE_LABOUR INNER JOIN ".PRFX."INVOICE ON ".PRFX."INVOICE.INVOICE_ID = ".PRFX."INVOICE_LABOUR.INVOICE_ID WHERE ".PRFX."INVOICE.DATE >= ".$db->qstr($start_date)." AND ".PRFX."INVOICE.DATE <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Expense Section **/

##################################################
#  Sum Expenses Net Amount in selected period    #
##################################################

function sum_expenses_net_amount_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(EXPENSE_NET_AMOUNT) AS sum FROM ".PRFX."EXPENSE WHERE EXPENSE_DATE  >= ".$db->qstr($start_date)." AND EXPENSE_DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}
##################################################
#  Sum Expenses Tax Amount in selected period    #
##################################################

function sum_expenses_tax_amount_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(EXPENSE_TAX_AMOUNT) AS sum FROM ".PRFX."EXPENSE WHERE EXPENSE_DATE  >= ".$db->qstr($start_date)." AND EXPENSE_DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

###################################################
#  Sum Expenses Gross Amount in selected period   #
###################################################

function sum_expenses_gross_amount_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(EXPENSE_GROSS_AMOUNT) AS sum FROM ".PRFX."EXPENSE WHERE EXPENSE_DATE  >= ".$db->qstr($start_date)." AND EXPENSE_DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Refunds Section **/

###################################################
#  Sum Refunds Net Amount in selected period      #
###################################################

function sum_refunds_net_amount_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(REFUND_NET_AMOUNT) AS sum FROM ".PRFX."REFUND WHERE REFUND_DATE  >= ".$db->qstr($start_date)." AND REFUND_DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

###################################################
#  Sum Refunds Tax Amount in selected period      #
###################################################

function sum_refunds_tax_amount_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(REFUND_TAX_AMOUNT) AS sum FROM ".PRFX."REFUND WHERE REFUND_DATE  >= ".$db->qstr($start_date)." AND REFUND_DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

###################################################
#  Sum Refunds Gross Amount in selected period    #
###################################################

function sum_refunds_gross_amount_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(REFUND_GROSS_AMOUNT) AS sum FROM ".PRFX."REFUND WHERE REFUND_DATE  >= ".$db->qstr($start_date)." AND REFUND_DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Invoice Section **/

########################################################################################
#  Sum of Invoice Sub totals (before tax and discounts are added) in selected period   #
########################################################################################

function sum_of_invoice_sub_totals_before_tax_and_disacounts_are_added_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(SUB_TOTAL) AS sum FROM ".PRFX."INVOICE WHERE DATE  >= ".$db->qstr($start_date)." AND DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

##################################################
#  Sum of discount amounts in selected period    #
##################################################

function sum_of_discount_amounts_in_selected_period($db, $start_date, $end_date) {
    
    global $smarty;
    
    $sql = "SELECT SUM(DISCOUNT) AS sum FROM ".PRFX."INVOICE WHERE DATE  >= ".$db->qstr($start_date)." AND DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

##################################################
#  Sum of TAX amounts in selected period         #
##################################################

function sum_of_tax_amounts_in_selected_period($db, $start_date, $end_date) {  
    
    global $smarty;
    
    $sql = "SELECT SUM(TAX) AS sum FROM ".PRFX."INVOICE WHERE DATE  >= ".$db->qstr($start_date)." AND DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

#############################################################
#  Sum of Invoice Total Amounts (Gross) in selected period  #
#############################################################

function sum_of_invoice_total_amounts_gross_in_selected_period($db, $start_date, $end_date) { 
    
    global $smarty;
    
    $sql = "SELECT SUM(TOTAL) AS sum FROM ".PRFX."INVOICE WHERE DATE  >= ".$db->qstr($start_date)." AND DATE  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_report_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Calculations Section **/

###########################
#  Taxable Profit Amount  # // not used
###########################

function taxable_profit_amount_for_the_selected_period() {
    
    // Taxable Profit = Invoiced - (Expenses - Refunds)
    
    
}