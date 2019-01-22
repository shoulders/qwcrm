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

/** Insert Functions **/

/** Get Functions **/

/** Update Functions **/

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

/** General Section */


/** Clients **/

#####################################
#    Get Client Overall Stats       #
#####################################

function get_client_overall_stats() {
    
    /** Dates **/
    
    $dateObject = new DateTime();    

    $dateObject->modify('first day of this month');
    $date_month_start = $dateObject->format('Y-m-d');

    $dateObject->modify('last day of this month');
    $date_month_end = $dateObject->format('Y-m-d');

    $date_year_start    = get_company_details('year_start');
    $date_year_end      = get_company_details('year_end');
    
    /* Build and return array */
    
    return array(
        "month_count"   =>  count_clients($date_month_start, $date_month_end),
        "year_count"    =>  count_clients($date_year_start, $date_year_end),
        "total_count"   =>  count_clients()
    );
    
}

#############################################
#    Count Clients                          #
#############################################

function count_clients($start_date = null, $end_date = null, $status = null) { 
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."client_records.client_id\n";    
    
    // Restrict by Status
    if($status) {        
        $whereTheseRecords .= " AND ".PRFX."client_records.active= ".$db->qstr($status);            
    }
        
    // Filter by Create Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND create_date >= ".$db->qstr($start_date)." AND create_date <= ".$db->qstr($end_date.' 23:59:59');
    }
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."client_records
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count the number of clients."));
    } else {
        
       return $rs->fields['count']; 
       
    }
    
}

/** Workorders **/

#####################################
#    Get Workorders Stats           #
#####################################

function get_workorders_stats($record_set, $start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array();
    
    // Common
    if($record_set) {
    
        $common_stats = array(
            "count_open"            =>  count_workorders('open', $start_date, $end_date, $employee_id, $client_id)            
        );

        $stats = array_merge($stats, $common_stats);
    
    }
    
    // Current
    if($record_set == 'current' || $record_set == 'all') {        
        
        $current_stats = array(            
            "count_unassigned"              =>  count_workorders('unassigned', $start_date, $end_date, $employee_id, $client_id),
            "count_assigned"                =>  count_workorders('assigned', $start_date, $end_date, $employee_id, $client_id),
            "count_waiting_for_parts"       =>  count_workorders('waiting_for_parts', $start_date, $end_date, $employee_id, $client_id),
            "count_scheduled"               =>  count_workorders('scheduled', $start_date, $end_date, $employee_id, $client_id),
            "count_with_client"             =>  count_workorders('with_client', $start_date, $end_date, $employee_id, $client_id),
            "count_on_hold"                 =>  count_workorders('on_hold', $start_date, $end_date, $employee_id, $client_id),
            "count_management"              =>  count_workorders('management', $start_date, $end_date, $employee_id, $client_id),
            "count_closed_without_invoice"  =>  count_workorders('closed_without_invoice', $start_date, $end_date, $employee_id, $client_id),
            "count_closed_with_invoice"     =>  count_workorders('closed_with_invoice', $start_date, $end_date, $employee_id, $client_id)
            
        );
        
        $stats = array_merge($stats, $current_stats);
    
    }
    
    // Overall
    if($record_set == 'overall' || $record_set == 'all') {       
        
        $overall_stats = array(
            "count_open"                    =>  count_workorders('open', $start_date, $end_date, $employee_id, $client_id),
            "count_opened"                  =>  count_workorders('opened', $start_date, $end_date, $employee_id, $client_id),            
            "count_closed"                  =>  count_workorders('closed', $start_date, $end_date, $employee_id, $client_id)
            
        );
        
        $stats = array_merge($stats, $overall_stats);
    
    }    
    
    return $stats;
    
}

#########################################
#     Count Work Orders                 #
#########################################

