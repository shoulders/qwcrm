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

    if(isset(\CMSApplication::$VAR['submit'])) {

        // Submission Action
        switch (\CMSApplication::$VAR['submit']) {

            // Submit Changes only
            case 'submitchangesonly':
                $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Resolution has been updated."));
                break;

            // Close without invoice
            case 'closewithoutinvoice':
                $this->app->components->workorder->closeRecord(\CMSApplication::$VAR['workorder_id']);
                $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
                break;

            // Close with invoice
            case 'closewithinvoice':

                // Create a new invoice attached to this work order
                $this->app->system->page->forcePage('invoice', 'new&workorder_id='.\CMSApplication::$VAR['workorder_id']);
                break;

        }

    }

    // Build the page
    $this->app->smarty->assign('resolution', $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'resolution'));
}


