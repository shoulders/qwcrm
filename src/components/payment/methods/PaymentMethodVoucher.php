<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentMethodVoucher extends PaymentMethod
{
    
    private $voucher_details = array();
    private $currency_symbol = '';
    
    public function __construct()
    {        
        parent::__construct();
        
        // Set class variables
        Payment::$payment_details['method'] = 'voucher';
        
        // Set currency symbol
        $this->currency_symbol = $this->app->components->company->getRecord('currency_symbol');
    }
    
    // Pre-Processing
    public function preProcess()
    {        
        parent::preProcess();
        
        // Get voucher details - Compensates for using voucher_code
        if(!isset($this->VAR['qpayment']['voucher_id']) && !$this->VAR['qpayment']['voucher_id'] = $this->app->components->voucher->getIdByVoucherCode($this->VAR['qpayment']['voucher_code']))
        {
            // If there is no voucher_id, we cannot proceed
            Payment::$payment_valid = false;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There is no Voucher with that code."));
            return; 
        }
        else
        {                
            $this->voucher_details = $this->app->components->voucher->getRecord($this->VAR['qpayment']['voucher_id']);
        }
        
        // New
        if(Payment::$action === 'new')
        {   
            // Is the voucher allowed to be redeemed
            if(!$this->app->components->voucher->checkRecordAllowsRedeem($this->VAR['qpayment']['voucher_id'], $this->VAR['qpayment']['invoice_id']))
            {
                Payment::$payment_valid = false;
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This Voucher is not valid or cannot be redeemed."));              
            }
            
            // Does the voucher have enough balance to cover the payment amount submitted
            if($this->VAR['qpayment']['amount'] > $this->voucher_details['balance'])
            {
                Payment::$payment_valid = false;                
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This Voucher does not have a sufficient balance to cover the submitted payment amount."));                
            }            
        }
        
        // Edit
        if(Payment::$action === 'edit')
        {            
            // Does the voucher have enough balance to cover the payment amount submitted (after removing this payments intial amount)
            if($this->VAR['qpayment']['amount'] > ($this->voucher_details['balance'] + Payment::$payment_details['amount']))
            {
                Payment::$payment_valid = false;                
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This Voucher does not have a sufficient balance to cover the submitted payment amount."));                
            }
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
        
        if(Payment::$action === 'new')
        {            
            // Build additional_info column
            $this->VAR['qpayment']['additional_info'] = $this->app->components->payment->buildAdditionalInfoJson();    

            // Insert the payment with the calculated information            
            if(Payment::$payment_details['payment_id'] = $this->app->components->payment->insertRecord($this->VAR['qpayment']))
            {
                // Update the balance, and status if required
                $this->app->components->voucher->recalculateTotals($this->VAR['qpayment']['voucher_id'], $this->VAR['qpayment']['amount'], Payment::$action);
                        
                Payment::$payment_successful = true;                 
            }
        }
        
        if(Payment::$action === 'edit')
        {            
            // Update the balance, and status if required
            $this->app->components->voucher->recalculateTotals($this->VAR['qpayment']['voucher_id'], $this->VAR['qpayment']['amount'], Payment::$action, Payment::$payment_details['amount']);
            
            Payment::$payment_successful = true;
        }
        
        if(Payment::$action === 'cancel')
        {            
            // Update the balance, and status if required
            $this->app->components->voucher->recalculateTotals($this->VAR['qpayment']['voucher_id'], Payment::$payment_details['amount'], Payment::$action);
            
            Payment::$payment_successful = true;
        }
        
        if(Payment::$action === 'delete')
        {            
            // Update the balance, and status if required
            $this->app->components->voucher->recalculateTotals($this->VAR['qpayment']['voucher_id'], Payment::$payment_details['amount'], Payment::$action);
        }
          
        return;        
    }
    
    // Post-Processing 
    public function postProcess()
    {       
        parent::postProcess();
        
        // Set success/failure message
        if(Payment::$payment_successful)
        {        
           $this->app->system->variables->systemMessagesWrite('success', _gettext("Voucher applied successfully."));       
        }
        else
        {            
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Voucher was not applied successfully."));  
            //$record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was redeemed by").' '.$this->app->components->client->getRecord($invoice_details['client_id'], 'display_name').'.';
        }
        
        // Refresh the voucher details
        if(isset($this->VAR['qpayment']['voucher_id']))
        {
            $this->voucher_details = $this->app->components->voucher->getRecord($this->VAR['qpayment']['voucher_id']);
            
            // Balance remaining
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("The balance left on this voucher is").': '.$this->currency_symbol.$this->voucher_details['balance']); 
        
        }        
        
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
}