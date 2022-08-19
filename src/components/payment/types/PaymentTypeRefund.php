<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeRefund extends PaymentType
{  
    private $refund_details = array();
    
    public function __construct()
    {
        parent::__construct();
        
        // Set class variables
        Payment::$payment_details['type'] = 'refund';                      
        $this->refund_details = $this->app->components->refund->getRecord($this->VAR['qpayment']['refund_id']);  //only needed for smarty?
        
        // For logging and insertRecord()
        Payment::$payment_details['client_id'] = \CMSApplication::$VAR['qpayment']['client_id'] = $this->refund_details['client_id'];        
        Payment::$payment_details['invoice_id'] = \CMSApplication::$VAR['qpayment']['invoice_id'] = $this->refund_details['invoice_id'];
        
        // Set intial record balance
        Payment::$record_balance = (float) $this->refund_details['balance'];
        
        // Assign Payment Type specific template variables
        $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('send', true, array()));
        $this->app->smarty->assign('client_details', $this->app->components->client->getRecord($this->refund_details['client_id']));        
        $this->app->smarty->assign('refund_details', $this->refund_details);
        $this->app->smarty->assign('refund_statuses', $this->app->components->refund->getStatuses());
        $this->app->smarty->assign('name_on_card', $this->app->components->company->getRecord('company_name'));        
    }
    
    // Pre-Processing
    public function preProcess()
    {
        parent::preProcess();
        
        // New
        if(Payment::$action === 'new')
        {            
            // Do nothing
        }        
        
        // Edit
        if(Payment::$action === 'edit')
        {            
           // Do nothing                       
        }
        
        // Cancel
        if(Payment::$action === 'cancel')
        {            
            // Do nothing
        }
        
        // Delete
        if(Payment::$action === 'delete')
        {            
            // Do nothing
        }
        
        return;
    }

    // Processing
    public function process()
    {
        parent::process();
        
        // Recalculate record totals
        $this->app->components->refund->recalculateTotals($this->VAR['qpayment']['refund_id']);
        
        // Refresh the record data        
        $this->refund_details = $this->app->components->refund->getRecord($this->VAR['qpayment']['refund_id']);        
        Payment::$record_balance = (float) $this->refund_details['balance'];
        
        $this->app->smarty->assign('refund_details', $this->refund_details);
        
        // New
        if(Payment::$action === 'new')
        {
            // Do nothing
        }
        
        // Edit
        if(Payment::$action === 'edit')
        {
            // Do nothing
        }
        
        // Cancel
        if(Payment::$action === 'cancel')
        {            
            // Do nothing
        }
        
        // Delete
        if(Payment::$action === 'delete')
        {            
            // Do nothing
        }
       
        return;        
    }
    
    // Post-Processing 
    public function postProcess()
    {
        parent::postProcess();
        
        // Different actions depending on success
        if(Payment::$payment_successful)
        {        
            // If the balance has been cleared
            if(Payment::$record_balance == 0)
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("The balance has been cleared."));
                $this->app->system->page->forcePage('refund', 'details&refund_id='.$this->VAR['refund_id']);
            }
            
            // New
            if(Payment::$action === 'new')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment added successfully and Refund").' '.$this->VAR['qpayment']['refund_id'].' '._gettext("has been updated to reflect this change."));
                // No forcepage, this will reload the new payment page
            }
            
            // Edit
            if(Payment::$action === 'edit')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Refund").' '.$this->VAR['qpayment']['refund_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('payment', 'details&payment_id='.Payment::$payment_details['payment_id']);
            }
            
            // Cancel
            if(Payment::$action === 'cancel')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Refund").' '.$this->VAR['qpayment']['refund_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('refund', 'details&refund_id='.$this->VAR['qpayment']['refund_id']);
            }
            
            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Refund").' '.$this->VAR['qpayment']['refund_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('refund', 'details&refund_id='.$this->VAR['qpayment']['refund_id']);
            }  
              
        }
        
        else
        {            
            // The same page will be reloaded unless specified here, error messages is handled by methof
            
            // New
            if(Payment::$action === 'new')
            {
                // Do nothing
            }
            
            // Edit
            if(Payment::$action === 'edit')
            {
                // Do nothing
            }
            
            // Cancel
            if(Payment::$action === 'cancel')            {
                
                $this->app->system->page->forcePage('refund', 'status&refund_id='.$this->VAR['qpayment']['refund_id']);                
            }
            
            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->page->forcePage('refund', 'status&refund_id='.$this->VAR['qpayment']['refund_id']);
            } 
        }        
        
        return;       
    }
    
    // General payment checks
    private function checkPaymentAllowed()
    {        
        $state_flag = parent::checkPaymentAllowed();
                        
        // Is on a different tax system
        if($this->refund_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The refund cannot receive a payment because it is on a different tax system."));            
            $this->app->system->page->forcePage('refund', 'details&refund_id='.$this->VAR['qpayment']['refund_id']);
            $state_flag = false;            
        }

        return $state_flag;      
    }
    
    // Build Buttons
    public function buildButtons()
    {        
        parent::buildButtons();
        
        // Submit
        if($this->refund_details['balance'] > 0) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }        
        
        // Cancel
        if(!$this->refund_details['balance'] == 0) {            
            if($this->app->system->security->checkPageAccessedViaQwcrm('refund', 'new') || $this->app->system->security->checkPageAccessedViaQwcrm('refund', 'details')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=refund&page_tpl=details&refund_id='.$this->VAR['qpayment']['refund_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }            
        }
        
        // Return To Record
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=refund&page_tpl=details&refund_id='.$this->VAR['qpayment']['refund_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }
        
        // Add New Record
        Payment::$buttons['addNewRecord']['allowed'] = false;
        Payment::$buttons['addNewRecord']['url'] = null;        
        Payment::$buttons['addNewRecord']['title'] = null;        
        
    }
}