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
if($this->app->components->creditnote->checkRecordCanBeCreated(\CMSApplication::$VAR['client_id'] ?? null, \CMSApplication::$VAR['invoice_id'] ?? null, \CMSApplication::$VAR['supplier_id'] ?? null, \CMSApplication::$VAR['expense_id'] ?? null, false))
{
    /* Sales Credit Notes */

    // Sales Credit Note (Client) - (client:details)
    if(\CMSApplication::$VAR['client_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('client', 'details'))
    {
        $record['client_id'] = \CMSApplication::$VAR['client_id'];

        $record['type'] = 'sales';
        $record['reference'] = _gettext("Client").': '.\CMSApplication::$VAR['client_id'];
        $record['sales_tax_rate'] = 0.00;
        $creditnote_items = array();    // This will not have items but i might add a single one manually
    }

    // Sales Credit Note (Invoice) - (invoice:details)
    elseif(\CMSApplication::$VAR['invoice_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details'))
    {
        $record['invoice_id'] = \CMSApplication::$VAR['invoice_id'];
        $record['client_id'] = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'client_id');

        $record['type'] = 'sales';
        $record['reference'] = _gettext("Invoice").': '.\CMSApplication::$VAR['invoice_id'];
        $record['sales_tax_rate'] = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'sales_tax_rate');

        // Get invoice items with voucher records merged as standard items
        $creditnote_items = $this->app->components->invoice->getItems(\CMSApplication::$VAR['invoice_id'], true);

        // Rename 'invoice_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'invoice_item_id' not renaming it
        $creditnote_items = json_encode($creditnote_items);
        $creditnote_items = str_replace('invoice_item_id', 'creditnote_item_id', $creditnote_items);
        $creditnote_items = json_decode($creditnote_items, true);
    }

    /* Purchase Credit Notes */

    // Purchase Credit Note (Supplier) - (supplier:details)
    elseif(\CMSApplication::$VAR['supplier_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('supplier', 'details'))
    {
        $record['supplier_id'] = \CMSApplication::$VAR['supplier_id'];

        $record['type'] = 'purchase';
        $record['reference'] = _gettext("Supplier").': '.\CMSApplication::$VAR['supplier_id'] ;
        $record['sales_tax_rate'] = 0.00;
        $creditnote_items = array();    // This will not have items but i might add a single one manually
    }

    // Purchase Credit Note (Expense) - (expense:details)
    elseif(\CMSApplication::$VAR['expense_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details'))
    {
        $record['expense_id'] = \CMSApplication::$VAR['expense_id'];
        $record['supplier_id'] = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id'], 'supplier_id');

        $record['type'] = 'purchase';
        $record['reference'] = _gettext("Expense").': '.\CMSApplication::$VAR['expense_id'] ;
        $record['sales_tax_rate'] = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id'], 'sales_tax_rate');

        // Get invoice items with voucher records merged as standard items
        $creditnote_items = $this->app->components->expense->getItems(\CMSApplication::$VAR['expense_id']);

        // Rename 'expense_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'expense_item_id' not renaming it
        $creditnote_items = json_encode($creditnote_items);
        $creditnote_items = str_replace('expense_item_id', 'creditnote_item_id', $creditnote_items);
        $creditnote_items = json_decode($creditnote_items, true);

        // Manual ? - builds a single row use `#__creditnote_items` for structure

        //$expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);

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
    }


    // Compensate for multiple entry points
    $record['client_id'] ??= null;
    $record['invoice_id'] ??= null;
    $record['supplier_id'] ??= null;
    $record['expense_id'] ??= null;

    // Create the credit note and return the new creditnote_id (this has no items)
    $creditnote_id = $this->app->components->creditnote->insertRecord($record);

    // Get invoice/expense items to populate the credit notes item fields
    $variables['qform']['creditnote_items'] = $creditnote_items;

    // Edit the newly created credit note populating with items on page load
    $this->app->system->page->forcePage('creditnote', 'edit&creditnote_id='.$creditnote_id, $variables);


}

// Return to details page if possible else send to CR search (not validating that there is more than one record variable so this modified order ensures the correct details page is loaded)
else
{
    if(\CMSApplication::$VAR['invoice_id'] ?? null)
    {
        $this->app->system->page->forcePage('invoice', 'details&invoice_id='.\CMSApplication::$VAR['invoice_id']);
    }
    elseif(\CMSApplication::$VAR['client_id'] ?? null)
    {
        $this->app->system->page->forcePage('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);
    }
    elseif(\CMSApplication::$VAR['expense_id'] ?? null)
    {
        $this->app->system->page->forcePage('expense', 'details&expense_id='.\CMSApplication::$VAR['expense_id']);
    }
    elseif(\CMSApplication::$VAR['supplier_id'] ?? null)
    {
        $this->app->system->page->forcePage('supplier', 'details&supplier_id='.\CMSApplication::$VAR['supplier_id']);
    }
    else
    {
        // Fallback Error Control
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot create a credit note by the method you just tried, report to admins."));
        $this->app->system->page->forcePage('creditnote', 'search');
    }

}
