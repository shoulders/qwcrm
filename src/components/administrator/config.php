<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'administrator.php');
require(INCLUDES_DIR.'user.php');

// Send a Test Mail
if(isset($VAR['send_test_mail'])) {
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        send_test_mail();
    }
    die();    
}

// Clear Smarty Compile
if(isset($VAR['clear_smarty_compile'])) {    
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        clear_smarty_compile();        
    }    
    die();
}

// Clear Smarty Cache button
if(isset($VAR['clear_smarty_cache'])) {
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        clear_smarty_cache();
    }
    die();
}

// Update Config details
if(isset($VAR['submit'])) {   
    
    if(update_qwcrm_config($VAR['qwcrm_config'])) {
        
        // Compensate for SEF change  
        $url_sef = $VAR['qwcrm_config']['sef'] ? 'sef' : 'nonsef';
        
        // Load maintenance page if enabled
        if(!QFactory::getConfig()->get('maintenance') && $VAR['qwcrm_config']['maintenance']) {
            logout_all_users();
            force_page('index.php', null, null, 'get', $url_sef);
        }        
        
        // Reload Page (nonSSL to SSL)
        elseif (!QFactory::getConfig()->get('force_ssl') && $VAR['qwcrm_config']['force_ssl']) {
            force_page('administrator', 'config', 'information_msg='._gettext("Config settings updated successfully."), 'auto', $url_sef, 'https');
            
        // Reload page with forced logout (SSL to nonSSL)
        } elseif(QFactory::getConfig()->get('force_ssl') && !$VAR['qwcrm_config']['force_ssl']) {
            logout_all_users();
            force_page('user', 'login', null, 'get', $url_sef, 'http');
        
        // Reload Page (No change in SSL state or maintenance mode)
        } else {
            force_page('administrator', 'config', 'information_msg='._gettext("Config settings updated successfully."), 'auto', $url_sef);             
        }        
        
    } else {
        
        // Load the submitted values
        $smarty->assign('warning_msg', _gettext("Some information was invalid, please check for errors and try again."));
        $smarty->assign('qwcrm_config', $VAR['qwcrm_config']); 
    }
    
} else {

    // No data submitted so just load the current config settings
    $smarty->assign('qwcrm_config', get_qwcrm_config_from_file() );

}

// Build the page
$BuildPage .= $smarty->fetch('administrator/config.tpl');