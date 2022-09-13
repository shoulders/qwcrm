<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Cronjob extends Components {

     /** Get Functions **/

    ###############################
    #  Display Cronjobs           #
    ###############################

    public function getRecords($order_by, $direction) {       

        // The SQL code
        $sql =  "SELECT * 
                FROM ".PRFX."cronjob_records                                                   
                WHERE ".PRFX."cronjob_records.cronjob_id\n
                GROUP BY ".PRFX."cronjob_records.".$order_by."
                ORDER BY ".PRFX."cronjob_records.".$order_by."
                ".$direction;
        
        // Get the records
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Return the data (not all setting below are relevant because this is a cut down getRecords())      
        return array(
                'records' => $rs->GetArray(),
                'total_results' => null,
                'total_pages' => null,             // This make the drop down menu look correct on search tpl with use_pages off
                'page_no' => null,
                'previous_page_no' => null,
                'next_page_no' => null,                    
                'restricted_records' => false,
                );

    }   

    ###############################
    #   Get Cronjob details       #
    ###############################

    public function getRecord($cronjob_id, $item = null) {        

        $sql = "SELECT * FROM ".PRFX."cronjob_records WHERE cronjob_id=".$this->app->db->qStr($cronjob_id);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc();            

        } else {

            return $rs->fields[$item];   

        }  

    }
    
    ##############################
    #   Get System Details       # 
    ##############################

    public function getSystem($item = null) {

        $sql = "SELECT * FROM ".PRFX."cronjob_system";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        if($item === null){

            return $rs->GetRowAssoc();            

        } else {

            return $rs->fields[$item];   

        }         

    }       

    /** Update Functions **/

    #####################################
    #     Update cron                   #
    #####################################

    public function updateRecord($qform) {
        
        $sql = "UPDATE ".PRFX."cronjob_records SET                
                active              =". $this->app->db->qStr( $qform['active']              ).",
                pseudo_allowed      =". $this->app->db->qStr( $qform['pseudo_allowed']      ).",            
                minute              =". $this->app->db->qStr( $qform['minute']              ).",            
                hour                =". $this->app->db->qStr( $qform['hour']                ).",
                day                 =". $this->app->db->qStr( $qform['day']                 ).",
                month               =". $this->app->db->qStr( $qform['month']               ).",
                weekday             =". $this->app->db->qStr( $qform['weekday']             )."                     
                WHERE cronjob_id    =". $this->app->db->qStr( $qform['cronjob_id']          );
        
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity        
        $record = _gettext("Cronjob Record").' '.$qform['cronjob_id'].' '._gettext("updated.");
        $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id);

        return true; 

    }
    
    ####################################
    #    Update record Last Run Time   #
    ####################################

    private function updateRecordLastRunTime($cronjob_id, $lastRunTime) {

        $sql = "UPDATE ".PRFX."cronjob_records SET
                last_run_time=".$this->app->db->qStr($lastRunTime)."
                WHERE cronjob_id=".$this->app->db->qStr($cronjob_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }    
    
    #####################################
    #    Update record Last Run Status  #   #updateRecordLastRunStatus
    #####################################

    private function updateRecordLastRunStatus($cronjob_id, $status) {

        $sql = "UPDATE ".PRFX."cronjob_records SET
                last_run_status=".$this->app->db->qStr($status)."
                WHERE cronjob_id=".$this->app->db->qStr($cronjob_id);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    } 
    
    ##################################
    #   Update record Locked status  #       # true/false ///////////////////////// not currently used
    ##################################

    public function updateRecordLockedStatus($cronjob_id, int $status) {

        $sql = "UPDATE ".PRFX."cronjob_records SET
                locked=".$this->app->db->qStr($status)."
                WHERE cronjob_id=".$this->app->db->qStr($cronjob_id);
                
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    } 

    #################################
    #  Update System Last Run Time  #
    #################################

    private function updateSystemLastRunTime($lastRunTime) {

        $sql = "UPDATE ".PRFX."cronjob_system SET
                last_run_time=".$this->app->db->qStr($lastRunTime);
                
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }  
    
    #####################################
    #    Update system Last Run status  #
    #####################################

    private function updateSystemLastRunStatus($status) {

        $sql = "UPDATE ".PRFX."cronjob_system SET
                last_run_status=".$this->app->db->qStr($status);

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    }
    #################################
    #    Update System Lock         # true/false
    #################################

    public function updateSystemLockedStatus($status) {

        $sql = "UPDATE ".PRFX."cronjob_system SET
                locked=".$this->app->db->qStr($status);
                
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

    } 
    
    /** Other Functions **/
   
    ###############################
    # This is run on QWcrm load   #  // decide if the cronjobs are going to be run by the system
    ###############################
    
    public function systemRun() {
                
        $cronjobSystem = $this->app->config->get('cronjob_system');
        
        // Real Cronjob system (CLI)
        if($cronjobSystem === 'real')
        {            
            // If loaded by cron.php
            if(defined('_REAL_CRONJOB'))
            {
                // Identify cronjobs are being executed by QWcrm and not manually
                define('CRONJOB_SYSTEM_ACTIVE', 1);
            }     
            
            // Ignore for normal page load
            else
            {
                return;
            }
        }
        
        // Pseudo Cronjob system - Check to see if it should be run
        if($cronjobSystem === 'pseudo')
        {           
            $currentTime        = time();                                                   // Current Time (in timestamp)
            $cronjobLastActive  = strtotime($this->getSystem('last_run_time'));             // Last run time (in timestamp)
            $pseudoInterval     = $this->app->config->get('cronjob_pseudo_interval') * 60;  // Pseudo Cronjob Interval (in seconds)   
      
            // Do not run cronjobs if time expired is less than the interval         
            if( ($currentTime - $cronjobLastActive) < $pseudoInterval ) { return; }
            
            // Identify cronjobs are being executed by QWcrm and not manually
            define('CRONJOB_SYSTEM_ACTIVE', 1);            
        }
               
        // Run All Cronjobs
        $this->runCronjobs();
        
        // Perform correct exit strategy               
        if(defined('_REAL_CRONJOB'))
        {
            die();
        } else {
            return;
        }
          
    }
    
    ##################################
    # This is the main run function  #
    ##################################
    
    private function runCronjobs($silent = true) {
        
        $state_flag = true;
        $currentTime = $this->app->system->general->mysqlDatetime();
        $cronjob_system_details = $this->getSystem();        
        
        // If Cronjob system is turned off
        if(!$this->app->config->get('cronjob_system')) {
            if(!$silent) {
                $this->app->system->variables->systemMessagesWrite('warning', _gettext("Cronjob System is disabled and cannot been run."));                
            }            
            return false;
        }
        
        // If the cronjob system is locked
        if($cronjob_system_details['locked']) {
            if(!$silent) {
                $this->app->system->variables->systemMessagesWrite('warning', _gettext("Cronjob System is locked and cannot been run."));                
            }            
            return false;
        }
        
        // Lock the cronjob system while we run the cronjobs
        $this->updateSystemLockedStatus(true);
        
        // The SQL code
        $sql =  "SELECT cronjob_id FROM ".PRFX."cronjob_records";

        // Return the records
        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
        $records = $rs->GetArray();        
        
        // Loop through the records and execute cronjobs
        foreach($records as $record) {            
            if(!$this->runCronjob($record['cronjob_id'], $silent)) {
                $state_flag = false;
            }
        }
        
        // Update system cronjobs last_run_time
        $this->updateSystemLastRunTime($currentTime);
        
        // Update the last run status in the database
        $this->updateSystemLastRunStatus($state_flag);
        
        // Unlock the cronjob system
        $this->updateSystemLockedStatus(false);
        
        // Cronjob System run messages
        if($state_flag) {
            // Success Message
            if(!$silent) {
               $this->app->system->variables->systemMessagesWrite('success', _gettext("The Cronjob System ran all enabled cronjobs successfully."));                
            }
        } else {        
            // Fail Message
            if(!$silent) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The Cronjob System did not run all cronjobs successfully."));                
            } 
        }
        
        return $state_flag;
    }
    
    ##########################
    #  Run a single cronjob  #
    ##########################
    
    public function runCronjob($cronjob_id, $silent = true) {
                
        $currentTime = $this->app->system->general->mysqlDatetime();
        $cronjob_details = $this->getRecord($cronjob_id);        
        
        // Is the cronjob enabled
        if(!$cronjob_details['active']) {
            if(!$silent) {
                $this->app->system->variables->systemMessagesWrite('warning', _gettext("Cron").' '.$cronjob_id.' '._gettext("is disabled and has not been run."));                
            }
            
            // Disabled single cronjobs should not fail the cron system, but manually run disabled cronjobs should fail
            if(defined('CRONJOB_SYSTEM_ACTIVE')) {
                return true;
            } else {
                return false;
            }

        }
        
        // If the cronjob is locked
        if($cronjob_details['locked']) {
            if(!$silent) {
                $this->app->system->variables->systemMessagesWrite('warning', _gettext("Cronjob").' '.$cronjob_id.' '._gettext("is locked and cannot been run."));                
            }            
            return false;
        }
            
        // Skip this if the cronjob has been run manually (i.e. when `pseudo_allowed` is not enabled and `pseudo` is the cron system)
        if(defined('CRONJOB_SYSTEM_ACTIVE')) {
            
            // Is the cronjob allowed for pseudo cron
            if(!$cronjob_details['pseudo_allowed']) {
                if(!$silent) {
                    $this->app->system->variables->systemMessagesWrite('warning', _gettext("Cronjob").' '.$cronjob_id.' '._gettext("is not allowed for Pseudo Cronjob system execution."));                
                }
                return false;                
            }
            
            // If cronjob is not due, return true (This check will not fail the cron system)
            $cronParser = Cron\CronExpression::factory($cronjob_details['minute'].' '.$cronjob_details['hour'].' '.$cronjob_details['day']. ' '.$cronjob_details['month'].' '.$cronjob_details['weekday']);
            $nextRunDate = $cronParser->getNextRunDate($cronjob_details['last_run_time'])->format('Y-m-d H:i:s');
            if($nextRunDate > $currentTime) {
                return true;
            }
            
        }        
        
        // Lock the cronjob system while we run the cronjobs
        $this->updateRecordLockedStatus($cronjob_id, true);
        
        // Get Cronjob Command
        $cronjobCommand = json_decode($cronjob_details['command'], true);        
        $cronjobClass = $cronjobCommand['class'];
        $cronjobFunction = $cronjobCommand['function'];
        
        // Execute the cronjob
        if((new $cronjobClass)->$cronjobFunction()) {            
            // true/passed
            if(!$silent) {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Cron").' '.$cronjob_id.' '._gettext("has been run successfully."));                
            }
            $state_flag = true;
        } else {
            // false/failed
            if(!$silent) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("Cron").' '.$cronjob_id.' '._gettext("has failed to run successfully."));                
            }
            $state_flag = false;
        }
              
        // Update cronjob last_run_time
        $this->updateRecordLastRunTime($cronjob_id, $currentTime);
        
        // Update the cronjob last_run_status
        $this->updateRecordLastRunStatus($cronjob_id, $state_flag);
        
        // Unlock the cronjob
        $this->updateRecordLockedStatus($cronjob_id, false);
        
        return $state_flag;
        
    }
  
    /** System Cronjobs **/
    
    ############################################
    #      Send Test Email                     #
    ############################################

    public function cronjobTest() {

        $company_details = $this->app->components->company->getRecord();
        
        // Send Email (silently)
        $message = _gettext("Cron Test").': '._gettext("This is a test mail sent using").' '.$this->app->config->get('email_mailer').'. '._gettext("Your email settings are correct!");
        $this->app->system->email->send($company_details['email'], _gettext("Test mail from QWcrm Cronjob System").' ( '.date(str_replace('%', '', DATE_FORMAT.' H:i:s').' )'), $message, $company_details['company_name'], array(), null, null, null, null, true);        
        
        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("Cronjob test initiated."));
        
        return true;

    }
    
    ############################################
    #     Check all Vouchers for expiry        #
    ############################################

    public function cronjobCheckAllVouchersForExpiry() {

        $this->app->components->voucher->checkAllVouchersForExpiry();
        
        return true;

    }
    
    ############################################
    #     Check all Credit Notes for expiry        #
    ############################################

    public function cronjobCheckAllCreditnotesForExpiry() {

        $this->app->components->creditnote->checkAllCreditnotesForExpiry();
        
        return true;

    }
    
}