function count_workorders($status = null, $start_date = null, $end_date = null, $employee_id = null, $client_id =null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."workorder_records.workorder_id\n";  
    
    // Restrict by Status
    if($status) {
        
        // All Open Status workorders
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."workorder_records.is_closed = '0'";
                    
        // All Close Status workorders
        } elseif($status == 'close') {
            
            $whereTheseRecords .= " AND ".PRFX."workorder_records.is_closed = '1'";
                        
        // All Opened workorders
        } elseif($status == 'opened') {
            
            // do nothing here           

        // All Closed workorders
        } elseif($status == 'closed') {
            
            // these give slightly different results because of the ability to manually change status
            
            $whereTheseRecords .= " AND ".PRFX."workorder_records.is_closed = '1'";
            //$whereTheseRecords .= " AND ".PRFX."workorder_records.close_date != ''";   
        
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."workorder_records.status= ".$db->qstr($status);                       
            
        }
        
    }   
    
    // Filter by Date
    if($status == 'closed') {
        
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND close_date >= ".$db->qstr($start_date)." AND close_date <= ".$db->qstr($end_date.' 23:59:59');
        }
        
    } else {
        
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND open_date >= ".$db->qstr($start_date)." AND open_date <= ".$db->qstr($end_date.' 23:59:59');
        }
        
    }
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND client_id=".$db->qstr($client_id);
    }
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."workorder_records
            ".$whereTheseRecords;    
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count Work Orders for the defined status."));
        
    } else {      
        
        return  $rs->fields['count'];
        
    }
    
}

/** Schedules **/

############################################
#    Count Schedule items                  #  // Currently only used in schedule delete check
############################################

function count_schedules($workorder_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."schedule_records.schedule_id\n";  

    // Filter by workorder_id
    if($workorder_id) {
        $whereTheseRecords .= " AND workorder_id=".$db->qstr($workorder_id);
    }    
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."schedule_records
            ".$whereTheseRecords;   
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count schedule items for the specified Work Order."));
        
    } else {      
        
        return  $rs->fields['count'];
        
    }
    
}

/** Invoices **/

#####################################
#   Get All invoices stats          #
#####################################

