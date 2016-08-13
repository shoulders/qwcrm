<?php

/** Main Include File **/

#####################################
#   Redirect page with javascript   #
#####################################

function force_page($module, $page = Null, $variables = Null) {
    echo('
        <script type="text/javascript">
            window.location = "index.php?page='.$module.':'.$page.'&'.$variables.'"
        </script>
        ');
}

#####################################################
#  function force_page_external($page) - if neeed   #
#####################################################

// function goes here



############################################
#  Language Translation Function           #
############################################

// remove error control from the modules and add it here.

function xml2php($module) {
    global $smarty;

    //$file = FILE_ROOT."language".SEP.$module.SEP.LANG ;
    $file = 'language/'.LANG ;

    $xml_parser = xml_parser_create();
    if (!($fp = fopen($file, 'r'))) {
       die('unable to open XML');
    }
    $contents = fread($fp, filesize($file));
    fclose($fp);
    xml_parse_into_struct($xml_parser, $contents, $arr_vals);   
    xml_parser_free($xml_parser);

    foreach($arr_vals as $things){
        if($things['tag'] != 'TRANSLATE' && $things['value'] != "" ){
            $smarty->assign('translate_'.strtolower($things['tag']),$things['value']);
        }
    }    

    return true;
}

##########################################################
#  Verify Employee's authorization for a specific page   #
##########################################################

function check_acl($db, $login_id, $module, $page){
    
    // if $_SESSION['login_id'] is not set, this goes mental into a loop
    
    // so add if login_id does not exit under any format, die, force logout, this will prevent dodgy logins

    /* Get Employee Account Type (Group ID) */
    $q = 'SELECT '.PRFX.'CONFIG_EMPLOYEE_TYPE.TYPE_NAME
            FROM '.PRFX.'TABLE_EMPLOYEE,'.PRFX.'CONFIG_EMPLOYEE_TYPE 
            WHERE '.PRFX.'TABLE_EMPLOYEE.EMPLOYEE_TYPE  = '.PRFX.'CONFIG_EMPLOYEE_TYPE.TYPE_ID AND EMPLOYEE_ID='.$db->qstr($login_id);
    
    if(!$rs = $db->execute($q)) {
        force_page('core','error&error_msg=Could not get Group ID for user');
        exit;
    } else {
        $employee_acl_account_type = $rs->fields['TYPE_NAME'];
    }

    /* Check Page to see if we have access */ // this hould not be needed - no page etc force logout must incase
    if(!isset($module)) {
        $module_page = 'core:home';
    } else {
        $module_page = $module.':'.$page;
    }
    
    $q = "SELECT ".$employee_acl_account_type." AS PAGE_ACL FROM ".PRFX."ACL WHERE page=".$db->qstr($module_page);

    if(!$rs = $db->execute($q)) {
        force_page('core','error&error_msg=Could not get Page ACL'.$db->ErrorMsg());
        exit;
    } else {
        $acl = $rs->fields['PAGE_ACL'];
        if($acl != 1) {
            return false;	
        } else {
            return true;	
        }
    }
}

############################################
#  Verify QWcrm is installed correctly     #
############################################

function verify_qwcrm_is_installed_correctly($db){

    // If the lock file is not present QWcrm has not been installed - redirect to the installation directory
    if(!is_file('cache/lock')){
        echo('
                <script type="text/javascript">            
                    window.location = "install"           
                </script>
            ');
    }
        
    // has been installed but the installion directory is still present  
    if(is_dir('install') ) {
        echo('<a style="color: red;">The install Directory Exists!! Please Rename or remove the install directory.</a>');
        die;
    }
    
    // has been installed but the upgrade directory is still present  
    if(is_dir('install') ) {
        echo('<a style="color: red;">The Upgrade Directory Exists!! Please Rename or remove the upgrade directory.</a>');
        die;
    }    

    // Compare the version number of the file system against the database - if mismatch load upgrade for further instructions
    if(version_compare(get_qwcrm_database_version_number($db), QWCRM_VERSION, '!=')){
        
        // I have not decides to use a message or automatic redirect to the upgrade folder
        
        echo('<a style="color: red;">The File System and Database versions do not match, run the upgrade routine</a>');
        die;
        
        /*
        echo('
            <script type="text/javascript">            
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

    $q = 'SELECT * FROM '.PRFX.'VERSION ORDER BY '.PRFX.'VERSION.`VERSION_INSTALLED` DESC LIMIT 1';
    if(!$rs = $db->execute($q)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
}

################################################
#  Get company logo location                  #
################################################

function get_company_logo($db){    

    $q = 'SELECT COMPANY_LOGO FROM '.PRFX.'TABLE_COMPANY';
    if(!$rs = $db->execute($q)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        return $rs->fields['COMPANY_LOGO'];
    }
}

################################################
#  Get currency symbol                         #
################################################

function get_currency_symbol($db){    

    $q = 'SELECT COMPANY_CURRENCY_SYMBOL FROM '.PRFX.'TABLE_COMPANY';
    if(!$rs = $db->execute($q)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        return $rs->fields['COMPANY_CURRENCY_SYMBOL'];
    }
}

################################################
#  Get currency symbol                         #
################################################

function get_date_format($db){    

    $q = 'SELECT COMPANY_DATE_FORMAT FROM '.PRFX.'TABLE_COMPANY';
    if(!$rs = $db->execute($q)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        return $rs->fields['COMPANY_DATE_FORMAT'];
    }
}

####################################################################
#  Encryption Routine using the secret key from configuration.php  #
####################################################################

function encrypt($strString, $strKey){
    
    $deresult = '';
    
    for($i=0; $i<strlen($strString); $i++){
        $char       =   substr($strString, $i, 1);
        $keychar    =   substr($strKey, ($i % strlen($strKey))-1, 1);
        $char       =   chr(ord($char)+ord($keychar));
        $deresult  .=   $char;
    }    
    return base64_encode($deresult);
}

####################################################################
#  Deryption Routine using the secret key from configuration.php   #
####################################################################

function decrypt($strString, $strKey){
     
    $deresult = '';
    base64_decode($strstring);
    
    for($i=0; $i<strlen($strString); $i++){
        $char       =   substr($strString, $i, 1);
        $keychar    =   substr($strKey, ($i % strlen($strKey))-1, 1);
        $char       =   chr(ord($char)-ord($keychar));
        $deresult  .=   $char;
    }
    return $deresult;
}

###################################################################################
#  Alternate encrytption routines - Not Used - might be for something (Untested)  #
###################################################################################

/*
function encrypt($strString, $strKey){

	if ($strString == '') {
            return $strString;
	}
        
	$iv         = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$enString   = mcrypt_ecb(MCRYPT_BLOWFISH, $strKey, $strString, MCRYPT_ENCRYPT, $iv);
	$enString   = bin2hex($enString);

	return ($enString);
	
}
*/

###################################################################################
#  Alternate Decrytption routines - Not Used - might be for something (Untested)  #
###################################################################################

/*
function decrypt($strString, $strKey){
	
	if ($strString == '') {
            return $strString;
	}
        
	$iv         = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$strString  = hex2bin($strString);
	$deString   = mcrypt_ecb(MCRYPT_BLOWFISH, $strKey, $strString, MCRYPT_DECRYPT, $iv);

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

################################################
#  Write a record to the Tracker Table         #
################################################

function write_record_to_tracker_table($db, $page_display_controller, $page, $module){
    
   $q = 'INSERT into '.PRFX.'TRACKER SET
   date          = '. $db->qstr( time()                     ).',
   ip            = '. $db->qstr( getIP()                    ).',
   uagent        = '. $db->qstr( getenv('HTTP_USER_AGENT')  ).',
   full_page     = '. $db->qstr( $page_display_controller   ).',
   module        = '. $db->qstr( $module                    ).',
   page          = '. $db->qstr( $page                      ).',
   referer       = '. $db->qstr( getenv('HTTP_REFERER')     );

   if(!$rs = $db->Execute($q)) {
      echo 'Error inserting tracker :'. $db->ErrorMsg();
   }
    
}


############################################
#  Write a record to the activity log file #
############################################

/*
 * This writes Specific QWcrm activity note to the log, i.e. login/logout
 */

function write_record_to_activity_log($record){
    
    // Build log entry
    $log_entry = $_SERVER['REMOTE_ADDR'] . ',' . date(DATE_W3C) . ',' . $record . "\n";
    
    // Apache log format
    // https://httpd.apache.org/docs/1.3/logs.html
    // Combined Log Format - LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"" combined
    // 127.0.0.1 - frank [10/Oct/2000:13:55:36 -0700] "GET /apache_pb.gif HTTP/1.0" 200 2326 "http://www.example.com/start.html" "Mozilla/4.08 [en] (Win98; I ;Nav)"
    
    // Write log entry to access log    
    $fp = fopen(ACTIVITY_LOG,'a') or die($smarty->get_template_vars('translate_include_error_message_cant_open_activity_log').': '.$php_errormsg);
    fwrite($fp, $log_entry);
    fclose($fp);
    
    return;    
}

############################################
#  Write a record to the access log file   #
############################################

/*
 * This will create an apache compatible access log (Combined Log Format)
 */

function write_record_to_access_log($login_usr = Null){

    // Build log entry
    //$log_entry = $_SERVER['REMOTE_ADDR'] . ',' . date(DATE_W3C) . ',' . $record . "\n";
    
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
    $request_method = $_SERVER['REQUEST_METHOD'];                           // GET/POST
    $url            = $_SERVER['REQUEST_URI'];                              // the URL
    $protocol       = $_SERVER['SERVER_PROTOCOL'];                          // HTTP/1.0    
    $status         = '-';                                                  // dont think I can get this 200,401,404 etc..
    $bytes          = '-';                                                  // cant get this - page size / payload size
    
    // Referring URL
    if(isset($_SERVER['HTTP_REFERER'])){
        $referring_url = $_SERVER['HTTP_REFERER']; 
    } else {
        $referring_url = '-';
    }   
    
    // User Agent - check this logic can useragent be set with nothinh in it and then does apache return "-"
    if(isset($_SERVER['HTTP_USER_AGENT']) && ($_SERVER['HTTP_USER_AGENT'] != '')){
        $user_agent = $_SERVER['HTTP_USER_AGENT']; 
    } else {
        $user_agent = '-';
    } 
   
    $log_entry = $remote_ip.' '.$logname.' '.$user.' '.$time.' "'.$request_method.' '.$url.' '.$protocol.'" '.$status.' '.$bytes.' "'.$referring_url.'" "'.$user_agent.'"'."\n";
    
    // Write log entry to access log    
    $fp = fopen(ACCESS_LOG,'a') or die($smarty->get_template_vars('translate_include_error_message_cant_open_access_log').': '.$php_errormsg);
    fwrite($fp, $log_entry);
    fclose($fp);
    
    return;    
}