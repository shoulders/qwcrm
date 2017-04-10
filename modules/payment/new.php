<?php
require_once ('include.php');
require(INCLUDES_DIR.'modules/customer.php');

// make sure we have an invoice id
if($invoice_id == '' || $invoice_id == '0') {
    force_page('core', 'error&error_msg=No Invoice ID&menu=1');
    exit;
}

// Fetch page and assign variables
$smarty->assign('invoice_details',  get_single_invoice_details($db, $invoice_id)    );
$smarty->assign('customer_details', display_customer_info($db, $customer_id)        );
$smarty->assign('payment_options',  get_active_payment_methods($db)                 );
$smarty->assign('credit_cards',     get_active_credit_cards($db)                    );
$smarty->assign('transactions',     get_invoice_transactions($db, $invoice_id)      );  
$BuildPage .= $smarty->fetch('payment/new.tpl');