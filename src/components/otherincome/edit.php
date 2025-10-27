<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Otherincome ID supplied."));
    $this->app->system->page->forcePage('otherincome', 'search');
}

// Check if otherincome is deleted
if($this->app->components->otherincome->getRecord(\CMSApplication::$VAR['otherincome_id'], 'status') === 'deleted') {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this otherincome because it has been deleted."));
    $this->app->system->page->forcePage('otherincome', 'search');
}

// Check if otherincome can be edited
if(!$this->app->components->otherincome->checkRecordAllowsEdit(\CMSApplication::$VAR['otherincome_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this otherincome because its status does not allow it."));
    $this->app->system->page->forcePage('otherincome', 'details&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);
}

// Get otherincome details from whichever source, and fill in the blanks (page submission or new)
$otherincome_details = $this->app->components->otherincome->getRecord(\CMSApplication::$VAR['otherincome_id']);
\CMSApplication::$VAR['qform'] = \CMSApplication::$VAR['qform'] ?? array();
$otherincome_details = array_merge($otherincome_details, \CMSApplication::$VAR['qform']);

// Get otherincome items (if present) from whichever source
$otherincome_items = \CMSApplication::$VAR['qform']['otherincome_items'] ?? $this->app->components->otherincome->getItems(\CMSApplication::$VAR['otherincome_id']) ?? null;

##################################
#      Update otherincome        #
##################################

// Update otherincome (if submited)
if(isset(\CMSApplication::$VAR['submit']))
{
    // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
    if($this->app->components->otherincome->checkRecordCanBeSubmitted($otherincome_details))
    {
        // Update the record
        $this->app->components->otherincome->updateRecord($otherincome_details);
        $this->app->components->otherincome->insertItems($otherincome_details['otherincome_id'], $otherincome_items);
        $this->app->components->otherincome->recalculateTotals($otherincome_details['otherincome_id']);
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Expense updated successfully."));

        // Load the new otherincome page
        if (\CMSApplication::$VAR['submit'] == 'submitandnew')
        {
            $this->app->system->page->forcePage('otherincome', 'new');
        }

        // Load the new payment page for otherincome
        elseif (\CMSApplication::$VAR['submit'] == 'submitandpayment')
        {
            $this->app->system->page->forcePage('payment', 'new&type=otherincome&otherincome_id='.$otherincome_details['otherincome_id']);
        }

        // Refresh otherincome record - this makes sure any calculations are taken into account such as balance and status after record update
        else
        {
            $otherincome_details = $this->app->components->otherincome->getRecord($otherincome_details['otherincome_id']);
        }
    }
}

// Build the page

// Expense Details
$this->app->smarty->assign('otherincome_details',       $otherincome_details);
$this->app->smarty->assign('otherincome_items_json',    json_encode($otherincome_items));

// Misc
$this->app->smarty->assign('otherincome_statuses',         $this->app->components->otherincome->getStatuses());
$this->app->smarty->assign('otherincome_types',            $this->app->components->otherincome->getTypes());
$this->app->smarty->assign('vat_tax_codes',            $this->app->components->company->getVatTaxCodes(false));
$this->app->smarty->assign('default_vat_tax_code',     $this->app->components->company->getDefaultVatTaxCode($otherincome_details['tax_system']));
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($otherincome_details['employee_id'], 'display_name'));
