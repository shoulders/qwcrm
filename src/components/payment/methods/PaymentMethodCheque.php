<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodCheque {
    
    private $app = null;
    private $VAR = null;
    
    public function __construct() {
        
        // Set class variables
        $this->app = \Factory::getApplication();
        $this->VAR = &\CMSApplication::$VAR;        
        
    }
    
    // Pre-Processing
    public function preProcess() {

            return true;
            
    }

    // Processing
    public function process() {
        
        // Build additional_info column
        $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->buildAdditionalInfoJson(null, null, null, $this->VAR['qpayment']['cheque_number']);
        
        // Insert the payment with the calculated information
        if($this->app->components->payment->insertRecord($this->VAR['qpayment'])) {            
            Payment::$payment_processed = true;            
        }
        
        return;
        
    }
    
    // Post-Processing 
    public function postProcess() { 
        
        // Set success/failure message
        if(!Payment::$payment_processed) {
        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Cheque payment was not successful."));
        
        } else {            
            
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Cheque payment added successfully."));

        }
        
        return;
       
    }  

}