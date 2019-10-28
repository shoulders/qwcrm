<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Make sure a payment type is set
if(!isset(\CMSApplication::$VAR['type']) && (\CMSApplication::$VAR['type'] == 'invoice' || \CMSApplication::$VAR['type'] == 'refund' || \CMSApplication::$VAR['type'] == 'expense' || \CMSApplication::$VAR['type'] == 'otherincome')) {
    force_page('payment', 'search', 'msg_danger='._gettext("No Payment Type supplied."));  
}

// Prevent undefined variable errors (with and without submit)
\CMSApplication::$VAR['qpayment']['type'] = \CMSApplication::$VAR['type'];
\CMSApplication::$VAR['qpayment']['method'] = isset(\CMSApplication::$VAR['qpayment']['method']) ? \CMSApplication::$VAR['qpayment']['method'] : null;
\CMSApplication::$VAR['qpayment']['invoice_id'] = isset(\CMSApplication::$VAR['qpayment']['invoice_id']) ? \CMSApplication::$VAR['qpayment']['invoice_id'] : '';
\CMSApplication::$VAR['qpayment']['invoice_id'] = isset(\CMSApplication::$VAR['invoice_id']) ? \CMSApplication::$VAR['invoice_id'] : \CMSApplication::$VAR['qpayment']['invoice_id'];
\CMSApplication::$VAR['qpayment']['voucher_id'] = isset($qpayment['voucher_id']) ? $qpayment['voucher_id'] : ''; // Do i need this? probably!
\CMSApplication::$VAR['qpayment']['refund_id'] = isset(\CMSApplication::$VAR['qpayment']['refund_id']) ? \CMSApplication::$VAR['qpayment']['refund_id'] : '';
\CMSApplication::$VAR['qpayment']['refund_id'] = isset(\CMSApplication::$VAR['refund_id']) ? \CMSApplication::$VAR['refund_id'] : \CMSApplication::$VAR['qpayment']['refund_id'];
\CMSApplication::$VAR['qpayment']['expense_id'] = isset(\CMSApplication::$VAR['qpayment']['expense_id']) ? \CMSApplication::$VAR['qpayment']['expense_id'] : '';
\CMSApplication::$VAR['qpayment']['expense_id'] = isset(\CMSApplication::$VAR['expense_id']) ? \CMSApplication::$VAR['expense_id'] : \CMSApplication::$VAR['qpayment']['expense_id'];
\CMSApplication::$VAR['qpayment']['otherincome_id'] = isset(\CMSApplication::$VAR['qpayment']['otherincome_id']) ? \CMSApplication::$VAR['qpayment']['otherincome_id'] : '';
\CMSApplication::$VAR['qpayment']['otherincome_id'] = isset(\CMSApplication::$VAR['otherincome_id']) ? \CMSApplication::$VAR['otherincome_id'] : \CMSApplication::$VAR['qpayment']['otherincome_id'];
\CMSApplication::$VAR['qpayment']['name_on_card'] = isset(\CMSApplication::$VAR['qpayment']['name_on_card']) ? \CMSApplication::$VAR['qpayment']['name_on_card'] : null;

