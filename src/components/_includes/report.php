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
        $whereTheseRecords .= " AND ".PRFX."client_records.create_date >= ".$db->qstr($start_date)." AND ".PRFX."client_records.create_date <= ".$db->qstr($end_date.' 23:59:59');
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
    
    // Historic
    if($record_set == 'historic' || $record_set == 'all') {       
        
        $historic_stats = array(
            "count_open"                    =>  count_workorders('open', $start_date, $end_date, $employee_id, $client_id),
            "count_opened"                  =>  count_workorders('opened', $start_date, $end_date, $employee_id, $client_id),            
            "count_closed"                  =>  count_workorders('closed', $start_date, $end_date, $employee_id, $client_id)
            
        );
        
        $stats = array_merge($stats, $historic_stats);
    
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
        
        if($status == 'open') {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.close_date = '0000-00-00 00:00:00'"; 
        } elseif($status == 'opened') {
            // Do nothing         
        } elseif($status == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.close_date != '0000-00-00 00:00:00'";  
        } else {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.status= ".$db->qstr($status);                       
        }
        
    }   
    
    // Filter by Date
    if($start_date && $end_date) {
        if($status == 'closed') {       
            $whereTheseRecords .= " AND ".PRFX."workorder_records.close_date >= ".$db->qstr($start_date)." AND ".PRFX."workorder_records.close_date <= ".$db->qstr($end_date.' 23:59:59');
        } else {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.open_date >= ".$db->qstr($start_date)." AND ".PRFX."workorder_records.open_date <= ".$db->qstr($end_date.' 23:59:59');
        }
    }
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."workorder_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."workorder_records.client_id=".$db->qstr($client_id);
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
        $whereTheseRecords .= " AND ".PRFX."workorder_records.workorder_id=".$db->qstr($workorder_id);
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

