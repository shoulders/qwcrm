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

    // If data has been submitted, validate and then update the record
    if(isset(\CMSApplication::$VAR['submit'])) {

        // Holding variable for validation tests
        $submitFailedValidation = false;

        // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
        if($this->app->components->workorder->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'], 'comment')) {

            // Update the record
            $this->app->components->workorder->updateComment(\CMSApplication::$VAR['qform']);

            // Success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Comment has been updated."));

        // Submission has failed validation,
        } else {
            $submitFailedValidation = true;
        }

        // If submission was successful, load the details page
        if(!$submitFailedValidation) {
            $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
        }

    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $workorder_details = array_merge($this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id']), \CMSApplication::$VAR['qform']);
    } else {
        $workorder_details = $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id']);
    }

    // Build the page
    $this->app->smarty->assign('comment', $workorder_details['comment']);

}
