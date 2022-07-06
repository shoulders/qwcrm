<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(SETUP_DIR.'migrate/myitcrm/migrate_routines.php');

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('setup', 'migrate', 'index_allowed')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Prevent undefined variable errors && Get 'stage' from the submit button
\CMSApplication::$VAR['stage'] = \CMSApplication::$VAR['submit'] ?? null;
$this->app->smarty->assign('stage', \CMSApplication::$VAR['stage']);

####################################
#        MyITCRM Migration         #
####################################

// Create a Setup Object
$MigrateMyitcrm = new MigrateMyitcrm();

// Database Connection (QWcrm)
if(!isset(\CMSApplication::$VAR['stage']) || \CMSApplication::$VAR['stage'] == 'database_connection_qwcrm') {    
    
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_connection_qwcrm') {
        
        // test the supplied database connection details
        if($MigrateMyitcrm->checkDatabaseConnectionDetailsValid(\CMSApplication::$VAR['qwcrm_config']['db_host'], \CMSApplication::$VAR['qwcrm_config']['db_user'], \CMSApplication::$VAR['qwcrm_config']['db_pass'], \CMSApplication::$VAR['qwcrm_config']['db_name'])) {
            
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Database connection successful."));
            
            // Create Configuration File
            $MigrateMyitcrm->createConfigFileFromDefault(SETUP_DIR.'migrate/myitcrm/migrate_configuration.php');
            
            // Load the configuration file into the registry            
            $this->app->components->administrator->refreshQwcrmConfig();
            
            // Update the Database Credentials
            $this->app->components->administrator->updateQwcrmConfigSetting('db_host', \CMSApplication::$VAR['qwcrm_config']['db_host']);
            $this->app->components->administrator->updateQwcrmConfigSetting('db_user', \CMSApplication::$VAR['qwcrm_config']['db_user']);
            $this->app->components->administrator->updateQwcrmConfigSetting('db_pass', \CMSApplication::$VAR['qwcrm_config']['db_pass']);
            $this->app->components->administrator->updateQwcrmConfigSetting('db_name', \CMSApplication::$VAR['qwcrm_config']['db_name']);
            
            $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("Connected successfully to the database with the supplied credentials and added them to the config file."));  
            \CMSApplication::$VAR['stage'] = 'database_connection_myitcrm';
            
        // Load the page - Error message done by verify_database_connection_details();
        } else {
            
            // reload the database connection page with the details and error message
            $this->app->smarty->assign('qwcrm_config', \CMSApplication::$VAR['qwcrm_config']);            
            $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("Failed to connect to the database with the supplied credentials."));
            $this->app->smarty->assign('stage', 'database_connection_qwcrm');  
            
            
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
        $this->app->smarty->assign('stage', 'database_connection_qwcrm');
        
        // Log message to setup log - only when starting the process
        $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("QWcrm migration from MyITCRM has begun."));
        
    }
    
}


// Database Connection (MyITCRM)
if(\CMSApplication::$VAR['stage'] == 'database_connection_myitcrm') {
    
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_connection_myitcrm') {
        
        // Test the supplied database connection details
        if($MigrateMyitcrm->checkMyitcrmDatabaseConnection(\CMSApplication::$VAR['qwcrm_config']['myitcrm_prefix'])) {
            
            // Record details into the config file and display success message and load the next page       
            $this->app->components->administrator->updateQwcrmConfigSettingsFile(\CMSApplication::$VAR['qwcrm_config']);           
            $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("Connected successfully to the MyITCRM database with the supplied prefix."));  
            $this->app->system->variables->systemMessagesWrite('success', _gettext("MyITCRM database connection successful."));
            \CMSApplication::$VAR['stage'] = 'config_settings';
        
        // Load the page with error
        } else {
            
            // Reload the database connection page with the details and error message
            $this->app->smarty->assign('qwcrm_config', \CMSApplication::$VAR['qwcrm_config']);
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The MyITCRM database is either missing or the prefix is wrong."));
            $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("Failed to connect to the MyITCRM database with the supplied prefix.")); 
            $this->app->smarty->assign('stage', 'database_connection_myitcrm');
            
        }        

    // Load the page
    } else {
        
        // Prevent undefined variable errors
        $qwcrm_config = array
                            (
                                'myitcrm_prefix' => null,
                            );
        
        $this->app->smarty->assign('qwcrm_config', $qwcrm_config);
        $this->app->smarty->assign('stage', 'database_connection_myitcrm');
    }
}