function get_invoices_stats($record_set, $start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array();
    
    // Current
    if($record_set == 'current' || $record_set == 'all') {
    
        $current_stats = array(
            "count_open"            =>  count_invoices('open', $start_date, $end_date, null, null, $employee_id, $client_id),
            "count_discounted"      =>  count_invoices('discounted', $start_date, $end_date, null, null, $employee_id, $client_id),
            "count_deleted"         =>  count_invoices('deleted', $start_date, $end_date, null, null, $employee_id, $client_id),         // Not always available  
            
            "count_pending"         =>  count_invoices('pending', $start_date, $end_date, null, null, $employee_id, $client_id),   
            "count_unpaid"          =>  count_invoices('unpaid', $start_date, $end_date, null, null, $employee_id, $client_id),   
            "count_partially_paid"  =>  count_invoices('partially_paid', $start_date, $end_date, null, null, $employee_id, $client_id),   
            "count_paid"            =>  count_invoices('paid', $start_date, $end_date, null, null, $employee_id, $client_id),   
            "count_in_dispute"      =>  count_invoices('in_dispute', $start_date, $end_date, null, null, $employee_id, $client_id),   
            "count_overdue"         =>  count_invoices('overdue', $start_date, $end_date, null, null, $employee_id, $client_id),   
            "count_collections"     =>  count_invoices('collections', $start_date, $end_date, null, null, $employee_id, $client_id),   
            "count_refunded"        =>  count_invoices('refunded', $start_date, $end_date, null, null, $employee_id, $client_id),   
            "count_cancelled"       =>  count_invoices('cancelled', $start_date, $end_date, null, null, $employee_id, $client_id)            
             
        );

        $stats = array_merge($stats, $current_stats);
    
    }
    
    // Historic
    if($record_set == 'historic' || $record_set == 'all') {       
        
        $historic_stats = array(                       
            "count_opened"              =>  count_invoices('opened', $start_date, $end_date, 'opened', null, $employee_id, $client_id),
            "count_closed"              =>  count_invoices('closed', $start_date, $end_date, 'closed', null, $employee_id, $client_id),            
                                    
            "count_closed_discounted"   =>  count_invoices('discounted', $start_date, $end_date, 'closed', null, $employee_id, $client_id),
            "count_closed_paid"         =>  count_invoices('paid', $start_date, $end_date, 'closed', null, $employee_id, $client_id),
            "count_closed_refunded"     =>  count_invoices('refunded', $start_date, $end_date, 'closed', null, $employee_id, $client_id),
            "count_closed_cancelled"    =>  count_invoices('cancelled', $start_date, $end_date, 'closed', null, $employee_id, $client_id)
        );
        
        $stats = array_merge($stats, $historic_stats);
    
    }  
    
    // Revenue  - sort this
    if($record_set == 'revenue' || $record_set == 'all') {       
        
        $revenue_stats = array(                       
            
            // I should use payments for revenue calculation ??? This currently uses the invoice date and not payments    
            
            "sum_sub_total"             =>  sum_invoices_items('sub_total', 'open', $start_date, $end_date, null, null, $employee_id, $client_id),
            "sum_discount_amount"       =>  sum_invoices_items('discount_amount', 'open', $start_date, $end_date, null, null, $employee_id, $client_id),           
            "sum_net_amount"            =>  sum_invoices_items('net_amount', 'open', $start_date, $end_date, null, null, $employee_id, $client_id),
            "sum_tax_amount"            =>  sum_invoices_items('tax_amount', 'open', $start_date, $end_date, null, null, $employee_id, $client_id),           
            "sum_gross_amount"          =>  sum_invoices_items('gross_amount', 'open', $start_date, $end_date, null, null, $employee_id, $client_id),            
            "sum_paid_amount"           =>  sum_invoices_items('paid_amount', 'open', $start_date, $end_date, null, null, $employee_id, $client_id),         
            "sum_balance"               =>  sum_invoices_items('balance', 'open', $start_date, $end_date, null, null, $employee_id, $client_id),            
            
            "sum_sales_tax_amount"      =>  sum_invoices_items('tax_amount', 'open', $start_date, $end_date, null, 'sales', $employee_id, $client_id),
            "sum_vat_tax_amount"        =>  sum_invoices_items('tax_amount', 'open', $start_date, $end_date, null, 'vat', $employee_id, $client_id),
            
            "sum_refunded_net"         =>  sum_invoices_items('net_amount', 'refunded', $start_date, $end_date, null, null, $employee_id, $client_id),
            "sum_refunded_gross"       =>  sum_invoices_items('gross_amount', 'refunded', $start_date, $end_date, null, null, $employee_id, $client_id),
            
            "sum_cancelled_net"         =>  sum_invoices_items('net_amount', 'cancelled', $start_date, $end_date, null, null, $employee_id, $client_id),
            "sum_cancelled_gross"       =>  sum_invoices_items('gross_amount', 'cancelled', $start_date, $end_date, null, null, $employee_id, $client_id)            
            
        );
        
        $stats = array_merge($stats, $revenue_stats);
    
    } 
    
    return $stats;
    
}

#####################################
#  Build invoice Status filter SQL  #
#####################################

function invoice_build_filter_by_status($status = null) {
    
    $db = QFactory::getDbo();
     
    $whereTheseRecords = '';
    
    if($status) {   
        if($status == 'open') {            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.close_date = '0000-00-00 00:00:00'";                  
        } elseif($status == 'opened') {            
            // Do nothing                 
        } elseif($status == 'closed') {            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.close_date != '0000-00-00 00:00:00'"; 
        } elseif($status == 'discounted') {            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.discount_amount > 0";                    
        } else {            
            $whereTheseRecords .= " AND ".PRFX."invoice_records.status= ".$db->qstr($status);            
        }
    }
        
    return $whereTheseRecords;
    
}

#####################################
#   Build invoice Date filter SQL   #
#####################################

