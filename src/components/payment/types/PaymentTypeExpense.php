<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeExpense {
    
    private $app = null;
    private $VAR = null;    
    private $expense_details = null;
    
    public function __construct() {
        
        // Set class variables
        $this->app = \Factory::getApplication();
        $this->VAR = &\CMSApplication::$VAR;        
        $this->expense_details = $this->app->components->expense->getRecord($this->VAR['qpayment']['expense_id']);        
        
        // Set intial record balance
        Payment::$record_balance = $this->expense_details['balance'];
        
        // Assign Type specific template variables  
        $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('send', 'enabled'));
        $this->app->smarty->assign('expense_details', $this->expense_details);
        $this->app->smarty->assign('expense_statuses', $this->app->components->expense->getStatuses());   
        
    }    
    
    // Pre-Processing
    public function pre_process() {
        
        // Add required variables
        $this->VAR['qpayment']['client_id'] = '';
        $this->VAR['qpayment']['workorder_id'] = '';
        
        // Validate payment_amount (New Payments)
        if(Payment::$action === 'new') {
            Payment::$record_balance = $this->expense_details['balance'];
            if(!$this->app->components->payment->checkAmountValid(Payment::$record_balance, $this->VAR['qpayment']['amount'])) {
                Payment::$payment_valid = false;
            }            
        }
        
        // Validate payment_amount (Payment Update)
        if(Payment::$action === 'update') {
            Payment::$record_balance = ($this->expense_details['balance'] + Payment::$payment_details['amount']);
            if(!$this->app->components->payment->checkAmountValid(Payment::$record_balance, Payment::$payment_details['amount'])) {
                Payment::$payment_valid = false;
            }
        }
        
        return;

    }

    // Processing
    public function process() {
        
        // Recalculate record totals
        $this->app->components->expense->recalculateTotals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        $this->expense_details = $this->app->components->expense->getRecord($this->VAR['qpayment']['expense_id']);
        $this->app->smarty->assign('expense_details', $this->expense_details);
        Payment::$record_balance = $this->expense_details['balance'];
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() {   
        
        /* If the balance has been cleared, redirect to the record details page
        if($this->expense_details['balance'] == 0) {
            $this->app->system->variables->systemMessagesWrite('success', _gettext("The balance has been cleared."));
            $this->app->system->page->force_page('expense', 'details&expense_id='.$this->VAR['expense_id']);
        }*/
        
        return;
       
    }
    
    // Build Buttons
    public function build_buttons() {
        
        // Submit
        if($this->expense_details['balance'] > 0) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }        
        
        // Cancel
        if(!$this->expense_details['balance'] == 0) {            
            if($this->app->system->security->checkPageAccessedViaQwcrm('expense', 'new') || $this->app->system->security->checkPageAccessedViaQwcrm('expense', 'details')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=expense&page_tpl=details&expense_id='.$this->VAR['qpayment']['expense_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }            
        }
        
        // Return To Record
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=expense&page_tpl=details&expense_id='.$this->VAR['qpayment']['expense_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }
        
        // Add New Record
        Payment::$buttons['addNewRecord']['allowed'] = true;
        Payment::$buttons['addNewRecord']['url'] = 'index.php?component=expense&page_tpl=new';
        Payment::$buttons['addNewRecord']['title'] = _gettext("Add New Expense Record");
        
    }   
    
    // Update Payment
    public function update() {
        
        // Update the payment
        $this->app->components->payment->updateRecord($this->VAR['qpayment']);
                
        // Recalculate record totals
        $this->app->components->expense->recalculateTotals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        //$this->expense_details = $this->app->components->expense->get_expense_details($this->VAR['qpayment']['expense_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->forcePage('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id']);
                
        return;        
        
    }
    
    // Cancel Payment
    public function cancel() {
        
        // Cancel the payment
        $this->app->components->payment->cancelRecord($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        $this->app->components->expense->recalculateTotals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        //$this->expense_details = $this->app->components->expense->get_expense_details($this->VAR['qpayment']['expense_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->forcePage('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id']);
                
        return;        
        
    }
    
    // Delete Payment
    public function delete() {
        
        // Delete the payment
        $this->app->components->payment->deleteRecord($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        $this->app->components->expense->recalculateTotals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        //$this->expense_details = $this->app->components->expense->get_expense_details($this->VAR['qpayment']['expense_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->forcePage('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id']);
                
        return;        
        
    }
    
    // Check Payment is allowed
    public function check_payment_allowed() {
        
        $state_flag = true;
        
        // Is on a different tax system
        if($this->expense_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The expense cannot receive a payment because it is on a different tax system."));
            $this->app->system->page->forcePage('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id']);
            //$state_flag = false;
            
        }
        
        return $state_flag;
       
    }

}