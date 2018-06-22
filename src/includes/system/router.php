<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/* Mandatory */
  
############################################
#  Build path to relevant Page Controller  #
############################################

function get_page_controller(&$VAR = null) {        
    
    $config = QFactory::getConfig();    
    $user = QFactory::getUser();
    
    // Maintenance Mode
    if($config->get('maintenance')) {

        // Set to the maintenance page    
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = 'maintenance';        
        $VAR['theme']       = 'off';   

        // If user logged in, then log user off (Hard logout, no logging) - This is needed for 'remember me', purge as they come in.
        if(isset($user->login_token)) {    
            QFactory::getAuth()->logout(); 
        }
        
        goto page_controller_check;
        
    }    
    
    // Check if URL is valid
    if(!check_link_is_valid($_SERVER['REQUEST_URI'])) {

        // Set the error page    
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = 'error';        
        $VAR['theme']       = 'off'; 

        goto page_controller_check;

    }

    // If SEF routing is enabled parse the link - This allows the use of Non-SEF URLS in the SEF enviroment
    if ($config->get('sef') && check_link_is_sef($_SERVER['REQUEST_URI'])) {

        // Set 'component' and 'page_tpl' variables in $VAR for correct routing when using SEF
        parseSEF($_SERVER['REQUEST_URI'], 'set_var', $VAR);

        goto page_controller_check;

    
    }
    
    // Check to see if the page exists otherwise send to the 404 page
    if (isset($VAR['component']) && !isset($VAR['page_tpl']) && !check_page_exists($VAR['component'], $VAR['page_tpl'])) {

        // Set to the 404 error page       
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = '404';            
        $VAR['theme']       = 'off';
        
        goto page_controller_check;

    }  

    // If no page specified set page based on login status
    if(!isset($VAR['component']) && !isset($VAR['page_tpl']) ) {    

        if(isset($user->login_token)) {

            // If logged in
            $VAR['component']           = 'core';
            $VAR['page_tpl']            = 'dashboard';

        } else {

            // If NOT logged in
            $VAR['component']           = 'core';
            $VAR['page_tpl']            = 'home';

        }
        
    }

    page_controller_check:    
    
    // Check the requested page with the current usergroup against the ACL for authorisation, if it fails set page 403
    if(!check_page_acl($VAR['component'], $VAR['page_tpl'])) {

        // Log activity
        $record = _gettext("A user tried to access the following resource without the correct permissions.").' ('.$VAR['component'].':'.$VAR['page_tpl'].')';
        write_record_to_activity_log($record, $VAR['employee_id'], $VAR['customer_id'], $VAR['workorder_id'], $VAR['invoice_id']); 

        // Set to the 403 error page 
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = '403';        
        $VAR['theme']       = 'off';
        
    }    
        
    // Return the page display controller for the requested page    
    return COMPONENTS_DIR.$VAR['component'].'/'.$VAR['page_tpl'].'.php';
    
}


#####################################
#  Build SEF URL from Non-SEF URL   #
#####################################

function buildSEF($non_sef_url) {
    
    // Move URL into an array 
    $parsed_url = parse_url($non_sef_url);

    // Get URL Query Variables
    parse_str($parsed_url['query'], $parsed_url_query);
    
    // Build URL 'Path' from query variables and then remove them as they are no longer needed
    if($parsed_url_query['component'] && $parsed_url_query['page_tpl']) { 
        $sef_url_path = $parsed_url_query['component'].'/'.$parsed_url_query['page_tpl'];
        unset($parsed_url_query['component']);
        unset($parsed_url_query['page_tpl']);    
    } else {
        // Compensate for home/dashboard special case
        $sef_url_path = '';
    }
       
    // Build URL 'Query' if variables present
    if ($parsed_url_query) {
        
        foreach($parsed_url_query as $key => $value) {

            $sef_url_query .= '&'.$key.'='.$value;

        }
        
        // Remove the first & and prepend a ?
        $sef_url_query = '?'.ltrim($sef_url_query, '&');        
            
    }
    
    // Build URL 'Fragement'
    if($parsed_url['fragment']) { $sef_url_fragement = '#'.$parsed_url['fragment']; }
       
    // Build and return full SEF URL
    return QWCRM_BASE_PATH. $sef_url_path . $sef_url_query . $sef_url_fragement;
    
}

#########################################################################################################################
#  Convert a SEF url into a standard url and (optionally) inject routing varibles into $VAR or return routing variables #
#########################################################################################################################

function parseSEF($sef_url, $mode = null, &$VAR = null) {    
    
    // Remove base path from URI
    $sef_url = str_replace(QWCRM_BASE_PATH, '', $sef_url);
    
    // Move URL into an array
    $parsed_url = parse_url($sef_url);

    // Get Variables from path
    $url_segments = array_filter(explode('/', $parsed_url['path']));
    
    // Create a holding variable because page is index.php
    if($mode == 'get_var') { $onlyVar = array(); }
    
    // If there are routing variables
    if ($url_segments) {
        
        // Set $_GET routing variables
        $nonsef_url_path_variables .= '?';
        $nonsef_url_path_variables .= 'component='.$url_segments['0'];
        $nonsef_url_path_variables .= '&page_tpl='.$url_segments['1'];       
        
        // Sets the following routing values into $VAR for routing
        if ($mode == 'get_var') {
            if($url_segments['0'] != '') { $onlyVar['component'] = $url_segments['0']; }
            if($url_segments['1'] != '') { $onlyVar['page_tpl'] = $url_segments['1']; }
        }
        
        // Sets the following routing values into $VAR for routing
        if ($mode == 'set_var') {
            if($url_segments['0'] != '') { $VAR['component'] = $url_segments['0']; }
            if($url_segments['1'] != '') { $VAR['page_tpl'] = $url_segments['1']; }
        }
    
    }
    
    // No further processing needed with 'only_get_var'
    if ($mode == 'get_var') { return $onlyVar; }
    
    // No further processing needed with 'only_set_var'
    if ($mode == 'set_var') { return; }
    
    // Get URL Query Variables (if present)
    if(parse_str($parsed_url['query'], $parsed_url_query_variables)) {
       
        // Build URL 'Query' if variables present
        if ($parsed_url_query_variables) {
            foreach($parsed_url_query_varibles as $key => $value) {
                $nonsef_url_query .= '&'.$key.'='.$value;
            }
            
            // Remove the first & and prepend a ?
            $nonsef_url_query = '?'.ltrim($nonsef_url_query, '&');            
            
        }
        
    }    
    
    // Build URL 'Fragement'
    if($parsed_url['fragment']) { $nonsef_url_fragment = '#'.$parsed_url['fragment']; }
    
    // Build and return full SEF URL
    return 'index.php' . $nonsef_url_path_variables . $nonsef_url_query . $nonsef_url_fragment;
    
}

