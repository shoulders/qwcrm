<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'administrator.php');
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
if(isset($VAR['action']) && $VAR['action'] == 'delete_setup_folder' && check_page_accessed_via_qwcrm('setup', 'install')) {
    $qsetup->delete_setup_folder();
}


##################################################

// Temp for testing
$VAR['stage'] = 'database_upgrade_qwcrm';
$VAR['submit'] = 'database_upgrade_qwcrm';

##################################################


// Check Compatability tests - add a refresh button and a next when all tests pass
if($VAR['stage'] == 'compatibility_tests' || !isset($VAR['stage'])) {
    
    
    
    if(isset($VAR['submit']) && $VAR['submit'] == 'compatibility_tests') {
        
        // Test the supplied database connection details and store details if successful
        if($qsetup->verify_database_connection_details($VAR['qwcrm_config']['db_host'], $VAR['qwcrm_config']['db_user'], $VAR['qwcrm_config']['db_pass'], $VAR['qwcrm_config']['db_name'])) {
                           
        } else {
                                   
            
        }
        
    // Load the page
    } else {        
        
        // Prevent undefined variable errors
        $qwcrm_config = array
                            (
                                'db_host' => null,
                                'db_name' => null,
                                'db_user' => null,
                                'db_pass' => null
                            );
        
        // Log message to setup log
        $qsetup->write_record_to_setup_log('upgrade', _gettext("QWcrm upgrade has begun"));

        // Load the page            
        $smarty->assign('stage', 'compatibility_tests'); 
        
    }
    
}


// Upgrade the database (QWcrm)
if($VAR['stage'] == 'database_upgrade_qwcrm') {    
    
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_upgrade_qwcrm') {
        
        $qsetup->write_record_to_setup_log('migrate', _gettext("Starting Database upgrade."));
        
        // set version number to 0.0.0'

        // Build a List of all of the upgrade version steps
        $upgrade_steps = QSetup::get_upgrade_steps();

        // Process each upgrade step
        QSetup::process_upgrade_steps($VAR, $upgrade_steps);
    
    // Load the page
    } else {
        $smarty->assign('stage', 'database_install_qwcrm');        
    }
    
}


// Database Upgrade Results (QWcrm)
if($VAR['stage'] == 'database_upgrade_results') {    

    // load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_upgrade_results') {
        
        $VAR['stage'] = 'company_details';      
    
    // Load the page  
    } else {
        
        // Output Execution results to the screen
        $smarty->assign('executed_sql_results' ,QSetup::$executed_sql_results);        
        $smarty->assign('stage', 'database_install_results_qwcrm');
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