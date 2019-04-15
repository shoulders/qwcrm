<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent undefined variable errors
$VAR['qpayment']['invoice_id'] = isset($VAR['qpayment']['invoice_id']) ? $VAR['qpayment']['invoice_id'] : null;
//$VAR['qpayment']['voucher_id'] = isset($VAR['qpayment']['voucher_id']) ? $VAR['qpayment']['voucher_id'] : null;
$VAR['qpayment']['refund_id'] = isset($VAR['qpayment']['refund_id']) ? $VAR['qpayment']['refund_id'] : null;
$VAR['qpayment']['expense_id'] = isset($VAR['qpayment']['expense_id']) ? $VAR['qpayment']['expense_id'] : null;
$VAR['qpayment']['otherincome_id'] = isset($VAR['qpayment']['otherincome_id']) ? $VAR['qpayment']['otherincome_id'] : null;
$payment_type = isset($VAR['payment_type']) ? $VAR['payment_type'] : null;                  // This should always be set and this statement might not be needed
if(isset($VAR['qpayment']['type'])) { $payment_type = $VAR['qpayment']['type']; }           // This is after the page has been resubmitted
$payment_method = isset($VAR['qpayment']['method']) ? $VAR['qpayment']['method'] : null;    // On first page load the payment_method variable is not set
$payment_validated = null;

// Prevent direct access to this page - and Determine whether should 'invoice' or 'refund' payment by referer and then set payment type based on this
if(check_page_accessed_via_qwcrm('invoice', 'edit')) {  
    
    // Check we have a valid request
    if($VAR['payment_type'] == 'invoice' && (!isset($VAR['invoice_id']) || !$VAR['invoice_id'])) {
        force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));    
    }    
    
} elseif(check_page_accessed_via_qwcrm('refund', 'new')) {   
    
    // Check we have a valid request
    if($VAR['payment_type'] == 'refund' && (!isset($VAR['refund_id']) || !$VAR['refund_id'])) {
        force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied."));    
    }    
    
} elseif(check_page_accessed_via_qwcrm('expense', 'new')) {
    
    // Check we have a valid request
    if($VAR['payment_type'] = 'expense' && (!isset($VAR['expense_id']) || !$VAR['expense_id'])) {
        force_page('expense', 'search', 'warning_msg='._gettext("No Expense ID supplied."));    
    }
 
} elseif(check_page_accessed_via_qwcrm('otherincome', 'new')) {
    
    // Check we have a valid request
    if($VAR['payment_type'] = 'otherincome' && (!isset($VAR['otherincome_id']) || !$VAR['otherincome_id'])) {
        force_page('otherincome', 'search', 'warning_msg='._gettext("No Otherincome ID supplied."));    
    }
     
} elseif(!check_page_accessed_via_qwcrm('payment', 'new')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

/* Global Changes */

// Wrap the submitted note
$VAR['qpayment']['note'] = '<p>'.$VAR['qpayment']['note'].'</p>';

// If the form is submitted
if(isset($VAR['submit'])) {     
    
    // Load the routines specific for the specific payment type
    switch($payment_type) {

        case 'invoice':
        require(COMPONENTS_DIR.'payment/type/invoice.php');
        break;
    
        case 'refund':
        require(COMPONENTS_DIR.'payment/type/refund.php');
        break;
    
        case 'expense':
        require(COMPONENTS_DIR.'payment/type/expense.php');
        break;
    
        case 'otherincome':
        require(COMPONENTS_DIR.'payment/type/otherincome.php');
        break;
    
        default:
        force_page('payment', 'search', 'warning_msg='._gettext("Invalid Payment Type."));
        break;

    }
}

// If the form is submitted
if($payment_validated) {   
    
    // Load the method specific payment method processor upon form submission
    switch($payment_method) {

        case 'bank_transfer':
        require(COMPONENTS_DIR.'payment/methods/method_bank_transfer.php');
        break;
    
        case 'card':
        require(COMPONENTS_DIR.'payment/methods/method_card.php');
        break;
    
        case 'cash':
        require(COMPONENTS_DIR.'payment/methods/method_cash.php');
        break;
    
        case 'cheque':
        require(COMPONENTS_DIR.'payment/methods/method_cheque.php');
        break;
    
        case 'direct_debit':
        require(COMPONENTS_DIR.'payment/methods/method_direct_debit.php');
        break;        

        case 'voucher':
        require(COMPONENTS_DIR.'payment/methods/method_voucher.php');
        break;
    
        case 'other':
        require(COMPONENTS_DIR.'payment/methods/method_other.php');
        break;
    
        case 'paypal':
        require(COMPONENTS_DIR.'payment/methods/method_paypal.php');
        break;
    
        default:
        force_page('payment', 'search', 'warning_msg='._gettext("Invalid Payment Method."));
        break;
    }

}

// If the invoice has been closed redirect to the invoice details page / redirect after last payment added.
if(get_invoice_details($VAR['invoice_id'], 'is_closed')) {
    force_page('invoice', 'details&invoice_id='.$VAR['invoice_id']);
}

// Build the page
$smarty->assign('client_details',                    get_client_details(get_invoice_details($VAR['invoice_id'] , 'client_id'))     );
$smarty->assign('invoice_details',                   get_invoice_details($VAR['invoice_id'])                                                );
$smarty->assign('invoice_statuses',                  get_invoice_statuses()                                                                   );
$smarty->assign('display_payments',                  display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, $VAR['qpayment']['invoice_id'], $VAR['qpayment']['refund_id'], $VAR['qpayment']['expense_id'], $VAR['qpayment']['otherincome_id'])  );
$smarty->assign('payment_method',                    $payment_method                                                             );
$smarty->assign('payment_type',                      $payment_type                                                                               );
$smarty->assign('payment_types',                     get_payment_types()                                                             );
$smarty->assign('payment_methods',                   get_payment_methods('receive', 'enabled')                                                             );
$smarty->assign('payment_active_card_types',         get_payment_active_card_types()                                                                );

// Build action button urls here - the type code above should of calculated them (maybe build an array containing html)

$BuildPage .= $smarty->fetch('payment/new.tpl');