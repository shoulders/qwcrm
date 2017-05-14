<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/payment.php');
require(INCLUDES_DIR.'modules/workorder.php');

// make sure we have an invoice id
if($invoice_id == '' || $invoice_id == '0') {
    force_page('core', 'error&error_msg=No Invoice ID&menu=1');
    exit;
}

// Enter the transaction in to the database - not currently using
if(isset($VAR['submit'])) { 
    
    // Load the method specific processor
    switch($VAR['type']) {

        case 1:
        $method = 'Credit Card';
        require(MODULES_DIR.'payment/methods/method_credit_card.php');
        break;

        case 2:
        $method = 'Cheque';        
        require(MODULES_DIR.'payment/methods/method_cheque.php');
        break;

        case 3:
        $method = 'Cash';
        require(MODULES_DIR.'payment/methods/method_cash.php');
        break;

        case 4:
        $method = 'Gift Certificate';
        require(MODULES_DIR.'payment/methods/method_gift_certificate.php');
        break;

        case 5:
        $method = 'PayPal';
        require(MODULES_DIR.'payment/methods/method_paypal.php');
        break;

        case 6:
        $method = 'Direct Deposit';
        require(MODULES_DIR.'payment/methods/method_direct_deposit.php');
        break;    

    }

}  

// Fetch page and assign variables
$smarty->assign('customer_details',         get_customer_details($db, get_invoice_details($db, $invoice_id , 'CUSTOMER_ID'))    );
$smarty->assign('transactions',             get_invoice_transactions($db, $invoice_id)                                          );  
$smarty->assign('active_payment_methods',   get_active_payment_methods($db)                                                     );
$smarty->assign('active_credit_cards',      get_active_credit_cards($db)                                                        );

$smarty->assign('invoice_total',            get_invoice_details($db, $invoice_id , 'TOTAL')                                     );
$smarty->assign('IS_PAID_amount',           get_invoice_details($db, $invoice_id , 'PAID_AMOUNT')                               );
$smarty->assign('workorder_id',             get_invoice_details($db, $invoice_id , 'WORKORDER_ID')                              );
$smarty->assign('balance',                  get_invoice_details($db, $invoice_id , 'BALANCE')                                   );
$smarty->assign('invoice_date',             get_invoice_details($db, $invoice_id , 'DATE')                                      );
$smarty->assign('invoice_due',              get_invoice_details($db, $invoice_id , 'DUE_DATE')                                  );

$BuildPage .= $smarty->fetch('payment/new.tpl');