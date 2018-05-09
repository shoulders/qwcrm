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
if($invoice_id == '') {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
    exit;
}

// Enter the transaction in to the database - not currently using
if(isset($VAR['submit'])) { 
    
    // Load the method specific processor
    switch($VAR['method_type']) {

        case 'credit_card':
        require(MODULES_DIR.'payment/methods/method_credit_card.php');
        break;

        case 'cheque':
        require(MODULES_DIR.'payment/methods/method_cheque.php');
        break;

        case 'cash':
        require(MODULES_DIR.'payment/methods/method_cash.php');
        break;

        case 'gift_certificate':
        require(MODULES_DIR.'payment/methods/method_gift_certificate.php');
        break;

        case 'paypal':
        require(MODULES_DIR.'payment/methods/method_paypal.php');
        break;

        case 'direct_deposit':
        require(MODULES_DIR.'payment/methods/method_direct_deposit.php');
        break;    

    }

}

// Build the page
$smarty->assign('customer_details',                 get_customer_details($db, get_invoice_details($db, $invoice_id , 'customer_id'))    );
$smarty->assign('invoice_details',                  get_invoice_details($db, $invoice_id)                                               );
$smarty->assign('invoice_statuses',                 get_invoice_statuses($db)                                                           );
$smarty->assign('transactions',                     get_invoice_transactions($db, $invoice_id)                                          );
$smarty->assign('transaction_statuses',             get_payment_system_methods($db)                                                     );
$smarty->assign('active_payment_system_methods',    get_active_payment_system_methods($db)                                              );
$smarty->assign('active_credit_cards',              get_active_credit_cards($db)                                                        );

$BuildPage .= $smarty->fetch('payment/new.tpl');