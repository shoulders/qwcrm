<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'customer.php');
require(INCLUDES_DIR.'giftcert.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

// Load the method specific processor upon form submission
if(isset($VAR['submit'])) {     
    
    switch($VAR['method_type']) {

        case 'credit_card':
        require(COMPONENTS_DIR.'payment/methods/method_credit_card.php');
        break;

        case 'cheque':
        require(COMPONENTS_DIR.'payment/methods/method_cheque.php');
        break;

        case 'cash':
        require(COMPONENTS_DIR.'payment/methods/method_cash.php');
        break;

        case 'gift_certificate':
        require(COMPONENTS_DIR.'payment/methods/method_gift_certificate.php');
        break;

        case 'paypal':
        require(COMPONENTS_DIR.'payment/methods/method_paypal.php');
        break;

        case 'direct_deposit':
        require(COMPONENTS_DIR.'payment/methods/method_direct_deposit.php');
        break;    

    }

}

// If the invoice has been closed redirect to the invoice details page / redirect after last payment added.
if(get_invoice_details($VAR['invoice_id'], 'is_closed')) {
    force_page('invoice', 'details&invoice_id='.$VAR['invoice_id']);
}

// Build the page
$smarty->assign('customer_details',                  get_customer_details(get_invoice_details($VAR['invoice_id'] , 'customer_id'))     );
$smarty->assign('invoice_details',                   get_invoice_details($VAR['invoice_id'])                                                );
$smarty->assign('invoice_statuses',                  get_invoice_statuses()                                                                   );
$smarty->assign('display_payments',                  display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, $VAR['invoice_id'])  );
$smarty->assign('payment_methods',                   get_payment_accepted_methods()                                                             );
$smarty->assign('payment_accepted_methods_statuses', get_payment_accepted_methods_statuses()                                                      );
$smarty->assign('active_credit_cards',               get_active_credit_cards()                                                                );

$BuildPage .= $smarty->fetch('payment/new.tpl');