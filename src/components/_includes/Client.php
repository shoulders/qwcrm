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
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

class Client extends Components {


    /** Insert Functions **/

    #####################################
    #    Insert new client              #
    #####################################

    public function insertRecord($qform) {

        $sql = "INSERT INTO ".PRFX."client_records SET
                opened_on       =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime()         ).",
                company_name    =". $this->app->db->qStr( $qform['company_name']     ).",
                first_name      =". $this->app->db->qStr( $qform['first_name']       ).",
                last_name       =". $this->app->db->qStr( $qform['last_name']        ).",
                website         =". $this->app->db->qStr( $this->app->system->general->processInputtedUrl($qform['website'])).",
                email           =". $this->app->db->qStr( $qform['email']            ).",
                credit_terms    =". $this->app->db->qStr( $qform['credit_terms']     ).",
                discount_rate   =". $this->app->db->qStr( $qform['discount_rate']    ).",
                type            =". $this->app->db->qStr( $qform['type']             ).",
                active          =". $this->app->db->qStr( $qform['active']           ).",
                primary_phone   =". $this->app->db->qStr( $qform['primary_phone']    ).",
                mobile_phone    =". $this->app->db->qStr( $qform['mobile_phone']     ).",
                fax             =". $this->app->db->qStr( $qform['fax']              ).",
                address         =". $this->app->db->qStr( $qform['address']          ).",
                city            =". $this->app->db->qStr( $qform['city']             ).",
                state           =". $this->app->db->qStr( $qform['state']            ).",
                zip             =". $this->app->db->qStr( $qform['zip']              ).",
                country         =". $this->app->db->qStr( $qform['country']          ).",
                note            =". $this->app->db->qStr( $qform['note']             );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $client_id = $this->app->db->Insert_ID();

        // Log activity
        $record = _gettext("New client").', '.$this->getRecord($client_id, 'display_name').', '._gettext("has been created.");
        $this->app->system->general->writeRecordToActivityLog($record, null, $this->app->db->Insert_ID());

