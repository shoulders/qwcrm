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
    private $invoice_details = null;
    
    public function __construct(&$VAR) {
        
        $this->VAR = &$VAR;
        $this->smarty = QFactory::getSmarty();
        $this->invoice_details = get_invoice_details($this->VAR['invoice_id']);
        NewPayment::$record_balance = $this->invoice_details['balance'];
                       
        // Assign Type specific template variables        
        $this->smarty->assign('client_details', get_client_details($this->invoice_details['client_id']));
        $this->smarty->assign('payment_active_methods', get_payment_methods('receive', 'enabled'));
        $this->smarty->assign('invoice_details', $this->invoice_details);
        $this->smarty->assign('invoice_statuses', get_invoice_statuses());        
        
    }
        
    // Pre-Processing
    public function pre_process() {          
        
        // Add required variables
        $this->VAR['qpayment']['client_id'] = $this->invoice_details['client_id'];
        $this->VAR['qpayment']['workorder_id'] = $this->invoice_details['workorder_id'];
        
        // Validate_payment_amount
        if(!validate_payment_amount(NewPayment::$record_balance, $this->VAR['qpayment']['amount'])) {
            
            NewPayment::$payment_validated = false;            

        } else {

            NewPayment::$payment_validated = true;
            
        }
        
        
        
        return;

    }

    // Processing
    public function process() {  
        
        return;
       
    }
    
    // Post-Processing 
    public function post_process() {
        
        // If the balance has been cleared, redirect to the record details page
        if($this->invoice_details['balance'] == 0) {
            force_page('invoice', 'details&invoice_id='.$this->VAR['invoice_id'], 'information_msg='._gettext("The balance has been cleared."));
        }
        
        return;
       
    }
    
    // Build Buttons
    public function build_buttons() {
        
        // Submit
        if($this->invoice_details['balance'] > 0) {
            NewPayment::$buttons['submit']['allowed'] = true;
            NewPayment::$buttons['submit']['url'] = null;
        }        
        
        // Cancel
        if(!$this->invoice_details['balance'] == 0) {
            
            if(check_page_accessed_via_qwcrm('invoice', 'edit')) {
                NewPayment::$buttons['cancel']['allowed'] = true;
                NewPayment::$buttons['cancel']['url'] = 'index.php?component=invoice&page_tpl=edit&invoice_id='.$this->VAR['qpayment']['invoice_id'];            
            }
            if(check_page_accessed_via_qwcrm('invoice', 'details')) {
                NewPayment::$buttons['cancel']['allowed'] = true;
                NewPayment::$buttons['cancel']['url'] = 'index.php?component=invoice&page_tpl=details&invoice_id='.$this->VAR['qpayment']['invoice_id'];
            }
            
        }
        
        // Return To Record
        if(check_page_accessed_via_qwcrm('payment', 'new')) {
            NewPayment::$buttons['returnToRecord']['allowed'] = true;
            NewPayment::$buttons['returnToRecord']['url'] = 'index.php?component=invoice&page_tpl=details&invoice_id='.$this->VAR['qpayment']['invoice_id'];
        }
        
        // Add New Record
        NewPayment::$buttons['addNewRecord']['allowed'] = false;
        NewPayment::$buttons['sddNewRecord']['url'] = null;        
        
    }    

}