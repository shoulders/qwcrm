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


    /** Mandatory Code **/

    /** Display Functions **/

    ###############################
    #  Display otherincomes       #
    ###############################

    public function display_otherincomes($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $item_type = null, $status = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';
        $search_category = $search_category ?: 'otherincome_id';    

        /* Records Search */    

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";

        // Restrict results by search category and search term
        if($search_term) {$whereTheseRecords .= " AND ".PRFX."otherincome_records.$search_category LIKE ".$this->db->qstr('%'.$search_term.'%');} 

        /* Filter the Records */  

        // Restrict by Type
        if($item_type) { $whereTheseRecords .= " AND ".PRFX."otherincome_records.item_type= ".$this->db->qstr($item_type);}

        // Restrict by status
        if($status) {$whereTheseRecords .= " AND ".PRFX."otherincome_records.status= ".$this->db->qstr($status);} 

        /* The SQL code */

        $sql =  "SELECT * 
                FROM ".PRFX."otherincome_records                                                   
                ".$whereTheseRecords."            
                GROUP BY ".PRFX."otherincome_records.".$order_by."
                ORDER BY ".PRFX."otherincome_records.".$order_by."
                ".$direction;           

        /* Restrict by pages */

        if($use_pages) {

            // Get Start Record
            $start_record = (($page_no * $records_per_page) - $records_per_page);

            // Figure out the total number of records in the database for the given search        
            if(!$rs = $this->db->Execute($sql)) {
                $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to count the matching otherincome records."));
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
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to return the matching otherincome records."));
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
    #      Insert Otherincome                #
    ##########################################

    public function insert_otherincome($qform) {

        $sql = "INSERT INTO ".PRFX."otherincome_records SET
                employee_id      =". $this->db->qstr( $this->app->user->login_user_id ).",
                payee            =". $this->db->qstr( $qform['payee']                   ).",
                date             =". $this->db->qstr( $this->app->system->general->date_to_mysql_date($qform['date'])).",
                tax_system       =". $this->db->qstr( QW_TAX_SYSTEM                   ).",            
                item_type        =". $this->db->qstr( $qform['item_type']               ).",            
                unit_net         =". $this->db->qstr( $qform['unit_net']                ).",
                vat_tax_code     =". $this->db->qstr( $qform['vat_tax_code']            ).",
                unit_tax_rate    =". $this->db->qstr( $qform['unit_tax_rate']           ).",
                unit_tax         =". $this->db->qstr( $qform['unit_tax']                ).",
                unit_gross       =". $this->db->qstr( $qform['unit_gross']              ).",
                status           =". $this->db->qstr( 'unpaid'                        ).",            
                opened_on        =". $this->db->qstr( $this->app->system->general->mysql_datetime()                ).",            
                items            =". $this->db->qstr( $qform['items']                   ).",
                note             =". $this->db->qstr( $qform['note']                    );

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to insert the otherincome record into the database."));
        } else {

            // Log activity        
            $record = _gettext("Otherincome Record").' '.$this->db->Insert_ID().' '._gettext("created.");
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id);

            return $this->db->Insert_ID();

        } 

    }

    /** Get Functions **/

    ###############################
    #   Get otherincome details   #
    ###############################

    public function get_otherincome_details($otherincome_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."otherincome_records WHERE otherincome_id=".$this->db->qstr($otherincome_id);

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get the otherincome details."));
        } else {

            if($item === null){

                return $rs->GetRowAssoc();            

            } else {

                return $rs->fields[$item];   

            } 

        }

    }

    #####################################
    #    Get Otherincome Statuses       #
    #####################################

    public function get_otherincome_statuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."otherincome_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get Otherincome statuses."));
        } else {

            return $rs->GetArray();     

        }    

    }

    ##########################################
    #  Get Otherincome status display name   #
    ##########################################

    public function get_otherincome_status_display_name($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."otherincome_statuses WHERE status_key=".$this->db->qstr($status_key);

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get the otherincome status display name."));
        } else {

            return $rs->fields['display_name'];

        }    

    }

    #####################################
    #    Get Otherincome Types          #
    #####################################

    public function get_otherincome_types() {

        $sql = "SELECT * FROM ".PRFX."otherincome_types";

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get otherincome types."));
        } else {

            return $rs->GetArray();

        }    

    }

    /** Update Functions **/

    #####################################
    #     Update otherincome            #
    #####################################

    public function update_otherincome($qform) {

        $sql = "UPDATE ".PRFX."otherincome_records SET
                employee_id      =". $this->db->qstr( $this->app->user->login_user_id ).",
                payee            =". $this->db->qstr( $qform['payee']                   ).",
                date             =". $this->db->qstr( $this->app->system->general->date_to_mysql_date($qform['date'])).",            
                item_type        =". $this->db->qstr( $qform['item_type']               ).",            
                unit_net         =". $this->db->qstr( $qform['unit_net']                ).",
                vat_tax_code     =". $this->db->qstr( $qform['vat_tax_code']            ).",
                unit_tax_rate    =". $this->db->qstr( $qform['unit_tax_rate']           ).",
                unit_tax         =". $this->db->qstr( $qform['unit_tax']                ).",
                unit_gross       =". $this->db->qstr( $qform['unit_gross']              ).",
                last_active      =". $this->db->qstr( $this->app->system->general->mysql_datetime()                ).",
                items            =". $this->db->qstr( $qform['items']                   ).",
                note             =". $this->db->qstr( $qform['note']                    )."
                WHERE otherincome_id  =". $this->db->qstr( $qform['otherincome_id']     );                        

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update the otherincome details."));
        } else {

            // Log activity        
            $record = _gettext("Otherincome Record").' '.$qform['otherincome_id'].' '._gettext("updated.");
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id);

            return true;

        }

    } 

    #############################
    # Update Otherincome Status #
    #############################

    public function update_otherincome_status($otherincome_id, $new_status, $silent = false) {

        // Get otherincome details
        $otherincome_details = $this->get_otherincome_details($otherincome_id);

        // if the new status is the same as the current one, exit
        if($new_status == $otherincome_details['status']) {        
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }    

        // Unify Dates and Times
        $datetime = $this->app->system->general->mysql_datetime();

        // Set the appropriate closed_on date
        $closed_on = ($new_status == 'paid') ? $datetime : '0000-00-00 00:00:00';

        $sql = "UPDATE ".PRFX."otherincome_records SET
                status             =". $this->db->qstr( $new_status   ).",
                closed_on          =". $this->db->qstr( $closed_on    ).",
                last_active        =". $this->db->qstr( $datetime     )." 
                WHERE otherincome_id =". $this->db->qstr( $otherincome_id );

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update an otherincome Status."));

        } else {    

            // Status updated message
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("otherincome status updated.")); }

            // For writing message to log file, get otherincome status display name
            $otherincome_status_display_name = _gettext($this->get_otherincome_status_display_name($new_status));

            // Log activity        
            $record = _gettext("Otherincome").' '.$otherincome_id.' '._gettext("Status updated to").' '.$otherincome_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id);

            return true;

        }

    }

    /** Close Functions **/

    #####################################
    #   Cancel Otherincome              #
    #####################################

    public function cancel_otherincome($otherincome_id) {

        // Make sure the otherincome can be cancelled
        if(!$this->check_otherincome_can_be_cancelled($otherincome_id)) {        
            return false;
        }

        // Change the otherincome status to cancelled (I do this here to maintain consistency)
        $this->update_otherincome_status($otherincome_id, 'cancelled');      

        // Log activity        
        $record = _gettext("Otherincome").' '.$otherincome_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Record                  #
    #####################################

    public function delete_otherincome($otherincome_id) {

        // Change the otherincome status to deleted (I do this here to maintain consistency)
        $this->update_otherincome_status($otherincome_id, 'deleted'); 

        $sql = "UPDATE ".PRFX."otherincome_records SET
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
            WHERE otherincome_id =". $this->db->qstr($otherincome_id);

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to delete the otherincome records."));
        } else {

            // Log activity        
            $record = _gettext("Otherincome Record").' '.$otherincome_id.' '._gettext("deleted.");
            $this->app->system->general->write_record_to_activity_log($record, $this->app->user->login_user_id);

            return true;

        }

    }

    /** Other Functions **/

    ##########################################
    #      Last Record Look Up               #  // not currently used
    ##########################################

    public function last_otherincome_id_lookup() {

        $sql = "SELECT * FROM ".PRFX."otherincome_records ORDER BY otherincome_id DESC LIMIT 1";

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to lookup the last otherincome record ID."));
        } else {

            return $rs->fields['otherincome_id'];

        }

    }
    
    ##########################################
    #    Recalculate Other income totals     #
    ##########################################

    public function recalculate_otherincome_totals($otherincome_id) {

        $otherincome_details            = $this->get_otherincome_details($otherincome_id);    

        $unit_gross                     = $otherincome_details['unit_gross'];   
        $payments_sub_total             = $this->app->components->report->sum_payments(null, null, 'date', null, 'valid', 'otherincome', null, null, null, null, null, null, $otherincome_id);
        $balance                        = $unit_gross - $payments_sub_total;

        $sql = "UPDATE ".PRFX."otherincome_records SET
                balance                 =". $this->db->qstr( $balance        )."
                WHERE otherincome_id    =". $this->db->qstr( $otherincome_id );

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to recalculate the otherincome totals."));
        } else {

            /* Update Status - only change if there is a change in status */        

            // Balance = Gross Amount (i.e no payments)
            if($unit_gross > 0 && $unit_gross == $balance && $otherincome_details['status'] != 'unpaid') {
                $this->update_otherincome_status($otherincome_id, 'unpaid');
            }

            // Balance < Gross Amount (i.e some payments)
            elseif($unit_gross > 0 && $payments_sub_total > 0 && $payments_sub_total < $unit_gross && $otherincome_details['status'] != 'partially_paid') {            
                $this->update_otherincome_status($otherincome_id, 'partially_paid');
            }

            // Balance = 0.00 (i.e has payments and is all paid)
            elseif($unit_gross > 0 && $unit_gross == $payments_sub_total && $otherincome_details['status'] != 'paid') {            
                $this->update_otherincome_status($otherincome_id, 'paid');
            }        

            return;        

        }

    }

    ##############################################################
    #  Check if the otherincome status is allowed to be changed  #  // not currently used
    ##############################################################

     public function check_otherincome_status_can_be_changed($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->get_otherincome_details($otherincome_id);

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
        if($this->app->components->report->count_payments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome status cannot be changed because the otherincome has payments."));
            $state_flag = false;        
        }

        return $state_flag;    

     }

    ###################################################################
    #   Check to see if the otherincome can be refunded (by status)   #  // not currently used - i DONT think i will use this
    ###################################################################

    public function check_otherincome_can_be_refunded($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->get_otherincome_details($otherincome_id);

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
        if(!$this->app->components->report->count_payments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be refunded because the otherincome has no payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the otherincome can be cancelled          #
    ###############################################################

    public function check_otherincome_can_be_cancelled($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->get_otherincome_details($otherincome_id);

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
        if($this->app->components->report->count_payments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be cancelled because the otherincome has payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the otherincome can be deleted            #
    ###############################################################

    public function check_otherincome_can_be_deleted($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->get_otherincome_details($otherincome_id);

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
        if($this->app->components->report->count_payments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be deleted because it has payments."));
            $state_flag = false;        
        }

        return $state_flag;

    }

    ##########################################################
    #  Check if the otherincome status allows editing        #       
    ##########################################################

     public function check_otherincome_can_be_edited($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->get_otherincome_details($otherincome_id);

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
        if($this->app->components->report->count_payments(null, null, 'date', null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it has payments."));
            $state_flag = false;        
        }

        // The current record VAT code is enabled
        if(!$this->app->components->company->get_vat_tax_code_status($otherincome_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it's current VAT Tax Code is not enabled."));
            $state_flag = false; 
        }

        return $state_flag;   

    }
    
}