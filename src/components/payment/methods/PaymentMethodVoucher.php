<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodVoucher extends PaymentMethod
{

    private $voucher_details = array();

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

        // Get `voucher_id` - Compensates if a voucher code is supplied instead
        if(!isset($this->VAR['qpayment']['voucher_id']) &&!$this->VAR['qpayment']['voucher_id'] = $this->app->components->voucher->getIdByVoucherCode($this->VAR['qpayment']['voucher_code'])) {
            // If there is no voucher_id, we cannot proceed
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no voucher with that code."));
            Payment::$payment_valid = false;
            return;
        }

        // Is Expired (Live Check)
        if($this->app->components->voucher->checkVoucherIsExpired($this->VAR['qpayment']['voucher_id'])) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot perform payment actions with an expired voucher.", $silent));
            Payment::$payment_valid = false;
        }

        // Get voucher details
        if((!$this->voucher_details = $this->app->components->voucher->getRecord($this->VAR['qpayment']['voucher_id']))) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no voucher note with that ID."));
            Payment::$payment_valid = false;
            return;
        }

        // Is on a different tax system
        if($this->voucher_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot perform payment actions with a voucher on a different Tax system.", $silent));
            Payment::$payment_valid = false;
        }

        // New
        if(Payment::$action === 'new')
        {
            // Does the voucher have enough balance to cover the payment amount submitted
            if($this->VAR['qpayment']['amount'] > $this->voucher_details['balance'])
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This Voucher does not have a sufficient balance to cover the submitted payment amount."));
                Payment::$payment_valid = false;
            }

            // Voucher cannot be used to pay for itself
            if($this->voucher_details['invoice_id'] == $this->VAR['qpayment']['invoice_id']) {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be used to pay for itself.", $silent));
                Payment::$payment_valid = false;
            }

            // Check Voucher Status
            switch ($this->voucher_details['status'])
            {
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be redeemed because it is a draft."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'unpaid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be redeemed because it has not been paid."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'partially_paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be redeemed because it has been partially paid."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'unredeemed':
                    break;
                case 'partially_redeemed':
                    break;
                case 'redeemed':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher has been redeemed so cannot be used anymore."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'closed_with_creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be redeemed because it has been closed with a credit note."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'suspended':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be redeemed because it has been suspended."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be redeemed because it has been voided."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This voucher cannot be redeemed because it has been deleted."), $silent);
                    Payment::$payment_valid = false;
                    break;
            }

        }

        // Edit
        if(Payment::$action === 'edit')
        {
            // Does the voucher have enough balance to cover the payment amount submitted (after removing this payments initial amount)
            if($this->VAR['qpayment']['amount'] > ($this->voucher_details['balance'] + Payment::$payment_details['amount']))
            {
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This Voucher does not have a sufficient balance to cover the submitted payment amount."));
                Payment::$payment_valid = false;
            }

            // Check Voucher Status
            switch ($this->voucher_details['status'])
            {
                case 'draft':
                case 'unpaid':
                case 'partially_paid':
                case 'unredeemed':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no payment to edit. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'partially_redeemed':
                    break;
                case 'redeemed':
                    break;
                case 'closed_with_creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because the voucehr has been closed wih a credit note."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'suspended':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because the voucher has been suspended."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because the voucher has been voided."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because the voucher has been deleted."), $silent);
                    Payment::$payment_valid = false;
                    break;
            }

        }

        // Void
        if(Payment::$action === 'void')
        {
            // Check Voucher Status
            switch ($this->voucher_details['status'])
            {
                case 'draft':
                case 'unpaid':
                case 'partially_paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no payment to void. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'unredeemed':
                    break;
                case 'partially_redeemed':
                    break;
                case 'redeemed':
                    break;
                case 'closed_with_creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because the voucher has been closed wih a credit note."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'suspended':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because the voucher has been suspended."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because the voucher has been voided."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because the voucher has been deleted."), $silent);
                    Payment::$payment_valid = false;
                    break;
            }
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Check Voucher Status
            switch ($this->voucher_details['status'])
            {
                case 'draft':
                case 'unpaid':
                case 'partially_paid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no payment to delete. You should not see this error, report to admins."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'unredeemed':
                    break;
                case 'partially_redeemed':
                    break;
                case 'redeemed':
                    break;
                case 'closed_with_creditnote':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because the voucher has been closed wih a credit note."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'suspended':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because the voucher has been suspended."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because the voucher has been voided."), $silent);
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because the voucher has been deleted."), $silent);
                    Payment::$payment_valid = false;
                    break;
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
                // Recalculate the Voucher record totals
                $this->app->components->voucher->recalculateTotals($this->VAR['qpayment']['voucher_id'], $this->VAR['qpayment']['amount'], Payment::$action);

                Payment::$payment_successful = true;
            }
        }

        if(Payment::$action === 'edit')
        {
            // Recalculate the Voucher record totals
            $this->app->components->voucher->recalculateTotals($this->VAR['qpayment']['voucher_id'], $this->VAR['qpayment']['amount'], Payment::$action, Payment::$payment_details['amount']);

            Payment::$payment_successful = true;
        }

        if(Payment::$action === 'void')
        {
            // Recalculate the Voucher record totals
            $this->app->components->voucher->recalculateTotals($this->VAR['qpayment']['voucher_id'], Payment::$payment_details['amount'], Payment::$action);

            Payment::$payment_successful = true;
        }

        if(Payment::$action === 'delete')
        {
            // Recalculate the Voucher record totals
            $this->app->components->voucher->recalculateTotals($this->VAR['qpayment']['voucher_id'], Payment::$payment_details['amount'], Payment::$action);
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
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Voucher applied successfully."));
        }
        else
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher was not applied successfully."));
            //$logMessage = _gettext("Voucher").' '.$voucher_id.' '._gettext("was redeemed by").' '.$this->app->components->client->getRecord($invoice_details['client_id'], 'display_name').'.';
        }

        // Refresh the voucher details TODO: there used to be a test if the details existed, is this needed?
        $this->voucher_details = $this->app->components->voucher->getRecord($this->voucher_details['voucher_id']);
        $this->app->system->variables->systemMessagesWrite('warning', _gettext("The balance left on this voucher is").': '.CURRENCY_SYMBOL.$this->voucher_details['balance']);

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
