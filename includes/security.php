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

function check_page_accessed_via_qwcrm($qwcrm_page = null) {    

    // check if a specific QWcrm page is the referer
    if($qwcrm_page != null) {        
        return preg_match('/^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_PATH, '/').'index\.php\?page='.$qwcrm_page.'.*/U', getenv('HTTP_REFERER'));
        
    // Check if any QWcrm page is the referer   
    } else {        
        return preg_match('/^'.preg_quote(QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_PATH, '/').'(index\.php\?page=)?.*/U', getenv('HTTP_REFERER')); 
    }
    
}