function invoice_build_filter_by_date($start_date = null, $end_date = null, $date_type = null) {
    
    $db = QFactory::getDbo();
     
    $whereTheseRecords = '';
    
    if($start_date && $end_date) {
        if($date_type == 'opened') {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.open_date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.open_date <= ".$db->qstr($end_date.' 23:59:59');
        } elseif($date_type == 'closed') {       
            $whereTheseRecords .= " AND ".PRFX."invoice_records.close_date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.close_date <= ".$db->qstr($end_date.' 23:59:59');
        } elseif($date_type == 'active') {       
            $whereTheseRecords .= " AND ".PRFX."invoice_records.last_active >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.last_active <= ".$db->qstr($end_date.' 23:59:59');
        } elseif($date_type == 'date') {       
            $whereTheseRecords .= " AND ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
        } elseif($date_type == 'due_date') {       
            $whereTheseRecords .= " AND ".PRFX."invoice_records.due_date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.due_date <= ".$db->qstr($end_date);
        } else {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
        }
    }
        
    return $whereTheseRecords;
    
}

####################################################
#     Count Invoices                               #
####################################################

function count_invoices($status = null, $start_date = null, $end_date = null,  $date_type = null, $tax_type = null, $employee_id = null, $client_id = null) {   
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n";  
    
    // Restrict by Status
    $whereTheseRecords .= invoice_build_filter_by_status($status);
            
    // Filter by Date
    $whereTheseRecords .= invoice_build_filter_by_date($status, $start_date, $end_date, $date_type);
    
    // Filter by Tax Type
    if($tax_type) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.tax_type=".$db->qstr($tax_type);
    }
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$db->qstr($client_id);
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

function sum_invoices_items($value_name, $status = null, $start_date = null, $end_date = null, $date_type = null, $tax_type = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n"; 
    
    // Restrict by Status
    $whereTheseRecords .= invoice_build_filter_by_status($status);
            
    // Filter by Date
    $whereTheseRecords .= invoice_build_filter_by_date($start_date, $end_date, $date_type);
    
    // Filter by Tax Type
    if($tax_type) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.tax_type=".$db->qstr($tax_type);
    }
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$db->qstr($client_id);
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

#####################################
#   Get All invoices labour stats   #
#####################################

function get_labour_stats($start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array(
        "count_items"    =>  count_labour_items(null, $start_date, $end_date, $employee_id, $client_id),              // Total Different Items
        "sum_items"      =>  sum_labour_items('qty', null, $start_date, $end_date, $employee_id, $client_id),         // Total Items
        "sum_sub_total"  =>  sum_labour_items('sub_total', null, $start_date, $end_date, $employee_id, $client_id)    // Total net amount for labour
    );

    return $stats;
    
}

#########################
#  Count labour items   #
#########################

function count_labour_items($status, $start_date = null, $end_date = null, $date_type = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();    
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."invoice_labour.invoice_labour_id\n";    
    
    // Restrict by Status
    $whereTheseRecords .= invoice_build_filter_by_status($status);
            
    // Filter by Date
    $whereTheseRecords .= invoice_build_filter_by_date($start_date, $end_date, $date_type);
        
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$db->qstr($client_id);
    }
        
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."invoice_labour
            LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_labour.invoice_id = ".PRFX."invoice_records.invoice_id
            ".$whereTheseRecords;    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the total number of selected labour items."));
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

#########################################
#  Sum selected value of labour items   #
#########################################

function sum_labour_items($value_name, $status = null, $start_date = null, $end_date = null, $date_type = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();   
    
    // Prevent ambiguous error
    $value_name = PRFX."invoice_labour.".$value_name;
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."invoice_labour.invoice_labour_id\n"; 
    
    // Restrict by Status
    $whereTheseRecords .= invoice_build_filter_by_status($status);
            
    // Filter by Date
    $whereTheseRecords .= invoice_build_filter_by_date($start_date, $end_date, $date_type);
        
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$db->qstr($client_id);
    }
    
    $sql = "SELECT SUM($value_name) AS sum
            FROM ".PRFX."invoice_labour
            LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_labour.invoice_id = ".PRFX."invoice_records.invoice_id
            ".$whereTheseRecords;
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum of labour items selected."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Parts **/

#####################################
#   Get All invoices parts stats    #
#####################################

