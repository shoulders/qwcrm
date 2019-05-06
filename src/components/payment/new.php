<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'expense.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'otherincome.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

// Make sure a payment type is set
if(!isset($VAR['type']) && ($VAR['type'] == 'invoice' || $VAR['type'] == 'refund' || $VAR['type'] == 'expense' || $VAR['type'] == 'otherincome')) {
    force_page('payment', 'search', 'warning_msg='._gettext("No Payment Type supplied."));  
}

// Prevent undefined variable errors (with and without submit)
$VAR['qpayment']['type'] = $VAR['type'];
$VAR['qpayment']['method'] = isset($VAR['qpayment']['method']) ? $VAR['qpayment']['method'] : null;
$VAR['qpayment']['invoice_id'] = isset($VAR['qpayment']['invoice_id']) ? $VAR['qpayment']['invoice_id'] : '';
$VAR['qpayment']['invoice_id'] = isset($VAR['invoice_id']) ? $VAR['invoice_id'] : $VAR['qpayment']['invoice_id'];
$VAR['qpayment']['voucher_id'] = isset($qpayment['voucher_id']) ? $qpayment['voucher_id'] : ''; // Do i need this? probably!
$VAR['qpayment']['refund_id'] = isset($VAR['qpayment']['refund_id']) ? $VAR['qpayment']['refund_id'] : '';
$VAR['qpayment']['refund_id'] = isset($VAR['refund_id']) ? $VAR['refund_id'] : $VAR['qpayment']['refund_id'];
$VAR['qpayment']['expense_id'] = isset($VAR['qpayment']['expense_id']) ? $VAR['qpayment']['expense_id'] : '';
$VAR['qpayment']['expense_id'] = isset($VAR['expense_id']) ? $VAR['expense_id'] : $VAR['qpayment']['expense_id'];
$VAR['qpayment']['otherincome_id'] = isset($VAR['qpayment']['otherincome_id']) ? $VAR['qpayment']['otherincome_id'] : '';
$VAR['qpayment']['otherincome_id'] = isset($VAR['otherincome_id']) ? $VAR['otherincome_id'] : $VAR['qpayment']['otherincome_id'];

// Prevent direct access to this page, and validate requests
if(check_page_accessed_via_qwcrm('invoice', 'edit') || check_page_accessed_via_qwcrm('invoice', 'details')) {  
    
    // Check we have a valid request
    if($VAR['qpayment']['type'] == 'invoice' && (!isset($VAR['invoice_id']) || !$VAR['invoice_id'])) {
        force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));    
    }    
    
} elseif(check_page_accessed_via_qwcrm('refund', 'new') || check_page_accessed_via_qwcrm('refund', 'details')) {   
    
    // Check we have a valid request
    if($VAR['qpayment']['type'] == 'refund' && (!isset($VAR['refund_id']) || !$VAR['refund_id'])) {
        force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied."));    
    }    
    
} elseif(check_page_accessed_via_qwcrm('expense', 'new') || check_page_accessed_via_qwcrm('expense', 'details')) {
    
    // Check we have a valid request
    if($VAR['qpayment']['type'] == 'expense' && (!isset($VAR['expense_id']) || !$VAR['expense_id'])) {
        force_page('expense', 'search', 'warning_msg='._gettext("No Expense ID supplied."));    
    }
 
} elseif(check_page_accessed_via_qwcrm('otherincome', 'new') || check_page_accessed_via_qwcrm('otherincome', 'details')) {
    
    // Check we have a valid request
    if($VAR['qpayment']['type'] == 'otherincome' && (!isset($VAR['otherincome_id']) || !$VAR['otherincome_id'])) {
        force_page('otherincome', 'search', 'warning_msg='._gettext("No Otherincome ID supplied."));    
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
    public static $payment_validated = false;
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
            $this->type->pre_process();
            $this->method->pre_process();

            // Process the payment
            if(self::$payment_validated) {                 
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
            force_page('payment', 'search', 'warning_msg='._gettext("Invalid Payment Type."));
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
            force_page('payment', 'search', 'warning_msg='._gettext("Invalid Payment Method."));
            break;
        }
        
        // Load and set the relevant class
        $this->method = new PMethod($this->VAR);
    
    }
    
}

// Instanciate New Payment Class
$payment = new NewPayment($VAR);

// Build the page
$smarty->assign('display_payments',                  display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, $VAR['qpayment']['invoice_id'], $VAR['qpayment']['refund_id'], $VAR['qpayment']['expense_id'], $VAR['qpayment']['otherincome_id'])  );
$smarty->assign('payment_method',                    $VAR['qpayment']['method']                                                             );
$smarty->assign('payment_type',                      $VAR['qpayment']['type']                                                                               );
$smarty->assign('payment_types',                     get_payment_types()                                                             );
$smarty->assign('payment_methods',                   get_payment_methods()                                                             );
$smarty->assign('payment_statuses',                  get_payment_statuses()                                                                          );
$smarty->assign('payment_active_card_types',         get_payment_active_card_types()                                                                );
$smarty->assign('record_balance',                    NewPayment::$record_balance                                                               );
$smarty->assign('buttons',                           NewPayment::$buttons                                                                );

$BuildPage .= $smarty->fetch('payment/new.tpl');
