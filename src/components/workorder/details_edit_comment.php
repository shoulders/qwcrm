<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    $this->app->system->page->force_page('workorder', 'search');
}

// Check if we can edit the workorder comment
if($this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'is_closed')) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Cannot edit the comment of a closed Work Order."));
    $this->app->system->page->force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
}

// If updated comment are submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // update the workorder comment in the database
    $this->app->components->workorder->updateComment(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['comment']);
    
    // load the workorder details page
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Comment has been updated."));
    $this->app->system->page->force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
    
}

// Build the page
$this->app->smarty->assign('comment', $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'comment'));