function get_parts_stats($start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array(
        "count_items"    =>  count_parts_items(null, $start_date, $end_date, $employee_id, $client_id),              // Total Different Items
        "sum_items"      =>  sum_parts_items('qty', null, $start_date, $end_date, $employee_id, $client_id),         // Total Items
        "sum_sub_total"  =>  sum_parts_items('sub_total', null, $start_date, $end_date, $employee_id, $client_id)    // Total net amount for labour
    );

    return $stats;
    
}
        
########################
#  Count parts items   #
########################

function count_parts_items($status = null, $start_date = null, $end_date = null, $date_type = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();    
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."invoice_parts.invoice_parts_id\n";    
    
    // Restrict by Status
    $whereTheseRecords .= invoice_build_filter_by_status($status);
            
    // Filter by Date
    $whereTheseRecords .= invoice_build_filter_by_date($start_date, $end_date, $date_type);
        
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$db->qstr($client_id);
    }
        
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."invoice_parts
            LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_parts.invoice_id = ".PRFX."invoice_records.invoice_id
            ".$whereTheseRecords;    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the total number of selected parts items."));
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}


###################################
#  Sum selected value of Parts    #
###################################

function sum_parts_items($value_name, $status = null, $start_date = null, $end_date = null, $date_type = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();   
    
    // Prevent ambiguous error
    $value_name = PRFX."invoice_parts.".$value_name;
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."invoice_parts.invoice_parts_id\n"; 
    
    // Restrict by Status
    $whereTheseRecords .= invoice_build_filter_by_status($status);
            
    // Filter by Date
    $whereTheseRecords .= invoice_build_filter_by_date($start_date, $end_date, $date_type);
        
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$db->qstr($client_id);
    }
    
    $sql = "SELECT SUM($value_name) AS sum
            FROM ".PRFX."invoice_parts
            LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_parts.invoice_id = ".PRFX."invoice_records.invoice_id
            ".$whereTheseRecords;
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum of labour items selected."));
    } else {
        
        return $rs->fields['sum'];
        
    }  
    
}

/** Expenses **/

#####################################
#   Get expense stats               #
#####################################

function get_expenses_stats($start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array(
        "count_items"       =>  count_expenses($start_date, $end_date, $employee_id, $client_id),
        "sum_net_amount"    =>  sum_expenses_items('net_amount', $start_date, $end_date, $employee_id, $client_id),
        "sum_vat_amount"    =>  sum_expenses_items('vat_amount', $start_date, $end_date, $employee_id, $client_id),
        "sum_gross_amount"  =>  sum_expenses_items('gross_amount', $start_date, $end_date, $employee_id, $client_id)
    );

    return $stats;
    
}


#########################################
#     Count Expenses                    #  // Currently only used in invoice delete check
#########################################

function count_expenses($start_date = null, $end_date = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";  
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND ".PRFX."expense_records.date >= ".$db->qstr($start_date)." AND ".PRFX."expense_records.date <= ".$db->qstr($end_date);
    }

    // Filter by invoice_id
    if($invoice_id) {
        $whereTheseRecords .= " AND ".PRFX."expense_records.invoice_id=".$db->qstr($invoice_id);
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

function sum_expenses_items($value_name, $start_date = null, $end_date = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";  
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND ".PRFX."expense_records.date >= ".$db->qstr($start_date)." AND ".PRFX."expense_records.date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(".PRFX."expense_records.$value_name) AS sum
            FROM ".PRFX."expense_records
            ".$whereTheseRecords; 
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected expenses."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Refunds **/

#####################################
#   Get refund stats                #
#####################################

function get_refunds_stats($start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array(
        "count_items"       =>  count_refunds($start_date, $end_date, $employee_id, $client_id),
        "sum_net_amount"    =>  sum_refunds_items('net_amount', $start_date, $end_date, $employee_id, $client_id),
        "sum_vat_amount"    =>  sum_refunds_items('vat_amount', $start_date, $end_date, $employee_id, $client_id),
        "sum_gross_amount"  =>  sum_refunds_items('gross_amount', $start_date, $end_date, $employee_id, $client_id)
    );

    return $stats;
    
}


#########################################
#     Count Refunds                     #
#########################################

function count_refunds($start_date = null, $end_date = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."refund_records.refund_id\n";  
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND ".PRFX."refund_records.date >= ".$db->qstr($start_date)." AND ".PRFX."refund_records.date <= ".$db->qstr($end_date);
    }

    // Filter by invoice_id
    if($invoice_id) {
        $whereTheseRecords .= " AND ".PRFX."refund_records.invoice_id=".$db->qstr($invoice_id);
    }
    
    // Execute the SQL
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."refund_records
            ".$whereTheseRecords;    
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count refunds."));
        
    } else {      
        
        return $rs->fields['count'];
        
    }
    
}


