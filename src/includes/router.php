<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

############################################
#  Page Preparation Logic                  #
#  Extract Page Parameters and Validate    #
#  the page exists ready for building      #
############################################   

function get_page_controller($db, &$VAR = null, $QConfig = null, $user = null, $employee_id = null, $customer_id = null, $workorder_id = null, $invoice_id = null) {
    
    global $smarty;

    // Maintenance Mode
    if($QConfig->maintenance) {

        // Set to the maintenance page    
        $component      = 'core';
        $page_tpl       = 'maintenance';
        $VAR['theme']   = 'off';   

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
    } elseif(isset($VAR['component']) && $VAR['component'] != '' && isset($VAR['page_tpl']) && $VAR['page_tpl'] != '') {     

        // Check to see if the page controller exists otherwise set to the 404 page
        if (file_exists(COMPONENTS_DIR.$VAR['component'].'/'.$VAR['page_tpl'].'.php')) {

            // Set the requested page
            $component  = $VAR['component'];
            $page_tpl   = $VAR['page_tpl'];        

        } else {        

            // Set to the 404 error page       
            $component  = 'core';
            $page_tpl   = '404';
            $VAR['theme'] = 'off';

        }

    // If the URL is malformed display the 404 page    
    } else {

        // Set to the 404 error page       
        $component  = 'core';
        $page_tpl   = '404';
        $VAR['theme'] = 'off';    

    }

    // Check the requested page with the current usergroup against the ACL for authorisation, if it fails set page 403
    if(!check_acl($db, $user, $component, $page_tpl)) {

        // Log activity
        $record = _gettext("A user tried to access the following resource without the correct permissions.").' ('.$component.':'.$page_tpl.')';
        write_record_to_activity_log($record, $employee_id, $customer_id, $workorder_id, $invoice_id); 

        // Set to the 403 error page 
        $component  = 'core';
        $page_tpl   = '403';
        $VAR['theme'] = 'off';
        $smarty->assign('warning_msg', _gettext("You do not have permission to access this resource or your session has expired.").' ('.$component.':'.$page_tpl.')');

        //force_error_page($_GET['component'], $_GET['page_tpl'], 'authentication', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("You do not have permission to access the resource - ").' '.$component.':'.$page_tpl);
        //force_page('index.php', null, 'warning_msg='._gettext("You do not have permission to access this resource or your session has expired.").' ('.$component.':'.$page_tpl.')');
        //exit;

    }
    
    // Return the page display controller for the requested page    
    return COMPONENTS_DIR.$component.'/'.$page_tpl.'.php';
    
}


/*
 * Build url from non-sef url
 */
function buildRoute($non_sef_url) {    
    
    // Move URL into an array
    $parsed_url = parse_url($non_sef_url);
    
    // Get URL Query Variables
    parse_str($parsed_url['query'], $parsed_url_query);
    
    // Build URL 'Path' from query variables and Remove Non-Query Variables as they are no longer needed
    $sef_url_path = $parsed_url_query['component'].'/'.$parsed_url_query['page_tpl'];    
    unset($parsed_url_query['component']);
    unset($parsed_url_query['page_tpl']);    
       
    // Build URL 'Query' if variables present
    if ($parsed_url_query) {
        
        $sef_url_query .= '?';
        
        foreach($parsed_url_query as $key => $value) {

            $sef_url_query .= '&'.$key.'='.$value;

        }
    }
    
    // Build URL 'Fragement'
    if($parsed_url['fragment']) { $sef_url_fragement = '#'.$parsed_url['fragment']; }
        
    // Build and return full SEF URL
    return $sef_url_path . $sef_url_query . $sef_url_fragement;
    
}

/*
 * Convert a SEF url into a standard url
 */
function parseRoute($sef_url, &$VAR = null) {
    
    // Move URL into an array
    $parsed_url = parse_url($sef_url);
    
    // Get Variables from path
    $url_segments = explode('/', $parsed_url['path']);
    
    if ($url_segments) {
        $nonsef_url_path_variables .= '?';
        $nonsef_url_path_variables .= 'component='.$url_segments['0'];
        $nonsef_url_path_variables .= '&page_tpl='.$url_segments['1'];
    }    

    // If this is the live URL then the following variables need to be set in $VAR (which is passed by reference)
    if ($VAR) {
        $VAR['component'] = $url_segments['0'];
        $VAR['path_tpl'] = $url_segments['1'];
    }
    
    // Get URL Query Variables
    parse_str($parsed_url['query'], $parsed_url_query_variables);
       
    // Build URL 'Query' if variables present
    if ($parsed_url_query_variables) {
        
        foreach($parsed_url_query_varibles as $key => $value) {

            $nonsef_url_query .= '&'.$key.'='.$value;

        }
    }
    
    // Build URL 'Fragement'
    if($parsed_url['fragment']) { $nonsef_url_fragment = '#'.$parsed_url['fragment']; }
    
    // Build and return full SEF URL
    return 'index.php' . $nonsef_url_path_variables . $nonsef_url_query . $nonsef_url_fragment;
    
}

/*
echo buildRoute('index.php?component=invoice&page_tpl=status&invoice_id=15#chicken');
echo '<br>';
echo parseRoute('workorder/details/chicken?hat=turnip15#ball');
echo '<br>';echo '<br>';echo '<br>';echo '<br>';
*/
/*
echo buildRoute('workorder/details/chicken?hat=turnip15#ball');
echo '<br>';
echo parseRoute('index.php?component=invoice&page_tpl=status&invoice_id=15#chicken');*/