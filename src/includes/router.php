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
    
    // Disable Theme
    $VAR['theme']   = 'off';   
    
    // If user logged in, then log user off (Hard logout, no logging)
    if(isset($login_token)) {    
        QFactory::getAuth()->logout(); 
    }    

// If no page specified set page based on login status
} elseif(!isset($VAR['page'])) {    
    
    if(isset($login_token)) {
        
        // If logged in
        $component                  = 'core';
        $page_tpl                   = 'dashboard';
        
    } else {
        
        // If NOT logged in
        $component                  = 'core';
        $page_tpl                   = 'home';  
        
    }     
    
// If there is a page set
} else {        

    // Explode the URL so we can get the component and page_tpl
    list($component, $page_tpl) = explode(':', $VAR['page']);    

    // Check to see if the page controller exists otherwise set to the 404 page
    if (!file_exists(COMPONENTS_DIR.$component.'/'.$page_tpl.'.php')) {
                   
        // Set to the 404 error page       
        $component  = 'core';
        $page_tpl   = '404';
        
        // Disable Theme
        $VAR['theme'] = 'off';
       
    } else {
        // Use discovered $component and $page_tpl        
    }
    
}

// Return the page display controller for the requested page
$page_display_controller = COMPONENTS_DIR.$component.'/'.$page_tpl.'.php'; 