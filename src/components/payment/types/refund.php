<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PType {
    
    private $VAR = null;
    private $smarty = null;
    private $refund_details = null;
    
    public function __construct(&$VAR) {
        
        $this->VAR = &$VAR;
        $this->smarty = QFactory::getSmarty();    
        $this->refund_details = get_refund_details($this->VAR['qpayment']['refund_id']);
        
        // Set intial record balance
        if(class_exists('NewPayment')) {NewPayment::$record_balance = $this->refund_details['balance'];}
        if(class_exists('UpdatePayment')) {UpdatePayment::$record_balance = $this->refund_details['balance'];}
        
        // Assign Type specific template variables
        $this->smarty->assign('client_details', get_client_details($this->refund_details['client_id']));
        $this->smarty->assign('payment_active_methods', get_payment_methods('send', 'enabled'));
        $this->smarty->assign('refund_details', $this->refund_details);
        $this->smarty->assign('refund_statuses', get_refund_statuses());
        
    }
    
    // Pre-Processing
    public function pre_process() {
        
        // Add required variables
        $this->VAR['qpayment']['client_id'] = $this->refund_details['client_id'];
        $this->VAR['qpayment']['workorder_id'] = $this->refund_details['workorder_id'];
        
        // Validate payment_amount (New Payments)
        if(class_exists('NewPayment')) {
            NewPayment::$record_balance = $this->refund_details['balance'];
            if(!validate_payment_amount(NewPayment::$record_balance, $this->VAR['qpayment']['amount'])) {
                NewPayment::$payment_validated = false;
            } else {
                NewPayment::$payment_validated = true;
            }
        }
        
        // Validate payment_amount (Payment Update)
        if(class_exists('UpdatePayment')) {
            UpdatePayment::$record_balance = ($this->refund_details['balance'] + UpdatePayment::$payment_details['amount']);
            if(!validate_payment_amount(UpdatePayment::$record_balance, UpdatePayment::$payment_details['amount'])) {
                UpdatePayment::$payment_validated = false;
            } else {
                UpdatePayment::$payment_validated = true;
            }
        }
        
        return;

    }

    // Processing
    public function process() {
        
        // Recalculate record totals
        recalculate_refund_totals($this->VAR['qpayment']['refund_id']);
        
        // Refresh the record data        
        $this->refund_details = get_refund_details($this->VAR['qpayment']['refund_id']);
        $this->smarty->assign('refund_details', $this->refund_details);
        NewPayment::$record_balance = $this->refund_details['balance'];
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() {
        
        // If the balance has been cleared, redirect to the record details page
        if($this->refund_details['balance'] == 0) {
            force_page('refund', 'details&refund_id='.$this->VAR['refund_id'], 'information_msg='._gettext("The balance has been cleared."));
        }
        
        return;
       
    }
    
    // Build Buttons
    public function build_buttons() {
        
        // Submit
        if($this->refund_details['balance'] > 0) {
            NewPayment::$buttons['submit']['allowed'] = true;
            NewPayment::$buttons['submit']['url'] = null;
            NewPayment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }        
        
        // Cancel
        if(!$this->refund_details['balance'] == 0) {            
            if(check_page_accessed_via_qwcrm('refund', 'new') || check_page_accessed_via_qwcrm('refund', 'details')) {
                NewPayment::$buttons['cancel']['allowed'] = true;
                NewPayment::$buttons['cancel']['url'] = 'index.php?component=refund&page_tpl=details&refund_id='.$this->VAR['qpayment']['refund_id'];
                NewPayment::$buttons['cancel']['title'] = _gettext("Cancel");
            }            
        }
        
        // Return To Record
        if(check_page_accessed_via_qwcrm('payment', 'new')) {
            NewPayment::$buttons['returnToRecord']['allowed'] = true;
            NewPayment::$buttons['returnToRecord']['url'] = 'index.php?component=refund&page_tpl=details&refund_id='.$this->VAR['qpayment']['refund_id'];
            NewPayment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }
        
        // Add New Record
        NewPayment::$buttons['addNewRecord']['allowed'] = false;
        NewPayment::$buttons['addNewRecord']['url'] = null;        
        NewPayment::$buttons['addNewRecord']['title'] = null;
        
    } 
    
    // Update Payment
    public function update() {
        
        // update the payment
        update_payment($this->VAR['qpayment']);
                
        // Recalculate record totals
        recalculate_refund_totals($this->VAR['qpayment']['refund_id']);
        
        // Refresh the record data        
        //$this->refund_details = get_refund_details($this->VAR['qpayment']['refund_id']);        
        
        // Load the relevant record details page
        force_page('refund', 'details&refund_id='.$this->VAR['qpayment']['refund_id'], 'information_msg='._gettext("Payment updated successfully and Refund").' '.$this->VAR['qpayment']['refund_id'].' '._gettext("has been updated to reflect this change."));
                
        return;        
        
    }
    
    // Cancel Payment
    public function cancel() {
        
        // Cancel the payment
        cancel_payment($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        recalculate_refund_totals($this->VAR['qpayment']['refund_id']);
        
        // Refresh the record data        
        //$this->refund_details = get_refund_details($this->VAR['qpayment']['refund_id']);        
        
        // Load the relevant record details page
        force_page('refund', 'details&refund_id='.$this->VAR['qpayment']['refund_id'], 'information_msg='._gettext("Payment cancelled successfully and Refund").' '.$this->VAR['qpayment']['refund_id'].' '._gettext("has been updated to reflect this change."));
                
        return;        
        
    }
    
    // Delete Payment
    public function delete() {
        
        // Delete the payment
        delete_payment($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        recalculate_refund_totals($this->VAR['qpayment']['refund_id']);
        
        // Refresh the record data        
        //$this->refund_details = get_refund_details($this->VAR['qpayment']['refund_id']);        
        
        // Load the relevant record details page
        force_page('refund', 'details&refund_id='.$this->VAR['qpayment']['refund_id'], 'information_msg='._gettext("Payment deleted successfully and Refund").' '.$this->VAR['qpayment']['refund_id'].' '._gettext("has been updated to reflect this change."));
                
        return;        
        
    }
    
    // Check Payment is allowed
    public function check_payment_allowed() {
        
        // Is on a different tax system
        if($this->refund_details['tax_system'] != QW_TAX_SYSTEM) {
            //postEmulationWrite('warning_msg', _gettext("The refund cannot receive a payment because it is on a different tax system."));
            //return false;            
            force_page('refund', 'details&refund_id='.$this->VAR['qpayment']['refund_id'], 'warning_msg='._gettext("The refund cannot receive a payment because it is on a different tax system."));
            
        }

        // All checks passed
        return true;
       
    }

}