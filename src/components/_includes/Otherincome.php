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

class OtherIncome extends Components {



    /** Insert Functions **/

    ##########################################
    #      Insert Otherincome                #
    ##########################################

    public function insertRecord($qform) {

        $sql = "INSERT INTO ".PRFX."otherincome_records SET
                employee_id      =". $this->app->db->qstr( $this->app->user->login_user_id ).",
                payee            =". $this->app->db->qstr( $qform['payee']                   ).",
                date             =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($qform['date'])).",
                tax_system       =". $this->app->db->qstr( QW_TAX_SYSTEM                     ).",            
                type             =". $this->app->db->qstr( $qform['type']                    ).",            
                unit_net         =". $this->app->db->qstr( $qform['unit_net']                ).",
                vat_tax_code     =". $this->app->db->qstr( $qform['vat_tax_code']            ).",
                unit_tax_rate    =". $this->app->db->qstr( $qform['unit_tax_rate']           ).",
                unit_tax         =". $this->app->db->qstr( $qform['unit_tax']                ).",
                unit_gross       =". $this->app->db->qstr( $qform['unit_gross']              ).",
                status           =". $this->app->db->qstr( 'unpaid'                        ).",            
                opened_on        =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()                ).",            
                items            =". $this->app->db->qstr( $qform['items']                   ).",
                note             =". $this->app->db->qstr( $qform['note']                    );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity        
        $record = _gettext("Otherincome Record").' '.$this->app->db->Insert_ID().' '._gettext("created.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        return $this->app->db->Insert_ID();        

    }

     /** Get Functions **/

    ###############################
    #  Display otherincomes       #
    ###############################

    public function getRecords($order_by, $direction, $records_per_page = null, $use_pages = false, $page_no = null, $search_category = 'otherincome_id', $search_term = null, $type = null, $status = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;    

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";

        // Restrict results by search category and search term
        if($search_term) {$whereTheseRecords .= " AND ".PRFX."otherincome_records.$search_category LIKE ".$this->app->db->qstr('%'.$search_term.'%');} 

        // Restrict by Type
        if($type) { $whereTheseRecords .= " AND ".PRFX."otherincome_records.type= ".$this->app->db->qstr($type);}

        // Restrict by status
        if($status) {$whereTheseRecords .= " AND ".PRFX."otherincome_records.status= ".$this->app->db->qstr($status);} 

        // The SQL code
        $sql =  "SELECT * 
                FROM ".PRFX."otherincome_records                                                   
                ".$whereTheseRecords."            
                GROUP BY ".PRFX."otherincome_records.".$order_by."
                ORDER BY ".PRFX."otherincome_records.".$order_by."
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
   

    ###############################
    #   Get otherincome details   #
    ###############################

    public function getRecord($otherincome_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."otherincome_records WHERE otherincome_id=".$this->app->db->qstr($otherincome_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc();            

        } else {

            return $rs->fields[$item];   

        } 

    }

    #####################################
    #    Get Otherincome Statuses       #
    #####################################

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."otherincome_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();        

    }

    ##########################################
    #  Get Otherincome status display name   #
    ##########################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."otherincome_statuses WHERE status_key=".$this->app->db->qstr($status_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];            

    }

    #####################################
    #    Get Otherincome Types          #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."otherincome_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }
    

    ##########################################
    #      Last Record Look Up               #  // not currently used
    ##########################################

    public function getLastRecordId() {

        $sql = "SELECT * FROM ".PRFX."otherincome_records ORDER BY otherincome_id DESC LIMIT 1";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['otherincome_id'];

    }
        

    /** Update Functions **/

    #####################################
    #     Update otherincome            #
    #####################################

    public function updateRecord($qform) {

        $sql = "UPDATE ".PRFX."otherincome_records SET
                employee_id      =". $this->app->db->qstr( $this->app->user->login_user_id ).",
                payee            =". $this->app->db->qstr( $qform['payee']                   ).",
                date             =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($qform['date'])).",            
                type             =". $this->app->db->qstr( $qform['type']               ).",            
                unit_net         =". $this->app->db->qstr( $qform['unit_net']                ).",
                vat_tax_code     =". $this->app->db->qstr( $qform['vat_tax_code']            ).",
                unit_tax_rate    =". $this->app->db->qstr( $qform['unit_tax_rate']           ).",
                unit_tax         =". $this->app->db->qstr( $qform['unit_tax']                ).",
                unit_gross       =". $this->app->db->qstr( $qform['unit_gross']              ).",
                last_active      =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()                ).",
                items            =". $this->app->db->qstr( $qform['items']                   ).",
                note             =". $this->app->db->qstr( $qform['note']                    )."
                WHERE otherincome_id  =". $this->app->db->qstr( $qform['otherincome_id']     );                        

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity        
        $record = _gettext("Otherincome Record").' '.$qform['otherincome_id'].' '._gettext("updated.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        return true;       

    } 

    #############################
    # Update Otherincome Status #
    #############################

    public function updateStatus($otherincome_id, $new_status, $silent = false) {

        // Get otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // if the new status is the same as the current one, exit
        if($new_status == $otherincome_details['status']) {        
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }    

        // Unify Dates and Times
        $datetime = $this->app->system->general->mysqlDatetime();

        // Set the appropriate closed_on date
        $closed_on = ($new_status == 'paid') ? $datetime : '0000-00-00 00:00:00';

        $sql = "UPDATE ".PRFX."otherincome_records SET
                status             =". $this->app->db->qstr( $new_status   ).",
                closed_on          =". $this->app->db->qstr( $closed_on    ).",
                last_active        =". $this->app->db->qstr( $datetime     )." 
                WHERE otherincome_id =". $this->app->db->qstr( $otherincome_id );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}   

        // Status updated message
        if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("otherincome status updated.")); }

        // For writing message to log file, get otherincome status display name
        $otherincome_status_display_name = _gettext($this->getStatusDisplayName($new_status));

        // Log activity        
        $record = _gettext("Otherincome").' '.$otherincome_id.' '._gettext("Status updated to").' '.$otherincome_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        return true; 

    }

    /** Close Functions **/

    #####################################
    #   Cancel Otherincome              #
    #####################################

    public function cancelRecord($otherincome_id) {

        // Make sure the otherincome can be cancelled
        if(!$this->checkRecordAllowsCancel($otherincome_id)) {        
            return false;
        }

        // Change the otherincome status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($otherincome_id, 'cancelled');      

        // Log activity        
        $record = _gettext("Otherincome").' '.$otherincome_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Record                  #
    #####################################

    public function deleteRecord($otherincome_id) {

        // Change the otherincome status to deleted (I do this here to maintain consistency)
        $this->updateStatus($otherincome_id, 'deleted'); 

        $sql = "UPDATE ".PRFX."otherincome_records SET
            employee_id         = NULL,
            payee               = '',           
            date                = NULL, 
            tax_system          = '',  
            type                = '',        
            unit_net            = 0.00,
            vat_tax_code        = '',
            unit_tax_rate       = 0.00,
            unit_tax            = 0.00,
            unit_gross          = 0.00,
            balance             = 0.00,
            status              = 'deleted', 
            opened_on           = NULL,
            closed_on           = NULL,
            last_active         = NULL,
            items               = '',
            note                = ''
            WHERE otherincome_id =". $this->app->db->qstr($otherincome_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity        
        $record = _gettext("Otherincome Record").' '.$otherincome_id.' '._gettext("deleted.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        return true;        

    }
    
    
    /** Check Functions **/

    ##############################################################
    #  Check if the otherincome status is allowed to be changed  #  // not currently used
    ##############################################################

     public function checkRecordAllowsChange($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // Is partially paid
        if($otherincome_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome status cannot be changed because the otherincome has payments and is partially paid."));
            $state_flag = false;        
        }

        // Is paid
        if($otherincome_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome status cannot be changed because the otherincome has payments and is paid."));
            $state_flag = false;        
        }

        // Is deleted
        if($otherincome_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome status cannot be changed because the otherincome has been deleted."));
            $state_flag = false;        
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome status cannot be changed because the otherincome has payments."));
            $state_flag = false;        
        }

        return $state_flag;    

     }

    ###################################################################
    #   Check to see if the otherincome can be refunded (by status)   #  // not currently used - i DONT think i will use this
    ###################################################################

    public function checkRecordAllowsRefund($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // Is partially paid
        if($otherincome_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be refunded because the otherincome is partially paid."));
            $state_flag = false;
        }

        // Is refunded
        if($otherincome_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot be refunded because the otherincome has already been refunded."));
            $state_flag = false;        
        }

        // Is cancelled
        if($otherincome_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot be refunded because the otherincome has been cancelled."));
            $state_flag = false;        
        }

        // Is deleted
        if($otherincome_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot be refunded because the otherincome has been deleted."));
            $state_flag = false;        
        }    

        // Has no payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if(!$this->app->components->report->countPayments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be refunded because the otherincome has no payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the otherincome can be cancelled          #
    ###############################################################

    public function checkRecordAllowsCancel($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // Is partially paid
        if($otherincome_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be cancelled because the otherincome is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($otherincome_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is paid."));
            $state_flag = false;        
        }

        // Is cancelled
        if($otherincome_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot be cancelled because the otherincome has already been cancelled."));
            $state_flag = false;        
        }

        // Is deleted
        if($otherincome_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot be cancelled because the otherincome has been deleted."));
            $state_flag = false;        
        }    

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be cancelled because the otherincome has payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the otherincome can be deleted            #
    ###############################################################

    public function checkRecordAllowsDelete($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // Is partially paid
        if($otherincome_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be deleted because it has payments and is partially paid."));
            $state_flag = false;        
        }

        // Is paid
        if($otherincome_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be deleted because it has payments and is paid."));
            $state_flag = false;        
        }

        // Is cancelled
        if($otherincome_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be deleted because it has been cancelled."));
            $state_flag = false;        
        }

        // Is deleted
        if($otherincome_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be deleted because it already been deleted."));
            $state_flag = false;        
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be deleted because it has payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ##########################################################
    #  Check if the otherincome status allows editing        #       
    ##########################################################

     public function checkRecordAllowsEdit($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // Is on a different tax system
        if($otherincome_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot be edited because it is on a different Tax system."));
            $state_flag = false;        
        }

        // Is partially paid
        if($otherincome_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it has payments and is partially paid."));
            $state_flag = false;        
        }

        // Is paid
        if($otherincome_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it has payments and is paid."));
            $state_flag = false;        
        }

        // Is cancelled
        if($otherincome_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it already been deleted."));
            $state_flag = false;        
        }

        // Is deleted
        if($otherincome_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot be edited because it has been deleted."));
            $state_flag = false;        
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it has payments."));
            $state_flag = false;        
        }

        // The current record VAT code is enabled
        if(!$this->app->components->company->getVatTaxCodeStatus($otherincome_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it's current VAT Tax Code is not enabled."));
            $state_flag = false; 
        }

        return $state_flag;   

    }
       

    /** Other Functions **/

    ##########################################
    #    Recalculate Other income totals     #
    ##########################################

    public function recalculateTotals($otherincome_id) {

        $otherincome_details            = $this->getRecord($otherincome_id);    

        $unit_gross                     = $otherincome_details['unit_gross'];   
        $payments_subtotal             = $this->app->components->report->sumPayments(null, null, 'date', null, 'valid', 'otherincome', null, null, null, null, null, null, $otherincome_id);
        $balance                        = $unit_gross - $payments_subtotal;

        $sql = "UPDATE ".PRFX."otherincome_records SET
                balance                 =". $this->app->db->qstr( $balance        )."
                WHERE otherincome_id    =". $this->app->db->qstr( $otherincome_id );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status - only change if there is a change in status */        

        // Balance = Gross Amount (i.e no payments)
        if($unit_gross > 0 && $unit_gross == $balance && $otherincome_details['status'] != 'unpaid') {
            $this->updateStatus($otherincome_id, 'unpaid');
        }

        // Balance < Gross Amount (i.e some payments)
        elseif($unit_gross > 0 && $payments_subtotal > 0 && $payments_subtotal < $unit_gross && $otherincome_details['status'] != 'partially_paid') {            
            $this->updateStatus($otherincome_id, 'partially_paid');
        }

        // Balance = 0.00 (i.e has payments and is all paid)
        elseif($unit_gross > 0 && $unit_gross == $payments_subtotal && $otherincome_details['status'] != 'paid') {            
            $this->updateStatus($otherincome_id, 'paid');
        }        

        return;   

    }

}