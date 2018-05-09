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
if($QConfig->maintenance){
    
    // Set to the maintenance page    
    $page_display_controller = COMPONENTS_DIR.'core/maintenance.php'; 
    $component      = 'core';
    $page_tpl       = 'maintenance';
    $VAR['theme']   = 'off';   
    
    // If user logged in, then log user off (Hard logout, no logging)
    if(isset($login_token)) {    
        QFactory::getAuth()->logout(); 
    }    

// If there is a page set, verify it and build the controller
} elseif(isset($VAR['page']) && $VAR['page'] != '') { 

    // Explode the URL so we can get the component and page_tpl
    list($component, $page_tpl) = explode(':', $VAR['page']);
    $page_display_controller    = COMPONENTS_DIR.$component.'/'.$page_tpl.'.php';

    // Check to see if the page exists and set it, otherwise send them to the 404 page
    if (file_exists($page_display_controller)){
        $page_display_controller = COMPONENTS_DIR.$component.'/'.$page_tpl.'.php';            
    } else {
        
        // set to the 404 error page 
        $page_display_controller = COMPONENTS_DIR.'core/404.php'; 
        $component  = 'core';
        $page_tpl   = '404';
        
        // Send 404 header
        $VAR['theme'] = 'off';
        header('HTTP/1.1 404 Not Found');
        
    }        

// If no page specified load a default landing page   
} else {        

    if(isset($login_token)){
        // If logged in
        $page_display_controller    = COMPONENTS_DIR.'core/dashboard.php';
        $component                  = 'core';
        $page_tpl                   = 'dashboard';       
    } else {
        // If NOT logged in
        $page_display_controller    = COMPONENTS_DIR.'core/home.php';
        $component                  = 'core';
        $page_tpl                   = 'home';            
    }

}