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
        if($this->app->components->workorder->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'], 'resolution')) {

            // Update the record
            $this->app->components->workorder->updateResolution(\CMSApplication::$VAR['qform']);

            // Success message
            switch (\CMSApplication::$VAR['submit']) {

                // Submit Changes only
                case 'submitchangesonly':
                    $this->app->system->variables->systemMessagesWrite('success', _gettext("Resolution has been updated."));
                    break;

                // Close without invoice
                case 'closewithoutinvoice':
                    // Messages and further actions handled later
                    break;

                // Close with invoice
                case 'closewithinvoice':
                    // Messages and further actions handled later
                    break;

            }

        // Submission has failed validation,
        } else {
            $submitFailedValidation = true;
        }

        // If submission was successful, load the details page
        if(!$submitFailedValidation) {            

            switch (\CMSApplication::$VAR['submit']) {

                // Submit Changes only
                case 'submitchangesonly':
                    $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
                    break;

                // Close without invoice
                case 'closewithoutinvoice':

                    // Check the whole workorder record before closing
                    if(!$this->app->components->workorder->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'], 'all')) {
                        //$submitFailedValidation = true;
                        $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
                    } else {
                        // Close Message is handled in this function
                        $this->app->components->workorder->closeRecord(\CMSApplication::$VAR['workorder_id']);
                    }
                    break;

                // Close with invoice
                case 'closewithinvoice':

                     // Check the whole workorder record before closing
                    if(!$this->app->components->workorder->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'], 'all')) {
                        //$submitFailedValidation = true;
                        $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
                    } else {
                        // Create a new invoice attached to this work order (closing this workorder this is handled upstream by the invoice creation process)
                        $this->app->system->page->forcePage('invoice', 'new&workorder_id='.\CMSApplication::$VAR['workorder_id']);
                    }
                    break;

            }

        }

    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $workorder_details = array_merge($this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id']), \CMSApplication::$VAR['qform']);
    } else {
        $workorder_details = $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id']);
    }

    // Build the page
    $this->app->smarty->assign('resolution', $workorder_details['resolution']);

}
