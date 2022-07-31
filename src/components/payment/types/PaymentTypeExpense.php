<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeExpense extends PaymentType
{     
    private $expense_details = array();
    
    public function __construct()
    {        
        parent::__construct();
        
        // Set class variables
        Payment::$payment_details['type'] = 'expense';            
        $this->expense_details = $this->app->components->expense->getRecord($this->VAR['qpayment']['expense_id']); // only needed for smarty?
        
        // For logging and insertRecord()
        Payment::$payment_details['client_id'] = \CMSApplication::$VAR['qpayment']['client_id'] = null;
        Payment::$payment_details['workorder_id'] = \CMSApplication::$VAR['qpayment']['workorder_id'] = null;
        Payment::$payment_details['invoice_id'] = \CMSApplication::$VAR['qpayment']['invoice_id'] = null;
        
        // Set intial record balance
        Payment::$record_balance = (float) $this->expense_details['balance'];
        
        // Assign Payment Type specific template variables
        $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('send', true, array()));
        $this->app->smarty->assign('expense_details', $this->expense_details);
        $this->app->smarty->assign('expense_statuses', $this->app->components->expense->getStatuses());      
    }    
    
    // Pre-Processing
    public function preProcess()
    {
        parent::preProcess();        
        
        if(Payment::$action === 'new')
        {            
            // Do nothing
        }        
        
        if(Payment::$action === 'edit')
        {            
           // Do nothing                       
        }
        
        if(Payment::$action === 'cancel')
        {            
            // Do nothing
        }
        
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
        $this->app->components->expense->recalculateTotals($this->VAR['qpayment']['expense_id']);
        
        // Refresh the record data        
        $this->expense_details = $this->app->components->expense->getRecord($this->VAR['qpayment']['expense_id']);        
        Payment::$record_balance = (float) $this->expense_details['balance'];
        
        $this->app->smarty->assign('expense_details', $this->expense_details);  ////////////////////
        
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
                $this->app->system->page->forcePage('expense', 'details&expense_id='.$this->VAR['expense_id']);
            }
            
            // New
            if(Payment::$action === 'new')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment added successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
                // No forcepage, this will reload the new payment page
            }
            
            // Edit
            if(Payment::$action === 'edit')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('payment', 'details&payment_id='.Payment::$payment_details['payment_id']);
            }
            
            // Cancel
            if(Payment::$action === 'cancel')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id']);
            }
            
            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Expense").' '.$this->VAR['qpayment']['expense_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('expense', 'details&expense_id='.$this->VAR['qpayment']['expense_id']);
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
                
                $this->app->system->page->forcePage('expense', 'status&expense_id='.$this->VAR['qpayment']['expense_id']);                
            }
            
            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->page->forcePage('expense', 'status&expense_id='.$this->VAR['qpayment']['expense_id']);
            } 
        }
        
        return;       
    }
    
    // Build Buttons
    public function buildButtons() {
        
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
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
            Payment::$buttons['addNewRecord']['allowed'] = true;
            Payment::$buttons['addNewRecord']['url'] = 'index.php?component=expense&page_tpl=new';
            Payment::$buttons['addNewRecord']['title'] = _gettext("Add New Expense Record");
        }
        
    }
}