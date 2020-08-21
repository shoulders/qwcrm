<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a schedule_id
if(!isset(\CMSApplication::$VAR['schedule_id']) || !\CMSApplication::$VAR['schedule_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Schedule ID supplied."));
    $this->app->system->page->forcePage('schedule', 'search');
}

// Get workorder_id before deleting the record
\CMSApplication::$VAR['workorder_id'] = $this->app->components->schedule->getRecord(\CMSApplication::$VAR['schedule_id'], 'workorder_id');

// Delete the schedule
$this->app->components->schedule->deleteRecord(\CMSApplication::$VAR['schedule_id']);

// load schedule search page
$this->app->system->variables->systemMessagesWrite('success', _gettext("Schedule record has been deleted."));
$this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
