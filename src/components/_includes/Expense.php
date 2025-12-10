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

class Expense extends Components {

    /** Insert Functions **/

    ##########################################
    #      Insert Expense                    #  //supplier_id is a variable so I can create an expense directly from a supplier page.
    ##########################################

    public function insertRecord($supplier_id = null) {

        // Unify Dates and Times
        $timestamp = time();

        // If QWcrm Tax system is set to Sales Tax, then set the rate
        $sales_tax_rate = (QW_TAX_SYSTEM === 'sales_tax_cash') ? $this->app->components->company->getRecord('sales_tax_rate') : 0.00;

        $sql = "INSERT INTO ".PRFX."expense_records SET
                employee_id     =". $this->app->db->qStr($this->app->user->login_user_id).",
                supplier_id     =". $this->app->db->qStr($supplier_id).",
                date            =". $this->app->db->qStr($this->app->system->general->mysqlDate($timestamp)).",
                due_date        =". $this->app->db->qStr($this->app->system->general->mysqlDate($timestamp)).",
                tax_system      =". $this->app->db->qStr(QW_TAX_SYSTEM).",
                sales_tax_rate  =". $this->app->db->qStr( $sales_tax_rate                      ).",
                status          =". $this->app->db->qStr('pending').",
                opened_on       =". $this->app->db->qStr($this->app->system->general->mysqlDatetime($timestamp)).",
                additional_info =". $this->app->db->qStr( '{}'                                 );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Get expense_id
        $expense_id = $this->app->db->Insert_ID();

        /* This code is not used because I removed 'invoice_id'
         * Get related invoice details
        $invoice_details = $this->app->components->invoice->getRecord($qform['invoice_id']);

        // Create a Workorder History Note
        $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$this->app->db->Insert_ID().' '._gettext("added").' '._gettext("by").' '.$this->app->user->login_display_name.'.');
        */

        // Log activity
        $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("created.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);


        // Update last active record
        $this->updateLastActive($expense_id, $timestamp);
        $this->app->components->supplier->updateLastActive($supplier_id, $timestamp);

        return $expense_id;

    }

    #####################################
    #     Insert Items                  #  // Some or all of these calculations are done on the expense:edit page - This extra code might not be needed in the future
    #####################################  done

    public function insertItems($expense_id, $items = null) {

        // Get Expense Details
        $expense_details = $this->getRecord($expense_id);

        // Delete all items from the expense to prevent duplication
        $sql = "DELETE FROM ".PRFX."expense_items WHERE expense_id=".$this->app->db->qStr($expense_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Insert Items/Rows into database (if any)
        if($items) {

            $sql = "INSERT INTO `".PRFX."expense_items` (`expense_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `unit_discount`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `subtotal_net`, `subtotal_tax`, `subtotal_gross`) VALUES ";

            foreach($items as $item) {

                // Correct Sales Tax Exempt indicator
                $sales_tax_exempt = isset($item['sales_tax_exempt']) ? 1 : 0;

                // Add in missing vat_tax_codes (i.e. submissions from 'no_tax' and 'sales_tax_cash' dont have VAT codes)
                $vat_tax_code = $item['vat_tax_code'] ?? $this->app->components->company->getDefaultVatTaxCode($expense_details['tax_system']);

                $sql .="(".
                        $this->app->db->qStr( $expense_id                    ).",".
                        $this->app->db->qStr( $expense_details['tax_system'] ).",".
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


    #####################################################
    #         Display expenses                          #
    #####################################################

    public function getRecords($order_by, $direction, $records_per_page = 0, $use_pages = false, $page_no = null, $search_category = 'expense_id', $search_term = null, $type = null, $status = null, $supplier_id = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."expense_records.expense_id\n";
        $havingTheseRecords = '';

        // Restrict results by search category (display_name/payee) and search term
        if($search_category == 'display_name') { $havingTheseRecords .= " HAVING display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%'); }

        // Restrict results by search category and search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."expense_records.$search_category LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict by type
        if($type) { $whereTheseRecords .= " AND ".PRFX."expense_records.type= ".$this->app->db->qStr($type);}

        // Restrict by Status
        if($status) {

            // All Open Expenses
            if($status == 'open') {

                $whereTheseRecords .= " AND ".PRFX."expense_records.closed_on IS NULL";

            // All Closed Expenses
            } elseif($status == 'closed') {

                $whereTheseRecords .= " AND ".PRFX."expense_records.closed_on";

            // Return Expenses for the given status
            } else {

                $whereTheseRecords .= " AND ".PRFX."expense_records.status= ".$this->app->db->qStr($status);

            }

        }

        // Restrict by Supplier
        if($supplier_id) {$whereTheseRecords .= " AND ".PRFX."expense_records.supplier_id=".$this->app->db->qStr($supplier_id);}

        // The SQL code
        $sql = "SELECT ".PRFX."expense_records.*,

                IF(
                    ".PRFX."supplier_records.supplier_id !='',
                    IF(
                        ".PRFX."supplier_records.company_name !='',
                        ".PRFX."supplier_records.company_name,
                        CONCAT(".PRFX."supplier_records.first_name, ' ', ".PRFX."supplier_records.last_name)
                        ),
                    payee
                ) AS display_name,

                items.combined as expense_items

                FROM ".PRFX."expense_records

                LEFT JOIN (
                    SELECT ".PRFX."expense_items.expense_id,
                    GROUP_CONCAT(
                        CONCAT(".PRFX."expense_items.unit_qty, ' x ', ".PRFX."expense_items.description)
                        ORDER BY ".PRFX."expense_items.expense_item_id
                        ASC
                        SEPARATOR '|||'
                    ) AS combined
                    FROM ".PRFX."expense_items
                    GROUP BY ".PRFX."expense_items.expense_id
                    ORDER BY ".PRFX."expense_items.expense_id
                    ASC
                ) AS items
                ON ".PRFX."expense_records.expense_id = items.expense_id

                LEFT JOIN ".PRFX."supplier_records ON ".PRFX."expense_records.supplier_id = ".PRFX."supplier_records.supplier_id

                ".$whereTheseRecords."
                GROUP BY ".PRFX."expense_records.".$order_by."
                ".$havingTheseRecords."
                ORDER BY ".PRFX."expense_records.".$order_by."
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
    #  Get Expense Details   #
    ##########################

    public function getRecord($expense_id = null, $item = null) {

        // This allows for blank calls
        if(!$expense_id)
        {
            return;
        }

        $sql = "SELECT * FROM ".PRFX."expense_records WHERE expense_id=".$this->app->db->qStr($expense_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!$item){

            $results = $rs->GetRowAssoc();
            $results['display_name'] = $results['supplier_id'] ? $this->app->components->supplier->getRecord($results['supplier_id'], 'display_name') : $results['payee'];
            return $results;

        } else {

            return $rs->fields[$item];

        }

    }

    #####################################
    #   Get All expense items           #
    #####################################

    public function getItems($expense_id) {

        $sql = "SELECT * FROM ".PRFX."expense_items WHERE expense_id=".$this->app->db->qStr($expense_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($rs->recordCount())
        {
            return $rs->GetArray();
        }
        else
        {
            return null;
        }

    }

    ############################################
    #   Get expense items Sub Totals           #
    ############################################

    public function getItemsSubtotals($expense_id) {

        // I could use $this->app->components->report->creditnoteItemSum() - with additional calculation for subtotal_discount
        // NB: i dont think i need the aliases
        // $expense_items_subtotals = $this->app->components->report->expenseGetStats('items', null, null, null, null, null, $supplier_id);

        $sql = "SELECT
                SUM(unit_discount * unit_qty) AS subtotal_discount,
                SUM(subtotal_net) AS subtotal_net,
                SUM(subtotal_tax) AS subtotal_tax,
                SUM(subtotal_gross) AS subtotal_gross
                FROM ".PRFX."expense_items
                WHERE expense_id=". $this->app->db->qStr($expense_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetRowAssoc();

    }

    #####################################
    #    Get Expense Statuses           #
    #####################################

    public function getStatuses($restricted_statuses = false) {

        $sql = "SELECT * FROM ".PRFX."expense_statuses";

        // Restrict statuses to those that are allowed to be changed by the user
        if($restricted_statuses) {
            $sql .= "\nWHERE status_key NOT IN ('paid', 'partially_paid', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    ######################################
    #  Get Expense status display name   # // might not be used anymore
    ######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."expense_statuses WHERE status_key=".$this->app->db->qStr($status_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];

    }

    #####################################
    #    Get Expense Types              #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."expense_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    ##########################################
    #      Last Record Look Up               #  // not currently used
    ##########################################

    public function getLastRecordId() {

        $sql = "SELECT * FROM ".PRFX."expense_records ORDER BY expense_id DESC LIMIT 1";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['expense_id'];

    }


    /** Update Functions **/

    #####################################
    #     Update Expense                #
    #####################################

    public function updateRecord($qform) {

        // Unify Dates and Times
        $timestamp = time();

        $sql = "UPDATE ".PRFX."expense_records SET
                employee_id         =". $this->app->db->qStr( $this->app->user->login_user_id    ).",
                supplier_id         =". $this->app->db->qStr( $qform['supplier_id'] ?: null      ).",
                payee               =". $this->app->db->qStr( $qform['payee']                    ).",
                date                =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['date']) ).",
                due_date            =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['date']) ).",
                type                =". $this->app->db->qStr( $qform['type']                     ).",
                sales_tax_rate      =". $this->app->db->qStr( $qform['sales_tax_rate']           ).",
                reference           =". $this->app->db->qStr( $qform['reference']                    ).",
                note                =". $this->app->db->qStr( $qform['note']                     )."
                WHERE expense_id    =". $this->app->db->qStr( $qform['expense_id']               );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* This code is not used because I removed 'invoice_id'
         * Get related invoice details
        $invoice_details = $this->app->components->invoice->getRecord($qform['invoice_id']);

        // Create a Workorder History Note
        $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense").' '.$expense_id.' '._gettext("updated").' '._gettext("by").' '.$this->app->user->login_display_name.'.');
        */

        // Log activity
        $record = _gettext("Expense Record").' '.$qform['expense_id'].' '._gettext("updated.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        // Update last active record
        $this->updateLastActive($qform['expense_id'], $timestamp);
        $this->app->components->supplier->updateLastActive($qform['supplier_id'], $timestamp);

        return true;

    }

    ############################
    # Update Expense Status    #
    ############################

    public function updateStatus($expense_id, $new_status, $silent = false) {

        // Unify Dates and Times
        $timestamp = time();

        // Get expense details
        $expense_details = $this->getRecord($expense_id);

        // if the new status is the same as the current one, exit
        if($new_status == $expense_details['status']) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.", $silent));
            return false;
        }

        // Build SQL
        $sql = "UPDATE ".PRFX."expense_records SET
                employee_id         =". $this->app->db->qStr($expense_details['employee_id']).",
                status              =". $this->app->db->qStr($new_status).",";
        if($new_status == 'paid' || $new_status == 'cancelled' || $new_status == 'deleted')
        {
            $sql .= "closed_on =". $this->app->db->qStr($this->app->system->general->mysqlDatetime($timestamp) );
        }
        else
        {
            $sql .= "closed_on = NULL\n";
        }
        $sql .= "WHERE expense_id =". $this->app->db->qStr($expense_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Status updated message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Expense status updated."));

        /* This code is not used because I removed 'invoice_id'
         * Get related invoice details
        $invoice_details = $this->app->components->invoice->getRecord($expense_details['invoice_id']);

        // Create a Workorder History Note (Not Used)
        $this->app->components->workorder->insert_workorder_history_note($invoice_details['workorder_id'], _gettext("Expense Status updated to").' '.$expense_status_display_name.' '._gettext("by").' '.$this->app->user->login_display_name.'.');
        */

        // Log activity
        $record = _gettext("Expense").' '.$expense_id.' '._gettext("Status updated to").' '._gettext($this->getStatusDisplayName($new_status)).' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        // Update last active record
        $this->updateLastActive($expense_id, $timestamp);
        $this->app->components->supplier->updateLastActive($expense_details['supplier_id'], $timestamp);

        return true;

    }

    #################################
    #    Update Last Active         #
    #################################

    public function updateLastActive($expense_id = null, $timestamp = null) {

        // Allow null calls
        if(!$expense_id) { return; }

        $sql = "UPDATE ".PRFX."expense_records SET
                last_active=".$this->app->db->qStr( $this->app->system->general->mysqlDatetime($timestamp) )."
                WHERE expense_id=".$this->app->db->qStr($expense_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    ###################################  if you send a new key/pair, it will be added
    #    update additional info       #  if you send an existing key/pair the value will be updated
    ###################################  if you send a key/pair with a null value, it will be removed

    public function updateAdditionalInfo($expense_id, array $new_additional_info = array()) {

        // Make sure we merge current data from the database, decode as an array even if empty
        $current_additional_info = json_decode($this->getRecord($expense_id, 'additional_info'), true);

        // Merge arrays
        $additional_info = array_merge($current_additional_info, $new_additional_info);

        // Remove all entries defined as null
        $additional_info = array_filter($additional_info, function($var) {return ($var !== null);});

        $sql = "UPDATE ".PRFX."expense_records SET
                additional_info=".$this->app->db->qStr(json_encode($additional_info, JSON_FORCE_OBJECT))."
                WHERE expense_id=".$this->app->db->qStr($expense_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    /** Close Functions **/

    #####################################
    #   Cancel Expense                  #
    #####################################

    public function cancelRecord($expense_id, $reason_for_cancelling) {

        // Unify Dates and Times
        $timestamp = time();

        // Make sure the expense can be cancelled
        if(!$this->checkRecordAllowsCancel($expense_id)) {
            return false;
        }

        // Get expense details
        $expense_details = $this->getRecord($expense_id);

        // Change the expense status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($expense_id, 'cancelled');

        // Add Cancelled message to the additional info
        $this->updateAdditionalInfo($expense_id, array('reason_for_cancelling' => $reason_for_cancelling));

        // Log activity
        $record = _gettext("Expense").' '.$expense_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        // Update last active record
        $this->updateLastActive($expense_id, $timestamp);
        $this->app->components->supplier->updateLastActive($expense_details['supplier_id'], $timestamp);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Record                  #
    #####################################

    public function deleteRecord($expense_id) {

        // Get expense details before deleting the record
        $expense_details = $this->getRecord($expense_id);

        // Change the expense status to deleted (I do this here to maintain consistency)
        $this->updateStatus($expense_id, 'deleted');

        // Delete record items
        $sql = "DELETE FROM ".PRFX."expense_items WHERE expense_id=".$this->app->db->qStr($expense_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Delete Main record
        $sql = "UPDATE ".PRFX."expense_records SET
                employee_id         = NULL,
                supplier_id         = NULL,
                payee               = '',
                date                = NULL,
                due_date            = NULL,
                tax_system          = '',
                type                = '',
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
                reference           = '',
                note                = ''
                WHERE expense_id    =". $this->app->db->qStr($expense_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity
        $record = _gettext("Expense Record").' '.$expense_id.' '._gettext("deleted.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        // Update last active record
        $this->app->components->supplier->updateLastActive($expense_details['supplier_id']);

        return true;

    }


    /** Check Functions **/



    ############################################################# done
    # Validate submitted information before allowing submission #
    #############################################################

    public function checkRecordCanBeSubmitted($qform)
    {
        $state_flag = true;

        //$expense_details = $this->app->components->expense->getRecord($qform['expense_id']);

        // Check there is a positive unit_gross
        if($qform['unit_gross'] <= 0)
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot have a negative or zero gross amount."));
            $state_flag = false;
        }

        return $state_flag;

    }

    ##########################################################
    #  Check if the expense status is allowed to be changed  #  // not currently used
    ##########################################################

    public function checkRecordAllowsManualStatusChange($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments and is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($expense_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments and is paid."));
            $state_flag = false;
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has been deleted."));
            $state_flag = false;
        }

        // Has payments
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', 'expense', null, null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because the expense has payments."));
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, $expense_details['expense_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense status cannot be changed because it has linked credit notes."));
            return false;
        }

        return $state_flag;

     }

    ##########################################################
    #  Check if the expense status allows editing            #
    ##########################################################

     public function checkRecordAllowsEdit($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

        // Is on a different tax system
        if($expense_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be edited because it is on a different Tax system."));
            $state_flag = false;
        }

        // Is Pending
        if($expense_details['status'] == 'pending') {
        }

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments and is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($expense_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments and is paid."));
            $state_flag = false;
        }

        // Is cancelled
        if($expense_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has been cancelled."));
            $state_flag = false;
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be edited because it has been deleted."));
            $state_flag = false;
        }

        // Has payments
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', 'expense', null, null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be edited because it has payments."));
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, $expense_details['expense_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be edited because it has linked credit notes."));
            return false;
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the expense can be cancelled              #  // Do I actuallu use this, the code seems to be implemented
    ###############################################################

    public function checkRecordAllowsCancel($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

        // Is pending
        if($expense_details['status'] == 'pending') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be cancelled because the expense is pending."));
            $state_flag = false;
        }

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be cancelled because the expense is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($expense_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is paid."));
            $state_flag = false;
        }

        // Is cancelled
        if($expense_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be cancelled because the expense has already been cancelled."));
            $state_flag = false;
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be cancelled because the expense has been deleted."));
            $state_flag = false;
        }

        // Has payments
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', 'expense', null, null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be cancelled because the expense has payments."));
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, $expense_details['expense_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be cancelled because it has linked credit notes."));
            return false;
        }

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the expense can be deleted                #
    ###############################################################

    public function checkRecordAllowsDelete($expense_id) {

        $state_flag = true;

        // Get the expense details
        $expense_details = $this->getRecord($expense_id);

        // Is Pending
        if($expense_details['status'] == 'pending') {
        }

        // Is partially paid
        if($expense_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($expense_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments and is paid."));
            $state_flag = false;
        }

        // Is cancelled
        if($expense_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has been cancelled."));
            $state_flag = false;
        }

        // Is deleted
        if($expense_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it already been deleted."));
            $state_flag = false;
        }

        // Has payments
        if($this->app->components->report->paymentCount('date', null, null, null, 'all', 'expense', null, null, null, null, null, null, $expense_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense cannot be deleted because it has payments."));
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, $expense_details['expense_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot be deleted because it has linked credit notes."));
            return false;
        }

        return $state_flag;

    }

    /** Other Functions **/

    #####################################
    #   Recalculate Expense Totals      #
    #####################################

    public function recalculateTotals($expense_id) {

        $items_subtotals        = $this->getItemsSubtotals($expense_id);
        $payments_subtotal      = $this->app->components->report->paymentSum('date', null, null, null, 'valid', 'expense', null, null, null, null, null, null, $expense_id);

        $unit_discount          = $items_subtotals['subtotal_discount'];
        $unit_net               = $items_subtotals['subtotal_net'];
        $unit_tax               = $items_subtotals['subtotal_tax'];
        $unit_gross             = $items_subtotals['subtotal_gross'];
        $balance                = $unit_gross - $payments_subtotal;

        $sql = "UPDATE ".PRFX."expense_records SET
                unit_net            =". $this->app->db->qstr( $unit_net            ).",
                unit_discount       =". $this->app->db->qstr( $unit_discount       ).",
                unit_tax            =". $this->app->db->qstr( $unit_tax            ).",
                unit_gross          =". $this->app->db->qstr( $unit_gross          ).",
                balance             =". $this->app->db->qstr( $balance             )."
                WHERE expense_id    =". $this->app->db->qstr( $expense_id          );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status - only if required */

        $expense_details = $this->getRecord($expense_id);

        // No expense amount, set to pending (if not already)
        if($expense_details['unit_gross'] == 0 && $expense_details['status'] != 'pending') {
            $this->updateStatus($expense_id, 'pending');
        }

        // Has expense amount with no payments, set to unpaid (if not already)
        elseif($expense_details['unit_gross'] > 0 && $expense_details['unit_gross'] == $balance && $expense_details['status'] != 'unpaid') {
            $this->updateStatus($expense_id, 'unpaid');
        }

        // Has expense amount with partially usage, set to partially paid (if not already)
        elseif($expense_details['unit_gross'] > 0 && $payments_subtotal > 0 && $payments_subtotal < $expense_details['unit_gross'] && $expense_details['status'] != 'partially_paid') {
            $this->updateStatus($expense_id, 'partially_paid');
        }

        // Has expense amount and the payment(s) match the credit note amount, set to paid (if not already)
        elseif($expense_details['unit_gross'] > 0 && $expense_details['unit_gross'] == $payments_subtotal && $expense_details['status'] != 'paid') {
            $this->updateStatus($expense_id, 'paid');
        }

        return;

    }

}
