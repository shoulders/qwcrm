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
        
        // Check the Voucher exists and set the voucher_id
        if(!$VAR['qpayment']['voucher_id'] = get_voucher_id_by_voucher_code($this->VAR['qpayment']['voucher_code'])) {

            $this->smarty->assign('warning_msg', _gettext("There is no Voucher with that code."));
            
            return false;
        }

        // Make sure the Voucher is valid and then pass the amount to the next process
        if(!check_voucher_can_be_redeemed($VAR['qpayment']['voucher_id'], $VAR['qpayment']['invoice_id'])) {

                $this->smarty->assign('warning_msg', _gettext("This Voucher is not valid or cannot be redeemed."));        

                return false;
        }
        
        // Set the value of the Voucher to the amount to be applied
        $this->VAR['qpayment']['amount'] = get_voucher_details($this->VAR['qpayment']['voucher_id'], 'unit_net');
        
        return true;

    }

    // Processing
    public function process() {
        
        // Build additional information column
        $this->VAR['qpayment']['additional_info'] = build_additional_info_json();    

        // Insert the payment with the calculated information
        if($payment_id != insert_payment($this->VAR['qpayment'])) {
            
            NewPayment::$payment_processed = false; 
            
        } else {
            
            // Change the status of the Voucher to prevent further use
            update_voucher_status($this->VAR['qpayment']['voucher_id'], 'redeemed', true);

            // Update the redeemed Voucher with the missing redemption information
            update_voucher_as_redeemed($this->VAR['qpayment']['voucher_id'], $this->VAR['qpayment']['invoice_id'], $payment_id);
            
            NewPayment::$payment_processed = true;
            
        }
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() { 
        
        // Set success/failure message
        if(!NewPayment::$payment_validated ) {
        
            $this->smarty->assign('warning_msg', _gettext("Voucher was not apllied successful."));
        
        } else {            
            
            $this->smarty->assign('information_msg', _gettext("Voucher applied successfully."));

        }
        
        return;
       
    }
    
}