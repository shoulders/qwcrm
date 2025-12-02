<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeCreditnote extends PaymentType
{
    public $creditnote_details = array();

    public function __construct()
    {
        parent::__construct();

        // Set class variables
        Payment::$payment_details['type'] = 'creditnote';
        $this->creditnote_details = $this->app->components->creditnote->getRecord(Payment::$payment_details['invoice_id'] ?? $this->VAR['qpayment']['creditnote_id']);

        // Set Payment direction
        if($this->creditnote_details['type'] == 'sales')
        {
            // Sending a Payment
            $this->VAR['qpayment']['direction'] = 'debit';
        }
        else
        {
            // Receiving a Payment
            $this->VAR['qpayment']['direction'] = 'credit';
        }

        // Disable Unwanted Payment Methods
        Payment::$disabledMethods[] = 'creditnote';
        Payment::$disabledMethods[] = 'voucher';

        // For logging and insertRecord()
        Payment::$payment_details['client_id'] = \CMSApplication::$VAR['qpayment']['client_id'] = $this->creditnote_details['client_id'];
        Payment::$payment_details['invoice_id'] = \CMSApplication::$VAR['qpayment']['invoice_id'] = null;

        // Set initial record balance
        Payment::$record_balance = (float) $this->creditnote_details['balance'];

        // Assign Payment Type specific template variables
        if($this->creditnote_details['type'] == 'sales')
        {
            // show payment methods to send money (debit)
            $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('send', true, Payment::$disabledMethods));

            $this->app->smarty->assign('client_details', $this->app->components->client->getRecord($this->creditnote_details['client_id']));
        }
        // type == purchase
        else
        {
            // show payment methods to receive money (credit)
            $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('receive', true, Payment::$disabledMethods));

            $this->app->smarty->assign('supplier_details', $this->app->components->supplier->getRecord($this->creditnote_details['supplier_id']));
        }
        $this->app->smarty->assign('creditnote_details', $this->creditnote_details);
        $this->app->smarty->assign('creditnote_statuses', $this->app->components->creditnote->getStatuses());
    }

    // Pre-Processing - Prep/validate the data
    public function preProcess()
    {
        parent::preProcess();

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

    // Processing - Process the payment
    public function process()
    {
        parent::process();

        // Recalculate record totals
        $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);

        // Refresh the record data
        $this->creditnote_details = $this->app->components->creditnote->getRecord($this->VAR['qpayment']['creditnote_id']);
        Payment::$record_balance = (float) $this->creditnote_details['balance'];

        $this->app->smarty->assign('creditnote_details', $this->creditnote_details);

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

    // Post-Processing - Now do final things like set messages and redirects
    public function postProcess()
    {
        parent::postProcess();

        // Refresh the record data
        $this->creditnote_details = $this->app->components->creditnote->getRecord($this->VAR['qpayment']['creditnote_id']);

        // Different actions depending on success
        if(Payment::$payment_successful)
        {
            // If the balance has been cleared
            if(Payment::$record_balance == 0)
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("The balance has been cleared."));
                $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
            }

            // New
            if(Payment::$action === 'new')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment added successfully and Credit Note").' '.$this->VAR['qpayment']['creditnote_id'].' '._gettext("has been updated to reflect this change."));
                // No forcepage, this will reload the new payment page
            }

            // Edit
            if(Payment::$action === 'edit')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Credit Note").' '.$this->VAR['qpayment']['creditnote_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('payment', 'details&payment_id='.Payment::$payment_details['payment_id']);
            }

            // Cancel
            if(Payment::$action === 'cancel')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Credit Note").' '.$this->VAR['qpayment']['creditnote_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
            }

            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Credit Note").' '.$this->VAR['qpayment']['creditnote_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
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
            if(Payment::$action === 'edit')
            {
                // Do nothing
            }

            // Cancel
            if(Payment::$action === 'cancel')            {

                $this->app->system->page->forcePage('creditnote', 'status&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
            }

            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->page->forcePage('creditnote', 'status&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
            }
        }

        return;
    }

    // General payment checks
    private function checkPaymentAllowed()
    {
        $state_flag = parent::checkPaymentAllowed();

        // Is on a different tax system
        if($this->creditnote_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The creditnote cannot receive a payment because it is on a different tax system."));
            $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
            $state_flag = false;
        }

        return $state_flag;
    }

    // Build Buttons
    public function buildButtons()
    {
        parent::buildButtons();

        // Submit
        if($this->creditnote_details['balance'] > 0) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }

        // Cancel
        if(!$this->creditnote_details['balance'] == 0)
        {
            if($this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'edit')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=creditnote&page_tpl=edit&creditnote_id='.$this->VAR['qpayment']['creditnote_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }
            if($this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'details')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=creditnote&page_tpl=details&creditnote_id='.$this->VAR['qpayment']['creditnote_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }
        }

        // Return To Record
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new'))
        {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=creditnote&page_tpl=details&creditnote_id='.$this->VAR['qpayment']['creditnote_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }

        // Add New Record
        Payment::$buttons['addNewRecord']['allowed'] = false;
        Payment::$buttons['addNewRecord']['url'] = null;
        Payment::$buttons['addNewRecord']['title'] = null;
    }

}
