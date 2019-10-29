<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->check_page_accessed_via_qwcrm('otherincome', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Otherincome ID supplied."));
    $this->app->system->general->force_page('otherincome', 'search');
} 

// Delete the otherincome function call
$this->app->components->otherincome->delete_otherincome(\CMSApplication::$VAR['otherincome_id']);

// Load the otherincome search page
$this->app->system->general->force_page('otherincome', 'search', 'msg_success='._gettext("Otherincome deleted successfully."));
