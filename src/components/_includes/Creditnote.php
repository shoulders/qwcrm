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

    public function insertRecord($crRecord) {

        // Unify Dates and Times
        $timestamp = time();
        
        // Generate the creditnote expiry date
        $dateObject = new DateTime();    
        $dateObject->modify('+'.$this->app->components->company->getRecord('creditnote_expiry_offset').' days');
        $expiry_date = $dateObject->format('Y-m-d');

        $sql = "INSERT INTO ".PRFX."creditnote_records SET     
                employee_id     =". $this->app->db->qStr( $this->app->user->login_user_id   ).",
                client_id       =". $this->app->db->qStr( $crRecord['client_id']                          ).",
                invoice_id      =". $this->app->db->qStr( $crRecord['invoice_id']                         ).",     
                supplier_id     =". $this->app->db->qStr( $crRecord['supplier_id']                         ).",
                expense_id      =". $this->app->db->qStr( $crRecord['expense_id']                         ).",                    
                date            =". $this->app->db->qStr( $this->app->system->general->mysqlDate($timestamp)).",
                expiry_date     =". $this->app->db->qStr( $expiry_date ).",
                type            =". $this->app->db->qStr( $crRecord['type']                         ).", 
                reference       =". $this->app->db->qStr( $crRecord['reference']                         ).", 
                tax_system      =". $this->app->db->qStr( QW_TAX_SYSTEM                          ).",                
                sales_tax_rate  =". $this->app->db->qStr( $crRecord['sales_tax_rate']                      ).",            
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
               
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $crRecord['client_id'], null, $crRecord['invoice_id']);

        // Update last active record    
        $this->app->components->client->updateLastActive($crRecord['client_id']);
        $this->app->components->invoice->updateLastActive($crRecord['invoice_id']);     

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
                
                /* All this is done in the TPL
                    // Calculate the correct tax rate based on tax system (and exemption status)
                    if($creditnote_details['tax_system'] == 'sales_tax_cash' && $sales_tax_exempt) { $unit_tax_rate = 0.00; }
                    elseif($creditnote_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $creditnote_details['sales_tax_rate']; }
                    elseif(preg_match('/^vat_/', $creditnote_details['tax_system'])) { $unit_tax_rate = $this->app->components->company->getVatRate($item['vat_tax_code']); }
                    else { $unit_tax_rate = 0.00; }                

                    // Build item totals based on selected TAX system
                    $item_totals = $this->calculateItemsSubtotals($creditnote_details['tax_system'], $item['unit_qty'], $item['unit_net'], $unit_tax_rate);
                */

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

    ##################################### done
    #   Get Credit Note details         #
    #####################################

    public function getRecord($creditnote_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."creditnote_records WHERE creditnote_id =".$this->app->db->qStr($creditnote_id);

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
    
    
    ######################################### done
    #   Get All Credit Note items           #
    #########################################

    public function getItems($creditnote_id) {

        $sql = "SELECT * FROM ".PRFX."creditnote_items WHERE creditnote_id=".$this->app->db->qStr($creditnote_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!empty($rs)) {

            return $rs->GetArray();

        }

    }

    #######################################  done
    #   Get Credit Note item details      #  // not used anywhere
    #######################################

    public function getItem($creditnote_item_id, $item = null) {

        $sql = "SELECT * FROM ".PRFX."creditnote_items WHERE creditnote_item_id =".$this->app->db->qStr($creditnote_item_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc();

        } else {

            return $rs->fields[$item];   

        }        

    }
    
    ############################################ done
    #   Get Credit Note items Sub Totals       #
    ############################################

    public function getItemsSubtotals($creditnote_id) {

        // I could use $this->app->components->report->sumCreditnoteItems() - with additional calculation for subtotal_discount
        // NB: i dont think i need the aliases
        // $creditnote_items_subtotals = $this->app->components->report->getCreditnotesStats('items', null, null, null, null, null, $invoice_id);        
        
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
        $this->app->components->client->updateLastActive($this->getRecord($creditnote_details['creditnote_id'], 'client_id'));        
        $this->app->components->invoice->updateLastActive($this->getRecord($creditnote_details['creditnote_id'], 'invoice_id'));         
        
        return;

    }  
    
    /*####################################
    #   update invoice static values   #  // This is used when a user updates an invoice before any payments
    ####################################

    public function updateStaticValues($invoice_id, $date, $due_date) {

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
        $this->app->components->client->updateLastActive($this->getRecord($invoice_id, 'client_id'));
        $this->app->components->workorder->updateLastActive($this->getRecord($invoice_id, 'workorder_id'));
        $this->updateLastActive($invoice_id);        

    }*/

    ################################ done
    # Update Credit Note Status    #
    ################################

    public function updateStatus($creditnote_id, $new_status) {

        // Get credit note details
        $creditnote_details = $this->getRecord($creditnote_id);

        // If the new status is the same as the current one, exit
        if($new_status == $creditnote_details['status']) {        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Nothing done. The new credit note status is the same as the current creditnote status."));
            return false;
        }    

        // Set the appropriate employee_id
        $employee_id = ($new_status == 'unassigned') ? null : $creditnote_details['employee_id'];
        
        $sql = "UPDATE ".PRFX."creditnote_records SET   
                employee_id         =". $this->app->db->qStr( $employee_id     ).",
                status              =". $this->app->db->qStr( $new_status      )."               
                WHERE creditnote_id =". $this->app->db->qStr( $creditnote_id      );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}   

        // Update creditnote 'is_closed' boolean
        if($new_status == 'fully_applied' || $new_status == 'expired_unused' || $new_status == 'cancelled' || $new_status == 'deleted') {
            $this->updateClosedStatus($creditnote_id, 'closed');
        } else {
            $this->updateClosedStatus($creditnote_id, 'open');
        }

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
        $this->app->components->client->updateLastActive($creditnote_details['client_id']);
        $this->app->components->invoice->updateLastActive($creditnote_details['invoice_id']);
                    

        return true;

    }

    ####################################### done
    # Update Credit Note Closed Status    #
    #######################################

    public function updateClosedStatus($creditnote_id, $new_closed_status) {

        if($new_closed_status == 'open') {

            $sql = "UPDATE ".PRFX."creditnote_records SET
                    closed_on           = NULL,
                    is_closed           =". $this->app->db->qStr( 0                )."
                    WHERE creditnote_id    =". $this->app->db->qStr( $creditnote_id      );

        }

        if($new_closed_status == 'closed') {

            $sql = "UPDATE ".PRFX."creditnote_records SET
                    closed_on           =". $this->app->db->qStr( $this->app->system->general->mysqlDatetime() ).",
                    is_closed           =". $this->app->db->qStr( 1                )."
                    WHERE creditnote_id    =". $this->app->db->qStr( $creditnote_id      );
        }    

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }

    
    ################################# done
    #    Update Last Active         # 
    #################################

    public function updateLastActive($creditnote_id) {

        $sql = "UPDATE ".PRFX."creditnote_records SET
                last_active=".$this->app->db->qStr( $this->app->system->general->mysqlDatetime() )."
                WHERE creditnote_id=".$this->app->db->qStr($creditnote_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }    
     
    ###################################  done
    #    update additional info       #
    ###################################

    public function updateAdditionalInfo($creditnote_id, $additional_info = null) {

        $sql = "UPDATE ".PRFX."creditnote_records SET
                additional_info=".$this->app->db->qStr( $additional_info )."
                WHERE creditnote_id=".$this->app->db->qStr($creditnote_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }    

    /** Close Functions **/

    /*##################################### //not used
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

        // Refund any Vouchers - handled in updateInvoiceVouchersStatuses()
        //$this->app->components->voucher->refundInvoiceVouchers($refund_details['invoice_id'], $refund_id);

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

    }*/
    
    ##################################### done
    #   Cancel Credit Note              # // This does not delete information i.e. client went bust and did not pay
    #####################################

    public function cancelRecord($creditnote_id, $reason_for_cancelling = null) {

        // Make sure the creditnote can be cancelled
        if(!$this->checkRecordAllowsCancel($creditnote_id)) {        
            return false;
        }

        // Get creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);  

        // Change the creditnote status to cancelled (I do this here to maintain consistency)
        $this->updateStatus($creditnote_id, 'cancelled');
        
        // Add Cancelled message to the additional info
        $this->updateAdditionalInfo($creditnote_id, $this->buildAdditionalInfoJson($creditnote_id, $reason_for_cancelling));

        // Create a Workorder History Note  - this is an invoice
        //$this->app->components->workorder->insertHistory($invoice_details['invoice_id'], _gettext("Invoice").' '.$invoice_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.');

        // Log activity        
        $record = _gettext("Credit Note").' '.$creditnote_id.' '._gettext("was cancelled by").' '.$this->app->user->login_display_name.'.';
        $this->app->system->general->writeRecordToActivityLog($record, $creditnote_details['employee_id'], $creditnote_details['client_id'], null, $creditnote_details['invoice_id']);

        // Update last active record
        $this->app->components->client->updateLastActive($creditnote_details['client_id']);
        $this->updateLastActive($creditnote_id);

        return true;

    }

    /** Delete Functions **/

    ##################################### done
    #   Delete Credit Note              #
    #####################################

    public function deleteRecord($creditnote_id) {

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

        // Build the data to replace the creditnote record (some stuff has just been updated with $this->updateStatus())
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
        $this->app->components->client->updateLastActive($creditnote_details['client_id']);        
        $this->updateLastActive($creditnote_id);

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
        
        // Has the creditnote been closed already (same effect as expired)
        elseif($creditnote_details['closed_on'])
        {
            $expired_status = true;          
        }

        // Has the creditnote just expired and needs to be updated
        elseif (time() > strtotime($creditnote_details['expiry_date'].' 23:59:59'))
        {            
            $expired_status = true;
            
            // Update the creditnote record
            $sql = "UPDATE ".PRFX."creditnote_records SET                
                closed_on           =". $this->app->db->qstr( $this->app->system->general->mysqlDatetime())."
                WHERE creditnote_id    =". $this->app->db->qstr( $creditnote_id          );
            if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);} 
            
            // Update the creditnote status (silenty)
            if ($creditnote_details['status'] == 'pending' || $creditnote_details['status'] == 'unused') {
                $this->updateStatus($creditnote_id, 'expired_unused', true);      
            }            

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
    
 ////////////////////////////////////////////////////////  
    
    ###############################################   done
    #  Check if a credit note can be created      #  // Used to hide create CR buttons + in creditnote:new
    ###############################################
    
    public function checkRecordCanBeCreated($client_id = null, $invoice_id = null, $supplier_id = null, $expense_id = null, $silent = true) {
        
        $state_flag = true;
        
        // If CR is created from an Invoice
        if($invoice_id)
        {
            $invoice_details = $this->app->components->invoice->getRecord($invoice_id);
            
            // Is on a different tax system
            if($invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                if(!$silent)
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be created because the supplied invoice is on another tax system."));
                }
                $state_flag = false;       
            }
            
            // Invoice needs an outstanding balance
            if(!$invoice_details['balance'])
            {
                if(!$silent)
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("An invoice needs a balance to be able to generate a linked credit note."));
                }
                $state_flag = false;
            }        
           
        }
        
        // If CR is created from an Expense
        elseif($expense_id)
        {
            $expense_details = $this->app->components->expense->getRecord($expense_id);
            
            // Is on a different tax system
            if($expense_details['tax_system'] != QW_TAX_SYSTEM) {
                if(!$silent)
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be created because the supplied expense is on another tax system."));
                }
                $state_flag = false;       
            }
            
            // Needs a supplier ID (legacy records with no assigned supplier)
            if(!$expense_details['supplier_id'])
            {
                if(!$silent)
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("A supplier is required to generate a linked credit note"));
                }
                $state_flag = false;
            }
            
            // Expense needs an outstanding balance
            if(!$expense_details['balance'])
            {
                if(!$silent)
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("An expense needs a balance to be able to generate a linked credit note."));
                }
                $state_flag = false;
            }
        }
        // Standalone CR
        else
        {
            // If CR is created from a Client
            if($client_id)
            {
                // Do nothing                
            }
            
            // If CR is created from a Supplier
            if($supplier_id)
            {
                // Do nothing
            }
        }
        
        return $state_flag;   
        
    }
    
    ############################################################# done
    # Validate submitted information before allowing submission #
    #############################################################
    
    public function checkRecordCanBeSubmitted($qform)
    {
        $state_flag = true; 
        
        // Check the expiry date is valid,
        if(!$this->checkCreditnoteExpiryIsValid($qform['expiry_date']))
        {
           $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be submitted because there is an issue with the expiry date."));
           $state_flag = false; 
        }
        
        // Current credit note details stored in the database
        $creditnote_details = $this->app->components->creditnote->getRecord($qform['creditnote_id']);
        
        // Check there is a positive unit_gross
        if($qform['unit_gross'] <= 0)
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot have a negative or zero gross amount."));
            $state_flag = false;                     
        }
        
        // If CR is created from an Invoice
        if($qform['invoice_id'])
        {
            $invoice_details = $this->app->components->invoice->getRecord($qform['invoice_id']);
            
            // Submitted unit_gross cannot be greater than the invoice unit_gross
            if($qform['unit_gross'] > $invoice_details['unit_gross'])
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be submitted because it's gross amount is greater than the linked invoices's gross amount."));
                $state_flag = false;
            }        
            
            // Submitted unit_gross cannot be greater that outstanding balance on invoice
            if($qform['unit_gross'] > $invoice_details['balance'] + $creditnote_details['unit_gross'])
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be submitted because it's gross amount is greater than the linked invoices's remaining balance."));
                $state_flag = false;
            }            
        }
        
        // If CR is created from an Expense
        elseif($qform['expense_id'])
        {
            $expense_details = $this->app->components->expense->getRecord($qform['expense_id']);

            // Submitted unit_gross cannot be greater than the expense unit_gross
            if($qform['unit_gross'] > $expense_details['unit_gross'])
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be submitted because it's gross amount is greater than the linked expenses's gross amount."));
                $state_flag = false;
            }
            
            // Submitted unit_gross cannot be greater that outstanding balance on expense
            if($qform['unit_gross'] > $expense_details['balance'] + $creditnote_details['unit_gross'])
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be submitted because it's gross amount is greater than the linked expenses's remaining balance."));
                $state_flag = false;
            }
        }
        // Standalone CR
        else
        {
            // If CR is created from a Client
            if($qform['client_id']){}
            
            // If CR is created from a Supplier
            if($qform['supplier_id']){}
        }
        
        return $state_flag;       
        
    }
    
    ############################################################# // not used, but set to disabled to disable in status
    #  Check if the creditnote status is allowed to be changed  # 
    ############################################################# done

     public function checkRecordAllowsManualStatusChange($creditnote_id) {
         
        // Prevent the manual changing of status - this is not a feature i want enabled until i have a use for it
        return false;

        $state_flag = true; 
        
        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because it has expired."));
            $state_flag = false;
        }

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);
        
        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because it is on a different Tax system."));
            $state_flag = false;       
        }
        
        // Is Pending
        if($creditnote_details['status'] == 'pending') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because the creditnote is pending."));
            $state_flag = false;        
        }
        
        // Is Unused
        if($creditnote_details['status'] == 'unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because the creditnote is unused."));
            $state_flag = false;        
        }
        
        // Is Partially Applied
        if($creditnote_details['status'] == 'partially_applied') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because the creditnote has payments and is partially applied."));
            $state_flag = false;       
        }

        // Is Fully Applied
        if($creditnote_details['status'] == 'fully_applied') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because the creditnote has payments and is fully applied."));
            $state_flag = false;        
        }

        // Is Expired Unused
        if($creditnote_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because the creditnote is unused and has expired."));
            $state_flag = false;        
        }

        // Is Cancelled
        if($creditnote_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because the creditnote has been cancelled."));
            $state_flag = false;        
        }

        // Is Deleted
        if($creditnote_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because the creditnote has been deleted."));
            $state_flag = false;       
        }

        /* Has Transactions (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if(countPayments('', null, null, null, null, null, null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote status cannot be changed because it has transactions against it."));
            $state_flag = false;        
        }*/


        return $state_flag;     

     }


    ############################################################### done
    #   Check to see if the creditnote can be cancelled           #
    ###############################################################

    public function checkRecordAllowsCancel($creditnote_id) {

        $state_flag = true;
        
        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it has expired."));
            $state_flag = false;
        }

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);
        
        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it is on a different Tax system."));
            $state_flag = false;       
        }

        // Is Pending
        if($creditnote_details['status'] == 'pending') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it is pending."));
            $state_flag = false;       
        }
        
        // Is Unused
        if($creditnote_details['status'] == 'unused') {                
        }

        // Is Partially Applied
        if($creditnote_details['status'] == 'partially_applied') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This creditnote cannot be cancelled because it is partially applied."));
            $state_flag = false;
        }

        // Is Fully Applied
        if($creditnote_details['status'] == 'fully_applied') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it is fully applied."));
            return false;        
        }

        // Is Expired Unused
        if($creditnote_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it is unused and has expired."));
            return false;        
        }
       
        // Is Cancelled
        if($creditnote_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it has already been cancelled."));
            $state_flag = false;       
        }

        // Is Deleted
        if($creditnote_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it has been deleted."));
            $state_flag = false;        
        }    

        /* Has Transactions (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if(countPayments('', null, null, null, null, null, null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it has transactions against it."));
            $state_flag = false;       
        }*/

        return $state_flag;

    }

    ############################################################### done
    #   Check to see if the creditnote can be deleted             #
    ###############################################################

    public function checkRecordAllowsDelete($creditnote_id) {

        $state_flag = true;
        
        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be deleted because it has expired."));
            $state_flag = false;
        }

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);
        
        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be deleted because it is on a different Tax system."));
            $state_flag = false;       
        }

        // Is Pending
        if($creditnote_details['status'] == 'pending') {              
        }
        
        // Is Unused
        if($creditnote_details['status'] == 'unused') {                
        }

        // Is Partially Applied
        if($creditnote_details['status'] == 'partially_applied') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This creditnote cannot be deleted because it is partially applied."));
            $state_flag = false;
        }

        // Is Fully Applied
        if($creditnote_details['status'] == 'fully_applied') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be deleted because it is fully applied."));
            return false;        
        }

        // Is Expired Unused
        if($creditnote_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be deleted because it is unused and has expired."));
            return false;        
        }
       
        // Is Cancelled
        if($creditnote_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be deleted because it has been cancelled."));
            $state_flag = false;       
        }

        // Is Deleted
        if($creditnote_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be deleted because it has already been deleted."));
            $state_flag = false;        
        }    

        /* Has Transactions (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if(countPayments('', null, null, null, null, null, null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be deleted because it has transactions against it."));
            $state_flag = false;       
        }*/

        return $state_flag;

    }

    ############################################################# done
    #  Check if the creditnote status is allowed to be Edited   #
    #############################################################

     public function checkRecordAllowsEdit($creditnote_id) {

        $state_flag = true;
        
        // Is Expired (Live Check)
        if($this->checkCreditnoteIsExpired($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be edited because it has expired."));
            $state_flag = false;
        }

        // Get the creditnote details
        $creditnote_details = $this->getRecord($creditnote_id);
        
        // Is on a different tax system
        if($creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be edited because it is on a different Tax system."));
            $state_flag = false;       
        }

        // Is Pending
        if($creditnote_details['status'] == 'pending') {                   
        }
        
        // Is Unused
        if($creditnote_details['status'] == 'unused') {                
        }

        // Is Partially Applied
        if($creditnote_details['status'] == 'partially_applied') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This creditnote cannot be edited because it is partially applied."));
            $state_flag = false;
        }

        // Is Fully Applied
        if($creditnote_details['status'] == 'fully_applied') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be edited because it is fully applied."));
            return false;        
        }

        // Is Expired Unused
        if($creditnote_details['status'] == 'expired_unused') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be edited because it is unused and has expired."));
            return false;        
        }
       
        // Is Cancelled
        if($creditnote_details['status'] == 'cancelled') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be edited because it has been cancelled."));
            $state_flag = false;       
        }

        // Is Deleted
        if($creditnote_details['status'] == 'deleted') {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be edited because it has been deleted."));
            $state_flag = false;        
        }    

        /* Has Transactions (Fallback - is currently not needed because of statuses, but it might be used for information reporting later)
        if(countPayments('', null, null, null, null, null, null, null, null, null, null, null, null, $creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot be cancelled because it has transactions against it."));
            $state_flag = false;       
        }*/      

        // The current record VAT code is enabled
        if(!$this->checkVatTaxCodeStatuses($creditnote_id)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This creditnote cannot be edited because one or more of it's items have a VAT Tax Code that is not enabled."));
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
    #   Recalculate Credit Note Totals  # 
    #####################################  done

    public function recalculateTotals($creditnote_id) {
        
        $items_subtotals        = $this->getItemsSubtotals($creditnote_id);               
        $payments_subtotal      = $this->app->components->report->sumPayments('date', null, null, null, 'valid', 'creditnote', null, null, null, null, null, null, null, $creditnote_id);
        
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

        // Has creditable amount with partially usage, set to partially applid (if not already)
        elseif($creditnote_details['unit_gross'] > 0 && $payments_subtotal > 0 && $payments_subtotal < $creditnote_details['unit_gross'] && $creditnote_details['status'] != 'partially_applied') {            
            $this->updateStatus($creditnote_id, 'partially_applied');
        }

        // Has creditable amount and the payment(s) match the credit note amount, set to fully applied (if not already)
        elseif($creditnote_details['unit_gross'] > 0 && $creditnote_details['unit_gross'] == $payments_subtotal && $creditnote_details['status'] != 'fully_applied') {            
            $this->updateStatus($creditnote_id, 'fully_applied');
        }        
                
        return;

    }


    ###########################################  // the code is still in status but I might not used this
    # Assign Credit Note to another employee  #
    ########################################### done

    public function assignToEmployee($creditnote_id, $target_employee_id) {

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
        $this->app->components->user->updateLastActive($creditnote_details['employee_id']);
        $this->app->components->user->updateLastActive($target_employee_id);
        $this->app->components->client->updateLastActive($creditnote_details['client_id']);        
        $this->updateLastActive($creditnote_id);

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
    
    #########################################
    #  Build additional_info JSON           #       
    #########################################  done

     public function buildAdditionalInfoJson($creditnote_id, $reason_for_cancelling = null) {

        // Make sure we merge current data from the database - decodes as an array even if empty
        $additional_info = json_decode($this->app->components->creditnote->getRecord($creditnote_id, 'additional_info'), true);
       
        // Add reason for cancelling
        if($reason_for_cancelling)
        {           
            $additional_info['reason_for_cancelling'] = $reason_for_cancelling;
        }
        
        // Return as a JSON object
        return json_encode($additional_info, JSON_FORCE_OBJECT);

    }
    

}
