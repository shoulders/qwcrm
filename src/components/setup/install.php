<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->check_page_accessed_via_qwcrm('setup', 'install', 'index_allowed')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Prevent undefined variable errors && Get 'stage' from the submit button
\CMSApplication::$VAR['stage'] = isset(\CMSApplication::$VAR['submit']) ? \CMSApplication::$VAR['submit'] : null;
$this->app->smarty->assign('stage', \CMSApplication::$VAR['stage']);

// Get 'stage' from the submit button
\CMSApplication::$VAR['stage'] = isset(\CMSApplication::$VAR['submit']) ? \CMSApplication::$VAR['submit'] : null;

// Delete Setup files Action
if(isset(\CMSApplication::$VAR['action']) && \CMSApplication::$VAR['action'] == 'delete_setup_folder' && $this->app->system->security->check_page_accessed_via_qwcrm('setup', 'install')) {
    $this->app->components->setup->deleteSetupFolder();
}

// Log message to setup log - only when starting the process - this start every page loads
$this->app->components->setup->writeRecordToSetupLog('install', _gettext("QWcrm installation has begun."));

// Database Connection
if(!isset(\CMSApplication::$VAR['stage']) || \CMSApplication::$VAR['stage'] == 'database_connection') {
    
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_connection') {
        
        // Test the supplied database connection details and store details if successful
        if($this->app->components->setup->checkDatabaseConnectionDetailsValid(\CMSApplication::$VAR['qwcrm_config']['db_host'], \CMSApplication::$VAR['qwcrm_config']['db_user'], \CMSApplication::$VAR['qwcrm_config']['db_pass'], \CMSApplication::$VAR['qwcrm_config']['db_name'])) {
            
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Database connection successful."));
            
            // Create Configuration File
            $this->app->components->setup->createConfigFileFromDefault(SETUP_DIR.'install/install_configuration.php');
            
            // Load the configuration file into the registry            
            $this->app->components->administrator->refreshQwcrmConfig();
            
            $this->app->components->administrator->updateQwcrmConfigSettingsFile(\CMSApplication::$VAR['qwcrm_config']);           
            $this->app->components->setup->writeRecordToSetupLog('install', _gettext("Connected successfully to the database with the supplied credentials and added them to the config file."));  
            \CMSApplication::$VAR['stage'] = 'config_settings';
        
        // Load the page - Error message done by verify_database_connection_details();
        } else {
            
            // reload the database connection page with the entered values and error message
            $this->app->smarty->assign('qwcrm_config', \CMSApplication::$VAR['qwcrm_config']);                       
            $this->app->components->setup->writeRecordToSetupLog('install', _gettext("Failed to connect to the database with the supplied credentials.")); 
            $this->app->smarty->assign('stage', 'database_connection');             
            
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
        
        $this->app->smarty->assign('qwcrm_config', $qwcrm_config);
        $this->app->smarty->assign('stage', 'database_connection');  
        
    }
    
}


// Database Prefix (and other Config Settings)
if(\CMSApplication::$VAR['stage'] == 'config_settings') {    
    
    // submit the config settings and load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'config_settings') {
                 
        // Add other required varibles
        \CMSApplication::$VAR['qwcrm_config']['secret_key']          = \Joomla\CMS\User\UserHelper::genRandomPassword(32);
        
        $this->app->components->administrator->updateQwcrmConfigSettingsFile(\CMSApplication::$VAR['qwcrm_config']);
        $this->app->components->setup->writeRecordToSetupLog('install', _gettext("Config settings have been added to the config file."));
        \CMSApplication::$VAR['stage'] = 'database_install';
    
    // Load the page
    } else {
        
        \CMSApplication::$VAR['qwcrm_config']['db_prefix'] = $this->app->components->setup->generateDatabasePrefix();
    
        $this->app->smarty->assign('qwcrm_config', \CMSApplication::$VAR['qwcrm_config']);        
        $this->app->smarty->assign('stage', 'config_settings');
        
    }
    
}


// Install the database
if(\CMSApplication::$VAR['stage'] == 'database_install') {    
    
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_install') {
       
        $this->app->components->setup->writeRecordToSetupLog('install', _gettext("Starting Database installation."));
        
        // install the database file and load the next page
        if($this->app->components->setup->installDatabase(SETUP_DIR.'install/install_database.sql')) {
            
            $record = _gettext("The database installed successfully.");            
            $this->app->system->variables->systemMessagesWrite('success', $record); 
            $this->app->components->setup->writeRecordToSetupLog('install', $record);
            \CMSApplication::$VAR['stage'] = 'database_install_results';            
        
        // Load the page with the error message      
        } else {            
              
           $record = _gettext("The database failed to install.");                      
           $this->app->system->variables->systemMessagesWrite('danger', $record);
           $this->app->components->setup->writeRecordToSetupLog('install', $record);
           \CMSApplication::$VAR['stage'] = 'database_install_results';
           
        }        
    
    // Load the page
    } else {
        $this->app->smarty->assign('stage', 'database_install');        
    }
    
}


