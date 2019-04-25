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
        $this->smarty = QFactory::getSmarty();
        
    }
    
    // Pre-Processing
    public function pre_process() {

            return true;
            
    }

    // Processing
    public function process() {
        
        // Build additional_info column
        $this->VAR['qpayment']['additional_info'] = build_additional_info_json(null, null, null, $this->VAR['qpayment']['cheque_number']);
        
        // Insert the payment with the calculated information
        if(!insert_payment($this->VAR['qpayment'])) {
            
            NewPayment::$payment_processed = false;  
            
        } else {
        
            NewPayment::$payment_processed = true;
        
        }
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() { 
        
        // Set success/failure message
        if(!NewPayment::$payment_validated ) {
        
            $this->smarty->assign('warning_msg', _gettext("Cheque payment was not successful."));
        
        } else {            
            
            $this->smarty->assign('information_msg', _gettext("Cheque payment added successfully."));

        }
        
        return;
       
    }  

}