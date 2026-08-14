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

    public function __construct()
    {
        parent::__construct();
    }

    // Pre-Processing
    public function preProcess()
    {
        parent::preProcess();

        // Allow system messages
        $silent = false;

        // Is Expired (Live Check)
        if($this->app->components->creditnote->checkCreditnoteIsExpired(Payment::$payment_details['creditnote_id'] ?? $this->VAR['qpayment']['creditnote_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot perform payment actions with an expired credit note.", $silent));
            Payment::$payment_valid = false;
        }

        // Get Credit note details
        if((!$this->creditnote_details = $this->app->components->creditnote->getRecord(Payment::$payment_details['creditnote_id'] ?? $this->VAR['qpayment']['creditnote_id']))) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no credit note with that ID."));
            Payment::$payment_valid = false;
            return;
        }

        // Set Action and Direction of payment
        if($this->creditnote_details['type'] == 'sales') {
            // Override direction set by PaymentType because of the special case of creditnotes (i.e. reverse invoices)
            $this->VAR['qpayment']['direction'] = 'debit';
        } else {
            // Override direction set by PaymentType because of the special case of creditnotes (i.e. reverse invoices)
            $this->VAR['qpayment']['direction'] = 'credit';
        }

        // Is on a different tax system
        if($this->creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used because it is on a different Tax system."));
            Payment::$payment_valid = false;
        }

        // If there is a client, are they active
        if($this->creditnote_details['client_id'] && !$this->app->components->client->getRecord($this->creditnote_details['client_id'], 'active')) {
            //$this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this client because they are not active."));
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Payment actions cannot be performed against this client because they are not active."));
            Payment::$payment_valid = false;
        }

        // If there is a supplier, are they active
        if($this->creditnote_details['supplier_id'] && $this->app->components->supplier->getRecord($this->creditnote_details['supplier_id'], 'status') != 'activated') {
            //$this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this supplier because they are not active."));
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Payment actions cannot be performed against this supplier because they are not active."));
            Payment::$payment_valid = false;
        }

        // New
        if(Payment::$action === 'new')
        {
            // Does the creditnote have enough balance to cover the payment amount submitted
            if($this->VAR['qpayment']['amount'] > $this->creditnote_details['balance']) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note does not have a sufficient balance to cover the submitted payment amount."));
                Payment::$payment_valid = false;
            }

            // Ensure credit note is only allowed to be applied against the correct record type
            switch (Payment::$type) {
                /* Cannot be used to pay on another credit note - This should not be an issue here, but this is just incase
                case 'creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be applied against another credit note. You should not be seeing this message, report to admins."));
                    Payment::$payment_valid = false;*/
                case 'invoice':
                    // Only a sales credit note can be applied against an invoice
                    if($this->creditnote_details['type'] != 'sales') {
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is not a sales credit note and cannot be applied against an invoice."));
                        Payment::$payment_valid = false;
                    }
                    break;
                case 'expense':
                    // Only a purchase credit note can be applied against an expense
                    if($this->creditnote_details['type'] != 'purchase'){
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is not a purchase credit note and cannot be applied against an expense."));
                        Payment::$payment_valid = false;
                    }
                    break;

                // Only invoices and expenses can have CR applied against them - This should not be needed, but is just incase I missed something
                default:
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an invoice or an expense."));
                    Payment::$payment_valid = false;
                    break;
            }

            // Cannot be used to pay on itself - This should not be an issue here because you cannot use a CR as a payment method on a CR record, but this is just incase I change my mind later
            if($this->creditnote_details['creditnote_id'] ==  $this->VAR['qpayment']['creditnote_id'] ?? null) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be applied against itself. You should not be seeing this message, report to admins."));
                Payment::$payment_valid = false;
            }

            // Check Credit Note status
            switch ($this->creditnote_details['status']) {
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used as a payment because it is a draft."));
                    Payment::$payment_valid = false;
                    break;
                case 'unused':
                    break;
                case 'partially_used':
                    break;
                case 'used':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used as a payment because it has been used and has no available balance."));
                    Payment::$payment_valid = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used as a payment because it has been voided."));
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be as a payment used because it has been deleted."));
                    Payment::$payment_valid = false;
                    break;
            }

            /** Sales Credit Notes **/

            // You can only use a CR as a payment method against an invoice that belongs to a client

            if($this->creditnote_details['client_id']) {

                /* Common Tests */

                // Is the target client and the parent client that the credit note was issued against, the same?
                if($this->creditnote_details['client_id'] != $this->VAR['qpayment']['client_id']) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is not allowed. The credit note can only be used as a payment method against invoices issued to the same client as the credit note was."));
                    Payment::$payment_valid = false;
                }

                // CR can only be applied against the credit note's specified client's invoices
                if($this->creditnote_details['client_id'] != $this->app->components->invoice->getRecord($this->VAR['qpayment']['invoice_id'], 'client_id')){
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used against this invoice. It can only be used against invoices belonging to the client this credit note was issued to."));
                    //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an invoice belonging to the client it is linked with.").' '._gettext("Client").': '.$this->creditnote_details['client_id']);
                    Payment::$payment_valid = false;
                }

                /* Sales Credit Note - (Client) (Standalone) (client:details) */

                // Used to reduce the amount a client owes on one of their invoices, by using the balance from a standalone sales credit note as a payment method.

                if(!$this->creditnote_details['invoice_id']) {

                    $target_invoice_details = $this->VAR['qpayment']['invoice_id'];

                    // Is the Target Invoice on a different tax system (the Target Invoice will have been previously checked only if this is a `close` or `refund` CR type as part of checkRecordCanBeCreated() )
                    if($target_invoice_details['tax_system'] != QW_TAX_SYSTEM) {
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this invoice because it is on a different Tax system."));
                        Payment::$payment_valid = false;
                    }

                    // Does the Target Invoice belong to the client
                    if($target_invoice_details['client_id'] != $this->creditnote_details['client_id']) {
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this invoice because it is not owned by the same client."));
                        Payment::$payment_valid = false;
                    }

                }

                /* Sales Credit Note - (Client) (Invoice) (invoice:details) */

                // Used to reduce the amount a client owes on on of their invoices, by using the balance from the sales credit note raised against that invoice as a payment method.

                elseif($this->creditnote_details['invoice_id']) {

                    // Is the parent credit note a `close` action type (Used to clear invoice balances without receiving monies)
                    if($this->creditnote_details['type'] == 'close') {

                        // The target invoice must be the invoice the parent CR was raised against
                        // A CR raised against an invoice with a balance, is issued to close that invoice only, so it can only be used to close that invoice.
                        if($this->creditnote_details['invoice_id'] != $this->VAR['qpayment']['invoice_id']) {
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used against this invoice. It must be used to close the invoice it was raised against."));
                            //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against the invoice it is linked with.").' '._gettext("Invoice").': '.$this->creditnote_details['invoice_id']);
                            Payment::$payment_valid = false;
                        }

                    }

                }

            /** Purchase Credit Notes  **/

            // You can only use a CR as a payment method against an expense that belongs to a supplier

            } elseif($this->creditnote_details['supplier_id']) {

                /* Common Tests */

                // Is the target supplier and the parent supplier that the credit note was issued aginst, the same?
                if($this->creditnote_details['supplier_id'] != $this->VAR['qpayment']['supplier_id']) {
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is not allowed. The credit note can only be used as a payment method against expenses issued to the same supplier as the credit note was."));
                    Payment::$payment_valid = false;
                }

                // CR can only be applied against the credite note's specified suppliers's expenses
                if($this->creditnote_details['supplier_id'] != $this->app->components->expense->getRecord($this->VAR['qpayment']['expense_id'], 'supplier_id')){
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used against this expense. It can only be used against expense belonging to the supplier this credit note was issued to."));
                    //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against an expense belonging to the supplier it is linked with.").' '._gettext("Supplier").': '.$this->creditnote_details['supplier_id']);
                    Payment::$payment_valid = false;
                }

                /* Purchase Credit Note - (Supplier) (Standalone) (supplier:details) */

                // Used to reduce the amount I owe a supplier on one of their expenses, by using the balance from a standalone purchase credit note as a payment method.

                if(!$this->creditnote_details['expense_id']) {

                    $target_expense_details = $this->VAR['qpayment']['expense_id'];

                    // Is the Target Expense on a different tax system (the Target Expense will have been previously checked only if this is a `close` or `refund` CR type as part of checkRecordCanBeCreated() )
                    if($target_expense_details['tax_system'] != QW_TAX_SYSTEM) {
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this expense because it is on a different Tax system."));
                        Payment::$payment_valid = false;
                    }

                    // Does the Target Expense belong to the supplier
                    if($target_expense_details['supplier_id'] != $this->creditnote_details['supplier_id']) {
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The credit note cannot be used against this expense because it is not owned by the same supplier."));
                        Payment::$payment_valid = false;
                    }

                /* Purchase Credit Note - (Supplier) (Expense) (expense:details) */

                // Used to reduce the amount I owe a supplier on one of their expenses, by using the balance from the purchase credit note raised against that expense as a payment method.

                } elseif($this->creditnote_details['expense_id']) {

                    // Is the parent credit note a `close` action type (Used to clear expense balances without sending monies)
                    if($this->creditnote_details['type'] == 'close') {

                        // The target expense must be the expense the parent credit note was raised against
                        // A credit note raised against an expense with a balance, is issued to close that expense only, so it can only be used to close that expense.
                        if($this->creditnote_details['expense_id'] != $this->VAR['qpayment']['expense_id']) {
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note cannot be used against this expense. It must be used to close the expense it was raised against."));
                            //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You can only apply this credit note against the expense it is linked with.").' '._gettext("Expense").': '.$this->creditnote_details['expense_id']);
                            Payment::$payment_valid = false;
                        }

                    }
                }

            }

        }

        // Edit
        if(Payment::$action === 'edit')
        {
            // Does the credit note have enough balance to cover the payment amount submitted (after removing this payments initial amount) TODO: should i move into checkMethodAllowsSubmit() becasue this is monies and calculation
            if($this->VAR['qpayment']['amount'] > ($this->creditnote_details['balance'] + Payment::$payment_details['amount'])){
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note does not have a sufficient balance to cover the submitted payment amount."));
                Payment::$payment_valid = false;
            }

            // Check Credit Note status
            switch ($this->creditnote_details['status']) {
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note is a draft and there is no payment to edit. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'unused':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note is unused and there is no payment to edit. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'partially_used':
                    break;
                case 'used':
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because the credit note has been voided."));
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because the credit note has been deleted."));
                    Payment::$payment_valid = false;
                    break;
            }

            /** Sales Credit Notes **/

            if($this->creditnote_details['client_id']) {

                /* Common Tests */

                // Do nothing

                /* Sales Credit Note - (Client) (Standalone) (client:details) */

                // You can only use a CR as a payment method against an invoice or expense, so this is not a valid option.

                if(!$this->creditnote_details['invoice_id']) {

                    // Do nothing

                }

                /* Sales Credit Note - (Client) (Invoice) (invoice:details) */

                // Used to reduce the amount a client owes on an invoice, by using the balance from the sales credit note as a payment method against an invoice.

                elseif($this->creditnote_details['invoice_id']) {

                    // Is the parent credit note a `close` action type (Used to clear invoice balances without receiving monies)
                    if($this->creditnote_details['type'] == 'close') {

                        // You cannot edit a credit note payment closing an invoice
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note was used to close an invoice. It cannot be edited."));
                        Payment::$payment_valid = false;

                    }

                }

            /** Purchase Credit Notes  **/

            } elseif($this->creditnote_details['supplier_id']) {

                /* Common Tests */

                // Do nothing

                /* Purchase Credit Note - (Supplier) (Standalone) (supplier:details) */

                // You can only use a CR as a payment method against an invoice or expense, so this is not a valid option.

                if(!$this->creditnote_details['expense_id']) {

                    // Do nothing

                /* Purchase Credit Note - (Supplier) (Expense) (expense:details) */

                // Used to reduce the amount I owe a supplier on an expense, by using the balance from the purchase credit note as a payment method against an expense.

                } elseif($this->creditnote_details['expense_id']) {

                    // Is the parent credit note a `close` action type (Used to clear expense balances without sending monies)
                    if($this->creditnote_details['type'] == 'close') {

                        // You cannot edit a credit note payment closing an expense
                        if($this->creditnote_details['expense_id'] != $this->VAR['qpayment']['expense_id']) {
                            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note was used to close an expense. It cannot be edited."));
                        Payment::$payment_valid = false;
                        }

                    }

                }
            }
        }

        // Void
        if(Payment::$action === 'void')
        {
            // Check Credit Note status
            switch ($this->creditnote_details['status']) {
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note is a draft and there is no payment to void. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'unused':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note is unused and there is no payment to void. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'partially_used':
                    break;
                case 'used':
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because the credit note has been voided."));
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because the credit note has been deleted."));
                    Payment::$payment_valid = false;
                    break;
            }

            /** Sales Credit Notes **/

            if($this->creditnote_details['client_id']) {

                /* Common Tests */

                // Do nothing

                /* Sales Credit Note - (Client) (Standalone) (client:details) */

                // You can only use a CR as a payment method against an invoice or expense, so this is not a valid option.

                if(!$this->creditnote_details['invoice_id']) {

                    // Do nothing

                }

                /* Sales Credit Note - (Client) (Invoice) (invoice:details) */

                // Used to reduce the amount a client owes on an invoice, by using the balance from the sales credit note as a payment method against an invoice.

                elseif($this->creditnote_details['invoice_id']) {

                    // Do nothing

                }

            /** Purchase Credit Notes  **/

            } elseif($this->creditnote_details['supplier_id']) {

                /* Common Tests */

                // Do nothing

                /* Purchase Credit Note - (Supplier) (Standalone) (supplier:details) */

                // You can only use a CR as a payment method against an invoice or expense, so this is not a valid option.

                if(!$this->creditnote_details['expense_id']) {

                    // Do nothing

                /* Purchase Credit Note - (Supplier) (Expense) (expense:details) */

                // Used to reduce the amount I owe a supplier on an expense, by using the balance from the purchase credit note as a payment method against an expense.

                } elseif($this->creditnote_details['expense_id']) {

                    // Do nothing

                }
            }

        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Check Credit Note status
            switch ($this->creditnote_details['status']) {
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note is a draft and there is no payment to delete. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'unused':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This credit note is unused and there is no payment to delete. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'partially_used':
                    break;
                case 'used':
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because the credit note has been voided."));
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because the credit note has been deleted."));
                    Payment::$payment_valid = false;
                    break;
            }

            /** Sales Credit Notes **/

            if($this->creditnote_details['client_id']) {

                /* Common Tests */

                // Do nothing

                /* Sales Credit Note - (Client) (Standalone) (client:details) */

                // You can only use a CR as a payment method against an invoice or expense, so this is not a valid option.

                if(!$this->creditnote_details['invoice_id']) {

                    // Do nothing

                }

                /* Sales Credit Note - (Client) (Invoice) (invoice:details) */

                // Used to reduce the amount a client owes on an invoice, by using the balance from the sales credit note as a payment method against an invoice.

                elseif($this->creditnote_details['invoice_id']) {

                    // Do nothing

                }

            /** Purchase Credit Notes  **/

            } elseif($this->creditnote_details['supplier_id']) {

                /* Common Tests */

                // Do nothing

                /* Purchase Credit Note - (Supplier) (Standalone) (supplier:details) */

                // You can only use a CR as a payment method against an invoice or expense, so this is not a valid option.

                if(!$this->creditnote_details['expense_id']) {

                    // Do nothing

                /* Purchase Credit Note - (Supplier) (Expense) (expense:details) */

                // Used to reduce the amount I owe a supplier on an expense, by using the balance from the purchase credit note as a payment method against an expense.

                } elseif($this->creditnote_details['expense_id']) {

                    // Do nothing

                }
            }

        }

        return;
    }

    // Processing
    public function process()
    {
        parent::process();

        if(Payment::$action === 'new')
        {
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
            $this->app->components->creditnote->recalculateTotals(Payment::$payment_details['creditnote_id']);

            Payment::$payment_successful = true;
        }

        if(Payment::$action === 'void')
        {
            // Recalculate the Credit Note record totals
            $this->app->components->creditnote->recalculateTotals(Payment::$payment_details['creditnote_id']);

            Payment::$payment_successful = true;
        }

        if(Payment::$action === 'delete')
        {
            // Recalculate the Credit Note record totals
            $this->app->components->creditnote->recalculateTotals(Payment::$payment_details['creditnote_id']);
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
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Credit note applied successfully."));
        }
        else
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Credit note was not applied successfully."));
        }

        // Refresh the Credit Note details  TODO: there used to be a test if the details existed, is this needed?
        $this->creditnote_details = $this->app->components->creditnote->getRecord($this->creditnote_details['creditnote_id']);
        $this->app->system->variables->systemMessagesWrite('warning', _gettext("The balance left on the credit note is").': '.CURRENCY_SYMBOL.$this->creditnote_details['balance']);

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

        // Void
        if(Payment::$action === 'void')
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
