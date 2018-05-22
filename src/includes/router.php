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
#  Validate links and prep SEF enviroment  #
############################################

function prepare_page_routing($QConfig, &$VAR = null) {
    
    // Check if URL is valid
    if(!check_link_is_valid($_SERVER['REQUEST_URI'])) {

        // Set to the maintenance page    
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = 'error';        
        $VAR['theme']       = 'off'; 

        //force_error_page($_GET['component'], $_GET['page_tpl'], 'url', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Malformed URL."));
        //exit;

    } else {    

        // If SEF routing is enabled
        if ($QConfig->sef) {

            // Running parseSEF only when the link is a SEF allows the use of Non-SEF URLS aswell
            if (check_link_is_sef($_SERVER['REQUEST_URI'])) {

                // Set 'component' and 'page_tpl' variables in $VAR for correct routing when using SEF
                parseSEF($_SERVER['REQUEST_URI'], $VAR, true);

            }

        }

    }

}
    
############################################
#  Build path to relevant Page Controller  #
############################################

function get_page_controller($db, &$VAR = null, $QConfig = null, $user = null, $employee_id = null, $customer_id = null, $workorder_id = null, $invoice_id = null) {
    
    global $smarty;

    // Maintenance Mode
    if($QConfig->maintenance) {

        // Set to the maintenance page    
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = 'maintenance';        
        $VAR['theme']       = 'off';   

        // If user logged in, then log user off (Hard logout, no logging)
        if(isset($user->login_token)) {    
            QFactory::getAuth()->logout(); 
        }    

    // If no page specified set page based on login status
    } elseif(!isset($VAR['component']) && !isset($VAR['page_tpl']) ) {    

        if(isset($user->login_token)) {

            // If logged in
            $VAR['component']           = 'core';
            $VAR['page_tpl']            = 'dashboard';

        } else {

            // If NOT logged in
            $VAR['component']           = 'core';
            $VAR['page_tpl']            = 'home';

        }     

    // Check to see if the page exists otherwise send to the 404 page
    } else {
        
        if (!check_page_exists($db, $VAR['component'], $VAR['page_tpl'])) {

            // Set to the 404 error page       
            $VAR['component']   = 'core';
            $VAR['page_tpl']    = '404';            
            $VAR['theme']       = 'off';

        }
       
    }
    
    // Check the requested page with the current usergroup against the ACL for authorisation, if it fails set page 403
    if(!check_page_acl($db, $VAR['component'], $VAR['page_tpl'])) {

        // Log activity
        $record = _gettext("A user tried to access the following resource without the correct permissions.").' ('.$VAR['component'].':'.$VAR['page_tpl'].')';
        write_record_to_activity_log($record, $employee_id, $customer_id, $workorder_id, $invoice_id); 

        // Set to the 403 error page 
        $VAR['component']   = 'core';
        $VAR['page_tpl']    = '403';        
        $VAR['theme']       = 'off';
        $smarty->assign('warning_msg', _gettext("You do not have permission to access this resource or your session has expired.").' ('.$VAR['component'].':'.$VAR['page_tpl'].')');

        //force_error_page($_GET['component'], $_GET['page_tpl'], 'authentication', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("You do not have permission to access the resource - ").' '.$component.':'.$page_tpl);
        //force_page('index.php', null, 'warning_msg='._gettext("You do not have permission to access this resource or your session has expired.").' ('.$component.':'.$page_tpl.')');
        //exit;

    }

    // Return the page display controller for the requested page    
    return COMPONENTS_DIR.$VAR['component'].'/'.$VAR['page_tpl'].'.php';
    
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
    $url_segments = array_filter(explode('/', $parsed_url['path']));
    
    // If there are routing variables
    if ($url_segments) {
        
        // Set $_GET routing variables
        $nonsef_url_path_variables .= '?';
        $nonsef_url_path_variables .= 'component='.$url_segments['0'];
        $nonsef_url_path_variables .= '&page_tpl='.$url_segments['1'];       

        // Sets the following routing values into $VAR for routing
        if ($setOnlyVAR) {
            if($url_segments['0'] != '') { $VAR['component'] = $url_segments['0']; }
            if($url_segments['1'] != '') { $VAR['page_tpl'] = $url_segments['1']; }
        }
    
    }
    
    // No further processing when $setOnlyVAR set
    if ($setOnlyVAR) { return; }
    
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

    // Check to see if what remains start with index.php    
    if (preg_match('|^index\.php|U', $url)) {
        
        // is Non-SEF
        return false;
        
    } else {

        // is SEF
        return true;
        
    }   
    
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


/** Other Functions **/

#################################################################
#  Verify User's authorisation for a specific page / operation  #
#################################################################

function check_page_acl($db, $component, $page_tpl, $user = null) {
    
    // Get the current user unless a user (object) has been passed
    if($user == null) { $user = QFactory::getUser(); }
    
    // If installing
    if(defined('QWCRM_SETUP') && (QWCRM_SETUP == 'install' || QWCRM_SETUP == 'upgrade')) { return true; }
    
    // Usergroup Error catching - you cannot use normal error logging as it will cause a loop (should not be needed now)
    if($user->login_usergroup_id == '') {
        die(_gettext("The ACL has been supplied with no usergroup. QWcrm will now die."));                
    }

    // Get user's Group Name by login_usergroup_id
    $sql = "SELECT ".PRFX."user_usergroups.usergroup_display_name
            FROM ".PRFX."user_usergroups
            WHERE usergroup_id =".$db->qstr($user->login_usergroup_id);
    
    if(!$rs = $db->execute($sql)) {        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not get the user's Group Name by Login Account Type ID."));
        exit;
    } else {
        $usergroup_display_name = $rs->fields['usergroup_display_name'];
    } 
    
    // Build the page name for the ACL lookup
    $page_name = $component.':'.$page_tpl;
    
    /* Check Page to see if we have access */
    
    $sql = "SELECT ".$usergroup_display_name." AS acl FROM ".PRFX."user_acl WHERE page=".$db->qstr($page_name);

    if(!$rs = $db->execute($sql)) {        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'authentication', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not get the Page's ACL."));
        exit;
    } else {
        
        $acl = $rs->fields['acl'];
        
        // Add if guest (8) rules here if there are errors
        
        if($acl != 1) {
            
            return false;
            
        } else {
            
            return true;
            
        }
        
    }
    
}

############################################
#  Check page has been internally refered  #
############################################

function check_page_accessed_via_qwcrm($component = null, $page_tpl = null, $access_rule = null) {    
    
    // Check if a 'SPECIFIC' QWcrm page is the referer
    if($component != null && $page_tpl != null) {       
        
        // If supplied page matches the 'Referring Page'
        if(preg_match('/^'.preg_quote(build_url_from_variables($component, $page_tpl, 'full', 'auto'), '/').'/U', getenv('HTTP_REFERER'))) {
            
            return true;
            
        } else {            
            
            // Setup Access Rule - Prevent Specified Direct Page Access but allow direct via index.php (useful for setup:install, setup:migrate, setup:upgrade / system pages)
            if($access_rule == 'setup') {                
                
                // No Referer, but page is directly loaded by '', '/', 'index.php'
                if(getenv('HTTP_REFERER') == '' && preg_match('/^'.preg_quote(build_url_from_variables($component, $page_tpl, 'relative', 'auto'), '/').'/U', getenv('REQUEST_URI'))) {
                    
                    return true;
                    
                }
                
                // If 'Referring Page' == '', '/', 'index.php'
                if(preg_match('/^'.preg_quote(build_url_from_variables($component, $page_tpl, 'full', 'auto'), '/').'/U', getenv('HTTP_REFERER'))) {
                    
                    return true;
                    
                }
                
            }                     
    
        }//build_url_from_variables($component, $page_tpl, 'full', 'auto')
        //(index\.php)?
        //'(index\.php\?page=)?.*/U'
        
        // Referring Page does not match and no access rules were triggered to allow access
        return false;   
          
        
    // Check if 'ANY' QWcrm page is the referer   
    } else {
        
        return preg_match('/^'.preg_quote(build_url_from_variables($component, $page_tpl, 'full', 'auto'), '/').'/U', getenv('HTTP_REFERER'));
        
    }
    
}