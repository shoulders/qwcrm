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
    
    public function __construct(&$VAR) {
        
        $this->VAR = &$VAR;
        $this->smarty = QFactory::getSmarty();        
        
        $this->build_buttons();        
        
    }
    
    // Build Buttons (I should add the whole button HTML here maybe for consitency ? template/controller location for this?)
    public function build_buttons() {
        
        // Build cancel button
        if(check_page_accessed_via_qwcrm('invoice', 'edit')) {
            $this->smarty->assign('cancel_button_url', 'index.php?component=invoice&page_tpl=edit&invoice_id='.$this->VAR['qpayment']['invoice_id']);
        } else {
            $this->smarty->assign('cancel_button_url', 'index.php?component=invoice&page_tpl=details&invoice_id='.$this->VAR['qpayment']['invoice_id']);
        }
    }
    
    // Pre-Processing
    public function pre_process() {        
        
        // Validate_payment_amount
        if(!validate_payment_amount(get_invoice_details($this->VAR['qpayment']['invoice_id'], 'balance'), $this->VAR['qpayment']['amount'])) {
            
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
        
        // Build submit/submit and new buttons etc..
        
        // If the invoice has been closed redirect to the invoice details page / redirect after last payment added.
        if(get_invoice_details($this->VAR['invoice_id'], 'is_closed')) {
            force_page('invoice', 'details&invoice_id='.$this->VAR['invoice_id']);
        }
        
        return;
       
    }

}