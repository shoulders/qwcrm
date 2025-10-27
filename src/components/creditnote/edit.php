<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an creditnote_id
if(!isset(\CMSApplication::$VAR['creditnote_id']) || !\CMSApplication::$VAR['creditnote_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Credit Note ID supplied."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Check if creditnote is deleted
if($this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id'], 'status') === 'deleted') {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this credit note because it has been deleted."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Check if creditnote can be edited
if(!$this->app->components->creditnote->checkRecordAllowsEdit(\CMSApplication::$VAR['creditnote_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this credit note because its status does not allow it."));
    $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.\CMSApplication::$VAR['creditnote_id']);
}

// Get credit note details from whichever source, and fill in the blanks
$creditnote_details = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id']);
\CMSApplication::$VAR['qform'] = \CMSApplication::$VAR['qform'] ?? array();
$creditnote_details = array_merge($creditnote_details, \CMSApplication::$VAR['qform']);

// Get credit note items (if present) from whichever source
$creditnote_items = \CMSApplication::$VAR['qform']['creditnote_items'] ?? $this->app->components->creditnote->getItems(\CMSApplication::$VAR['creditnote_id']) ?? null;

// Update credit note (if submited)
if(isset(\CMSApplication::$VAR['submit']))
{
    // Check the submission is valid, if not, load the page with an error message
    if($this->app->components->creditnote->checkRecordCanBeSubmitted($creditnote_details))
    {
        $this->app->components->creditnote->insertItems($creditnote_details['creditnote_id'], $creditnote_items);
        $this->app->components->creditnote->updateRecord($creditnote_details);
        $this->app->components->creditnote->recalculateTotals($creditnote_details['creditnote_id']);
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Credit note updated successfully."));

        // Load credit note record - this makes sure any calculations are taken into account such as balance and status
        //$creditnote_details = $this->app->components->creditnote->getRecord($creditnote_details['creditnote_id']);

        // Load details page
        $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.$creditnote_details['creditnote_id']);
    }
}

// Credit Note Details
$this->app->smarty->assign('creditnote_details',       $creditnote_details);
$this->app->smarty->assign('company_details',          $this->app->components->company->getRecord());
$this->app->smarty->assign('client_details',           $this->app->components->client->getRecord($creditnote_details['client_id'] ?? null));
$this->app->smarty->assign('supplier_details',         $this->app->components->supplier->getRecord($creditnote_details['supplier_id'] ?? null));
$this->app->smarty->assign('creditnote_items_json',    json_encode($creditnote_items));

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes());
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses());
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, null, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['creditnote_id']));

// Misc
$this->app->smarty->assign('creditnote_statuses',      $this->app->components->creditnote->getStatuses());
$this->app->smarty->assign('creditnote_types',         $this->app->components->creditnote->getTypes());
$this->app->smarty->assign('vat_tax_codes',            $this->app->components->company->getVatTaxCodes(false));
$this->app->smarty->assign('default_vat_tax_code',     $this->app->components->company->getDefaultVatTaxCode($creditnote_details['tax_system']));
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($creditnote_details['employee_id'], 'display_name'));
