<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an user_id
if(!isset(\CMSApplication::$VAR['user_id']) || !\CMSApplication::$VAR['user_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No User ID supplied."));
    $this->app->system->page->forcePage('user', 'search');
}

// Load the edit page if allowed
if(!$this->app->components->user->checkRecordAllowsEdit(\CMSApplication::$VAR['user_id'])) {
    $this->app->system->page->forcePage('user', 'details&user_id='.\CMSApplication::$VAR['user_id']);

} else {

    // If user data has been submitted, Update the record
    if(isset(\CMSApplication::$VAR['submit'])) {

        // Holding variable for validation tests
        $submitFailedValidation = false;

        // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
        if($this->app->components->user->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'])) {

            // Update the record
            $this->app->components->user->updateRecord(\CMSApplication::$VAR['qform']);

            // Redirect to the users's details page
            $this->app->system->variables->systemMessagesWrite('success', _gettext("User details updated."));
            $this->app->system->page->forcePage('user', 'details&user_id='.\CMSApplication::$VAR['qform']['user_id']);

        } else {
            $submitFailedValidation = true;
        }

        // Load the details page is submission was successful
        if(!$submitFailedValidation) {
            $this->app->system->page->forcePage('user', 'details&user_id='.\CMSApplication::$VAR['qform']['user_id']);
        }

    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $user_details = array_merge($this->app->components->user->getRecord(\CMSApplication::$VAR['qform']['user_id']), \CMSApplication::$VAR['qform']);
    } else {
        $user_details = $this->app->components->user->getRecord(\CMSApplication::$VAR['user_id']);
    }

    // Set the template for the correct user type (client/employee)
    if($user_details['client_id']) {
        $this->app->smarty->assign('client_display_name', $this->app->components->client->getRecord($user_details['client_id'], 'client_display_name'));
        $this->app->smarty->assign('usergroups', $this->app->components->user->getUsergroups('clients'));
    } else {
        $this->app->smarty->assign('usergroups', $this->app->components->user->getUsergroups('employees'));
    }

    // Build the page
    $this->app->smarty->assign('user_details', $user_details);
    $this->app->smarty->assign('user_locations', $this->app->components->user->getLocations());

}
