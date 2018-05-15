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

// Maintenance Mode
if($QConfig->maintenance) {
    
    // Set to the maintenance page    
    $component      = 'core';
    $page_tpl       = 'maintenance';
    $VAR['theme']   = 'off';   
    
    // If user logged in, then log user off (Hard logout, no logging)
    if(isset($login_token)) {    
        QFactory::getAuth()->logout(); 
    }    

// If no page specified set page based on login status
} elseif(!isset($VAR['component']) && !isset($VAR['page_tpl']) ) {    
    
    if(isset($login_token)) {
        
        // If logged in
        $component                  = 'core';
        $page_tpl                   = 'dashboard';
        
    } else {
        
        // If NOT logged in
        $component                  = 'core';
        $page_tpl                   = 'home';  
        
    }     
    
// If there are page variables set with values
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
if(!check_acl($db, $login_usergroup_id, $component, $page_tpl)) {
    
    // Log activity
    $record = _gettext("A user tried to access the following resource without the correct permissions.").' ('.$component.':'.$page_tpl.')';
    write_record_to_activity_log($record, $employee_id, $customer_id, $workorder_id, $invoice_id); 
    
    //Set to the 403 error page 
    $component  = 'core';
    $page_tpl   = '403';
    $VAR['theme'] = 'off';
    $smarty->assign('warning_msg', _gettext("You do not have permission to access this resource or your session has expired.").' ('.$component.':'.$page_tpl.')');
      
    //force_error_page($_GET['page'], 'authentication', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("You do not have permission to access the resource - ").' '.$component.':'.$page_tpl);
    //force_page('index.php', null, 'warning_msg='._gettext("You do not have permission to access this resource or your session has expired.").' ('.$component.':'.$page_tpl.')');
    //exit;

}

// Return the page display controller for the requested page
$page_display_controller = COMPONENTS_DIR.$component.'/'.$page_tpl.'.php'; 