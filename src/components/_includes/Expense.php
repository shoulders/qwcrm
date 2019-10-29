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

class Expense extends Components {

    /** Mandatory Code **/

    /** Display Functions **/


    #####################################################
    #         Display expenses                          #
    #####################################################

    public function display_expenses($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $item_type = null, $status = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';
        $search_category = $search_category ?: 'expense_id';    

        /* Records Search */

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";

        // Restrict results by search category and search term
        if($search_term) {$whereTheseRecords .= " AND ".PRFX."expense_records.$search_category LIKE ".$this->db->qstr('%'.$search_term.'%');}     

        /* Filter the Records */  

        // Restrict by Item Type
        if($item_type) { $whereTheseRecords .= " AND ".PRFX."expense_records.item_type= ".$this->db->qstr($item_type);}

        // Restrict by status
        if($status) {$whereTheseRecords .= " AND ".PRFX."expense_records.status= ".$this->db->qstr($status);} 

        /* The SQL code */

        $sql =  "SELECT * 
                FROM ".PRFX."expense_records                                                   
                ".$whereTheseRecords."            
                GROUP BY ".PRFX."expense_records.".$order_by."
                ORDER BY ".PRFX."expense_records.".$order_by."
                ".$direction;           

        /* Restrict by pages */

        if($use_pages) {

            // Get Start Record
            $start_record = (($page_no * $records_per_page) - $records_per_page);

            // Figure out the total number of records in the database for the given search        
            if(!$rs = $this->db->Execute($sql)) {
                $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to count the matching expense records."));
            } else {        
                $total_results = $rs->RecordCount();            
                $this->smarty->assign('total_results', $total_results);
            }        

            // Figure out the total number of pages. Always round up using ceil()
            $total_pages = ceil($total_results / $records_per_page);
            $this->smarty->assign('total_pages', $total_pages);

            // Set the page number
            $this->smarty->assign('page_no', $page_no);

            // Assign the Previous page        
            $previous_page_no = ($page_no - 1);        
            $this->smarty->assign('previous_page_no', $previous_page_no);          

            // Assign the next page        
            if($page_no == $total_pages) {$next_page_no = 0;}
            elseif($page_no < $total_pages) {$next_page_no = ($page_no + 1);}
            else {$next_page_no = $total_pages;}
            $this->smarty->assign('next_page_no', $next_page_no);

            // Only return the given page's records
            $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;

            // add the restriction on to the SQL
            $sql .= $limitTheseRecords;

        } else {

            // This make the drop down menu look correct
            $this->smarty->assign('total_pages', 1);

        }

        /* Return the records */

        if(!$rs = $this->db->Execute($sql)) {

            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to return the matching expense records."));

        } else {

            $records = $rs->GetArray();   // do i need to add the check empty

            if(empty($records)){

                return false;

            } else {

                return $records;

            }

        }

    }

    /** Insert Functions **/

    ##########################################
    #      Insert Expense                    #
    ##########################################

