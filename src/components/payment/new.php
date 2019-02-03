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

######### referrer based ####################

// Prevent direct access to this page - and Determine whether should 'invoice' or 'refund' payment by referer and then set payment type based on this
if(check_page_accessed_via_qwcrm('invoice', 'edit')) {
    
    // Check if we have an invoice_id
    if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
        force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));    
    }
    
    // Set payment type
    $VAR['type'] = 'invoice';
    
} elseif(check_page_accessed_via_qwcrm('refund', 'new')) {
    
    // Check if we have an invoice_id
    if(!isset($VAR['refund_id']) || !$VAR['refund_id']) {
        force_page('refund', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));    
    }
    
    // Set payment type
    $VAR['type'] = 'refund';
    
} elseif(!check_page_accessed_via_qwcrm('payment', 'new')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

/*################ Variable Based ####################keep#########

// Prevent direct access to this page - and Determine whether 'invoice' or 'refund' payment then set payment type
if(!check_page_accessed_via_qwcrm('invoice', 'edit') || !check_page_accessed_via_qwcrm('refund', 'new') || !check_page_accessed_via_qwcrm('payment', 'new')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a payment type
if(!isset($VAR['type']) || !$VAR['type']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Payment Type supplied."));
}

// Check if we have a correct ID
switch($VAR['type']) {

    case 'invoice':
    if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) { force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied.")); }
    break;

    case 'refund':
    if(!isset($VAR['refund_id']) || !$VAR['refund_id']) { force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied.")); }
    break;

    default:
    force_page('payment', 'search', 'warning_msg='._gettext("Invalid Payment Type."));
    break;

}

####################################################################*/

// Prevent undefined variable errors
$payment_type = isset($VAR['type']) ? $VAR['type'] : null;
if(isset($VAR['qpayment']['type'])) { $payment_type = $VAR['qpayment']['type']; }
$payment_method = isset($VAR['qpayment']['method']) ? $VAR['qpayment']['method'] : null;

// Load the method specific payment method processor upon form submission
if(isset($VAR['submit'])) {     
    
    switch($VAR['qpayment']['method']) {

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
        force_page('payment', 'search', 'warning_msg='._gettext("Invalid Payment Type."));
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
$smarty->assign('display_payments',                  display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, $VAR['invoice_id'])  );
$smarty->assign('payment_method',                    $payment_method                                                             );
$smarty->assign('payment_type',                      $payment_type                                                                               );
$smarty->assign('payment_types',                     get_payment_types()                                                             );
$smarty->assign('payment_methods',                   get_payment_methods('receive', 'enabled')                                                             );
$smarty->assign('payment_active_card_types',         get_payment_active_card_types()                                                                );

$BuildPage .= $smarty->fetch('payment/new.tpl');