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

/** Workorders **/

#########################################
#     Count Work Orders                 #
#########################################

function count_workorders_with_status($db, $status, $start_date = null, $end_date = null) {
    
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
    
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND open_date >= ".$db->qstr($start_date)." AND open_date <= ".$db->qstr($end_date);
    }    
    
    $sql = "SELECT COUNT(*) AS workorder_count
            FROM ".PRFX."workorder
            ".$whereTheseRecords;          
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count Work Orders for the defined status."));
        exit;
        
    } else {      
        
        return  $rs->fields['workorder_count'];
        
    }
    
}

/** Invoices **/

####################################################
#     Count Invoices                               #
####################################################

function count_invoices($db, $status, $start_date = null, $end_date = null) {    
    
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // Filter by Unpaid Invoices
        if($status == 'unpaid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid != '1'";
        
        // Filter by Partially Paid Invoices
        } elseif($status == 'partially_paid') {
            
            $whereTheseRecords .= "AND is_paid=".$db->qstr(0)." AND balance <> total";
            
        }
            
        // Filter by Paid Invoices
        } elseif($status == 'paid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            //$whereTheseRecords .= " AND ".PRFX."invoice.status= ".$db->qstr($status);
            
        }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT COUNT(*) AS invoice_count
            FROM ".PRFX."invoice
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of Invoices."));
        exit;
    } else {
        
       return $rs->fields['invoice_count']; 
       
    }
    
}

###################################################
# Sum of Discounts for Invoices of a given status #
###################################################

function sum_invoices_discounts($db, $status, $start_date = null, $end_date = null) {
        
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // Filter by Unpaid Invoices
        if($status == 'unpaid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid != '1'";
        
        // Filter by Partially Paid Invoices
        } elseif($status == 'partially_paid') {
            
            $whereTheseRecords .= "AND is_paid=".$db->qstr(0)." AND balance <> total";
            
        }
            
        // Filter by Paid Invoices
        } elseif($status == 'paid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            //$whereTheseRecords .= " AND ".PRFX."invoice.status= ".$db->qstr($status);
            
        }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(discount_amount) AS discount_amount_sum
            FROM ".PRFX."invoice
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the invoice discount amounts."));
        exit;
    } else {
        
       return $rs->fields['discount_amount_sum']; 
       
    }
    
    
}

#############################################
# Sum of Invoices Balance                   #
#############################################

function sum_invoices_balance($db, $status, $start_date = null, $end_date = null) {
        
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // Filter by Unpaid Invoices
        if($status == 'unpaid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid != '1'";
        
        // Filter by Partially Paid Invoices
        } elseif($status == 'partially_paid') {
            
            $whereTheseRecords .= "AND is_paid=".$db->qstr(0)." AND balance <> total";
            
        }
            
        // Filter by Paid Invoices
        } elseif($status == 'paid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            //$whereTheseRecords .= " AND ".PRFX."invoice.status= ".$db->qstr($status);
            
        }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(balance) AS balance_sum
            FROM ".PRFX."invoice
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not sum the invoice balances."));
        exit;
    } else {
        
       return $rs->fields['balance_sum']; 
       
    }
        
}

#############################################
# Sum of Invoices Sub_total                  #
#############################################

function sum_invoices_sub_total($db, $status, $start_date = null, $end_date = null) {
        
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // Filter by Unpaid Invoices
        if($status == 'unpaid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid != '1'";
        
        // Filter by Partially Paid Invoices
        } elseif($status == 'partially_paid') {
            
            $whereTheseRecords .= "AND is_paid=".$db->qstr(0)." AND balance <> total";
            
        }
            
        // Filter by Paid Invoices
        } elseif($status == 'paid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            //$whereTheseRecords .= " AND ".PRFX."invoice.status= ".$db->qstr($status);
            
        }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(sub_total) AS sub_total_sum
            FROM ".PRFX."invoice
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return invoice sum of sub totals."));
        exit;
    } else {
        
       return $rs->fields['sub_total_sum']; 
       
    }
        
}

#############################################
#     Sum of Invoices Tax                   #
#############################################