// Prevent direct access to this page, and validate requests
if(check_page_accessed_via_qwcrm('invoice', 'edit') || check_page_accessed_via_qwcrm('invoice', 'details')) {  
    
    // Check we have a valid request
    if(\CMSApplication::$VAR['qpayment']['type'] == 'invoice' && (!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id'])) {
        force_page('invoice', 'search', 'msg_danger='._gettext("No Invoice ID supplied."));    
    }    
    
} elseif(check_page_accessed_via_qwcrm('refund', 'new') || check_page_accessed_via_qwcrm('refund', 'details')) {   
    
    // Check we have a valid request
    if(\CMSApplication::$VAR['qpayment']['type'] == 'refund' && (!isset(\CMSApplication::$VAR['refund_id']) || !\CMSApplication::$VAR['refund_id'])) {
        force_page('refund', 'search', 'msg_danger='._gettext("No Refund ID supplied."));    
    }    
    
} elseif(check_page_accessed_via_qwcrm('expense', 'new') || check_page_accessed_via_qwcrm('expense', 'details')) {
    
    // Check we have a valid request
    if(\CMSApplication::$VAR['qpayment']['type'] == 'expense' && (!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id'])) {
        force_page('expense', 'search', 'msg_danger='._gettext("No Expense ID supplied."));    
    }
 
} elseif(check_page_accessed_via_qwcrm('otherincome', 'new') || check_page_accessed_via_qwcrm('otherincome', 'details')) {
    
    // Check we have a valid request
    if(\CMSApplication::$VAR['qpayment']['type'] == 'otherincome' && (!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id'])) {
        force_page('otherincome', 'search', 'msg_danger='._gettext("No Otherincome ID supplied."));    
    }
     
} elseif(!check_page_accessed_via_qwcrm('payment', 'new')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// This is a dirty hack because QWcrm is not fully OOP yet
class NewPayment {
    
    private $VAR = null;
    private $type = null;
    private $method = null;
    public static $buttons = array();
    public static $payment_valid = true;
    public static $payment_processed = false;
    public static $record_balance = null;
    
    function __construct(&$VAR) {
        
        // Set class variables
        $this->VAR = &$VAR;
        
        // Build button array
        self::$buttons = array(
            'submit' => array('allowed' => false, 'url' => null, 'title' => null),
            'cancel' => array('allowed' => false, 'url' => null, 'title' => null),
            'returnToRecord' => array('allowed' => false, 'url' => null, 'title' => null),
            'addNewRecord' => array('allowed' => false, 'url' => null, 'title' => null)
        );
        
        // Set name on card to company name (if appropriate)
        if(!$this->VAR['qpayment']['name_on_card'] && ($this->VAR['qpayment']['type'] == 'refund' || $this->VAR['qpayment']['type'] == 'expense'))
        {
            $this->VAR['qpayment']['name_on_card'] = get_company_details('company_name');
        }
               
        // Set the payment type class
        $this->set_payment_type();
        
        // Is the payment allowed
        $this->type->check_payment_allowed();
        
        // If the form is submitted
        if(isset($this->VAR['submit'])) {  
            
            // Wrap the submitted note
            if($this->VAR['qpayment']['note'] != '') {$this->VAR['qpayment']['note'] = '<p>'.$this->VAR['qpayment']['note'].'</p>';}
            
            // Set payment method class
            $this->set_payment_method();
            
            // Prep/validate the data
            if(self::$payment_valid) {
                $this->type->pre_process();
                $this->method->pre_process();
            }

            // Process the payment
            if(self::$payment_valid) {                 
                $this->method->process();
                $this->type->process();
            }

            // Now do final things like set messages and build buttons        
            $this->method->post_process();
            $this->type->post_process();
            
        }
        
        // Build the buttons
        $this->type->build_buttons();
       
    }
        
    function set_payment_type() {
        
        // Load the routines specific for the specific payment type
        switch($this->VAR['qpayment']['type']) {

            case 'invoice':
            require(COMPONENTS_DIR.'payment/types/invoice.php');
            break;

            case 'refund':
            require(COMPONENTS_DIR.'payment/types/refund.php');
            break;

            case 'expense':
            require(COMPONENTS_DIR.'payment/types/expense.php');
            break;

            case 'otherincome':
            require(COMPONENTS_DIR.'payment/types/otherincome.php');
            break;

            default:
            systemMessagesWrite('danger', _gettext("Invalid Payment Type."));
            force_page('payment', 'search');
            break;

        }
        
        // Load and set the relevant class
        $this->type = new PType($this->VAR);
    
    }
    
    // Load the method specific payment method processor upon form submission
    function set_payment_method() {        

        switch($this->VAR['qpayment']['method']) {

            case 'bank_transfer':
            require(COMPONENTS_DIR.'payment/methods/bank_transfer.php');
            break;

            case 'card':
            require(COMPONENTS_DIR.'payment/methods/card.php');
            break;

            case 'cash':
            require(COMPONENTS_DIR.'payment/methods/cash.php');
            break;

            case 'cheque':
            require(COMPONENTS_DIR.'payment/methods/cheque.php');
            break;

            case 'direct_debit':
            require(COMPONENTS_DIR.'payment/methods/direct_debit.php');
            break;        

            case 'voucher':
            require(COMPONENTS_DIR.'payment/methods/voucher.php');
            break;

            case 'other':
            require(COMPONENTS_DIR.'payment/methods/other.php');
            break;

            case 'paypal':
            require(COMPONENTS_DIR.'payment/methods/paypal.php');
            break;

            default:
            systemMessagesWrite('danger', _gettext("Invalid Payment Method."));
            force_page('payment', 'search');
            break;
        }
        
        // Load and set the relevant class
        $this->method = new PMethod($this->VAR);
    
    }
    
}

// Instanciate New Payment Class
$payment = new NewPayment(\CMSApplication::$VAR);

// Build the page
$smarty->assign('display_payments',                  display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['qpayment']['invoice_id'], \CMSApplication::$VAR['qpayment']['refund_id'], \CMSApplication::$VAR['qpayment']['expense_id'], \CMSApplication::$VAR['qpayment']['otherincome_id'])  );
$smarty->assign('payment_method',                    \CMSApplication::$VAR['qpayment']['method']                                                      );
$smarty->assign('payment_type',                      \CMSApplication::$VAR['qpayment']['type']                                                        );
$smarty->assign('payment_types',                     get_payment_types()                                                             );
$smarty->assign('payment_methods',                   get_payment_methods()                                                           );
$smarty->assign('payment_statuses',                  get_payment_statuses()                                                          );
$smarty->assign('payment_active_card_types',         get_payment_active_card_types()                                                 );
$smarty->assign('name_on_card',                      \CMSApplication::$VAR['qpayment']['name_on_card']                                                );
$smarty->assign('record_balance',                    NewPayment::$record_balance                                                     );
$smarty->assign('buttons',                           NewPayment::$buttons                                                            );
