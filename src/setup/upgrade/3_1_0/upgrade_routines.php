<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Upgrade3_1_0 extends Setup {
    
    private $upgrade_step = null;
    private $company_tax_system = null;
    private $default_vat_tax_code = null;
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
        $this->app->components->administrator->insertQwcrmConfigSetting('sef', '0');
        $this->app->components->administrator->insertQwcrmConfigSetting('error_handler_whoops', '1');
        $this->app->components->administrator->updateQwcrmConfigSetting('smarty_debugging_ctrl', 'NONE');
        
        // Tag all previous payments as type 'invoice'
        $this->updateColumnValues(PRFX.'payment_records', 'type', '*', 'invoice');
        
        // Change expense record types
        $this->updateColumnValues(PRFX.'expense_records', 'item_type', 'broadband', 'telco');
        $this->updateColumnValues(PRFX.'expense_records', 'item_type', 'landline', 'telco');
        $this->updateColumnValues(PRFX.'expense_records', 'item_type', 'mobile_phone', 'telco');
        $this->updateColumnValues(PRFX.'expense_records', 'item_type', 'advertising', 'marketing');
        $this->updateColumnValues(PRFX.'expense_records', 'item_type', 'customer_refund', 'other');
        $this->updateColumnValues(PRFX.'expense_records', 'item_type', 'tax', 'other');
        $this->updateColumnValues(PRFX.'expense_records', 'item_type', 'gift_certificate', 'voucher');

        // Change otherincome record types
        $this->updateColumnValues(PRFX.'otherincome_records', 'item_type', 'credit_note', 'other');
        $this->updateColumnValues(PRFX.'otherincome_records', 'item_type', 'proxy_invoice', 'other');
        $this->updateColumnValues(PRFX.'otherincome_records', 'item_type', 'returned_services', 'cancelled_services');
                
        // Change otherincome record payment_methods
        $this->updateColumnValues(PRFX.'otherincome_records', 'payment_method', 'google_checkout', 'other');
        $this->updateColumnValues(PRFX.'otherincome_records', 'payment_method', 'direct_deposit', 'bank_transfer');
        $this->updateColumnValues(PRFX.'otherincome_records', 'payment_method', 'credit', 'other');
        $this->updateColumnValues(PRFX.'otherincome_records', 'payment_method', 'credit_card', 'card');
        $this->updateColumnValues(PRFX.'otherincome_records', 'payment_method', 'voucher', 'other');
        $this->updateColumnValues(PRFX.'otherincome_records', 'payment_method', 'credit_note', 'other');
        
        // Change supplier record types
        $this->updateColumnValues(PRFX.'supplier_records', 'type', 'advertising', 'marketing');
        $this->updateColumnValues(PRFX.'supplier_records', 'type', 'affiliate_marketing', 'marketing');
                       
        // Reverse blocked account values because of the rename active --> blocked
        $this->updateColumnValues(PRFX.'voucher_records', 'blocked', '0', '9');
        $this->updateColumnValues(PRFX.'voucher_records', 'blocked', '1', '0');
        $this->updateColumnValues(PRFX.'voucher_records', 'blocked', '9', '0');        
        
        // Convert timestamps to MySQL DATE
        $this->columnTimestampToMysqlDate(PRFX.'company_record', 'year_start', 'company_name');
        $this->columnTimestampToMysqlDate(PRFX.'company_record', 'year_end', 'company_name');
        $this->columnTimestampToMysqlDate(PRFX.'expense_records', 'date', 'expense_id');
        $this->columnTimestampToMysqlDate(PRFX.'voucher_records', 'expiry_date', 'voucher_id');
        $this->columnTimestampToMysqlDate(PRFX.'invoice_records', 'date', 'invoice_id');
        $this->columnTimestampToMysqlDate(PRFX.'invoice_records', 'due_date', 'invoice_id');
        $this->columnTimestampToMysqlDate(PRFX.'payment_records', 'date', 'payment_id');        
        $this->columnTimestampToMysqlDate(PRFX.'otherincome_records', 'date', 'otherincome_id');
                
        // Convert timestamps to MySQL DATETIME
        $this->columnTimestampToMysqlDatetime(PRFX.'user_records', 'last_active', 'user_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'user_records', 'register_date', 'user_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'user_records', 'last_reset_time', 'user_id');     
        $this->columnTimestampToMysqlDatetime(PRFX.'client_notes', 'date', 'client_note_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'client_records', 'opened_on', 'client_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'client_records', 'last_active', 'client_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'workorder_history', 'date', 'history_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'workorder_notes', 'date', 'workorder_note_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'workorder_records', 'opened_on', 'workorder_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'workorder_records', 'closed_on', 'workorder_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'workorder_records', 'last_active', 'workorder_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'schedule_records', 'start_time', 'schedule_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'schedule_records', 'end_time', 'schedule_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'invoice_records', 'opened_on', 'invoice_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'invoice_records', 'closed_on', 'invoice_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'invoice_records', 'last_active', 'invoice_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'voucher_records', 'opened_on', 'voucher_id');
        $this->columnTimestampToMysqlDatetime(PRFX.'voucher_records', 'redeemed_on', 'voucher_id');
        
        // Populate the last_active columns with record date for the following becasue they have only just received last_Active
        $this->copyColumnAToColumnB('expense_records', 'date', 'last_active');
        $this->copyColumnAToColumnB('otherincome_records', 'date', 'last_active');
        $this->copyColumnAToColumnB('payment_records', 'date', 'last_active');  
        
        // Update Invoice Tax Types
        $this->updateColumnValues(PRFX.'company_record', 'tax_system', 'none', 'no_tax');
        $this->updateColumnValues(PRFX.'company_record', 'tax_system', 'vat', 'vat_standard');
        $this->updateColumnValues(PRFX.'company_record', 'tax_system', 'sales', 'sales_tax_cash');        
        $this->updateColumnValues(PRFX.'invoice_records', 'tax_system', 'none', 'no_tax');
        $this->updateColumnValues(PRFX.'invoice_records', 'tax_system', 'vat', 'vat_standard');
        $this->updateColumnValues(PRFX.'invoice_records', 'tax_system', 'sales', 'sales_tax_cash');
        
        // Set the Company Tax system and VAT tax code now the Company Record has been updated
        $this->company_tax_system = $this->app->components->company->getRecord('tax_system');
        $this->default_vat_tax_code = $this->app->components->company->getDefaultVatTaxCode($this->company_tax_system); // This is an educated guess
                
        // Update Invoice Items        
        $this->updateColumnValues(PRFX.'invoice_labour', 'tax_system', '*', $this->company_tax_system);
        $this->updateColumnValues(PRFX.'invoice_parts', 'tax_system', '*', $this->company_tax_system);         
        $this->updateColumnValues(PRFX.'invoice_labour', 'vat_tax_code', '*', $this->default_vat_tax_code);
        $this->updateColumnValues(PRFX.'invoice_parts', 'vat_tax_code', '*', $this->default_vat_tax_code);
        
        // Parse Labour and Parts records and update their totals to reflect the new VAT system
        $this->invoiceCorrectLabourTotals();
        $this->invoiceCorrectPartsTotals();        
        
        // Parse Voucher records and correct records        
        $this->voucherCorrectRecords();
        $this->updateColumnValues(PRFX.'voucher_records', 'type', '*', 'MPV');
        $this->updateColumnValues(PRFX.'voucher_records', 'tax_system', '*', $this->company_tax_system);
        $this->updateColumnValues(PRFX.'voucher_records', 'vat_tax_code', '*', $this->app->components->voucher->getVatTaxCode('MPV', $this->company_tax_system)); 
        
        // Sales Tax Rate should be zero except for all invoices of 'sales_tax_cash' type
        $this->updateRecordValue(PRFX.'invoice_records', 'sales_tax_rate', 0.00, 'tax_system', 'sales_tax_cash', '!');
        
        // Populate newley created 'tax_system' and 'vat_tax_code' columns
        $this->updateColumnValues(PRFX.'expense_records', 'tax_system', '*', $this->company_tax_system);
        $this->updateColumnValues(PRFX.'expense_records', 'vat_tax_code', '*', $this->default_vat_tax_code);  
        $this->updateColumnValues(PRFX.'otherincome_records', 'tax_system', '*', $this->company_tax_system);
        $this->updateColumnValues(PRFX.'otherincome_records', 'vat_tax_code', '*', $this->default_vat_tax_code);
        
        // Populate newly created status columns
        $this->updateColumnValues(PRFX.'expense_records', 'status', '*', 'valid');
        $this->updateColumnValues(PRFX.'otherincome_records', 'status', '*', 'valid');        
        $this->updateColumnValues(PRFX.'supplier_records', 'status', '*', 'valid');
        $this->updateColumnValues(PRFX.'payment_records', 'status', '*', 'valid');
        
        // Correct currently upgraded invoice payment records  
        $this->updateColumnValues(PRFX.'payment_records', 'method', '6', 'bank_transfer'); // This might be a MyITCRM correction
        $this->updateColumnValues(PRFX.'payment_records', 'method', 'direct_deposit', 'bank_transfer');
        $this->updateColumnValues(PRFX.'payment_records', 'tax_system', '*', $this->company_tax_system );
        $this->updateColumnValues(PRFX.'payment_records', 'type', '*', 'invoice');
        
        // Parse Payment notes and extract information into 'additional_info' column for invoices
        $this->paymentsParseImportAdditionalInfo();
        
        // Convert expense, refund and otherincome transactions into separate record and payment
        $this->paymentsCreateExpenseRecordsPayments();        
        $this->paymentsCreateOtherincomeRecordsPayments(); 
                
        // Update Records to allow for openen_on, closed_on and last_active        
        $this->copyColumnAToColumnB('expense_records', 'date', 'opened_on');
        $this->copyColumnAToColumnB('expense_records', 'date', 'closed_on');
        $this->copyColumnAToColumnB('expense_records', 'date', 'last_active');
        $this->copyColumnAToColumnB('otherincome_records', 'date', 'opened_on');
        $this->copyColumnAToColumnB('otherincome_records', 'date', 'closed_on');
        $this->copyColumnAToColumnB('otherincome_records', 'date', 'last_active');
        $this->updateColumnValues(PRFX.'supplier_records', 'opened_on', '*', $this->app->system->general->mysqlDatetime($this->setup_time));
        
        // correct users with 00:00:00 registered dates
        $this->updateColumnValues(PRFX.'user_records', 'register_date', '0000-00-00 00:00:00', $this->app->system->general->mysqlDatetime($this->setup_time));
        
        // Correct logo filepath
        $this->updateRecordValue(PRFX.'company_record', 'logo', str_replace('media/', '', $this->app->components->company->getRecord('logo')));
        
        // Update database version number
        $this->updateRecordValue(PRFX.'version', 'database_version', str_replace('_', '.', $this->upgrade_step));
        
        // Log message to setup log
        $record = _gettext("Database has now been upgraded to").' v'.str_replace('_', '.', $this->upgrade_step);
        $this->writeRecordToSetupLog('upgrade', $record);
        
    }    
    
    /* Version Specific Upgrade Methods */
    
    ##############################################################
    #   Convert a timestamp `column` to a MySQL DATE `column`    #
    ##############################################################
    
     public function columnTimestampToMysqlDate($table, $column_timestamp, $column_primary_key) {
        
        $mysql_date = null;
        $temp_prfx = 'temp_';
        $local_error_flag = false;
        $column_comment = null;
        
        // Get Column Comment if present
        if ($column_comment = $this->getColumnComment($table, $column_timestamp)) {
            $column_comment = "COMMENT '$column_comment' ";
        }
        
        // Create a temp column for the new DATE values
        $sql = "ALTER TABLE `".$table."` ADD `".$temp_prfx.$column_timestamp."` DATE NOT NULL AFTER `".$column_timestamp."`";        
        if(!$this->app->db->execute($sql)) { 
                        
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to create a temporary column called").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);            
           
            // The process has failed so stop any further proccesing
            goto process_end;
            
        }              
        
        // Loop through all of the timestamps, calculate the correct Date and enter it into the temporary timestamp column
        $sql = "SELECT * FROM ".$table;
        if(!$rs = $this->app->db->execute($sql)) {
                        
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select the records from the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
            
        } else {

            // Loop through all records and 
            while(!$rs->EOF) { 

                // Convert the timestamp into the correct MySQL DATE
                $mysql_date = $this->timestampToMysqlDateOffsetAware($rs->fields[$column_timestamp]);

                // Update the temporary column record
                $sql = "UPDATE `".$table."` SET `".$temp_prfx.$column_timestamp."` = '".$mysql_date."' WHERE `".$table."`.`".$column_primary_key."` = '".$rs->fields[$column_primary_key]."';";
                if(!$this->app->db->execute($sql)) { 
                                        
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message
                    $record = _gettext("Failed to update the record").' `'.$column_primary_key.'` '._gettext("in the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>'; 
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;                    
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }        
                
            // Remove the orginal timestamp column
            $sql = "ALTER TABLE `".$table."` DROP `".$column_timestamp."`";
            if(!$this->app->db->execute($sql)) { 
                                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to remove the original column").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;
                
            }

            // Rename temporary column (temp_xxx) to the original column name
            $sql = "ALTER TABLE `".$table."` CHANGE `temp_".$column_timestamp."` `".$column_timestamp."` DATE NOT NULL ".$column_comment;
            if(!$this->app->db->execute($sql)) { 
                                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to rename the temporary column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("to").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;
                
            }            
        
        }
        
        process_end:
        
        // The conversion of the column, success and failed messages. 
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to covert the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATE`").' '._gettext("in the table").' `'.$table.'`. '._gettext("Check the previous error for the cause.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully converted the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATE`").' '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;
            
        }        
    
    }
    
    ##############################################################
    #  Convert a timestamp `column` to a MySQL DATETIME `column` #
    ##############################################################
    
    public function columnTimestampToMysqlDatetime($table, $column_timestamp, $column_primary_key) {
        
        $mysql_datetime = null;
        $temp_prfx = 'temp_';
        $local_error_flag = false;
        $column_comment = null;
        
        // Get Column Comment if present
        if ($column_comment = $this->getColumnComment($table, $column_timestamp)) {
            $column_comment = "COMMENT '$column_comment' ";
        }
        
        // Create a new temp column for the new DATETIME values
        $sql = "ALTER TABLE `".$table."` ADD `".$temp_prfx.$column_timestamp."` DATETIME NOT NULL AFTER `".$column_timestamp."`";        
        if(!$this->app->db->execute($sql)) { 
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to create a temporary column called").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
           
            // The process has failed so stop any further proccesing
            goto process_end;
            
        }              
        
        // Loop through all of the timestamps, calculate the correct Datetime and enter them into the temporary column
        $sql = "SELECT * FROM ".$table;
        if(!$rs = $this->app->db->execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records and 
            while(!$rs->EOF) { 

                // If there is no timestamp set an empty MySQL DATE
                if(!$rs->fields[$column_timestamp]) {
                    $mysql_datetime = '0000-00-00 00:00:00';
                } else {                    
                    // Convert the timestamp into the correct MySQL DATETIME
                    $mysql_datetime = $this->app->system->general->timestampMysqlDatetime($rs->fields[$column_timestamp]);
                }

                // Update the temporary column record
                $sql = "UPDATE `".$table."` SET `".$temp_prfx.$column_timestamp."` = '".$mysql_datetime."' WHERE `".$table."`.`".$column_primary_key."` = '".$rs->fields[$column_primary_key]."';";
                if(!$this->app->db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Log Message
                    $record = _gettext("Failed to update the record").' `'.$column_primary_key.'` '._gettext("in the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }        
                
            // Remove the orginal timestamp column
            $sql = "ALTER TABLE `".$table."` DROP `".$column_timestamp."`";
            if(!$this->app->db->execute($sql)) {
                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to remove the original column").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;   
                
            }

            // Rename temporary column (temp_xxx) to the original column name
            $sql = "ALTER TABLE `".$table."` CHANGE `temp_".$column_timestamp."` `".$column_timestamp."` DATETIME NOT NULL ".$column_comment;
            if(!$this->app->db->execute($sql)) {
                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to rename the temporary column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("to").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;
                
            }

        }
        
        process_end:
        
        // The conversion of the column, success and failed messages. 
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to covert the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATETIME`").' '._gettext("in the table").' `'.$table.'`. '._gettext("Check the previous error for the cause.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully converted the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATETIME`").' '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    ##################################################################################################
    #  Convert a timestamp a MySQL DATE whilst compensating for (GMT/BST) || (Winter/Summer) offsets #
    ##################################################################################################
    
    public function timestampToMysqlDateOffsetAware($timestamp) {
        
        // If there is no timestamp return an empty MySQL DATE
        if(!$timestamp) {
            return '0000-00-00';
        }
        
        // If the timestamp already is a proper date in the format xxxx/xx/xx 00:00 then timestamp is correct 'as is' (there is no offset)
        if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} 00:00:00$/', date('Y-m-d H:i:s', $timestamp))) {              
            return $this->app->system->general->timestampMysqlDate($timestamp);           
        }
        
        // Calculate backward difference
        $backward = $timestamp - 86400;         // This removes 24 hours from the timestamp - This ensures that a difference exists for calculations
        $backward = date('Y-m-d', $backward);   // Convert the timestamp into a string which removes the hours and minutes
        $backward = strtotime($backward);       // Convert back to a timestamp, but now it is a perfect date xx/xx/xx 00:00 (with no hours or minutes i.e. 00:00)      
        $backward = $backward - $timestamp;     // This calculates the difference in seconds between the backwards date and the timestamp date
        $backward = $backward % 86400;          // This removes all full days (86400 seconds) from the difference and leaves the remainder which is the offset
        
        // Calculate the forward difference
        $forward = $timestamp + 86400;          // This adds 24 hours to the timestamp - This ensures that a difference exists for calculations
        $forward = date('Y-m-d', $forward);     // Convert the timestamp into a string which removes the hours and minutes
        $forward = strtotime($forward);         // Convert back to a timestamp, but now it is a perfect date xx/xx/xx 00:00 (with no hours or minutes i.e. 00:00)       
        $forward = $forward - $timestamp;       // This calculates the difference in seconds between the backwards date and the timestamp date
        $forward = $forward % 86400;            // This removes all full days (86400 seconds) from the difference and leaves the remainder which is the offset
        
        // The correct direction will have the smallest difference and therefore will have the correct offset (ignores -ve & +ve)
        if(abs($backward) < abs($forward)) {            
            $offset = $backward;
        } elseif(abs($backward) > abs($forward))  {            
            $offset = $forward;          
        } else {            
            // If there is no difference, the date is already correct (I have already checked for this with preg_match() at the top) 
            $offset = 0;
        }

        // Apply the selected time offset too correct the timestamp
        $corrected_timestamp = $timestamp + $offset;
     
        // Return the correct date in MySQL DATE format
        return $this->app->system->general->timestampMysqlDate($corrected_timestamp);        
        
    } 
    
    #################################################################  // This is after conversion to mysql DATE
    #  Parse Voucher records and populate with appropriate status   #  // call this voucher_correct_records / correct_voucher_records  
    #################################################################  // add in the other fixes to the vouceher records here and deleete the others
                                                                        

    function voucherCorrectRecords() {
        
        $local_error_flag = false;                     
        
        // Loop through all of the Voucher records
        $sql = "SELECT * FROM ".PRFX."voucher_records";
        if(!$rs = $this->app->db->execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `voucher_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each Voucher's status
            while(!$rs->EOF) { 
                
                /* Get variables */
                
                $redeemed_client_id = $rs->fields['redeemed_invoice_id'] ? get_invoice_details($rs->fields['redeemed_invoice_id'], 'client_id') : '';
                $opened_on = mysql_datetime($this->setup_time);
                
                /* Time and Status */
                
                // Set qualifying Vouchers to redeemed status
                if($rs->fields['redeemed_on'] != '0000-00-00 00:00:00') {
                    
                    $status = 'redeemed';
                    $closed_on = $rs->fields['redeemed_on'];
                    $last_active = $rs->fields['redeemed_on'];
                    $blocked = 1;
                
                // Set qualifying Vouchers to expired status
                } elseif (time() > strtotime($rs->fields['expiry_date'].' 23:59:59')) {
                                        
                    $status = 'expired'; 
                    $closed_on = $rs->fields['expiry_date'].' 23:59:59';
                    $last_active = $rs->fields['expiry_date'].' 23:59:59';
                    $blocked = 1;
                
                // If not redeemed or expired it must be unused
                } else {
                    
                    $status = 'unused';  
                    $closed_on = '0000-00-00 00:00:00'; 
                    $last_active = '0000-00-00 00:00:00'; 
                    $blocked = 0;
                    
                }
                
                /* Build SQL */ 
                
                // 'invoice_id = 0' is because imported vouchers do not have an invoice and htis is required
                
                $sql = "UPDATE `".PRFX."voucher_records` SET
                        `invoice_id` = '0', 
                        `redeemed_client_id` = ".$this->app->db->qStr($redeemed_client_id).",                        
                        `status` = ".$this->app->db->qStr($status).",
                        `opened_on` = ".$this->app->db->qStr($opened_on).",
                        `closed_on` = ".$this->app->db->qStr($closed_on).",
                        `last_active` = ".$this->app->db->qStr($last_active).",
                        `blocked` = ".$blocked."
                        WHERE `voucher_id` = ".$rs->fields['voucher_id'];
                
                
                // Run the SQL
                if(!$this->app->db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to correct the the Voucher record").' '.$rs->fields['voucher_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the loop to the next record            
                $rs->MoveNext();           

            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete correcting all Voucher records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed assigning `status` to all Voucher records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    #################################################################
    #  Recalculate Labour totals because of the new VAT system      #
    #################################################################

    function invoiceCorrectLabourTotals() {
        
        $local_error_flag = false;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."invoice_labour";                

        if(!$rs = $this->app->db->execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `invoice_labour`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each labour items's status
            while(!$rs->EOF) { 
                
                // Get the invoice details or use manual options here (compensates for records with missing invoices)
                if(!$invoice_details = $this->app->components->invoice->getRecord($rs->fields['invoice_id'])) {
                    $invoice_details['tax_system'] = 'no_tax';
                } 
                
                // Set sales tax exempt and all off. this feature was not available in earlier versions so nothing is exempt
                $sales_tax_exempt = 0;

                // Set the correct VAT code
                $vat_tax_code = $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']); 

                // Calculate the correct tax rate based on tax system (and exemption status)
                if($invoice_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $invoice_details['sales_tax_rate']; }
                elseif($invoice_details['tax_system'] == 'vat_standard') { $unit_tax_rate = get_vat_rate($vat_tax_code); }
                else { $unit_tax_rate = 0.00; }

                $item_totals = $this->app->components->invoice->calculateItemsSubtotals($invoice_details['tax_system'], $rs->fields['unit_qty'], $rs->fields['unit_net'], $unit_tax_rate);

                $sql = "UPDATE `".PRFX."invoice_labour` SET
                    `invoice_id`        = ".$rs->fields['invoice_id'].",
                    `tax_system`        = ".$this->app->db->qStr($invoice_details['tax_system']).",
                    `description`       = ".$this->app->db->qStr($rs->fields['description']).",
                    `unit_qty`          = ".$rs->fields['unit_qty'].",
                    `unit_net`          = ".$rs->fields['unit_net'].",
                    `sales_tax_exempt`  = ".$sales_tax_exempt.",
                    `vat_tax_code`      = ".$this->app->db->qStr($vat_tax_code).",                        
                    `unit_tax_rate`     = ".$unit_tax_rate.",                       
                    `unit_tax`          = ".$item_totals['unit_tax'].",
                    `unit_gross`        = ".$item_totals['unit_gross'].",                        
                    `sub_total_net`     = ".$item_totals['sub_total_net'].",
                    `sub_total_tax`     = ".$item_totals['sub_total_tax'].",
                    `sub_total_gross`   = ".$item_totals['sub_total_gross']."
                    WHERE `invoice_labour_id`  = ".$rs->fields['invoice_labour_id'];                

                // Run the SQL
                if(!$this->app->db->execute($sql)) {

                    // Set the setup global error flag
                    self::$setup_error_flag = true;

                    // Set the local error flag
                    $local_error_flag = true;

                    // Log Message                    
                    $record = _gettext("Failed to update the `totals` for the labour record").' '.$rs->fields['invoice_labour_id'];

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);

                    // The process has failed so stop any further proccesing
                    goto process_end;

                } 

                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete updating `totals` for all labour records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed updating `totals` for all labour records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    #################################################################
    #  Recalculate Parts totals because of the new VAT system       #
    #################################################################

    function invoiceCorrectPartsTotals() {
        
        $local_error_flag = false;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."invoice_parts";                

        if(!$rs = $this->app->db->execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `invoice_parts`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each parts items's status
            while(!$rs->EOF) { 
                                
                // Get the invoice details or use manual options here (compensates for records with missing invoices)
                if(!$invoice_details = $this->app->components->invoice->getRecord($rs->fields['invoice_id'])) {
                    $invoice_details['tax_system'] = 'no_tax';
                }                
                
                // Set sales tax exempt and all off. this feature was not available in earlier versions so nothing is exempt
                $sales_tax_exempt = 0;

                // Set the correct VAT code
                $vat_tax_code = $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']); 

                // Calculate the correct tax rate based on tax system (and exemption status)
                if($invoice_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $invoice_details['sales_tax_rate']; }
                elseif($invoice_details['tax_system'] == 'vat_standard') { $unit_tax_rate = $this->app->components->company->getVatRate($vat_tax_code); }
                else { $unit_tax_rate = 0.00; }

                $item_totals = $this->app->components->invoice->calculateItemsSubtotals($invoice_details['tax_system'], $rs->fields['unit_qty'], $rs->fields['unit_net'], $unit_tax_rate);

                $sql = "UPDATE `".PRFX."invoice_parts` SET
                    `invoice_id`        = ".$rs->fields['invoice_id'].",
                    `tax_system`        = ".$this->app->db->qStr($invoice_details['tax_system']).",
                    `description`       = ".$this->app->db->qStr($rs->fields['description']).",
                    `unit_qty`          = ".$rs->fields['unit_qty'].",
                    `unit_net`          = ".$rs->fields['unit_net'].",
                    `sales_tax_exempt`  = ".$sales_tax_exempt.",
                    `vat_tax_code`      = ".$this->app->db->qStr($vat_tax_code).",                        
                    `unit_tax_rate`     = ".$unit_tax_rate.",                       
                    `unit_tax`          = ".$item_totals['unit_tax'].",
                    `unit_gross`        = ".$item_totals['unit_gross'].",                        
                    `sub_total_net`     = ".$item_totals['sub_total_net'].",
                    `sub_total_tax`     = ".$item_totals['sub_total_tax'].",
                    `sub_total_gross`   = ".$item_totals['sub_total_gross']."
                    WHERE `invoice_parts_id` = ".$rs->fields['invoice_parts_id'];               
                
                // Run the SQL
                if(!$this->app->db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to update the `totals` for the parts record").' '.$rs->fields['invoice_parts_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete updating `totals` for all parts records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed updating `totals` for all parts records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }    
    
    #################################################################
    #  Parse Payment records and populate additinal information     #
    #################################################################

    function paymentsParseImportAdditionalInfo() {
        
        $local_error_flag = false;                     
        
        // Loop through all of the payment records
        $sql = "SELECT * FROM ".PRFX."payment_records";
        if(!$rs = $this->app->db->execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `payment_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each Voucher's status
            while(!$rs->EOF) { 
                
                // holding variables
                $bank_transfer_reference = '';
                $card_type = '';
                $card_type_key = '';
                $name_on_card = '';                
                $cheque_number = '';
                $direct_debit_reference = '';
                $paypal_transaction_id = '';
                $voucher_id = '';
                                
                /* Parse out the information from payment notes (3.0.0 and MyITCRM) */
                
                // Bank Transfer
                if(preg_match('/Deposit ID#: (.*)([ ]{2}|,)/U', $rs->fields['note'], $matches)) {
                    $bank_transfer_reference =  $matches[1];
                }              
                if(preg_match('/Deposit Reference:  (.*),/U', $rs->fields['note'], $matches)) {
                    $bank_transfer_reference =  $matches[1];
                }
                
                // Cards
                if(preg_match('/Card Type: (.*), Name on Card: (.*),/U', $rs->fields['note'], $matches)) {
                    $card_type =  $matches[1];
                    $name_on_card =  $matches[2];
                }
                if($card_type == 'Visa') {$card_type_key = 'visa';}
                if($card_type == 'MasterCard') {$card_type_key = 'mastercard';}
                if($card_type == 'American Express') {$card_type_key = 'american_express';}
                if($card_type == 'Debit Card') {$card_type_key = 'debit_card';}
                if($card_type == 'Other') {$card_type_key = 'other';}                  
                
                // Check / Cheque
                if(preg_match('/Check Number: ([0-9]+)([ ]{2}|,)/U', $rs->fields['note'], $matches)) {
                    $cheque_number =  $matches[1];
                }
                if(preg_match('/Cheque Number: ([0-9]+)([ ]{2}|,)/U', $rs->fields['note'], $matches)) {
                    $cheque_number =  $matches[1];
                }
                
                // Paypal
                if(preg_match('/PayPal Transaction ID (.*),/U', $rs->fields['note'], $matches)) {
                    $paypal_transaction_id =  $matches[1];
                }
                                
                // Voucher / Giftcert
                if(preg_match('/Gift Certificate Code: (.*),/U', $rs->fields['note'], $matches)) {
                    $voucher_code =  $matches[1];
                    $voucher_id = $this->app->components->voucher->getIdByVoucherCode($voucher_code);                    
                }                          
                                
                // Build 'additional_info' array
                $additional_info = array();
                $additional_info['bank_transfer_reference'] = $bank_transfer_reference;
                $additional_info['card_type_key'] = $card_type_key;
                $additional_info['name_on_card'] = $name_on_card;
                $additional_info['cheque_number'] = $cheque_number;
                $additional_info['direct_debit_reference'] = $direct_debit_reference;
                $additional_info['paypal_transaction_id'] = $paypal_transaction_id;
                
                // Build SQL                
                $sql = "UPDATE `".PRFX."payment_records` SET
                        `voucher_id` = ".$this->app->db->qStr($voucher_id).",                        
                        `additional_info` = ". $this->app->db->qStr(json_encode($additional_info))."
                        WHERE `payment_id` = ".$rs->fields['payment_id'];                
                
                // Run the SQL
                if(!$this->app->db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to import the `additional info` for the Payment record").' '.$rs->fields['payment_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to import `additional info` for all Payment records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed importing `additional info` for all Payment records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }   
    
    #######################################################################
    #  Convert Expenses into a separate item and make a related payment   #
    #######################################################################

    function paymentsCreateExpenseRecordsPayments() {
        
        $local_error_flag = false;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."expense_records";                

        if(!$rs = $this->app->db->execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `expense_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records
            while(!$rs->EOF) { 
                
                $sql = "INSERT INTO ".PRFX."payment_records SET            
                    employee_id     = ".$this->app->db->qStr($rs->fields['employee_id']                   ).",
                    client_id       = '',
                    workorder_id    = '',
                    invoice_id      = '',
                    voucher_id      = '',
                    refund_id       = '',
                    expense_id      = ".$this->app->db->qStr($rs->fields['expense_id']                    ).",
                    otherincome_id  = '',
                    date            = ".$this->app->db->qStr($rs->fields['date']                          ).",
                    tax_system      = ".$this->app->db->qStr($rs->fields['tax_system']                    ).",
                    type            = 'expense',
                    method          = ".$this->app->db->qStr($rs->fields['payment_method']                ).",
                    status          = 'valid',
                    amount          = ".$this->app->db->qStr($rs->fields['unit_gross']                    ).",
                    last_active     = ".$this->app->db->qStr($rs->fields['date']                          ).",
                    additional_info = ".$this->app->db->qStr($this->app->components->payment->buildAdditionalInfoJson()                 ).",
                    note            = ".$this->app->db->qStr('<p>'._gettext("Created from an expense record during an upgrade of QWcrm.").'</p>');               
                
                // Run the SQL
                if(!$this->app->db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to insert the corresponding payment record for expense reocrd").': '.$rs->fields['expense_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        // Delete column
        if(!$local_error_flag) {
            $sql = "ALTER TABLE `".PRFX."expense_records` DROP `payment_method`;";
            
            // Run the SQL
            if(!$this->app->db->execute($sql)) {

                // Set the setup global error flag
                self::$setup_error_flag = true;

                // Set the local error flag
                $local_error_flag = true;

                // Log Message                    
                $record = _gettext("Failed to delete the `expense_record` table `payment_method` column.");

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                // Log message to setup log
                $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);

                // The process has failed so stop any further proccesing
                goto process_end;

            }
                
        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete converting expense records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed converting expense records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }    
    
    ############################################################################
    #  Convert Otherincomes into a separate item and make a related payment    #
    ############################################################################

    function paymentsCreateOtherincomeRecordsPayments() {
        
        $local_error_flag = false;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."otherincome_records";                

        if(!$rs = $this->app->db->execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `otherincome_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records
            while(!$rs->EOF) { 
                
                $sql = "INSERT INTO ".PRFX."payment_records SET            
                    employee_id     = ".$this->app->db->qStr($rs->fields['employee_id']                   ).",
                    client_id       = '',
                    workorder_id    = '',
                    invoice_id      = '',
                    voucher_id      = '',
                    refund_id       = '',
                    expense_id      = '',
                    otherincome_id  = ".$this->app->db->qStr($rs->fields['otherincome_id']                ).",
                    date            = ".$this->app->db->qStr($rs->fields['date']                          ).",
                    tax_system      = ".$this->app->db->qStr($rs->fields['tax_system']                    ).",
                    type            = 'otherincome',
                    method          = ".$this->app->db->qStr($rs->fields['payment_method']                ).",
                    status          = 'valid',
                    amount          = ".$this->app->db->qStr($rs->fields['unit_gross']                    ).",
                    last_active     = ".$this->app->db->qStr($rs->fields['date']                          ).",
                    additional_info = ".$this->app->db->qStr($this->app->components->payment->buildAdditionalInfoJson()                 ).",
                    note            = ".$this->app->db->qStr('<p>'._gettext("Created from a otherincome record during an upgrade of QWcrm.").'</p>');               
                
                // Run the SQL
                if(!$this->app->db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to insert the corresponding payment record for otherincome record").': '.$rs->fields['otherincome_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        // Delete column
        if(!$local_error_flag) {
            $sql = "ALTER TABLE `".PRFX."otherincome_records` DROP `payment_method`;";
            
            // Run the SQL
            if(!$this->app->db->execute($sql)) {

                // Set the setup global error flag
                self::$setup_error_flag = true;

                // Set the local error flag
                $local_error_flag = true;

                // Log Message                    
                $record = _gettext("Failed to delete the `otherincome_record` table `payment_method` column.");

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                // Log message to setup log
                $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);

                // The process has failed so stop any further proccesing
                goto process_end;

            }
                
        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete converting otherincome records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed converting otherincome records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }    
    
}