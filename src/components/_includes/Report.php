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
 * Other Functions - All other public functions not covered above
 */

defined('_QWEXEC') or die;

class Report extends Components {

    /** Clients **/

    #####################################
    #    Get Client Stats               #
    #####################################

    public function getClientsStats($record_set, $start_date = null, $end_date = null) {

        $stats = array();

        // Basic
        if($record_set == 'basic' || $record_set == 'all') {   

            $stats['count_new'] = $this->countClients($start_date, $end_date);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {   

            $dateObject = new DateTime();    

            $dateObject->modify('first day of this month');
            $date_month_start = $dateObject->format('Y-m-d');

            $dateObject->modify('last day of this month');
            $date_month_end = $dateObject->format('Y-m-d');

            $date_year_start    = $this->app->components->company->getRecord('year_start');
            $date_year_end      = $this->app->components->company->getRecord('year_end');

            $stats['count_month'] = $this->countClients($date_month_start, $date_month_end);
            $stats['count_year']  = $this->countClients($date_year_start, $date_year_end);
            $stats['count_total'] = $this->countClients();

        }  

        return $stats;

    }

    #############################################
    #    Count Clients                          #
    #############################################

    public function countClients($start_date = null, $end_date = null, $status = null) { 

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."client_records.client_id\n";    

        // Filter by Create Date
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND ".PRFX."client_records.opened_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."client_records.opened_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
        }

        // Restrict by Status
        if($status) {        
            $whereTheseRecords .= " AND ".PRFX."client_records.active= ".$this->app->db->qstr($status);            
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."client_records
                ".$whereTheseRecords;                

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count the number of clients."));
        } else {

           return $rs->fields['count']; 

        }

    }

    /** Workorders **/

    #####################################
    #    Get Workorders Stats           #
    #####################################

