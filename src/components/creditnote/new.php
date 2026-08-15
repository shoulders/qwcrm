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

// Check CR can be created (this check is also done on the buttons but silently)
if($this->app->components->creditnote->checkRecordCanBeCreated(\CMSApplication::$VAR['client_id'] ?? null, \CMSApplication::$VAR['invoice_id'] ?? null, \CMSApplication::$VAR['supplier_id'] ?? null, \CMSApplication::$VAR['expense_id'] ?? null))
{
    // Holding arrays (not really needed)
    $record = array();
    $record_items = array();
    $standard_item = array();
    $voucher_item = null;

    /* Sales Credit Notes */

    // Sales Credit Note - (Client) (Standalone) (client:details)
    if(\CMSApplication::$VAR['client_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('client', 'details'))
    {
        // Build Creditnote record
        $record['client_id'] = \CMSApplication::$VAR['client_id'];
        $record['type'] = 'sales';
        $record['reference'] = _gettext("Client").': '.\CMSApplication::$VAR['client_id'];
        $record['sales_tax_rate'] = 0.00;
        $record['action_type'] = 'standalone';

        // Build Standard Item
        $standard_item['description'] = _gettext("Invoice Item(s)");
        $standard_item['unit_qty'] = '1.00';
        $standard_item['unit_net'] = '0.00';
        $standard_item['unit_discount'] = '0.00';
        $standard_item['sales_tax_exempt'] = 0;
        $standard_item['vat_tax_code'] = 'T9';
        $standard_item['unit_tax_rate'] = '0.00';
        $standard_item['unit_tax'] = '0.00';
        $standard_item['unit_gross'] = '0.00';
        $standard_item['subtotal_net'] = '0.00';
        $standard_item['subtotal_tax'] = '0.00';
        $standard_item['subtotal_gross'] = '0.00';
    }

    // Sales Credit Note - (Client) (Invoice) (invoice:details)
    elseif(\CMSApplication::$VAR['invoice_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details'))
    {
        $invoice_details = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id']);

        // Build Creditnote record
        $record['client_id'] = $invoice_details['client_id'];
        $record['invoice_id'] = \CMSApplication::$VAR['invoice_id'];
        $record['type'] = 'sales';
        $record['reference'] = (float) $invoice_details['balance']
            ? _gettext("Close").' '._gettext("Invoice").': '.\CMSApplication::$VAR['invoice_id']
            : _gettext("Refund").' '._gettext("Invoice").': '.\CMSApplication::$VAR['invoice_id'];
        $record['sales_tax_rate'] = $invoice_details['sales_tax_rate'];
        $record['action_type'] = (float) $invoice_details['balance'] ? 'close' : 'refund';

        // Build items for closing an invoice
        if($record['action_type'] == 'close') {

            // Get split subtotals (taken from invoice recalculateTotals() )
            $items_subtotals        = $this->app->components->invoice->getItemsSubtotals(\CMSApplication::$VAR['invoice_id'] );
            $voucher_subtotals      = $this->app->components->voucher->getInvoiceVouchersSubtotals(\CMSApplication::$VAR['invoice_id'] );

            // Build Standard Item
            $standard_item['description'] = _gettext("Invoice Item(s)");
            $standard_item['unit_qty'] = '1.00';
            $standard_item['unit_net'] = $items_subtotals['subtotal_net'];
            $standard_item['unit_discount'] = $items_subtotals['subtotal_discount'];
            $standard_item['sales_tax_exempt'] = 0;
            $standard_item['vat_tax_code'] = $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']);
            $standard_item['unit_tax_rate'] = preg_match('/^vat_/', QW_TAX_SYSTEM)
                                            ? $this->app->components->company->getVatRate($standard_item['vat_tax_code'])
                                            : (QW_TAX_SYSTEM === 'sales_tax_cash'
                                                ? $this->app->components->company->getRecord('sales_tax_rate')
                                                : '0.00');
            $standard_item['unit_tax'] = $items_subtotals['subtotal_tax'];
            $standard_item['unit_gross'] = $items_subtotals['subtotal_gross'];
            $standard_item['subtotal_net'] = $items_subtotals['subtotal_net'];
            $standard_item['subtotal_tax'] = $items_subtotals['subtotal_tax'];
            $standard_item['subtotal_gross'] = $items_subtotals['subtotal_gross'];

            // Build Voucher Item (if there are any vouchers)
            if($voucher_subtotals){
                $voucher_item['description'] = _gettext("Voucher(s)");
                $voucher_item['unit_qty'] = '1.00';
                $voucher_item['unit_net'] = $voucher_subtotals['subtotal_net'];
                $voucher_item['unit_discount'] = 0.00;
                $voucher_item['sales_tax_exempt'] = 1;
                $voucher_item['vat_tax_code'] = $this->app->components->voucher->getVatTaxCode('mpv', $invoice_details['tax_system']);
                $voucher_item['unit_tax_rate'] = 0.00;
                $voucher_item['unit_tax'] = $voucher_subtotals['subtotal_tax'];
                $voucher_item['unit_gross'] = $voucher_subtotals['subtotal_gross'];
                $voucher_item['subtotal_net'] = $voucher_subtotals['subtotal_net'];
                $voucher_item['subtotal_tax'] = $voucher_subtotals['subtotal_tax'];
                $voucher_item['subtotal_gross'] = $voucher_subtotals['subtotal_gross'];
            }

        // Build items for refunding real monies on a closed invoice (calculations from creditnote.php)
        // Tax is not accounted for, user will have edit before submission if required
        } else {

            // Calculate real monies paid on this invoice by the client (excludes credit notes and vouchers, this allows you to close an invoice with a `Close` CR and not give free money to a client)
            $moniesIn = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'invoice', 'real_monies', 'credit', null, null, null, \CMSApplication::$VAR['invoice_id']);

            // Get all payments against this invoice (real monies via credit notes)
            $moniesOut = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'invoice', null, 'debit', null, null, null, \CMSApplication::$VAR['invoice_id']);

            // Is there any real money left that can be refunded (there will be because this was tested in creditnote.php)
            $moniesThatCanBeRefunded = $moniesIn - $moniesOut;

            // Build Standard Item
            $standard_item['description'] = _gettext("Item(s)");
            $standard_item['unit_qty'] = '1.00';
            $standard_item['unit_net'] = $moniesThatCanBeRefunded;
            $standard_item['unit_discount'] = 0.00;
            $standard_item['sales_tax_exempt'] = 0;
            $standard_item['vat_tax_code'] = $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']);
            $standard_item['unit_tax_rate'] = preg_match('/^vat_/', QW_TAX_SYSTEM)
                                            ? $this->app->components->company->getVatRate($standard_item['vat_tax_code'])
                                            : (QW_TAX_SYSTEM === 'sales_tax_cash'
                                                ? $this->app->components->company->getRecord('sales_tax_rate')
                                                : '0.00');
            $standard_item['unit_tax'] = 0.00;
            $standard_item['unit_gross'] = $moniesThatCanBeRefunded;
            $standard_item['subtotal_net'] = $moniesThatCanBeRefunded;
            $standard_item['subtotal_tax'] = 0.00;
            $standard_item['subtotal_gross'] = $moniesThatCanBeRefunded;

        }
    }

    /* Purchase Credit Notes */

    // Purchase Credit Note - (Supplier) (Standalone) (supplier:details)
    elseif(\CMSApplication::$VAR['supplier_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('supplier', 'details'))
    {
        // Build Creditnote record
        $record['supplier_id'] = \CMSApplication::$VAR['supplier_id'];
        $record['type'] = 'purchase';
        $record['reference'] = _gettext("Supplier").': '.\CMSApplication::$VAR['supplier_id'] ;
        $record['sales_tax_rate'] = 0.00;
        $record['action_type'] = 'standalone';

        // Build Standard Item
        $standard_item['description'] = _gettext("Expense Item(s)");
        $standard_item['unit_qty'] = '1.00';
        $standard_item['unit_net'] = '0.00';
        $standard_item['unit_discount'] = '0.00';
        $standard_item['sales_tax_exempt'] = 0;
        $standard_item['vat_tax_code'] = 'T9';
        $standard_item['unit_tax_rate'] = '0.00';
        $standard_item['unit_tax'] = '0.00';
        $standard_item['unit_gross'] = '0.00';
        $standard_item['subtotal_net'] = '0.00';
        $standard_item['subtotal_tax'] = '0.00';
        $standard_item['subtotal_gross'] = '0.00';
    }

    // Purchase Credit Note - (Supplier) (Expense) (expense:details)
    elseif(\CMSApplication::$VAR['expense_id'] ?? false && $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details'))
    {
        $expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);

        // Build Creditnote record
        $record['supplier_id'] = $expense_details['supplier_id'];
        $record['expense_id'] = \CMSApplication::$VAR['expense_id'];
        $record['type'] = 'purchase';
        $record['reference'] = (float) $expense_details['balance']
            ? _gettext("Close").' '._gettext("Expense").': '.\CMSApplication::$VAR['expense_id']
            : _gettext("Refund").' '._gettext("Expense").': '.\CMSApplication::$VAR['expense_id'];
        $record['sales_tax_rate'] = 0.00;
        $record['action_type'] = (float) $expense_details['balance'] ? 'close' : 'refund';

        // Build items for closing an invoice
        if($record['action_type'] == 'close') {

            // Build Standard Item
            $standard_item['description'] = _gettext("Expense Item(s)");
            $standard_item['unit_qty'] = '1.00';
            $standard_item['unit_net'] = $expense_details['unit_net'];
            $standard_item['unit_discount'] = 0.00;
            $standard_item['sales_tax_exempt'] = 0;
            $standard_item['vat_tax_code'] = $this->app->components->company->getDefaultVatTaxCode($expense_details['tax_system']);
            $standard_item['unit_tax_rate'] = preg_match('/^vat_/', QW_TAX_SYSTEM)
                                            ? $this->app->components->company->getVatRate($standard_item['vat_tax_code'])
                                            : (QW_TAX_SYSTEM === 'sales_tax_cash'
                                                ? $this->app->components->company->getRecord('sales_tax_rate')
                                                : '0.00');
            $standard_item['unit_tax'] = $expense_details['unit_tax'];
            $standard_item['unit_gross'] = $expense_details['unit_gross'];
            $standard_item['subtotal_net'] = $expense_details['unit_net'];
            $standard_item['subtotal_tax'] = $expense_details['unit_tax'];
            $standard_item['subtotal_gross'] = $expense_details['unit_gross'];

        // Build items for refunding real monies on a closed expense (calculations from creditnote.php)
        // Tax is not accounted for, user will have edit before submission if required
        } else {

            // Calculate real monies paid on this expense by the us (excludes credit notes and vouchers, this allows you to close an expense with a `Close` CR and not receive any money from the supplier)
            $moniesIn = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'expense', 'real_monies', 'debit', null, null, null, null, \CMSApplication::$VAR['expense_id']);

            // Get all payments against this expense (real monies via credit notes)
            $moniesOut = $this->app->components->report->paymentSum(null, null, null, null, 'valid', 'expense', null, 'credit', null, null, null, null, \CMSApplication::$VAR['expense_id']);

            // Is there any real money is there left which can then be refunded (there will be because this was tested in creditnote.php)
            $moniesThatCanBeRefunded = $moniesIn - $moniesOut;

            // Build Standard Item
            $standard_item['description'] = _gettext("Item(s)");
            $standard_item['unit_qty'] = '1.00';
            $standard_item['unit_net'] = $moniesThatCanBeRefunded;
            $standard_item['unit_discount'] = 0.00;
            $standard_item['sales_tax_exempt'] = 0;
            $standard_item['vat_tax_code'] = $this->app->components->company->getDefaultVatTaxCode($expense_details['tax_system']);
            $standard_item['unit_tax_rate'] = preg_match('/^vat_/', QW_TAX_SYSTEM)
                                            ? $this->app->components->company->getVatRate($standard_item['vat_tax_code'])
                                            : (QW_TAX_SYSTEM === 'sales_tax_cash'
                                                ? $this->app->components->company->getRecord('sales_tax_rate')
                                                : '0.00');
            $standard_item['unit_gross'] = $moniesThatCanBeRefunded;
            $standard_item['subtotal_net'] = $moniesThatCanBeRefunded;
            $standard_item['subtotal_tax'] = 0.00;
            $standard_item['subtotal_gross'] = $moniesThatCanBeRefunded;

        }
    }

    // Compensate record for multiple entry points
    $record['client_id'] ??= null;
    $record['invoice_id'] ??= null;
    $record['supplier_id'] ??= null;
    $record['expense_id'] ??= null;

    // Create credit note
    $creditnote_id = $this->app->components->creditnote->insertRecord($record);

    // Build record items array (allow for vouchers not always being present)
    $record_items[] = $standard_item;
    if($voucher_item){$record_items[] = $voucher_item;}

    // Insert item(s) (standard and voucher) (nested in an array for correct looping)
    $this->app->components->creditnote->insertItems($creditnote_id, $record_items);

    // Recalculate creditnote record totals to ensure they are correct in the DB
    $this->app->components->creditnote->recalculateTotals($creditnote_id);

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
