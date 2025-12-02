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
    private $creditnote_exists = false;

    public function __construct()
    {
        parent::__construct();

        // Set class variables
        Payment::$payment_details['method'] = 'creditnote';

        // Does this credit exist
        if(!$this->creditnote_details = $this->app->components->creditnote->getRecord($this->VAR['qpayment']['creditnote_id']))
        {
            // If there is no credit note with this ID, we cannot proceed
            Payment::$payment_valid = false;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no Credit Note with that ID."));
            $this->creditnote_exists = false;
            return;
        }
        else
        {
            // Set CR exists flag
            $this->creditnote_exists = true;

            // Set Action and Direction of payment
            if($this->creditnote_details['type'] == 'sales')
            {
                // Override direction set by PaymentType because of the special case of creditnotes (i.e. reverse invoices)
                $this->VAR['qpayment']['direction'] = 'debit';

            }
            else
            {
                // Override direction set by PaymentType because of the special case of creditnotes (i.e. reverse invoices)
                $this->VAR['qpayment']['direction'] = 'credit';
            }
        }
    }

    // Pre-Processing
    public function preProcess()
    {
        // If there is no credit note, do not continue
        if(!$this->creditnote_exists) {return;}

        parent::preProcess();

        // New
        if(Payment::$action === 'new')
        {
            // Can this credit note be used for a payment method / Is it a valid payment for this record??
            if(!$this->app->components->creditnote->checkMethodAllowsSubmit($this->creditnote_details, Payment::$payment_details))
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used as a payment method against this record."));
                Payment::$payment_valid = false;
            }

            // Apply credit note against an Invoice
            if(Payment::$payment_details['type'] == 'invoice') {

                // Make sure this is a Sales Credit Note
                if($this->creditnote_details['type'] != 'sales')
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is not a sales credit note and cannot be applied against an invoice."));
                    Payment::$payment_valid = false;
                }
            }

            // Apply credit note against an Expense
            elseif(Payment::$payment_details['type'] == 'expense')
            {
                // Make sure this is a Purchase Credit Note
                if($this->creditnote_details['type'] != 'purchase')
                {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is not a purchase credit note and cannot be applied against an expense."));
                    Payment::$payment_valid = false;
                }
            }

            // Only invoice and expense can have CR applied against them - This should not be needed, but is just incase I missed something
            else
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an invoice or an expense."));
                Payment::$payment_valid = false;
            }

            // Does the credit note have enough balance to cover the payment amount submitted
            if($this->VAR['qpayment']['amount'] > $this->creditnote_details['balance'])
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note does not have a sufficient balance to cover the submitted payment amount."));
                Payment::$payment_valid = false;
            }
        }

        // Edit
        if(Payment::$action === 'edit')
        {
            // Does the credit note have enough balance to cover the payment amount submitted (after removing this payments initial amount)
            if($this->VAR['qpayment']['amount'] > ($this->creditnote_details['balance'] + Payment::$payment_details['amount']))
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note does not have a sufficient balance to cover the submitted payment amount."));
                Payment::$payment_valid = false;
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
                // Recalculate the Credit Note record totals
                $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);

                Payment::$payment_successful = true;
            }
        }

        if(Payment::$action === 'edit')
        {
            // Recalculate the Credit Note record totals
            $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);

            Payment::$payment_successful = true;
        }

        if(Payment::$action === 'cancel')
        {
            // Recalculate the Credit Note record totals
            $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);

            Payment::$payment_successful = true;
        }

        if(Payment::$action === 'delete')
        {
            // Recalculate the Credit Note record totals
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
        if($this->creditnote_exists)
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