    public function getWorkordersStats($record_set, $start_date = null, $end_date = null, $employee_id = null, $client_id = null) {

        $stats = array();

        // Common
        if($record_set) {

            $stats['count_open'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'open', $employee_id, $client_id);

        }

        // Current
        if($record_set == 'current' || $record_set == 'all') {        

            $stats['count_unassigned'] = $this->countWorkorders($start_date, $end_date, 'opened_on','unassigned', $employee_id, $client_id);
            $stats['count_assigned'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'assigned', $employee_id, $client_id);
            $stats['count_waiting_for_parts'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'waiting_for_parts',$employee_id, $client_id);
            $stats['count_scheduled'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'scheduled', $employee_id, $client_id);
            $stats['count_with_client'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'with_client', $employee_id, $client_id);
            $stats['count_on_hold'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'on_hold', $employee_id, $client_id);
            $stats['count_management'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'management', $employee_id, $client_id);
            $stats['count_closed_without_invoice'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'closed_without_invoice', $employee_id, $client_id);
            $stats['count_closed_with_invoice'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'closed_with_invoice', $employee_id, $client_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {        

            $stats['count_open'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'open', $employee_id, $client_id);
            $stats['count_opened'] = $this->countWorkorders($start_date, $end_date, 'opened_on', 'opened', $employee_id, $client_id);         
            $stats['count_closed'] = $this->countWorkorders($start_date, $end_date, 'closed_on', 'closed', $employee_id, $client_id);
            $stats['count_deleted'] = $this->countWorkorders(null, null, null, 'deleted', $employee_id, $client_id);   // Only used on basic stats

        }    

        return $stats;

    }

    #########################################
    #     Count Work Orders                 #
    #########################################

    public function countWorkorders($start_date = null, $end_date = null, $date_type = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."workorder_records.workorder_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->workorderBuildFilterByDate($start_date, $end_date, $date_type);

        // Restrict by Status
        $whereTheseRecords .= $this->workorderBuildFilterByStatus($status);      

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.client_id=".$this->app->db->qstr($client_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."workorder_records
                ".$whereTheseRecords;    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count Work Orders for the defined status."));

        } else {      

            return  $rs->fields['count'];

        }

    }

    ######################################
    #   Build workorder Date filter SQL  #
    ######################################

    public function workorderBuildFilterByDate($start_date = null, $end_date = null, $date_type = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date && $date_type) {
            if($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."workorder_records.opened_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."workorder_records.opened_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif($date_type == 'closed_on') {       
                $whereTheseRecords .= " AND ".PRFX."workorder_records.closed_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."workorder_records.closed_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif($date_type == 'last_active') {       
                $whereTheseRecords .= " AND ".PRFX."workorder_records.last_active >= ".$this->app->db->qstr($start_date)." AND ".PRFX."workorder_records.last_active <= ".$this->app->db->qstr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #######################################
    #  Build workorder Status filter SQL  #
    #######################################

    public function workorderBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        if($status) {   
            if($status == 'open') {            
                $whereTheseRecords .= " AND ".PRFX."workorder_records.closed_on = '0000-00-00 00:00:00'";                  
            } elseif($status == 'opened') {            
                // Do nothing                 
            } elseif($status == 'closed') {            
                $whereTheseRecords .= " AND ".PRFX."workorder_records.closed_on != '0000-00-00 00:00:00'"; 
            } else {            
                $whereTheseRecords .= " AND ".PRFX."workorder_records.status= ".$this->app->db->qstr($status);            
            }
        }

        return $whereTheseRecords;

    }

    /** Schedules **/

    ############################################
    #    Count Schedule items                  #
    ############################################

    public function countSchedules($workorder_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."schedule_records.schedule_id\n";  

        // Filter by workorder_id
        if($workorder_id) {
            $whereTheseRecords .= " AND ".PRFX."schedule_records.workorder_id=".$this->app->db->qstr($workorder_id);
        }    

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."schedule_records
                ".$whereTheseRecords;   

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count schedule items for the specified Work Order."));

        } else {      

            return  $rs->fields['count'];

        }

    }

    /** Invoices **/

    #####################################
    #   Get All invoices stats          #
    #####################################

    public function getInvoicesStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $client_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {    

            $stats['count_open'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'open', $employee_id, $client_id);
            $stats['count_discounted'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'discounted', $employee_id, $client_id);        
            $stats['count_pending'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'pending', $employee_id, $client_id);  
            $stats['count_unpaid'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'unpaid', $employee_id, $client_id); 
            $stats['count_partially_paid'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'partially_paid', $employee_id, $client_id);  
            $stats['count_paid'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'paid', $employee_id, $client_id);   
            $stats['count_in_dispute'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'in_dispute', $employee_id, $client_id);  
            $stats['count_overdue'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'overdue', $employee_id, $client_id);
            $stats['count_collections'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'collections', $employee_id, $client_id);  
            $stats['count_refunded'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'refunded', $employee_id, $client_id);
            $stats['count_cancelled'] = $this->countInvoices($start_date, $end_date, 'date', $tax_system, 'cancelled', $employee_id, $client_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {              

            $stats['count_opened'] = $this->countInvoices($start_date, $end_date, 'opened_on', $tax_system, 'opened', $employee_id, $client_id);
            $stats['count_closed'] = $this->countInvoices($start_date, $end_date, 'closed_on', $tax_system, 'closed', $employee_id, $client_id);
            $stats['count_closed_discounted'] = $this->countInvoices($start_date, $end_date, 'closed_on', $tax_system, 'discounted', $employee_id, $client_id);
            $stats['count_closed_paid'] = $this->countInvoices($start_date, $end_date, 'closed_on', $tax_system, 'paid', $employee_id, $client_id);
            $stats['count_closed_refunded'] = $this->countInvoices($start_date, $end_date, 'closed_on', $tax_system, 'refunded', $employee_id, $client_id);
            $stats['count_closed_cancelled'] = $this->countInvoices($start_date, $end_date, 'closed_on', $tax_system, 'cancelled', $employee_id, $client_id);

            // Only used on basic stats
            $stats['count_closed_deleted'] = $this->countInvoices($start_date, $end_date, null, $tax_system, 'deleted', $employee_id, $client_id);
            $stats['invoiced_total'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, null, $employee_id, $client_id);
            $stats['received_monies'] = $this->sumInvoices('unit_paid', $start_date, $end_date, 'date', $tax_system, null, $employee_id, $client_id);
            $stats['balance'] = $this->sumInvoices('balance', $start_date, $end_date, 'date', $tax_system, 'open', $employee_id, $client_id);

        }       

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {       

            $stats['sum_unit_discount'] = $this->sumInvoices('unit_discount', $start_date, $end_date, 'date', $tax_system, null, $employee_id, $client_id);        
            $stats['sum_unit_net'] = $this->sumInvoices('unit_net', $start_date, $end_date, 'date', $tax_system, null, $employee_id, $client_id);
            $stats['sum_unit_tax'] = $this->sumInvoices('unit_tax', $start_date, $end_date, 'date', $tax_system, null, $employee_id, $client_id);        
            $stats['sum_unit_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, null, $employee_id, $client_id);           
            $stats['sum_unit_paid'] = $this->sumInvoices('unit_paid', $start_date, $end_date, 'date', $tax_system, null, $employee_id, $client_id);       
            $stats['sum_balance'] = $this->sumInvoices('balance', $start_date, $end_date, 'date', $tax_system, null, $employee_id, $client_id);       

            // Only used on Client Tab        
            $stats['sum_pending_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'pending', $employee_id, $client_id);
            $stats['sum_unpaid_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'unpaid', $employee_id, $client_id);
            $stats['sum_partially_paid_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'partially_paid', $employee_id, $client_id);
            $stats['sum_paid_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'paid', $employee_id, $client_id);
            $stats['sum_in_dispute_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'in_dipute', $employee_id, $client_id);
            $stats['sum_overdue_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'overdue', $employee_id, $client_id);
            $stats['sum_collections_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'collections', $employee_id, $client_id);
            $stats['sum_refunded_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, 'refunded', $employee_id, $client_id);
            $stats['sum_cancelled_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, 'cancelled', $employee_id, $client_id);        
            $stats['sum_open_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'open', $employee_id, $client_id);
            $stats['sum_discounted_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'date', $tax_system, 'discounted', $employee_id, $client_id);  // Cannot remove cancelled with discount
            $stats['sum_opened_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'opened_on', $tax_system, 'opened', $employee_id, $client_id);
            $stats['sum_closed_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, 'closed', $employee_id, $client_id);
            $stats['sum_closed_discounted_gross'] = $this->sumInvoices('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, 'discounted', $employee_id, $client_id);  // Cannot remove cancelled with discount

            // Adjust for Cancelled records    
            $stats['sum_unit_discount'] -= $this->sumInvoices('unit_discount', $start_date, $end_date, 'closed_on', $tax_system, 'cancelled', $employee_id, $client_id); 
            $stats['sum_unit_net'] -= $this->sumInvoices('unit_net', $start_date, $end_date, 'closed_on', $tax_system, 'cancelled', $employee_id, $client_id);
            $stats['sum_unit_tax'] -= $this->sumInvoices('unit_tax', $start_date, $end_date, 'closed_on', $tax_system, 'cancelled', $employee_id, $client_id);        
            $stats['sum_unit_gross'] -= $this->sumInvoices('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, 'cancelled', $employee_id, $client_id);
            $stats['sum_unit_paid'] -= $this->sumInvoices('unit_paid', $start_date, $end_date, 'closed_on', $tax_system, 'cancelled', $employee_id, $client_id);
            $stats['sum_balance'] -= $this->sumInvoices('balance', $start_date, $end_date, 'closed_on', $tax_system, 'cancelled', $employee_id, $client_id);

        }

        // Labour
        if($record_set == 'labour' || $record_set == 'all') {        

            $stats['labour_count_items'] = $this->countLabourItems($start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);             // Total Different Items
            $stats['labour_sum_items'] = $this->sumLabourItems('unit_qty', $start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);        // Total Items
            $stats['labour_sum_sub_total_net'] = $this->sumLabourItems('sub_total_net', $start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);   // Total net amount for labour               
            $stats['labour_sum_sub_total_tax'] = $this->sumLabourItems('sub_total_tax', $start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);
            $stats['labour_sum_sub_total_gross'] = $this->sumLabourItems('sub_total_gross', $start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);

            // Adjust for Cancelled records  
            $stats['labour_count_items'] -= $this->countLabourItems($start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);
            $stats['labour_sum_items'] -= $this->sumLabourItems('unit_qty', $start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);
            $stats['labour_sum_sub_total_net'] -= $this->sumLabourItems('sub_total_net', $start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);
            $stats['labour_sum_sub_total_tax'] -= $this->sumLabourItems('sub_total_tax', $start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);
            $stats['labour_sum_sub_total_gross'] -= $this->sumLabourItems('sub_total_gross', $start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);        

        }

        // Parts
        if($record_set == 'parts' || $record_set == 'all') {        

            $stats['parts_count_items'] = $this->countPartsItems($start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);              // Total Different Items
            $stats['parts_sum_items'] = $this->sumPartsItems('unit_qty', $start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);         // Total Items
            $stats['parts_sum_sub_total_net'] = $this->sumPartsItems('sub_total_net', $start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);    // Total net amount for labour
            $stats['parts_sum_sub_total_tax'] = $this->sumPartsItems('sub_total_tax', $start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);
            $stats['parts_sum_sub_total_gross'] = $this->sumPartsItems('sub_total_gross', $start_date, $end_date, 'date', $tax_system, null, null, $employee_id, $client_id);

            // Adjust for Cancelled records  
            $stats['parts_count_items'] -= $this->countPartsItems($start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);
            $stats['parts_sum_items'] -= $this->sumPartsItems('unit_qty', $start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);
            $stats['parts_sum_sub_total_net'] -= $this->sumPartsItems('sub_total_net', $start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);
            $stats['parts_sum_sub_total_tax'] -= $this->sumPartsItems('sub_total_tax', $start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);
            $stats['parts_sum_sub_total_gross'] -= $this->sumPartsItems('sub_total_gross', $start_date, $end_date, 'closed_on', $tax_system, null, 'cancelled', $employee_id, $client_id);

        }   

        return $stats;

    }

    ####################################################
    #     Count Invoices                               #
    ####################################################

    public function countInvoices($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $status = null, $employee_id = null, $client_id = null) {   

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."invoice_records
                ".$whereTheseRecords;                

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count the number of Invoices."));
        } else {

           return $rs->fields['count']; 

        }

    }

    #########################################
    #  Sum selected value of invoices       #
    #########################################

    public function sumInvoices($value_name, $start_date = null, $end_date = null, $date_type = null, $tax_system = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n"; 

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Execute the SQL
        $sql = "SELECT SUM(".PRFX."invoice_records.$value_name) AS sum
                FROM ".PRFX."invoice_records
                ".$whereTheseRecords;                

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not sum the invoice values."));
        } else {

           return $rs->fields['sum']; 

        }    

    }

    #####################################
    #   Build invoice Date filter SQL   #
    #####################################

    public function invoiceBuildFilterByDate($start_date = null, $end_date = null, $date_type = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date && $date_type) {
            if ($date_type == 'date') {       
                $whereTheseRecords .= " AND ".PRFX."invoice_records.date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$this->app->db->qstr($end_date);
            } elseif ($date_type == 'due_date') {       
                $whereTheseRecords .= " AND ".PRFX."invoice_records.due_date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."invoice_records.due_date <= ".$this->app->db->qstr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."invoice_records.opened_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."invoice_records.opened_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {       
                $whereTheseRecords .= " AND ".PRFX."invoice_records.closed_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."invoice_records.closed_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'last_active') {       
                $whereTheseRecords .= " AND ".PRFX."invoice_records.last_active >= ".$this->app->db->qstr($start_date)." AND ".PRFX."invoice_records.last_active <= ".$this->app->db->qstr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #####################################
    #  Build invoice Status filter SQL  #
    #####################################

    public function invoiceBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        if($status) {   
            if($status == 'open') {            
                $whereTheseRecords .= " AND ".PRFX."invoice_records.closed_on = '0000-00-00 00:00:00'";                  
            } elseif($status == 'opened') {            
                // Do nothing                 
            } elseif($status == 'closed') {            
                $whereTheseRecords .= " AND ".PRFX."invoice_records.closed_on != '0000-00-00 00:00:00'"; 
            } elseif($status == 'discounted') {            
                $whereTheseRecords .= " AND ".PRFX."invoice_records.unit_discount > 0";                    
            } else {            
                $whereTheseRecords .= " AND ".PRFX."invoice_records.status= ".$this->app->db->qstr($status);            
            }
        }

        return $whereTheseRecords;

    }

    /** Labour **/

    #########################
    #  Count labour items   #
    #########################

    public function countLabourItems($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_labour.invoice_labour_id\n";    

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_labour.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."invoice_labour.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qstr($client_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."invoice_labour
                LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_labour.invoice_id = ".PRFX."invoice_records.invoice_id
                ".$whereTheseRecords;    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to count the total number of selected labour items."));
        } else {

            return $rs->fields['count']; 

        }   

    }

    #########################################
    #  Sum selected value of labour items   #
    #########################################

    public function sumLabourItems($value_name, $start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null) {

        // Prevent ambiguous error
        $value_name = PRFX."invoice_labour.".$value_name;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_labour.invoice_labour_id\n"; 

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_labour.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."invoice_labour.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }    

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_labour.invoice_id=".$this->app->db->qstr($invoice_id);
        }

        $sql = "SELECT SUM($value_name) AS sum
                FROM ".PRFX."invoice_labour
                LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_labour.invoice_id = ".PRFX."invoice_records.invoice_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the sum of labour items selected."));
        } else {

            return $rs->fields['sum'];

        }   

    }

    /** Parts **/

    ########################
    #  Count parts items   #
    ########################

    public function countPartsItems($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_parts.invoice_parts_id\n";    

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_parts.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."invoice_parts.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }    

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_parts.invoice_id=".$this->app->db->qstr($invoice_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."invoice_parts
                LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_parts.invoice_id = ".PRFX."invoice_records.invoice_id
                ".$whereTheseRecords;    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to count the total number of selected parts items."));
        } else {

            return $rs->fields['count']; 

        }   

    }

    ###################################
    #  Sum selected value of Parts    #
    ###################################

    public function sumPartsItems($value_name, $start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $status = null, $employee_id = null, $client_id = null) {

        // Prevent ambiguous error
        $value_name = PRFX."invoice_parts.".$value_name;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_parts.invoice_parts_id\n"; 

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_parts.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."invoice_parts.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }    

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qstr($client_id);
        }

        $sql = "SELECT SUM($value_name) AS sum
                FROM ".PRFX."invoice_parts
                LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_parts.invoice_id = ".PRFX."invoice_records.invoice_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the sum of labour items selected."));
        } else {

            return $rs->fields['sum'];

        }  

    }

    /** Vouchers **/

    #####################################
    #   Get Voucher stats               #
    #####################################

    public function getVouchersStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $client_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_open'] = $this->countVouchers($start_date, $end_date, 'date', $tax_system, null, null, 'open', $employee_id, $client_id);
            $stats['count_unused'] = $this->countVouchers($start_date, $end_date, 'date', $tax_system, null, null, 'unused', $employee_id, $client_id);
            $stats['count_redeemed'] = $this->countVouchers($start_date, $end_date, 'date', $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['count_suspended'] = $this->countVouchers($start_date, $end_date, 'date', $tax_system, null, null, 'suspended', $employee_id, $client_id);           
            $stats['count_expired'] = $this->countVouchers($start_date, $end_date, 'date', $tax_system, null, null, 'expired', $employee_id, $client_id);
            $stats['count_refunded'] = $this->countVouchers($start_date, $end_date, 'date', $tax_system, null, null, 'refunded', $employee_id, $client_id);
            $stats['count_cancelled'] = $this->countVouchers($start_date, $end_date, 'date', $tax_system, null, null, 'cancelled', $employee_id, $client_id);                                

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {       

            $stats['count_opened'] = $this->countVouchers($start_date, $end_date, 'opened_on', $tax_system, null, null, 'opened', $employee_id, $client_id);
            $stats['count_closed'] = $this->countVouchers($start_date, $end_date, 'closed_on', $tax_system, null, null, 'closed', $employee_id, $client_id);            
            $stats['count_claimed'] = $this->countVouchers($start_date, $end_date, 'closed_on', $tax_system, null, null, 'claimed', $employee_id, $client_id);  // This is where the client has used a Voucher from someone else  

        }  

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {       

            $stats['count_items'] = $this->countVouchers($start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);    
            $stats['sum_unit_net'] = $this->sumVouchers('unit_net', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);
            $stats['sum_unit_tax'] = $this->sumVouchers('unit_tax', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);
            $stats['sum_unit_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);        
            $stats['sum_redeemed_net'] = $this->sumVouchers('unit_net', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['sum_redeemed_tax'] = $this->sumVouchers('unit_tax', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['sum_redeemed_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'redeemed', $employee_id, $client_id);         
            $stats['sum_expired_net'] = $this->sumVouchers('unit_net', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'expired', $employee_id, $client_id);
            $stats['sum_expired_tax'] = $this->sumVouchers('unit_tax', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'expired', $employee_id, $client_id);
            $stats['sum_expired_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'expired', $employee_id, $client_id);

            // Used for VAT Flate Rate calculations (not currently used)
            //$stats['sum_voucher_spv_unit_gross'] = $this->sum_vouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, 'SPV', null, $employee_id, $client_id);
            //$stats['sum_voucher_mpv_unit_gross'] = $this->sum_vouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, 'MPV', null, $employee_id, $client_id);

            // Only used on Client Tab        
            $stats['sum_unused_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, 'unused', $employee_id, $client_id);
            //$stats['sum_redeemed_gross'] = $this->sum_vouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['sum_suspended_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, 'suspended', $employee_id, $client_id);         
            //$stats['sum_expired_gross'] = $this->sum_vouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, 'expired', $employee_id, $client_id);
            $stats['sum_refunded_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'refunded', $employee_id, $client_id);
            $stats['sum_cancelled_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id); 
            $stats['sum_open_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, 'open', $employee_id, $client_id);
            $stats['sum_opened_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, 'opened', $employee_id, $client_id);
            $stats['sum_closed_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'closed', $employee_id, $client_id);
            $stats['sum_claimed_gross'] = $this->sumVouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'claimed', $employee_id, $client_id);  // This is where the client has used a Voucher from someone else

            // Adjust for Cancelled records  
            $stats['count_items'] -= $this->countVouchers($start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);    
            $stats['sum_unit_net'] -= $this->sumVouchers('unit_net', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);
            $stats['sum_unit_tax'] -= $this->sumVouchers('unit_tax', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);
            $stats['sum_unit_gross'] -= $this->sumVouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);            
            //$stats['sum_voucher_spv_unit_gross'] -= $this->sum_vouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, 'SPV', 'cancelled', $employee_id, $client_id);        
            //$stats['sum_voucher_mpv_unit_gross'] -= $this->sum_vouchers('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, 'MPV', 'cancelled', $employee_id, $client_id);        

        }

        return $stats;

    }

    #########################################
    #     Count Vouchers                    #
    #########################################

    public function countVouchers($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $type = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."voucher_records.voucher_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->voucherBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }

        // Filter by Item Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.type=".$this->app->db->qstr($type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->voucherBuildFilterByStatus($status, $client_id);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Claimed status
        if($client_id && $status != 'claimed') {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.invoice_id=".$this->app->db->qstr($invoice_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."voucher_records
                LEFT JOIN ".PRFX."invoice_records ON ".PRFX."voucher_records.invoice_id = ".PRFX."invoice_records.invoice_id
                ".$whereTheseRecords;    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count Vouchers."));

        } else {      

            return $rs->fields['count'];

        }

    }

    ###########################################
    #  Sum selected value of Vouchers         #
    ###########################################

    public function sumVouchers($value_name, $start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $type = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."voucher_records.voucher_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->voucherBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }

        // Filter by Item Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.type=".$this->app->db->qstr($type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->voucherBuildFilterByStatus($status, $client_id);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.voucher_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id && $status != 'claimed') {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.invoice_id=".$this->app->db->qstr($invoice_id);
        }

        $sql = "SELECT SUM(".PRFX."voucher_records.$value_name) AS sum
                FROM ".PRFX."voucher_records
                LEFT JOIN ".PRFX."invoice_records ON ".PRFX."voucher_records.invoice_id = ".PRFX."invoice_records.invoice_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected Vouchers."));
        } else {

            return $rs->fields['sum'];

        }   

    }

    #####################################
    #  Build Voucher Status filter SQL  #
    #####################################

    public function voucherBuildFilterByStatus($status = null, $client_id = null) {

        $whereTheseRecords = '';

        if($status) {

            if($status == 'open') {            
                $whereTheseRecords .= " AND ".PRFX."voucher_records.closed_on = '0000-00-00 00:00:00'";
            } elseif($status == 'opened') {            
                // Do nothing                 
            } elseif($status == 'closed') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.closed_on != '0000-00-00 00:00:00'";
            } elseif($status == 'claimed' && $client_id) {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.status = 'redeemed'";
                $whereTheseRecords .= " AND ".PRFX."voucher_records.redeemed_client_id = ".$this->app->db->qstr($client_id);
            } else {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.status = ".$this->app->db->qstr($status);                       
            }

        }

        return $whereTheseRecords;

    }

    #####################################
    #   Build Voucher Date filter SQL   #
    #####################################

    public function voucherBuildFilterByDate($start_date = null, $end_date = null, $date_type = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date) {

            if($date_type == 'date') {       
                $whereTheseRecords .= " AND ".PRFX."invoice_records.date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."invoice_records.date <= ".$this->app->db->qstr($end_date);
            } elseif ($date_type == 'due_date') {       
                $whereTheseRecords .= " AND ".PRFX."invoice_records.due_date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."invoice_records.due_date <= ".$this->app->db->qstr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.opened_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."voucher_records.opened_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type== 'expiry') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.expiry_date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."voucher_records.expiry_date <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'redeemed_on') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.redeemed_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."voucher_records.redeemed_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.closed_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."voucher_records.closed_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            }

        }

        return $whereTheseRecords;

    }

    /** Refunds **/

    #####################################
    #   Get refund stats                #
    #####################################

    public function getRefundsStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $client_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_unpaid'] = $this->countRefunds($start_date, $end_date, 'date', $tax_system, null, null, 'unpaid', $employee_id, $client_id);
            $stats['count_partially_paid'] = $this->countRefunds($start_date, $end_date, 'date', $tax_system, null, null, 'partially_paid', $employee_id, $client_id);
            $stats['count_paid'] = $this->countRefunds($start_date, $end_date, 'date', $tax_system, null, null, 'paid', $employee_id, $client_id);          
            $stats['count_cancelled'] = $this->countRefunds($start_date, $end_date, 'date', $tax_system, null, null, 'cancelled', $employee_id, $client_id);       

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {          

            $stats['count_opened'] = $this->countRefunds($start_date, $end_date, 'date', $tax_system, null, null, 'opened', $employee_id, $client_id);
            $stats['count_closed'] = $this->countRefunds($start_date, $end_date, 'date', $tax_system, null, null, 'closed', $employee_id, $client_id); 

        }  

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {       

            $stats['count_items'] = $this->countRefunds($start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);
            $stats['sum_unit_net'] = $this->sumRefunds('unit_net', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);
            $stats['sum_unit_tax'] = $this->sumRefunds('unit_tax', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);           
            $stats['sum_unit_gross'] = $this->sumRefunds('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);                   
            $stats['sum_balance'] = $this->sumRefunds('balance', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id, $client_id);

            // Adjust for Cancelled records  
            $stats['count_items'] -= $this->countRefunds($start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);
            $stats['sum_unit_net'] -= $this->sumRefunds('unit_net', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);
            $stats['sum_unit_tax'] -= $this->sumRefunds('unit_tax', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);           
            $stats['sum_unit_gross'] -= $this->sumRefunds('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);
            $stats['sum_balance'] -= $this->sumRefunds('balance', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id, $client_id);        

        }     

        return $stats;

    }

    #########################################
    #     Count Refunds                     #
    #########################################

    public function countRefunds($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $item_type = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."refund_records.refund_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->refundBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }

        // Filter by Item Type
        if($item_type) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.item_type=".$this->app->db->qstr($item_type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->refundBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."refund_records
                ".$whereTheseRecords;    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count Refunds."));

        } else {      

            return $rs->fields['count'];

        }

    }

    ###########################################
    #  Sum selected value of refunds          #
    ###########################################

    public function sumRefunds($value_name, $start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $item_type = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."refund_records.refund_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->refundBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }

        // Filter by Item Type
        if($item_type) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.item_type=".$this->app->db->qstr($item_type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->refundBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."refund_records.client_id=".$this->app->db->qstr($client_id);
        }

        $sql = "SELECT SUM(".PRFX."refund_records.$value_name) AS sum
                FROM ".PRFX."refund_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected Refunds."));
        } else {

            return $rs->fields['sum'];

        }   

    }

    ######################################
    #   Build refund Date filter SQL     #
    ######################################

    public function refundBuildFilterByDate($start_date = null, $end_date = null, $date_type = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date && $date_type) {
            if ($date_type == 'date') {       
                $whereTheseRecords .= " AND ".PRFX."refund_records.date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."refund_records.date <= ".$this->app->db->qstr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."refund_records.opened_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."refund_records.opened_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {       
                $whereTheseRecords .= " AND ".PRFX."refund_records.closed_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."refund_records.closed_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'last_active') {       
                $whereTheseRecords .= " AND ".PRFX."refund_records.last_active >= ".$this->app->db->qstr($start_date)." AND ".PRFX."refund_records.last_active <= ".$this->app->db->qstr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #######################################
    #  Build refund Status filter SQL     #
    #######################################

    public function refundBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        if($status) {   
            if($status == 'open') {            
                $whereTheseRecords .= " AND ".PRFX."refund_records.closed_on = '0000-00-00 00:00:00'";                  
            } elseif($status == 'opened') {            
                // Do nothing                 
            } elseif($status == 'closed') {            
                $whereTheseRecords .= " AND ".PRFX."refund_records.closed_on != '0000-00-00 00:00:00'"; 
            } else {            
                $whereTheseRecords .= " AND ".PRFX."refund_records.status= ".$this->app->db->qstr($status);            
            }
        }

        return $whereTheseRecords;

    }

    /** Expenses **/

    #####################################
    #   Get expense stats               #
    #####################################

    public function getExpensesStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {    

            $stats['count_unpaid'] = $this->countExpenses($start_date, $end_date, 'date', $tax_system, null, null, 'unpaid', $employee_id);
            $stats['count_partially_paid'] = $this->countExpenses($start_date, $end_date, 'date', $tax_system, null, null, 'partially_paid', $employee_id);
            $stats['count_paid'] = $this->countExpenses($start_date, $end_date, 'date', $tax_system, null, null, 'paid', $employee_id);            
            $stats['count_cancelled'] = $this->countExpenses($start_date, $end_date, 'date', $tax_system, null, null, 'cancelled', $employee_id); 

        }

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {            

            $stats['count_items'] = $this->countExpenses($start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);
            $stats['sum_unit_net'] = $this->sumExpenses('unit_net', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);
            $stats['sum_unit_tax'] = $this->sumExpenses('unit_tax', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);       
            $stats['sum_unit_gross'] = $this->sumExpenses('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);                   
            $stats['sum_balance'] = $this->sumExpenses('balance', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);

            // Adjust for Cancelled records  
            $stats['count_items'] -= $this->countExpenses($start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);
            $stats['sum_unit_net'] -= $this->sumExpenses('unit_net', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);
            $stats['sum_unit_tax'] -= $this->sumExpenses('unit_tax', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);       
            $stats['sum_unit_gross'] -= $this->sumExpenses('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);
            $stats['sum_balance'] -= $this->sumExpenses('balance', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);   

        }        

        return $stats;

    }


    #########################################
    #     Count Expenses                    #
    #########################################

    public function countExpenses($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $item_type = null, $status = null, $employee_id = null, $invoice_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->expenseBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }    

        // Filter by Item Type
        if($item_type) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.item_type=".$this->app->db->qstr($item_type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->expenseBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by invoice_id
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.invoice_id=".$this->app->db->qstr($invoice_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."expense_records
                ".$whereTheseRecords;    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count Expenses."));

        } else {      

            return $rs->fields['count'];

        }

    }

    ###################################
    #  Sum selected value of expenses #
    ###################################

    public function sumExpenses($value_name, $start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $item_type = null, $status = null, $employee_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->expenseBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }      

        // Filter by Item Type
        if($item_type) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.item_type=".$this->app->db->qstr($item_type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->expenseBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        $sql = "SELECT SUM(".PRFX."expense_records.$value_name) AS sum
                FROM ".PRFX."expense_records
                ".$whereTheseRecords; 

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected expenses."));
        } else {

            return $rs->fields['sum'];

        }   

    }

    ######################################
    #   Build expense Date filter SQL    #
    ######################################

    public function expenseBuildFilterByDate($start_date = null, $end_date = null, $date_type = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date && $date_type) {

            if ($date_type == 'date') {       
                $whereTheseRecords .= " AND ".PRFX."expense_records.date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."expense_records.date <= ".$this->app->db->qstr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."expense_records.opened_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."expense_records.opened_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {       
                $whereTheseRecords .= " AND ".PRFX."expense_records.closed_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."expense_records.closed_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'last_active') {       
                $whereTheseRecords .= " AND ".PRFX."expense_records.last_active >= ".$this->app->db->qstr($start_date)." AND ".PRFX."expense_records.last_active <= ".$this->app->db->qstr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #######################################
    #  Build expense Status filter SQL    #
    #######################################

    public function expenseBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        if($status) {   
            if($status == 'open') {            
                $whereTheseRecords .= " AND ".PRFX."expense_records.closed_on = '0000-00-00 00:00:00'";                  
            } elseif($status == 'opened') {            
                // Do nothing                 
            } elseif($status == 'closed') {            
                $whereTheseRecords .= " AND ".PRFX."expense_records.closed_on != '0000-00-00 00:00:00'"; 
            } else {            
                $whereTheseRecords .= " AND ".PRFX."expense_records.status= ".$this->app->db->qstr($status);            
            }
        }

        return $whereTheseRecords;

    }

    /** Other Incomes **/

    #####################################
    #   Get Otherincomes stats          #
    #####################################

    public function getOtherincomesStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {    

            $stats['count_unpaid'] = $this->countOtherincomes($start_date, $end_date, 'date', $tax_system, null, null, 'unpaid', $employee_id);
            $stats['count_partially_paid'] = $this->countOtherincomes($start_date, $end_date, 'date', $tax_system, null, null, 'partially_paid', $employee_id);
            $stats['count_paid'] = $this->countOtherincomes($start_date, $end_date, 'date', $tax_system, null, null, 'paid', $employee_id);            
            $stats['count_cancelled'] = $this->countOtherincomes($start_date, $end_date, 'date', $tax_system, null, null, 'cancelled', $employee_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {            

            $stats['count_opened'] = $this->countOtherincomes($start_date, $end_date, 'date', $tax_system, null, null, 'opened', $employee_id);
            $stats['count_closed'] = $this->countOtherincomes($start_date, $end_date, 'date', $tax_system, null, null, 'closed', $employee_id);

        }  

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {            

            $stats['count_items'] = $this->countOtherincomes($start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);
            $stats['sum_unit_net'] = $this->sumOtherincomes('unit_net', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);
            $stats['sum_unit_tax'] = $this->sumOtherincomes('unit_tax', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);       
            $stats['sum_unit_gross'] = $this->sumOtherincomes('unit_gross', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);                   
            $stats['sum_balance'] = $this->sumOtherincomes('balance', $start_date, $end_date, 'date', $tax_system, null, null, null, $employee_id);

            // Adjust for Cancelled records  
            $stats['count_items'] -= $this->countOtherincomes($start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);
            $stats['sum_unit_net'] -= $this->sumOtherincomes('unit_net', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);
            $stats['sum_unit_tax'] -= $this->sumOtherincomes('unit_tax', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);       
            $stats['sum_unit_gross'] -= $this->sumOtherincomes('unit_gross', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);
            $stats['sum_balance'] -= $this->sumOtherincomes('balance', $start_date, $end_date, 'closed_on', $tax_system, null, null, 'cancelled', $employee_id);        

        }     

        return $stats;

    }

    #########################################
    #     Count Other Incomes               #
    #########################################

    public function countOtherincomes($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $item_type = null, $status = null, $employee_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->otherincomeBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }

        // Filter by Item Type
        if($item_type) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.item_type=".$this->app->db->qstr($item_type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->otherincomeBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."otherincome_records
                ".$whereTheseRecords;    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count other incomes."));

        } else {      

            return $rs->fields['count'];

        }

    }

    #########################################
    #  Sum selected value of Other Incomes  #
    #########################################

    public function sumOtherincomes($value_name, $start_date = null, $end_date = null, $date_type = null, $tax_system = null, $vat_tax_code = null, $item_type = null, $status = null, $employee_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->otherincomeBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.vat_tax_code=".$this->app->db->qstr($vat_tax_code);
        }

        // Filter by Item Type
        if($item_type) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.item_type=".$this->app->db->qstr($item_type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->otherincomeBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        $sql = "SELECT SUM(".PRFX."otherincome_records.$value_name) AS sum
                FROM ".PRFX."otherincome_records
                ".$whereTheseRecords; 

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the sum value for the selected other incomes."));
        } else {

            return $rs->fields['sum'];

        }   

    }

    ########################################
    #   Build otherincome Date filter SQL  #
    ########################################

    public function otherincomeBuildFilterByDate($start_date = null, $end_date = null, $date_type = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date && $date_type) {
            if ($date_type == 'date') {       
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."otherincome_records.date <= ".$this->app->db->qstr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.opened_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."otherincome_records.opened_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {       
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.closed_on >= ".$this->app->db->qstr($start_date)." AND ".PRFX."otherincome_records.closed_on <= ".$this->app->db->qstr($end_date.' 23:59:59');
            } elseif ($date_type == 'last_active') {       
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.last_active >= ".$this->app->db->qstr($start_date)." AND ".PRFX."otherincome_records.last_active <= ".$this->app->db->qstr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #########################################
    #  Build otherincome Status filter SQL  #
    #########################################

    public function otherincomeBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        if($status) {   
            if($status == 'open') {            
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.closed_on = '0000-00-00 00:00:00'";                  
            } elseif($status == 'opened') {            
                // Do nothing                 
            } elseif($status == 'closed') {            
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.closed_on != '0000-00-00 00:00:00'"; 
            } else {            
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.status= ".$this->app->db->qstr($status);            
            }
        }

        return $whereTheseRecords;

    }

    /** Payments **/

    #####################################
    #   Get All payments stats          #
    #####################################

    public function getPaymentsStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $client_id = null, $invoice_id = null, $refund_id = null, $expense_id = null, $otherincome_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_valid'] = $this->countPayments($start_date, $end_date, 'date', $tax_system, 'valid', null, null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {       

            $stats['count_invoice'] = $this->countPayments($start_date, $end_date, 'date', $tax_system, null, 'invoice', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['count_refund'] = $this->countPayments($start_date, $end_date, 'date', $tax_system, null, 'refund', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['count_expense'] = $this->countPayments($start_date, $end_date, 'date', $tax_system, null, 'expense', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['count_otherincome'] = $this->countPayments($start_date, $end_date, 'date', $tax_system, null, 'otherincome', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['count_sent'] = $this->countPayments($start_date, $end_date, 'date', $tax_system, null, 'sent', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['count_received'] = $this->countPayments($start_date, $end_date, 'date', $tax_system, null, 'received', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);

            // Remove vouchers from payments
            $stats['count_invoice'] -= $this->countPayments($start_date, $end_date, 'date', $tax_system, null, 'invoice', 'voucher', $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['count_received'] -= $this->countPayments($start_date, $end_date, 'date', $tax_system, null, 'invoice', 'voucher', $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);

        }  

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {       
            $stats['sum_invoice'] = $this->sumPayments($start_date, $end_date, 'date', $tax_system, null, 'invoice', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_refund'] = $this->sumPayments($start_date, $end_date, 'date', $tax_system, null, 'refund', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_expense'] = $this->sumPayments($start_date, $end_date, 'date', $tax_system, null, 'expense', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_otherincome'] = $this->sumPayments($start_date, $end_date, 'date', $tax_system, null, 'otherincome', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_sent'] = $this->sumPayments($start_date, $end_date, 'date', $tax_system, null, 'sent', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_received'] = $this->sumPayments($start_date, $end_date, 'date', $tax_system, null, 'received', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);

            // Remove vouchers from payments
            $stats['sum_invoice'] -= $this->sumPayments($start_date, $end_date, 'date', $tax_system, null, 'invoice', 'voucher', $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_received'] -= $this->sumPayments($start_date, $end_date, 'date', $tax_system, null, 'invoice', 'voucher', $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);

            // Adjust for Cancelled records  
            $stats['sum_invoice'] -= $this->sumPayments($start_date, $end_date, 'date', $tax_system, 'cancelled', 'invoice', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_refund'] -= $this->sumPayments($start_date, $end_date, 'date', $tax_system, 'cancelled', 'refund', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_expense'] -= $this->sumPayments($start_date, $end_date, 'date', $tax_system, 'cancelled', 'expense', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_otherincome'] -= $this->sumPayments($start_date, $end_date, 'date', $tax_system, 'cancelled', 'otherincome', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_sent'] -= $this->sumPayments($start_date, $end_date, 'date', $tax_system, 'cancelled', 'sent', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);
            $stats['sum_received'] -= $this->sumPayments($start_date, $end_date, 'date', $tax_system, 'cancelled', 'received', null, $employee_id, $client_id, $invoice_id, $refund_id, $expense_id, $otherincome_id);

        } 

        return $stats;

    }

    ####################################################
    #     Count Payments                               #
    ####################################################

    public function countPayments($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $status = null, $type = null, $method = null, $employee_id = null, $client_id = null, $invoice_id = null, $refund_id = null, $expense_id = null, $otherincome_id = null) {   

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";  

        // Filter by Date
        $whereTheseRecords .= $this->paymentBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Restrict by Status
        if($status) {   
            $whereTheseRecords .= " AND ".PRFX."payment_records.status= ".$this->app->db->qstr($status);  
        }

        // Restrict by Type
        $whereTheseRecords .= $this->paymentBuildFilterByType($type); 

        // Filter by Method
        if($method) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.method=".$this->app->db->qstr($method);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qstr($invoice_id);
        }

        // Filter by Refund
        if($refund_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.refund_id=".$this->app->db->qstr($refund_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$this->app->db->qstr($expense_id);
        }

        // Filter by Otherincome
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$this->app->db->qstr($otherincome_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."payment_records
                ".$whereTheseRecords;                

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count the number of payments."));
        } else {

           return $rs->fields['count']; 

        }

    }

    #########################################
    #  Sum selected value of payments       #
    #########################################

    public function sumPayments($start_date = null, $end_date = null, $date_type = null, $tax_system = null, $status = null, $type = null, $method = null, $employee_id = null, $client_id = null, $invoice_id = null, $refund_id = null, $expense_id = null, $otherincome_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n"; 

        // Filter by Date
        $whereTheseRecords .= $this->paymentBuildFilterByDate($start_date, $end_date, $date_type);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Restrict by Status
        if($status) {   
            $whereTheseRecords .= " AND ".PRFX."payment_records.status= ".$this->app->db->qstr($status);  
        }

        // Restrict by Type
        $whereTheseRecords .= $this->paymentBuildFilterByType($type);    

        // Filter by Method
        if($method) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.method=".$this->app->db->qstr($method);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qstr($invoice_id);
        }

        // Filter by Refund
        if($refund_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.refund_id=".$this->app->db->qstr($refund_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$this->app->db->qstr($expense_id);
        }

        // Filter by Otherincome
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$this->app->db->qstr($otherincome_id);
        }

        // Execute the SQL
        $sql = "SELECT SUM(".PRFX."payment_records.amount) AS sum
                FROM ".PRFX."payment_records
                ".$whereTheseRecords;                

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not sum the payment values."));
        } else {

           return $rs->fields['sum']; 

        }    

    }

    ########################################
    #   Build payment Date filter SQL      #
    ########################################

    public function paymentBuildFilterByDate($start_date = null, $end_date = null, $date_type = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date && $date_type) {
            if ($date_type == 'date') {       
                $whereTheseRecords .= " AND ".PRFX."payment_records.date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."payment_records.date <= ".$this->app->db->qstr($end_date);
            } elseif ($date_type == 'last_active') {       
                $whereTheseRecords .= " AND ".PRFX."payment_records.last_active >= ".$this->app->db->qstr($start_date)." AND ".PRFX."payment_records.last_active <= ".$this->app->db->qstr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #####################################
    #  Build payment type filter SQL    #
    #####################################

    public function paymentBuildFilterByType($type = null) {

        $whereTheseRecords = '';

        // Restrict by Type
        if($type) {   

            // All received monies
            if($type == 'received') {            
                $whereTheseRecords .= " AND ".PRFX."payment_records.type IN ('invoice', 'otherincome')";

            // All sent monies
            } elseif($type == 'sent') {            
                $whereTheseRecords .= " AND ".PRFX."payment_records.type IN ('expense', 'refund')";        

            // Return records for the given type
            } else {            
                $whereTheseRecords .= " AND ".PRFX."payment_records.type= ".$this->app->db->qstr($type);            
            }

        }

        return $whereTheseRecords;

    }

    ##############################################################################################  // cancelled payment records are ignored
    #  Calulate the revenue and tax liability for a ALL payments against their parent record     #  // I dont use most of these filters at the minute (only start_date, end_date and tax_system)
    ##############################################################################################

    // This is for calculating real 'NET, TAX and GROSS'.
    // By taking each payment and breaking them down into 'NET, TAX and GROSS' by prorata'ing them against their parent transaction.
    // Vouchers are not real money and should therefore not contribute anything to the the NET and GROSS totals, however:
    // MPV vouchers have their TAX liability accounted for at the point of redemption, so does add TAX to the totals,
    // SPV has already had the TAX taken at the point of sale so does not suffer this fate.
    // 'voucher' allows me to pass up the tree how much of vouchers SPV/MPV (in their NET/TAX/GROSS) have actually been paid (it is prorated aswell). This is separate to revenue totals and used upstream.

    public function revenuePaymentsProratedAgainstRecords($start_date = null, $end_date = null, $tax_system = null, $status = null, $type = null, $method = null, $employee_id = null, $client_id = null, $invoice_id = null, $refund_id = null, $expense_id = null, $otherincome_id = null) {

        // Holding array for prorata totals // I could use a blank array here??? but it is a good reference
        $prorata_totals = array(
                            "invoice" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                            //"voucher" => array("spv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00), "mpv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00)),  (not currently used)                       
                            "refund" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                            "expense" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                            "otherincome" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),                      
                            );

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n"; 

        // Filter by Date
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.date >= ".$this->app->db->qstr($start_date)." AND ".PRFX."payment_records.date <= ".$this->app->db->qstr($end_date);
        }

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.tax_system=".$this->app->db->qstr($tax_system);
        }

        // Restrict by Status
        if($status) {   
            $whereTheseRecords .= " AND ".PRFX."payment_records.status= ".$this->app->db->qstr($status);  
        }

        // Restrict by Type
        $whereTheseRecords .= $this->paymentBuildFilterByType($type);    

        // Filter by Method
        if($method) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.method=".$this->app->db->qstr($method);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qstr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qstr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qstr($invoice_id);
        }

        // Filter by Refund
        if($refund_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.refund_id=".$this->app->db->qstr($refund_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$this->app->db->qstr($expense_id);
        }

        // Filter by Otherincome
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$this->app->db->qstr($otherincome_id);
        }

        // Execute the SQL
        $sql = "SELECT *
                FROM ".PRFX."payment_records
                ".$whereTheseRecords;                

        if(!$rs = $this->app->db->Execute($sql)) {

            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching payments."));

        } else {

            while(!$rs->EOF) {            

                $prorata_record = null;

                // Adjust for Cancelled payment records - By ignoring them
                if($rs->fields['status'] == 'cancelled') { 
                    $rs->MoveNext(); 
                    continue;                
                }

                if($rs->fields['type'] == 'invoice') {
                    $prorata_record = $this->revenuePaymentProratedAgainstRecord($rs->fields['payment_id'], 'invoice');

                    // Vouchers must be compensated for profit purposes
                    if($rs->fields['method'] == 'voucher') {

                        $voucher_type = $this->app->components->voucher->getRecord($rs->fields['voucher_id'], 'type');

                        // Multi Purpose
                        if($voucher_type == 'MPV') {
                            $prorata_totals['invoice']['net'] += 0.00;
                            $prorata_totals['invoice']['tax'] += $prorata_record['tax']; 
                            $prorata_totals['invoice']['gross'] += 0.00;  

                            /* Total the transaction amounts that have been paid for with MPV voucher (not currently used)
                            $prorata_totals['voucher']['mpv']['net'] += $prorata_record['net'];
                            $prorata_totals['voucher']['mpv']['tax'] += $prorata_record['tax'];
                            $prorata_totals['voucher']['mpv']['gross'] += $prorata_record['gross'];*/                    

                        }

                        // Single Use
                        if($voucher_type == 'single_use') {
                            $prorata_totals['invoice']['net'] += 0.00;
                            $prorata_totals['invoice']['tax'] += 0.00; 
                            $prorata_totals['invoice']['gross'] += 0.00;

                            /* Total the transaction amounts that have been paid for with MPV voucher (not currently used)
                            $prorata_totals['voucher']['spv']['net'] += $prorata_record['voucher']['mpv']['net'];
                            $prorata_totals['voucher']['spv']['tax'] += $prorata_record['voucher']['mpv']['tax']; 
                            $prorata_totals['voucher']['spv']['gross'] += $prorata_record['voucher']['mpv']['gross'];*/
                        }

                    // Normal Payments    
                    } else {   

                        // Record main totals prorated)
                        $prorata_totals['invoice']['net'] += $prorata_record['net'];
                        $prorata_totals['invoice']['tax'] += $prorata_record['tax']; 
                        $prorata_totals['invoice']['gross'] += $prorata_record['gross'];

                    }

                }

                if($rs->fields['type'] == 'refund') {
                    $prorata_record = $this->revenuePaymentProratedAgainstRecord($rs->fields['payment_id'], 'refund');
                    $prorata_totals['refund']['net'] += $prorata_record['net'];
                    $prorata_totals['refund']['tax'] += $prorata_record['tax']; 
                    $prorata_totals['refund']['gross'] += $prorata_record['gross'];                
                }   

                if($rs->fields['type'] == 'expense') {
                    $prorata_record = $this->revenuePaymentProratedAgainstRecord($rs->fields['payment_id'], 'expense');
                    $prorata_totals['expense']['net'] += $prorata_record['net'];
                    $prorata_totals['expense']['tax'] += $prorata_record['tax']; 
                    $prorata_totals['expense']['gross'] += $prorata_record['gross'];                
                }      

                if($rs->fields['type'] == 'otherincome') {
                    $prorata_record = $this->revenuePaymentProratedAgainstRecord($rs->fields['payment_id'], 'otherincome');
                    $prorata_totals['otherincome']['net'] += $prorata_record['net'];
                    $prorata_totals['otherincome']['tax'] += $prorata_record['tax']; 
                    $prorata_totals['otherincome']['gross'] += $prorata_record['gross'];                
                }            

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return $prorata_totals;

        }

    }

    ##############################################################################################
    #  Calulate the revenue and tax liability for a single payments against their parent record  #  // This returns what has been paid in NET/TAX/GROSS for a single payment against record
    ##############################################################################################

    public function revenuePaymentProratedAgainstRecord($payment_id, $record_type) {

        $payment_details = $this->app->components->payment->getRecord($payment_id);

        // Holding array
        $record_prorata_totals = array(
                            "net" => 0.00,
                            "tax" => 0.00,
                            "gross" => 0.00,                       
                            //"voucher" => array("spv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00), "mpv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00)),                                      
                            );    

        // Get the correct record details to process
        if($record_type == 'invoice') {$record_details = $this->app->components->invoice->getRecord($payment_details['invoice_id']);}
        if($record_type == 'refund') {$record_details = $this->app->components->refund->getRecord($payment_details['refund_id']);}
        if($record_type == 'expense') {$record_details = $this->app->components->expense->getRecord($payment_details['expense_id']);}
        if($record_type == 'otherincome') {$record_details = $this->app->components->otherincome->getRecord($payment_details['otherincome_id']);}    

        // Calcualte the proata values
        $percentage = $payment_details['amount'] / $record_details['unit_gross'];
        $record_prorata_totals['net'] = $record_details['unit_net'] * $percentage;
        $record_prorata_totals['tax'] = $record_details['unit_tax'] * $percentage;
        $record_prorata_totals['gross'] = $record_details['unit_gross'] * $percentage;

        /* This gets the exact amounts of vouchers paid for, used upstream (not currently used)
        if($record_type == 'invoice') {  
            $record_prorata_totals['voucher']['spv']['net'] = $this->sum_vouchers('unit_net', null, null, null, null, null, 'SPV', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['spv']['tax'] = $this->sum_vouchers('unit_tax', null, null, null, null, null, 'SPV', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['spv']['gross'] = $this->sum_vouchers('unit_gross', null, null, null, null, null, 'SPV', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['mpv']['net'] = $this->sum_vouchers('unit_net', null, null, null, null, null, 'MPV', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['mpv']['tax'] = $this->sum_vouchers('unit_tax', null, null, null, null, null, 'MPV', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['mpv']['gross'] = $this->sum_vouchers('unit_gross', null, null, null, null, null, 'MPV', null, null, null, $record_details['invoice_id']) * $percentage;     
        }*/

        return $record_prorata_totals;

    }

    /** Suppliers **/

    #############################################
    #    Count Suppliers                        #  // not currently used
    #############################################

    public function countSuppliers() { 

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."supplier_records";                           

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not count the number of suppliers."));
        } else {

           return $rs->fields['count']; 

        }

    }
    
}