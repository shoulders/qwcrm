<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/* Mandatory */

// If SEF routing is enabled
if ($QConfig->sef) {
    
    // Running parseSEF only when the link is a SEF allows the use of Non-SEF URLS aswell
    if (check_link_is_sef($_SERVER['REQUEST_URI'])) {
        
        // Set 'component' and 'page_tpl' variables in $VAR for correct routing when using SEF
        parseSEF($_SERVER['REQUEST_URI'], $VAR, true);
    
    }
    
}
    
// Get the page controller
$page_controller = get_page_controller($db, $VAR, $QConfig, $user, $employee_id, $customer_id, $workorder_id, $invoice_id);

############################################
#  Build path to relevant Page Controller  #
############################################

function get_page_controller($db, &$VAR = null, $QConfig = null, $user = null, $employee_id = null, $customer_id = null, $workorder_id = null, $invoice_id = null) {
    
    global $smarty;

    // Maintenance Mode
    if($QConfig->maintenance) {

        // Set to the maintenance page    
        $component          = 'core';
        $page_tpl           = 'maintenance';
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = 'dashboard';        
        $VAR['theme']       = 'off';   

        // If user logged in, then log user off (Hard logout, no logging)
        if(isset($user->login_token)) {    
            QFactory::getAuth()->logout(); 
        }    

    // If no page specified set page based on login status
    } elseif(!isset($VAR['component']) && !isset($VAR['page_tpl']) ) {    

        if(isset($user->login_token)) {

            // If logged in
            $component                  = 'core';            
            $page_tpl                   = 'dashboard';
            $VAR['component']           = 'core';
            $VAR['page_tpl']            = 'dashboard';

        } else {

            // If NOT logged in
            $component                  = 'core';
            $page_tpl                   = 'home';
            $VAR['component']           = 'core';
            $VAR['page_tpl']            = 'home';

        }     

    // If there are correct page variables set with values
    } else {     

        // Check to see if the page exists otherwise send to the 404 page
        if (check_page_exists($db, $VAR['component'], $VAR['page_tpl'])) {

            // Set the requested page
            $component  = $VAR['component'];
            $page_tpl   = $VAR['page_tpl'];        

        } else {        

            // Set to the 404 error page       
            $component          = 'core';
            $page_tpl           = '404';
            $VAR['component']   = 'core';
            $VAR['page_tpl']    = '404';            
            $VAR['theme']       = 'off';

        }
       
    }
    
    // Check the requested page with the current usergroup against the ACL for authorisation, if it fails set page 403
    if(!check_acl($db, $user, $component, $page_tpl)) {

        // Log activity
        $record = _gettext("A user tried to access the following resource without the correct permissions.").' ('.$component.':'.$page_tpl.')';
        write_record_to_activity_log($record, $employee_id, $customer_id, $workorder_id, $invoice_id); 

        // Set to the 403 error page 
        $component          = 'core';
        $page_tpl           = '403';
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = '403';        
        $VAR['theme']       = 'off';
        $smarty->assign('warning_msg', _gettext("You do not have permission to access this resource or your session has expired.").' ('.$component.':'.$page_tpl.')');

        //force_error_page($_GET['component'], $_GET['page_tpl'], 'authentication', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("You do not have permission to access the resource - ").' '.$component.':'.$page_tpl);
        //force_page('index.php', null, 'warning_msg='._gettext("You do not have permission to access this resource or your session has expired.").' ('.$component.':'.$page_tpl.')');
        //exit;

    }
    
    // Return the page display controller for the requested page    
    return COMPONENTS_DIR.$component.'/'.$page_tpl.'.php';
    
}


###############################################################
#  Build SEF URL from Non-SEF URL //or return $VAR variables  #
###############################################################

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

#################################################################################
#  Convert a SEF url into a standard url and inject routing varibles into $VAR  #
#################################################################################

function parseSEF($sef_url, &$VAR = null, $setOnlyVAR = false) {    
    
    // Remove base path from URI
    $sef_url = str_replace(QWCRM_BASE_PATH, '', $sef_url);
    
    // Move URL into an array
    $parsed_url = parse_url($sef_url);

    // Get Variables from path
    $url_segments = explode('/', $parsed_url['path']);
    
    if ($url_segments) {
        $nonsef_url_path_variables .= '?';
        $nonsef_url_path_variables .= 'component='.$url_segments['0'];
        $nonsef_url_path_variables .= '&page_tpl='.$url_segments['1'];
    }    

    // Sets the following values into $VAR for routing
    if ($setOnlyVAR) {
        if($url_segments['0'] != '') { $VAR['component'] = $url_segments['0']; }
        if($url_segments['1'] != '') { $VAR['page_tpl'] = $url_segments['1']; }
        
        return;
    }
    
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

function check_page_exists($db, $component = null, $page_tpl = null) {
    
    // Old checking code here
    //if (file_exists(COMPONENTS_DIR.$component.'/'.$page_tpl.'.php')){ ... }
    
    $sql = "SELECT page FROM ".PRFX."user_acl WHERE page = ".$db->qstr($component.':'.$page_tpl);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check is a page exists."));
        exit;
    } else {
        
        if($rs->RecordCount() == 1) {
        
            return true;
            
        } else {
            
            return false;
            
        }
            
    }
    
}

#####################################
#  Check to see if the link is SEF  #
#####################################

function check_link_is_sef($url) {
    
    // Get URL path
    $url = parse_url($url, PHP_URL_PATH);

    // Remove base path from URL path
    $url = str_replace(QWCRM_BASE_PATH, '', $url);

    // Check to see if what remains start with index.php    
    if (preg_match('|^index\.php|U', $url)) {
        
        // is Non-SEF
        return false;
        
    } else {

        // is SEF
        return true;
        
    }   
    
}