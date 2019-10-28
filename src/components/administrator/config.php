<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Send a Test Mail
if(isset(\CMSApplication::$VAR['send_test_mail'])) {
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        send_test_mail();
    }
    die();    
}

// Clear Smarty Compile
if(isset(\CMSApplication::$VAR['clear_smarty_compile'])) {    
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        clear_smarty_compile();        
    }    
    die();
}

// Clear Smarty Cache button
if(isset(\CMSApplication::$VAR['clear_smarty_cache'])) {
    if(check_page_accessed_via_qwcrm('administrator', 'config')) {
        clear_smarty_cache();
    }
    die();
}

// Update Config details
if(isset(\CMSApplication::$VAR['submit'])) {   
    
    if(update_qwcrm_config_settings_file(\CMSApplication::$VAR['qform'])) {
        
        // Compensate for SEF change  
        $url_sef = \CMSApplication::$VAR['qform']['sef'] ? 'sef' : 'nonsef';
        
        // Load maintenance page if enabled
        if(!\Factory::getConfig()->get('maintenance') && \CMSApplication::$VAR['qform']['maintenance']) {
            logout_all_users();
            force_page('index.php', null, null, 'get', $url_sef);
        }        
        
        // Reload Page (nonSSL to SSL)
        elseif (!\Factory::getConfig()->get('force_ssl') && \CMSApplication::$VAR['qform']['force_ssl']) {
            force_page('administrator', 'config', 'msg_success='._gettext("Config settings updated successfully."), 'auto', $url_sef, 'https');
            
        // Reload page with forced logout (SSL to nonSSL)
        } elseif(\Factory::getConfig()->get('force_ssl') && !\CMSApplication::$VAR['qform']['force_ssl']) {
            logout_all_users();
            force_page('user', 'login', null, 'get', $url_sef, 'http');
        
        // Reload Page (No change in SSL state or maintenance mode)
        } else {
            force_page('administrator', 'config', 'msg_success='._gettext("Config settings updated successfully."), 'auto', $url_sef);             
        }        
        
    } else {
        
        // Load the submitted values
        systemMessagesWrite('danger', _gettext("Some information was invalid, please check for errors and try again."));
        $smarty->assign('qwcrm_config', \CMSApplication::$VAR['qform']); 
    }
    
} else {

    // No data submitted so just load the current config settings
    $smarty->assign('qwcrm_config', get_qwcrm_config_settings() );

}

// Build the page
$smarty->assign('available_languages', load_languages() );