// Database Prefix (and other Config Settings)
if(\CMSApplication::$VAR['stage'] == 'config_settings') {    
    
    // submit the config settings and load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'config_settings') {
                 
        // Correct missing secret varibles
        \CMSApplication::$VAR['qwcrm_config']['session_name']        = \Joomla\CMS\User\UserHelper::genRandomPassword(16);
        \CMSApplication::$VAR['qwcrm_config']['secret_key']          = \Joomla\CMS\User\UserHelper::genRandomPassword(32);
        
        $this->app->components->administrator->updateQwcrmConfigSettingsFile(\CMSApplication::$VAR['qwcrm_config']);
        $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("Config settings have been added to the config file."));
        \CMSApplication::$VAR['stage'] = 'database_install_qwcrm';
    
    // Load the page
    } else {
        
        \CMSApplication::$VAR['qwcrm_config']['db_prefix'] = $this->app->components->setup->generateDatabasePrefix();
    
        $this->app->smarty->assign('qwcrm_config', \CMSApplication::$VAR['qwcrm_config']);        
        $this->app->smarty->assign('stage', 'config_settings');
        
    }
    
}


// Install the database (QWcrm)
if(\CMSApplication::$VAR['stage'] == 'database_install_qwcrm') {    
    
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_install_qwcrm') {
        
        $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("Starting Database installation."));
        
        // install the database file and load the next page
        if($this->app->components->setup->installDatabase(SETUP_DIR.'migrate/myitcrm/migrate_database.sql')) {
            
            $record = _gettext("The database installed successfully.");
            $this->app->system->variables->systemMessagesWrite('success', $record);
            $this->app->components->setup->writeRecordToSetupLog('migrate', $record);
            \CMSApplication::$VAR['stage'] = 'database_install_results_qwcrm';            
        
        // Load the page with the error message      
        } else {            
              
           $record = _gettext("The database failed to install.");                     
           $this->app->system->variables->systemMessagesWrite('danger', $record);
           $this->app->components->setup->writeRecordToSetupLog('migrate', $record); 
           \CMSApplication::$VAR['stage'] = 'database_install_results_qwcrm';
           
        }
    
    // Load the page
    } else {
        $this->app->smarty->assign('stage', 'database_install_qwcrm');        
    }
    
}


// Database Installation Results (QWcrm)
if(\CMSApplication::$VAR['stage'] == 'database_install_results_qwcrm') {    

    // load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_install_results_qwcrm') {
        
        \CMSApplication::$VAR['stage'] = 'company_details';      
    
    // Load the page  
    } else {
        
        // Output Execution results to the screen
        $this->app->smarty->assign('executed_sql_results', Setup::$executed_sql_results);        
        $this->app->smarty->assign('stage', 'database_install_results_qwcrm');
    }
    
}


// Company Details
if(\CMSApplication::$VAR['stage'] == 'company_details') {   
        
    // submit the company details and load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'company_details') {
                
        // Add missing information to the form submission
        $company_details = $MigrateMyitcrm->getCompanyDetails();
        \CMSApplication::$VAR['welcome_msg']             = $company_details['welcome_msg'];
        \CMSApplication::$VAR['email_signature']         = $company_details['email_signature'];
        \CMSApplication::$VAR['email_signature_active']  = $company_details['email_signature_active'];
        \CMSApplication::$VAR['email_msg_workorder']     = $company_details['email_msg_workorder'];
        \CMSApplication::$VAR['email_msg_invoice']       = $company_details['email_msg_invoice'];
           
        // Set the date format required for $this->app->components->company->update_company_details()
        defined('DATE_FORMAT') ?: define('DATE_FORMAT', $MigrateMyitcrm->getCompanyDetails('date_format'));
        
        // update company details and load next stage
        $MigrateMyitcrm->updateCompanyDetails(\CMSApplication::$VAR);
        $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("Company options inserted."));
        \CMSApplication::$VAR['stage'] = 'database_migrate';
        
    // Load the page    
    } else {
        
        // date format is not set in the javascript date picker because i am manipulating stages not pages
        
        $company_details = $MigrateMyitcrm->getCompanyDetailsMerged();
        
        // Update the logo in the database with the merged value (this allows the logo to be displayed)
        $this->app->components->setup->updateRecordValue(PRFX.'company', 'logo', $company_details['logo']);
        
        // Assign Smarty variables
        $this->app->smarty->assign('company_details', $company_details);        
        $this->app->smarty->assign('company_logo', QW_MEDIA_DIR . $company_details['logo'] );
        $this->app->smarty->assign('date_format', $MigrateMyitcrm->getCompanyDetails('date_format'));
        $this->app->smarty->assign('stage', 'company_details');             
        
    }
    
}


