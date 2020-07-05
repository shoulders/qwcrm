<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Upgrade3_1_2 extends Setup {
    
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
        $this->app->components->administrator->deleteQwcrmConfigSetting('session_name');
        $this->app->components->administrator->deleteQwcrmConfigSetting('session');
        
        // Correct records that did not have their status updated properly to 'paid'
        $this->updateColumnValues(PRFX.'expense_records', 'status', 'valid', 'paid');
        $this->updateColumnValues(PRFX.'otherincome_records', 'status', 'valid', 'paid');
        
        // Update database version number
        $this->updateRecordValue(PRFX.'version', 'database_version', str_replace('_', '.', $this->upgrade_step));
        
        // Log message to setup log
        $record = _gettext("Database has now been upgraded to").' v'.str_replace('_', '.', $this->upgrade_step);
        $this->writeRecordToSetupLog('upgrade', $record);
        
    }    
    
    /* Version Specific Upgrade Methods */
        
}