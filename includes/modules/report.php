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
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."workorder WHERE work_order_open_date >= ".$db->qstr($start_date)." AND work_order_open_date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count open work orders in the selected period."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count closed work orders in selected period #
################################################

function count_open_workorders_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."workorder WHERE work_order_close_date >= ".$db->qstr($start_date)." AND work_order_close_date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count closed work orders in the selected period."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count New Customers in selected period      #
################################################

function count_new_customers_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."customer WHERE create_date >= ".$db->qstr($start_date)." AND create_date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count new customers in the selected period."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count Total Customers in QWcrm              #
################################################

function count_total_customers_in_qwcrm($db) {
    
    $sql = "SELECT COUNT(*) AS count FROM ".PRFX."customer";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the total number customers in QWcrm."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count Created Invoices in selected period   #
################################################

function count_created_invoices_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."invoice WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count created invoices in the selected period."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

################################################
#  Count Paid Invoices in selected period      #
################################################

function count_paid_invoices_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT count(*) AS count FROM ".PRFX."invoice WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date)." AND is_paid = 1";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count paid invoices in the selected period."));
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
    
    $sql = "SELECT COUNT(*) AS count FROM ".PRFX."invoice_parts INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_parts.invoice_id WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the total number of parts items ordered in the selected period."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

##########################################################################
#  Sum Total quantity of parts items ordered in selected period           #
##########################################################################

function sum_total_quantity_of_part_items_ordered_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(invoice_parts_count) AS sum FROM ".PRFX."invoice_parts INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_parts.invoice_id WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the total number of parts items ordered in the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

####################################################
#  Sum Parts Sub Total in selected period          #
####################################################

function sum_parts_sub_total_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(invoice_parts_subtotal) AS sum FROM ".PRFX."invoice_parts INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_parts.invoice_id WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sub total of parts items ordered in the selected period."));
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
    
    $sql = "SELECT COUNT(*) AS count FROM ".PRFX."invoice_labour INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_labour.invoice_id WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the total number of labour items ordered in the selected period."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}
################################################################
#  Sum Total quantity of labour items in selected period       #
################################################################

function sum_total_quantity_of_labour_items_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(invoice_labour_unit) AS sum FROM ".PRFX."invoice_labour INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_labour.invoice_id WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the total number of labour items ordered in the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

##################################################
#  Sum Labour Sub Totals in selected period      #
##################################################

function sum_labour_sub_totals_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(invoice_labour_subtotal) AS sum FROM ".PRFX."invoice_labour INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_labour.invoice_id WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sub total of labour items ordered in the selected period."));
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
    
    $sql = "SELECT SUM(expense_net_amount) AS sum FROM ".PRFX."expense WHERE expense_date  >= ".$db->qstr($start_date)." AND expense_date  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the expenses net total for the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}
##################################################
#  Sum Expenses Tax Amount in selected period    #
##################################################

function sum_expenses_tax_amount_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(expense_tax_amount) AS sum FROM ".PRFX."expense WHERE expense_date  >= ".$db->qstr($start_date)." AND expense_date  <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the expenses tax total for the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

###################################################
#  Sum Expenses Gross Amount in selected period   #
###################################################

function sum_expenses_gross_amount_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(expense_gross_amount) AS sum FROM ".PRFX."expense WHERE expense_date >= ".$db->qstr($start_date)." AND expense_date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the expenses Gross total for the selected period."));
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
    
    $sql = "SELECT SUM(refund_net_amount) AS sum FROM ".PRFX."refund WHERE refund_date >= ".$db->qstr($start_date)." AND refund_date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the refunds net total for the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

###################################################
#  Sum Refunds Tax Amount in selected period      #
###################################################

function sum_refunds_tax_amount_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(refund_tax_amount) AS sum FROM ".PRFX."refund WHERE refund_date >= ".$db->qstr($start_date)." AND refund_date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the refunds tax total for the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

###################################################
#  Sum Refunds Gross Amount in selected period    #
###################################################

function sum_refunds_gross_amount_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(refund_gross_amount) AS sum FROM ".PRFX."refund WHERE refund_date >= ".$db->qstr($start_date)." AND refund_date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the refunds Gross total for the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Invoice Section **/

########################################################################################
#  Sum of Invoice Sub totals (before tax and discounts are added) in selected period   #
########################################################################################

function sum_of_invoice_sub_totals_before_tax_and_discounts_are_added_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(sub_total) AS sum FROM ".PRFX."invoice WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return invoice sub totals before tax and discounts."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

##################################################
#  Sum of discount amounts in selected period    #
##################################################

function sum_of_discount_amounts_in_selected_period($db, $start_date, $end_date) {
    
    $sql = "SELECT SUM(discount) AS sum FROM ".PRFX."invoice WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sum of discounts in the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

##################################################
#  Sum of TAX amounts in selected period         #
##################################################

function sum_of_tax_amounts_in_selected_period($db, $start_date, $end_date) {  
    
    $sql = "SELECT SUM(tax) AS sum FROM ".PRFX."invoice WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sum of tax in the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

#############################################################
#  Sum of Invoice Total Amounts (Gross) in selected period  #
#############################################################

function sum_of_invoice_total_amounts_gross_in_selected_period($db, $start_date, $end_date) { 
    
    $sql = "SELECT SUM(total) AS sum FROM ".PRFX."invoice WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sum of invoice gross amounts in the selected period."));
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