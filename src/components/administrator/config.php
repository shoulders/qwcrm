<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/administrator.php');
require(INCLUDES_DIR.'components/user.php');

// Clear Smarty Cache
if($VAR['clear_smarty_cache'] == 'true') {
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        clear_smarty_cache();
    }
    die();
}

// Clear Smarty Compile
if($VAR['clear_smarty_compile'] == 'true') {    
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        clear_smarty_compile();        
    }    
    die();
}

// Send a Test Mail
if($VAR['send_test_mail'] == 'true') {
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        send_test_mail();
    }
    die();    
}

// Update Config details
if($VAR['submit'] == 'update') {   
    
    if(update_qwcrm_config($VAR['qwconfig'])) {
        
        // Compensate for SEF change  
        $url_sef = $VAR['qwconfig']['sef'] ? 'sef' : 'nonsef';
        
        // Load maintenance page if maintenance mode has been enabled
        if(!QFactory::getConfig()->get('maintenance') && $VAR['qwconfig']['maintenance']) {
            logout_all_users();
            force_page('index.php', null, null, $url_sef, 'auto', 'get');
        }        
        
        /// Reload Page (nonSSL to SSL)
        elseif (!QFactory::getConfig()->get('force_ssl') && $VAR['qwconfig']['force_ssl']) {
            force_page('administrator', 'config', 'information_msg='._gettext("Config settings updated successfully."), $url_sef, 'https');
            
        // Reload page with forced logout (SSL to nonSSL)
        } elseif(QFactory::getConfig()->get('force_ssl') && !$VAR['qwconfig']['force_ssl']) {
            logout_all_users();
            force_page('user', 'login', null, $url_sef, 'http', 'get');
        
        // Reload Page (No change in SSL state or maintenance mode)
        } else {
            force_page('administrator', 'config', 'information_msg='._gettext("Config settings updated successfully."), $url_sef, 'auto');             
        }
     
        
            

        
        
    } else {
        
        // Load the submitted values
        $smarty->assign('warning_msg', _gettext("Some information was invalid, please check for errors and try again."));
        $smarty->assign('qwcrm_config', $VAR['qwconfig']); 
    }
    
} else {

    // No data submitted so just load the current config settings
    $smarty->assign('qwcrm_config', get_qwcrm_config() );

}

// Build the page
$BuildPage .= $smarty->fetch('administrator/config.tpl');