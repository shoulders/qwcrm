<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeOtherincome {
    
    private $app = null;
    private $VAR = null;    
    private $otherincome_details = null;
    
    public function __construct() {
        
        // Set class variables
        $this->app = \Factory::getApplication();
        $this->VAR = &\CMSApplication::$VAR;        
        $this->otherincome_details = $this->app->components->otherincome->get_otherincome_details($this->VAR['qpayment']['otherincome_id']);
        
        // Set intial record balance
        Payment::$record_balance = $this->otherincome_details['balance'];
        
        // Assign Type specific template variables  
        $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->get_payment_methods('receive', 'enabled'));
        $this->app->smarty->assign('otherincome_details', $this->otherincome_details);
        $this->app->smarty->assign('otherincome_statuses', $this->app->components->otherincome->get_otherincome_statuses());
        
    }
    
    // Pre-Processing
    public function pre_process() {
        
        // Add required variables
        $this->VAR['qpayment']['client_id'] = '';
        $this->VAR['qpayment']['workorder_id'] = '';
        
        // Validate payment_amount (New Payments)
        if(Payment::$action === 'new') {
            Payment::$record_balance = $this->otherincome_details['balance'];
            if(!$this->app->components->payment->validate_payment_amount(Payment::$record_balance, $this->VAR['qpayment']['amount'])) {
                Payment::$payment_valid = false;
            }
        }
        
        // Validate payment_amount (Payment Update)
        if(Payment::$action === 'update') {
            Payment::$record_balance = ($this->otherincome_details['balance'] + Payment::$payment_details['amount']);
            if(!$this->app->components->payment->validate_payment_amount(Payment::$record_balance, Payment::$payment_details['amount'])) {
                Payment::$payment_valid = false;
            }
        }
        
        return;

    }

    // Processing
    public function process() {
        
        // Recalculate record totals
        $this->app->components->otherincome->recalculate_otherincome_totals($this->VAR['qpayment']['otherincome_id']);
        
        // Refresh the record data        
        $this->otherincome_details = $this->app->components->otherincome->get_otherincome_details($this->VAR['qpayment']['otherincome_id']);
        $this->app->smarty->assign('otherincome_details', $this->otherincome_details);
        Payment::$record_balance = $this->otherincome_details['balance'];
        
        return;
        
    }
    
    // Post-Processing 
    public function post_process() {   
        
        // If the balance has been cleared, redirect to the record details page
        if($this->otherincome_details['balance'] == 0) {
            $this->app->system->variables->systemMessagesWrite('success', _gettext("The balance has been cleared."));
            $this->app->system->page->force_page('otherincome', 'details&otherincome_id='.$this->VAR['otherincome_id']);
        }
        
        return;
       
    }
    
    // Build Buttons
    public function build_buttons() {
        
        // Submit
        if($this->otherincome_details['balance'] > 0) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }        
        
        // Cancel
        if(!$this->otherincome_details['balance'] == 0) {
            if($this->app->system->security->check_page_accessed_via_qwcrm('otherincome', 'new') || $this->app->system->security->check_page_accessed_via_qwcrm('otherincome', 'details')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=otherincome&page_tpl=details&otherincome_id='.$this->VAR['qpayment']['otherincome_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }            
        }
        
        // Return To Record
        if($this->app->system->security->check_page_accessed_via_qwcrm('payment', 'new')) {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=otherincome&page_tpl=details&otherincome_id='.$this->VAR['qpayment']['otherincome_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }
        
        // Add New Record
        Payment::$buttons['addNewRecord']['allowed'] = true;
        Payment::$buttons['addNewRecord']['url'] = 'index.php?component=otherincome&page_tpl=new'; 
        Payment::$buttons['addNewRecord']['title'] = _gettext("Add New Other Income Record");
        
    }  
    
    // Update Payment
    public function update() {
        
        // Update the payment
        $this->app->components->payment->update_payment($this->VAR['qpayment']);
                
        // Recalculate record totals
        $this->app->components->otherincome->recalculate_otherincome_totals($this->VAR['qpayment']['otherincome_id']);
        
        // Refresh the record data        
        //$this->otherincome_details = $this->app->components->otherincome->get_otherincome_details($this->VAR['qpayment']['otherincome_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Otherincome").' '.$this->VAR['qpayment']['otherincome_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->force_page('otherincome', 'details&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
                
        return;        
        
    }

    // Cancel Payment
    public function cancel() {
        
        // Cancel the payment
        $this->app->components->payment->cancel_payment($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        $this->app->components->otherincome->recalculate_otherincome_totals($this->VAR['qpayment']['otherincome_id']);
        
        // Refresh the record data        
        //$this->otherincome_details = $this->app->components->otherincome->get_otherincome_details($this->VAR['qpayment']['otherincome_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Otherincome").' '.$this->VAR['qpayment']['otherincome_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->force_page('otherincome', 'details&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
                
        return;        
        
    }
    
    // Delete Payment
    public function delete() {
        
        // Delete the payment
        $this->app->components->payment->delete_payment($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        $this->app->components->otherincome->recalculate_otherincome_totals($this->VAR['qpayment']['otherincome_id']);
        
        // Refresh the record data        
        //$this->otherincome_details = $this->app->components->otherincome->get_otherincome_details($this->VAR['qpayment']['otherincome_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Otherincome").' '.$this->VAR['qpayment']['otherincome_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->force_page('otherincome', 'details&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
                
        return;        
        
    }
    
    // Check Payment is allowed
    public function check_payment_allowed() {
        
        $state_flag = true;
        
        // Is on a different tax system
        if($this->otherincome_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The other income cannot receive a payment because it is on a different tax system."));
            $this->app->system->page->force_page('otherincome', 'details&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
            //$state_flag = false;
        }

        return $state_flag;
       
    }
    
}