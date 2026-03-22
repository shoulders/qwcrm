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
    $this->app->system->page->forcePage('workorder', 'search');
}

// Load the edit page if allowed
if(!$this->app->components->workorder->checkRecordAllowsEdit(\CMSApplication::$VAR['workorder_id'])) {
    $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
} else {
    // If updated scope and description are submitted
    if(isset(\CMSApplication::$VAR['submit'])) {

        // update the scope and description in the database
        $this->app->components->workorder->updateScopeDescription(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['scope'], \CMSApplication::$VAR['description']);

        // load the workorder details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Description has been updated."));
        $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);

    }

    // Build the page
    $this->app->smarty->assign('scope',          $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'scope')        );
    $this->app->smarty->assign('description',    $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'description')  );
}
