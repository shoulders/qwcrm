<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('cronjob', 'overview') && !$this->app->system->security->checkPageAccessedViaQwcrm('cronjob', 'details')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a valid command
if(!isset(\CMSApplication::$VAR['unlock_type']) && (\CMSApplication::$VAR['unlock_type'] !== 'system' || \CMSApplication::$VAR['unlock_type'] !== 'cronjob')) { 
    
    // invalid command message
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No valid unlock command supplied."));
    
    // Return to the page this cron was run from
    $this->app->system->page->forcePage($_SERVER['HTTP_REFERER'], null, null, 'get');
} 


// Unlock Cronjob System
if(\CMSApplication::$VAR['unlock_type'] === 'system') {
    
    // Update system locked status
    updateSystemLockedStatus(false);
    
    // Success Message
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Cronjob system").' '._gettext("has been unlocked."));
    
}

// Unlock cronjob
if(\CMSApplication::$VAR['unlock_type'] === 'cronjob') {
    
    // Check if we have a cronjob_id
    if(!isset(\CMSApplication::$VAR['cronjob_id']) || !\CMSApplication::$VAR['cronjob_id']) {    
        $this->app->system->page->forcePage('cronjob', 'overview');
    }
    
    // Update cronjob locked status
    $this->app->components->cronjob->updateRecordLockedStatus(\CMSApplication::$VAR['cronjob_id'], false);
    
    // Success message
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Cron").' '.\CMSApplication::$VAR['cronjob_id'].' '._gettext("has been unlocked."));
    
}

// Return to the page this cron was run from
$this->app->system->page->forcePage($_SERVER['HTTP_REFERER'], null, null, 'get');