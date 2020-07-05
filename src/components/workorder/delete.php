<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->check_page_accessed_via_qwcrm('workorder', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    $this->app->system->page->force_page('workorder', 'search');
}

// Delete the Workorder
if(!$this->app->components->workorder->deleteRecord(\CMSApplication::$VAR['workorder_id'])) {
    
    // load the staus page
    $this->app->system->page->force_page('workorder', 'status', 'workorder_id='.\CMSApplication::$VAR['workorder_id']);
    
} else {
    
    
    // load the workorder search page
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Work Order has been deleted."));
    $this->app->system->page->force_page('workorder', 'search');
    
}
    