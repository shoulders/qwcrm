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
                status              =". $this->app->db->qStr( 'unpaid'                                     ).",
                opened_on           =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime()                             ).",
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
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was created by").' '.$this->app->user->login_display_name.'.';      
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $invoice_details['client_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($invoice_details['client_id']);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id']);
        $this->app->components->invoice->updateLastActive($invoice_details['invoice_id']);       

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

        if($item === null){

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

    public function getStatuses($restrict_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."voucher_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restrict_statuses) {
            $sql .= "\nWHERE status_key IN ('paid_unused', 'suspended')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray(); 

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
    #   Get Invoice Voucher  Sub Totals          #  // All statuses should be summed up, deleted vouchers do not have an invoice_id anyway so are ignored and cancelled vouchers only exist on cancelled invoices.
    ##############################################

    public function getInvoiceVouchersSubtotals($invoice_id) {

        $sql = "SELECT
                SUM(unit_net) AS subtotal_net,
                SUM(unit_tax) AS subtotal_tax,
                SUM(unit_gross) AS subtotal_gross            
                FROM ".PRFX."voucher_records
                WHERE invoice_id=". $this->app->db->qStr($invoice_id);

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
                
        $unit_tax_rate = $this->getRecord($voucher_id, 'unit_tax_rate');
        $unit_tax = $unit_net * ($unit_tax_rate/100);    

        $sql = "UPDATE ".PRFX."voucher_records SET     
                employee_id     =". $this->app->db->qStr( $this->app->user->login_user_id           ).",
                expiry_date     =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($expiry_date) ).",            
                unit_net        =". $unit_net                                                .",
                unit_tax        =". $unit_tax                                                .",
                unit_gross      =". ($unit_net + $unit_tax)                                  .",
                balance         =". ($unit_net)                                              .",
                last_active     =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime()                             ).",  
                note            =". $this->app->db->qStr( $note                                        )."
                WHERE voucher_id =". $this->app->db->qStr($voucher_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Refresh voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Recalculate the invoice totals and update them
        $this->app->components->invoice->recalculateTotals($voucher_details['invoice_id']);

        // Log activity
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $voucher_details['employee_id'], $voucher_details['client_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($voucher_details['client_id']);
        $this->app->components->workorder->updateLastActive($voucher_details['workorder_id']);
        $this->app->components->invoice->updateLastActive($voucher_details['invoice_id']);

        return;

    }

    ############################
    # Update Voucher Status    #
    ############################

    public function updateStatus($voucher_id, $new_status, $silent = false) {

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Unify Dates and Times
        $datetime = $this->app->system->general->mysqlDatetime();

        // if the new status is the same as the current one, exit
        if($new_status == $voucher_details['status']) {        
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }  

        // Set appropriate redeemed_on datetime for the new status
        //$redeemed_on = ($new_status == 'redeemed') ? $datetime : null;

        // Update voucher 'closed_on' boolean for the new status
        if(in_array($new_status, array('redeemed', 'expired_unused', 'cancelled'))) {
            $closed_on = $datetime;
        } else {
            $closed_on = null;
        }

        // Update voucher 'blocked' boolean for the new status
        if(in_array($new_status, array('paid_unused', 'partially_redeemed'))) {
            $blocked = 0;
        } else {
            $blocked = 1;
        }

        $sql = "UPDATE ".PRFX."voucher_records SET
                status             =". $this->app->db->qStr( $new_status   ).",                
                closed_on          =". $this->app->db->qStr( $closed_on    ).",
                last_active        =". $this->app->db->qStr( $datetime     ).",
                blocked            =". $this->app->db->qStr( $blocked      )."
                WHERE voucher_id   =". $this->app->db->qStr( $voucher_id   ); 

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}   

        // Status updated message
        if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("Voucher status updated.")); }

        // For writing message to log file, get voucher status display name
        $voucher_status_display_name = _gettext($this->getStatusDisplayName($new_status));

        // Create a Workorder History Note       
        $this->app->components->workorder->insertHistory($voucher_details['workorder_id'], _gettext("Voucher Status updated to").' '.$voucher_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("Status updated to").' '.$voucher_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_id);

        // Update last active record
        $this->app->components->client->updateLastActive($voucher_details['client_id']);
        $this->app->components->workorder->updateLastActive($voucher_details['workorder_id']);
        $this->app->components->invoice->updateLastActive($voucher_details['invoice_id']);                

        return true;        

    }
    
    ######################################### // i dont have to load voucher details twice, but it makes logic easier to understand
    #   Update Voucher Balance              # // when a voucher is redeemed against an invoice, or that payment is cancel or deleted, the balance needs updating and the status needs recalcualting
    ######################################### // only change status if required                                         

    public function recalculateTotals($voucher_id, $amount, $action, $previous_amount = null) {
        
        /* Update the balance */
        
        $current_balance = $this->app->components->voucher->getRecord($voucher_id, 'balance');
        $new_balance = null;
        
        // Calculate the new balance
        if($action === 'new')
        {
            $new_balance = $current_balance - $amount;   
        }
        elseif($action === 'edit')
        {
            $new_balance = ($current_balance - $previous_amount) - $amount;
            
        }        
        elseif($action === 'cancel' || $action === 'delete')
        {
            $new_balance = $current_balance + $amount;
        }            
        else
        {
            return false;
        }        
        
        // Update the balance in the database
        $sql = "UPDATE ".PRFX."voucher_records SET                               
                balance             =". $this->app->db->qStr( $new_balance )."                          
                WHERE voucher_id    =". $this->app->db->qStr( $voucher_id );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
        
        /* Update the Status */
        
        // Get fresh Voucher details
        $voucher_details = $this->app->components->voucher->getRecord($voucher_id);

        // Balance is zero
        if($voucher_details['balance'] == 0 && $voucher_details['status'] != 'redeemed')
        {
            $this->updateStatus($voucher_id, 'redeemed');
        }
        
        // Balance Partially used
        if(($voucher_details['balance'] > 0 && $voucher_details['balance'] != $voucher_details['unit_net']) && $voucher_details['status'] != 'redeemed')
        {
            $this->updateStatus($voucher_id, 'partially_redeemed');
        }
        
        // Balance is unused
        if($voucher_details['balance'] == $voucher_details['unit_net'] && $voucher_details['status'] != 'paid_unused')
        {
            $this->updateStatus($voucher_id, 'paid_unused');
        }

        return;
    }
    
    ############################################
    #  Invoice Totals have changed - process   #  // when i update an invoice and the totals are recalculated, the vouchers need their status recalculating and setting
    ############################################  // This is only triggered when there is a change in invoice status
    
    public function updateInvoiceVouchersStatuses($invoice_id, $invoice_new_status)
    {
        $vouchers_new_status = null;
        $invoice_details = $this->app->components->invoice->getRecord($invoice_id);

        // Get Invoice Vouchers (other method)
        //$invoice_vouchers_details = $this->app->components->voucher->getRecords('voucher_id', 'DESC', 0, false, null, null, null, null, null, null, null, $invoice_id);
        
        // Get Invoice Vouchers
        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Is Pending
        if($invoice_details['status'] == 'pending') {
            $vouchers_new_status = 'unpaid';  
        }
        
        // Is Unpaid
        if($invoice_new_status == 'unpaid') {
            $vouchers_new_status = 'unpaid';     
        }
        
        // Is partially paid
        if($invoice_new_status == 'partially_paid') {
            $vouchers_new_status = 'partially_paid';       
        }

        // Is Paid
        if($invoice_new_status == 'paid') {
            $vouchers_new_status = 'paid_unused';     
        }
        
        // In Dispute
        if($invoice_new_status == 'in_dispute') {
            $vouchers_new_status = 'unpaid';     
        }
        
        // Is Overdue
        if($invoice_new_status == 'overdue') {
            $vouchers_new_status = 'unpaid';      
        }
        
        // In Collections
        if($invoice_new_status == 'collections') {
            $vouchers_new_status = 'unpaid';    
        }

        // Is Cancelled (this happens when you cancel the invoice) - from cancelInvoiceVouchers()
        if($invoice_new_status == 'cancelled') {
            
            while(!$rs->EOF)
            {
                // Cancel Voucher
                $this->cancelRecord($rs->fields['voucher_id']);

                // Advance the loop to the next record
                $rs->MoveNext();
            }

            return;        
        }

        // Is Deleted (this happens when you delete the invoice) - from deleteInvoiceVouchers()
        if($invoice_new_status == 'deleted') {

            while(!$rs->EOF)
            {
                // Delete Voucher
                $this->deleteRecord($rs->fields['voucher_id']);

                // Advance the loop to the next record
                $rs->MoveNext();
            }

            return;     
        }

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'invoice', null, null, null, null, $invoice_id)) {         
            $vouchers_new_status = '';      
        }*/
        
        // Default Status change handler - this is when the vouchers have not been processed above with special routines but still need status changing
        if($vouchers_new_status)
        {
            while(!$rs->EOF)
            {
                // Update Voucher Status
                $this->updateStatus($rs->fields['voucher_id'], $vouchers_new_status, true);
                        
                // Advance the loop to the next record
                $rs->MoveNext();
            }

            return;
        }
        
    }    

    /** Close Functions **/
        
    ##############################
    #  Cancel Voucher            #  // update and set blocked as you cannot really delete an issued Voucher  
    ##############################  // This can only be done when you cancel an invoice - updateInvoiceVouchersStatuses()

    private function cancelRecord($voucher_id) {     

        $voucher_details = $this->getRecord($voucher_id);    

        if(!$this->checkSingleVoucherAllowsCancel($voucher_id)) {

            // Load the relevant invoice page with failed message
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be cancelled."));
            $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$voucher_details['invoice_id']);

        } else {

            // Change the voucher status to cancelled (I do this here to maintain log consistency)
            $this->updateStatus($voucher_id, 'cancelled', true);

            // Log activity        
            $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->writeRecordToActivityLog($record, $voucher_details['employee_id'], $voucher_details['client_id']);

            // Update last active record
            $this->app->components->client->updateLastActive($voucher_details['client_id']);
            $this->app->components->workorder->updateLastActive($voucher_details['workorder_id']);
            $this->app->components->invoice->updateLastActive($voucher_details['invoice_id']);

            return true;        

        }

    }

    /** Delete Functions **/
    
    ##############################
    #  Delete Voucher            #  // remove some information and set blocked as you cannot really delete an issued Voucher  
    ##############################  // this can be called from voucher:delete or by updateInvoiceVouchersStatuses()

    public function deleteRecord($voucher_id) {     

        $voucher_details = $this->getRecord($voucher_id);    

        if(!$this->checkSingleVoucherAllowsDelete($voucher_id)) {

            // Load the relevant invoice page with failed message
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be deleted."));
            $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$voucher_details['invoice_id']);

        } else {

            // Change the voucher status to deleted (I do this here to maintain log consistency)
            $this->updateStatus($voucher_id, 'deleted', true);

            $sql = "UPDATE ".PRFX."voucher_records SET
                voucher_code       =". $this->app->db->qStr( $voucher_details['voucher_code']   ).",
                employee_id         =   NULL,
                client_id           =   NULL,
                workorder_id        =   NULL,
                invoice_id          =   NULL,                               
                expiry_date         =   NULL,
                status              =   'deleted',
                opened_on           =   NULL,                
                closed_on           =   NULL,
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

            // Log activity        
            $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.';
            $this->app->system->general->writeRecordToActivityLog($record, $voucher_details['employee_id'], $voucher_details['client_id']);

            // Update last active record
            $this->app->components->client->updateLastActive($voucher_details['client_id']);
            $this->app->components->workorder->updateLastActive($voucher_details['workorder_id']);
            $this->app->components->invoice->updateLastActive($voucher_details['invoice_id']);

            return true;

        }

    }
    
    /** Check Functions **/

    #####################################################
    #   Check all vouchers to see if any have expired   #   // This does a live check to see if the voucher is expired and tagged as such
    #####################################################   // by default all vouchers are checked

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
    
    ################################################# // This does a live check to see if the voucher is expired
    #   Check to see if the voucher is expired      # // This function will update the voucher status as required
    ################################################# // This will update the voucher for the purposes of tax and profit if newly expired
                                                      // This will all closed as expired, which is the same really
    public function checkVoucherIsExpired($voucher_id) {

        $expired_status = false;

        $voucher_details = $this->getRecord($voucher_id);
        
        // Is the voucher deleted
        if($voucher_details['status'] === 'deleted')
        {
            $expired_status = true; 
        }
        
        // Has the voucher been closed already (same effect as expired)
        elseif($voucher_details['closed_on'])
        {
            $expired_status = true;          
        }

        // Has the voucher just expired and needs to be updated
        elseif (time() > strtotime($voucher_details['expiry_date'].' 23:59:59'))
        {            
            $expired_status = true;
            
            // Update the voucher record
            $sql = "UPDATE ".PRFX."voucher_records SET                
                closed_on           =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime())."
                WHERE voucher_id    =". $this->app->db->qstr( $voucher_id          );
            if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);} 
            
            // Update the voucher status (silenty)
            if ($voucher_details['status'] == 'paid_unused' || $voucher_details['status'] == 'suspended') {
                $this->updateStatus($voucher_id, 'expired_unused', true);      
            }            

            // Process the Voucher for the purposes of Tax
            $this->processNewlyExpiredVoucher($voucher_id);            

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
        
        // Is the Voucher expired - this should not be needed but I feel better
        if($this->checkVoucherIsExpired($voucher_id)) { return; }        
            
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
    
 ////////////////////////////////////////////////////////   
      
   
    /* Standard Voucher/Record check functions */ 
    /* These functions: check the parent invoice status and it's vouchers for their statuses before making a descision about the specific voucher */
    
    ###########################################################
    #  Check if the voucher status is allowed to be changed   #  // used on voucher:status
    ###########################################################

    public function checkRecordAllowsManualStatusChange($voucher_id) {
        
         $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows change
        if(!$this->checkSingleVoucherAllowsStatusChange($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher's status cannot be changed because it's status does not allow it."));
            $state_flag = false;
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsSingleVoucherStatusChange($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher's status cannot be changed because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;
        
     }
     
    ##############################################################
    #  Check if a Voucher can be redeemed                        #
    ##############################################################

    public function checkRecordAllowsRedeem($voucher_id, $redeem_invoice_id) {
                
        $state_flag = true;
        
        // Check the specified voucher record allows redeem
        if(!$this->checkSingleVoucherAllowsRedeem($voucher_id, $redeem_invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be redeemed because it's status does not allow it."));
            $state_flag = false;
        }
        
        /* Notes for reference only
         * Check to see if the parent invoice allows redemption of it's vouchers funciton would go here
         * This is not the same as adding, removing or editing vouchers
         * We dont need to check the parent invoice because we are not changing the invoice in anyway.
         * Only the single voucher needs testing
         */        
                
        return $state_flag;
        
    }
    
    ###############################################################    
    #   Check to see a voucher can be cancelled                   #  // not currently used - Needed for cancellation via button on voucher:status (checks parent invoice aswell)
    ###############################################################

    public function checkRecordAllowsCancel($voucher_id) {

        $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows cancel
        if(!$this->checkSingleVoucherAllowsCancel($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it's status does not allow it."));
            $state_flag = false;        
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsSingleVoucherChanges($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;

    }
        
    ###############################################################
    #   Check to see if the voucher can be deleted                #
    ###############################################################

    public function checkRecordAllowsDelete($voucher_id) {

        $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows delete
        if(!$this->checkSingleVoucherAllowsDelete($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it's status does not allow it."));
            $state_flag = false;        
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsSingleVoucherChanges($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;

    }
    
    ###############################################################
    #   Check to see if the voucher can be edited                 #
    ###############################################################

    public function checkRecordAllowsEdit($voucher_id) {

        $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows edit
        if(!$this->checkSingleVoucherAllowsEdit($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it's status does not allow it."));
            $state_flag = false;        
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsSingleVoucherChanges($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;

    }
   
        
 ////////////////////////////////////////////////////////////////////// 
       
    /* These are looped through when checking Vouchers on invoices */
    /* They only act on the individual vouchers and are not aware of anything else. */ 
    
    ###########################################################
    #  Check if the voucher status is allowed to be changed   #  // used on voucher:status
    ###########################################################

    private function checkSingleVoucherAllowsStatusChange($voucher_id) {

        $state_flag = false;
        
        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has expired."));
            $state_flag = false;
        }
        
        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it is unpaid."));
            $state_flag = false; 
        }
        
        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it is partially paid."));
            $state_flag = false;    
        }
        
        // Is Paid Unused
        if($voucher_details['status'] == 'paid_unused') {            
            $state_flag = true;  
        }
        
        // Is Partially Redeemed
        if($voucher_details['status'] == 'partially_redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it is partially redeemed."));
            $state_flag = false;    
        }

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been fully redeemed."));
            $state_flag = false;        
        }
        
        // Is Suspended
        if($voucher_details['status'] == 'suspended') {            
            $state_flag = true;        
        }

        /* Is Expired Unused - done above with live check
        if($voucher_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it is expired."));
            $state_flag = false;        
        }*/ 

        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been deleted."));
            $state_flag = false;        
        }

        return $state_flag;    

    }

    ############################################################### 
    #   Check to see if the voucher status allows Editing         #
    ###############################################################

    private function checkSingleVoucherAllowsInvoiceEdit($voucher_id) {
        
        $state_flag = false;

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

        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
            $state_flag = true;  
        }
        
        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been partially paid."));
            $state_flag = false;    
        }
        
        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been fully redeemed."));
            $state_flag = false;        
        }
        
        // Is Unused
        if($voucher_details['status'] == 'paid_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has has been paid."));
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
        
        /* Is Expired Unused - done above with live check
        if($voucher_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because this voucher has been expired."));
            $state_flag = false;        
        }*/
        
        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $state_flag = true;      
        }

        // Is Deleted (this should not be needed)
        if($voucher_details['status'] == 'deleted') {
            $state_flag = true;        
        }

        return $state_flag; 
        
    }
    
    ############################################################### 
    #   Check to see if the voucher status allows cancelling      #
    ###############################################################

    private function checkSingleVoucherAllowsInvoiceCancel($voucher_id) {
        
        $state_flag = false;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has expired."));        
            $state_flag = false;
        }      
        
        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);
        
        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
            $state_flag = true;  
        }
        
        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been partially paid."));
            $state_flag = false;    
        }
        
        // Is Unused
        if($voucher_details['status'] == 'paid_unused') {
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
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been fully redeemed."));
            $state_flag = false;        
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been suspended."));
            $state_flag = false;        
        }

        /* Is Expired Unused - done above with live check
        if($voucher_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been expired."));
            $state_flag = false;        
        }*/

        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because this voucher has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted (should not be needed)
        if($voucher_details['status'] == 'deleted') {            
            $state_flag = true;        
        }    

        return $state_flag;
        
    }
    
    ############################################################### 
    #   Check to see if the voucher status allows Deleting        #
    ###############################################################

    private function checkSingleVoucherAllowsInvoiceDelete($voucher_id) {
        
        $state_flag = false;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has expired."));        
            $state_flag = false;
        }
        
        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
            $state_flag = true;  
        }
        
        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been partially paid."));
            $state_flag = false;    
        }
        
        // Is Unused
        if($voucher_details['status'] == 'paid_unused') {
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
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been fully redeemed."));
            $state_flag = false;        
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been suspended."));
            $state_flag = false;        
        }

        /* Is Expired Unused - done above with live check
        if($voucher_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been expired."));
            $state_flag = false;        
        }*/

        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted (should not be needed)
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because this voucher has already been deleted."));
            $state_flag = false;        
        }    

        return $state_flag;
        
    }
        
    ##############################################################
    #  Check if the Voucher can be redeemed                      #
    ##############################################################

    private function checkSingleVoucherAllowsRedeem($voucher_id, $redeem_invoice_id) {

        $state_flag = false;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be redeemed because it has expired."));        
            $state_flag = false;
        }
        
        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);
        
        // Voucher can not be used to pay for itself
        if($voucher_details['invoice_id'] == $redeem_invoice_id) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be used to pay for itself."));
            $state_flag = false;        
        }
        
        // Check if blocked
        if($voucher_details['blocked']) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be redeemed because it is blocked."));
            $state_flag = false;        
        }

        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher has not been paid for so cannot be redeemed"));
            $state_flag = false;  
        }
        
        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be redeemed because it's invoice has been partially paid."));
            $state_flag = false;    
        }
        
        // Is Paid Unused
        if($voucher_details['status'] == 'paid_unused') {
            $state_flag = true;  
        } 
        
        // Is Partially Redeemed
        if($voucher_details['status'] == 'partially_redeemed') {
            $state_flag = true;   
        }
        
        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be redeemed because it has been suspended."));
            $state_flag = false;        
        }

        /* Is Expired Unused - done above with live check
        if($voucher_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be redeemed because it has expired."));        
            $state_flag = false;        
        }*/

        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be redeemed because it has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be redeemed because it has been deleted."));
            $state_flag = false;        
        }

        return $state_flag;

    }
        
    ############################################################### 
    #   Check to see if the voucher status allows cancellation    #
    ###############################################################

    private function checkSingleVoucherAllowsCancel($voucher_id) {

        $state_flag = false;        
        
        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it has expired."));        
            $state_flag = false;
        }      
        
        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);  
        
        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
            $state_flag = true;  
        }
        
        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it's invoice has been partially paid."));
            $state_flag = false;    
        }
        
        // Is Paid Unused
        if($voucher_details['status'] == 'paid_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it's invoice has been paid."));
            $state_flag = false;  
        }
        
        // Is Partially Redeemed
        if($voucher_details['status'] == 'partially_redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it has been partially redeemed."));
            $state_flag = false;    
        }
        
        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it has been fully redeemed."));
            $state_flag = false;        
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it has been suspended."));
            $state_flag = false;        
        }

        /* Is Expired Unused - done above with live check
        if($voucher_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it has been expired."));
            $state_flag = false;        
        }*/

        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it has already been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because it has been deleted."));
            $state_flag = false;        
        }    

        return $state_flag;

    }
    
    ###############################################################
    #   Check to see if the voucher status allows deletion        # /* These are looped through when checking Vouchers on invoices and if they effect anything */
    ###############################################################

    private function checkSingleVoucherAllowsDelete($voucher_id) {

        $state_flag = false;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has expired."));        
            $state_flag = false;
        }
        
        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
            $state_flag = true;  
        }
        
        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it's invoice has been partially paid."));
            $state_flag = false;    
        }
        
        // Is Paid Unused
        if($voucher_details['status'] == 'paid_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it's invoice has been paid."));
            $state_flag = false;  
        }
        
        // Is Partially Redeemed
        if($voucher_details['status'] == 'partially_redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been partially redeemed."));
            $state_flag = false;    
        }
        
        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been fully redeemed."));
            $state_flag = false;        
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been suspended."));
            $state_flag = false;        
        }

        /* Is Expired Unused - done above with live check
        if($voucher_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been expired."));
            $state_flag = false;        
        }*/

        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has already been deleted."));
            $state_flag = false;        
        }    

        return $state_flag;

    }
    
    ##########################################################
    #  Check if the voucher status allows editing            # 
    ##########################################################

    private function checkSingleVoucherAllowsEdit($voucher_id) {

        $state_flag = false;

        // Is Expired (Live Check)
        if($this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it has expired."));        
            $state_flag = false;
        }
        
        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Is on a different tax system
        if($voucher_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it is on a different Tax system."));
            $state_flag = false;        
        }
        
        // Is the record's VAT code is enabled
        if(!$this->app->components->company->getVatTaxCodeStatus($voucher_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it's current VAT Tax Code is not enabled."));
            $state_flag = false; 
        }

        // Is Unpaid
        if($voucher_details['status'] == 'unpaid') {
            $state_flag = true;  
        }
        
        // Is Partially Paid
        if($voucher_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it's invoice has been partially paid."));
            $state_flag = false;    
        }
        
        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it has been fully redeemed."));
            $state_flag = false;        
        }
        
        // Is Paid Unused
        if($voucher_details['status'] == 'paid_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it's invoice has been paid."));
            $state_flag = false;  
        }
        
        // Is Partially Redeemed
        if($voucher_details['status'] == 'partially_redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it has been partially redeemed."));
            $state_flag = false;    
        }
        
        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it has been redeemed."));
            $state_flag = false;        
        }
        
        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it has been suspended."));
            $state_flag = false;        
        }
        
        /* Is Expired Unused - done above with live check
        if($voucher_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it has been expired."));
            $state_flag = false;        
        }*/
        
        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it has been deleted."));
            $state_flag = false;        
        }

        return $state_flag;  

    }
    
 //////////////////////////////////////////////////////////////////////     
    
  /* Invoice voucher checking routines - is this the best place for these ??? - these are used when perfomring actions on an ivoice and reports back to the invoice module*/

