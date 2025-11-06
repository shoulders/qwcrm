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
        if($record_set == 'current' || $record_set == 'all') {

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

            $stats['count_new_month'] = $this->countClients($date_month_start, $date_month_end);
            $stats['count_new_year']  = $this->countClients($date_year_start, $date_year_end);
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
            $whereTheseRecords .= " AND ".PRFX."client_records.opened_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."client_records.opened_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
        }

        // Restrict by Status
        if($status) {
            $whereTheseRecords .= " AND ".PRFX."client_records.active= ".$this->app->db->qStr($status);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."client_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    /** Workorders **/

    #####################################
    #    Get Workorders Stats           #
    #####################################

    public function getWorkordersStats($record_set, $start_date = null, $end_date = null, $employee_id = null, $client_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_open'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'open', $employee_id, $client_id);
            $stats['count_unassigned'] = $this->countWorkorders('opened_on', $start_date, $end_date,'unassigned', $employee_id, $client_id);
            $stats['count_assigned'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'assigned', $employee_id, $client_id);
            $stats['count_waiting_for_parts'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'waiting_for_parts',$employee_id, $client_id);
            $stats['count_scheduled'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'scheduled', $employee_id, $client_id);
            $stats['count_with_client'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'with_client', $employee_id, $client_id);
            $stats['count_on_hold'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'on_hold', $employee_id, $client_id);
            $stats['count_management'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'management', $employee_id, $client_id);
        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {

            $stats['count_opened'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'opened', $employee_id, $client_id);
            $stats['count_closed'] = $this->countWorkorders('closed_on', $start_date, $end_date, 'closed', $employee_id, $client_id);
            $stats['count_closed_without_invoice'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'closed_without_invoice', $employee_id, $client_id);
            $stats['count_closed_with_invoice'] = $this->countWorkorders('opened_on', $start_date, $end_date, 'closed_with_invoice', $employee_id, $client_id);
            $stats['count_deleted'] = $this->countWorkorders(null, null, null, 'deleted', $employee_id, $client_id);   // Only used on basic stats

        }

        return $stats;

    }

    #########################################
    #     Count Work Orders                 #
    #########################################

    public function countWorkorders($date_type, $start_date = null, $end_date = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."workorder_records.workorder_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->workorderBuildFilterByDate($date_type, $start_date, $end_date);

        // Restrict by Status
        $whereTheseRecords .= $this->workorderBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.client_id=".$this->app->db->qStr($client_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."workorder_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return  $rs->fields['count'];

    }

    ######################################
    #   Build workorder Date filter SQL  #
    ######################################

    public function workorderBuildFilterByDate($date_type, $start_date = null, $end_date = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date) {
            if($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."workorder_records.opened_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."workorder_records.opened_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif($date_type == 'closed_on') {
                $whereTheseRecords .= " AND ".PRFX."workorder_records.closed_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."workorder_records.closed_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif($date_type == 'last_active') {
                $whereTheseRecords .= " AND ".PRFX."workorder_records.last_active >= ".$this->app->db->qStr($start_date)." AND ".PRFX."workorder_records.last_active <= ".$this->app->db->qStr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #######################################
    #  Build workorder Status filter SQL  # // build a filter on whether or not the workorder is open or closed
    ####################################### // you can filter by a particular status aswell, not sure if i have used that

    public function workorderBuildFilterByStatus($status = null) {   //handle deleted workorders here

        $whereTheseRecords = '';

        if($status == 'open') {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.closed_on IS NULL";
        } elseif($status == 'opened') {
            // Do nothing
        } elseif($status == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.closed_on IS NOT NULL";
        } elseif($status) {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.status= ".$this->app->db->qStr($status);
        }

        // Remove Deleted Records from the results, unless the status is 'deleted'
        if($status !== 'deleted')
        {
            $whereTheseRecords .= " AND ".PRFX."workorder_records.status != 'deleted'";
        }

        return $whereTheseRecords;

    }

    /** Schedules **/

    ############################################
    #    Count Schedules                       #
    ############################################

    public function countSchedules($workorder_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."schedule_records.schedule_id\n";

        // Filter by workorder_id
        if($workorder_id) {
            $whereTheseRecords .= " AND ".PRFX."schedule_records.workorder_id=".$this->app->db->qStr($workorder_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."schedule_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return  $rs->fields['count'];

    }

    /** Invoices **/

    #####################################
    #   Get All invoices stats          #
    #####################################

    public function getInvoicesStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $client_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_open'] = $this->countInvoices('date', $start_date, $end_date, $tax_system, 'open', $employee_id, $client_id);
            $stats['count_discounted'] = $this->countInvoices('date', $start_date, $end_date, $tax_system, 'discounted', $employee_id, $client_id);
            $stats['count_pending'] = $this->countInvoices('date', $start_date, $end_date, $tax_system, 'pending', $employee_id, $client_id);
            $stats['count_unpaid'] = $this->countInvoices('date', $start_date, $end_date, $tax_system, 'unpaid', $employee_id, $client_id);
            $stats['count_partially_paid'] = $this->countInvoices('date', $start_date, $end_date, $tax_system, 'partially_paid', $employee_id, $client_id);
            $stats['count_in_dispute'] = $this->countInvoices('date', $start_date, $end_date, $tax_system, 'in_dispute', $employee_id, $client_id);
            $stats['count_overdue'] = $this->countInvoices('date', $start_date, $end_date, $tax_system, 'overdue', $employee_id, $client_id);
            $stats['count_collections'] = $this->countInvoices('date', $start_date, $end_date, $tax_system, 'collections', $employee_id, $client_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {

            $stats['count_opened'] = $this->countInvoices('opened_on', $start_date, $end_date, $tax_system, 'opened', $employee_id, $client_id);
            $stats['count_closed'] = $this->countInvoices('closed_on', $start_date, $end_date, $tax_system, 'closed', $employee_id, $client_id);
            $stats['count_paid'] = $this->countInvoices('closed_on', $start_date, $end_date, $tax_system, 'paid', $employee_id, $client_id);
            $stats['count_cancelled'] = $this->countInvoices('closed_on', $start_date, $end_date, $tax_system, 'cancelled', $employee_id, $client_id);
            $stats['count_deleted'] = $this->countInvoices(null, $start_date, $end_date, $tax_system, 'deleted', $employee_id, $client_id);
            $stats['count_closed_discounted'] = $this->countInvoices('closed_on', $start_date, $end_date, $tax_system, 'discounted', $employee_id, $client_id);

        }

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {

            // Totals
            $stats['sum_unit_net'] = $this->sumInvoices('unit_net', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id);
            $stats['sum_unit_discount'] = $this->sumInvoices('unit_discount', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id);
            $stats['sum_unit_tax'] = $this->sumInvoices('unit_tax', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id);
            $stats['sum_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id);
            $stats['sum_balance'] = $this->sumInvoices('balance', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id);

            // Sums by Status - Only used on Client Tab
            $stats['sum_pending_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'pending', $employee_id, $client_id);
            $stats['sum_unpaid_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'unpaid', $employee_id, $client_id);
            $stats['sum_partially_paid_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'partially_paid', $employee_id, $client_id);
            $stats['sum_paid_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'paid', $employee_id, $client_id);
            $stats['sum_in_dispute_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'in_dipute', $employee_id, $client_id);
            $stats['sum_overdue_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'overdue', $employee_id, $client_id);
            $stats['sum_collections_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'collections', $employee_id, $client_id);
            $stats['sum_cancelled_unit_gross'] = $this->sumInvoices('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, 'cancelled', $employee_id, $client_id);
            $stats['sum_open_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'open', $employee_id, $client_id);
            $stats['sum_discounted_unit_gross'] = $this->sumInvoices('unit_gross', 'date', $start_date, $end_date, $tax_system, 'discounted', $employee_id, $client_id);  // Cannot remove cancelled with discount
            $stats['sum_opened_unit_gross'] = $this->sumInvoices('unit_gross', 'opened_on', $start_date, $end_date, $tax_system, 'opened', $employee_id, $client_id);
            $stats['sum_closed_unit_gross'] = $this->sumInvoices('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, 'closed', $employee_id, $client_id);
            $stats['sum_closed_discounted_unit_gross'] = $this->sumInvoices('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, 'discounted', $employee_id, $client_id);  // Cannot remove cancelled with discount

        }

        // Items - This might be redundant now - only used in report:financial - useful to split vouchers and normal invoice items
        if($record_set == 'items' || $record_set == 'all') {

            $stats['items_count'] = $this->countInvoiceItems('date', $start_date, $end_date, $tax_system, null, null, $employee_id, $client_id);             // Total Different Items
            $stats['items_sum_unit_qty'] = $this->sumInvoiceItems('unit_qty', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id, $client_id);
            $stats['items_sum_subtotal_net'] = $this->sumInvoiceItems('subtotal_net', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id, $client_id);
            $stats['items_sum_subtotal_tax'] = $this->sumInvoiceItems('subtotal_tax', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id, $client_id);
            $stats['items_sum_subtotal_gross'] = $this->sumInvoiceItems('subtotal_gross', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id, $client_id);

        }

        // Could possible add vouchers here

        return $stats;

    }

    ####################################################
    #     Count Invoices                               #
    ####################################################

    public function countInvoices($date_type, $start_date = null, $end_date = null, $tax_system = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."invoice_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    #########################################
    #  Sum selected value of invoices       #
    #########################################

    public function sumInvoices($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Execute the SQL
        $sql = "SELECT SUM(".PRFX."invoice_records.$value_name) AS sum
                FROM ".PRFX."invoice_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    #####################################
    #   Build invoice Date filter SQL   #
    #####################################

    public function invoiceBuildFilterByDate($date_type, $start_date = null, $end_date = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date) {
            if ($date_type == 'date') {
                $whereTheseRecords .= " AND ".PRFX."invoice_records.date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."invoice_records.date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'due_date') {
                $whereTheseRecords .= " AND ".PRFX."invoice_records.due_date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."invoice_records.due_date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."invoice_records.opened_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."invoice_records.opened_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {
                $whereTheseRecords .= " AND ".PRFX."invoice_records.closed_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."invoice_records.closed_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'last_active') {
                $whereTheseRecords .= " AND ".PRFX."invoice_records.last_active >= ".$this->app->db->qStr($start_date)." AND ".PRFX."invoice_records.last_active <= ".$this->app->db->qStr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #####################################
    #  Build invoice Status filter SQL  #
    #####################################

    public function invoiceBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        // Restrict the records
        if($status == 'open') {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.closed_on IS NULL";
        } elseif($status == 'opened') {
            // Do nothing
        } elseif($status == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.closed_on IS NOT NULL";
        } elseif($status == 'discounted') {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.unit_discount > 0";
        } elseif($status) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.status= ".$this->app->db->qStr($status);
        }

        // Remove `Cancelled` records from the results, unless you are looking up cancelled records except for opened and closed as these are absolutes
        if($status !== 'cancelled' && $status !== 'opened' && $status !== 'closed') {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.status != 'cancelled'";
        }

        // Remove `Deleted` records from the results, unless you are looking up deleted records
        if($status !== 'deleted')
        {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.status != 'deleted'";
        }

        return $whereTheseRecords;

    }

    #########################
    #  Count invoice items  #
    #########################

    public function countInvoiceItems($date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $status = null, $employee_id = null, $client_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_items.invoice_item_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_items.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."invoice_items.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qStr($client_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."invoice_items
                LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_items.invoice_id = ".PRFX."invoice_records.invoice_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    #########################################
    #  Sum selected value of invoice items  #
    #########################################

    public function sumInvoiceItems($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $status = null, $employee_id = null, $client_id = null) {

        // Prevent ambiguous error
        $value_name = PRFX."invoice_items.".$value_name;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_items.invoice_item_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->invoiceBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."invoice_items.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."invoice_items.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->invoiceBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qStr($client_id);
        }

        $sql = "SELECT SUM($value_name) AS sum
                FROM ".PRFX."invoice_items
                LEFT JOIN ".PRFX."invoice_records ON ".PRFX."invoice_items.invoice_id = ".PRFX."invoice_records.invoice_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    /** Vouchers **/

    #####################################
    #   Get Voucher stats               #
    #####################################

    public function getVouchersStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $client_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_open'] = $this->countVouchers('date', $start_date, $end_date, $tax_system, null, null, 'open', $employee_id, $client_id);
            $stats['count_paid_unused'] = $this->countVouchers('date', $start_date, $end_date, $tax_system, null, null, 'paid_unused', $employee_id, $client_id);
            $stats['count_redeemed'] = $this->countVouchers('date', $start_date, $end_date, $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['count_suspended'] = $this->countVouchers('date', $start_date, $end_date, $tax_system, null, null, 'suspended', $employee_id, $client_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {

            $stats['count_items'] = $this->countVouchers('date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);
            $stats['count_opened'] = $this->countVouchers('opened_on', $start_date, $end_date, $tax_system, null, null, 'opened', $employee_id, $client_id);
            $stats['count_closed'] = $this->countVouchers('closed_on', $start_date, $end_date, $tax_system, null, null, 'closed', $employee_id, $client_id);
            $stats['count_claimed'] = $this->countVouchers('closed_on', $start_date, $end_date, $tax_system, null, null, 'claimed', $employee_id, $client_id);  // This is where the client has used a Voucher from someone else on their account
            $stats['count_expired_unused'] = $this->countVouchers('date', $start_date, $end_date, $tax_system, null, null, 'expired_unused', $employee_id, $client_id);
            $stats['count_cancelled'] = $this->countVouchers('date', $start_date, $end_date, $tax_system, null, null, 'cancelled', $employee_id, $client_id);

        }

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {

            $stats['sum_unit_net'] = $this->sumVouchers('unit_net', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);
            $stats['sum_unit_tax'] = $this->sumVouchers('unit_tax', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);
            $stats['sum_unit_gross'] = $this->sumVouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);
            $stats['sum_redeemed_unit_net'] = $this->sumVouchers('unit_net', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['sum_redeemed_unit_tax'] = $this->sumVouchers('unit_tax', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['sum_redeemed_unit_gross'] = $this->sumVouchers('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['sum_expired_unused_unit_net'] = $this->sumVouchers('unit_net', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'expired_unused', $employee_id, $client_id);
            $stats['sum_expired_unused_unit_tax'] = $this->sumVouchers('unit_tax', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'expired_unused', $employee_id, $client_id);
            $stats['sum_expired_unused_unit_gross'] = $this->sumVouchers('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'expired_unused', $employee_id, $client_id);
            //$stats['sum_cancelled_unit_net'] = $this->sumVouchers('unit_net', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'cancelled', $employee_id, $client_id);
            //$stats['sum_cancelled_unit_tax'] = $this->sumVouchers('unit_tax', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'cancelled', $employee_id, $client_id);
            //$stats['sum_cancelled_unit_gross'] = $this->sumVouchers('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'cancelled', $employee_id, $client_id);

            // Only used on Client Tab
            $stats['sum_paid_unused_unit_gross'] = $this->sumVouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, 'paid_unused', $employee_id, $client_id);
            //$stats['sum_redeemed_unit_gross'] = $this->sumVouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, 'redeemed', $employee_id, $client_id);
            $stats['sum_suspended_unit_gross'] = $this->sumVouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, 'suspended', $employee_id, $client_id);
            //$stats['sum_expired_unused_unit_gross'] = $this->sumVouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, 'expired_unused', $employee_id, $client_id);
            $stats['sum_cancelled_unit_gross'] = $this->sumVouchers('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'cancelled', $employee_id, $client_id);
            $stats['sum_open_unit_gross'] = $this->sumVouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, 'open', $employee_id, $client_id);
            $stats['sum_opened_unit_gross'] = $this->sumVouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, 'opened', $employee_id, $client_id);
            $stats['sum_closed_unit_gross'] = $this->sumVouchers('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'closed', $employee_id, $client_id);
            $stats['sum_claimed_unit_gross'] = $this->sumVouchers('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, null, null, 'claimed', $employee_id, $client_id);  // This is where the client has used a Voucher from someone else

            // Used for VAT Flate Rate calculations (not currently used)
            //$stats['sum_voucher_spv_unit_gross'] = $this->sum_vouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, 'spv', null, $employee_id, $client_id);
            //$stats['sum_voucher_mpv_unit_gross'] = $this->sum_vouchers('unit_gross', 'date', $start_date, $end_date, $tax_system, null, 'mpv', null, $employee_id, $client_id);

        }

        return $stats;

    }

    #########################################
    #     Count Vouchers                    #
    #########################################

    public function countVouchers($date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $type = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."voucher_records.voucher_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->voucherBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Filter by Item Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.type=".$this->app->db->qStr($type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->voucherBuildFilterByStatus($status, $client_id);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client (who purchaed the voucher)
        if($client_id && $status != 'claimed') {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Claimed Client (who used the voucher)
        if($client_id && $status == 'claimed') {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Build the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."voucher_records
                ";
        if($status != 'claimed')
        {
            $sql .= "LEFT JOIN ".PRFX."invoice_records ON ".PRFX."voucher_records.invoice_id = ".PRFX."invoice_records.invoice_id
                ";
        }
        else
        {
            $sql .= "RIGHT JOIN ".PRFX."payment_records ON ".PRFX."voucher_records.voucher_id = ".PRFX."payment_records.voucher_id
                ";
        }
        $sql .= $whereTheseRecords;

        // Execute the SQL
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    ###########################################
    #  Sum selected value of Vouchers         #
    ###########################################

    public function sumVouchers($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $type = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."voucher_records.voucher_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->voucherBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Filter by Item Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.type=".$this->app->db->qStr($type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->voucherBuildFilterByStatus($status, $client_id);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.voucher_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client (who purchaed the voucher)
        if($client_id && $status != 'claimed') {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Claimed Client (who used the voucher)
        if($client_id && $status == 'claimed') {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Build the SQL
        $sql = "SELECT SUM(".PRFX."voucher_records.$value_name) AS sum
                FROM ".PRFX."voucher_records
                ";
        if($status != 'claimed')
        {
            $sql .= "LEFT JOIN ".PRFX."invoice_records ON ".PRFX."voucher_records.invoice_id = ".PRFX."invoice_records.invoice_id
                ";
        }
        else
        {
            $sql .= "RIGHT JOIN ".PRFX."payment_records ON ".PRFX."voucher_records.voucher_id = ".PRFX."payment_records.voucher_id
                ";
        }
        $sql .= $whereTheseRecords;

        // Execute the SQL
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    #####################################
    #  Build Voucher Status filter SQL  #
    #####################################

    public function voucherBuildFilterByStatus($status = null, $client_id = null) {

        $whereTheseRecords = '';

        if($status == 'open') {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.closed_on IS NULL";
        } elseif($status == 'opened') {
            // Do nothing
        } elseif($status == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.closed_on IS NOT NULL";
        } elseif($status == 'claimed' && $client_id) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.status = 'redeemed'";
        } elseif($status) {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.status = ".$this->app->db->qStr($status);
        }

        // Remove `Cancelled` records from the results, unless you are looking up cancelled records except for opened and closed as these are absolutes
        if($status !== 'cancelled' && $status !== 'opened' && $status !== 'closed') {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.status != 'cancelled'";
        }

        // Remove `Deleted` records from the results, unless you are looking up deleted records
        if($status !== 'deleted')
        {
            $whereTheseRecords .= " AND ".PRFX."voucher_records.status != 'deleted'";
        }

        return $whereTheseRecords;

    }

    #####################################
    #   Build Voucher Date filter SQL   #
    #####################################

    public function voucherBuildFilterByDate($date_type, $start_date = null, $end_date = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date) {

            if($date_type == 'date') {
                $whereTheseRecords .= " AND ".PRFX."invoice_records.date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."invoice_records.date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'due_date') {
                $whereTheseRecords .= " AND ".PRFX."invoice_records.due_date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."invoice_records.due_date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.opened_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."voucher_records.opened_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type== 'expiry') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.expiry_date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."voucher_records.expiry_date <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'redeemed_on') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.redeemed_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."voucher_records.redeemed_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {
                $whereTheseRecords .= " AND ".PRFX."voucher_records.closed_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."voucher_records.closed_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            }

        }

        return $whereTheseRecords;

    }

    /** Expenses **/

    #####################################
    #   Get expense stats               #
    #####################################

    public function getExpensesStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $supplier_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_pending'] = $this->countExpenses('date', $start_date, $end_date, $tax_system, null, 'unpaid', $employee_id, $supplier_id);
            $stats['count_unpaid'] = $this->countExpenses('date', $start_date, $end_date, $tax_system, null, 'unpaid', $employee_id, $supplier_id);
            $stats['count_partially_paid'] = $this->countExpenses('date', $start_date, $end_date, $tax_system, null, 'partially_paid', $employee_id, $supplier_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {

            $stats['count_items'] = $this->countExpenses('date', $start_date, $end_date, $tax_system, null, null, $employee_id, $supplier_id);
            $stats['count_opened'] = $this->countExpenses('date', $start_date, $end_date, $tax_system, null, null, $employee_id, $supplier_id);
            $stats['count_closed'] = $this->countExpenses('date', $start_date, $end_date, $tax_system, null, null, $employee_id, $supplier_id);
            $stats['count_paid'] = $this->countExpenses('date', $start_date, $end_date, $tax_system, null, 'paid', $employee_id, $supplier_id);
            $stats['count_cancelled'] = $this->countExpenses('date', $start_date, $end_date, $tax_system, null, 'cancelled', $employee_id, $supplier_id);

        }

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {

            $stats['sum_unit_net'] = $this->sumExpenses('unit_net', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id, $supplier_id);
            $stats['sum_unit_tax'] = $this->sumExpenses('unit_tax', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id, $supplier_id);
            $stats['sum_unit_gross'] = $this->sumExpenses('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id, $supplier_id);
            $stats['sum_balance'] = $this->sumExpenses('balance', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id, $supplier_id);

        }

        /* Items - This might be redundant now - only used in report:financial
        if($record_set == 'items' || $record_set == 'all') {

            $stats['items_count'] = $this->countExpenseItems('date', $start_date, $end_date, $tax_system, null, null, null,  $employee_id, $supplier_id);            // Total Different Items
            $stats['items_sum_unit_qty'] = $this->sumExpenseItems('unit_qty', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);
            $stats['items_sum_subtotal_net'] = $this->sumExpenseItems('subtotal_net', 'date', $start_date, $end_date, $tax_system, null, null, null,  $employee_id, $supplier_id);
            $stats['items_sum_subtotal_tax'] = $this->sumExpenseItems('subtotal_tax', 'date', $start_date, $end_date, $tax_system, null, null, null,  $employee_id, $supplier_id);
            $stats['items_sum_subtotal_gross'] = $this->sumExpenseItems('subtotal_gross', 'date', $start_date, $end_date, $tax_system, null, null, null,  $employee_id, $supplier_id);

        }*/

        return $stats;

    }

    #########################################
    #     Count Expenses                    #
    #########################################

    public function countExpenses($date_type, $start_date = null, $end_date = null, $tax_system = null, $type = null, $status = null, $employee_id = null, $supplier_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->expenseBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by Item Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.type=".$this->app->db->qStr($type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->expenseBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by supplier_id
        if($supplier_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.invoice_id=".$this->app->db->qStr($supplier_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."expense_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    ###################################
    #  Sum selected value of expenses #
    ###################################

    public function sumExpenses($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $type = null, $status = null, $employee_id = null, $supplier_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->expenseBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by Item Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.type=".$this->app->db->qStr($type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->expenseBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by supplier_id
        if($supplier_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.invoice_id=".$this->app->db->qStr($supplier_id);
        }

        $sql = "SELECT SUM(".PRFX."expense_records.$value_name) AS sum
                FROM ".PRFX."expense_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    #############################
    #  Count Expense items      #
    #############################

    public function countExpenseItems($date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $status = null, $type = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_items.expense_item_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->expenseBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."expense_items.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."expense_items.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->expenseBuildFilterByStatus($status);

        // Filter by Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.type=".$this->app->db->qStr($type);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Supplier
        if($supplier_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.supplier_id=".$this->app->db->qStr($supplier_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."expense_items
                LEFT JOIN ".PRFX."expense_records ON ".PRFX."expense_items.expense_id = ".PRFX."expense_records.expense_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    #############################################
    #  Sum selected value of expense items      #
    #############################################

    public function sumExpenseItems($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $status = null, $type = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null) {

        // Prevent ambiguous error
        $value_name = PRFX."expense_items.".$value_name;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_items.expense_item_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->expenseBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."expense_items.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."expense_items.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->expenseBuildFilterByStatus($status);

        // Filter by Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.type=".$this->app->db->qStr($type);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Supplier
        if($supplier_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.supplier_id=".$this->app->db->qStr($supplier_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        $sql = "SELECT SUM($value_name) AS sum
                FROM ".PRFX."expense_items
                LEFT JOIN ".PRFX."expense_records ON ".PRFX."expense_items.expense_id = ".PRFX."expense_records.expense_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    ######################################
    #   Build expense Date filter SQL    #
    ######################################

    public function expenseBuildFilterByDate($date_type, $start_date = null, $end_date = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date) {

            if ($date_type == 'date') {
                $whereTheseRecords .= " AND ".PRFX."expense_records.date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."expense_records.date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."expense_records.opened_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."expense_records.opened_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {
                $whereTheseRecords .= " AND ".PRFX."expense_records.closed_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."expense_records.closed_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'last_active') {
                $whereTheseRecords .= " AND ".PRFX."expense_records.last_active >= ".$this->app->db->qStr($start_date)." AND ".PRFX."expense_records.last_active <= ".$this->app->db->qStr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #######################################
    #  Build expense Status filter SQL    #
    #######################################

    public function expenseBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        if($status == 'open') {
            $whereTheseRecords .= " AND ".PRFX."expense_records.closed_on = IS NULL";
        } elseif($status == 'opened') {
            // Do nothing
        } elseif($status == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."expense_records.closed_on IS NOT NULL";
        } elseif($status) {
            $whereTheseRecords .= " AND ".PRFX."expense_records.status= ".$this->app->db->qStr($status);
        }

        // Remove `Cancelled` records from the results, unless you are looking up cancelled records except for opened and closed as these are absolutes
        if($status !== 'cancelled' && $status !== 'opened' && $status !== 'closed') {
            $whereTheseRecords .= " AND ".PRFX."expense_records.status != 'cancelled'";
        }

        // Remove `Deleted` records from the results, unless you are looking up deleted records
        if($status !== 'deleted')
        {
            $whereTheseRecords .= " AND ".PRFX."expense_records.status != 'deleted'";
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

            $stats['count_pending'] = $this->countOtherincomes('date', $start_date, $end_date, $tax_system, null, 'unpaid', $employee_id);
            $stats['count_unpaid'] = $this->countOtherincomes('date', $start_date, $end_date, $tax_system, null, 'unpaid', $employee_id);
            $stats['count_partially_paid'] = $this->countOtherincomes('date', $start_date, $end_date, $tax_system, null, 'partially_paid', $employee_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {

            $stats['count_items'] = $this->countOtherincomes('date', $start_date, $end_date, $tax_system, null, null, $employee_id);
            $stats['count_opened'] = $this->countOtherincomes('date', $start_date, $end_date, $tax_system, null, 'opened', $employee_id);
            $stats['count_closed'] = $this->countOtherincomes('date', $start_date, $end_date, $tax_system, null, 'closed', $employee_id);
            $stats['count_paid'] = $this->countOtherincomes('date', $start_date, $end_date, $tax_system, null, 'paid', $employee_id);
            $stats['count_cancelled'] = $this->countOtherincomes('date', $start_date, $end_date, $tax_system, null, 'cancelled', $employee_id);

        }

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {

            $stats['sum_unit_net'] = $this->sumOtherincomes('unit_net', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id);
            $stats['sum_unit_tax'] = $this->sumOtherincomes('unit_tax', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id);
            $stats['sum_unit_gross'] = $this->sumOtherincomes('unit_gross', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id);
            $stats['sum_balance'] = $this->sumOtherincomes('balance', 'date', $start_date, $end_date, $tax_system, null, null, $employee_id);

        }

        /* Items - This might be redundant now - only used in report:financial
        if($record_set == 'items' || $record_set == 'all')
        {
            $stats['items_count'] = $this->countOtherincomeItems('date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $otherincome_id);          // Total Different Items
            $stats['items_sum_unit_qty'] = $this->sumOtherincomeItems('unit_qty', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $otherincome_id);
            $stats['items_sum_subtotal_net'] = $this->sumOtherincomeItems('subtotal_net', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $otherincome_id);
            $stats['items_sum_subtotal_tax'] = $this->sumOtherincomeItems('subtotal_tax', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $otherincome_id);
            $stats['items_sum_subtotal_gross'] = $this->sumOtherincomeItems('subtotal_gross', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $otherincome_id);
        }
           */

        return $stats;

    }

    #########################################
    #     Count Other Incomes               #
    #########################################

    public function countOtherincomes($date_type, $start_date = null, $end_date = null, $tax_system = null, $type = null, $status = null, $employee_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->otherincomeBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by Item Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.type=".$this->app->db->qStr($type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->otherincomeBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."otherincome_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    #########################################
    #  Sum selected value of Other Incomes  #
    #########################################

    public function sumOtherincomes($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $type = null, $status = null, $employee_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->otherincomeBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by Item Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.type=".$this->app->db->qStr($type);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->otherincomeBuildFilterByStatus($status);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        $sql = "SELECT SUM(".PRFX."otherincome_records.$value_name) AS sum
                FROM ".PRFX."otherincome_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    #################################
    #  Count Otherincome items      #
    #################################

    function countOtherincomeItems($date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $status = null, $type = null, $employee_id = null, $otherincome_id = null)
    {
        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_items.otherincome_item_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->otherincomeBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_items.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_items.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->otherincomeBuildFilterByStatus($status);

        // Filter by Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.type=".$this->app->db->qStr($type);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Otherincome
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."otherincome_items
                LEFT JOIN ".PRFX."otherincome_records ON ".PRFX."otherincome_items.otherincome_id = ".PRFX."otherincome_records.otherincome_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    #################################
    #  Sum Otherincome items        #
    #################################
    function sumOtherincomeItems($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $status = null, $type = null, $employee_id = null, $otherincome_id = null) {

        // Prevent ambiguous error
        $value_name = PRFX."otherincome_items.".$value_name;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_items.otherincome_item_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->otherincomeBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_items.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_items.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->otherincomeBuildFilterByStatus($status);

        // Filter by Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.type=".$this->app->db->qStr($type);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Expense
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        $sql = "SELECT SUM($value_name) AS sum
                FROM ".PRFX."otherincome_items
                LEFT JOIN ".PRFX."otherincome_records ON ".PRFX."otherincome_items.otherincome_id = ".PRFX."otherincome_records.otherincome_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    ########################################
    #   Build otherincome Date filter SQL  #
    ########################################

    public function otherincomeBuildFilterByDate($date_type, $start_date = null, $end_date = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date) {
            if ($date_type == 'date') {
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."otherincome_records.date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.opened_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."otherincome_records.opened_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.closed_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."otherincome_records.closed_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'last_active') {
                $whereTheseRecords .= " AND ".PRFX."otherincome_records.last_active >= ".$this->app->db->qStr($start_date)." AND ".PRFX."otherincome_records.last_active <= ".$this->app->db->qStr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #########################################
    #  Build otherincome Status filter SQL  #
    #########################################

    public function otherincomeBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        if($status == 'open') {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.closed_on IS NULL";
        } elseif($status == 'opened') {
            // Do nothing
        } elseif($status == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.closed_on IS NOT NULL";
        } elseif($status) {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.status= ".$this->app->db->qStr($status);
        }

        // Remove `Cancelled` records from the results, unless you are looking up cancelled records except for opened and closed as these are absolutes
        if($status !== 'cancelled' && $status !== 'opened' && $status !== 'closed') {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.status != 'cancelled'";
        }

        // Remove `Deleted` records from the results, unless you are looking up deleted records
        if($status !== 'deleted')
        {
            $whereTheseRecords .= " AND ".PRFX."otherincome_records.status != 'deleted'";
        }

        return $whereTheseRecords;

    }

    /** Credit Notes **/

    #####################################
    #   Get All Credit Notes stats      #
    #####################################

    public function getCreditnotesStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_open'] = $this->countCreditnotes('date', $start_date, $end_date, $tax_system, 'open', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_discounted'] = $this->countCreditnotes('date', $start_date, $end_date, $tax_system, 'discounted', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_pending'] = $this->countCreditnotes('date', $start_date, $end_date, $tax_system, 'pending', $employee_id, null, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_unused'] = $this->countCreditnotes('date', $start_date, $end_date, $tax_system, 'unused', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_partially_used'] = $this->countCreditnotes('date', $start_date, $end_date, $tax_system, 'partially_used', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {

            $stats['count_opened'] = $this->countCreditnotes('opened_on', $start_date, $end_date, $tax_system, 'opened', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_closed'] = $this->countCreditnotes('closed_on', $start_date, $end_date, $tax_system, 'closed', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_fully_used'] = $this->countCreditnotes('closed_on', $start_date, $end_date, $tax_system, 'fully_used', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_expired_unused'] = $this->countCreditnotes('closed_on', $start_date, $end_date, $tax_system, 'expired_unused', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_cancelled'] = $this->countCreditnotes('closed_on', $start_date, $end_date, $tax_system, 'cancelled', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_deleted'] = $this->countCreditnotes(null, $start_date, $end_date, $tax_system, 'deleted', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['count_closed_discounted'] = $this->countCreditnotes('closed_on', $start_date, $end_date, $tax_system, 'discounted', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);

        }

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {

            // Totals
            $stats['sum_unit_net'] = $this->sumCreditnotes('unit_net', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_unit_discount'] = $this->sumCreditnotes('unit_discount', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_unit_tax'] = $this->sumCreditnotes('unit_tax', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_unit_gross'] = $this->sumCreditnotes('unit_gross', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_balance'] = $this->sumCreditnotes('balance', 'date', $start_date, $end_date, $tax_system, null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);

            // Sums by Status - Only used on Client Tab
            $stats['sum_pending_unit_gross'] = $this->sumCreditnotes('unit_gross', 'date', $start_date, $end_date, $tax_system, 'pending', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_unused_unit_gross'] = $this->sumCreditnotes('unit_gross', 'date', $start_date, $end_date, $tax_system, 'unused', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_partially_used_unit_gross'] = $this->sumCreditnotes('unit_gross', 'date', $start_date, $end_date, $tax_system, 'partially_used', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_fully_used_unit_gross'] = $this->sumCreditnotes('unit_gross', 'date', $start_date, $end_date, $tax_system, 'fully_used', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_cancelled_unit_gross'] = $this->sumCreditnotes('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, 'cancelled', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_open_unit_gross'] = $this->sumCreditnotes('unit_gross', 'date', $start_date, $end_date, $tax_system, 'open', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_discounted_unit_gross'] = $this->sumCreditnotes('unit_gross', 'date', $start_date, $end_date, $tax_system, 'discounted', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);  // Cannot remove cancelled with discount
            $stats['sum_opened_unit_gross'] = $this->sumCreditnotes('unit_gross', 'opened_on', $start_date, $end_date, $tax_system, 'opened', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_closed_unit_gross'] = $this->sumCreditnotes('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, 'closed', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);
            $stats['sum_closed_discounted_unit_gross'] = $this->sumCreditnotes('unit_gross', 'closed_on', $start_date, $end_date, $tax_system, 'discounted', null, $employee_id, $client_id, $supplier_id, $invoice_id, $expense_id, $otherincome_id);  // Cannot remove cancelled with discount

        }

        /* Items - This might be redundant now - only used in report:financial
        if($record_set == 'items' || $record_set == 'all') {

            $stats['items_count'] = $this->countCreditnoteItems('date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);             // Total Different Items
            $stats['items_sum_unit_qty'] = $this->sumCreditnoteItems('unit_qty', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);
            $stats['items_sum_subtotal_net'] = $this->sumCreditnoteItems('subtotal_net', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);
            $stats['items_sum_subtotal_tax'] = $this->sumCreditnoteItems('subtotal_tax', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);
            $stats['items_sum_subtotal_gross'] = $this->sumCreditnoteItems('subtotal_gross', 'date', $start_date, $end_date, $tax_system, null, null, null, $employee_id, $client_id);

        }*/


        return $stats;

    }

    ####################################################
    #     Count Credit Notes                           #
    ####################################################

    public function countCreditnotes($date_type, $start_date = null, $end_date = null, $tax_system = null, $status = null, $type = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."creditnote_records.creditnote_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->creditnoteBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->creditnoteBuildFilterByStatus($status);

        // Filter by Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.type=".$this->app->db->qStr($type);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Supplier
        if($supplier_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.supplier_id=".$this->app->db->qStr($supplier_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        // Filter by Other Income
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."creditnote_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    #########################################
    #  Sum selected value of Credit Notes   #
    #########################################

    public function sumCreditnotes($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $status = null, $type = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."creditnote_records.creditnote_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->creditnoteBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->creditnoteBuildFilterByStatus($status);

        // Filter by Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.type=".$this->app->db->qStr($type);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Supplier
        if($supplier_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.supplier_id=".$this->app->db->qStr($supplier_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        // Filter by Other Income
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        // Execute the SQL
        $sql = "SELECT SUM(".PRFX."creditnote_records.$value_name) AS sum
                FROM ".PRFX."creditnote_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    #########################################
    #   Build Credit Note Date filter SQL   #
    #########################################

    public function creditnoteBuildFilterByDate($date_type, $start_date = null, $end_date = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date) {
            if ($date_type == 'date') {
                $whereTheseRecords .= " AND ".PRFX."creditnote_records.date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."creditnote_records.date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'due_date') {
                $whereTheseRecords .= " AND ".PRFX."creditnote_records.due_date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."creditnote_records.due_date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'opened_on') {
                $whereTheseRecords .= " AND ".PRFX."creditnote_records.opened_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."creditnote_records.opened_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'closed_on') {
                $whereTheseRecords .= " AND ".PRFX."creditnote_records.closed_on >= ".$this->app->db->qStr($start_date)." AND ".PRFX."creditnote_records.closed_on <= ".$this->app->db->qStr($end_date.' 23:59:59');
            } elseif ($date_type == 'last_active') {
                $whereTheseRecords .= " AND ".PRFX."creditnote_records.last_active >= ".$this->app->db->qStr($start_date)." AND ".PRFX."creditnote_records.last_active <= ".$this->app->db->qStr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #########################################
    #  Build Credit Note Status filter SQL  #
    #########################################

    public function creditnoteBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        // Restrict the records
        if($status == 'open') {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.closed_on IS NULL";
        } elseif($status == 'opened') {
            // Do nothing
        } elseif($status == 'closed') {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.closed_on IS NOT NULL";
        } elseif($status == 'discounted') {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.unit_discount > 0";
        } elseif($status) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.status= ".$this->app->db->qStr($status);
        }

        // Remove `Cancelled` records from the results, unless you are looking up cancelled records except for opened and closed as these are absolutes
        if($status !== 'cancelled' && $status !== 'opened' && $status !== 'closed') {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.status != 'cancelled'";
        }

        // Remove `Deleted` records from the results, unless you are looking up deleted records
        if($status !== 'deleted')
        {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.status != 'deleted'";
        }

        return $whereTheseRecords;

    }

    #############################
    #  Count Credit Note items  #
    #############################

    public function countCreditnoteItems($date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $status = null, $type = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."creditnote_items.creditnote_item_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->creditnoteBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_items.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_items.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->creditnoteBuildFilterByStatus($status);

        // Filter by Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.type=".$this->app->db->qStr($type);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Supplier
        if($supplier_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.supplier_id=".$this->app->db->qStr($supplier_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        // Filter by Other Income
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."creditnote_items
                LEFT JOIN ".PRFX."creditnote_records ON ".PRFX."creditnote_items.creditnote_id = ".PRFX."creditnote_records.creditnote_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    #############################################
    #  Sum selected value of Credit Note items  #
    #############################################

    public function sumCreditnoteItems($value_name, $date_type, $start_date = null, $end_date = null, $tax_system = null, $vat_tax_code = null, $status = null, $type = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null) {

        // Prevent ambiguous error
        $value_name = PRFX."creditnote_items.".$value_name;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."creditnote_items.creditnote_item_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->creditnoteBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_items.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Filter by VAT Tax Code
        if($vat_tax_code) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_items.vat_tax_code=".$this->app->db->qStr($vat_tax_code);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->creditnoteBuildFilterByStatus($status);

        // Filter by Type
        if($type) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.type=".$this->app->db->qStr($type);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Supplier
        if($supplier_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.supplier_id=".$this->app->db->qStr($supplier_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        // Filter by Other Income
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."creditnote_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        $sql = "SELECT SUM($value_name) AS sum
                FROM ".PRFX."creditnote_items
                LEFT JOIN ".PRFX."creditnote_records ON ".PRFX."creditnote_items.creditnote_id = ".PRFX."creditnote_records.creditnote_id
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'];

    }

    /** Payments **/

    #####################################
    #   Get All payments stats          #
    #####################################

    public function getPaymentsStats($record_set, $start_date = null, $end_date = null, $tax_system = null, $employee_id = null, $client_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null, $creditnote_id = null, $voucher_id = null) {

        $stats = array();

        // Current
        if($record_set == 'current' || $record_set == 'all') {

            $stats['count_valid'] = $this->countPayments('date', $start_date, $end_date, $tax_system, 'valid', null, null, $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, $voucher_id);

        }

        // Historic
        if($record_set == 'historic' || $record_set == 'all') {

            $stats['count_invoice'] = $this->countPayments('date', $start_date, $end_date, $tax_system, null, 'invoice', null, null, $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, $voucher_id);
            $stats['count_expense'] = $this->countPayments('date', $start_date, $end_date, $tax_system, null, 'expense', null, null, $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, $voucher_id);
            $stats['count_otherincome'] = $this->countPayments('date', $start_date, $end_date, $tax_system, null, 'otherincome', null, null, $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, $voucher_id);
            $stats['count_sent'] = $this->countPayments('date', $start_date, $end_date, $tax_system, null, 'monies_sent', null, 'monies_sent', $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, 'monies_sent', $voucher_id);
            $stats['count_received'] = $this->countPayments('date', $start_date, $end_date, $tax_system, null, 'monies_received', null, 'monies_received', $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, 'monies_received', $voucher_id);

        }

        // Revenue
        if($record_set == 'revenue' || $record_set == 'all') {
            $stats['sum_invoice'] = $this->sumPayments('date', $start_date, $end_date, $tax_system, null, 'invoice', null, null, $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, $voucher_id);
            $stats['sum_expense'] = $this->sumPayments('date', $start_date, $end_date, $tax_system, null, 'expense', null, null, $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, $voucher_id);
            $stats['sum_otherincome'] = $this->sumPayments('date', $start_date, $end_date, $tax_system, null, 'otherincome', null, null, $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, $voucher_id);
            $stats['sum_sent'] = $this->sumPayments('date', $start_date, $end_date, $tax_system, null, 'monies_sent', null, 'monies_sent', $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, 'monies_sent', $voucher_id);
            $stats['sum_received'] = $this->sumPayments('date', $start_date, $end_date, $tax_system, null, 'monies_received', null, 'monies_received', $employee_id, $client_id, $invoice_id, $expense_id, $otherincome_id, $creditnote_id, 'monies_received', $voucher_id);

        }

        return $stats;

    }

    ####################################################
    #     Count Payments                               #
    ####################################################

    public function countPayments($date_type, $start_date = null, $end_date = null, $tax_system = null, $status = null, $type = null, $method = null, $paymentDirection = null, $employee_id = null, $client_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null, $creditnote_id = null, $voucher_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->paymentBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->paymentBuildFilterByStatus($status);

        // Restrict by Type
        $whereTheseRecords .= $this->paymentBuildFilterByType($type);

        // Filter by Method
        if($method) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.method=".$this->app->db->qStr($method);
        }

        // Restrict by Direction
        $whereTheseRecords .= $this->paymentBuildFilterByPaymentDirection($paymentDirection);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        // Filter by Otherincome
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        // Filter by Creditnote
        if($creditnote_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.creditnote_id=".$this->app->db->qStr($creditnote_id);
        }

        // Filter by Voucher
        if($voucher_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.voucher_id=".$this->app->db->qStr($voucher_id);
        }

        // Execute the SQL
        $sql = "SELECT COUNT(*) AS count
                FROM ".PRFX."payment_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

    #########################################
    #  Sum selected value of payments       #
    #########################################

    public function sumPayments($date_type, $start_date = null, $end_date = null, $tax_system = null, $status = null, $type = null, $method = null, $paymentDirection = null, $employee_id = null, $client_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null, $creditnote_id = null, $voucher_id = null) {

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";

        // Filter by Date
        $whereTheseRecords .= $this->paymentBuildFilterByDate($date_type, $start_date, $end_date);

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->paymentBuildFilterByStatus($status);

        // Restrict by Type
        $whereTheseRecords .= $this->paymentBuildFilterByType($type);

        // Filter by Method
        if($method) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.method=".$this->app->db->qStr($method);
        }

        // Restrict by Direction
        $whereTheseRecords .= $this->paymentBuildFilterByPaymentDirection($paymentDirection);

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        // Filter by Otherincome
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        // Filter by Creditnote
        if($creditnote_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.creditnote_id=".$this->app->db->qStr($creditnote_id);
        }

        // Filter by Voucher
        if($voucher_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.voucher_id=".$this->app->db->qStr($voucher_id);
        }

        // Execute the SQL
        $sql = "SELECT SUM(".PRFX."payment_records.amount) AS sum
                FROM ".PRFX."payment_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['sum'] ?: 0.00;

    }

    ########################################
    #   Build payment Date filter SQL      #
    ########################################

    public function paymentBuildFilterByDate($date_type, $start_date = null, $end_date = null) {

        $whereTheseRecords = '';

        if($start_date && $end_date) {
            if ($date_type == 'date') {
                $whereTheseRecords .= " AND ".PRFX."payment_records.date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."payment_records.date <= ".$this->app->db->qStr($end_date);
            } elseif ($date_type == 'last_active') {
                $whereTheseRecords .= " AND ".PRFX."payment_records.last_active >= ".$this->app->db->qStr($start_date)." AND ".PRFX."payment_records.last_active <= ".$this->app->db->qStr($end_date.' 23:59:59');
            }
        }

        return $whereTheseRecords;

    }

    #####################################
    #  Build payment Status filter SQL  #
    #####################################

    public function paymentBuildFilterByStatus($status = null) {

        $whereTheseRecords = '';

        // Return records for the given status
        if($status) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.status= ".$this->app->db->qStr($status);
        }

        // Remove `Cancelled` records from the results, unless you are looking up cancelled records except for opened and closed as these are absolutes
        if($status !== 'cancelled') {
            $whereTheseRecords .= " AND ".PRFX."payment_records.status != 'cancelled'";
        }

        // Remove `Deleted` records from the results, unless you are looking up deleted records
        if($status !== 'deleted')
        {
            $whereTheseRecords .= " AND ".PRFX."payment_records.status != 'deleted'";
        }

        return $whereTheseRecords;

    }

    #####################################
    #  Build payment type filter SQL    # // Restrict by Type
    #####################################

    public function paymentBuildFilterByType($type = null) {

        $whereTheseRecords = '';

        // All received monies
        if($type == 'monies_received') {
            $whereTheseRecords .= " AND ".PRFX."payment_records.type IN ('invoice', 'otherincome', 'creditnote')";

        // All sent monies
        } elseif($type == 'monies_sent') {
            $whereTheseRecords .= " AND ".PRFX."payment_records.type IN ('expense', 'creditnote')";

        // Return records for the given type
        } elseif($type) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.type= ".$this->app->db->qStr($type);
        }

        // By default remove `voucher` records from the results, unless you are looking up voucher records.
        // Vouchers are not real payments and are accounted for elsewhere
        else
        {
            $whereTheseRecords .= " AND ".PRFX."payment_records.type != 'voucher'";
        }

        return $whereTheseRecords;

    }

    ##########################################
    #  Build payment direction filter SQL    # // Restrict by direction
    ##########################################  TODO: do i need to transpose monies_sent here, can i not just use debit/credit, monies_sent shopuld be used for real money

    public function paymentBuildFilterByPaymentDirection($paymentDirection = null) {

        $whereTheseRecords = '';

        // All received monies
        if($paymentDirection == 'monies_received') {
            $whereTheseRecords .= " AND ".PRFX."payment_records.direction = 'credit'";

        // All sent monies
        } elseif($paymentDirection == 'monies_sent') {
            $whereTheseRecords .= " AND ".PRFX."payment_records.direction = 'debit'";

        // Return records for the given direction
        } elseif($paymentDirection) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.direction= ".$this->app->db->qStr($paymentDirection);
        }

        return $whereTheseRecords;

    }

    ##############################################################################################  // cancelled payment records are ignored
    #  Calulate the revenue and tax liability for a ALL payments against their parent record     #  // I dont use most of these filters at the minute (only start_date, end_date and tax_system)
    ##############################################################################################

    // This is for calculating TAX liability from invoices and is aware of partially paid invoices.
    // By taking each payment and breaking them down into 'NET, TAX and GROSS' by prorata'ing them against their parent transaction.
    // Vouchers are not real money and should therefore not contribute anything to the the NET and GROSS totals, however:
    // MPV (multi purpose vouchers i.e. phone top up) vouchers have their TAX liability accounted for at the point of redemption, so does add TAX to the totals,
    // SPV (single purpose voucher i.e. gift card) has already had the TAX taken at the point of sale so does not suffer this fate.
    // 'voucher' allows me to pass up the tree how much of vouchers SPV/MPV (in their NET/TAX/GROSS) have actually been paid (it is prorated aswell). This is separate to revenue totals and used upstream.

    // Does this function need all of these variables, start_date, end_date and tax_system should be enough? only report:financial uses this and it does not use all the variables
    public function revenuePaymentsProratedAgainstRecords($start_date = null, $end_date = null, $tax_system = null, $status = null, $type = null, $method = null, $employee_id = null, $client_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null, $creditnote_id =null) {

        // Holding array for prorata totals // I could use a blank array here??? but it is a good reference
        $prorata_totals = array(
                            "invoice" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                            //"voucher" => array("spv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00), "mpv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00)),  (not currently used)
                            "expense" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                            "otherincome" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),

                            /// ADD credit  notes to this function prorata, only the variable at the top added. these variables might get stripped
                            );

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";

        // Filter by Date
        if($start_date && $end_date) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.date >= ".$this->app->db->qStr($start_date)." AND ".PRFX."payment_records.date <= ".$this->app->db->qStr($end_date);
        }

        // Filter by Tax System
        if($tax_system) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.tax_system=".$this->app->db->qStr($tax_system);
        }

        // Restrict by Status
        $whereTheseRecords .= $this->paymentBuildFilterByStatus($status);

        // Restrict by Type
        $whereTheseRecords .= $this->paymentBuildFilterByType($type);

        // Filter by Method
        if($method) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.method=".$this->app->db->qStr($method);
        }

        // Filter by Employee
        if($employee_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$this->app->db->qStr($employee_id);
        }

        // Filter by Client
        if($client_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qStr($client_id);
        }

        // Filter by Invoice
        if($invoice_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qStr($invoice_id);
        }

        // Filter by Expense
        if($expense_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$this->app->db->qStr($expense_id);
        }

        // Filter by Otherincome
        if($otherincome_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$this->app->db->qStr($otherincome_id);
        }

        // Filter by Credit Note
        if($creditnote_id) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.creditnote_id=".$this->app->db->qStr($creditnote_id);
        }

        // Execute the SQL
        $sql = "SELECT *
                FROM ".PRFX."payment_records
                ".$whereTheseRecords;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {

            $prorata_record = null;

            if($rs->fields['type'] == 'invoice') {
                $prorata_record = $this->revenuePaymentProratedAgainstRecord($rs->fields['payment_id'], 'invoice');

                // Vouchers must be compensated for profit purposes
                if($rs->fields['method'] == 'voucher') {

                    $voucher_type = $this->app->components->voucher->getRecord($rs->fields['voucher_id'], 'type');

                    // Multi Purpose Voucher
                    if($voucher_type == 'mpv') {
                        $prorata_totals['invoice']['net'] += 0.00;
                        $prorata_totals['invoice']['tax'] += $prorata_record['tax'];
                        $prorata_totals['invoice']['gross'] += 0.00;

                        /* Total the transaction amounts that have been paid for with MPV voucher (not currently used)
                        $prorata_totals['voucher']['mpv']['net'] += $prorata_record['net'];
                        $prorata_totals['voucher']['mpv']['tax'] += $prorata_record['tax'];
                        $prorata_totals['voucher']['mpv']['gross'] += $prorata_record['gross'];*/

                    }

                    // Single Purpose Voucher
                    if($voucher_type == 'spv') {
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
        if($record_type == 'expense') {$record_details = $this->app->components->expense->getRecord($payment_details['expense_id']);}
        if($record_type == 'otherincome') {$record_details = $this->app->components->otherincome->getRecord($payment_details['otherincome_id']);}

        // Calcualte the proata values
        $percentage = $payment_details['amount'] / $record_details['unit_gross'];
        $record_prorata_totals['net'] = $record_details['unit_net'] * $percentage;
        $record_prorata_totals['tax'] = $record_details['unit_tax'] * $percentage;
        $record_prorata_totals['gross'] = $record_details['unit_gross'] * $percentage;

        /* This gets the exact amounts of vouchers paid for, used upstream (not currently used)
        if($record_type == 'invoice') {
            $record_prorata_totals['voucher']['spv']['net'] = $this->sum_vouchers('unit_net', null, null, null, null, null, 'spv', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['spv']['tax'] = $this->sum_vouchers('unit_tax', null, null, null, null, null, 'spv', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['spv']['gross'] = $this->sum_vouchers('unit_gross', null, null, null, null, null, 'spv', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['mpv']['net'] = $this->sum_vouchers('unit_net', null, null, null, null, null, 'mpv', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['mpv']['tax'] = $this->sum_vouchers('unit_tax', null, null, null, null, null, 'mpv', null, null, null, $record_details['invoice_id']) * $percentage;
            $record_prorata_totals['voucher']['mpv']['gross'] = $this->sum_vouchers('unit_gross', null, null, null, null, null, 'mpv', null, null, null, $record_details['invoice_id']) * $percentage;
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

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['count'];

    }

}
