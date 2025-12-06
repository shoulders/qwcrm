<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodCash extends PaymentMethod
{
    public function __construct()
    {
        parent::__construct();
    }

    // Pre-Processing
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

    // Processing
    public function process()
    {
        parent::process();

        if(Payment::$action === 'new')
        {
            // Insert the payment with the calculated information
            if(Payment::$payment_details['payment_id'] = $this->app->components->payment->insertRecord($this->VAR['qpayment'])) {
                Payment::$payment_successful = true;
            }
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
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Cash payment added successfully."));
        }
        else
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Cash payment was not successful."));
        }

        return;
    }
}
