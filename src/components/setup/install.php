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
require(INCLUDES_DIR.'setup.php');
require(INCLUDES_DIR.'user.php');

// Get stage from the submit button
$VAR['stage'] = isset($VAR['submit']) ? $VAR['submit'] : null;

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup', 'install', 'index')) {
    die(_gettext("No Direct Access Allowed."));
}

// Log message to setup log - only when starting the process - this start every page loads
//write_record_to_setup_log('install', _gettext("QWcrm installation has begun."));

// Delete Setup files Action
if(isset($VAR['action']) && $VAR['action'] == 'delete_setup_folder' && check_page_accessed_via_qwcrm('setup', 'install')) {
    delete_setup_folder();
}


// Database Connection
if($VAR['stage'] == 'database_connection') {
    
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_connection') {
        
        // Test the supplied database connection details and store details if successful
        if(verify_database_connection_details($VAR['qwcrm_config']['db_host'], $VAR['qwcrm_config']['db_user'], $VAR['qwcrm_config']['db_pass'], $VAR['qwcrm_config']['db_name'])) {
            
            $smarty->assign('information_msg', _gettext("Database connection successful."));
            create_config_file_from_default();
            update_qwcrm_config($VAR['qwcrm_config']);           
            write_record_to_setup_log('install', _gettext("Connected successfully to the database with the supplied credentials and added them to the config file."));  
            $VAR['stage'] = 'database_prefix';
        
        // load the page - Error message done by verify_database_connection_details();
        } else {
            
            // reload the database connection page with the entered values and error message
            $smarty->assign('qwcrm_config', $VAR['qwcrm_config']);                       
            write_record_to_setup_log('install', _gettext("Failed to connect to the database with the supplied credentials.")); 
            $smarty->assign('stage', 'database_connection');             
            
        }
        
    // load the page
    } else {
        
        $smarty->assign('stage', 'database_connection');
        
    }
    
}


// Database Prefix (and other Config Settings
if($VAR['stage'] == 'database_prefix') {    
    
    // submit the config settings and load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_prefix') {
                 
        // Correct missing secret varibles
        $VAR['qwcrm_config']['session_name']        = JUserHelper::genRandomPassword(16);
        $VAR['qwcrm_config']['secret_key']          = JUserHelper::genRandomPassword(32);
        
        update_qwcrm_config($VAR['qwcrm_config']);
        write_record_to_setup_log('install', _gettext("Config settings have been added to the config file."));
        $VAR['stage'] = 'database_install';
    
    // load the page
    } else {
        
        $VAR['qwcrm_config']['db_prefix'] = generate_database_prefix();
    
        $smarty->assign('qwcrm_config', $VAR['qwcrm_config']);        
        $smarty->assign('stage', 'database_prefix');
        
    }
    
}


// Install the database
if($VAR['stage'] == 'database_install') {    
    
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_install') {
       
        write_record_to_setup_log('install', _gettext("Starting Database installation."));
        
        // install the database file and load the next page
        if(install_database()) {
            
            $record = _gettext("The database installed successfully.");            
            $smarty->assign('information_msg', $record); 
            write_record_to_setup_log('install', $record);
            $VAR['stage'] = 'database_install_results';            
        
        // load the page with the error message      
        } else {            
              
           $record = _gettext("The database failed to install.");                      
           $smarty->assign('warning_msg', $record);
           write_record_to_setup_log('install', $record);
           $VAR['stage'] = 'database_install_results';
           
        }        
    
    // load the page
    } else {
        $smarty->assign('stage', 'database_install');        
    }
    
}


// Database Installation Results
if($VAR['stage'] == 'database_install_results') { 

    // load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_install_results') {
        
        // Prefill Company Financial dates
        update_record_value(PRFX.'company_record', 'year_start', mysql_date()) ;
        update_record_value(PRFX.'company_record', 'year_end', timestamp_mysql_date(strtotime('+1 year')));
        $VAR['stage'] = 'company_details';    
    
    // load the page  
    } else {
        
        // Output Execution results to the screen
        global $executed_sql_results;
        $smarty->assign('executed_sql_results' ,$executed_sql_results);        
        $smarty->assign('stage', 'database_install_results');
        
    }
    
}


// Company Options
if($VAR['stage'] == 'company_details') {   
    
    // submit the company details and load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'company_details') {
        
        // Add missing information
        $company_details = get_company_details();
        $VAR['welcome_msg']             = $company_details['welcome_msg'];
        $VAR['email_signature']         = $company_details['email_signature'];
        $VAR['email_signature_active']  = $company_details['email_signature_active'];
        $VAR['email_msg_workorder']     = $company_details['email_msg_workorder'];
        $VAR['email_msg_invoice']       = $company_details['email_msg_invoice'];
                
        // update company details and load next stage
        update_company_details($VAR);
        write_record_to_setup_log('install', _gettext("Company options inserted."));
        $VAR['stage'] = 'start_numbers';
        
    // load the page    
    } else {
        
        // date format is not set in the javascript date picker because i am manipulating stages not pages
        
        $smarty->assign('date_formats', get_date_formats());
        $smarty->assign('company_details', get_company_details());
        $smarty->assign('company_logo', QW_MEDIA_DIR . get_company_details('logo') );
        $smarty->assign('stage', 'company_details');
        
    }
    
}


// Work Order and Invoice Start Numbers
if($VAR['stage'] == 'start_numbers') {  
    
    // submit the workorder and invoice start numbers if supplied, then load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'start_numbers') {
        
        if($VAR['workorder_start_number']) {
            set_workorder_start_number($VAR['workorder_start_number']);
            write_record_to_setup_log('install', _gettext("Starting Work Order number has been set."));
        }
        
        if($VAR['invoice_start_number']) {
            set_invoice_start_number($VAR['invoice_start_number']);
            write_record_to_setup_log('install', _gettext("Starting Invoice number has been set."));
        }
        
        $VAR['stage'] = 'administrator_account';
    
    // load the page
    } else {
        $smarty->assign('stage', 'start_numbers');
    }
        
}


// Create an Administrator account
if($VAR['stage'] == 'administrator_account') {
    
    // create the administrator and load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'administrator_account') {
       
        insert_user($VAR);
        write_record_to_setup_log('install', _gettext("The administrator account has been created."));
        write_record_to_setup_log('install', _gettext("The QWcrm installation process has completed successfully."));
        $smarty->assign('information_msg', _gettext("The QWcrm installation process has completed successfully."));
        $VAR['stage'] = 'delete_setup_folder';        
    
    // load the page
    } else {
    
        // Set mandatory default values
        $smarty->assign('user_locations', get_user_locations());           
        $smarty->assign('stage', 'administrator_account');
        
    }
    
}


// Delete Setup folder
if($VAR['stage'] == 'delete_setup_folder') {
    
    // There is not submit action on this stage
    if(isset($VAR['submit']) && $VAR['submit'] == 'delete_setup_folder') {
        
        //$VAR['stage'] = 'unknown';
   
    // load the page
    } else {
    
        // Set mandatory default values               
        $smarty->assign('stage', 'delete_setup_folder');
        
    }
    
}


// Build the page
$BuildPage .= $smarty->fetch('setup/install.tpl');