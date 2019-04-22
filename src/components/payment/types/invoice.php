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
                       
        // Assign Type specific template variables        
        $this->smarty->assign('client_details', get_client_details($this->invoice_details['client_id']));
        $this->smarty->assign('payment_active_methods', get_payment_methods('receive', 'enabled'));
        $this->smarty->assign('invoice_details', $this->invoice_details);
        $this->smarty->assign('invoice_statuses', get_invoice_statuses());
        
        
    }
        
    // Pre-Processing
    public function pre_process() {          
        
        // Add required variables // should these be place holders for consistency thourhgout the types but just = null where not needed
        $this->VAR['qpayment']['client_id'] = $this->invoice_details['client_id'];
        $this->VAR['qpayment']['workorder_id'] = $this->invoice_details['workorder_id'];
        
        // Validate_payment_amount
        if(!validate_payment_amount(get_invoice_details($this->VAR['qpayment']['invoice_id'], 'balance'), $this->VAR['qpayment']['amount'])) {
            
            NewPayment::$payment_validated = false;            

        } else {

            NewPayment::$payment_validated = true;
            
        }
        
        
        
        return;

    }

    // Processing (nothing to do here? Kept for reference!)
    public function process() {  
        
        return;
       
    }
    
    // Post-Processing 
    public function post_process() {
        
        // Build submit/submit and new buttons etc..
        
        // If the invoice has been closed redirect to the invoice details page / redirect after last payment added.
        if($this->invoice_details['is_closed']) {
            force_page('invoice', 'details&invoice_id='.$this->VAR['invoice_id']);
        }
        
        return;
       
    }
    
    // Build Buttons
    public function build_buttons() {
        
        NewPayment::$buttons['cancel']['allowed'] = true;
        
        // Build cancel button
        if(check_page_accessed_via_qwcrm('invoice', 'edit')) {
            NewPayment::$buttons['cancel']['url'] = 'index.php?component=invoice&page_tpl=edit&invoice_id='.$this->VAR['qpayment']['invoice_id'];
            
        } else {
            NewPayment::$buttons['cancel']['url'] = 'index.php?component=invoice&page_tpl=details&invoice_id='.$this->VAR['qpayment']['invoice_id'];
        }
        
    }    

}