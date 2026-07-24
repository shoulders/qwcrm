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

// Load the edit page if allowed
if(!$this->app->components->creditnote->checkRecordAllowsEdit(\CMSApplication::$VAR['creditnote_id'])) {
    $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.\CMSApplication::$VAR['creditnote_id']);
} else {

    /* I dont think block is needed
    // Get credit note details from whichever source, and fill in the blanks
    $creditnote_details = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id']);
    \CMSApplication::$VAR['qform'] = \CMSApplication::$VAR['qform'] ?? array();
    $creditnote_details = array_merge($creditnote_details, \CMSApplication::$VAR['qform']);

    // Get credit note items (if present) from whichever source
    $creditnote_items = \CMSApplication::$VAR['qform']['creditnote_items'] ?? $this->app->components->creditnote->getItems(\CMSApplication::$VAR['creditnote_id']) ?? null;
    */

    // Prevent undefined variable errors
    \CMSApplication::$VAR['qform']['creditnote_items'] = \CMSApplication::$VAR['qform']['creditnote_items'] ?? null;

    // Update credit note (if submited)
    if(isset(\CMSApplication::$VAR['submit']))
    {
        // Check the submission is valid, if not, reload the page with an error message
        if($this->app->components->creditnote->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform']))
        {
            $this->app->components->creditnote->updateRecord(\CMSApplication::$VAR['qform']);
            $this->app->components->creditnote->insertItems(\CMSApplication::$VAR['qform']['creditnote_id'], \CMSApplication::$VAR['qform']['creditnote_items']);
            $this->app->components->creditnote->recalculateTotals(\CMSApplication::$VAR['qform']['creditnote_id']);
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Credit note updated successfully."));

            // Load credit note record - this makes sure any calculations are taken into account such as balance and status
            //$creditnote_details = $this->app->components->creditnote->getRecord($creditnote_details['creditnote_id']);

            // Load details page
            $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.\CMSApplication::$VAR['qform']['creditnote_id']);

        // Submission has failed validation,
        } else {
            $submitFailedValidation = true;
        }
    }

    // If a submission happend and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $creditnote_details = array_merge($this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id']), \CMSApplication::$VAR['qform']);
        $creditnote_items = \CMSApplication::$VAR['qform']['creditnote_items'];
    } else {
        $creditnote_details = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id']);

        // Use items passed from creditnote:new when creating a new creditnote item, or just load the record
        $creditnote_items = \CMSApplication::$VAR['qform']['creditnote_items'] ?? $this->app->components->creditnote->getItems(\CMSApplication::$VAR['creditnote_id']);
    }

    // Default Record Items - used to populate creditenote when you press a button
    if($creditnote_details['invoice_id']) {
            // Get invoice items with voucher records merged as standard items
            $parent_record_items = $this->app->components->invoice->getItems($creditnote_details['invoice_id']);

            // Rename 'invoice_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'invoice_item_id' not renaming it
            $parent_record_items = json_encode($parent_record_items);
            $parent_record_items = str_replace('invoice_item_id', 'creditnote_item_id', $parent_record_items);
            $parent_record_items = json_decode($parent_record_items, true);

    } else if($creditnote_details['expense_id']) {

            // Get expense items
            $parent_record_items = $this->app->components->expense->getItems($creditnote_details['expense_id']);

            // Add `unit_discount` to each item to allow for Credit note compatibility
            foreach($parent_record_items as &$parent_record_item) {
                $parent_record_item['unit_discount'] = '0.00';
            }
            unset($parent_record_item); // break the reference after the loop

            // Rename 'expense_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'expense_item_id' not renaming it
            $parent_record_items = json_encode($parent_record_items);
            $parent_record_items = str_replace('expense_item_id', 'creditnote_item_id', $parent_record_items);
            $parent_record_items = json_decode($parent_record_items, true);

    } else {
        //fallback
        $creditnote_items = null;
    }

    // Disable all VAT codes except `T9` for `Standalone` Action Type CR - I could specify only for VAT tax systems, but I have not as it makes no difference
    if($creditnote_details['action_type'] == 'standalone') {
        $vat_tax_codes = $this->app->components->company->getVatTaxCodes(false, null, ['T9']);
        $default_vat_tax_code = 'T9';
    } else {
        $vat_tax_codes = $this->app->components->company->getVatTaxCodes(false);
        $default_vat_tax_code = $this->app->components->company->getDefaultVatTaxCode($creditnote_details['tax_system']);
    }

    // Credit Note Details
    $this->app->smarty->assign('creditnote_details',       $creditnote_details);
    $this->app->smarty->assign('company_details',          $this->app->components->company->getRecord());
    $this->app->smarty->assign('client_details',           $this->app->components->client->getRecord($creditnote_details['client_id']));
    $this->app->smarty->assign('supplier_details',         $this->app->components->supplier->getRecord($creditnote_details['supplier_id']));
    $this->app->smarty->assign('creditnote_items_json',    json_encode($creditnote_items));
    $this->app->smarty->assign('parent_record_items_json', json_encode($parent_record_items));

    // Payment Details
    $this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes());
    $this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods());
    $this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses());
    $this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, null, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['creditnote_id']));

    // Misc
    $this->app->smarty->assign('creditnote_statuses',      $this->app->components->creditnote->getStatuses());
    $this->app->smarty->assign('creditnote_types',         $this->app->components->creditnote->getTypes());
    $this->app->smarty->assign('creditnote_action_types',  $this->app->components->creditnote->getActionTypes());
    $this->app->smarty->assign('vat_tax_codes',            $vat_tax_codes);
    $this->app->smarty->assign('default_vat_tax_code',     $default_vat_tax_code);
    $this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($creditnote_details['employee_id'], 'display_name'));

}
