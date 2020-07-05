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

class Payment extends Components {
    
    // Used for Payment Types and Methods
    public static $action = '';
    public static $buttons = array();
    public static $payment_details = array();
    public static $payment_valid = true;
    public static $payment_processed = false;
    public static $record_balance = null;
    //public static $paymentType = null;      // not currently used here
    //public static $paymentMtehod = null;    // not currently used here


    /** Insert Functions **/

    ############################
    #   Insert Payment         #
    ############################

    public function insertRecord($qpayment) {

        $sql = "INSERT INTO ".PRFX."payment_records SET            
                employee_id     = ".$this->app->db->qstr( $this->app->user->login_user_id       ).",
                client_id       = ".$this->app->db->qstr( $qpayment['client_id']                   ).",
                workorder_id    = ".$this->app->db->qstr( $qpayment['workorder_id']                ).",
                invoice_id      = ".$this->app->db->qstr( $qpayment['invoice_id']                  ).",
                voucher_id      = ".$this->app->db->qstr( $qpayment['voucher_id']                  ).",               
                refund_id       = ".$this->app->db->qstr( $qpayment['refund_id']                   ).", 
                expense_id      = ".$this->app->db->qstr( $qpayment['expense_id']                  ).", 
                otherincome_id  = ".$this->app->db->qstr( $qpayment['otherincome_id']              ).",
                date            = ".$this->app->db->qstr( $this->app->system->general->dateToMysqlDate($qpayment['date'])    ).",
                tax_system      = ".$this->app->db->qstr( QW_TAX_SYSTEM                            ).",   
                type            = ".$this->app->db->qstr( $qpayment['type']                        ).",
                method          = ".$this->app->db->qstr( $qpayment['method']                      ).",
                status          = 'valid',
                amount          = ".$this->app->db->qstr( $qpayment['amount']                      ).",
                last_active     =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()                         ).",
                additional_info = ".$this->app->db->qstr( $qpayment['additional_info']             ).",
                note            = ".$this->app->db->qstr( $qpayment['note']                        );

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to insert payment into the database."));

        } else {

            // Get Payment Record ID
            $payment_id = $this->app->db->Insert_ID();

            // Create a Workorder History Note       
            $this->app->components->workorder->insertHistory($qpayment['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("added by").' '.$this->app->user->login_display_name);

            // Log activity        
            $record = _gettext("Payment").' '.$payment_id.' '._gettext("created.");
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $qpayment['client_id'], $qpayment['workorder_id'], $qpayment['invoice_id']);

            // Update last active record    
            $this->app->components->client->updateLastActive($qpayment['client_id']);
            $this->app->components->workorder->updateLastActive($qpayment['workorder_id']);
            $this->app->components->invoice->updateLastActive($qpayment['invoice_id']);

            // Return the payment_id
            return $payment_id;

        }    

    }

    /** Get Functions **/
    
    #####################################################
    #  Display all payments the given status            #
    #####################################################

    public function getRecords($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no =  null, $search_category = null, $search_term = null, $type = null, $method = null, $status = null, $employee_id = null, $client_id = null, $invoice_id = null, $refund_id = null, $expense_id = null, $otherincome_id = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';
        $search_category = $search_category ?: 'payment_id';
        $havingTheseRecords = '';

        /* Records Search */

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";

        // Restrict results by search category (client) and search term
        if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

       // Restrict results by search category (employee) and search term
        elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}     

        // Restrict results by search category and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."payment_records.$search_category LIKE ".$this->app->db->qstr('%'.$search_term.'%');} 

        /* Filter the Records */

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

        // Restrict by method
        if($method) {$whereTheseRecords .= " AND ".PRFX."payment_records.method= ".$this->app->db->qstr($method);}

        // Restrict by status
        if($status) {$whereTheseRecords .= " AND ".PRFX."payment_records.status= ".$this->app->db->qstr($status);}   

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$this->app->db->qstr($employee_id);}

        // Restrict by Client
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qstr($client_id);}

        // Restrict by Invoice
        if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qstr($invoice_id);}    

        // Restrict by Refund
        if($refund_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.refund_id=".$this->app->db->qstr($refund_id);} 

        // Restrict by Expense
        if($expense_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$this->app->db->qstr($expense_id);} 

        // Restrict by Otherincome
        if($otherincome_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$this->app->db->qstr($otherincome_id);}             

        /* The SQL code */

        $sql =  "SELECT
                ".PRFX."payment_records.*,
                IF(company_name !='', company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,
                CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name

                FROM ".PRFX."payment_records
                LEFT JOIN ".PRFX."user_records ON ".PRFX."payment_records.employee_id = ".PRFX."user_records.user_id
                LEFT JOIN ".PRFX."client_records ON ".PRFX."payment_records.client_id = ".PRFX."client_records.client_id

                ".$whereTheseRecords."
                GROUP BY ".PRFX."payment_records.".$order_by."
                ".$havingTheseRecords."
                ORDER BY ".PRFX."payment_records.".$order_by."
                ".$direction;           

        /* Restrict by pages */

        if($use_pages) {

            // Get Start Record
            $start_record = (($page_no * $records_per_page) - $records_per_page);

            // Figure out the total number of records in the database for the given search        
            if(!$rs = $this->app->db->Execute($sql)) {
                $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to count the matching payments."));
            } else {        
                $total_results = $rs->RecordCount();            
                $this->app->smarty->assign('total_results', $total_results);
            }        

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

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching payments."));
        } else {

            $records = $rs->GetArray();   // do i need to add the check empty

            if(empty($records)){

                return false;

            } else {

                return $records;

            }

        }

    }
    
    #############################
    #  Get payment details      #
    #############################

    public function getRecord($payment_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."payment_records WHERE payment_id=".$this->app->db->qstr($payment_id);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get payment details."));
        } else {

            if($item === null){

                return $rs->GetRowAssoc();            

            } else {

                return $rs->fields[$item];   

            } 

        }

    }

    ##########################
    #  Get payment options   #
    ##########################

    public function getOptions($item = null) {

        $sql = "SELECT * FROM ".PRFX."payment_options";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get payment options."));
        } else {

            if($item === null){

                return $rs->GetRowAssoc();            

            } else {

                return $rs->fields[$item];   

            } 

        }

    }

    ################################################
    #   Get get Payment Methods                    #
    ################################################

    public function getMethods($direction = null, $status = null) {

        $sql = "SELECT *
                FROM ".PRFX."payment_methods";

        // If the send direction is specified
        if($direction == 'send') {
            $sql .= "\nWHERE send = '1'";

        // If the receive direction is specified    
        } elseif($direction == 'receive') {        
            $sql .= "\nWHERE receive = '1'";        
        }

        // Only return methods that are enabled
        if($direction && $status == 'enabled') { 
            $sql .= "\nAND enabled = '1'";        
        }

        if(!$rs = $this->app->db->execute($sql)) {        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get payment method types."));
        } else {

            return $rs->GetArray();            

        }    

    }

    #####################################
    #    Get Payment Types              #  // i.e. invoice, refund
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."payment_types";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get payment types."));
        } else {

            //return $rs->GetRowAssoc();
            return $rs->GetArray();

        }    

    }

    #####################################
    #    Get Payment Statuses           #
    #####################################

    public function getStatuses() {

        $sql = "SELECT * FROM ".PRFX."payment_statuses";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get payment statuses."));
        } else {

            //return $rs->GetRowAssoc();
            return $rs->GetArray();

        }    

    }
    
    #####################################
    #  Get status names as an array     #
    #####################################

    public function getStatusDisplayNames() {

        $sql = "SELECT status_key, display_name
                FROM ".PRFX."payment_statuses";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get Status Names."));
        } else {

            $records = $rs->GetAssoc();

            if(empty($records)){

                return false;

            } else {

                return $records;

            }

        }  
    }
    
    #####################################
    #  Get Card names as an array       #  // Used in smarty modifier - libraries/vendor/smarty/smarty/libs/plugins/modifier.adinfodisplay.php
    #####################################

    public function getCardTypes() {

        $sql = "SELECT type_key, display_name
                FROM ".PRFX."payment_card_types";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get Card Names."));
        } else {

            $records = $rs->GetAssoc();

            if(empty($records)){

                return false;

            } else {

                return $records;

            }

        }  

    }

    #########################################
    #   Get get active credit cards         #
    #########################################

    public function getActiveCardTypes() {

        $sql = "SELECT
                type_key,
                display_name
                FROM ".PRFX."payment_card_types
                WHERE active='1'";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get the active cards."));
        } else {

            $records = $rs->GetArray();

            if(empty($records)){

                return false;

            } else {

                return $records;

            }

        }  

    }

    #####################################
    #  Get Card name from type          # // not currently used
    #####################################

    public function getCardDisplayNameFromKey($type_key) {

        $sql = "SELECT display_name FROM ".PRFX."payment_card_types WHERE type_key=".$this->app->db->qstr($type_key);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get Credit Card Name by key."));
        } else {

            return $rs->fields['display_name'];

        }    

    }


    ##########################################
    #    Get Payment additional info names   #  // Used in smarty modifier - libraries/vendor/smarty/smarty/libs/plugins/modifier.adinfodisplay.php
    ##########################################  

    public function getAdditionalInfoTypes() {

        $sql = "SELECT type_key, display_name
                FROM ".PRFX."payment_additional_info_types";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get payment additional info names."));
        } else {

            $records = $rs->GetAssoc();

            if(empty($records)){

                return false;

            } else {

                return $records;

            }

        }  

    }

    /** Update Functions **/

    #####################
    #   update payment  #
    #####################

    public function updateRecord($qpayment) {    

        $sql = "UPDATE ".PRFX."payment_records SET        
                employee_id     = ".$this->app->db->qstr( $this->app->user->login_user_id    ).",
                date            = ".$this->app->db->qstr( $this->app->system->general->dateToMysqlDate($qpayment['date']) ).",
                amount          = ".$this->app->db->qstr( $qpayment['amount']                   ).",
                last_active     =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()                      ).",
                note            = ".$this->app->db->qstr( $qpayment['note']                     )."
                WHERE payment_id =". $this->app->db->qstr( $qpayment['payment_id']              );

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update the payment details."));

        } else {

            // Create a Workorder History Note       
            $this->app->components->workorder->insertHistory($qpayment['workorder_id'], _gettext("Payment").' '.$qpayment['payment_id'].' '._gettext("updated by").' '.$this->app->user->login_display_name);           

            // Log activity 
            $record = _gettext("Payment").' '.$qpayment['payment_id'].' '._gettext("updated.");
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $qpayment['client_id'], $qpayment['workorder_id'], $qpayment['invoice_id']);

            // Update last active record    
            $this->app->components->client->updateLastActive($qpayment['client_id']);
            $this->app->components->workorder->updateLastActive($qpayment['workorder_id']);
            $this->app->components->invoice->updateLastActive($qpayment['invoice_id']);

        }

        return;

    }

    #####################################
    #    Update Payment options         #
    #####################################

    public function updateOptions($qform) {

        $sql = "UPDATE ".PRFX."payment_options SET            
                bank_account_name           =". $this->app->db->qstr( $qform['bank_account_name']            ).",
                bank_name                   =". $this->app->db->qstr( $qform['bank_name']                    ).",
                bank_account_number         =". $this->app->db->qstr( $qform['bank_account_number']          ).",
                bank_sort_code              =". $this->app->db->qstr( $qform['bank_sort_code']               ).",
                bank_iban                   =". $this->app->db->qstr( $qform['bank_iban']                    ).",
                paypal_email                =". $this->app->db->qstr( $qform['paypal_email']                 ).",        
                invoice_bank_transfer_msg   =". $this->app->db->qstr( $qform['invoice_bank_transfer_msg']    ).",
                invoice_cheque_msg          =". $this->app->db->qstr( $qform['invoice_cheque_msg']           ).",
                invoice_footer_msg          =". $this->app->db->qstr( $qform['invoice_footer_msg']           );            

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update payment options."));
        } else {

            // Log activity 
            // Done in payment:options controller

            return;

        }

    }

    #####################################
    #  Update Payment Methods statuses  #
    #####################################

    public function updateMethodsStatuses($payment_methods) {

        // Loop throught the various payment system methods and update the database
        foreach($payment_methods as $payment_method) {

            // When not checked, no value is sent so this sets zero for those cases
            if(!isset($payment_method['send'])) { $payment_method['send'] = '0'; }
            if(!isset($payment_method['receive'])) { $payment_method['receive'] = '0'; }
            if(!isset($payment_method['enabled'])) { $payment_method['enabled'] = '0'; }

            $sql = "UPDATE ".PRFX."payment_methods
                    SET
                    send                    = ". $this->app->db->qstr($payment_method['send']).",
                    receive                 = ". $this->app->db->qstr($payment_method['receive']).",
                    enabled                 = ". $this->app->db->qstr($payment_method['enabled'])."   
                    WHERE method_key = ". $this->app->db->qstr($payment_method['method_key']); 

            if(!$rs = $this->app->db->execute($sql)) {
                $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update payment method statuses."));
            }

        }

        // Log activity 
        // Done in payment:options controller

        return;

    }

    ############################
    # Update Payment Status    #
    ############################

    public function updateStatus($payment_id, $new_status, $silent = false) {

        // Get payment details
        $payment_details = $this->getRecord($payment_id);

        // if the new status is the same as the current one, exit
        if($new_status == $payment_details['status']) {        
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }    

        $sql = "UPDATE ".PRFX."payment_records SET
                status               =". $this->app->db->qstr( $new_status      ).",
                last_active          =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime() )."
                WHERE payment_id     =". $this->app->db->qstr( $payment_id      );

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a Payment Status."));

        } else {        

            // Status updated message
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment status updated.")); }

            // For writing message to log file, get payment status display name
            $payment_status_names = $this->getStatusDisplayNames();
            $payment_status_display_name = _gettext($payment_status_names[$new_status]);

            // Create a Workorder History Note (Not Used)      
            $this->app->components->workorder->insertHistory($payment_details['workorder_id'], _gettext("Payment Status updated to").' '.$payment_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Expense").' '.$payment_id.' '._gettext("Status updated to").' '.$payment_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $payment_details['client_id'], $payment_details['workorder_id'], $payment_details['invoice_id']);

            // Update last active record (Not Used)
            $this->app->components->client->updateLastActive($payment_details['client_id']);
            $this->app->components->workorder->updateLastActive($payment_details['workorder_id']);
            $this->app->components->invoice->updateLastActive($payment_details['invoice_id']);

            return true;

        }

    }

    /** Close Functions **/

    public function cancelRecord($payment_id) {

        // Make sure the payment can be cancelled
        if(!$this->checkRecordAllowsCancel($payment_id)) {        
            return false;
        }

        // Get payment details
        $payment_details = $this->getRecord($payment_id);

        // Change the payment status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($payment_id, 'cancelled');      

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($payment_details['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Expense").' '.$payment_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $payment_details['client_id'], $payment_details['workorder_id'], $payment_details['invoice_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($payment_details['client_id']);
        $this->app->components->workorder->updateLastActive($payment_details['workorder_id']);
        $this->app->components->invoice->updateLastActive($payment_details['invoice_id']);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Payment                 #
    #####################################

    public function deleteRecord($payment_id) {

        // Get payment details before deleting the record
        $payment_details = $this->getRecord($payment_id);

        $sql = "UPDATE ".PRFX."payment_records SET        
                employee_id     = '',
                client_id       = '',
                workorder_id    = '',
                invoice_id      = '',
                voucher_id      = '',
                refund_id       = '',
                expense_id      = '',
                otherincome_id  = '',
                date            = '0000-00-00',
                tax_system      = '',
                type            = '',
                method          = '',
                status          = 'deleted',
                amount          = '0.00',
                additional_info = '',
                last_active         = '0000-00-00 00:00:00',
                note            = ''
                WHERE payment_id =". $this->app->db->qstr( $payment_id );    

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to delete the payment record."));
        } else {

            // Create a Workorder History Note       
            $this->app->components->workorder->insertHistory($payment_details['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("has been deleted by").' '.$this->app->user->login_display_name);           

            // Log activity        
            $record = _gettext("Payment").' '.$payment_id.' '._gettext("has been deleted.");
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $payment_details['client_id'], $payment_details['workorder_id'], $payment_details['invoice_id']);

            // Update last active record    
            $this->app->components->client->updateLastActive($payment_details['client_id']);
            $this->app->components->workorder->updateLastActive($payment_details['workorder_id']);
            $this->app->components->invoice->updateLastActive($payment_details['invoice_id']);

            return true;        

        } 

    }
    
    /** Check functions **/
    
    ######################################################
    #   Make sure the submitted payment amount is valid  #
    ######################################################

    public function checkAmountValid($record_balance, $payment_amount) {

        // If a negative amount has been submitted. (This should not be allowed because of the <input> masks.)
        if($payment_amount < 0){

            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can not enter a payment with a negative amount."));

            return false;

        }

        // Has a zero amount been submitted, this is not allowed
        if($payment_amount == 0){

            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can not enter a payment with a zero (0.00) amount."));

            return false;

        }

        // Is the payment larger than the outstanding invoice balance, this is not allowed
        if($payment_amount > $record_balance){

            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can not enter an payment with an amount greater than the outstanding balance."));

            return false;

        }

        return true;

    }

    ####################################################
    #      Check if a payment method is active         #
    ####################################################

    public function checkMethodActive($method, $direction = null) {

        $sql = "SELECT *
                FROM ".PRFX."payment_methods
                WHERE method_key=".$this->app->db->qstr($method);

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to check if the payment method is active."));    

        } else {

            // If module is disabled, always return disabled for both directions
            if(!$rs->fields['enabled']) { return false; }

            // If send direction is specified
            if($direction == 'send') { return $rs->fields['send']; }

            // If receive direction is specified
            if($direction == 'receive') { return $rs->fields['receive']; }

            // Fallback behaviour
            return true;

        }

    }

    ##########################################################
    #  Check if the payment status is allowed to be changed  #  // not currently used
    ##########################################################

     public function checkRecordAllowsChange($payment_id) {

        $state_flag = false; // Disable the ability to manually change status for now

        // Get the payment details
        $payment_details = $this->getRecord($payment_id);

        // Is the current payment method active, if not you cannot change status
        if(!$this->checkMethodActive($payment_details['method'], 'receive')) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment status cannot be changed because it's current payment method is not available."));
            $state_flag = false;       
        }

        // Is deleted
        if($payment_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment status cannot be changed because the payment has been deleted."));
            $state_flag = false;       
        }

        // Is this an invoice payment and parent invoice has been refunded
        if($payment_details['type'] == 'invoice' && $this->app->components->invoice->getRecord($payment_details['invoice_id'], 'status') == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be changed because the parent invoice has been refunded."));
            $state_flag = false; 
        }

        return $state_flag;   

     }

    ###############################################################
    #   Check to see if the payment can be refunded (by status)   #  // not currently used - i DONT think i will use this , you cant refund a payment?
    ###############################################################

    public function checkRecordAllowsRefund($payment_id) {

        $state_flag = true;

        // Get the payment details
        $payment_details = $this->getRecord($payment_id);

        // Is partially paid
        if($payment_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be refunded because the payment is partially paid."));
            return $state_flag;
        }

        // Is refunded
        if($payment_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be refunded because the payment has already been refunded."));
            $state_flag = false;       
        }

        // Is cancelled
        if($payment_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be refunded because the payment has been cancelled."));
            $state_flag = false;       
        }

        // Is deleted
        if($payment_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be refunded because the payment has been deleted."));
            $state_flag = false;       
        }    

        // Is this an invoice payment and parent invoice has been refunded
        if($payment_details['type'] == 'invoice' && $this->app->components->invoice->getRecord($payment_details['invoice_id'], 'status') == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be refunded because the parent invoice has been refunded."));
            $state_flag = false; 
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the payment can be cancelled              #
    ###############################################################

    public function checkRecordAllowsCancel($payment_id) {

        $state_flag = true;

        // Get the payment details
        $payment_details = $this->getRecord($payment_id);

        // Is cancelled
        if($payment_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be cancelled because the payment has already been cancelled."));
            $state_flag = false;       
        }

        // Is deleted
        if($payment_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be cancelled because the payment has been deleted."));
            $state_flag = false;       
        }

        // Is this an invoice payment and parent invoice has been refunded
        if($payment_details['type'] == 'invoice' && $this->app->components->invoice->getRecord($payment_details['invoice_id'], 'status') == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be cancelled because the parent invoice has been refunded."));
            $state_flag = false; 
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the payment can be deleted                #
    ###############################################################

    public function checkRecordAllowsDelete($payment_id) {

        $state_flag = true;

        // Get the payment details
        $payment_details = $this->getRecord($payment_id);

        // Is cancelled
        if($payment_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because it has been cancelled."));
            $state_flag = false;       
        }

        // Is deleted
        if($payment_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because it already been deleted."));
            $state_flag = false;       
        }

        // Is this an invoice payment and parent invoice has been refunded
        if($payment_details['type'] == 'invoice' && $this->app->components->invoice->getRecord($payment_details['invoice_id'], 'status') == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be deleted because the parent invoice has been refunded."));
            $state_flag = false; 
        }

        return $state_flag;

    }

    ##########################################################
    #  Check if the payment status allows editing            #
    ##########################################################

     public function checkRecordAllowsEdit($payment_id) {

        $state_flag = true;

        // Get the payment details
        $payment_details = $this->getRecord($payment_id);

        // Is on a different tax system
        if($payment_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be edited because it is on a different Tax system."));
            $state_flag = false;       
        }

        /* Is the current payment method active, if not you cannot change status
        if(!$this->check_payment_method_is_active($payment_details['method'], 'receive')) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment status cannot be edited because it's current payment method is not available."));
            $state_flag = false;       
        }*/

        // Is Cancelled
        if($payment_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be edited because it has been cancelled."));
            $state_flag = false;       
        }

        // Is Deleted
        if($payment_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be edited because it has been deleted."));
            $state_flag = false;       
        }

        // Is this an invoice payment and parent invoice has been refunded
        if($payment_details['type'] == 'invoice' && $this->app->components->invoice->getRecord($payment_details['invoice_id'], 'status') == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be edited because the parent invoice has been refunded."));
            $state_flag = false; 
        }

        return $state_flag; 

    }    

    /** Other Functions **/

    #########################################
    #  Build additional_info JSON           #       
    #########################################

     public function buildAdditionalInfoJson($bank_transfer_reference = null, $card_type_key = null, $name_on_card = null, $cheque_number = null, $direct_debit_reference = null, $paypal_transaction_id = null) {

        $additional_info = array();

        // Build Array
        $additional_info['bank_transfer_reference'] = $bank_transfer_reference ? $bank_transfer_reference : '';
        $additional_info['card_type_key'] = $card_type_key ? $card_type_key : '';
        $additional_info['name_on_card'] = $name_on_card ? $name_on_card : '';
        $additional_info['cheque_number'] = $cheque_number ? $cheque_number : '';
        $additional_info['direct_debit_reference'] = $direct_debit_reference ? $direct_debit_reference : '';
        $additional_info['paypal_transaction_id'] = $paypal_transaction_id ? $paypal_transaction_id : '';

        // Return the JSON data
        return json_encode($additional_info);

    }


    
    // Build the buttons array for payment buttons (currently only used for new payments)
    function prepareButtonsHolder() {
        
        Payment::$buttons = array(
            'submit' => array('allowed' => false, 'url' => null, 'title' => null),
            'cancel' => array('allowed' => false, 'url' => null, 'title' => null),
            'returnToRecord' => array('allowed' => false, 'url' => null, 'title' => null),
            'addNewRecord' => array('allowed' => false, 'url' => null, 'title' => null)
        );
        
    }
    
    // Build qpayment array - Set the various payment type IDs in to qpayment
    function buildQpaymentArray() {
        
        \CMSApplication::$VAR['qpayment']['payment_id'] = Payment::$payment_details['payment_id'];
        \CMSApplication::$VAR['qpayment']['type'] = Payment::$payment_details['type'];
        \CMSApplication::$VAR['qpayment']['invoice_id'] = Payment::$payment_details['invoice_id'];
        \CMSApplication::$VAR['qpayment']['voucher_id'] = Payment::$payment_details['voucher_id'];
        \CMSApplication::$VAR['qpayment']['refund_id'] = Payment::$payment_details['refund_id'];
        \CMSApplication::$VAR['qpayment']['expense_id'] = Payment::$payment_details['expense_id'];
        \CMSApplication::$VAR['qpayment']['otherincome_id'] = Payment::$payment_details['otherincome_id'];
        
    }
    
    
}