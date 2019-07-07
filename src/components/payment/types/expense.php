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
    private $expense_details = null;
    
    public function __construct(&$VAR) {
        
        $this->VAR = &$VAR;
        $this->smarty = QFactory::getSmarty();
        $this->expense_details = get_expense_details($this->VAR['qpayment']['expense_id']);        
        
        // Set intial record balance
        if(class_exists('NewPayment')) {NewPayment::$record_balance = $this->expense_details['balance'];}
        if(class_exists('UpdatePayment')) {UpdatePayment::$record_balance = $this->expense_details['balance'];}
        
        // Assign Type specific template variables  
        $this->smarty->assign('payment_active_methods', get_payment_methods('send', 'enabled'));
        $this->smarty->assign('expense_details', $this->expense_details);
        $this->smarty->assign('expense_statuses', get_expense_statuses());   
        
    }    
    
    // Pre-Processing
    public function pre_process() {
        
        // Add required variables
        $this->VAR['qpayment']['client_id'] = '';
        $this->VAR['qpayment']['workorder_id'] = '';
        
        // Validate payment_amount (New Payments)
        if(class_exists('NewPayment')) {
            NewPayment::$record_balance = $this->expense_details['balance'];
            if(!validate_payment_amount(NewPayment::$record_balance, $this->VAR['qpayment']['amount'])) {
                NewPayment::$payment_valid = false;
            }            
        }
        
        // Validate payment_amount (Payment Update)
        if(class_exists('UpdatePayment')) {
            UpdatePayment::$record_balance = ($this->expense_details['balance'] + UpdatePayment::$payment_details['amount']);
            if(!validate_payment_amount(UpdatePayment::$record_balance, UpdatePayment::$payment_details['amount'])) {
                UpdatePayment::$payment_valid = false;
            }
        }
        
        return;

    }

    // Processing
    public function process() {
        
        // Recalculate record totals
        recalculate_expense_totals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        $this->expense_details = get_expense_details($this->VAR['qpayment']['expense_id']);
        $this->smarty->assign('expense_details', $this->expense_details);
        NewPayment::$record_balance = $this->expense_details['balance'];
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() {   
        
        /* If the balance has been cleared, redirect to the record details page
        if($this->expense_details['balance'] == 0) {
            force_page('expense', 'details&expense_id='.$this->VAR['expense_id'], 'information_msg='._gettext("The balance has been cleared."));
        }*/
        
        return;
       
    }
    
    // Build Buttons
    public function build_buttons() {
        
        // Submit
        if($this->expense_details['balance'] > 0) {
            NewPayment::$buttons['submit']['allowed'] = true;
            NewPayment::$buttons['submit']['url'] = null;
            NewPayment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }        
        
        // Cancel
        if(!$this->expense_details['balance'] == 0) {            
            if(check_page_accessed_via_qwcrm('expense', 'new') || check_page_accessed_via_qwcrm('expense', 'details')) {
                NewPayment::$buttons['cancel']['allowed'] = true;
                NewPayment::$buttons['cancel']['url'] = 'index.php?component=expense&page_tpl=details&expense_id='.$this->VAR['qpayment']['expense_id'];
                NewPayment::$buttons['cancel']['title'] = _gettext("Cancel");
            }            
        }
        
        // Return To Record
        if(check_page_accessed_via_qwcrm('payment', 'new')) {
            NewPayment::$buttons['returnToRecord']['allowed'] = true;
            NewPayment::$buttons['returnToRecord']['url'] = 'index.php?component=expense&page_tpl=details&expense_id='.$this->VAR['qpayment']['expense_id'];
            NewPayment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }
        
        // Add New Record
        NewPayment::$buttons['addNewRecord']['allowed'] = true;
        NewPayment::$buttons['addNewRecord']['url'] = 'index.php?component=expense&page_tpl=new';
        NewPayment::$buttons['addNewRecord']['title'] = _gettext("Add New Expense Record");
        
    }   
    
    // Update Payment
    public function update() {
        
        // Update the payment
        update_payment($this->VAR['qpayment']);
                
        // Recalculate record totals
        recalculate_expense_totals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        //$this->expense_details = get_expense_details($this->VAR['qpayment']['expense_id']);        
        
        // Load the relevant record details page
        force_page('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id'], 'information_msg='._gettext("Payment updated successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
                
        return;        
        
    }
    
    // Cancel Payment
    public function cancel() {
        
        // Cancel the payment
        cancel_payment($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        recalculate_expense_totals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        //$this->expense_details = get_expense_details($this->VAR['qpayment']['expense_id']);        
        
        // Load the relevant record details page
        force_page('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id'], 'information_msg='._gettext("Payment cancelled successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
                
        return;        
        
    }
    
    // Delete Payment
    public function delete() {
        
        // Delete the payment
        delete_payment($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        recalculate_expense_totals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        //$this->expense_details = get_expense_details($this->VAR['qpayment']['expense_id']);        
        
        // Load the relevant record details page
        force_page('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id'], 'information_msg='._gettext("Payment deleted successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
                
        return;        
        
    }
    
    // Check Payment is allowed
    public function check_payment_allowed() {
        
        // Is on a different tax system
        if($this->expense_details['tax_system'] != QW_TAX_SYSTEM) {
            //postEmulationWrite('warning_msg', _gettext("The expense cannot receive a payment because it is on a different tax system."));
            //return false;            
            force_page('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id'], 'warning_msg='._gettext("The expense cannot receive a payment because it is on a different tax system."));
            
        }

        // All checks passed
        return true;
       
    }

}