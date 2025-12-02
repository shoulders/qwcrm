<?php

/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

// Payment Types control the specific logic for the target of the payment eg: Creditnote, Expense, Invoice, Otherincome

defined('_QWEXEC') or die;

class PaymentType
{
    protected $app = null;
    protected $VAR = null;

    protected function __construct()
    {
        // Make sure an action is set or fail the payment
        if(!Payment::$action) {Payment::$payment_valid = false;}

        // Set class variables
        $this->app = \Factory::getApplication();
        $this->VAR = &\CMSApplication::$VAR;
    }

    // Pre-Processing - Prep/validate the data
    protected function preProcess()
    {
        // Is the payment allowed - no checks in this function are currently in place - this is a placeholder
        if(!$this->checkPaymentAllowed())
        {
            Payment::$payment_valid = false;
        }

        // New
        if(Payment::$action === 'new')
        {
            // Validate payment_amount
            if(!$this->app->components->payment->checkAmountValid(Payment::$record_balance, $this->VAR['qpayment']['amount'])) {
                Payment::$payment_valid = false;
            }
        }

        // Edit
        if(Payment::$action === 'edit')
        {
            // Check payment Status allows record Edit  - This check is also done upstream on payment:details
            if(!$this->app->components->payment->checkRecordAllowsEdit(Payment::$payment_details['payment_id']))
            {
                Payment::$payment_valid = false;
            }

            // Is the new amount the same as the last, if so do nothing
            if($this->VAR['qpayment']['amount'] == Payment::$payment_details['amount'])
            {
                Payment::$payment_valid = false;
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The amount is unchanged, no edit has occured."));
            }

            // Validate payment_amount (after returning the previous payment to the record balance allow before applying the new one)
            $edit_record_balance = (Payment::$record_balance + Payment::$payment_details['amount']);
            if(!$this->app->components->payment->checkAmountValid($edit_record_balance, $this->VAR['qpayment']['amount'])) {
                Payment::$payment_valid = false;
            }
        }

        // Cancel
        if(Payment::$action === 'cancel')
        {
            // Check payment Status allows record Cancel  - This check is done upstream on payment:status
            if(!$this->app->components->payment->checkRecordAllowsCancel(Payment::$payment_details['payment_id']))
            {
                Payment::$payment_valid = false;
            }
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Check payment Status allows record Delete  - This check is done upstream on payment:status
            if(!$this->app->components->payment->checkRecordAllowsDelete(Payment::$payment_details['payment_id']))
            {
                Payment::$payment_valid = false;
            }
        }

        return;
    }

    // Processing - Process the payment
    protected function process()
    {
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
    protected function postProcess()
    {
        // Different actions depending on success
        if(Payment::$payment_successful)
        {
            // New
            if(Payment::$action === 'new')
            {
                $record = _gettext(Payment::$payment_details['type']).' '._gettext("had a new").' '._gettext(Payment::$payment_details['method']).' '._gettext("payment made with the Payment ID").': '.Payment::$payment_details['payment_id'];
            }

            // Edit
            if(Payment::$action === 'edit')
            {
                $record = "Payment ID".': '.Payment::$payment_details['payment_id'].' '._gettext("was edited.");
            }

            // Cancel
            if(Payment::$action === 'cancel')
            {
                $record = "Payment ID".': '.Payment::$payment_details['payment_id'].' '._gettext("was cancelled.");
            }

            // Delete
            if(Payment::$action === 'delete')
            {
                $record = "Payment ID".': '.Payment::$payment_details['payment_id'].' '._gettext("was deleted.");
            }

            // Log activity
            $this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, Payment::$payment_details['client_id'], null, Payment::$payment_details['invoice_id']);

        }

        else
        {
            // Do nothing, loads the same page
        }

        return;
    }

    // General payment checks (placeholder for now)
    private function checkPaymentAllowed()
    {
       return true;
    }

    // Build Buttons (placeholder for now)
    public function buildButtons()
    {
        return;
    }

}