###################################
#  Sum selected value of Refunds  #
###################################

function sum_refunds_items($value_name, $start_date = null, $end_date = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."refund_records.refund_id\n";  
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND ".PRFX."refund_records.date >= ".$db->qstr($start_date)." AND ".PRFX."refund_records.date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(".PRFX."refund_records.$value_name) AS sum
            FROM ".PRFX."refund_records
            ".$whereTheseRecords; 
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected refunds."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Other Incomes **/

#####################################
#   Get Otherincomes stats          #
#####################################

function get_otherincomes_stats($start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array(
        "count_items"       =>  count_otherincomes($start_date, $end_date, $employee_id, $client_id),
        "sum_net_amount"    =>  sum_otherincomes_items('net_amount', $start_date, $end_date, $employee_id, $client_id),
        "sum_vat_amount"    =>  sum_otherincomes_items('vat_amount', $start_date, $end_date, $employee_id, $client_id),
        "sum_gross_amount"  =>  sum_otherincomes_items('gross_amount', $start_date, $end_date, $employee_id, $client_id)
    );

    return $stats;
    
}

#########################################
#     Count Other Incomes               #
#########################################

function count_otherincomes($start_date = null, $end_date = null, $invoice_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";  
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND ".PRFX."otherincome_records.date >= ".$db->qstr($start_date)." AND ".PRFX."otherincome_records.date <= ".$db->qstr($end_date);
    }

    // Filter by invoice_id
    if($invoice_id) {
        $whereTheseRecords .= " AND ".PRFX."otherincome_records.invoice_id=".$db->qstr($invoice_id);
    }
    
    // Execute the SQL
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."otherincome_records
            ".$whereTheseRecords;    
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count other incomes."));
        
    } else {      
        
        return $rs->fields['count'];
        
    }
    
}

#########################################
#  Sum selected value of Other Incomes  #
#########################################

function sum_otherincomes_items($value_name, $start_date = null, $end_date = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";  
        
    // Filter by Date
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND ".PRFX."otherincome_records.date >= ".$db->qstr($start_date)." AND ".PRFX."otherincome_records.date <= ".$db->qstr($end_date);
    }
    
    $sql = "SELECT SUM(".PRFX."otherincome_records.$value_name) AS sum
            FROM ".PRFX."otherincome_records
            ".$whereTheseRecords; 
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected other incomes."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

/** Suppliers **/

#############################################
#    Count Suppliers                        #  // not currently used
#############################################

function count_suppliers() { 
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."supplier_records";
                           

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count the number of suppliers."));
    } else {
        
       return $rs->fields['count']; 
       
    }
    
}

/** Gift Certificates **/

#####################################
#   Get giftcerts stats             #
#####################################

