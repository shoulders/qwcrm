<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'new')
    && !$this->app->system->security->checkPageAccessedViaQwcrm('client', 'details')
    && !$this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details')
    && !$this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details')
    && !$this->app->system->security->checkPageAccessedViaQwcrm('supplier', 'details')
) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check CR can be created (this check is also do on the buttons but silently)
if($this->app->components->creditnote->checkRecordCanBeCreated(\CMSApplication::$VAR['client_id'] = null, \CMSApplication::$VAR['invoice_id'] = null, \CMSApplication::$VAR['supplier_id'] = null, \CMSApplication::$VAR['expense_id'] = null, false))
{
    /* Sales Credit Notes */

    // Sales Credit Note (Client) - (client:details)
    if(\CMSApplication::$VAR['client_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('client', 'details'))
    {
        $client_id = \CMSApplication::$VAR['client_id'];
        $type = 'sales';
        //$reference = _gettext("Client").': '.$client_id;
        $reference = '';
    }

    // Sales Credit Note (Invoice) - (invoice:details)
    if(\CMSApplication::$VAR['invoice_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details'))
    {
        $invoice_id = \CMSApplication::$VAR['invoice_id'];
        $client_id = $this->app->components->invoice->getRecord($invoice_id, 'client_id');
        $type = 'sales';
        //$reference = _gettext("Invoice").': '.$invoice_id;
        $reference = '';
        $sales_tax_rate = $this->app->components->invoice->getRecord($invoice_id, 'sales_tax_rate');

        // Get invoice items with voucher records merged as standard items
        $creditnote_items = $this->app->components->invoice->getItems($invoice_id, true);

        // Rename 'invoice_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'invoice_item_id' not renaming it
        $creditnote_items = json_encode($creditnote_items);
        $creditnote_items = str_replace('invoice_item_id', 'creditnote_item_id', $creditnote_items);
        $creditnote_items = json_decode($creditnote_items, true);
    }

    /* Purchase Credit Notes */

    // Purchase Credit Note (Supplier) - (supplier:details)
    if(\CMSApplication::$VAR['supplier_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('supplier', 'details'))
    {
        $supplier_id = \CMSApplication::$VAR['supplier_id'];
        $type = 'purchase';
        //$reference = _gettext("Supplier").': '.$supplier_id ;
        $reference = '';
    }

    // Purchase Credit Note (Expense) - (expense:details)
    if(\CMSApplication::$VAR['expense_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details'))
    {
        $expense_id = \CMSApplication::$VAR['expense_id'];
        $expense_details = $this->app->components->expense->getRecord($expense_id);
        $supplier_id = $expense_details['supplier_id'];
        $type = 'purchase';
        //$reference = _gettext("Expense").': '.$expense_id ;
        $reference = '';

        /* Build a single item to match the expense record - this is a workaround whilst expenses does not use items
        $creditnote_items = array();
        $creditnote_items[0]['creditnote_item_id'] = 1;
        $creditnote_items[0]['expense_id'] = $expense_details['expense_id'];
        $creditnote_items[0]['tax_system'] = $expense_details['tax_system'];
        $creditnote_items[0]['description'] = _gettext("Items from from expense").': '.$expense_details['expense_id'];
        $creditnote_items[0]['unit_qty'] = 1;
        $creditnote_items[0]['unit_net'] = $expense_details['unit_net'];
        $creditnote_items[0]['unit_discount'] = 0.00;
        $creditnote_items[0]['sales_tax_exempt'] = 1;
        $creditnote_items[0]['vat_tax_code'] = 'T9';
        $creditnote_items[0]['unit_tax_rate'] = 0.00;
        $creditnote_items[0]['unit_tax'] = $expense_details['unit_tax'];
        $creditnote_items[0]['unit_gross'] = $expense_details['unit_gross'];
        $creditnote_items[0]['subtotal_net'] = $expense_details['unit_net'];
        $creditnote_items[0]['subtotal_tax'] = $expense_details['unit_tax'];
        $creditnote_items[0]['subtotal_gross'] = $expense_details['unit_gross'];
        */

        // Get invoice items with voucher records merged as standard items
        $creditnote_items = $this->app->components->expense->getItems($expense_id);

        // Rename 'invoice_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'invoice_item_id' not renaming it
        $creditnote_items = json_encode($creditnote_items);
        $creditnote_items = str_replace('expense_item_id', 'creditnote_item_id', $creditnote_items);
        $creditnote_items = json_decode($creditnote_items, true);
    }

    // We have a valid request
    if($client_id ?? $supplier_id ?? false)
    {
        // Build variables to be used to populate creditnote
        $record['client_id'] = $client_id ?? null;
        $record['invoice_id'] = $invoice_id ?? null;
        $record['supplier_id'] = $supplier_id ?? null;
        $record['expense_id'] = $expense_id ?? null;
        $record['type'] = $type ?? null;
        $record['reference'] = $reference ?? null;
        $record['sales_tax_rate'] = $sales_tax_rate ?? (QW_TAX_SYSTEM === 'sales_tax_cash') ? $this->app->components->company->getRecord('sales_tax_rate') : 0.00;

        // Create the credit note and return the new creditnote_id
        $creditnote_id = $this->app->components->creditnote->insertRecord($record);

        // Get invoice items to populate the credit note
        $variables['qform']['creditnote_items'] = $creditnote_items ?? array();

        // Load the newly created credit note edit page but populate with invoice items
        $this->app->system->page->forcePage('creditnote', 'edit&creditnote_id='.$creditnote_id, $variables);
    }

}

// Fallback Error Control
$this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot create a credit note by the method you just tried, report to admins."));
$this->app->system->page->forcePage('creditnote', 'search');
