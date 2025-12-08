<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/**
 * Description of Creditnote
 *
 * @author shoulders
 */
class Creditnote extends Components {

        /** Insert Functions **/

    ##################################### done
    #     insert creditnote             #
    #####################################

    public function insertRecord($qform) {

        // Unify Dates and Times
        $timestamp = time();

        // Generate the creditnote expiry date
        $dateObject = new DateTime();
        $dateObject->modify('+'.$this->app->components->company->getRecord('creditnote_expiry_offset').' days');
        $expiry_date = $dateObject->format('Y-m-d');

        $sql = "INSERT INTO ".PRFX."creditnote_records SET
                employee_id     =". $this->app->db->qStr( $this->app->user->login_user_id   ).",
                client_id       =". $this->app->db->qStr( $qform['client_id']                          ).",
                invoice_id      =". $this->app->db->qStr( $qform['invoice_id']                         ).",
                supplier_id     =". $this->app->db->qStr( $qform['supplier_id']                         ).",
                expense_id      =". $this->app->db->qStr( $qform['expense_id']                         ).",
                date            =". $this->app->db->qStr( $this->app->system->general->mysqlDate($timestamp)).",
                expiry_date     =". $this->app->db->qStr( $expiry_date ).",
                type            =". $this->app->db->qStr( $qform['type']                         ).",
                reference       =". $this->app->db->qStr( $qform['reference']                         ).",
                tax_system      =". $this->app->db->qStr( QW_TAX_SYSTEM                          ).",
                sales_tax_rate  =". $this->app->db->qStr( $qform['sales_tax_rate']                      ).",
                status          =". $this->app->db->qStr( 'pending'                            ).",
                opened_on       =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime($timestamp)           ).",
                additional_info =". $this->app->db->qStr( '{}'                                 );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Get creditnote_id
        $creditnote_id = $this->app->db->Insert_ID();

        // Create a Workorder History Note - this is not a work order
        //$this->app->components->workorder->insertHistory($workorder_id, _gettext("Credit Note").' '.$creditnote_id.' '._gettext("was created for this Work Order").' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Credit Note").' '.$creditnote_id.' '._gettext("was created by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $qform['client_id'], null, $qform['invoice_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($qform['client_id'], $timestamp);
        $this->app->components->invoice->updateLastActive($qform['invoice_id'], $timestamp);
        $this->app->components->supplier->updateLastActive($qform['supplier_id'], $timestamp);

