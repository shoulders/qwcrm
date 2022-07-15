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

    /** Insert Functions **/

    ##########################################
    #      Insert Expense                    #
    ##########################################

    public function insertRecord($qform) {

        $sql = "INSERT INTO ".PRFX."expense_records SET
                employee_id     =". $this->app->db->qStr( $this->app->user->login_user_id   ).",
                payee           =". $this->app->db->qStr( $qform['payee']                   ).",
                date            =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['date'])).",
                tax_system      =". $this->app->db->qStr( QW_TAX_SYSTEM                     ).",              
                type            =". $this->app->db->qStr( $qform['type']                    ).",
                unit_net        =". $this->app->db->qStr( $qform['unit_net']                ).",                
                unit_tax        =". $this->app->db->qStr( $qform['unit_tax']                ).",
                unit_gross      =". $this->app->db->qStr( $qform['unit_gross'  ]            ).",
                status          =". $this->app->db->qStr( 'unpaid'                          ).",            
                opened_on       =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime()                ).",              
                items           =". $this->app->db->qStr( $qform['items']                   ).",
                note            =". $this->app->db->qStr( $qform['note']                    );            

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* This code is not used because I removed 'invoice_id'
         * Get related invoice details
        $invoice_details = $this->app->components->invoice->get_invoice_details($qform['invoice_id']);

        // Create a Workorder History Note
        $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$this->app->db->Insert_ID().' '._gettext("added").' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Expense Record").' '.$this->app->db->Insert_ID().' '._gettext("created.");
        $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $invoice_details['workorder_id'], $invoice_details['client_id'], $qform['invoice_id']);

        // Update last active record
        $this->app->components->client->update_client_last_active($invoice_details['client_id']);
        $this->app->components->workorder->update_workorder_last_active($invoice_details['workorder_id']);
        $this->app->components->invoice->update_invoice_last_active($qform['invoice_id']);*/

        return $this->app->db->Insert_ID();        

    } 


    /** Get Functions **/


    #####################################################
    #         Display expenses                          #
    #####################################################

    public function getRecords($order_by, $direction, $records_per_page = 0, $use_pages = false, $page_no = null, $search_category = 'expense_id', $search_term = null, $type = null, $status = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;  

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";

        // Restrict results by search category and search term
        if($search_term) {$whereTheseRecords .= " AND ".PRFX."expense_records.$search_category LIKE ".$this->app->db->qStr('%'.$search_term.'%');}     

        // Restrict by type
        if($type) { $whereTheseRecords .= " AND ".PRFX."expense_records.type= ".$this->app->db->qStr($type);}

        // Restrict by status
        if($status) {$whereTheseRecords .= " AND ".PRFX."expense_records.status= ".$this->app->db->qStr($status);} 

        // The SQL code
        $sql = "SELECT * 
                FROM ".PRFX."expense_records                                                   
                ".$whereTheseRecords."            
                GROUP BY ".PRFX."expense_records.".$order_by."
                ORDER BY ".PRFX."expense_records.".$order_by."
                ".$direction;    
        
        // Get the total number of records in the database for the given search    
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}      
        $total_results = $rs->RecordCount();        

        // Restrict by pages
        if($use_pages) {

            // Get Start Record
            $start_record = (($page_no * $records_per_page) - $records_per_page);

            // Figure out the total number of pages. Always round up using ceil()
            $total_pages = ceil($total_results / $records_per_page);
            
            // Assign the Previous page        
            $previous_page_no = ($page_no - 1);        
             
            // Assign the next page        
            if($page_no == $total_pages) {$next_page_no = 0;}
            elseif($page_no < $total_pages) {$next_page_no = ($page_no + 1);}
            else {$next_page_no = $total_pages;}            

            // Only return the given page's records
            $sql .= " LIMIT ".$start_record.", ".$records_per_page;

        // Restrict by number of records   
        } elseif($records_per_page) {

            // Only return the first x number of records
            $sql .= " LIMIT 0, ".$records_per_page;

            // Show restricted records message if required
            $restricted_records = $total_results > $records_per_page ? true : false;

        }

        // Get the records
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Return the data        
        return array(
                'records' => $rs->GetArray(),
                'total_results' => $total_results,
                'total_pages' => $total_pages ?? 1,             // This make the drop down menu look correct on search tpl with use_pages off
                'page_no' => $page_no,
                'previous_page_no' => $previous_page_no ?? null,
                'next_page_no' => $next_page_no ?? null,                    
                'restricted_records' => $restricted_records ?? false,
                );

    }  

    ##########################
    #  Get Expense Details   #
    ##########################

    public function getRecord($expense_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."expense_records WHERE expense_id=".$this->app->db->qStr($expense_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc();            

        } else {

            return $rs->fields[$item];   

        }        

    }

    #####################################
    #    Get Expense Statuses           #
    #####################################

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."expense_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray(); 
        
    }

    ######################################
    #  Get Expense status display name   # // might not be used anymore 
    ######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."expense_statuses WHERE status_key=".$this->app->db->qStr($status_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];  

    }

    #####################################
    #    Get Expense Types              #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."expense_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();           

    }

    ##########################################
    #      Last Record Look Up               #  // not currently used
    ##########################################

    public function getLastRecordId() {

        $sql = "SELECT * FROM ".PRFX."expense_records ORDER BY expense_id DESC LIMIT 1";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['expense_id'];

    }    
    
    
    /** Update Functions **/

    #####################################
    #     Update Expense                #
    #####################################

    public function updateRecord($qform) {

        $sql = "UPDATE ".PRFX."expense_records SET
                employee_id         =". $this->app->db->qStr( $this->app->user->login_user_id ).",
                payee               =". $this->app->db->qStr( $qform['payee']                    ).",            
                date                =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['date']) ).",            
                type                =". $this->app->db->qStr( $qform['type']                     ).",
                unit_net            =". $this->app->db->qStr( $qform['unit_net']                 ).",                
                unit_tax            =". $this->app->db->qStr( $qform['unit_tax']                 ).",
                unit_gross          =". $this->app->db->qStr( $qform['unit_gross']               ).",
                last_active         =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime()                 ).",
                items               =". $this->app->db->qStr( $qform['items']                    ).",
                note                =". $this->app->db->qStr( $qform['note']                     )."
                WHERE expense_id    =". $this->app->db->qStr( $qform['expense_id']               );                        

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

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

    ############################
    # Update Expense Status    #
    ############################

    public function updateStatus($expense_id, $new_status, $silent = false) {

        // Get expense details
        $expense_details = $this->getRecord($expense_id);

        // if the new status is the same as the current one, exit
        if($new_status == $expense_details['status']) {        
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }    

        // Unify Dates and Times
        $datetime = $this->app->system->general->mysqlDatetime();

        // Set the appropriate closed_on date
        $closed_on = ($new_status == 'paid') ? $datetime : '0000-00-00 00:00:00';

        $sql = "UPDATE ".PRFX."expense_records SET
                status             =". $this->app->db->qStr( $new_status   ).",
                closed_on          =". $this->app->db->qStr( $closed_on    ).",
                last_active        =". $this->app->db->qStr( $datetime     )."
                WHERE expense_id   =". $this->app->db->qStr( $expense_id   );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}       

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

    /** Close Functions **/

    #####################################
    #   Cancel Expense                  #
    #####################################

    public function cancelRecord($expense_id) {

        // Make sure the expense can be cancelled
        if(!$this->checkRecordAllowsCancel($expense_id)) {        
            return false;
        }

        // Get expense details
        $expense_details = $this->getRecord($expense_id);

        // Get related invoice details
        //$invoice_details = $this->app->components->invoice->get_invoice_details($expense_details['invoice_id']);

        // Change the expense status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($expense_id, 'cancelled');      

        /*Create a Workorder History Note  
        $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');*/

        // Log activity        
        $record = _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

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

    public function deleteRecord($expense_id) {

        /* Get invoice_id before deleting the record
        $invoice_id = $this->get_expense_details($expense_id, 'invoice_id');

        // Get related invoice details before deleting the record
        $invoice_details = $this->app->components->invoice->get_invoice_details($invoice_id);*/

        // Change the expense status to deleted (I do this here to maintain consistency)
        $this->updateStatus($expense_id, 'deleted');  

        $sql = "UPDATE ".PRFX."expense_records SET
                employee_id         = NULL,
                payee               = '',           
                date                = NULL, 
                tax_system          = '',  
                type                = '',
                unit_net            = 0.00,                
                unit_tax            = 0.00,
                unit_gross          = 0.00,
                balance             = 0.00,
                status              = 'deleted', 
                opened_on           = NULL,
                closed_on           = NULL,
                last_active         = NULL,
                items               = '',
                note                = ''
                WHERE expense_id    =". $this->app->db->qStr($expense_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Create a Workorder History Note  
        $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.');*/

        // Log activity        
        $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("deleted.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        /* Log activity        
        $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("deleted.");
        $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update last active record
        $this->app->components->client->update_client_last_active($invoice_details['client_id']);
        $this->app->components->workorder->update_workorder_last_active($invoice_details['workorder_id']);
        //$this->app->components->invoice->update_invoice_last_active($invoice_id);*/

        return true;        

    }
    
    
    /** Check Functions **/

    ##########################################################
    #  Check if the expense status is allowed to be changed  #  // not currently used
    ##########################################################

     public function checkRecordAllowsChange($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

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

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments."));
            $state_flag = false;        
        }*/

        return $state_flag;    

     }

    ###############################################################
    #   Check to see if the expense can be refunded (by status)   #  // not currently used - i DONT think i will use this
    ###############################################################

    public function checkRecordAllowsRefund($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

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

        /* Has no payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if(!$this->app->components->report->countPayments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be refunded because the expense has no payments."));
            $state_flag = false;        
        }*/

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the expense can be cancelled              #  // Do I actuallu use this, the code seems to be implemented
    ###############################################################

    public function checkRecordAllowsCancel($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

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

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be cancelled because the expense has payments."));
            $state_flag = false;        
        }*/

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the expense can be deleted                #
    ###############################################################

    public function checkRecordAllowsDelete($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

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

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments."));
            $state_flag = false;        
        }*/

        return $state_flag;

    }

    ##########################################################
    #  Check if the expense status allows editing            #       
    ##########################################################

     public function checkRecordAllowsEdit($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

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

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'expense', null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments."));
            $state_flag = false;        
        }*/

        return $state_flag;    

    }    

    /** Other Functions **/

    #####################################
    #   Recalculate Expense Totals      #
    #####################################

    public function recalculateTotals($expense_id) {

        $expense_details            = $this->getRecord($expense_id);    
        $unit_gross                 = $expense_details['unit_gross'];   
        $payments_subtotal         = $this->app->components->report->sumPayments(null, null, 'date', null, 'valid', 'expense', null, null, null, null, null, $expense_id);
        $balance                    = $unit_gross - $payments_subtotal;

        $sql = "UPDATE ".PRFX."expense_records SET
                balance             =". $this->app->db->qStr( $balance    )."
                WHERE expense_id    =". $this->app->db->qStr( $expense_id );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status - only change if there is a change in status */        

        // Balance = Gross Amount (i.e no payments)
        if($unit_gross > 0 && $unit_gross == $balance && $expense_details['status'] != 'unpaid') {
            $this->updateStatus($expense_id, 'unpaid');
        }

        // Balance < Gross Amount (i.e some payments)
        elseif($unit_gross > 0 && $payments_subtotal > 0 && $payments_subtotal < $unit_gross && $expense_details['status'] != 'partially_paid') {            
            $this->updateStatus($expense_id, 'partially_paid');
        }

        // Balance = 0.00 (i.e has payments and is all paid)
        elseif($unit_gross > 0 && $unit_gross == $payments_subtotal && $expense_details['status'] != 'paid') {            
            $this->updateStatus($expense_id, 'paid');
        }        

        return;        

    }

}