function get_invoices_stats($record_set, $start_date = null, $end_date = null, $tax_type = null, $employee_id = null, $client_id = null) {
    
    $stats = array();
    
    // Common
    if($record_set) {
    
        $common_stats = array(
            "count_open"            =>  count_invoices('open', $start_date, $end_date, $tax_type, $employee_id, $client_id),            
        );

        $stats = array_merge($stats, $common_stats);
    
    }
    
    // Current
    if($record_set == 'current' || $record_set == 'all') {
    
        $current_stats = array(
            "count_pending"         =>  count_invoices('pending', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_unpaid"          =>  count_invoices('unpaid', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_partially_paid"  =>  count_invoices('partially_paid', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_paid"            =>  count_invoices('paid', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_in_dispute"      =>  count_invoices('in_dispute', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_overdue"         =>  count_invoices('overdue', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_collections"     =>  count_invoices('collections', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_refunded"        =>  count_invoices('refunded', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_cancelled"       =>  count_invoices('cancelled', $start_date, $end_date, $tax_type, $employee_id, $client_id)            
        );

        $stats = array_merge($stats, $current_stats);
    
    }
    
    // Overall
    if($record_set == 'overall' || $record_set == 'all') {       
        
        $overall_stats = array(
            "count_total"           =>  count_invoices(null, $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            //"count_open"            =>  count_invoices('open', $start_date, $end_date, $tax_type, $employee_id, $client_id), 
            "count_opened"          =>  count_invoices('opened', $start_date, $end_date, $tax_type, $employee_id, $client_id),
            "count_closed"          =>  count_invoices('closed', $start_date, $end_date, $tax_type, $employee_id, $client_id),   
            "count_discounted"      =>  count_invoices('discounted', $start_date, $end_date, $tax_type, $employee_id, $client_id),
            "count_outstanding"     =>  count_invoices('outstanding', $start_date, $end_date, $tax_type, $employee_id, $client_id),
            "count_deleted"         =>  count_invoices('deleted', $start_date, $end_date, $tax_type, $employee_id, $client_id),
            
            "sum_net_amount"        =>  sum_invoices_value('net_amount', null, null, null, null, $client_id),
            "sum_gross_amount"      =>  sum_invoices_value('gross_amount', null, null, null, null, $client_id),
            "sum_discounted"        =>  sum_invoices_value('discount_amount', null, null, null, null, $client_id),
            "sum_paid_amount"       =>  sum_invoices_value('paid_amount', null, null, null, null, $client_id),
            "sum_cancelled"         =>  sum_invoices_value('gross_amount', 'cancelled', null, null, null, $client_id),
            "sum_balance"           =>  sum_invoices_value('balance', null, null, null, null, $client_id)
            
        );
        
        $stats = array_merge($stats, $overall_stats);
    
    }    
    
    return $stats;
    
}

####################################################
#     Count Invoices                               #
####################################################

function count_invoices($status = null, $start_date = null, $end_date = null, $tax_type = null, $employee_id = null, $client_id = null) {   
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n";  
    
    // Restrict by Status
    if($status) {
        
        // All Open Status invoices
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.is_closed = '0'";
                    
        // All Close Status invoices
        } elseif($status == 'close') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.is_closed = '1'";
                        
        // All Opened workorders
        } elseif($status == 'opened') {
            
            // do nothing here           

        // All Closed invoices
        } elseif($status == 'closed') {
            
            // these give slightly different results because of the ability to manually change status
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.is_closed = '1'";
            //$whereTheseRecords .= " AND ".PRFX."invoice_records.close_date != ''";            
        
        // All Discounted invoices
        } elseif($status == 'discounted') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.discount_amount > 0";
            
        // All invoices with outstanding balances
        } elseif($status == 'outstanding') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.balance > 0";
            $whereTheseRecords .= " AND ".PRFX."invoice_records.is_closed = 0";
                     
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.status= ".$db->qstr($status);                       
            
        }
        
    }
        
    // Filter by Date
    if($status == 'closed') {
        
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND close_date >= ".$db->qstr($start_date)." AND close_date <= ".$db->qstr($end_date.' 23:59:59');
        } 
        
    } else {
        
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND open_date >= ".$db->qstr($start_date)." AND open_date <= ".$db->qstr($end_date.' 23:59:59');
        }
        
    }
    
    // Filter by Tax Type
    if($tax_type) {
        $whereTheseRecords .= " AND tax_type=".$db->qstr($tax_type);
    }
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND client_id=".$db->qstr($client_id);
    }
    
    // Execute the SQL
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."invoice_records
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count the number of Invoices."));
    } else {
        
       return $rs->fields['count']; 
       
    }
    
}

#########################################
#  Sum selected value of invoices       #
#########################################

function sum_invoices_value($value_name, $status = null, $start_date = null, $end_date = null, $tax_type = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n"; 
    
    // Restrict by Status
    if($status) {
        
        // Filter by Unpaid Invoices
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.is_closed != '1'";
        
        } elseif($status == 'close') {
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.is_closed = '1'";        
        
        // Return Invoices for the given status
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.status= ".$db->qstr($status);
            
        }
        
    }
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    // Filter by Tax Type
    if($tax_type) {
        $whereTheseRecords .= " AND tax_type=".$db->qstr($tax_type);
    }
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND client_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND client_id=".$db->qstr($client_id);
    }
    
    // Execute the SQL
    $sql = "SELECT SUM(".PRFX."invoice_records.$value_name) AS sum
            FROM ".PRFX."invoice_records
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not sum the invoice values."));
    } else {
        
       return $rs->fields['sum']; 
       
    }    
    
}

/** Labour **/

#########################
#  Count labour items   #  // not currently used
#########################

