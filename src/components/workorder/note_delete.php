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

// Check if we have a workorder_note_id
if(!isset(\CMSApplication::$VAR['workorder_note_id']) || !\CMSApplication::$VAR['workorder_note_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Work Order Note ID supplied."));
    $this->app->system->page->forcePage('workorder', 'search');
}

// Get the workorder_id before we delete the record
$workorder_id = $this->app->components->workorder->getNote(\CMSApplication::$VAR['workorder_note_id'], 'workorder_id');

// Delete the record
$this->app->components->workorder->deleteNote(\CMSApplication::$VAR['workorder_note_id']);

// Reload the workorder details page
$this->app->system->variables->systemMessagesWrite('success', _gettext("The note has been deleted."));
$this->app->system->page->forcePage('workorder', 'details&workorder_id='.$workorder_id);