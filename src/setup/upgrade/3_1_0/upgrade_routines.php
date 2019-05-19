<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Upgrade3_1_0 extends QSetup {
    
    private $upgrade_step = null;
    private $company_tax_system = null;
    private $default_vat_tax_code = null;
    
    public function __construct(&$VAR) {
        
        // Call parent's constructor
        parent::__construct($VAR);
        
        // Get the upgrade step name
        $this->upgrade_step = str_replace('Upgrade', '', static::class);  // `__CLASS__` ? - `static::class` currently will not work for classes with name spaces
        
        // Perform the upgrade
        $this->pre_database();
        $this->process_database();
        $this->post_database();
                        
    }    
    
    // scripts executed before SQL script (if required)
    public function pre_database() {        
        
    }
    
    // Execute the upgrade SQL script
    public function process_database() {
        
        $this->execute_sql_file_lines(SETUP_DIR.'upgrade/'.$this->upgrade_step.'/upgrade_database.sql');      
        
    }
    
    // Execute post database scipts and tidy up the data
    public function post_database() {
        
        // Config File
        insert_qwcrm_config_setting('sef', '0');
        insert_qwcrm_config_setting('error_handler_whoops', '1');
        update_qwcrm_config_setting('smarty_debugging_ctrl', 'NONE');
        
        // Tag all previous payments as type 'invoice'
        $this->update_column_values(PRFX.'payment_records', 'type', '*', 'invoice');
        
        // Change expense record types
        $this->update_column_values(PRFX.'expense_records', 'item_type', 'broadband', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'item_type', 'landline', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'item_type', 'mobile_phone', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'item_type', 'advertising', 'marketing');
        $this->update_column_values(PRFX.'expense_records', 'item_type', 'customer_refund', 'other');
        $this->update_column_values(PRFX.'expense_records', 'item_type', 'tax', 'other');
        $this->update_column_values(PRFX.'expense_records', 'item_type', 'gift_certificate', 'voucher');

        // Change otherincome record types
        $this->update_column_values(PRFX.'otherincome_records', 'item_type', 'credit_note', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'item_type', 'proxy_invoice', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'item_type', 'returned_services', 'cancelled_services');
                
        // Change otherincome record payment_methods
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'google_checkout', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'direct_deposit', 'bank_transfer');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'credit', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'credit_card', 'card');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'voucher', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'credit_note', 'other');
        
        // Change supplier record types
        $this->update_column_values(PRFX.'supplier_records', 'type', 'advertising', 'marketing');
        $this->update_column_values(PRFX.'supplier_records', 'type', 'affiliate_marketing', 'marketing');
                       
        // Reverse blocked account values because of the rename active --> blocked
        $this->update_column_values(PRFX.'voucher_records', 'blocked', '0', '9');
        $this->update_column_values(PRFX.'voucher_records', 'blocked', '1', '0');
        $this->update_column_values(PRFX.'voucher_records', 'blocked', '9', '0');        
        
        // Convert timestamps to MySQL DATE
        $this->column_timestamp_to_mysql_date(PRFX.'company_record', 'year_start', 'company_name');
        $this->column_timestamp_to_mysql_date(PRFX.'company_record', 'year_end', 'company_name');
        $this->column_timestamp_to_mysql_date(PRFX.'expense_records', 'date', 'expense_id');
        $this->column_timestamp_to_mysql_date(PRFX.'voucher_records', 'expiry_date', 'voucher_id');
        $this->column_timestamp_to_mysql_date(PRFX.'invoice_records', 'date', 'invoice_id');
        $this->column_timestamp_to_mysql_date(PRFX.'invoice_records', 'due_date', 'invoice_id');
        $this->column_timestamp_to_mysql_date(PRFX.'payment_records', 'date', 'payment_id');        
        $this->column_timestamp_to_mysql_date(PRFX.'otherincome_records', 'date', 'otherincome_id');
                
        // Convert timestamps to MySQL DATETIME
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_notes', 'date', 'client_note_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_records', 'create_date', 'client_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_records', 'last_active', 'client_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'voucher_records', 'open_date', 'voucher_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'voucher_records', 'redeem_date', 'voucher_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'invoice_records', 'open_date', 'invoice_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'invoice_records', 'close_date', 'invoice_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'invoice_records', 'last_active', 'invoice_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'schedule_records', 'start_time', 'schedule_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'schedule_records', 'end_time', 'schedule_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'user_records', 'last_active', 'user_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'user_records', 'register_date', 'user_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'user_records', 'last_reset_time', 'user_id');        
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_history', 'date', 'history_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_notes', 'date', 'workorder_note_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_records', 'open_date', 'workorder_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_records', 'close_date', 'workorder_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_records', 'last_active', 'workorder_id');
        
        // Populate the last_active columns with record date for the following becasue they have only just received last_Active
        $this->copy_columnA_to_columnB('expense_records', 'date', 'last_active');
        $this->copy_columnA_to_columnB('otherincome_records', 'date', 'last_active');
        $this->copy_columnA_to_columnB('payment_records', 'date', 'last_active');  
        
        // Update Invoice Tax Types
        $this->update_column_values(PRFX.'company_record', 'tax_system', 'none', 'no_tax');
        $this->update_column_values(PRFX.'company_record', 'tax_system', 'vat', 'vat_cash');
        $this->update_column_values(PRFX.'company_record', 'tax_system', 'sales', 'sales_tax_cash');        
        $this->update_column_values(PRFX.'invoice_records', 'tax_system', 'none', 'no_tax');
        $this->update_column_values(PRFX.'invoice_records', 'tax_system', 'vat', 'vat_cash');
        $this->update_column_values(PRFX.'invoice_records', 'tax_system', 'sales', 'sales_tax_cash');
        
        // Set the Company Tax system and VAT tax code now the Company Record has been updated
        $this->company_tax_system = get_company_details('tax_system');
        $this->default_vat_tax_code = get_default_vat_tax_code($this->company_tax_system); // This is an educated guess
                
        // Update Invoice Items        
        $this->update_column_values(PRFX.'invoice_labour', 'tax_system', '*', $this->company_tax_system);
        $this->update_column_values(PRFX.'invoice_parts', 'tax_system', '*', $this->company_tax_system);         
        $this->update_column_values(PRFX.'invoice_labour', 'vat_tax_code', '*', $this->default_vat_tax_code);
        $this->update_column_values(PRFX.'invoice_parts', 'vat_tax_code', '*', $this->default_vat_tax_code);
        
        // Parse Labour and Parts records and update their totals to reflect the new VAT system
        $this->invoice_correct_labour_totals();
        $this->invoice_correct_parts_totals();        
        
        // Parse Voucher records and correct records        
        //$this->voucher_correct_workorder_id();
        //$this->voucher_correct_expiry_date(); not needed
        //$this->voucher_correct_status();
        $this->voucher_correct_records();
        $this->update_column_values(PRFX.'voucher_records', 'type', '*', 'multi_purpose');
        $this->update_column_values(PRFX.'voucher_records', 'tax_system', '*', $this->company_tax_system);
        $this->update_column_values(PRFX.'voucher_records', 'vat_tax_code', '*', get_voucher_vat_tax_code('multi_purpose', $this->company_tax_system)); 
        
        // Sales Tax Rate should be zero except for all invoices of 'sales_tax_cash' type
        $this->update_record_value(PRFX.'invoice_records', 'sales_tax_rate', 0.00, 'tax_system', 'sales_tax_cash', '!');
        
        // Populate newley created 'tax_system' and 'vat_tax_code' columns
        $this->update_column_values(PRFX.'expense_records', 'tax_system', '*', $this->company_tax_system);
        $this->update_column_values(PRFX.'expense_records', 'vat_tax_code', '*', $this->default_vat_tax_code);  
        $this->update_column_values(PRFX.'otherincome_records', 'tax_system', '*', $this->company_tax_system);
        $this->update_column_values(PRFX.'otherincome_records', 'vat_tax_code', '*', $this->default_vat_tax_code);
        
        // Populate newly created status columns
        $this->update_column_values(PRFX.'expense_records', 'status', '*', 'valid');
        $this->update_column_values(PRFX.'otherincome_records', 'status', '*', 'valid');        
        $this->update_column_values(PRFX.'supplier_records', 'status', '*', 'valid');
        $this->update_column_values(PRFX.'payment_records', 'status', '*', 'valid');
        
        // Correct currently upgraded invoice payment records  
        $this->update_column_values(PRFX.'payment_records', 'method', '6', 'bank_transfer'); // This might be a MyITCRM correction
        $this->update_column_values(PRFX.'payment_records', 'method', 'direct_deposit', 'bank_transfer');
        $this->update_column_values(PRFX.'payment_records', 'tax_system', '*', $this->company_tax_system );
        $this->update_column_values(PRFX.'payment_records', 'type', '*', 'invoice');
        
        // Parse Payment notes and extract information into 'additional_info' column for invoices
        $this->payments_parse_import_additional_info();
        
        // Convert expense, refund and otherincome transactions into separate record and payment
        $this->payments_create_expense_records_payments();        
        $this->payments_create_otherincome_records_payments(); 
                
        // Update database version number
        $this->update_record_value(PRFX.'version', 'database_version', '3.1.0');
        
        // Log message to setup log
        $record = _gettext("Database has now been upgraded to").' v'.$this->upgrade_step;
        $this->write_record_to_setup_log('upgrade', $record);
        
    }    
    
    /* Version Specific Upgrade Methods */
    
    ##############################################################
    #   Convert a timestamp `column` to a MySQL DATE `column`    #
    ##############################################################
    
     public function column_timestamp_to_mysql_date($table, $column_timestamp, $column_primary_key) {
        
        $db = QFactory::getDbo();
        $mysql_date = null;
        $temp_prfx = 'temp_';
        $local_error_flag = false;
        $column_comment = null;
        
        // Get Column Comment if present
        if ($column_comment = $this->get_column_comment($table, $column_timestamp)) {
            $column_comment = "COMMENT '$column_comment' ";
        }
        
        // Create a temp column for the new DATE values
        $sql = "ALTER TABLE `".$table."` ADD `".$temp_prfx.$column_timestamp."` DATE NOT NULL AFTER `".$column_timestamp."`";        
        if(!$rs = $db->execute($sql)) { 
                        
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to create a temporary column called").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);            
           
            // The process has failed so stop any further proccesing
            goto process_end;
            
        }              
        
        // Loop through all of the timestamps, calculate the correct Date and enter it into the temporary timestamp column
        $sql = "SELECT * FROM ".$table;
        if(!$rs = $db->Execute($sql)) {
                        
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select the records from the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
            
        } else {

            // Loop through all records and 
            while(!$rs->EOF) { 

                // Convert the timestamp into the correct MySQL DATE
                $mysql_date = $this->timestamp_to_mysql_date_offset_aware($rs->fields[$column_timestamp]);

                // Update the temporary column record
                $sql = "UPDATE `".$table."` SET `".$temp_prfx.$column_timestamp."` = '".$mysql_date."' WHERE `".$table."`.`".$column_primary_key."` = '".$rs->fields[$column_primary_key]."';";
                if(!$temp_rs = $db->execute($sql)) { 
                                        
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message
                    $record = _gettext("Failed to update the record").' `'.$column_primary_key.'` '._gettext("in the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>'; 
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;                    
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }        
                
            // Remove the orginal timestamp column
            $sql = "ALTER TABLE `".$table."` DROP `".$column_timestamp."`";
            if(!$rs = $db->execute($sql)) { 
                                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to remove the original column").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;
                
            }

            // Rename temporary column (temp_xxx) to the original column name
            $sql = "ALTER TABLE `".$table."` CHANGE `temp_".$column_timestamp."` `".$column_timestamp."` DATE NOT NULL ".$column_comment;
            if(!$rs = $db->execute($sql)) { 
                                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to rename the temporary column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("to").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                
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
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully converted the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATE`").' '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }        
    
    }
    
    ##############################################################
    #  Convert a timestamp `column` to a MySQL DATETIME `column` #
    ##############################################################
    
    public function column_timestamp_to_mysql_datetime($table, $column_timestamp, $column_primary_key) {
        
        $db = QFactory::getDbo();
        $mysql_datetime = null;
        $temp_prfx = 'temp_';
        $local_error_flag = false;
        $column_comment = null;
        
        // Get Column Comment if present
        if ($column_comment = $this->get_column_comment($table, $column_timestamp)) {
            $column_comment = "COMMENT '$column_comment' ";
        }
        
        // Create a new temp column for the new DATETIME values
        $sql = "ALTER TABLE `".$table."` ADD `".$temp_prfx.$column_timestamp."` DATETIME NOT NULL AFTER `".$column_timestamp."`";        
        if(!$rs = $db->execute($sql)) { 
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to create a temporary column called").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
           
            // The process has failed so stop any further proccesing
            goto process_end;
            
        }              
        
        // Loop through all of the timestamps, calculate the correct Datetime and enter them into the temporary column
        $sql = "SELECT * FROM ".$table;
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records and 
            while(!$rs->EOF) { 

                // Convert the timestamp into the correct MySQL DATETIME
                $mysql_datetime = timestamp_mysql_datetime($rs->fields[$column_timestamp]);

                // Update the temporary column record
                $sql = "UPDATE `".$table."` SET `".$temp_prfx.$column_timestamp."` = '".$mysql_datetime."' WHERE `".$table."`.`".$column_primary_key."` = '".$rs->fields[$column_primary_key]."';";
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Log Message
                    $record = _gettext("Failed to update the record").' `'.$column_primary_key.'` '._gettext("in the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }        
                
            // Remove the orginal timestamp column
            $sql = "ALTER TABLE `".$table."` DROP `".$column_timestamp."`";
            if(!$rs = $db->execute($sql)) {
                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to remove the original column").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;   
                
            }

            // Rename temporary column (temp_xxx) to the original column name
            $sql = "ALTER TABLE `".$table."` CHANGE `temp_".$column_timestamp."` `".$column_timestamp."` DATETIME NOT NULL ".$column_comment;
            if(!$rs = $db->execute($sql)) {
                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to rename the temporary column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("to").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                
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
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully converted the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATETIME`").' '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    ##################################################################################################
    #  Convert a timestamp a MySQL DATE whilst compensating for (GMT/BST) || (Winter/Summer) offsets #
    ##################################################################################################
    
    public function timestamp_to_mysql_date_offset_aware($timestamp) {
        
        // If there is no timestamp return an empty MySQL DATE
        if(!$timestamp) {
            return '0000-00-00';
        }
        
        // If the timestamp already is a proper date in the format xxxx/xx/xx 00:00 then timestamp is correct 'as is' (there is no offset)
        if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} 00:00:00$/', date('Y-m-d H:i:s', $timestamp))) {              
            return timestamp_mysql_date($timestamp);           
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
        return timestamp_mysql_date($corrected_timestamp);        
        
    } 
    
    /*#######################################################################
    #  Parse Voucher records and populate with appropriate workorder_id   #  // This will only get the information about the invoice the voucher was spent on == broekn
    #######################################################################

    function voucher_correct_workorder_id() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = false;                     
        
        // Loop through all of the Voucher records
        $sql = "SELECT * FROM ".PRFX."voucher_records";
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `voucher_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each Voucher's status
            while(!$rs->EOF) { 
                
                // Get the workorder_id from the relevant invoice record
                $sql = "SELECT workorder_id FROM ".PRFX."invoice_records WHERE invoice_id = ".$rs->fields['invoice_id'];
                               
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to update the `workorder_id` for the Voucher record").' '.$rs->fields['voucher_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } else {
                    
                    // Update gifcert record with the new workorder_id
                    $this->update_record_value(PRFX.'voucher_records', 'workorder_id', $temp_rs->fields['workorder_id'], 'voucher_id', $rs->fields['voucher_id']);
                    
                    
                }
                
                // Advance the loop to the next record            
                $rs->MoveNext(); 
                    
            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete assigning `workorder_id` to all Voucher records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed assigning `workorder_id` to all Voucher records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }*/ 
    
    /*#################################
    #  Voucher correct expiry date  # // expiry date is not a DATE - so this is not needed currently
    #################################

    function voucher_correct_expiry_date() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = false;

        // Loop through all of the Voucher records
        $sql = "SELECT * FROM ".PRFX."voucher_records";
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `voucher_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each Voucher's status
            while(!$rs->EOF) { 
                                
                // Correct the Voucher expiry date - 00:00:00 to 23:59:59
                $correct_expiry_date = preg_replace('/00:00:00/', '23:59:59', $rs->fields['expiry_date']);
                                 
                // Update gifcert with the new expiry date
                $this->update_record_value(PRFX.'voucher_records', 'expiry_date', $correct_expiry_date , 'voucher_id', $rs->fields['voucher_id']);
                
                // Advance the loop to the next record            
                $rs->MoveNext(); 
                    
            }                       

        }               

        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete converting MySQL `date` column to `datetime`");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed converting MySQL `date` column to `datetime`");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }*/  
    
    #################################################################
    #  Parse Voucher records and populate with appropriate status   #  // This is after conversion to mysql DATE
    #################################################################  / call this voucher_correct_records / correct_voucher_records
                                                                        // add in the other fixes to the vouceher records here and deleete the others

    function voucher_correct_records() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = false;                     
        
        // Loop through all of the Voucher records
        $sql = "SELECT * FROM ".PRFX."voucher_records";
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `voucher_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each Voucher's status
            while(!$rs->EOF) { 
                
                /* Redeemed Client ID */
                
                $redeemed_client_id = $rs->fields['redeemed_invoice_id'] ? get_invoice_details($rs->fields['redeemed_invoice_id'], 'client_id') : '';
                
                /* Close Date / Status / Blocked */
                
                // Set qualifying Vouchers to redeemed status
                if($rs->fields['redeem_date'] != '0000-00-00 00:00:00') {
                    
                    $close_date = $rs->fields['redeem_date'];
                    $status = 'redeemed';                    
                    $blocked = 1;
                
                // Set qualifying Vouchers to expired status
                } elseif (time() > strtotime($rs->fields['expiry_date'].' 23:59:59')) {
                    
                    $close_date = $rs->fields['expiry_date'].' 23:59:59';
                    $status = 'expired';                    
                    $blocked = 1;
                
                // If not redeemed or expired it must be unused
                } else {
                    
                    $close_date = '0000-00-00 00:00:00';
                    $status = 'unused';                    
                    $blocked = 0;
                    
                }
                
                /* Build SQL */ 
                
                // 'invoice_id = 0' is because imported vouchers do not have an invoice and htis is required
                
                $sql = "UPDATE `".PRFX."voucher_records` SET
                        `invoice_id` = '0', 
                        `redeemed_client_id` = ".$db->qstr($redeemed_client_id).",                        
                        `close_date` = ".$db->qstr($close_date).",
                        `status` = ".$db->qstr($status).",                        
                        `blocked` = ".$blocked."
                        WHERE `voucher_id` = ".$rs->fields['voucher_id'];
                
                
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to correct the the Voucher record").' '.$rs->fields['voucher_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
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
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed assigning `status` to all Voucher records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    #################################################################
    #  Recalculate Labour totals because of the new VAT system      #
    #################################################################

    function invoice_correct_labour_totals() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = false;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."invoice_labour";                

        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `invoice_labour`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each labour items's status
            while(!$rs->EOF) { 
                
                // Get the invoice details or use manual options here (compensates for records with missing invoices)
                if(!$invoice_details = get_invoice_details($rs->fields['invoice_id'])) {
                    $invoice_details['tax_system'] = 'no_tax';
                } 
                
                // Set sales tax exempt and all off. this feature was not available in earlier versions so nothing is exempt
                $sales_tax_exempt = 0;

                // Set the correct VAT code
                $vat_tax_code = get_default_vat_tax_code($invoice_details['tax_system']); 

                // Calculate the correct tax rate based on tax system (and exemption status)
                if($invoice_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $invoice_details['sales_tax_rate']; }
                elseif($invoice_details['tax_system'] == 'vat_standard') { $unit_tax_rate = get_vat_rate($vat_tax_code); }
                else { $unit_tax_rate = 0.00; }

                $item_totals = calculate_invoice_item_sub_totals($invoice_details['tax_system'], $rs->fields['unit_qty'], $rs->fields['unit_net'], $unit_tax_rate);

                $sql = "UPDATE `".PRFX."invoice_labour` SET
                    `invoice_id`        = ".$rs->fields['invoice_id'].",
                    `tax_system`        = ".$db->qstr($invoice_details['tax_system']).",
                    `description`       = ".$db->qstr($rs->fields['description']).",
                    `unit_qty`          = ".$rs->fields['unit_qty'].",
                    `unit_net`          = ".$rs->fields['unit_net'].",
                    `sales_tax_exempt`  = ".$sales_tax_exempt.",
                    `vat_tax_code`      = ".$db->qstr($vat_tax_code).",                        
                    `unit_tax_rate`     = ".$unit_tax_rate.",                       
                    `unit_tax`          = ".$item_totals['unit_tax'].",
                    `unit_gross`        = ".$item_totals['unit_gross'].",                        
                    `sub_total_net`     = ".$item_totals['sub_total_net'].",
                    `sub_total_tax`     = ".$item_totals['sub_total_tax'].",
                    `sub_total_gross`   = ".$item_totals['sub_total_gross']."
                    WHERE `invoice_labour_id`  = ".$rs->fields['invoice_labour_id'];                

                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {

                    // Set the setup global error flag
                    self::$setup_error_flag = true;

                    // Set the local error flag
                    $local_error_flag = true;

                    // Log Message                    
                    $record = _gettext("Failed to update the `totals` for the labour record").' '.$rs->fields['invoice_labour_id'];

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);

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
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed updating `totals` for all labour records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    #################################################################
    #  Recalculate Parts totals because of the new VAT system       #
    #################################################################

    function invoice_correct_parts_totals() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = false;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."invoice_parts";                

        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `invoice_parts`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each parts items's status
            while(!$rs->EOF) { 
                                
                // Get the invoice details or use manual options here (compensates for records with missing invoices)
                if(!$invoice_details = get_invoice_details($rs->fields['invoice_id'])) {
                    $invoice_details['tax_system'] = 'no_tax';
                }                
                
                // Set sales tax exempt and all off. this feature was not available in earlier versions so nothing is exempt
                $sales_tax_exempt = 0;

                // Set the correct VAT code
                $vat_tax_code = get_default_vat_tax_code($invoice_details['tax_system']); 

                // Calculate the correct tax rate based on tax system (and exemption status)
                if($invoice_details['tax_system'] == 'sales_tax_cash') { $unit_tax_rate = $invoice_details['sales_tax_rate']; }
                elseif($invoice_details['tax_system'] == 'vat_standard') { $unit_tax_rate = get_vat_rate($vat_tax_code); }
                else { $unit_tax_rate = 0.00; }

                $item_totals = calculate_invoice_item_sub_totals($invoice_details['tax_system'], $rs->fields['unit_qty'], $rs->fields['unit_net'], $unit_tax_rate);

                $sql = "UPDATE `".PRFX."invoice_parts` SET
                    `invoice_id`        = ".$rs->fields['invoice_id'].",
                    `tax_system`        = ".$db->qstr($invoice_details['tax_system']).",
                    `description`       = ".$db->qstr($rs->fields['description']).",
                    `unit_qty`          = ".$rs->fields['unit_qty'].",
                    `unit_net`          = ".$rs->fields['unit_net'].",
                    `sales_tax_exempt`  = ".$sales_tax_exempt.",
                    `vat_tax_code`      = ".$db->qstr($vat_tax_code).",                        
                    `unit_tax_rate`     = ".$unit_tax_rate.",                       
                    `unit_tax`          = ".$item_totals['unit_tax'].",
                    `unit_gross`        = ".$item_totals['unit_gross'].",                        
                    `sub_total_net`     = ".$item_totals['sub_total_net'].",
                    `sub_total_tax`     = ".$item_totals['sub_total_tax'].",
                    `sub_total_gross`   = ".$item_totals['sub_total_gross']."
                    WHERE `invoice_parts_id` = ".$rs->fields['invoice_parts_id'];               
                
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to update the `totals` for the parts record").' '.$rs->fields['invoice_parts_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
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
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed updating `totals` for all parts records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }    
    
    #################################################################
    #  Parse Payment records and populate additinal information     #
    #################################################################

    function payments_parse_import_additional_info() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = false;                     
        
        // Loop through all of the payment records
        $sql = "SELECT * FROM ".PRFX."payment_records";
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `payment_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
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
                                
                /* Parse out the infomration from payment notes (3.0.0 and MyITCRM) */
                
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
                    $voucher_id = get_voucher_id_by_voucher_code($voucher_code);                    
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
                        `voucher_id` = ".$db->qstr($voucher_id).",                        
                        `additional_info` = ". $db->qstr(json_encode($additional_info))."
                        WHERE `payment_id` = ".$rs->fields['payment_id'];                
                
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to import the `additional info` for the Payment record").' '.$rs->fields['payment_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
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
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed importing `additional info` for all Payment records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }   
    
    #######################################################################
    #  Convert Expenses into a separate item and make a related payment   #
    #######################################################################

    function payments_create_expense_records_payments() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = false;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."expense_records";                

        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `expense_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records
            while(!$rs->EOF) { 
                
                $sql = "INSERT INTO ".PRFX."payment_records SET            
                    employee_id     = ".$db->qstr($rs->fields['employee_id']                   ).",
                    client_id       = '',
                    workorder_id    = '',
                    invoice_id      = '',
                    voucher_id      = '',
                    refund_id       = '',
                    expense_id      = ".$db->qstr($rs->fields['expense_id']                    ).",
                    otherincome_id  = '',
                    date            = ".$db->qstr($rs->fields['date']                          ).",
                    tax_system      = ".$db->qstr($rs->fields['tax_system']                    ).",
                    type            = 'expense',
                    method          = ".$db->qstr($rs->fields['payment_method']                ).",
                    status          = 'valid',
                    amount          = ".$db->qstr($rs->fields['unit_gross']                    ).",
                    last_active     = ".$db->qstr($rs->fields['date']                          ).",
                    additional_info = ".$db->qstr(build_additional_info_json()                 ).",
                    note            = ".$db->qstr('<p>'._gettext("Created from an expense record during an upgrade of QWcrm.").'</p>');               
                
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to insert the corresponding payment record for expense reocrd").': '.$rs->fields['expense_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
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
            if(!$temp_rs = $db->execute($sql)) {

                // Set the setup global error flag
                self::$setup_error_flag = true;

                // Set the local error flag
                $local_error_flag = true;

                // Log Message                    
                $record = _gettext("Failed to delete the `expense_record` table `payment_method` column.");

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);

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
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed converting expense records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }    
    
    ############################################################################
    #  Convert Otherincomes into a separate item and make a related payment    #
    ############################################################################

    function payments_create_otherincome_records_payments() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = false;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."otherincome_records";                

        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `otherincome_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records
            while(!$rs->EOF) { 
                
                $sql = "INSERT INTO ".PRFX."payment_records SET            
                    employee_id     = ".$db->qstr($rs->fields['employee_id']                   ).",
                    client_id       = '',
                    workorder_id    = '',
                    invoice_id      = '',
                    voucher_id      = '',
                    refund_id       = '',
                    expense_id      = '',
                    otherincome_id  = ".$db->qstr($rs->fields['otherincome_id']                ).",
                    date            = ".$db->qstr($rs->fields['date']                          ).",
                    tax_system      = ".$db->qstr($rs->fields['tax_system']                    ).",
                    type            = 'otherincome',
                    method          = ".$db->qstr($rs->fields['payment_method']                ).",
                    status          = 'valid',
                    amount          = ".$db->qstr($rs->fields['unit_gross']                    ).",
                    last_active     = ".$db->qstr($rs->fields['date']                          ).",
                    additional_info = ".$db->qstr(build_additional_info_json()                 ).",
                    note            = ".$db->qstr('<p>'._gettext("Created from a otherincome record during an upgrade of QWcrm.").'</p>');               
                
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to insert the corresponding payment record for otherincome record").': '.$rs->fields['otherincome_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
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
            if(!$temp_rs = $db->execute($sql)) {

                // Set the setup global error flag
                self::$setup_error_flag = true;

                // Set the local error flag
                $local_error_flag = true;

                // Log Message                    
                $record = _gettext("Failed to delete the `otherincome_record` table `payment_method` column.");

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);

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
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed converting otherincome records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }    
    
}