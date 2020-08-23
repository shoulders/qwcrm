<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('cronjob', 'details')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a cronjob_id
if(!isset(\CMSApplication::$VAR['cronjob_id']) || !\CMSApplication::$VAR['cronjob_id']) {    
    $this->app->system->page->forcePage('cronjob', 'overview');
} 

// Run the cronjob
$this->app->components->cronjob->runCronjob(\CMSApplication::$VAR['cronjob_id'], false);

// Return to the page this cron was run from
$this->app->system->page->forcePage($_SERVER['HTTP_REFERER']);