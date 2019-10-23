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
require(SETUP_DIR.'migrate/myitcrm/migrate_routines.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup', 'migrate', 'index_allowed')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Prevent undefined variable errors && Get 'stage' from the submit button
\QFactory::$VAR['stage'] = isset(\QFactory::$VAR['submit']) ? \QFactory::$VAR['submit'] : null;
$smarty->assign('stage', \QFactory::$VAR['stage']);


####################################
#        MyITCRM Migration         #
####################################

// Create a Setup Object
$MigrateMyitcrm = new MigrateMyitcrm(\QFactory::$VAR);

// Database Connection (QWcrm)
if(!isset(\QFactory::$VAR['stage']) || \QFactory::$VAR['stage'] == 'database_connection_qwcrm') {    
    
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_connection_qwcrm') {
        
        // test the supplied database connection details
        if($MigrateMyitcrm->verify_database_connection_details(\QFactory::$VAR['qwcrm_config']['db_host'], \QFactory::$VAR['qwcrm_config']['db_user'], \QFactory::$VAR['qwcrm_config']['db_pass'], \QFactory::$VAR['qwcrm_config']['db_name'])) {
            
            systemMessagesWrite('success', _gettext("Database connection successful."));
            $MigrateMyitcrm->create_config_file_from_default(SETUP_DIR.'migrate/myitcrm/migrate_configuration.php');
            
            // Load the Config settings from the new configuration.php
            get_qwcrm_config_settings();
            
            // Update the Database Credentials
            update_qwcrm_config_setting('db_host', \QFactory::$VAR['qwcrm_config']['db_host']);
            update_qwcrm_config_setting('db_user', \QFactory::$VAR['qwcrm_config']['db_user']);
            update_qwcrm_config_setting('db_pass', \QFactory::$VAR['qwcrm_config']['db_pass']);
            update_qwcrm_config_setting('db_name', \QFactory::$VAR['qwcrm_config']['db_name']);
            
            $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("Connected successfully to the database with the supplied credentials and added them to the config file."));  
            \QFactory::$VAR['stage'] = 'database_connection_myitcrm';
            
        // Load the page - Error message done by verify_database_connection_details();
        } else {
            
            // reload the database connection page with the details and error message
            $smarty->assign('qwcrm_config', \QFactory::$VAR['qwcrm_config']);                      
            $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("Failed to connect to the database with the supplied credentials."));
            $smarty->assign('stage', 'database_connection_qwcrm');  
            
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
        $smarty->assign('stage', 'database_connection_qwcrm');
        
        // Log message to setup log - only when starting the process
        $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("QWcrm migration from MyITCRM has begun."));
        
    }
    
}


// Database Connection (MyITCRM)
if(\QFactory::$VAR['stage'] == 'database_connection_myitcrm') {
    
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_connection_myitcrm') {
        
        // Test the supplied database connection details
        if($MigrateMyitcrm->check_myitcrm_database_connection(\QFactory::$VAR['qwcrm_config']['myitcrm_prefix'])) {
            
            // Record details into the config file and display success message and load the next page       
            update_qwcrm_config_settings_file(\QFactory::$VAR['qwcrm_config']);           
            $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("Connected successfully to the MyITCRM database with the supplied prefix."));  
            systemMessagesWrite('success', _gettext("MyITCRM database connection successful."));
            \QFactory::$VAR['stage'] = 'config_settings';
        
        // Load the page with error
        } else {
            
            // Reload the database connection page with the details and error message
            $smarty->assign('qwcrm_config', \QFactory::$VAR['qwcrm_config']);
            systemMessagesWrite('danger', _gettext("The MyITCRM database is either missing or the prefix is wrong."));
            $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("Failed to connect to the MyITCRM database with the supplied prefix.")); 
            $smarty->assign('stage', 'database_connection_myitcrm');
            
        }        

    // Load the page
    } else {
        
        // Prevent undefined variable errors
        $qwcrm_config = array
                            (
                                'myitcrm_prefix' => null,
                            );
        
        $smarty->assign('qwcrm_config', $qwcrm_config);
        $smarty->assign('stage', 'database_connection_myitcrm');
    }
}


