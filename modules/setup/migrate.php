<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/administrator.php');
require(INCLUDES_DIR.'modules/company.php');
require(INCLUDES_DIR.'modules/setup.php');
//require(INCLUDES_DIR.'modules/user.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup:migrate', 'setup') || QWCRM_SETUP != 'install') {
    die(gettext("No Direct Access Allowed."));
}

// Log message to setup log - only when starting the process
if(!check_page_accessed_via_qwcrm('setup:migrate') ) {
    write_record_to_setup_log(gettext("QWcrm migration from MyITCRM has begun."), 'migrate');
}

// Stage 1 - Database Connection -->
if($VAR['stage'] == '1' || !isset($VAR['stage'])) {
    
    if($VAR['submit'] == 'stage1') {
        
        // test the supplied database connection details
        if(check_database_connection($db, $VAR['db_host'], $VAR['db_user'], $VAR['db_pass'], $VAR['db_name'])) {
            
            // Record details into the config file and display success message and load the next page       
            submit_qwcrm_config_settings($VAR);            
            write_record_to_setup_log(gettext("Connected successfully to the database with the supplied credentials and added them to the config file."), 'migrate');  
            $VAR['stage'] = '1a';
            $smarty->assign('information_msg', gettext("Database connection successful."));
        
        // load the page
        } else {
            
            // reload the database connection page with the details and error message
            $smarty->assign('qwcrm_config', $VAR);
            $smarty->assign('warning_msg', gettext("There is a database connection issue. Check your settings."));
            write_record_to_setup_log(gettext("Failed to connect to the database with the supplied credentials."), 'migrate'); 
            $smarty->assign('stage', '1');
            
        }
        
    }
    
}

// Stage 1a - Database Connection (MyITCRM) -->
if($VAR['stage'] == '1a') {
    
    if($VAR['submit'] == 'stage1a') {
        
        // test the supplied database connection details
        if(check_myitcrm_database_connection($db, $VAR['myitcrm_prefix'])) {
            
            // Record details into the config file and display success message and load the next page       
            submit_qwcrm_config_settings($VAR);            
            write_record_to_setup_log(gettext("Connected successfully to the MyITCRM database with the supplied credentials and added them to the config file."), 'migrate');  
            $VAR['stage'] = '2';
            $smarty->assign('information_msg', gettext("MyITCRM Database connection successful."));
        
        // load the page
        } else {
            
            // reload the database connection page with the details and error message
            $smarty->assign('qwcrm_config', $VAR);
            $smarty->assign('warning_msg', gettext("There is a database connection issue. Check your settings."));
            write_record_to_setup_log(gettext("Failed to connect to the MyITCRM database with the supplied credentials."), 'migrate'); 
            $smarty->assign('stage', '1a');
            
        }
        
    }
    
}

// Stage 2 - Config Settings
if($VAR['stage'] == '2') {    
    
    // submit the config settings and load the next page
    if($VAR['submit'] == 'stage2') {
        submit_qwcrm_config_settings($VAR);
        write_record_to_setup_log(gettext("Config settings have been added to the config file."), 'migrate');
        $VAR['stage'] = '3';
    
    // load the page
    } else {
        
        // Set mandatory default values
        if($VAR['google_server'] == '')         { $VAR['google_server'] = 'https://www.google.com/'; }
        if($VAR['session_lifetime'] == '')      { $VAR['session_lifetime'] = '15'; }
        if($VAR['cookie_lifetime'] == '')       { $VAR['cookie_lifetime'] = '60'; }
        if($VAR['cookie_token_length'] == '')   { $VAR['cookie_token_length'] = '16'; }
        
        // Prefill databse prefix with a random value
        $VAR['db_prefix'] = generate_database_prefix($VAR['myitcrm_prefix']);
    
        $smarty->assign('qwcrm_config', $VAR);        
        $smarty->assign('stage', '2');
        
    }
    
}

// Stage 3 - Install the database
if($VAR['stage'] == '3') {    
    
    if($VAR['submit'] == 'stage3') {
        
        write_record_to_setup_log(gettext("Starting Database installation."), 'migrate');
        
        // install the database file and load the next page
        if(install_database($db)) {
            
            $record = gettext("The database installed successfully.");
            write_record_to_setup_log($record, 'migrate');
            $smarty->assign('information_msg', $record);            
            $VAR['stage'] = '4';            
        
        // load the page with the error message      
        } else {            
              
           $record = gettext("The database failed to install.");           
           write_record_to_setup_log($record, 'migrate');           
           $smarty->assign('warning_msg', $record);
           $smarty->assign('failed', true);
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
        $smarty->assign('stage', '4');
    }
    
}

// Stage 5 - Company Details
if($VAR['stage'] == '5') {   
        
    // submit the company details and load the next page
    if($VAR['submit'] == 'stage5') {
        
        // upload_company details
        update_company_details($db, $VAR);
        write_record_to_setup_log(gettext("Company details inserted."), 'migrate');
        $VAR['stage'] = '6';
        
    // load the page    
    } else {
        
        $smarty->assign('date_format', get_myitcrm_company_details($db, 'COMPANY_DATE_FORMAT'));
        $smarty->assign('company_details', get_merged_company_details($db));
        $smarty->assign('stage', '5');
        
    }
    
}

// Stage 6 - Migrate the database (MyITCRM)
if($VAR['stage'] == '6') {    
    
    if($VAR['submit'] == 'stage6') {
        
        write_record_to_setup_log(gettext("Starting MyITCRM Database Migration."), 'migrate');
        
        // install the database file and load the next page
        if(migrate_database($db)) {
            
            $record = gettext("The MyITCRM database migrated successfully.");
            write_record_to_setup_log($record, 'migrate');
            $smarty->assign('information_msg', $record);            
            $VAR['stage'] = '7';            
        
        // load the page with the error message      
        } else {            
              
           $record = gettext("The MyITCRM database failed to migrate.");           
           write_record_to_setup_log($record, 'migrate');           
           $smarty->assign('warning_msg', $record);
           $smarty->assign('failed', true);
           $VAR['stage'] = '7';
           
        }
    
    // load the page
    } else {
        $smarty->assign('stage', '6');        
    }
    
}

// Stage 7 - Database Migration Results (MyITCRM)
if($VAR['stage'] == '7') {    

    // load the next page
    if($VAR['submit'] == 'stage7') {
        $VAR['stage'] = '8';    
    
    // load the page  
    } else {
        $smarty->assign('stage', '7');
    }
    
}

// Stage 8 - Final page
if($VAR['stage'] == '8') {
    
    // create the administrator and load the next page
    if($VAR['submit'] == 'stage8') {  
       
        write_record_to_setup_log(gettext("The MyITCRM migration and QWcrm installation process has completed successfully."), 'migrate');
        //$VAR['stage'] = '9';
        
        force_page('user', 'login', 'setup=finished&information_msg='.gettext("MyITCRM migration successful. Please login with your old administrator. All users will need to reset their passwords in order to login."), 'get');        
        exit;
    
    // load the page (not required)
    } else {
    
        //$smarty->assign('stage', '8');
        
    }
    
}




// Build the page
$BuildPage .= $smarty->fetch('setup/migrate.tpl');