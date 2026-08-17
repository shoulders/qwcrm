<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    $this->app->system->page->forcePage('client', 'search');
}

// Load the edit page if allowed
if(!$this->app->components->client->checkRecordAllowsEdit(\CMSApplication::$VAR['client_id'])) {
    $this->app->system->page->forcePage('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);

} else {

    // If client data has been submitted, Update the record
    if(isset(\CMSApplication::$VAR['submit'])) {

        // Holding variable for validation tests
        $submitFailedValidation = false;

        // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
        if($this->app->components->client->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'])) {

            // Update the record
            $this->app->components->client->updateRecord(\CMSApplication::$VAR['qform']);

            // Redirect to the client's details page
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Client details updated."));
            $this->app->system->page->forcePage('client', 'details&client_id='.\CMSApplication::$VAR['qform']['client_id']);

        } else {
            $submitFailedValidation = true;
        }

        // Load the details page is submission was successful
        if(!$submitFailedValidation) {
            $this->app->system->page->forcePage('client', 'details&client_id='.\CMSApplication::$VAR['qform']['client_id']);
        }

    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $client_details = array_merge($this->app->components->client->getRecord(\CMSApplication::$VAR['qform']['client_id']), \CMSApplication::$VAR['qform']);
    } else {
        $client_details = $this->app->components->client->getRecord(\CMSApplication::$VAR['client_id']);
    }    

    // Build the page
    $this->app->smarty->assign('client_details', $client_details);
    $this->app->smarty->assign('client_types', $this->app->components->client->getTypes());
        
}