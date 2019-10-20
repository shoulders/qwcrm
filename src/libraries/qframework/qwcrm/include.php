<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

/* Get Functions */

##########################
#  Get Company details   #
##########################

/*
 * This combined function allows you to pull any of the company information individually
 * or return them all as an array
 * supply the required field name for a single item or all for all items as an array.
 */

function get_company_details($item = null) {
    
    $db = \QFactory::getDbo();
        
    // This is a fallback to make diagnosing critical database failure - This is the first function loaded for $date_format
    if (!$db->isConnected()) {
        die('
                <div style="color: red;">'.
                _gettext("Something went wrong with your QWcrm database connection and it is not connected.").'<br><br>'.
                _gettext("Check to see if your Prefix is correct, if not, you might have a").' <strong>configuration.php</strong> '._gettext("file that should not be present or is corrupt.").'<br><br>'.
                _gettext("Error occured at").' <strong>'.__FUNCTION__.'()</strong><br><br>'.
                '<strong>'._gettext("Database Error Message").':</strong> '.$db->ErrorMsg().
                '</div>'
            );
    }
    
    $sql = "SELECT * FROM ".PRFX."company_record";
    
    if(!$rs = $db->execute($sql)) {          
        
        // Part of the fallback
        if($item == 'date_format') {            
            
            // This is first database Query that will fail if there are issues with the database connection          
            die('
                    <div style="color: red;">'.
                    _gettext("Something went wrong executing an SQL query.").'<br><br>'.
                    _gettext("Check to see if your Prefix is correct, if not, you might have a").' <strong>configuration.php</strong> '._gettext("file that should not be present or is corrupt.").'<br><br>'.
                    _gettext("Error occured at").' <strong>function '.__FUNCTION__.'()</strong> '._gettext("when trying to get the variable").' <strong>date_format</strong>'.'<br><br>'.
                    '<strong>'._gettext("Database Error Message").':</strong> '.$db->ErrorMsg().
                    '</div>'
               );
            
            }        
        
        // Any other lookup error
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get company details."));        
        
    } else {
        
        if($item === null) {
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

/* Update Functions */

#######################################
#    Update User's Last Active Date   #
#######################################

function update_user_last_active($user_id = null) {
    
    $db = \QFactory::getDbo();
    
    // compensate for some operations not having a user_id
    if(!$user_id) { return; }        
    
    $sql = "UPDATE ".PRFX."user_records SET last_active=".$db->qstr( mysql_datetime() )." WHERE user_id=".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a User's last active time."));
    }
    
}

/* Other Functions */

#####################################
#   force_page - Page Redirector    #  // Can send variables as a GET string or POST variables
#####################################

/*
 * If no $page_tpl and $variables are supplied then this function 
 * will force a URL redirect exactly how it was supplied 
 */

function force_page($component, $page_tpl = null, $variables = null, $method = 'auto', $url_sef = 'auto', $url_protocol = 'auto') {
    
    /* Process Options */
    
    // Set method to be used
    if($method == null || $method == 'auto') { $method = 'post'; }    

    // Set URL SEF type to be used
    if ($url_sef == 'sef') { $makeSEF = true; }
    elseif ($url_sef == 'nonsef') { $makeSEF = false; }
    elseif(class_exists('QFactory')) { $makeSEF = \QFactory::getConfig()->get('sef'); }
    else { $makeSEF = false; }
    
    // Configure and set URL protocol and domain segment (allows for https to http, http to https using QWcrm style force_page() links)
    if ($url_protocol == 'https') { $protocol_domain_segment = 'https://'.QWCRM_DOMAIN; }
    elseif ($url_protocol == 'http') { $protocol_domain_segment = 'http://'.QWCRM_DOMAIN; }
    //else { $protocol_domain_segment = null; }                         // This makes relative links
    else { $protocol_domain_segment = QWCRM_PROTOCOL.QWCRM_DOMAIN; }    // This makes absolute links using define settings
    
    /* Standard URL Redirect */
      
    if($component != 'index.php' && $page_tpl == null) {       

        // Build the URL and perform the redirect
        perform_redirect($protocol_domain_segment.$component);        

    }
    
    /* GET - Send Variables via $_GET / Return URL*/
    
    if($method == 'get' || $method == 'url') {
        
        // If variables exist 
        if($variables) {
              
            // If variables are in an array convert into an encoded string
            if($variables && is_array($variables)) {

                // Remove routing variables here to prevent 'Double Bubble' (might not be needed)
                unset($variables['component']);
                unset($variables['page_tpl']); 

                $variables = http_build_query($variables);            
            }
            
        }
        
        // If home, dashboard or maintenance do not show module:page
        if($component == 'index.php') { 
            
            // If there are variables, prepare them as a query string
            if($variables) { $variables = '?'.$variables; }
            
            // Build URL with/without variables
            $url = QWCRM_BASE_PATH.'index.php'.$variables;
            
            // Convert to SEF if enabled            
            if ($makeSEF) { $url = build_sef_url($url); }
            
            // Perform redirect
            if($method == 'get') {
                perform_redirect($protocol_domain_segment.$url);
            } else {
                return $url;
            }

        // Page Name and Variables (QWcrm Style Redirect)  
        } else {
            
            // If there are variables, prepare them as additional GET variables
            if($variables) { $variables = '&'.$variables; }
            
            // Build URL with/without variables
            $url = QWCRM_BASE_PATH.'index.php?component='.$component.'&page_tpl='.$page_tpl.$variables;
            
            // Convert to SEF if enabled            
            if ($makeSEF) { $url = build_sef_url($url); }
            
            // Perform redirect
            if($method == 'get') {
                perform_redirect($protocol_domain_segment.$url);            
            } else {
                return $url;
            }
        }
        
    }
    
    /* POST - Send Varibles via POST Emulation (was $_SESSION but now using Joomla session store)*/    
    
    if($method == 'post') {
        
        // If there are variables, prepare them
        if($variables) {
            
            // If variables are in an encoded string convert to an array
            if(is_string($variables)) {
                parse_str($variables, $variable_array);
            } else {
                $variable_array = $variables;
            }

            // Set the page varible in the session - it does not matter page varible is set twice 1 in $_SESSION and 1 in $_GET the array merge will fix that
            foreach($variable_array as $key => $value) {                    
                postEmulationWrite($key, $value);
            }               

        }
        
        // If home, dashboard or maintenance do not show module:page
        if($component == 'index.php') { 
            
            // Build URL
            $url = QWCRM_BASE_PATH.'index.php';
            
            // Convert to SEF if enabled            
            if ($makeSEF) { $url = build_sef_url($url); }
            
            // Perform redirect
            perform_redirect($protocol_domain_segment.$url);
       
        // Page Name and Variables (QWcrm Style Redirect)     
        } else {
            
            // Build URL
            $url = QWCRM_BASE_PATH.'index.php?component='.$component.'&page_tpl='.$page_tpl;
            
            // Convert to SEF if enabled            
            if ($makeSEF) { $url = build_sef_url($url);}
            
            // Perform redirect
            perform_redirect($protocol_domain_segment.$url);
                
        }
        
    }
    
}

############################################
#     Perform a Browser Redirect           #
############################################

function perform_redirect($url, $type = 'header') {
   
    // Redirect using Headers (cant always use this method in QWcrm)
    if($type == 'header') {
        
        // From http://php.net/manual/en/function.headers-sent.php
        // Note that $filename and $linenum are passed in for later use.
        // Do not assign them values beforehand.
        if (!headers_sent($filename, $linenum)) {
            
            header('Location: ' . $url);
            exit;
            
        // If headers already sent, log and output this error
        } else {
            
            // Build the error message
            $error_msg = '<p>'._gettext("Headers already sent in").' '.$filename.' '._gettext("on line").' '.$linenum.'.</p>';
            
            // Get routing variables
            $routing_variables = get_routing_variables_from_url($_SERVER['REQUEST_URI']);
            
            // Log errors to log if enabled
            if(\QFactory::getConfig()->get('qwcrm_error_log')) {    
                write_record_to_error_log($routing_variables['component'].':'.$routing_variables['page_tpl'], 'redirect', '', debug_backtrace()[1]['function'], '', $error_msg, '');    
            }
            
            // Output the message and stop processing
            die($error_msg);            
            
        }
        
    }
    
    // Redirect using Javascript
    if($type == 'javascript') {         
        echo('
                <script>
                    window.location = "'.$url.'"
                </script>
            ');
        exit;
    }
    
}

############################################
#           force_error_page               #
############################################

// Example to use
// new - force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not display the Work Order record requested"));

function force_error_page($error_type, $error_location, $error_php_function, $error_database, $error_sql_query, $error_msg) { 
    
    // Get routing variables
    $routing_variables = get_routing_variables_from_url($_SERVER['REQUEST_URI']);
    
    // Prepare Variables
    $VAR['error_component']     = prepare_error_data('error_component', $routing_variables['component']);
    $VAR['error_page_tpl']      = prepare_error_data('error_page_tpl', $routing_variables['page_tpl']);
    $VAR['error_type']          = $error_type;
    $VAR['error_location']      = prepare_error_data('error_location', $error_location);
    $VAR['error_php_function']  = prepare_error_data('error_php_function', $error_php_function);
    $VAR['error_database']      = $error_database ;
    $VAR['error_sql_query']     = prepare_error_data('error_sql_query', $error_sql_query);
    $VAR['error_msg']           = $error_msg;
    
    $VAR['error_enable_override'] = 'override'; // This is required to prevent page looping when an error occurs early on (i.e. in a root page)
        
    // raw_output mode is very basic, error logging still works, bootloops are prevented, page tracking and compression are skipped
    if(\QFactory::getConfig()->get('error_page_raw_output')) {
        
        // Create and empty page object
        \QFactory::$BuildPage = '';
        
        // Allow error page to display RAW Output
        $output_raw_error_page = true;
        
        // Error page main content and processing logic
        require(COMPONENTS_DIR.'core/error.php');

        // Output the error page and finish
        die(\QFactory::$BuildPage);
    
    // This will show errors within the template as normal - but occassionaly can cause boot loops during development
    } else {  

        // Load Error Page
        force_page('core', 'error', $VAR);   // No referer unless loaded from clicked link
        
    }
    
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

############################################
#  Error Handling - Data preperation       #
############################################

function prepare_error_data($type, $data = null) {
    
    // Allows errors from install/migrate to be processed
    if(!defined('QWCRM_SETUP')) {
        $user = \QFactory::getUser();
    }

    /* Error Page (by referring page) - only needed when using referrer - not currently used 
    if($type === 'error_page' && isset()) {
     */
        
        // extract the qwcrm page reference from the url (if present)
        //$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;   
        // preg_match('/^.*\?page=(.*)&.*/U', $referer, $page_string);
                
      /*  // compensate for home and login pages
        if($page_string[1] == '') {     
            // Must be Login or Home
            if(isset($user->login_token)) {
                $error_page = 'home';
            } else {
                $error_page = 'login';
            }    
        } else {
            $error_page = $page_string[1];            
        }       
        return $error_page;
    }
    */
        
    // Component (by using $_GET['component']
    if($type === 'error_component') {
        
        // compensate for home and dashboard
        if($data == '') {
            
            // Must be Login or Home
            if(isset($user->login_token)) {
                $data = 'core';
            } else {
                $data = 'core';
            } 
            
        }       
        
        return $data;
        
    }     
    
    // Page_tpl (by using $_GET['page'])
    if($type === 'error_page_tpl') {
        
        // compensate for home and dashboard
        if($data == '') {
            
            // Must be Login or Home
            if(isset($user->login_token)) {
                $data = 'dashboard';
            } else {
                $data = 'home';
            } 
            
        }      
        
        return $data;
        
    } 
    
    // Error Location
    if($type === 'error_location') {     
               
        // remove qwcrm base physical webroot path
        $data = str_replace(QWCRM_PHYSICAL_PATH, '', $data);
        
        // replace backslashes with forward slashes (Windows OS)
        $data = str_replace('\\','/',$data);
        
        // remove drive letter only (Windows OS)
        //$data = preg_replace('/^[a-zA-Z]:/', '', $data);
        
        // remove preceeding slash
        $data = preg_replace('/^\//', '', $data);
        
        return $data;

    }
   
    // PHP Function
    if($type === 'error_php_function') {

        // add () to the end of the php function name
        if($data != '') { $data.= '()'; }        
        return $data;
    }
    
    // Database Error
    if($type === 'error_database') {

        // remove newlines from the database string
        if($data != '') {
            $data = str_replace("\r", '', $data);
            $data = str_replace("\n", '', $data);            
        }
        return $data;
        
    }    

    // SQL Query - for display
    if($type === 'error_sql_query') {

        // change newlines to <br>
        if($data != '') { $data = str_replace("\n", '<br>', $data); }        
        return $data;
        
    }      
    
    // SQL Query - for log
    if($type === 'sql_query_for_log') {
        
        // done seperate because used in MyITCRM migration with dirty data

        // change newlines to text \r\n
        if($data != '') {
            $data = str_replace("\r", '\r', $data);
            $data = str_replace("\n", '\n', $data);            
        }   
        return $data;
        
    }     
    
    // Database Connection Error
    if($type === 'error_database_connection') {

        // remove newlines from the database string
        if($data != '') {
            $data = str_replace("\r", '', $data);
            $data = str_replace("\n", '', $data);
            $data = str_replace("'", "\\'", $data); 
        }
        return $data;
        
    }  
    
}

##########################################################
#  Verify QWcrm install state and set routing as needed  #
##########################################################

function verify_qwcrm_install_state() {
    
    // Temporary Development Override - Keep
    return;
           
    /* Is a QWcrm installation or MyITCRM migration in progress */
    
    // Installation is fine
    if (is_file('configuration.php') && !is_dir(SETUP_DIR)) {      
        return;        
    }
    
    // Prevent undefined variable errors
    QFramework::$VAR['component'] = isset(QFramework::$VAR['component']) ? QFramework::$VAR['component'] : null;
    QFramework::$VAR['page_tpl']  = isset(QFramework::$VAR['page_tpl'])  ? QFramework::$VAR['page_tpl']  : null;
    
    // Installation is in progress
    if (check_page_accessed_via_qwcrm('setup', 'install', 'refered-index_allowed-route_matched', QFramework::$VAR['component'], QFramework::$VAR['page_tpl'])) {
        
        QFramework::$VAR['component'] = 'setup';
        QFramework::$VAR['page_tpl']  = 'install';
        QFramework::$VAR['theme']     = 'menu_off';        
        define('QWCRM_SETUP', 'install');  
        
        return;        
    
        
    // Migration is in progress (but if migration is passing to upgrade, ignore)
    } elseif (check_page_accessed_via_qwcrm('setup', 'migrate', 'refered-index_allowed-route_matched', QFramework::$VAR['component'], QFramework::$VAR['page_tpl'])) {
        
        QFramework::$VAR['component'] = 'setup';
        QFramework::$VAR['page_tpl']  = 'migrate';
        QFramework::$VAR['theme']     = 'menu_off';
        define('QWCRM_SETUP', 'install'); 
        
        return;        
    
        
    // Upgrade is in progress
    } elseif (check_page_accessed_via_qwcrm('setup', 'upgrade', 'refered-index_allowed-route_matched', QFramework::$VAR['component'], QFramework::$VAR['page_tpl'])) {
        
        QFramework::$VAR['component'] = 'setup';
        QFramework::$VAR['page_tpl']  = 'upgrade';
        QFramework::$VAR['theme']     = 'menu_off';        
        define('QWCRM_SETUP', 'install');
        
        return;
        
    /* Redirect to choice page (optional)
    elseif (!is_file('configuration.php') && is_dir(SETUP_DIR)) && !check_page_accessed_via_qwcrm() && !isset(QFramework::$VAR['component'], QFramework::$VAR['page_tpl'])) {        
        
        force_page('setup', 'choice');
             
    }*/        
        
    // Choice - Fresh Installation/Migrate/Upgrade (1st Run) (or refered from the migration process)
    } elseif (!is_file('configuration.php') && is_dir(SETUP_DIR) && !check_page_accessed_via_qwcrm()) {
        
        // Prevent direct access to this page
        if(!check_page_accessed_via_qwcrm(null, null, 'no_referer-routing_disallowed', QFramework::$VAR['component'], QFramework::$VAR['page_tpl'])) {
            header('HTTP/1.1 403 Forbidden');
            die(_gettext("No Direct Access Allowed."));
        }
        
        // Allow only root or index.php
        if($_SERVER['REQUEST_URI'] != QWCRM_BASE_PATH && $_SERVER['REQUEST_URI'] != QWCRM_BASE_PATH.'index.php') {
            header('HTTP/1.1 404 Not Found');
            die(_gettext("This page does not exist."));
        }        
        
        // Move Direct page access control to the pages controller (i.e. I might allow direct access to setup:choice)        
        \QFramework::$VAR['component'] = 'setup';
        \QFramework::$VAR['page_tpl']  = 'choice';
        \QFramework::$VAR['theme']     = 'menu_off';        
        
        /* This allows the use of the database ASAP in the setup process
        if (defined('PRFX') && \QFactory::getDbo()->isConnected() && get_qwcrm_database_version_number()) {
            define('QWCRM_SETUP', 'database_allowed'); 
        } else {
            define('QWCRM_SETUP', 'install'); 
        }*/
        define('QWCRM_SETUP', 'install');
        
        return;       
            
    // Appears to be a valid installation but the setup directory is still present
    } elseif (is_file('configuration.php') && is_dir(SETUP_DIR)) {
        
        // Prevent direct access to this page
        if(!check_page_accessed_via_qwcrm(null, null, 'no_referer-routing_disallowed', QFramework::$VAR['component'], QFramework::$VAR['page_tpl'])) {
            header('HTTP/1.1 403 Forbidden');
            die(_gettext("No Direct Access Allowed."));
        }        

        // Allow only root or index.php
        if(!check_page_accessed_via_qwcrm(null, null, 'root_only')) {
            header('HTTP/1.1 404 Not Found');
            die(_gettext("This page does not exist."));
        }               
        
        // This will compare the database and filesystem and automatically start the upgrade if valid (no need for setup:choice)       
        compare_qwcrm_filesystem_and_database(QFramework::$VAR);    
      
    // Fallback option for those situations I have not thought about
    } else {
    
        die('
                <div style="color: red;">'.
                _gettext("Something went wrong with your installation of QWcrm.").'<br>'.
                _gettext("You might have a configuration.php file that should not be present or is corrupt.").
                '</div>'
            ); 
        
    }
    
    return;
 
}

#########################################################
#  Compare the QWcrm file system and database versions  #  // This is only run if the /setup/ dir exists
#########################################################

function compare_qwcrm_filesystem_and_database() {
    
    // Get the QWcrm database version number (assumes database connection is good)
    $qwcrm_database_version = get_qwcrm_database_version_number();

    // File System and Database versions match(not needed handles in opening 'if' statement, left for reference)
    if(version_compare(QWCRM_VERSION, $qwcrm_database_version,  '=')) {
        
        die(
            '<div style="color: red;">'.
            _gettext("You must delete the 'Setup' directory before you can use QWcrm.").'<br>'.
            '<strong>'.QWCRM_PART_URL.SETUP_DIR.'</strong><br>'.
            '<strong>'.QWCRM_PHYSICAL_PATH.SETUP_DIR.'</strong>'.
            '</div>'
            ); 
        
    } 
    
    /* If the file system is newer than the database - run upgrade (this loads setup:upgrade directly)
    if(version_compare(QWCRM_VERSION, $qwcrm_database_version, '>')) {             
        QFramework::$VAR['component']     = 'setup';
        QFramework::$VAR['page_tpl']      = 'upgrade';
        QFramework::$VAR['theme']         = 'menu_off';
        define('QWCRM_SETUP', 'install'); 
        return;
    }*/
    
    // If the file system is newer than the database - run upgrade (this loads setup:choice but flags it as an upgrade directly)
    if(version_compare(QWCRM_VERSION, $qwcrm_database_version, '>')) {             
        QFramework::$VAR['component']     = 'setup';
        QFramework::$VAR['page_tpl']      = 'choice';
        QFramework::$VAR['theme']         = 'menu_off';
        QFramework::$VAR['setup_type']    = 'upgrade';
        define('QWCRM_SETUP', 'install'); 
        return;
    }

    // Setup failed / Invalid configuration.php
    if($qwcrm_database_version == false) { 
        die('<div style="color: red;">'._gettext("A previous setup attempt never completed successfully and/or there is an invalid configuration.php file present or the database prefix is wrong.").'</div>');            
    }

    // Failed upgrade
    if($qwcrm_database_version == '0.0.0') { 
        die('<div style="color: red;">'._gettext("The upgrade never completed successfully. Check the upgrade and error logs.").'</div>');
    }

    // If the file system is older than the database
    if(version_compare(QWCRM_VERSION, $qwcrm_database_version,  '<')) {             
        die('<div style="color: red;">'._gettext("The file system is older than the database. Check the logs and your settings.").'</div>');
    }

    return;
    
}

################################################
#  Get QWcrm version number from the database  #
################################################

function get_qwcrm_database_version_number() {
    
    $db = \QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."version ORDER BY ".PRFX."version.database_version DESC LIMIT 1";
      
    if(!$rs = $db->execute($sql)) {

       return false;

    } else {
        
        return $rs->fields['database_version'];
        
    }
    
}

####################################################################
#  check the selected template is valid for this version of QWcrm  #
####################################################################

function check_template_is_compatible() {
    
    // Get template details
    $template_details = parse_xml_file_into_array(THEME_DIR.'templateDetails.xml');
        
    // is the QWCRM version too low to run the template
    if (version_compare(QWCRM_VERSION, $template_details['qwcrm_min_version'], '<')) {
        
        return false;
        /*echo _gettext("The current version or QWcrm is too low to use this template.").'<br>';
        echo _gettext("Your current version of QWcrm is").' '.QWCRM_VERSION.'<br>';
        echo _gettext("The template supports QWcrm versions in the range").': '.$template_details['qwcrm_min_version'].' -> '.$template_details['qwcrm_max_version'];
        die();*/
        
    }
    
    // is the QWCRM version to high to run the template
    if (version_compare(QWCRM_VERSION, $template_details['qwcrm_max_version'], '>')) {
        
        return false;
        /*echo _gettext("The current version or QWcrm is too high to use this template.").'<br>';
        echo _gettext("Your current version of QWcrm is").' '.QWCRM_VERSION.'<br>';
        echo _gettext("The template supports QWcrm versions in the range").': '.$template_details['qwcrm_min_version'].' -> '.$template_details['qwcrm_max_version'];
        die();*/
        
    }
    
    return true;
    
}

################################################
#   Get MySQL version                          #
################################################

function get_mysql_version() {
    
    $db = \QFactory::getDbo();    
    
    // adodb.org prefered method - does not bring back complete string - [server_info] =&gt; 5.5.5-10.1.13-MariaDB - Array ( [description] => 10.1.13-MariaDB [version] => 10.1.13 ) 
    //$db->ServerInfo();
    
    // Extract and return the MySQL version - print_r() this and it gives you all of the values - 5.5.5-10.1.13-MariaDB
    preg_match('/^[vV]?(\d+\.\d+\.\d+)/', $db->_connectionID->server_info, $matches);
    return $matches[1];    
    
}

############################################
#   Parse XML file into an array           #
############################################

function parse_xml_sting_into_array($string) {
    
    // SimpleXML - Convert an XML file into a SimpleXMLElement object, then output keys and elements of the object:
    $xml_object = simplexml_load_string($string);
   
    // Convert Object into an array
    $xml_object = get_object_vars($xml_object);
    
    // Return the array
    return $xml_object;
    
}

############################################
#   Parse XML file into an array           #
############################################

function parse_xml_file_into_array($file) {
    
    // Remove base path to make reference relative
    $file = str_replace(QWCRM_BASE_PATH, '', $file);
    
    // SimpleXML - Convert an XML file into a SimpleXMLElement object, then output keys and elements of the object:
    $xml_object = simplexml_load_file($file);
   
    // Convert Object into an array
    $xml_object = get_object_vars($xml_object);
    
    // Return the array
    return $xml_object;
    
    /*
    ALTERNATIVE Version - reference only
    
    // xml_parse_into_struct() old method - keep for reference

    // Load file into memory
    if (!($fp = fopen($file, 'r'))) {
       die(_gettext("Unable to open XML file.").' : '.$file);
    }
    $xmldata = fread($fp, filesize($file));
    fclose($fp);
    
    // Start the XML parser
    $xmlparser = xml_parser_create();
    
    // Convert XML data into an array
    xml_parse_into_struct($xmlparser, $xmldata, $values, $index);
    
    // Frees the given XML parser - I assume to reduce memory usage
    xml_parser_free($xmlparser);    
        
    return $index;
    */
    
}

/* Logging */

############################################
#  Write a record to the Access Log        #  // This will create an apache compatible access log (Combined Log Format)
############################################

function write_record_to_access_log() {    
    
    // Apache log format
    // https://httpd.apache.org/docs/2.4/logs.html
    // http://docstore.mik.ua/orelly/webprog/pcook/ch11_14.htm
    /* Combined Log Format - LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"" combined */    
    // $remote_host, $logname, $user, $time, $method, $request, $protocol, $status, $bytes, $referer, $user_agent
    
    $remote_ip      = $_SERVER['REMOTE_ADDR'];                              // only using IP - not hostname lookup
    $logname        = '-';                                                  //  This is the RFC 1413 identity of the client determined by identd on the clients machine. This information is highly unreliable and should almost never be used except on tightly controlled internal networks.
    
    // Login User - substituting qwcrm user for the traditional apache HTTP Authentication
    if(!\QFactory::getUser()->login_username) {
        $username = '-';
    } else {
        $username = \QFactory::getUser()->login_username;
    }  
    
    $time           = date("[d/M/Y:H:i:s O]", $_SERVER['REQUEST_TIME']);    // Time in apache log format
    
    // The Following 3 items make up the Request
    $method         = $_SERVER['REQUEST_METHOD'];                           // GET/POST
    $uri            = $_SERVER['REQUEST_URI'];                              // the URL
    $protocol       = $_SERVER['SERVER_PROTOCOL'];                          // HTTP/1.0    
    
    $status         = '-';                                                  // page returned status - dont think I can get this 200,401,403,404 etc..
    $bytes          = '-';                                                  // cant get this - page size / payload size
    
    // Referring URL
    if(isset($_SERVER['HTTP_REFERER'])) {
        $referring_url = $_SERVER['HTTP_REFERER']; 
    } else {
        $referring_url = '-';
    }   
    
    // User Agent - if there is no user agent or it cannot be detected then apache uses "-"
    if(isset($_SERVER['HTTP_USER_AGENT']) && ($_SERVER['HTTP_USER_AGENT'] != '')) {
        $user_agent = $_SERVER['HTTP_USER_AGENT']; 
    } else {
        $user_agent = '-';
    } 
   
    $log_entry = $remote_ip.' '.$logname.' '.$username.' '.$time.' "'.$method.' '.$uri.' '.$protocol.'" '.$status.' '.$bytes.' "'.$referring_url.'" "'.$user_agent.'"'."\r\n";
    
    // Write log entry   
    if(!$fp = fopen(ACCESS_LOG, 'a')) {        
        force_error_page('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Access Log to save the record."));
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
    
    return;
    
}

############################################
#  Write a record to the Activity Log      #
############################################

function write_record_to_activity_log($record, $employee_id = null, $client_id = null, $workorder_id = null, $invoice_id = null) {
    
    // if activity logging not enabled exit
    if(\QFactory::getConfig()->get('qwcrm_activity_log') != true) { return; }
    
    /* Use any supplied IDs instead of $GLOBALS[] counterpart
    if(!$employee_id)   { $employee_id  = $GLOBALS['employee_id'];  }
    if(!$client_id)     { $client_id  = $GLOBALS['client_id'];      }
    if(!$workorder_id)  { $workorder_id = $GLOBALS['workorder_id']; }
    if(!$invoice_id)    { $invoice_id   = $GLOBALS['invoice_id'];   }*/   
    
    // Apache Login User - using qwcrm user to emulate the traditional apache HTTP Authentication
    if(!\QFactory::getUser()->login_username) {
        $username = '-';
    } else {
        $username = \QFactory::getUser()->login_username;
    } 
    
    // Build log entry
    $log_entry = $_SERVER['REMOTE_ADDR'].','.$username.','.date("[d/M/Y:H:i:s O]", time()).','.\QFactory::getUser()->login_user_id.','.$employee_id.','.$client_id.','.$workorder_id.','.$invoice_id.','.'"'.$record.'"'."\r\n";
    
    // Write log entry  
    if(!$fp = fopen(ACTIVITY_LOG, 'a')) {        
        force_error_page('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Activity Log to save the record."));
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
    
    return;
    
}

############################################
#  Write a record to the Error Log         #
############################################

function write_record_to_error_log($error_page, $error_type, $error_location, $php_function, $database_error, $error_msg) {
    
    // it is not - force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching Work Orders."));
    
    // Apache Login User - using qwcrm user to emulate the traditional apache HTTP Authentication
    if(!\QFactory::getUser()->login_username) {
        $username = '-';
    } else {
        $username = \QFactory::getUser()->login_username;
    }

    // Build log entry - perhaps use the apache time stamp below
    $log_entry = $_SERVER['REMOTE_ADDR'].','.$username.','.date("[d/M/Y:H:i:s O]", $_SERVER['REQUEST_TIME']).','.$error_page.','.$error_type.','.$error_location.','.$php_function.','.$database_error.','.$error_msg."\r\n";

    // Write log entry  
    if(!$fp = fopen(ERROR_LOG, 'a')) {        
        force_error_page('file', __FILE__, __FUNCTION__.'()', '', '', _gettext("Could not open the Error Log to save the record."));
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
        
    return;
    
}

/* Date and Time */

##########################################
#      Get Date Formats                  #
##########################################

function get_date_formats() {
    
    $db = \QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."company_date_formats";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get date formats."));
    } else {
        
        return $rs->GetArray();
        
    }
    
}    

##########################################
#   Convert Date into Unix Timestamp     #  // $date_format is not currently used
##########################################

function date_to_timestamp($date_to_convert, $date_format = null) {   
    
    // http://php.net/manual/en/datetime.createfromformat.php
    // Be warned that DateTime object created without explicitely providing the time portion will have the current time set instead of 00:00:00.
    // can also use - instead of /
    // the ! allows the use without supplying the time portion
    // this works for all formats of dates where as mktime() might be a bit dodgy
    
    switch(!is_null($date_format) ? $date_format : DATE_FORMAT) {
        
        case '%d/%m/%Y':   
        return DateTime::createFromFormat('!d/m/Y', $date_to_convert)->getTimestamp();
        
        case '%d/%m/%y':    
        return DateTime::createFromFormat('!d/m/y', $date_to_convert)->getTimestamp();
        
        case '%m/%d/%Y':   
        return DateTime::createFromFormat('!m/d/Y', $date_to_convert)->getTimestamp();

        case '%m/%d/%y':    
        return DateTime::createFromFormat('!m/d/y', $date_to_convert)->getTimestamp();
            
        case '%Y-%m-%d':         
        return DateTime::createFromFormat('!Y-m-d', $date_to_convert)->getTimestamp();
        
    }
    
    return;
      
}

################################################
#    Smarty Date and Time to Unix Timestamp    #  // only used in schedule at the minute - smartytime_to_otherformat
################################################

function smartytime_to_otherformat($format, $date, $hour, $minute, $second, $clock, $meridian = null) {
    
    // When using a 12 hour clock
    if($clock == '12') {
        
        // Create timestamp from date
        $timestamp = date_to_timestamp($date);

        // if hour is 12am set hour as 0 - for correct calculation as no zero hour
        if($hour == '12' && $meridian == 'am') {$hour = '0';}

        // Convert hours into seconds and then add - AM/PM aware
        if($meridian == 'pm') {$timestamp += ($hour * 60 * 60 + 43200 );} else {$timestamp += ($hour * 60 * 60);}    

        // Convert minutes into seconds and add
        $timestamp += ($minute * 60);
        
        // Add seconds
        $timestamp += $second;        
        
        // Return time in DATETIME format
        if($format === 'datetime') {
            return date('Y-m-d H:i:s', $timestamp);
        }
        
        // Return a Timestamp
        if($format === 'timestamp') {
            return $timestamp;
        }
        
    }
    
    // When using a 24 hour clock
    if($clock == '24') {
        
        // Create timestamp from date
        $timestamp = date_to_timestamp($date);        

        // Convert hours into seconds and then add
        $timestamp += ($hour * 60 * 60 );

        // Convert minutes into seconds and add
        $timestamp += ($minute * 60);
        
        // Add seconds
        $timestamp += $second;        
        
        // Return time in DATETIME format
        if($format === 'datetime') {
            return date('Y-m-d H:i:s', $timestamp);
        }
        
        // Return a Timestamp
        if($format === 'timestamp') {
            return $timestamp;
        }
        
    }
    
}

#############################################
#    Get Timestamp from year/month/day      #
#############################################

function convert_year_month_day_to_timestamp($year, $month, $day) {  
            
        return DateTime::createFromFormat('!Y/m/d', $year.'/'.$month.'/'.$day)->getTimestamp();   
        
}

##########################################
#   Timestamp to calendar date format    #
##########################################

function timestamp_to_calendar_format($timestamp) {
    
    return date('Ymd', $timestamp);
    
}

##########################################
#     Timestamp to date                  #  // not used anywhere at the minute
##########################################

function timestamp_to_date($timestamp, $date_format = null) {    

    switch(!is_null($date_format) ? $date_format : DATE_FORMAT) {
        
        case '%d/%m/%Y':
        return date('d/m/Y', $timestamp);        
        
        case '%d/%m/%y':
        return date('d/m/y', $timestamp);       

        case '%m/%d/%Y':
        return date('m/d/Y', $timestamp);        

        case '%m/%d/%y':
        return date('m/d/y', $timestamp);
            
        case '%Y-%m-%d':
        return date('Y-m-d', $timestamp);
            
    }

}

#####################################################
#   Convert a timestamp into MySQL DATE Format      #
#####################################################

function timestamp_mysql_date($timestamp) {       
     
   // If there is no timestamp return an empty MySQL DATE
    if($timestamp == '') {
        
        return '0000-00-00';
        
    } else {        
        
        return date('Y-m-d', $timestamp);
        
    }
      
}

#####################################################
#   Convert a timestamp into MySQL DATETIME Format  #  // not currently used
#####################################################

function timestamp_mysql_datetime($timestamp) { 
    
    // If there is no timestamp return an empty MySQL DATETIME
    if(!$timestamp) {
        
        return '0000-00-00 00:00:00';
        
    } else {
        
        return date('Y-m-d H:i:s', $timestamp);
        
    }   
      
}

############################################
#   Convert Date into MySQL DATE Format    #  // $date_format is not currently used
############################################

function date_to_mysql_date($date_to_convert, $date_format = null) {   
    
    // http://php.net/manual/en/datetime.createfromformat.php
    // Be warned that DateTime object created without explicitely providing the time portion will have the current time set instead of 00:00:00.
    // can also use - instead of /
    // the ! allows the use without supplying the time portion    
    
    switch(!is_null($date_format) ? $date_format : DATE_FORMAT) {
        
        case '%d/%m/%Y':   
        return DateTime::createFromFormat('!d/m/Y', $date_to_convert)->format('Y-m-d');
        
        case '%d/%m/%y':    
        return DateTime::createFromFormat('!d/m/y', $date_to_convert)->format('Y-m-d');
        
        case '%m/%d/%Y':   
        return DateTime::createFromFormat('!m/d/Y', $date_to_convert)->format('Y-m-d');

        case '%m/%d/%y':    
        return DateTime::createFromFormat('!m/d/y', $date_to_convert)->format('Y-m-d');
            
        case '%Y-%m-%d':   
        return DateTime::createFromFormat('!Y-m-d', $date_to_convert)->format('Y-m-d');
        
    }
    
    return;
      
}

##################################################
#   Get current date in MySQL DATE Format        #  // gives current datetime unless a timstamp is used then that is converted
##################################################

function mysql_date($timestamp = null) {       
       
    // These do the same job and are for reference
    //(new DateTime('now'))->format('Y-m-d H:i:s');    
    //date('Y-m-d', time());  // The time() argument is redundant for current time
    
    return is_null($timestamp) ? date('Y-m-d') : date('Y-m-d', $timestamp);
 
}

##################################################
#   Get current time in MySQL DATETIME Format    #  // gives current datetime unless a timstamp is used then that is converted
##################################################

function mysql_datetime($timestamp = null) {       
     
    // These do the same job and are for reference
    //(new DateTime('now'))->format('Y-m-d H:i:s');    
    //date('Y-m-d H:i:s', time());  // the time() argument is redundant for current time
        
    return is_null($timestamp) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $timestamp);
      
}

##############################################
#   Build MySQL DATETIME                     # 
##############################################

function build_mysql_datetime($hour = null, $minute = null, $second = null, $month = null, $day = null, $year = null) {
 
    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    return date('Y-m-d H:i:s', $timestamp);
    
}

#########################################################
#   Return Date in correct format from year/month/day   #  // only used in schedule
#########################################################

function convert_year_month_day_to_date($year, $month, $day) {    

    // Ensure months supplied as 2 digits
    if(strlen($month) == 1) {$month = '0'.$month;}
    
    // Ensure days supplied as 2 digits
    if(strlen($day) == 1) {$day = '0'.$day;}
    
    switch(DATE_FORMAT) {

        case '%d/%m/%Y':
        return $day."/".$month."/".$year;

        case '%d/%m/%y':
        return $day.'/'.$month.'/'.substr($year, 2);

        case '%m/%d/%Y':
        return $month.'/'.$day.'/'.$year;

        case '%m/%d/%y':
        return $month.'/'.$day.'/'.substr($year, 2);
            
        case '%Y-%m-%d':
        return $year.'-'.$month.'-'.$day;
            
    }
    
}

/* Other */

##############################################
#  Clear any onscreen notifications          #   // this is needed for messages when pages are requested via ajax (emails/config)
##############################################

function ajax_clear_onscreen_notifications() {

    echo "<script>clearSystemMessages();</script>";
    
}

##############################################
#  Output email notifications onscreen       #   // this is needed for messages when pages are requested via ajax (emails/config)
##############################################

function ajax_output_notifications_onscreen($information_msg = '', $warning_msg = '') {
   
    echo "<script>processSystemMessages('".escape_for_javascript($information_msg)."', '".escape_for_javascript($warning_msg)."');</script>";
    
}

##############################################
#  Escape string for use in Javascript       #
##############################################

function escape_for_javascript($text){
    
    $text = nl2br($text);
    $text = strtr($text, array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n', '</' => '<\/'));
    
    return $text;
    
}

##############################################
#  Output email notifications onscreen       #   // this is needed for messages when pages are requested via ajax (emails/config)
##############################################

function toggle_element_by_id($element_id, $action = 'hide') {
  
    /* JQuery Version */
    if($action == 'hide') {
        
        echo '
        <script>                
            $("#'.$element_id.'").hide();
        </script>';
        
    } 
    
    if ($action == 'show') {
        
        echo '
        <script>              

            $("#'.$element_id.'").show();              

        </script>';
               
    }
    
    if($action == 'disable') {
        
        echo '
        <script>                
            $("#'.$element_id.'").prop("disabled", true);
        </script>';
        
    } 
    
    if ($action == 'enable') {
        
        echo '
        <script>
            $("#'.$element_id.'").prop("disabled", false);
        </script>';
               
    }
    
    /* Javascript Version (for reference only) 
    if($action == 'hide') {
        
        echo '
            <script>                

                var x = document.getElementById("'.$element_id.'");
                if (x.style.display !== "none") {
                    x.style.display = "none";
                }             

            </script>';
        
    } elseif ($action == 'show') {
        
        echo '
            <script>              

                var x = document.getElementById("'.$element_id.'");
                if (x.style.display !== "block") {
                    x.style.display = "block";
                }               

            </script>';
               
    }*/
    
}

/* Smarty Section */

############################################
#      Clear Smarty Cache                  #
############################################

function clear_smarty_cache() {
    
    $smarty = \QFactory::getSmarty();
    
    // Clear any onscreen notifications - this allows for mutiple errors to be displayed
    ajax_clear_onscreen_notifications();
    
    // clear the entire cache
    $smarty->clearAllCache();

    // clears all files over one hour old
    //$smarty->clearAllCache(3600);
    
    // Output the system message to the browser   
    ajax_output_notifications_onscreen(_gettext("The Smarty cache has been emptied successfully."), '');
    
    // Log activity        
    write_record_to_activity_log(_gettext("Smarty Cache Cleared."));
    
}

############################################
#      Clear Smarty Compile                #
############################################

function clear_smarty_compile() {
    
    $smarty = \QFactory::getSmarty();
    
    // Clear any onscreen notifications - this allows for mutiple errors to be displayed
    ajax_clear_onscreen_notifications();
    
    // clear a specific template resource
    //$smarty->clearCompiledTemplate('index.tpl');

    // clear entire compile directory
    $smarty->clearCompiledTemplate();
    
    // Output the system message to the browser   
    ajax_output_notifications_onscreen(_gettext("The Smarty compile directory has been emptied successfully."), '');
    
    // Log activity        
    write_record_to_activity_log(_gettext("Smarty Compile Cache Cleared."));    
    
}

################################################
#         Load Languages                       #  List the available languages and return as an array
################################################

function load_languages() {

    // Get the array of directories
    $languages = glob(LANGUAGE_DIR . '*', GLOB_ONLYDIR);
    
    // Remove path from directory and just leave the directory name (i.e. en_GB)
    $languages = array_map('basename', $languages);
        
    // Make sure that en_GB is always first in the list (find it by value, delete and then re-add)
    if (($key = array_search('en_GB', $languages)) !== false) {
        unset($languages[$key]);
        array_unshift($languages, 'en_GB');
    }
        
    // Remove '_gettext_only' directory    
    if (($key = array_search('_gettext_only', $languages)) !== false) {
        unset($languages[$key]);
    }
    
    // Re-index the array - This is not needed but keeps things neat
    $languages = array_values($languages);
    
    return $languages;
    
}
    
################################################
#         Load Language                        #  // Most people use $locale instead of $language
################################################

function load_language() {
    
    // Load compatibility layer (motranslator)
    PhpMyAdmin\MoTranslator\Loader::loadFunctions();

    // Autodetect Language - I18N support information here
    if(function_exists('locale_accept_from_http') && (\QFactory::getConfig()->get('autodetect_language') == '1' || \QFactory::getConfig()->get('autodetect_language') == null)) {

        // Use the locale language if detected or default language or british english (format = en_GB)
        if(!$language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

            // Set default language as the chosen language or fallback to british english
            if(!$language = \QFactory::getConfig()->get('default_language')) {
                $language = 'en_GB';
            }

        }

        // If there is no language file for the locale, set language to british english - This allows me to use CONSTANTS in translations but bypasses normal fallback mechanism for _gettext()
        if(!is_file(LANGUAGE_DIR.$language.'/LC_MESSAGES/site.po')) {
            $language = 'en_GB';    
        }

    } else {

        // Set default language or fallback to british english
        if(!$language = \QFactory::getConfig()->get('default_language')) {
            $language = 'en_GB';
        }

    }

    // Here we define the global system locale given the found language (apparently can also use putenv("LANGUAGE=$language");)
    putenv("LANG=$language");

    // https://www.php.net/manual/en/function.setlocale.php
    
    // This sets local for all these settings - LC_COLLATE, LC_CTYPE, LC_MONETARY, LC_NUMERIC, LC_TIME, LC_MESSAGES
    // This might be useful for date or money formatting etc...
    _setlocale(LC_ALL, $language);
    
    // Set the LC_MESSAGES store - This sets the folder name which stores the LC_MESSAGES folder - This does not work
    //_setlocale(LC_MESSAGES, $language);
    
    // Set the text domain - This sets the name of the .mo file
    $textdomain = 'site';

    // This will make _gettext look for ../language/<lang>/LC_MESSAGES/site.mo
    _bindtextdomain($textdomain, LANGUAGE_DIR);

    // Indicates in what encoding the file should be read
    _bind_textdomain_codeset($textdomain, 'UTF-8');

    // Here we indicate the default domain the _gettext() calls will respond to - The default .mo file
    _textdomain($textdomain);

}

################################################
#   Process and correct user inputted URLs     #  // make sure the url has a https?:// before being added to the database, if not add one
################################################

function process_inputted_url($url) {
    
    // If no URL has been submitted return nothing
    if($url == '') {
        return '';
    }
    
    if ($parsed_url = parse_url($url)) {

        // Check if there is a protocol(scheme) set
        if (!isset($parsed_url['scheme'])) {
            
            return 'http://'.$url;
            
        } else {
        
            return $url;

        }        
        
    // If the url is corrupt return nothing    
    } else {
        
        return '';
    
        
    }
    
}