#########################################################################
#   Check to see if the invoice allows voucher status change            #  // Check to see if the parent invoice allows status changes to it's vouchers
#########################################################################  // used on voucher:status
    
public function checkInvoiceAllowsSingleVoucherStatusChange($invoice_id) {

        $state_flag = true;

        // Get the invoice details
        $invoice_details = $this->app->components->invoice->getRecord($invoice_id);

        // Is on a different tax system
        if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice is on a different Tax system."));
            $state_flag = false;       
        }
    
        // Is the Parent Invoice Pending      
        if($invoice_details['status'] == 'pending') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice is pending."));
            $state_flag = false;       
        }
        
        // Is the Parent Invoice Unpaid      
        if($invoice_details['status'] == 'unpaid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice is pending."));
            $state_flag = false;       
        }
        
        // Is the Parent Invoice Partially Paid       
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice is partially paid."));
            $state_flag = false;       
        }
        
        // Is the Parent Invoice Paid
        if($invoice_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice is paid."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice In dispute
        if($invoice_details['status'] == 'in_dispute') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice is in dispute."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Overdue
        if($invoice_details['status'] == 'overdue') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice payment is overdue."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice in Collections
        if($invoice_details['status'] == 'collections') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice is in collections."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice has been cancelled."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Deleted (might not need this check)
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because the parent invoice has been deleted."));
            $state_flag = false;        
        }
 
        return $state_flag;
}
    
