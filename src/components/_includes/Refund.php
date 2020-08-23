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

class Refund extends Components {
    
        /** Insert Functions **/

    ##########################################
    #      Insert Refund                     #
    ##########################################

    public function insertRecord($qform) {

        $sql = "INSERT INTO ".PRFX."refund_records SET
                employee_id      =". $this->app->db->qstr( $this->app->user->login_user_id ).",
                client_id        =". $this->app->db->qstr( $qform['client_id']               ).",
                workorder_id     =". $this->app->db->qstr( $qform['workorder_id']            ).",
                invoice_id       =". $this->app->db->qstr( $qform['invoice_id']              ).",                        
                date             =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($qform['date'])).",
                tax_system       =". $this->app->db->qstr( $qform['tax_system']              ).",
                item        =". $this->app->db->qstr( $qform['item']               ).",             
                unit_net         =". $this->app->db->qstr( $qform['unit_net']                ).", 
                vat_tax_code     =". $this->app->db->qstr( $qform['vat_tax_code']            ).", 
                unit_tax_rate    =". $this->app->db->qstr( $qform['unit_tax_rate']           ).",
                unit_tax         =". $this->app->db->qstr( $qform['unit_tax']                ).",
                unit_gross       =". $this->app->db->qstr( $qform['unit_gross']              ).",
                balance          =". $this->app->db->qstr( $qform['unit_gross']              ).",
                status           =". $this->app->db->qstr( 'unpaid'                        ).",   
                opened_on        =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()                ).",                        
                note             =". $this->app->db->qstr( $qform['note']                    );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $refund_id = $this->app->db->Insert_ID();

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($qform['workorder_id'], _gettext("Refund").' '.$refund_id.' '._gettext("added").' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Refund Record").' '.$refund_id.' '._gettext("created.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $qform['client_id'], $qform['workorder_id'], $qform['invoice_id']);

        // Update last active record    
        $this->app->components->client->updateLastActive($qform['client_id']);
        $this->app->components->workorder->updateLastActive($qform['workorder_id']);
        $this->app->components->invoice->updateLastActive($qform['invoice_id']);

        return $refund_id; 

    }

    /** Get Functions **/

    #############################
    #     Display refunds       #
    #############################

    public function getRecords($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $item = null, $status = null, $employee_id = null, $client_id = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';
        $search_category = $search_category ?: 'refund_id';    

        /* Records Search */    

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."refund_records.refund_id\n";
        $havingTheseRecords = '';

        // Restrict results by search category (client) and search term
        if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Restrict results by search category (employee) and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."refund_records.$search_category LIKE ".$this->app->db->qstr('%'.$search_term.'%');} 

        /* Filter the Records */  

        // Restrict by Type
        if($item) { $whereTheseRecords .= " AND ".PRFX."refund_records.item= ".$this->app->db->qstr($item);}

        // Restrict by Status
        if($status) {$whereTheseRecords .= " AND ".PRFX."refund_records.status= ".$this->app->db->qstr($status);}

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."refund_records.employee_id=".$this->app->db->qstr($employee_id);}        

        // Restrict by Client
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."refund_records.client_id=".$this->app->db->qstr($client_id);}

        /* The SQL code */

        $sql =  "SELECT
                ".PRFX."refund_records.*,

                IF(company_name !='', company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name

                FROM ".PRFX."refund_records

                LEFT JOIN ".PRFX."client_records ON ".PRFX."refund_records.client_id = ".PRFX."client_records.client_id  

                ".$whereTheseRecords."            
                GROUP BY ".PRFX."refund_records.".$order_by."
                ".$havingTheseRecords."
                ORDER BY ".PRFX."refund_records.".$order_by."
                ".$direction;           

        /* Restrict by pages */

        if($use_pages) {

            // Get Start Record
            $start_record = (($page_no * $records_per_page) - $records_per_page);

            // Figure out the total number of records in the database for the given search        
            if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}      
            $total_results = $rs->RecordCount();            
            $this->app->smarty->assign('total_results', $total_results);    

            // Figure out the total number of pages. Always round up using ceil()
            $total_pages = ceil($total_results / $records_per_page);
            $this->app->smarty->assign('total_pages', $total_pages);

            // Set the page number
            $this->app->smarty->assign('page_no', $page_no);

            // Assign the Previous page        
            $previous_page_no = ($page_no - 1);        
            $this->app->smarty->assign('previous_page_no', $previous_page_no);          

            // Assign the next page        
            if($page_no == $total_pages) {$next_page_no = 0;}
            elseif($page_no < $total_pages) {$next_page_no = ($page_no + 1);}
            else {$next_page_no = $total_pages;}
            $this->app->smarty->assign('next_page_no', $next_page_no);

            // Only return the given page's records
            $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;

            // add the restriction on to the SQL
            $sql .= $limitTheseRecords;

        } else {

            // This make the drop down menu look correct
            $this->app->smarty->assign('total_pages', 1);

        }

        /* Return the records */

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $records = $rs->GetArray();   // do i need to add the check empty

        if(empty($records)){

            return false;

        } else {

            return $records;

        }   

    }


    ##########################
    #   Get refund details   #
    ##########################

    public function getRecord($refund_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."refund_records WHERE refund_id=".$this->app->db->qstr($refund_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc();            

        } else {

            return $rs->fields[$item];   

        }    

    }

    #####################################
    #    Get Refund Statuses            #
    #####################################

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."refund_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();    

    }

    ######################################
    #  Get Refund status display name    #
    ######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."refund_statuses WHERE status_key=".$this->app->db->qstr($status_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];
        
    }

    #####################################
    #    Get Refund Types               #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."refund_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }
    
    ##########################################
    #      Last Record Look Up               #  // not currently used
    ##########################################

    public function getLastRecordId() {

        $sql = "SELECT * FROM ".PRFX."refund_records ORDER BY refund_id DESC LIMIT 1";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['refund_id'];

    }    

    /** Update Functions **/

    #####################################
    #     Update refund                 #
    #####################################

    public function updateRecord($qform) {

        $sql = "UPDATE ".PRFX."refund_records SET
                employee_id      =". $this->app->db->qstr( $this->app->user->login_user_id ).",
                date             =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($qform['date'])   ).",            
                last_active      =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()                   ).",
                note             =". $this->app->db->qstr( $qform['note']                       )."
                WHERE refund_id  =". $this->app->db->qstr( $qform['refund_id']                  );                        

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $refund_details = $this->getRecord($qform['refund_id']);

        // Get related workorder_id
        $workorder_id = $this->app->components->invoice->getRecord($refund_details['invoice_id'], 'workorder_id');

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($workorder_id, _gettext("Refund").' '.$qform['refund_id'].' '._gettext("updated").' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Refund Record").' '.$qform['refund_id'].' '._gettext("updated.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $refund_details['client_id'], $workorder_id, $refund_details['invoice_id']);

        // Update last active record  
        $this->app->components->client->updateLastActive($refund_details['client_id']);
        $this->app->components->workorder->updateLastActive($workorder_id);
        $this->app->components->invoice->updateLastActive($refund_details['invoice_id']);

        return true;

    } 

    ############################
    # Update Refund Status     #
    ############################

    public function updateStatus($refund_id, $new_status, $silent = false) {

        // Get refund details
        $refund_details = $this->getRecord($refund_id);

        // if the new status is the same as the current one, exit
        if($new_status == $refund_details['status']) {        
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }    

        // Unify Dates and Times
        $datetime = $this->app->system->general->mysqlDatetime();

        // Set the appropriate closed_on date
        $closed_on = ($new_status == 'paid') ? $datetime : '0000-00-00 00:00:00';

        $sql = "UPDATE ".PRFX."refund_records SET
                status             =". $this->app->db->qstr( $new_status   ).",
                closed_on          =". $this->app->db->qstr( $closed_on    ).",
                last_active        =". $this->app->db->qstr( $datetime     )." 
                WHERE refund_id    =". $this->app->db->qstr( $refund_id    );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}   

        // Get related workorder_id
        $workorder_id = $this->app->components->invoice->getRecord($refund_details['invoice_id'], 'workorder_id');

        // Status updated message
        if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("Refund status updated.")); }

        // For writing message to log file, get refund status display name
        $refund_status_display_name = _gettext($this->getStatusDisplayName($new_status));

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($workorder_id, _gettext("Refund Status updated to").' '.$refund_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Refund").' '.$refund_id.' '._gettext("Status updated to").' '.$refund_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $refund_details['client_id'], $workorder_id, $refund_details['invoice_id']);

        // Update last active record - // not used, the current user is updated elsewhere  
        $this->app->components->client->updateLastActive($refund_details['client_id']);
        $this->app->components->workorder->updateLastActive($workorder_id);
        $this->app->components->invoice->updateLastActive($refund_details['invoice_id']);              

        return true;

    }

    /** Close Functions **/

    #####################################
    #   Cancel Refund                   #
    #####################################

    public function cancelRecord($refund_id) {

        // Make sure the refund can be cancelled
        if(!$this->checkRecordAllowsCancel($refund_id)) {        
            return false;
        }

        // Get refund details
        $refund_details = $this->getRecord($refund_id);

        // Get related workorder_id
        $workorder_id = $this->app->components->invoice->getRecord($refund_details['invoice_id'], 'workorder_id');

        // Change the refund status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($refund_id, 'cancelled');

        // Revert invoice status back to paid
        $this->app->components->invoice->updateStatus($refund_details['invoice_id'], 'paid');

        // Remove the refund ID from the invoice
        $this->app->components->invoice->updateRefundId($refund_details['invoice_id'], '');

        // Revert attached vouchers status back to paid
        $this->app->components->voucher->revertRefundedInvoiceVouchers($refund_details['invoice_id']);

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($workorder_id, _gettext("Refund").' '.$refund_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Refund").' '.$refund_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $refund_details['client_id'], $workorder_id, $refund_details['invoice_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($refund_details['client_id']);
        $this->app->components->workorder->updateLastActive($workorder_id);
        $this->app->components->invoice->updateLastActive($refund_details['invoice_id']);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Record                  #
    #####################################

    public function deleteRecord($refund_id) {

        // Make sure the invoice can be deleted (does not harm to check again here, other check is on status button)
        if(!$this->checkRecordAllowsDelete($refund_id)) {        
            return false;
        }

        // Get record before deleting the record
        $refund_details = $this->getRecord($refund_id);

        // Change the refund status to deleted (I do this here to maintain consistency)
        $this->updateStatus($refund_id, 'deleted');  

        // Revert invoice status back to paid
        $this->app->components->invoice->updateStatus($refund_details['invoice_id'], 'paid');

        // Remove the refund ID from the invoice
        $this->app->components->invoice->updateRefundId($refund_details['invoice_id'], '');

        // Revert attached vouchers status back to paid
        $this->app->components->voucher->revertRefundedInvoiceVouchers($refund_details['invoice_id']);

        $sql = "UPDATE ".PRFX."refund_records SET
                employee_id         = '',
                client_id           = '',
                workorder_id        = '',
                invoice_id          = '',
                date                = '0000-00-00', 
                tax_system          = '',  
                item           = '',             
                unit_net            = '',
                vat_tax_code        = '',
                unit_tax_rate       = '0.00',
                unit_tax            = '0.00',
                unit_gross          = '0.00',
                balance             = '0.00',
                status              = 'deleted', 
                last_active         = '0000-00-00 00:00:00',
                opened_on           = '0000-00-00 00:00:00',
                closed_on           = '0000-00-00 00:00:00',
                note                = ''
                WHERE refund_id    =". $this->app->db->qstr($refund_details['refund_id']);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Get related workorder_id
        $workorder_id = $this->app->components->invoice->getRecord($refund_details['invoice_id'], 'workorder_id');

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($workorder_id, _gettext("Expense").' '.$refund_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Refund Record").' '.$refund_id.' '._gettext("deleted.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $refund_details['client_id'], $workorder_id, $refund_details['invoice_id']);

        // Update last active record    
        $this->app->components->client->updateLastActive($refund_details['client_id']);
        $this->app->components->workorder->updateLastActive($workorder_id);
        $this->app->components->invoice->updateLastActive($refund_details['invoice_id']);

        return true;  

    }
    
    /** Check Functions **/
    
    ##########################################################
    #  Check if the refund status is allowed to be changed   #  // not currently used (from refund:status), manual change
    ##########################################################

     public function checkRecordAllowsChange($refund_id) {

        $state_flag = true;

        // Get the refund details
        $refund_details = $this->getRecord($refund_id);

        // Is unpaid
        if($refund_details['status'] == 'unpaid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund status cannot be changed because the refund is unpaid."));
            $state_flag = false;       
        }

        // Is partially paid
        if($refund_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund status cannot be changed because the refund has payments and is partially paid."));
            $state_flag = false;       
        }

        // Is paid
        if($refund_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund status cannot be changed because the refund has payments and is paid."));
            $state_flag = false;       
        }

        // Is Cancelled
        if($refund_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund status cannot be changed because the refund has been cancelled."));
            $state_flag = false;       
        }

        // Is deleted
        if($refund_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund status cannot be changed because the refund has been deleted."));
            $state_flag = false;       
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'refund', null, null, null, null, $refund_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund status cannot be changed because the refund has payments."));
            $state_flag = false;       
        }

        // Does the invoice have any Vouchers preventing refunding the invoice (i.e. any that have been used)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceRefund($refund_details ['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because of Vouchers on it prevent this."));
            $state_flag = false;
        }

        return $state_flag;    

     }

    ###############################################################
    #   Check to see if the refund can be cancelled               #
    ###############################################################

    public function checkRecordAllowsCancel($refund_id) {

        $state_flag = true;

        // Get the refund details
        $refund_details = $this->getRecord($refund_id);

        // Is partially paid (not used yet)
        if($refund_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be cancelled because the refund is partially paid."));
            return false;
        }

        // Is cancelled
        if($refund_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund cannot be cancelled because the refund has already been cancelled."));
            $state_flag = false;       
        }

        // Is deleted
        if($refund_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund cannot be cancelled because the refund has been deleted."));
            $state_flag = false;       
        }    

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'refund', null, null, null, null, $refund_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be cancelled because the refund has payments."));
            $state_flag = false;       
        }

        // Does the invoice have any Vouchers preventing cancelling the invoice (i.e. any that have been used)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceRefundCancel($refund_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because of Vouchers on it prevent this."));
            $state_flag = false;
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the refund can be deleted                 #
    ###############################################################

    public function checkRecordAllowsDelete($refund_id) {

        $state_flag = true;

        // Get the refund details
        $refund_details = $this->getRecord($refund_id);

        // Is partially paid (not used yet)
        if($refund_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be deleted because it has payments and is partially paid."));
            $state_flag = false;       
        }     

        // Is paid
        if($refund_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be deleted because it has payments and is paid."));
            $state_flag = false;       
        }

        // Is cancelled
        if($refund_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be deleted because it has been cancelled."));
            $state_flag = false;       
        }

        // Is deleted
        if($refund_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be deleted because it already been deleted."));
            $state_flag = false;       
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'refund', null, null, null, null, $refund_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be deleted because it has payments."));
            $state_flag = false;       
        }

        // Does the invoice status allow it to have its refund deleted (including vouchers)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceRefundDelete($refund_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because of Vouchers on it prevent this."));
            $state_flag = false;
        } 

        return $state_flag;

    }

    ##########################################################
    #  Check if the refund status allows editing             #       
    ##########################################################

     public function checkRecordAllowsEdit($refund_id) {

        $state_flag = true;

        // Get the refund details
        $refund_details = $this->getRecord($refund_id);

        // Is on a different tax system
        if($refund_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund cannot be edited because it is on a different Tax system."));
            $state_flag = false;       
        }

        /* Is unpaid
        if($refund_details['status'] == 'unpaid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be edited because it has payments and is partially paid."));
            $state_flag = false;       
        }*/

        // Is partially paid
        if($refund_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be edited because it has payments and is partially paid."));
            $state_flag = false;       
        }

        // Is paid
        if($refund_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be edited because it has payments and is paid."));
            $state_flag = false;       
        }

        // Is cancelled
        if($refund_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be edited because it already been cancelled."));
            $state_flag = false;       
        }

        // Is deleted
        if($refund_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund cannot be edited because it has been deleted."));
            $state_flag = false;       
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'refund', null, null, null, null, $refund_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be edited because it has payments."));
            $state_flag = false;       
        }

        // Does the invoice have any Vouchers preventing refunding the invoice (i.e. any that have been used)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceRefund($refund_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because of Vouchers on it prevent this."));
            $state_flag = false;
        }

        // The current record VAT code is enabled
        if(!$this->app->components->company->getVatTaxCodeStatus($refund_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This refund cannot be edited because it's current VAT Tax Code is not enabled."));
            $state_flag = false;
        }

        return $state_flag;

    }    

    /** Other Functions **/

    #####################################
    #   Recalculate Refund Totals       #
    #####################################

    public function recalculateTotals($refund_id) {

        $refund_details             = $this->getRecord($refund_id);    

        $unit_gross                 = $refund_details['unit_gross'];   
        $payments_subtotal         = $this->app->components->report->sumPayments(null, null, 'date', null, 'valid', 'refund', null, null, null, null, $refund_id);
        $balance                    = $unit_gross - $payments_subtotal;

        $sql = "UPDATE ".PRFX."refund_records SET
                balance             =". $this->app->db->qstr( $balance   )."
                WHERE refund_id     =". $this->app->db->qstr( $refund_id );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status - only change if there is a change in status */        

        // Balance = Gross Amount (i.e no payments)
        if($unit_gross > 0 && $unit_gross == $balance && $refund_details['status'] != 'unpaid') {
            $this->updateStatus($refund_id, 'unpaid');
        }

        // Balance < Gross Amount (i.e some payments)
        elseif($unit_gross > 0 && $payments_subtotal > 0 && $payments_subtotal < $unit_gross && $refund_details['status'] != 'partially_paid') {            
            $this->updateStatus($refund_id, 'partially_paid');
        }

        // Balance = 0.00 (i.e has payments and is all paid)
        elseif($unit_gross > 0 && $unit_gross == $payments_subtotal && $refund_details['status'] != 'paid') {            
            $this->updateStatus($refund_id, 'paid');
        }        

        return;     

    }

    
}