// Database Prefix (and other Config Settings)
if(\QFactory::$VAR['stage'] == 'config_settings') {    
    
    // submit the config settings and load the next page
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'config_settings') {
                 
        // Correct missing secret varibles
        \QFactory::$VAR['qwcrm_config']['session_name']        = \Joomla\CMS\User\UserHelper::genRandomPassword(16);
        \QFactory::$VAR['qwcrm_config']['secret_key']          = \Joomla\CMS\User\UserHelper::genRandomPassword(32);
        
        update_qwcrm_config_settings_file(\QFactory::$VAR['qwcrm_config']);
        $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("Config settings have been added to the config file."));
        \QFactory::$VAR['stage'] = 'database_install_qwcrm';
    
    // Load the page
    } else {
        
        \QFactory::$VAR['qwcrm_config']['db_prefix'] = $MigrateMyitcrm->generate_database_prefix();
    
        $smarty->assign('qwcrm_config', \QFactory::$VAR['qwcrm_config']);        
        $smarty->assign('stage', 'config_settings');
        
    }
    
}


// Install the database (QWcrm)
if(\QFactory::$VAR['stage'] == 'database_install_qwcrm') {    
    
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_install_qwcrm') {
        
        $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("Starting Database installation."));
        
        // install the database file and load the next page
        if($MigrateMyitcrm->install_database(SETUP_DIR.'migrate/myitcrm/migrate_database.sql')) {
            
            $record = _gettext("The database installed successfully.");
            systemMessagesWrite('success', $record);
            $MigrateMyitcrm->write_record_to_setup_log('migrate', $record);
            \QFactory::$VAR['stage'] = 'database_install_results_qwcrm';            
        
        // Load the page with the error message      
        } else {            
              
           $record = _gettext("The database failed to install.");                     
           systemMessagesWrite('danger', $record);
           $MigrateMyitcrm->write_record_to_setup_log('migrate', $record); 
           \QFactory::$VAR['stage'] = 'database_install_results_qwcrm';
           
        }
    
    // Load the page
    } else {
        $smarty->assign('stage', 'database_install_qwcrm');        
    }
    
}


// Database Installation Results (QWcrm)
if(\QFactory::$VAR['stage'] == 'database_install_results_qwcrm') {    

    // load the next page
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_install_results_qwcrm') {
        
        \QFactory::$VAR['stage'] = 'company_details';      
    
    // Load the page  
    } else {
        
        // Output Execution results to the screen
        $smarty->assign('executed_sql_results', QSetup::$executed_sql_results);        
        $smarty->assign('stage', 'database_install_results_qwcrm');
    }
    
}


// Company Details
if(\QFactory::$VAR['stage'] == 'company_details') {   
        
    // submit the company details and load the next page
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'company_details') {
                
        // Add missing information to the form submission
        $company_details = $MigrateMyitcrm->get_company_details();
        \QFactory::$VAR['welcome_msg']             = $company_details['welcome_msg'];
        \QFactory::$VAR['email_signature']         = $company_details['email_signature'];
        \QFactory::$VAR['email_signature_active']  = $company_details['email_signature_active'];
        \QFactory::$VAR['email_msg_workorder']     = $company_details['email_msg_workorder'];
        \QFactory::$VAR['email_msg_invoice']       = $company_details['email_msg_invoice'];
           
        // Set the date format required for update_company_details()
        defined('DATE_FORMAT') ?: define('DATE_FORMAT', $MigrateMyitcrm->get_company_details('date_format'));
        
        // update company details and load next stage
        $MigrateMyitcrm->update_company_details(\QFactory::$VAR);
        $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("Company options inserted."));
        \QFactory::$VAR['stage'] = 'database_migrate';
        
    // Load the page    
    } else {
        
        // date format is not set in the javascript date picker because i am manipulating stages not pages
        
        $company_details = $MigrateMyitcrm->get_company_details_merged();
        
        // Update the logo in the database with the merged value (this allows the logo to be displayed)
        $MigrateMyitcrm->update_record_value(PRFX.'company', 'logo', $company_details['logo']);
        
        // Assign Smarty variables
        $smarty->assign('company_details', $company_details);        
        $smarty->assign('company_logo', QW_MEDIA_DIR . $company_details['logo'] );
        $smarty->assign('date_format', $MigrateMyitcrm->get_company_details('date_format'));
        $smarty->assign('stage', 'company_details');             
        
    }
    
}


