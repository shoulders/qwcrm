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
        if(!$this->app->components->payment->checkMethodActive(Payment::$payment_details['method'])) {
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

        // Cancel
        if(Payment::$action === 'cancel')
        {
            // Cancel the payment
            if($this->app->components->payment->cancelRecord($this->VAR['qpayment']['payment_id']))
            {
                Payment::$payment_successful = true;
            }
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Delete the payment
            if($this->app->components->payment->deleteRecord($this->VAR['qpayment']['payment_id']))
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
    }
}
