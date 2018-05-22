<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/administrator.php');
require(INCLUDES_DIR.'components/company.php');
require(INCLUDES_DIR.'components/setup.php');
require(INCLUDES_DIR.'components/user.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup', 'install', 'setup') || !defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
    die(_gettext("No Direct Access Allowed."));
}

// Log message to setup log - only when starting the process
if(!check_page_accessed_via_qwcrm('setup', 'install') ) {
    write_record_to_setup_log('install', _gettext("QWcrm installation has begun."));
}

// Stage 1 - Database Connection -->
if($VAR['stage'] == '1') {
    
    if($VAR['submit'] == 'stage1') {
        
        // test the supplied database connection details
        if(check_database_connection($db, $VAR['db_host'], $VAR['db_user'], $VAR['db_pass'], $VAR['db_name'])) {
            
            // Record details into the config file and display success message and load the next page       
            submit_qwcrm_config_settings($VAR);
            $VAR['stage'] = '2';
            $smarty->assign('information_msg', _gettext("Database connection successful."));
            
            write_record_to_setup_log('install', _gettext("Connected successfully to the database with the supplied credentials and added them to the config file."));  
            
        
        // load the page
        } else {
            
            // reload the database connection page with the details and error message
            $smarty->assign('qwcrm_config', $VAR);
            $smarty->assign('stage', '1');
            
            //$smarty->assign('warning_msg', _gettext("There is a database connection issue. Check your settings.")); - error done by check_database_connection()
            write_record_to_setup_log('install', _gettext("Failed to connect to the database with the supplied credentials.")); 
            
            
        }
        
    }
    
}

// Stage 2 - Config Settings
if($VAR['stage'] == '2') {    
    
    // submit the config settings and load the next page
    if($VAR['submit'] == 'stage2') {
                 
        // correct missing secret varibles
        $VAR['session_name']        = JUserHelper::genRandomPassword(16);
        $VAR['secret_key']          = JUserHelper::genRandomPassword(32);
        
        submit_qwcrm_config_settings($VAR);
        write_record_to_setup_log('install', _gettext("Config settings have been added to the config file."));
        $VAR['stage'] = '3';
    
    // load the page
    } else {
        
        // Set default mandatory values - This makes it easier for users only
        $VAR['theme_name']          = 'default';
        $VAR['google_server']       = 'https://www.google.com/';
        $VAR['session_lifetime']    = '15';
        $VAR['cookie_lifetime']     = '60';
        $VAR['cookie_token_length'] = '16';
        $VAR['db_prefix']           = generate_database_prefix();
    
        $smarty->assign('qwcrm_config', $VAR);        
        $smarty->assign('stage', '2');
        
    }
    
}

// Stage 3 - Install the database
if($VAR['stage'] == '3') {    
    
    if($VAR['submit'] == 'stage3') {
       
        write_record_to_setup_log('install', _gettext("Starting Database installation."));
        
        // install the database file and load the next page
        if(install_database($db)) {
            
            $record = _gettext("The database installed successfully.");
            write_record_to_setup_log('install', $record);
            $smarty->assign('information_msg', $record);            
            $VAR['stage'] = '4';            
        
        // load the page with the error message      
        } else {            
              
           $record = _gettext("The database failed to install.");           
           write_record_to_setup_log('install', $record);           
           $smarty->assign('warning_msg', $record);           
           $VAR['stage'] = '4';
           
        }        
    
    // load the page
    } else {
        $smarty->assign('stage', '3');        
    }
    
}

// Stage 4 - Database Installation Results
if($VAR['stage'] == '4') { 

    // load the next page
    if($VAR['submit'] == 'stage4') {
        $VAR['stage'] = '5';    
    
    // load the page  
    } else {
        
        // Output Execution results to the screen
        global $executed_sql_results;
        $smarty->assign('executed_sql_results' ,$executed_sql_results);        
        $smarty->assign('stage', '4');
    }
    
}

// Stage 5 - Company Details
if($VAR['stage'] == '5') {   
        
    // submit the company details and load the next page
    if($VAR['submit'] == 'stage5') {
        
        // upload_company details
        update_company_details($db, $VAR);
        write_record_to_setup_log('install', _gettext("Company details inserted."));
        $VAR['stage'] = '6';
        
    // load the page    
    } else {
        
        $smarty->assign('date_format', get_company_details($db, 'date_format'));
        $smarty->assign('company_details', get_company_details($db));
        $smarty->assign('stage', '5');
        
    }
}

// Stage 6 - Work Order and Invoice Start Numbers
if($VAR['stage'] == '6') {  
    
    // submit the workorder and invoice start numbers if supplied, then load the next page
    if($VAR['submit'] == 'stage6') {
        
        if($VAR['workorder_start_number'] != '') {
            set_workorder_start_number($db, $VAR['workorder_start_number']);
            write_record_to_setup_log('install', _gettext("Starting Work Order number has been set."));
        }
        
        if($VAR['invoice_start_number'] != '') {
            set_invoice_start_number($db, $VAR['invoice_start_number']);
            write_record_to_setup_log('install', _gettext("Starting Invoice number has been set."));
        }
        
        $VAR['stage'] = '7';
    
    // load the page
    } else {
        $smarty->assign('stage', '6');
    }
        
}

// Stage 7 - Create an Administrator
if($VAR['stage'] == '7') {
    
    // create the administrator and load the next page
    if($VAR['submit'] == 'stage7') {  
       
        insert_user($db, $VAR);
        write_record_to_setup_log('install', _gettext("The administrator account has been created."));
        write_record_to_setup_log('install', _gettext("The QWcrm installation process has completed successfully."));
        //$VAR['stage'] = '8';
        
        force_page('user', 'login', 'setup=finished&information_msg='._gettext("The QWcrm installation process has completed successfully.").' '._gettext("Please login with the administrator account you have just created."), 'get');
        exit;
    
    // load the page
    } else {
    
        // Set mandatory default values
        $smarty->assign('is_employee', '1');    
        $smarty->assign('stage', '7');
        
    }
    
}

// Build the page
$BuildPage .= $smarty->fetch('setup/install.tpl');