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

class Voucher extends Components {


    /** Insert Functions **/

    #################################
    #   Insert Voucher              #
    #################################

    public function insertRecord($invoice_id, $type, $expiry_date, $unit_net, $note) {

        $invoice_details = $this->app->components->invoice->getRecord($invoice_id);

        // Add in missing sales tax exempt option - This prevents undefined variable errors (ALL 'sales_tax_cash' vouchers and coupons should be exempt)
        $sales_tax_exempt = ($invoice_details['tax_system'] == 'sales_tax_cash') ? 1 : 0;

        // Add in missing vat_tax_codes (i.e. submissions from 'no_tax' and 'sales_tax_cash' dont have VAT codes) - This prevents undefined variable errors
        $vat_tax_code = $this->getVatTaxCode($type, $invoice_details['tax_system']);

        // Calculate the correct tax rate based on tax system (and exemption status) -- KEEP this for reference
        if($invoice_details['tax_system'] == 'sales_tax_cash' && $sales_tax_exempt) { $unit_tax_rate = 0.00; }
        //elseif($invoice_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $invoice_details['sales_tax_rate']; } will not be used while $sales_tax_exempt = ...
        elseif(preg_match('/^vat_/', $invoice_details['tax_system'])) { $unit_tax_rate = $this->app->components->company->getVatRate($vat_tax_code); }
        else { $unit_tax_rate = 0.00; }

        $sql = "INSERT INTO ".PRFX."voucher_records SET
                voucher_code        =". $this->app->db->qStr( $this->generateVoucherCode()                      ).",
                employee_id         =". $this->app->db->qStr( $this->app->user->login_user_id           ).",
                client_id           =". $this->app->db->qStr( $invoice_details['client_id']                ).",
                workorder_id        =". $this->app->db->qStr( $invoice_details['workorder_id']             ).",
                invoice_id          =". $this->app->db->qStr( $invoice_details['invoice_id']               ).",
                expiry_date         =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($expiry_date) ).",
                status              =". $this->app->db->qStr( 'draft'                                     ).",
                opened_on           =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime(\CMSApplication::$timestamp)                             ).",
                blocked             =". $this->app->db->qStr( 1                                          ).",
                tax_system          =". $this->app->db->qStr( $invoice_details['tax_system']               ).",
                type                =". $this->app->db->qStr( $type                                        ).",
                unit_net            =". $unit_net                                               .",
                sales_tax_exempt    =". $sales_tax_exempt                                       .",
                vat_tax_code        =". $this->app->db->qStr( $vat_tax_code                                ).",
                unit_tax_rate       =". $unit_tax_rate                                          .",
                unit_tax            =". $unit_net * ($unit_tax_rate/100)                        .",
                unit_gross          =". $unit_net + ($unit_net * ($unit_tax_rate/100))          .",
                balance             =". $unit_net                                               .",
                note                =". $this->app->db->qStr( $note                                        );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $voucher_id = $this->app->db->Insert_ID();

        // Recalculate the invoice totals and update them
        $this->app->components->invoice->recalculateTotals($invoice_details['invoice_id']);

        // Log activity
        $logMessage = _gettext("Voucher").' '.$voucher_id.' '._gettext("was created by").' '.$this->app->user->login_display_name.'.';
        $recordIds = array(
                        'employee_id' => $this->app->user->login_user_id,
                        'client_id' => $invoice_details['client_id'],
                        'workorder_id' => $invoice_details['workorder_id'],
                        'invoice_id' => $invoice_details['invoice_id'],
                        'voucher_id' => $voucher_id
                        );
        $this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);
        $this->app->system->general->updateLastActive($recordIds);

        return $voucher_id;

    }

    /** Get Functions **/

    #########################################
    #     Display Vouchers                  #
    #########################################

    public function getRecords($order_by, $direction, $records_per_page = 0, $use_pages = false, $page_no = null, $search_category = 'voucher_id', $search_term = null, $status = null, $employee_id = null, $client_id = null, $workorder_id = null, $invoice_id = null, $redeemed_client_id = null, $redeemed_invoice_id = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."voucher_records.voucher_id\n";
        $havingTheseRecords = '';

        // Restrict results by search category (client) and search term
        if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (redeemed client) and search term
        elseif($search_category == 'redeemed_client_display_name') {$havingTheseRecords .= " HAVING redeemed_client_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (employee) and search term
        elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."voucher_records.$search_category LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict by Status
        if($status) {

            // All Active Vouchers
            if($status == 'active') {

                $whereTheseRecords .= " AND ".PRFX."voucher_records.blocked = 0";

            // All Blocked Vouchers
            } elseif($status == 'blocked') {

                $whereTheseRecords .= " AND ".PRFX."voucher_records.blocked = 1";

            // Return Vouchers for the given status
            } else {

                $whereTheseRecords .= " AND ".PRFX."voucher_records.status= ".$this->app->db->qStr($status);

            }

        }

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.employee_id=".$this->app->db->qStr($employee_id);}

        // Restrict by Client
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.client_id=".$this->app->db->qStr($client_id);}

        // Restrict by Workorder
        if($workorder_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.workorder_id=".$this->app->db->qStr($workorder_id);}

        // Restrict by Invoice
        if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.invoice_id=".$this->app->db->qStr($invoice_id);}

        // Restrict by Redeemed Client
        if($redeemed_client_id) {$whereTheseRecords .= " AND payment_records.client_id=".$this->app->db->qStr($redeemed_client_id);}

        // Restrict by Redeemed Invoice
        if($redeemed_invoice_id) {$whereTheseRecords .= " AND payment_records.invoice_id=".$this->app->db->qStr($redeemed_invoice_id);}

        // The SQL code
        $sql = "SELECT ".PRFX."voucher_records.*,

            IF(".PRFX."client_records.company_name !='', ".PRFX."client_records.company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,
            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name,
            redemptions

            FROM ".PRFX."voucher_records
            LEFT JOIN ".PRFX."user_records ON ".PRFX."voucher_records.employee_id = ".PRFX."user_records.user_id
            LEFT JOIN ".PRFX."client_records ON ".PRFX."voucher_records.client_id = ".PRFX."client_records.client_id
            ";

            if(!$redeemed_client_id && !$redeemed_invoice_id)
            {
                $sql .="
                    LEFT JOIN
                    (
                        SELECT ".PRFX."payment_records.voucher_id,
                        CONCAT('[',
                            GROUP_CONCAT(
                                JSON_OBJECT(
                                    'payment_id', payment_id
                                    ,'redeemed_client_id', client_id
                                    ,'redeemed_invoice_id', invoice_id
                                    ,'redeemed_on', `date`
                                    )
                                SEPARATOR ',')
                        ,']') AS redemptions
                        FROM ".PRFX."payment_records
                        GROUP BY ".PRFX."payment_records.voucher_id
                        ORDER BY ".PRFX."payment_records.voucher_id
                        ASC
                    ) AS payment_records
                    ON ".PRFX."voucher_records.voucher_id = payment_records.voucher_id
                    ";
            }
            else
            {
                $sql .="
                    RIGHT JOIN
                    (
                        SELECT
                        ".PRFX."payment_records.voucher_id,
                        ".PRFX."payment_records.client_id,
                        CONCAT('[',
                            JSON_OBJECT(
                                'payment_id', payment_id
                                ,'redeemed_client_id', client_id
                                ,'redeemed_invoice_id', invoice_id
                                ,'redeemed_on', `date`
                                )
                        ,']') AS redemptions
                        FROM ".PRFX."payment_records
                        GROUP BY ".PRFX."payment_records.voucher_id
                        ORDER BY ".PRFX."payment_records.voucher_id
                        ASC
                    ) AS payment_records
                    ON ".PRFX."voucher_records.voucher_id = payment_records.voucher_id
                    ";
            }

            $sql .="
                ".$whereTheseRecords."
                GROUP BY ".PRFX."voucher_records.".$order_by."
                ".$havingTheseRecords."
                ORDER BY ".PRFX."voucher_records.".$order_by."
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
    #  Get voucher details   #
    ##########################

    public function getRecord($voucher_id, $item = null) {

        $sql = "SELECT ".PRFX."voucher_records.*,
                redemptions

                FROM ".PRFX."voucher_records

                LEFT JOIN
                (
                    SELECT ".PRFX."payment_records.voucher_id,
                    CONCAT('[',
                        GROUP_CONCAT(
                            JSON_OBJECT(
                                'payment_id', payment_id
                                ,'redeemed_client_id', client_id
                                ,'redeemed_invoice_id', invoice_id
                                ,'redeemed_on', `date`
                                )
                            SEPARATOR ',')
                    ,']') AS redemptions
                    FROM ".PRFX."payment_records
                    GROUP BY ".PRFX."payment_records.voucher_id
                    ORDER BY ".PRFX."payment_records.voucher_id
                    ASC
                ) AS payment_records
                ON ".PRFX."voucher_records.voucher_id = payment_records.voucher_id

                WHERE ".PRFX."voucher_records.voucher_id=".$this->app->db->qStr($voucher_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!$item){

            return $rs->GetRowAssoc();

        } else {

            return $rs->fields[$item];

        }

    }

    #########################################
    #   Get voucher_id by voucher_code      #
    #########################################

    public function getIdByVoucherCode($voucher_code) {

        $sql = "SELECT * FROM ".PRFX."voucher_records WHERE voucher_code=".$this->app->db->qStr($voucher_code);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(isset($rs->fields['voucher_id'])) {
            return $rs->fields['voucher_id'];
        } else {
            return false;
        }

    }

    #####################################
    #    Get Voucher Statuses           #
    #####################################

    public function getStatuses($restricted = false, $voucher_id = null) {

        $sql = "SELECT * FROM ".PRFX."voucher_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted) {
            $sql .= "\nWHERE status_key IN ('unredeemed', 'partially_redeemed', 'suspended')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $statuses = $rs->GetArray();

        // Remove `unredeemed/partially_redeemed` - because a `suspended` records can be unredeemed or partially redeemed
        if($restricted && $voucher_id) {

            $voucher_details = $this->getRecord($voucher_id);

            // Which status to remove - the one that does NOT match the vouchers's current state
            $statusToRemove = ($voucher_details['unit_gross'] != $voucher_details['balance']) ? 'unredeemed' : 'partially_redeemed';

            // Remove relevant status from the array
            foreach($statuses as $key => $status) {
                if($status['status_key'] === $statusToRemove) {
                    unset($statuses[$key]);
                }
            }

        }

        return $statuses;

    }

    ######################################
    #  Get Voucher status display name   #
    ######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."voucher_statuses WHERE status_key=".$this->app->db->qStr($status_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];

    }

    #####################################
    #    Get Voucher Types              #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."voucher_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    ##############################################
    #   Get Invoice Voucher Sub Totals           #
    ##############################################

    public function getInvoiceVouchersSubtotals($invoice_id) {

        $sql = "SELECT
                SUM(unit_net) AS subtotal_net,
                SUM(unit_tax) AS subtotal_tax,
                SUM(unit_gross) AS subtotal_gross
                FROM ".PRFX."voucher_records
                WHERE invoice_id=". $this->app->db->qStr($invoice_id)."
                AND status NOT IN ('voided', 'deleted')";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetRowAssoc();

    }

    #####################################  // This gets the Voucher VAT Tax Code based on the company tax system or supplied tax_system
    #    Get Voucher default VAT Code   #  // not currently using '$tax_system = null'
    #####################################
    /*
     * Common
     * T9 is the correct code for any tax system that does not have VAT.
     * I am using T9 for non-british systems because then i don't have to invent a new code
     * MPV are always T9 because VAT is never a consideration at point of sale, the appropriate VAT is calculated upon sale if used.
     * SPV vouceher's VAT is calculated when the voucher is sold, hence the default code is T1 for VAT tax systems. This VAT code can be changed on the invoice.
     */

    public function getVatTaxCode($type, $tax_system = null) {

        if(!$tax_system) {$tax_system = QW_TAX_SYSTEM;}

        if($type == 'mpv') {
            if($tax_system == 'no_tax') { return 'T9'; }
            if($tax_system == 'sales_tax_cash') { return 'T9'; }
            if($tax_system == 'vat_standard') { return 'T9'; }
            if($tax_system == 'vat_cash') { return 'T9'; }
            if($tax_system == 'vat_flat_basic') { return 'T9'; }
            if($tax_system == 'vat_flat_cash') { return 'T9'; }
        }

        if($type == 'spv') {
            if($tax_system == 'no_tax') { return 'T9'; }
            if($tax_system == 'sales_tax_cash') { return 'T9'; }
            if($tax_system == 'vat_standard') { return 'T1'; }
            if($tax_system == 'vat_cash') { return 'T1'; }
            if($tax_system == 'vat_flat_basic') { return 'T1'; }
            if($tax_system == 'vat_flat_cash') { return 'T1'; }
        }

    }

    /** Update Functions **/

    #################################
    #   Update Voucher              #
    #################################

    public function updateRecord($voucher_id, $unit_net, $expiry_date, $note) {

        $voucher_details = $this->getRecord($voucher_id);

        $unit_tax_rate = $voucher_details['unit_tax_rate'];
        $unit_tax = $unit_net * ($unit_tax_rate/100);

        $sql = "UPDATE ".PRFX."voucher_records SET
                employee_id     =". $this->app->db->qStr( $this->app->user->login_user_id           ).",
                expiry_date     =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($expiry_date) ).",
                unit_net        =". $unit_net                                                .",
                unit_tax        =". $unit_tax                                                .",
                unit_gross      =". ($unit_net + $unit_tax)                                  .",
                balance         =". ($unit_net)                                              .",
                note            =". $this->app->db->qStr( $note                                        )."
                WHERE voucher_id =". $this->app->db->qStr($voucher_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Recalculate the invoice totals and update them
        $this->app->components->invoice->recalculateTotals($voucher_details['invoice_id']);

        // Log activity
        $logMessage = _gettext("Voucher").' '.$voucher_id.' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
        $recordIds = array('employee_id' => $this->app->user->login_user_id) + $voucher_details;
        $this->app->system->variables->systemMessagesWrite('success', $logMessage);
        $this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);
        $this->app->system->general->updateLastActive($recordIds);

        return;

    }

    ############################
    # Update Voucher Status    #
    ############################

    public function updateStatus($voucher_id, $new_status, $silent = false) {

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // if the new status is the same as the current one, exit
        if($new_status == $voucher_details['status']) {
            //$this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.", $silent));
            return false;
        }

        // Set appropriate redeemed_on datetime for the new status
        //$redeemed_on = ($new_status == 'redeemed') ? $this->app->system->general->mysqlDatetime(\CMSApplication::$timestamp) : null;

        // Is the new status a "closed" status
        if(in_array($new_status, array('redeemed', 'voided', 'deleted'))) {
            $closed_on = $this->app->system->general->mysqlDatetime(\CMSApplication::$timestamp);
        } else {
            $closed_on = null;
        }

        // Has the voucher been voided
        if($new_status == 'voided') {
            $voided_on = $this->app->system->general->mysqlDatetime(\CMSApplication::$timestamp);
        } else {
            $voided_on = null;
        }

        // Update voucher 'blocked' boolean for the new status ('blocked' is a way of disabling the voucher without permanently closing it, i.e. for suspended status, and is controlled by Expiry and Status)
        // If a voucher is suspended and then expires, when you change the voucher status (e.g. suspended --> unredeemed) it stays blocked.
        if(in_array($new_status, array('unredeemed', 'partially_redeemed')) && !$voucher_details['closed_on']) {
            $blocked = 0;
        } else {
            $blocked = 1;
        }

        $sql = "UPDATE ".PRFX."voucher_records SET
                employee_id        =". $this->app->db->qStr( $this->app->user->login_user_id).",
                status             =". $this->app->db->qStr( $new_status   ).",
                closed_on          =". $this->app->db->qStr( $closed_on    ).",
                voided_on          =". $this->app->db->qStr( $voided_on    ).",
                blocked            =". $this->app->db->qStr( $blocked      )."
                WHERE voucher_id   =". $this->app->db->qStr( $voucher_id   );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Status updated message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Voucher status updated.", $silent));

        // For writing message to log file, get voucher status display name
        $voucher_status_display_name = _gettext($this->getStatusDisplayName($new_status));

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($voucher_details['workorder_id'], _gettext("Voucher Status updated to").' '.$voucher_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $logMessage = _gettext("Voucher").' '.$voucher_id.' '._gettext("Status updated to").' '.$voucher_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $recordIds = array('employee_id' => $this->app->user->login_user_id) + $voucher_details;
        $this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);
        $this->app->system->general->updateLastActive($recordIds);

        return true;

    }

    ######################################### // When a voucher is redeemed against an invoice, or that voucher payment is voided or deleted,
    #   Update Voucher Balance              # // The voucher balance needs updating and the status needs recalcualting.
    ######################################### // The invoice balance will be calculated upstream separately.
                                              // Only used by PaymentMethodVoucher.php

    public function recalculateTotals($voucher_id, $amount, $action, $previous_amount = null) {

        /* Update the balance */

        $current_balance = $this->app->components->voucher->getRecord($voucher_id, 'balance');
        $new_balance = null;

        // Calculate the new balance
        switch ($action) {
            case 'new':
                $new_balance = $current_balance - $amount;
                break;
            case 'edit':
                $new_balance = ($current_balance - $previous_amount) - $amount;
                break;
            case 'void':
            case 'delete':
                $new_balance = $current_balance + $amount;
                break;
            default:
                return false;
        }

        // Update the voucher balance in the database
        $sql = "UPDATE ".PRFX."voucher_records SET
                balance             =". $this->app->db->qStr( $new_balance )."
                WHERE voucher_id    =". $this->app->db->qStr( $voucher_id );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status (as required) */

        // Get fresh record details
        $voucher_details = $this->app->components->voucher->getRecord($voucher_id);

        // Change status (based on the balance)
        switch (true) {

            // Unredeemed TODO: should this be using `unit_gross` - consider SPV or MPV
            case $voucher_details['balance'] == $voucher_details['unit_net'] :
                $this->updateStatus($voucher_id, 'unredeemed', true);
                break;

            // Partially Redeemed
            case $voucher_details['balance'] > 0 && $voucher_details['balance'] < $voucher_details['unit_net'] :
                $this->updateStatus($voucher_id, 'partially_redeemed', true);
                break;

            // Redeemed
            case $voucher_details['balance'] == 0 :
                $this->updateStatus($voucher_id, 'redeemed', true);
                break;

        }

        return;

    }

    ############################################  // This is only triggered when there is a change in an invoice's status,
    #  Invoice Totals have changed - process   #  // or when a Credit note is generated against an invoice.
    ############################################  // Update Voucher status based on the new Invoice status

    public function updateInvoiceVouchersStatuses($invoice_id, $invoice_new_status = null, $vouchers_new_status = null)
    {
        // Get Invoice Vouchers
        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Invoice Operations (this sets the correct voucher status for the invoice's new status)
        if($invoice_new_status)
        {
            switch ($invoice_new_status) {
                case 'draft':
                    $vouchers_new_status = 'draft';
                    break;
                case 'unpaid':
                    $vouchers_new_status = 'unpaid';
                    break;
                case 'partially_paid':
                    $vouchers_new_status = 'partially_paid';
                    break;
                case 'overdue':
                    $vouchers_new_status = 'suspended';
                    break;
                case 'in_dispute':
                    $vouchers_new_status = 'suspended';
                    break;
                case 'in_collections':
                    $vouchers_new_status = 'suspended';
                    break;
                case 'paid':
                    $vouchers_new_status = 'unredeemed';
                    break;
                case 'closed_with_creditnote':
                    $vouchers_new_status = 'closed_with_creditnote';
                    break;
                case 'voided':
                    $vouchers_new_status = 'voided';
                    break;
                case 'deleted':
                    $vouchers_new_status = 'deleted';
                    break;
            }
        }

        // Cycle through the vouchers
        switch ($vouchers_new_status) {

            // Close vouchers with a creditnote (creditnote:new) - records have already been checked that they allow closing ????
            case 'closed_with_creditnote':
                while(!$rs->EOF)
                {
                    $this->closeRecord($rs->fields['voucher_id']);
                    $rs->MoveNext();
                }
                break;

            // Void Vouchers (invoice:status) - records have already been checked that they allow deletion ????
            case 'voided':
                while(!$rs->EOF)
                {
                    $this->voidRecord($rs->fields['voucher_id']);
                    $rs->MoveNext();
                }
                break;

            // Delete vouchers (invoice:status) (invoice:edit) - records have already been checked that they allow deletion  ????
            case 'deleted':
                while(!$rs->EOF)
                    {
                        $this->deleteRecord($rs->fields['voucher_id']);
                        $rs->MoveNext();
                    }
                break;

            // Default Status change handler (eg draft/unpaid/partially_paid/unredeemed/suspended)
            default:
                if($vouchers_new_status){
                    while(!$rs->EOF)
                    {
                        // Update Voucher Status
                        $this->updateStatus($rs->fields['voucher_id'], $vouchers_new_status, true);

                        // Update last active record
                        $this->updateLastActive($rs->fields['voucher_id']);

                        // Advance the loop to the next record
                        $rs->MoveNext();
                    }
                }
                break;
        }

        return;

    }

    #################################
    #    Update Last Active         #
    #################################

    public function updateLastActive($voucher_id = null, $timestamp = null) {

        // Allow null calls
        if(!$voucher_id) { return; }

        $sql = "UPDATE ".PRFX."voucher_records SET
                last_active=".$this->app->db->qStr( $this->app->system->general->mysqlDatetime($timestamp) )."
                WHERE voucher_id=".$this->app->db->qStr($voucher_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    /** Close Functions **/

    ##############################  // Sets a voucher as blocked and voided
    #  Void Voucher              #  // Not currently used in status.php
    ##############################

    private function closeRecord($voucher_id) {

        $voucher_details = $this->getRecord($voucher_id);

        if(!$this->checkRecordAllowsClose($voucher_id)) {

            // Fail message
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be closed with a credit note."));

            return false;

        } else {

            // Change the voucher status
            $this->updateStatus($voucher_id, 'closed_with_creditnote', true);

            // Log activity
            $logMessage = _gettext("Voucher").' '.$voucher_id.' '._gettext("was closed with a credit note by").' '.$this->app->user->login_display_name.'.';
            $recordIds = $voucher_details;
            $this->app->system->variables->systemMessagesWrite('success', $logMessage);
            $this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);
            $this->app->system->general->updateLastActive($recordIds);

            return true;

        }

    }

    ##############################  // Sets a voucher as blocked and voided
    #  Void Voucher              #  // Not currently used in status.php
    ##############################

    private function voidRecord($voucher_id) {

        $voucher_details = $this->getRecord($voucher_id);

        if(!$this->checkRecordAllowsVoid($voucher_id)) {

            // Fail message
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be voided."));

            return false;

        } else {

            // Change the voucher status to voided (I do this here to maintain log consistency)
            $this->updateStatus($voucher_id, 'voided', true);

            // Log activity
            $logMessage = _gettext("Voucher").' '.$voucher_id.' '._gettext("was voided by").' '.$this->app->user->login_display_name.'.';
            $recordIds = $voucher_details;
            $this->app->system->variables->systemMessagesWrite('success', $logMessage);
            $this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);
            $this->app->system->general->updateLastActive($recordIds);

            return true;

        }

    }

    /** Delete Functions **/

    ##############################
    #  Delete Voucher            #  // remove some information and set blocked as you cannot really delete an issued Voucher
    ##############################  // this can be called from voucher:delete or by updateInvoiceVouchersStatuses()

    public function deleteRecord($voucher_id) {

        $voucher_details = $this->getRecord($voucher_id);

        // Truncate Main record (voucher_id and voucher_code are kept)
        $sql = "UPDATE ".PRFX."voucher_records SET
            employee_id         =   NULL,
            client_id           =   NULL,
            workorder_id        =   NULL,
            invoice_id          =   NULL,
            expiry_date         =   NULL,
            status              =   '',
            opened_on           =   NULL,
            closed_on           =   NULL,
            voided_on           =   NULL,
            last_active         =   NULL,
            blocked             =   1,
            tax_system          =   '',
            type                =   '',
            unit_net            =   0.00,
            sales_tax_exempt    =   0,
            vat_tax_code        =   '',
            unit_tax_rate       =   0.00,
            unit_tax            =   0.00,
            unit_gross          =   0.00,
            balance             =   0.00,
            note                =   ''
            WHERE voucher_id =". $this->app->db->qStr($voucher_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Change the record status to deleted
        $this->updateStatus($voucher_id, 'deleted', true);

        // Log activity
        $logMessage = _gettext("Voucher").' '.$voucher_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.';
        $recordIds = $voucher_details;
        $this->app->system->variables->systemMessagesWrite('success', $logMessage);
        $this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);
        $this->app->system->general->updateLastActive($recordIds);

        return true;

    }

    /** Check Functions **/

    ############################################
    #  Validate Expiry date                    #
    ############################################

    private function checkExpiryDateIsValid($expiry_date)
    {
        $state_flag = true;

        // Get the expiry date - Converted in to 0000-00-00, a format that will prevent incorrect calculations
        $expiry_date = new DateTime($this->app->system->general->dateToMysqlDate($expiry_date));

        // Get today's date
        $todays_date = new DateTime("now");

        // Expiry is in the past
        if($expiry_date < $todays_date) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expiry date is invalid because it is in the past."));
            $state_flag =  false;
        }

        /* Expiry is today
        if($expiry_date = $todays_date) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expiry date is invalid because it is today's date."));
            $state_flag =  false;
        }*/

        return $state_flag;

    }

    #####################################################  // This does a live check to see if the voucher is expired and tagged as such
    #   Check all vouchers to see if any have expired   #  // by default all vouchers are checked
    #####################################################  // This is not doing any tests for record checking functions

    public function checkAllVouchersForExpiry($invoice_id = null) {

        $sql = "SELECT voucher_id, status
                FROM ".PRFX."voucher_records
                ";

        if($invoice_id)
        {
            $sql .= "WHERE invoice_id=".$this->app->db->qStr($invoice_id);
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {

            $this->checkVoucherIsExpired($rs->fields['voucher_id']);

            // Advance the loop to the next record
            $rs->MoveNext();

        }

        return;

    }

    #################################################  // This does a live check to see if the voucher is expired
    #   Check to see if the voucher is expired      #  // This will close the voucher if expired
    #################################################  // This does nto change the vouchers status

    public function checkVoucherIsExpired($voucher_id) {

        $expired_status = false;
        $voucher_details = $this->getRecord($voucher_id);

        // Is the voucher deleted
        if($voucher_details['status'] == 'deleted')
        {
            $expired_status = true;
        }

        // Has the voucher expired
        elseif (time() > strtotime($voucher_details['expiry_date'].' 23:59:59'))
        {
            // Has the voucher been closed, if not update `closed_on` to match expiry date
            if(!$voucher_details['closed_on']) {

                // Update the voucher record (we dont update the status when they are expired, these are different things)
                // ('blocked' is a way of disabling the voucher without permanently closing it, i.e. for suspended status, and is controlled by Expiry and Status)
                $sql = "UPDATE ".PRFX."voucher_records SET
                    closed_on           =".$this->app->db->qstr($voucher_details['expiry_date'].' 23:59:59').",
                    blocked            =". $this->app->db->qStr(1)."
                    WHERE voucher_id    =". $this->app->db->qstr( $voucher_id          );
                if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

                // Process the Voucher for the purposes of Tax (not currently used, is for future proofing and keepoing the code separate for now)
                $this->processNewlyExpiredVoucher($voucher_id);
            }

            $expired_status = true;
        }

        // Returned the expired status
        return $expired_status;

    }

    ###########################################################
    #  Process the newly expired voucher for tax              # // This does nothing at the minute, but is an excellent placeholder for voucher tax processing
    ###########################################################

    private function processNewlyExpiredVoucher($voucher_id)
    {
        $voucher_details = $this->getRecord($voucher_id);

        // Is the voucher a SPV
        if($voucher_details['type'] == 'spv')
        {
            // No Tax system - No processing is required
            if($voucher_details['tax_system'] == 'no_tax') { return; }

            // Sales Tax system - I assume no tax is applicable
            if($voucher_details['tax_system'] == 'sales_tax_cash') { return; }

            // Any of the VAT TAX systems
            if(preg_match('/^vat_/', $voucher_details['tax_system']))
            {
                // This has already been processed at the point of sale.
                // In the case of a single-purpose voucher there is sufficient information (in terms of the place of supply and the tax treatment of the supply)
                // to tax the underlying goods or services when the voucher is issued.
                return;
            }
        }

        // Is the voucher a MPV
        if($voucher_details['type'] == 'mpv')
        {
            // No Tax system - No processing is required
            if($voucher_details['tax_system'] == 'no_tax') { return; }

            // Sales Tax system - I assume no tax is applicable
            if($voucher_details['tax_system'] == 'sales_tax_cash') { return; }

            // Any of the VAT TAX systems
            if(preg_match('/^vat_/', $voucher_details['tax_system']))
            {
                // In the case of a multi-purpose voucher it is not possible (at the time the voucher is issued or transferred) to know this information,
                // and thus the underlying goods or services are only taxed when the voucher is redeemed.
                return;
            }
        }

        return;
    }

///////////////////////////////////////////////////////////////////////
// Standard Voucher/Record standard check functions                  //
///////////////////////////////////////////////////////////////////////

    ###############################################
    #  Check if a voucher can be created          #  // Used to hide `Add Voucher` button on invoice:edit
    ###############################################

    public function checkRecordCanBeCreated($invoice_id,  $silent = false){

        $state_flag = true;

        $invoice_details = $this->app->components->invoice->getRecord($invoice_id);

        // Is the Client active
        if(!$this->app->components->client->getRecord($invoice_details['client_id'], 'active'))
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The specified client is not active so you cannot create a voucher against this invoice.", $silent));
            $state_flag = false;
        }

        // Check Parent Invoice Status
        switch($invoice_details['status']) {
            case 'draft':
                break;
            case 'unpaid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to an approved invoice."), $silent);
                $state_flag = false;
                break;
            case 'partially_paid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to a partially paid invoice."), $silent);
                $state_flag = false;
                break;
            case 'overdue':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to an approved invoice."), $silent);
                $state_flag = false;
                break;
            case 'in_dispute':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to an approved invoice."), $silent);
                $state_flag = false;
                break;
            case 'in_collections':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to an approved invoice."), $silent);
                $state_flag = false;
                break;
            case 'paid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to a paid invoice."), $silent);
                $state_flag = false;
                break;
            case 'closed_with_creditnote':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to a invoice closed with a credit note."), $silent);
                $state_flag = false;
                break;
            case 'voided':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to a voided invoice."), $silent);
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add vouchers to a deleted invoice."), $silent);
                $state_flag = false;
                break;
        }

        return $state_flag;

    }

    #############################################################
    # Validate submitted information before allowing submission #
    #############################################################

    public function checkRecordSubmissionIsValid($qform){

        $state_flag = true;

        // Check the expiry date (messages handled in the function)
        if(!$this->checkExpiryDateIsValid($qform['expiry_date'])){
            $state_flag = false;
        }

        // Add Submission Failed Validation message
        if(!$state_flag){
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher submission failed validation and was not committed to the database. Fix and re-submit."));
        }

        return $state_flag;

    }



    /* These functions: check the parent invoice status and it's vouchers for their statuses before making a descision about the specific voucher */

    ###########################################################  // used by invoice manual status change routine
    #  Check if the voucher status is allowed to be changed   #  // used on voucher:status
    ###########################################################  // can only swap between `unredeemed` and 'suspended`, and parent invoice must be paid

    public function checkRecordAllowsManualStatusChange($voucher_id, $checkParentInvoice = true, $silent = false) {

        // Disable this feature for now. I may enable or remove in future versions.
        $this->app->system->variables->systemMessagesWrite('warning', _gettext("The voucher cannot have it's status manually changed at this time because the feature is not available in this version of QWcrm."), $silent);
        return false;

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {

            // This prevents vouchers (or parent invoice) being stuck on the suspended status because of an expired voucher
            if($this->getRecord($voucher_id, ['status']) == 'suspended'){
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it has expired.", $silent));
                $state_flag = false;
            } else {
                $this->app->system->variables->systemMessagesWrite('warning', _gettext("This voucher has expired, but you can still unsuspend it.", $silent));
            }
        }

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);

        /*  Check the specified voucher record allows change */

        // Is the Client active
        if(!$this->app->components->client->getRecord($voucher_details['client_id'], 'active'))
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because the client it belongs to is not active.", $silent));
            $state_flag = false;
        }

        /* Is the voucher closed (This should not be needed because of expiry and status checks)
        if($voucher_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it has been closed.", $silent));
        }*/

        // Check Voucher Status
        switch ($voucher_details['status'])
        {
            case 'draft':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it is a draft."), $silent);
                $state_flag = false;
                break;
            case 'unpaid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it is unpaid."), $silent);
                $state_flag = false;
                break;
            case 'partially_paid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it partially paid."), $silent);
                $state_flag = false;
                break;
            case 'unredeemed':
                break;
            case 'partially_redeemed':
                break;
            case 'redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it has been redeemed."), $silent);
                $state_flag = false;
                break;
            case 'closed_with_creditnote':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it has been closed with a credit note."), $silent);
                $state_flag = false;
                break;
            case 'suspended':
                break;
            case 'voided':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it has been voided."), $silent);
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it has been deleted."), $silent);
                $state_flag = false;
                break;
        }

        /*  Check to see if the parent invoice allows manually changing of it's vouchers status. */

        if($checkParentInvoice)
        {
            // Get the invoice details
            $invoice_details = $this->app->components->invoice->getRecord($voucher_details['invoice_id']);

            // Is on a different tax system
            if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice is on a different Tax system.", $silent));
                $state_flag = false;
            }

            // Check Parent Invoice Status
            switch ($invoice_details['status'])
            {
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice is a draft."), $silent);
                    $state_flag = false;
                    break;
                case 'unpaid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'partially_paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been partially paid."), $silent);
                    $state_flag = false;
                    break;
                case 'overdue':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'in_dispute':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'in_collections':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been paid."), $silent);
                    $state_flag = false;
                    break;
                case 'closed_with_creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been closed with a credit note."), $silent);
                    $state_flag = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been voided."), $silent);
                    $state_flag = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot have it's status changed because it's parent invoice has been deleted."), $silent);
                    $state_flag = false;
                    break;
            }
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the voucher can be edited                 #
    ###############################################################  // used by invoice edit routine

    public function checkRecordAllowsEdit($voucher_id, $checkParentInvoice = true, $silent = false) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has expired.", $silent));
            $state_flag = false;
        }

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);

        /* Check the specified voucher record allows edit */

        // Is the Client active
        if(!$this->app->components->client->getRecord($voucher_details['client_id'], 'active'))
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because the client it belongs to is not active.", $silent));
            $state_flag = false;
        }

        // Is on a different tax system
        if($voucher_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it is on a different Tax system.", $silent));
            $state_flag = false;
        }

        // Is the record's VAT code is enabled
        if(!$this->app->components->company->getVatTaxCodeStatus($voucher_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's current VAT Tax Code is not enabled.", $silent));
            $state_flag = false;
        }

        /* Is the voucher closed (This should not be needed because of expiry and status checks)
        if($voucher_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has been closed.", $silent));
        }*/

        // Check Voucher Status
        switch ($voucher_details['status'])
        {
            case 'draft':
                break;
            case 'unpaid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has been approved."), $silent);
                $state_flag = false;
                break;
            case 'partially_paid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has been partially paid."), $silent);
                $state_flag = false;
                break;
            case 'unredeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it is currently unredeemed."), $silent);
                $state_flag = false;
                break;
            case 'partially_redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has been partially redeemed."), $silent);
                $state_flag = false;
                break;
            case 'redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has been redeemed."), $silent);
                $state_flag = false;
                break;
            case 'closed_with_creditnote':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has been closed with a credit note."), $silent);
                $state_flag = false;
                break;
            case 'suspended':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it is suspended."), $silent);
                $state_flag = false;
                break;
            case 'voided':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has been voided."), $silent);
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it has been deleted."), $silent);
                $state_flag = false;
                break;
        }

        /* Check to see if the parent invoice allows editing of it's vouchers */

        if($checkParentInvoice)
        {
            // Get the invoice details
            $invoice_details = $this->app->components->invoice->getRecord($voucher_details['invoice_id']);

            // Is on a different tax system
            if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice is on a different Tax system.", $silent));
                $state_flag = false;
            }

            // Check Parent Invoice Status
            switch ($invoice_details['status'])
            {
                case 'draft':
                    break;
                case 'unpaid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'partially_paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice is partially paid."), $silent);
                    $state_flag = false;
                    break;
                case 'overdue':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'in_dispute':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'in_collections':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice is paid."), $silent);
                    $state_flag = false;
                    break;
                case 'closed_with_creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice has been closed with a credit note."), $silent);
                    $state_flag = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice has been voided."), $silent);
                    $state_flag = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's parent invoice has been deleted."), $silent);
                    $state_flag = false;
                    break;
            }
        }

        return $state_flag;

    }

    ###############################################################  // Checks if an individual voucher can be closed with a creditnote checking it's siblings and parent invoice.
    #   Check to see a voucher can be closed with a credit note   #  // Used by invoice voiding routine when you generate a CR
    ###############################################################

    private function checkRecordAllowsClose($voucher_id, $checkParentInvoice = true, $silent = false) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a creditnote because it has expired.", $silent));
            $state_flag = false;
        }

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);

        /* Check the specified voucher record allows void */

        // Is the Client active
        if(!$this->app->components->client->getRecord($voucher_details['client_id'], 'active'))
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be closed with a creditnote because the client it belongs to is not active.", $silent));
            $state_flag = false;
        }

        /* Is the voucher closed (This should not be needed because of expiry and status checks)
        if($voucher_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has been closed.", $silent));
        }*/

        // Check Voucher Status
        switch ($voucher_details['status'])
        {
            case 'draft':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it is a draft."), $silent);
                $state_flag = false;
                break;
            case 'unpaid':
            case 'partially_paid':
                break;
            case 'unredeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it is uredeemed."), $silent);
                $state_flag = false;
                break;
            case 'partially_redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it has been partially redeemed."), $silent);
                $state_flag = false;
                break;
            case 'redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it has been redeemed."), $silent);
                $state_flag = false;
                break;
            case 'closed_with_creditnote':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it has already been closed with a creditnote."), $silent);
                $state_flag = false;
                break;
            case 'suspended':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it is suspended."), $silent);
                $state_flag = false;
                break;
            case 'voided':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it has been voided."), $silent);
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it has been deleted."), $silent);
                $state_flag = false;
                break;
        }

        /* Check to see if the parent invoice allows voiding of it's vouchers */

        if($checkParentInvoice)
        {
            // Get the invoice details
            $invoice_details = $this->app->components->invoice->getRecord($voucher_details['invoice_id']);

            // Is on a different tax system
            if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a creditnote because it's parent invoice is on a different Tax system.", $silent));
                $state_flag = false;
            }

            // Check Parent Invoice Status
            switch ($invoice_details['status'])
            {
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it's parent invoice is a draft."), $silent);
                    $state_flag = false;
                    break;
                case 'unpaid':
                case 'partially_paid':
                    break;
                case 'overdue':
                case 'in_dispute':
                case 'in_collections':
                    // If the invoice has payments don't allow closing
                    if($invoice_details['unit_gross'] != $invoice_details['balance']) {
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because the parent invoice status does not allow it."), $silent);
                        $state_flag = false;
                    }
                    break;
                case 'paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because the parent invoice has been paid."), $silent);
                    $state_flag = false;
                    break;
                case 'closed_with_creditnote':
                    // This allows closing a voucher just after the invoice status has been updated to `closed_with_creditnote` and prevent errors
                    if($voucher_details['status'] == 'closed_with_creditnote') {
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because it has already been closed with a creditnote by the parent invoice."), $silent);
                        $state_flag = false;
                    }
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because the parent invoice has been voided."), $silent);
                    $state_flag = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be closed with a credit note because the parent invoice has been deleted."), $silent);
                    $state_flag = false;
                    break;
            }
        }

        return $state_flag;

    }

    ###############################################################  // Checks if an individual voucher can be voided by checking it's siblings and parent invoice.
    #   Check to see a voucher can be voided                      #  // Used by invoice voiding routine when you generate a CR
    ###############################################################

    private function checkRecordAllowsVoid($voucher_id, $checkParentInvoice = true, $silent = false) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has expired.", $silent));
            $state_flag = false;
        }

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);

        /* Check the specified voucher record allows void */

        // Is the Client active
        if(!$this->app->components->client->getRecord($voucher_details['client_id'], 'active'))
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be voided because the client it belongs to is not active.", $silent));
            $state_flag = false;
        }

        /* Is the voucher closed (This should not be needed because of expiry and status checks)
        if($voucher_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has been closed.", $silent));
        }*/

        // Check Voucher Status
        switch ($voucher_details['status'])
        {
            case 'draft':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it is a draft."), $silent);
                $state_flag = false;
                break;
            case 'unpaid':
                break;
            case 'partially_paid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has been partially paid."), $silent);
                $state_flag = false;
                break;
            case 'unredeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it is uredeemed."), $silent);
                $state_flag = false;
                break;
            case 'partially_redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has been partially redeemed."), $silent);
                $state_flag = false;
                break;
            case 'redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has been redeemed."), $silent);
                $state_flag = false;
                break;
            case 'closed_with_creditnote':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has been closed with a credit note."), $silent);
                $state_flag = false;
                break;
            case 'suspended':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it is suspended."), $silent);
                $state_flag = false;
                break;
            case 'voided':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has already been voided."), $silent);
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it has been deleted."), $silent);
                $state_flag = false;
                break;
        }

        /* Check to see if the parent invoice allows voiding of it's vouchers */

        if($checkParentInvoice)
        {
            // Get the invoice details
            $invoice_details = $this->app->components->invoice->getRecord($voucher_details['invoice_id']);

            // Is on a different tax system
            if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it's parent invoice is on a different Tax system.", $silent));
                $state_flag = false;
            }

            // Check Parent Invoice Status
            switch ($invoice_details['status'])
            {
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because it's parent invoice is a draft."), $silent);
                    $state_flag = false;
                    break;
                case 'unpaid':
                    break;
                case 'partially_paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because the parent invoice is partially paid."), $silent);
                    $state_flag = false;
                    break;
                case 'overdue':
                case 'in_dispute':
                case 'in_collections':
                    // If the invoice has payments don't allow voiding
                    if($invoice_details['unit_gross'] != $invoice_details['balance']) {
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because the parent invoice has payments against it."), $silent);
                        $state_flag = false;
                    }
                    break;
                case 'paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because the parent invoice has been paid."), $silent);
                    $state_flag = false;
                    break;
                case 'closed_with_creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because the parent invoice has been closed with a credit note."), $silent);
                    $state_flag = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because the parent invoice has been voided."), $silent);
                    $state_flag = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be voided because the parent invoice has been deleted."), $silent);
                    $state_flag = false;
                    break;
            }
        }

        return $state_flag;

    }

    ############################################################### // used by invoice deletion routine
    #   Check to see if the voucher can be deleted                #
    ###############################################################

    public function checkRecordAllowsDelete($voucher_id, $checkParentInvoice = true, $silent = false) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it has expired.", $silent));
            $state_flag = false;
        }

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);

        /* Check the specified voucher record allows delete */

        // Is the Client active
        if(!$this->app->components->client->getRecord($voucher_details['client_id'], 'active'))
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because the client it belongs to is not active.", $silent));
            $state_flag = false;
        }

        /* Is the voucher closed (This should not be needed because of expiry and status checks)
        if($voucher_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been closed.", $silent));
        }*/

        // Check Voucher Status
        switch ($voucher_details['status'])
        {
            case 'draft':
                break;
            case 'unpaid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it is unpaid."), $silent);
                $state_flag = false;
                break;
            case 'partially_paid':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it has been partially paid."), $silent);
                $state_flag = false;
                break;
            case 'unredeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it is unredeemed."), $silent);
                $state_flag = false;
                break;
            case 'partially_redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it has been partially redeemed."), $silent);
                $state_flag = false;
                break;
            case 'redeemed':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it has been redeemed."), $silent);
                $state_flag = false;
                break;
            case 'closed_with_creditnote':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it has been closed with a credit note."), $silent);
                $state_flag = false;
                break;
            case 'suspended':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it issuspended."), $silent);
                $state_flag = false;
                break;
            case 'voided':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it has been voided."), $silent);
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it has already been deleted."), $silent);
                $state_flag = false;
                break;
        }

        /* Check to see if the parent invoice allows deleting of it's vouchers */

        if($checkParentInvoice)
        {
            // Get the invoice details
            $invoice_details = $this->app->components->invoice->getRecord($voucher_details['invoice_id']);

            // Is on a different tax system
            if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because the parent invoice is on a different Tax system.", $silent));
                $state_flag = false;
            }

            // Check Parent Invoice Status
            switch ($invoice_details['status'])
            {
                case 'draft':
                    break;
                case 'unpaid':
                    break;
                case 'partially_paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's parent invoice is partially paid."), $silent);
                    $state_flag = false;
                    break;
                case 'overdue':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'in_dispute':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'in_collections':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's parent invoice has been approved."), $silent);
                    $state_flag = false;
                    break;
                case 'paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's parent invoice is paid."), $silent);
                    $state_flag = false;
                    break;
                case 'closed_with_creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's parent invoice has been closed with a credit note."), $silent);
                    $state_flag = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's parent invoice has been voided."), $silent);
                    $state_flag = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's parent invoice has been deleted."), $silent);
                    $state_flag = false;
                    break;
            }
        }

        return $state_flag;

    }