// Migrate the database (MyITCRM)
if(\QFactory::$VAR['stage'] == 'database_migrate') {    
    
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_migrate') {
        
        $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("Starting MyITCRM Database Migration."));
        
        $config = QFactory::getConfig();
        
        // install the database file and load the next page
        if($MigrateMyitcrm->migrate_myitcrm_database($config->get('db_prefix'), $config->get('myitcrm_prefix'))) {
            
            // remove MyITCRM prefix from the config file
            delete_qwcrm_config_setting('myitcrm_prefix');            
            
            $record = _gettext("The MyITCRM database migrated successfully.");            
            systemMessagesWrite('success', $record);
            $MigrateMyitcrm->write_record_to_setup_log('migrate', $record);            
            \QFactory::$VAR['stage'] = 'database_migrate_results';            
        
        // Load the page with the error message      
        } else {            
              
            systemMessagesWrite('danger', $record); 
            $record = _gettext("The MyITCRM database failed to migrate successfully.");           
            $MigrateMyitcrm->write_record_to_setup_log('migrate', $record);
            \QFactory::$VAR['stage'] = 'database_migrate_results';
           
        }
    
    // Load the page
    } else {
        $smarty->assign('stage', 'database_migrate');        
    }
    
}


// Database Migration Results (MyITCRM)
if(\QFactory::$VAR['stage'] == 'database_migrate_results') {    

    // load the next page
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'database_migrate_results') {
        
        \QFactory::$VAR['stage'] = 'administrator_account';        
    
    // Load the page  
    } else {
        
        // Output Execution results to the screen
        $smarty->assign('executed_sql_results' ,QSetup::$executed_sql_results);        
        $smarty->assign('stage', 'database_migrate_results');
    }
    
}


// Create an Administrator account
if(\QFactory::$VAR['stage'] == 'administrator_account') {
    
    // create the administrator and load the next page
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'administrator_account') {
                
        // Check if the username or email have been used (the extra variable is to ignore the users current username and email to prevent submission errors when only updating other values)
        if ($MigrateMyitcrm->check_user_username_exists(\QFactory::$VAR['username']) || $MigrateMyitcrm->check_user_email_exists(\QFactory::$VAR['email'])) {     

            // send the posted data back to smarty
            $user_details = \QFactory::$VAR;

            // Reload the page with the POST'ed data
            $smarty->assign('user_details', $user_details);        
            
            // Set mandatory default values
            $smarty->assign('date_format', $MigrateMyitcrm->get_company_details('date_format'));
            $smarty->assign('stage', 'administrator_account');

        } else {    

            // Insert user record (and return the new ID)
            $MigrateMyitcrm->insert_user(\QFactory::$VAR);

            $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("The administrator account has been created."));
            $MigrateMyitcrm->write_record_to_setup_log('migrate', _gettext("The QWcrm installation and MyITCRM migration process has completed successfully."));
            systemMessagesWrite('success', _gettext("The QWcrm installation and MyITCRM migration process has completed successfully."));
            \QFactory::$VAR['stage'] = 'upgrade_confirmation';        

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
        $smarty->assign('user_details', $user_details); 
    
        // Set mandatory default values
        $smarty->assign('date_format', $MigrateMyitcrm->get_company_details('date_format'));
        $smarty->assign('stage', 'administrator_account');
        
    }
    
}


// Upgrade Confirmation
if(\QFactory::$VAR['stage'] == 'upgrade_confirmation') {
    
    // There is not submit action on this stage
    if(isset(\QFactory::$VAR['submit']) && \QFactory::$VAR['submit'] == 'upgrade_confirmation') {
        
        //\QFactory::$VAR['stage'] = 'unknown';
   
    // Load the page
    } else {
        
        // Log message to setup log - only when starting the process - this start every page loads
        $MigrateMyitcrm->write_record_to_setup_log('upgrade', _gettext("MyITCRM to QWcrm migration has finished."));
    
        // Clean up after setup process 
        $MigrateMyitcrm->setup_finished();
        
        // Set mandatory default values               
        $smarty->assign('stage', 'upgrade_confirmation');
        
    }
    
}