#######################
#  Check page exists  #
#######################

function check_page_exists($component = null, $page_tpl = null) {
    
    $db = QFactory::getDbo();

    // If a valid page has not been submitted
    if($component == null || $page_tpl == null) { return false; }

    // Check the controller file exists
    if (!file_exists(COMPONENTS_DIR.$component.'/'.$page_tpl.'.php')) { return false;  }
    
    // Check to see if the page exists in the ACL
    $sql = "SELECT page FROM ".PRFX."user_acl_page WHERE page = ".$db->qstr($component.':'.$page_tpl);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the page exists in the ACL."));
    } else {
        
        if($rs->RecordCount() == 1) {
        
            return true;
            
        } else {
            
            return false;
            
        }
            
    }
    
}

#######################################
#  Check to see if the link is valid  #
#######################################

function check_link_is_valid($url) {    
    
    // Get URL path
    $url = parse_url($url, PHP_URL_PATH);

    // Remove base path from URL path
    $url = str_replace(QWCRM_BASE_PATH, '', $url);

    // index.php can only be in the root, anywhere else is bad
    if (preg_match('|index\.php|U', $url) && !preg_match('|^index\.php|U', $url)) {
        
        // is not valid
        return false;
        
    }

    // is valid
    return true;     
    
}

#####################################
#  Check to see if the link is SEF  #
#####################################

function check_link_is_sef($url) {
    
    // Get URL path
    $url = parse_url($url, PHP_URL_PATH);

    // Remove base path from URL path
    $url = str_replace(QWCRM_BASE_PATH, '', $url);

    // If start with index.php == Non SEF
    if (preg_match('|^index\.php|U', $url)) {
        return false;        
    }
    
    // if root '/' - This can be either SEF or Non SEF so return Non SEF
    if($url == '') {        
        return false;
    }

    // Is SEF
    return true;       
    
}

###################################################
#  Build non sef url from component and page_tpl  #
###################################################

function build_url_from_variables($component, $page_tpl, $url_length = 'basic', $url_sef = 'auto') {
    
    // Set URL Type to return
    if($url_sef == 'sef') { $sef = true; }
    elseif($url_sef == 'nonsef') { $sef = false; }
    else { $sef = QFactory::getConfig()->get('sef'); }    
    //else { $sef = $config->sef; } 
    
    // Full URL (nonsef)
    if($url_length == 'full') {   
        $url = QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH .'index.php?component='.$component.'&page_tpl='.$page_tpl;    
        
    // Relative URL (nonsef)
    } elseif($url_length == 'relative') {
        $url = QWCRM_BASE_PATH.'index.php?component='.$component.'&page_tpl='.$page_tpl;
    
    // Basic URL
    } elseif($url_length == 'basic') {
        $url = 'index.php?component='.$component.'&page_tpl='.$page_tpl;
    }
    
    // Convert to SEF if set
    if($sef) {
        $url = buildSEF($url);
    }
    
    return $url;
    
}

############################################
#  Validate links and prep SEF enviroment  #
############################################

function get_routing_variables_from_url($url) {
    
    $user = QFactory::getUser();
    
    // Check if URL is valid
    if(!check_link_is_valid($_SERVER['REQUEST_URI'])) {
        
        return false;

    } else {    

        // Running parseSEF only when the link is a SEF allows the use of Non-SEF URLS aswell
        if (check_link_is_sef($url)) {

            // Set 'component' and 'page_tpl' variables in $VAR for correct routing when using SEF           
            $VAR = parseSEF($url, 'get_var');

        // non-sef url
        } else {
            
            // Get URL Query Variables
            parse_str(parse_url($url, PHP_URL_QUERY), $parsed_url_query);            
            
            // Set only routing variables if they exist
            if(isset($parsed_url_query['component'])) { $VAR['component'] = $parsed_url_query['component']; }
            if(isset($parsed_url_query['page_tpl'])) { $VAR['page_tpl'] = $parsed_url_query['page_tpl']; }
            
        }
        
        // If $VAR is empty it is because page is index.php, set required
        if($VAR['component'] == '' && $VAR['page_tpl'] == '') {
            
            if(isset($user->login_token)) {

                // If logged in
                $VAR['component']           = 'core';
                $VAR['page_tpl']            = 'dashboard';

            } else {

                // If NOT logged in
                $VAR['component']           = 'core';
                $VAR['page_tpl']            = 'home';

            }
            
        }   

    }
    
    return $VAR;

}

/** Other Functions **/

