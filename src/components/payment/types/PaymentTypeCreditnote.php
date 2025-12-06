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
    private $creditnote_details = array();

    public function __construct()
    {
        parent::__construct();

        // Get credit note details
        $this->creditnote_details = $this->app->components->creditnote->getRecord($this->VAR['qpayment']['creditnote_id']);

        // Set Payment direction (inject into the submission)
        if($this->creditnote_details['type'] == 'sales'){
            // Sending a Payment
            $this->VAR['qpayment']['direction'] = 'debit';
        }
        else{
            // Receiving a Payment
            $this->VAR['qpayment']['direction'] = 'credit';
        }

        // Additional Record References (inject into the submission)
        $this->VAR['qpayment']['client_id'] = $this->creditnote_details['client_id'];
        $this->VAR['qpayment']['supplier_id'] = $this->creditnote_details['supplier_id'];

        // Disable Unwanted Payment Methods
        Payment::$disabledMethods[] = 'creditnote';
        Payment::$disabledMethods[] = 'voucher';

        // Set initial record balance
        Payment::$record_balance = (float) $this->creditnote_details['balance'];

        // Assign Payment Type specific template variables
        if($this->creditnote_details['type'] == 'sales')
        {
            // show payment methods to send money (debit)
            $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('send', true, Payment::$disabledMethods));

            // Client Details
            $this->app->smarty->assign('client_details', $this->app->components->client->getRecord($this->creditnote_details['client_id']));
        }
        // type == purchase
        else
        {
            // show payment methods to receive money (credit)
            $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('receive', true, Payment::$disabledMethods));

            // supplier Details
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

        return;
    }

    // Processing - Process the payment
    public function process()
    {
        parent::process();

        // Different actions depending on success
        if(Payment::$payment_successful)
        {
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

            // Recalculate record totals
            $this->app->components->creditnote->recalculateTotals($this->VAR['qpayment']['creditnote_id']);

            // Refresh the record data
            $this->creditnote_details = $this->app->components->creditnote->getRecord($this->VAR['qpayment']['creditnote_id']);
            Payment::$record_balance = (float) $this->creditnote_details['balance'];

            $this->app->smarty->assign('creditnote_details', $this->creditnote_details);

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
            elseif(Payment::$action === 'new')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment added successfully and Credit Note").' '.$this->VAR['qpayment']['creditnote_id'].' '._gettext("has been updated to reflect this change."));
                // No forcepage, this will reload the new payment page
            }

            // Edit
            elseif(Payment::$action === 'edit')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Credit Note").' '.$this->VAR['qpayment']['creditnote_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('payment', 'details&payment_id='.Payment::$payment_details['payment_id']);
            }

            // Cancel
            elseif(Payment::$action === 'cancel')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Credit Note").' '.$this->VAR['qpayment']['creditnote_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
            }

            // Delete
            elseif(Payment::$action === 'delete')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Credit Note").' '.$this->VAR['qpayment']['creditnote_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('creditnote', 'details&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
            }

        } else {

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

                $this->app->system->page->forcePage('creditnote', 'status&creditnote_id='.$this->VAR['qpayment']['creditnote_id']);
            }

            // Delete
            elseif(Payment::$action === 'delete')
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
        if((float) $this->creditnote_details['balance']) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }

        // Cancel
        if(!(float) $this->creditnote_details['balance'])
        {
            if($this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'edit') || $this->app->system->security->checkPageAccessedViaQwcrm('creditnote', 'details')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=creditnote&page_tpl=edit&creditnote_id='.$this->VAR['creditnote_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }
        }

        // Return To Record
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new'))
        {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=creditnote&page_tpl=details&creditnote_id='.$this->VAR['creditnote_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }

        // Add New Record
        Payment::$buttons['addNewRecord']['allowed'] = true;
        Payment::$buttons['addNewRecord']['url'] = 'index.php?component=invoice&page_tpl=new';
        Payment::$buttons['addNewRecord']['title'] = _gettext("Add New Credit Note Record");
    }

}
