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
if($this->app->components->creditnote->checkRecordCanBeCreated(\CMSApplication::$VAR['client_id'] ?? null, \CMSApplication::$VAR['invoice_id'] ?? null, \CMSApplication::$VAR['supplier_id'] ?? null, \CMSApplication::$VAR['expense_id'] ?? null))
{
    /* Sales Credit Notes */

    // Sales Credit Note (Client) - (client:details)
    if(\CMSApplication::$VAR['client_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('client', 'details'))
    {
        $record['action_type'] = 'standalone';
        $record['client_id'] = \CMSApplication::$VAR['client_id'];
        $record['type'] = 'sales';
        $record['reference'] = _gettext("Client").': '.\CMSApplication::$VAR['client_id'];
        $record['sales_tax_rate'] = 0.00;
        $creditnote_items = array (0 =>
                                    array (
                                        'creditnote_item_id' => null,
                                        'invoice_id' => null,
                                        'tax_system' => null,
                                        'description' => $record['reference'],
                                        'unit_qty' => '1.00',
                                        'unit_net' => '0.00',
                                        'unit_discount' => '0.00',
                                        'sales_tax_exempt' => 0,
                                        'vat_tax_code' => 'T9',
                                        'unit_tax_rate' => '0.00',
                                        'unit_tax' => '0.00',
                                        'unit_gross' => '0.00',
                                        'subtotal_net' => '0.00',
                                        'subtotal_tax' => '0.00',
                                        'subtotal_gross' => '0.00'
                                    ),
                                );
    }

    // Sales Credit Note (Invoice) - (invoice:details)
    elseif(\CMSApplication::$VAR['invoice_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details'))
    {
        $invoice_details = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id']);

        // Void all of the parent invoice's vouchers (their ability to voided has already been checked)
        $this->app->components->voucher->updateInvoiceVouchersStatuses(\CMSApplication::$VAR['invoice_id'], null, 'voided');

        $record['action_type'] = (float) $invoice_details['balance'] ? 'close' : 'refund';
        $record['invoice_id'] = \CMSApplication::$VAR['invoice_id'];
        $record['client_id'] = $invoice_details['client_id'];
        $record['type'] = 'sales';
        $record['reference'] = $invoice_details['balance']
            ? _gettext("Close").' '._gettext("Invoice").': '.\CMSApplication::$VAR['invoice_id']
            : _gettext("Refund").' '._gettext("Invoice").': '.\CMSApplication::$VAR['invoice_id'];
        $record['sales_tax_rate'] = $invoice_details['sales_tax_rate'];

        // Copy invoice items or use single item
        $useRecordItems = (float) $invoice_details['balance'] ? true : false;

        // Build credit note items
        if($useRecordItems) {
            $creditnote_items = array (0 =>
                                    array (
                                        'creditnote_item_id' => null,
                                        'invoice_id' => null,
                                        'tax_system' => null,
                                        'description' => $record['reference'],
                                        'unit_qty' => '1.00',
                                        'unit_net' => '0.00',
                                        'unit_discount' => '0.00',
                                        'sales_tax_exempt' => 0,
                                        'vat_tax_code' => $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']),
                                        'unit_tax_rate' => '0.00',
                                        'unit_tax' => '0.00',
                                        'unit_gross' => '0.00',
                                        'subtotal_net' => '0.00',
                                        'subtotal_tax' => '0.00',
                                        'subtotal_gross' => '0.00'
                                    ),
                                );
        } else {
            // Get invoice items with voucher records merged as standard items
            $creditnote_items = $this->app->components->invoice->getItems(\CMSApplication::$VAR['invoice_id'], true);

            // Rename 'invoice_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'invoice_item_id' not renaming it
            $creditnote_items = json_encode($creditnote_items);
            $creditnote_items = str_replace('invoice_item_id', 'creditnote_item_id', $creditnote_items);
            $creditnote_items = json_decode($creditnote_items, true);
        }

    }

    /* Purchase Credit Notes */

    // Purchase Credit Note (Supplier) - (supplier:details)
    elseif(\CMSApplication::$VAR['supplier_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('supplier', 'details'))
    {
        $record['action_type'] = 'standalone';
        $record['supplier_id'] = \CMSApplication::$VAR['supplier_id'];
        $record['type'] = 'purchase';
        $record['reference'] = _gettext("Supplier").': '.\CMSApplication::$VAR['supplier_id'] ;
        $record['sales_tax_rate'] = 0.00;
        $creditnote_items = array (0 =>
                                    array (
                                        'creditnote_item_id' => null,
                                        'invoice_id' => null,
                                        'tax_system' => null,
                                        'description' => $record['reference'],
                                        'unit_qty' => '1.00',
                                        'unit_net' => '0.00',
                                        'unit_discount' => '0.00',
                                        'sales_tax_exempt' => 0,
                                        'vat_tax_code' => 'T9',
                                        'unit_tax_rate' => '0.00',
                                        'unit_tax' => '0.00',
                                        'unit_gross' => '0.00',
                                        'subtotal_net' => '0.00',
                                        'subtotal_tax' => '0.00',
                                        'subtotal_gross' => '0.00'
                                    ),
                                );
    }

    // Purchase Credit Note (Expense) - (expense:details)
    elseif(\CMSApplication::$VAR['expense_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details'))
    {
        $expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);

        $record['action_type'] = (float) $expense_details['balance'] ? 'close' : 'refund';
        $record['expense_id'] = \CMSApplication::$VAR['expense_id'];
        $record['supplier_id'] = $expense_details['supplier_id'];
        $record['type'] = 'purchase';
        $record['reference'] = $invoice_details['balance']
            ? _gettext("Close").' '._gettext("Expense").': '.\CMSApplication::$VAR['expense_id']
            : _gettext("Refund").' '._gettext("Expense").': '.\CMSApplication::$VAR['expense_id'];
        $record['sales_tax_rate'] = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id'], 'sales_tax_rate');

        // Copy expense items or use single item
        $useRecordItems = (float) $expense_details['balance'] ? true : false;

        // Build credit note items
        if($useRecordItems) {
            $creditnote_items = array (0 =>
                                    array (
                                        'creditnote_item_id' => null,
                                        'expense_id' => null,
                                        'tax_system' => null,
                                        'description' => $record['reference'],
                                        'unit_qty' => '1.00',
                                        'unit_net' => '0.00',
                                        'unit_discount' => '0.00',
                                        'sales_tax_exempt' => 0,
                                        'vat_tax_code' => $this->app->components->company->getDefaultVatTaxCode($expense_details['tax_system']),
                                        'unit_tax_rate' => '0.00',
                                        'unit_tax' => '0.00',
                                        'unit_gross' => '0.00',
                                        'subtotal_net' => '0.00',
                                        'subtotal_tax' => '0.00',
                                        'subtotal_gross' => '0.00'
                                    ),
                                );
        } else {
            // Get expense items
            $creditnote_items = $this->app->components->expense->getItems(\CMSApplication::$VAR['expense_id']);

            // Rename 'expense_item_id' --> 'creditnote_item_id' - chaining these functions fail by removing 'expense_item_id' not renaming it
            $creditnote_items = json_encode($creditnote_items);
            $creditnote_items = str_replace('expense_item_id', 'creditnote_item_id', $creditnote_items);
            $creditnote_items = json_decode($creditnote_items, true);
        }

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