        return $creditnote_id;

    }


    #####################################
    #     Insert Items                  #  // Some or all of these calculations are done on the creditnote:edit page - This extra code might not be needed in the future
    #####################################  done

    public function insertItems($creditnote_id, $items = null) {

        // Get Creditnote Details
        $creditnote_details = $this->getRecord($creditnote_id);

        // Delete all items from the creditnote to prevent duplication
        $sql = "DELETE FROM ".PRFX."creditnote_items WHERE creditnote_id=".$this->app->db->qStr($creditnote_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Insert Items/Rows into database (if any)
        if($items) {

            $sql = "INSERT INTO `".PRFX."creditnote_items` (`creditnote_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `unit_discount`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `subtotal_net`, `subtotal_tax`, `subtotal_gross`) VALUES ";

            foreach($items as $item) {

                // Correct Sales Tax Exempt indicator
                $sales_tax_exempt = isset($item['sales_tax_exempt']) ? 1 : 0;

                // Add in missing vat_tax_codes (i.e. submissions from 'no_tax' and 'sales_tax_cash' dont have VAT codes)
                $vat_tax_code = $item['vat_tax_code'] ?? $this->app->components->company->getDefaultVatTaxCode($creditnote_details['tax_system']);

                $sql .="(".
                        $this->app->db->qStr( $creditnote_id                    ).",".
                        $this->app->db->qStr( $creditnote_details['tax_system'] ).",".
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

    /** Get Functions **/

    ######################################### done
    #     Display Credit Notes              #
    #########################################

    public function getRecords($order_by, $direction, $records_per_page = 0, $use_pages = false, $page_no = null, $search_category = 'creditnote_id', $search_term = null, $status = null, $employee_id = null, $client_id = null, $supplier_id = null, $invoice_id = null, $expense_id = null, $redeemed_client_id = null, $redeemed_supplier_id = null, $redeemed_invoice_id = null, $redeemed_expense_id = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."creditnote_records.creditnote_id\n";
        $havingTheseRecords = '';
        $redeemedHavingTheseRecords = '';
        $redeemedWhereTheseRecords = '';

        // Restrict results by search category (employee) and search term
        if($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (client - created from) and search term
        elseif($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (supplier - created from) and search term
        elseif($search_category == 'supplier_display_name') {$havingTheseRecords .= " HAVING supplier_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (Invoice ID - created from) and search term
        elseif($search_category == 'invoice_id') {$havingTheseRecords .= " HAVING invoice_id = ".$this->app->db->qStr($search_term);}

        // Restrict results by search category (Expense ID - created from) and search term
        elseif($search_category == 'expense_id') {$havingTheseRecords .= " HAVING expense_id = ".$this->app->db->qStr($search_term);}

        // Restrict results by search category (client - redeemed against) and search term
        elseif($search_category == 'redeemed_client_display_name') {$redeemedHavingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (Invoice ID - redeemed against) and search term
        elseif($search_category == 'redeemed_invoice_id') {$redeemedWhereTheseRecords .= " WHERE invoice_id = ".$this->app->db->qStr($search_term);}

        // Restrict results by search category (supplier - redeemed against) and search term
        elseif($search_category == 'redeemed_supplier_display_name') {$redeemedHavingTheseRecords .= " HAVING supplier_display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category (Expense ID - redeemed against) and search term
        elseif($search_category == 'redeemed_expense_id') {$redeemedWhereTheseRecords .= " WHERE expense_id = ".$this->app->db->qStr($search_term);}

        // Restrict results by search category (creditnote items) and search term
        elseif($search_category == 'creditnote_items') {$havingTheseRecords .= " HAVING creditnote_items LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict results by search category and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."creditnote_records.$search_category LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict by Status
        if($status)
        {
            // All Open Credit Notes
            if($status == 'open') {

                $whereTheseRecords .= " AND ".PRFX."creditnote_records.is_closed != '1'";

            // All Closed Credit Notes
            } elseif($status == 'closed') {

                $whereTheseRecords .= " AND ".PRFX."creditnote_records.is_closed = '1'";

            // Return Credit Notes for the given status
            } else {

                $whereTheseRecords .= " AND ".PRFX."creditnote_records.status= ".$this->app->db->qStr($status);

            }
        }

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."creditnote_records.employee_id=".$this->app->db->qStr($employee_id);}

        // Restrict by Client (created from)
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."creditnote_records.client_id=".$this->app->db->qStr($client_id);}

        // Restrict by Invoice (created from)
        if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."creditnote_records.invoice_id=".$this->app->db->qStr($invoice_id);}

        // Restrict by Supplier (created from)
        if($supplier_id) {$whereTheseRecords .= " AND ".PRFX."creditnote_records.supplier_id=".$this->app->db->qStr($supplier_id);}

        // Restrict by Expense (created from)
        if($expense_id) {$whereTheseRecords .= " AND ".PRFX."creditnote_records.expense_id=".$this->app->db->qStr($expense_id);}

        // The SQL code
        $sql = "SELECT ".PRFX."creditnote_records.*,

            IF(".PRFX."client_records.company_name !='', ".PRFX."client_records.company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,
            ".PRFX."client_records.first_name AS client_first_name,
            ".PRFX."client_records.last_name AS client_last_name,
            ".PRFX."client_records.primary_phone AS client_phone,
            ".PRFX."client_records.mobile_phone AS client_mobile_phone,

            IF(".PRFX."supplier_records.company_name !='', ".PRFX."supplier_records.company_name, CONCAT(".PRFX."supplier_records.first_name, ' ', ".PRFX."supplier_records.last_name)) AS supplier_display_name,
            ".PRFX."supplier_records.first_name AS supplier_first_name,
            ".PRFX."supplier_records.last_name AS supplier_last_name,
            ".PRFX."supplier_records.primary_phone AS supplier_phone,
            ".PRFX."supplier_records.mobile_phone AS supplier_mobile_phone,

            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name,
            ".PRFX."user_records.work_primary_phone AS employee_work_primary_phone,
            ".PRFX."user_records.work_mobile_phone AS employee_work_mobile_phone,
            ".PRFX."user_records.home_mobile_phone AS employee_home_mobile_phone,

            items.combined as creditnote_items,
            redemptions

            FROM ".PRFX."creditnote_records

            ";

        // (sub records) Restrict credit note records by a redemptions metric
        if
            (
                // Has a record filter been supplied
                $redeemed_client_id || $redeemed_supplier_id || $redeemed_invoice_id || $redeemed_expense_id ||

                // Has a restricted search been requested
                in_array($search_category, array('redeemed_client_display_name', 'redeemed_supplier_display_name', 'redeemed_invoice_id', 'redeemed_expense_id'))
            )
            {
                $sql .="RIGHT JOIN";
            }

        // (sub records) If no redemption restrictions are applied, return all credit note records with their redemptions - this is uszed on standard search page
        else
        {
            $sql .="LEFT JOIN";
        }

        // (sub records) Common code for linking by sub records
        $sql .="
            (
                SELECT ".PRFX."payment_records.creditnote_id,
                IF(".PRFX."client_records.company_name !='', ".PRFX."client_records.company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,
                IF(".PRFX."supplier_records.company_name !='', ".PRFX."supplier_records.company_name, CONCAT(".PRFX."supplier_records.first_name, ' ', ".PRFX."supplier_records.last_name)) AS supplier_display_name,
                ".PRFX."payment_records.invoice_id,
                ".PRFX."payment_records.expense_id,
                CONCAT('[',
                    GROUP_CONCAT(
                        JSON_OBJECT(
                            'payment_id', ".PRFX."payment_records.payment_id
                            ,'redeemed_client_id', ".PRFX."payment_records.client_id
                            ,'redeemed_supplier_id', ".PRFX."payment_records.supplier_id
                            ,'redeemed_invoice_id', ".PRFX."payment_records.invoice_id
                            ,'redeemed_expense_id', ".PRFX."payment_records.expense_id
                            ,'redeemed_on', ".PRFX."payment_records.date
                            )
                        SEPARATOR ',')
                ,']') AS redemptions
                FROM ".PRFX."payment_records
                LEFT JOIN ".PRFX."client_records ON ".PRFX."payment_records.client_id = ".PRFX."client_records.client_id
                LEFT JOIN ".PRFX."supplier_records ON ".PRFX."payment_records.supplier_id = ".PRFX."supplier_records.supplier_id
                ".$redeemedWhereTheseRecords."
                GROUP BY ".PRFX."payment_records.creditnote_id
                ".$redeemedHavingTheseRecords."
                ORDER BY ".PRFX."payment_records.creditnote_id
                ASC
            ) AS payment_records
            ON ".PRFX."creditnote_records.creditnote_id = payment_records.creditnote_id
            ";

        // Link database tables
        $sql .="
            LEFT JOIN ".PRFX."client_records ON ".PRFX."creditnote_records.client_id = ".PRFX."client_records.client_id
            LEFT JOIN ".PRFX."user_records ON ".PRFX."creditnote_records.employee_id = ".PRFX."user_records.user_id
            LEFT JOIN ".PRFX."supplier_records ON ".PRFX."creditnote_records.supplier_id = ".PRFX."supplier_records.supplier_id
            LEFT JOIN (
                SELECT ".PRFX."creditnote_items.creditnote_id,
                GROUP_CONCAT(
                    CONCAT(".PRFX."creditnote_items.unit_qty, ' x ', ".PRFX."creditnote_items.description)
                    ORDER BY ".PRFX."creditnote_items.creditnote_item_id
                    ASC
                    SEPARATOR '|||'
                ) AS combined
                FROM ".PRFX."creditnote_items
                GROUP BY ".PRFX."creditnote_items.creditnote_id
                ORDER BY ".PRFX."creditnote_items.creditnote_id
                ASC
            ) AS items
            ON ".PRFX."creditnote_records.creditnote_id = items.creditnote_id

            ".$whereTheseRecords."
            GROUP BY ".PRFX."creditnote_records.".$order_by."
            ".$havingTheseRecords."
            ORDER BY ".PRFX."creditnote_records.".$order_by."
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
    #   Get Credit Note details         #
    #####################################

    public function getRecord($creditnote_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."creditnote_records WHERE creditnote_id =".$this->app->db->qStr($creditnote_id);

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


    ######################################### done
    #   Get All Credit Note items           #
    #########################################

    public function getItems($creditnote_id) {

        $sql = "SELECT * FROM ".PRFX."creditnote_items WHERE creditnote_id=".$this->app->db->qStr($creditnote_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($rs->recordCount())
        {
            return $rs->GetArray();
        }
        else
        {
            return array();
        }
    }

    #######################################  done
    #   Get Credit Note item details      #  // not used anywhere
    #######################################

    public function getItem($creditnote_item_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."creditnote_items WHERE creditnote_item_id =".$this->app->db->qStr($creditnote_item_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!$item){

            return $rs->GetRowAssoc();

        } else {

            return $rs->fields[$item];

        }

    }

    ############################################ done
    #   Get Credit Note items Sub Totals       #
    ############################################

    public function getItemsSubtotals($creditnote_id) {

        // I could use $this->app->components->report->creditnoteItemSum() - with additional calculation for subtotal_discount
        // NB: i dont think i need the aliases
        // $creditnote_items_subtotals = $this->app->components->report->creditnoteGetStats('items', null, null, null, null, null, $invoice_id);

        $sql = "SELECT
                SUM(unit_discount * unit_qty) AS subtotal_discount,
                SUM(subtotal_net) AS subtotal_net,
                SUM(subtotal_tax) AS subtotal_tax,
                SUM(subtotal_gross) AS subtotal_gross
                FROM ".PRFX."creditnote_items
                WHERE creditnote_id=". $this->app->db->qStr($creditnote_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetRowAssoc();

    }


    ##################################### done
    #    Get Credit Note Statuses       #
    #####################################

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."creditnote_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    ########################################### done
    #  Get Credit Note status display name    #
    ###########################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."creditnote_statuses WHERE status_key=".$this->app->db->qStr($status_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];

    }

    ##################################### done
    #    Get Credit note Types          #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."creditnote_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }


    /** Update Functions **/

    ##################################### done
    #   Update Credit Note              #  // Update the totals for the credit note (calculations are done onpage)
    #####################################

    public function updateRecord($qform) {

        // Unify Dates and Times
        $timestamp = time();

        $sql = "UPDATE ".PRFX."creditnote_records SET
                date                =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['date'])     ).",
                expiry_date         =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['expiry_date']) ).",
                reference           =". $this->app->db->qStr( $qform['reference']   ).",
                unit_discount       =". $this->app->db->qStr( $qform['unit_discount']   ).",
                unit_net            =". $this->app->db->qStr( $qform['unit_net']            ).",
                unit_tax            =". $this->app->db->qStr( $qform['unit_tax']            ).",
                unit_gross          =". $this->app->db->qStr( $qform['unit_gross']          ).",
                note                =". $this->app->db->qStr( $qform['note']          )."
                WHERE creditnote_id    =". $this->app->db->qStr( $qform['creditnote_id']          );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $creditnote_details = $this->getRecord($qform['creditnote_id']);

        // Create a Workorder History Note
        //$this->app->components->workorder->insertHistory($invoice_details['workorder_id'], _gettext("Invoice").' '.$invoice_details['invoice_id'].' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Credit Note").' '.$creditnote_details['creditnote_id'].' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $creditnote_details['employee_id'], $creditnote_details['client_id'], null, $creditnote_details['invoice_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($this->getRecord($creditnote_details['creditnote_id'], 'client_id'), $timestamp);
        $this->app->components->invoice->updateLastActive($this->getRecord($creditnote_details['creditnote_id'], 'invoice_id'), $timestamp);

        return;

    }

    /*####################################
    #   update invoice static values   #  // This is used when a user updates an invoice before any payments
    ####################################

    public function updateStaticValues($invoice_id, $date, $due_date) {

        // Unify Dates and Times
        $timestamp = time();

        $sql = "UPDATE ".PRFX."creditnote_records SET
                date                =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($date)     ).",
                due_date            =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($due_date) )."
                WHERE creditnote_id    =". $this->app->db->qStr( $invoice_id                   );

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

    ################################ done
    # Update Credit Note Status    #
    ################################

    public function updateStatus($creditnote_id, $new_status) {

        // Unify Dates and Times
        $timestamp = time();

        // Get credit note details
        $creditnote_details = $this->getRecord($creditnote_id);

        // If the new status is the same as the current one, exit
        if($new_status == $creditnote_details['status']) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new credit note status is the same as the current credit note status."));
            return false;
        }

        // Set the appropriate employee_id
        $employee_id = ($new_status == 'unassigned') ? null : $creditnote_details['employee_id'];

        // Set closed statuses
        if($new_status == 'used' || $new_status == 'cancelled' || $new_status == 'deleted') {
            $closed_on = $this->app->db->qStr($this->app->system->general->mysqlDatetime($timestamp) );
            $is_closed = $this->app->db->qStr(1);
        } else {
            $closed_on = null;
            $is_closed = $this->app->db->qStr(0);
        }

        $sql = "UPDATE ".PRFX."creditnote_records SET
                employee_id         =". $this->app->db->qStr($employee_id).",
                status              =". $this->app->db->qStr( $new_status  ).",
                closed_on           =". $this->app->db->qStr( $closed_on    ).",
                is_closed           =". $this->app->db->qStr( $is_closed    )."
                WHERE creditnote_id =". $this->app->db->qStr( $creditnote_id   );
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Status updated message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Credit Note status updated."));

        // For writing message to log file, get creditnote status display name
        $inv_status_diplay_name = _gettext($this->getStatusDisplayName($new_status));

        // Create a Workorder History Note     - not workorder
        //$this->app->components->workorder->insertHistory($creditnote_details['workorder_id'], _gettext("Invoice Status updated to").' '.$inv_status_diplay_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Credit Note").' '.$creditnote_id.' '._gettext("Status updated to").' '.$inv_status_diplay_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $creditnote_details['employee_id'], $creditnote_details['client_id'], null, $creditnote_details['invoice_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($creditnote_details['client_id'], $timestamp);
        $this->app->components->invoice->updateLastActive($creditnote_details['invoice_id'], $timestamp);


        return true;

    }

    ################################# done
    #    Update Last Active         #
    #################################

    public function updateLastActive($creditnote_id, $timestamp = null) {

        $sql = "UPDATE ".PRFX."creditnote_records SET
                last_active=".$this->app->db->qStr( $this->app->system->general->mysqlDatetime($timestamp) )."
                WHERE creditnote_id=".$this->app->db->qStr($creditnote_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    ###################################  if you send a new key/pair, it will be added
    #    update additional info       #  if you send an existing key/pair the value will be updated
    ###################################  if you send a key/pair with a null value, it will be removed

    public function updateAdditionalInfo($creditnote_id, array $new_additional_info = array()) {

        // Make sure we merge current data from the database, decode as an array even if empty
        $current_additional_info = json_decode($this->getRecord($creditnote_id, 'additional_info'), true);

        // Merge arrays
        $additional_info = array_merge($current_additional_info, $new_additional_info);

        // Remove all entries defined as null
        $additional_info = array_filter($additional_info, function($var) {return ($var !== null);});

        $sql = "UPDATE ".PRFX."creditnote_records SET
                additional_info=".$this->app->db->qStr(json_encode($additional_info, JSON_FORCE_OBJECT))."
                WHERE creditnote_id=".$this->app->db->qStr($creditnote_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    /** Close Functions **/

    ##################################### done
    #   Cancel Credit Note              # // This does not delete information i.e. client went bust and did not pay
    #####################################

    public function cancelRecord($creditnote_id, $reason_for_cancelling) {

        // Unify Dates and Times
        $timestamp = time();

        // Make sure the creditnote can be cancelled
        if(!$this->checkRecordAllowsCancel($creditnote_id)) {
            return false;
        }

        // Get creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);

        // Change the creditnote status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($creditnote_id, 'cancelled');

        // Add Cancelled message to the additional info
        $this->updateAdditionalInfo($creditnote_id, array('reason_for_cancelling' => $reason_for_cancelling));

        // Create a Workorder History Note  - this is an invoice
        //$this->app->components->workorder->insertHistory($invoice_details['invoice_id'], _gettext("Invoice").' '.$invoice_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Credit Note").' '.$creditnote_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $creditnote_details['employee_id'], $creditnote_details['client_id'], null, $creditnote_details['invoice_id']);

        // Update last active record
        $this->updateLastActive($creditnote_id, $timestamp);
        $this->app->components->client->updateLastActive($creditnote_details['client_id'], $timestamp);
        $this->app->components->invoice->updateLastActive($creditnote_details['invoice_id'], $timestamp);
        $this->app->components->supplier->updateLastActive($creditnote_details['supplier_id'], $timestamp);
        $this->app->components->expense->updateLastActive($creditnote_details['expense_id'], $timestamp);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #   Delete Credit Note              #
    #####################################

    public function deleteRecord($creditnote_id) {

        // Unify Dates and Times
        $timestamp = time();

        // Make sure the creditnote can be deleted
        if(!$this->checkRecordAllowsDelete($creditnote_id)) {
            return false;
        }

        // Get creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);

        // Delete creditnote items
        $this->deleteItems($creditnote_id);

        // Change the creditnote status to deleted - This triggers certain other routines
        $this->updateStatus($creditnote_id, 'deleted');

        // Delete record items
        $sql = "DELETE FROM `".PRFX."creditnote_items` WHERE `".PRFX."creditnote_items`.`creditnote_id` = $creditnote_id";
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Delete Main record
        $sql = "UPDATE ".PRFX."creditnote_records SET
                employee_id         = NULL,
                client_id           = NULL,
                invoice_id          = NULL,
                supplier_id         = NULL,
                expense_id          = NULL,
                date                = NULL,
                expiry_date         = NULL,
                type                = '',
                reference           = '',
                tax_system          = '',
                unit_net            = 0.00,
                unit_discount       = 0.00,
                sales_tax_rate      = 0.00,
                unit_tax            = 0.00,
                unit_gross          = 0.00,
                balance             = 0.00,
                status              = 'deleted',
                opened_on           = NULL,
                closed_on           = NULL,
                last_active         = NULL,
                is_closed           = 1,
                reference           = '',
                note                = '',
                additional_info     = ''
                WHERE creditnote_id    =". $this->app->db->qStr( $creditnote_id  );
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Create a Workorder History Note  - this is not a workorder
        //$this->app->components->workorder->insertHistory($invoice_id, _gettext("Invoice").' '.$invoice_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.');

        // Log activity
        $record = _gettext("Credit Note").' '.$creditnote_details['creditnote_id'].' ';
        $record .= _gettext("was deleted by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $creditnote_details['employee_id'], $creditnote_details['client_id'], null, $creditnote_details['invoice_id']);

        // Update last active record
        $this->updateLastActive($creditnote_id, $timestamp);
        $this->app->components->supplier->updateLastActive($creditnote_details['supplier_id'], $timestamp);
        $this->app->components->client->updateLastActive($creditnote_details['client_id'], $timestamp);


        return true;

    }

    ############################################# done
    #   Delete an creditnotes's Items (ALL)     #
    #############################################

    public function deleteItems($creditnote_id) {

        $sql = "DELETE FROM ".PRFX."creditnote_items WHERE creditnote_id=" . $this->app->db->qStr($creditnote_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return true;

    }

    /** Check Functions **/

    ########################################################  done
    #   Check all creditnotes to see if any have expired   #   // This does a live check to see if the creditnotes are expired and tagged as such
    ########################################################

    public function checkAllCreditnotesForExpiry() {

        $sql = "SELECT creditnote_id, status
                FROM ".PRFX."creditnote_records
                ";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        while(!$rs->EOF) {

            $this->checkCreditnoteIsExpired($rs->fields['creditnote_id']);

            // Advance the loop to the next record
            $rs->MoveNext();

        }

        return;

    }

    ################################################# // This does a live check to see if the creditnote is expired
    #   Check to see if the creditnote is expired   # // This function will update the credit note status as required
    ################################################# done

    public function checkCreditnoteIsExpired($creditnote_id) {

        $expired_status = false;
        $creditnote_details = $this->getRecord($creditnote_id);

        // Is the creditnote deleted
        if($creditnote_details['status'] === 'deleted')
        {
            $expired_status = true;
        }

        // Has the creditnote expired
        elseif (time() > strtotime($creditnote_details['expiry_date'].' 23:59:59'))
        {
            // Has the credit note been closed, if not update `closed_on` to match expiry date
            if(!$creditnote_details['closed_on']) {

                // Update the credit note record (we don't update the status when they are expired, these are different things)
                // ('blocked' is a way of disabling the voucher without permanently closing it, i.e. for suspended status, and is controlled by Expiry and Status)
                $sql = "UPDATE ".PRFX."voucher_records SET
                    closed_on           =".$this->app->db->qstr($creditnote_details['expiry_date'].' 23:59:59')."
                    WHERE voucher_id    =". $this->app->db->qstr( $creditnote_id          );
                if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

            }

            $expired_status = true;

        }

        // Returned the expired status
        return $expired_status;

    }

    ############################################ // this could be put in general with data stuff
    #  Check Credit note Expiry is valid       #
    ############################################ done

    function checkCreditnoteExpiryIsValid($expiry_date)
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

    ###############################################   done
    #  Check if a credit note can be created      #  // Used to hide create CR buttons + in creditnote:new
    ###############################################

    public function checkRecordCanBeCreated($client_id = null, $invoice_id = null, $supplier_id = null, $expense_id = null, $silent = false) {

        $state_flag = true;

        // Only allow one source ID for a creditnote check (This prevents submission errors and manipulation)
        if(((bool)$client_id + (bool)$invoice_id + (bool)$supplier_id + (bool)$expense_id) > 1)
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("More than one datasource was provided so validation cannot take place and so the credit note creation will not be allowed."));
            $state_flag = false;

            // Immediate return as the request is faulty
            return $state_flag;
        }

        // Get and complete the records to allow the logic below to work / Fill in the blanks
        if($invoice_id) {
            $invoice_details = $this->app->components->invoice->getRecord($invoice_id);
            $client_id = $invoice_details['client_id'];
        }
        elseif($expense_id) {
            $expense_details = $this->app->components->expense->getRecord($expense_id);
            $supplier_id = $expense_details['supplier_id'];
        }

        /** Sales Credit Notes **/

        if($client_id)
        {
            /* Common Tests */

            // Is the Client active
            if(!$this->app->components->client->getRecord($client_id, 'active'))
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The client is not active so you cannot create a credit note against it.", $silent));
                $state_flag = false;
            }

            // Check there are no pending credit notes attached to the client
            if($this->app->components->report->creditnoteCount(null, null, null, null, 'pending', null, null, null, $client_id))
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The client has a pending credit note assigned to it which needs sorting before you can create another credit note for this client.", $silent));
                $state_flag = false;
            }

            /* Sales Credit Note (Client) - (client:details) */
            // Used to refund real money to a client without an invoice, or they can use the credit to purchase other items

            if(!$invoice_id){

                // Dont allow this type of credit note (for now)
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is type of credit note for clients is not currently allowed. You should not see this error, report to admins.", $silent));
                $state_flag = false;
            }

            /* Sales Credit Note (Invoice) - (invoice:details) */
            // Used to close invoices with outstanding balances without accepting or sending real money

            elseif($invoice_id)
            {
                // Is on a different tax system
                if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be created because the invoice is on another tax system.", $silent));
                    $state_flag = false;
                }

                // Check if there are any open credit notes issued against this invoice (Only 1 open credit note is allowed against this invoice at any time)
                if($this->app->components->report->creditnoteCount(null, null, null, null, 'open', null, null, null, null, null, $invoice_id))
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice already has an open credit note assigned to it.", $silent));
                    $state_flag = false;
                }

                // Status Checks (CR Parent Invoice)
                switch ($invoice_details['status']) {
                    case 'pending':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is pending and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'unpaid':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice has no payments and should be cancelled, not closed with a credit note. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'partially_paid':
                        // Type 1 CR request (Credit) (Used to clear invoice balance) (invoices with no balance should be cancelled and not cleared with a CR)

                        // We are just closing with fake money
                        // All vouchers on invoices with this state are blocked, have never been used or activated and can be voided.
                        // When the CR is created the vouchers will be voided

                        // Do Nothing

                        break;
                    case 'paid':
                        // Type 2 CR request (Debit) (Refund monies to Clients or allow them to use the CR on another of their invoices)

                        // Check all the parent invoice's vouchers can be voided
                        if(!$this->app->components->voucher->checkAllInvoiceSiblingVouchersAllowVoid($invoice_id)){

                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice has vouchers that cannot be voided, so you cannot issue a credit note against this invoice.", $silent));
                            //$this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice has vouchers that cannot be voided so you cannot issue a credit note.", $silent));
                            $state_flag = false;
                        }

                        // Calculate real monies paid on this invoice by the client (excludes credit notes and vouchers, this allows you to close an invoice with a Type 1 CR and not gove free money to a client)
                        $moniesIn = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'invoice', 'real_monies', 'credit', null, null, null, $invoice_id);

                        // Get all payments against this invoice (real monies via credit notes)
                        $moniesOut = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'invoice', null, 'debit', null, null, null, $invoice_id);

                        // Is there any real money left that can be refunded.
                        $moniesThatCanBeRefunded = $moniesIn - $moniesOut;
                        if($moniesThatCanBeRefunded <= 0){
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice has no valid real monies left that can be refunded.", $silent));
                            $state_flag = false;
                        }

                        break;
                    case 'in_dispute':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is pending and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'overdue':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is overdue and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'collections':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is in collections and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'cancelled':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is cancelled and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'deleted':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is deleted and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    default:
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice status does not allow payments. You should not see this error, report to admins.", true));
                        $state_flag = false;
                        break;
                }

            }
        }

        /** Purchase Credit Notes **/

        elseif($supplier_id){

            /* Common Tests */

            // Is the Supplier active
            if($this->app->components->supplier->getRecord($supplier_id, 'status') != 'active')
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The supplier is not active so you cannot create a credit note against it.", $silent));
                $state_flag = false;
            }

            // Check there are no pending credit notes attached to the supplier
            if($this->app->components->report->creditnoteCount(null, null, null, null, 'pending', null, null, null, null, $supplier_id))
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This supplier already has a pending credit note assigned to it.", $silent));
                $state_flag = false;
            }

            /* Purchase Credit Note (Supplier) - (supplier:details) */
            // Used to reduce the amount you owe your supplier, or record a refund received from a supplier.
            // The refund can be in the form of credit with the supplier (via their credit note system) or a real payment such as cash or bank transfer.

            if(!$expense_id) {

                // Dont allow this type of credit note (for now)
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is type of credit note for expenses is not currently allowed. You should not see this error, report to admins.", $silent));
                $state_flag = false;

            }

            /* Purchase Credit Note (Expense) - (expense:details) */
            // Used to reduce the amount you owe on an expense, or record a refund received from a supplier against an expense.
            // The refund can be in the form of credit with the supplier (via their credit note system) or a real payment such as cash or bank transfer.

            elseif($expense_id) {

                // Is on a different tax system
                if($expense_details['tax_system'] != QW_TAX_SYSTEM) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be created because the expense is on another tax system.", $silent));
                    $state_flag = false;
                }

                // Check if there are any open credit notes issued against this expense (Only 1 open credit note is allowed against this exepsne at any time)
                if($this->app->components->report->creditnoteCount(null, null, null, null, 'open', null, null, null, null, null, null, $expense_id))
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense already has an open credit note assigned to it.", $silent));
                    $state_flag = false;
                }

                // Status Checks (CR Parent Expense)
                switch ($expense_details['status']) {
                    case 'pending':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense is pending and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'unpaid':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense has no payments and should be cancelled, not closed with a credit note. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'partially_paid':
                        // Type 1 CR request (Credit) (Used to clear expense balance) (expenses with no balance should be cancelled and not cleared with a CR)

                        // We are just closing with fake money
                        // Do nothing
                        break;
                    case 'paid':
                        // Type 2 CR request (Debit) (Refund monies to Suppliers or allow them to use the CR on another of their expenses)

                        // Calculate real monies paid on this invoice by the client (excludes credit notes and vouchers, this allows you to close an invoice with a Type 1 CR and not gove free money to a client)
                        $moniesIn = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'expense', 'real_monies', 'debit', null, null, null, null, $expense_id);

                        // Get all payments against this invoice (real monies via credit notes)
                        $moniesOut = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'expense', null, 'credit', null, null, null, null, $expense_id);

                        // Is there any real money is there left which can then be refunded.
                        $moniesThatCanBeRefunded = $moniesIn - $moniesOut;
                        if($moniesThatCanBeRefunded <= 0){
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense has no valid real monies left that can be refunded.", $silent));
                            $state_flag = false;
                        }
                        break;
                    case 'cancelled':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense is cancelled and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    case 'deleted':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense is deleted and cannot accept payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                    default:
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense status does not allow payments. You should not see this error, report to admins.", $silent));
                        $state_flag = false;
                        break;
                }
            }
        }

        // Status Fall Back - I don't think this will ever be called, but safety first.
        else
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creation is invalid. You should not see this error, report to admins.", true));
            $state_flag = false;
        }

        return $state_flag;

    }

    #############################################################
    # Validate submitted information before allowing submission #  TODO: do i need silents on this code, probably not
    #############################################################  TODO: change variable names + check the logic flow matchers method submitt

    public function checkRecordCanBeSubmitted($qform)
    {
        $state_flag = true;

        // Allow for CR created from different points
        $client_id = $qform['client_id'] ?? null;
        $invoice_id = $qform['invoice_id'] ?? null;
        $supplier_id = $qform['supplier_id'] ?? null;
        $expense_id = $qform['expense_id'] ?? null;

        /** Sales Credit Notes **/

        if($client_id) {

            /* Common Tests */

            // Is the client active
            if(!$this->app->components->client->getRecord($client_id, 'active')) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this client because they are not active."));
                $state_flag = false;
            }

            /* this check should not be needed as it is done upon creation, not submission
            // Check there are no pending credit notes attached to the client
            if($this->app->components->report->creditnoteCount(null, null, null, null, 'pending', null, null, null, $client_id))
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This client already has a pending credit note."));
                $state_flag = false;
            }*/

            /* Sales Credit Note (Client) - (client:details) */
            // Used to refund real money to a client without an invoice, or they can use the credit to purchase other items

            if(!$invoice_id)
            {
                // Dont allow this type of credit note (for now)
                $state_flag = false;
            }

            /* Sales Credit Note (Invoice) - (invoice:details) */
            // Used to close invoices with outstanding balances without accepting or sending real money

            elseif($invoice_id)
            {
                $invoice_details = $this->app->components->invoice->getRecord($invoice_id);

                // Is on a different tax system
                if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be created because the invoice is on another tax system."));
                    $state_flag = false;
                }

                /* this check should not be needed as it is done upon creation, not submission
                // Check if there are any open credit notes issued against this invoice (Only 1 open credit note is allowed against this invoice at any time)
                if($this->app->components->report->creditnoteCount(null, null, null, null, 'open', null, null, null, null, null, $invoice_id))
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice already has an open credit note assigned to it."));
                    $state_flag = false;
                }*/

                // Status Checks (CR Parent Invoice)
                switch ($invoice_details['status']) {
                    case 'pending':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is pending and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'unpaid':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice has no payments and should be cancelled, not closed with a credit note. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'partially_paid':
                        // Type 1 CR request (Credit) (Used to clear invoice balance) (invoices with no balance should be cancelled and not cleared with a CR)

                        // We are just closing with fake money
                        // All vouchers on invoices with this state are blocked, have never been used or activated and can be voided.
                        // When the CR is created the vouchers will be voided

                        // Make sure the submitted CR total is the same as the parent invoice's remaining balance
                        if($qform['unit_gross'] != $invoice_details['balance']){
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note requires that the amount is equal to the remaining balance on the parent invoice so it can be closed which is ").CURRENCY_SYMBOL.number_format($invoice_details['balance'], 2, '.'));
                            $state_flag = false;
                        }

                        break;
                    case 'paid':
                        // Type 2 CR request (Debit) (Refund monies to Clients or allow them to use the CR on another of their invoices)

                        // Calculate real monies paid on this invoice by the client (excludes credit notes and vouchers, this allows you to close an invoice with a Type 1 CR and not gove free money to a client)
                        $moniesIn = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'invoice', 'real_monies', 'credit', null, null, null, $invoice_id);

                        // Get all payments against this invoice (real monies via credit notes)
                        $moniesOut = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'invoice', null, 'debit', null, null, null, $invoice_id);

                        // How much real money is there left which can then be refunded.
                        $moniesThatCanBeRefunded = $moniesIn - $moniesOut;

                        // There is no real money left to refund
                        if($moniesThatCanBeRefunded <= 0){
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice has no valid real monies left that can be refunded."));
                            $state_flag = false;
                        }

                        // The submitted CR total is greater than real monies left on the parent invoice
                        if($qform['unit_gross'] > $moniesThatCanBeRefunded){
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot submit a credit note with an amount greater than the real monies left on the parent invoice, which is ").CURRENCY_SYMBOL.number_format($moniesThatCanBeRefunded, 2, '.'));
                            $state_flag = false;
                        }

                        break;
                    case 'in_dispute':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is pending and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'overdue':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is overdue and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'collections':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is in collections and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'cancelled':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is cancelled and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'deleted':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is deleted and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    default:
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice status does not allow payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                }
            }
        }

         /** Purchase Credit Notes **/

        elseif($supplier_id) {

            /* Common Tests */

            // Is the supplier active
            if($this->app->components->supplier->getRecord($supplier_id, 'status') != 'active') {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this supplier because they are not active."));
                $state_flag = false;
            }


            /* Purchase Credit Note (Supplier) - (supplier:details) */
            // Used to reduce the amount you owe your supplier, or record a refund received from a supplier.
            // The refund can be in the form of credit with the supplier (via their credit note system) or a real payment such as cash or bank transfer.

            if(!$expense_id)
            {
                /* this check should not be needed as it is done upon creation, not submission
                // Check there are no pending credit notes attached to the supplier
                if($this->app->components->report->creditnoteCount(null, null, null, null, 'pending', null, null, null, null, $supplier_id))
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This supplier already has a pending credit note assigned to it."));
                    $state_flag = false;
                }*/

                // Dont allow this type of credit note (for now)
                $state_flag = false;
            }


            /* Purchase Credit Note (Expense) - (expense:details) */
            // Used to reduce the amount you owe on an expense, or record a refund received from a supplier against an expense.
            // The refund can be in the form of credit with the supplier (via their credit note system) or a real payment such as cash or bank transfer.

            elseif($expense_id)
            {
                /* Common Tests */

                $expense_details = $this->app->components->expense->getRecord($expense_id);

                // Is on a different tax system
                if($expense_details['tax_system'] != QW_TAX_SYSTEM) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be created because the expense is on another tax system."));
                    $state_flag = false;
                }

                /* this check should not be needed as it is done upon creation, not submission
                // Check if there are any open credit notes issued against this expense (Only 1 open credit note is allowed against this exepsne at any time)
                if($this->app->components->report->creditnoteCount(null, null, null, null, 'open', null, null, null, null, null, null, $expense_id))
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense already has an open credit note assigned to it."));
                    $state_flag = false;
                }*/

                // Status Checks (CR Parent Expense)
                switch($expense_details['status']){
                    case 'pending':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense is pending and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'unpaid':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense has no payments and should be cancelled, not closed with a credit note. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'partially_paid':
                        // Type 1 CR request (Credit) (Used to clear expense balance) (expenses with no balance should be cancelled and not cleared with a CR)
                        // We are just closing with fake money

                        // Make sure the submitted CR total is the same as the parent expense's remaining balance
                        if($qform['unit_gross'] != $expense_details['balance']){
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note requires that the amount is equal to the remaining balance on the parent expense so it can be closed, which is ").CURRENCY_SYMBOL.number_format($expense_details['balance'], 2, '.'));
                            $state_flag = false;
                        }

                        break;
                    case 'paid':
                        // Type 2 CR request (Debit) (Refund monies to Suppliers or allow them to use the CR on another of their expenses)

                        // Calculate real monies paid on this invoice by the client (excludes credit notes and vouchers, this allows you to close an invoice with a Type 1 CR and not gove free money to a client)
                        $moniesIn = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'expense', 'real_monies', 'debit', null, null, null, null, $expense_id);

                        // Get all payments against this invoice (real monies via credit notes)
                        $moniesOut = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'expense', null, 'credit', null, null, null, null, $expense_id);

                        // How much real money is there left which can then be refunded.
                        $moniesThatCanBeRefunded = $moniesIn - $moniesOut;
                        if($moniesThatCanBeRefunded <= 0){
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense has no valid real monies left that can be refunded."));
                            $state_flag = false;
                        }

                        // The submitted CR total is greater than real monies left on the parent expense
                        if($qform['unit_gross'] > $moniesThatCanBeRefunded){
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot submit a credit note with an amount greater than the real monies left on the parent expense, which is ").CURRENCY_SYMBOL.number_format($moniesThatCanBeRefunded, 2, '.'));
                            $state_flag = false;
                        }

                        break;
                    case 'cancelled':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense is cancelled and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'deleted':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense is deleted and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    default:
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense status does not allow payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                }

            }

        }

        // Status Fall Back - I don't think this will ever be called, but safety first.
        else
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The submission is invalid. You should not see this error, report to admins."));
            $state_flag = false;
        }

        return $state_flag;

    }

    ############################################################# // Currently no status allows manually change
    #  Check if the creditnote status is allowed to be changed  # // This is probably not needed and the manually change mechanism can be removed for CR can be removed
    ############################################################# done

    public function checkRecordAllowsManualStatusChange($creditnote_id) {

        // Prevent the manual changing of status - this is not a feature i want enabled until i have a use for it
        return false;

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note has expired."));
            $state_flag = false;
        }

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);

        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because it is on a different Tax system."));
            $state_flag = false;
        }

        /* Is the credit note closed (This should not be needed because of expiry and status checks)
        if($creditnote_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note has been closed.", $silent));
        }

        // Status checks
        switch($creditnote_details['status']) {
            case 'pending':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note is pending.", $silent));
                $state_flag = false;
                break;
            case 'unused':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note is unused.", $silent));
                $state_flag = false;
                break;
            case 'partially_used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note has payments and is partially used.", $silent));
                $state_flag = false;
                break;
            case 'used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note has payments and is used.", $silent));
                $state_flag = false;
                break;
            case 'cancelled':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note has been cancelled.", $silent));
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note has been deleted.", $silent));
                $state_flag = false;
                break;
        }

        // Has payments
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', 'creditnote', null, null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note has payments."));
            $state_flag = false;
        }

        // Has been used as a payment
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', null, 'creditnote', null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note status cannot be changed because the credit note has been used as a payment."));
            $state_flag = false;
        }


        return $state_flag;

    }


    ############################################################# done
    #  Check if the creditnote status allows it to be Edited    #
    #############################################################

    public function checkRecordAllowsEdit($creditnote_id) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be edited because it has expired."));
            $state_flag = false;
        }

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);

        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be edited because it is on a different Tax system."));
            $state_flag = false;
        }

        // The current record VAT code is enabled
        if(!$this->checkVatTaxCodeStatuses($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be edited because one or more of it's items have a VAT Tax Code that is not enabled."));
            $state_flag = false;
        }

        /* Is the credit note closed (This should not be needed because of expiry and status checks)
        if($creditnote_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be edited because it has been closed.", $silent));

        }*/

        // Status checks
        switch($creditnote_details['status']) {
            case 'pending':
                break;
            case 'unused':
                break;
            case 'partially_used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be edited because it is partially used."));
                $state_flag = false;
                break;
            case 'used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be edited because it is used."));
                $state_flag = false;
                break;
            case 'cancelled':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be edited because it has been cancelled."));
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be edited because it has been deleted."));
                $state_flag = false;
                break;
        }

        // Has payments
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', 'creditnote', null, null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be edited because it has payments."));
            $state_flag = false;
        }

        // Has been used as a payment
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', null, 'creditnote', null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be edited because it has been used as a payment."));
            $state_flag = false;
        }

        return $state_flag;

    }

    ############################################################### done
    #   Check to see if the creditnote can be cancelled           #
    ###############################################################

    public function checkRecordAllowsCancel($creditnote_id) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be cancelled because it has expired."));
            $state_flag = false;
        }

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);

        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be cancelled because it is on a different Tax system."));
            $state_flag = false;
        }

        // No amount
        if(!(float)$creditnote_details['unit_net']) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be cancelled because it does not have an amount, you should delete instead."));
            $state_flag = false;
        }

        /* Is the credit note closed (This should not be needed because of expiry and status checks)
        if($creditnote_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be cancelled because it has been closed.", $silent));
        }*/

        // Status checks
        switch($creditnote_details['status']) {
            case 'pending':
                break;
            case 'unused':
                break;
            case 'partially_used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be cancelled because it is partially used."));
                $state_flag = false;
                break;
            case 'used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be cancelled because it is used."));
                $state_flag = false;
                break;
            case 'cancelled':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be cancelled because it has already been cancelled."));
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be cancelled because it has been deleted."));
                $state_flag = false;
                break;
        }

        // Has payments
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', 'creditnote', null, null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be cancelled because it has payments."));
            $state_flag = false;
        }

        // Has been used as a payment
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', null, 'creditnote', null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be cancelled because it has been used as a payment."));
            $state_flag = false;
        }

        return $state_flag;

    }

    ############################################################### done
    #   Check to see if the creditnote can be deleted             #
    ###############################################################

    public function checkRecordAllowsDelete($creditnote_id) {

        $state_flag = true;

        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be deleted because it has expired."));
            $state_flag = false;
        }

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);

        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be deleted because it is on a different Tax system."));
            $state_flag = false;
        }

        /* Is the credit note closed (This should not be needed because of expiry and status checks)
        if($creditnote_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be deleted because it has been closed.", $silent));
        }*/

        // Status checks
        switch($creditnote_details['status']) {
            case 'pending':
                break;
            case 'unused':
                break;
            case 'partially_used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be deleted because it is partially used."));
                $state_flag = false;
                break;
            case 'used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be deleted because it is used."));
                $state_flag = false;
                break;
            case 'cancelled':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be deleted because it has been cancelled."));
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be deleted because it has already been deleted."));
                $state_flag = false;
                break;
        }

        // Has payments
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', 'creditnote', null, null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be deleted because it has payments."));
            $state_flag = false;
        }

        // Has been used as a payment
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', null, 'creditnote', null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be deleted because it has been used as a payment."));
            $state_flag = false;
        }

        return $state_flag;

    }


    ##############################################################  // For (closing invoices and expenses | using store credit given to clients and suppliers)
    #  Check if a CR can be used as a payment Method (credit)    #  // This does not handle balance and submitted payment values on purpose
    ##############################################################  // $creditnote_details = the credit note being used, $qpayment = the payment to be used

    public function checkMethodAllowsSubmit(array $creditnote_details, array $qpayment) {

        $state_flag = true;

        // Cannot be used to pay on another Credit Note - This should not be an issue here, but this is just incase
        if(Payment::$type == 'creditnote') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be applied against another credit note. You should not be seeing this message, report to admins."));
            $state_flag = false;

            // Immediate return - no point in any other tests
            return $state_flag;
        }

        /* Cannot be used to pay on itself - This should not be an issue here because you cannot use a CR as a method on and CR record, but this is just incase I change my mind later
        if($creditnote_details['creditnote_id'] ==  $qpayment['creditnote_id'] ?? null) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be applied against itself. You should not be seeing this message, report to admins."));
            $state_flag = false;
            return $state_flag;
        }*/

        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_details['creditnote_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used because it has expired."));
            $state_flag = false;
        }

        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used because it is on a different Tax system."));
            $state_flag = false;
        }

        /* Is the credit note closed (This should not be needed because of expiry and status checks)
        if($creditnote_details['closed_on'])
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used because it has been closed.", $silent));
        }*/

        // Status Checks (Credit Note)
        switch ($creditnote_details['status']) {
            case 'pending':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credi note cannot be used because it is pending."));
                $state_flag = false;
                break;
            case 'unused':
                break;
            case 'partially_used':
                break;
            case 'used':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used because it has already been used."));
                $state_flag = false;
                break;
            case 'cancelled':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used because it has been cancelled."));
                $state_flag = false;
                break;
            case 'deleted':
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used because it has been deleted."));
                $state_flag = false;
                break;
        }

        /** Sales Credit Notes **/

        if($creditnote_details['client_id']) {

            /* Common Tests */

            // Is the client active
            if(!$this->app->components->client->getRecord($creditnote_details['client_id'], 'active')) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this client because they are not active."));
                $state_flag = false;
            }

            // CR can only be applied to any of the specified client's invoices
            if($creditnote_details['client_id'] != $this->app->components->invoice->getRecord($qpayment['invoice_id'], 'client_id')){
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used against this invoice. It can only be used against invoices belonging to the client this credit note was issued to."));
                //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an invoice belonging to the client it is linked with.").' '._gettext("Client").': '.$this->creditnote_details['client_id']);
                $state_flag = false;
            }

            /* Sales Credit Note (Client) - (client:details) */

            if(!$creditnote_details['invoice_id']) {
                // This is not a valid use
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("When using a credit note as a payment method it must be against an Invoice or Expense. This submission does not reference an Invoice. You should not see this error, report to admins."));
                $state_flag = false;
            }

            /* Sales Credit Note (Invoice) - (invoice:details) */

            // Used to reduce the amount a client owes on an expense, or close an expense.
            elseif($creditnote_details['invoice_id']) {

                // Invoice on a different tax system
                if($this->app->components->invoice->getRecord($qpayment['invoice_id'], 'tax_system') != QW_TAX_SYSTEM) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this invoice because it is on a different Tax system."));
                    $state_flag = false;
                }

                // Status Checks (CR Parent Invoice)
                switch ($this->app->components->invoice->getRecord($creditnote_details['invoice_id'], 'status')) {
                    case 'pending':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is pending and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'unpaid':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice has no payments and should be cancelled, not closed with a credit note. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'partially_paid':
                        // Type 1 CR request (Credit) (Used to clear outstanding invoice balances) (invoices with no balance should be cancelled and not cleared with a CR)
                        // A CR raised against an invoice with a partially paid balance is issued to close that invoice only, so it can only be used to close said invoice.

                        // The target invoice must be the invoice the CR was raised against
                        if($creditnote_details['invoice_id'] != $qpayment['invoice_id']) {
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used against this invoice. It must be used to close the invoice it was raised against."));
                            //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against the invoice it is linked with.").' '._gettext("Invoice").': '.$this->creditnote_details['invoice_id']);
                            $state_flag = false;
                        }
                        break;
                    case 'paid':
                        // Type 2 CR request (Debit) (Refund monies to Clients or allow them to use the CR on another of their invoices) (The code here only controls the use of the CR as a payment method)
                        // Do Nothing
                        break;
                    case 'in_dispute':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is pending and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'overdue':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is overdue and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'collections':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is in collections and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'cancelled':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is cancelled and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'deleted':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice is deleted and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    default:
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent invoice status does not allow payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                }

            }
        }

        /** Purchase Credit Notes  **/

        elseif($creditnote_details['supplier_id']){

            /* Common Tests */

            // Is the supplier active
            if($this->app->components->supplier->getRecord($creditnote_details['supplier_id'], 'status') != 'active') {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this supplier because they are not active."));
                $state_flag = false;
            }

            // CR can only be applied to any of the specified suppliers's expenses
            if($creditnote_details['supplier_id'] != $this->app->components->expense->getRecord($qpayment['expense_id'], 'supplier_id')){
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used against this expense. It can only be used against expense belonging to the supplier this credit note was issued to."));
                //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an expense belonging to the supplier it is linked with.").' '._gettext("Supplier").': '.$this->creditnote_details['supplier_id']);
                $state_flag = false;
            }

            /* Purchase Credit Note (Supplier) - (supplier:details) */

            if(!$creditnote_details['expense_id']) {
                // This is not a valid use
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("When using a credit note as a payment method it must be against an Invoice or Expense. This submission does not reference an Expense. You should not see this error, report to admins."));
                $state_flag = false;
            }

            /* Purchase Credit Note (Expense) - (expense:details) */

            // Used to reduce the amount a supplier owes on an expense, or close an expense.
            elseif($creditnote_details['expense_id']){

                // Expense on a different tax system
                if($this->app->components->expense->getRecord($qpayment['expense_id'], 'tax_system') != QW_TAX_SYSTEM) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this expense because it is on a different Tax system."));
                    $state_flag = false;
                }

                // Status Checks (CR Parent Expense)
                switch ($this->app->components->expense->getRecord($creditnote_details['expense_id'], 'status')) {
                    case 'pending':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense is pending and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'unpaid':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense has no payments and should be cancelled, not closed with a credit note. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'partially_paid':
                        // Type 1 CR request (Credit) (Used to clear outstanding expense balances) (expenses with no balance should be cancelled and not cleared with a CR)
                        // A CR raised against an expense with a partially paid balance is issued to close that expense only, so it can only be used to close said expense.
                        // The target expense must be the expense the CR was raised against
                        if($creditnote_details['expense_id'] != $qpayment['expense_id']) {
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used against this expense. It must be used to close the expense it was raised against."));
                            //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against the expense it is linked with.").' '._gettext("Expense").': '.$creditnote_details['expense_id']);
                            $state_flag = false;
                        }
                        break;
                    case 'paid':
                        // Type 2 CR request (Debit) (Refund monies to Suppliers or allow the CR to be used against another of their expenses) (The code here only controls the use of the CR as a payment method)
                        // Do Nothing
                        break;
                    case 'cancelled':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense is cancelled and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    case 'deleted':
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense is deleted and cannot accept payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                    default:
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The parent expense status does not allow payments. You should not see this error, report to admins."));
                        $state_flag = false;
                        break;
                }

            }
        }

        // Status Fall Back - I don't think this will ever be called, but safety first.
        else
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The method submission is invalid. You should not see this error, report to admins."));
            $state_flag = false;
        }

        return $state_flag;

    }



    /** Other Functions **/

    #####################################  // Most calculations are done on the creditnote:edit tpl but this is still required for when payments are made because of the balance field
    #   Recalculate Credit Note Totals  #
    #####################################  done

    public function recalculateTotals($creditnote_id) {

        $items_subtotals        = $this->getItemsSubtotals($creditnote_id);

        // Type - Payments onto the credit note (Real Monies)
        $payments_subtotal      = $this->app->components->report->paymentSum('date', null, null, null, 'valid', 'creditnote', null, null, null, null, null, null, null, null, $creditnote_id);

        // Method - Using the CR as a payment method (no real monies)
        $payments_subtotal      += $this->app->components->report->paymentSum('date', null, null, null, 'valid', null, 'creditnote', null, null, null, null, null, null, null, $creditnote_id);

        // Type + Methods - I could use this function which combines both the Type and Method subtotal functions from above, but it is harder to see the logic.
        //$payments_subtotal      = $this->app->components->report->paymentSum('date', null, null, null, 'valid', null, null, null, null, null, null, null, null, null, $creditnote_id);

        $unit_discount          = $items_subtotals['subtotal_discount'];
        $unit_net               = $items_subtotals['subtotal_net'];
        $unit_tax               = $items_subtotals['subtotal_tax'];
        $unit_gross             = $items_subtotals['subtotal_gross'];
        $balance                = $unit_gross - $payments_subtotal;

        $sql = "UPDATE ".PRFX."creditnote_records SET
                unit_net            =". $this->app->db->qstr( $unit_net            ).",
                unit_discount       =". $this->app->db->qstr( $unit_discount       ).",
                unit_tax            =". $this->app->db->qstr( $unit_tax            ).",
                unit_gross          =". $this->app->db->qstr( $unit_gross          ).",
                balance             =". $this->app->db->qstr( $balance             )."
                WHERE creditnote_id =". $this->app->db->qstr( $creditnote_id       );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status - only if required */

        $creditnote_details = $this->getRecord($creditnote_id);

        // No creditable amount, set to pending (if not already)
        if($creditnote_details['unit_gross'] == 0 && $creditnote_details['status'] != 'pending') {
            $this->updateStatus($creditnote_id, 'pending');
        }

        // Has creditable amount with no payments, set to unused (if not already)
        elseif($creditnote_details['unit_gross'] > 0 && $creditnote_details['unit_gross'] == $balance && $creditnote_details['status'] != 'unused') {
            $this->updateStatus($creditnote_id, 'unused');
        }

        // Has creditable amount with partially usage, set to partially applied (if not already)
        elseif($creditnote_details['unit_gross'] > 0 && $payments_subtotal > 0 && $payments_subtotal < $creditnote_details['unit_gross'] && $creditnote_details['status'] != 'partially_used') {
            $this->updateStatus($creditnote_id, 'partially_used');
        }

        // Has creditable amount and the payment(s) match the credit note amount, set to used (if not already)
        elseif($creditnote_details['unit_gross'] > 0 && $creditnote_details['unit_gross'] == $payments_subtotal && $creditnote_details['status'] != 'used') {
            $this->updateStatus($creditnote_id, 'used');
        }

        return;

    }


    ###########################################  // the code is still in status but I might not used this
    # Assign Credit Note to another employee  #
    ########################################### done

    public function assignToEmployee($creditnote_id, $target_employee_id) {

        // Unify Dates and Times
        $timestamp = time();

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);

        // if the new employee is the same as the current one, exit
        if($target_employee_id == $creditnote_details['employee_id']) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new employee is the same as the current employee."));
            return false;
        }

        // the SQL
        $sql = "UPDATE ".PRFX."creditnote_records SET
                    employee_id         =". $this->app->db->qStr( $target_employee_id  )."
                    WHERE creditnote_id    =". $this->app->db->qStr( $creditnote_id          );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Assigned employee success message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Assigned employee updated."));

        // Get Logged in Employee's Display Name
        $logged_in_employee_display_name = $this->app->user->login_display_name;

        // Get the Display Name of the currently Assigned Employee
        $assigned_employee_display_name = $this->app->components->user->getRecord($creditnote_details['employee_id'], 'display_name');

        // Get the Display Name of the Target Employee
        $target_employee_display_name = $this->app->components->user->getRecord($target_employee_id, 'display_name');

        // Creates a History record - not a workorder
        //$this->app->components->workorder->insertHistory($invoice_details['workorder_id'], _gettext("Invoice").' '.$creditnote_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.');

        // Log activity
        $record = _gettext("Credit Note").' '.$creditnote_id.' '._gettext("has been assigned to").' '.$target_employee_display_name.' '._gettext("from").' '.$assigned_employee_display_name.' '._gettext("by").' '. $logged_in_employee_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $target_employee_id, $creditnote_details['client_id'], null, $creditnote_details['invoice_id']);

        // Update last active record
        $this->updateLastActive($creditnote_id, $timestamp);
        $this->app->components->user->updateLastActive($creditnote_details['employee_id'], $timestamp);
        $this->app->components->user->updateLastActive($target_employee_id, $timestamp);
        $this->app->components->client->updateLastActive($creditnote_details['client_id'], $timestamp);

        return true;

    }

    #################################################################### done
    #   Check Credit Note items VAT Tax Codes are all enabled          #
    ####################################################################

    public function checkVatTaxCodeStatuses($creditnote_id) {

        $state_flag = true;

        foreach ($this->getItems($creditnote_id) as $key => $value) {
            if(!$this->app->components->company->getVatTaxCodeStatus($value['vat_tax_code'])) { $state_flag = false;}
        }

        return $state_flag;

    }

}
