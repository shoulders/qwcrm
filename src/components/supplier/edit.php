<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a supplier_id
if(!isset(\CMSApplication::$VAR['supplier_id']) || !\CMSApplication::$VAR['supplier_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Supplier ID supplied."));
    $this->app->system->page->forcePage('supplier', 'search');
}

// Load the edit page if allowed
if(!$this->app->components->supplier->checkRecordAllowsEdit(\CMSApplication::$VAR['supplier_id'])) {
    $this->app->system->page->forcePage('supplier', 'details&supplier_id='.\CMSApplication::$VAR['supplier_id']);
} else {

    // If supplier data has been submitted, Update the record
    if(isset(\CMSApplication::$VAR['submit'])) {

        // Holding variable for validation tests
        $submitFailedValidation = false;

        // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
        if($this->app->components->supplier->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'])) {

            // Update the record
            $this->app->components->supplier->updateRecord(\CMSApplication::$VAR['qform']);

            // Success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Supplier details updated."));


        } else {
            $submitFailedValidation = true;
        }

        // If submission was successful, load the details page
        if(!$submitFailedValidation) {
            $this->app->system->page->forcePage('supplier', 'details&supplier_id='.\CMSApplication::$VAR['qform']['supplier_id']);
        }

    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $supplier_details = array_merge($this->app->components->supplier->getRecord(\CMSApplication::$VAR['qform']['supplier_id']), \CMSApplication::$VAR['qform']);
    } else {
        $supplier_details = $this->app->components->supplier->getRecord(\CMSApplication::$VAR['supplier_id']);
    }

    // Build the page
    $this->app->smarty->assign('supplier_details', $supplier_details);
    $this->app->smarty->assign('supplier_statuses', $this->app->components->supplier->getStatuses());
    $this->app->smarty->assign('supplier_types', $this->app->components->supplier->getTypes());

}