    public function insert_expense($qform) {

        $sql = "INSERT INTO ".PRFX."expense_records SET
                employee_id     =". $this->db->qstr( $this->app->user->login_user_id ).",
                payee           =". $this->db->qstr( $qform['payee']                   ).",
                date            =". $this->db->qstr( $this->app->system->general->date_to_mysql_date($qform['date'])).",
                tax_system      =". $this->db->qstr( QW_TAX_SYSTEM                   ).",              
                item_type       =". $this->db->qstr( $qform['item_type']               ).",
                unit_net        =". $this->db->qstr( $qform['unit_net']                ).",
                vat_tax_code    =". $this->db->qstr( $qform['vat_tax_code']            ).",
                unit_tax_rate   =". $this->db->qstr( $qform['unit_tax_rate']           ).",
                unit_tax        =". $this->db->qstr( $qform['unit_tax']                ).",
                unit_gross      =". $this->db->qstr( $qform['unit_gross'  ]            ).",
                status          =". $this->db->qstr( 'unpaid'                        ).",            
                opened_on       =". $this->db->qstr( $this->app->system->general->mysql_datetime()                ).",              
                items           =". $this->db->qstr( $qform['items']                   ).",
                note            =". $this->db->qstr( $qform['note']                    );            

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to insert the expense record into the database."));
        } else {

            /* This code is not used because I removed 'invoice_id'
             * Get related invoice details
            $invoice_details = $this->app->components->invoice->get_invoice_details($qform['invoice_id']);

            // Create a Workorder History Note
            $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$this->db->Insert_ID().' '._gettext("added").' '._gettext("by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Expense Record").' '.$this->db->Insert_ID().' '._gettext("created.");
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $invoice_details['workorder_id'], $invoice_details['client_id'], $qform['invoice_id']);

            // Update last active record
            $this->app->components->client->update_client_last_active($invoice_details['client_id']);
            $this->app->components->workorder->update_workorder_last_active($invoice_details['workorder_id']);
            $this->app->components->invoice->update_invoice_last_active($qform['invoice_id']);*/

            return $this->db->Insert_ID();

        }

    } 

    /** Get Functions **/

    ##########################
    #  Get Expense Details   #
    ##########################

    public function get_expense_details($expense_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."expense_records WHERE expense_id=".$this->db->qstr($expense_id);

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get the expense details."));
        } else {

            if($item === null){

                return $rs->GetRowAssoc();            

            } else {

                return $rs->fields[$item];   

            } 

        }

    }

    #####################################
    #    Get Expense Statuses           #
    #####################################

    public function get_expense_statuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."expense_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get Expense statuses."));
        } else {

            return $rs->GetArray();     

        }    

    }

    ######################################
    #  Get Expense status display name   # // might not be used anymore 
    ######################################

    public function get_expense_status_display_name($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."expense_statuses WHERE status_key=".$this->db->qstr($status_key);

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get the expense status display name."));
        } else {

            return $rs->fields['display_name'];

        }    

    }

    #####################################
    #    Get Expense Types              #
    #####################################

    public function get_expense_types() {

        $sql = "SELECT * FROM ".PRFX."expense_types";

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get expense types."));
        } else {

            return $rs->GetArray();

        }    

    }

    /** Update Functions **/

    #####################################
    #     Update Expense                #
    #####################################

    public function update_expense($qform) {

        $sql = "UPDATE ".PRFX."expense_records SET
                employee_id         =". $this->db->qstr( $this->app->user->login_user_id ).",
                payee               =". $this->db->qstr( $qform['payee']                    ).",            
                date                =". $this->db->qstr( $this->app->system->general->date_to_mysql_date($qform['date']) ).",            
                item_type           =". $this->db->qstr( $qform['item_type']                ).",
                unit_net            =". $this->db->qstr( $qform['unit_net']                 ).",
                vat_tax_code        =". $this->db->qstr( $qform['vat_tax_code']             ).",
                unit_tax_rate       =". $this->db->qstr( $qform['unit_tax_rate']            ).",
                unit_tax            =". $this->db->qstr( $qform['unit_tax']                 ).",
                unit_gross          =". $this->db->qstr( $qform['unit_gross']               ).",
                last_active         =". $this->db->qstr( $this->app->system->general->mysql_datetime()                 ).",
                items               =". $this->db->qstr( $qform['items']                    ).",
                note                =". $this->db->qstr( $qform['note']                     )."
                WHERE expense_id    =". $this->db->qstr( $qform['expense_id']               );                        

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update the expense details."));
        } else {

            /* This code is not used because I removed 'invoice_id'
             * Get related invoice details
            $invoice_details = $this->app->components->invoice->get_invoice_details($qform['invoice_id']);

            // Create a Workorder History Note
            $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("updated").' '._gettext("by").' '.$this->app->user->login_display_name.'.');

            // Log activity
            $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("updated.");
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $invoice_details['workorder_id'], $invoice_details['client_id'], $qform['invoice_id']);

            // Update last active record
            $this->app->components->client->update_client_last_active($invoice_details['client_id']);
            $this->app->components->workorder->update_workorder_last_active($invoice_details['workorder_id']);
            $this->app->components->invoice->update_invoice_last_active($qform['invoice_id']);*/ 

            return true;

        }

    } 

    ############################
    # Update Expense Status    #
    ############################

    public function update_expense_status($expense_id, $new_status, $silent = false) {

        // Get expense details
        $expense_details = $this->get_expense_details($expense_id);

        // if the new status is the same as the current one, exit
        if($new_status == $expense_details['status']) {        
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }    

        // Unify Dates and Times
        $datetime = $this->app->system->general->mysql_datetime();

        // Set the appropriate closed_on date
        $closed_on = ($new_status == 'paid') ? $datetime : '0000-00-00 00:00:00';

        $sql = "UPDATE ".PRFX."expense_records SET
                status             =". $this->db->qstr( $new_status   ).",
                closed_on          =". $this->db->qstr( $closed_on    ).",
                last_active        =". $this->db->qstr( $datetime     )."
                WHERE expense_id   =". $this->db->qstr( $expense_id   );

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update an Expense Status."));

        } else {        

            // Status updated message
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("Expense status updated.")); }

            // For writing message to log file, get expense status display name
            /*$expense_status_display_name = _gettext($this->get_expense_status_display_name($new_status));

            /* This code is not used because I removed 'invoice_id'
             * Get related invoice details
            $invoice_details = $this->app->components->invoice->get_invoice_details($expense_details['invoice_id']);

            // Create a Workorder History Note (Not Used)      
            $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense Status updated to").' '.$expense_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Expense").' '.$expense_id.' '._gettext("Status updated to").' '.$expense_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $expense_details['invoice_id']);

            // Update last active record (Not Used)
            $this->app->components->client->update_client_last_active($invoice_details['client_id']);
            $this->app->components->workorder->update_workorder_last_active($invoice_details['workorder_id']);
            $this->app->components->invoice->update_invoice_last_active($expense_details['invoice_id']);*/

            return true;

        }

    }

    /** Close Functions **/

    #####################################
    #   Cancel Expense                  #
    #####################################

    public function cancel_expense($expense_id) {

        // Make sure the expense can be cancelled
        if(!$this->check_expense_can_be_cancelled($expense_id)) {        
            return false;
        }

        // Get expense details
        $expense_details = $this->get_expense_details($expense_id);

        // Get related invoice details
        //$invoice_details = $this->app->components->invoice->get_invoice_details($expense_details['invoice_id']);

        // Change the expense status to cancelled (I do this here to maintain consistency)
        $this->update_expense_status($expense_id, 'cancelled');      

        /*Create a Workorder History Note  
        $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');*/

        // Log activity        
        $record = _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id);

        /* Log activity        
        $record = _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $expense_details['invoice_id']);

        // Update last active record
        $this->app->components->client->update_client_last_active($invoice_details['client_id']);
        $this->app->components->workorder->update_workorder_last_active($invoice_details['workorder_id']);
        $this->app->components->invoice->update_invoice_last_active($expense_details['invoice_id']);*/

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Record                  #
    #####################################

    public function delete_expense($expense_id) {

        /* Get invoice_id before deleting the record
        $invoice_id = $this->get_expense_details($expense_id, 'invoice_id');

        // Get related invoice details before deleting the record
        $invoice_details = $this->app->components->invoice->get_invoice_details($invoice_id);*/

        // Change the expense status to deleted (I do this here to maintain consistency)
        $this->update_expense_status($expense_id, 'deleted');  

        $sql = "UPDATE ".PRFX."expense_records SET
                employee_id         = '',
                payee               = '',           
                date                = '0000-00-00', 
                tax_system          = '',  
                item_type           = '',
                unit_net            = '',
                vat_tax_code        = '',
                unit_tax_rate       = '0.00',
                unit_tax            = '0.00',
                unit_gross          = '0.00',
                balance             = '0.00',
                status              = 'deleted', 
                opened_on           = '0000-00-00 00:00:00',
                closed_on           = '0000-00-00 00:00:00',
                last_active         = '0000-00-00 00:00:00',
                items               = '',
                note                = ''
                WHERE expense_id    =". $this->db->qstr($expense_id);

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to delete the expense record."));
        } else {

            /* Create a Workorder History Note  
            $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.');*/

            // Log activity        
            $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("deleted.");
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id);

            /* Log activity        
            $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("deleted.");
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

            // Update last active record
            $this->app->components->client->update_client_last_active($invoice_details['client_id']);
            $this->app->components->workorder->update_workorder_last_active($invoice_details['workorder_id']);
            //$this->app->components->invoice->update_invoice_last_active($invoice_id);*/

            return true;

        } 

    }

    /** Other Functions **/

    ##########################################
    #      Last Record Look Up               #  // not currently used
    ##########################################

    public function last_expense_id_lookup() {

        $sql = "SELECT * FROM ".PRFX."expense_records ORDER BY expense_id DESC LIMIT 1";

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to lookup the last expense record ID."));
        } else {

            return $rs->fields['expense_id'];

        }

    }

    #####################################
    #   Recalculate Expense Totals      #
    #####################################

    public function recalculate_expense_totals($expense_id) {

        $expense_details            = $this->get_expense_details($expense_id);    
        $unit_gross                 = $expense_details['unit_gross'];   
        $payments_sub_total         = $this->app->components->report->sum_payments(null, null, 'date', null, 'valid', 'expense', null, null, null, null, null, $expense_id);
        $balance                    = $unit_gross - $payments_sub_total;

        $sql = "UPDATE ".PRFX."expense_records SET
                balance             =". $this->db->qstr( $balance    )."
                WHERE expense_id    =". $this->db->qstr( $expense_id );

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to recalculate the expense totals."));
        } else {

            /* Update Status - only change if there is a change in status */        

            // Balance = Gross Amount (i.e no payments)
            if($unit_gross > 0 && $unit_gross == $balance && $expense_details['status'] != 'unpaid') {
                $this->update_expense_status($expense_id, 'unpaid');
            }

            // Balance < Gross Amount (i.e some payments)
            elseif($unit_gross > 0 && $payments_sub_total > 0 && $payments_sub_total < $unit_gross && $expense_details['status'] != 'partially_paid') {            
                $this->update_expense_status($expense_id, 'partially_paid');
            }

            // Balance = 0.00 (i.e has payments and is all paid)
            elseif($unit_gross > 0 && $unit_gross == $payments_sub_total && $expense_details['status'] != 'paid') {            
                $this->update_expense_status($expense_id, 'paid');
            }        

            return;        

        }

    }

    ##########################################################
    #  Check if the expense status is allowed to be changed  #  // not currently used
    ##########################################################

     public function check_expense_status_can_be_changed($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->get_expense_details($expense_id);

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments and is partially paid."));
            $state_flag = false;        
        }

        // Is paid
        if($expense_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments and is paid."));
            $state_flag = false;        
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has been deleted."));
            $state_flag = false;        
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments."));
            $state_flag = false;        
        }

        return $state_flag;    

     }

    ###############################################################
    #   Check to see if the expense can be refunded (by status)   #  // not currently used - i DONT think i will use this
    ###############################################################

    public function check_expense_can_be_refunded($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->get_expense_details($expense_id);

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be refunded because the expense is partially paid."));
            $state_flag = false;
        }

        // Is refunded
        if($expense_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be refunded because the expense has already been refunded."));
            $state_flag = false;        
        }

        // Is cancelled
        if($expense_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be refunded because the expense has been cancelled."));
            $state_flag = false;        
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be refunded because the expense has been deleted."));
            $state_flag = false;        
        }    

        // Has no payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if(!$this->app->components->report->count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be refunded because the expense has no payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the expense can be cancelled              #  // not currently used
    ###############################################################

    public function check_expense_can_be_cancelled($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->get_expense_details($expense_id);

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be cancelled because the expense is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($expense_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is paid."));
            $state_flag = false;        
        }

        // Is cancelled
        if($expense_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be cancelled because the expense has already been cancelled."));
            $state_flag = false;        
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be cancelled because the expense has been deleted."));
            $state_flag = false;        
        }    

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be cancelled because the expense has payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the expense can be deleted                #
    ###############################################################

    public function check_expense_can_be_deleted($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->get_expense_details($expense_id);

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is partially paid."));
            $state_flag = false;        
        }

        // Is paid
        if($expense_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is paid."));
            $state_flag = false;        
        }

        // Is cancelled
        if($expense_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has been cancelled."));
            $state_flag = false;        
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it already been deleted."));
            $state_flag = false;        
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ##########################################################
    #  Check if the expense status allows editing            #       
    ##########################################################

     public function check_expense_can_be_edited($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->get_expense_details($expense_id);

        // Is on a different tax system
        if($expense_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be edited because it is on a different Tax system."));
            $state_flag = false;        
        }

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments and is partially paid."));
            $state_flag = false;        
        }

        // Is paid
        if($expense_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments and is paid."));
            $state_flag = false;        
        }

        // Is cancelled
        if($expense_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has been cancelled."));
            $state_flag = false;        
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be edited because it has been deleted."));
            $state_flag = false;        
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->count_payments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments."));
            $state_flag = false;        
        }   

        // The current record VAT code is enabled
        if(!$this->app->components->company->get_vat_tax_code_status($expense_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it's current VAT Tax Code is not enabled."));
            $state_flag = false; 
        }

        return $state_flag;    

    }

}