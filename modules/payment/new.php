<?php
require_once ('include.php');
require(INCLUDES_DIR.'modules/customer.php');

// make sure we have an invoice id
if($invoice_id == '' || $invoice_id == '0') {
    force_page('core', 'error&error_msg=No Invoice ID&menu=1');
    exit;
}

$invoice_details = get_single_invoice_details($db, $invoice_id);

// Fetch page and assign variables
$smarty->assign('invoice_details',  $invoice_details                                                    );
$smarty->assign('customer_details', display_customer_info($db, $invoice_details['1']['CUSTOMER_ID'])    );
$smarty->assign('transactions',     get_invoice_transactions($db, $invoice_id)                          );  
$smarty->assign('payment_options',  get_active_payment_methods($db)                                     );
$smarty->assign('credit_cards',     get_active_credit_cards($db)                                        );

// assigned in template
$smarty->assign('invoice_amount',       $invoice_details['1']['INVOICE_AMOUNT']                         );
$smarty->assign('invoice_paid_amount',  $invoice_details['1']['PAID_AMOUNT']                            );
$smarty->assign('invoice_id',           $invoice_id                                                     );
$smarty->assign('workorder_id',         $invoice_details['1']['WORKORDER_ID']                           );
$smarty->assign('balance',              $invoice_details['1']['BALANCE']                                );

// others from loop i removed
$smarty->assign('invoice_date',              $invoice_details['1']['INVOICE_DATE']                      );
$smarty->assign('invoice_due',              $invoice_details['1']['INVOICE_DUE']                        );

$BuildPage .= $smarty->fetch('payment/new.tpl');