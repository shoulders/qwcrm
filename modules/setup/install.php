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
require(INCLUDES_DIR.'modules/user.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup:install', 'setup') || QWCRM_SETUP != 'install') {
    die(gettext("No Direct Access Allowed"));
}

// Stage 1 - Database Connection -->
if($VAR['stage'] == '1' || !isset($VAR['stage'])) {
    
    if($VAR['submit'] == 'stage1') {
        
        // test the supplied database connection details
        if(check_database_connection($db, $VAR['db_host'], $VAR['db_user'], $VAR['db_pass'], $VAR['db_name'])) {
            
            // Record details into the config file and display success message and load the next page       
            submit_qwcrm_config_settings($VAR);            
            $VAR['stage'] = '2';
            $smarty->assign('information_msg', gettext("Database connection successful."));
        
        // load the page
        } else {
            
            // reload the database connection page with the details and error message
            $smarty->assign('qwcrm_config', $VAR);
            $smarty->assign('warning_msg', gettext("There is a database connection issue. Check your settings."));
            $smarty->assign('stage', '1');
            
        }
        
    }
    
}

// Stage 2 - Config Settings
if($VAR['stage'] == '2') {    
    
    // submit the config settings and load the next page
    if($VAR['submit'] == 'stage2') {
        submit_qwcrm_config_settings($VAR);
        $VAR['stage'] = '3';
    
    // load the page
    } else {
        
        // Set mandatory default values
        if($VAR['google_server'] == '')         { $VAR['google_server'] = 'https://www.google.com/'; }
        if($VAR['session_lifetime'] == '')      { $VAR['session_lifetime'] = '15'; }
        if($VAR['cookie_lifetime'] == '')       { $VAR['cookie_lifetime'] = '60'; }
        if($VAR['cookie_token_length'] == '')   { $VAR['cookie_token_length'] = '16'; }
        
        // Prefill databse prefix with a random value
        $VAR['db_prefix'] = generate_database_prefix();
    
        $smarty->assign('qwcrm_config', $VAR);        
        $smarty->assign('stage', '2');
        
    }
    
}

// Stage 3 - Install the database
if($VAR['stage'] == '3') {    
    
    if($VAR['submit'] == 'stage3') {
        
        // install the primary database file and load the next page
        if(install_database($db)) {
            
            $smarty->assign('information_msg', gettext("The primary database installed successfully."));
            $VAR['stage'] = '4';            
        
        // load the page with the error message      
        } else {            
              
           $smarty->assign('warning_msg', gettext("The primary database failed to install."));           
           $VAR['stage'] = '4';
           $smarty->assign('failed', true);
           
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
        
        //upload_company_logo($db);
        update_company_details($db, $VAR);
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
        }
        
        if($VAR['invoice_start_number'] != '') {
            set_invoice_start_number($db, $VAR['invoice_start_number']);
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
        //$VAR['stage'] = '8';
        
        force_page('user', 'login', 'setup=finished&information_msg='.gettext("Installation successful. Please login with the administrator account you just created."), 'get');        
        exit;
    
    // load thge page
    } else {
    
        // Set mandatory default values
        $smarty->assign('is_employee', '1');    
        $smarty->assign('usergroups', get_usergroups($db, 'employees'));
        $smarty->assign('stage', '7');
        
    }
    
}

// Build the page
$BuildPage .= $smarty->fetch('setup/install.tpl');