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
                voucher_code        =". $this->app->db->qstr( $this->generateVoucherCode()                      ).",  
                employee_id         =". $this->app->db->qstr( $this->app->user->login_user_id           ).",
                client_id           =". $this->app->db->qstr( $invoice_details['client_id']                ).",
                workorder_id        =". $this->app->db->qstr( $invoice_details['workorder_id']             ).",
                invoice_id          =". $this->app->db->qstr( $invoice_details['invoice_id']               ).",
                expiry_date         =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($expiry_date).' 23:59:59' ).",
                status              =". $this->app->db->qstr( 'unused'                                     ).",
                opened_on           =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()                             ).",
                blocked             =". $this->app->db->qstr( '0'                                          ).",
                tax_system          =". $this->app->db->qstr( $invoice_details['tax_system']               ).",    
                type                =". $this->app->db->qstr( $type                                        ).",
                unit_net            =". $unit_net                                               .",
                sales_tax_exempt    =". $sales_tax_exempt                                       .",
                vat_tax_code        =". $this->app->db->qstr( $vat_tax_code                                ).",
                unit_tax_rate       =". $unit_tax_rate                                          .", 
                unit_tax            =". $unit_net * ($unit_tax_rate/100)                        .",
                unit_gross          =". ($unit_net + ($unit_net * ($unit_tax_rate/100)) )       .",
                note                =". $this->app->db->qstr( $note                                        );

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to insert the Voucher into the database."));

        } else {

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

            return $voucher_id ;

        }

    }

    /** Get Functions **/

    #########################################
    #     Display Vouchers                  #
    #########################################

    public function getRecords($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $status = null, $employee_id = null, $client_id = null, $workorder_id = null, $invoice_id = null, $redeemed_client_id = null, $redeemed_invoice_id = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';
        $search_category = $search_category ?: 'voucher_id';
        $havingTheseRecords = '';

        /* Records Search */

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."voucher_records.voucher_id\n";

        // Restrict results by search category (client) and search term
        if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Restrict results by search category (redeemed client) and search term
        elseif($search_category == 'redeemed_client_display_name') {$havingTheseRecords .= " HAVING redeemed_client_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Restrict results by search category (employee) and search term
        elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Restrict results by search category and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."voucher_records.$search_category LIKE ".$this->app->db->qstr('%'.$search_term.'%');}  

        /* Filter the Records */

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

                $whereTheseRecords .= " AND ".PRFX."voucher_records.status= ".$this->app->db->qstr($status);

            }

        }    

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.employee_id=".$this->app->db->qstr($employee_id);}

        // Restrict by Client
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.client_id=".$this->app->db->qstr($client_id);}

        // Restrict by Workorder
        if($workorder_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.workorder_id=".$this->app->db->qstr($workorder_id);}

        // Restrict by Invoice
        if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.invoice_id=".$this->app->db->qstr($invoice_id);}

        // Restrict by Redeemed Client
        if($redeemed_client_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.redeemed_client_id=".$this->app->db->qstr($redeemed_client_id);}

        // Restrict by Redeemed Invoice
        if($redeemed_invoice_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.redeemed_invoice_id=".$this->app->db->qstr($redeemed_invoice_id);}

        /* The SQL code */

        $sql = "SELECT

            ".PRFX."voucher_records.*,                            
            IF(".PRFX."client_records.company_name !='', ".PRFX."client_records.company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,                       
            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name,            
            IF(redeemed_client_records.company_name !='', redeemed_client_records.company_name, CONCAT(redeemed_client_records.first_name, ' ', redeemed_client_records.last_name)) AS redeemed_client_display_name

            FROM ".PRFX."voucher_records            
            LEFT JOIN ".PRFX."user_records ON ".PRFX."voucher_records.employee_id = ".PRFX."user_records.user_id
            LEFT JOIN ".PRFX."client_records ON ".PRFX."voucher_records.client_id = ".PRFX."client_records.client_id
            LEFT JOIN ".PRFX."client_records AS redeemed_client_records ON ".PRFX."voucher_records.redeemed_client_id = redeemed_client_records.client_id

            ".$whereTheseRecords."
            GROUP BY ".PRFX."voucher_records.".$order_by."
            ".$havingTheseRecords."
            ORDER BY ".PRFX."voucher_records.".$order_by."
            ".$direction;   

        /* Restrict by pages */

        if($use_pages) {

            // Get the start Record
            $start_record = (($page_no * $records_per_page) - $records_per_page);        

            // Figure out the total number of records in the database for the given search        
            if(!$rs = $this->app->db->execute($sql)) {
                $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to count the matching Voucher records."));
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

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Voucher records."));

        } else {        

            $records = $rs->GetArray();   // If I call this twice for this search, no results are shown on the TPL

            if(empty($records)){

                return false;

            } else {

                return $records;

            }

        }

    }

    ##########################
    #  Get voucher details   #
    ##########################

    public function getRecord($voucher_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."voucher_records WHERE voucher_id=".$this->app->db->qstr($voucher_id);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get the Voucher details."));
        } else {

            if($item === null){

                return $rs->GetRowAssoc();            

            } else {

                return $rs->fields[$item];   

            } 

        }

    }

    #########################################
    #   Get voucher_id by voucher_code      #
    #########################################

    public function getIdByVoucherCode($voucher_code) {

        $sql = "SELECT * FROM ".PRFX."voucher_records WHERE voucher_code=".$this->app->db->qstr($voucher_code);

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get the Voucher ID by the Voucher code."));
        }

        if($rs->fields['voucher_id'] != '') {
            return $rs->fields['voucher_id'];
        } else {
            return false;
        }

    }

    #####################################
    #    Get Voucher Statuses           #
    #####################################

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."voucher_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('redeemed', 'expired', 'refunded', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get Voucher statuses."));
        } else {

            return $rs->GetArray();     

        }    

    }

    ######################################
    #  Get Voucher status display name   #
    ######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."voucher_statuses WHERE status_key=".$this->app->db->qstr($status_key);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get the voucher status display name."));
        } else {

            return $rs->fields['display_name'];

        }    

    }

    #####################################
    #    Get Voucher Types              #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."voucher_types";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get Voucher types."));
        } else {

            return $rs->GetArray();     

        }    

    }

    ##############################################
    #   Get Invoice Voucher  Sub Totals          #  // All statuses should be summed up, deleted vouchers do not have an invoice_id anyway so are ignored and cancelled vouchers only exist on cancelled invoices.
    ##############################################

    public function getInvoiceVouchersSubtotals($invoice_id) {

        $sql = "SELECT
                SUM(unit_net) AS sub_total_net,
                SUM(unit_tax) AS sub_total_tax,
                SUM(unit_gross) AS sub_total_gross            
                FROM ".PRFX."voucher_records
                WHERE invoice_id=". $this->app->db->qstr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get the invoice vouchers totals."));
        } else {

            return $rs->GetRowAssoc(); 

        }    

    }
    
    #####################################  // This gets the Voucher VAT Tax Code based on the company tax system or supplied tax_system
    #    Get Voucher default VAT Code   #  // not currently using '$tax_system = null'
    #####################################

    public function getVatTaxCode($type, $tax_system = null) {

        if(!$tax_system) {$tax_system = QW_TAX_SYSTEM;}

        if($type == 'MPV') {
            if($tax_system == 'no_tax') { return 'TNA'; }
            if($tax_system == 'sales_tax_cash') { return 'TNA'; } 
            if($tax_system == 'vat_standard') { return 'T9'; }        
            if($tax_system == 'vat_cash') { return 'T9'; }
            if($tax_system == 'vat_flat_basic') { return 'T9'; }
            if($tax_system == 'vat_flat_cash') { return 'T9'; }       
        }

        if($type == 'SPV') {
            if($tax_system == 'no_tax') { return 'TNA'; }
            if($tax_system == 'sales_tax_cash') { return 'TNA'; }
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

    public function updateRecord($voucher_id, $expiry_date, $unit_net, $note) {

        $unit_tax_rate = $this->getRecord($voucher_id, 'unit_tax_rate');
        $unit_tax = $unit_net * ($unit_tax_rate/100);    

        $sql = "UPDATE ".PRFX."voucher_records SET     
                employee_id     =". $this->app->db->qstr( $this->app->user->login_user_id           ).",
                expiry_date     =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($expiry_date).' 23:59:59' ).",            
                unit_net        =". $unit_net                                                .",
                unit_tax        =". $unit_tax                                                .",
                unit_gross      =". ($unit_net + $unit_tax)                                  .",
                last_active     =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime()                             ).",  
                note            =". $this->app->db->qstr( $note                                        )."
                WHERE voucher_id =". $this->app->db->qstr($voucher_id);

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update the Voucher record in the database."));

        } else {

            // Make sure correct expiry status is set (unused/expired)
            $this->checkVoucherIsExpired($voucher_id);

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
        $redeemed_on = ($new_status == 'redeemed') ? $datetime : '0000-00-00 00:00:00';

        // Update voucher 'closed_on' boolean for the new status
        if($new_status == 'redeemed' ||  $new_status == 'expired' || $new_status == 'refunded' || $new_status == 'cancelled') {
            $closed_on = $datetime;
        } else {
            $closed_on = '0000-00-00 00:00:00';
        }

        // Update voucher 'blocked' boolean for the new status
        if($new_status == 'redeemed' || $new_status == 'suspended' || $new_status == 'expired' || $new_status == 'refunded' || $new_status == 'cancelled' || $new_status == 'deleted') {
            $blocked = 1;
        } else {
            $blocked = 0;
        }

        $sql = "UPDATE ".PRFX."voucher_records SET
                status             =". $this->app->db->qstr( $new_status   ).",
                redeemed_on        =". $this->app->db->qstr( $redeemed_on  ).",   
                closed_on          =". $this->app->db->qstr( $closed_on    ).",
                last_active        =". $this->app->db->qstr( $datetime     ).",
                blocked            =". $this->app->db->qstr( $blocked      )."
                WHERE voucher_id   =". $this->app->db->qstr( $voucher_id   ); 

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update a Voucher Status."));

        } else {    

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

    }

    ######################################################
    #   Redeem the voucher against an invoice            # // ??
    ######################################################

    public function updateRecordAsRedeemed($voucher_id, $invoice_id, $payment_id) {

        $voucher_details = $this->app->components->invoice->getRecord($invoice_id);

        // some information has already been applied (as below) using $this->update_voucher_status() earlier in the process
        $sql = "UPDATE ".PRFX."voucher_records SET
                employee_id         =". $this->app->db->qstr( $this->app->user->login_user_id       ).",
                payment_id          =". $this->app->db->qstr( $payment_id                              ).",
                redeemed_client_id  =". $this->app->db->qstr( $voucher_details['client_id']            ).",   
                redeemed_invoice_id =". $this->app->db->qstr( $invoice_id                              )."            
                WHERE voucher_id    =". $this->app->db->qstr( $voucher_id                              );

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update the Voucher as redeemed."));
        } else {       

            // Change the voucher status to refunded (I do this here to maintain consistency)
            $this->updateStatus($voucher_id, 'redeemed', true);

            // Log activity        
            $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was redeemed by").' '.$this->app->components->client->getRecord($voucher_details['client_id'], 'display_name').'.';
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $voucher_details['client_id'], null, $invoice_id);

            // Update last active record
            $this->app->components->client->updateLastActive($voucher_details['client_id']);        
            $this->app->components->workorder->updateLastActive($voucher_details['workorder_id']);
            $this->app->components->invoice->updateLastActive($voucher_details['invoice_id']);        

        }

    }

    #################################
    #    Update voucher refund ID   #
    #################################

    public function updateRefundId($voucher_id, $refund_id) {

        $sql = "UPDATE ".PRFX."voucher_records SET
                refund_id            =".$this->app->db->qstr($refund_id)."
                WHERE voucher_id     =".$this->app->db->qstr($voucher_id);

        if(!$this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update an Invoice ID on the voucher."));
        }

    }

    /** Close Functions **/
    
    ##########################################
    #  Refund all of an Invoice's Vouchers   #
    ##########################################

    public function refundInvoiceVouchers($invoice_id, $refund_id) {

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

            while(!$rs->EOF) {            

                // Refund Voucher
                $this->refundRecord($rs->fields['voucher_id'], $refund_id);

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return;

        }

    }
    
    #################################################
    #  Revert Refund all of an Invoice's Vouchers   #
    #################################################

    public function revertRefundedInvoiceVouchers($invoice_id) {

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

            while(!$rs->EOF) {            

                // Refund Voucher
                $this->revertRecordRefund($rs->fields['voucher_id']);

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return;

        }

    }

    #####################################
    #   Refund Voucher                  #
    #####################################

    public function refundRecord($voucher_id, $refund_id) {

        // make sure the voucher can be cancelled
        if(!$this->checkSingleVoucherAllowsRefund($voucher_id)) {

            // Load the relevant invoice page with failed message
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be refunded."));
            $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->getRecord($voucher_id, 'invoice_id'));

        }

        // Change the voucher status to refunded (I do this here to maintain consistency)
        $this->updateStatus($voucher_id, 'refunded', true);

        // Update the voucher with the new refund_id
        $this->updateRefundId($voucher_id, $refund_id);  

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);    

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($voucher_details['voucher_id'], _gettext("Voucher").' '.$voucher_id.' '._gettext("was refunded by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("for Invoice").' '.$voucher_details['invoice_id'].' '._gettext("was refunded by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_id);

        // Update last active record
        $this->app->components->client->updateLastActive($voucher_details['client_id']);
        $this->app->components->workorder->updateLastActive($voucher_details['workorder_id']);
        $this->app->components->invoice->updateLastActive($voucher_details['invoice_id']);

        return true;

    }
    
    #####################################
    #   Revert Refund Voucher           #
    #####################################

    public function revertRecordRefund($voucher_id) {

        /* make sure the voucher can be cancelled
        if(!$this->checkSingleVoucherAllowsRefund($voucher_id)) {

            // Load the relevant invoice page with failed message
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be refunded."));
            $this->app->system->page->force_page('invoice', 'details&invoice_id='.$this->get_voucher_details($voucher_id, 'invoice_id'));

        }*/

        // Change the voucher status to refunded (I do this here to maintain consistency)
        $this->updateStatus($voucher_id, 'paid', true);

        // Update the voucher with the new refund_id
        $this->updateRefundId($voucher_id, '');  

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);    

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($voucher_details['voucher_id'], _gettext("Voucher").' '.$voucher_id.' '._gettext("was refund was reverted by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("for Invoice").' '.$voucher_details['invoice_id'].' '._gettext("refund was reverted by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_id);

        // Update last active record
        $this->app->components->client->updateLastActive($voucher_details['client_id']);
        $this->app->components->workorder->updateLastActive($voucher_details['workorder_id']);
        $this->app->components->invoice->updateLastActive($voucher_details['invoice_id']);

        return true;

    }
    

    ##########################################
    #  Cancel all of an Invoice's Vouchers   #
    ##########################################

    public function cancelInvoiceVouchers($invoice_id) {

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

            while(!$rs->EOF) {            

                // Cancel Voucher
                $this->cancelRecord($rs->fields['voucher_id']);

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return;

        }

    }
    
    ##############################
    #  Cancel Voucher            #  // update and set blocked as you cannot really delete an issued Voucher  
    ##############################

    public function cancelRecord($voucher_id) {     

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
    
    ##########################################
    #  Delete all of an Invoice's Vouchers   #
    ##########################################

    public function deleteInvoiceVouchers($invoice_id) {

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

            while(!$rs->EOF) {            

                // Refund Voucher
                $this->deleteRecord($rs->fields['voucher_id']);

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return;

        }

    }   

    ##############################
    #  Delete Voucher            #  // remove some information and set blocked as you cannot really delete an issued Voucher  
    ##############################

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
                voucher_code       =". $this->app->db->qstr( $voucher_details['voucher_code']   ).",
                employee_id         =   '',
                client_id           =   '',
                workorder_id        =   '',
                invoice_id          =   '',
                payment_id          =   '',
                refund_id           =   '',
                redeemed_client_id  =   '',
                redeemed_invoice_id =   '',
                expiry_date         =   '0000-00-00',
                status              =   'deleted',
                opened_on           =   '0000-00-00 00:00:00',
                redeemed_on         =   '0000-00-00 00:00:00',
                closed_on           =   '0000-00-00 00:00:00',            
                blocked             =   1,
                tax_system          =   '',
                type                =   '',
                unit_net            =   0.00,
                sales_tax_exempt    =   0,
                vat_tax_code        =   '',
                unit_tax_rate       =   0.00,
                unit_tax            =   0.00,
                unit_gross          =   0.00,
                note                =   ''
                WHERE voucher_id =". $this->app->db->qstr($voucher_id);        

            if(!$this->app->db->execute($sql)) {

                $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to delete the Voucher."));

            } else {

                // Recalculate the invoice totals and update them
                $this->app->components->invoice->recalculateTotals($voucher_details['invoice_id']);

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

    }
    
    /** Check Functions **/

    #################################################
    #   Check to see if the voucher is expired      #  // This does a live check to see if the voucher is expired and tagged as such
    #################################################

    public function checkAllVouchersForExpiry() {

        $sql = "SELECT voucher_id, status
                FROM ".PRFX."voucher_records;";

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

            while(!$rs->EOF) {

                // Skip checking vouchers with these statuses becasuse it is not required
                if($rs->fields['status'] == 'redeemed' || $rs->fields['status'] == 'expired' || $rs->fields['status'] == 'refunded' || $rs->fields['status'] == 'cancelled' || $rs->fields['status'] == 'deleted') {
                    $rs->MoveNext();
                    continue;
                }

                $this->checkVoucherIsExpired($rs->fields['voucher_id']);

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return;

        }

    }

    #################################################
    #   Check to see if the voucher is expired      #  // This does a live check to see if the voucher is expired and tagged as such
    #################################################

    public function checkVoucherIsExpired($voucher_id) {

        $expired_status = false;

        $voucher_details = $this->getRecord($voucher_id);

        // If the voucher is expired 
        if (time() > strtotime($voucher_details['expiry_date'].' 23:59:59')) {

            // If the status is not 'expired', update the status silenty (only from unused)
            if ($voucher_details['status'] == 'unused' || $voucher_details['status'] == 'suspended') {
                $this->updateStatus($voucher_id, 'expired', true);      
            }

            $expired_status = true;

        }

        // If the voucher has status of 'expired' but the date has been changed to a valid one
        if (time() <= strtotime($voucher_details['expiry_date'].' 23:59:59')) {

            //  If the status has not been updated, update the status silenty (only from expired)
            if ($voucher_details['status'] == 'expired') {
                $this->updateStatus($voucher_id, 'unused', true);      
            }

            $expired_status = false;

        }

        // Return the Expiry state
        return $expired_status;    

    }

    
 ////////////////////////////////////////////////////////   
      
   
    /* Standard Voucher/Record check functions */ 
    /* These functions: check the parent invoice status and it's vouchers for their statuses before making a descision about the specific voucher */
    
    ###########################################################
    #  Check if the voucher status is allowed to be changed   #
    ###########################################################

     public function checkRecordAllowsChange($voucher_id) {
        
         $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows change
        if(!$this->checkSingleVoucherAllowsChange($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be changed because it's status does not allow it."));
            $state_flag = false;
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsVoucherChange($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be refunded because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;
        
     }
     
    ##############################################################
    #  Check if the Voucher can be redeemed                      #
    ##############################################################

    public function checkRecordAllowsRedeem($voucher_id, $redeem_invoice_id) {
                
        $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows redeem
        if(!$this->checkSingleVoucherAllowsRedeem($voucher_id, $redeem_invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be redeemed because it's status does not allow it."));
            $state_flag = false;
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsVoucherChange($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be refunded because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;
        
    }
     
    ###############################################################   
    #   Check to see if the voucher can be refunded               #   // not currently used - Needed for refund via button on voucher:status
    ###############################################################

    public function checkRecordAllowsRefund($voucher_id) {

        $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows refund
        if(!$this->checkSingleVoucherAllowsRefund($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be refunded because it's status does not allow it."));
            $state_flag = false;
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsVoucherChange($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be refunded because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;

    }


    
    ###############################################################     /* these check not only the voucher requested but vouchers on shared invoices */    
    #   Check to see if the voucher can be cancelled              #  // not currently used - Needed for cancellation via button on voucher:status (checks parent invoice aswell)
    ###############################################################

    public function checkRecordAllowsCancel($voucher_id) {

        $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows cancel
        if(!$this->checkSingleVoucherAllowsCancel($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be cancelled because it's status does not allow it."));
            $state_flag = false;        
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsVoucherChange($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be cancelled because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;

    }
        
    ###############################################################
    #   Check to see if the voucher can be deleted                #  // Needed for deleting via button on voucher:status (checks parent invoice aswell)
    ###############################################################

    public function checkRecordAllowsDelete($voucher_id) {

        $state_flag = true;
        
        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);        
        
        // Check the specified voucher record allows delete
        if(!$this->checkSingleVoucherAllowsDelete($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be deleted because it's status does not allow it."));
            $state_flag = false;        
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsVoucherChange($voucher_details['invoice_id'])) {
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
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's status does not allow it."));
            $state_flag = false;        
        }
        
        // Check to see if the parent invoice allows changes to it's vouchers
        if(!$this->checkInvoiceAllowsVoucherChange($voucher_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because the invoice it is attached to, does not allow it."));
            $state_flag = false;        
        }
                
        return $state_flag;

    }
   
        
 ////////////////////////////////////////////////////////////////////// 
       
    /* These are looped through when checking Vouchers on invoices and if they effect anything */
    /* They only act on the indivdual voucher and are not aware of anything else. */
    
    ###########################################################
    #  Check if the voucher status is allowed to be changed   #
    ###########################################################

    private function checkSingleVoucherAllowsChange($voucher_id) {

        $state_flag = true;

        // Get the voucher status
        $voucher_details = $this->getRecord($voucher_id);

        // Unused and Expired
        if($voucher_details['status'] == 'unused' && $this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has expired."));
            $state_flag = false;        
        }

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been redeemed."));
            $state_flag = false;        
        }   

        // Is Expired - If not marked as expired this does a live check for expiry because expiry is not always upto date
        if($voucher_details['status'] == 'expired' || $this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been expired."));
            $state_flag = false;        
        }   

        // Is Refunded
        if($voucher_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been refunded."));
            $state_flag = false;        
        }

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
    
    ##############################################################
    #  Check if the Voucher can be redeemed                      #
    ##############################################################

    private function checkSingleVoucherAllowsRedeem($voucher_id, $redeem_invoice_id) {

        $state_flag = true;

        $voucher_details = $this->getRecord($voucher_id);

        // Voucher can not be used to pay for itself
        if($voucher_details['invoice_id'] == $redeem_invoice_id) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be used to pay for itself."));
            $state_flag = false;        
        }

        // Voucher must have been paid for
        if($this->app->components->invoice->getRecord($voucher_details['invoice_id'], 'status') !== 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher has not been paid for."));
            $state_flag = false;        
        }

        // Is Expired - If not marked as expired this does a live check for expiry because expiry is not always upto date
        if($voucher_details['status'] == 'expired' || $this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher is expired."));        
            $state_flag = false;        
        }

        // Check if unused (any other status causes failure)
        if($voucher_details['status'] !== 'unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher is unused."));
            $state_flag = false;        
        }    

        // Check if blocked
        if($voucher_details['blocked']) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher is blocked."));
            $state_flag = false;        
        }

        return $state_flag;

    }
    
    ############################################################### 
    #   Check to see if the voucher status allows refunding       #
    ###############################################################

    private function checkSingleVoucherAllowsRefund($voucher_id) {

        $state_flag = true;

        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been redeemed."));
            $state_flag = false;        
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has expired."));
            $state_flag = false;        
        }  

        // Is Expired - If not marked as expired this does a live check for expiry because expiry is not always upto date
        if($voucher_details['status'] == 'expired' || $this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be refunded because it has been expired."));
            $state_flag = false;        
        }

        // Is Refunded
        if($voucher_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been refunded."));
            $state_flag = false;        
        }

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
    
    
    #########################################################################
    #   Check to see if the voucher status allows refund to be cancelled    #
    #########################################################################

    private function checkSingleVoucherAllowsRefundCancel($voucher_id) {

        $state_flag = true;

        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        /* Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been deleted."));
            $state_flag = false;        
        }*/

        // Is not refunded
        if($voucher_details['status'] != 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot have it' refund deleted because it is not refunded."));
            $state_flag = false;        
        }

        return $state_flag;

    }     

    #########################################################################
    #   Check to see if the voucher status allows refund to be deleted      #
    #########################################################################

    private function checkSingleVoucherAllowsRefundDelete($voucher_id) {

        $state_flag = true;

        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        /* Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been deleted."));
            $state_flag = false;        
        }*/

        // Is not refunded
        if($voucher_details['status'] != 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot have it' refund deleted because it is not refunded."));
            $state_flag = false;        
        }

        return $state_flag;

    }     
    
    ############################################################### 
    #   Check to see if the voucher status allows cancellation    #
    ###############################################################

    private function checkSingleVoucherAllowsCancel($voucher_id) {

        $state_flag = true;

        // Get the voucher status
        $voucher_details = $this->getRecord($voucher_id);

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be cancelled because it has been redeemed."));
            $state_flag = false;        
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be cancelled because it has been suspended."));
            $state_flag = false;        
        }

        // Is Expired - If not marked as expired this does a live check for expiry because expiry is not always upto date
        if($voucher_details['status'] == 'expired' || $this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be cancelled because it has been expired."));
            $state_flag = false;        
        }

        // Is Refunded
        if($voucher_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be cancelled because it has been refunded."));
            $state_flag = false;        
        }

        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be cancelled because it has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be cancelled because it has been deleted."));
            $state_flag = false;        
        }    

        return $state_flag;

    }
    
    ###############################################################
    #   Check to see if the voucher status allows deletion        # /* These are looped through when checking Vouchers on invoices and if they effect anything */
    ###############################################################

    private function checkSingleVoucherAllowsDelete($voucher_id) {

        $state_flag = true;

        // Get voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been redeemed."));
            $state_flag = false;        
        }

        // Is Suspended
        if($voucher_details['status'] == 'suspended') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it is suspended."));
            $state_flag = false;        
        }

        // Is Expired - If not marked as expired this does a live check for expiry because expiry is not always upto date
        if($voucher_details['status'] == 'expired' || $this->checkVoucherIsExpired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be deleted because it has been expired."));
            $state_flag = false;        
        }

        // Is Refunded
        if($voucher_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been refunded."));
            $state_flag = false;        
        }

        // Is Cancelled
        if($voucher_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($voucher_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be deleted because it has been deleted."));
            $state_flag = false;        
        }    
        
        return $state_flag;

    }
    
    ##########################################################
    #  Check if the voucher status allows editing            # 
    ##########################################################

    private function checkSingleVoucherAllowsEdit($voucher_id) {

        $state_flag = true;

        // Validate voucher expired status
        $this->checkVoucherIsExpired($voucher_id);

        // Get the voucher details
        $voucher_details = $this->getRecord($voucher_id);

        // Is on a different tax system
        if($voucher_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be edited because it is on a different Tax system."));
            $state_flag = false;        
        }

        // Is Redeemed
        if($voucher_details['status'] == 'redeemed') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been redeemed."));
            $state_flag = false;        
        }

        // Is Refunded
        if($voucher_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been refunded."));
            $state_flag = false;        
        }

        /* Is Expired - If not marked as expired this does a live check for expiry because expiry is not always upto date
        if($voucher_details['status'] == 'expired' || $this->check_voucher_is_expired($voucher_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher status cannot be changed because it has been expired."));
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

        // The current record VAT code is enabled
        if(!$this->app->components->company->getVatTaxCodeStatus($voucher_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be edited because it's current VAT Tax Code is not enabled."));
            $state_flag = false; 
        }

        return $state_flag;  

    }
    
 //////////////////////////////////////////////////////////////////////     
    
  /* Invoice voucher checking routines - is this the best place for these ??? - these are used when perfomring actions on an ivoice and reports back to the invoice module*/
       
#########################################################################
#   Check to see if the invoice allows voucher changes                  #  // should this be in Invoice
#########################################################################
    
// Check to see if the parent invoice allows changes to it's vouchers
public function checkInvoiceAllowsVoucherChange($invoice_id) {

        $state_flag = true;

        // Get the invoice details
        $invoice_details = $this->app->components->invoice->getRecord($invoice_id);

        // Is on a different tax system
        if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice is on a different Tax system."));
            $state_flag = false;       
        }
    
        // Is the Parent Invoice Partially Paid       
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice is partially paid."));
            $state_flag = false;       
        }
        
        // Is the Parent Invoice Paid
        if($invoice_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice is paid."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice In dispute
        if($invoice_details['status'] == 'in_dispute') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice is in dispute."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Overdue
        if($invoice_details['status'] == 'overdue') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice payment is overdue."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice in Collections
        if($invoice_details['status'] == 'collections') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice is in collections."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Refunded
        if($invoice_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice has been refunded."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice has been cancelled."));
            $state_flag = false;        
        }
        
        // Is the Parent Invoice Deleted (might not need this check)
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The voucher cannot be changed because the parent invoice has been deleted."));
            $state_flag = false;        
        }
 
        return $state_flag;
 }
    
    ############################################################################
    # Check an invoices vouchers do not prevent the invoice getting refunded   #
    ############################################################################

    public function checkInvoiceVouchersAllowsInvoiceRefund($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

            while(!$rs->EOF) {            

                //$voucher_details = $rs->GetRowAssoc();

                // Check the Voucher to see if it can be refunded
                if(!$this->checkSingleVoucherAllowsRefund($rs->fields['voucher_id'])) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because of Voucher").': '.$rs->fields['voucher_id']); 
                    $state_flag = false;                
                }

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return $state_flag;

        }

    }
    
    ##################################################################################
    # Check an invoices vouchers do not prevent the invoice refund getting cancelled #  // should this use the function refund cancelled
    ##################################################################################

    public function checkInvoiceVouchersAllowsInvoiceRefundCancel($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

            while(!$rs->EOF) {            

                // Check the Voucher to see if it can be cancelled
                if(!$this->checkSingleVoucherAllowsRefundCancel($rs->fields['voucher_id'])) {  
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because of Voucher").': '.$rs->fields['voucher_id']); 
                    $state_flag = false;
                }

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return $state_flag;

        }

    }
    
    #################################################################################
    # Check an invoices vouchers do not prevent the invoice refund getting deleted  #
    #################################################################################

    public function checkInvoiceVouchersAllowsInvoiceRefundDelete($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

            while(!$rs->EOF) {            

                // Check the Voucher to see if it can be refunded
                if(!$this->checkSingleVoucherAllowsRefundDelete($rs->fields['voucher_id'])) {  
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because of Voucher").': '.$rs->fields['voucher_id']); 
                    $state_flag = false;
                }

                // Advance the loop to the next record
                $rs->MoveNext();           

            }

            return $state_flag;

        }

    }    
    
    ############################################################################
    # Check an invoices vouchers do not prevent the invoice getting cancelled  #
    ############################################################################

    public function checkInvoiceVouchersAllowsInvoiceCancel($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

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

    }
    
    ###########################################################################
    # Check an invoice's vouchers do not prevent the invoice getting deleted  #
    ###########################################################################

    public function checkInvoiceVouchersAllowsInvoiceDelete($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

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

    }
    
    ############################################################################
    # Check an invoice's vouchers do not prevent the invoice getting edited    #
    ############################################################################

    public function checkInvoiceVouchersAllowsInvoiceEdit($invoice_id) {

        $state_flag = true;

        $sql = "SELECT *
                FROM ".PRFX."voucher_records
                WHERE invoice_id = ".$invoice_id;

        if(!$rs = $this->app->db->execute($sql)) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

        } else {

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
            $voucher_code .= $acceptedChars{mt_rand(0, $max_offset)};
        }

        return $voucher_code;

    }    
    
}