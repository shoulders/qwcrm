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
    if($this->app->system->security->checkPageAccessedViaQwcrm('administrator', 'config')) {
        $this->app->components->administrator->sendTestEmail();
    }
    die();    
}

// Clear Smarty Compile
if(isset(\CMSApplication::$VAR['clear_smarty_compile'])) {    
    if($this->app->system->security->checkPageAccessedViaQwcrm('administrator', 'config')) {
        $this->app->system->general->clearSmartyCompile();        
    }    
    die();
}

// Clear Smarty Cache button
if(isset(\CMSApplication::$VAR['clear_smarty_cache'])) {
    if($this->app->system->security->checkPageAccessedViaQwcrm('administrator', 'config')) {
        $this->app->system->general->clearSmartyCache();
    }
    die();
}

// Update Config details
if(isset(\CMSApplication::$VAR['submit'])) {   
    
    if($this->app->components->administrator->updateQwcrmConfigSettingsFile(\CMSApplication::$VAR['qform'])) {
        
        // Compensate for SEF change  
        $url_sef = \CMSApplication::$VAR['qform']['sef'] ? 'sef' : 'nonsef';
        
        // Load maintenance page if enabled
        if(!$this->app->config->get('maintenance') && \CMSApplication::$VAR['qform']['maintenance']) {
            $this->app->components->user->logoutAllUsers();
            $this->app->system->page->forcePage('index.php', null, null, 'get', $url_sef);
        }        
        
        // Reload Page (nonSSL to SSL)
        elseif (!$this->app->config->get('force_ssl') && \CMSApplication::$VAR['qform']['force_ssl']) {
            $this->app->system->page->forcePage('administrator', 'config', 'msg_success='._gettext("Config settings updated successfully."), 'auto', $url_sef, 'https');
            
        // Reload page with forced logout (SSL to nonSSL)
        } elseif($this->app->config->get('force_ssl') && !\CMSApplication::$VAR['qform']['force_ssl']) {
            $this->app->components->user->logoutAllUsers();
            $this->app->system->page->forcePage('user', 'login', null, 'get', $url_sef, 'http');
        
        // Reload Page (No change in SSL state or maintenance mode)
        } else {
            $this->app->system->page->forcePage('administrator', 'config', 'msg_success='._gettext("Config settings updated successfully."), 'auto', $url_sef);             
        }        
        
    } else {
        
        // Load the submitted values
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("Some information was invalid, please check for errors and try again."));
        $this->app->smarty->assign('qwcrm_config', \CMSApplication::$VAR['qform']); 
    }
    
} else {

    // No data submitted so just load the current config settings
    $this->app->smarty->assign('qwcrm_config', $this->app->components->administrator->getQwcrmConfigAsArray() );

}

// Build the page
$this->app->smarty->assign('server_os', PHP_OS_FAMILY);
$this->app->smarty->assign('qwcrm_physical_path', QWCRM_PHYSICAL_PATH);
$this->app->smarty->assign('available_languages', $this->app->system->general->loadLanguages());