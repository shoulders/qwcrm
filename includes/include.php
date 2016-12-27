<?php

/** Main Include File **/

#####################################
#   Redirect page with javascript   #
#####################################

/*
 * If no $page_tpl and $variables are supplied then this function 
 * will force a URL redirect exactly how it was supplied 
 * 
 * force_page($module, $page_tpl = Null, $variables = Null, $method = null)
 * method could be null / $_GET / $_SESSION - i dont need to use $_POST. I just have a flag that wipes session data stored by force_page - i would also need to add it to the array merge
 */

function force_page($module, $page_tpl = Null, $variables = Null) {
    
    if($page_tpl === Null && $variables === Null){
        
        // Normal URL Redirect
        echo('
                <script>
                    window.location = "'.$module.'"
                </script>
            ');
        
    } elseif ($page_tpl != Null && $variables === Null){
    
        // Normal URL Redirect with no starting '&' for variable string 
        echo('
                <script>
                    window.location = "index.php?page='.$module.':'.$page_tpl.'"
                </script>
            ');
         
    } else {
        
        // QWcrm Style Redirect
        echo('
                <script>
                    window.location = "index.php?page='.$module.':'.$page_tpl.'&'.$variables.'"
                </script>
            ');
    }
    
}

   
    /* redirect using headers (fron auth.php) - Joomla uses header redirect not a javascript one
    function performRedirect($addFromQuery){        
         
        if ($addFromQuery){            
            header('Location: ' . $this->redirect . '?from=' . $_SERVER['REQUEST_URI']);
        } else {
            header('Location: ' . $this->redirect);
        }        
        exit();            
    }     
     */ 

// 26-11-16 - working correct error statement
//force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));

############################################
#  Language Translation Function           #
############################################

// remove error control from the modules and add it here.

function xml2php($module){
    
    global $smarty;

    $file = LANGUAGE_DIR.THEME_LANGUAGE;

    $xml_parser = xml_parser_create();
    if (!($fp = fopen($file, 'r'))) {
       die('unable to open XML');
    }
    $contents = fread($fp, filesize($file));
    fclose($fp);
    xml_parse_into_struct($xml_parser, $contents, $arr_vals);   
    xml_parser_free($xml_parser); //does this function exist?

    foreach($arr_vals as $things){
        if($things['tag'] != 'TRANSLATE' && $things['value'] != "" ){
            $smarty->assign('translate_'.strtolower($things['tag']),$things['value']);
        }
    }    

    return true;
    
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
        $smarty->assign('page_title', $smarty->get_template_vars('translate_'.$module.'_'.$page_tpl.'_header_page_title'));
    }    
    
    // Meta Tags
    $smarty->assign('meta_description', $smarty->get_template_vars('translate_'.$module.'_'.$page_tpl.'_header_meta_description')   );
    $smarty->assign('meta_keywords',    $smarty->get_template_vars('translate_'.$module.'_'.$page_tpl.'_header_meta_keywords')      );
    
    return;
}

##########################################################
#  Verify User's authorization for a specific page       #
##########################################################

function check_acl($db, $login_account_type_id, $module, $page_tpl){
    
    global $smarty;
    
    /* error catching - you cannot use normal error logging as it will cause a loop */
    if($login_account_type_id == ''){
        echo $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_no_account_type_id');
        die;        
    }

    /* Get user's Group Name by login_account_type_id */
    $q = 'SELECT '.PRFX.'CONFIG_EMPLOYEE_TYPE.TYPE_NAME
            FROM '.PRFX.'CONFIG_EMPLOYEE_TYPE 
            WHERE TYPE_ID ='.$db->qstr($login_account_type_id);
    
    if(!$rs = $db->execute($q)) {        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_group_name_failed'));
        exit;
    } else {
        $employee_acl_account_type_display_name = $rs->fields['TYPE_NAME'];
    } 
    
    // Build the page name for the ACL lookup
    $module_page = $module.':'.$page_tpl;
    
    /* Check Page to see if we have access */
    $q = "SELECT ".$employee_acl_account_type_display_name." AS PAGE_ACL FROM ".PRFX."ACL WHERE page=".$db->qstr($module_page);

    if(!$rs = $db->execute($q)) {       
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=authentication&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_get_page_acl_failed'));
        exit;
    } else {
        $acl = $rs->fields['PAGE_ACL'];
        
        // Add if guest (6) rules here if there are errors
        
        if($acl != 1) {            
            force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=authentication&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_no_page_permission'));
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

    global $smarty;
    
    // If the lock file is not present QWcrm has not been installed - redirect to the installation directory
    if(!is_file('cache/lock')){
        echo('
                <script>            
                    window.location = "install"           
                </script>
            ');
    }
        
    // has been installed but the installion directory is still present  
    if(is_dir('install') ) {
        echo('<div style="color: red;">'.$smarty->get_template_vars('translate_system_include_advisory_message_function_verify_qwcrm_is_installed_correctly_install_directory_exists').'</div>');
        die;
    }
    
    // has been installed but the upgrade directory is still present  
    if(is_dir('install') ) {
        echo('<div style="color: red;">'.$smarty->get_template_vars('translate_system_include_advisory_message_function_verify_qwcrm_is_installed_correctly_upgrade_directory_exists').'</div>');
        die;
    }    

    // Compare the version number of the file system against the database - if mismatch load upgrade for further instructions?
    if(version_compare(get_qwcrm_database_version_number($db), QWCRM_VERSION, '!=')){
        
        // I have not decided whether to use a message or automatic redirect to the upgrade folder        
        echo('<div style="color: red;">'.$smarty->get_template_vars('translate_system_include_advisory_message_function_verify_qwcrm_is_installed_correctly_file_database_versions_dont_match').'</div>');
        die;
        
        /*
        echo('
            <script>            
                window.location = "upgrade"           
            </script>
        ');
        */
        
    }    
}

################################################
#  Get QWcrm version number from the database  #
################################################

function get_qwcrm_database_version_number($db){
    
    global $smarty;

    $q = 'SELECT * FROM '.PRFX.'VERSION ORDER BY '.PRFX.'VERSION.`VERSION_INSTALLED` DESC LIMIT 1';
    
    if(!$rs = $db->execute($q)){        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return $rs->fields['VERSION_INSTALLED'];
    }
}

################################################
#  Get company info - indivudual items         #
################################################

/*
 * This combined function allows you to pull any of the company information individually
 * or return them all as an array
 * supply the required field name or all to return all of them as an array
 */

function get_company_info($db, $item){
    
    global $smarty;

    $q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
    
    if(!$rs = $db->execute($q)){        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        if($item === 'all'){            
            return $rs->GetArray();            
        } else {
            return $rs->fields[$item];          
        }        
    }
    
}

################################################
#  Get setup info - individual items           # 
################################################

/*
 * This combined function allows you to pull any of the setup information individually
 * or return them all as an array
 * supply the required field name or all to return all of them as an array
 */

function get_setup_info($db, $item){
    
    global $smarty;

    $sql = 'SELECT * FROM '.PRFX.'SETUP';
    
    if(!$rs = $db->execute($sql)){        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        if($item === 'all'){            
            return $rs->GetArray();            
        } else {
            return $rs->fields[$item];          
        }        
    }
    
}

####################################################################
#  Encryption Routine using the secret key from configuration.php  #
####################################################################

function encrypt($strString, $secretKey){
    
    $deresult = '';
    
    for($i=0; $i<strlen($strString); $i++){
        $char       =   substr($strString, $i, 1);
        $keychar    =   substr($secretKey, ($i % strlen($secretKey))-1, 1);
        $char       =   chr(ord($char)+ord($keychar));
        $deresult  .=   $char;
    }    
    
    return base64_encode($deresult);
    
}

####################################################################
#  Deryption Routine using the secret key from configuration.php   #
####################################################################

function decrypt($strString, $secretKey){
     
    $deresult = '';
    base64_decode($strstring);
    
    for($i=0; $i<strlen($strString); $i++){
        $char       =   substr($strString, $i, 1);
        $keychar    =   substr($secretKey, ($i % strlen($secretKey))-1, 1);
        $char       =   chr(ord($char)-ord($keychar));
        $deresult  .=   $char;
    }
    
    return $deresult;
    
}

###################################################################################
#  Alternate encrytption routines - Not Used - might be for something (Untested)  #
###################################################################################

/*
function encrypt($strString, $secretKey){

	if ($strString == '') {
            return $strString;
	}
        
	$iv         = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$enString   = mcrypt_ecb(MCRYPT_BLOWFISH, $secretKey, $strString, MCRYPT_ENCRYPT, $iv);
	$enString   = bin2hex($enString);

	return ($enString);
	
}
*/

###################################################################################
#  Alternate Decrytption routines - Not Used - might be for something (Untested)  #
###################################################################################

/*
function decrypt($strString, $secretKey){
	
	if ($strString == '') {
            return $strString;
	}
        
	$iv         = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$strString  = hex2bin($strString);
	$deString   = mcrypt_ecb(MCRYPT_BLOWFISH, $secretKey, $strString, MCRYPT_DECRYPT, $iv);

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
    if(getenv('HTTP_CLIENT_IP')){
        $ip = getenv('HTTP_CLIENT_IP');        
    }
    elseif(getenv('HTTP_X_FORWARDED_FOR')){
        $ip = getenv('HTTP_X_FORWARDED_FOR');        
    }
    elseif(getenv('REMOTE_ADDR')){
        $ip = getenv('REMOTE_ADDR');        
    }
    else {$ip = 'UNKNOWN';}
    
    return $ip;
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
    
   global $smarty;
    
   $q = 'INSERT into '.PRFX.'TRACKER SET
   date          = '. $db->qstr( time()                     ).',
   ip            = '. $db->qstr( getIP()                    ).',
   uagent        = '. $db->qstr( getenv('HTTP_USER_AGENT')  ).',
   full_page     = '. $db->qstr( $page_display_controller   ).',
   module        = '. $db->qstr( $module                    ).',
   page          = '. $db->qstr( $page_tpl                  ).',
   referer       = '. $db->qstr( getenv('HTTP_REFERER')     );

   if(!$rs = $db->Execute($q)) {
      force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
      exit;      
   }
    
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
    
    global $qwcrm_activity_log;

    // if activity logging not enabled exit
    if($qwcrm_activity_log != true){return;}
    
    // Build log entry - perhaps use the apache time stamp below
    $log_entry = $_SERVER['REMOTE_ADDR'] . ',' . date(DATE_W3C) . ',' . $record . "\n";
    
    // Write log entry to access log    
    if(!$fp = fopen(ACTIVITY_LOG,'a')) {        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
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

function write_record_to_access_log($login_usr = Null){
    
    // Apache log format
    // https://httpd.apache.org/docs/2.4/logs.html
    // http://docstore.mik.ua/orelly/webprog/pcook/ch11_14.htm
    // Combined Log Format - LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"" combined
    // $remote_host, $logname, $user, $time, $method, $request, $protocol, $status, $bytes, $referer, $user_agent
    
    $remote_ip      = $_SERVER['REMOTE_ADDR'];                              // only using IP - not hostname lookup
    $logname        = '-';                                                  //  This is the RFC 1413 identity of the client determined by identd on the clients machine. This information is highly unreliable and should almost never be used except on tightly controlled internal networks.
    
    // Login User - substituting qwcrm user for the traditional apache HTTP Authentication - check that isset works on variabels from null $_POST
    if($login_usr == ''){
        $user = '-';
    } else {
        $user = $login_usr;  
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
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
    
    return;    
}

############################################
#  Write a record to the error.log file    #
############################################

function write_record_to_error_log($login_usr = '-', $error_page, $error_type, $error_location, $php_function, $database_error, $error_msg){
    
    global $smarty;
    
    /* If no logged in user
    if($login_usr == ''){
        $login_usr = '-';        
    }*/    

    // Build log entry - perhaps use the apache time stamp below
    $log_entry = $_SERVER['REMOTE_ADDR'].','.$login_usr.','.date("[d/M/Y:H:i:s O]", $_SERVER['REQUEST_TIME']).','.$error_page.','.$error_type.','.$error_location.','.$php_function.','.$database_error.','.$error_msg."\n";

    // Write log entry to error.log    
    if(!$fp = fopen(ERROR_LOG,'a')) {        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
        
    return;    
}

// check this
/* current error - get is used because it is a super global to grab page title = simple*/
// force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_core_error_message_function_'.__FUNCTION__.'_failed'));

############################################
#  Error Handling - Data preperation       #
############################################

function prepare_error_data($type, $data = Null){

    /* Error Page (by referring page) - only needed when using referrer - not currently used 
    if($type === 'error_page'){
     */
        
        // extract the qwcrm page reference from the url      
        // preg_match('/^.*\?page=(.*)&.*/U', getenv('HTTP_REFERER'), $page_string);
                
      /*  // compensate for home and login pages
        if($page_string[1] == ''){     
            // Must be Login or Home
            if(isset($_SESSION['login_hash'])){
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
    if($type === 'error_page'){
        
        // compensate for home and login pages
        if($data == ''){     
            // Must be Login or Home
            if(isset($_SESSION['login_hash'])){
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
               
        // remove qwcrm base physical webroot path thing
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
        if($data != ''){$data.= '()';}        
        return $data;
    }
    
    /* Database Error */
    if($type === 'database_error'){

        // remove newlines from the database string
        if($data != ''){$data = str_replace("\n",'',$data);}  
        return $data;
    }
}

#########################################################
#   Return Date in correct format from year/month/day   #
#########################################################

// only used in schedule

function convert_year_month_day_to_date($schedule_start_year, $schedule_start_month, $schedule_start_day) {
    
        switch(DATE_FORMAT) {
            
            case '%d/%m/%Y':
            return $schedule_start_day."/".$schedule_start_month."/".$schedule_start_year;

            case '%d/%m/%y':
            return $schedule_start_day."/".$schedule_start_month."/".substr($schedule_start_year, 2);

            case '%m/%d/%Y':
            return $schedule_start_month."/".$schedule_start_day."/".$schedule_start_year;

            case '%m/%d/%y':
            return $schedule_start_month."/".$schedule_start_day."/".substr($schedule_start_year, 2);
            
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
    //Be warned that DateTime object created without explicitely providing the time portion will have the current time set instead of 00:00:00.
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