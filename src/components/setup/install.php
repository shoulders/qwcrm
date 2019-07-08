<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'administrator.php');
require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'setup.php');
require(INCLUDES_DIR.'user.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup', 'install', 'index_allowed')) {
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

// Log message to setup log - only when starting the process - this start every page loads
$qsetup->write_record_to_setup_log('install', _gettext("QWcrm installation has begun."));

// Database Connection
if(!isset($VAR['stage']) || $VAR['stage'] == 'database_connection') {
    
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_connection') {
        
        // Test the supplied database connection details and store details if successful
        if($qsetup->verify_database_connection_details($VAR['qwcrm_config']['db_host'], $VAR['qwcrm_config']['db_user'], $VAR['qwcrm_config']['db_pass'], $VAR['qwcrm_config']['db_name'])) {
            
            $smarty->assign('information_msg', _gettext("Database connection successful."));
            $qsetup->create_config_file_from_default(SETUP_DIR.'install/install_configuration.php');
            update_qwcrm_config_settings_file($VAR['qwcrm_config']);           
            $qsetup->write_record_to_setup_log('install', _gettext("Connected successfully to the database with the supplied credentials and added them to the config file."));  
            $VAR['stage'] = 'config_settings';
        
        // Load the page - Error message done by verify_database_connection_details();
        } else {
            
            // reload the database connection page with the entered values and error message
            $smarty->assign('qwcrm_config', $VAR['qwcrm_config']);                       
            $qsetup->write_record_to_setup_log('install', _gettext("Failed to connect to the database with the supplied credentials.")); 
            $smarty->assign('stage', 'database_connection');             
            
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
        
        $smarty->assign('qwcrm_config', $qwcrm_config);
        $smarty->assign('stage', 'database_connection');  
        
    }
    
}


// Database Prefix (and other Config Settings)
if($VAR['stage'] == 'config_settings') {    
    
    // submit the config settings and load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'config_settings') {
                 
        // Correct missing secret varibles
        $VAR['qwcrm_config']['session_name']        = \Joomla\CMS\User\UserHelper::genRandomPassword(16);
        $VAR['qwcrm_config']['secret_key']          = \Joomla\CMS\User\UserHelper::genRandomPassword(32);
        
        update_qwcrm_config_settings_file($VAR['qwcrm_config']);
        $qsetup->write_record_to_setup_log('install', _gettext("Config settings have been added to the config file."));
        $VAR['stage'] = 'database_install';
    
    // Load the page
    } else {
        
        $VAR['qwcrm_config']['db_prefix'] = $qsetup->generate_database_prefix();
    
        $smarty->assign('qwcrm_config', $VAR['qwcrm_config']);        
        $smarty->assign('stage', 'config_settings');
        
    }
    
}


// Install the database
if($VAR['stage'] == 'database_install') {    
    
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_install') {
       
        $qsetup->write_record_to_setup_log('install', _gettext("Starting Database installation."));
        
        // install the database file and load the next page
        if($qsetup->install_database(SETUP_DIR.'install/install_database.sql')) {
            
            $record = _gettext("The database installed successfully.");            
            $smarty->assign('information_msg', $record); 
            $qsetup->write_record_to_setup_log('install', $record);
            $VAR['stage'] = 'database_install_results';            
        
        // Load the page with the error message      
        } else {            
              
           $record = _gettext("The database failed to install.");                      
           $smarty->assign('warning_msg', $record);
           $qsetup->write_record_to_setup_log('install', $record);
           $VAR['stage'] = 'database_install_results';
           
        }        
    
    // Load the page
    } else {
        $smarty->assign('stage', 'database_install');        
    }
    
}


// Database Installation Results
if($VAR['stage'] == 'database_install_results') { 

    // load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'database_install_results') {
        
        // Prefill Company Financial dates
        $qsetup->update_record_value(PRFX.'company_record', 'year_start', mysql_date());
        $qsetup->update_record_value(PRFX.'company_record', 'year_end', timestamp_mysql_date(strtotime('+1 year')));
        $VAR['stage'] = 'company_details';    
    
    // Load the page  
    } else {
        
        // Output Execution results to the screen
        $smarty->assign('executed_sql_results', QSetup::$executed_sql_results);
        $smarty->assign('stage', 'database_install_results');
        
    }
    
}