function sum_invoices_tax($db, $status, $start_date = null, $end_date = null) {
        
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // Filter by Unpaid Invoices
        if($status == 'unpaid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid != '1'";
        
        // Filter by Partially Paid Invoices
        } elseif($status == 'partially_paid') {
            
            $whereTheseRecords .= "AND is_paid=".$db->qstr(0)." AND balance <> total";
            
        }
            
        // Filter by Paid Invoices
        } elseif($status == 'paid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            //$whereTheseRecords .= " AND ".PRFX."invoice.status= ".$db->qstr($status);
            
        }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(tax_amount) AS tax_amount_sum
            FROM ".PRFX."invoice
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sum of tax for the selected invoices."));
        exit;
    } else {
        
       return $rs->fields['tax_amount_sum']; 
       
    }
        
}

#############################################
#     Sum of Invoices Tax                   #
#############################################

function sum_invoices_total($db, $status, $start_date = null, $end_date = null) {
        
    // Default Action
    $whereTheseRecords = " WHERE invoice_id >= '0'";
    
    // Restrict by Status
    if($status != 'all') {
        
        // Filter by Unpaid Invoices
        if($status == 'unpaid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid != '1'";
        
        // Filter by Partially Paid Invoices
        } elseif($status == 'partially_paid') {
            
            $whereTheseRecords .= "AND is_paid=".$db->qstr(0)." AND balance <> total";
            
        }
            
        // Filter by Paid Invoices
        } elseif($status == 'paid') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice.is_paid = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            //$whereTheseRecords .= " AND ".PRFX."invoice.status= ".$db->qstr($status);
            
        }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(total) AS total_sum
            FROM ".PRFX."invoice
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sum of invoice totals for the selected invoices."));
        exit;
    } else {
        
       return $rs->fields['total_sum']; 
       
    }
        
}

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
    
    $sql = "SELECT COUNT(*) AS customer_count
            FROM ".PRFX."customer
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not count the number of customers."));
        exit;
    } else {
        
       return $rs->fields['customer_count']; 
       
    }
    
}

/** Parts Section (financial.tpl) **/

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
    
    $sql = "SELECT SUM(qty) AS sum FROM ".PRFX."invoice_parts INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_parts.invoice_id WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
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
    
    $sql = "SELECT SUM(sub_total) AS sum FROM ".PRFX."invoice_parts INNER JOIN ".PRFX."invoice ON ".PRFX."invoice.invoice_id = ".PRFX."invoice_parts.invoice_id WHERE ".PRFX."invoice.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the sub total of parts items ordered in the selected period."));
        exit;
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Labour Section (financial.tpl) **/

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

/** Expense Section (financial.tpl) **/

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

/** Refunds Section (financial.tpl) **/

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

/* Users */

#################################################
# Count a User's Work Orders for a given status #
#################################################

function count_user_workorders_with_status($db, $user_id, $workorder_status){
    
    // Default Action
    $whereTheseRecords = " WHERE employee_id=".$db->qstr($user_id);
    
    // All Open workorders
    if($workorder_status == 'open') {

        $whereTheseRecords .= " AND ".PRFX."workorder.is_closed != '1'";

    // All Closed workorders
    } elseif($workorder_status == 'closed') {

        $whereTheseRecords .= " AND ".PRFX."workorder.is_closed = '1'";

    // Return Workorders for the given status
    } else {

        $whereTheseRecords .= " AND ".PRFX."workorder.status =".$db->qstr($workorder_status);

    }
    
    $sql = "SELECT COUNT(*) AS workorder_status_count
            FROM ".PRFX."workorder
            ".$whereTheseRecords;
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the number of Work Orders for the user for the defined status."));
        exit;
    } else {
       
       return  $rs->fields['workorder_status_count']; 
       
    }
   
}

###############################################
# Count Employee Invoices for a given status  #
###############################################

function count_user_invoices_with_status($db, $user_id, $invoice_status){
    
    $sql = "SELECT COUNT(*) AS user_invoice_count
            FROM ".PRFX."invoice
            WHERE is_paid=".$db->qstr($invoice_status)."
            AND employee_id=".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed count the number of invoices for the user for the defined status."));
        exit;
   } else {
       
       return $rs->fields['user_invoice_count'];
       
   }
   
}