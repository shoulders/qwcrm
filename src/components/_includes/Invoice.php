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

    class Invoice extends Components {

    /** Insert Functions **/

    #####################################
    #     insert invoice                #
    #####################################

    public function insertRecord($client_id, $workorder_id = null) {

        // Unify Dates and Times
        $timestamp = time();

        // If QWcrm Tax system is set to Sales Tax, then set the rate
        $sales_tax_rate = (QW_TAX_SYSTEM === 'sales_tax_cash') ? $this->app->components->company->getRecord('sales_tax_rate') : 0.00;

        $sql = "INSERT INTO ".PRFX."invoice_records SET
                employee_id     =". $this->app->db->qStr( $this->app->user->login_user_id   ).",
                client_id       =". $this->app->db->qStr( $client_id                           ).",
                workorder_id    =". $this->app->db->qStr( $workorder_id ?: null                   ).",
                date            =". $this->app->db->qStr( $this->app->system->general->mysqlDate($timestamp)               ).",
                due_date        =". $this->app->db->qStr( $this->app->system->general->mysqlDate($timestamp)               ).",
                tax_system      =". $this->app->db->qStr( QW_TAX_SYSTEM                          ).",
                sales_tax_rate  =". $this->app->db->qStr( $sales_tax_rate                      ).",
                status          =". $this->app->db->qStr( 'pending'                            ).",
                opened_on       =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime($timestamp)           ).",
                is_closed       =". $this->app->db->qStr( 0                                    ).",
                additional_info =". $this->app->db->qStr( '{}'                                 );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Get invoice_id
        $invoice_id = $this->app->db->Insert_ID();

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($workorder_id, _gettext("Invoice").' '.$invoice_id.' '._gettext("was created for this Work Order").' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        if($workorder_id) {
            $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("for Work Order").' '.$workorder_id.' '._gettext("was created by").' '.$this->app->user->login_display_name.'.';
        } else {
            $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("Created with no Work Order").'.';
        }
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $client_id, $workorder_id, $invoice_id);

        // Update last active record
        $this->app->components->client->updateLastActive($client_id, $timestamp);
        $this->app->components->workorder->updateLastActive($workorder_id, $timestamp);

        return $invoice_id;

    }

    #####################################
    #     Insert Items                  #  // Some or all of these calculations are done on the invoice:edit page - This extra code might not be needed in the future
    #####################################

    public function insertItems($invoice_id, $items = null) {

        // Get Invoice Details
        $invoice_details = $this->getRecord($invoice_id);

        // Delete all items from the invoice to prevent duplication
        $sql = "DELETE FROM ".PRFX."invoice_items WHERE invoice_id=".$this->app->db->qStr($invoice_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Insert Items/Rows into database (if any)
        if($items) {

            $sql = "INSERT INTO `".PRFX."invoice_items` (`invoice_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `unit_discount`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `subtotal_net`, `subtotal_tax`, `subtotal_gross`) VALUES ";

            foreach($items as $item) {

                // Correct Sales Tax Exempt indicator
                $sales_tax_exempt = isset($item['sales_tax_exempt']) ? 1 : 0;

                // Add in missing vat_tax_codes (i.e. submissions from 'no_tax' and 'sales_tax_cash' dont have VAT codes)
                $vat_tax_code = $item['vat_tax_code'] ?? $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']);

                /* All this is done in the TPL
                    // Calculate the correct tax rate based on tax system (and exemption status)
                    if($invoice_details['tax_system'] == 'sales_tax_cash' && $sales_tax_exempt) { $unit_tax_rate = 0.00; }
                    elseif($invoice_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $invoice_details['sales_tax_rate']; }
                    elseif(preg_match('/^vat_/', $invoice_details['tax_system'])) { $unit_tax_rate = $this->app->components->company->getVatRate($item['vat_tax_code']); }
                    else { $unit_tax_rate = 0.00; }

                    // Build item totals based on selected TAX system
                    $item_totals = $this->calculateItemsSubtotals($invoice_details['tax_system'], $item['unit_qty'], $item['unit_net'], $unit_tax_rate);
                */

                $sql .="(".
                        $this->app->db->qStr( $invoice_id                       ).",".
                        $this->app->db->qStr( $invoice_details['tax_system']    ).",".
                        $this->app->db->qStr( $item['description']              ).",".
                        $this->app->db->qStr( $item['unit_qty']                 ).",".
                        $this->app->db->qStr( $item['unit_net']                 ).",".
                        $this->app->db->qStr( $item['unit_discount']            ).",".
                        $this->app->db->qStr( $sales_tax_exempt                 ).",".
                        $this->app->db->qStr( $vat_tax_code                     ).",".
                        $this->app->db->qStr( $item['unit_tax_rate']            ).",".
                        $this->app->db->qStr( $item['unit_tax']                 ).",".
                        $this->app->db->qStr( $item['unit_gross']               ).",".
                        $this->app->db->qStr( $item['subtotal_net']             ).",".
                        $this->app->db->qStr( $item['subtotal_tax']             ).",".
                        $this->app->db->qStr( $item['subtotal_gross']           )."),";

            }

            // Strips off last comma as this is a joined SQL statement
            $sql = substr($sql , 0, -1);

            if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

            return;

        }

    }

    #####################################
    #   insert invoice prefill items    #
    #####################################

    public function insertPrefillItems($prefill_items = null) {

        // Empty the invoice_prefill_items table
        $sql = "TRUNCATE ".PRFX."invoice_prefill_items";
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Only insert items if there are some
        if($prefill_items) {

            // Build SQL
            $sql = "INSERT INTO ".PRFX."invoice_prefill_items(description, type, unit_net, active) VALUES ";
            foreach($prefill_items as $prefill_item) {

                // When not checked, no value is sent so this sets zero for those cases
                if(!isset($prefill_item['active'])) { $prefill_item['active'] = '0'; }

                $sql .="(".
                    $this->app->db->qStr( $prefill_item['description'] ).",".
                    $this->app->db->qStr( $prefill_item['type']        ).",".
                    $this->app->db->qStr( $prefill_item['unit_net']    ).",".
                    $this->app->db->qStr( $prefill_item['active']      )."),";
            }

            // Strips off last comma as this is a joined SQL statement
            $sql = substr($sql , 0, -1);

            // Execute the SQL
            if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        }

        // Log activity
        $this->app->system->general->writeRecordToActivityLog(_gettext("The Invoice Prefill Items").' '._gettext("were modified by").' '.$this->app->user->login_display_name.'.');

    }

    /** Get Functions **/

    #########################################
    #     Display Invoices                  #
    #########################################

    public function getRecords($order_by, $direction, $records_per_page = 0, $use_pages = false, $page_no = null, $search_category = 'invoice_id', $search_term = null, $status = null, $employee_id = null, $client_id = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n";
        $havingTheseRecords = '';

        // Restrict results by search category (client) and search term
        if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (employee) and search term
        elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (invoice items) and search term
        elseif($search_category == 'invoice_items') {$havingTheseRecords .= " HAVING invoice_items LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category and search term
        elseif($search_term != null) {$whereTheseRecords .= " AND ".PRFX."invoice_records.$search_category LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict by Status
        if($status) {

            // All Open Invoices
            if($status == 'open') {

                $whereTheseRecords .= " AND ".PRFX."invoice_records.is_closed != '1'";

            // All Closed Invoices
            } elseif($status == 'closed') {

                $whereTheseRecords .= " AND ".PRFX."invoice_records.is_closed = '1'";

            // Return Invoices for the given status
            } else {

                $whereTheseRecords .= " AND ".PRFX."invoice_records.status= ".$this->app->db->qStr($status);

            }

        }

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qStr($employee_id);}

        // Restrict by Client
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qStr($client_id);}

        // The SQL code
        $sql = "SELECT ".PRFX."invoice_records.*,

            IF(company_name !='', company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,
            ".PRFX."client_records.first_name AS client_first_name,
            ".PRFX."client_records.last_name AS client_last_name,
            ".PRFX."client_records.primary_phone AS client_phone,
            ".PRFX."client_records.mobile_phone AS client_mobile_phone,
            ".PRFX."client_records.fax AS client_fax,

            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name,
            ".PRFX."user_records.work_primary_phone AS employee_work_primary_phone,
            ".PRFX."user_records.work_mobile_phone AS employee_work_mobile_phone,
            ".PRFX."user_records.home_mobile_phone AS employee_home_mobile_phone,

            ".PRFX."workorder_records.scope,

            items.combined as invoice_items,
            vouchers.voucher_items

            FROM ".PRFX."invoice_records

            LEFT JOIN (
                SELECT ".PRFX."invoice_items.invoice_id,
                GROUP_CONCAT(
                    CONCAT(".PRFX."invoice_items.unit_qty, ' x ', ".PRFX."invoice_items.description)
                    ORDER BY ".PRFX."invoice_items.invoice_item_id
                    ASC
                    SEPARATOR '|||'
                ) AS combined
                FROM ".PRFX."invoice_items
                GROUP BY ".PRFX."invoice_items.invoice_id
                ORDER BY ".PRFX."invoice_items.invoice_id
                ASC
            ) AS items
            ON ".PRFX."invoice_records.invoice_id = items.invoice_id

            LEFT JOIN (
                SELECT ".PRFX."voucher_records.invoice_id,
                CONCAT('[',
                    GROUP_CONCAT(
                        JSON_OBJECT(
                            'voucher_id', voucher_id
                            ,'voucher_code', voucher_code
                            ,'expiry_date', expiry_date
                            ,'unit_net', unit_net
                            ,'balance', balance
                            )
                        SEPARATOR ',')
                ,']') AS voucher_items
                FROM ".PRFX."voucher_records
                GROUP BY ".PRFX."voucher_records.invoice_id
                ORDER BY ".PRFX."voucher_records.voucher_id
                ASC
            ) AS vouchers
            ON ".PRFX."invoice_records.invoice_id = vouchers.invoice_id

            LEFT JOIN ".PRFX."client_records ON ".PRFX."invoice_records.client_id = ".PRFX."client_records.client_id
            LEFT JOIN ".PRFX."user_records ON ".PRFX."invoice_records.employee_id = ".PRFX."user_records.user_id
            LEFT JOIN ".PRFX."workorder_records ON ".PRFX."invoice_records.workorder_id = ".PRFX."workorder_records.workorder_id

            ".$whereTheseRecords."
            GROUP BY ".PRFX."invoice_records.".$order_by."
            ".$havingTheseRecords."
            ORDER BY ".PRFX."invoice_records.".$order_by."
            ".$direction;

        // Get the total number of records in the database for the given search
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
        $total_results = $rs->RecordCount();

        // Restrict by pages
        if($use_pages) {

            // Get the start Record
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

    #####################################
    #   Get invoice details             #
    #####################################

    public function getRecord($invoice_id = null, $item = null) {

        // This allows for blank calls
        if(!$invoice_id){
            return;
        }

        $sql = "SELECT * FROM ".PRFX."invoice_records WHERE invoice_id =".$this->app->db->qStr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // This makes sure there is a record to return to prevent errors (currently only needed for upgrade)
        if(!$rs->recordCount()) {

            return false;

        } else {

            if(!$item){

                return $rs->GetRowAssoc();

            } else {

                return $rs->fields[$item];

            }

        }

    }


    #########################################
    #   Get All invoice items               # // withVouchers adds the invoice vouchers in as items, useful for credit notes and print/email TPL
    #########################################

    public function getItems($invoice_id, $withVouchers = false) {

        $invoice_items = array();

        $sql = "SELECT * FROM ".PRFX."invoice_items WHERE invoice_id=".$this->app->db->qStr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($rs->recordCount())
        {
            $invoice_items = $rs->GetArray();
        }
        else
        {
            return array();
        }

        // Converts invoice voucher records into items and merges them into the invoice items- This is a bit of a workaround, this
        if($withVouchers)
        {
            $voucher_records = $this->app->components->voucher->getRecords('voucher_id', 'DESC', 25, false, null, null, null, null, null, null, null, $invoice_id);

            $voucher_items = array();

            (int) $index = count($invoice_items);

            foreach($voucher_records['records'] as $key => $value)
            {
                $voucher_items[$index]['invoice_item_id'] = $value['voucher_id'];  // this number is not actually used in the TPL
                $voucher_items[$index]['invoice_id'] = $value['invoice_id'];
                $voucher_items[$index]['tax_system'] = $value['tax_system'];
                $voucher_items[$index]['description'] = _gettext("Voucher").': '.$value['voucher_code'];
                $voucher_items[$index]['unit_qty'] = 1;
                $voucher_items[$index]['unit_net'] = $value['unit_net'];
                $voucher_items[$index]['unit_discount'] = 0.00;
                $voucher_items[$index]['sales_tax_exempt'] = $value['sales_tax_exempt'];
                $voucher_items[$index]['vat_tax_code'] = $value['vat_tax_code'];
                $voucher_items[$index]['unit_tax_rate'] = $value['unit_tax_rate'];
                $voucher_items[$index]['unit_tax'] = $value['unit_tax'];
                $voucher_items[$index]['unit_gross'] = $value['unit_gross'];
                $voucher_items[$index]['subtotal_net'] = $value['unit_net'];
                $voucher_items[$index]['subtotal_tax'] = $value['unit_tax'];
                $voucher_items[$index]['subtotal_gross'] = $value['unit_gross'];

                ++$index;
            }

            // Merge Item arrays
            $invoice_items = $invoice_items + $voucher_items;

        }

        return $invoice_items;

    }

    #######################################
    #   Get invoice item details           #  // not used anywhere
    #######################################

    public function getItem($invoice_item_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."invoice_items WHERE invoice_item_id =".$this->app->db->qStr($invoice_item_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!$item){

            return $rs->GetRowAssoc();

        } else {

            return $rs->fields[$item];

        }

    }

    ############################################
    #   Get Invoice items Sub Totals           #
    ############################################

    public function getItemsSubtotals($invoice_id) {

        // I could use $this->app->components->report->sumInvoiceItems() - with additional calculation for subtotal_discount
        // NB: i dont think i need the aliases
        // $invoice_items_subtotals = $this->app->components->report->getInvoicesStats('items', null, null, null, null, null);

        $sql = "SELECT
                SUM(unit_discount * unit_qty) AS subtotal_discount,
                SUM(subtotal_net) AS subtotal_net,
                SUM(subtotal_tax) AS subtotal_tax,
                SUM(subtotal_gross) AS subtotal_gross
                FROM ".PRFX."invoice_items
                WHERE invoice_id=". $this->app->db->qStr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetRowAssoc();

    }

    #######################################
    #   Get invoice prefill items         #
    #######################################

    public function getPrefillItems($status = null) {

        $sql = "SELECT * FROM ".PRFX."invoice_prefill_items";

        // Prepare the sql for the optional filter
        $sql .= " WHERE invoice_prefill_id >= 1";

        // filter by status
        if($status) {$sql .= " AND active=".$this->app->db->qStr($status);}

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!empty($rs)) {

            return $rs->GetArray();

        }

    }

    #####################################
    #    Get Invoice Statuses           #
    #####################################

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."invoice_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('partially_paid', 'paid', 'in_dispute', 'overdue', 'collections', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    ######################################
    #  Get Invoice status display name   #
    ######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."invoice_statuses WHERE status_key=".$this->app->db->qStr($status_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];

    }


    /** Update Functions **/

    #####################################
    #   Update Invoice                  #  // Update the totals for the invoice (calculations are done onpage)
    #####################################

    public function updateRecord($qform) {

        // Unify Dates and Times
        $timestamp = time();

        $sql = "UPDATE ".PRFX."invoice_records SET
                date                =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['date'])     ).",
                due_date            =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['due_date']) ).",
                unit_discount       =". $this->app->db->qStr( $qform['unit_discount']   ).",
                unit_net            =". $this->app->db->qStr( $qform['unit_net']            ).",
                unit_tax            =". $this->app->db->qStr( $qform['unit_tax']            ).",
                unit_gross          =". $this->app->db->qStr( $qform['unit_gross']          )."
                WHERE invoice_id    =". $this->app->db->qStr( $qform['invoice_id']          );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $invoice_details = $this->getRecord($qform['invoice_id']);

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($invoice_details['workorder_id'], _gettext("Invoice").' '.$invoice_details['invoice_id'].' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Invoice").' '.$invoice_details['invoice_id'].' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);

        // Update last active record
        $this->updateLastActive($invoice_details['invoice_id'], $timestamp);
        $this->app->components->client->updateLastActive($this->getRecord($invoice_details['invoice_id'], 'client_id'), $timestamp);
        $this->app->components->workorder->updateLastActive($this->getRecord($invoice_details['invoice_id'], 'workorder_id'), $timestamp);

        return;

    }

    /*####################################
    #   update invoice static values   #  // This is used when a user updates an invoice before any payments
    ####################################

    public function updateStaticValues($invoice_id, $date, $due_date) {

        // Unify Dates and Times
        $timestamp = time();

        $sql = "UPDATE ".PRFX."invoice_records SET
                date                =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($date)     ).",
                due_date            =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($due_date) )."
                WHERE invoice_id    =". $this->app->db->qStr( $invoice_id                   );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $invoice_details = $this->getRecord($invoice_id);

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($invoice_details['workorder_id'], _gettext("Invoice").' '.$invoice_id.' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update last active record
        $this->updateLastActive($invoice_id);
        $this->app->components->client->updateLastActive($this->getRecord($invoice_id, 'client_id'), $timestamp);
        $this->app->components->workorder->updateLastActive($this->getRecord($invoice_id, 'workorder_id'), $timestamp);

    }*/

    ############################
    # Update Invoice Status    #
    ############################

    public function updateStatus($invoice_id, $new_status) {

        // Unify Dates and Times
        $timestamp = time();

        // Get invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // If the new status is the same as the current one, exit
        if($new_status == $invoice_details['status']) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status."));
            return false;
        }

        // Set the appropriate employee_id
        $employee_id = ($new_status == 'unassigned') ? null : $invoice_details['employee_id'];

        // Build SQL
        $sql = "UPDATE ".PRFX."invoice_records SET
                employee_id         =". $this->app->db->qStr($employee_id).",
                status              =". $this->app->db->qStr($new_status).",";
        if($new_status == 'paid' || $new_status == 'cancelled' || $new_status == 'deleted')
        {
            $sql .= "closed_on =". $this->app->db->qStr($this->app->system->general->mysqlDatetime($timestamp) ).",
                     is_closed =". $this->app->db->qStr(1);
        }
        else
        {
            $sql .= "closed_on = NULL,
                     is_closed   =". $this->app->db->qStr(0);
        }
        $sql .= "WHERE invoice_id =". $this->app->db->qStr($invoice_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Process invoice Vouchers and their status
        $this->app->components->voucher->updateInvoiceVouchersStatuses($invoice_id, $new_status);

        // Status updated message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Invoice status updated."));

        // For writing message to log file, get invoice status display name
        $inv_status_diplay_name = _gettext($this->getStatusDisplayName($new_status));

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($invoice_details['workorder_id'], _gettext("Invoice Status updated to").' '.$inv_status_diplay_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("Status updated to").' '.$inv_status_diplay_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update last active record
        $this->updateLastActive($invoice_id, $timestamp);
        $this->app->components->client->updateLastActive($invoice_details['client_id'], $timestamp);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id'], $timestamp);

        return true;

    }


    #################################
    #    Update Last Active         #
    #################################

    public function updateLastActive($invoice_id = null, $timestamp = null) {

        // Allow null calls (some Workorders do not have Invoices)
        if(!$invoice_id) { return; }

        $sql = "UPDATE ".PRFX."invoice_records SET
                last_active=".$this->app->db->qStr( $this->app->system->general->mysqlDatetime($timestamp) )."
                WHERE invoice_id=".$this->app->db->qStr($invoice_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    ###################################
    #    update additional info       #
    ###################################

    public function updateAdditionalInfo($invoice_id, $additional_info = null) {

        $sql = "UPDATE ".PRFX."invoice_records SET
                additional_info=".$this->app->db->qStr( $additional_info )."
                WHERE invoice_id=".$this->app->db->qStr($invoice_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    /** Close Functions **/


    #####################################
    #   Cancel Invoice                  # // This does not delete information i.e. client went bust and did not pay
    #####################################

    public function cancelRecord($invoice_id, $reason_for_cancelling = null) {

        // Unify Dates and Times
        $timestamp = time();

        // Make sure the invoice can be cancelled
        if(!$this->checkRecordAllowsCancel($invoice_id)) {
            return false;
        }

        // Get invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Cancel any Vouchers - handled in updateInvoiceVouchersStatuses()
        //$this->app->components->voucher->cancelInvoiceVouchers($invoice_id);

        // Change the invoice status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($invoice_id, 'cancelled');

        // Add Cancelled message to the additional info
        $this->updateAdditionalInfo($invoice_id, $this->buildAdditionalInfoJson($invoice_id, $reason_for_cancelling));

        // Create a Workorder History Note  - this is an invoice
        //$this->app->components->workorder->insertHistory($invoice_details['invoice_id'], _gettext("Invoice").' '.$invoice_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("for Work Order").' '.$invoice_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update last active record
        $this->updateLastActive($invoice_id, $timestamp);
        $this->app->components->client->updateLastActive($invoice_details['client_id'], $timestamp);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id'], $timestamp);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #   Delete Invoice                  #
    #####################################

    public function deleteRecord($invoice_id) {

        // Unify Dates and Times
        $timestamp = time();

        // Make sure the invoice can be deleted
        if(!$this->checkRecordAllowsDelete($invoice_id)) {
            return false;
        }

        // Get invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Delete any Vouchers - handled in updateInvoiceVouchersStatuses()
        //$this->app->components->voucher->deleteInvoiceVouchers($invoice_id);

        // Delete invoice items
        $this->deleteItems($invoice_id);

        // Change the invoice status to deleted - This triggers certain routines such as voucher deletion
        $this->updateStatus($invoice_id, 'deleted');

        // Build the data to replace the invoice record (some stuff has just been updated with $this->update_invoice_status())
        $sql = "UPDATE ".PRFX."invoice_records SET
                employee_id         = NULL,
                client_id           = NULL,
                workorder_id        = NULL,
                date                = NULL,
                due_date            = NULL,
                tax_system          = '',
                unit_discount       = 0.00,
                unit_net            = 0.00,
                sales_tax_rate      = 0.00,
                unit_tax            = 0.00,
                unit_gross          = 0.00,
                balance             = 0.00,
                status              = 'deleted',
                opened_on           = NULL,
                closed_on           = NULL,
                last_active         = NULL,
                is_closed           = 1,
                additional_info     = ''
                WHERE invoice_id    =". $this->app->db->qStr( $invoice_id  );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Remove the invoice_id from the related Workorder record (if present)
        $this->app->components->workorder->updateInvoiceId($invoice_details['workorder_id'], null);

        // Create a Workorder History Note
        $this->app->components->workorder->insertHistory($invoice_id, _gettext("Invoice").' '.$invoice_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Invoice").' '.$invoice_details['invoice_id'].' ';
        if($invoice_details['workorder_id'])
        {
            $record .= _gettext("for Work Order").' '.$invoice_details['workorder_id'].' ';
        }
        $record .= _gettext("was deleted by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update workorder status
        if($invoice_details['workorder_id'])
        {
            $this->app->components->workorder->updateStatus($invoice_details['workorder_id'], 'closed_without_invoice');
        }

        // Update last active record
        $this->updateLastActive($invoice_id, $timestamp);
        $this->app->components->client->updateLastActive($invoice_details['client_id'], $timestamp);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id'], $timestamp);

        return true;

    }

    #############################################
    #   Delete an invoice's Items (ALL)         #
    #############################################

    public function deleteItems($invoice_id) {

        $sql = "DELETE FROM ".PRFX."invoice_items WHERE invoice_id=" . $this->app->db->qStr($invoice_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return true;

    }

    /** Check Functions **/

    ##########################################################
    #  Check if the invoice status is allowed to be changed  #
    ##########################################################

     public function checkRecordAllowsManualStatusChange($invoice_id) {

        $state_flag = true;

        // Get the invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Is on a different tax system
        if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because it is on a different Tax system."));
            $state_flag = false;
        }

        // Is partially paid
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has payments and is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($invoice_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has payments and is paid."));
            $state_flag = false;
        }

        // Is cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been cancelled."));
            $state_flag = false;
        }

        // Is deleted
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been deleted."));
            $state_flag = false;
        }

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'invoice', null, null, null, null, $invoice_id))
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has payments."));
            $state_flag = false;
        }*/

        // Does the invoice have any Vouchers preventing changing the invoice status
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceEdit($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because of Vouchers on it prevent this."));
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->countCreditnotes(null, null, null, null, null, null, null, null, $invoice_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because it has linked credit notes."));
            $state_flag = false;
        }

        return $state_flag;

     }

    ###############################################################
    #   Check to see if the invoice can be cancelled              #
    ###############################################################

    public function checkRecordAllowsCancel($invoice_id) {

        $state_flag = true;

        // Get the invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Is on a different tax system
        if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because it is on a different Tax system."));
            $state_flag = false;
        }

        // Does not have a balance
        if($invoice_details['balance'] == 0) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be cancelled because the invoice does not have a balance."));
            $state_flag = false;
        }

        // Is partially paid
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be cancelled because the invoice is partially paid."));
            $state_flag = false;
        }

        // Is cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because the invoice has already been cancelled."));
            $state_flag = false;
        }

        // Is deleted
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because the invoice has been deleted."));
            $state_flag = false;
        }

        /* Has no payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'invoice', null, null, null, null, $invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be cancelled because the invoice has payments."));
            $state_flag = false;
        }*/

        // Does the invoice have any Vouchers preventing cancelling the invoice (i.e. any that have been used)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceCancel($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because of Vouchers on it prevent this."));
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->countCreditnotes(null, null, null, null, null, null, null, null, $invoice_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because it has linked credit notes."));
            $state_flag = false;
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the invoice can be deleted                #
    ###############################################################

    public function checkRecordAllowsDelete($invoice_id) {

        $state_flag = true;

        // Get the invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Is on a different tax system
        if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because it is on a different Tax system."));
            $state_flag = false;
        }

        // Is closed
        if($invoice_details['is_closed'] == true) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it is closed."));
            $state_flag = false;
        }

        // Is partially paid
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has payments and is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($invoice_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has payments and is paid."));
            $state_flag = false;
        }

        // Is cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has been cancelled."));
            $state_flag = false;
        }

        // Is deleted
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it already been deleted."));
            $state_flag = false;
        }

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'invoice', null, null, null, null, $invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has payments."));
            $state_flag = false;
        }*/

        /*
        // Has Items (these will get deleted anyway)
        if(!empty($this->getItems($invoice_id))) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has items."));
            $state_flag = false;
        }
        */

        // Does the invoice have any Vouchers preventing deletion of the invoice (i.e. any that have been used)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceDelete($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because of Vouchers on it prevent this."));
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->countCreditnotes(null, null, null, null, null, null, null, null, $invoice_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because it has linked credit notes."));
            $state_flag = false;
        }

        return $state_flag;

    }

    ##########################################################
    #  Check if the invoice status is allowed to be Edited   #
    ##########################################################

     public function checkRecordAllowsEdit($invoice_id) {

        $state_flag = true;

        // Get the invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Is on a different tax system
        if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because it is on a different Tax system."));
            $state_flag = false;
        }

        // Is partially paid
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because the invoice has payments and is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($invoice_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because the invoice has payments and is paid."));
            $state_flag = false;
        }

        // Is cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because the invoice has been cancelled."));
            $state_flag = false;
        }

        // Is deleted
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because the invoice has been deleted."));
            $state_flag = false;
        }

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'invoice', null, null, null, null, $invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because the invoice has payments."));
            $state_flag = false;
        }*/

        // Does the invoice have any Vouchers preventing changing the invoice status
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceEdit($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because of Vouchers on it prevent this."));
            $state_flag = false;
        }

        // The current record VAT code is enabled
        if(!$this->checkVatTaxCodeStatuses($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be edited because one or more of it's items have a VAT Tax Code that is not enabled."));
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->countCreditnotes(null, null, null, null, null, null, null, null, $invoice_details['invoice_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because it has linked credit notes."));
            $state_flag = false;
        }

        return $state_flag;

    }

    /** Other Functions **/

    ################################################  // sales tax and VAT are the same
    #   calculate an Invoice Item Sub Totals       #  // The exact dane totals calculation is done for all, just the inputs are different
    ################################################  // might njot be needed anymore
                                                      // this is used in 3.1.0 upgrade
                                                      // this should be removed or remmend out
                                                      // these dont take into account individual rows

    /*public function calculateItemsSubtotals($tax_system, $unit_qty, $unit_net, $unit_tax_rate = null) {

        $item_totals = array();

        // No Tax
        if($tax_system == 'no_tax') {
            $item_totals['unit_tax'] = 0.00;
            $item_totals['unit_gross'] = $unit_net;
            $item_totals['subtotal_net'] = $unit_net * $unit_qty;
            $item_totals['subtotal_tax'] = 0.00;
            $item_totals['subtotal_gross'] = $item_totals['subtotal_net'];
        }

        // Sales Tax Calculations
        if($tax_system == 'sales_tax_cash') {
            $item_totals['unit_tax'] = $unit_net * ($unit_tax_rate / 100);
            $item_totals['unit_gross'] = $unit_net + $item_totals['unit_tax'];
            $item_totals['subtotal_net'] = $unit_net * $unit_qty;
            $item_totals['subtotal_tax'] = $item_totals['subtotal_net'] * ($unit_tax_rate / 100);
            $item_totals['subtotal_gross'] = $item_totals['subtotal_net'] + $item_totals['subtotal_tax'];
        }

        // VAT Calculations
        if(preg_match('/^vat_/', $tax_system)) {
            $item_totals['unit_tax'] = $unit_net * ($unit_tax_rate / 100);
            $item_totals['unit_gross'] = $unit_net + $item_totals['unit_tax'];
            $item_totals['subtotal_net'] = $unit_net * $unit_qty;
            $item_totals['subtotal_tax'] = $item_totals['subtotal_net'] * ($unit_tax_rate / 100);
            $item_totals['subtotal_gross'] = $item_totals['subtotal_net'] + $item_totals['subtotal_tax'];
        }

        return $item_totals;

    }*/

    #####################################  // Most calculations are done on the invoice:edit tpl but this is still required for when payments are made because of the balance field
    #   Recalculate Invoice Totals      #  // Vouchers cannot be accounted for update voucher and insert voucher
    #####################################  // Vouchers are not discounted on purpose
                                           // this works for all tax systems because tax specific calculations are done on a per row basis in invoice:edit TPL

    public function recalculateTotals($invoice_id) {

        $items_subtotals        = $this->getItemsSubtotals($invoice_id);
        $voucher_subtotals      = $this->app->components->voucher->getInvoiceVouchersSubtotals($invoice_id);
        $payments_subtotal      = $this->app->components->report->sumPayments('date', null, null, null, 'valid', 'invoice', null, null, null, null, $invoice_id);

        $unit_discount          = $items_subtotals['subtotal_discount'];
        $unit_net               = $items_subtotals['subtotal_net'] + $voucher_subtotals['subtotal_net'];
        $unit_tax               = $items_subtotals['subtotal_tax'] + $voucher_subtotals['subtotal_tax'];
        $unit_gross             = $items_subtotals['subtotal_gross'] + $voucher_subtotals['subtotal_gross'];
        $balance                = $unit_gross - $payments_subtotal;

        $sql = "UPDATE ".PRFX."invoice_records SET
                unit_net            =". $this->app->db->qstr( $unit_net            ).",
                unit_discount       =". $this->app->db->qstr( $unit_discount       ).",
                unit_tax            =". $this->app->db->qstr( $unit_tax            ).",
                unit_gross          =". $this->app->db->qstr( $unit_gross          ).",
                balance             =". $this->app->db->qstr( $balance             )."
                WHERE invoice_id    =". $this->app->db->qstr( $invoice_id          );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status - only if required */

        $invoice_details = $this->getRecord($invoice_id);

        // No invoiceable amount, set to pending (if not already)
        if($invoice_details['unit_gross'] == 0 && $invoice_details['status'] != 'pending') {
            $this->updateStatus($invoice_id, 'pending');
        }

        // Has invoiceable amount with no payments, set to unpaid (if not already)
        elseif($invoice_details['unit_gross'] > 0 && $invoice_details['unit_gross'] == $balance && $invoice_details['status'] != 'unpaid') {
            $this->updateStatus($invoice_id, 'unpaid');
        }

        // Has invoiceable amount with partially payment, set to partially paid (if not already)
        elseif($invoice_details['unit_gross'] > 0 && $payments_subtotal > 0 && $payments_subtotal < $invoice_details['unit_gross'] && $invoice_details['status'] != 'partially_paid') {
            $this->updateStatus($invoice_id, 'partially_paid');
        }

        // Has invoicable amount and the payment(s) match the invoiceable amount, set to paid (if not already)
        elseif($invoice_details['unit_gross'] > 0 && $invoice_details['unit_gross'] == $payments_subtotal && $invoice_details['status'] != 'paid') {
            $this->updateStatus($invoice_id, 'paid');
        }

        return;

    }

    ##############################################
    #   Upload Prefill items using a CSV file    #
    ##############################################

    public function uploadPrefillItemsCsv($empty_prefill_items_table) {

        $error_flag = false;

        // Allowed extensions
        $allowedExt = array('csv');

        // Allowed mime types
        $allowedMime = array('text/csv', 'application/vnd.ms-excel'); // 'text/plain' seems a bit dangerous to have enabled

        // Max Allowed Size (bytes) (2097152 = 2MB)
        $maxAllowedSize = 2097152;

        // Check there is an uploaded file
        if($_FILES['invoice_prefill_csv']['size'] = 0) {
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There was no csv uploaded."));
        }

        // Check for file submission errors
        if ($_FILES['invoice_prefill_csv']['error'] > 0 ) {
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Files submission error with Return Code").': ' . $_FILES['invoice_prefill_csv']['error'] . '<br />');
        }

        // Get file extension
        $filename_info = pathinfo($_FILES['invoice_prefill_csv']['name']);
        $fileExtension = $filename_info['extension'];

        // Validate the uploaded file is an allowed file type
        if (!in_array($fileExtension, $allowedExt)) {
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("Failed to upload the new csv because it does not have an allowed file extension."));
        }

        // Validate the uploaded file is allowed mime type
        if (!in_array($_FILES['invoice_prefill_csv']['type'], $allowedMime)) {
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("Failed to upload the new csv because it does not have an allowed mime type."));
        }

        // Validate the uploaded file is not to big
        if ($_FILES['invoice_prefill_csv']['size'] > $maxAllowedSize) {
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("Failed to upload the new logo because it is too large.").' '._gettext("The maximum size is ").' '.($maxAllowedSize/1024/1024).'MB');
        }

        // If no errors
        if(!$error_flag) {

            // Empty Current Invoice Rates Table (if set)
            if($empty_prefill_items_table) {

                $sql = "TRUNCATE ".PRFX."invoice_prefill_items";

                if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

            }

            // Open CSV file
            $handle = fopen($_FILES['invoice_prefill_csv']['tmp_name'], 'r');

            // Row counter to allow for header line
            $row = 1;

            // Read CSV data and insert into database
            while (($data = fgetcsv($handle)) !== FALSE) {

                // Skip the first line with the column names
                if($row == 1) {
                    $row++;
                    continue;
                }

                $sql = "INSERT INTO ".PRFX."invoice_prefill_items(description, unit_net, active) VALUES ('$data[0]','$data[1]','$data[2]')";

                if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

                $row++;

            }

            // Close CSV file
            fclose($handle);

            // Delete CSV file - not sure this is needed becaus eit is temp
            unlink($_FILES['invoice_prefill_csv']['tmp_name']);

            // Success Message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("The invoice prefill items were sucessfully updated."));

            // Log activity
            $this->app->system->general->writeRecordToActivityLog(_gettext("Invoice Prefill Items were uploaded via csv by").' '.$this->app->user->login_display_name.'.');

            return true;


        } else {

            /*
            echo "Upload: "    . $_FILES['invoice_prefill_csv']['name']           . '<br />';
            echo "Type: "      . $_FILES['invoice_prefill_csv']['type']           . '<br />';
            echo "Size: "      . ($_FILES['invoice_prefill_csv']['size'] / 1024)  . ' Kb<br />';
            echo "Temp file: " . $_FILES['invoice_prefill_csv']['tmp_name']       . '<br />';
            echo "Stored in: " . MEDIA_DIR . $_FILES['file']['name']       ;
             */

            //$this->app->system->page->forceErrorPage('file', __FILE__, __FUNCTION__, '', '', _gettext("Failed to update the invoice prefill items because the submitted file was invalid."));
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("The invoice prefill items have not been changed."));

            return false;

        }

    }

    ##################################################
    #   Export Invoice Prefill Items as a CSV file   #
    ##################################################

    public function exportPrefillItemsCsv() {

        $sql = "SELECT description, unit_net, active FROM ".PRFX."invoice_prefill_items";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $prefill_items = $rs->GetArray();

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=qwcrm_invoice_prefill_items.csv');

        // create a file pointer connected to the output stream
        $output_stream = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output_stream, array(_gettext("Description"), _gettext("Unit Net"), _gettext("Active")));

        // loop over the rows, outputting them
        foreach($prefill_items as $key => $value) {
            $row = array($value['description'], $value['unit_net'], $value['active']);
            fputcsv($output_stream, $row);
        }

        // close the csv file
        fclose($output_stream);

        // Log activity
        $this->app->system->general->writeRecordToActivityLog(_gettext("Invoice Prefill Items were exported by").' '.$this->app->user->login_display_name.'.');

    }


    #########################################
    # Assign Invoice to another employee  #
    #########################################

    public function assignToEmployee($invoice_id, $target_employee_id) {

        // Unify Dates and Times
        $timestamp = time();

        // get the invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // if the new employee is the same as the current one, exit
        if($target_employee_id == $invoice_details['employee_id']) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new employee is the same as the current employee."));
            return false;
        }

        // only change invoice status if unassigned
        if($invoice_details['status'] == 'unassigned') {

            $sql = "UPDATE ".PRFX."invoice_records SET
                    employee_id         =". $this->app->db->qStr( $target_employee_id  ).",
                    status              =". $this->app->db->qStr( 'assigned'           )."
                    WHERE invoice_id    =". $this->app->db->qStr( $invoice_id          );

        // Keep the same invoice status
        } else {

            $sql = "UPDATE ".PRFX."invoice_records SET
                    employee_id         =". $this->app->db->qStr( $target_employee_id  )."
                    WHERE invoice_id    =". $this->app->db->qStr( $invoice_id          );

        }

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Assigned employee success message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Assigned employee updated."));

        // Get Logged in Employee's Display Name
        $logged_in_employee_display_name = $this->app->user->login_display_name;

        // Get the currently assigned employee ID
        $assigned_employee_id = $invoice_details['employee_id'];

        // Get the Display Name of the currently Assigned Employee
        if($assigned_employee_id == null){
            $assigned_employee_display_name = _gettext("Unassigned");
        } else {
            $assigned_employee_display_name = $this->app->components->user->getRecord($assigned_employee_id, 'display_name');
        }

        // Get the Display Name of the Target Employee
        $target_employee_display_name = $this->app->components->user->getRecord($target_employee_id, 'display_name');

        // Creates a History record
        $this->app->components->workorder->insertHistory($invoice_details['workorder_id'], _gettext("Invoice").' '.$invoice_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.');

        // Log activity
        $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $target_employee_id, $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update last active record
        $this->updateLastActive($invoice_id, $timestamp);
        $this->app->components->user->updateLastActive($invoice_details['employee_id'], $timestamp);
        $this->app->components->user->updateLastActive($target_employee_id, $timestamp);
        $this->app->components->client->updateLastActive($invoice_details['client_id'], $timestamp);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id'], $timestamp);

        return true;

    }

    ####################################################################
    #   Check invoice items VAT Tax Codes are all enabled              #
    ####################################################################

    public function checkVatTaxCodeStatuses($invoice_id) {

        $state_flag = true;

        foreach ($this->getItems($invoice_id) as $key => $value) {
            if(!$this->app->components->company->getVatTaxCodeStatus($value['vat_tax_code'])) { $state_flag = false;}
        }

        return $state_flag;

    }

    #########################################
    #  Build additional_info JSON           #
    #########################################

     public function buildAdditionalInfoJson($invoice_id, $reason_for_cancelling = null) {

        // Make sure we merge current data from the database - decodes as an array even if empty
        $additional_info = json_decode($this->app->components->invoice->getRecord($invoice_id, 'additional_info'), true);

        // Add reason for cancelling
        if($reason_for_cancelling)
        {
            $additional_info['reason_for_cancelling'] = $reason_for_cancelling;
        }

        // Return as a JSON object
        return json_encode($additional_info, JSON_FORCE_OBJECT);

    }

}
