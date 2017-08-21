<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/* 
 * i am going to use this to hold all security related functions for easy reference
 * some code will auto run aswell as functions being here almost like a seperate library
 */

defined('_QWEXEC') or die;

// Force SSL/HTTPS if enabled - add base path stuff here
if($QConfig->force_ssl == 1 && !isset($_SERVER['HTTPS'])) {   
    force_page('https://' . QWCRM_DOMAIN . QWCRM_PATH );
    exit;
}

#######################################
#   Prevent direct access to a page   #
#######################################

function check_page_accessed_via_qwcrm($qwcrm_page = null, $access_rule = null) {    
    
    // Check if a 'SPECIFIC' QWcrm page is the referer
    if($qwcrm_page != null) {       
        
        // If supplied page matches the 'Referring Page'
        if(preg_match('/^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_PATH, '/').'index\.php\?page='.$qwcrm_page.'.*/U', getenv('HTTP_REFERER'))) {
            
            return true;
            
        } else {            
            
            // Setup Access Rule - Prevent Specified Direct Page Access but allow direct via index.php (useful for setup:install, setup:migrate, setup:upgrade / system pages)
            if($access_rule == 'setup') {                
                
                // No Referer but page is directly loaded by '', '/', 'index.php'
                if(getenv('HTTP_REFERER') == '' && preg_match('/^'.preg_quote(QWCRM_PATH, '/').'(index\.php)?$/U', getenv('REQUEST_URI'))) {
                    
                    return true;
                    
                }
                
                // If 'Referring Page' == '', '/', 'index.php'
                if(preg_match('/^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_PATH, '/').'(index\.php)?$/U', getenv('HTTP_REFERER'))) {
                    
                    return true;
                    
                }
                
            }                     
    
        }
        
        // Referring Page does not match and no access rules were triggered to allow access
        return false;   
          
        
    // Check if 'ANY' QWcrm page is the referer   
    } else {
        
        return preg_match('/^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_PATH, '/').'(index\.php\?page=)?.*/U', getenv('HTTP_REFERER'));
        
    }
    
}