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
        
        $this->build_cancel_button(); 
        
        // Assign Type specific template variables  
        $this->smarty->assign('payment_active_methods', get_payment_methods('receive', 'enabled'));
        
    }    
    
    // Pre-Processing
    public function pre_process() {
        
        // Validate_payment_amount
        if(!validate_payment_amount(get_expense_details($this->VAR['qpayment']['expense_id'], 'balance'), $this->VAR['qpayment']['amount'])) {
            
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
        
        // If the invoice has been closed redirect to the invoice details page / redirect after last payment added.
        if(get_expense_details($this->VAR['expense_id'], 'status') == 'paid') {
            force_page('expense', 'details&expense_id='.$this->VAR['expense_id']);
        }
        
        return;
       
    }
    
    // Build Cancel Button
    public function build_cancel_button() {
        
        // Build cancel button
        if(check_page_accessed_via_qwcrm('expense', 'edit')) {
            $this->smarty->assign('cancel_button_url', 'index.php?component=expense&page_tpl=edit&expense_id='.$this->VAR['qpayment']['expense_id']);
        } else {
            $this->smarty->assign('cancel_button_url', 'index.php?component=expense&page_tpl=details&expense_id='.$this->VAR['qpayment']['expense_id']);
        }
        
    }

}