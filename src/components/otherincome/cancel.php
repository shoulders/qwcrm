<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('otherincome', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Otherincome ID supplied."));
    $this->app->system->page->forcePage('otherincome', 'search');
}

// Run the cancel function if allowed
if(!$this->app->components->otherincome->checkRecordAllowsCancel(\CMSApplication::$VAR['otherincome_id'])) {
    $this->app->system->page->forcePage('otherincome', 'details&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);
} else {
    $this->app->components->otherincome->cancelRecord(\CMSApplication::$VAR['otherincome_id'], \CMSApplication::$VAR['qform']['reason_for_cancelling']);
    $this->app->system->page->forcePage('otherincome', 'search');
}
