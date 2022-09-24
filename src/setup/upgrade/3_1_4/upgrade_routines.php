<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Upgrade3_1_4 extends Setup {
    
    private $upgrade_step = null;
    private $setup_time = null;
    
    public function __construct() {
        
        // Call parent's constructor
        parent::__construct();
        
        // Some operations need to have a unified point in time
        $this->setup_time = time();
        
        // Get the upgrade step name
        $this->upgrade_step = str_replace('Upgrade', '', static::class);  // `__CLASS__` ? - `static::class` currently will not work for classes with name spaces
        
        // Perform the upgrade
        $this->preDatabase();
        $this->processDatabase();
        $this->postDatabase();
                        
    }    
    
    // scripts executed before SQL script (if required)
    public function preDatabase() {        
        
    }
    
    // Execute the upgrade SQL script
    public function processDatabase() {
        
        $this->executeSqlFileLines(SETUP_DIR.'upgrade/'.$this->upgrade_step.'/upgrade_database.sql');      
        
    }
    
    // Execute post database scipts and tidy up the data
    public function postDatabase() {
                
        // Config File
        $this->app->components->administrator->insertQwcrmConfigSetting('cronjob_system', 'pseudo');
        $this->app->components->administrator->insertQwcrmConfigSetting('cronjob_pseudo_interval', '15');
        
        // Set direction for invoice, expense, otherincome
        $this->updateColumnValues(PRFX.'payment_records', 'direction', '', 'credit', 'type', 'invoice');
        $this->updateColumnValues(PRFX.'payment_records', 'direction', '', 'debit', 'type', 'expense');
        $this->updateColumnValues(PRFX.'payment_records', 'direction', '', 'credit', 'type', 'otherincome');
        
        // Remove Refunds and convert to Creditnotes       
        $this->convertRefundsToCreditnotes();
        $this->removeRefundColumnsAndTables();        
        $this->copyColumnAToColumnB('payment_records', 'refund_id', 'creditnote_id');
        $this->updateColumnValues(PRFX.'payment_records', 'type', 'refund', 'creditnote');
        $this->updateColumnValues(PRFX.'payment_records', 'direction', '', 'credit', 'type', 'creditnote');
        $this->updateColumnValues(PRFX.'payment_records', 'creditnote_action', '', 'sales_refund', 'type', 'creditnote');
        
        // Fix invoice and Voucher statuses
        $this->updateColumnValues(PRFX.'invoice_records', 'status', 'refunded', 'paid');
        $this->updateColumnValues(PRFX.'voucher_records', 'status', 'refunded', 'paid');        
                
        // Update database version number
        $this->updateRecordValue(PRFX.'version', 'database_version', str_replace('_', '.', $this->upgrade_step));
        
        // Log message to setup log
        $record = _gettext("Database has now been upgraded to").' v'.str_replace('_', '.', $this->upgrade_step);
        $this->writeRecordToSetupLog('upgrade', $record);
        
    }    
    
    /* Version Specific Upgrade Methods */
    
    ###########################################
    #   Convert Refunds into Credit Notes     #  // just map records over including building the credit note with items. there is only full refunds.. see creditnote:new.php
    ###########################################
    
    public function convertRefundsToCreditnotes()
    {
        $local_error_flag = false;                     
        
        // Loop through all of the Voucher records
        $sql = "SELECT * FROM ".PRFX."refund_records";
        if(!$rs = $this->app->db->execute($sql))
        {            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `refund_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        }
        else
        {
            // Loop through all records
            while(!$rs->EOF)
            {                 
                /* Main Record */ 
                
                // Status - Refund as key / Creditnote as value
                $status = array(
                                'unpaid' => 'unused',
                                'partially_paid' => 'partially_used',
                                'paid' => 'fully_used',
                                'cancelled' => 'cancelled',
                                'deleted' => 'deleted'                    
                                );
                
                // Build Credit note main record migration SQL
                $sql = "INSERT INTO ".PRFX."creditnote_records SET
                    creditnote_id   =". $this->app->db->qStr($rs->fields['refund_id']).",
                    employee_id     =". $this->app->db->qStr($rs->fields['employee_id']).",
                    client_id       =". $this->app->db->qStr($rs->fields['client_id']).",
                    invoice_id      =". $this->app->db->qStr($rs->fields['invoice_id']).",    
                    supplier_id     =". $this->app->db->qStr(null).",
                    expense_id      =". $this->app->db->qStr(null).",                   
                    date            =". $this->app->db->qStr($rs->fields['date']).",
                    expiry_date     =". $this->app->db->qStr($rs->fields['date']).",
                    type            =". $this->app->db->qStr($rs->fields['sales_refund']).",                                           
                    tax_system      =". $this->app->db->qStr($rs->fields['tax_system']).",
                    unit_net        =". $this->app->db->qStr($rs->fields['unit_net']).",
                    unit_discount   =". $this->app->db->qStr($rs->fields[0.00]).",
                    sales_tax_rate  =". $this->app->db->qStr($this->app->components->invoice->getRecord($rs->fields['invoice_id'], 'sales_tax_rate')).",
                    unit_tax        =". $this->app->db->qStr($rs->fields['unit_tax']).",
                    unit_gross      =". $this->app->db->qStr($rs->fields['unit_gross']).",                    
                    balance         =". $this->app->db->qStr($rs->fields['balance']).",
                    status          =". $this->app->db->qStr($status[$rs->fields['status']]).",
                    opened_on       =". $this->app->db->qStr($rs->fields['opened_on']).",        
                    closed_on       =". $this->app->db->qStr($rs->fields['closed_on']).",
                    last_Active     =". $this->app->db->qStr($rs->fields['last_active']).",
                    is_closed       =". $this->app->db->qStr($rs->fields['closed_on'] ? 1 : 0).",
                    reference       =". $this->app->db->qStr(_gettext("Migrated from Refund").': '.$rs->fields['refund_id']).",
                    note            =". $this->app->db->qStr($rs->fields['note']).",
                    additional_info =". $this->app->db->qStr('{}');                
                
                // Run the Main Record SQL
                if(!$this->app->db->execute($sql))
                {                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to migrate the refund record").' '.$rs->fields['refund_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;                    
                } 
                
                /* Record Items */
                
                // Get invoice items with voucher records merged as standard items
                $creditnote_items = $this->invoiceGetItems($rs->fields['invoice_id'], true);

                // Rename 'invoice_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'invoice_item_id' not renaming it
                $creditnote_items = json_encode($creditnote_items);
                $creditnote_items = str_replace('invoice_item_id', 'creditnote_item_id', $creditnote_items);
                $creditnote_items = json_decode($creditnote_items, true);
                
                // Build Items/Rows insert SQL
                if($creditnote_items)
                {
                    $sql = "INSERT INTO `".PRFX."creditnote_items` (`creditnote_id`, `tax_system`, `description`, `unit_qty`, `unit_net`, `unit_discount`, `sales_tax_exempt`, `vat_tax_code`, `unit_tax_rate`, `unit_tax`, `unit_gross`, `subtotal_net`, `subtotal_tax`, `subtotal_gross`) VALUES ";

                    foreach($items as $item)
                    {
                        $sql .="(".
                                $this->app->db->qStr( $rs->fields['refund_id']          ).",".                    
                                $this->app->db->qStr( $item['tax_system']               ).",".                    
                                $this->app->db->qStr( $item['description']              ).",".                    
                                $this->app->db->qStr( $item['unit_qty']                 ).",".
                                $this->app->db->qStr( $item['unit_net']                 ).",".
                                $this->app->db->qStr( $item['unit_discount']            ).",".
                                $this->app->db->qStr( $item['sales_tax_exempt']         ).",".
                                $this->app->db->qStr( $item['vat_tax_code']             ).",".
                                $this->app->db->qStr( $item['unit_tax_rate']            ).",".
                                $this->app->db->qStr( $item['unit_tax']                 ).",".
                                $this->app->db->qStr( $item['unit_gross']               ).",".                    
                                $this->app->db->qStr( $item['subtotal_net']             ).",".
                                $this->app->db->qStr( $item['subtotal_tax']             ).",".
                                $this->app->db->qStr( $item['subtotal_gross']           )."),";
                    }

                    // Strips off last comma as this is a joined SQL statement
                    $sql = substr($sql , 0, -1);
                }
                
                // Run the Record items SQL (if there is anything to run)
                if($sql && !$this->app->db->execute($sql))
                {                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to migrate items for the converted refund record").' '.$rs->fields['refund_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;                    
                }                                
                
                /* Advance the loop to the next record */
                
                $rs->MoveNext();                

            }
        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag)
        {            
            // Log Message
            $record = _gettext("Failed to complete conversion of refunds to credit notes.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        }
        else
        {            
            // Log Message
            $record = _gettext("Successfully completed conversion of refunds to credit notes.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;            
        }          
    
    } 
    
    #########################################
    #   Get All invoice items               # // withVouchers adds the invoice vouchers in as items, useful for credit notes and print/email TPL
    #########################################  

    private function invoiceGetItems($invoice_id, $withVouchers = false) {
        
        $invoice_items = array();

        $sql = "SELECT * FROM ".PRFX."invoice_items WHERE invoice_id=".$this->app->db->qStr($invoice_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if(!empty($rs)) {

            $invoice_items = $rs->GetArray();

        }
        
        // Converts invoice voucher records into items and merges them into the invoice items - This is a bit of a workaround, this 
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
    
    #############################################
    # Remove Refund database columns and Tables #  // the rest are in the upgrade SQL
    #############################################
    
    public function removeRefundColumnsAndTables()
    {
        $sqls = array();
        $sqls[] = "DROP TABLE `".PRFX."refund_records`;";
        $sqls[] = "ALTER TABLE `".PRFX."payment_records` DROP `refund_id`;";
        
        foreach($sqls as $sql)
        {
            if(!$this->app->db->execute($sql))
            {       
                // Set the setup global error flag
                self::$setup_error_flag = true;
                    
                // Log Message                    
                $record = _gettext("Failed to run the SQL command");

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                // Log message to setup log
                $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);                   
            }
            else
            {
                // Log Message
                $record = _gettext("Successfully ran the SQL command");

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
                self::$executed_sql_results .= '<div>&nbsp;</div>';

                // Log message to setup log
                $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);

                return true; 
            }

        }
    }
        
}