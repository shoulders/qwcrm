<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Other Functions **/

############################################
#  Force SSL/HTTPS (if enabled)            #
############################################

function force_ssl($force_ssl_config) {
    
    // Force SSL/HTTPS if enabled - add base path stuff here
    if($force_ssl_config >= 1 && !isset($_SERVER['HTTPS'])) {   
        force_page($_SERVER['REQUEST_URI'], null, null, 'auto', 'auto', 'https' );
    }

}

############################################
#  Check page has been internally refered  #
############################################

function check_page_accessed_via_qwcrm($component = null, $page_tpl = null, $access_rule = null) {
   
    // Get Referer
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    
    /* BOF Access Rules */
    
    // Override - Return true always
    if($access_rule == 'override') {
        return true;        
    }    
    
    // Index - Allows the specified page and homepage
    if($access_rule == 'index') {
                
        // Allow the referer to be the homepage (sef/nonsef)
        if(preg_match('|^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH, '/').'(index/.php)?$|U', $referer)) {
            return true;            
        }   
    
    }
    
    // Setup - Allows page access during a setup process but not direct access
    if($access_rule == 'setup') {
        
        if(defined('QWCRM_SETUP') && !confirm_direct_access($component, $page_tpl)) {
            return true;            
        } else {
            return false;        
        }        
    
    }    
    
    // No Referer - Allows direct access when no referer (not currently used)
    if($access_rule == 'no_referer') {
        
        // Allow direct access during setup
        if(!$referer) { return true; } 
    
    }
    
    /* EOF Access Rules */
    
    // If no referer (the page was not accessed via QWcrm) and if a setup procedure is not occuring
    if(!$referer) { return false; }           
    
    // Allow the referer to be the homepage (sef/nonsef)
    if($component == 'index.php' && !$page_tpl) {        
        return preg_match('|^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH, '/').'(index/.php)?$|U', $referer);            
    }
    
    // Check if a 'SPECIFIC' QWcrm page is the referer
    if($component && $page_tpl) {       

        // If 'Referring Page' matches the specified page (returns true/false as needed)
        return preg_match('/^'.preg_quote(build_url_from_variables($component, $page_tpl, 'absolute', 'auto'), '/').'/U', $referer);
        
    // Check if 'ANY' QWcrm page is the referer (returns true/false as needed)
    } else {        
        return preg_match('/^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH, '/').'/U', $referer);        
    }
    
    return false;
    
}

##################################################
#   Has the requested page been access directly  #
##################################################

function confirm_direct_access($component, $page_tpl) {
    
    if($_SERVER['REQUEST_URI'] === build_url_from_variables($component, $page_tpl, $url_length = 'relative')) {
        
        return true;
        
    } else{
        
        return false;
        
    }   
    
}

################################################
#  Get Vistor IP address                       #
################################################

/*
 * This attempts to get the real IP address of the user 
 */

function get_visitor_ip_address() {    
    
    $http_client_ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : null;
    $http_x_forwarded_for = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;    
    
    if($http_client_ip) {
        $ip_address = $http_client_ip;        
    }
    elseif($http_x_forwarded_for) {
        $ip_address = $http_x_forwarded_for;        
    }
    elseif($remote_addr) {
        $ip_address = $remote_addr;        
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