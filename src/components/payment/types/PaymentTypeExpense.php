<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeExpense extends PaymentType
{
    private $expense_details = array();
    private $creditnote_details = array();
    private $closedByCreditnotePaymentId = null;

    public function __construct()
    {
        parent::__construct();

        // Get expense details
        $this->expense_details = $this->app->components->expense->getRecord(Payment::$payment_details['expense_id'] ?? $this->VAR['qpayment']['expense_id']);

        // Disable Unwanted Payment Methods
        Payment::$disabledMethods[] = 'voucher';

        // Set intial record balance
        Payment::$record_balance = (float) $this->expense_details['balance'];

        // Assign Payment Type specific template variables
        $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('send', true, Payment::$disabledMethods));
        $this->app->smarty->assign('expense_details', $this->expense_details);
        $this->app->smarty->assign('expense_statuses', $this->app->components->expense->getStatuses());
    }

    // Pre-Processing (Prep/validate the submission)
    public function preProcess()
    {
        parent::preProcess();

        // If this was a partially paid expense, and closed by a credit note (Type1), then return the `payment_id`
        $this->closedByCreditnotePaymentId = json_decode($this->expense_details['additional_info'], true)['closed_by_creditnote_payment_id'] ?? null;

        // Load credit note details (if required)
        if(Payment::$method == 'creditnote'){
            $creditnote_details = $this->app->components->creditnote->getRecord(Payment::$payment_details['creditnote_id'] ?? $this->VAR['qpayment']['creditnote_id']);
        }

        // New
        if(Payment::$action === 'new')
        {
            // Inject missing information into the submission [qpayment]
            $this->VAR['qpayment']['direction'] = 'debit';
            $this->VAR['qpayment']['supplier_id'] = $this->expense_details['supplier_id'];

            // Credit Note Method
            if(Payment::$method == 'creditnote'){

                // Type 1 CR Payment - A partially paid expense generated a credit note to close itself using this creditnote payment (expense:details)
                // There should only ever be one CR created from the expense that is applied to the same expense, resulting in a zero balance (i.e. Type 1 CR)
                if((float) $this->expense_details['balance'] && $this->VAR['qpayment']['expense_id'] == $creditnote_details['expense_id']){  // The payment record does not exist yet and is why you cannot use it to compare.

                    // The Payment must be the exact amount to close the expense
                    // A Type 1 CR payment will always close the expense balance in one payment
                    if($this->VAR['qpayment']['amount'] != Payment::$record_balance){
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("This expense requires the credit note payment to be equal to the remaining balance on the expense so it can be closed which is ").CURRENCY_SYMBOL.number_format($this->expense_details['balance'], 2, '.'));
                        Payment::$payment_valid = false;
                    }

                // Type 2 CR Payments - From CR generated from other expenses owned by the supplier (expense:details), supplier Standalone CR method (supplier:details)
                } else {
                    // expense and credit note need the same supplier
                    if($this->expense_details['supplier_id'] != $creditnote_details['supplier_id']){
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot apply this credit note against this expense because they are not both owned by the same supplier."));
                        Payment::$payment_valid = false;
                    }
                }

            // All Other Methods (i.e. not Credit Notes)
            } else {

                // If this expense was closed by a credit note (Type 1)
                if($this->closedByCreditnotePaymentId){
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add a new payment to this expense because it has been closed by a credit note."));
                    Payment::$payment_valid = false;
                }

                // // Does this expense have any credit notes generated against it
                if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, $this->VAR['qpayment']['expense_id'])){
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot add a new payment to this expense because it has one or more credit notes generated against it."));
                    Payment::$payment_valid = false;
                }
            }
        }

        // Edit
        elseif(Payment::$action === 'edit')
        {
            // Credit Note Method
            if(Payment::$method == 'creditnote'){

                // Type 1 CR Payment - A partially paid expense generated a credit note to close itself using this payment (expense:details)
                // There should only ever be one CR created from the expense that is applied to the same expense, resulting in a zero balance (i.e. Type 1 CR)
                if(this->closedByCreditnotePaymentId == Payment::$payment_details['payment_id']){

                    // Prevent editing the CR (Type 1) that closed this expense. You can only delete this CR payment.
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit a credit note payment that was used to close an expense. You can only cancel or delete this type of payment."));
                    Payment::$payment_valid = false;

                // Type 2 CR Payments - From CR generated from other expenses owned by the supplier (expense:details), supplier Standalone CR method (supplier:details)
                } else {

                    // Do Nothing

                }

            // All Other Methods (i.e. not Credit Notes)
            } else {

                // Does this expense have any credit notes generated against it
                if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, Payment::$payment_details['expense_id'])){
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this payment because the expense has one or more credit notes generated against it."));
                    Payment::$payment_valid = false;
                }

            }
        }

        // Cancel
        elseif(Payment::$action === 'cancel')
        {
            // Credit Note Method
            if(Payment::$method == 'creditnote'){

                // Type 1 CR Payment - A partially paid expense generated a credit note to close itself using this payment (expense:details)
                // There should only ever be one CR created from the expense that is applied to the same expense, resulting in a zero balance (i.e. Type 1 CR)
                if($this->closedByCreditnotePaymentId == Payment::$payment_details['payment_id']
                ){

                    // You can only cancel the CR payment (Type 1) if there are no other credit notes attached to this expense (eg for refunds or store credit)
                    if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, Payment::$payment_details['expense_id']) > 1){
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot cancel this credit note payment that was used to close this expense because there are more credit notes have been generated against this expense for the purpose of refunding or store credit."));
                        Payment::$payment_valid = false;
                    }

                    // Prevent cancelling the CR (Type 1) that closed this expense. You can only delete this CR payment.
                    //$this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot cancel a credit note payment that was used to close an expense. You can only delete this type of payment."));
                    //Payment::$payment_valid = false;


                // Type 2 CR Payments - From CR generated from other expenses owned by the supplier (expense:details), supplier Standalone CR method (supplier:details)
                } else {

                    // Do Nothing

                }


            // All Other Methods (i.e. not Credit Notes)
            } else {

                // Does this expense have any credit notes generated against it
                if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, Payment::$payment_details['expense_id'])){
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot cancel this payment because the expense has one or more credit notes generated against it."));
                    Payment::$payment_valid = false;
                }
            }
        }

        // Delete
        elseif(Payment::$action === 'delete')
        {
            // Credit Note Method
            if(Payment::$method == 'creditnote'){

                // Type 1 CR Payment - A partially paid expense generated a credit note to close itself using this payment (expense:details)
                // There should only ever be one CR created from the expense that is applied to the same expense, resulting in a zero balance (i.e. Type 1 CR)
                if($this->closedByCreditnotePaymentId == Payment::$payment_details['payment_id']){

                    // You can only delete the CR payment (Type 1) if there are no other credit notes attached to this expense (eg for refunds or store credit).
                    if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, Payment::$payment_details['expense_id']) > 1){
                        $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot delete this credit note payment that was used to close this expense because one or more credit notes have been generated against this expense for the purpose of refunding or store credit."));
                        Payment::$payment_valid = false;
                    }

                // Type 2 CR Payments - From CR generated from other expenses owned by the supplier (expense:details), supplier Standalone CR method (supplier:details)
                } else {

                    // Do Nothing

                }

            // All Other Methods (i.e. not Credit Notes)
            } else {
                // Does this expense have any credit notes generated against it
                if($this->app->components->report->creditnoteCount(null, null, null, null, null, null, null, null, null, null, null, Payment::$payment_details['expense_id'])){
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot delete this payment because the expense has one or more credit notes generated against it."));
                    Payment::$payment_valid = false;
                }

            }
        }

        return;

    }

    // Processing (Process the payment)
    public function process()
    {
        parent::process();

        // Different actions depending on success
        if(Payment::$payment_successful)
        {
            // New
            if(Payment::$action === 'new')
            {
                // Is this is a Type 1 credit note payment (closed a partially open expense), then tag it in the expense record
                if(Payment::$method == 'creditnote' && (float) $this->expense_details['balance'] && $this->VAR['qpayment']['expense_id'] == $this->creditnote_details['expense_id']){
                    $this->app->components->invoice->updateAdditionalInfo($this->expense_details['expense_id'], array('closed_by_creditnote_payment_id' => Payment::$payment_details['payment_id']));
                    $this->closedByCreditnotePaymentId = Payment::$payment_details['payment_id'];
                }
            }

            // Edit
            elseif(Payment::$action === 'edit')
            {
                // Do nothing
            }

            // Cancel
            elseif(Payment::$action === 'cancel')
            {
                 // Is this is a Type 1 credit note payment (closed a partially open expense), then remove the tag
                if($this->closedByCreditnotePaymentId == Payment::$payment_details['payment_id']){
                    $this->app->components->invoice->updateAdditionalInfo($this->expense_details['expense_id'], array('closed_by_creditnote_payment_id' => null));
                    $this->closedByCreditnotePaymentId = null;
                }
            }

            // Delete
            elseif(Payment::$action === 'delete')
            {
                // Do nothing
            }

            // Recalculate record totals
            $this->app->components->expense->recalculateTotals(Payment::$payment_details['expense_id'] ?? $this->VAR['qpayment']['expense_id']);

            // Refresh the record data
            $this->expense_details = $this->app->components->expense->getRecord(Payment::$payment_details['expense_id'] ?? $this->VAR['qpayment']['expense_id']);
            Payment::$record_balance = (float) $this->expense_details['balance'];
            $this->app->smarty->assign('expense_details', $this->expense_details);

        } else {

            // New
            if(Payment::$action === 'new')
            {
                // Do nothing
            }

            // Edit
            elseif(Payment::$action === 'edit')
            {
                // Do nothing
            }

            // Cancel
            elseif(Payment::$action === 'cancel')
            {
                // Do nothing
            }

            // Delete
            elseif(Payment::$action === 'delete')
            {
                // Do nothing
            }

        }

        return;

    }

    // Post-Processing (Final things like set messages and redirects)
    public function postProcess()
    {
        parent::postProcess();

        // Different actions depending on success
        if(Payment::$payment_successful)
        {
            // If the balance has been cleared
            if(Payment::$record_balance == 0)
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("The balance has been cleared."));
                $this->app->system->page->forcePage('expense', 'details&expense_id='.Payment::$payment_details['expense_id']);
            }

            // New
            if(Payment::$action === 'new')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment added successfully and Expense").' '.Payment::$payment_details['expense_id'].' '._gettext("has been updated to reflect this change."));
                // No forcepage, this will reload the new payment page
            }

            // Edit
            elseif(Payment::$action === 'edit')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Expense").' '.Payment::$payment_details['expense_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('payment', 'details&payment_id='.Payment::$payment_details['payment_id']);
            }

            // Cancel
            elseif(Payment::$action === 'cancel')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Expense").' '.Payment::$payment_details['expense_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('expense', 'details&expense_id='.Payment::$payment_details['expense_id']);
            }

            // Delete
            elseif(Payment::$action === 'delete')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Expense").' '.Payment::$payment_details['expense_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('expense', 'details&expense_id='.Payment::$payment_details['expense_id']);
            }

        }
        else
        {
            // The same page will be reloaded unless specified here, error messages is handled by methof

            // New
            if(Payment::$action === 'new')
            {
                // Do nothing
            }

            // Edit
            elseif(Payment::$action === 'edit')
            {
                // Do nothing
            }

            // Cancel
            elseif(Payment::$action === 'cancel')            {

                $this->app->system->page->forcePage('expense', 'status&expense_id='.Payment::$payment_details['expense_id']);
            }

            // Delete
            elseif(Payment::$action === 'delete')
            {
                $this->app->system->page->forcePage('expense', 'status&expense_id='.Payment::$payment_details['expense_id']);
            }
        }

        return;
    }

    // General payment checks
    private function checkPaymentAllowed()
    {
        $state_flag = parent::checkPaymentAllowed();

        // Is on a different tax system
        if($this->expense_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot receive a payment because it is on a different tax system."));
            $this->app->system->page->forcePage('expense', 'details&expense_id='.$this->VAR['expense_id']);
            $state_flag = false;
        }

        return $state_flag;
    }

    // Build Buttons
    public function buildButtons() {

        parent::buildButtons();

        // Submit
        if((float) $this->expense_details['balance']) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }

        // Cancel
        if(!(float) $this->expense_details['balance']) {
            if($this->app->system->security->checkPageAccessedViaQwcrm('expense', 'edit') || $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=expense&page_tpl=details&expense_id='.$this->VAR['expense_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }
        }

        // Return To Record
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=expense&page_tpl=details&expense_id='.$this->VAR['expense_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }

        // Add New Record
        Payment::$buttons['addNewRecord']['allowed'] = true;
        Payment::$buttons['addNewRecord']['url'] = 'index.php?component=expense&page_tpl=new';
        Payment::$buttons['addNewRecord']['title'] = _gettext("Add New Expense Record");

    }
}