#########################################################################  // Check to see if the parent invoice allows changes to it's vouchers add/edit/delete/cancel not reedeem
#   Check to see if the invoice allows voucher changes                  #
#########################################################################  

private function checkInvoiceAllowsSingleVoucherChanges($invoice_id) {

        $state_flag = true;

        // Get the invoice details
        $invoice_details = $this->app->components->invoice->getRecord($invoice_id);

        // Is the Parent Invoice Pending
        if($invoice_details['status'] == 'pending') {
            $state_flag = true;        
        }
        
        // Is the Parent Invoice Unpaid
        if($invoice_details['status'] == 'unpaid') {
            $state_flag = true;        
        }

        // Is on a different tax system
        if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice's vouchers cannot be changed because the parent invoice is on a different Tax system."));
            $state_flag = false;       
        }
    
        // Is the Parent Invoice Partially Paid       
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice's vouchers cannot be changed because the parent invoice is partially paid."));
            $state_flag = false;       
        }
        
        // Is the Parent Invoice Paid
        if($invoice_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice's vouchers cannot be changed because the parent invoice is paid."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice In dispute
        if($invoice_details['status'] == 'in_dispute') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice's vouchers cannot be changed because the parent invoice is in dispute."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Overdue
        if($invoice_details['status'] == 'overdue') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice's vouchers cannot be changed because the parent invoice payment is overdue."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice in Collections
        if($invoice_details['status'] == 'collections') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice's vouchers cannot be changed because the parent invoice is in collections."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice's vouchers cannot be changed because the parent invoice has been cancelled."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Deleted (might not need this check)
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice's vouchers cannot be changed because the parent invoice has been deleted."));
            $state_flag = false;        
        }
 
        return $state_flag;
 }
    
    ############################################################################
    # Check an invoices vouchers do not prevent the invoice getting cancelled  #
    ############################################################################

    public function checkInvoiceVouchersAllowsInvoiceCancel($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {            

            //$voucher_details = $rs->GetRowAssoc(); 

            // Check the Voucher to see if it can be deleted
            if(!$this->checkSingleVoucherAllowsCancel($rs->fields['voucher_id'])) {   
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because of Voucher").': '.$rs->fields['voucher_id']); 
                $state_flag = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }

        return $state_flag; 

    }
    
    ###########################################################################
    # Check an invoice's vouchers do not prevent the invoice getting deleted  #
    ###########################################################################

    public function checkInvoiceVouchersAllowsInvoiceDelete($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {            

            // Check the Voucher to see if it can be deleted
            if(!$this->checkSingleVoucherAllowsDelete($rs->fields['voucher_id'])) {     
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because of Voucher").': '.$rs->fields['voucher_id']);
                $state_flag = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }

        return $state_flag;

    }
    
    ############################################################################
    # Check an invoice's vouchers do not prevent the invoice getting edited    #
    ############################################################################

    public function checkInvoiceVouchersAllowsInvoiceEdit($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {            

            // Check the Voucher to see if it can be deleted
            if(!$this->checkSingleVoucherAllowsEdit($rs->fields['voucher_id'])) {  
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because of Voucher").': '.$rs->fields['voucher_id']);
                $state_flag = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }

        return $state_flag;
        
    }
    
    ############################################  // this could be put in general with date stuff
    #  Check Voucher Expiry is valid           #
    ############################################
    
    function checkVoucherExpiryIsValid($expiry_date)
    {
        // Get the expiry date - Converted in to 0000-00-00, a format that will prevent incorrect calculations
        $expiry_date = new DateTime($this->app->system->general->dateToMysqlDate($expiry_date));
        
        // Get today's date
        $todays_date = new DateTime("now");
        
        // Expiry is in the past
        if($expiry_date < $todays_date) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expiry date is invalid because it is in the past."));
            return false;
        }
        
        /* Expiry is today
        if($expiry_date = $todays_date) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expiry date is invalid because it is today's date."));
            return false;
        }*/
        
        return true;
        
    }
    
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