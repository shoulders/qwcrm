<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodCard extends PaymentMethod
{    
    public function __construct()
    {        
        parent::__construct();
        
        // Set class variables
        Payment::$payment_details['method'] = 'card';
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
            $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->buildAdditionalInfoJson(null, $this->VAR['qpayment']['card_type_key'], $this->VAR['qpayment']['name_on_card']);  

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
           $this->app->system->variables->systemMessagesWrite('success', _gettext("Card payment added successfully."));        
        }
        else
        {            
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Card payment was not successful.")); 
        }
        
        return;       
    } 
}