function get_giftcerts_stats($record_set, $start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array();
    
    // Current
    if($record_set == 'current' || $record_set == 'all') {
    
        $current_stats = array(
                        
            "count_open"        =>  count_giftcerts('open', $start_date, $end_date, null, $employee_id, $client_id),
            
            "count_unused"      =>  count_giftcerts('unused', $start_date, $end_date, null, $employee_id, $client_id),
            "count_redeemed"    =>  count_giftcerts('redeemed', $start_date, $end_date, null,  $employee_id, $client_id),
            "count_suspended"   =>  count_giftcerts('suspended', $start_date, $end_date, null, $employee_id, $client_id),            
            "count_expired"     =>  count_giftcerts('expired', $start_date, $end_date, null, $employee_id, $client_id),
            "count_refunded"    =>  count_giftcerts('refunded', $start_date, $end_date, null, $employee_id, $client_id),
            "count_cancelled"   =>  count_giftcerts('refunded', $start_date, $end_date, null, $employee_id, $client_id),
            "count_deleted"     =>  count_giftcerts('deleted', $start_date, $end_date, null, $employee_id, $client_id), // not always available                
             
        );

        $stats = array_merge($stats, $current_stats);
    
    }
    
    // Historic
    if($record_set == 'historic' || $record_set == 'all') {       
        
        $historic_stats = array(                       
            "count_opened"      =>  count_giftcerts('opened', $start_date, $end_date, null, $employee_id, $client_id),
            "count_closed"      =>  count_giftcerts('closed', $start_date, $end_date, null, $employee_id, $client_id),

            // This is where the client has used a giftcert from someone else      
            "count_claimed"     =>  count_giftcerts('claimed', $start_date, $end_date, null, $employee_id, $client_id),
            
        );
        
        $stats = array_merge($stats, $historic_stats);
    
    }  
    
    // Revenue
    if($record_set == 'revenue' || $record_set == 'all') {       
        
        $revenue_stats = array(                       
            
            "sum_opened"        =>  sum_giftcerts_items('amount', 'opened', $start_date, $end_date, null, $employee_id, $client_id),
            "sum_closed"        =>  sum_giftcerts_items('amount', 'closed', $start_date, $end_date, null, $employee_id, $client_id),
            
            "sum_unused"        =>  sum_giftcerts_items('amount', 'unused', $start_date, $end_date, null, $employee_id, $client_id),
            "sum_redeemed"      =>  sum_giftcerts_items('amount', 'redeemed', $start_date, $end_date, null, $employee_id, $client_id),
            "sum_suspended"     =>  sum_giftcerts_items('amount', 'suspended', $start_date, $end_date, null, $employee_id, $client_id),            
            "sum_expired"       =>  sum_giftcerts_items('amount', 'expired', $start_date, $end_date, null, $employee_id, $client_id),
            "sum_refunded"      =>  sum_giftcerts_items('amount', 'refunded', $start_date, $end_date, null, $employee_id, $client_id),
            "sum_cancelled"     =>  sum_giftcerts_items('amount', 'cancelled', $start_date, $end_date, null, $employee_id, $client_id),
            
            // This is where the client has used a giftcert from someone else
            "sum_claimed"       =>  sum_giftcerts_items('amount', 'claimed', $start_date, $end_date, 'date', $employee_id, $client_id)
            
        );
        
        $stats = array_merge($stats, $revenue_stats);
    
    }     
       
    return $stats;
    
}

#####################################
#  Build giftcert Status filter SQL  #
#####################################

function giftcert_build_filter_by_status($status = null, $client_id = null) {
    
    $db = QFactory::getDbo();
     
    $whereTheseRecords = '';
        
    if($status) {
                
        if($status == 'open') {            
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.close_date = '0000-00-00 00:00:00'";
        } elseif($status == 'opened') {
            // Do nothing
        } elseif($status == 'expired') {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.expiry_date != '0000-00-00 00:00:00'";   
        } elseif($status == 'redeemed') {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.redeem_date != '0000-00-00 00:00:00'";   
        } elseif($status == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.close_date != '0000-00-00 00:00:00'";
        } elseif($status == 'claimed' && $client_id) {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.status = 'redeemed'";
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.redeemed_client_id = ".$db->qstr($client_id);
        } else {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.status = ".$db->qstr($status);                       
        }
        
    }
        
    return $whereTheseRecords;
    
}

#####################################
#   Build giftcert Date filter SQL   #
#####################################

