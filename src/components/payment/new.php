<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/giftcert.php');
require(INCLUDES_DIR.'components/invoice.php');
require(INCLUDES_DIR.'components/payment.php');
require(INCLUDES_DIR.'components/workorder.php');

// Check if we have an invoice_id
if($VAR['invoice_id'] == '') {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
    exit;
}

// Enter the transaction in to the database - not currently using
if(isset($VAR['submit'])) { 
    
    // Load the method specific processor
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
if(get_invoice_details($db, $VAR['invoice_id'], 'is_closed')) {
    force_page('invoice', 'details&invoice_id='.$VAR['invoice_id']);
}

// Build the page
$smarty->assign('customer_details',                 get_customer_details($db, get_invoice_details($db, $VAR['invoice_id'] , 'customer_id'))     );
$smarty->assign('invoice_details',                  get_invoice_details($db, $VAR['invoice_id'])                                                );
$smarty->assign('invoice_statuses',                 get_invoice_statuses($db)                                                                   );
$smarty->assign('display_payments',         display_payments($db, 'payment_id', 'DESC', false, null, null, null, null, null, null, null, $VAR['invoice_id'])  );
$smarty->assign('payment_statuses',             get_payment_system_methods($db)                                                             );
$smarty->assign('active_payment_system_methods',    get_active_payment_system_methods($db)                                                      );
$smarty->assign('active_credit_cards',              get_active_credit_cards($db)                                                                );

$BuildPage .= $smarty->fetch('payment/new.tpl');