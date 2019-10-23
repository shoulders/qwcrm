<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'administrator.php');
require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'setup.php');
require(INCLUDES_DIR.'voucher.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup', 'upgrade', 'index_allowed')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Prevent undefined variable errors && Get 'stage' from the submit button
\QFactory::$VAR['stage'] = isset(\QFactory::$VAR['submit']) ? \QFactory::$VAR['submit'] : null;
$smarty->assign('stage', \QFactory::$VAR['stage']);

// Create a Setup Object
$qsetup = new QSetup(\QFactory::$VAR);

// Delete Setup files Action
if(isset(\QFactory::$VAR['action']) && \QFactory::$VAR['action'] == 'delete_setup_folder' && check_page_accessed_via_qwcrm('setup', 'upgrade')) {
    $qsetup->delete_setup_folder();
}


##################################################

// Temp for testing - This allows skips straight to database processing
//\QFactory::$VAR['stage'] = 'database_upgrade';
//\QFactory::$VAR['submit'] = 'database_upgrade';

##################################################


// Database Connection
if(!isset(\QFactory::$VAR['stage']) || \QFactory::$VAR['stage'] == 'database_connection') {
    
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_connection') {
        
        // Get the final version number for this part of the process, in the correct format, this also detects a multi-process upgrade
        $final_version = $qsetup->get_upgrade_steps();
        $final_version = str_replace('_', '.', end($final_version));
        
        // Load the 'To' and 'From' version numbers
        $smarty->assign('qwcrm_config', array('from' => get_qwcrm_database_version_number(), 'to' => $final_version));        
        
        // If multiple steps - Advertise the fact here
        if(QSetup::$split_database_upgrade) {
            $record = _gettext("The upgrade procedure has been split into multiple parts to prevent server timeouts.").'<br>';
            $record .= _gettext("Follow this process through until the upgrade is complete.").'<br>';
            $record .= _gettext("The final QWcrm version will be").': '.QWCRM_VERSION;    
            $smarty->assign('msg_success', $record);            
        }
        
        $smarty->assign('stage', 'database_upgrade'); 
            
    } else {
        
        $qwcrm_config = new QConfig;
        
        // Test the supplied database connection details, set message and button permission
        if($qsetup->verify_database_connection_details($qwcrm_config->db_host, $qwcrm_config->db_user, $qwcrm_config->db_pass, $qwcrm_config->db_name)) {            
            $smarty->assign('enable_next', true);
            $record = _gettext("Connected successfully to the database with the supplied credentials from the config file.");
            $smarty->assign('msg_success', $record);
            $qsetup->write_record_to_setup_log('upgrade', $record);                   
        } else {            
            $smarty->assign('enable_next', false);
            $record = _gettext("Failed to connect to the database with the supplied credentials. Check your config file.");
            $smarty->assign('msg_danger', $record);
            $qsetup->write_record_to_setup_log('upgrade', $record);            
        }
        
        // Load the Database Connection page
        $smarty->assign('qwcrm_config', array('db_host' => $qwcrm_config->db_host, 'db_name' => $qwcrm_config->db_name, 'db_user' => $qwcrm_config->db_user));
        $smarty->assign('stage', 'database_connection');         
        
    }
    
}

// Upgrade the database (also allows for multi-part database upgrades)
if(\QFactory::$VAR['stage'] == 'database_upgrade') {    
    
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_upgrade') {
       
        $qsetup->write_record_to_setup_log('upgrade', _gettext("Starting Database upgrade."));
        
        // Build a List of all of the upgrade version steps
        $upgrade_steps = $qsetup->get_upgrade_steps();
        
        // Upgrade the database by Process each upgrade step
        $process_upgrade_steps = $qsetup->process_upgrade_steps(\QFactory::$VAR, $upgrade_steps);
        
        if(!QSetup::$setup_error_flag) {            
            $record = _gettext("The database upgraded successfully.");            
            $smarty->assign('msg_success', $record); 
            $qsetup->write_record_to_setup_log('upgrade', $record);
            \QFactory::$VAR['stage'] = 'database_upgrade_results';            
        
        // Load the results page with the error message      
        } else {              
           $record = _gettext("The database failed to upgrade.");                      
           $smarty->assign('msg_danger', $record);
           $qsetup->write_record_to_setup_log('upgrade', $record);
           \QFactory::$VAR['stage'] = 'database_upgrade_results';
           
        }        
    
    // Load the page
    } else {
                
        $smarty->assign('stage', 'database_upgrade');
        
    }
    
}

// Database Upgrade Results
if(\QFactory::$VAR['stage'] == 'database_upgrade_results') {

    // load the next page
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_upgrade_results') {        
        
            $record = _gettext("The QWcrm upgrade process has completed successfully.");
            $qsetup->write_record_to_setup_log('upgrade', $record);
            $smarty->assign('msg_success', $record);
            \QFactory::$VAR['stage'] = 'delete_setup_folder'; 
    
    // Load the page  
    } else {
        
        // If the upgrade has been split by break.txt, continue the database upgrade and tell the user, else continue to next stage        
        if(QSetup::$split_database_upgrade) {  
            $record = _gettext("This QWcrm upgrade `Part` has completed successfully.");
            $qsetup->write_record_to_setup_log('upgrade', $record);
            $smarty->assign('msg_success', $record);
            
            // Set the 'Next' button to restart from the datbase upgrade step
            $next_button_value = 'database_connection';
         
        } else {
            
            // Set the 'Next' button to continue to the next step as normal
            $next_button_value= 'database_upgrade_results';
        }
        
        // Output Execution results to the screen
        $smarty->assign('executed_sql_results', QSetup::$executed_sql_results);        
        $smarty->assign('stage', 'database_upgrade_results');
        $smarty->assign('next_button_value', $next_button_value);
                
    }
    
}

// Delete Setup folder
if(\QFactory::$VAR['stage'] == 'delete_setup_folder') {
    
    // There is a submit action on this stage
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'delete_setup_folder') {
        
        //\QFactory::$VAR['stage'] = 'unknown';
   
    // Load the page
    } else {
        
        // Log message to setup log - only when starting the process - this start every page loads
        $qsetup->write_record_to_setup_log('upgrade', _gettext("QWcrm upgrade has finished."));
    
        // Clean up after setup process 
        $qsetup->setup_finished();
        
        // Set mandatory default values               
        $smarty->assign('stage', 'delete_setup_folder');
        
    }
    
}