<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeInvoice {
    
    private $app = null;
    private $VAR = null;    
    private $invoice_details = null;
    
    public function __construct() {
        
        // Set class variables
        $this->app = \Factory::getApplication();
        $this->VAR = &\CMSApplication::$VAR;        
        $this->invoice_details = $this->app->components->invoice->getRecord($this->VAR['qpayment']['invoice_id']);
        
        // Set intial record balance
        Payment::$record_balance = $this->invoice_details['balance'];
                       
        // Assign Type specific template variables        
        $this->app->smarty->assign('client_details', $this->app->components->client->getRecord($this->invoice_details['client_id']));
        $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('receive', true, array()));
        $this->app->smarty->assign('invoice_details', $this->invoice_details);
        $this->app->smarty->assign('invoice_statuses', $this->app->components->invoice->getStatuses());        
        
    }
        
    // Pre-Processing
    public function pre_process() {          
                
        // Add required variables
        $this->VAR['qpayment']['client_id'] = $this->invoice_details['client_id'];
        $this->VAR['qpayment']['workorder_id'] = $this->invoice_details['workorder_id'];
        
        // Validate payment_amount (New Payments)
        if(Payment::$action === 'new') {
            //Payment::$record_balance = $this->invoice_details['balance'];  // this is not needed
            if(!$this->app->components->payment->checkAmountValid(Payment::$record_balance, $this->VAR['qpayment']['amount'])) {
                Payment::$payment_valid = false;
            }
        }
        
        // Validate payment_amount (Payment Update)
        if(Payment::$action === 'update') {
            Payment::$record_balance = ($this->invoice_details['balance'] + Payment::$payment_details['amount']);
            if(!$this->app->components->payment->checkAmountValid(Payment::$record_balance, Payment::$payment_details['amount'])) {
                Payment::$payment_valid = false;
            }
        }
        
        return;

    }

    // Processing
    public function process() {  
        
        // Recalculate record totals
        $this->app->components->invoice->recalculateTotals($this->VAR['qpayment']['invoice_id']);
        
        // Refresh the record data        
        $this->invoice_details = $this->app->components->invoice->getRecord($this->VAR['qpayment']['invoice_id']);
        $this->app->smarty->assign('invoice_details', $this->invoice_details);
        Payment::$record_balance = $this->invoice_details['balance'];
        
        return;
       
    }
    
    // Post-Processing 
    public function post_process() {
        
        // If the balance has been cleared, redirect to the record details page
        if($this->invoice_details['balance'] == 0) {
            $this->app->system->variables->systemMessagesWrite('success', _gettext("The balance has been cleared."));
            $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->VAR['invoice_id']);
        }
        
        return;
       
    }
    
    // Build Buttons
    public function build_buttons() {
        
        // Submit
        if($this->invoice_details['balance'] > 0) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }        
        
        // Cancel
        if(!$this->invoice_details['balance'] == 0) {
            
            if($this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'edit')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=invoice&page_tpl=edit&invoice_id='.$this->VAR['qpayment']['invoice_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }
            if($this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'details')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=invoice&page_tpl=details&invoice_id='.$this->VAR['qpayment']['invoice_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }
            
        }
        
        // Return To Record
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=invoice&page_tpl=details&invoice_id='.$this->VAR['qpayment']['invoice_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }
        
        // Add New Record
        Payment::$buttons['addNewRecord']['allowed'] = false;
        Payment::$buttons['addNewRecord']['url'] = null; 
        Payment::$buttons['addNewRecord']['title'] = null;
        
    }    
    
    // Update Payment
    public function update() {
        
        // Update the payment
        $this->app->components->payment->updateRecord($this->VAR['qpayment']);
                
        // Recalculate record totals
        $this->app->components->invoice->recalculateTotals($this->VAR['qpayment']['invoice_id']);
        
        // Refresh the record data        
        //$this->invoice_details = $this->app->components->invoice->get_invoice_details($this->VAR['qpayment']['invoice_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Invoice").' '.$this->VAR['qpayment']['invoice_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->VAR['qpayment']['invoice_id']);
                
        return;        
        
    }
    
    // Cancel Payment
    public function cancel() {
        
        // Cancel the payment
        $this->app->components->payment->cancelRecord($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        $this->app->components->invoice->recalculateTotals($this->VAR['qpayment']['invoice_id']);
        
        // Refresh the record data        
        //$this->invoice_details = $this->app->components->invoice->get_invoice_details($this->VAR['qpayment']['invoice_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Invoice").' '.$this->VAR['qpayment']['invoice_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->VAR['qpayment']['invoice_id']);
                
        return;        
        
    }
    
    // Delete Payment
    public function delete() {
        
        // Delete the payment
        $this->app->components->payment->deleteRecord($this->VAR['qpayment']['payment_id']);
                
        // Recalculate record totals
        $this->app->components->invoice->recalculateTotals($this->VAR['qpayment']['invoice_id']);
        
        // Refresh the record data        
        //$this->invoice_details = $this->app->components->invoice->get_invoice_details($this->VAR['qpayment']['invoice_id']);        
        
        // Load the relevant record details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Invoice").' '.$this->VAR['qpayment']['invoice_id'].' '._gettext("has been updated to reflect this change."));
        $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->VAR['qpayment']['invoice_id']);
                
        return;        
        
    }
    
    // Check Payment is allowed
    public function check_payment_allowed() {
        
        $state_flag = true;
        
        // Is on a different tax system
        if($this->invoice_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The invoice cannot receive a payment because it is on a different tax system."));            
            $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->VAR['qpayment']['invoice_id']);
            //$state_flag = true;
            
        }

        return $state_flag;
       
    }

}