///////////////////////////////////////////////////////////////////////
// check all vouchers on an invoice to make sure they all allow the invoice operation (edit/void/delete)
///////////////////////////////////////////////////////////////////////


    ############################################################################
    # Check an invoice's vouchers do not prevent the invoice getting edited    #
    ############################################################################

    public function checkAllInvoiceSiblingVouchersAllowEdit($invoice_id) {

        $state_flag = true;
        $blockingVouchers = '';

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {

            // Check the Voucher to see if it can be Edited
            if(!$this->checkRecordAllowsEdit($rs->fields['voucher_id'], false, true)) {
                $blockingVouchers .= $rs->fields['voucher_id'].',';
                $state_flag = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();

        }

        if(!$state_flag) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because of Voucher(s)").': '.rtrim($blockingVouchers, ',').'.');
        }

        return $state_flag;

    }

    ############################################################################  // This is different because the invoice status is not changed, is stays as closed
    # Check an invoices vouchers allow closingwiht a creditnote                #  // Used by invoice voiding routine when you generate a CR
    ############################################################################

    public function checkAllInvoiceSiblingVouchersAllowClose($invoice_id) {

        $state_flag = true;
        $blockingVouchers = '';

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {

            // Check the Voucher to see if it can be Closed with a creditnote
            if(!$this->checkRecordAllowsClose($rs->fields['voucher_id'], false, true)) {
                $blockingVouchers .= $rs->fields['voucher_id'].',';
                $state_flag = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();

        }

        if(!$state_flag) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be closed with a credit note because of Voucher(s)").': '.rtrim($blockingVouchers, ',').'.');
        }

        return $state_flag;

    }

    ############################################################################  // This is different because the invoice status is not changed, is stays as closed
    # Check an invoices vouchers allow voiding                                 #  // Used by invoice voiding routine when you generate a CR
    ############################################################################

    public function checkAllInvoiceSiblingVouchersAllowVoid($invoice_id) {

        $state_flag = true;
        $blockingVouchers = '';

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {

            // Check the Voucher to see if it can be Voided
            if(!$this->checkRecordAllowsVoid($rs->fields['voucher_id'], false, true)) {
                $blockingVouchers .= $rs->fields['voucher_id'].',';
                $state_flag = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();

        }

        if(!$state_flag) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be voided because of Voucher(s)").': '.rtrim($blockingVouchers, ',').'.');
        }

        return $state_flag;

    }

    ###########################################################################
    # Check an invoice's vouchers do not prevent the invoice getting deleted  #
    ###########################################################################

    public function checkAllInvoiceSiblingVouchersAllowDelete($invoice_id) {

        $state_flag = true;
        $blockingVouchers = '';

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Check all of the Vouchers to see if they can be deleted
        while(!$rs->EOF) {

            if(!$this->checkRecordAllowsDelete($rs->fields['voucher_id'], false, true)) {
                $blockingVouchers .= $rs->fields['voucher_id'].',';
                $state_flag = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();

        }

        if(!$state_flag) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because of Voucher(s)").': '.rtrim($blockingVouchers, ',').'.');
        }

        return $state_flag;

    }