// Company Details
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
        
        // Set the date format required for update_company_details()
        defined('DATE_FORMAT') ?: define('DATE_FORMAT', get_company_details('date_format'));
        
        // update company details and load next stage      
        update_company_details($VAR);
        $qsetup->write_record_to_setup_log('install', _gettext("Company options inserted."));
        $VAR['stage'] = 'start_numbers';
        
    // Load the page    
    } else {
        
        // date format is not set in the javascript date picker because i am manipulating stages not pages
                
        $smarty->assign('company_details', get_company_details());
        $smarty->assign('company_logo', QW_MEDIA_DIR . get_company_details('logo') );
        $smarty->assign('date_format', get_company_details('date_format'));
        $smarty->assign('date_formats', get_date_formats());
        $smarty->assign('tax_systems', get_tax_systems());
        $smarty->assign('vat_tax_codes', get_vat_tax_codes(null, true) );
        $smarty->assign('stage', 'company_details');
        
    }
    
}


// Work Order and Invoice Start Numbers
if($VAR['stage'] == 'start_numbers') {  
    
    // submit the workorder and invoice start numbers if supplied, then load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'start_numbers') {
        
        if($VAR['workorder_start_number']) {
            $qsetup->set_workorder_start_number($VAR['workorder_start_number']);
            $qsetup->write_record_to_setup_log('install', _gettext("Starting Work Order number has been set to").' '.$VAR['workorder_start_number']);
        }
        
        if($VAR['invoice_start_number']) {
            $qsetup->set_invoice_start_number($VAR['invoice_start_number']);
            $qsetup->write_record_to_setup_log('install', _gettext("Starting Invoice number has been set to").' '.$VAR['invoice_start_number']);
        }
        
        $VAR['stage'] = 'administrator_account';
    
    // Load the page
    } else {
        $smarty->assign('stage', 'start_numbers');
    }
        
}


// Create an Administrator account
if($VAR['stage'] == 'administrator_account') {
    
    // create the administrator and load the next page
    if(isset($VAR['submit']) && $VAR['submit'] == 'administrator_account') {
       
        insert_user($VAR);
        $qsetup->write_record_to_setup_log('install', _gettext("The administrator account has been created."));
        $qsetup->write_record_to_setup_log('install', _gettext("The QWcrm installation process has completed successfully."));
        $smarty->assign('information_msg', _gettext("The QWcrm installation process has completed successfully."));
        $VAR['stage'] = 'delete_setup_folder';        
    
    // Load the page
    } else {
        
        // Prevent undefined variable errors
        $user_details = array
                            (
                                'first_name' => null,
                                'last_name' => null,
                                'is_employee' => null,
                                'based' => null,
                                'email' => null,
                                'username' => null,
                                'password' => null,
                                'usergroup' => null,
                                'active' => null,
                                'require_reset' => null,
                                'work_primary_phone' => null,
                                'work_mobile_phone' => null,
                                'work_fax' => null,
                                'home_primary_phone' => null,
                                'home_mobile_phone' => null,
                                'home_email' => null,
                                'home_address' => null,
                                'home_city' => null,
                                'home_state' => null,
                                'home_zip' => null,
                                'home_country' => null,
                                'note' => null        
                            );
    
        // Set mandatory default values
        $smarty->assign('date_format', get_company_details('date_format'));
        $smarty->assign('user_details', $user_details); 
        $smarty->assign('user_locations', get_user_locations());           
        $smarty->assign('stage', 'administrator_account');
        
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
        $qsetup->write_record_to_setup_log('upgrade', _gettext("QWcrm installation has finished."));
        
        // Clean up after setup process 
        $qsetup->setup_finished();
        
        // Set mandatory default values               
        $smarty->assign('stage', 'delete_setup_folder');
        
    }
    
}


// Build the page
$BuildPage .= $smarty->fetch('setup/install.tpl');