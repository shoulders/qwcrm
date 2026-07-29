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
    // Holding arrays (not really needed)
    $record = array();
    $single_item = array();

    /* Sales Credit Notes */

    // Sales Credit Note (Client) - (client:details)
    if(\CMSApplication::$VAR['client_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('client', 'details'))
    {
        // Build Creditnote record
        $record['client_id'] = \CMSApplication::$VAR['client_id'];
        $record['type'] = 'sales';
        $record['reference'] = _gettext("Client").': '.\CMSApplication::$VAR['client_id'];
        $record['sales_tax_rate'] = 0.00;
        $record['action_type'] = 'standalone';

        // Build Single Item
        $single_item['description'] = $record['reference'];
        $single_item['unit_qty'] = '1.00';
        $single_item['unit_net'] = '0.00';
        $single_item['unit_discount'] = '0.00';
        $single_item['sales_tax_exempt'] = 0;
        $single_item['vat_tax_code'] = 'T9';
        $single_item['unit_tax_rate'] = '0.00';
        $single_item['unit_tax'] = '0.00';
        $single_item['unit_gross'] = '0.00';
        $single_item['subtotal_net'] = '0.00';
        $single_item['subtotal_tax'] = '0.00';
        $single_item['subtotal_gross'] = '0.00';
    }

    // Sales Credit Note (Invoice) - (invoice:details)
    elseif(\CMSApplication::$VAR['invoice_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details'))
    {
        $invoice_details = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id']);

        // Void all of the parent invoice's vouchers (their ability to voided has already been checked)
        $this->app->components->voucher->updateInvoiceVouchersStatuses(\CMSApplication::$VAR['invoice_id'], null, 'voided');

        // Build Creditnote record
        $record['client_id'] = $invoice_details['client_id'];
        $record['invoice_id'] = \CMSApplication::$VAR['invoice_id'];
        $record['type'] = 'sales';
        $record['reference'] = $invoice_details['balance']
            ? _gettext("Close").' '._gettext("Invoice").': '.\CMSApplication::$VAR['invoice_id']
            : _gettext("Refund").' '._gettext("Invoice").': '.\CMSApplication::$VAR['invoice_id'];
        $record['sales_tax_rate'] = $invoice_details['sales_tax_rate'];
        $record['action_type'] = (float) $invoice_details['balance'] ? 'close' : 'refund';

        // Build Single Item
        $single_item['description'] = $record['reference'];
        $single_item['unit_qty'] = '1.00';
        $single_item['unit_net'] = $invoice_details['unit_net'];
        $single_item['unit_discount'] = $invoice_details['unit_discount'];
        $single_item['sales_tax_exempt'] = 0;
        $single_item['vat_tax_code'] = $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']);
        $single_item['unit_tax_rate'] = preg_match('/^vat_/', QW_TAX_SYSTEM)
                                        ? $this->app->components->company->getVatRate($single_item['vat_tax_code'])
                                        : (QW_TAX_SYSTEM === 'sales_tax_cash'
                                            ? $this->app->components->company->getRecord('sales_tax_rate')
                                            : '0.00');
        $single_item['unit_tax'] = $invoice_details['unit_tax'];
        $single_item['unit_gross'] = $invoice_details['unit_gross'];
        $single_item['subtotal_net'] = $invoice_details['unit_net'];
        $single_item['subtotal_tax'] = $invoice_details['unit_tax'];
        $single_item['subtotal_gross'] = $invoice_details['unit_gross'];
    }

    /* Purchase Credit Notes */

    // Purchase Credit Note (Supplier) - (supplier:details)
    elseif(\CMSApplication::$VAR['supplier_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('supplier', 'details'))
    {
        // Build Creditnote record
        $record['supplier_id'] = \CMSApplication::$VAR['supplier_id'];
        $record['type'] = 'purchase';
        $record['reference'] = _gettext("Supplier").': '.\CMSApplication::$VAR['supplier_id'] ;
        $record['sales_tax_rate'] = 0.00;
        $record['action_type'] = 'standalone';

        // Build Single Item
        $single_item['description'] = $record['reference'];
        $single_item['unit_qty'] = '1.00';
        $single_item['unit_net'] = '0.00';
        $single_item['unit_discount'] = '0.00';
        $single_item['sales_tax_exempt'] = 0;
        $single_item['vat_tax_code'] = 'T9';
        $single_item['unit_tax_rate'] = '0.00';
        $single_item['unit_tax'] = '0.00';
        $single_item['unit_gross'] = '0.00';
        $single_item['subtotal_net'] = '0.00';
        $single_item['subtotal_tax'] = '0.00';
        $single_item['subtotal_gross'] = '0.00';
    }

    // Purchase Credit Note (Expense) - (expense:details)
    elseif(\CMSApplication::$VAR['expense_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details'))
    {
        $expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);

        // Build Creditnote record
        $record['supplier_id'] = $expense_details['supplier_id'];
        $record['expense_id'] = \CMSApplication::$VAR['expense_id'];
        $record['type'] = 'purchase';
        $record['reference'] = $expense_details['balance']
            ? _gettext("Close").' '._gettext("Expense").': '.\CMSApplication::$VAR['expense_id']
            : _gettext("Refund").' '._gettext("Expense").': '.\CMSApplication::$VAR['expense_id'];
        $record['sales_tax_rate'] = 0.00;
        $record['action_type'] = (float) $expense_details['balance'] ? 'close' : 'refund';

        // Build Single Item
        $single_item['description'] = $record['reference'];
        $single_item['unit_qty'] = '1.00';
        $single_item['unit_net'] = $expense_details['unit_net'];
        $single_item['unit_discount'] = $expense_details['unit_discount'];
        $single_item['sales_tax_exempt'] = 0;
        $single_item['vat_tax_code'] = $this->app->components->company->getDefaultVatTaxCode($expense_details['tax_system']);
        $single_item['unit_tax_rate'] = preg_match('/^vat_/', QW_TAX_SYSTEM)
                                        ? $this->app->components->company->getVatRate($single_item['vat_tax_code'])
                                        : (QW_TAX_SYSTEM === 'sales_tax_cash'
                                            ? $this->app->components->company->getRecord('sales_tax_rate')
                                            : '0.00');
        $single_item['unit_tax'] = $expense_details['unit_tax'];
        $single_item['unit_gross'] = $expense_details['unit_gross'];
        $single_item['subtotal_net'] = $expense_details['unit_net'];
        $single_item['subtotal_tax'] = $expense_details['unit_tax'];
        $single_item['subtotal_gross'] = $expense_details['unit_gross'];
    }

    // Compensate record for multiple entry points
    $record['client_id'] ??= null;
    $record['invoice_id'] ??= null;
    $record['supplier_id'] ??= null;
    $record['expense_id'] ??= null;

    // Create credit note
    $creditnote_id = $this->app->components->creditnote->insertRecord($record);

    // Insert single item (nested in an array for correct looping)
    $this->app->components->creditnote->insertItems($creditnote_id, [$single_item]);

    // Recalculate creditote record totals to ensure they are correect
    $this->app->components->creditnote->recalculateTotals($creditnote_id);

    // Ensure creditnote status is draft
    $this->app->components->creditnote->updateStatus($creditnote_id, 'draft', true);

    // Edit the newly created credit note populating with items on page load
    $this->app->system->page->forcePage('creditnote', 'edit&creditnote_id='.$creditnote_id);

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
