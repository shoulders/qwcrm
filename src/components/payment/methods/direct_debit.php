<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PMethod extends NewPayment {
    
    private $VAR = null;
    private $smarty = null;
    
    public function __construct(&$VAR) {
        
        $this->VAR = &$VAR;
        $this->smarty = \Factory::getSmarty();
        
    }
    
    // Pre-Processing
    public function pre_process() {

            return true;
            
    }

    // Processing
    public function process() {
        
        // Build additional_info column
        $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->build_additional_info_json(null, null, null, null, $this->VAR['qpayment']['direct_debit_reference']);   
        
        // Insert the payment with the calculated information
        if($this->app->components->payment->insert_payment($this->VAR['qpayment'])) {            
            NewPayment::$payment_processed = true;            
        }
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() { 
        
        // Set success/failure message
        if(!NewPayment::$payment_processed) {
        
            $this->smarty->assign('msg_danger', _gettext("Direct Debit payment was not successful."));
        
        } else {            
            
            $this->smarty->assign('msg_success', _gettext("Direct Debit payment added successfully."));

        }
        
        return;
       
    }  

}