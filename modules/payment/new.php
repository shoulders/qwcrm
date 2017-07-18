<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/payment.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have an invoice_id
if($invoice_id == '') {
    force_page('invoice', 'search', 'warning_msg='.gettext("No Invoice ID supplied."));
    exit;
}

// Enter the transaction in to the database - not currently using
if(isset($VAR['submit'])) { 
    
    // Load the method specific processor
    switch($VAR['type']) {

        case 1:
        $method_name = gettext("Credit Card");
        require(MODULES_DIR.'payment/methods/method_credit_card.php');
        break;

        case 2:
        $method_name = gettext("Cheque");        
        require(MODULES_DIR.'payment/methods/method_cheque.php');
        break;

        case 3:
        $method_name = gettext("Cash");
        require(MODULES_DIR.'payment/methods/method_cash.php');
        break;

        case 4:
        $method_name = gettext("Gift Certificate");
        require(MODULES_DIR.'payment/methods/method_gift_certificate.php');
        break;

        case 5:
        $method_name = gettext("PayPal");
        require(MODULES_DIR.'payment/methods/method_paypal.php');
        break;

        case 6:
        $method_name = gettext("Direct Deposit");
        require(MODULES_DIR.'payment/methods/method_direct_deposit.php');
        break;    

    }

}

// Build page
$smarty->assign('customer_details',         get_customer_details($db, get_invoice_details($db, $invoice_id , 'CUSTOMER_ID'))    );
$smarty->assign('invoice_details',          get_invoice_details($db, $invoice_id)                                               );
$smarty->assign('transactions',             get_invoice_transactions($db, $invoice_id)                                          );  
$smarty->assign('active_payment_methods',   get_active_payment_methods($db)                                                     );
$smarty->assign('active_credit_cards',      get_active_credit_cards($db)                                                        );

$BuildPage .= $smarty->fetch('payment/new.tpl');