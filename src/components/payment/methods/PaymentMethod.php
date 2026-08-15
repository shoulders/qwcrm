<?php

/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

// Payment Methods control the specific logic for the souce of the payment eg: Cash, Cheques, Credit Notes.....

class PaymentMethod
{
    protected $app = null;
    protected $VAR = null;

    protected function __construct()
    {
        // Set class variables
        $this->app = \Factory::getApplication();
        $this->VAR = &\CMSApplication::$VAR;
    }

    // Pre-Processing - Prep/validate the data
    protected function preProcess()
    {
        // Is this payment method active
        if(!$this->app->components->payment->checkMethodActive(Payment::$method)) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The payment cannot be processed because it's current payment method is not available."));
            Payment::$payment_valid = false;
        }

        // New
        if(Payment::$action === 'new')
        {
            // Do nothing
        }

        // Edit
        if(Payment::$action === 'edit')
        {
            // Status Checks
            switch(Payment::$payment_details['status']){
                case 'draft':
                    break;
                case 'valid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because it has been approved."));
                    Payment::$payment_valid = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because it has been voided."));
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be edited because it has been deleted."));
                    Payment::$payment_valid = false;
                    break;
            }
        }

        // Void
        if(Payment::$action === 'void')
        {
            // Status Checks
            switch(Payment::$payment_details['status']){
                case 'draft':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because it is a draft."));
                    Payment::$payment_valid = false;
                    break;
                case 'valid':
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because it has already been voided."));
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be voided because it has been deleted."));
                    Payment::$payment_valid = false;
                    break;
            }
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Status Checks
            switch(Payment::$payment_details['status']){
                case 'draft':
                    break;
                case 'valid':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because it has been approved."));
                    Payment::$payment_valid = false;
                    break;
                case 'voided':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because it has been voided."));
                    Payment::$payment_valid = false;
                    break;
                case 'deleted':
                    $this->app->system->variables->systemMessagesWrite('danger', _gettext("This payment cannot be deleted because it has already been deleted."));
                    Payment::$payment_valid = false;
                    break;
            }
        }
    }

    // Processing
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
            // Update the payment
            if($this->app->components->payment->updateRecord($this->VAR['qpayment']))
            {
                Payment::$payment_successful = true;
            }
        }

        // Void
        if(Payment::$action === 'void')
        {
            // Void the payment
            if($this->app->components->payment->voidRecord(Payment::$payment_details['payment_id'], \CMSApplication::$VAR['qform']['reason_for_voiding']))
            {
                Payment::$payment_successful = true;
            }
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Delete the payment
            if($this->app->components->payment->deleteRecord(Payment::$payment_details['payment_id']))
            {
                Payment::$payment_successful = true;
            }
        }
    }

    // Post-Processing
    protected function postProcess()
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

        // void
        if(Payment::$action === 'void')
        {
            // Do nothing
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Do nothing
        }
    }
}
