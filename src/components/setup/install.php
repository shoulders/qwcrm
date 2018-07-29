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

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup', 'install', 'index')) {
    die(_gettext("No Direct Access Allowed."));
}

// Log message to setup log - only when starting the process
write_record_to_setup_log('install', _gettext("QWcrm installation has begun."));

// Delete Setup files Action
if(isset($VAR['action']) && $VAR['action'] == 'delete_setup_folder' && check_page_accessed_via_qwcrm('setup', 'install')) {
    delete_setup_folder();
}

// Stage 1 - Database Connection -->
if(isset($VAR['stage']) && $VAR['stage'] == '1') {
    
    if($VAR['submit'] == 'stage1') {
        
        // test the supplied database connection details
        if(verify_database_connection_details($VAR['qwcrm_config']['db_host'], $VAR['qwcrm_config']['db_user'], $VAR['qwcrm_config']['db_pass'], $VAR['qwcrm_config']['db_name'])) {
            
            $smarty->assign('information_msg', _gettext("Database connection successful."));
            create_config_file_from_default();
            update_qwcrm_config($VAR['qwcrm_config']);           
            write_record_to_setup_log('install', _gettext("Connected successfully to the database with the supplied credentials and added them to the config file."));  
            $VAR['stage'] = '2';
        
        // load the page - Error message done by verify_database_connection_details();
        } else {
            
            // reload the database connection page with the details and error message
            $smarty->assign('qwcrm_config', $VAR['qwcrm_config']);                       
            write_record_to_setup_log('install', _gettext("Failed to connect to the database with the supplied credentials.")); 
            $smarty->assign('stage', '1');             
            
        }
        
    }
    
}

// Stage 2 - Config Settings
if(isset($VAR['stage']) && $VAR['stage'] == '2') {    
    
    // submit the config settings and load the next page
    if($VAR['submit'] == 'stage2') {
                 
        // Correct missing secret varibles
        $VAR['qwcrm_config']['session_name']        = JUserHelper::genRandomPassword(16);
        $VAR['qwcrm_config']['secret_key']          = JUserHelper::genRandomPassword(32);
        
        update_qwcrm_config($VAR['qwcrm_config']);
        write_record_to_setup_log('install', _gettext("Config settings have been added to the config file."));
        $VAR['stage'] = '3';
    
    // load the page
    } else {
        
        $VAR['qwcrm_config']['db_prefix'] = generate_database_prefix();
    
        $smarty->assign('qwcrm_config', $VAR['qwcrm_config']);        
        $smarty->assign('stage', '2');
        
    }
    
}

// Stage 3 - Install the database
if(isset($VAR['stage']) && $VAR['stage'] == '3') {    
    
    if($VAR['submit'] == 'stage3') {
       
        write_record_to_setup_log('install', _gettext("Starting Database installation."));
        
        // install the database file and load the next page
        if(install_database()) {
            
            $record = _gettext("The database installed successfully.");            
            $smarty->assign('information_msg', $record); 
            write_record_to_setup_log('install', $record);
            $VAR['stage'] = '4';            
        
        // load the page with the error message      
        } else {            
              
           $record = _gettext("The database failed to install.");                      
           $smarty->assign('warning_msg', $record);
           write_record_to_setup_log('install', $record);
           $VAR['stage'] = '4';
           
        }        
    
    // load the page
    } else {
        $smarty->assign('stage', '3');        
    }
    
}

// Stage 4 - Database Installation Results
if(isset($VAR['stage']) && $VAR['stage'] == '4') { 

    // load the next page
    if($VAR['submit'] == 'stage4') {
        
        // Prefill Company Financial dates
        update_record_value(PRFX.'company_options', 'year_start', mysql_date()) ;
        update_record_value(PRFX.'company_options', 'year_end', timestamp_mysql_date(strtotime('+1 year')));
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
if(isset($VAR['stage']) && $VAR['stage'] == '5') {   
    
    // submit the company details and load the next page
    if($VAR['submit'] == 'stage5') {
        
        // upload_company details
        update_company_details($VAR);
        write_record_to_setup_log('install', _gettext("Company details inserted."));
        $VAR['stage'] = '6';
        
    // load the page    
    } else {
        
        // date format is not set in the javascript date picker because i am manipulating stages not pages
        
        $smarty->assign('date_formats', get_date_formats());
        $smarty->assign('company_details', get_company_details());
        $smarty->assign('company_logo', QW_MEDIA_DIR . get_company_details('logo') );
        $smarty->assign('stage', '5');
        
    }
}

// Stage 6 - Work Order and Invoice Start Numbers
if(isset($VAR['stage']) && $VAR['stage'] == '6') {  
    
    // submit the workorder and invoice start numbers if supplied, then load the next page
    if($VAR['submit'] == 'stage6') {
        
        if($VAR['workorder_start_number'] != '') {
            set_workorder_start_number($VAR['workorder_start_number']);
            write_record_to_setup_log('install', _gettext("Starting Work Order number has been set."));
        }
        
        if($VAR['invoice_start_number'] != '') {
            set_invoice_start_number($VAR['invoice_start_number']);
            write_record_to_setup_log('install', _gettext("Starting Invoice number has been set."));
        }
        
        $VAR['stage'] = '7';
    
    // load the page
    } else {
        $smarty->assign('stage', '6');
    }
        
}

// Stage 7 - Create an Administrator
if(isset($VAR['stage']) && $VAR['stage'] == '7') {
    
    // create the administrator and load the next page
    if($VAR['submit'] == 'stage7') {  
       
        insert_user($VAR);
        write_record_to_setup_log('install', _gettext("The administrator account has been created."));
        write_record_to_setup_log('install', _gettext("The QWcrm installation process has completed successfully."));
        $smarty->assign('information_msg', _gettext("The QWcrm installation process has completed successfully."));
        $VAR['stage'] = '8';        
    
    // load the page
    } else {
    
        // Set mandatory default values
        $smarty->assign('user_locations', get_user_locations());           
        $smarty->assign('stage', '7');
        
    }
    
}

// Stage 8 - Delete Setup files
if(isset($VAR['stage']) && $VAR['stage'] == '8') {
    
    // Create the administrator and load the next page
    if($VAR['submit'] == 'stage8') {              
        
        //$VAR['stage'] = '9';  
    
    // load the page
    } else {
    
        // Set mandatory default values
        $smarty->assign('user_locations', get_user_locations());           
        $smarty->assign('stage', '8');
        
    }
    
}

// Build the page
$BuildPage .= $smarty->fetch('setup/install.tpl');