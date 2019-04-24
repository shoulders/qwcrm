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
        $this->refund_details = get_refund_details($this->VAR['refund_id']);
        NewPayment::$record_balance = $this->refund_details['balance'];
        
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
        $this->VAR['qpayment']['workorder_id'] = get_invoice_details($this->refund_details['invoice_id'], 'workorder_id');
        
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
        
        // Refresh the record data
        $this->refund_details = get_refund_details($this->VAR['refund_id']);
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
        }        
        
        // Cancel
        if(!$this->refund_details['balance'] == 0) {            
            if(check_page_accessed_via_qwcrm('refund', 'new') || check_page_accessed_via_qwcrm('refund', 'details')) {
                NewPayment::$buttons['cancel']['allowed'] = true;
                NewPayment::$buttons['cancel']['url'] = 'index.php?component=refund&page_tpl=details&refund_id='.$this->VAR['qpayment']['refund_id'];
            }            
        }
        
        // Return To Record
        if(check_page_accessed_via_qwcrm('payment', 'new')) {
            NewPayment::$buttons['returnToRecord']['allowed'] = true;
            NewPayment::$buttons['returnToRecord']['url'] = 'index.php?component=refund&page_tpl=details&refund_id='.$this->VAR['qpayment']['refund_id'];
        }
        
        // Add New Record
        NewPayment::$buttons['addNewRecord']['allowed'] = false;
        NewPayment::$buttons['sddNewRecord']['url'] = null;        
        
    }    

}