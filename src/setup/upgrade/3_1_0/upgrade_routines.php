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
        insert_qwcrm_config_setting('error_handler_whoops', '0');
        update_qwcrm_config_setting('smarty_debugging_ctrl', 'NONE');
        
        // Tag all previous payments as type 'invoice'
        $this->update_column_values(PRFX.'payment_records', 'type', '*', 'invoice');
        
        // Change expense record types
        $this->update_column_values(PRFX.'expense_records', 'type', 'broadband', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'type', 'landline', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'type', 'mobile_phone', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'type', 'advertising', 'marketing');
        $this->update_column_values(PRFX.'expense_records', 'type', 'customer_refund', 'other');
        $this->update_column_values(PRFX.'expense_records', 'type', 'tax', 'other');
        $this->update_column_values(PRFX.'expense_records', 'type', 'gift_certificate', 'voucher');

        // Change otherincome record types
        $this->update_column_values(PRFX.'otherincome_records', 'type', 'credit_note', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'type', 'proxy_invoice', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'type', 'returned_services', 'cancelled_services');
        
        // Change supplier record types
        $this->update_column_values(PRFX.'supplier_records', 'type', 'advertising', 'marketing');
        $this->update_column_values(PRFX.'supplier_records', 'type', 'affiliate_marketing', 'marketing');
        
        // Change record payment methods
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'google_checkout', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'direct_deposit', 'bank_transfer');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'credit', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'credit_card', 'card');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'voucher', 'other');
        $this->update_column_values(PRFX.'otherincome_records', 'payment_method', 'credit_note', 'other');
                       
        // Reverse blocked account values because of the rename active --> blocked
        $this->update_column_values(PRFX.'voucher_records', 'blocked', '0', '9');
        $this->update_column_values(PRFX.'voucher_records', 'blocked', '1', '0');
        $this->update_column_values(PRFX.'voucher_records', 'blocked', '9', '0');        
        
        // Convert timestamps to MySQL DATE
        $this->column_timestamp_to_mysql_date(PRFX.'company_record', 'year_start', 'company_name');
        $this->column_timestamp_to_mysql_date(PRFX.'company_record', 'year_end', 'company_name');
        $this->column_timestamp_to_mysql_date(PRFX.'expense_records', 'date', 'expense_id');
        $this->column_timestamp_to_mysql_date(PRFX.'voucher_records', 'date_expires', 'voucher_id');
        $this->column_timestamp_to_mysql_date(PRFX.'invoice_records', 'date', 'invoice_id');
        $this->column_timestamp_to_mysql_date(PRFX.'invoice_records', 'due_date', 'invoice_id');
        $this->column_timestamp_to_mysql_date(PRFX.'payment_records', 'date', 'payment_id');
        $this->column_timestamp_to_mysql_date(PRFX.'refund_records', 'date', 'refund_id');
        
        // Convert timestamps to MySQL DATETIME
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_notes', 'date', 'client_note_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_records', 'create_date', 'client_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_records', 'last_active', 'client_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'voucher_records', 'date_created', 'voucher_id');
        $this->column_timestamp_to_mysql_datetime(PRFX.'voucher_records', 'date_redeemed', 'voucher_id');
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
        
        // Update Invoice Tax Types
        $this->update_column_values(PRFX.'invoice_records', 'tax_system', 'vat', 'vat_standard');
        $this->update_column_values(PRFX.'invoice_records', 'tax_system', 'sales', 'sales_tax');
        
        // Update Invoice Items
        $company_tax_system = get_company_details('tax_system');
        $this->update_column_values(PRFX.'invoice_labour', 'tax_system', '*', $company_tax_system);
        $this->update_column_values(PRFX.'invoice_parts', 'tax_system', '*', $company_tax_system);
        $default_vat_tax_code = get_default_vat_tax_code(); // This is an educated guess
        $this->update_column_values(PRFX.'invoice_labour', 'vat_tax_code', '*', $default_vat_tax_code);
        $this->update_column_values(PRFX.'invoice_labour', 'vat_tax_code', '*', $default_vat_tax_code);
        
        // Parse Labour and Parts records and update their totals to reflect the new VAT system
        $this->invoice_correct_labour_totals();
        $this->invoice_correct_parts_totals();        
        $this->invoice_correct_sales_tax_rate();
        
        // Parse Voucher records and correct records        
        $this->voucher_correct_workorder_id();
        $this->voucher_correct_expiry_date();
        $this->voucher_correct_status();
        $this->update_column_values(PRFX.'voucher_records', 'tax_system', '*', get_company_details('tax_system'));
        $this->update_column_values(PRFX.'voucher_records', 'vat_tax_code', '*', get_voucher_vat_tax_code()); 
        
        // Sales Tax Rate should be zero except for all invoices of 'sales_tax' type
        $this->update_record_value(PRFX.'invoice_records', 'sales_tax_rate', 0.00, 'tax_system', 'sales_tax', '!');
        
        // Populate newley created status columns
        $this->update_column_values(PRFX.'expense_records', 'status', '*', 'paid');
        $this->update_column_values(PRFX.'otherincome_records', 'status', '*', 'paid');
        $this->update_column_values(PRFX.'refund_records', 'status', '*', 'paid');
        $this->update_column_values(PRFX.'supplier_records', 'status', '*', 'valid');
                
        // Update database version number
        $this->update_record_value(PRFX.'version', 'database_version', '3.1.0');
        
        // Log message to setup log
        $record = _gettext("Database has now been upgraded to").' v'.$this->upgrade_step;
        $this->write_record_to_setup_log('upgrade', $record);
        
    }
     
    
}