        return $client_id;



    }

    #############################
    #    Insert client note     #
    #############################

    public function insertNote($client_id, $note) {

        $sql = "INSERT INTO ".PRFX."client_notes SET
                employee_id =". $this->app->db->qStr( $this->app->user->login_user_id   ).",
                client_id   =". $this->app->db->qStr( $client_id                           ).",
                date        =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime()                     ).",
                note        =". $this->app->db->qStr( $note                                );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity
        $record = _gettext("A new client note was added to the client").' '.$this->getRecord($client_id, 'display_name').' '._gettext("by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $client_id);

        // Update last active record
        $this->updateLastActive($client_id);

        return true;

    }

    /** Get Functions **/

    #####################################
    #   Display Clients                 #
    #####################################

    public function getRecords($order_by, $direction, $records_per_page = 0, $use_pages = false, $page_no = null, $search_category = 'client_id', $search_term = null, $type = null, $status = null) {

        // This is needed because of how page numbering works
        $page_no = $page_no ?: 1;

        // Default Action
        $whereTheseRecords = " WHERE ".PRFX."client_records.client_id\n";
        $havingTheseRecords = '';

        // Search category (display_name) and search term
        if($search_category == 'display_name') { $havingTheseRecords .= " HAVING display_name LIKE ".$this->app->db->qStr('%'.$search_term.'%'); }

        // Search category (full_name) and search term
        elseif($search_category == 'full_name') { $havingTheseRecords .= " HAVING full_name LIKE ".$this->app->db->qStr('%'.$search_term.'%'); }

        // Search category with search term
        elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."client_records.$search_category LIKE ".$this->app->db->qStr('%'.$search_term.'%');}

        // Restrict by Type
        if($type) {$whereTheseRecords .= " AND ".PRFX."client_records.type= ".$this->app->db->qStr($type);}

        // Restrict by Status (is null because using boolean/integer)
        if(!is_null($status)) {$whereTheseRecords .= " AND ".PRFX."client_records.active=".$this->app->db->qStr($status);}

        // The SQL code
        $sql = "SELECT
            ".PRFX."client_records.*,
            IF(company_name !='', company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS display_name,
            CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name) AS full_name

            FROM ".PRFX."client_records

            ".$whereTheseRecords."
            GROUP BY ".PRFX."client_records.".$order_by."
            ".$havingTheseRecords."
            ORDER BY ".PRFX."client_records.".$order_by."
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


    ################################
    #  Get Client Details          #
    ################################

    public function getRecord($client_id, $item = null) {

        // This allows blank calls (i.e. payment:details, not all records have a client_id)
        if(!$client_id) {
            return;
        }

        $sql = "SELECT * FROM ".PRFX."client_records WHERE client_id=".$this->app->db->qStr($client_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null) {

            $results = $rs->GetRowAssoc();

            // Add these dynamically created fields
            $results['display_name'] = $results['company_name'] ?: $results['first_name'].' '.$results['last_name'];
            $results['full_name'] = $results['first_name'].' '.$results['last_name'];

            return $results;

        } else {

            // Return the dynamically created 'display_name'
            if($item == 'display_name') {
                $results = $rs->GetRowAssoc();
                return $results['company_name'] ?: $results['first_name'].' '.$results['last_name'];
            }

            // Return the dynamically created 'full_name'
            if($item == 'full_name') {
                $results = $rs->GetRowAssoc();
                return $results['first_name'].' '.$results['last_name'];
            }

            // Return static item
            return $rs->fields[$item];

        }

    }



    #####################################
    #  Get ALL of a client's notes      #
    #####################################

    public function getNotes($client_id) {

        $sql = "SELECT
                ".PRFX."client_notes.*,
                ".PRFX."user_records.first_name,
                ".PRFX."user_records.last_name,

                CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name

                FROM ".PRFX."client_notes
                LEFT JOIN ".PRFX."user_records ON ".PRFX."client_notes.employee_id = ".PRFX."user_records.user_id
                WHERE ".PRFX."client_notes.client_id=".$this->app->db->qStr($client_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    #####################################
    #  Get a single client note         #
    #####################################

    public function getNote($client_note_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."client_notes WHERE client_note_id=".$this->app->db->qStr($client_note_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc();

        } else {

            return $rs->fields[$item];

        }



    }

    #####################################
    #    Get Client Types               #
    #####################################

    public function getTypes() {

        $sql = "SELECT * FROM ".PRFX."client_types";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }

    /** Update Functions **/

    #####################################
    #    Update Client                  #
    #####################################

    public function updateRecord($qform) {

        $sql = "UPDATE ".PRFX."client_records SET
                company_name    =". $this->app->db->qStr( $qform['company_name']     ).",
                first_name      =". $this->app->db->qStr( $qform['first_name']       ).",
                last_name       =". $this->app->db->qStr( $qform['last_name']        ).",
                website         =". $this->app->db->qStr( $this->app->system->general->processInputtedUrl($qform['website'])).",
                email           =". $this->app->db->qStr( $qform['email']            ).",
                credit_terms    =". $this->app->db->qStr( $qform['credit_terms']     ).",
                discount_rate   =". $this->app->db->qStr( $qform['discount_rate']    ).",
                type            =". $this->app->db->qStr( $qform['type']             ).",
                active          =". $this->app->db->qStr( $qform['active']           ).",
                primary_phone   =". $this->app->db->qStr( $qform['primary_phone']    ).",
                mobile_phone    =". $this->app->db->qStr( $qform['mobile_phone']     ).",
                fax             =". $this->app->db->qStr( $qform['fax']              ).",
                address         =". $this->app->db->qStr( $qform['address']          ).",
                city            =". $this->app->db->qStr( $qform['city']             ).",
                state           =". $this->app->db->qStr( $qform['state']            ).",
                zip             =". $this->app->db->qStr( $qform['zip']              ).",
                country         =". $this->app->db->qStr( $qform['country']          ).",
                note            =". $this->app->db->qStr( $qform['note']             )."
                WHERE client_id  =". $this->app->db->qStr( $qform['client_id']       );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity
        $record = _gettext("The client").' '.$this->getRecord($qform['client_id'], 'display_name').' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, null, $qform['client_id']);

        // Update last active record
        $this->updateLastActive($qform['client_id']);

        return true;



    }

    #############################
    #   update client note      #
    #############################

    public function updateNote($client_note_id, $note) {

        $sql = "UPDATE ".PRFX."client_notes SET
                employee_id             =". $this->app->db->qStr( $this->app->user->login_user_id   ).",
                note                    =". $this->app->db->qStr( $note                                )."
                WHERE client_note_id    =". $this->app->db->qStr( $client_note_id                      );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // get client_id
        $client_id = $this->getNote($client_note_id, 'client_id');

        // Log activity
        $record = _gettext("Client Note").' '.$client_note_id.' '._gettext("for").' '.$this->getRecord($client_id, 'display_name').' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $client_id);

        // Update last active record
        $this->updateLastActive($client_id);



    }

    #################################
    #    Update Last Active         #
    #################################

    public function updateLastActive($client_id = null) {

        // compensate for some operations not having a client_id - i.e. sending some emails
        if(!$client_id) { return; }

        $sql = "UPDATE ".PRFX."client_records SET
                last_active=".$this->app->db->qStr( $this->app->system->general->mysqlDatetime() )."
                WHERE client_id=".$this->app->db->qStr($client_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    /** Close Functions **/

    /** Delete Functions **/

    #####################################
    #    Delete Client                  #
    #####################################

    public function deleteRecord($client_id) {

        // Make sure the client can be deleted
        if(!$this->checkRecordAllowsDelete($client_id)) {
            return false;
        }

        /* We can now delete the client */

        // Get client details for logging before we delete anything
        $client_details = $this->getRecord($client_id);

        // Delete any Client user accounts
        $sql = "DELETE FROM ".PRFX."user_records WHERE client_id=".$this->app->db->qStr($client_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Delete Client
        $sql = "DELETE FROM ".PRFX."client_records WHERE client_id=".$this->app->db->qStr($client_id);
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Write the record to the activity log
        $record = _gettext("The client").' '.$client_details['display_name'].' '._gettext("has been deleted by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, null, $client_id);

        return true;

    }

    ##################################
    #    Delete a client's note      #
    ##################################

    public function deleteNote($client_note_id) {

        // Get information before deleting the record
        $client_id = $this->getNote($client_note_id, 'client_id');
        $employee_id = $this->getNote($client_note_id, 'employee_id');

        $sql = "DELETE FROM ".PRFX."client_notes WHERE client_note_id=".$this->app->db->qStr($client_note_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $client_details = $this->getRecord($client_id);

        // Log activity
        $record = _gettext("Client Note").' '.$client_note_id.' '._gettext("for Client").' '.$client_details['display_name'].' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $employee_id, $client_id);

        // Update last active record
        $this->updateLastActive($client_id);

    }

    /** Check Functions **/


    ###############################################################
    #   Check to see if the client can be deleted                 #
    ###############################################################

    public function checkRecordAllowsDelete($client_id) {

        $state_flag = true;

        // Get the client details
        $client_details = $this->getRecord($client_id);

        // Check if client has any workorders
        $sql = "SELECT count(*) as count FROM ".PRFX."workorder_records WHERE client_id=".$this->app->db->qStr($client_id);
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
        if($rs->fields['count'] > 0 ) {
            $this->app->system->variables->systemMessagesWrite('danger', 'You can not delete a client who has work orders.');
            $state_flag = false;
        }

        // Check if client has any invoices
        $sql = "SELECT count(*) as count FROM ".PRFX."invoice_records WHERE client_id=".$this->app->db->qStr($client_id);
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
        if($rs->fields['count'] > 0 ) {
            $this->app->system->variables->systemMessagesWrite('danger', 'You can not delete a client who has invoices.');
            $state_flag = false;
        }

        // Check if client has any Vouchers
        $sql = "SELECT count(*) as count FROM ".PRFX."voucher_records WHERE client_id=".$this->app->db->qStr($client_id);
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
        if($rs->fields['count'] > 0 ) {
            $this->app->system->variables->systemMessagesWrite('danger', 'You can not delete a client who has Vouchers.');
            $state_flag = false;
        }

        // Check if client has any client notes
        $sql = "SELECT count(*) as count FROM ".PRFX."client_notes WHERE client_id=".$this->app->db->qStr($client_id);
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
        if($rs->fields['count'] > 0 ) {
            $this->app->system->variables->systemMessagesWrite('danger', 'You can not delete a client who has client notes.');
            $state_flag = false;
        }

        // Has Credit notes
        if($this->app->components->report->countCreditnotes(null, null, null, null, null, null, $client_details['client_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The client cannot be deleted because it has linked credit notes."));
            return false;
        }

        return $state_flag;

    }


    #########################################
    #    check for Duplicate display name   #  // is not currently used
    #########################################

    public function checkDisplayNameExists($display_name) {

        $sql = "SELECT COUNT(*) AS count FROM ".PRFX."client_records WHERE display_name=".$this->app->db->qStr($display_name);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $row = $rs->FetchRow();

        if ($row['count'] == 1) {

            return false;

        } else {

            return true;

        }

    }


    /** Other Functions **/


    #####################################
    #    Build a Google map string      #
    #####################################

    public function buildGooglemapDirectionsURL($client_id, $employee_id) {

        $company_details    = $this->app->components->company->getRecord();
        $client_details     = $this->getRecord($client_id);
        $employee_details   = $this->app->components->user->getRecord($employee_id);

        // Get google server or use default value, then removes a trailing slash if present
        $google_server = rtrim($this->app->config->get('google_server', 'https://www.google.com/'), '/');

        // Determine the employee's start location
        if ($employee_details['based'] == 'office' || $employee_details['based'] == 'onsite') {

            // Works from the office
            $employee_address  = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $company_details['address']);
            $employee_city     = $company_details['city'];
            $employee_zip      = $company_details['zip'];

        } else {

            // Works from home
            $employee_address  = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $employee_details['home_address']);
            $employee_city     = $employee_details['home_city'];
            $employee_zip      = $employee_details['home_zip'];

        }

        // Get Client's Address
        $client_address   = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $client_details['address']);
        $client_city      = $client_details['city'];
        $client_zip       = $client_details['zip'];

        // return the built google map URL
        return "$google_server/maps?f=d&source=s_d&hl=en&geocode=&saddr=$employee_address,$employee_city,$employee_zip&daddr=$client_address,$client_city,$client_zip";

    }


}