// Migrate the database (MyITCRM)
if(\CMSApplication::$VAR['stage'] == 'database_migrate') {    
    
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_migrate') {
        
        $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("Starting MyITCRM Database Migration."));
                
        // install the database file and load the next page
        if($MigrateMyitcrm->migrateMyitcrmDatabase($this->app->config->get('db_prefix'), $this->app->config->get('myitcrm_prefix'))) {
            
            // remove MyITCRM prefix from the config file
            $this->app->components->administrator->deleteQwcrmConfigSetting('myitcrm_prefix');            
            
            $record = _gettext("The MyITCRM database migrated successfully.");            
            $this->app->system->variables->systemMessagesWrite('success', $record);
            $this->app->components->setup->writeRecordToSetupLog('migrate', $record);            
            \CMSApplication::$VAR['stage'] = 'database_migrate_results';            
        
        // Load the page with the error message      
        } else {            
              
            $this->app->system->variables->systemMessagesWrite('danger', $record); 
            $record = _gettext("The MyITCRM database failed to migrate successfully.");           
            $this->app->components->setup->writeRecordToSetupLog('migrate', $record);
            \CMSApplication::$VAR['stage'] = 'database_migrate_results';
           
        }
    
    // Load the page
    } else {
        $this->app->smarty->assign('stage', 'database_migrate');        
    }
    
}


// Database Migration Results (MyITCRM)
if(\CMSApplication::$VAR['stage'] == 'database_migrate_results') {    

    // load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'database_migrate_results') {
        
        \CMSApplication::$VAR['stage'] = 'administrator_account';        
    
    // Load the page  
    } else {
        
        // Output Execution results to the screen
        $this->app->smarty->assign('executed_sql_results' ,Setup::$executed_sql_results);        
        $this->app->smarty->assign('stage', 'database_migrate_results');
    }
    
}


// Create an Administrator account
if(\CMSApplication::$VAR['stage'] == 'administrator_account') {
    
    // create the administrator and load the next page
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'administrator_account') {
                
        // Check if the username or email have been used (the extra variable is to ignore the users current username and email to prevent submission errors when only updating other values)
        if ($MigrateMyitcrm->checkUsernameExists(\CMSApplication::$VAR['username']) || $MigrateMyitcrm->checkUserEmailExists(\CMSApplication::$VAR['email'])) {     

            // send the posted data back to smarty
            $user_details = \CMSApplication::$VAR;

            // Reload the page with the POST'ed data
            $this->app->smarty->assign('user_details', $user_details);        
            
            // Set mandatory default values
            $this->app->smarty->assign('date_format', $MigrateMyitcrm->getCompanyDetails('date_format'));
            $this->app->smarty->assign('stage', 'administrator_account');

        } else {    

            // Insert user record (and return the new ID)
            $MigrateMyitcrm->insertUser(\CMSApplication::$VAR);

            $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("The administrator account has been created."));
            $this->app->components->setup->writeRecordToSetupLog('migrate', _gettext("The QWcrm installation and MyITCRM migration process has completed successfully."));
            $this->app->system->variables->systemMessagesWrite('success', _gettext("The QWcrm installation and MyITCRM migration process has completed successfully."));
            \CMSApplication::$VAR['stage'] = 'upgrade_confirmation';        

        }
        
    // Load the page
    } else {
        
        // Prevent undefined variable errors
        $user_details = array
                            (
                                'display_name' => null,
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
                                'notes' => null        
                            );        
        $this->app->smarty->assign('user_details', $user_details); 
    
        // Set mandatory default values
        $this->app->smarty->assign('date_format', $MigrateMyitcrm->getCompanyDetails('date_format'));
        $this->app->smarty->assign('stage', 'administrator_account');
        
    }
    
}


// Upgrade Confirmation
if(\CMSApplication::$VAR['stage'] == 'upgrade_confirmation') {
    
    // There is not submit action on this stage
    if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'upgrade_confirmation') {
        
        //\CMSApplication::$VAR['stage'] = 'unknown';
   
    // Load the page
    } else {
        
        // Log message to setup log - only when starting the process - this start every page loads
        $this->app->components->setup->writeRecordToSetupLog('upgrade', _gettext("MyITCRM to QWcrm migration has finished."));
    
        // Clean up after setup process 
        $this->app->components->setup->setupFinished();
        
        // Set mandatory default values               
        $this->app->smarty->assign('stage', 'upgrade_confirmation');
        
    }
    
}