function count_labour_items($start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(qty) AS count
            FROM ".PRFX."invoice_labour
            INNER JOIN ".PRFX."invoice_records ON ".PRFX."invoice_records.invoice_id = ".PRFX."invoice_labour.invoice_id
            WHERE ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the total number of labour items ordered."));
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

###################################
#  Count different labour items   #
###################################

function count_labour_different_items($start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."invoice_labour
            INNER JOIN ".PRFX."invoice_records ON ".PRFX."invoice_records.invoice_id = ".PRFX."invoice_labour.invoice_id
            WHERE ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the total number of labour items ordered."));
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

#########################################
#  Sum selected value of labour items   #
#########################################

function sum_labour_items($value_name, $start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(".PRFX."invoice_labour.$value_name) AS sum
            FROM ".PRFX."invoice_labour
            INNER JOIN ".PRFX."invoice_records ON ".PRFX."invoice_records.invoice_id = ".PRFX."invoice_labour.invoice_id
            WHERE ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum of labour items ordered."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Parts **/

########################
#  Count parts items   #  // not currently used
########################

function count_parts_items($start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(qty) AS count
            FROM ".PRFX."invoice_parts
            INNER JOIN ".PRFX."invoice_records ON ".PRFX."invoice_records.invoice_id = ".PRFX."invoice_parts.invoice_id
            WHERE ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the total number of different parts items ordered."));
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

##################################
#  Count different parts items   #
##################################

function count_parts_different_items($start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."invoice_parts
            INNER JOIN ".PRFX."invoice_records ON ".PRFX."invoice_records.invoice_id = ".PRFX."invoice_parts.invoice_id
            WHERE ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the total number of parts items ordered."));
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

###################################
#  Sum selected value of Parts    #
###################################

function sum_parts_value($value_name, $start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(".PRFX."invoice_parts.$value_name) AS sum
            FROM ".PRFX."invoice_parts
            INNER JOIN ".PRFX."invoice_records ON ".PRFX."invoice_records.invoice_id = ".PRFX."invoice_parts.invoice_id
            WHERE ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the total number of parts items ordered."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Expenses **/

#########################################
#     Count Expenses                    #  // Currently only used in invoice delete check
#########################################

function count_expenses($start_date = null, $end_date = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";  
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }

    // Filter by invoice_id
    if($invoice_id) {
        $whereTheseRecords .= " AND invoice_id=".$db->qstr($invoice_id);
    }
    
    // Execute the SQL
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."expense_records
            ".$whereTheseRecords;    
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count Expenses."));
        
    } else {      
        
        return $rs->fields['count'];
        
    }
    
}

###################################
#  Sum selected value of expenses #
###################################

function sum_expenses_value($value_name, $start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(".PRFX."expense_records.$value_name) AS sum
            FROM ".PRFX."expense_records
            WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected expenses."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Refunds **/

#########################################
#     Count Refunds                     #  // Currently only used in invoice delete check
#########################################

function count_refunds($start_date = null, $end_date = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."refund_records.refund_id\n";  
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }
    
    // Filter by invoice_id
    if($invoice_id) {
        $whereTheseRecords .= " AND invoice_id=".$db->qstr($invoice_id);
    }

    // Execute the SQL
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."refund_records
            ".$whereTheseRecords;    
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count Refunds."));
        
    } else {      
        
        return $rs->fields['count'];
        
    }
    
}

###################################
#  Sum selected value of Refunds  #
###################################

function sum_refunds_value($value_name, $start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(".PRFX."refund_records.$value_name) AS sum
            FROM ".PRFX."refund_records
            WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected refunds."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Otherincomes **/

#########################################
#     Count Other Incomes               #  // currently not used
#########################################

function count_otherincomes($start_date = null, $end_date = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."otherincome_records.refund_id\n";  
        
    // Filter by invoice_id
    if($invoice_id) {
        $whereTheseRecords .= " AND invoice_id=".$db->qstr($invoice_id);
    }
    
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    }

    // Execute the SQL
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."otherincome_records
            ".$whereTheseRecords;    
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count Other Incomes."));
        
    } else {      
        
        return $rs->fields['count'];
        
    }
    
}

#########################################
#  Sum selected value of Other Incomes  #
#########################################

function sum_otherincomes_value($value_name, $start_date, $end_date) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(".PRFX."otherincome_records.$value_name) AS sum
            FROM ".PRFX."otherincome_records
            WHERE date >= ".$db->qstr($start_date)." AND date <= ".$db->qstr($end_date);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected Other Incomes."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}