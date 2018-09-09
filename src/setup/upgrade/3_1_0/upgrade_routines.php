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
    
    public function __construct(&$VAR, &$BuildPage) {
        
        // Call parent's constructor
        parent::__construct($VAR, $BuildPage);
        
        // Get the upgrade step name
        $this->upgrade_step = str_replace('Upgrade', '', static::class);  // __CLASS__ ? - currently will not work for classes with name spaces
        
        // Perform the upgrade
        pre_database();
        process_database();
        post_database();
        
    }    
    
    public function pre_database() {
        
        // scripts executed before SQL script (if required)
        
    }
    
    public function process_database() {
        
        // SQL script
        execute_sql_file_lines(SETUP_DIR.'upgrade/'.$this->upgrade_step.'/upgrade.sql');
        
    }
    
    public function post_database() {
        
        // Data
        update_column_values(PRFX.'expense_records', 'type', 'broadband', 'telco');
        update_column_values(PRFX.'expense_records', 'typer', 'landline', 'telco');
        update_column_values(PRFX.'expense_records', 'type', 'mobile_phone', 'telco');
        update_column_values(PRFX.'expense_records', 'type', 'advertising', 'marketing');
        update_column_values(PRFX.'supplier_records', 'type', 'advertising', 'marketing');
        update_column_values(PRFX.'supplier_records', 'type', 'affiliate_marketing', 'marketing');
        
        // Config File
        insert_qwcrm_config_setting('sef', '0');
        insert_qwcrm_config_setting('error_handler_whoops', '1');
        update_qwcrm_config_setting('smarty_debugging_ctrl', 'NONE');

        
        
        
        // scripts executed before SQL script (if required)
        
        // update version number here? or in the SQL
        
    }

    
}