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
    public static $payment_successful = false;
    public static $record_balance = null;
    public static $disabledMethods = array();
    public static $timestamp = null;


    public function __construct()
    {
        // Unify Dates and Times
        $this::$timestamp = time();

        parent::__construct();
    }

    /** Insert Functions **/

    ############################  // as you can see not all variables are submitted
    #   Insert Payment         #
    ############################

    public function insertRecord($qpayment) {

        $sql = "INSERT INTO ".PRFX."payment_records SET
                employee_id     = ".$this->app->db->qStr( $this->app->user->login_user_id          ).",
                client_id       = ".$this->app->db->qStr( $qpayment['client_id'] ?: null           ).",
                supplier_id     = ".$this->app->db->qStr( $qpayment['supplier_id'] ?: null         ).",
                invoice_id      = ".$this->app->db->qStr( $qpayment['invoice_id'] ?: null          ).",
                expense_id      = ".$this->app->db->qStr( $qpayment['expense_id'] ?: null          ).",
                otherincome_id  = ".$this->app->db->qStr( $qpayment['otherincome_id'] ?: null      ).",
                creditnote_id   = ".$this->app->db->qStr( $qpayment['creditnote_id'] ?: null       ).",
                creditnote_action  = ".$this->app->db->qStr( $qpayment['creditnote_action'] ?: null).",
                voucher_id      = ".$this->app->db->qStr( $qpayment['voucher_id'] ?: null          ).",
                date            = ".$this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qpayment['date'])    ).",
                tax_system      = ".$this->app->db->qStr( QW_TAX_SYSTEM                            ).",
                type            = ".$this->app->db->qStr( $qpayment['type']        ).",
                method          = ".$this->app->db->qStr( $qpayment['method']      ).",
                direction       = ".$this->app->db->qStr( $qpayment['direction']      ).",
                status          = 'valid',
                amount          = ".$this->app->db->qStr( $qpayment['amount']                      ).",
                additional_info = ".$this->app->db->qStr( $qpayment['additional_info']             ).",
                note            = ".$this->app->db->qStr( $qpayment['note']                        );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Get Payment Record ID
        $payment_id = $this->app->db->Insert_ID();

        // Create a Workorder History Note - not a work order
        //$this->app->components->workorder->insertHistory(Payment::$payment_details['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("added by").' '.$this->app->user->login_display_name);

        // Log activity
        $record = _gettext("Payment").' '.$payment_id.' '._gettext("created.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $qpayment['client_id'], null, $qpayment['invoice_id']);

        // Update last active record
        $this->updateLastActive($payment_id, $this::$timestamp);
        $this->app->components->client->updateLastActive($qpayment['client_id'], $this::$timestamp);
        $this->app->components->invoice->updateLastActive($qpayment['invoice_id'], $this::$timestamp);
        $this->app->components->supplier->updateLastActive($qpayment['supplier_id'], $this::$timestamp);

        // Return the payment_id
        return $payment_id;

    }

    /** Get Functions **/

    #####################################################
    #  Display all payments the given status            #
    #####################################################

    public function getRecords($order_by, $direction, $records_per_page = 0, $use_pages = false, $page_no =  null, $search_category = 'payment_id', $search_term = null, $type = null, $method = null, $paymentDirection = null, $status = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null, $otherincome_id = null, $creditnote_id = null, $creditnote_action = null, $voucher_id = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."payment_records.payment_id\n";
        $havingTheseRecords = '';

        // Restrict results by search category (client) and search term
        if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

       // Restrict results by search category (employee) and search term
        elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."payment_records.$search_category LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict by Type
        $whereTheseRecords .= $this->app->components->report->paymentBuildFilterByType($type);
        /*if($type) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.type= ".$this->app->db->qStr($type);
        }*/

        // Restrict by Method
        if($method) {$whereTheseRecords .= " AND ".PRFX."payment_records.method= ".$this->app->db->qStr($method);}

        // Restrict by Direction
        $whereTheseRecords .= $this->app->components->report->paymentBuildFilterByPaymentDirection($paymentDirection);
        /*if($paymentDirection) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.direction= ".$this->app->db->qStr($paymentDirection);
        }*/

        // Restrict by Status
        if($status) {$whereTheseRecords .= " AND ".PRFX."payment_records.status= ".$this->app->db->qStr($status);}

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.employee_id=".$this->app->db->qStr($employee_id);}

        // Restrict by Client
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qStr($client_id);}

        // Restrict by Invoice
        if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.invoice_id=".$this->app->db->qStr($invoice_id);}

        // Restrict by Supplier
        if($supplier_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.client_id=".$this->app->db->qStr($client_id);}

        // Restrict by Expense
        if($expense_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.expense_id=".$this->app->db->qStr($expense_id);}

        // Restrict by Otherincome
        if($otherincome_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.otherincome_id=".$this->app->db->qStr($otherincome_id);}

        // Restrict by Credit Note
        if($creditnote_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.creditnote_id=".$this->app->db->qStr($creditnote_id);}

        // Restrict by Credit Note Action
        $whereTheseRecords .= $this->app->components->report->paymentBuildFilterByCreditnoteAction($creditnote_action);
        /*if($creditnote_action) {
            $whereTheseRecords .= " AND ".PRFX."payment_records.creditnote_action=".$this->app->db->qStr($creditnote_action);
        }*/

        // Restrict by Voucher
        if($voucher_id) {$whereTheseRecords .= " AND ".PRFX."payment_records.voucher_id=".$this->app->db->qStr($voucher_id);}

        // The SQL code
        $sql =  "SELECT
                ".PRFX."payment_records.*,

                CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name,
                IF(".PRFX."client_records.company_name !='', ".PRFX."client_records.company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,
                IF(".PRFX."supplier_records.company_name !='', ".PRFX."supplier_records.company_name, CONCAT(".PRFX."supplier_records.first_name, ' ', ".PRFX."supplier_records.last_name)) AS supplier_display_name

                FROM ".PRFX."payment_records
                LEFT JOIN ".PRFX."user_records ON ".PRFX."payment_records.employee_id = ".PRFX."user_records.user_id
                LEFT JOIN ".PRFX."client_records ON ".PRFX."payment_records.client_id = ".PRFX."client_records.client_id
                LEFT JOIN ".PRFX."supplier_records ON ".PRFX."payment_records.supplier_id = ".PRFX."supplier_records.supplier_id

                ".$whereTheseRecords."
                GROUP BY ".PRFX."payment_records.".$order_by."
                ".$havingTheseRecords."
                ORDER BY ".PRFX."payment_records.".$order_by."
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

    #############################
    #  Get payment details      #
    #############################

    public function getRecord($payment_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."payment_records WHERE payment_id=".$this->app->db->qStr($payment_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!$item){

            return $rs->GetRowAssoc();

        } else {

            return $rs->fields[$item];

        }

    }

    ##########################
    #  Get payment options   #
    ##########################

    public function getOptions($item = null) {

        $sql = "SELECT * FROM ".PRFX."payment_options";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!$item){

            return $rs->GetRowAssoc();

        } else {

            return $rs->fields[$item];

        }

    }

    ################################################  // default = returns all methods
    #   Get get Payment Methods                    #  // can restrict returned methods by direction, status
    ################################################  // invalidTypes() are specific payment methods that are not allowed for this payment type

    public function getMethods($direction = null, $activeOnly = false, $invalidMethods = array()) {

        // Default Action
        $sql = "SELECT * FROM ".PRFX."payment_methods
                WHERE ".PRFX."payment_methods.id\n";

        // If the method direction is specified
        if($direction == 'send') {
            $sql .= "\nAND send = '1'";
        } elseif($direction == 'receive') {
            $sql .= "\nAND receive = '1'";
        }

        // Only return methods that are enabled
        if($activeOnly) {
            $sql .= "\nAND enabled = '1'";
        }

        // Restrict Payment Methods - remove these methods from results
        if($invalidMethods) {
            $notTheseMethods = '';
            foreach($invalidMethods as $invalidMethod) {
                $notTheseMethods .= "'$invalidMethod', ";
            }
            $sql .= "\nAND method_key NOT IN (".rtrim($notTheseMethods, ', ').")";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    #####################################
    #    Get Payment Types              #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."payment_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    #####################################
    #    Get Payment Statuses           #
    #####################################

    public function getStatuses() {

        $sql = "SELECT * FROM ".PRFX."payment_statuses";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    #############################################
    #    Get Credit Note Action Types           #
    #############################################


    public function getCreditnoteActionTypes() {

        $sql = "SELECT * FROM ".PRFX."payment_creditnote_action_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    #############################################
    #    Get Payment Directions                 #
    #############################################


    public function getDirections() {

        $sql = "SELECT * FROM ".PRFX."payment_directions";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    #####################################
    #  Get status names as an array     #
    #####################################

    public function getStatusDisplayNames() {

        $sql = "SELECT status_key, display_name
                FROM ".PRFX."payment_statuses";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $records = $rs->GetAssoc();

        if(empty($records)){

            return false;

        } else {

            return $records;

        }

    }

    #####################################
    #  Get Card names as an array       #  // Used in smarty modifier - libraries/vendor/smarty/smarty/libs/plugins/modifier.adinfodisplay.php
    #####################################

    public function getCardTypes() {

        $sql = "SELECT type_key, display_name
                FROM ".PRFX."payment_card_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $records = $rs->GetAssoc();

        if(empty($records)){

            return false;

        } else {

            return $records;

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

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $records = $rs->GetArray();

        if(empty($records)){

            return false;

        } else {

            return $records;

        }



    }

    #####################################
    #  Get Card name from type          # // not currently used
    #####################################

    public function getCardDisplayNameFromKey($type_key) {

        $sql = "SELECT display_name FROM ".PRFX."payment_card_types WHERE type_key=".$this->app->db->qStr($type_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];

    }


    ##########################################
    #    Get Payment additional info names   #  // Used in smarty modifier - libraries/vendor/smarty/smarty/libs/plugins/modifier.adinfodisplay.php
    ##########################################

    public function getAdditionalInfoTypes() {

        $sql = "SELECT type_key, display_name
                FROM ".PRFX."payment_additional_info_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $records = $rs->GetAssoc();

        if(empty($records)){

            return false;

        } else {

            return $records;

        }

    }

    /** Update Functions **/

    #####################
    #   update payment  #
    #####################

    public function updateRecord($qpayment) {

        $sql = "UPDATE ".PRFX."payment_records SET
                employee_id     = ".$this->app->db->qStr( $this->app->user->login_user_id    ).",
                date            = ".$this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qpayment['date']) ).",
                amount          = ".$this->app->db->qStr( $qpayment['amount']                   ).",
                note            = ".$this->app->db->qStr( $qpayment['note']                     )."
                WHERE payment_id =". $this->app->db->qStr( $qpayment['payment_id']              );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Create a Workorder History Note - not a Workorder
        //$this->app->components->workorder->insertHistory($qpayment['workorder_id'], _gettext("Payment").' '.$qpayment['payment_id'].' '._gettext("updated by").' '.$this->app->user->login_display_name);

        // Log activity
        $record = _gettext("Payment").' '.$qpayment['payment_id'].' '._gettext("updated.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $qpayment['client_id'], null, $qpayment['invoice_id']);

        // Update last active record
        $this->updateLastActive($payment_id, $this::$timestamp);
        $this->app->components->client->updateLastActive($qpayment['client_id'], $this::$timestamp);
        $this->app->components->invoice->updateLastActive($qpayment['invoice_id'], $this::$timestamp);
        $this->app->components->supplier->updateLastActive($qpayment['supplier_id'], $this::$timestamp);

        return true;

    }

    #####################################
    #    Update Payment options         #
    #####################################

    public function updateOptions($qform) {

        $sql = "UPDATE ".PRFX."payment_options SET
                bank_account_name           =". $this->app->db->qStr( $qform['bank_account_name']            ).",
                bank_name                   =". $this->app->db->qStr( $qform['bank_name']                    ).",
                bank_account_number         =". $this->app->db->qStr( $qform['bank_account_number']          ).",
                bank_sort_code              =". $this->app->db->qStr( $qform['bank_sort_code']               ).",
                bank_iban                   =". $this->app->db->qStr( $qform['bank_iban']                    ).",
                paypal_email                =". $this->app->db->qStr( $qform['paypal_email']                 ).",
                invoice_bank_transfer_msg   =". $this->app->db->qStr( $qform['invoice_bank_transfer_msg']    ).",
                invoice_cheque_msg          =". $this->app->db->qStr( $qform['invoice_cheque_msg']           ).",
                invoice_footer_msg          =". $this->app->db->qStr( $qform['invoice_footer_msg']           ).",
                creditnote_footer_msg       =". $this->app->db->qStr( $qform['creditnote_footer_msg']        );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity
        // Done in payment:options controller

        return;

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
                    send                    = ". $this->app->db->qStr($payment_method['send']).",
                    receive                 = ". $this->app->db->qStr($payment_method['receive']).",
                    enabled                 = ". $this->app->db->qStr($payment_method['enabled'])."
                    WHERE method_key = ". $this->app->db->qStr($payment_method['method_key']);

            if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

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
                status               =". $this->app->db->qStr( $new_status      )."
                WHERE payment_id     =". $this->app->db->qStr( $payment_id      );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Status updated message
        if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment status updated.")); }

        // For writing message to log file, get payment status display name
        $payment_status_names = $this->getStatusDisplayNames();
        $payment_status_display_name = _gettext($payment_status_names[$new_status]);

        // Create a Workorder History Note (Not Used) - not a workorder
        //$this->app->components->workorder->insertHistory($payment_details['workorder_id'], _gettext("Payment Status updated to").' '.$payment_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Expense").' '.$payment_id.' '._gettext("Status updated to").' '.$payment_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $payment_details['client_id'], null, $payment_details['invoice_id']);

        // Update last active record
        $this->updateLastActive($payment_id, $this::$timestamp);
        $this->app->components->client->updateLastActive($payment_details['client_id'], $this::$timestamp);
        $this->app->components->invoice->updateLastActive($payment_details['invoice_id'], $this::$timestamp);
        $this->app->components->supplier->updateLastActive($payment_details['supplier_id'], $this::$timestamp);

        return true;

    }

    #################################
    #    Update Last Active         #
    #################################

    public function updateLastActive($payment_id = null, $timestamp = null) {

        // Allow null calls
        if(!$payment_id) { return; }

        $sql = "UPDATE ".PRFX."payment_records SET
                last_active=".$this->app->db->qStr( $this->app->system->general->mysqlDatetime($timestamp) )."
                WHERE payment_id=".$this->app->db->qStr($payment_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    /** Close Functions **/

    #####################################
    #    Cancel  Payment                #
    #####################################

    public function cancelRecord($payment_id) {

        // Get payment details
        $payment_details = $this->getRecord($payment_id);

        // Change the payment status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($payment_id, 'cancelled');

        // Create a Workorder History Note - not a work order
        //$this->app->components->workorder->insertHistory($payment_details['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Expense").' '.$payment_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $payment_details['client_id'], null, $payment_details['invoice_id']);

        // Update last active record
        $this->updateLastActive($payment_id, $this::$timestamp);
        $this->app->components->client->updateLastActive($payment_details['client_id'], $this::$timestamp);
        $this->app->components->invoice->updateLastActive($payment_details['invoice_id'], $this::$timestamp);
        $this->app->components->supplier->updateLastActive($payment_details['supplier_id'], $this::$timestamp);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Payment                 #
    #####################################

    public function deleteRecord($payment_id) {

        // Get payment details before deleting the record
        $payment_details = $this->getRecord($payment_id);

        // Change the payment status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($payment_id, 'deleted');

        $sql = "UPDATE ".PRFX."payment_records SET
                employee_id     = NULL,
                client_id       = NULL,
                supplier_id     = NULL,
                invoice_id      = NULL,
                expense_id      = NULL,
                otherincome_id  = NULL,
                creditnote_id   = NULL,
                voucher_id      = NULL,
                date            = NULL,
                tax_system      = '',
                type            = '',
                method          = '',
                direction       = '',
                status          = 'deleted',
                amount          = 0.00,
                additional_info = '',
                last_active     = NULL,
                note            = ''
                WHERE payment_id = ". $payment_id;

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Create a Workorder History Note - not a workorder
        //$this->app->components->workorder->insertHistory($payment_details['workorder_id'], _gettext("Payment").' '.$payment_id.' '._gettext("has been deleted by").' '.$this->app->user->login_display_name);

        // Log activity
        $record = _gettext("Payment").' '.$payment_id.' '._gettext("has been deleted.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $payment_details['client_id'], null, $payment_details['invoice_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($payment_details['client_id'], $this::$timestamp);
        $this->app->components->invoice->updateLastActive($payment_details['invoice_id'], $this::$timestamp);
        $this->app->components->supplier->updateLastActive($payment_details['supplier_id'], $this::$timestamp);

        return true;

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

        // Is the payment larger than the outstanding balance, this is not allowed
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

        // If payment is deleted it's method is null, always return disabled for both directions
        if(!$method) { return false; }

        $sql = "SELECT *
                FROM ".PRFX."payment_methods
                WHERE method_key=".$this->app->db->qStr($method);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // If no direction specified, return method active status
        if(!$direction) { return $rs->fields['enabled']; }

        // If module is disabled, always return disabled for both directions
        if(!$rs->fields['enabled']) { return false; }

        // If send direction is specified
        if($direction == 'send') { return $rs->fields['send']; }

        // If receive direction is specified
        if($direction == 'receive') { return $rs->fields['receive']; }

        // Fallback behaviour
        return false;

    }

    ##########################################################
    #  Check if the payment status is allowed to be changed  #  // used on payment:status
    ##########################################################  // This feature is not implemented, but present

     public function checkRecordAllowsManualStatusChange($payment_id) {

        $state_flag = false; // Disable the ability to manually change status for now

        // Get the payment details
        $payment_details = $this->getRecord($payment_id);

        // Is the current payment method is not active, if not you cannot change status
        if(!$this->checkMethodActive($payment_details['method'], 'receive')) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment status cannot be changed because it's current payment method is not available."));
            $state_flag = false;
        }

        // Is deleted
        if($payment_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment status cannot be changed because the payment has been deleted."));
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

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the payment can be cancelled              #
    ###############################################################

    public function checkRecordAllowsCancel($payment_id) {

        $state_flag = true;

        // Get the payment details
        $payment_details = $this->getRecord($payment_id);

        // Is on a different tax system
        if($payment_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be cancelled because it is on a different Tax system."));
            $state_flag = false;
        }

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

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the payment can be deleted                #
    ###############################################################

    public function checkRecordAllowsDelete($payment_id) {

        $state_flag = true;

        // Get the payment details
        $payment_details = $this->getRecord($payment_id);

        // Is on a different tax system
        if($payment_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be deleted because it is on a different Tax system."));
            $state_flag = false;
        }

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

        return $state_flag;

    }


    /** Other Functions **/

    #########################################
    #  Build additional_info JSON           #
    #########################################

    public function buildAdditionalInfoJson($bank_transfer_reference = null, $card_type_key = null, $name_on_card = null, $cheque_number = null, $direct_debit_reference = null, $paypal_transaction_id = null) {

        $additional_info = array();

        // Build Array (not all types have all fields)
        $additional_info['bank_transfer_reference'] = $bank_transfer_reference ?: '';
        $additional_info['card_type_key'] = $card_type_key ?: '';
        $additional_info['name_on_card'] = $name_on_card ?: '';
        $additional_info['cheque_number'] = $cheque_number ?: '';
        $additional_info['direct_debit_reference'] = $direct_debit_reference ?: '';
        $additional_info['paypal_transaction_id'] = $paypal_transaction_id ?: '';

        // Return the JSON data
        return json_encode($additional_info);

    }

    ##################################################################################
    #  Build the enviroment for for making payments - The relevant Type and Class    #
    ##################################################################################

    public function buildPaymentEnvironment($action)
    {
        // Set Action Type
        Payment::$action = $action;

        // New
        if($action === 'new')
        {
            // Prevent undefined variable errors (with and without submit)  - some of these vairables can be from page referrals (i.e. new) or page reloads or dont exists,
            \CMSApplication::$VAR['qpayment']['type']               = \CMSApplication::$VAR['type'];
            \CMSApplication::$VAR['qpayment']['method']             = \CMSApplication::$VAR['method'] ?? \CMSApplication::$VAR['qpayment']['method'] ?? null;
            \CMSApplication::$VAR['qpayment']['direction']          = null;
            \CMSApplication::$VAR['qpayment']['client_id']          = \CMSApplication::$VAR['client_id'] ?? \CMSApplication::$VAR['qpayment']['client_id'] ?? null;
            \CMSApplication::$VAR['qpayment']['supplier_id']        = \CMSApplication::$VAR['supplier_id'] ?? \CMSApplication::$VAR['qpayment']['supplier_id'] ?? null;
            \CMSApplication::$VAR['qpayment']['invoice_id']         = \CMSApplication::$VAR['invoice_id'] ?? \CMSApplication::$VAR['qpayment']['invoice_id'] ?? null;
            \CMSApplication::$VAR['qpayment']['expense_id']         = \CMSApplication::$VAR['expense_id'] ?? \CMSApplication::$VAR['qpayment']['expense_id'] ?? null;
            \CMSApplication::$VAR['qpayment']['otherincome_id']     = \CMSApplication::$VAR['otherincome_id'] ?? \CMSApplication::$VAR['qpayment']['otherincome_id'] ?? null;
            \CMSApplication::$VAR['qpayment']['creditnote_id']      = \CMSApplication::$VAR['creditnote_id'] ?? \CMSApplication::$VAR['qpayment']['creditnote_id'] ?? null;
            \CMSApplication::$VAR['qpayment']['creditnote_action']  = null;
            \CMSApplication::$VAR['qpayment']['voucher_id']         = null;
            \CMSApplication::$VAR['qpayment']['voucher_code']       = \CMSApplication::$VAR['voucher_code'] ?? \CMSApplication::$VAR['qpayment']['voucher_code'] ?? null;
            \CMSApplication::$VAR['qpayment']['name_on_card']       = \CMSApplication::$VAR['qpayment']['name_on_card'] ?? null;

            // Build empty button array - to prevent undefined variable errors
            Payment::$buttons = array(
                'submit' => array('allowed' => false, 'url' => null, 'title' => null),
                'cancel' => array('allowed' => false, 'url' => null, 'title' => null),
                'returnToRecord' => array('allowed' => false, 'url' => null, 'title' => null),
                'addNewRecord' => array('allowed' => false, 'url' => null, 'title' => null)
            );
        }

        // For all actions that are not new
        if($action !== 'new')
        {
            // Set Payment details
            Payment::$payment_details = $this->app->components->payment->getRecord(\CMSApplication::$VAR['payment_id']);

            // Set Payment IDs into [qpayment]
            \CMSApplication::$VAR['qpayment']['payment_id'] = Payment::$payment_details['payment_id'];
            \CMSApplication::$VAR['qpayment']['type'] = Payment::$payment_details['type'];
            \CMSApplication::$VAR['qpayment']['method'] = Payment::$payment_details['method'];
            \CMSApplication::$VAR['qpayment']['direction'] = Payment::$payment_details['direction'];
            \CMSApplication::$VAR['qpayment']['invoice_id'] = Payment::$payment_details['invoice_id'];
            \CMSApplication::$VAR['qpayment']['expense_id'] = Payment::$payment_details['expense_id'];
            \CMSApplication::$VAR['qpayment']['otherincome_id'] = Payment::$payment_details['otherincome_id'];
            \CMSApplication::$VAR['qpayment']['creditnote_id'] = Payment::$payment_details['creditnote_id'];
            \CMSApplication::$VAR['qpayment']['creditnote_action'] = Payment::$payment_details['creditnote_action'];
            \CMSApplication::$VAR['qpayment']['voucher_id'] = Payment::$payment_details['voucher_id'];
        }

        // Load the Type and Method classes (files only, no store)
        \CMSApplication::classFilesLoad(COMPONENTS_DIR.'payment/types/');
        \CMSApplication::classFilesLoad(COMPONENTS_DIR.'payment/methods/');

        // Set the payment type class (Capitalise the first letter, Workaround: removes underscores, these might go when i go full PSR-1)
        $typeClassName = 'PaymentType'.ucfirst(str_replace('_', '', \CMSApplication::$VAR['qpayment']['type']));
        $this->paymentType = new $typeClassName;
    }

    // Process the payment
    public function processPayment()
    {
        // Set the payment method class (Capitalise the first letter, Workaround: removes underscores, these might go when i go full PSR-1)
        $methodClassName = 'PaymentMethod'.ucfirst(str_replace('_', '', \CMSApplication::$VAR['qpayment']['method']));
        $this->paymentMethod = new $methodClassName;

        // Prep/Validate the data
        $this->paymentType->preProcess();      // Need to validate payment against Type first
        $this->paymentMethod->preProcess();    // now need to check if the payment method is valid

        // Process the payment
        if(Payment::$payment_valid)
        {
            $this->paymentMethod->process();  // Insert/edit/cancel/delete database operations
            $this->paymentType->process();    // Recalcualtion of type records
        }

        // Final things like set messages and redirects based on results
        $this->paymentMethod->postProcess();  // Messages
        $this->paymentType->postProcess();    // Messages

        // Refresh the payment details (used for page reloads) - if an insert fails, then there will be no payment_id
        if(Payment::$payment_details['payment_id'] ?? false)
        {
            Payment::$payment_details = $this->app->components->payment->getRecord(Payment::$payment_details['payment_id']);
        }

        // Log activity here if the payment activity failed (otherwise activity would not get logged)
        if(!Payment::$payment_successful)
        {
            // Update last active record
            $this->updateLastActive(Payment::$payment_details['payment_id'], Payment::$timestamp);
            $this->app->components->client->updateLastActive(Payment::$payment_details['client_id'], Payment::$timestamp);
            $this->app->components->invoice->updateLastActive(Payment::$payment_details['invoice_id'], Payment::$timestamp);
            $this->app->components->supplier->updateLastActive(Payment::$payment_details['supplier_id'], Payment::$timestamp);

        }

    }

}
