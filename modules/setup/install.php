<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;


// I might nbeed to add a php timeout override for this and migrate

require(INCLUDES_DIR.'modules/administrator.php');
require(INCLUDES_DIR.'modules/company.php');
require(INCLUDES_DIR.'modules/setup.php');
require(INCLUDES_DIR.'modules/user.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup:install', 'setup') || QWCRM_SETUP != 'install') {
    die(gettext("No Direct Access Allowed"));
}

// Stage 1 - Database connection and test -->
if($VAR['stage'] == '1' || !isset($VAR['stage'])) {
    
    $smarty->assign('qwcrm_config', $VAR);
    
    if($VAR['submit'] == 'stage1') {
        
        if(check_database_connection($db, $VAR['db_host'], $VAR['db_user'], $VAR['db_pass'], $VAR['db_name'])) {
            
            // Record details into the config file (or temp-config-file)
            $smarty->assign('information_msg', gettext("Database connection successful."));
            
            // administrator:config.php function update_qwcrm_config($new_config)            
            submit_qwcrm_config_settings($VAR);            
            $VAR['stage'] = '2';
            
        } else {
            // reload the page with the details and error messgae
            $smarty->assign('warning_msg', gettext("There is a database connection issue. Check your settings."));
            $smarty->assign('stage', '1');
        }
        
    }
    
}

// Stage 2 - Main Config Settings
if($VAR['stage'] == '2') {    
        
    if($VAR['submit'] == 'stage2') {
        submit_qwcrm_config_settings($VAR);
        $VAR['stage'] = '3';
    } else {
        
        // Set mandatory default values
        if($VAR['google_server'] == '')         { $VAR['google_server'] = 'https://www.google.com/'; }
        if($VAR['session_lifetime'] == '')      { $VAR['session_lifetime'] = '15'; }
        if($VAR['cookie_lifetime'] == '')       { $VAR['cookie_lifetime'] = '60'; }
        if($VAR['cookie_token_length'] == '')   { $VAR['cookie_token_length'] = '16'; }
    
        $smarty->assign('qwcrm_config', $VAR);
        
        $smarty->assign('stage', '2');
        
    }
    
}

// Stage 3 - Install the database
if($VAR['stage'] == '3') {    
    
    if($VAR['submit'] == 'stage3') {
        
        // install the primary database file
        if(install_database($db)) {
            $smarty->assign('information_msg', gettext("The primary database installed successfully."));
            $VAR['stage'] = '4';
        } else {
            
           // Reload the page (stage 3) - useful for testing varibles           
           $smarty->assign('warning_msg', gettext("The primary database failed to install."));
           $smarty->assign('stage', '3');
           $VAR['stage'] = '3';
        }
        
    } else {
        $smarty->assign('stage', '3');
    }
}

// Stage 4 - Database Install Results
if($VAR['stage'] == '4') {    

    // after reading the results, click submit
    if($VAR['submit'] == 'stage4') {
        $VAR['stage'] = '5';
    
        
    } else {
        $smarty->assign('stage', '4');
    }
    
}


// Stage 5 - Company Details
if($VAR['stage'] == '5') {    
    
    if($VAR['submit'] == 'stage5') {  
        update_company_details($db, $VAR);
        $VAR['stage'] = '6';
    } else {
        $smarty->assign('stage', '5');
    }
}

// Stage 6 - Workorder/Invoice Start numbers
if($VAR['stage'] == '6') {    
    
    if($VAR['submit'] == 'stage6') {
        
        if($VAR['workorder_start_number'] != '') {
            set_workorder_start_number($db, $VAR['workorder_start_number']);
        }
        
        if($VAR['invoice_start_number'] != '') {
            set_invoice_start_number($db, $VAR['invoice_start_number']);
        }
        
        $VAR['stage'] = '7';
        
    } else {
        $smarty->assign('stage', '6');
    }
        
}

// Stage 7 - Create an administrator account
if($VAR['stage'] == '7') {
    
    if($VAR['submit'] == 'stage7') {    
        insert_user($db, $VAR);        
        //$VAR['stage'] = '8';
        force_page('user', 'login', 'information_msg='.gettext("Installation successful. Please login with the administrator account you just created."));
                
    } else {
    
        // Set mandatory default values
        $smarty->assign('is_employee', '1');    
        $smarty->assign('usergroups', get_usergroups($db, 'employees'));
        $smarty->assign('stage', '7');
    }
}

// Build the page
$BuildPage .= $smarty->fetch('setup/install.tpl');