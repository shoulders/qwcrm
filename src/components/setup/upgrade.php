<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('setup', 'upgrade', 'index_allowed')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Prevent undefined variable errors && Get 'stage' from the submit button
\CMSApplication::$VAR['stage'] = \CMSApplication::$VAR['submit'] ?? null;
$this->app->smarty->assign('stage', \CMSApplication::$VAR['stage']);

// Delete Setup files Action
if(isset(\CMSApplication::$VAR['action']) && \CMSApplication::$VAR['action'] == 'delete_setup_folder' && $this->app->system->security->checkPageAccessedViaQwcrm('setup', 'upgrade')) {
    $this->app->components->setup->deleteSetupFolder();
}


##################################################

// Temp for testing - This allows skips straight to database processing
//\CMSApplication::$VAR['stage'] = 'database_upgrade';
//\CMSApplication::$VAR['submit'] = 'database_upgrade';

##################################################


// Database Connection
if(!isset(\CMSApplication::$VAR['stage']) || \CMSApplication::$VAR['stage'] == 'database_connection') {
    
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_connection') {
        
        // Get the final version number for this part of the process, in the correct format, this also detects a multi-process upgrade
        $final_version = $this->app->components->setup->getUpgradeSteps();
        $final_version = str_replace('_', '.', end($final_version));
        
        // Load the 'To' and 'From' version numbers
        $this->app->smarty->assign('qwcrm_config', array('from' => $this->app->system->general->getQwcrmDatabaseVersionNumber(), 'to' => $final_version));        
        
        // If multiple steps - Advertise the fact here
        if(Setup::$split_database_upgrade) {
            $record = _gettext("The upgrade procedure has been split into multiple parts to prevent server timeouts.").'<br>';
            $record .= _gettext("Follow this process through until the upgrade is complete.").'<br>';
            $record .= _gettext("The final QWcrm version will be").': '.QWCRM_VERSION;    
            $this->app->system->variables->systemMessagesWrite('success', $record);            
        }
        
        $this->app->smarty->assign('stage', 'database_upgrade'); 
            
    } else {
        
        // Test the supplied database connection details, set message and button permission
        if($this->app->db->isConnected()) {            
            $this->app->smarty->assign('enable_next', true);
            $record = _gettext("Connected successfully to the database with the supplied credentials from the config file.");
            $this->app->system->variables->systemMessagesWrite('success', $record);
            $this->app->components->setup->writeRecordToSetupLog('upgrade', $record);                   
        } else {            
            $this->app->smarty->assign('enable_next', false);
            $record = _gettext("Failed to connect to the database with the supplied credentials. Check your config file.");
            $this->app->system->variables->systemMessagesWrite('danger', $record);
            $this->app->components->setup->writeRecordToSetupLog('upgrade', $record);            
        }
        
        // Load the Database Connection page
        $this->app->smarty->assign('qwcrm_config', array('db_host' => $this->app->config->get('db_host'), 'db_name' => $this->app->config->get('db_name'), 'db_user' => $this->app->config->get('db_user')));
        $this->app->smarty->assign('stage', 'database_connection');         
        
    }
    
}

// Upgrade the database (also allows for multi-part database upgrades)
if(\CMSApplication::$VAR['stage'] == 'database_upgrade') {    
    
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_upgrade') {
       
        $this->app->components->setup->writeRecordToSetupLog('upgrade', _gettext("Starting Database upgrade."));
        
        // Build a List of all of the upgrade version steps
        $upgrade_steps = $this->app->components->setup->getUpgradeSteps();
        
        // Upgrade the database by Process each upgrade step
        $process_upgrade_steps = $this->app->components->setup->processUpgradeSteps(\CMSApplication::$VAR, $upgrade_steps);
        
        if(!Setup::$setup_error_flag) {            
            $record = _gettext("The database upgraded successfully.");            
            $this->app->system->variables->systemMessagesWrite('success', $record); 
            $this->app->components->setup->writeRecordToSetupLog('upgrade', $record);
            \CMSApplication::$VAR['stage'] = 'database_upgrade_results';            
        
        // Load the results page with the error message      
        } else {              
           $record = _gettext("The database failed to upgrade.");                      
           $this->app->system->variables->systemMessagesWrite('danger', $record);
           $this->app->components->setup->writeRecordToSetupLog('upgrade', $record);
           \CMSApplication::$VAR['stage'] = 'database_upgrade_results';
           
        }        
    
    // Load the page
    } else {
                
        $this->app->smarty->assign('stage', 'database_upgrade');
        
    }
    
}

// Database Upgrade Results
if(\CMSApplication::$VAR['stage'] == 'database_upgrade_results') {

    // load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_upgrade_results') {        
        
            $record = _gettext("The QWcrm upgrade process has completed successfully.");
            $this->app->components->setup->writeRecordToSetupLog('upgrade', $record);
            $this->app->system->variables->systemMessagesWrite('success', $record);
            \CMSApplication::$VAR['stage'] = 'delete_setup_folder'; 
    
    // Load the page  
    } else {
        
        // If the upgrade has been split by break.txt, continue the database upgrade and tell the user, else continue to next stage        
        if(Setup::$split_database_upgrade) {  
            $record = _gettext("This QWcrm upgrade `Part` has completed successfully.");
            $this->app->components->setup->writeRecordToSetupLog('upgrade', $record);
            $this->app->system->variables->systemMessagesWrite('success', $record);
            
            // Set the 'Next' button to restart from the datbase upgrade step
            $next_button_value = 'database_connection';
         
        } else {
            
            // Set the 'Next' button to continue to the next step as normal
            $next_button_value= 'database_upgrade_results';
        }
        
        // Output Execution results to the screen
        $this->app->smarty->assign('executed_sql_results', Setup::$executed_sql_results);        
        $this->app->smarty->assign('stage', 'database_upgrade_results');
        $this->app->smarty->assign('next_button_value', $next_button_value);
                
    }
    
}

// Delete Setup folder
if(\CMSApplication::$VAR['stage'] == 'delete_setup_folder') {
    
    // There is a submit action on this stage
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'delete_setup_folder') {
        
        //\CMSApplication::$VAR['stage'] = 'unknown';
   
    // Load the page
    } else {
        
        // Log message to setup log - only when starting the process - this start every page loads
        $this->app->components->setup->writeRecordToSetupLog('upgrade', _gettext("QWcrm upgrade has finished."));
    
        // Clean up after setup process 
        $this->app->components->setup->setupFinished();
        
        // Set mandatory default values               
        $this->app->smarty->assign('stage', 'delete_setup_folder');
        
    }
    
}