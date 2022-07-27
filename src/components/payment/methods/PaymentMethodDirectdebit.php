<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodDirectdebit extends PaymentMethod
{    
    public function __construct()
    {        
        parent::__constuct();
        
        // Set class variables
        Payment::$payment_details['method'] = 'direct_debit';
    }
    
    // Pre-Processing
    public function preProcess()
    {
        parent::preProcess();
        return;            
    }

    // Processing
    public function process()
    {
        parent::process();
        
        if(Payment::$action === 'new')
        { 
            // Build additional_info column
            $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->buildAdditionalInfoJson(null, null, null, null, $this->VAR['qpayment']['direct_debit_reference']);   

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
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Direct Debit payment added successfully."));        
        }
        else
        {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Direct Debit payment was not successful."));
        }
        
        return;       
    }  

}