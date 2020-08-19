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

    public function insertRecord($client_id, $workorder_id, $unit_discount_rate) {

        // Unify Dates and Times
        $timestamp = time();

        // Get invoice tax type
        $tax_system = QW_TAX_SYSTEM;

        // Sales Tax Rate based on Tax Type
        $sales_tax_rate = ($tax_system == 'sales_tax_cash') ? $sales_tax_rate = $this->app->components->company->getRecord('sales_tax_rate') : 0.00;

        $sql = "INSERT INTO ".PRFX."invoice_records SET     
                employee_id     =". $this->app->db->qstr( $this->app->user->login_user_id   ).",
                client_id       =". $this->app->db->qstr( $client_id                           ).",
                workorder_id    =". $this->app->db->qstr( $workorder_id                        ).",
                date            =". $this->app->db->qstr( $this->app->system->general->mysqlDate($timestamp)               ).",
                due_date        =". $this->app->db->qstr( $this->app->system->general->mysqlDate($timestamp)               ).",            
                unit_discount_rate   =". $this->app->db->qstr( $unit_discount_rate             ).",
                tax_system      =". $this->app->db->qstr( $tax_system                          ).",
                sales_tax_rate  =". $this->app->db->qstr( $sales_tax_rate                      ).",            
                status          =". $this->app->db->qstr( 'pending'                            ).",
                opened_on       =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime($timestamp)           ).",
                is_closed       =". $this->app->db->qstr( 0                                    ); 

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
        $this->app->components->client->updateLastActive($client_id);
        $this->app->components->workorder->updateLastActive($workorder_id);        

        return $invoice_id;

    }

    ##################################### 
    #     Insert Items                  #  // Some or all of these calculations are done on the invoice:edit page - This extra code might not be needed in the future
    ##################################### // section = 'labour' or 'parts'

    public function insertItems($invoice_id, $section, $items = null) {
        
        // Get Invoice Details
        $invoice_details = $this->getRecord($invoice_id);
        
        // Delete all items from the invoice to prevent duplication
        $sql = "DELETE FROM ".PRFX."invoice_$section WHERE invoice_id=".$this->app->db->qstr($invoice_id);    
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Insert Items into database (if any)
        if($items) {

            $sql = "INSERT INTO `".PRFX."invoice_$section` (`invoice_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `sub_total_net`, `sub_total_tax`, `sub_total_gross`) VALUES ";

            foreach($items as $item) {

                // Add in missing sales tax exempt option - This prevents undefined variable errors
                $sales_tax_exempt = $item['sales_tax_exempt'] ?? 0;

                // Add in missing vat_tax_codes (i.e. submissions from 'no_tax' and 'sales_tax_cash' dont have VAT codes) - This prevents undefined variable errors
                $vat_tax_code = $item['vat_tax_code'] ?? $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']); 

                // Calculate the correct tax rate based on tax system (and exemption status)
                if($invoice_details['tax_system'] == 'sales_tax_cash' && $sales_tax_exempt) { $unit_tax_rate = 0.00; }
                elseif($invoice_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $invoice_details['sales_tax_rate']; }
                elseif(preg_match('/^vat_/', $invoice_details['tax_system'])) { $unit_tax_rate = $this->app->components->company->getVatRate($item['vat_tax_code']); }
                else { $unit_tax_rate = 0.00; }

                // Build item totals based on selected TAX system
                $item_totals = $this->calculateItemsSubtotals($invoice_details['tax_system'], $item['unit_qty'], $item['unit_net'], $unit_tax_rate);

                $sql .="(".
                        $this->app->db->qstr( $invoice_id                       ).",".                    
                        $this->app->db->qstr( $invoice_details['tax_system']    ).",".                    
                        $this->app->db->qstr( $item['description']              ).",".                    
                        $this->app->db->qstr( $item['unit_qty']                 ).",".
                        $this->app->db->qstr( $item['unit_net']                 ).",".
                        $this->app->db->qstr( $sales_tax_exempt                 ).",".
                        $this->app->db->qstr( $vat_tax_code                     ).",".
                        $this->app->db->qstr( $unit_tax_rate                    ).",".
                        $this->app->db->qstr( $item_totals['unit_tax']          ).",".
                        $this->app->db->qstr( $item_totals['unit_gross']        ).",".                    
                        $this->app->db->qstr( $item_totals['sub_total_net']     ).",".
                        $this->app->db->qstr( $item_totals['sub_total_tax']     ).",".
                        $this->app->db->qstr( $item_totals['sub_total_gross']   )."),";

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
                    $this->app->db->qstr( $prefill_item['description'] ).",".                    
                    $this->app->db->qstr( $prefill_item['type']        ).",".                    
                    $this->app->db->qstr( $prefill_item['unit_net']    ).",".                    
                    $this->app->db->qstr( $prefill_item['active']      )."),";
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

    public function getRecords($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $status = null, $employee_id = null, $client_id = null) {

        // Process certain variables - This prevents undefined variable errors
        $records_per_page = $records_per_page ?: '25';
        $page_no = $page_no ?: '1';
        $search_category = $search_category ?: 'invoice_id';
        $havingTheseRecords = '';

        /* Records Search */

        // Default Action
        $whereTheseRecords = "WHERE ".PRFX."invoice_records.invoice_id\n";
        $havingTheseRecords = '';

        // Restrict results by search category (client) and search term
        if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Restrict results by search category (employee) and search term
        elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        // Restrict results by search category (labour items / labour descriptions) and search term
        elseif($search_category == 'labour_items') {$whereTheseRecords .= " AND labour.labour_items LIKE ".$this->app->db->qstr('%'.$search_term.'%');} 

        // Restrict results by search category (parts items / parts descriptions) and search term
        elseif($search_category == 'parts_items') {$whereTheseRecords .= " AND parts.parts_items LIKE ".$this->app->db->qstr('%'.$search_term.'%');}    

        // Restrict results by search category and search term
        elseif($search_term != null) {$whereTheseRecords .= " AND ".PRFX."invoice_records.$search_category LIKE ".$this->app->db->qstr('%'.$search_term.'%');}

        /* Filter the Records */

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

                $whereTheseRecords .= " AND ".PRFX."invoice_records.status= ".$this->app->db->qstr($status);

            }

        }

        // Restrict by Employee
        if($employee_id) {$whereTheseRecords .= " AND ".PRFX."invoice_records.employee_id=".$this->app->db->qstr($employee_id);}        

        // Restrict by Client
        if($client_id) {$whereTheseRecords .= " AND ".PRFX."invoice_records.client_id=".$this->app->db->qstr($client_id);}

        /* The SQL code */

        $sql = "SELECT        
            ".PRFX."invoice_records.*,

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

            labour.labour_items,
            parts.parts_items

            FROM ".PRFX."invoice_records

            LEFT JOIN (
                SELECT ".PRFX."invoice_labour.invoice_id,            
                GROUP_CONCAT(
                    CONCAT(".PRFX."invoice_labour.unit_qty, ' x ', ".PRFX."invoice_labour.description)                
                    ORDER BY ".PRFX."invoice_labour.invoice_labour_id
                    ASC
                    SEPARATOR '|||'                
                ) AS labour_items           
                FROM ".PRFX."invoice_labour
                GROUP BY ".PRFX."invoice_labour.invoice_id
                ORDER BY ".PRFX."invoice_labour.invoice_id
                ASC            
            ) AS labour
            ON ".PRFX."invoice_records.invoice_id = labour.invoice_id 

            LEFT JOIN (
                SELECT ".PRFX."invoice_parts.invoice_id,            
                GROUP_CONCAT(
                    CONCAT(".PRFX."invoice_parts.unit_qty, ' x ', ".PRFX."invoice_parts.description)                
                    ORDER BY ".PRFX."invoice_parts.invoice_parts_id
                    ASC
                    SEPARATOR '|||'                
                ) AS parts_items
                FROM ".PRFX."invoice_parts
                GROUP BY ".PRFX."invoice_parts.invoice_id
                ORDER BY ".PRFX."invoice_parts.invoice_id
                ASC            
            ) AS parts
            ON ".PRFX."invoice_records.invoice_id = parts.invoice_id 

            LEFT JOIN ".PRFX."client_records ON ".PRFX."invoice_records.client_id = ".PRFX."client_records.client_id         
            LEFT JOIN ".PRFX."user_records ON ".PRFX."invoice_records.employee_id = ".PRFX."user_records.user_id

            ".$whereTheseRecords."
            GROUP BY ".PRFX."invoice_records.".$order_by."
            ".$havingTheseRecords."
            ORDER BY ".PRFX."invoice_records.".$order_by."
            ".$direction;

        /* Restrict by pages */

        if($use_pages) {

            // Get the start Record
            $start_record = (($page_no * $records_per_page) - $records_per_page);        

            // Figure out the total number of records in the database for the given search        
            if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}       
            $total_results = $rs->RecordCount();            
            $this->app->smarty->assign('total_results', $total_results);            

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

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $records = $rs->GetArray();   // do i need to add the check empty

        if(empty($records)){

            return false;

        } else {

            return $records;

        }        

    }

        

    #####################################
    #   Get invoice details             #
    #####################################

    public function getRecord($invoice_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."invoice_records WHERE invoice_id =".$this->app->db->qstr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // This makes sure there is a record to return to prevent errors (currently only needed for upgrade)
        if(!$rs->recordCount()) {

            return false;

        } else {

            if($item === null){

                return $rs->GetRowAssoc(); 

            } else {

                return $rs->fields[$item];   

            }
            
        }        

    }
    
    
    #########################################
    #   Get All invoice labour items        #
    #########################################

    public function getLabourItems($invoice_id) {

        $sql = "SELECT * FROM ".PRFX."invoice_labour WHERE invoice_id=".$this->app->db->qstr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!empty($rs)) {

            return $rs->GetArray();

        }

    }

    #######################################
    #   Get invoice labour item details   #
    #######################################

    public function getLabourItem($invoice_labour_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."invoice_labour WHERE invoice_labour_id =".$this->app->db->qstr($invoice_labour_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc();

        } else {

            return $rs->fields[$item];   

        }        

    }
    
    ############################################
    #   Get Labour Invoice Sub Totals          #
    ############################################

    public function getLabourItemsSubtotals($invoice_id) {

        // I could use $this->app->components->report->sum_labour_items() 
        // NB: i dont think i need the aliases

        $sql = "SELECT
                SUM(sub_total_net) AS sub_total_net,
                SUM(sub_total_tax) AS sub_total_tax,
                SUM(sub_total_gross) AS sub_total_gross
                FROM ".PRFX."invoice_labour
                WHERE invoice_id=". $this->app->db->qstr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetRowAssoc(); 

    }

    #####################################
    #   Get All invoice parts items     #
    #####################################

    public function getPartsItems($invoice_id) {

        $sql = "SELECT * FROM ".PRFX."invoice_parts WHERE invoice_id=".$this->app->db->qstr( $invoice_id );

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!empty($rs)) {

            return $rs->GetArray();

        }

    }

    #######################################
    #   Get invoice parts item details    #
    #######################################

    public function getPartsItem($invoice_parts_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."invoice_parts WHERE invoice_parts_id =".$this->app->db->qstr($invoice_parts_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc(); 

        } else {

            return $rs->fields[$item];   

        } 

    }
    
    ###########################################
    #   Get Parts Invoice Sub Total           #
    ###########################################

    public function getPartsItemsSubtotals($invoice_id) {

        // I could use $this->app->components->report->sum_parts_items()
        // NB: i dont think i need the aliases

        $sql = "SELECT
                SUM(sub_total_net) AS sub_total_net,
                SUM(sub_total_tax) AS sub_total_tax,
                SUM(sub_total_gross) AS sub_total_gross
                FROM ".PRFX."invoice_parts
                WHERE invoice_id=". $this->app->db->qstr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetRowAssoc();
        
    }

    #######################################
    #   Get invoice prefill items         #
    #######################################

    public function getPrefillItems($type = null, $status = null) {

        $sql = "SELECT * FROM ".PRFX."invoice_prefill_items";

        // prepare the sql for the optional filter
        $sql .= " WHERE invoice_prefill_id >= 1";

        // filter by type
        if($type) { $sql .= " AND type=".$this->app->db->qstr($type);}    

        // filter by status
        if($status) {$sql .= " AND active=".$this->app->db->qstr($status);}

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
            $sql .= "\nWHERE status_key NOT IN ('partially_paid', 'paid', 'in_dispute', 'overdue', 'collections', 'refunded', 'cancelled', 'deleted')";
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();   

    }

    ######################################
    #  Get Invoice status display name   #
    ######################################

    public function getStatusDisplayName($status_key) {

        $sql = "SELECT display_name FROM ".PRFX."invoice_statuses WHERE status_key=".$this->app->db->qstr($status_key);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['display_name'];  

    }
    

    /** Update Functions **/
    
    #####################################
    #     update invoice (full)         #  // not currently used
    #####################################

    public function updateRecordFull($qform, $doNotLog = false) {

        $sql = "UPDATE ".PRFX."invoice_records SET     
                employee_id         =". $this->app->db->qstr( $qform['employee_id']     ).", 
                client_id           =". $this->app->db->qstr( $qform['client_id']       ).",
                workorder_id        =". $this->app->db->qstr( $qform['workorder_id']    ).",               
                date                =". $this->app->db->qstr( $qform['date']            ).",
                due_date            =". $this->app->db->qstr( $qform['due_date']        ).", 
                tax_system          =". $this->app->db->qstr( $qform['tax_system']      ).", 
                unit_discount_rate  =". $this->app->db->qstr( $qform['unit_discount_rate']   ).",                            
                unit_discount       =". $this->app->db->qstr( $qform['unit_discount'] ).",   
                unit_net            =". $this->app->db->qstr( $qform['unit_net']      ).",
                sales_tax_rate      =". $this->app->db->qstr( $qform['sales_tax_rate']  ).",
                unit_tax            =". $this->app->db->qstr( $qform['unit_tax']      ).",             
                unit_gross          =". $this->app->db->qstr( $qform['unit_gross']    ).", 
                unit_paid           =". $this->app->db->qstr( $qform['unit_paid']     ).",
                balance             =". $this->app->db->qstr( $qform['balance']         ).",
                opened_on           =". $this->app->db->qstr( $qform['opened_on']       ).",
                closed_on           =". $this->app->db->qstr( $qform['closed_on']       ).",
                last_active         =". $this->app->db->qstr( $qform['last_active']     ).",
                status              =". $this->app->db->qstr( $qform['status']          ).",
                is_closed           =". $this->app->db->qstr( $qform['is_closed']       )."            
                WHERE invoice_id    =". $this->app->db->qstr( $qform['invoice_id']      );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if (!$doNotLog) {

            // Create a Workorder History Note  
            $this->app->components->workorder->insertHistory($this->db, $qform['workorder_id'], _gettext("Invoice").' '.$qform['invoice_id'].' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.');

            // Log activity        
            $record = _gettext("Invoice").' '.$qform['invoice_id'].' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';        
            $this->app->system->general->writeRecordToActivityLog($record, $qform['employee_id'], $qform['client_id'], $qform['workorder_id'], $qform['invoice_id']);

            // Update last active record    
            $this->app->components->client->updateLastActive($this->db, $qform['client_id']);
            $this->app->components->workorder->updateLastActive($this->db, $qform['workorder_id']);
            $this->updateLastActive($this->db, $qform['invoice_id']);        

        }

        return true;

    }    

    ####################################
    #   update invoice static values   #  // This is used when a user updates an invoice before any payments
    ####################################

    public function updateStaticValues($invoice_id, $date, $due_date, $unit_discount_rate) {

        $sql = "UPDATE ".PRFX."invoice_records SET
                date                =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($date)     ).",
                due_date            =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($due_date) ).",
                unit_discount_rate  =". $this->app->db->qstr( $unit_discount_rate           )."               
                WHERE invoice_id    =". $this->app->db->qstr( $invoice_id                   );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        $invoice_details = $this->getRecord($invoice_id);

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($invoice_details['workorder_id'], _gettext("Invoice").' '.$invoice_id.' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("was updated by").' '.$this->app->user->login_display_name.'.';        
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update last active record    
        $this->app->components->client->updateLastActive($this->getRecord($invoice_id, 'client_id'));
        $this->app->components->workorder->updateLastActive($this->getRecord($invoice_id, 'workorder_id'));
        $this->updateLastActive($invoice_id);        

    }

    ############################
    # Update Invoice Status    #
    ############################

    public function updateStatus($invoice_id, $new_status) {

        // Get invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // if the new status is the same as the current one, exit
        if($new_status == $invoice_details['status']) {        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new invoice status is the same as the current invoice status."));
            return false;
        }    

        // Set the appropriate employee_id
        $employee_id = ($new_status == 'unassigned') ? '' : $invoice_details['employee_id'];

        // Set the appropriate closed_on date
        $closed_on = ($new_status == 'closed') ? $this->app->system->general->mysqlDatetime() : '0000-00-00 00:00:00';

        $sql = "UPDATE ".PRFX."invoice_records SET   
                employee_id         =". $this->app->db->qstr( $employee_id     ).",
                status              =". $this->app->db->qstr( $new_status      ).",
                closed_on           =". $this->app->db->qstr( $closed_on       )."  
                WHERE invoice_id    =". $this->app->db->qstr( $invoice_id      );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}   

        // Update invoice 'is_closed' boolean
        if($new_status == 'paid' || $new_status == 'refunded' || $new_status == 'cancelled' || $new_status == 'deleted') {
            $this->updateClosedStatus($invoice_id, 'closed');
        } else {
            $this->updateClosedStatus($invoice_id, 'open');
        }

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
        $this->app->components->client->updateLastActive($invoice_details['client_id']);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id']);
        $this->updateLastActive($invoice_id);                

        return true;

    }

    ###################################
    # Update invoice Closed Status    #
    ###################################

    public function updateClosedStatus($invoice_id, $new_closed_status) {

        if($new_closed_status == 'open') {

            $sql = "UPDATE ".PRFX."invoice_records SET
                    closed_on           = '0000-00-00 00:00:00',
                    is_closed           =". $this->app->db->qstr( 0                )."
                    WHERE invoice_id    =". $this->app->db->qstr( $invoice_id      );

        }

        if($new_closed_status == 'closed') {

            $sql = "UPDATE ".PRFX."invoice_records SET
                    closed_on           =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime() ).",
                    is_closed           =". $this->app->db->qstr( 1                )."
                    WHERE invoice_id    =". $this->app->db->qstr( $invoice_id      );
        }    

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }



    #################################
    #    Update invoice refund ID   #
    #################################

    public function updateRefundId($invoice_id, $refund_id) {

        $sql = "UPDATE ".PRFX."invoice_records SET
                refund_id           =".$this->app->db->qstr($refund_id)."
                WHERE invoice_id    =".$this->app->db->qstr($invoice_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }
    
    #################################
    #    Update Last Active         #
    #################################

    public function updateLastActive($invoice_id = null) {

        // compensate for some workorders not having invoices
        if(!$invoice_id) { return; }

        $sql = "UPDATE ".PRFX."invoice_records SET
                last_active=".$this->app->db->qstr( $this->app->system->general->mysqlDatetime() )."
                WHERE invoice_id=".$this->app->db->qstr($invoice_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }    
        

    /** Close Functions **/

    #####################################
    #   Refund Invoice                  #
    #####################################

    public function refundRecord($refund_details) {

        // Make sure the invoice can be refunded
        if(!$this->checkRecordAllowsRefund($refund_details['invoice_id'])) {
            return false;
        }

        // Get invoice details
        $invoice_details = $this->getRecord($refund_details['invoice_id']);

        // Insert refund record and return refund_id
        $refund_id = $this->app->components->refund->insertRecord($refund_details);

        // Refund any Vouchers
        $this->app->components->voucher->refundInvoiceVouchers($refund_details['invoice_id'], $refund_id);

        // Update the invoice with the new refund_id
        $this->updateRefundId($refund_details['invoice_id'], $refund_id);    

        // Change the invoice status to refunded (I do this here to maintain consistency)
        $this->updateStatus($refund_details['invoice_id'], 'refunded');

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($invoice_details['invoice_id'], _gettext("Invoice").' '.$refund_details['invoice_id'].' '._gettext("was refunded by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Invoice").' '.$refund_details['invoice_id'].' '._gettext("for Work Order").' '.$invoice_details['invoice_id'].' '._gettext("was refunded by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $refund_details['invoice_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($invoice_details['client_id']);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id']);
        $this->updateLastActive($invoice_details['invoice_id']);

        return $refund_id;

    }
    
    #####################################
    #   Cancel Invoice                  # // This does not delete information i.e. client went bust and did not pay
    #####################################

    public function cancelRecord($invoice_id) {

        // Make sure the invoice can be cancelled
        if(!$this->checkRecordAllowsCancel($invoice_id)) {        
            return false;
        }

        // Get invoice details
        $invoice_details = $this->getRecord($invoice_id);  

        // Cancel any Vouchers
        $this->app->components->voucher->cancelInvoiceVouchers($invoice_id);

        // Change the invoice status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($invoice_id, 'cancelled');      

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($invoice_details['invoice_id'], _gettext("Invoice").' '.$invoice_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("for Work Order").' '.$invoice_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update last active record
        $this->app->components->client->updateLastActive($invoice_details['client_id']);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id']);
        $this->updateLastActive($invoice_id);

        return true;

    }

    /** Delete Functions **/

    #####################################
    #   Delete Invoice                  #
    #####################################

    public function deleteRecord($invoice_id) {

        // Make sure the invoice can be deleted 
        if(!$this->checkRecordAllowsDelete($invoice_id)) {        
            return false;
        }

        // Get invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Delete any Vouchers
        $this->app->components->voucher->deleteInvoiceVouchers($invoice_id);  

        // Delete parts and labour
        $this->deleteLabourItems($invoice_id);
        $this->deletePartsItems($invoice_id);

        // Change the invoice status to deleted (I do this here to maintain log consistency)
        $this->updateStatus($invoice_id, 'deleted'); 

        // Build the data to replace the invoice record (some stuff has just been updated with $this->update_invoice_status())
        $sql = "UPDATE ".PRFX."invoice_records SET        
                employee_id         = '', 
                client_id           = '',
                workorder_id        = '',               
                date                = '0000-00-00',    
                due_date            = '0000-00-00', 
                tax_system          = '',  
                unit_discount_rate  = '0.00',                         
                unit_discount       = '0.00', 
                unit_net            = '0.00', 
                sales_tax_rate      = '0.00', 
                unit_tax            = '0.00',             
                unit_gross          = '0.00',  
                unit_paid           = '0.00', 
                balance             = '0.00',
                status              = 'deleted',
                opened_on           = '0000-00-00 00:00:00',
                closed_on           = '0000-00-00 00:00:00',
                last_active         = '0000-00-00 00:00:00',            
                is_closed           = '1'            
                WHERE invoice_id    =". $this->app->db->qstr( $invoice_id  );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Remove the invoice_if from the related Workorder record (if present)
        $this->app->components->workorder->updateInvoiceId($invoice_details['workorder_id'], '');               

        // Create a Workorder History Note  
        $this->app->components->workorder->insertHistory($invoice_id, _gettext("Invoice").' '.$invoice_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Invoice").' '.$invoice_id.' '._gettext("for Work Order").' '.$invoice_id.' '._gettext("was deleted by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_id);

        // Update workorder status
        $this->app->components->workorder->updateStatus($invoice_details['workorder_id'], 'closed_without_invoice');        

        // Update last active record
        $this->app->components->client->updateLastActive($invoice_details['client_id']);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id']);
        $this->updateLastActive($invoice_id);

        return true;

    }

    #############################################
    #   Delete an invoice's Labour Items (ALL)  #
    #############################################

    public function deleteLabourItems($invoice_id) {

        $sql = "DELETE FROM ".PRFX."invoice_labour WHERE invoice_id=" . $this->app->db->qstr($invoice_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return true;

    }
    
    #############################################
    #   Delete an invoice's Parts Items (ALL)   #
    #############################################

    public function deletePartsItems($invoice_id) {

        $sql = "DELETE FROM ".PRFX."invoice_parts WHERE invoice_id=" . $this->app->db->qstr($invoice_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return true;

    }   

    /** Check Functions **/
    
    ##########################################################
    #  Check if the invoice status is allowed to be changed  #
    ##########################################################

     public function checkRecordAllowsChange($invoice_id) {

        $state_flag = true; 

        // Get the invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Is partially paid
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has payments and is partially paid."));
            return false;        
        }

        // Is paid
        if($invoice_details['status'] == 'paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has payments and is paid."));
            return false;        
        }

        // Is partially refunded (not currently used)
        if($invoice_details['status'] == 'partially_refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been partially refunded."));
            return false;        
        }

        // Is refunded
        if($invoice_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been refunded."));
            return false;        
        }

        // Is cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been cancelled."));
            return false;        
        }

        // Is deleted
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been deleted."));
            return false;        
        }

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'invoice', null, null, null, $invoice_id)) {       
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has payments."));
            return false;        
        }

        // Does the invoice have any Vouchers preventing changing the invoice status
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceEdit($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because of Vouchers on it prevent this."));
            return false;
        } 

        return $state_flag;     

     }

    ###############################################################
    #   Check to see if the invoice can be refunded (by status)   #
    ###############################################################

    public function checkRecordAllowsRefund($invoice_id) {

        $state_flag = true;

        // Get the invoice details
        $invoice_details = $this->getRecord($invoice_id);

        // Is partially paid
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be refunded because the invoice is partially paid."));
            return false;
        }

        // Is partially refunded (not currently used)
        if($invoice_details['status'] == 'partially_refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been partially refunded."));
            return false;        
        }

        // Is refunded
        if($invoice_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because the invoice has already been refunded."));
            return false;        
        }

        // Is cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because the invoice has been cancelled."));
            return false;        
        }

        // Is deleted
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because the invoice has been deleted."));
            return false;        
        }    

        // Has no payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if(!$this->app->components->report->countPayments(null, null, 'date', null, null, 'invoice', null, null, null, $invoice_id)) { 
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be refunded because the invoice has no payments."));
            return false;        
        }

        // Has Refunds (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countRefunds(null, null, null, null, $invoice_id) > 0) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because the invoice has already been refunded."));
            return false;
        }

        // Does the invoice have any Vouchers preventing refunding the invoice (i.e. any that have been used)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceRefund($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be refunded because of Vouchers on it prevent this."));
            return false;
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

        // Does not have a balance
        if($invoice_details['balance'] == 0) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be cancelled because the invoice does not have a balance."));
            return false;
        }

        // Is partially paid
        if($invoice_details['status'] == 'partially_paid') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be cancelled because the invoice is partially paid."));
            return false;
        }

        // Is partially refunded (not currently used)
        if($invoice_details['status'] == 'partially_refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been partially refunded."));
            return false;        
        }


        // Is refunded
        if($invoice_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because the invoice has been refunded."));
            return false;        
        }

        // Is cancelled
        if($invoice_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because the invoice has already been cancelled."));
            return false;        
        }

        // Is deleted
        if($invoice_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because the invoice has been deleted."));
            return false;        
        }    

        // Has no payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'invoice', null, null, null, $invoice_id)) { 
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be cancelled because the invoice has payments."));
            return false;        
        }

        // Has Refunds (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countRefunds(null, null, null, null, $invoice_id) > 0) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because the invoice has been refunded."));
            return false;
        }

        // Does the invoice have any Vouchers preventing cancelling the invoice (i.e. any that have been used)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceCancel($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be cancelled because of Vouchers on it prevent this."));
            return false;
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

        // Is partially refunded (not currently used)
        if($invoice_details['status'] == 'partially_refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice status cannot be changed because the invoice has been partially refunded."));
            $state_flag = false;       
        }     

        // Is refunded
        if($invoice_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has been refunded."));
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

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'invoice', null, null, null, $invoice_id)) { 
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has payments."));
            $state_flag = false;       
        }

        /*
        // Has Labour (these will get deleted anyway)
        if(!empty($this->get_invoice_labour_items($invoice_id))) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has labour items."));
            $state_flag = false;         
        }    

        // Has Parts (these will get deleted anyway)
        if(!empty($this->get_invoice_parts_items($invoice_id))) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has parts."));
            $state_flag = false;         
        }
        */

        // Has Refunds (should not be needed)
        if($this->app->components->report->countRefunds(null, null, null, null, $invoice_id) > 0) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be deleted because it has been refunded."));
            return false;
        }

        // Does the invoice have any Vouchers preventing refunding the invoice (i.e. any that have been used)
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceDelete($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be deleted because of Vouchers on it prevent this."));
            return false;
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

        // Is partially refunded (not currently used)
        if($invoice_details['status'] == 'partially_refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because the invoice has been partially refunded."));
            $state_flag = false;       
        }

        // Is refunded
        if($invoice_details['status'] == 'refunded') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because the invoice has been refunded."));
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

        // Has payments (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if($this->app->components->report->countPayments(null, null, 'date', null, null, 'invoice', null, null, null, $invoice_id)) {       
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because the invoice has payments."));
            $state_flag = false;       
        }

        // Does the invoice have any Vouchers preventing changing the invoice status
        if(!$this->app->components->voucher->checkInvoiceVouchersAllowsInvoiceEdit($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot be edited because of Vouchers on it prevent this."));
            return false;
        }

        // The current record VAT code is enabled
        if(!$this->checkVatTaxCodeStatuses($invoice_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This invoice cannot be edited because one or more of the parts or labour have a VAT Tax Code that is not enabled."));
            $state_flag = false;
        }

        return $state_flag;   

    }    

    /** Other Functions **/

    ################################################  // sales tax and VAT are the same
    #   calculate an Invoice Item Sub Totals       #  // The exact dane totals calculation is done for all, just the inputs are different
    ################################################

    public function calculateItemsSubtotals($tax_system, $unit_qty, $unit_net, $unit_tax_rate = null) {

        $item_totals = array();

        // No Tax
        if($tax_system == 'no_tax') {        
            $item_totals['unit_tax'] = 0.00;
            $item_totals['unit_gross'] = $unit_net;
            $item_totals['sub_total_net'] = $unit_net * $unit_qty;
            $item_totals['sub_total_tax'] = 0.00;
            $item_totals['sub_total_gross'] = $item_totals['sub_total_net'];
        }

        // Sales Tax Calculations
        if($tax_system == 'sales_tax_cash') {        
            $item_totals['unit_tax'] = $unit_net * ($unit_tax_rate / 100);
            $item_totals['unit_gross'] = $unit_net + $item_totals['unit_tax'];
            $item_totals['sub_total_net'] = $unit_net * $unit_qty;
            $item_totals['sub_total_tax'] = $item_totals['sub_total_net'] * ($unit_tax_rate / 100);
            $item_totals['sub_total_gross'] = $item_totals['sub_total_net'] + $item_totals['sub_total_tax'];
        }

        // VAT Calculations
        if(preg_match('/^vat_/', $tax_system)) {        
            $item_totals['unit_tax'] = $unit_net * ($unit_tax_rate / 100);
            $item_totals['unit_gross'] = $unit_net + $item_totals['unit_tax'];
            $item_totals['sub_total_net'] = $unit_net * $unit_qty;
            $item_totals['sub_total_tax'] = $item_totals['sub_total_net'] * ($unit_tax_rate / 100);
            $item_totals['sub_total_gross'] = $item_totals['sub_total_net'] + $item_totals['sub_total_tax'];
        }

        return $item_totals;

    }

    #####################################  // A lot of these calculations are done in the invoice:edit tpl - this is still required for when payments are made becasue of the balance field
    #   Recalculate Invoice Totals      #   ///  re-check these calcuclations as they are wrong (not much though) i should account for vouchers as if they had tax allow for development later.
    #####################################  // Vouchers are not discounted

    public function recalculateTotals($invoice_id) {

        $invoice_details            = $this->getRecord($invoice_id);    

        $labour_items_sub_totals    = $this->getLabourItemsSubtotals($invoice_id); 
        $parts_items_sub_totals     = $this->getPartsItemsSubtotals($invoice_id);   
        $voucher_sub_totals         = $this->app->components->voucher->getInvoiceVouchersSubtotals($invoice_id);

        $unit_discount              = ($labour_items_sub_totals['sub_total_net'] + $parts_items_sub_totals['sub_total_net']) * ($invoice_details['unit_discount_rate'] / 100);          // Divide by 100; turns 17.5 in to 0.17575
        $unit_net                   = ($labour_items_sub_totals['sub_total_net'] + $parts_items_sub_totals['sub_total_net'] + $voucher_sub_totals['sub_total_net']) - $unit_discount;   // Vouchers are not discounted on purpose
        $unit_tax                   = $labour_items_sub_totals['sub_total_tax'] + $parts_items_sub_totals['sub_total_tax'] + $voucher_sub_totals['sub_total_tax'];
        $unit_gross                 = $unit_net + $unit_tax;    
        $payments_sub_total         = $this->app->components->report->sumPayments(null, null, 'date', null, 'valid', 'invoice', null, null, null, $invoice_id);
        $balance                    = $unit_gross - $payments_sub_total;

        $sql = "UPDATE ".PRFX."invoice_records SET            
                unit_discount       =". $this->app->db->qstr( $unit_discount       ).",
                unit_net            =". $this->app->db->qstr( $unit_net            ).",
                unit_tax            =". $this->app->db->qstr( $unit_tax            ).",
                unit_gross          =". $this->app->db->qstr( $unit_gross          ).",
                unit_paid           =". $this->app->db->qstr( $payments_sub_total  ).",
                balance             =". $this->app->db->qstr( $balance             )."
                WHERE invoice_id    =". $this->app->db->qstr( $invoice_id          );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        /* Update Status - only change if there is a change in status*/        

        // No invoiceable amount, set to pending (if not already)
        if($unit_gross == 0 && $invoice_details['status'] != 'pending') {
            $this->updateStatus($invoice_id, 'pending');
        }

        // Has invoiceable amount with no payments, set to unpaid (if not already)
        elseif($unit_gross > 0 && $unit_gross == $balance && $invoice_details['status'] != 'unpaid') {
            $this->updateStatus($invoice_id, 'unpaid');
        }

        // Has invoiceable amount with partially payment, set to partially paid (if not already)
        elseif($unit_gross > 0 && $payments_sub_total > 0 && $payments_sub_total < $unit_gross && $invoice_details['status'] != 'partially_paid') {            
            $this->updateStatus($invoice_id, 'partially_paid');
        }

        // Has invoicable amount and the payment(s) match the invoiceable amount, set to paid (if not already)
        elseif($unit_gross > 0 && $unit_gross == $payments_sub_total && $invoice_details['status'] != 'paid') {            
            $this->updateStatus($invoice_id, 'paid');
        }        

        return;

    }

    #####################################
    #   Upload labour rates CSV file    #
    #####################################

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

                $sql = "INSERT INTO ".PRFX."invoice_prefill_items(description, type, unit_net, active) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";

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

        $sql = "SELECT description, type, unit_net, active FROM ".PRFX."invoice_prefill_items";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}     

        $prefill_items = $rs->GetArray();

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=qwcrm_invoice_prefill_items.csv');

        // create a file pointer connected to the output stream
        $output_stream = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output_stream, array(_gettext("Description"), _gettext("Type"), _gettext("Unit Net"), _gettext("Active")));

        // loop over the rows, outputting them
        foreach($prefill_items as $key => $value) {
            $row = array($value['description'], $value['type'], $value['unit_net'], $value['active']);
            fputcsv($output_stream, $row);            
        }       

        // close the csv file
        fclose($output_stream);

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("Invoice Prefill Items were exported by").' '.$this->app->user->login_display_name.'.');   

    }


    #########################################
    # Assign Workorder to another employee  #
    #########################################

    public function assignToEmployee($invoice_id, $target_employee_id) {

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
                    employee_id         =". $this->app->db->qstr( $target_employee_id  ).",
                    status              =". $this->app->db->qstr( 'assigned'           )."
                    WHERE invoice_id    =". $this->app->db->qstr( $invoice_id          );

        // Keep the same invoice status    
        } else {    

            $sql = "UPDATE ".PRFX."invoice_records SET
                    employee_id         =". $this->app->db->qstr( $target_employee_id  )."            
                    WHERE invoice_id    =". $this->app->db->qstr( $invoice_id          );

        }

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Assigned employee success message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Assigned employee updated."));        

        // Get Logged in Employee's Display Name        
        $logged_in_employee_display_name = $this->app->user->login_display_name;

        // Get the currently assigned employee ID
        $assigned_employee_id = $invoice_details['employee_id'];

        // Get the Display Name of the currently Assigned Employee
        if($assigned_employee_id == ''){
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
        $this->app->components->user->updateLastActive($invoice_details['employee_id']);
        $this->app->components->user->updateLastActive($target_employee_id);
        $this->app->components->client->updateLastActive($invoice_details['client_id']);
        $this->app->components->workorder->updateLastActive($invoice_details['workorder_id']);
        $this->updateLastActive($invoice_id);

        return true;

    }

    ####################################################################
    #   Check invoice Labour and parts VAT Tax Codes are all enabled   #
    ####################################################################

    public function checkVatTaxCodeStatuses($invoice_id) {

        $state_flag = true;

        // Check all labour
        foreach ($this->getLabourItems($invoice_id) as $key => $value) {        
            if(!$this->app->components->company->getVatTaxCodeStatus($value['vat_tax_code'])) { $state_flag = false;}        
        }

        // Check all parts
        foreach ($this->getPartsItems($invoice_id) as $key => $value) {        
            if(!$this->app->components->company->getVatTaxCodeStatus($value['vat_tax_code'])) { $state_flag = false;}        
        }

        return $state_flag; 

    }
    
}