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
$VAR['stage'] = isset($VAR['submit']) ? $VAR['submit'] : null;
$smarty->assign('stage', $VAR['stage']);

// Get 'stage' from the submit button
$VAR['stage'] = isset($VAR['submit']) ? $VAR['submit'] : null;

// Create a Setup Object
$qsetup = new QSetup($VAR);

// Delete Setup files Action
if(isset($VAR['action']) && $VAR['action'] == 'delete_setup_folder' && check_page_accessed_via_qwcrm('setup', 'upgrade')) {
    $qsetup->delete_setup_folder();
}


##################################################

// Temp for testing - This allows skips straight to database processing
//$VAR['stage'] = 'database_upgrade';
//$VAR['submit'] = 'database_upgrade';

##################################################


// Database Connection
if(!isset($VAR['stage']) || $VAR['stage'] == 'database_connection') {
    
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_connection') {
        
        // Load the Upgrade Database page        
        $smarty->assign('qwcrm_config', array('from' => get_qwcrm_database_version_number(), 'to' => QWCRM_VERSION));
        $smarty->assign('stage', 'database_upgrade'); 
            
    } else {
        
        $qwcrm_config = new QConfig;
        
        // Test the supplied database connection details, set message and button permission
        if($qsetup->verify_database_connection_details($qwcrm_config->db_host, $qwcrm_config->db_user, $qwcrm_config->db_pass, $qwcrm_config->db_name)) {            
            $smarty->assign('enable_next', true);
            $record = _gettext("Connected successfully to the database with the supplied credentials from the config file.");
            $smarty->assign('information_msg', $record);
            $qsetup->write_record_to_setup_log('upgrade', $record);                   
        } else {            
            $smarty->assign('enable_next', false);
            $record = _gettext("Failed to connect to the database with the supplied credentials. Check your config file.");
            $smarty->assign('Warning_msg', $record);
            $qsetup->write_record_to_setup_log('upgrade', $record);            
        }
        
        // Load the Database Connection page
        $smarty->assign('qwcrm_config', array('db_host' => $qwcrm_config->db_host, 'db_name' => $qwcrm_config->db_name, 'db_user' => $qwcrm_config->db_user));
        $smarty->assign('stage', 'database_connection');         
        
    }
    
}

// Upgrade the database
if($VAR['stage'] == 'database_upgrade') {    
    
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_upgrade') {
       
        $qsetup->write_record_to_setup_log('upgrade', _gettext("Starting Database upgrade."));
        
        // Build a List of all of the upgrade version steps
        $upgrade_steps = $qsetup->get_upgrade_steps();
        
        // Upgrade the database by Process each upgrade step
        $qsetup->process_upgrade_steps($VAR, $upgrade_steps);
        
        if(!QSetup::$setup_error_flag) {            
            $record = _gettext("The database upgraded successfully.");            
            $smarty->assign('information_msg', $record); 
            $qsetup->write_record_to_setup_log('upgrade', $record);
            $VAR['stage'] = 'database_upgrade_results';            
        
        // Load the results page with the error message      
        } else {              
           $record = _gettext("The database failed to upgrade.");                      
           $smarty->assign('warning_msg', $record);
           $qsetup->write_record_to_setup_log('upgrade', $record);
           $VAR['stage'] = 'database_upgrade_results';
           
        }        
    
    // Load the page
    } else {
        $smarty->assign('stage', 'database_upgrade');        
    }
    
}

// Database Upgrade Results
if($VAR['stage'] == 'database_upgrade_results') {    

    // load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_upgrade_results') {
        
        $record = _gettext("The QWcrm upgrade process has completed successfully.");
        $qsetup->write_record_to_setup_log('upgrade', $record);
        $smarty->assign('information_msg', $record);
        $VAR['stage'] = 'delete_setup_folder';           
    
    // Load the page  
    } else {
        
        // Output Execution results to the screen
        $smarty->assign('executed_sql_results', QSetup::$executed_sql_results);        
        $smarty->assign('stage', 'database_upgrade_results');
    }
    
}

// Delete Setup folder
if($VAR['stage'] == 'delete_setup_folder') {
    
    // There is a submit action on this stage
    if(isset($VAR['submit']) && $VAR['submit'] == 'delete_setup_folder') {
        
        //$VAR['stage'] = 'unknown';
   
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

// Build the page
$BuildPage .= $smarty->fetch('setup/upgrade.tpl');