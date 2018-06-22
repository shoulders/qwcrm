<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

// Force SSL/HTTPS if enabled - add base path stuff here
if(QFactory::getConfig()->get('force_ssl') >= 1 && !isset($_SERVER['HTTPS'])) {   
    force_page($_SERVER['REQUEST_URI'], null, null, 'auto', 'auto', 'https' );
}

// add security routines here
// post get varible sanitisation
// url checking,
// sql injection

/** Other Functions **/

############################################
#  Check page has been internally refered  #
############################################

function check_page_accessed_via_qwcrm($component = null, $page_tpl = null, $access_rule = null) {
    
    // If override is set, return true
    if($access_rule == 'override') {return true;}
    
    // If no referer, page was not access via QWcrm and a setup procedure is not occuring
    if(!getenv('HTTP_REFERER') && $access_rule != 'setup') {return false;}
    
    // Check if a 'SPECIFIC' QWcrm page is the referer
    if($component != null && $page_tpl != null) {       
        
        // If supplied page matches the 'Referring Page'
        if(preg_match('/^'.preg_quote(build_url_from_variables($component, $page_tpl, 'full', 'auto'), '/').'/U', getenv('HTTP_REFERER'))) {
            
            return true;
            
        }
        
        // Setup Access Rule - allow direct access (useful for setup routines and some system pages)
        if(!getenv('HTTP_REFERER') && $access_rule == 'setup') {          
            
            return true;

        }
        
        // Page was not accessed via QWcrm
        return false;
                  
        
    // Check if 'ANY' QWcrm page is the referer (returns true/false as needed)
    } else {
        
        return preg_match('/^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH, '/').'/U', getenv('HTTP_REFERER'));       
        
    }
    
}

################################################
#  Get Vistor IP address                       #
################################################

/*
 * This attempts to get the real IP address of the user 
 */

function get_visitor_ip_address() {
    
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
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip=$_SERVER['REMOTE_ADDR'];
}

echo ('My real IP is:'.$ip);
*/

/* Encryption */

####################################################################
#  Encryption Routine using the secret key from configuration.php  #  // Not currently used
####################################################################

function encrypt($strString, $secret_key) {
    
    $deresult = '';
    
    for($i=0; $i<strlen($strString); $i++) {
        $char       =   substr($strString, $i, 1);
        $keychar    =   substr($secret_key, ($i % strlen($secret_key))-1, 1);
        $char       =   chr(ord($char)+ord($keychar));
        $deresult  .=   $char;
    }    
    
    return base64_encode($deresult);
    
}

####################################################################
#  Deryption Routine using the secret key from configuration.php   #  // Not currently used
####################################################################

function decrypt($strString, $secret_key) {
     
    $deresult = '';
    base64_decode($strString);
    
    for($i=0; $i<strlen($strString); $i++) {
        $char       =   substr($strString, $i, 1);
        $keychar    =   substr($secret_key, ($i % strlen($secret_key))-1, 1);
        $char       =   chr(ord($char)-ord($keychar));
        $deresult  .=   $char;
    }
    
    return $deresult;
    
}

########################################################################
#  Alternate encrytption routines - Not Used - might be for something  #  // Untested
########################################################################

/*
function encrypt($strString, $secret_key) {

	if ($strString == '') {
            return $strString;
	}
        
	$iv         = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$enString   = mcrypt_ecb(MCRYPT_BLOWFISH, $secret_key, $strString, MCRYPT_ENCRYPT, $iv);
	$enString   = bin2hex($enString);

	return ($enString);
	
}
*/

########################################################################
#  Alternate Decrytption routines - Not Used - might be for something  #  // Untested
########################################################################

/*
function decrypt($strString, $secret_key) {
	
	if ($strString == '') {
            return $strString;
	}
        
	$iv         = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$strString  = hex2bin($strString);
	$deString   = mcrypt_ecb(MCRYPT_BLOWFISH, $secret_key, $strString, MCRYPT_DECRYPT, $iv);

	return ($deString);

}
*/