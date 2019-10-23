<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

################################################
#  Load and set the System's global variables  #
################################################

function load_system_variables() {
    
    $smarty = \QFactory::getSmarty();
    
    if(!defined('QWCRM_SETUP')) {
        $company_details = get_company_details();
    }

    /* Acquire variables from classes examples */
    // $user->login_user_id;                                    // This is a public variable defined in the class
    // \QFactory::getUser()->login_user_id;                      // Static method to get variable
    // \QFactory::getConfig()->get('sef')                        // This is a variable stored in the registry
    // $config = \QFactory::getConfig();  | $config->get('sef')  // Get the config into a variable and then you can call the config settings
    // $QConfig->sef                                            // Only works for the QConfig class I made in the root

    ##########################################################
    #   Assign Global PHP Variables                          #
    ##########################################################
   
    // If there are DATABASE ERRORS, they will present here (white screen) when verify QWcrm function is not on
    if(!defined('QWCRM_SETUP')) {
        define('DATE_FORMAT',   $company_details['date_format']);
        define('QW_TAX_SYSTEM', $company_details['tax_system'] );
    }

    ##########################################################################
    #   Assign variables into Smarty for use by component templates          #
    ##########################################################################

    // QWcrm System Folders
    $smarty->assign('base_path',                QWCRM_BASE_PATH             );      // set base path, useful for javascript links i.e. 404.tpl
    $smarty->assign('media_dir',                QW_MEDIA_DIR                );      // set media directory

    // QWcrm Theme Directory Template Variables
    $smarty->assign('theme_dir',                THEME_DIR                   );      // set theme directory
    $smarty->assign('theme_images_dir',         THEME_IMAGES_DIR            );      // set theme images directory
    $smarty->assign('theme_css_dir',            THEME_CSS_DIR               );      // set theme CSS directory
    $smarty->assign('theme_js_dir',             THEME_JS_DIR                );      // set theme JS directory

    // QWcrm Theme Directory Template Smarty File Include Path Variables
    $smarty->assign('theme_js_dir_finc',        THEME_JS_DIR_FINC           );
    
    // This assigns framework globals to smarty and also prevents undefined variable errors (mainly for the menu)
    isset(\QFactory::$VAR['user_id'])          ? $smarty->assign('user_id', \QFactory::$VAR['user_id'])               : $smarty->assign('user_id', null);
    isset(\QFactory::$VAR['employee_id'])      ? $smarty->assign('employee_id', \QFactory::$VAR['employee_id'])       : $smarty->assign('employee_id', null);
    isset(\QFactory::$VAR['client_id'])        ? $smarty->assign('client_id', \QFactory::$VAR['client_id'])           : $smarty->assign('client_id', null);
    isset(\QFactory::$VAR['workorder_id'])     ? $smarty->assign('workorder_id', \QFactory::$VAR['workorder_id'])     : $smarty->assign('workorder_id', null);
    isset(\QFactory::$VAR['schedule_id'])      ? $smarty->assign('schedule_id', \QFactory::$VAR['schedule_id'])       : $smarty->assign('schedule_id', null);
    isset(\QFactory::$VAR['invoice_id'])       ? $smarty->assign('invoice_id', \QFactory::$VAR['invoice_id'])         : $smarty->assign('invoice_id', null);
    isset(\QFactory::$VAR['voucher_id'])       ? $smarty->assign('voucher_id', \QFactory::$VAR['voucher_id'])         : $smarty->assign('voucher_id', null); 
    isset(\QFactory::$VAR['payment_id'])       ? $smarty->assign('payment_id', \QFactory::$VAR['payment_id'])         : $smarty->assign('payment_id', null);
    isset(\QFactory::$VAR['refund_id'])        ? $smarty->assign('refund_id', \QFactory::$VAR['refund_id'])           : $smarty->assign('refund_id', null);
    isset(\QFactory::$VAR['expense_id'])       ? $smarty->assign('expense_id', \QFactory::$VAR['expense_id'])         : $smarty->assign('expense_id', null);    
    isset(\QFactory::$VAR['otherincome_id'])   ? $smarty->assign('otherincome_id', \QFactory::$VAR['otherincome_id']) : $smarty->assign('otherincome_id', null);      
    isset(\QFactory::$VAR['supplier_id'])      ? $smarty->assign('supplier_id', \QFactory::$VAR['supplier_id'])       : $smarty->assign('supplier_id', null);    
   
    // Used throughout the site
    if(!defined('QWCRM_SETUP')) {
        $smarty->assign('currency_sym',  $company_details['currency_symbol']     );
        $smarty->assign('company_logo',  QW_MEDIA_DIR . $company_details['logo'] );
        $smarty->assign('qw_tax_system', QW_TAX_SYSTEM                           ); 
        $smarty->assign('date_format',   DATE_FORMAT                             );
    }
    
    #############################
    #        Exit Function      #
    #############################
    
    return;
    
}

######################################
#  System Messages                   #  // This function will take any messages from \QFactory::$VAR and put them into \QFactory::$messages
######################################  // This has all of the bootstrap message types here

