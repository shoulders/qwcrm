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
    
    public function pre_database() {
        
        // scripts executed before SQL script (if required)
        
    }
    
    public function process_database() {
        
        // Execute the upgrade SQL script
        $this->execute_sql_file_lines(SETUP_DIR.'upgrade/'.$this->upgrade_step.'/upgrade_database.sql');
        
    }
    
    public function post_database() {
        
        // Data
        $this->update_column_values(PRFX.'expense_records', 'type', 'broadband', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'type', 'landline', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'type', 'mobile_phone', 'telco');
        $this->update_column_values(PRFX.'expense_records', 'type', 'advertising', 'marketing');
        $this->update_column_values(PRFX.'supplier_records', 'type', 'advertising', 'marketing');
        $this->update_column_values(PRFX.'supplier_records', 'type', 'affiliate_marketing', 'marketing');
        
        // Config File
        insert_qwcrm_config_setting('sef', '0');
        insert_qwcrm_config_setting('error_handler_whoops', '1');
        update_qwcrm_config_setting('smarty_debugging_ctrl', 'NONE');
        
        // Convert timestamps to MySQL DATE
        $this->column_timestamp_to_mysql_date(PRFX.'company_record', 'company_name', 'year_start');
        $this->column_timestamp_to_mysql_date(PRFX.'company_record', 'company_name', 'year_end');
        $this->column_timestamp_to_mysql_date(PRFX.'expense_records', 'expense_id', 'date');
        $this->column_timestamp_to_mysql_date(PRFX.'giftcert_records', 'giftcert_id', 'date_expires');
        $this->column_timestamp_to_mysql_date(PRFX.'invoice_records', 'invoice_id', 'date');
        $this->column_timestamp_to_mysql_date(PRFX.'invoice_records', 'invoice_id', 'due_date');
        $this->column_timestamp_to_mysql_date(PRFX.'payment_records', 'payment_id', 'date');
        $this->column_timestamp_to_mysql_date(PRFX.'refund_records', 'refund_id', 'date');
        
        // Convert timestamps to MySQL DATETIME
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_notes', 'client_note_id', 'date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_records', 'client_id', 'create_date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'client_records', 'client_id', 'last_active');
        $this->column_timestamp_to_mysql_datetime(PRFX.'giftcert_records', 'giftcert_id', 'date_created');
        $this->column_timestamp_to_mysql_datetime(PRFX.'giftcert_records', 'giftcert_id', 'date_redeemed');
        $this->column_timestamp_to_mysql_datetime(PRFX.'invoice_records', 'invoice_id', 'open_date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'invoice_records', 'invoice_id', 'close_date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'invoice_records', 'invoice_id', 'last_active');
        $this->column_timestamp_to_mysql_datetime(PRFX.'schedule_records', 'schedule_id', 'start_time');
        $this->column_timestamp_to_mysql_datetime(PRFX.'schedule_records', 'schedule_id', 'end_time');
        $this->column_timestamp_to_mysql_datetime(PRFX.'user_records', 'user_id', 'last_active');
        $this->column_timestamp_to_mysql_datetime(PRFX.'user_records', 'user_id', 'register_date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'user_records', 'user_id', 'last_reset_time');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_history', 'history_id', 'date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_notes', 'workorder_note_id', 'date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_records', 'workorder_id', 'open_date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_records', 'workorder_id', 'close_date');
        $this->column_timestamp_to_mysql_datetime(PRFX.'workorder_records', 'workorder_id', 'last_active');
               
        // Update database version number (Done here beacause all database changes have been done)
        $this->update_record_value(PRFX.'version', 'database_version', '3.1.0');
        
    }
    
 
    
}