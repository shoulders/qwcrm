<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a cronjob_id
if(!isset(\CMSApplication::$VAR['cronjob_id']) || !\CMSApplication::$VAR['cronjob_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Cronjob ID supplied."));
    $this->app->system->page->forcePage('cronjob', 'overview');
}

// Load the edit page if allowed
if(!$this->app->components->cronjob->checkRecordAllowsEdit(\CMSApplication::$VAR['cronjob_id'])) {
    $this->app->system->page->forcePage('cron', 'details&cronjob_id='.\CMSApplication::$VAR['cronjob_id']);
} else {

    // If data has been submitted, validate and then update the record
    if(isset(\CMSApplication::$VAR['submit'])) {

        // Holding variable for validation tests
        $submitFailedValidation = false;

        // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
        if($this->app->components->cronjob->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'])) {

            // Update the record
            $this->app->components->cronjob->updateRecord(\CMSApplication::$VAR['qform']);

            // Success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Cronjob updated successfully."));


        } else {
            $submitFailedValidation = true;
        }

        // If submission was successful, load the details page
        if(!$submitFailedValidation) {
            $this->app->system->page->forcePage('cronjob', 'details&cronjob_id='.\CMSApplication::$VAR['qform']['cronjob_id']);

        }

    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $cronjob_details = array_merge($this->app->components->cronjob->getRecord(\CMSApplication::$VAR['cronjob_id']), \CMSApplication::$VAR['qform']);
    } else {
        $cronjob_details = $this->app->components->cronjob->getRecord(\CMSApplication::$VAR['cronjob_id']);
    }

    // Build the page
    $this->app->smarty->assign('cronjob_details', $cronjob_details);

}