function giftcert_build_filter_by_date($start_date = null,  $end_date = null, $date_type = null) {
    
    $db = QFactory::getDbo();
     
    $whereTheseRecords = '';
        
    if($start_date && $end_date) {
        
        if($date_type == 'opened') {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.open_date >= ".$db->qstr($start_date)." AND ".PRFX."giftcert_records.open_date <= ".$db->qstr($end_date.' 23:59:59');
        } elseif($date_type== 'expired') {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.expiry_date >= ".$db->qstr($start_date)." AND ".PRFX."giftcert_records.expiry_date <= ".$db->qstr($end_date.' 23:59:59');
        } elseif($date_type == 'redeemed') {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.redeem_date >= ".$db->qstr($start_date)." AND ".PRFX."giftcert_records.redeem_date <= ".$db->qstr($end_date.' 23:59:59');
        } elseif($date_type == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.close_date >= ".$db->qstr($start_date)." AND ".PRFX."giftcert_records.close_date <= ".$db->qstr($end_date.' 23:59:59');
        } elseif($date_type == 'date') {       
            $whereTheseRecords .= " AND ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
        } elseif($date_type == 'due_date') {       
            $whereTheseRecords .= " AND ".PRFX."invoice_records.due_date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.due_date <= ".$db->qstr($end_date);
        } else {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.date >= ".$db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$db->qstr($end_date);
        }
        
    }
        
    return $whereTheseRecords;
    
}

#########################################
#     Count giftcerts                   #
#########################################

function count_giftcerts($status = null, $start_date = null, $end_date = null, $date_type = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."giftcert_records.giftcert_id\n";  
    
    // Restrict by Status
    $whereTheseRecords .= giftcert_build_filter_by_status($status, $client_id);
            
    // Filter by Date
    $whereTheseRecords .= giftcert_build_filter_by_date($start_date, $end_date, $date_type);

    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."giftcert_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id && $status != 'claimed') {
        $whereTheseRecords .= " AND ".PRFX."giftcert_records.client_id=".$db->qstr($client_id);
    }
    
    // Execute the SQL
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."giftcert_records
            LEFT JOIN ".PRFX."invoice_records ON ".PRFX."giftcert_records.invoice_id = ".PRFX."invoice_records.invoice_id
            ".$whereTheseRecords;    
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count gift certificates."));
        
    } else {      
        
        return $rs->fields['count'];
        
    }
    
}

###########################################
#  Sum selected value of gift certificate #
###########################################

function sum_giftcerts_items($value_name, $status = null, $start_date = null, $end_date = null, $date_type = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."giftcert_records.giftcert_id\n";  
        
    // Restrict by Status
    $whereTheseRecords .= giftcert_build_filter_by_status($status, $client_id);
            
    // Filter by Date
    $whereTheseRecords .= giftcert_build_filter_by_date($start_date, $end_date, $date_type);
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."giftcert_records.giftcert_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id && $status != 'claimed') {
        $whereTheseRecords .= " AND ".PRFX."giftcert_records.client_id=".$db->qstr($client_id);
    }
    
    $sql = "SELECT SUM(".PRFX."giftcert_records.$value_name) AS sum
            FROM ".PRFX."giftcert_records
            LEFT JOIN ".PRFX."invoice_records ON ".PRFX."giftcert_records.invoice_id = ".PRFX."invoice_records.invoice_id
            ".$whereTheseRecords;
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected gift certificates."));
    } else {
        
        return $rs->fields['sum'];
        
    }   
    
}

////////////////////////////////////////////////////////////

/** Payments **/

#####################################
#   Get All payments stats          #
#####################################

function get_payments_stats($record_set, $start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $stats = array();
    
    // Current
    if($record_set == 'current' || $record_set == 'all') {
    
        $current_stats = array(
            "count_valid"               =>  count_payments('valid', null, $start_date, $end_date, $employee_id, $client_id),
            "count_deleted"             =>  count_payments('deleted', null, $start_date, $end_date, $employee_id, $client_id)         // Not currently used                 
             
        );

        $stats = array_merge($stats, $current_stats);
    
    }
    
    // Historic
    if($record_set == 'historic' || $record_set == 'all') {       
        
        $historic_stats = array(                       
            
            "count_received"            =>  count_payments(null, 'received', $start_date, $end_date, $employee_id, $client_id),
            "count_transmitted"         =>  count_payments(null, 'transmitted', $start_date, $end_date, $employee_id, $client_id),
        );
        
        $stats = array_merge($stats, $historic_stats);
    
    }  
    
    // Revenue
    if($record_set == 'revenue' || $record_set == 'all') {       
        
        $revenue_stats = array(                       
            
            "sum_received"               =>  sum_payments_items(null, 'received', $start_date, $end_date, $employee_id, $client_id),
            "sum_transmitted"            =>  sum_payments_items(null, 'transmitted', $start_date, $end_date, $employee_id, $client_id) 
            
        );
        
        $stats = array_merge($stats, $revenue_stats);
    
    } 
    
    return $stats;
    
}

