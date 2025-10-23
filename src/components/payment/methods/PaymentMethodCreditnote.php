<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodCreditnote extends PaymentMethod
{
    private $creditnote_details = array();
    private $currency_symbol = '';
    private $credit_note_exists = false;

    public function __construct()
    {
        parent::__construct();

        // Set class variables
        Payment::$payment_details['method'] = 'credit_note';

        // Does this credit exist
        if(!$this->creditnote_details = $this->app->components->creditnote->getRecord($this->VAR['qpayment']['creditnote_id']))
        {
            // If there is no credit note with this ID, we cannot proceed
            Payment::$payment_valid = false;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no Credit Note with that ID."));
            $this->credit_note_exists = false;
            return;
        }
        else
        {
            // Set CR exists flag
            $this->credit_note_exists = true;

            // Set Action and Direction of payment
            if($this->creditnote_details['type'] == 'sales')
            {
                // Apply a Client's credit note
                $this->VAR['qpayment']['creditnote_action'] = 'sales_apply';

                // Override direction set by PaymentType because of the special case of creditnotes (i.e. reverse invoices)
                $this->VAR['qpayment']['direction'] = 'debit';

            }
            else
            {
                // Apply a Supplier's credit note
                $this->VAR['qpayment']['creditnote_action'] = 'purchase_apply';

                // Override direction set by PaymentType because of the special case of creditnotes (i.e. reverse invoices)
                $this->VAR['qpayment']['direction'] = 'credit';
            }

            // Set currency symbol
            $this->currency_symbol = $this->app->components->company->getRecord('currency_symbol');
        }
    }

    // Pre-Processing
    public function preProcess()
    {
        // If there is no credit note, do not continue
        if(!$this->credit_note_exists) {return;}

        parent::preProcess();

        // New
        if(Payment::$action === 'new')
        {
            // Apply credit note against an Invoice
            if(Payment::$payment_details['type'] == 'invoice')
            {
                // Make sure this is a Sales Credit Note
                if($this->creditnote_details['type'] != 'sales')
                {
                    Payment::$payment_valid = false;
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is not a sales credit note and cannot be applied against an invoice."));
                }

                // You can only apply a CR to an invoice belonging to the Client it was created from (standalone)
                if($this->creditnote_details['client_id'] != $this->app->components->payment->paymentType->invoice_details['client_id'])
                {
                    Payment::$payment_valid = false;
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an invoice belonging to the client it is linked with.").' '._gettext("Client").': '.$this->creditnote_details['client_id']);
                }

                // If this CR was created from an invoice, you can only apply this credit note against that client and invoice it was linked with
                if($this->creditnote_details['invoice_id'] && $this->creditnote_details['invoice_id'] != $this->VAR['qpayment']['invoice_id'])
                {
                    Payment::$payment_valid = false;
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against the invoice it is linked with.").' '._gettext("Invoice").': '.$this->creditnote_details['invoice_id']);
                }
            }

            // Apply credit note against an Expense
            elseif(Payment::$payment_details['type'] == 'expense')
            {
                // Make sure this is a Purchase Credit Note
                if($this->creditnote_details['type'] != 'purchase')
                {
                    Payment::$payment_valid = false;
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is not a purchase credit note and cannot be applied against an expense."));
                }

                // You can only apply a CR to an expense belonging to the Supplier it was created from (standalone)
                if($this->creditnote_details['supplier_id'] != $this->app->components->payment->paymentType->expense_details['supplier_id'])
                {
                    Payment::$payment_valid = false;
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an expense belonging to the supplier it is linked with.").' '._gettext("Supplier").': '.$this->creditnote_details['supplier_id']);
                }

                // If this CR was created from an expense, you can only apply this credit note against that expense and supplier it was linked with
                if($this->creditnote_details['expense_id'] && $this->creditnote_details['expense_id'] != $this->VAR['qpayment']['expense_id'])
                {
                    Payment::$payment_valid = false;
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against the expense it is linked with.").' '._gettext("Expense").': '.$this->creditnote_details['expense_id']);
                }
            }

            // Only invoice and expense can have CR applied against them - This should not be needed, but is just incase I missed something
            else
            {
                Payment::$payment_valid = false;
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an invoice or an expense."));
            }

            // Does the credit note have enough balance to cover the payment amount submitted
            if($this->VAR['qpayment']['amount'] > $this->creditnote_details['balance'])
            {
                Payment::$payment_valid = false;
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This Credit Note does not have a sufficient balance to cover the submitted payment amount."));
            }
        }

        // Edit
        if(Payment::$action === 'edit')
        {
            // Does the voucher have enough balance to cover the payment amount submitted (after removing this payments initial amount)
            if($this->VAR['qpayment']['amount'] > ($this->creditnote_details['balance'] + Payment::$payment_details['amount']))
            {
                Payment::$payment_valid = false;
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This Credit Note does not have a sufficient balance to cover the submitted payment amount."));
            }
        }

        // Cancel
        if(Payment::$action === 'cancel')
        {
            // Do nothing
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Do nothing
        }

        return;
    }

    // Processing
    public function process()
    {
        parent::process();

        if(Payment::$action === 'new')
        {
            // Build additional_info column
            $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->buildAdditionalInfoJson();

            // Insert the payment with the calculated information
            if(Payment::$payment_details['payment_id'] = $this->app->components->payment->insertRecord($this->VAR['qpayment']))
            {
                // Recalculate record totals
                $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);

                Payment::$payment_successful = true;
            }
        }

        if(Payment::$action === 'edit')
        {
            // Recalculate record totals
            $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);

            Payment::$payment_successful = true;
        }

        if(Payment::$action === 'cancel')
        {
            // Recalculate record totals
            $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);

            Payment::$payment_successful = true;
        }

        if(Payment::$action === 'delete')
        {
            // Recalculate record totals
        $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);
        }

        return;
    }

    // Post-Processing
    public function postProcess()
    {
        parent::postProcess();

        // Set success/failure message
        if(Payment::$payment_successful)
        {
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Credit Note applied successfully."));
        }
        else
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Credit Note was not applied successfully."));
        }

        // Refresh the Credit Note details
        if($this->credit_note_exists)
        {
            $this->creditnote_details = $this->app->components->creditnote->getRecord($this->VAR['qpayment']['creditnote_id']);

            // Balance remaining
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("The balance left on the credit note is").': '.$this->currency_symbol.$this->creditnote_details['balance']);
        }
        else
        {
            return;
        }

        // New
        if(Payment::$action === 'new')
        {
            // Do nothing
        }

        // Edit
        if(Payment::$action === 'edit')
        {
            // Do nothing
        }

        // Cancel
        if(Payment::$action === 'cancel')
        {
            // Do nothing
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Do nothing
        }

        return;
    }
}
