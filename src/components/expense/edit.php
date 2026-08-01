<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an expense_id
if(!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    $this->app->system->page->forcePage('expense', 'search');
}

// Load the edit page if allowed
if(!$this->app->components->expense->checkRecordAllowsEdit(\CMSApplication::$VAR['expense_id'])) {
    $this->app->system->page->forcePage('expense', 'details&expense_id='.\CMSApplication::$VAR['expense_id']);
} else {

    /* I dont think block is needed
    // Get expense details from whichever source, and fill in the blanks (page submission or new)
    $expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);
    \CMSApplication::$VAR['qform'] = \CMSApplication::$VAR['qform'] ?? array();
    $expense_details = array_merge($expense_details, \CMSApplication::$VAR['qform']);

    // Get expense items (if present) from whichever source
    $expense_items = \CMSApplication::$VAR['qform']['expense_items'] ?? $this->app->components->expense->getItems(\CMSApplication::$VAR['expense_id']) ?? null;
    */

    // Prevent undefined variable errors
    \CMSApplication::$VAR['qform']['expense_items'] = \CMSApplication::$VAR['qform']['expense_items'] ?? null;

    ##################################
    #      Update Expense            #
    ##################################

    // Update expense (if submited)
    if(isset(\CMSApplication::$VAR['submit']))
    {
        // Holding variable for validation tests
        $submitFailedValidation = false;

        // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
        if($this->app->components->expense->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform']))
        {
            // Update the record
            $this->app->components->expense->updateRecord(\CMSApplication::$VAR['qform']);
            $this->app->components->expense->insertItems(\CMSApplication::$VAR['qform']['expense_id'], \CMSApplication::$VAR['qform']['expense_items']);
            $this->app->components->expense->recalculateTotals(\CMSApplication::$VAR['qform']['expense_id']);
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Expense updated successfully."));

            // The user also wants to approve the record
            if (\CMSApplication::$VAR['submit'] == 'submitandapprove') {
                if($this->app->components->expense->checkRecordAllowsApprove(\CMSApplication::$VAR['qform']['expense_id'])) {
                    $this->app->components->expense->updateStatus(\CMSApplication::$VAR['qform']['expense_id'], 'unpaid');
                } else {
                    $submitFailedValidation = true;
                }
            }

        } else {
            $submitFailedValidation = true;
        }

        // Load the details page is submission was successful
        if(!$submitFailedValidation) {
            $this->app->system->page->forcePage('expense', 'details&expense_id='.\CMSApplication::$VAR['qform']['expense_id']);
        }
    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $expense_details = array_merge($this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']), \CMSApplication::$VAR['qform']);
        $expense_items = \CMSApplication::$VAR['qform']['expense_items'] ;
    } else {
        $expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);
        $expense_items = $this->app->components->expense->getItems(\CMSApplication::$VAR['expense_id']);
    }

    // Build the page

    // Expense Details
    $this->app->smarty->assign('expense_details',       $expense_details);
    $this->app->smarty->assign('expense_items_json',    json_encode($expense_items));

    // Misc
    $this->app->smarty->assign('expense_statuses',         $this->app->components->expense->getStatuses());
    $this->app->smarty->assign('expense_types',            $this->app->components->expense->getTypes());
    $this->app->smarty->assign('vat_tax_codes',            $this->app->components->company->getVatTaxCodes(false));
    $this->app->smarty->assign('default_vat_tax_code',     $this->app->components->company->getDefaultVatTaxCode($expense_details['tax_system']));
    $this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($expense_details['employee_id'], 'display_name'));
}
