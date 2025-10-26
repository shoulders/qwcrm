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

// Check if expense is deleted
if($this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id'], 'status') === 'deleted') {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this expense because it has been deleted."));
    $this->app->system->page->forcePage('expense', 'search');
}

// Check if expense can be edited
if(!$this->app->components->expense->checkRecordAllowsEdit(\CMSApplication::$VAR['expense_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this expense because its status does not allow it."));
    $this->app->system->page->forcePage('expense', 'details&expense_id='.\CMSApplication::$VAR['expense_id']);
}

// Get expense details from whichever source, and fill in the blanks (page submission or new)
$expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);
\CMSApplication::$VAR['qform'] = \CMSApplication::$VAR['qform'] ?? array();
$expense_details = array_merge($expense_details, \CMSApplication::$VAR['qform']);

// Get expense items (if present) from whichever source
$expense_items = \CMSApplication::$VAR['qform']['expense_items'] ?? $this->app->components->expense->getItems(\CMSApplication::$VAR['expense_id']) ?? null;

##################################
#      Update Expense            #
##################################

// Update expense (if submited)
if(isset(\CMSApplication::$VAR['submit']))
{
    // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
    if($this->app->components->expense->checkRecordCanBeSubmitted($expense_details))
    {
        // Update the record
        $this->app->components->expense->updateRecord($expense_details);
        $this->app->components->expense->insertItems($expense_details['expense_id'], $expense_items);
        $this->app->components->expense->recalculateTotals($expense_details['expense_id']);
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Expense updated successfully."));

        // Load the new expense page
        if (\CMSApplication::$VAR['submit'] == 'submitandnew')
        {
            $this->app->system->page->forcePage('expense', 'new');
        }

        // Load the new payment page for expense
        elseif (\CMSApplication::$VAR['submit'] == 'submitandpayment')
        {
            $this->app->system->page->forcePage('payment', 'new&type=expense&expense_id='.$expense_details['expense_id']);
        }

        // Refresh expense record - this makes sure any calculations are taken into account such as balance and status after record update
        else
        {
            $expense_details = $this->app->components->expense->getRecord($expense_details['expense_id']);
        }
    }
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
$this->app->smarty->assign('supplier_display_name', $this->app->components->supplier->getRecord($expense_details['supplier_id'] ?? null, 'display_name'));
