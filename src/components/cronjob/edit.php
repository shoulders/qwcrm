<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a cronjob_id
if(!isset(\CMSApplication::$VAR['cronjob_id']) || !\CMSApplication::$VAR['cronjob_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Cronjob ID supplied."));
    $this->app->system->page->forcePage('cronjob', 'overview');
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset(\CMSApplication::$VAR['submit'])) {    
        
    // Update the cron in the database
    $this->app->components->cronjob->updateRecord(\CMSApplication::$VAR['qform']);    
    
    // Load details page
    $this->app->system->page->forcePage('cronjob', 'details&cronjob_id='.\CMSApplication::$VAR['qform']['cronjob_id'], 'msg_success='._gettext("Cronjob updated successfully."));
    
} else {  
    
    // Build the page
    $this->app->smarty->assign('cronjob_details', $this->app->components->cronjob->getRecord(\CMSApplication::$VAR['cronjob_id']));

}