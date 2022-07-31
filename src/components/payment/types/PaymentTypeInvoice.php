<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeInvoice extends PaymentType
{    
    private $invoice_details = array();
    
    public function __construct()
    {    
        parent::__construct();       
        
        // Set class variables
        Payment::$payment_details['type'] = 'invoice';        
        $this->invoice_details = $this->app->components->invoice->getRecord($this->VAR['qpayment']['invoice_id']); // only needed for smarty?
        
        // For logging and insertRecord()
        Payment::$payment_details['client_id'] = \CMSApplication::$VAR['qpayment']['client_id'] = $this->invoice_details['client_id'];        
        Payment::$payment_details['invoice_id'] = \CMSApplication::$VAR['qpayment']['invoice_id'] = $this->invoice_details['invoice_id'];
        
        // Set initial record balance
        Payment::$record_balance = (float) $this->invoice_details['balance'];
                       
        // Assign Payment Type specific template variables
        $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('receive', true, array()));
        $this->app->smarty->assign('client_details', $this->app->components->client->getRecord($this->invoice_details['client_id']));        
        $this->app->smarty->assign('invoice_details', $this->invoice_details);
        $this->app->smarty->assign('invoice_statuses', $this->app->components->invoice->getStatuses());        
    }
        
    // Pre-Processing - Prep/validate the data
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

    // Processing - Process the payment   
    public function process()
    {        
        parent::process();
        
        // Recalculate record totals
        $this->app->components->invoice->recalculateTotals($this->VAR['qpayment']['invoice_id']);
        
        // Refresh the record data        
        $this->invoice_details = $this->app->components->invoice->getRecord($this->VAR['qpayment']['invoice_id']);
        Payment::$record_balance = (float) $this->invoice_details['balance'];
        
        $this->app->smarty->assign('invoice_details', $this->invoice_details); 
        
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
    
    // Post-Processing - Now do final things like set messages and redirects   
    public function postProcess()
    {        
        parent::postProcess();
        
        // Refresh the record data        
        $this->invoice_details = $this->app->components->invoice->getRecord($this->VAR['qpayment']['invoice_id']); 
        
        // Different actions depending on success
        if(Payment::$payment_successful)
        {        
            // If the balance has been cleared
            if(Payment::$record_balance == 0)
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("The balance has been cleared."));
                $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->VAR['qpayment']['invoice_id']);
            }
            
            // New
            if(Payment::$action === 'new')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment added successfully and Invoice").' '.$this->VAR['qpayment']['invoice_id'].' '._gettext("has been updated to reflect this change."));
                // No forcepage, this will reload the new payment page
            }
            
            // Edit
            if(Payment::$action === 'edit')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Invoice").' '.$this->VAR['qpayment']['invoice_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('payment', 'details&payment_id='.Payment::$payment_details['payment_id']);
            }
            
            // Cancel
            if(Payment::$action === 'cancel')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Invoice").' '.$this->VAR['qpayment']['invoice_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->VAR['qpayment']['invoice_id']);                
            }
            
            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Invoice").' '.$this->VAR['qpayment']['invoice_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('invoice', 'details&invoice_id='.$this->VAR['qpayment']['invoice_id']);
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
                
                $this->app->system->page->forcePage('invoice', 'status&invoice_id='.$this->VAR['qpayment']['invoice_id']);                
            }
            
            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->page->forcePage('invoice', 'status&invoice_id='.$this->VAR['qpayment']['invoice_id']);
            } 
        }        
        
        return;       
    }
      
    // Build Buttons
    public function buildButtons()
    {        
        // Submit
        if($this->invoice_details['balance'] > 0) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }        
        
        // Cancel
        if(!$this->invoice_details['balance'] == 0)
        {            
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
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new'))
        {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=invoice&page_tpl=details&invoice_id='.$this->VAR['qpayment']['invoice_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }
        
        // Add New Record        
        Payment::$buttons['addNewRecord']['allowed'] = false;
        Payment::$buttons['addNewRecord']['url'] = null; 
        Payment::$buttons['addNewRecord']['title'] = null;        
    }

}