// Database Installation Results
if(\CMSApplication::$VAR['stage'] == 'database_install_results') { 

    // load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_install_results') {
        
        // Prefill Company Financial dates
        $this->app->components->setup->updateRecordValue(PRFX.'company_record', 'year_start', $this->app->system->general->mysql_date());
        $this->app->components->setup->updateRecordValue(PRFX.'company_record', 'year_end', $this->app->system->general->timestamp_mysql_date(strtotime('+1 year')));
        \CMSApplication::$VAR['stage'] = 'company_details';    
    
    // Load the page  
    } else {
        
        // Output Execution results to the screen
        $this->app->smarty->assign('executed_sql_results', Setup::$executed_sql_results);
        $this->app->smarty->assign('stage', 'database_install_results');
        
    }
    
}


// Company Details
if(\CMSApplication::$VAR['stage'] == 'company_details') {   
    
    // submit the company details and load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'company_details') {
        
        // Add missing information
        $company_details = $this->app->components->company->getRecord();
        \CMSApplication::$VAR['welcome_msg']             = $company_details['welcome_msg'];
        \CMSApplication::$VAR['email_signature']         = $company_details['email_signature'];
        \CMSApplication::$VAR['email_signature_active']  = $company_details['email_signature_active'];
        \CMSApplication::$VAR['email_msg_workorder']     = $company_details['email_msg_workorder'];
        \CMSApplication::$VAR['email_msg_invoice']       = $company_details['email_msg_invoice'];                
        
        // Set the date format required for $this->app->components->company->update_company_details()
        defined('DATE_FORMAT') ?: define('DATE_FORMAT', $this->app->components->company->getRecord('date_format'));
        
        // update company details and load next stage      
        $this->app->components->company->updateRecord(\CMSApplication::$VAR);
        $this->app->components->setup->writeRecordToSetupLog('install', _gettext("Company options inserted."));
        \CMSApplication::$VAR['stage'] = 'start_numbers';
        
    // Load the page    
    } else {
        
        // date format is not set in the javascript date picker because i am manipulating stages not pages
                
        $this->app->smarty->assign('company_details', $this->app->components->company->getRecord());
        // Only build the link if there is a logo set.
        if($this->app->components->company->getRecord('logo'))
        {
            $this->app->smarty->assign('company_logo', QW_MEDIA_DIR . $this->app->components->company->getRecord('logo') );
        } else {
            $this->app->smarty->assign('company_logo', '');
        }
        $this->app->smarty->assign('date_format', $this->app->components->company->getRecord('date_format'));
        $this->app->smarty->assign('date_formats', $this->app->system->general->get_date_formats());
        $this->app->smarty->assign('tax_systems', $this->app->components->company->getTaxSystems());
        $this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes(null, true) );
        $this->app->smarty->assign('stage', 'company_details');
        
    }
    
}


// Work Order and Invoice Start Numbers
if(\CMSApplication::$VAR['stage'] == 'start_numbers') {  
    
    // submit the workorder and invoice start numbers if supplied, then load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'start_numbers') {
        
        if(\CMSApplication::$VAR['workorder_start_number']) {
            $this->app->components->setup->setWorkorderStartNumber(\CMSApplication::$VAR['workorder_start_number']);
            $this->app->components->setup->writeRecordToSetupLog('install', _gettext("Starting Work Order number has been set to").' '.\CMSApplication::$VAR['workorder_start_number']);
        }
        
        if(\CMSApplication::$VAR['invoice_start_number']) {
            $this->app->components->setup->setInvoiceStartNumber(\CMSApplication::$VAR['invoice_start_number']);
            $this->app->components->setup->writeRecordToSetupLog('install', _gettext("Starting Invoice number has been set to").' '.\CMSApplication::$VAR['invoice_start_number']);
        }
        
        \CMSApplication::$VAR['stage'] = 'administrator_account';
    
    // Load the page
    } else {
        $this->app->smarty->assign('stage', 'start_numbers');
    }
        
}


// Create an Administrator account
if(\CMSApplication::$VAR['stage'] == 'administrator_account') {
    
    // create the administrator and load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'administrator_account') {
       
        $this->app->components->user->insertRecord(\CMSApplication::$VAR);
        $this->app->components->setup->writeRecordToSetupLog('install', _gettext("The administrator account has been created."));
        $this->app->components->setup->writeRecordToSetupLog('install', _gettext("The QWcrm installation process has completed successfully."));
        $this->app->system->variables->systemMessagesWrite('success', _gettext("The QWcrm installation process has completed successfully."));
        \CMSApplication::$VAR['stage'] = 'delete_setup_folder';        
    
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
        $this->app->smarty->assign('date_format', $this->app->components->company->getRecord('date_format'));
        $this->app->smarty->assign('user_details', $user_details); 
        $this->app->smarty->assign('user_locations', $this->app->components->user->getLocations());           
        $this->app->smarty->assign('stage', 'administrator_account');
        
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
        $this->app->components->setup->writeRecordToSetupLog('upgrade', _gettext("QWcrm installation has finished."));
        
        // Clean up after setup process 
        $this->app->components->setup->setupFinished();
        
        // Set mandatory default values               
        $this->app->smarty->assign('stage', 'delete_setup_folder');
        
    }
    
}