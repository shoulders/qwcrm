<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodVoucher {
    
    private $app = null;
    private $VAR = null;  
    
    public function __construct() {
        
        // Set class variables
        $this->app = \Factory::getApplication();
        $this->VAR = &\CMSApplication::$VAR;        
        
        // Check the Voucher exists, get the voucher_id and set amount
        if(!$this->VAR['qpayment']['voucher_id'] = $this->app->components->voucher->get_voucher_id_by_voucher_code($this->VAR['qpayment']['voucher_code'])) {
            Payment::$payment_valid = false;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no Voucher with that code."));                   
        } else {                        
            $this->VAR['qpayment']['amount'] = $this->app->components->voucher->get_voucher_details($this->VAR['qpayment']['voucher_id'], 'unit_net');
        }        
        
    }
    
    // Pre-Processing
    public function pre_process() {
        
        // Make sure the Voucher is valid and then pass the amount to the next process
        if(!$this->app->components->voucher->check_voucher_can_be_redeemed($this->VAR['qpayment']['voucher_id'], $this->VAR['qpayment']['invoice_id'])) {
            Payment::$payment_valid = false;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This Voucher is not valid or cannot be redeemed."));
            return false;                
        }
        
        return true;

    }

    // Processing
    public function process() {
        
        // Build additional_info column
        $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->build_additional_info_json();    

        // Insert the payment with the calculated information
        $payment_id = $this->app->components->payment->insert_payment($this->VAR['qpayment']);
        if($payment_id) {
            
            Payment::$payment_processed = true;
            
            // Change the status of the Voucher to prevent further use
            $this->app->components->voucher->update_voucher_status($this->VAR['qpayment']['voucher_id'], 'redeemed', true);

            // Update the redeemed Voucher with the missing redemption information
            $this->app->components->voucher->update_voucher_as_redeemed($this->VAR['qpayment']['voucher_id'], $this->VAR['qpayment']['invoice_id'], $payment_id);
            
        }
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() { 
        
        // Set success/failure message
        if(!Payment::$payment_processed) {
        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher was not applied successfully."));
        
        } else {            
            
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Voucher applied successfully."));

        }
        
        return;
       
    }
    
}