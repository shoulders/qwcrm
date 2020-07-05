<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodBanktransfer {
    
    private $app = null;
    private $VAR = null;
    private $smarty = null;
    
    public function __construct() {
        
        // Set class variables
        $this->app = \Factory::getApplication();
        $this->VAR = &\CMSApplication::$VAR;        
        
    }
    
    // Pre-Processing
    public function pre_process() {

            return true;
            
    }

    // Processing
    public function process() {
        
        // Build additional_info column
        $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->buildAdditionalInfoJson($this->VAR['qpayment']['bank_transfer_reference']);     
        
        // Insert the payment with the calculated information
        if($this->app->components->payment->insertRecord($this->VAR['qpayment'])) {            
            Payment::$payment_processed = true;            
        }
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() { 
        
        // Set success/failure message
        if(!Payment::$payment_processed) {
        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Bank Transfer payment was not successful."));
        
        } else {            
            
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Bank Transfer payment added successfully."));

        }
        
        return;
       
    }  

}