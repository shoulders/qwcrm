<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/** Main Include File **/

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

/* Update Functions */


#######################################
#    Update User's Last Active Date   #
#######################################

function update_user_last_active($db, $user_id) {
    
    $sql = "UPDATE ".PRFX."user SET last_active=".$db->qstr(time())." WHERE user_id=".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a User's last active time."));
        exit;
    }
    
}

/* Other Functions */

#####################################
#   force_page - Page Redirector    #
#####################################

/*
 * If no $page_tpl and $variables are supplied then this function 
 * will force a URL redirect exactly how it was supplied 
 */

function force_page($module, $page_tpl = Null, $variables = Null, $method = 'session') {
    
    /* Normal URL Redirect */
    
    if($page_tpl == Null && $variables == Null){       

        // Build the URL and perform the redirect
        perform_redirect($module);        

    }
    
    /* Send Variables via $_GET */
    
    if($method == 'get') {
        
        // If Login, home or maintenance do not show module:page
        if($page_tpl == 'login' || $page_tpl == 'home' || $page_tpl == 'maintenance') {                
            
            // Page Name Only    
            if ($page_tpl != Null && $variables == Null) {

                // Build the URL and perform the redirect
                perform_redirect('index.php');            

            }

            // Page Name and Variables (QWcrm Style Redirect)
            if ($page_tpl != Null && $variables != Null) {   

                // Build the URL and perform the redirect
                perform_redirect('index.php?'.$variables);            

            }
            
        } else {
            
            // Page Name Only    
            if ($page_tpl != Null && $variables == Null) {

                // Build the URL and perform the redirect
                perform_redirect('index.php?page='.$module.':'.$page_tpl);            

            }

            // Page Name and Variables (QWcrm Style Redirect)
            if ($page_tpl != Null && $variables != Null) {   

                // Build the URL and perform the redirect
                perform_redirect('index.php?page='.$module.':'.$page_tpl.'&'.$variables);            

            }
            
        }
        
    }
    
    /* Session - Send Varibles via POST Emulation (was $_SESSION but now using Joomla session store)*/    
    
    if($method == 'session') {
        
        // If Login, home or maintenance do not show module:page
        if($page_tpl == 'login' || $page_tpl == 'home' || $page_tpl == 'maintenance') { 
            
            // Page Name Only
            if ($page_tpl != Null && $variables == Null) {                        

                // Build the URL and perform the redirect
                perform_redirect('index.php');            

            }

            // Page Name and Variables (QWcrm Style Redirect)
            if($page_tpl != Null && $variables != Null) {          

                // Parse the URL into an array            
                $variable_array = array();
                parse_str($variables, $variable_array);

                // Set the page varible in the session - it does not matter page varible is set twice 1 in $_SESSION and 1 in $_GET the array merge will fix that
                foreach($variable_array as $key => $value) {                    
                    postEmulationWrite($key, $value);
                }               

                // Build the URL and perform the redirect
                perform_redirect('index.php');

            }
            
        } else {
            
            // Page Name Only
            if ($page_tpl != Null && $variables == Null) {                        

                // Build the URL and perform the redirect
                perform_redirect('index.php?page='.$module.':'.$page_tpl);            

            }

            // Page Name and Variables (QWcrm Style Redirect)
            if($page_tpl != Null && $variables != Null) {          

                // Parse the URL into an array            
                $variable_array = array();
                parse_str($variables, $variable_array);

                // Set the page varible in the session - it does not matter page varible is set twice 1 in $_SESSION and 1 in $_GET the array merge will fix that
                foreach($variable_array as $key => $value) {                    
                    postEmulationWrite($key, $value);
                }

                // Build the URL and perform the redirect
                perform_redirect('index.php?page='.$module.':'.$page_tpl);                

            }
                
        }
        
    }
    
}

############################################
#     Perform a Browser Redirect           #
############################################