#####################################
#  Build payment Status filter SQL  #
#####################################

function payment_build_filter_by_status($status = null) {
    
    $db = QFactory::getDbo();
     
    $whereTheseRecords = '';
    
    if($status) {   
        $whereTheseRecords .= " AND ".PRFX."payment_records.status= ".$db->qstr($status);  
    }
        
    return $whereTheseRecords;
    
}

#####################################
#  Build payment Status filter SQL  #
#####################################

function payment_build_filter_by_type($type = null) {
    
    $db = QFactory::getDbo();
     
    $whereTheseRecords = '';
    
    if($type) {   
        if($type == 'received') {            
            $whereTheseRecords .= " AND ".PRFX."payment_records.type IN ('invoice')";
        } elseif($type == 'transmitted') {            
            $whereTheseRecords .= " AND ".PRFX."payment_records.type IN ('refund')";               
        } else {            
            $whereTheseRecords .= " AND ".PRFX."payment_records.type= ".$db->qstr($type);            
        }
    }
        
    return $whereTheseRecords;
    
}

#####################################
#   Build payment Date filter SQL   #
#####################################

function payment_build_filter_by_date($start_date = null, $end_date = null) {
    
    $db = QFactory::getDbo();
     
    $whereTheseRecords = '';
    
    if($start_date && $end_date) {
        $whereTheseRecords .= " AND ".PRFX."payment_records.date >= ".$db->qstr($start_date)." AND ".PRFX."payment_records.date <= ".$db->qstr($end_date);
    }
        
    return $whereTheseRecords;
    
}

####################################################
#     Count Payments                               #
####################################################

function count_payments($status = null, $type = null, $start_date = null, $end_date = null, $employee_id = null, $client_id = null) {   
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";  
    
    // Restrict by Status
    $whereTheseRecords .= payment_build_filter_by_status($status);
    
    // Restrict by Type
    $whereTheseRecords .= payment_build_filter_by_type($type); 
            
    // Filter by Date
    $whereTheseRecords .= payment_build_filter_by_date($status, $start_date, $end_date);
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$db->qstr($client_id);
    }
    
    // Execute the SQL
    $sql = "SELECT COUNT(*) AS count
            FROM ".PRFX."payment_records
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not count the number of payments."));
    } else {
        
       return $rs->fields['count']; 
       
    }
    
}

#########################################
#  Sum selected value of payments       #
#########################################

function sum_payments_items($status = null, $type = null, $start_date = null, $end_date = null, $employee_id = null, $client_id = null) {
    
    $db = QFactory::getDbo();
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n"; 
    
    // Restrict by Status
    $whereTheseRecords .= payment_build_filter_by_status($status);
      
    // Restrict by Type
    $whereTheseRecords .= payment_build_filter_by_type($type);    
          
    // Filter by Date
    $whereTheseRecords .= payment_build_filter_by_date($start_date, $end_date);
    
    // Filter by Employee
    if($employee_id) {
        $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$db->qstr($employee_id);
    }
    
    // Filter by Client
    if($client_id) {
        $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$db->qstr($client_id);
    }
    
    // Execute the SQL
    $sql = "SELECT SUM(".PRFX."payment_records.amount) AS sum
            FROM ".PRFX."payment_records
            ".$whereTheseRecords;                

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not sum the payment values."));
    } else {
        
       return $rs->fields['sum']; 
       
    }    
    
}
