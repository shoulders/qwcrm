<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodOther extends PaymentMethod
{    
    public function __construct()
    {        
        parent::__construct();
        
        // Set class variables
        Payment::$payment_details['method'] = 'other';
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
            $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->buildAdditionalInfoJson();  

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
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Other payment added successfully."));        
        }
        else
        {            
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Other payment was not successful."));
        }
        
        return;       
    } 
}