function perform_redirect($url, $type = 'header') {
   
    // Redirect using Headers (cant always use this method in QWcrm)
    if($type == 'header') {
        header('Location: ' . $url);
        exit;
    }
    
    // Redirect using Javascript
    if($type == 'javascript') {                     
        echo('
                <script>
                    window.location = "'.$url.'"
                </script>
            ');        
    }
    
}

############################################
#           force_error_page               #
############################################

// Example to use
// If a function needs more than 1 error notification - add after _failed - this keeps it easy to swapp stuff out : i.e _failed --> _failed_notfound ?
// old - force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
// new - force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not display the Work Order record requested"));

function force_error_page($error_page, $error_type, $error_location, $php_function, $database_error, $sql_query, $error_msg) { 
    
    // Load config settigns
    $QConfig = new QConfig;
   
    // raw_output mode is very basic, error logging still works, bootloops are prevented, page tracking and compression are skipped
    if($QConfig->error_page_raw_output) {
        
        // required for some smarty operations in the error page (this is used in the included page)
        global $smarty;

        // make sure the page object is empty
        $BuildPage = '';

        $VAR['error_page']      = prepare_error_data('error_page', $error_page);
        $VAR['error_type']      = $error_type;
        $VAR['error_location']  = prepare_error_data('error_location', $error_location);
        $VAR['php_function']    = prepare_error_data('php_function', $php_function);
        $VAR['database_error']  = $database_error ;
        $VAR['sql_query']       = prepare_error_data('sql_query', $sql_query);
        $VAR['error_msg']       = $error_msg;

        // Error page main content and processing logic
        require(MODULES_DIR.'core/error.php');

        // output the error page
        echo $BuildPage;
        exit;
    
    // This will show errors within the template as normal - but occassionaly can cause boot loops during development
    } else {
        
        // Pass varibles to the error page after preperation
        postEmulationWrite('error_page',         prepare_error_data('error_page', $error_page)           );
        postEmulationWrite('error_type',         $error_type                                             );
        postEmulationWrite('error_location',     prepare_error_data('error_location', $error_location)   );
        postEmulationWrite('php_function',       prepare_error_data('php_function', $php_function)       );
        postEmulationWrite('database_error',     $database_error                                         );
        postEmulationWrite('sql_query',          prepare_error_data('sql_query', $sql_query)             );
        postEmulationWrite('error_msg',          $error_msg                                              );    

        // Load Error Page
        force_page('core', 'error');
        exit;
    }
    
}

###########################################
#  POST Emulation - for server to server  #  // might only work for logged in users, need to check, but fails on logout because session data is destroyed?
###########################################

/*
 * this writes into the session registry/$data
 * the register_shutdown_function() in native.php registers teh save()function to be run as the last thing run by the script
 * $post_emulation_varible to registry code in the save() function in native.php - does work but i cannot control if the post varibles stay in the databse store
 */

// this writes to the $post_emulation_varible and then the varible to the store
function postEmulationWrite($key, $value) {
    
    // Refresh the store timer to keep it fresh
    QFactory::getSession()->set('post_emulation_timer', time());
    
    // Set the varible in the $post_emulation_store variable
    QFactory::getSession()->post_emulation_store[$key] = $value;
    
    // Save the whole $post_emulation_store varible into the registry (does this for every variable write)
    QFactory::getSession()->set('post_emulation_store', QFactory::getSession()->post_emulation_store);
    
}

// This reads the data from $post_emulation_varible
function postEmulationRead($key) {
    
    // Refresh the store timer to keep it fresh
    QFactory::getSession()->set('post_emulation_timer', time());
    
    // Read a varible from the store and return it
    return QFactory::getSession()->post_emulation_store[$key];
    
}

function postEmulationReturnStore($keep_store = false) {
    
    // Make temporary copy of the post store
    $post_store = QFactory::getSession()->get('post_emulation_store');
    
    // Delete Stale Post Store - make sure the store is not an old one by putting a time limit on the validity
    if(time() - QFactory::getSession()->get('post_emulation_timer') > 5 ) {        
        
        // Empty the registry store -  but keep it as an array
        QFactory::getSession()->set('post_emulation_store', array());
        
        // Empty the $post_emulation_store - not 100% i need this
        QFactory::getSession()->post_emulation_store = array();
        
    }
    
    // This is used for testing that the varibles get stored
    if($keep_store === true) {
        
        QFactory::getSession()->set('post_emulation_store', $post_store);
        
    } else {
        
        // Empty the registry store -  but keep it as an array
        QFactory::getSession()->set('post_emulation_store', array());
        
        // Empty the $post_emulation_store - not 100% i need this
        QFactory::getSession()->post_emulation_store = array();
        
    }
    
    // Set the store timer to zero
    QFactory::getSession()->set('post_emulation_timer', '0');
    
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

function prepare_error_data($type, $data = Null) {
    
    $user = QFactory::getUser();

    /* Error Page (by referring page) - only needed when using referrer - not currently used 
    if($type === 'error_page'){
     */
        
        // extract the qwcrm page reference from the url      
        // preg_match('/^.*\?page=(.*)&.*/U', getenv('HTTP_REFERER'), $page_string);
                
      /*  // compensate for home and login pages
        if($page_string[1] == ''){     
            // Must be Login or Home
            if(isset($user->login_token)){
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
        
    /* Error Page (by using $_GET['page'] */    
    if($type === 'error_page') {
        
        // compensate for home and login pages
        if($data == '') {
            
            // Must be Login or Home
            if(isset($user->login_token)){
                $error_page = 'home';
            } else {
                $error_page = 'login';
            }    
        } else {
            $error_page = $data;            
        }       
        
        return $error_page;
        
    }     
    
    /* Error Location */
    if($type === 'error_location'){     
               
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
   
    /* PHP Function */
    if($type === 'php_function'){

        // add () to the end of the php function name
        if($data != '') {$data.= '()';}        
        return $data;
    }
    
    /* Database Error */
    if($type === 'database_error'){

        // remove newlines from the database string
        if($data != '') {$data = str_replace("\n", '', $data);}
        return $data;
        
    }
    
    /* SQL Query */
    if($type === 'sql_query'){

        // change newlines to <br>
        if($data != '') {$data = str_replace("\n", '<br>', $data);}        
        return $data;
        
    }    
    
}

############################################
#  Language Translation Function           #    // this is depreceated
############################################

function load_language() {
    
    global $smarty; 
     
    // xml_parse_into_struct() old method - keep for reference

    // Load file into memory
    $file = LANGUAGE_DIR.THEME_LANGUAGE;   
    if (!($fp = fopen($file, 'r'))) {
       die('unable to open XML');
    }
    $xmldata = fread($fp, filesize($file));
    fclose($fp);
    
    // Start the XML parser
    $xmlparser = xml_parser_create();
    
    // Convert XML data into an array
    xml_parse_into_struct($xmlparser, $xmldata, $values);   // $arr_vals is correct here
    
    // Frees the given XML parser - I assume to reduce memory usage
    xml_parser_free($xmlparser);    
    
    // Loop through the array key/pairs and make smarty variables
    foreach($values as $things){
        if($things['tag'] != 'TRANSLATE' && $things['value'] != ''){
            $smarty->assign('translate_'.strtolower($things['tag']),$things['value']);
        }
    }    

    return true;

    /*
    // SimpleXML Method (could be slower but more programmable)
    
    $language_xml = simplexml_load_file(LANGUAGE_DIR.THEME_LANGUAGE);
   
    // Extract the <language_specific_settings> as an array
    foreach ($language_xml->language_specific_settings as $language_specific_settings) {
        // Cycle throught the array and extract
        foreach ($language_specific_settings as $key => $value) {
            //$smarty->assign('translate_'.$key, $value);
            // for use later
        }
    }
    
    // Extract the <translate> as an array
    foreach ($language_xml->translate as $translate) {
        // Cycle throught the array and extract
        foreach ($translate as $key => $value) {
            $smarty->assign('translate_'.$key, $value);             
        }
    }
    
    // Destroy the loaded XML data to save memory
    unset($language_xml);
*/
    
}

############################################
#      Set Page Header and Meta Data       #
############################################

/*
 * This does cause these translations to be loaded/assigned twice but allows me to use 1 file language instead of 2
 */

function set_page_header_and_meta_data($module, $page_tpl, $page_title_from_var = Null){
    
    global $smarty;
    
    /* Page Title
     * This allows the title to be overidden and legacy compatibility where the title is passed to the new page
     * or just use the page title from the language file
     * legacy option will be removed in future
     */
    if ($page_title_from_var != Null){
        $smarty->assign('page_title', $page_title_from_var); 
    } else {        
        $smarty->assign('page_title', gettext(strtoupper($module).'_'.strtoupper($page_tpl).'_PAGE_TITLE'));
    }    
    
    // Meta Tags
    $smarty->assign('meta_description', gettext(strtoupper($module).'_'.strtoupper($page_tpl).'_PAGE_META_DESCRIPTION')   );
    $smarty->assign('meta_keywords',    gettext(strtoupper($module).'_'.strtoupper($page_tpl).'_PAGE_META_KEYWORDS')      );
    
    return;
    
}


#####################################################################
#  Verify User's authorization for a specific page / operation      #
#####################################################################

function check_acl($db, $login_usergroup_id, $module, $page_tpl) {
    
    /* error catching - you cannot use normal error logging as it will cause a loop */
    if($login_usergroup_id == ''){
        die(gettext("The ACL has been supplied with no account type ID - I will now die."));                
    }

    /* Get user's Group Name by login_usergroup_id */
    $sql = "SELECT ".PRFX."user_usergroups.usergroup_display_name
            FROM ".PRFX."user_usergroups
            WHERE usergroup_id =".$db->qstr($login_usergroup_id);
    
    if(!$rs = $db->execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not get the user's Group Name by Login Account Type ID."));
        exit;
    } else {
        $usergroup_display_name = $rs->fields['usergroup_display_name'];
    } 
    
    // Build the page name for the ACL lookup
    $module_page = $module.':'.$page_tpl;
    
    /* Check Page to see if we have access */
    $sql = "SELECT ".$usergroup_display_name." AS acl FROM ".PRFX."user_acl WHERE page=".$db->qstr($module_page);

    if(!$rs = $db->execute($sql)) {        
        force_error_page($_GET['page'], 'authentication', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not get the Page's ACL."));
        exit;
    } else {
        
        $acl = $rs->fields['acl'];
        
        // Add if guest (8) rules here if there are errors
        
        if($acl != 1) {
            
            //force_error_page($_GET['page'], 'authentication', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("You do not have permission to access the page - ").' '.$module.':'.$page_tpl);             
            force_page('core', 'login', 'warning_msg='.gettext("You do not have permission to access the page").' - '.$module.':'.$page_tpl);            
            exit;
            
        } else {
            
            return true;
            
        }
        
    }
    
}    

############################################
#  Verify QWcrm is installed correctly     #
############################################

function verify_qwcrm_is_installed_correctly($db){

    // Is gettext install (for translations)
    if(!function_exists('gettext')) {
        die('Gettext is not installed which is required for the translation system.');
    }
    
    // If no configuration file - redirect to the installation directory
    if(!is_file('configuration.php')){        
        force_page('install/index.php');
        exit;
    }
        
    // check the database can actually be read and retrieve the QWcrm database verion for the next function
    if(!$qwcrm_database_version = get_qwcrm_database_version_number($db)) {
        die(gettext("Cannot read the QWcrm version number from the database. This could be a database connection issue."));
    }


    // Compare the version number of the file system against the database - if mismatch load upgrade for further instructions?
    if(version_compare($qwcrm_database_version, QWCRM_VERSION, '!=')){
        
        // I have not decided whether to use a message or automatic redirect to the upgrade folder        
        die('<div style="color: red;">'.gettext("The File System and Database versions do not match, run the upgrade routine.").'</div>');    
                
        //force_page('upgrade');
        
    }
    
    // has been installed but the installion directory is still present  
    if(is_dir('install') ) {
        die('<div style="color: red;">'.gettext("The install Directory Exists!! Please Rename or remove the install directory.").'</div>');       
    }
    
    // has been installed but the upgrade directory is still present  
    if(is_dir('upgrade') ) {
        die('<div style="color: red;">'.gettext("The Upgrade Directory Exists!! Please Rename or remove the upgrade directory.").'</div>');     
    }  
    
}

################################################
#  Get QWcrm version number from the database  #
################################################

function get_qwcrm_database_version_number($db){
    
    $sql = "SELECT * FROM ".PRFX."qwcrm_version ORDER BY ".PRFX."qwcrm_version.version_installed DESC LIMIT 1";
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not compare the version of the file system and databases to verify they match."));
        exit;
    } else {
        
        return $rs->fields['version_installed'];
        
    }
    
}

##########################
#  Get company details   #
##########################

/*
 * This combined function allows you to pull any of the company information individually
 * or return them all as an array
 * supply the required field name or all to return all of them as an array
 */

function get_company_details($db, $item = null){
    
    $sql = "SELECT * FROM ".PRFX."company";
    
    if(!$rs = $db->execute($sql)){        
        //force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get company details."));
        echo $sql.'<br>';
        echo $db->ErrorMsg();
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

####################################################################
#  Encryption Routine using the secret key from configuration.php  #  // not sure this is used anywhere
####################################################################

function encrypt($strString, $secret_key){
    
    $deresult = '';
    
    for($i=0; $i<strlen($strString); $i++){
        $char       =   substr($strString, $i, 1);
        $keychar    =   substr($secret_key, ($i % strlen($secret_key))-1, 1);
        $char       =   chr(ord($char)+ord($keychar));
        $deresult  .=   $char;
    }    
    
    return base64_encode($deresult);
    
}

####################################################################
#  Deryption Routine using the secret key from configuration.php   # // not sure this is used anywhere
####################################################################

function decrypt($strString, $secret_key){
     
    $deresult = '';
    base64_decode($strstring);
    
    for($i=0; $i<strlen($strString); $i++){
        $char       =   substr($strString, $i, 1);
        $keychar    =   substr($secret_key, ($i % strlen($secret_key))-1, 1);
        $char       =   chr(ord($char)-ord($keychar));
        $deresult  .=   $char;
    }
    
    return $deresult;
    
}

###################################################################################
#  Alternate encrytption routines - Not Used - might be for something (Untested)  #
###################################################################################

/*
function encrypt($strString, $secret_key){

	if ($strString == '') {
            return $strString;
	}
        
	$iv         = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$enString   = mcrypt_ecb(MCRYPT_BLOWFISH, $secret_key, $strString, MCRYPT_ENCRYPT, $iv);
	$enString   = bin2hex($enString);

	return ($enString);
	
}
*/

###################################################################################
#  Alternate Decrytption routines - Not Used - might be for something (Untested)  #
###################################################################################

/*
function decrypt($strString, $secret_key){
	
	if ($strString == '') {
            return $strString;
	}
        
	$iv         = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$strString  = hex2bin($strString);
	$deString   = mcrypt_ecb(MCRYPT_BLOWFISH, $secret_key, $strString, MCRYPT_DECRYPT, $iv);

	return ($deString);

}
*/

################################################
#  Get Real IP address                         #
################################################

/*
 * This attempts to get the real IP address of the user 
 */

function get_ip_address(){
    
    if(getenv('HTTP_CLIENT_IP')) {
        $ip_address = getenv('HTTP_CLIENT_IP');        
    }
    elseif(getenv('HTTP_X_FORWARDED_FOR')) {
        $ip_address = getenv('HTTP_X_FORWARDED_FOR');        
    }
    elseif(getenv('REMOTE_ADDR')) {
        $ip_address = getenv('REMOTE_ADDR');        
    }
    else {$ip_address = 'UNKNOWN';}
    
    return $ip_address;
    
}

/*
 * The following code delivers ::1 instead of 127.0.0.1
 */

/*
// Check ip from share internet
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
}

// To check ip is pass from proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip=$_SERVER['REMOTE_ADDR'];
}

echo ('My real IP is:'.$ip);
*/

################################################
#  Write a record to the Tracker Table         #
################################################

function write_record_to_tracker_table($db, $page_display_controller, $module, $page_tpl){
    
   $sql = "INSERT into ".PRFX."tracker SET
   date          = ". $db->qstr( time()                     ).",
   ip            = ". $db->qstr( get_ip_address()           ).",
   uagent        = ". $db->qstr( getenv('HTTP_USER_AGENT')  ).",
   full_page     = ". $db->qstr( $page_display_controller   ).",
   module        = ". $db->qstr( $module                    ).",
   page          = ". $db->qstr( $page_tpl                  ).",
   referer       = ". $db->qstr( getenv('HTTP_REFERER')     );

   if(!$rs = $db->Execute($sql)) {
      force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Error inserting item into tracker database."));
      exit;      
   }
   
   return;
    
}

############################################
#  Write a record to the activity.log file #
############################################

/*
 * This writes Specific QWcrm activity note to the activity.log, i.e. login/logout
 * The messages and information is already formed before reaching here
 * 
 * add username and other stuff here? not just the message
 */

function write_record_to_activity_log($record){
    
    global $QConfig;

    // if activity logging not enabled exit
    if($QConfig->qwcrm_activity_log != true){return;}
    
    // Build log entry - perhaps use the apache time stamp below
    $log_entry = $_SERVER['REMOTE_ADDR'] . ',' . $_SESSION['login_username'] . ',' . date("[d/M/Y:H:i:s O]", time()) . ',' . $record . "\n";
    
    // Write log entry to access log    
    if(!$fp = fopen(ACTIVITY_LOG,'a')) {        
        force_error_page($_GET['page'], 'file', __FILE__, __FUNCTION__, '', '', gettext("Could not open activity.log to save the record."));
        exit;
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
    
    return;
    
}

############################################
#  Write a record to the access.log file   #
############################################

/*
 * This will create an apache compatible access.log (Combined Log Format)
 */

function write_record_to_access_log($login_username = Null){    
    
    // Apache log format
    // https://httpd.apache.org/docs/2.4/logs.html
    // http://docstore.mik.ua/orelly/webprog/pcook/ch11_14.htm
    /* Combined Log Format - LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"" combined */    
    // $remote_host, $logname, $user, $time, $method, $request, $protocol, $status, $bytes, $referer, $user_agent
    
    $remote_ip      = $_SERVER['REMOTE_ADDR'];                              // only using IP - not hostname lookup
    $logname        = '-';                                                  //  This is the RFC 1413 identity of the client determined by identd on the clients machine. This information is highly unreliable and should almost never be used except on tightly controlled internal networks.
    
    // Login User - substituting qwcrm user for the traditional apache HTTP Authentication
    if($login_username == ''){
        $user = '-';
    } else {
        $user = $login_username;  
    }  
    
    $time           = date("[d/M/Y:H:i:s O]", $_SERVER['REQUEST_TIME']);    // Time in apache log format
    
    // The Following 3 items make up the Request
    $method         = $_SERVER['REQUEST_METHOD'];                           // GET/POST
    $uri            = $_SERVER['REQUEST_URI'];                              // the URL
    $protocol       = $_SERVER['SERVER_PROTOCOL'];                          // HTTP/1.0    
    
    $status         = '-';                                                  // page returned status - dont think I can get this 200,401,404 etc..
    $bytes          = '-';                                                  // cant get this - page size / payload size
    
    // Referring URL
    if(isset($_SERVER['HTTP_REFERER'])){
        $referring_url = $_SERVER['HTTP_REFERER']; 
    } else {
        $referring_url = '-';
    }   
    
    // User Agent - if there is no user agent or it cannot be detected then apache uses "-"
    if(isset($_SERVER['HTTP_USER_AGENT']) && ($_SERVER['HTTP_USER_AGENT'] != '')){
        $user_agent = $_SERVER['HTTP_USER_AGENT']; 
    } else {
        $user_agent = '-';
    } 
   
    $log_entry = $remote_ip.' '.$logname.' '.$user.' '.$time.' "'.$method.' '.$uri.' '.$protocol.'" '.$status.' '.$bytes.' "'.$referring_url.'" "'.$user_agent.'"'."\n";
    
    // Write log entry to access log    
    if(!$fp = fopen(ACCESS_LOG,'a')) {        
        force_error_page($_GET['page'], 'file', __FILE__, __FUNCTION__, '', '', gettext("Could not open access.log to save the record."));
        exit;
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
    
    return;
    
}

############################################
#  Write a record to the error.log file    #
############################################

function write_record_to_error_log($login_username, $error_page, $error_type, $error_location, $php_function, $database_error, $error_msg){
    
    // If no logged in user
    if($login_username == '') {
        $login_username = '-';        
    }   

    // Build log entry - perhaps use the apache time stamp below
    $log_entry = $_SERVER['REMOTE_ADDR'].','.$login_username.','.date("[d/M/Y:H:i:s O]", $_SERVER['REQUEST_TIME']).','.$error_page.','.$error_type.','.$error_location.','.$php_function.','.$database_error.','.$error_msg."\n";

    // Write log entry to error.log    
    if(!$fp = fopen(ERROR_LOG,'a')) {        
        force_error_page($_GET['page'], 'file', __FILE__, __FUNCTION__, '', '', gettext("Could not open error.log to save the record."));
        exit;
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
        
    return;
    
}

#########################################################
#   Return Date in correct format from year/month/day   #
#########################################################

// only used in schedule

function convert_year_month_day_to_date($year, $month, $day) {    

    // Ensure months supplied as 2 digits
    if(strlen($month) == 1) {$month = '0'.$month;}
    
    // Ensure days supplied as 2 digits
    if(strlen($day) == 1) {$day = '0'.$day;}
    
    switch(DATE_FORMAT) {

        case '%d/%m/%Y':
        return $day."/".$month."/".$year;

        case '%d/%m/%y':
        return $day."/".$month."/".substr($year, 2);

        case '%m/%d/%Y':
        return $month."/".$day."/".$year;

        case '%m/%d/%y':
        return $month."/".$day."/".substr($year, 2);
            
    }
    
}

#############################################
#    Get Timestamp from year/month/day      #
#############################################

function convert_year_month_day_to_timestamp($schedule_start_year, $schedule_start_month, $schedule_start_day) {  
            
        return DateTime::createFromFormat('!Y/m/d', $schedule_start_year.'/'.$schedule_start_month.'/'.$schedule_start_day)->getTimestamp();   
        
}

##########################################
#   Convert Date into Unix Timestamp     #
##########################################

function date_to_timestamp($date_to_convert){   
    
    // this is just returning the current time
    // http://php.net/manual/en/datetime.createfromformat.php
    // Be warned that DateTime object created without explicitely providing the time portion will have the current time set instead of 00:00:00.
    // can also use - instead of /
    // the ! allows the use without supplying the time portion
    // this works for all formats of dates where as mktime() might be a bit dodgy
    
    switch(DATE_FORMAT) {
        
        case '%d/%m/%Y':         
        return DateTime::createFromFormat('!d/m/Y', $date_to_convert)->getTimestamp();
        
        case '%d/%m/%y':         
        return DateTime::createFromFormat('!d/m/y', $date_to_convert)->getTimestamp();
        
        case '%m/%d/%Y':         
        return DateTime::createFromFormat('!m/d/Y', $date_to_convert)->getTimestamp();

        case '%m/%d/%y':         
        return DateTime::createFromFormat('!m/d/y', $date_to_convert)->getTimestamp(); 
        
    }
    
    /* Alternate method - keep for now
     * // Invoice Date
        if(DATE_FORMAT == "%d/%m/%Y"){
            
            // Invoice Date
            $date_part = explode("/",$VAR['date']);
            $timestamp = mktime(0,0,0,$date_part[1],$date_part[0],$date_part[2]);
            $datef = $timestamp;

            // Invoice Due Date
            $date_part2 = explode("/",$VAR['due_date']);
            $timestamp2 = mktime(0,0,0,$date_part2[1],$date_part2[0],$date_part2[2]);
            $datef2 = $timestamp2;
        }
        if(DATE_FORMAT == "%m/%d/%Y"){
            
            // Invoice Date
            $date_part = explode("/",$VAR['date']);
            $timestamp = mktime(0,0,0,$date_part[0],$date_part[1],$date_part[2]);
            $datef = $timestamp;

            // Invoice Due Date
            $date_part2 = explode("/",$VAR['due_date']);
            $timestamp2 = mktime(0,0,0,$date_part2[0],$date_part2[1],$date_part2[2]);
            $datef2 = $timestamp2;
        }     
     */
      
}

##########################################
#    Date with Time to Unix Timestamp    #
##########################################

// only used in schedule at the minute
// is this the same as mktime(hour,minute,second,month,day,year,is_dst);

function datetime_to_timestamp($date, $hour, $minute, $second, $clock, $meridian = null) {
    
    // When using a 12 hour clock
    if($clock == '12') {
        
        // Create timestamp from date
        $timestamp = date_to_timestamp($date);

        // if hour is 12am set hour as 0 - for correct calculation as no zero hour
        if($hour == '12' && $meridian == 'am'){$hour = '0';}

        // Convert hours into seconds and then add - AM/PM aware
        if($meridian == 'pm'){$timestamp += ($hour * 60 * 60 + 43200 );} else {$timestamp += ($hour * 60 * 60);}    

        // Convert minutes into seconds and add
        $timestamp += ($minute * 60);
        
        // Add seconds
        $timestamp += $second;        
        
        return $timestamp;
        
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
        
        return $timestamp;
        
    }
    
}


##########################################
#     Timestamp to dates                 #
##########################################

// not used anywhere at the minute

function timestamp_to_date($timestamp){    

    switch(DATE_FORMAT) {
        
        case '%d/%m/%Y':
        return date('d/m/Y', $timestamp);        
        
        case '%d/%m/%y':
        return date('d/m/y', $timestamp);       

        case '%m/%d/%Y':
        return date('m/d/Y', $timestamp);        

        case '%m/%d/%y':
        return date('m/d/y', $timestamp);
            
    }

}

##########################################
#   Timestamp to calendar date format    #
##########################################

function timestamp_to_calendar_format($timestamp) {
    
    return date('Ymd', $timestamp);
    
}

##########################################
#   Timestamp to calendar date format    #
##########################################

function get_country_codes($db) {

    $sql = "SELECT * FROM ".PRFX."company_country_list";

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Could not retrieve country codes from the database."));
        exit;        
    } else {
        
        return $rs->GetArray();
        
    }
    
}

##########################################
#          xml2php Gateway               # // this is a legacy function might not be used for long
# Loads language file up as a PHP array  #
##########################################

function gateway_xml2php($module) {    

    $file = QWCRM_PHYSICAL_PATH.LANGUAGE_DIR.THEME_LANGUAGE;

    $xml_parser = xml_parser_create();
    if (!($fp = fopen($file, 'r'))) {
        die('unable to open XML');
    }
    $contents = fread($fp, filesize($file));
    fclose($fp);
    xml_parse_into_struct($xml_parser, $contents, $arr_vals);
    xml_parser_free($xml_parser);

    $xmlarray = array();

    foreach ($arr_vals as $things) {
        if ($things['tag'] != 'TRANSLATE' && $things['value'] != "") {

            $ttag = strtolower($things['tag']);
            $tvalue = $things['value'];
            $xmlarray[$ttag] = $tvalue;

        }
    }

    return $xmlarray;
    
}


###########################################
#  Compress page output and send headers  #
###########################################

/**
 * Checks the accept encoding of the browser and compresses the data before
 * sending it to the client if possible.
 *
 * @return  void
 *
 * @since   11.3
 *
 * From {Joomla}libraries/joomla/application/web.php
 */

/**
 * @package     Joomla.Platform
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2017 - Jon Brown / Quantumwarp.com
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

function compress_page_output($BuildPage)
{
    // Supported compression encodings.
    $supported = array(
        'x-gzip'    => 'gz',
        'gzip'      => 'gz',
        'deflate'   => 'deflate'
    );

    // Get the supported encoding.
    $encodings = array_intersect(browserSupportedCompressionEncodings(), array_keys($supported));

    // If no supported encoding is detected do nothing and return.
    if (empty($encodings))
    {
        return $BuildPage;
    }

    // Verify that headers have not yet been sent, and that our connection is still alive.
    if (headers_sent() || (connection_status() !== CONNECTION_NORMAL))
    {
        return $BuildPage;
    }

    // Iterate through the encodings and attempt to compress the data using any found supported encodings.
    foreach ($encodings as $encoding)
    {
        if (($supported[$encoding] == 'gz') || ($supported[$encoding] == 'deflate'))
        {
            // Verify that the server supports gzip compression before we attempt to gzip encode the data.            
            if (!extension_loaded('zlib') || ini_get('zlib.output_compression'))
            {
                continue;
            }           

            // Attempt to gzip encode the page with an optimal level 4.            
            $gzBuildPage = gzencode($BuildPage, 4, ($supported[$encoding] == 'gz') ? FORCE_GZIP : FORCE_DEFLATE);

            // If there was a problem encoding the data just try the next encoding scheme.            
            if ($gzBuildPage === false)
            {
                continue;
            }            

            // Set the encoding headers.
            header("Content-Encoding: $encoding");

            // Replace the output with the encoded data.            
            return $gzBuildPage;
            
        }
    }
}

####################################################################
#  Get the supported compression algorithms in the client browser  #
####################################################################

function browserSupportedCompressionEncodings() {
        
    return array_map('trim', (array) explode(',', $_SERVER['HTTP_ACCEPT_ENCODING']));

}