<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an creditnote_id
if(!isset(\CMSApplication::$VAR['creditnote_id']) || !\CMSApplication::$VAR['creditnote_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Credit Note ID supplied."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Run the delete function if allowed
if(!$this->app->components->creditnote->checkRecordAllowsDelete(\CMSApplication::$VAR['creditnote_id'])) {
    $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.\CMSApplication::$VAR['creditnote_id']);
} else {
    $this->app->components->creditnote->deleteRecord(\CMSApplication::$VAR['creditnote_id']);
    $this->app->system->page->forcePage('creditnote', 'search');
}