// Build the Sysmte Messages Store
function systemMessagesBuildStore() {
    
    // Build the array in the correct order (for display purposes)
    $types = array('primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark');
     
    // Loop through the different types or system message    
    foreach ($types as $type) {
        
        // Add this Message Type to the global System Message Store
        \QFactory::$messages[$type] = array();
        
        // Check for this message type in \QFactory::$VAR and set to system message store
        if(isset(\QFactory::$VAR['msg_'.$type])) {
            \QFactory::$messages[$type][] = strip_tags(\QFactory::$VAR['msg_'.$type], '<br>');
            unset(\QFactory::$VAR['msg_'.$type]);
        }     
        
    }
    
    return;
    
}

function systemMessagesWrite($type, $message) {
    
    \QFactory::$messages[$type] = [$message];
    
}


// Return the Messages Store
function systemMessagesReturnStore() {
    
    // Remove all empty message type holders
    \QFactory::$messages = array_filter(\QFactory::$messages);
    
    // HTML holder
    $html = '';    
    
    // Loop through the different types of message and build HTML
    foreach (\QFactory::$messages as $messageStoreType => $messages) {
        
        foreach ($messages as $message) {
            
            $html .= "<div class=\"alert alert-$messageStoreType\" role=\"alert\">$message</div>\n";
            
        }        
        
    }
    
    return $html;
    
}

// This will parse the page payload and add the system messages, (only works on an empty HTML `system_messages` div)
function systemMessagesParsePage(&$pagePayload) {    
    
    if($systemMessageStore = systemMessagesReturnStore()) {
        $search = '<div id="system_messages" style="display: none;"></div>';
        $replace = "<div id=\"system_messages\">\n".$systemMessageStore."</div>\n";
        $count = (int)1;
        $pagePayload = str_replace($search, $replace, $pagePayload, $count);
    }
    
}

#####################################
#  Set the User's Smarty Variables  #  // Empty if not logged in or installing (except for usergroup)
#####################################

function smarty_set_user_variables() {
    
    $smarty = \QFactory::getSmarty();
    
    if(!defined('QWCRM_SETUP')) {
    
        $user = \QFactory::getUser();    
    
        $smarty->assign('login_user_id',            $user->login_user_id          );
        $smarty->assign('login_username',           $user->login_username         );
        $smarty->assign('login_usergroup_id',       $user->login_usergroup_id     );
        $smarty->assign('login_display_name',       $user->login_display_name     );
        $smarty->assign('login_token',              $user->login_token            );
        $smarty->assign('login_is_employee',        $user->login_is_employee      );
        $smarty->assign('login_client_id',          $user->login_client_id        );
    
    }
    
    return;
    
}


###########################################
#  POST Emulation - for server to server  #  // Might only work for logged in users, need to check, but fails on logout because session data is destroyed?
###########################################

/*
 * this writes into the session registry/$data
 * the register_shutdown_function() in native.php registers the function save() to be run as the last thing run by the script
 * $post_emulation_variable is created in the session registry.
 * It does work but i cannot control if the post varibles stay in the database store. Is this correct???
 * There is a timer to prevent abuse of this emulation and to keep messages valid. It is set to 5 seconds.
 */

// This writes to the $post_emulation_varible and then the varible to the store
function postEmulationWrite($key, $value) {
    
    // Refresh the store timer to keep it fresh
    \QFactory::getSession()->set('post_emulation_timer', time());
    
    // Set the varible in the $post_emulation_store variable
    \QFactory::getSession()->post_emulation_store[$key] = $value;
    
    // Save the whole $post_emulation_store varible into the registry (does this for every variable write)
    \QFactory::getSession()->set('post_emulation_store', \QFactory::getSession()->post_emulation_store);
    
}

// This reads the data from $post_emulation_varible
function postEmulationRead($key) {
    
    // Refresh the store timer to keep it fresh
    \QFactory::getSession()->set('post_emulation_timer', time());
    
    // Read a varible from the store and return it
    return \QFactory::getSession()->post_emulation_store[$key];
    
}

function postEmulationReturnStore($keep_store = false) {
    
    // Make temporary copy of the post store
    $post_store = \QFactory::getSession()->get('post_emulation_store');
    
    // Delete Stale Post Store - make sure the store is not an old one by putting a time limit on the validity
    if(time() - \QFactory::getSession()->get('post_emulation_timer') > 5 ) {        
        
        // Empty the registry store -  but keep it as an array
        \QFactory::getSession()->set('post_emulation_store', array());
        
        // Empty the $post_emulation_store - not 100% i need this
        \QFactory::getSession()->post_emulation_store = array();
        
    }
    
    // This is used for testing that the varibles get stored
    if($keep_store === true) {
        
        \QFactory::getSession()->set('post_emulation_store', $post_store);
        
    } else {
        
        // Empty the registry store -  but keep it as an array
        \QFactory::getSession()->set('post_emulation_store', array());
        
        // Empty the $post_emulation_store - not 100% i need this
        \QFactory::getSession()->post_emulation_store = array();
        
    }
    
    // Set the store timer to zero
    \QFactory::getSession()->set('post_emulation_timer', '0');
    
    // Return the post store - this compensates for logout
    if(!is_array($post_store)) {
        return array();
    } else {
        return $post_store;
    }
    
}