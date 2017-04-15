<?php

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/payment.php');

// make sure we have an invoice id
if($invoice_id == '' || $invoice_id == '0') {
    force_page('core', 'error&error_msg=No Invoice ID&menu=1');
    exit;
}

// Enter the transaction in to the database - not currently using
if(isset($VAR['submit'])) { 
    
    // Load the method specific code processor
    switch($VAR['type']) {

        case 1:
        //$method = 'Credit Card';
        require(MODULES_DIR.'payment/methods/payment.php');
        break;

        case 2:
        //$method = 'Cheque';        
        require(MODULES_DIR.'payment/methods/payment.php');
        break;

        case 3:
        //$method = 'Cash';
        require(MODULES_DIR.'payment/methods/method_cash.php');
        break;

        case 4:
        //$method = 'Gift Certificate';
        require(MODULES_DIR.'payment/methods/payment.php');
        break;

        case 5:
        //$method = 'PayPal';
        require(MODULES_DIR.'payment/methods/payment.php');
        break;

        case 6:
        //$method = 'Direct Deposit';
        require(MODULES_DIR.'payment/methods/payment.php');
        break;    

    }

}  
        
// Get invoice Details      
$invoice_details = get_invoice_details($db, $invoice_id);

// Fetch page and assign variables
$smarty->assign('invoice_details',  $invoice_details                                                    );
$smarty->assign('customer_details', display_customer_info($db, $invoice_details['1']['CUSTOMER_ID'])    );
$smarty->assign('transactions',     get_invoice_transactions($db, $invoice_id)                          );  
$smarty->assign('payment_options',  get_active_payment_methods($db)                                     );
$smarty->assign('credit_cards',     get_active_credit_cards($db)                                        );
$smarty->assign('invoice_total',    $invoice_details['0']['TOTAL']                                      );
$smarty->assign('IS_PAID_amount',   $invoice_details['0']['PAID_AMOUNT']                                );
$smarty->assign('workorder_id',     $invoice_details['0']['WORKORDER_ID']                               );
$smarty->assign('balance',          $invoice_details['0']['BALANCE']                                    );
$smarty->assign('invoice_date',     $invoice_details['0']['DATE']                                       );
$smarty->assign('invoice_due',      $invoice_details['0']['DUE_DATE']                                   );

$BuildPage .= $smarty->fetch('payment/new.tpl');