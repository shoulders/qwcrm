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

    public function insertRecord() {

        // Unify Dates and Times
        $timestamp = time();

        // If QWcrm Tax system is set to Sales Tax, then set the rate
        $sales_tax_rate = (QW_TAX_SYSTEM === 'sales_tax_cash') ? $this->app->components->company->getRecord('sales_tax_rate') : 0.00;

        $sql = "INSERT INTO ".PRFX."otherincome_records SET
                employee_id     =". $this->app->db->qStr($this->app->user->login_user_id).",
                date            =". $this->app->db->qStr($this->app->system->general->mysqlDate($timestamp)).",
                due_date        =". $this->app->db->qStr($this->app->system->general->mysqlDate($timestamp)).",
                tax_system      =". $this->app->db->qStr(QW_TAX_SYSTEM).",
                sales_tax_rate  =". $this->app->db->qStr( $sales_tax_rate                      ).",
                status          =". $this->app->db->qStr('pending').",
                opened_on       =". $this->app->db->qStr($this->app->system->general->mysqlDatetime($timestamp));

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Get otherincome_id
        $otherincome_id = $this->app->db->Insert_ID();

        // Log activity
        $record = _gettext("Otherincome Record").' '.$otherincome_id.' '._gettext("created.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        // Update last active record
        $this->updateLastActive($otherincome_id, $timestamp);

        return $otherincome_id;

    }

    #####################################
    #     Insert Items                  #  // Some or all of these calculations are done on the otherincome:edit page - This extra code might not be needed in the future
    #####################################  done

    public function insertItems($otherincome_id, $items = null) {

        // Get Otherincome Details
        $otherincome_details = $this->getRecord($otherincome_id);

        // Delete all items from the otherincome to prevent duplication
        $sql = "DELETE FROM ".PRFX."otherincome_items WHERE otherincome_id=".$this->app->db->qStr($otherincome_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Insert Items/Rows into database (if any)
        if($items) {

            $sql = "INSERT INTO `".PRFX."otherincome_items` (`otherincome_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `unit_discount`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `subtotal_net`, `subtotal_tax`, `subtotal_gross`) VALUES ";

            foreach($items as $item) {

                // Correct Sales Tax Exempt indicator
                $sales_tax_exempt = isset($item['sales_tax_exempt']) ? 1 : 0;

                // Add in missing vat_tax_codes (i.e. submissions from 'no_tax' and 'sales_tax_cash' dont have VAT codes)
                $vat_tax_code = $item['vat_tax_code'] ?? $this->app->components->company->getDefaultVatTaxCode($otherincome_details['tax_system']);

                /* All this is done in the TPL
                    // Calculate the correct tax rate based on tax system (and exemption status)
                    if($otherincome_details['tax_system'] == 'sales_tax_cash' && $sales_tax_exempt) { $unit_tax_rate = 0.00; }
                    elseif($otherincome_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $otherincome_details['sales_tax_rate']; }
                    elseif(preg_match('/^vat_/', $otherincome_details['tax_system'])) { $unit_tax_rate = $this->app->components->company->getVatRate($item['vat_tax_code']); }
                    else { $unit_tax_rate = 0.00; }

                    // Build item totals based on selected TAX system
                    $item_totals = $this->calculateItemsSubtotals($otherincome_details['tax_system'], $item['unit_qty'], $item['unit_net'], $unit_tax_rate);
                */

                $sql .="(".
                        $this->app->db->qStr( $otherincome_id                    ).",".
                        $this->app->db->qStr( $otherincome_details['tax_system'] ).",".
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

    ###############################
    #  Display otherincomes       #
    ###############################

    public function getRecords($order_by, $direction, $records_per_page = null, $use_pages = false, $page_no = null, $search_category = 'otherincome_id', $search_term = null, $type = null, $status = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";

        // Restrict results by search category and search term
        if($search_term) {$whereTheseRecords .= " AND ".PRFX."otherincome_records.$search_category LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict by Type
        if($type) { $whereTheseRecords .= " AND ".PRFX."otherincome_records.type= ".$this->app->db->qStr($type);}

        // Restrict by Status
        if($status) {

            // All Open Otherrincomes
            if($status == 'open') {

                $whereTheseRecords .= " AND ".PRFX."otherincome_records.closed_on IS NULL";

            // All Closed Otherincomes
            } elseif($status == 'closed') {

                $whereTheseRecords .= " AND ".PRFX."otherincome_records.closed_on";

            // Return Otherincomes for the given status
            } else {

                $whereTheseRecords .= " AND ".PRFX."otherincome_records.status= ".$this->app->db->qStr($status);

            }

        }

        // The SQL code
        $sql = "SELECT ".PRFX."otherincome_records.*,

                items.combined as otherincome_items

                FROM ".PRFX."otherincome_records

                LEFT JOIN (
                    SELECT ".PRFX."otherincome_items.otherincome_id,
                    GROUP_CONCAT(
                        CONCAT(".PRFX."otherincome_items.unit_qty, ' x ', ".PRFX."otherincome_items.description)
                        ORDER BY ".PRFX."otherincome_items.otherincome_item_id
                        ASC
                        SEPARATOR '|||'
                    ) AS combined
                    FROM ".PRFX."otherincome_items
                    GROUP BY ".PRFX."otherincome_items.otherincome_id
                    ORDER BY ".PRFX."otherincome_items.otherincome_id
                    ASC
                ) AS items
                ON ".PRFX."otherincome_records.otherincome_id = items.otherincome_id

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

    public function getRecord($otherincome_id, $item = null)
    {
        // This allows for blank calls
        if(!$otherincome_id)
        {
            return;
        }

        $sql = "SELECT * FROM ".PRFX."otherincome_records WHERE otherincome_id=".$this->app->db->qStr($otherincome_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // I have only done this record check here not currently in other components
        if($rs->recordCount())
        {
            if(!$item)
            {
                return $rs->GetRowAssoc();

            } else {

                return $rs->fields[$item];

            }
        }
        else
        {
            return null;
        }

    }

    #####################################
    #   Get All otherincome items       #
    #####################################

    public function getItems($otherincome_id)
    {
        $sql = "SELECT * FROM ".PRFX."otherincome_items WHERE otherincome_id=".$this->app->db->qStr($otherincome_id);

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
    #   Get otherincome items Sub Totals       #
    ############################################

    public function getItemsSubtotals($otherincome_id) {

        // I could use $this->app->components->report->sumCreditnoteItems() - with additional calculation for subtotal_discount
        // NB: i dont think i need the aliases
        // $otherincome_items_subtotals = $this->app->components->report->getExpensesStats('items', null, null, null, null, null, $supplier_id);

        $sql = "SELECT
                SUM(unit_discount * unit_qty) AS subtotal_discount,
                SUM(subtotal_net) AS subtotal_net,
                SUM(subtotal_tax) AS subtotal_tax,
                SUM(subtotal_gross) AS subtotal_gross
                FROM ".PRFX."otherincome_items
                WHERE otherincome_id=". $this->app->db->qStr($otherincome_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetRowAssoc();

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

        $sql = "SELECT display_name FROM ".PRFX."otherincome_statuses WHERE status_key=".$this->app->db->qStr($status_key);

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
                employee_id      =". $this->app->db->qStr( $this->app->user->login_user_id ).",
                payee            =". $this->app->db->qStr( $qform['payee']                   ).",
                date             =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['date'])).",
                due_date         =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['date']) ).",
                type             =". $this->app->db->qStr( $qform['type']               ).",
                sales_tax_rate   =". $this->app->db->qStr( $qform['sales_tax_rate']           ).",
                reference        =". $this->app->db->qStr( $qform['reference']                    ).",
                note             =". $this->app->db->qStr( $qform['note']                    )."
                WHERE otherincome_id  =". $this->app->db->qStr( $qform['otherincome_id']     );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity
        $record = _gettext("Otherincome Record").' '.$qform['otherincome_id'].' '._gettext("updated.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        // Update last active record
        $this->updateLastActive($qform['otherincome_id']);

        return true;

    }

    #############################
    # Update Otherincome Status #
    #############################

    public function updateStatus($otherincome_id, $new_status, $silent = false) {

        // Unify Dates and Times
        $timestamp = time();

        // Get otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // if the new status is the same as the current one, exit
        if($new_status == $otherincome_details['status']) {
            if (!$silent) { $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new status is the same as the current status.")); }
            return false;
        }

        // Build SQL
        $sql = "UPDATE ".PRFX."otherincome_records SET
                employee_id         =". $this->app->db->qStr($otherincome_details['employee_id']).",
                status              =". $this->app->db->qStr($new_status).",";
        if($new_status == 'paid' || $new_status == 'cancelled' || $new_status == 'deleted')
        {
            $sql .= "closed_on =". $this->app->db->qStr($this->app->system->general->mysqlDatetime($timestamp) );
        }
        else
        {
            $sql .= "closed_on = NULL\n";
        }
        $sql .= "WHERE otherincome_id =". $this->app->db->qStr($otherincome_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Status updated message
        if (!$silent) { $this->app->system->variables->systemMessagesWrite('success', _gettext("Otherincome status updated.")); }

        // Log activity
        $record = _gettext("Otherincome").' '.$otherincome_id.' '._gettext("Status updated to").' '._gettext($this->getStatusDisplayName($new_status)).' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        // Update last active record
        $this->updateLastActive($otherincome_id, $timestamp);

        return true;

    }

    #################################
    #    Update Last Active         #
    #################################

    public function updateLastActive($otherincome_id = null, $timestamp = null) {

        // Allow null calls
        if(!$otherincome_id) { return; }

        $sql = "UPDATE ".PRFX."otherincome_records SET
                last_active=".$this->app->db->qStr( $this->app->system->general->mysqlDatetime($timestamp) )."
                WHERE otherincome_id=".$this->app->db->qStr($otherincome_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

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

        // Update last active record
        $this->updateLastActive($otherincome_id);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #    Delete Record                  #
    #####################################

    public function deleteRecord($otherincome_id) {

        // Change the otherincome status to deleted (I do this here to maintain consistency)
        $this->updateStatus($otherincome_id, 'deleted');

        // Delete record items
        $sql = "DELETE FROM `".PRFX."otherincome_items` WHERE `".PRFX."otherincome_items`.`otherincome_id` = $otherincome_id";
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Delete Main record
        $sql = "UPDATE ".PRFX."otherincome_records SET
            employee_id         = NULL,
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
            WHERE otherincome_id =". $this->app->db->qStr($otherincome_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Delete all items from the otherincome to prevent duplication
        $sql = "DELETE FROM ".PRFX."otherincome_items WHERE otherincome_id=".$this->app->db->qStr($otherincome_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity
        $record = _gettext("Otherincome Record").' '.$otherincome_id.' '._gettext("deleted.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        return true;

    }


    /** Check Functions **/

    #############################################################
    # Validate submitted information before allowing submission #
    #############################################################

    public function checkRecordCanBeSubmitted($qform)
    {
        $state_flag = true;

        //$otherincome_details = $this->app->components->otherincome->getRecord($qform['otherincome_id']);

        // Check there is a positive unit_gross
        if($qform['unit_gross'] <= 0)
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot have a negative or zero gross amount."));
            $state_flag = false;
        }

        return $state_flag;

    }

    ##############################################################
    #  Check if the otherincome status is allowed to be changed  #  // not currently used
    ##############################################################

     public function checkRecordAllowsManualStatusChange($otherincome_id) {

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

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome status cannot be changed because the otherincome has payments."));
            $state_flag = false;
        }*/

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the otherincome can be cancelled          #
    ###############################################################

    public function checkRecordAllowsCancel($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // Is pending
        if($otherincome_details['status'] == 'pending') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be cancelled because the otherincome is pending."));
            $state_flag = false;
        }

        // Is partially paid
        if($otherincome_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be cancelled because the otherincome is partially paid."));
            $state_flag = false;
        }

        // Is paid
        if($otherincome_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be deleted because it has payments and is paid."));
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

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be cancelled because the otherincome has payments."));
            $state_flag = false;
        }*/

        return $state_flag;

    }

    ###############################################################
    #   Check to see if the otherincome can be deleted            #
    ###############################################################

    public function checkRecordAllowsDelete($otherincome_id) {

        $state_flag = true;

        // Get the otherincome details
        $otherincome_details = $this->getRecord($otherincome_id);

        // Is Pending
        if($otherincome_details['status'] == 'pending') {
        }

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

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be deleted because it has payments."));
            $state_flag = false;
        }*/

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

        // Is Pending
        if($otherincome_details['status'] == 'pending') {
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
        if($otherincome_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it has been cancelled."));
            $state_flag = false;
        }

        // Is deleted
        if($otherincome_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot be edited because it has been deleted."));
            $state_flag = false;
        }

        /* Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments('date', null, null, null, null, 'otherincome', null, null, null, null, null, null, $otherincome_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it has payments."));
            $state_flag = false;
        }*/

        // Add tax code check for all current items + add this to credit notes, vouchgers? expense, invoices - the code should be present in on of the others


        /* The current record VAT code is enabled
        if(!$this->app->components->company->getVatTaxCodeStatus($otherincome_details['vat_tax_code'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This otherincome cannot be edited because it's current VAT Tax Code is not enabled."));
            $state_flag = false;
        }*/

        return $state_flag;

    }


    /** Other Functions **/

    #######################################
    #   Recalculate Otherincome Totals    #
    #######################################

    public function recalculateTotals($otherincome_id) {

        $items_subtotals        = $this->getItemsSubtotals($otherincome_id);
        $payments_subtotal      = $this->app->components->report->sumPayments('date', null, null, null, 'valid', 'otherincome', null, null, null, null, null, null, $otherincome_id);

        $unit_discount          = $items_subtotals['subtotal_discount'];
        $unit_net               = $items_subtotals['subtotal_net'];
        $unit_tax               = $items_subtotals['subtotal_tax'];
        $unit_gross             = $items_subtotals['subtotal_gross'];
        $balance                = $unit_gross - $payments_subtotal;

        $sql = "UPDATE ".PRFX."otherincome_records SET
                unit_net            =". $this->app->db->qstr( $unit_net            ).",
                unit_discount       =". $this->app->db->qstr( $unit_discount       ).",
                unit_tax            =". $this->app->db->qstr( $unit_tax            ).",
                unit_gross          =". $this->app->db->qstr( $unit_gross          ).",
                balance             =". $this->app->db->qstr( $balance             )."
                WHERE otherincome_id    =". $this->app->db->qstr( $otherincome_id  );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status - only if required */

        $otherincome_details = $this->getRecord($otherincome_id);

        // No otherincome amount, set to pending (if not already)
        if($otherincome_details['unit_gross'] == 0 && $otherincome_details['status'] != 'pending') {
            $this->updateStatus($otherincome_id, 'pending');
        }

        // Has otherincome amount with no payments, set to unpaid (if not already)
        elseif($otherincome_details['unit_gross'] > 0 && $otherincome_details['unit_gross'] == $balance && $otherincome_details['status'] != 'unpaid') {
            $this->updateStatus($otherincome_id, 'unpaid');
        }

        // Has otherincome amount with partially usage, set to partially paid (if not already)
        elseif($otherincome_details['unit_gross'] > 0 && $payments_subtotal > 0 && $payments_subtotal < $otherincome_details['unit_gross'] && $otherincome_details['status'] != 'partially_paid') {
            $this->updateStatus($otherincome_id, 'partially_paid');
        }

        // Has otherincome amount and the payment(s) match the credit note amount, set to paid (if not already)
        elseif($otherincome_details['unit_gross'] > 0 && $otherincome_details['unit_gross'] == $payments_subtotal && $otherincome_details['status'] != 'paid') {
            $this->updateStatus($otherincome_id, 'paid');
        }

        return;

    }

}