/*
///////////////////////////////////////////////////////////////////////
// Does a single voucher prevent the change/editing/cancel/delete of a parent invoice  - this section might be old code and is not same format as above
///////////////////////////////////////////////////////////////////////  -- this is also pre-removing cancel

- These additional tests would assume the vouchers can become out of sync with their invoices and this is not allowed.
- vouchers are in sync with their invoice until unredeemed at which point they can diverege, intentionally.

    #######################################################################
    #   Check to see if the voucher status allows invoice Editing         #
    #######################################################################

    private function checkSingleVoucherAllowsInvoiceEdit($voucher_id) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has expired."));
            $state_flag = false;
        }

        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Is on a different tax system
        if($voucher_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher is on a different Tax system."));
            $state_flag = false;
        }

        // Is the record's VAT code is enabled
        if(!$this->app->components->company->getVatTaxCodeStatus($voucher_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher's current VAT Tax Code is not enabled."));
            $state_flag = false;
        }

        // Is Draft
        if($voucher_details['status'] == 'draft') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher is draft."));
            $state_flag = false;
        }

        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
        }

        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been partially paid."));
            $state_flag = false;
        }

        // Is Unredeemed
        if($voucher_details['status'] == 'unredeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been paid."));
            $state_flag = false;
        }

        // Is Partially Redeemed
        if($voucher_details['status'] == 'partially_redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been partially redeemed."));
            $state_flag = false;
        }

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been redeemed."));
            $state_flag = false;
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been suspended."));
            $state_flag = false;
        }

        // Is Voided
        if($voucher_details['status'] == 'voided') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been voided."));
            $state_flag = false;
        }

        // Is Deleted (this should not be needed)
        if($voucher_details['status'] == 'deleted') {
            $state_flag = false;
        }

        return $state_flag;

    }

    #######################################################################
    #   Check to see if the voucher status allows invoice cancelling      #
    #######################################################################

    private function checkSingleVoucherAllowsInvoiceCancel($voucher_id) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has expired."));
            $state_flag = false;
        }

        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Is Draft
        if($voucher_details['status'] == 'draft') {
        }

        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
        }

        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been partially paid."));
            $state_flag = false;
        }

        // Is Unredeemed
        if($voucher_details['status'] == 'unredeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been paid."));
            $state_flag = false;
        }

        // Is Partially Redeemed
        if($voucher_details['status'] == 'partially_redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been partially redeemed."));
            $state_flag = false;
        }

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been redeemed."));
            $state_flag = false;
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been suspended."));
            $state_flag = false;
        }

        // Is Voided
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been voided."));
            $state_flag = false;
        }

        // Is Deleted (should not be needed)
        if($voucher_details['status'] == 'deleted') {
            $state_flag = false;
        }

        return $state_flag;

    }

    #######################################################################
    #   Check to see if the voucher status allows invoice Deleting        #
    #######################################################################

    private function checkSingleVoucherAllowsInvoiceDelete($voucher_id) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has expired."));
            $state_flag = false;
        }

        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Is Draft
        if($voucher_details['status'] == 'draft') {
        }

        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
        }

        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been partially paid."));
            $state_flag = false;
        }

        // Is Unredeemed
        if($voucher_details['status'] == 'unredeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been paid."));
            $state_flag = false;
        }

        // Is Partially Redeemed
        if($voucher_details['status'] == 'partially_redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been partially redeemed."));
            $state_flag = false;
        }

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been redeemed."));
            $state_flag = false;
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been suspended."));
            $state_flag = false;
        }

        // Is Voided
        if($voucher_details['status'] == 'voided') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been voided."));
            $state_flag = false;
        }

        // Is Deleted (should not be needed)
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has already been deleted."));
            $state_flag = false;
        }

        return $state_flag;

    }
*/


    /** Other Functions **/

    ############################################
    #  Generate Random Voucher code            #
    ############################################

    public function generateVoucherCode() {

        $acceptedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $max_offset = strlen($acceptedChars)-1;
        $voucher_code = '';

        for($i=0; $i < 16; $i++) {
            $voucher_code .= $acceptedChars[mt_rand(0, $max_offset)];
        }

        return $voucher_code;

    }

}
